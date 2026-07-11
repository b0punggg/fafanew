<html>
    <head>
        <title>Menampilkan List Printer</title>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
        <script type="text/javascript">
            function cetak(){
                var printer = $("#printer").val();
                if (!printer) {
                    alert('Pilih printer terlebih dahulu');
                    return;
                }
                $.ajax({
                    url : "cetak_langsung.php",
                    type: "POST",
                    data : "nama_printer="+encodeURIComponent(printer),
                    dataType: "json",
                    success: function(data)
                    {
                        if (data.success) {
                            alert('Test cetak berhasil ke printer: ' + data.printer);
                        } else {
                            alert('Gagal cetak: ' + (data.error || 'Unknown error'));
                        }
                    },
                    error: function() {
                        alert('Gagal menghubungi server cetak');
                    }
                });
            }
        </script>
    </head>
    <body>
        <h3>Silahkan Pilih Printer :</h3>
        <?php
        include_once 'thermal_printer.php';
        $printers = thermal_list_printers();
        if (empty($printers)) {
            echo '<p style="color:red">Tidak ada printer terdeteksi.</p>';
            echo '<p>Extension php_printer: ' . (function_exists('printer_open') ? '<b style="color:green">AKTIF</b>' : '<b style="color:orange">TIDAK AKTIF</b>') . '</p>';
            echo '<p>Windows copy fallback: ' . (thermal_shell_available() ? '<b style="color:green">TERSEDIA</b>' : '<b style="color:red">TIDAK TERSEDIA</b>') . '</p>';
            echo '<p>Pastikan printer sudah terinstall di Windows (Control Panel → Devices and Printers).</p>';
        } else {
            echo '<select name="printers" id="printer" style="min-width:320px;padding:4px;">';
            $defaultPrinter = 'BP-LITE 80D+80X Printer';
            foreach ($printers as $PrintDest) {
                $name = $PrintDest['NAME'];
                $label = $PrintDest['DESCRIPTION'];
                if (strpos($label, ',') !== false) {
                    $parts = explode(',', $label);
                    $label = isset($parts[1]) ? trim($parts[1]) : $name;
                }
                $selected = ($name === $defaultPrinter) ? ' selected' : '';
                echo "<option value='" . htmlspecialchars($name, ENT_QUOTES) . "'$selected>" . htmlspecialchars($label, ENT_QUOTES) . " ($name)</option>";
            }
            echo '</select>';
            echo '<br><br><button type="button" onClick="cetak()">Test Cetak</button>';
        }
        ?>
    </body>
</html>
