<?php

// Receive the RAW post data.
$json = file_get_contents("php://input");

// Decode json to assoc. array
$content =  json_decode($json, true);

// XXX: CODE UDEV RULES FOR PRINT EVENTS GOES HERE/OR DRIVERS PROCESS
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $valor = print_bcode($content);
    //var_dump($valor);

    $puerto = system('ls /dev/usb/lp*');
    if ($puerto == '/dev/usb/lp0')
        $printer = "/dev/usb/lp0";
    else
        $printer = "/dev/usb/lp1";

    try {
        //iniciar string

        //$datos = str_replace($latinchars, $encoded, $d);

        //send data to USB printer
        $fp = fopen($printer, 'wb');
        fwrite($fp, $valor);
        fclose($fp);
        echo "impreso";
    } catch (Throwable $t) {
        // Executed only in PHP 7, will not match in PHP 5.x
        echo 'NO SE PUDO IMPRIMIR: " Excepción capturada: ',  $t->getMessage(), "\n";
    } catch (Exception $e) {
        // Executed only in PHP 5.x, will not be reached in PHP 7
        echo 'NO SE PUDO IMPRIMIR: " Excepción capturada: ',  $e->getMessage(), "\n";
    }
} else {
    $msg = array(
        'type' => 'error',
        'msg'  => 'XHR: Error on requested petition',
    );
}

// XXX: IF OK OR ERROR. PLEASE SEND A MESSAGE TO FRONT
$msg = array(
    'type' => 'success',
    'msg'  => 'Barcode(s) printed successfuly.',
);

// $msg = array(
//     'type' => 'error',
//     'msg'  => 'Error on printing... Barcodes cannot be printed.',
// );

// XXX: SEND JSON TO FRONT

function print_bcode($content)
{
    $empresa = " UES FACULTAD DE ORIENTE ";
    $string = "";
    $valor = $content['data'];
    foreach ($content['data'] as $valor) {
        foreach ($valor as $key => $val) {

            $header = $valor['header'];
            $subheader = $valor['subheader'];
            if ($key == 'barcode') {
                $barcode = $val;
                $posx =    115;
                $posy = 15; //x,y posicion
                $string .= "^XA";
                $string .= "^CF0,20";
                $string .= "^FO" . $posx . "," . $posy . "^FD" . $header . "^FS";
                $string .= "^CF0,20";
                $posy += 20;
                $string .= "^FO" . $posx . "," . $posy . "^FD" . $subheader . "^FS";
                $string .= "^CF0,30";
                $string .= "^BY2,1";
                $posx += 5;
                $posy += 30;
                $string .= "^FO" . $posx . "," . $posy . "^BY2,2";
                $string .= "^BCN,60,Y,N,N";
                $string .= "^FD" . $barcode . "^FS";
                $posx += 315; //segunda etiqueta lado derecho
                $posy -= 55;
                $string .= "^CF0,20";
                $string .= "^FO" . $posx . "," . $posy . "^FD" . $header . "^FS";
                $string .= "^CF0,20";
                $posy += 20;
                $string .= "^FO" . $posx . "," . $posy . "^FD" . $subheader . "^FS";
                $string .= "^CF0,30";
                $string .= "^BY2,1";
                $posx += 5;
                $posy += 30;
                $string .= "^FO" . $posx . "," . $posy . "^BY2,2";
                $string .= "^BCN,60,Y,N,N";
                $string .= "^FD" . $barcode . "^FS";
                $string .= "^XZ";
            }
        }
    }
    return ($string);
}
