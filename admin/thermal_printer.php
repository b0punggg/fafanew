<?php
/**
 * Helper cetak thermal - mendukung php_printer extension ATAU Windows copy /B (RAW).
 * Tidak wajib php_printer.dll jika shell_exec tersedia di Windows.
 */

if (!function_exists('thermal_is_windows')) {
    function thermal_is_windows() {
        return strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';
    }
}

if (!function_exists('thermal_shell_available')) {
    function thermal_shell_available() {
        if (!thermal_is_windows()) {
            return false;
        }
        $disabled = ini_get('disable_functions');
        if ($disabled === false || $disabled === '') {
            return function_exists('shell_exec');
        }
        $disabledList = array_map('trim', explode(',', strtolower($disabled)));
        return function_exists('shell_exec') && !in_array('shell_exec', $disabledList, true);
    }
}

if (!function_exists('thermal_print_available')) {
    function thermal_print_available() {
        return function_exists('printer_open') || thermal_shell_available();
    }
}

if (!function_exists('thermal_default_printers')) {
    function thermal_default_printers() {
        return array(
            'BP-LITE 80D+80X Printer',
            'BP-LITE80D',
            'BP-LITE 80D+80X',
            'GP-80250N Series',
            'POS-80C',
            'GP-80220(Cut) Series',
            'ZJ-80',
        );
    }
}

if (!function_exists('thermal_copy_success')) {
    function thermal_copy_success($output) {
        if ($output === null || $output === '') {
            return false;
        }
        $o = strtolower($output);
        if (strpos($o, '0 file(s) copied') !== false || strpos($o, '0 file copied') !== false) {
            return false;
        }
        return (strpos($o, '1 file(s) copied') !== false)
            || (strpos($o, '1 file copied') !== false)
            || (strpos($o, '1 berkas') !== false);
    }
}

if (!function_exists('thermal_print_winspool')) {
    function thermal_print_winspool($printerName, $text) {
        if (!thermal_shell_available()) {
            return false;
        }
        $ps1 = dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'print-bridge' . DIRECTORY_SEPARATOR . 'raw-print.ps1';
        if (!file_exists($ps1)) {
            return false;
        }
        $tmpFile = tempnam(sys_get_temp_dir(), 'thprint_');
        if ($tmpFile === false) {
            return false;
        }
        file_put_contents($tmpFile, $text);
        $cmd = 'powershell -NoProfile -ExecutionPolicy Bypass -File "' . $ps1 . '" -PrinterName "' . str_replace('"', '""', $printerName) . '" -FilePath "' . str_replace('"', '""', $tmpFile) . '"';
        $output = shell_exec($cmd);
        @unlink($tmpFile);
        return $output !== null && stripos($output, 'OK') !== false;
    }
}

if (!function_exists('thermal_list_printers')) {
    function thermal_list_printers() {
        $result = array();

        if (function_exists('printer_list')) {
            if (!defined('PRINTER_ENUM_LOCAL')) {
                define('PRINTER_ENUM_LOCAL', 2);
            }
            $list = @printer_list(PRINTER_ENUM_LOCAL);
            if (is_array($list)) {
                foreach ($list as $item) {
                    if (!empty($item['NAME'])) {
                        $result[] = array(
                            'NAME' => $item['NAME'],
                            'DESCRIPTION' => isset($item['DESCRIPTION']) ? $item['DESCRIPTION'] : $item['NAME'],
                        );
                    }
                }
            }
            if (!empty($result)) {
                return $result;
            }
        }

        if (!thermal_shell_available()) {
            return $result;
        }

        $output = @shell_exec('wmic printer get name /format:csv 2>nul');
        if (!empty($output)) {
            $lines = preg_split('/\r\n|\r|\n/', trim($output));
            foreach ($lines as $line) {
                $line = trim($line);
                if ($line === '' || stripos($line, 'Node,Name') !== false) {
                    continue;
                }
                $parts = str_getcsv($line);
                $name = trim(end($parts));
                if ($name !== '' && strcasecmp($name, 'Name') !== 0) {
                    $result[] = array(
                        'NAME' => $name,
                        'DESCRIPTION' => $name,
                    );
                }
            }
        }

        if (empty($result)) {
            $ps = @shell_exec('powershell -NoProfile -Command "Get-WmiObject Win32_Printer | Select-Object -ExpandProperty Name" 2>nul');
            if (!empty($ps)) {
                $lines = preg_split('/\r\n|\r|\n/', trim($ps));
                foreach ($lines as $name) {
                    $name = trim($name);
                    if ($name !== '') {
                        $result[] = array(
                            'NAME' => $name,
                            'DESCRIPTION' => $name,
                        );
                    }
                }
            }
        }

        return $result;
    }
}

if (!function_exists('thermal_print_windows_copy')) {
    function thermal_print_windows_copy($printerName, $text) {
        if (!thermal_shell_available()) {
            return false;
        }

        $tmpFile = tempnam(sys_get_temp_dir(), 'thprint_');
        if ($tmpFile === false) {
            return false;
        }

        file_put_contents($tmpFile, $text);
        $escapedFile = str_replace('"', '""', $tmpFile);
        $escapedPrinter = str_replace('"', '""', $printerName);
        $cmd = 'copy /B "' . $escapedFile . '" "\\\\localhost\\' . $escapedPrinter . '"';
        $output = shell_exec($cmd);
        @unlink($tmpFile);

        if ($output === null) {
            return false;
        }

        return thermal_copy_success($output);
    }
}

if (!function_exists('thermal_print_to')) {
    function thermal_print_to($printerName, $text) {
        if (thermal_print_extension($printerName, $text)) {
            return true;
        }
        if (thermal_print_winspool($printerName, $text)) {
            return true;
        }
        return thermal_print_windows_copy($printerName, $text);
    }
}

if (!function_exists('thermal_print_extension')) {
    function thermal_print_extension($printerName, $text) {
        if (!function_exists('printer_open')) {
            return false;
        }

        if (!defined('PRINTER_MODE')) {
            define('PRINTER_MODE', 2);
        }

        $printer = @call_user_func('printer_open', $printerName);
        if (!$printer) {
            return false;
        }

        try {
            @call_user_func('printer_set_option', $printer, PRINTER_MODE, 'RAW');
            @call_user_func('printer_write', $printer, $text);
            @call_user_func('printer_close', $printer);
            return true;
        } catch (Exception $e) {
            @call_user_func('printer_close', $printer);
            error_log('thermal_print_extension error: ' . $e->getMessage());
            return false;
        }
    }
}

if (!function_exists('thermal_print_raw')) {
    /**
     * Cetak teks RAW ke printer thermal.
     * @return array ['success'=>bool, 'printer'=>string|null, 'error'=>string|null]
     */
    function thermal_print_raw($text, $printerName = null) {
        $candidates = array();
        if (!empty($printerName)) {
            $candidates[] = $printerName;
        }
        foreach (thermal_default_printers() as $name) {
            if (!in_array($name, $candidates, true)) {
                $candidates[] = $name;
            }
        }

        foreach ($candidates as $name) {
            if (thermal_print_to($name, $text)) {
                error_log("Thermal printing berhasil ke printer: $name");
                return array('success' => true, 'printer' => $name, 'error' => null);
            }
        }

        $msg = 'Gagal cetak ke semua printer: ' . implode(', ', $candidates);
        error_log($msg);
        return array('success' => false, 'printer' => null, 'error' => $msg);
    }
}

?>
