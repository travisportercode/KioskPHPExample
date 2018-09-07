<?php


#$file = 'printTest/testing.txt';
#$file = '/dev/print';
#file_put_contents($file,$_REQUEST['fgl']);

    $f = fopen("/dev/print", "r+");
    if(!$f) {
        echo "error opening file\n";
        exit;
    }

    if (!empty($_REQUEST['xml'])){
        write($f, "<NR>   <RC50,260>      <F3><HW2,1>TEST EVENT <RC120,260>      <F9><HW2,1>Christina Franklin - 6254154819      <RC180,260>     <F5><HW2,1>     <RC250,260>     <F5><HW1,1>     <RC310,260>     <F10><HW1,1>25.00<RL>   <RC220,910>     <F1><HW2,1>2014CF       <RC320,950>     <F9><HW1,1>ADULT / 6254154819   <RC80,1010><FL><X3>:1413500401: <RC260,1020>1413500401<p>");
    } else if (!empty($_REQUEST['fgl'])){
        write($f,$_REQUEST['fgl']);
    } else {
        exit('ERROR - missing xml or fgl.');
    }
    
exit;



$usb = 'ttyUSB0';        
    `stty -F /dev/$usb 9600`;
    `stty -F /dev/$usb -parity`;
    `stty -F /dev/$usb cs8`;
    `stty -F /dev/$usb -cstopb`;
    $f = fopen("/dev/$usb", "r+");
    if(!$f) {
        echo "error opening file\n";
        exit;
    }

    statusRequest($f);
    sleep(1);
    $c = readPort($f);
    echo "$c\n";

function statusRequest($port) {
    $data = "request";
    fwrite($port, $data);
    fflush($port);
}

function write($port,$data) {
    fwrite($port, $data);
    fflush($port);
}

function readPort($port) {
    $read = 1;
    $c = '';
    while($read > 0) {
        $bytesr = unpack("h*", fread($port, 1));
        $c .= $bytesr[1];
        //echo $bytesr[1];
        if($bytesr[1] == 'ff') {
            $read = 0;
        }
    }    
    return $c;
}









?>

