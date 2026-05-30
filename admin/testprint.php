<?php
require('../assets/escpos-php/autoload.php');
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;
$logo = EscposImage::load("img/logofafa2.png", false);
try {
    $connector = new WindowsPrintConnector("IDTOKO-1");
    $printer = new Printer($connector);

    //cetak judul awal
    $printer -> setJustification(Printer::JUSTIFY_CENTER);
    $printer -> bitImage($logo);
    $printer -> close();
    } catch (Exception $e) {
        echo "Couldn't print to this printer: " . $e -> getMessage() . "\n";
    }

// try {
       
//         $connector = new WindowsPrintConnector("POS80");
//         $printer = new Printer($connector);
        
//         /** RATA TENGAH */
//         $printer -> setJustification(Printer::JUSTIFY_CENTER);
//         //$printer -> graphics($logo);
//         $printer -> bitImage($logo);

//         $title = "TEST PRINTER ANTRIAN";
//         $printer->initialize();
//         $printer->setFont(Printer::FONT_A);
//         $printer->setJustification(Printer::JUSTIFY_CENTER);
//         $printer->text("\n");
        
//         $printer->initialize();
//         $printer->setFont(Printer::FONT_B);
//         $printer->setJustification(Printer::JUSTIFY_CENTER);
//         $printer->text(date('d/m/Y H:i:s'). "\n");
//         $printer->setLineSpacing(5);
//         $printer->text("\n");

//         $printer->initialize();
//         $printer->setFont(Printer::FONT_A);
//         $printer->setJustification(Printer::JUSTIFY_CENTER);
//         $printer->text("Nomor Antrian Anda Adalah :\n");
//         $printer->text("\n");

//         $printer->initialize();
//         $printer->setJustification(Printer::JUSTIFY_CENTER);
//         $printer->setTextSize(6, 4);
//         $printer->text("A010" . "\n");
//         $printer->text("\n");

//         $printer->initialize();
//         $printer->setFont(Printer::FONT_C);
//         $printer->setTextSize(2, 2);
//         $printer->setJustification(Printer::JUSTIFY_CENTER);
//         $printer->text("LOKET : UMUM" . "\n");
//         $printer->text("\n\n\n");

//         $printer->initialize();
//         $printer->setFont(Printer::FONT_A);
//         $printer->setJustification(Printer::JUSTIFY_CENTER);
//         $printer->text("Silahkan Menunggu Antrian Anda\n");
//         $printer->text("Terima Kasih\n");
//         $printer->text("\n");

//         $printer->cut();
        
//         /* Close printer */
//         $printer->close();
//     } catch (Exception $e) {
//         echo "Couldn't print to this printer: " . $e -> getMessage() . "\n";
//     }

// use Mike42\Escpos\Printer;
// use Mike42\Escpos\PrintConnectors\DummyPrintConnector;
// use Mike42\Escpos\CapabilityProfile;

// // Make sure you load a Star print connector or you may get gibberish.
// $connector = new DummyPrintConnector();
// $profile = CapabilityProfile::load("default");
// $printer = new Printer($connector);
// $printer -> text("Hello world!\n");
// $printer -> cut();

// // Get the data out as a string
// $data = $connector -> getData();

// // Return it, check the manual for specifics.
// header('Content-type: application/octet-stream');
// header('Content-Length: '.strlen($data));
// echo $data;

// // Close the printer when done.
// $printer -> close();