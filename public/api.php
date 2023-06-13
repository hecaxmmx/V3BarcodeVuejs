<?php

    // Receive the RAW post data.
    $json = file_get_contents("php://input");

    // Decode json to assoc. array
    $content =  json_decode($json, true);

    // XXX: CODE UDEV RULES FOR PRINT EVENTS GOES HERE/OR DRIVERS PROCESS
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Do somting here ...
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
    header("Content-Type: application/json; charset=utf-8");
    echo json_encode(array('results' => [$msg]));

?>