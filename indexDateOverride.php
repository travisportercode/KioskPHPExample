<?php


// TODO: make spinner for sales work like will-call - it continues to spin while things are happening.
// TODO: login using membership number.
// TODO: check whether membership number is active.



foreach (glob('../models/*.php') as $filename){
    include $filename;
}


// has to go before the session start.
foreach (glob('../../models/*.php') as $filename){
    include $filename;
}


session_start();

$_SESSION['data']['db'] = 'ecommerce';
$_SESSION['data']['user'] = 'ecommerce'; 
$_SESSION['data']['pass'] = 'ecommerce_connect';
$_SESSION['metaTag'] = '';

date_default_timezone_set("America/New_York");
if (!empty($_REQUEST['date'])){
    $_SESSION['date'] = $_REQUEST['date'];
}  else {
    $_SESSION['date'] = date('Ymd');
}
//exit("d = " . $_SESSION['date']);

if (!empty($_REQUEST['kiosk'])){
//    exit("kiosk = " . $_REQUEST['kiosk']);
    $_SESSION['kiosk'] = $_REQUEST['kiosk'];
}
// oops! I named the request client. Don't confuse this with the soap client.
if (!empty($_REQUEST['client'])){
    $_SESSION['client'] = $_REQUEST['client'];
//} else {
//    $_SESSION['client'] = 'unknown';
}
$_SESSION['dateOverride'] = '';
$_SESSION['timeOverride'] = '';

if (!empty($_REQUEST['dateOverride'])){
    $_SESSION['dateOverride'] = $_REQUEST['dateOverride'];
    $_SESSION['date'] = $_SESSION['dateOverride'];
}
if (!empty($_REQUEST['timeOverride'])){
    $_SESSION['timeOverride'] = $_REQUEST['timeOverride'];
}


$_SESSION['backgroundCss'] = "background-image:url('images/bg_laser.jpg');";
$liveOrTest = 'live';


//include ('ShoppingCartItem.php');  // TODO: CURRENTLY NOT BEING USED ANYMORE. DELETE IN THE FUTURE.
// include other things here.
// include other things here.
$environmentUrl = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
if (strpos($environmentUrl,"indexTest.php") > 1){
    $liveOrTest = 'test';  
} else if (strpos($environmentUrl,"indexDerek.php") > 1){
    $liveOrTest = 'test';  
} else if (strpos($environmentUrl,"indexDerekNewTest.php") > 1){
    $liveOrTest = 'test';
    exit("here");
} else if (strpos($environmentUrl,"indexChansoudaNewTest.php") > 1){
    $liveOrTest = 'test'; 
} else if (strpos($environmentUrl,"indexMfaNewTest.php") > 1){
    $liveOrTest = 'test';
} else if (strpos($environmentUrl,"index.php") > 1){
    $liveOrTest = 'live';
} else if (strpos($environmentUrl,"indexTravisTest.php") > 1){
    $liveOrTest = 'test';
} else if (strpos($environmentUrl,"index2.php") > 1){
    $liveOrTest = 'test';
} else {
    $liveOrTest = 'live';
}


// put everything here so to reduce the cahnce for error in updating...
if ($liveOrTest == 'test'){
    $_SESSION['soapClient'] = new SoapClient("https://tesslive.frostscience.org/TessituraWebAPI/tessitura.asmx?WSDL");
    $_SESSION['soapUrl'] = 'https://tesslive.frostscience.org/TessituraWebAPI/tessitura.asmx?WSDL';
    $_SESSION['soapUrlDirect'] = 'https://tesslive.frostscience.org/TessituraWebAPI/tessitura.asmx';
    include ('../TessituraDerekApiNewTest.php');
    include ('../ElementApi.php');
    $_SESSION['reprint'] = true; 
    $_SESSION['salesCacheFile'] = '/ECommerceSessions/frost/PerformancesTest_' . $_SESSION['date'] . '.txt';
} else {
    $_SESSION['soapClient'] = new SoapClient("https://tesslive.frostscience.org/TessituraWebAPI/tessitura.asmx?WSDL");
    $_SESSION['soapUrl'] = 'https://tesslive.frostscience.org/TessituraWebAPI/tessitura.asmx?WSDL';
    $_SESSION['soapUrlDirect'] = 'https://tesslive.frostscience.org/TessituraWebAPI/tessitura.asmx';
    include ('../TessituraDerekApiNewTest.php');
    include ('../ElementApi.php');
    $_SESSION['reprint'] = false;  
//    $_SESSION['salesCacheFile'] = '/ECommerceSessions/bam/PerformancesProd_' . date('Ymd') . '.txt';
    $_SESSION['salesCacheFile'] = '/ECommerceSessions/frost/PerformancesTest_' . $_SESSION['date'] . '.txt';
//    $_SESSION['salesCacheFile'] = '/ECommerceSessions/bam/PerformancesTest.txt';
}




//$setToProd = 1; // 11-1-2015 - commented out by tkp. setToProd is not being used anywhere else in the program (only in comments). 
$_SESSION['printToday'] = true;


if (empty($_SESSION['startUrl'])) {
    $_SESSION['startUrl'] = $environmentUrl;
    //exit('session is empty');
}


if (empty($_SESSION['kiosk']) && empty($_SESSION['client'])){
    errorPageSales('Missing client name and kiosk name in Start URL.');
} else if (empty($_SESSION['client'])){
    errorPageSales('Missing client name in Start URL.');
} else if (empty($_SESSION['kiosk'])){
    errorPageSales('Missing kiosk name in Start URL.');
}
//exit('startURL: ' . $_SESSION['startUrl']);
//exit('clientName: ' . $_SESSION['client']);
//csn 20160613
//if (empty($_SESSION['location'])) {
//    $_SESSION['kiosk'] = new kiosk();
//    $_SESSION['location'] = $_SESSION['kiosk']->loadFromDb($_SESSION['kiosk'], $_SESSION['client']);
//}

//$_SESSION['kioskObject']->loadFromDb($_SESSION['kiosk'], $_SESSION['client']);
//    echo "<pre>";
//    print_r($_SESSION['kiosk']);
//    print_r($_SESSION['client']);
//    print_r($_SESSION['kioskObject']);
//    echo "</pre>";
//    exit;

//csn 20160613
if (empty($_SESSION['kioskObject'])) {
    $_SESSION['kioskObject'] = new kiosk();
    $_SESSION['kioskObject']->loadFromDb($_SESSION['kiosk'], $_SESSION['client']);
    // $_SESSION['kioskObject']->location;
    
//    echo "<pre>";
//    print_r($_SESSION['kiosk']);
//    print_r($_SESSION['client']);
//    print_r($_SESSION['kioskObject']);
//    echo "</pre>";
//    exit;
}

    



/*$reqDump = "\n\n\n\n\n";
$reqDump .= "---------------------" . date('Y-m-d H:i:s') . "---------------------";
$reqDump .= "\n\n\n\n\n";
$reqDump .= print_r($_REQUEST, true);
$reqDump .= print_r($_SESSION, true);

$fp = file_put_contents('/ECommerceSessions/15864/requests_' .  date("Ymd") . '.txt', $reqDump, FILE_APPEND);
*/



$_SESSION['loginData']['IP'] = '';

$_SESSION['data']['db'] = 'ecommerce';
$_SESSION['data']['user'] = 'ecommerce'; 
$_SESSION['data']['pass'] = 'ecommerce_connect';

$_SESSION['account'] = new Account();
$_SESSION['account']->loadFromDb('frost');

$_SESSION['modeOfSale'] = '9';
//$_SESSION['newPromoCode'] = $_SESSION['kioskObject']->location;
$_SESSION['newPromoCode'] = '1';



if (empty($_SESSION['lastTask'])){
    $_SESSION['lastTask'] = '';
}


$javaFgl = '';
$errorMessage = ''; //csn
$_SESSION['fglReceipt'] = '';
    
//    salesprinting(), called in /var/www/html/ECommerce/Tessitura/Kiosk/index.php on line 63 and defined in /var/www/html/ECommerce/Tessitura/Kiosk/index.php on line 1097





    // set up the default task.
    //$default = 'willcall';
    $default = 'startPage';
    if (empty($_REQUEST['task'])){
        //$_REQUEST['task'] = 'startPage';
        $_REQUEST['task'] = $default;
    }
    // create a request record for everything besides willcall/OR BESIDES THE DEFAULT.
    // TODO: look for special tasks, lookupByConfirmation, lookupByAccount, lookupByCC, or memberYesNo 
    //if (!($_REQUEST['task'] == $default)){
    if ($_REQUEST['task'] == 'memberYesNo'){
        
        // this has been moved for the mode of sales. we now go each path after memberYesNo
        // validateMember or survey
        

        //$_SESSION['request'] = new Request();
        //$_SESSION['request']->insert();
    } else if ($_REQUEST['task'] == 'lookupByConfirmation'){
//        exit('lookupByConfirmation');
        $_SESSION['request'] = new Request();
        $_SESSION['request']->insert();
    } else if ($_REQUEST['task'] == 'lookupByAcctNbr'){
//        exit('lookupByAcctNbr');
        $_SESSION['request'] = new Request();
        $_SESSION['request']->insert();
    } else if ($_REQUEST['task'] == 'lookupByCC'){
//        exit('lookupByCC');
        $_SESSION['request'] = new Request();
        $_SESSION['request']->insert();
    }
    
    
   //         elseif  $_REQUEST['task'] == 'lookupByConfirmation' || $_REQUEST['task'] == 'lookupByAcctNbr' || $_REQUEST['task'] == 'lookupByCC'){

    
   

    if ($_REQUEST['task'] == 'startPage'){
        // this is now handled in the startPage method....
       
        //if (!empty($_REQUEST['mode'])){
        //    if ($_REQUEST['mode'] == 'willCall'){
        //        willcall(); 
        //    } else {
        //        startPage();
        //    }
        //} else {
            startPage();
        //}
    } else if ($_REQUEST['task'] == 'logout'){
        startPage();
    } else if ($_REQUEST['task'] == 'memberYesNo'){
        memberYesNo();
    } else if ($_REQUEST['task'] == 'validateMember'){
        validateMember();
//    } else if ($_REQUEST['task'] == 'validateMemberTemp'){
//        validateMemberTemp();
    } else if ($_REQUEST['task'] == 'prices'){
        prices($errorMessage);
    } else if ($_REQUEST['task'] == 'pricesByTime'){
        if (!empty($_REQUEST['performanceTime'])){
            $performanceTime = $_REQUEST['performanceTime'];
        }
        pricesByTime($errorMessage, $performanceTime);
    } else if ($_REQUEST['task'] == 'performanceTimes'){
        $startNumber = 0;
        $pricingGroup = 'blank';
        if (!empty($_REQUEST['startNumber'])){
            $startNumber = $_REQUEST['startNumber'];
        }
        if (!empty($_REQUEST['pricingGroup'])){
            $_SESSION['pricingGroup'] = $_REQUEST['pricingGroup'];
            $pricingGroup = $_REQUEST['pricingGroup'];
        }
        performanceTimes($startNumber, $pricingGroup);    
    } else if ($_REQUEST['task'] == 'reserve'){
        reserve();
    } else if ($_REQUEST['task'] == 'checkEntitlements'){
        checkEntitlements(); //csn
    } else if ($_REQUEST['task'] == 'authorizePayment'){
        authorizePayment();
    } else if ($_REQUEST['task'] == 'checkoutApiAndPickupAndPrint'){
        checkoutApiAndPickupAndPrint();
    } else if ($_REQUEST['task'] == 'performances'){
        $startNumber = 0;
        if (!empty($_REQUEST['startNumber'])){
            $startNumber = $_REQUEST['startNumber'];
        }
        performances($startNumber);
    } else if ($_REQUEST['task'] == 'performancesWithoutTimes'){
        $startNumber = 0;
        if (!empty($_REQUEST['startNumber'])){
            $startNumber = $_REQUEST['startNumber'];
        }
        performancesWithoutTimes($startNumber);
    } else if ($_REQUEST['task'] == 'survey'){
        survey();
    } else if ($_REQUEST['task'] == 'willcall'){
        willcall();
    } else if ($_REQUEST['task'] == 'willcallPrinting'){
       willcallPrinting($javaFgl);
    } else if ($_REQUEST['task'] == 'sales'){
        sales();
//    } else if ($_REQUEST['task'] == 'zipcode'){
//        zipcode();
    } else if ($_REQUEST['task'] == 'groups'){
        groups();
    } else if ($_REQUEST['task'] == 'continueWithNonMemberPricing'){
        continueWithNonMemberPricing();
//    } else if ($_REQUEST['task'] == 'items'){
//        items();
    } else if ($_REQUEST['task'] == 'membership'){
        membership();
    } else if ($_REQUEST['task'] == 'payment'){
        payment();
    } else if ($_REQUEST['task'] == 'salesprinting'){
        salesPrinting($javaFgl);
    } else if ($_REQUEST['task'] == 'lookupByConfirmation'){
        lookupByConfirmation($_REQUEST['confirmationInput'], 'willcall');
    } else if ($_REQUEST['task'] == 'lookupByAcctNbr'){
        lookupByAcctNbr($_REQUEST['acctNbr']);
    } else if ($_REQUEST['task'] == 'lookupByCC'){
        lookupByCC();
    } else if ($_REQUEST['task'] == 'displayCartNew'){
        $_SESSION['request'] = new Request();
        $_SESSION['request']->insert();
        displayCartNew($errorMessage);
    } else if ($_REQUEST['task'] == 'getTicketPrintInformationFromFile'){
        $_SESSION['request'] = new Request();
        $_SESSION['request']->insert();
        getTicketPrintInformationFromFile();
//    } elseif ($task == 'landingPage'){
//    landingPage('', 0); // pass in a blank content here. this will only happen when someone requests it through the url.
//        
//    } else if ($_REQUEST['task'] == 'startPage'){
//        $_SESSION['header'] = '<!-- Header -->
//            <table cellspacing=0 cellpadding=0 style="background: #284e63" width="100%">
//                <tr>
//                    <td><img src="site_files/JustArriveLogo.png" height="60"></td>
//                    <td></td>
//                    <td></td>
//                    <td></td>
//                    <td></td>
//                </tr>
//            </table>
//
//        ';
//startPage();
//willcall();
    
        
    } else {
        echo"<B>INVALID TASK</B><BR>";
        echo "<pre>";
        print_r($_REQUEST);
        echo "</pre>";
        exit;
        errorPageSales("Invalid Task: " . $_REQUEST['task']);
    }
 

function authorizePaymentThirdParty(){
    
    
    // you must display the xml in a textarea or it will look blank (because the xml is interpretted as html tags)
    //echo "<textarea rows=20 cols=120>";
    //echo $_REQUEST['swipeData'];
    //echo "</textarea>";
    //exit();
    
    if (empty($_REQUEST['swipeData'])){
        displayCart("There was an error reading the data from your card.<br>Your card was not charged.<br>Please try the swipe again.");
    } else if ($_REQUEST['swipeData'] == 'fake failure'){
//        exit("hello made it here");
        displayCart("There was an error reading the data from your card.<br>Your card was not charged.<br>Please try the swipe again.");
    }
    
    
    
    
        
        if ($_REQUEST['swipeData'] == 'fake'){
            //$_REQUEST['swipeData'] = '<DvcMsg Ver="1.0"><Dvc App="EDGE" AppVer="2.4" DvcType="ElementPINPad" DvcSN="80059228" Entry="SWIPE" ></Dvc>
            //<Card ETrk1="147C50469CC630B91B6D3C59197D57E9711F3B9EEED6EE1CCB62809818F65F98720793864C7991A4C2814B321467758A8DC4C38049B657636AF3CC0E0452C410:FFFF987654000320002C" 
            //ETrk2="352D8B0F073EC757552B7074A344FA84602F2009D960A3D438AB88F6BBAF610FFE8DE2AC3C540A4A" 
            //CDataKSN="FFFF987654000320002C" MskPAN="4239********8145" Exp="0916" 
            //CHolder="PORTER TRAVIS" ></Card><Addr ></Addr><Tran TranType="CREDIT" ></Tran></DvcMsg>';
            
//            // visa
//            $_REQUEST['swipeData'] = '<DvcMsg Ver="1.0"><Dvc App="EDGE" AppVer="2.4" DvcType="ElementPINPad" DvcSN="20020844" Entry="SWIPE" ></Dvc>
//                     <Card ETrk1="4111111111111111^SAVEREID      /CHRISTOPHER^1608101196170040000A000" ETrk2="4111111111111111=16081011961740000000" 
//                    CDataKSN="" MskPAN="4427********6199" Exp="0816" CHolder="SAVEREID      /CHRISTOPHER" ></Card><Addr ></Addr><Tran TranType="CREDIT" >
//                    </Tran></DvcMsg>';
            
            
//            // master card
//            $_REQUEST['swipeData'] = '<DvcMsg Ver="1.0"><Dvc App="EDGE" AppVer="2.4" DvcType="ElementPINPad" DvcSN="20020844" Entry="SWIPE" ></Dvc>
//                     <Card ETrk1="5555555555554444^SAVEREID      /CHRISTOPHER^1608101196170040000A000" ETrk2="5555555555554444=16081011961740000000" 
//                    CDataKSN="" MskPAN="4427********6199" Exp="0816" CHolder="SAVEREID      /CHRISTOPHER" ></Card><Addr ></Addr><Tran TranType="CREDIT" >
//                    </Tran></DvcMsg>';

            
            // american express
            $_REQUEST['swipeData'] = '<DvcMsg Ver="1.0"><Dvc App="EDGE" AppVer="2.4" DvcType="ElementPINPad" DvcSN="20020844" Entry="SWIPE" ></Dvc>
                     <Card ETrk1="378282246310005^SAVEREID      /CHRISTOPHER^1608101196170040000A000" ETrk2="378282246310005=16081011961740000000" 
                    CDataKSN="" MskPAN="4427********6199" Exp="0816" CHolder="SAVEREID      /CHRISTOPHER" ></Card><Addr ></Addr><Tran TranType="CREDIT" >
                    </Tran></DvcMsg>';
            
            
            
            
            
            
        }
        
        // testing.
        //displayCart("There was an error reading the data from your card.<br>Your card was not charged.<br>Please try the swipe again.");
        
        
        
        
        $asciiString = mb_convert_encoding ($_REQUEST['swipeData'], 'US-ASCII', 'UTF-8');
        //echo "swipeData:<br><textarea rows=20 cols=100>swipeData: " . $asciiString . "</textarea><br>";
        //logString("swipeData: " . $asciiString);
        $mskPAN = "";
        $encryptedTrack1 = "";
        $encryptedCardData = "";
        $encryptedTrack2 = "";
        $CHolder = "";
        $encryptedCardData = "";
        $_SESSION['terminalSerialNumber'] = '';
//        $exp = "";
        $entry = "";
        $acctnbr = "";
        
        
        $mskPANStartPos = strpos($asciiString,'MskPAN="') + 8;
        if ($mskPANStartPos > 0){
            $mskPANEndPos = strpos($asciiString,'"',$mskPANStartPos) -1;
//            echo "mskPANStartPos: " . $mskPANStartPos . "<br>";
//            echo "mskPANEndPos: " . $mskPANEndPos . "<br>";
            $mskPAN = substr($asciiString,$mskPANStartPos,$mskPANEndPos-$mskPANStartPos+1);
//            exit("mskPan: " . $mskPAN);
        } else {
            //echo "track1 data not found.";
            displayCart("There was an error(mskPan) reading the data from your card.<br>Your card was not charged.<br>Please try the swipe again.");
        }
        
        
        $encryptedTrk1StartPos = strpos($asciiString,'ETrk1="') + 7;
        if ($encryptedTrk1StartPos > 0){
            $encryptedTrk1EndPos = strpos($asciiString,':',$encryptedTrk1StartPos) -1;
            //echo "encryptedTrk1StartPos: " . $encryptedTrk1StartPos . "<br>";
            //echo "encryptedTrk1EndPos: " . $encryptedTrk1EndPos . "<br>";
            $encryptedTrack1 = substr($asciiString,$encryptedTrk1StartPos,$encryptedTrk1EndPos-$encryptedTrk1StartPos+1);
            $encryptedCardDataStartPos = $encryptedTrk1EndPos + 2;
            $encryptedCardDataEndPos = strpos($asciiString,'"',$encryptedCardDataStartPos) -1;
            $encryptedCardData = substr($asciiString,$encryptedCardDataStartPos,$encryptedCardDataEndPos-$encryptedCardDataStartPos+1);
//            echo "encryptedTrack1: " . $encryptedTrack1 . "<br>";
//            echo "encryptedCardData: " . $encryptedCardData . "<br>"; 
        } else {
            //echo "track1 data not found.";
            displayCart("There was an error(track1) reading the data from your card.<br>Your card was not charged.<br>Please try the swipe again.");
        }
        
        
        $encryptedTrk2StartPos = strpos($asciiString,'ETrk2="') + 7;
        if ($encryptedTrk2StartPos > 0){
            $encryptedTrk2EndPos = strpos($asciiString,'"',$encryptedTrk2StartPos) -1;
            //echo "encryptedTrk2StartPos: " . $encryptedTrk2StartPos . "<br>";
            //echo "encryptedTrk2EndPos: " . $encryptedTrk2EndPos . "<br>";
            $encryptedTrack2 = substr($asciiString,$encryptedTrk2StartPos,$encryptedTrk2EndPos-$encryptedTrk2StartPos+1);
            //echo "encryptedTrack2: " . $encryptedTrack2 . "<br>";
        } else {
            //echo "track1 data not found.";
            displayCart("There was an error(Track2) reading the data from your card.<br>Your card was not charged.<br>Please try the swipe again.");
        }
        
        $CHolderStartPos = strpos($asciiString,'CHolder="') + 9;
        if ($CHolderStartPos > 0){
            $CHolderEndPos = strpos($asciiString,'"',$CHolderStartPos) -1;
            //echo "CHolderStartPos: " . $CHolderStartPos . "<br>";
            //echo "CHolderEndPos: " . $CHolderEndPos . "<br>";
            $CHolder = substr($asciiString,$CHolderStartPos,$CHolderEndPos-$CHolderStartPos+1);
            //echo "CHolder: " . $CHolder . "<br>";
        } else {
            //echo "CHolder data not found.";
            displayCart("There was an error(CHolder) reading the data from your card.<br>Your card was not charged.<br>Please try the swipe again.");
        }
        
        $terminalSerialNumberStartPos = strpos($asciiString,'DvcSN="') + 7;
        if ($terminalSerialNumberStartPos > 0){
            $terminalSerialNumberEndPos = strpos($asciiString,'"',$terminalSerialNumberStartPos) -1;
            //echo "encryptedTrk2StartPos: " . $encryptedTrk2StartPos . "<br>";
            //echo "encryptedTrk2EndPos: " . $encryptedTrk2EndPos . "<br>";
            $_SESSION['terminalSerialNumber'] = substr($asciiString,$terminalSerialNumberStartPos,$terminalSerialNumberEndPos-$terminalSerialNumberStartPos+1);
            //echo "terminaSerial: " . $terminalSerialNumber . "<br>";
        } else {
            displayCart("There was an error(SerialNbr) reading the data from your card.<br>Your card was not charged.<br>Please try the swipe again.");
        }
        
//        $expStartPos = strpos($asciiString,'Exp="') + 5;
//        if ($expStartPos > 0){
//            $expEndPos = strpos($asciiString,'"',$expStartPos) -1;
//            //echo "encryptedTrk2StartPos: " . $encryptedTrk2StartPos . "<br>";
//            //echo "encryptedTrk2EndPos: " . $encryptedTrk2EndPos . "<br>";
//            $_SESSION['exp'] = substr($asciiString,$expStartPos,$expEndPos-$expStartPos+1);
//            //echo "exp: " . $exp . "<br>";
//        } else {
//            //echo "serialNumber data not found.";
//            generalError();
//        }
        
        $entryStartPos = strpos($asciiString,'Entry="') + 7;
        if ($entryStartPos > 0){
            $entryEndPos = strpos($asciiString,'"',$entryStartPos) -1;
            //echo "encryptedTrk2StartPos: " . $encryptedTrk2StartPos . "<br>";
            //echo "encryptedTrk2EndPos: " . $encryptedTrk2EndPos . "<br>";
            $_SESSION['entry'] = substr($asciiString,$entryStartPos,$entryEndPos-$entryStartPos+1);
            //echo "entry: " . $entry . "<br>";
        } else {
            displayCart("There was an error(entry) reading the data from your card.<br>Your card was not charged.<br>Please try the swipe again.");
        }
        
        
       
        
        //csn added ccNumber variable to get unencrypted number 20160504
        $ETrk2Values = explode("=", $encryptedTrack2);
        $_SESSION['ccNumber'] = $ETrk2Values[0];
        //exit('ccNumber: ' . $_SESSION['ccNumber']);
        
        //csn added to get first and last name of cardholder 20160505
        
        if (!empty($CHolder)) {
            $CHolderValues = explode("/", $CHolder);
            $_SESSION['ccLastName'] = $CHolderValues[0];
            $_SESSION['ccFirstName'] = $CHolderValues[1];
            //exit('ccFirstName ' . $_SESSION['ccFirstName']);
            $data['ccOwner'] = $_SESSION['ccFirstName'] . " " . $_SESSION['ccLastName'];
            //exit('ccOwner: ' . $data['ccOwner']);
        } else {
            $data['ccOwner'] = 'Kiosk User';
        }
        
//        $startWith = substr($_SESSION['ccNumber'],0,2);
//        //exit('startWith: ' . $startWith);
//        
//        if ($startWith >= '40' && $startWith <= '49') {
//            $ccType = '33';
//        } else if ($startWith >= '51' && $startWith <= '55') {   
//            $ccType = '34';
//        } else if ($startWith == '34' || $startWith == '37') {   
//            $ccType = '35';
//        }
//        
//        exit('startWith ' . $startWith);
        //displayCart("Made it this far.");
        
        
       
        
        
        //TODO: GET OTHER FIELDS
        
        
        
        
        
        if ($encryptedTrack2 > "" && $encryptedCardData > ""){
            // use encryptedtrack2 to process payment.
            $ccData = '';
            $test = "Visa Swiped (EncryptedTrack2Data)";
            $ccData['encryptedTrack1Data'] = '';
            $ccData['cardDataKeySerialNumber'] = $encryptedCardData;
            //$ccData['mskPAN'] = $mskPAN;
            $ccData['encryptedTrack2Data'] = $encryptedTrack2; // travis' encrypted card
            $ccData['amount'] = $_SESSION['orderTotal'];
            $ccData['billingZipCode'] = '72901';
            //$ccData['terminalId'] = 'kiosk_test3';
            $ccData['terminalId'] = $_SESSION['kiosk'];
            $returnList = saleElement($ccData);
            $approved = $returnList[0];
            $orderId = $returnList[1];
            $errorMessage = $returnList[2];
            
            
            
            
            //showData($test,$ccData, $returnList);
            
            if ($approved === true){
                
                //TODO: Get Act number, get from GetTicketInformation, use static for now
                $_SESSION['exp'] = "XX/XX";
                $_SESSION['acctNbr'] = "12345";

                $_SESSION['clerk'] = $_SESSION['kiosk'];

                $_SESSION['approvalCode'] = $_SESSION['response']['response']['Transaction']['ApprovalNumber'];
                //echo "approvalCode: " . $_SESSION['response']['response']['Transaction']['ApprovalNumber'] . "<br>";
                $_SESSION['creditPurchase'] = $_SESSION['response']['response']['Transaction']['ApprovedAmount'];
                //echo "creditPurchase: " . $creditPurchase . "<br>";
                $_SESSION['card'] = $_SESSION['response']['response']['Card']['CardLogo'];
                //echo "card: " . $card . "<br";
                $_SESSION['ref'] = $_SESSION['response']['response']['Transaction']['ReferenceNumber'];
                //echo "ref: " . $ref . "<br>";
                $_SESSION['merchLocCode'] = $_SESSION['credentials']['AccountID'];
                //echo "merch: " . $merchLocCode;

                $tranDate = $_SESSION['response']['response']['ExpressTransactionDate']; //20150626
                $_SESSION['tranDate'] = date('F j, Y', strtotime($tranDate));
        
                $tranTime = $_SESSION['response']['response']['ExpressTransactionTime']; //152907
                $_SESSION['tranTime'] = date('g:iA', strtotime($tranTime));
                
                //exit('encryptedTrack2: ' . $encryptedTrack2);
                thanks($mskPAN);
            } else {
//                echo "<textarea rows=20 cols=120>";
//                echo $_REQUEST['swipeData'];
//                echo "</textarea>";
//                exit();
                displayCart("There was a problem authorizing that card.<br>" . $errorMessage . ".<br>Your card was not charged.<br>Please try the swipe again.");
            }
            
        } else {
            displayCart("There was an error(last) reading the data from your card.<br>Your card was not charged.<br>Please try the swipe again.");
        }
        
        
        
        
        //???
        //If $approved = False go to error page
        //If $approved = True go to Thank You page.
        //if ($returnList[0] == true){
        //    thanks();
        //} else {
        //    generalError();
        //}
        
         
        
        
        
        
    
}      



function checkoutApiAndPickupAndPrint(){
    //$log = html_entity_decode($_REQUEST['log']);
//    echo "<pre>";
//    print_r($_REQUEST);
//    echo "</pre>";
//    exit();
    
    
    
    $_SESSION['tranDate'] = date('F j, Y');
    $_SESSION['tranTime'] = date('g:iA');
    $_SESSION['clerk'] = $_SESSION['kiosk'];
    $_SESSION['login'] = $_SESSION['loginEmail'];
    $_SESSION['amount'] = $_REQUEST['amount'];
    $_SESSION['accountNumber'] = $_REQUEST['accountNumber'];
    $_SESSION['expirationMonth'] = $_REQUEST['expirationMonth'];
    $_SESSION['expirationYear'] = $_REQUEST['expirationYear'];
    $_SESSION['cardLogo'] = $_REQUEST['cardLogo'];
    $_SESSION['entryMode'] = $_REQUEST['entryMode'];
    
    $_SESSION['transactionId'] = $_REQUEST['transactionId'];
    
    
    if (!empty($_REQUEST['$approvalNumber'])){
        $_SESSION['$approvalNumber'] = $_REQUEST['$approvalNumber'];
    } else {
        $_SESSION['$approvalNumber'] = "";
    }
    
    
    $log = $_REQUEST['log'];
    
    
    $log = str_replace('DoubleQuotes', '"', $log); // replace double quotes with DoubleQuotes
    $log = str_replace('LessThan', '<', $log); // replace double quotes with DoubleQuotes
    $log = str_replace('GreaterThan', '>', $log); // replace double quotes with DoubleQuotes
    
    //echo "log:";
    //echo "<textarea rows=100 cols=220>";
    //echo $log;
    //echo "</textarea>";
    $_SESSION['request']->updateApiCommunication("process.php log:" . $log);
    
    if (empty($_REQUEST['error'])){
        
        
        
        $_SESSION['fglReceipt'] = '"<F9><RC600,100><RL>Frost Museum of Science\n\
                        <F2><RC550,130><RL>1101 Biscayne Blvd<F2>\n\
                        <F2><RC540,160><RL>Miami, FL 33132\n\
                        <F2><RC525,190><RL>(305)434-9600\n\
                        <F9><RC600,280><RL>' . $_SESSION['tranDate'] . '    ' . $_SESSION['tranTime'] . '\n\
                        <F9><RC600,430><RL>KSK#  : ' . $_SESSION['clerk'] . '\n\
                        <F9><RC600,460><RL>ACT # : ' . $_SESSION['accountNumber'] . '\n\
                        <F9><RC600,490><RL>EXP   : ' . $_SESSION['expirationMonth'] . '/' . $_SESSION['expirationYear'] . '\n\
                        <F9><RC600,520><RL>CARD  : ' . $_SESSION['cardLogo'] . '\n\
                        <F9><RC600,550><RL>ENTRY : ' . $_SESSION['entryMode'] . '\n\
                        <F9><RC600,610><RL>CREDIT PURCHASE    $' . $_SESSION['amount'] . '.00\n\
                        <F9><RC600,670><RL>APPROVAL CODE:     ' . $_SESSION['$approvalNumber'] . '\n\
                        <F9><RC600,730><RL>TRAN ID:       ' . $_SESSION['transactionId'] . '\n\
                        <F9><RC500,860><RL> THANK YOU\n\
                        <F9><RC525,890><RL>CARDHOLDER COPY<p>"';
        
        
        
        
        
        authorizePaymentTriPOS($_SESSION['transactionId'], $_SESSION['amount'], $_SESSION['accountNumber']);
    } else {
        errorPageSales($_REQUEST['error']);
    }
}
    

    
    
    
function authorizePayment(){
    if (!$_SESSION['requireSwipe']){
        thanks('');
    }
    
    // you must display the xml in a textarea or it will look blank (because the xml is interpretted as html tags)
    //echo "<textarea rows=20 cols=120>";
    //echo $_REQUEST['swipeData'];
    //echo "</textarea>";
    //exit();
    
    if (empty($_REQUEST['swipeData'])){
        displayCart("There was an error reading the data from your card.<br>Your card was not charged.<br>Please try the swipe again.");
    } else if ($_REQUEST['swipeData'] == 'fake failure'){
//        exit("hello made it here");
        displayCart("There was an error reading the data from your card.<br>Your card was not charged.<br>Please try the swipe again.");
    }
    
    
    
    
        
    if ($_REQUEST['swipeData'] == 'fake'){
        //$_REQUEST['swipeData'] = '<DvcMsg Ver="1.0"><Dvc App="EDGE" AppVer="2.4" DvcType="ElementPINPad" DvcSN="80059228" Entry="SWIPE" ></Dvc>
        //<Card ETrk1="147C50469CC630B91B6D3C59197D57E9711F3B9EEED6EE1CCB62809818F65F98720793864C7991A4C2814B321467758A8DC4C38049B657636AF3CC0E0452C410:FFFF987654000320002C" 
        //ETrk2="352D8B0F073EC757552B7074A344FA84602F2009D960A3D438AB88F6BBAF610FFE8DE2AC3C540A4A" 
        //CDataKSN="FFFF987654000320002C" MskPAN="4239********8145" Exp="0916" 
        //CHolder="PORTER TRAVIS" ></Card><Addr ></Addr><Tran TranType="CREDIT" ></Tran></DvcMsg>';

        // visa
        $_REQUEST['swipeData'] = '<DvcMsg Ver="1.0"><Dvc App="EDGE" AppVer="2.4" DvcType="ElementPINPad" DvcSN="20020844" Entry="SWIPE" ></Dvc>
                 <Card ETrk1="4111111111111111^SAVEREID      /CHRISTOPHER^1608101196170040000A000" ETrk2="4111111111111111=16081011961740000000" 
                CDataKSN="" MskPAN="4427********6199" Exp="0816" CHolder="SAVEREID      /CHRISTOPHER" ></Card><Addr ></Addr><Tran TranType="CREDIT" >
                </Tran></DvcMsg>';


//            // master card
//            $_REQUEST['swipeData'] = '<DvcMsg Ver="1.0"><Dvc App="EDGE" AppVer="2.4" DvcType="ElementPINPad" DvcSN="20020844" Entry="SWIPE" ></Dvc>
//                     <Card ETrk1="5555555555554444^SAVEREID      /CHRISTOPHER^1608101196170040000A000" ETrk2="5555555555554444=16081011961740000000" 
//                    CDataKSN="" MskPAN="4427********6199" Exp="0816" CHolder="SAVEREID      /CHRISTOPHER" ></Card><Addr ></Addr><Tran TranType="CREDIT" >
//                    </Tran></DvcMsg>';


//            // american express
//            $_REQUEST['swipeData'] = '<DvcMsg Ver="1.0"><Dvc App="EDGE" AppVer="2.4" DvcType="ElementPINPad" DvcSN="20020844" Entry="SWIPE" ></Dvc>
//                     <Card ETrk1="378282246310005^SAVEREID      /CHRISTOPHER^1608101196170040000A000" ETrk2="378282246310005=16081011961740000000" 
//                    CDataKSN="" MskPAN="4427********6199" Exp="0816" CHolder="SAVEREID      /CHRISTOPHER" ></Card><Addr ></Addr><Tran TranType="CREDIT" >
//                    </Tran></DvcMsg>';






    }
        
    // testing.
    //displayCart("There was an error reading the data from your card.<br>Your card was not charged.<br>Please try the swipe again.");




    $asciiString = mb_convert_encoding ($_REQUEST['swipeData'], 'US-ASCII', 'UTF-8');
    //echo "swipeData:<br><textarea rows=20 cols=100>swipeData: " . $asciiString . "</textarea><br>";
    //logString("swipeData: " . $asciiString);
    $mskPAN = "";
    $encryptedTrack1 = "";
    $encryptedCardData = "";
    $encryptedTrack2 = "";
    $cHolder = "";
    $encryptedCardData = "";
    $_SESSION['terminalSerialNumber'] = '';
    $exp = "";
    $entry = "";
    $acctnbr = "";
    $_SESSION['ccOwner'] = '';
    $_SESSION['ccMonth'] = '';
    $_SESSION['ccYear'] = '';


    $mskPANStartPos = strpos($asciiString,'MskPAN="') + 8;
    if ($mskPANStartPos > 0){
        $mskPANEndPos = strpos($asciiString,'"',$mskPANStartPos) -1;
//            echo "mskPANStartPos: " . $mskPANStartPos . "<br>";
//            echo "mskPANEndPos: " . $mskPANEndPos . "<br>";
        $mskPAN = substr($asciiString,$mskPANStartPos,$mskPANEndPos-$mskPANStartPos+1);
//            exit("mskPan: " . $mskPAN);
    } else {
        //echo "track1 data not found.";
        displayCart("There was an error(mskPan) reading the data from your card.<br>Your card was not charged.<br>Please try the swipe again.");
    }


    $encryptedTrk1StartPos = strpos($asciiString,'ETrk1="') + 7;
    if ($encryptedTrk1StartPos > 0){
        $encryptedTrk1EndPos = strpos($asciiString,':',$encryptedTrk1StartPos) -1;
        //echo "encryptedTrk1StartPos: " . $encryptedTrk1StartPos . "<br>";
        //echo "encryptedTrk1EndPos: " . $encryptedTrk1EndPos . "<br>";
        $encryptedTrack1 = substr($asciiString,$encryptedTrk1StartPos,$encryptedTrk1EndPos-$encryptedTrk1StartPos+1);
        $encryptedCardDataStartPos = $encryptedTrk1EndPos + 2;
        $encryptedCardDataEndPos = strpos($asciiString,'"',$encryptedCardDataStartPos) -1;
        $encryptedCardData = substr($asciiString,$encryptedCardDataStartPos,$encryptedCardDataEndPos-$encryptedCardDataStartPos+1);
//            echo "encryptedTrack1: " . $encryptedTrack1 . "<br>";
//            echo "encryptedCardData: " . $encryptedCardData . "<br>"; 
    } else {
        //echo "track1 data not found.";
        displayCart("There was an error(track1) reading the data from your card.<br>Your card was not charged.<br>Please try the swipe again.");
    }


    $encryptedTrk2StartPos = strpos($asciiString,'ETrk2="') + 7;
    if ($encryptedTrk2StartPos > 0){
        $encryptedTrk2EndPos = strpos($asciiString,'"',$encryptedTrk2StartPos) -1;
        //echo "encryptedTrk2StartPos: " . $encryptedTrk2StartPos . "<br>";
        //echo "encryptedTrk2EndPos: " . $encryptedTrk2EndPos . "<br>";
        $encryptedTrack2 = substr($asciiString,$encryptedTrk2StartPos,$encryptedTrk2EndPos-$encryptedTrk2StartPos+1);
        //echo "encryptedTrack2: " . $encryptedTrack2 . "<br>";
    } else {
        //echo "track1 data not found.";
        displayCart("There was an error(Track2) reading the data from your card.<br>Your card was not charged.<br>Please try the swipe again.");
    }

    $cHolderStartPos = strpos($asciiString,'CHolder="') + 9;
    if ($cHolderStartPos > 0){
        $cHolderEndPos = strpos($asciiString,'"',$cHolderStartPos) -1;
        //echo "CHolderStartPos: " . $CHolderStartPos . "<br>";
        //echo "CHolderEndPos: " . $CHolderEndPos . "<br>";
        $cHolder = substr($asciiString,$cHolderStartPos,$cHolderEndPos-$cHolderStartPos+1);
        //echo "CHolder: " . $CHolder . "<br>";
    } else {
        //echo "CHolder data not found.";
        displayCart("There was an error(CHolder) reading the data from your card.<br>Your card was not charged.<br>Please try the swipe again.");
    }

    $expStartPos = strpos($asciiString,'Exp="') + 5;
    if ($expStartPos > 0){
        $expEndPos = strpos($asciiString,'"',$expStartPos) -1;
        //echo "expStartPos: " . $expStartPos . "<br>";
        //echo "expEndPos: " . $expEndPos . "<br>";
        $exp = substr($asciiString,$expStartPos,$expEndPos-$expStartPos+1);
        $exp = substr($exp,0,2) . '20' . substr($exp,2,2);
        //exit("exp: " . $exp);// . "<br>";
    } else {
        //echo "exp data not found.";
        displayCart("There was an error(Exp) reading the data from your card.<br>Your card was not charged.<br>Please try the swipe again.");
    }

    $terminalSerialNumberStartPos = strpos($asciiString,'DvcSN="') + 7;
    if ($terminalSerialNumberStartPos > 0){
        $terminalSerialNumberEndPos = strpos($asciiString,'"',$terminalSerialNumberStartPos) -1;
        //echo "encryptedTrk2StartPos: " . $encryptedTrk2StartPos . "<br>";
        //echo "encryptedTrk2EndPos: " . $encryptedTrk2EndPos . "<br>";
        $_SESSION['terminalSerialNumber'] = substr($asciiString,$terminalSerialNumberStartPos,$terminalSerialNumberEndPos-$terminalSerialNumberStartPos+1);
        //echo "terminaSerial: " . $terminalSerialNumber . "<br>";
    } else {
        displayCart("There was an error(SerialNbr) reading the data from your card.<br>Your card was not charged.<br>Please try the swipe again.");
    }

//        $expStartPos = strpos($asciiString,'Exp="') + 5;
//        if ($expStartPos > 0){
//            $expEndPos = strpos($asciiString,'"',$expStartPos) -1;
//            //echo "encryptedTrk2StartPos: " . $encryptedTrk2StartPos . "<br>";
//            //echo "encryptedTrk2EndPos: " . $encryptedTrk2EndPos . "<br>";
//            $_SESSION['exp'] = substr($asciiString,$expStartPos,$expEndPos-$expStartPos+1);
//            //echo "exp: " . $exp . "<br>";
//        } else {
//            //echo "serialNumber data not found.";
//            generalError();
//        }

    $entryStartPos = strpos($asciiString,'Entry="') + 7;
    if ($entryStartPos > 0){
        $entryEndPos = strpos($asciiString,'"',$entryStartPos) -1;
        //echo "encryptedTrk2StartPos: " . $encryptedTrk2StartPos . "<br>";
        //echo "encryptedTrk2EndPos: " . $encryptedTrk2EndPos . "<br>";
        $_SESSION['entry'] = substr($asciiString,$entryStartPos,$entryEndPos-$entryStartPos+1);
        //echo "entry: " . $entry . "<br>";
    } else {
        displayCart("There was an error(entry) reading the data from your card.<br>Your card was not charged.<br>Please try the swipe again.");
    }




    //csn added ccNumber variable to get unencrypted number 20160504
    $ETrk2Values = explode("=", $encryptedTrack2);
    $_SESSION['ccNumber'] = $ETrk2Values[0];
    //exit('ccNumber: ' . $_SESSION['ccNumber']);

    //csn added to get first and last name of cardholder 20160505

    if (!empty($cHolder)) {
        $cHolderValues = explode("/", $cHolder);
        $_SESSION['ccLastName'] = trim($cHolderValues[0]);
        $_SESSION['ccFirstName'] = trim($cHolderValues[1]);
        //exit('<textarea>ccFirstName:  |' . $_SESSION['ccFirstName'] . '|</textarea>');
        //$_SESSION['ccOwner'] = trim($_SESSION['ccFirstName'] . " " . $_SESSION['ccLastName']);
        $_SESSION['ccOwner'] = trim($_SESSION['ccFirstName'] . " " . $_SESSION['ccLastName']);
        //exit('ccOwner: ' . $_SESSION['ccOwner']);
    } else {
        $_SESSION['ccOwner'] = 'Kiosk User';
    }


    $_SESSION['expMonth'] = substr($exp,0,2);
    $_SESSION['expYear'] = substr($exp,2,4);

    //exit('expYear: ' . $_SESSION['expYear']);
//        $startWith = substr($_SESSION['ccNumber'],0,2);
//        //exit('startWith: ' . $startWith);
//        
//        if ($startWith >= '40' && $startWith <= '49') {
//            $ccType = '33';
//        } else if ($startWith >= '51' && $startWith <= '55') {   
//            $ccType = '34';
//        } else if ($startWith == '34' || $startWith == '37') {   
//            $ccType = '35';
//        }
//        
//        exit('startWith ' . $startWith);
    //displayCart("Made it this far.");





    //TODO: GET OTHER FIELDS





    if ($encryptedTrack2 > "" && $encryptedCardData > ""){
        // use encryptedtrack2 to process payment.
        $ccData = '';
        $test = "Visa Swiped (EncryptedTrack2Data)";
        $ccData['encryptedTrack1Data'] = '';
        $ccData['cardDataKeySerialNumber'] = $encryptedCardData;
        //$ccData['mskPAN'] = $mskPAN;
        $ccData['encryptedTrack2Data'] = $encryptedTrack2; // travis' encrypted card
        $ccData['amount'] = $_SESSION['orderTotal'];
        $ccData['billingZipCode'] = '72901';
        //$ccData['terminalId'] = 'kiosk_test3';
        $ccData['terminalId'] = $_SESSION['kiosk'];
        // -------------- DO NOT DELETE ---------- THIS IS FOR 3RD PARTY AUTHORIZATIONS -------------
//            $returnList = saleElement($ccData);
//            $approved = $returnList[0];
//            $orderId = $returnList[1];
//            $errorMessage = $returnList[2];
        // ============================================================================================



        $approved = true;
        $orderId = date('YmdHis');
        $errorMessage = "";


        //showData($test,$ccData, $returnList);

        if ($_SESSION['ccNumber'] > ''){

            $_SESSION['tranDate'] = date('F j, Y');
            $_SESSION['tranTime'] = date('g:iA');
            $_SESSION['clerk'] = $_SESSION['kiosk'];
            $_SESSION['login'] = $_SESSION['loginEmail'];
            $_SESSION['exp'] = $_SESSION['expMonth'] . "/" . $_SESSION['expYear'];
            $_SESSION['entry'] = "SWIPE";




            thanks($mskPAN);
        } else {
//                echo "<textarea rows=20 cols=120>";
//                echo $_REQUEST['swipeData'];
//                echo "</textarea>";
//                exit();
            displayCart("There was a problem authorizing that card.<br>" . $errorMessage . ".<br>Your card was not charged.<br>Please try the swipe again.");
        }

    } else {
        displayCart("There was an error(last) reading the data from your card.<br>Your card was not charged.<br>Please try the swipe again.");
    }




    //???
    //If $approved = False go to error page
    //If $approved = True go to Thank You page.
    //if ($returnList[0] == true){
    //    thanks();
    //} else {
    //    generalError();
    //}
        
         
        
        
        
        
    
}
    
function authorizePaymentTriPOS($transId, $amount, $ccNumber){
//    echo "<pre>";
//    print_r($_REQUEST);
//    echo "</pre>";
//    exit("here");
    
    $data['transId'] = $transId;
    $data['amount'] = $amount;
    $data['ccNumber'] = $ccNumber;
    
    
    
    $checkedOut = checkoutEx4TriPOS($data);  // should return a true or a "1" if checked out.
//    exit("checkedOut = " . $checkedOut);
    if ($checkedOut === false){
        errorPageSales("ERROR CHECKING OUT. PLEASE SEE VISITOR'S SERVICES. <br>YOUR CREDIT CARD CHARGES WILL NEED TO BE REFUNDED.");
    } else {
        lookupByConfirmation($_SESSION['orderNo'], 'sales');
    }
}
    
function logOutAndBackIn(){
       
    if (empty($_SESSION['logInMethod'])){
        exit('logInMethod is empty');
    }
    logOut();
    getNewSessionKey();
    if ($_SESSION['logInMethod'] == 'loginUsingEmail'){
        //exit('here');
        //loginUsingEmail('KioskAnonymousUser@mfa.org');
        loginUsingEmail($_SESSION['loginEmail']);

        //$_SESSION['suppressMembershipPricing'] = true;
         $loggedIn = loggedIn();
         if ($loggedIn == 'false'){
             errorPageSales("Unable to continue with this login. Please start over. If you continue to have trouble, see Visitor's Services");
         } 
         $_SESSION['loggedInTable'] = getLoggedInTable();
    } // TODO: ALLOW FOR OTHER METHODS OF LOGINS.
    $modeOfSale = changeModeOfSaleEx($_SESSION['modeOfSale']);
    if ($modeOfSale <> 9){
    $_SESSION['request']->updateApiCommunication("Groups: Change mode of sale is " . print_r($modeOfSale,true));
    errorPageSales("MODE OF SALE IS NOT 9.");
    }
}

function displayCartNew($errorMessage){
    
//    echo "<pre>";
//    print_r($_SESSION);
//    echo "</pre>";
//    exit();
    
    
    
    $errorDiv = '';
    if (strlen($errorMessage) > 1){
        $errorDiv = '<div id="errorDiv">
                <div class="error">
                    
                    <table bgcolor="#ffffff" cellspacing=0 cellpadding=0 class="errorTable">
                        <tr>
                            <td width=20></td>
                            <td ><img id="error" src="images/error.jpg" width=150></td>
                            <td width=20></td>
                            <td width ="500" height="250" valign=top><br><font class="errorTitle">ERROR!</font><br><font class="errorMessage">' .  $entitlementsTextarea . '</font></td>
                            <td width=20></td>
                        </tr>
                        <tr>
                            <td colspan=3></td>
                            <td>
                                <table width=100%>
                                    <tr>
                                        <td width=50%></td>
                                        <td width=50% align=right class="errorOKTd" onclick="this.className=\'errorOKTd_clicked\';document.getElementById(\'errorDiv\').style.visibility=\'hidden\';">OK</td>
                                    </tr>
                                </table>
                            </td>   
                            <td width=20></td>
                        </tr>
                    </table>
                    </textarea>
                </div>
            </div>';
    }
    
    
    
    $postingDiv =  '<div id="postingDiv">
                        <div class="posting">
                            <table cellspacing=0 cellpadding=0 style="border: 4px solid white;">
                                <tr>
                                    <td align="center" bgcolor="#ffffff" width ="500" height="250"><h1>Please wait...</h1><br><img id="timer" src="http://galileo.ewwl2.com/ECommerce/Tessitura/15864/images/Loading-Animation.gif"></td>
                                </tr>
                            </table>
                        </div>
                    </div>';
    
    
    
    
    
    $cartObject = getCart(); 
    //$cartObject = getCartFromFile();
//    echo "<textarea rows=20 cols=120>";
//    print_r($cartObject);
//    echo "</textarea>";
//    exit;
    $cartResults = $cartObject;
    $orderNo = $cartResults->{'Order'}->order_no;
    $_SESSION['orderNo'] = trim($orderNo);
    
    
    $priceTypes = array();
    $priceTypeDescriptions = array();
    $priceTypeQtys = array();
    $priceTypePrices = array();
    
    $idx = 0;
    foreach ($cartObject->{'PriceType'} as $oneCartObjectPriceType){
        
        $priceTypes[$idx] = (string) $oneCartObjectPriceType->price_type;
        //$priceTypeDescriptions[$idx] = (string) $oneCartObjectPriceType->description;
        $priceTypeDescriptions[$idx] = (string) str_replace('Admission', '', $oneCartObjectPriceType->description);
        $priceTypeQtys[$idx] = (string) 0;
        $priceTypePrices[$idx] = '0.00';
        $idx ++;
    }
    $idx = 0;
    foreach ($priceTypes as $onePriceType){
        foreach ($cartObject->{'SubLineItem'} as $oneCartObjectSubLineItem){
            if ($oneCartObjectSubLineItem->zone_desc == "General Admission"){
                if ($oneCartObjectSubLineItem->price_type == $onePriceType){
                
                    $priceTypeQtys[$idx] ++;
                    //$priceTypePrices[$idx] += $oneCartObjectSubLineItem->due_amount;
                    $priceTypePrices[$idx] += (string) $oneCartObjectSubLineItem->due_amt;
                }
            }
        }
        $idx ++;
    }
//    exit();
        
        
        $seatingDescription = $_SESSION['pricingGroup'];
//        exit($seatingDescription . ">kdkdk");
        //$seatingDescription = 'Museum Admission'; // csn testing only
        
        $idx = 0;
        $qtyTotal = 0;
        $priceTotal = 0.00;
        $seatingTR = '';
        foreach ($priceTypes as $onePriceType){
//            echo "<pre>";
//            print_r($onePriceType);
//            echo "</pre>";
//            echo "oneLineItem: " . $oneLineItem->li_seq_no . "<br>";
//            echo "oneSubLineItem: " . $oneSubLineItem->li_seq_no . "<br>";
//
//            echo "<textarea rows=20 cols=120>";
//            print_r($oneSubLineItem);
//            echo "</textarea><br>";
            if ($seatingDescription == 'Museum Admission'){
                $seatingTR .= '
                                    <tr height="74px" class="seatingTR">
                                        <td width="235px" style="border-top:1px solid #acacac;">&nbsp</td>
                                        <td align="left" width="350px" style="border-top:1px solid #acacac;border-right:1px solid #acacac;padding-left: 275px"><font class="prompt1">' . $priceTypeDescriptions[$idx] . '</font></td>
                                        <td align="center" width="350px" style="border-top:1px solid #acacac;border-right:1px solid #acacac;"><font class="prompt1">' . $priceTypeQtys[$idx] . '</font></td>
                                        <td align="center" width="350px" style="border-top:1px solid #acacac;"><font class="prompt1"> $' . sprintf("%.2f",$priceTypePrices[$idx]) . '<font class="prompt1"></td>
                                        <td width="235px" style="border-top:1px solid #acacac;">&nbsp</td>
                                        <!--<td width=10% align=right>$' . '$00.' . '</td>-->
                                    </tr>';
            } else {  //performances
                $seatingTR .= '
                                    <tr height="94px" class="seatingTR">
                                        <td width="160px" style="border-top:1px solid #acacac;">&nbsp</td>
                                        <td align="right" width="400px" style="border-top:1px solid #acacac;border-right:1px solid #acacac;padding-right: 20px"><font class="prompt1">' . $priceTypeDescriptions[$idx] . '</font></td>
                                        <td align="center" width="400px" style="border-top:1px solid #acacac;border-right:1px solid #acacac;"><font class="prompt1">' . 'General Admission' . '</font></td>
                                        <td align="center" width="400px" style="border-top:1px solid #acacac;border-right:1px solid #acacac;"><font class="prompt1">' . $priceTypeQtys[$idx] . '</font></td>
                                        <td align="center" width="400px" style="border-top:1px solid #acacac;"><font class="prompt1"> $' . sprintf("%.2f",$priceTypePrices[$idx]) . '<font class="prompt1"></td>
                                        <td width="160px" style="border-top:1px solid #acacac;">&nbsp</td>
                                        <!--<td width=10% align=right>$' . '$00.' . '</td>-->
                                    </tr>';
            }

            $qtyTotal += $priceTypeQtys[$idx]; 
            $priceTotal += $priceTypePrices[$idx];
            $idx++;
        }
        
        
        
        //$cartTable .= $seatingTable;
        
        if ($seatingDescription == 'Museum Admission'){
            $seatingHeadingTR = '
                                    <tr height="80px" class="displayHeadings">
                                        <td width="235px" style="border-top:1px solid #acacac;border-left:1px solid #acacac;">&nbsp</td>
                                        <td align="center" width="350px" style="border-top:1px solid #acacac;border-right:1px solid #acacac;padding-right: 50px" "><font style="font-size:30pt">TYPE</font></td>
                                        <td align="center" width="350px" style="border-top:1px solid #acacac;border-right:1px solid #acacac;"><font style="font-size:30pt">QUANTITY</font></td>
                                        <td align="center" width="350px" style="border-top:1px solid #acacac;">&nbsp</td>
                                        <td width="235px" style="border-top:1px solid #acacac;">&nbsp</td>
                                    </tr>';
            $totalTR = '
                                    <tr height="120px" class="totalTR">
                                        <td width="235px" style="border-top:1px solid #acacac;border-bottom:1px solid #acacac;">&nbsp</td>
                                        <td align="right" width="350px" style="border-top:1px solid #acacac;border-bottom:1px solid #acacac;padding-right: 100px">Total</td>
                                        <td align="center" width="350px" style="border-top:1px solid #acacac;border-bottom:1px solid #acacac;"><font class="prompt2">' . $qtyTotal . '</font></td>
                                        <td align="center" width="350px" style="border-top:1px solid #acacac;border-bottom:1px solid #acacac;"><font style="color:#d91e18" class="prompt2"> $' . sprintf("%.2f",$priceTotal) . '</font></td>
                                        <td width="235px" style="border-top:1px solid #acacac;border-bottom:1px solid #acacac;">&nbsp</td>
                                    </tr>';
        } else { //performances
            $seatingHeadingTR = ' 
                                    <tr height="80px" class="displayHeadings">
                                        <td width="160px" style="border-top:1px solid #acacac;border-left:1px solid #acacac;">&nbsp</td>
                                        <td align="center" width="400px" style="border-top:1px solid #acacac;border-right:1px solid #acacac;padding-right: 50px">TYPE</td>
                                        <td align="center" width="400px" style="border-top:1px solid #acacac;border-right:1px solid #acacac;padding-right: 160px">SEATING</td>
                                        <td align="center" width="400px" style="border-top:1px solid #acacac;border-right:1px solid #acacac;">QUANTITY</td>
                                        <td align="center" width="400px" style="border-top:1px solid #acacac;">&nbsp</td>
                                        <td width="160px" style="border-top:1px solid #acacac;">&nbsp</td>
                                    </tr>';
            $totalTR = '
                                    <tr height="120px" class="totalTR">
                                        <td colspan=2 width="160px" style="border-top:1px solid #acacac;border-bottom:1px solid #acacac;">&nbsp</td>
                                        <td align="right" width="400px" style="border-top:1px solid #acacac;border-bottom:1px solid #acacac;padding-right: 100px">Total</td>
                                        <td align="center" width="400px" style="border-top:1px solid #acacac;border-bottom:1px solid #acacac;">' . $qtyTotal . '</td>
                                        <td align="center" width="400px" style="border-top:1px solid #acacac;border-bottom:1px solid #acacac;"><font style="color:#d91e18"> $' . sprintf("%.2f",$priceTotal) . '</font></td>
                                        <td width="160px" style="border-top:1px solid #acacac;border-bottom:1px solid #acacac;">&nbsp</td>
                                    </tr>';
                    
        }
        
        
   $printTicketsButton = '';   
   if ($priceTotal == 0){
       
       
       $printTicketsButton = '
           <tr><td height=100></td></tr>
                <tr>
                    <td colspan=6 align=center>
                        <table width=350>
                            <tr>
                                <td id="print" style="visibility:visible;" valign=middle class="button2" 
                                    onclick="authorizePayment();" 
                                    valign=bottom>
                                    Print Tickets
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>';
       $_SESSION['requireSwipe'] = false;
       //$reviewHeading = 'Please review your selections. Click button below to print ticket(s).';
       $reviewHeading = '';
       
       // 2016-09-13 - skip displaying the cart if the value of the cart is $0... just move onto printing the tickets.
       $_REQUEST['task'] = 'authorizePayment';
       authorizePayment();
       
       
       
   } else {
       $printTicketsButton = '';
       $_SESSION['requireSwipe'] = true;
       $reviewHeading = 'Please review your selections. Press continue to complete your transaction.';
   }    
   
    $cartTable = '
                    <table width="1920" cellspacing=0 cellpadding=0>
                        ' . $seatingHeadingTR . '
                        ' . $seatingTR . '
                        ' . $totalTR . '
                        ' . $printTicketsButton . '
                    </table>';
        
    
    
    //exit("cartTable: <br>" . $cartTable);
    
    $_SESSION['title'] = 'displayCart';
    
    $_SESSION['javaScript'] = '
        
    
        <script language="javascript">

            function postMyDisplayCartForm() {
                document.getElementById("timerDiv").style.visibility="visible";
                setTimeout(function() {
                    document.getElementById("timerDiv").style.visibility="visible";
                    document.myDisplayCartForm.submit();
                    return true;
                },250);
            }
            
//            function postMyDisplayCartForm() {
//                document.getElementById("timerDiv").style.visibility="hidden";
//                document.myDisplayCartForm.submit();
//            }
            function authorizePayment() {
                document.myDisplayCartForm.task.value = "authorizePayment";
                document.myDisplayCartForm.submit();
            }

        </script>';
    

       $_SESSION['topTable'] = '
           

                <script>
                    document.addEventListener("keypress", keyPressSwipe, false);
                    var textEntered = "";

                    var startKey = "[";
                    var endKey = "]";
                    var startKeyUnicode = 91;
                    var endKeyUnicode = 93;
                    
                    var collecting = false;

                    function keyPressSwipe(e) {
                        var unicode=e.charCode? e.charCode : e.keyCode;
                        var actualKey=String.fromCharCode(unicode);
                        if (unicode == startKeyUnicode){
                            textEntered = "";
                            collecting = true;
                            document.getElementById("back").style.visibility="hidden";
                            document.getElementById("exit").style.visibility="hidden";
                        } else if (unicode == endKeyUnicode) {
                                document.myDisplayCartForm.swipeData.value = textEntered;
                                collecting = false;
                                postMyDisplayCartForm();
                         } else {
                            textEntered += actualKey;
                        }
                    }
                    
                    function postTickets(price){
                        setTimeout(function() {
//                            document.getElementById("postingDiv").style.visibility="visible";
                            document.getElementById("timerDiv").style.visibility="visible";                         ' . "\n" . '
                            document.getElementById("processingMessage").innerHTML = "Please swipe card below...";
                            location.href="http://localhost/FrostLocal/process.php?kiosk=' . $_SESSION['kiosk'] . '&price="  + price + "&startUrl=' . urlencode($_SESSION['startUrl']) . '";
                        },500);
                    }
                    
                    function fakeFailureSubmit(){
                        document.myDisplayCartForm.swipeData.value="fake failure";
                        postMyDisplayCartForm();
                    }
                    
                    function fakeSubmit(){
                        document.myDisplayCartForm.swipeData.value="fake";
                        document.getElementById("timerDiv").style.visibility="hidden";
                        postMyDisplayCartForm();
                    }

            </script>
            
            <form name="myDisplayCartForm" action="" method="post">
                <!-- input id="task" name="task" type="hidden" value="authorizePayment" -->
                <input id="swipeData" name="swipeData" type="hidden" value="">

                <center>
                    <table>
                        <tr height="100px">
                            <td>
                                <center><font class="prompt2">' . $seatingDescription . '<br></font></center>
                                <!--<center><font class="prompt1">Please review your selections. Insert your credit card below to complete your transaction.</font></center> -->
                                <center><font class="prompt1">' . $reviewHeading . '</font></center>
                                <!--ORDER # ' . $_SESSION['orderNo'] . '<br><br></center>-->
                            </td>
                        </tr>
                    </table>
                    <br>
                    ' . $cartTable . '
                        <div style="position:absolute;height:70px;top:600px;width:1920px;display:table;">
                            <table id="continueTable" width=100%>
                                <tr height="65px">
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td id="continue" valign=middle class="button1"
                                        onMouseOut="document.getElementById(\'continue\').style.backgroundImage = \'url(images/redBar.png)\';"
                                        onclick="postTickets(' . sprintf("%.2f",$priceTotal) . ');" valign=bottom>
                                        Continue
                                    </td>
                                    <td></td>
                                </tr>
                            </table>
                        </div>
                     
                </center>
            </form>
                ' . $errorDiv . '
                ' . $postingDiv;
//                    <tr>
//                        <td valign=top>
//                            <table height=600 width=500>
//                                    <tr><td valign=top align=center class="labels">Swipe Credit Card</td></tr>
//                                    <tr><td height=50></td></tr>
//                                    <tr><td valign=top align=center class="labels">Insert your credit card<br>to purchase ticket(s)</td></tr>
//                                    <tr><td height=50></td></tr>
//                                        <!--//change fakeSubmit() to fakeFailureSubmit() to test card error -->
//                                    <tr><td align=center><img src="CreditCardAnimation.gif" height="220px" onclick="fakeSubmit();"></td></tr>
//                                    <tr><td height=50></td></tr>
//                             </table>
//                       </td>
//                    </tr>
            
            
          //$_SESSION['navigationTable'] = '<table><tr><td></td></tr></table>';
       
       
       
       
           $_SESSION['navigationTable'] =  '<center>
                <table width=100%>
                <tr>
                    <td width="10px">&nbsp;
                    <td  align="left" width="500px">
                        <img id="creditCards" src="images/AMX_MC_VISA.png">
                    </td>
                    <td  align="center" width="500px" class="groupsLabels2">
                        <center>
                            To edit your selection, tap <b>Back</b>.
                        </center>
                    </td>
                    <td width="500px">&nbsp;
                    <td width="10px">&nbsp;
                    </td>
                </tr>
                </table></center>' ;      
          

    $_SESSION['request']->updateApiCommunication("Review cart screen now showing" . "\n");
    printHtmlStuffSales();
    
}     

function displayCartOriginal($errorMessage){
    
    $errorDiv = '';
    if (strlen($errorMessage) > 1){
        $errorDiv = '<div id="errorDiv">
                <div class="error">
                    
                    <table bgcolor="#ffffff" cellspacing=0 cellpadding=0 class="errorTable">
                        <tr>
                            <td width=20></td>
                            <td><img id="error" src="images/error.jpg" width=150></td>
                            <td width=20></td>
                            <td width ="500" height="250" valign=top><br><font class="errorTitle">ERROR!</font><br><font class="errorMessage">' .  $entitlementsTextarea . '</font></td>
                            <td width=20></td>
                        </tr>
                        <tr>
                            <td colspan=3></td>
                            <td>
                                <table width=100%>
                                    <tr>
                                        <td width=50%></td>
                                        <td width=50% align=right class="errorOKTd" onclick="this.className=\'errorOKTd_clicked\';document.getElementById(\'errorDiv\').style.visibility=\'hidden\';">OK</td>
                                    </tr>
                                </table>
                            </td>   
                            <td width=20></td>
                        </tr>
                    </table>
                    </textarea>
                </div>
            </div>';
    }
    
    $cartObject = getCart(); 
//    echo "<textarea rows=20 cols=120>";
//    print_r($cartObject);
//    echo "</textarea>";
//    exit;
    

    //exit;
    /*
     * 
     * SimpleXMLElement Object
(
    [GetCartResults] => SimpleXMLElement Object
        (
            [Order] => SimpleXMLElement Object
                (
                    [sessionkey] => N65KUBGPDWD3DD0B30C4OAHBFF9IKK94DD1161RMMS65CEUT7HGGM4MKRU5M7168
                    [order_no] => 280717
                    [appeal_no] => 10
                    [source_no] => 25
                    [customer_no] => 1281884
                    [solicitor] => webAPI  
                    [MOS] => 7
                    [order_dt] => 2015-12-29T15:46:34.043-05:00
                    [order_total] => 121.0000
                    [order_value] => 121.0000
                    [db_status] => 1
                    [amt_to_charge] => 121.0000
                    [first_seat_added_dt] => 2015-12-29T15:46:34.043-05:00
                    [amt_paid_to_dt] => 0.0000
                    [amt_paid_now] => 0.0000
                    [balance_to_charge] => 121.0000
                    [SubTotal] => 121.0000
                    [HandlingCharges] => 0.0000
                )

            [LineItem] => Array
                (
                    [0] => SimpleXMLElement Object
                        (
                            [li_seq_no] => 355515
                            [li_no] => 0
                            [order_no] => 280717
                            [pkg_no] => 0
                            [perf_no] => 2456
                            [priority] => 0
                            [zone_no] => 54
                            [alt_upgrd_ind] => SimpleXMLElement Object
                                (
                                )

                            [primary_ind] => Y
                            [perf_code] => M160102P01
                            [perf_dt] => 2016-01-02T10:00:00-05:00
                            [perf_desc] => Museum Admission
                            [pkg_code] => SimpleXMLElement Object
                                (
                                )

                            [pkg_desc] => SimpleXMLElement Object
                                (
                                )

                            [facility_desc] => Museum of Fine Arts, Boston
                            [db_status] => 1
                            [zmap_no] => 32
                            [prod_type] => 6
                            [prod_type_desc] => MFA Admission Paid
                            [season_no] => 18
                            [season_desc] => FY16 Museum Admission
                            [ga_ind] => Y
                            [facil_no] => 20
                            [zone_no_int] => 54
                            [line_source_no] => 25
                        )

                    [1] => SimpleXMLElement Object
                        (
                            [li_seq_no] => 355516
                            [li_no] => 0
                            [order_no] => 280717
                            [pkg_no] => 0
                            [perf_no] => 2456
                            [priority] => 0
                            [zone_no] => 54
                            [alt_upgrd_ind] => SimpleXMLElement Object
                                (
                                )

                            [primary_ind] => Y
                            [perf_code] => M160102P01
                            [perf_dt] => 2016-01-02T10:00:00-05:00
                            [perf_desc] => Museum Admission
                            [pkg_code] => SimpleXMLElement Object
                                (
                                )

                            [pkg_desc] => SimpleXMLElement Object
                                (
                                )

                            [facility_desc] => Museum of Fine Arts, Boston
                            [db_status] => 1
                            [zmap_no] => 32
                            [prod_type] => 6
                            [prod_type_desc] => MFA Admission Paid
                            [season_no] => 18
                            [season_desc] => FY16 Museum Admission
                            [ga_ind] => Y
                            [facil_no] => 20
                            [zone_no_int] => 54
                            [line_source_no] => 25
                        )

                )

            [SubLineItem] => Array
                (
                    [0] => SimpleXMLElement Object
                        (
                            [sli_no] => 837606
                            [li_seq_no] => 355515
                            [due_amt] => 25.0000
                            [paid_amt] => 0.0000
                            [price_type] => 70
                            [seat_no] => 90791
                            [perf_no] => 2456
                            [pkg_no] => 0
                            [zone_no] => 54
                            [order_no] => 280717
                            [seat_row] => 1
                            [seat_num] => 11
                            [zone_desc] => 5000 Capacity
                            [section_desc] => Museum Admission
                            [section_short_desc] => Mus Adm
                            [section_print_desc] => MFA
                            [db_status] => 1
                            [prod_season_no] => 922
                            [facility_no] => 20
                            [seat_type] => 1
                            [seat_type_desc] => Regular
                            [zone_no_int] => 54
                        )

                    [1] => SimpleXMLElement Object
                        (
                            [sli_no] => 837607
                            [li_seq_no] => 355515
                            [due_amt] => 25.0000
                            [paid_amt] => 0.0000
                            [price_type] => 70
                            [seat_no] => 90792
                            [perf_no] => 2456
                            [pkg_no] => 0
                            [zone_no] => 54
                            [order_no] => 280717
                            [seat_row] => 1
                            [seat_num] => 12
                            [zone_desc] => 5000 Capacity
                            [section_desc] => Museum Admission
                            [section_short_desc] => Mus Adm
                            [section_print_desc] => MFA
                            [db_status] => 1
                            [prod_season_no] => 922
                            [facility_no] => 20
                            [seat_type] => 1
                            [seat_type_desc] => Regular
                            [zone_no_int] => 54
                        )

                    [2] => SimpleXMLElement Object
                        (
                            [sli_no] => 837608
                            [li_seq_no] => 355515
                            [due_amt] => 25.0000
                            [paid_amt] => 0.0000
                            [price_type] => 70
                            [seat_no] => 90793
                            [perf_no] => 2456
                            [pkg_no] => 0
                            [zone_no] => 54
                            [order_no] => 280717
                            [seat_row] => 1
                            [seat_num] => 13
                            [zone_desc] => 5000 Capacity
                            [section_desc] => Museum Admission
                            [section_short_desc] => Mus Adm
                            [section_print_desc] => MFA
                            [db_status] => 1
                            [prod_season_no] => 922
                            [facility_no] => 20
                            [seat_type] => 1
                            [seat_type_desc] => Regular
                            [zone_no_int] => 54
                        )

                    [3] => SimpleXMLElement Object
                        (
                            [sli_no] => 837609
                            [li_seq_no] => 355516
                            [due_amt] => 23.0000
                            [paid_amt] => 0.0000
                            [price_type] => 71
                            [seat_no] => 90794
                            [perf_no] => 2456
                            [pkg_no] => 0
                            [zone_no] => 54
                            [order_no] => 280717
                            [seat_row] => 1
                            [seat_num] => 14
                            [zone_desc] => 5000 Capacity
                            [section_desc] => Museum Admission
                            [section_short_desc] => Mus Adm
                            [section_print_desc] => MFA
                            [db_status] => 1
                            [prod_season_no] => 922
                            [facility_no] => 20
                            [seat_type] => 1
                            [seat_type_desc] => Regular
                            [zone_no_int] => 54
                        )

                    [4] => SimpleXMLElement Object
                        (
                            [sli_no] => 837610
                            [li_seq_no] => 355516
                            [due_amt] => 23.0000
                            [paid_amt] => 0.0000
                            [price_type] => 71
                            [seat_no] => 90795
                            [perf_no] => 2456
                            [pkg_no] => 0
                            [zone_no] => 54
                            [order_no] => 280717
                            [seat_row] => 1
                            [seat_num] => 15
                            [zone_desc] => 5000 Capacity
                            [section_desc] => Museum Admission
                            [section_short_desc] => Mus Adm
                            [section_print_desc] => MFA
                            [db_status] => 1
                            [prod_season_no] => 922
                            [facility_no] => 20
                            [seat_type] => 1
                            [seat_type_desc] => Regular
                            [zone_no_int] => 54
                        )

                )

            [PriceType] => Array
                (
                    [0] => SimpleXMLElement Object
                        (
                            [price_type] => 70
                            [description] => Adult Admission
                            [short_desc] => ADU
                            [category] => Paid Adult
                            [def_price_type] => Y
                        )

                    [1] => SimpleXMLElement Object
                        (
                            [price_type] => 71
                            [description] => Senior Admission
                            [short_desc] => SEN
                            [category] => Paid Senior
                            [def_price_type] => N
                        )

                )

        )

)



     * 
     */
    
    
    
    $cartTable = '
             <form name="myDisplayCartForm" action="" method="post">
                <input id="task" name="task" type="hidden" value="authorizePayment">
                <input id="swipeData" name="swipeData" type="hidden" value="">
            <table width="100%">' . "\n";
   
    
    
   
   
   
   
   //$cartResults = $cartObject->GetCartResults;  //csn this is for the GetCartOld method
   $cartResults = $cartObject;
//   echo "<textarea rows=40 cols=120>";
//   print_r($cartResults);
//   echo "</textarea><br>";
//   exit();
   
   
   //$lineItem = $cartResults->LineItem[0];
   //echo "<textarea rows=40 cols=120>";
   //print_r($lineItem);
   //echo "</textarea><br>";
   
   
   
   $orderNo = $cartResults->{'Order'}->order_no;
//   exit ('orderNo: ' . $orderNo);
   $_SESSION['orderNo'] = trim($orderNo);
   
   $_SESSION['orderTotal'] = sprintf("%.2f",trim($cartResults->{'Order'}->order_total));
   
   
   //getOrderDetails();  //csn 20160616 to confirm update of location in Tessitura
   
    
   if ($_SESSION['suppressMembershipPricing'] == false) {
   
   
       // loop through all prices selected, and if any of them are part of that group, then we check entitlements.
       // atLeastOneMemberPriceGroupSelected =false;
       // loop through all prices selected
       // if any is found, turn it to true.
       
       
       
       // if atLeastOneMemberPriceGroupSelected == true
       //     check Entitlements
       
       
       
       if ($_SESSION['checkEntitlements'] == true){
           //exit('going to check entitlements');
            $entitlementObject = checkEntitlements();    
             $entitlementsOK = true;
             $entitlementsString = "";
             foreach ($entitlementObject as $oneEntitlement){
                 //echo "message: " . $oneEntitlement->message . "<br>"; 
                 //*** Accept only the values below: ***
                 if (trim($oneEntitlement->message) != 'Not an entitlement.  Nothing to do.' && 
                     trim($oneEntitlement->message) != 'Membership entitlement applied to your cart.'){ //need to test this when entitlements are applied 
                     $errorMessage = trim($oneEntitlement->message);
                     $entitlementsString .= $errorMessage . "\n";
                     $entitlementsOK = false;
                 }
             }
             if ($entitlementsOK === false){
         //        //clearCart();
         //    echo "<textarea rows=20 cols=120>";
         //    print_r($entitlementsString);
         //    echo "</textarea>";
         //    exit;
                 $entitlementsTextarea = "<textarea rows=10 cols=40>" . $entitlementsString. "</textarea>";
                 prices($entitlementsTextarea);
             }
       
       } else {
            $_SESSION['request']->updateApiCommunication("Entitlement check was skipped. NO MEMBER TICKETS FOUND." . "\n");
           // do nothing
       }
        
        
    

   }
    
//    $entitlementObject = checkEntitlements(); 
////    echo "<textarea rows=20 cols=120>";
////    print_r($entitlementObject);
////    echo "</textarea>";
////    exit;
//    $entitlementsOK = true;
//    foreach ($entitlementObject as $oneEntitlement){
//        //echo "message: " . $oneEntitlement->message . "<br>"; 
//        if (trim($oneEntitlement->message) == 'You have exceeded your entitlements for at least one ticket in your cart.'){
//            $errorMessage = trim($oneEntitlement->message);
//            $entitlementsOK = false;
//        }
//    }
//    if ($entitlementsOK === false){
//        //clearCart();
//        prices($errorMessage);
//    }    
   
    
   $_SESSION['request']->updateApiCommunication("JA about to analyze cart result" . "\n");
   $lineItems = 0;
   foreach ($cartResults->{'LineItem'} as $oneLineItem){
       
        $cartTable .= '<tr><td><hr></td></tr>' . "\n";
        $cartTable .= '<tr>' . "\n";
        $cartTable .= '<td class="displayCartLabel">' . $oneLineItem->perf_desc . '</td>' . "\n";
        $cartTable .= '</tr>' . "\n\n\n";    
        $lineItems++;
        

//        echo "<textarea rows=90 cols=120>";
//        print_r($cartResults);
//        echo "</textarea><br>";
//        exit;
        
        
        



        // get all that belong to this line item.
        $seatingTable = '<table width=100%>
                <tr><td>&nbsp;</td><td colspan=3 align=center class="displayCartSeatingTitle">Seating</td></tr>
                
                
                ';
        foreach ($cartResults->{'SubLineItem'} as $oneSubLineItem){
            //echo "oneLineItem: " . $oneLineItem->li_seq_no . "<br>";
            //echo "oneSubLineItem: " . $oneSubLineItem->li_seq_no . "<br>";
            
            //echo "<textarea rows=20 cols=120>";
            //print_r($oneSubLineItem);
            //echo "</textarea><br>";
            
            
            if (trim($oneSubLineItem->li_seq_no) == trim($oneLineItem->li_seq_no)){
                //echo "found one.";
                
                // search for this price type.
                $priceTypeDescription = 'Price Type Not Found';
                foreach ($cartResults->{'PriceType'} as $onePriceType){
                if (trim($oneSubLineItem->price_type) == trim($onePriceType->price_type)){
                        $priceTypeDescription = $onePriceType->description;
                    }
                }
                
                $seatingDescription = 'Gen Adm';
                
                
                $seatingTable .= '<tr>
                                    <td width=50%>&nbsp;</td>
                                    <td width=30%>' . $priceTypeDescription . '</td>
                                    <td width=10%>' . $seatingDescription . '</td>    
                                    <td width=10% align=right>$' . sprintf("%.2f",$oneSubLineItem->due_amt) . '</td>
                                  </tr>';
            } 
        }
        $seatingTable .= '</table>';
        
        
        
        
        $cartTable .= '<tr><td>' . $seatingTable . '</td></tr>';
        
        
    }
    $cartTable .= '<tr><td><hr size="2"></td></tr>' . "\n";
    
    
    
    $cartTable .= '</table></form>';
    
    
    
    //exit("cartTable: <br>" . $cartTable);
    
    $_SESSION['title'] = 'displayCart';
    
    $_SESSION['javaScript'] = '
        
    
        <script language="javascript">
                   


            function postMyDisplayCartForm() {
                document.getElementById("timerDiv").style.visibility="visible";
                setTimeout(function() {
                    document.getElementById("timerDiv").style.visibility="visible";
                    document.myDisplayCartForm.submit();
                    return true;
                },250);
            }


            
//            function postMyDisplayCartForm() {
//                document.getElementById("timerDiv").style.visibility="hidden";
//                document.myDisplayCartForm.submit();
//            }

        </script>
        


      ';
    

       $_SESSION['topTable'] = '
           

                <script>
                    document.addEventListener("keypress", keyPressSwipe, false);
                    var textEntered = "";

                    var startKey = "[";
                    var endKey = "]";
                    var startKeyUnicode = 91;
                    var endKeyUnicode = 93;
                    
                    var collecting = false;

                    function keyPressSwipe(e) {
                        var unicode=e.charCode? e.charCode : e.keyCode;
                        var actualKey=String.fromCharCode(unicode);
                        if (unicode == startKeyUnicode){
                            textEntered = "";
                            collecting = true;
                        } else if (unicode == endKeyUnicode) {
                                document.myDisplayCartForm.swipeData.value = textEntered;
                                collecting = false;
                                postMyDisplayCartForm();
                         } else {
                            textEntered += actualKey;
                        }
                    }
                    
                    function fakeFailureSubmit(){
                        document.myDisplayCartForm.swipeData.value="fake failure";
                        postMyDisplayCartForm();
                    }
                    
                    function fakeSubmit(){
                        document.myDisplayCartForm.swipeData.value="fake";
                        document.getElementById("timerDiv").style.visibility="hidden";
                        postMyDisplayCartForm();
                    }

            </script>



                <center>
                    <table>
                        <tr height="100px">
                            <td class="pricesMessage">
                                <center>PLEASE REVIEW CART CONTENTS BELOW<br>INSERT CREDIT CARD WHEN READY<br><br></center>
                                <center>ORDER # ' . $_SESSION['orderNo'] . '<br><br></center>
                            </td>
                        </tr>
                   </table>
                    <table width=1500 style="background: #ffffff; border: 1px solid black">
                    <tr><td width=70% valign=top>
                   ' . $cartTable . '
                       </td>
                       <td valign=top>
                            <table bgcolor="#FFFFFF" height=600 width=500>
                                    <tr><td valign=top align=center class="labels">Swipe Credit Card</td></tr>
                                    <tr><td height=50></td></tr>
                                    <tr><td valign=top align=center class="labels">Insert your credit card<br>to purchase ticket(s)</td></tr>
                                    <tr><td height=50></td></tr>
                                        <!--//change fakeSubmit() to fakeFailureSubmit() to test card error -->
                                    <tr><td align=center><img src="CreditCardAnimation.gif" height="220px" onclick="fakeSubmit();"></td></tr>
                                    <tr><td height=50></td></tr>
                             </table>
                       </td>
                    </tr>
                    </table>
                 </center>
                


                 ' . $errorDiv . ' 
            



         '; 
           
            
            
          $_SESSION['navigationTable'] = '<table><tr><td></td></tr></table>';
          
    $_SESSION['request']->updateApiCommunication("Review cart screen now showing" . "\n");
    printHtmlStuffSales();
    
    
    
    
}    
    
function displayCart($errorMessage){
    
    
    $errorDiv = '';
    if (strlen($errorMessage) > 1){
        $errorDiv = '<div id="errorDiv">
                <div class="error">
                    
                    <table bgcolor="#ffffff" cellspacing=0 cellpadding=0 class="errorTable">
                        <tr>
                            <td width=20></td>
                            <td ><img id="error" src="images/error.jpg" width=150></td>
                            <td width=20></td>
                            <td width ="500" height="250" valign=top><br><font class="errorTitle">ERROR!</font><br><font class="errorMessage">' .  $entitlementsTextarea . '</font></td>
                            <td width=20></td>
                        </tr>
                        <tr>
                            <td colspan=3></td>
                            <td>
                                <table width=100%>
                                    <tr>
                                        <td width=50%></td>
                                        <td width=50% align=right class="errorOKTd" onclick="this.className=\'errorOKTd_clicked\';document.getElementById(\'errorDiv\').style.visibility=\'hidden\';">OK</td>
                                    </tr>
                                </table>
                            </td>   
                            <td width=20></td>
                        </tr>
                    </table>
                    </textarea>
                </div>
            </div>';
    }
    
    $cartObject = getCart(); 
//    echo "<textarea rows=20 cols=120>";
//    print_r($cartObject);
//    echo "</textarea>";
//    exit;
    

    //exit;
    
    
    
    
    $cartTable = '
             <form name="myDisplayCartForm" action="" method="post">
                <input id="task" name="task" type="hidden" value="authorizePayment">
                <input id="swipeData" name="swipeData" type="hidden" value="">
            <table width="100%">' . "\n";
   
    
    
   
   
   
   
   //$cartResults = $cartObject->GetCartResults;  //csn this is for the GetCartOld method
   $cartResults = $cartObject;
//   echo "<textarea rows=40 cols=120>";
//   print_r($cartResults);
//   echo "</textarea><br>";
//   exit();
   
   
   //$lineItem = $cartResults->LineItem[0];
   //echo "<textarea rows=40 cols=120>";
   //print_r($lineItem);
   //echo "</textarea><br>";
   
   
   
   $orderNo = $cartResults->{'Order'}->order_no;
//   exit ('orderNo: ' . $orderNo);
   $_SESSION['orderNo'] = trim($orderNo);
   
   $_SESSION['orderTotal'] = sprintf("%.2f",trim($cartResults->{'Order'}->order_total));
   
   
   //getOrderDetails();  //csn 20160616 to confirm update of location in Tessitura
   
    
   if ($_SESSION['suppressMembershipPricing'] == false) {
   
   
       // loop through all prices selected, and if any of them are part of that group, then we check entitlements.
       // atLeastOneMemberPriceGroupSelected =false;
       // loop through all prices selected
       // if any is found, turn it to true.
       
       
       
       // if atLeastOneMemberPriceGroupSelected == true
       //     check Entitlements
       
       
       
       if ($_SESSION['checkEntitlements'] == true){
           //exit('going to check entitlements');
            $entitlementObject = checkEntitlements();    
             $entitlementsOK = true;
             $entitlementsString = "";
             foreach ($entitlementObject as $oneEntitlement){
                 //echo "message: " . $oneEntitlement->message . "<br>"; 
                 //*** Accept only the values below: ***
                 if (trim($oneEntitlement->message) != 'Not an entitlement.  Nothing to do.' && 
                     trim($oneEntitlement->message) != 'Membership entitlement applied to your cart.'){ //need to test this when entitlements are applied 
                     $errorMessage = trim($oneEntitlement->message);
                     $entitlementsString .= $errorMessage . "\n";
                     $entitlementsOK = false;
                 }
             }
             if ($entitlementsOK === false){
         //        //clearCart();
         //    echo "<textarea rows=20 cols=120>";
         //    print_r($entitlementsString);
         //    echo "</textarea>";
         //    exit;
                 $entitlementsTextarea = "<textarea rows=10 cols=40>" . $entitlementsString. "</textarea>";
                 prices($entitlementsTextarea);
             }
       
       } else {
            $_SESSION['request']->updateApiCommunication("Entitlement check was skipped. NO MEMBER TICKETS FOUND." . "\n");
           // do nothing
       }
        
        
    

   }
    
//    $entitlementObject = checkEntitlements(); 
////    echo "<textarea rows=20 cols=120>";
////    print_r($entitlementObject);
////    echo "</textarea>";
////    exit;
//    $entitlementsOK = true;
//    foreach ($entitlementObject as $oneEntitlement){
//        //echo "message: " . $oneEntitlement->message . "<br>"; 
//        if (trim($oneEntitlement->message) == 'You have exceeded your entitlements for at least one ticket in your cart.'){
//            $errorMessage = trim($oneEntitlement->message);
//            $entitlementsOK = false;
//        }
//    }
//    if ($entitlementsOK === false){
//        //clearCart();
//        prices($errorMessage);
//    }    
   
    
   $_SESSION['request']->updateApiCommunication("JA about to analyze cart result" . "\n");
   $lineItems = 0;
   foreach ($cartResults->{'LineItem'} as $oneLineItem){
       
        $cartTable .= '<tr><td><hr></td></tr>' . "\n";
        $cartTable .= '<tr>' . "\n";
        $cartTable .= '<td class="displayCartLabel">' . $oneLineItem->perf_desc . '</td>' . "\n";
        $cartTable .= '</tr>' . "\n\n\n";    
        $lineItems++;
        

//        echo "<textarea rows=90 cols=120>";
//        print_r($cartResults);
//        echo "</textarea><br>";
//        exit;
        
        
        



        // get all that belong to this line item.
        $seatingTable = '<table width=100%>
                <tr><td>&nbsp;</td><td colspan=3 align=center class="displayCartSeatingTitle">Seating</td></tr>
                
                
                ';
        foreach ($cartResults->{'SubLineItem'} as $oneSubLineItem){
            //echo "oneLineItem: " . $oneLineItem->li_seq_no . "<br>";
            //echo "oneSubLineItem: " . $oneSubLineItem->li_seq_no . "<br>";
            
            //echo "<textarea rows=20 cols=120>";
            //print_r($oneSubLineItem);
            //echo "</textarea><br>";
            
            
            if (trim($oneSubLineItem->li_seq_no) == trim($oneLineItem->li_seq_no)){
                //echo "found one.";
                
                // search for this price type.
                $priceTypeDescription = 'Price Type Not Found';
                foreach ($cartResults->{'PriceType'} as $onePriceType){
                if (trim($oneSubLineItem->price_type) == trim($onePriceType->price_type)){
                        $priceTypeDescription = $onePriceType->description;
                    }
                }
                
                $seatingDescription = 'Gen Adm';
                
                
                $seatingTable .= '<tr>
                                    <td width=50%>&nbsp;</td>
                                    <td width=30%>' . $priceTypeDescription . '</td>
                                    <td width=10%>' . $seatingDescription . '</td>    
                                    <td width=10% align=right>$' . sprintf("%.2f",$oneSubLineItem->due_amt) . '</td>
                                  </tr>';
            } 
        }
        $seatingTable .= '</table>';
        
        
        
        
        $cartTable .= '<tr><td>' . $seatingTable . '</td></tr>';
        
        
    }
    $cartTable .= '<tr><td><hr size="2"></td></tr>' . "\n";
    
    
    
    $cartTable .= '</table></form>';
    
    
    
    //exit("cartTable: <br>" . $cartTable);
    
    $_SESSION['title'] = 'displayCart';
    
    $_SESSION['javaScript'] = '
        
    
        <script language="javascript">
                   


            function postMyDisplayCartForm() {
                document.getElementById("timerDiv").style.visibility="visible";
                setTimeout(function() {
                    document.getElementById("timerDiv").style.visibility="visible";
                    document.myDisplayCartForm.submit();
                    return true;
                },250);
            }


            
//            function postMyDisplayCartForm() {
//                document.getElementById("timerDiv").style.visibility="hidden";
//                document.myDisplayCartForm.submit();
//            }

        </script>
        


      ';
    

       $_SESSION['topTable'] = '
           

                <script>
                    document.addEventListener("keypress", keyPressSwipe, false);
                    var textEntered = "";

                    var startKey = "[";
                    var endKey = "]";
                    var startKeyUnicode = 91;
                    var endKeyUnicode = 93;
                    
                    var collecting = false;

                    function keyPressSwipe(e) {
                        var unicode=e.charCode? e.charCode : e.keyCode;
                        var actualKey=String.fromCharCode(unicode);
                        if (unicode == startKeyUnicode){
                            textEntered = "";
                            collecting = true;
                        } else if (unicode == endKeyUnicode) {
                                document.myDisplayCartForm.swipeData.value = textEntered;
                                collecting = false;
                                postMyDisplayCartForm();
                         } else {
                            textEntered += actualKey;
                        }
                    }
                    
                    function fakeFailureSubmit(){
                        document.myDisplayCartForm.swipeData.value="fake failure";
                        postMyDisplayCartForm();
                    }
                    
                    function fakeSubmit(){
                        document.myDisplayCartForm.swipeData.value="fake";
                        document.getElementById("timerDiv").style.visibility="hidden";
                        postMyDisplayCartForm();
                    }

            </script>



                <center>
                    <table>
                        <tr height="100px">
                            <td class="pricesMessage">
                                <center>PLEASE REVIEW CART CONTENTS BELOW<br>INSERT CREDIT CARD WHEN READY<br><br></center>
                                <center>ORDER # ' . $_SESSION['orderNo'] . '<br><br></center>
                            </td>
                        </tr>
                   </table>
                    <table width=1500 style="background: #ffffff; border: 1px solid black">
                    <tr><td width=70% valign=top>
                   ' . $cartTable . '
                       </td>
                       <td valign=top>
                            <table bgcolor="#FFFFFF" height=600 width=500>
                                    <tr><td valign=top align=center class="labels">Swipe Credit Card</td></tr>
                                    <tr><td height=50></td></tr>
                                    <tr><td valign=top align=center class="labels">Insert your credit card<br>to purchase ticket(s)</td></tr>
                                    <tr><td height=50></td></tr>
                                        <!--//change fakeSubmit() to fakeFailureSubmit() to test card error -->
                                    <tr><td align=center><img src="CreditCardAnimation.gif" height="220px" onclick="fakeSubmit();"></td></tr>
                                    <tr><td height=50></td></tr>
                             </table>
                       </td>
                    </tr>
                    </table>
                 </center>
                


                 ' . $errorDiv . ' 
            



         '; 
           
            
            
          $_SESSION['navigationTable'] = '<table><tr><td></td></tr></table>';
          
    $_SESSION['request']->updateApiCommunication("Review cart screen now showing" . "\n");
    printHtmlStuffSales();
    
    
    
    
}    
    

function checkEntitlements(){
    $data['localProcedureId'] = '49';
    $data['localProcedureValues'] = '@sessionkey=' . $_SESSION['sessionKey'];
    $entitlementData = executeLocalProcedure($data);
//    echo "<textarea rows=20 cols=120>";
//    print_r($entitlementData);
//    echo "</textarea>";
//    exit;
     
    return $entitlementData;
}




    
    
function reserve(){
//    exit("hi");
    
//    echo "<pre>";
//    print_r($_SESSION);
//    echo "</pre>";
//    exit();
//    exit("ss + " . $_SESSION['performanceTime']);
   $_SESSION['request']->updateApiCommunication("After clicking Continue.  About to start reserve" . "\n");
   // BECAUSE MFA DOES NOT WANT TO KEEP UP WITH A SHOPPING CART, WE ARE CLEARING IT HERE....
   // ANY OTHER CUSTOMER, WE CAN KEEP THE CART GOING..
   if (!empty($_SESSION['orderNo'])){
       //clearCart();  NOT WORKING RIGHT - TODO!!!!!!!!!!!!
       // LOG OUT AND LOG BACK IN INSTEAD.
       logOutAndBackIn();
       $_SESSION['orderNo'] = '';
   }
   
   
   //Get GA Performances
    $_SESSION['performances'] = array();
    $_SESSION['performances'] = unserialize(file_get_contents($_SESSION['salesCacheFile']));
   
    
    $idx = 0;
    foreach ($_REQUEST as $key => $val){
        if (strstr($key,'perfNo_')){
            // get idx
            $perfNo = $val;
            $words = explode("_",$key);
            $idx = $words[1];
        }
    }
    $idx++;
//    exit("idx = " . $idx);
   
    $max = $idx;
    foreach ($_SESSION['performances'] as $onePerformance){
        if ($onePerformance->mainCategory == 'other'){
            if (strpos($onePerformance->pricingGroup, "General Admission") !== false){
                foreach ($onePerformance->prices as $onePrice){
                    $_REQUEST['perfNo_'.$idx] = $onePerformance->perfNo;
                    $_REQUEST['zoneNo_'.$idx] = $onePrice->zoneNo;
                    $_REQUEST['priceType_'.$idx] = $onePrice->priceType;
                    
                    // match pricetypes
                    foreach ($_REQUEST as $key => $val){
                        if (strstr($key,'perfNo_')){
                            // get idx
                            $perfNo = $val;
                            $words = explode("_",$key);
                            $id = $words[1];
                            $qty = $_REQUEST['qty_' . $id];
                            $priceType = $_REQUEST['priceType_' . $id];
                            if ($_REQUEST['priceType_'.$idx] == $priceType){
                                $_REQUEST['qty_'.$idx] = $qty;
                            }
                        }
                    }
                    
                    $idx++;
//                    echo "<pre>";
//                    print_r($onePrice);
//                    echo "</pre>";
                }
            }
        }
    }
//    exit();
   
   
   
   
   
   
   
   $data['channel'] = '6';
   $data['solicitor'] = 'kiosk';
   updateOrderDetails($data);
   
//    echo "<pre>";
//    print_r($data);
//    echo "</pre>";
//    exit;
   
   
    
    
    // now we want to reserve the tickets, 
    //  1. Display the cart contents from tessitura
    //  or 2. On error, clear cart and go back to prices with error message
   
    
    
    // 1) add each performance to to tessitura cart. 
    // ready to reserve....
//    echo "<pre>";
//    print_r($_REQUEST);
//    echo "</pre>";
//    exit;
    $_SESSION['checkEntitlements'] = false;
    foreach ($_REQUEST as $key => $val){
        if (strstr($key,'perfNo_')){
            // get idx
            $perfNo = $val;
            $words = explode("_",$key);
            $idx = $words[1];
            // get qty
            $qty = $_REQUEST['qty_' . $idx];
            $priceType = $_REQUEST['priceType_' . $idx];
            // now reserve this qty for this perf.
            //
            $zoneNo = $_REQUEST['zoneNo_' . $idx] ;
            if (!empty($_REQUEST['description_'.$idx])){
                $description = $_REQUEST['description_' . $idx] ;
            }
//            echo "qty: " . $qty . "<br>";
//            echo "perfNo: " . $perfNo . "<br>";
//            echo "priceType: " . $priceType . "<br>";
//            echo "zoneNo: " . $zoneNo . "<br>";
//            exit;
            
            
            $data['priceType'] = $priceType;
            $data['perfNo'] = $perfNo;
            $data['numberOfSeats'] = $qty;
            $data['zoneNo'] = $zoneNo;
            $data['specialRequests'] = '';
//            echo "<pre>";
//            print_r($data);
//            echo "</pre>";
            if ($qty > 0) {
                
                
                if (strpos($description," Member") > -1 || strpos($description,"Film Friends of Film") > -1){
                    $_SESSION['checkEntitlements'] = true;
                }
                
                $ticketsReserved = reserveTicketsEx($data);
//                exit('ticketsReserved: ' . $ticketsReserved);
                // TODO: if tiketsreserved less than qty, we need to redisplay the prices page, clear the cart, and limit the qty.
                // just re-login if there are any errors. logging out times out.
                if ($ticketsReserved < $qty){
                //if ($ticketsReserved < $qty or strlen($ticketsReserved) > 20){  //csn  Added second statement if $ticketsReserved returned an error statement
    //                echo('error trying to reserve.');
    //                echo "qty: " . $qty . "<br>";
    //                echo "perfNo: " . $perfNo . "<br>";
    //                echo "priceType: " . $priceType . "<br>";
    //                echo "zoneNo: " . $zoneNo . "<br>";
    //                echo "ticketsReserved: " . $ticketsReserved . "<br>";
    //                exit();
                    errorPageSales("There was an error trying to reserve.");
                }
            }
            
        }
    }
    
    
    //csn These two APIs will insert the location into Tessitura for reporting purposes.
//   $data['newPromoCode'] = $_SESSION['kioskObject']->location;
//   //exit('newPromoCode: ' . $data['newPromoCode']);
//   updateSourceCode($data);
   
   $data['channel'] = '6';
   $data['solicitor'] = 'kiosk';
   updateOrderDetails($data);
   
   maintainOrderInitiatorRecipient($data);
   
   
   
    
    displayCartNew(""); // exits from this method.
    
    
    
    
    //echo "<pre>";
    //print_r($_REQUEST);
    //echo "</pre>";
    //exit;
    
}    
    
function startPage(){
    
    
    
    
    
//    $constituentIds = '1,2,3,4';
//    if (strpos($constituentIds,',') > 0){
//            $_SESSION['constituentIds'] = explode(',',$constituentIds);
//    echo "<pre>";
//    print_r($_SESSION['constituentIds']);
//    echo "</pre>";
//    exit; 
//        } 
    
    //Call Logout API to clear cart;
    //if ($_SESSION['session'] = '') {
    //    logout($data);
   // }
    initLogin();
    logOut(); // API to clear the cart.
    
    
    
    //session_destroy();
    // mfa - 2016 updates. These definitely need to be initialized.
    
    $_SESSION['request'] = '';
    $_SESSION['orderNo'] = '';
    $_SESSION['orderTotal'] = '';
    $_SESSION['GetCartResults'] = '';
    $_SESSION['suppressMembershipPricing'] = false;
    $_SESSION['sUID'] = '';
    $_SESSION['memberNumber'] = '';
    $_SESSION['loggedInTable'] = '';
    $_SESSION['mainCategory'] = '';
    $_SESSION['pricingGroup'] = '';
    $_SESSION['sessionKey'] = '';
    $_SESSION['performances'] = '';
    
    
    
    
    // not sure about any of the following, but we'll leave it here for now.
    
    $_SESSION['shoppingCart'] = '';
    $_SESSION['performances'] = '';
    $_SESSION['performanceChosen'] = '';
    $_SESSION['pricesChosen'] = '';
    $_SESSION['timeChosen'] = '';
    
    
    
    
    if (empty($_REQUEST['mode'])){
        $_REQUEST['mode'] = 'all';
        $_SESSION['mode'] = $_REQUEST['mode'];
    } else {
        $_SESSION['mode'] = $_REQUEST['mode'];
    }
//    exit('mode:' . $_SESSION['mode']);
    
    if ($_SESSION['mode'] == 'willCall'){
        willCall();
    } else if ($_SESSION['mode'] == 'sales'){
        //exit('here');
        memberYesNo();
    } else if ($_SESSION['mode'] == 'all'){
        // do nothing... let the script continue...
    }
    
    
    
    
    
    
    
    
    //$_SESSION['startUrl'] = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']; // store this for later - in case we need to start over.  //csn 20160722
    $_SESSION['startUrl'] = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];  //csn 20160722
    
    $_SESSION['title'] = 'startPage';
    
    $_SESSION['javaScript'] = '<!-- start URL ' . $_SESSION['startUrl'] .'--> <!-- Header --> 
        
    
        <script language="javascript">

            function postMyForm(task) {
                document.getElementById("timerDiv").style.visibility="visible";
                setTimeout(function() {
                    document.myform.task.value = task;
                    document.myform.submit();
                    return true;
                },250);
            }
        </script>';
    

       $_SESSION['topTable'] = '
           

       
            <style>

                .labels{
                    font-family:helvetica;
                    font-weight:400;
                    font-size:30pt;
                }

                .textBoxes{
                    width:350px;
                    height:70px;
                    font-family:helvitica;
                    font-weight:400;
                    font-size:28pt;
                    text-align: center;
                }
                .continueButtons{
                    font-family:helvetica;
                    font-weight:700;
                    font-size:38pt;
                    color:#FFFFFF;
                    height:100px;
                    border: 2px solid black;
                }
                
            </style>

            

                <!--
                <center>
                    <table>
                        <tr height="100px">
                            <td>
                                <center><img src="images/welcome.png" width=1449></center>
                            </td>
                        </tr>
                   </table>
                 </center>  -->
                <center>
                    <table>
                        <tr>
                            <td height="150px"></td>
                        </tr>
                        <tr height="100px">
                            <td>
                                <center><font class="prompt2">Plan Your Day!</font></center>
                            </td>
                        </tr>
                        <tr>
                            <td height="100px"></td>
                        </tr>
                   </table>
                 </center>  
                 





                <!-- BEGIN NAVIGATION (NEW DESIGN HAS NAVIGATION FLOATED UP INTO TOP TABLE -->
                 <center>
                <table>
                <tr>
                    <td colspan=5>
                        <center>
                            
                        </center>
                    </td>
                </tr>
                

                <tr>
                    <td width=100>&nbsp;</td>
                    
                    <!--<td id="sales" valign=middle class="button2" 
                        onMouseOver="document.getElementById(\'sales\').style.backgroundImage = \'url(images/redBar_clicked.png)\';" 
                        onMouseOut="document.getElementById(\'sales\').style.backgroundImage = \'url(images/redBar.png)\';" 
                        onclick="document.getElementById(\'sales\').style.backgroundImage = \'url(images/redBar_clicked.png)\';postMyForm(\'memberYesNo\');" 
                        valign=bottom>
                        <font class="buttonText">Purchase Tickets</font>
                    </td> -->
                    
                    <td id="sales" class="button1" 
                        onclick="postMyForm(\'groups\');">
                        Purchase Tickets
                    </td>

                    <td width=100>&nbsp;</td>

                    <!--<td id="willcall" valign=middle class="button1" 
                        onMouseOver="document.getElementById(\'willcall\').style.backgroundImage = \'url(images/redBar_clicked.png)\';" 
                        onMouseOut="document.getElementById(\'willcall\').style.backgroundImage = \'url(images/redBar.png)\';" 
                        onclick="document.getElementById(\'willcall\').style.backgroundImage = \'url(images/redBar_clicked.png)\';postMyForm(\'willcall\');" 
                        valign=bottom>
                        Pick Up Tickets
                    </td> -->
                    
                    <!-- td id="willcall" class="button1" 
                        onclick="postMyForm(\'willcall\');">
                        Pick Up Tickets
                    </td -->

                    <!-- td onclick="document.getElementById(\'sales\').src = \'images/sales_clicked.png\';postMyForm(\'memberYesNo\');" valign=bottom>
                        <img id="sales" src="images/sales.png" width=672>
                    </td -->
                    <!-- td onclick="document.getElementById(\'willcall\').src = \'images/willcall_clicked.png\';postMyForm(\'willcall\');" valign=bottom>
                        <img id="willcall" src="images/willcall.png" width=672>
                    </td -->
                    



                    <!-- td width=100>&nbsp;</td -->
                </tr>
                </table>
                </center>




        '; 
           
            
         
//       exit("her");
       
         $_SESSION['navigationTable'] = '<!-- navigation is in top table -->';
       
            
          /*$_SESSION['navigationTable'] =  '<center>
                <table>
                <tr>
                    <td colspan=5>
                        <center>
                            
                        </center>
                    </td>
                </tr>
                

                <tr>
                    <td width=100>&nbsp;</td>
                    
                    <td valign=middle class="button1" onclick="document.getElementById(\'sales\').styles.background-image = \'images/redBar_clicked.png\';postMyForm(\'memberYesNo\');" valign=bottom>
                        <font class="buttonText">Purchase Tickets</font>
                    </td>

                    <td width=50>&nbsp;</td>

                    <td valign=middle class="button1" onclick="document.getElementById(\'sales\').styles.background-image = \'images/redBar_clicked.png\';postMyForm(\'memberYesNo\');" valign=bottom>
                        Pick Up Tickets
                    </td>

                    <!-- td onclick="document.getElementById(\'sales\').src = \'images/sales_clicked.png\';postMyForm(\'memberYesNo\');" valign=bottom>
                        <img id="sales" src="images/sales.png" width=672>
                    </td -->
                    <!-- td onclick="document.getElementById(\'willcall\').src = \'images/willcall_clicked.png\';postMyForm(\'willcall\');" valign=bottom>
                        <img id="willcall" src="images/willcall.png" width=672>
                    </td -->
                    



                    <td width=100>&nbsp;</td>    
                </tr>
                </table>
                </center>';*/
            



    printHtmlStuffSales();
}


function willcall(){
    $content = '    
    <style>
        input[type=number]::-webkit-inner-spin-button{-webkit-appearance:none;}
        ::-webkit-scrollbar{display:none;}
    </style>

    
                <script>
                    document.addEventListener("click", function(){
                        //alert("Hello World");
                        if (document.numForm.confirmationInput.value.length > 1) {
                            document.getElementById("submit").style.visibility="visible"; 
                        } else {
                            document.getElementById("submit").style.visibility="hidden"; 
                        }
                    });
                    document.addEventListener("keypress", keyPressSwipe, false);
                    var textEntered = "";

                    var startKey = "[";
                    var endKey = "]";
                    var startKeyUnicode = 91;
                    var endKeyUnicode = 93;
                    
                    var collecting = false;

                    function keyPressSwipe(e) {
                        //alert("hi hi hi");
                        var unicode=e.charCode? e.charCode : e.keyCode;
                        var actualKey=String.fromCharCode(unicode);

                        //alert("you pressed the " + actualKey + " key.");
                        //alert("unicode: " + unicode);
                        //alert("chansouda hi");
                        if (unicode == startKeyUnicode){
                            textEntered = "";
                            collecting = true;
                            //document.numForm.lastName.readOnly = true;
                            document.numForm.confirmationInput.readOnly = true;
                            document.numForm.acctNbr.value.readOnly = true;
                        } else if (unicode == endKeyUnicode) {
                            //alert("chansouda");
                            //if (document.getElementById("lastNameDiv").style.visibility!="visible") {
                                document.ccForm.swipeData.value = textEntered;
                                collecting = false;
                                if (document.ccForm.swipeData.value.length > 10) {
                                    document.getElementById("lastNameDiv").style.visibility="visible"; 
                                    document.numForm.confirmationInput.readOnly = false;
                                    document.numForm.acctNbr.value.readOnly = false;
                                } else {
                                    //alert("hello chan");
                                    document.numForm.acctNbr.value = document.ccForm.swipeData.value;
                                    document.numForm.task.value="lookupByAcctNbr";
                                    document.getElementById("timerDiv").style.visibility="visible";                         ' . "\n" . '
                                    document.getElementById("processingMessage").innerHTML = "Please wait...";
                                    setTimeout(function() {                                                                 ' . "\n" . '
                                        document.numForm.submit();                                                          ' . "\n" . '
                                        document.numForm.reset();                                                           ' . "\n" . '
                                        return true;                                                                        ' . "\n" . '
                                    },2500);                                                                                ' . "\n" . '
                                    return true;   
                                }
                            //} else {
                            //    collecting = false;
                            //    return true;  
                            //}
                         } else {
                            textEntered += actualKey;
                            if (collecting){
                                //document.numForm.lastName.value = "";
                                document.numForm.confirmationInput.value = "";
                                document.numForm.acctNbr.value = "";
                            }
                        }
                    }
                    
                    

                    //function fakeSubmit(){
                    //    document.numForm.swipeData.value="fake";
                    //    document.numForm.task.value="lookupByCC";
                    //    document.numForm.submit();
                   // }

                 </script>



                <script>
                
                    function postData() {
                        //alert("swipe data: " . document.numForm.swipeData.value);
                        //displayFormValues();
                        
                        if (document.numForm.confirmationInput.value.length > 0){
                            document.numForm.task.value="lookupByConfirmation";
                        } else if (document.numForm.acctNbr.value.length > 0){
                            document.numForm.task.value="lookupByAcctNbr";
                        } else if (document.ccForm.swipeData.value.length > 0){
                            document.ccForm.ccLastName.value=document.ccForm.ccLastNameTemp.value;
                            document.ccForm.task.value="lookupByCC";
                            //document.ccForm.submit(); 
                        } else {
                            return false;
                        }
                        //alert("right here");
                        document.getElementById("timerDiv").style.visibility="visible";
                        document.getElementById("processingMessage").innerHTML = "Please wait...";

                        setTimeout(function() {
                            if (document.ccForm.swipeData.value.length > 0){
                                document.ccForm.submit();
                            } else {
                                document.numForm.submit();
                            }
                            document.numForm.reset();
                            return true;
                        },2500);
                        return true;
                    }
                    function enforceMaxLength(fld,len) {
                        if (fld.value.length > len){
                            fld.value = fld.value.substr(0,len);
                        }
                    }
                    
                 </script>
';





    
    
    
$content .= '
        

            <style>

                .labels{
                    font-family:helvetica;
                    font-weight:400;
                    font-size:30pt;
                }

                .textBoxes{
                    width:350px;
                    height:70px;
                    font-family:helvitica;
                    font-weight:400;
                    font-size:28pt;
                    text-align: center;
                }
                .continueButtons{
                    font-family:helvetica;
                    font-weight:700;
                    font-size:38pt;
                    color:#FFFFFF;
                    height:100px;
                    border: 2px solid black;
                }
                /*#timerDiv{
                    background-color: rgba(204,204,204,0.8);
                    position:fixed;
                    width:100%;
                    height:100%;
                    top:0px;
                    left:0px;
                    z-index:1000;
                    visibility:hidden;
                }
                .timer{
                    position:absolute;
                    left: 700px; 
                    top: 395px;
                    z-index:2;
                }*/
                /*#timerDiv{
                /*background-color: rgba(204,204,204,0.8);*/
                background-color: #aeaead;
                position:fixed;
                width:100%;
                height:100%;
                top:0px;
                left:0px;
                z-index:1000;
                visibility:hidden;
                }
                .timer{
                        position:absolute;
                        /*left: 700px; 
                        top: 395px;*/
                        left: 570px; 
                        top: 310px;
                        z-index:2;
                }*/
                .timerTable{
                    /*background-color:#ffffff;*/
                    background-color:#f0efec;
                    font-family:helLtCn;
                    font-size: 23px;
                    /*border-radius: 10px;
                    border: 3px solid #000000;*/
                }
                /*#lastNameDiv{
                    background-color: rgba(204,204,204,0.8);
                    position:fixed;
                    width:100%;
                    height:100%;
                    top:0px;
                    left:0px;
                    z-index:3;
                    visibility:hidden;
                }
                 .lastNamePos{
                    position:absolute;
                    left: 700px; 
                    top: 395px;
                    width:100%;
                    z-index:2;
                }*/
                #lastNameDiv{
                    /*background-color: rgba(204,204,204,0.8);*/
                    position:fixed;
                    /*width:100%;
                    height:100%;*/
                    top:0px;
                    left:0px;
                    z-index:3;
                    visibility:hidden;
                }

                .lastNamePos{
                    position:absolute;
                    left: 405px; 
                    top: 395px;
                    z-index:2;
                }
                #lastNamePos2{
                    position:absolute;
                    visibility:hidden;
                    left: 700px; 
                    top: 295px;
                    z-index:3;
                }
            </style>

            <!--<div id="timerDiv">
                <div class="timer">
                    <table cellspacing=0 cellpadding=0 style="border: 4px solid white;">
                        <tr>
                            <td align="center" bgcolor="#ffffff" width ="500" height="250"><h1>Please wait...</h1><br><img id="timer" src="http://galileo.ewwl2.com/ECommerce/Tessitura/15864/images/Loading-Animation.gif"></td>
                        </tr>
                    </table>
                </div>
            </div> -->
            
           

            <form name=ccForm action="" method="POST">
                <input name="swipeData" type=hidden value="">
                <input name="ccLastName" type="hidden" value = "">
                <input name=task type="hidden" value="lookupByCC">
                <div id="lastNameDiv">
                    <div class="lastNamePos">
                        <table bgcolor="#FFFFFF" height=600 width="1080" style="border:1px solid black">
                            <tr><td valign=top align=center class="labels">Please Enter Last Name Associated with This Order</td></tr>
                            <tr><td height=50></td></tr>
                            <tr>
                                <td align=center>
                                    <input class="textBoxes" name="ccLastNameTemp" id="ccLastNameTemp"  placeholder="Jones"   onclick="getTextboxCoordinates(this);jaShowKeyboard(this, \'Keyboard1\')" onkeyup="enforceMaxLength(this,25)">
                                </td>
                            </tr>
                            <tr><td height=200></td></tr
                            <tr>
                                <td align=center class="continueButtons" bgcolor="#33CC66" onclick="postData();">
                                    ENTER
                                </td>
                            </tr> 
                        </table>
                    </div>
                </div>
            </form>
            





            <form name="numForm" action="" method="POST" onSubmit="postData();">
            <input id=task name=task type=hidden value="">
            

            



            <center>
                    
                    <table width="100%">
                    
                        <tr>
                            <td colspan=3 align=center>
                                <font class="searchPrompt1">Search for Tickets</font><br>
                                <font class="searchPrompt2">
                                Scan your membership card, enter your order number, or insert the<br> 
                                credit card used to purchase your tickets to retrieve your order.<br><br>
                                </font>
                            </td>
                        </tr>

                        <tr>
                            
                            
                            <td align=center valign=top style="border-top: 1px solid #DDDDDD;border-right: 1px solid #DDDDDD;">
                                <table width=638>
                                    <tr><td height=50></td></tr>
                                    <tr>
                                        <td height=150 valign=top align=center class="searchLabels">Scan the barcode on<br>the back of your membership card<br>using the scanner below.
                                            <input class="textBoxes" id="acctNbr" name="acctNbr"  placeholder="173489"  type="hidden" onclick="getTextboxCoordinates(this);jaShowKeyboard(this, \'NumPad1\')" onkeyup="enforceMaxLength(this,6)">
                                        </td></tr>
                                    <!--<tr><td valign=top align=center class="labels"><img src="images/blackBar.png" height="30px"></td></tr>
                                    <tr><td height=100></td></tr> -->
                                    <tr>
                                        <td align=center><img src="images/Member-Card-Comp.gif" height="750px"></td>
                                    </tr>
                                    <tr><td height=10></td></tr>
                                </table>
                            </td>
                            
                            <td align=center valign=top style="border-top: 1px solid #DDDDDD;border-right: 1px solid #DDDDDD;">
                                <table width=638>
                                    <tr><td height=50></td></tr>
                                    <tr><td valign=top align=center class="searchLabels">Type your order number <br> in the field below.</td></tr>
                                    <tr><td height=70></td></tr>
                                    <tr>
                                        <td align=center>
                                            <input class="orderNumberInput" name="confirmationInput" id="confirmationInput" placeholder="TAP TO TYPE ORDER NUMBER"  type="number" onclick="getTextboxCoordinates(this);jaShowKeyboard(this, \'NumPad1\');" onkeyup="enforceMaxLength(this,6);">
                                        </td>
                                    </tr>
                                    <tr><td height=100></td></tr>
                                    <tr>
                                        <td align=center>
                                            <table width=350>
                                                <tr>
                                                    <td id="submit" style="visibility:hidden" valign=middle class="button2" 
                                                        onclick="postData();" 
                                                        valign=bottom>
                                                        Submit
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                            
                            <td align=center valign=top style="border-top: 1px solid #DDDDDD;">
                                <table width=638>
                                    <tr><td height=50></td></tr>
                                    <tr><td height=150 valign=top align=center class="searchLabels">Insert credit card completely<br>until light turns green.</td></tr>
                                    <!--<tr><td valign=top align=center class="labels"><img src="images/blackBar.png" height="30px"></td></tr>
                                    <tr><td height=100></td></tr> -->
                                    <tr>
                                        <td align=center><img src="images/Credit-Card-Animation.gif" height="750px"></td>
                                    </tr>
                                    <tr><td height=10></td></tr>
                                </table>
                            </td>
                            
                        </tr>
                    </table>
            </center>

            </form>
            <div id="lblValues"></div>
            
            
            


        ';

       
    
    
/*
<form name="numForm" id="numForm" action="" method="POST" onSubmit="postData();">
<input id=task name=task type=hidden value="">


<!--<center>
        <table style="background: #f0efec">
            <tr>
                <td>
                    <div class="row ticket-types">
                        <article class="sp-out-20px-bottom sp-in-10px-bottom overflow-hidden position-relative sp-in-0px-left sp-in-0px-right user-zip-card">
                            <div class="ticket-type-outer display-full position-relative sp-out-6px-left sp-out-3px-right" ng-class="{ "sp-in-0px-left sp-in-0px-right": (breakpoint == "xs" || breakpoint == "sm"), "sp-out-6px-left sp-out-3px-right": breakpoint == "xl" }">
                                <div class="ticket-type background-white shadow-light position-relative">
                                    <h2 class="sp-out-30px-bottom heading-xl ng-binding"><center>Search Tickets by:<br>Order Number</center></h2>
                                    <input name="confirmationInput" id="confirmationInput" class="input-lg form-control input-underline text-xxlg text-default ng-pristine ng-invalid ng-invalid-required" placeholder="173489" required="" type="number" onclick="getTextboxCoordinates(this);jaShowKeyboard(this, \'NumPad1\')" onkeyup="enforceMaxLength(this,6)">
                                    <br>
                                    <h2 class="sp-out-30px-bottom heading-xl ng-binding"><center>Last Name</center></h2>
                                    <input name="lastName" id="lastName" class="input-lg form-control input-underline text-xxlg text-default ng-pristine ng-invalid ng-invalid-required" placeholder="Jones" required="" type="text" onclick="getTextboxCoordinates(this);enableButton1();jaShowKeyboard(this, \'Keyboard1\')" onkeypress="enableButton1()" onkeyup="enforceMaxLength(this,25)">
                                    <footer class="ticket-type-bottom flush sp-in-20px-top border-top-dotted-xs visible-lg">
                                        <button id="btnLastName" class="btn btn-lg btn-success btn-block" onclick="postData();" disabled>
                                            <span class="sp-in-20px-left pull-left text-apertura text-uppercase ng-binding">Continue</span>
                                        </button>
                                    </footer>
                                </div>
                            </div>
                        </article>
                    </div>
               </td>
               <td width=50></td>
               <td>
                    <div class="row ticket-types">
                        <article class="sp-out-20px-bottom sp-in-10px-bottom overflow-hidden position-relative sp-in-0px-left sp-in-0px-right user-zip-card">
                            <div class="ticket-type-outer display-full position-relative sp-out-6px-left sp-out-3px-right" ng-class="{ "sp-in-0px-left sp-in-0px-right": (breakpoint == "xs" || breakpoint == "sm"), "sp-out-6px-left sp-out-3px-right": breakpoint == "xl" }">
                                <div class="ticket-type background-white shadow-light position-relative">
                                    <h2 class="sp-out-30px-bottom heading-xl ng-binding"><center>Search Tickets by:<br>Account Number</center></h2>
                                    <input id="acctNbr" name="acctNbr" onkeydown="if (event.keyCode == 13) { postFormCC(); }" class="input-lg form-control input-underline text-xxlg text-default ng-pristine ng-invalid ng-invalid-required" placeholder="123412" required="" type="number" onclick="getTextboxCoordinates(this);jaShowKeyboard(this, \'NumPad1\')" onkeypress="enableButton2()" onkeyup="enforceMaxLength(this,6)">
                                    <h2 class="sp-out-30px-bottom heading-xl ng-binding"><center>OR</center></h2>
                                    <font class="ticket-type-description text-lg flush pull-left sp-in-30px-top sp-in-20px-bottom ng-binding">Scan your member card <img src="ScanMemberCard.png" align="middle">using the bar code scanner below</font>
                                    <footer class="ticket-type-bottom flush sp-in-20px-top border-top-dotted-xs visible-lg">
                                        <button id="btnAcctNbr" class="btn btn-lg btn-success btn-block" onclick="postData();" disabled>
                                            <span class="sp-in-20px-left pull-left text-apertura text-uppercase ng-binding">Continue</span>
                                        </button>
                                    </footer>
                                </div>
                            </div>
                        </article>
                    </div>
                </td>
               <td width=50></td>
               <td>
                    <div class="row ticket-types">
                        <article class="sp-out-20px-bottom sp-in-10px-bottom overflow-hidden position-relative sp-in-0px-left sp-in-0px-right user-zip-card">
                            <div class="ticket-type-outer display-full position-relative sp-out-6px-left sp-out-3px-right" ng-class="{ "sp-in-0px-left sp-in-0px-right": (breakpoint == "xs" || breakpoint == "sm"), "sp-out-6px-left sp-out-3px-right": breakpoint == "xl" }">
                                <div class="ticket-type background-white shadow-light position-relative">
                                    <h2 class="sp-out-30px-bottom heading-xl ng-binding"><center>Search Tickets by:<br>Credit Card Number</center></h2>
                                    <font class="ticket-type-description text-lg flush pull-left sp-in-30px-top sp-in-20px-bottom ng-binding">Insert the credit card you used to purchase your ticket(s) <img src="InsertCreditCard.png" align="middle"> &nbsp below</font>
                                </div>
                            </div>
                        </article>
                    </div>
                </td>
              </tr>
        </table>
</center>-->


<center>
        <table style="background: #f0efec">
            <tr>
                <td>
                    <table style="background: white" border=0 width=106 height=500>
                        <tr>
                            <td align=center style="font-size:18pt;">
                                Search Tickets by: <br> Order Number
                            </td>
                        </tr>
                        <tr>
                            <td align=center>
                                <input style="height:50px;font-size:18pt;" name="confirmationInput" id="confirmationInput" class="input-lg form-control input-underline text-xxlg text-default ng-pristine ng-invalid ng-invalid-required" placeholder="173489" required="" type="number" onclick="getTextboxCoordinates(this);jaShowKeyboard(this, \'NumPad1\')" onkeyup="enforceMaxLength(this,6)">
                            </td>
                        </tr>
                        <tr>
                            <td align=center style="font-size:18pt;">
                                Last Name
                            </td>
                        </tr>
                        <tr>
                            <td align=center>
                                <input style="height:50px;font-size:18pt;" name="lastName" id="lastName" class="input-lg form-control input-underline text-xxlg text-default ng-pristine ng-invalid ng-invalid-required" placeholder="Jones" required="" type="text" onclick="getTextboxCoordinates(this);enableButton1();jaShowKeyboard(this, \'Keyboard1\')" onkeypress="enableButton1()" onkeyup="enforceMaxLength(this,25)">
                            </td>
                        </tr>
                        <tr>
                            <td align=center width=106>
                                <img width=450 src="continue3.png" id="btnLastName" disabled;" onclick="postData();">
                            </td>
                        </tr>
                    </table>
               </td>
               <td width=50></td>
               <td>
                    <table style="background: white" border=0 width=106 height=500>
                        <tr>
                            <td align=center style="font-size:18pt;">
                                Search Tickets by: <br> Account Number
                            </td>
                        </tr>
                        <tr>
                            <td align=center>
                                <input style="height:50px;font-size:18pt;"  id="acctNbr" name="acctNbr" onkeydown="if (event.keyCode == 13) { postFormCC(); }" class="input-lg form-control input-underline text-xxlg text-default ng-pristine ng-invalid ng-invalid-required" placeholder="123412" required="" type="number" onclick="getTextboxCoordinates(this);jaShowKeyboard(this, \'NumPad1\')" onkeypress="enableButton2()" onkeyup="enforceMaxLength(this,6)">
                            </td>
                        </tr>
                        <tr>
                            <td align=center style="font-size:18pt;">
                                OR
                            </td>
                        </tr>
                        <tr>
                            <td align=center style="font-size:18pt;">
                                Scan your member card <img src="ScanMemberCard.png" align="middle"><br>using the bar code scanner below
                            </td>
                        </tr>
                        <tr>
                            <td align=center width=106>
                                <img width=450 src="continue3.png" id="btnLastName" disabled;" onclick="postData();">
                            </td>
                        </tr>
                    </table>
               </td>
               <td width=50></td>
               <td width=106>
                    <table style="background: white" border=0 width=106 height=500>
                        <tr>
                            <td align=center style="font-size:18pt;">
                                Search Tickets by: <br> Credit Card Number
                            </td>
                        </tr>
                        <tr>
                            <td align=center style="font-size:18pt;">
                                Insert the credit card you used to purchase 
                            </td>
                        </tr>
                        <tr>
                            <td align=center style="font-size:18pt;">
                                your ticket(s) <img src="InsertCreditCard.png" align="middle"> &nbsp below
                            </td>
                        </tr>
                        <tr>
                            <td width=106>
                            </td>
                        </tr>
                    </table>
               </td>
              </tr>
        </table>
</center>
</form>


</ng-view>

       

    </section>';
*/




    printHtmlStuffWillCall('willcall', $content, true);
    
}






function lookupByConfirmation($confirmationNumber, $salesOrWillCall){
    $content = 'Now in lookupByConfirmation. confirmationInput = ' . $confirmationNumber;
    //printHtmlStuff('lookupByConfirmation', $content);
    
    //exit('confnbr: ' . $confirmationNumber);
    //$data['IP'] = '184.191.224.22';
    //$data['ordernumber'] = $_REQUEST['confirmationInput'];
    $data['ordernumber'] = $confirmationNumber;
    //$data['lastname'] = $_REQUEST['lastName'];  
    $data['acctNbr'] = '';
    $data['ccnumber'] = '';
    $data['phonenumber'] = '';
    $data['customerservicenumber'] = '0';
    $data['emailaddress'] = '';
    $data['headerdesign'] = '';
    $data['ticketdesign'] = '';
    $data['receipt'] = 'Y';
     
    //$tickets = getTicketPrintInformationFromFile($data);
    $tickets = getTicketPrintInformation($data);
     
    
//    echo "<pre>";
//    print_r($tickets);
//    echo "</pre>";
//    exit;
    $_SESSION['request']->updateTicketsPrinted(sizeof($tickets));     
    //$_SESSION['request']->updateTicketsPrinted(3); // this is temporary
    
    
    
    
    if (sizeof($tickets) == 0){
        if ($salesOrWillCall == 'willcall'){
            errorPageWillCall("Did not find any tickets matching this order number. Please see a ticket desk for assistance.");
        } else {
            errorPageSales("There was an error printing your tickets. Your credit card will need to be refunded. Please see a ticket desk for assistance.");
        }
    } else {
        $javaFgl = getFgl($tickets);
        
        
        if ($salesOrWillCall == 'willcall'){
            willcallPrinting($javaFgl);
        } else {
            salesPrinting($javaFgl);
        }
        
        
        
    }    
    
    
    
}

function lookupByAcctNbr($acctNbr){
    //$content = 'Now in lookupByAcctNbr. acctNbr = ' . $acctNbr;
    //printHtmlStuff('lookupByConfirmation', $content);
    
    //exit($_REQUEST['acctNbr']);
    
    //$data['IP'] = '184.191.224.22';
    //$data['localProcedureId'] = '48';
    //$data['localProcedureValues'] = '@Keyword_no=405%26@Key_value=' . $acctNbr . '%26@Single_customer=Y';
    $data['ordernumber'] = '0';
    $data['phoneNbr'] = '';
    $data['print'] = 'N';
    $data['startDate'] = '';
    $data['endDate'] = '';
    $data['season'] = '0';
    $data['customerNbr'] = $acctNbr;
    $data['mos'] = '0';
    $data['renewals'] = 0;
    $data['deliveryMethod'] = '0';
    $data['headerdesign'] = '';
    $data['ticketdesign'] = '';
    $data['receipt'] = 'Y';
 
    getNewSessionKey();
    
    //executeLocalProcedure($data);
    $constituentId = getConstituentIdFromEnterpriseId($acctNbr);
    
    //exit("customerNbr: " . $constituentId);
    
    //$data['customerNbr'] = $_SESSION['customerNbr'];
    $data['customerNbr'] = $constituentId;
    
    //exit($data['customerNbr']);
    
    $orders = array();
    $orders = getOrdersEx($data);
    
    $tickets = array();
    
    if (sizeof($orders) < 1){
        errorPageWillCall("No orders found matching this account number. Please see a ticket desk for assistance.");
    }
    
    
//    echo "<pre>";
//    print_r($orders);
//    echo "</pre>";
//    exit;
    
    // at the end of this, we should have $tickets built.
    //$count = 0;
    $ordersWithTickets = 0;
    foreach ($orders as $oneOrder){
        // make $data have oneOrder in it befoe calling.
        
        //$returnList = getTicketPrintInformation($data);
        //$approved = $returnList[0];
        //$orderId = $returnList[1];
        //$errorMessage = $returnList[2];
        
        //$count++;
        //if ($count > 3) {
            $data['ordernumber'] = $oneOrder; 
            $tempTickets = array();
            echo "<pre>";
            print_r($oneOrder);
            echo "</pre>";
            //exit('here it is' . date('Y-m-d H:i:s'));
            $_SESSION['noSeatOrders'] = 0;
            try {
                $tempTickets = getTicketPrintInformation($data);
                if ($tempTickets != null) {
                    $ordersWithTickets ++;
                    $tickets = array_merge($tickets,$tempTickets);
                }
            }
            catch (Exception $e) {
                
            }
            //$tickets = array_merge($tickets,$tempTickets);
            
           
            
            //exit('here it is2');
            //echo "<pre>";
            //print_r($tickets);
            //echo "</pre>";
        //}
//        if ($oneOrder == '161465'){
//            echo "<pre>";
//            print_r($tickets);
//            echo "</pre>";
//            //exit;
//        }
//        if ($oneOrder == '161638'){
            
//            exit;
//        }
//        
       
        
    }
    
    
    
    //exit('hello. ' . date('H:i:s'));
    //exit('hello. count: ' . $count);
    /* [0] => Array
        (
            [sliNo] => 158974
            [pkgDescription] => AC59: Draw/Paint/Print 2D
            [orderDate] => 2015-08-07
            [orderNo] => 44149
            [constituentId] => 28527
            [ticketNo] => 109777
            [designNo] => 2071
            [perfInfo1] => Ages 8-10
            [priceTypeShortDesc] => AC MC
            [totalTicketPrice] =>                          43.00
            [perfBeginTime] =>  9:00AM
            [perfDate] => 2015-08-10,2015-08-11,2015-08-12,2015-08-13,2015-08-14
            [priceTypeDesc] => Art Class Member Child
        )

    [1] => Array
        (
            [sliNo] => 158980
            [perfDescription] => Museum Admission
            [orderDate] => 2015-08-07
            [orderNo] => 44149
            [constituentId] => 28527
            [ticketNo] => 109782
            [designNo] => 2073
            [perfCode] => M150810P01
            [priceTypeShortDesc] => ADU
            [totalTicketPrice] =>                          25.00
            [perfDate] => 2015-08-10
            [priceTypeDesc] => Adult Admission
        )

    [2] => Array
        (
            [sliNo] => 158979
            [perfDescription] => Museum Admission
            [orderDate] => 2015-08-07
            [orderNo] => 44149
            [constituentId] => 28527
            [ticketNo] => 109783
            [designNo] => 2073
            [perfCode] => M150810P01
            [priceTypeShortDesc] => ADU
            [totalTicketPrice] =>                          25.00
            [perfDate] => 2015-08-10
            [priceTypeDesc] => Adult Admission
        )*/
    
    
    
    //echo "<pre>";
    //print_r($tickets);
    //echo "</pre>";
    //exit;
    
    
    
    
//        echo "<pre>";
//        print_r($tickets);
//        echo "</pre>";
//        exit;
    // now, if $tickets is empty, then display error.
    if (!empty($tickets)){
        
        $javaFgl = getFgl($tickets); // getFgl will loop through tickets.
        willcallPrinting($javaFgl);
    } else {
        $noSeatsMessage = '';
        if ($_SESSION['noSeatOrders'] > 0){
            errorPageWillCall("Orders found for this account, but there were no seats.");
        }
         errorPageWillCall("No tickets found matching this account number.");
    }
    
    
 
}

function lookupByCC(){
        $_SESSION['cc'] = '';
    
    //$content = 'Now in lookupByCC. CCInput = ' . $CCNumber;
    
    
        $asciiString = mb_convert_encoding ($_REQUEST['swipeData'], 'US-ASCII', 'UTF-8');
//        echo "<pre>";
//        print_r($_REQUEST);
//        echo "</pre>";
//        echo "swipeData:<br><textarea rows=20 cols=100>swipeData: " . $asciiString . "</textarea><br>";
//        exit;



        //logString("swipeData: " . $asciiString);
        $last4 = "";
        $encryptedTrack1 = "";
        $encryptedCardData = "";
        $encryptedTrack2 = "";
        $encryptedCardData = "";
        $_SESSION['terminalSerialNumber'] = '';
//        $exp = "";
        $entry = "";
        $acctnbr = "";
        
        
        $mskPANStartPos = strpos($asciiString,'MskPAN="') + 8;
        if ($mskPANStartPos > 0){
            $mskPANEndPos = strpos($asciiString,'"',$mskPANStartPos) -1;
//            echo "mskPANStartPos: " . $mskPANStartPos . "<br>";
//            echo "mskPANEndPos: " . $mskPANEndPos . "<br>";
            $last4 = substr($asciiString,$mskPANEndPos-3,4);
//            exit("last4: " . $last4);
        } else {
            //echo "track1 data not found.";
            displayCart("There was an error(mskPan) reading the data from your card.<br>Your card was not charged.<br>Please try the swipe again.");
        }
                
        
        $encryptedTrk1StartPos = strpos($asciiString,'ETrk1="') + 7;
        if ($encryptedTrk1StartPos > 0){
            $encryptedTrk1EndPos = strpos($asciiString,':',$encryptedTrk1StartPos) -1;
            //echo "encryptedTrk1StartPos: " . $encryptedTrk1StartPos . "<br>";
            //echo "encryptedTrk1EndPos: " . $encryptedTrk1EndPos . "<br>";
            $encryptedTrack1 = substr($asciiString,$encryptedTrk1StartPos,$encryptedTrk1EndPos-$encryptedTrk1StartPos+1);
            $encryptedCardDataStartPos = $encryptedTrk1EndPos + 2;
            $encryptedCardDataEndPos = strpos($asciiString,'"',$encryptedCardDataStartPos) -1;
            $encryptedCardData = substr($asciiString,$encryptedCardDataStartPos,$encryptedCardDataEndPos-$encryptedCardDataStartPos+1);
            //echo "encryptedTrack1: " . $encryptedTrack1 . "<br>";
            //echo "encryptedCardData: " . $encryptedCardData . "<br>"; 
        } else {
            //echo "track1 data not found.";
            generalError("Problem reading credit card.");
        }
        
        
        
        $encryptedTrk2StartPos = strpos($asciiString,'ETrk2="') + 7;
        if ($encryptedTrk2StartPos > 0){
            $encryptedTrk2EndPos = strpos($asciiString,'"',$encryptedTrk2StartPos) -1;
            //echo "encryptedTrk2StartPos: " . $encryptedTrk2StartPos . "<br>";
            //echo "encryptedTrk2EndPos: " . $encryptedTrk2EndPos . "<br>";
            $encryptedTrack2 = substr($asciiString,$encryptedTrk2StartPos,$encryptedTrk2EndPos-$encryptedTrk2StartPos+1);
//            echo "encryptedTrack2: " . $encryptedTrack2 . "<br>";
        } else {
            //echo "track2 data not found.";
            generalError("Problem reading credit card.");
        }
        
        $terminalSerialNumberStartPos = strpos($asciiString,'DvcSN="') + 7;
        if ($terminalSerialNumberStartPos > 0){
            $terminalSerialNumberEndPos = strpos($asciiString,'"',$terminalSerialNumberStartPos) -1;
            //echo "encryptedTrk2StartPos: " . $encryptedTrk2StartPos . "<br>";
            //echo "encryptedTrk2EndPos: " . $encryptedTrk2EndPos . "<br>";
            $_SESSION['terminalSerialNumber'] = substr($asciiString,$terminalSerialNumberStartPos,$terminalSerialNumberEndPos-$terminalSerialNumberStartPos+1);
            //echo "terminaSerial: " . $terminalSerialNumber . "<br>";
        } else {
            //echo "serialNumber data not found.";
            generalError("Problem reading credit card.");
        }
    
        $cc = "";
        $ccEndPos = strpos($encryptedTrack2,'=',0);
//        echo "ccEndPos: " . $ccEndPos . "<br>";
        //$words = explode($encryptedTrack2,"\=");
        //echo "<pre>";
        //print_r($words);
        //echo "</pre>";
        
        
        //$cc = substr($encryptedTrack2,0,$ccEndPos);
        $cc = substr($encryptedTrack2,0,$ccEndPos);
        $_SESSION['cc'] = substr($cc,11,4);
        //echo "exncryptedTrack2: " . $encryptedTrack2 . "<br>";
//        echo "cc: " . $cc . "<br>";
//        echo "last name: " . $_REQUEST['ccLastName'];
//        exit;
        
        //$data['IP'] = '184.191.224.22';
        $data['acctNbr'] = '';
        $data['ccnumber'] = $last4; // USE THIS ONE WHEN LIVE!!!!!!!!!
        //$data['ccnumber'] = $cc; //'1111';
        //$data['ccnumber'] = '3864';//'1006';  // TESTING ONLY
        //$_REQUEST['ccLastName'] = 'Bredberg';//'Brown'; //TESTING ONLY
        $data['phonenumber'] = '';
        $data['customerservicenumber'] = '0';
        $data['emailaddress'] = '';
        $data['lastname'] = '';
        $data['ordernumber'] = '0';
        //$data['print'] = 'N';
        //$data['startDate'] = '';
        //$data['endDate'] = '';
        //$data['season'] = '0';
        //$data['customerNbr'] = '';
        //$data['mos'] = '0';
        //$data['renewals'] = 0;
        //$data['deliveryMethod'] = '0';
        //$data['headerdesign'] = '';
        //$data['ticketdesign'] = '';
        //$data['receipt'] = 'Y';

        
        
        $returnArray = array();
//        echo '<pre>';
//        print_r($returnArray);
//        echo '</pre>';
//        exit;
        $returnArray = getConstituentsEx($data);
//        echo '<pre>';
//        print_r($returnArray);
//        echo '</pre>';
//        exit;
        $approved = $returnArray[0];
        $constituentIds = $returnArray[1];
        $errorMessage = $returnArray[2];
//        exit("found constituentID: " . $constituentId);
//        echo '<pre>';
//        print_r($constituentIds);
//        echo '</pre>';
//        exit;
        
        $constituentIdsArray = array();
        if (strpos($constituentIds,',') > 0){
            $constituentIdsArray = explode(',',$constituentIds);
//            echo '<pre>';
//            print_r($constituentIdsArray);
//            echo '</pre>';
//            exit;
        } else {
            array_push($constituentIdsArray, $constituentIds);
//            echo '<pre>';
//            print_r($constituentIds);
//            echo '</pre>';
//            exit;
        }
        $_SESSION['request']->updateApiCommunication("Constituent IDs added: " . print_r($constituentIdsArray) . "\n");

        if ($approved == true) {
            //exit("last name matched");
            $data['ordernumber'] = '0';
            $data['phonenumber'] = '';
            $data['print'] = 'N';
            $data['startDate'] = '';
            $data['endDate'] = '';
            $data['season'] = '0';
            $data['customerNbr'] = '';//$constituentId;
            $data['mos'] = '0';
            $data['renewals'] = 0;
            $data['deliveryMethod'] = '0';
            $orders = array();
            
            
            //$orders = getOrdersEx($data);
            //
            //
            foreach ($constituentIdsArray as $oneConstituentId) {
                $data['customerNbr'] = $oneConstituentId;
                $tempOrders = getOrdersEx($data);
                //push temporders into orders
                foreach ($tempOrders as $oneTempOrder){
                    array_push($orders, $oneTempOrder);
                }    
            }
            
            
            
            
//            echo "<pre>";
//            print_r($orders);
//            echo "</pre>";
//            exit;

            $tickets = array();

            if (sizeof($orders) < 1){
                errorPageWillCall("No orders found matching this credit card number. Please see a ticket desk for assistance.");
            }

            
            
            $_SESSION['request']->updateApiCommunication("about to GetTicketPrintInformation for the following orders: " . print_r($orders,true) . "\n");
            
            $ordersWithTickets = 0;
            // at the end of this, we should have $tickets built.
            foreach ($orders as $oneOrder){
                // make $data have oneOrder in it befoe calling.

                //$returnList = getTicketPrintInformation($data);
                //$approved = $returnList[0];
                //$orderId = $returnList[1];
                //$errorMessage = $returnList[2];
                $_SESSION['request']->updateApiCommunication("about to GetTicketPrintInformation for this order: " . $oneOrder . "\n");
                $data['ordernumber'] = $oneOrder; 
                $data['headerdesign'] = '';
                $data['ticketdesign'] = '';
                $data['receipt'] = 'Y';
                
                $tempTickets = array();
                $tempTickets = getTicketPrintInformation($data);
                if ($tempTickets != null) {
                    $ordersWithTickets++;
                    $tickets = array_merge($tickets,$tempTickets);
                }
                
        //        if ($oneOrder == '407'){
        //            echo "<pre>";
        //            print_r($tickets);
        //            echo "</pre>";
        //            exit;
        //        }
            }
            if ($ordersWithTickets == 0) {
                errorPageWillCall("None of the orders matching this credit card has tickets.");
            }
//                echo "<pre>";
//                print_r($tickets);
//                echo "</pre>";
//                exit;
            // now, if $tickets is empty, then display error.
//            echo "<pre>";
//            print_r($tickets);
//            echo "</pre>";
//            exit;
            if (!empty($tickets)){
                $javaFgl = getFgl($tickets); // getFgl will loop through tickets.
                willcallPrinting($javaFgl);
            } else {
                 errorPageWillCall("No tickets found matching this credit card number. Please see a ticket desk for assistance.");
            }



        } else {
            //exit("last name did not match");
            errorPageWillCall($errorMessage);
        }

        
        
    
    
}

function salesPrinting($javaFgl){
    
//    exit("fgl = " . $javaFgl);
    
    //$_SESSION['output'] .= "Done Time: " . date('m/d/Y H:i:s');
    //exit($_SESSION['output']);
    
//    if (strlen($javaFgl) < 1) {
//        // now we build our test Fgl.
//        
////        $javaFgl = '
////        var tkts = [
////        "<F2><HW1,1><NR><RC52,20>01/02/2015   <F2><HW1,1><NR><RC52,766>Date         <F2><HW1,1><NR><RC52,860>   01/02/2015<F2><HW1,1><NR><RC80,20>10:01am      <F2><HW1,1><NR><RC80,766>Time         <F2><HW1,1><NR><RC80,860>      10:01am<F2><HW1,1><NR><RC116,20>GEN-WEB9     <F2><HW1,1><NR><RC116,766>Item         <F2><HW1,1><NR><RC116,860>     GEN-WEB9<F2><HW1,1><NR><RC144,20>GA           <F2><HW1,1><NR><RC144,766>Category     <F2><HW1,1><NR><RC144,860>           GA<F2><HW1,1><NR><RC172,20>Student      <F2><HW1,1><NR><RC172,766>Level        <F2><HW1,1><NR><RC172,860>      Student<F2><HW1,1><NR><RC200,20>$23.00       <F2><HW1,1><NR><RC200,766>Price        <F2><HW1,1><NR><RC200,860>       $23.00<F2><HW1,1><NR><RC236,20>1721453      <F2><HW1,1><NR><RC236,766>Customer     <F2><HW1,1><NR><RC236,860>      1721453<F2><HW1,1><NR><RC264,20>2407887      <F2><HW1,1><NR><RC264,766>Order        <F2><HW1,1><NR><RC264,860>      2407887<F2><HW1,1><NR><RC292,20>9178241      <F2><HW1,1><NR><RC292,766>Transaction  <F2><HW1,1><NR><RC292,860>      9178241<F2><HW1,1><NR><RC320,20>1081124      <F2><HW1,1><NR><RC320,766>Cashier      <F2><HW1,1><NR><RC320,860>      1081124<F8><HW2,1><NR><RC0,180>Museum Admission            <F2><HW2,1><NR><RC60,180>All-day access to galleries and special exhibitions.     <F2><HW2,1><NR><RC106,180>                                                         <F8><HW2,1><NR><RC156,180>FRIDAY, JANUARY 02, 2015    <F2><HW1,1><NR><RC360,180>Includes one free repeat visit to the MFA Galeries within ten days.        <p>"
////        ];';
//        
//        $javaFgl = '
//        var tkts = [
//        "<F8><HW2,1><NR><RC10,50>Museum Admission            <F2><HW2,1><NR><RC90,50>All-day access to galleries and special exhibitions.
//<F2><HW1,1><NR><RC130,50>Includes one free repeat visit to the MFA Galleries within ten days.                                         <F8><HW2,1><NR><RC180,50>Tuesday, July 14, 2015
//<F2><HW2,1><NR><RC300,50>Adult Admission  <F2><HW2,1><NR><RC360,70>4820   <F2><HW2,1><NR><RC360,100> |   0     |   6936  |  ADU   |  25.00
//
//<F2><HW2,1><RC20,850>Order Date    <F2><HW2,1><RC20,1050> 07/14/15
//<F2><HW2,1><RC50,850>Order #       <F2><HW2,1><RC50,1050> 4820
//<F2><HW2,1><RC80,850>Customer #    <F2><HW2,1><RC80,1050> 12220
//<F2><HW2,1><RC110,850>Ticket #     <F2><HW2,1><RC110,1050> 6948
//
//
// <p>"        ];';
//        
//        
//    }   
    
    $content = '    <!-- Content -->';
    
    $content .= '   
    <style>
        ::-webkit-scrollbar{display:none;}
    </style>
    <script src="printTkts/jquery.min.js"></script>
    <script>  



        ' . $javaFgl . '
        var totalTickets = tkts.length;
        var currentTicket = 0;
        var kiosk_name = ' . $_SESSION['kiosk'] . ';
        var client_name = ' . $_SESSION['client'] . ';




        function printNext(){
                printTkt(tkts[currentTicket], currentTicket+1, totalTickets);
                currentTicket++;
                if (currentTicket == 1){
                    //print receipt also.
                    //alert("rctpFgl: " + rcptFgl);
                    //printReceipt(rcptFgl);
                }
                if (currentTicket < totalTickets){
                    window.setTimeout("printNext()", 2500);
                } else if (currentTicket == totalTickets) {
                    window.setTimeout("location.href=\"' . $_SESSION['startUrl'] . '\";",10000);
                } else {
                    window.setTimeout(5000);
                    location.href="' . $_SESSION['startUrl'] . '";
                }   
            }


        function printTkt(fgl, currentTicket, totalTickets){
            kiosk = kiosk_name;
            client = client_name;

            document.getElementById(\'printingMessage\').innerHTML = "Printing " + currentTicket + " of " + totalTickets + "...";
                $.ajax({
                    url : "http://localhost/printTkts/printTktsWindows.php",
                    type: "POST",
                    data : "fgl=" + fgl + "&client=" + client + "&kiosk=" + kiosk,
                    success: function(data, textStatus, jqXHR)
                    {
                        document.getElementById(\'printingMessage\').innerHTML = "Printing " + currentTicket + " of " + totalTickets + "...";
                    },
                    error: function(xhr, status, error) {
                        var err = eval("(" + xhr.responseText + ")");
                        document.write(err.Message);
                    }
                });
        }
    </script>

        ';
    
    
    $content .= ' 
        <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
        <center>
            <table class="thankYou">
                <tr>
                    <td>
                        THANK YOU<br>Your tickets are printing.
                    </td>
                </tr>
                <tr>
                    <td>
                        <center>
                            <table height=50px >
                                <tr class="thankYou2">
                                    <td width=350 align=center><div align="center" id=printingMessage class="ticket-type-description text-lg flush pull-left sp-in-30px-top sp-in-20px-bottom ng-binding"></div></td>
                                </tr>
                            </table>
                        </center>
                    </td>
                </tr>
            </table>
            <script>window.setTimeout("printNext()", 2000);</script>
        </center>  
                 ';
    
    $_SESSION['backgroundCss'] = "background-image:url('images/thankYouBackground2.jpg');";
    printHtmlStuffWillCall('salesPrinting', $content, false);
}


function willcallPrinting($javaFgl){   
    //$_SESSION['output'] .= "Done Time: " . date('m/d/Y H:i:s');
    //exit($_SESSION['output']);
    
//    if (strlen($javaFgl) < 1) {
//        // now we build our test Fgl.
//        
////        $javaFgl = '
////        var tkts = [
////        "<F2><HW1,1><NR><RC52,20>01/02/2015   <F2><HW1,1><NR><RC52,766>Date         <F2><HW1,1><NR><RC52,860>   01/02/2015<F2><HW1,1><NR><RC80,20>10:01am      <F2><HW1,1><NR><RC80,766>Time         <F2><HW1,1><NR><RC80,860>      10:01am<F2><HW1,1><NR><RC116,20>GEN-WEB9     <F2><HW1,1><NR><RC116,766>Item         <F2><HW1,1><NR><RC116,860>     GEN-WEB9<F2><HW1,1><NR><RC144,20>GA           <F2><HW1,1><NR><RC144,766>Category     <F2><HW1,1><NR><RC144,860>           GA<F2><HW1,1><NR><RC172,20>Student      <F2><HW1,1><NR><RC172,766>Level        <F2><HW1,1><NR><RC172,860>      Student<F2><HW1,1><NR><RC200,20>$23.00       <F2><HW1,1><NR><RC200,766>Price        <F2><HW1,1><NR><RC200,860>       $23.00<F2><HW1,1><NR><RC236,20>1721453      <F2><HW1,1><NR><RC236,766>Customer     <F2><HW1,1><NR><RC236,860>      1721453<F2><HW1,1><NR><RC264,20>2407887      <F2><HW1,1><NR><RC264,766>Order        <F2><HW1,1><NR><RC264,860>      2407887<F2><HW1,1><NR><RC292,20>9178241      <F2><HW1,1><NR><RC292,766>Transaction  <F2><HW1,1><NR><RC292,860>      9178241<F2><HW1,1><NR><RC320,20>1081124      <F2><HW1,1><NR><RC320,766>Cashier      <F2><HW1,1><NR><RC320,860>      1081124<F8><HW2,1><NR><RC0,180>Museum Admission            <F2><HW2,1><NR><RC60,180>All-day access to galleries and special exhibitions.     <F2><HW2,1><NR><RC106,180>                                                         <F8><HW2,1><NR><RC156,180>FRIDAY, JANUARY 02, 2015    <F2><HW1,1><NR><RC360,180>Includes one free repeat visit to the MFA Galeries within ten days.        <p>"
////        ];';
//        
//        $javaFgl = '
//        var tkts = [
//        "<F8><HW2,1><NR><RC10,50>Museum Admission            <F2><HW2,1><NR><RC90,50>All-day access to galleries and special exhibitions.
//<F2><HW1,1><NR><RC130,50>Includes one free repeat visit to the MFA Galleries within ten days.                                         <F8><HW2,1><NR><RC180,50>Tuesday, July 14, 2015
//<F2><HW2,1><NR><RC300,50>Adult Admission  <F2><HW2,1><NR><RC360,70>4820   <F2><HW2,1><NR><RC360,100> |   0     |   6936  |  ADU   |  25.00
//
//<F2><HW2,1><RC20,850>Order Date    <F2><HW2,1><RC20,1050> 07/14/15
//<F2><HW2,1><RC50,850>Order #       <F2><HW2,1><RC50,1050> 4820
//<F2><HW2,1><RC80,850>Customer #    <F2><HW2,1><RC80,1050> 12220
//<F2><HW2,1><RC110,850>Ticket #     <F2><HW2,1><RC110,1050> 6948
//
//
// <p>"        ];';
//        
//        
//    }   
    
    $content = '    <!-- Content -->';
    
    $content .= '   
    <style>
        ::-webkit-scrollbar{display:none;}
    </style>
    <script src="printTkts/jquery.min.js"></script>
        <script> 
        function hideProcessing() {
            document.getElementById("timerDiv").style.visibility="hidden";
        }
        
        var currentTicket = 0;

        ' . $javaFgl . '

        var totalTickets = tkts.length;     
        function printNext(){
            printTkt(tkts[currentTicket], currentTicket+1, totalTickets);
            //alert(tkts[currentTicket]);
            currentTicket++;
            if (currentTicket < totalTickets){
                window.setTimeout("printNext()", 1500);
            } else if (currentTicket == totalTickets) {
                window.setTimeout("printNext();", 5000);
            } else {
                //location.href="?task=willcall";
                location.href="' . $_SESSION['startUrl'] . '";
            }
        }
        function printTkt(fgl, currentTicket, totalTickets){
        
            //ignore "undefined message
            if (typeof(fgl) != "undefined"){

                document.getElementById(\'printingMessage\').innerHTML = "Printing " + currentTicket + " of " + totalTickets + "...";
                $.ajax({
                    url : "http://localhost/printTkts/printTkts.php",
                    type: "POST",
                    data : "fgl=" + fgl,
                    success: function(data, textStatus, jqXHR)
                    {
                        document.getElementById(\'printingMessage\').innerHTML = "Printing " + currentTicket + " of " + totalTickets + "...";
                    },
                    error: function(xhr, status, error) {
                        var err = eval("(" + xhr.responseText + ")");
                        document.write(err.Message);
                    }
                    /*error: function (jqXHR, textStatus, errorThrown)
                    {
                        document.write("ERROR");
                        document.write("jqXHR: " + jqXHR);
                        document.write("textStatus: " + textStatus);
                        document.write("errorThrown: " + errorThrown);
                    }*/
                });
            }    
        }

        </script>    
        ';
    
    $content .= ' 
        <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
        <center>
            <table class="thankYou">
                <tr>
                    <td>
                        THANK YOU <br>
                        Your tickets are printing.
                    </td>
                </tr>
                <tr>
                    <td>
                        <center>
                            <table height=50px >
                                <tr class="thankYou2">
                                    <!--<td width=250 align=center><img onload="hideProcessing()"></td> -->
                                    <td width=350 align=center><div align="center" id=printingMessage class="ticket-type-description text-lg flush pull-left sp-in-30px-top sp-in-20px-bottom ng-binding"></div></td>
                                </tr>
                            </table>
                        </center>
                    </td>
                </tr>
            </table>
            <script>window.setTimeout("printNext()", 2000);</script>
        </center>  
                 ';
    
    $_SESSION['backgroundCss'] = "background-image:url('images/thankYouBackground2.jpg');";
    printHtmlStuffWillCall('willcallPrinting', $content, false);
}


function willcallPrintingOld($javaFgl){   
    //$_SESSION['output'] .= "Done Time: " . date('m/d/Y H:i:s');
    //exit($_SESSION['output']);
    
//    if (strlen($javaFgl) < 1) {
//        // now we build our test Fgl.
//        
////        $javaFgl = '
////        var tkts = [
////        "<F2><HW1,1><NR><RC52,20>01/02/2015   <F2><HW1,1><NR><RC52,766>Date         <F2><HW1,1><NR><RC52,860>   01/02/2015<F2><HW1,1><NR><RC80,20>10:01am      <F2><HW1,1><NR><RC80,766>Time         <F2><HW1,1><NR><RC80,860>      10:01am<F2><HW1,1><NR><RC116,20>GEN-WEB9     <F2><HW1,1><NR><RC116,766>Item         <F2><HW1,1><NR><RC116,860>     GEN-WEB9<F2><HW1,1><NR><RC144,20>GA           <F2><HW1,1><NR><RC144,766>Category     <F2><HW1,1><NR><RC144,860>           GA<F2><HW1,1><NR><RC172,20>Student      <F2><HW1,1><NR><RC172,766>Level        <F2><HW1,1><NR><RC172,860>      Student<F2><HW1,1><NR><RC200,20>$23.00       <F2><HW1,1><NR><RC200,766>Price        <F2><HW1,1><NR><RC200,860>       $23.00<F2><HW1,1><NR><RC236,20>1721453      <F2><HW1,1><NR><RC236,766>Customer     <F2><HW1,1><NR><RC236,860>      1721453<F2><HW1,1><NR><RC264,20>2407887      <F2><HW1,1><NR><RC264,766>Order        <F2><HW1,1><NR><RC264,860>      2407887<F2><HW1,1><NR><RC292,20>9178241      <F2><HW1,1><NR><RC292,766>Transaction  <F2><HW1,1><NR><RC292,860>      9178241<F2><HW1,1><NR><RC320,20>1081124      <F2><HW1,1><NR><RC320,766>Cashier      <F2><HW1,1><NR><RC320,860>      1081124<F8><HW2,1><NR><RC0,180>Museum Admission            <F2><HW2,1><NR><RC60,180>All-day access to galleries and special exhibitions.     <F2><HW2,1><NR><RC106,180>                                                         <F8><HW2,1><NR><RC156,180>FRIDAY, JANUARY 02, 2015    <F2><HW1,1><NR><RC360,180>Includes one free repeat visit to the MFA Galeries within ten days.        <p>"
////        ];';
//        
//        $javaFgl = '
//        var tkts = [
//        "<F8><HW2,1><NR><RC10,50>Museum Admission            <F2><HW2,1><NR><RC90,50>All-day access to galleries and special exhibitions.
//<F2><HW1,1><NR><RC130,50>Includes one free repeat visit to the MFA Galleries within ten days.                                         <F8><HW2,1><NR><RC180,50>Tuesday, July 14, 2015
//<F2><HW2,1><NR><RC300,50>Adult Admission  <F2><HW2,1><NR><RC360,70>4820   <F2><HW2,1><NR><RC360,100> |   0     |   6936  |  ADU   |  25.00
//
//<F2><HW2,1><RC20,850>Order Date    <F2><HW2,1><RC20,1050> 07/14/15
//<F2><HW2,1><RC50,850>Order #       <F2><HW2,1><RC50,1050> 4820
//<F2><HW2,1><RC80,850>Customer #    <F2><HW2,1><RC80,1050> 12220
//<F2><HW2,1><RC110,850>Ticket #     <F2><HW2,1><RC110,1050> 6948
//
//
// <p>"        ];';
//        
//        
//    }   
    
    $content = '    <!-- Content -->';
    
    $content .= '   
    <style>
        ::-webkit-scrollbar{display:none;}
    </style>
    <script src="printTkts/jquery.min.js"></script>
        <script> 
        function hideProcessing() {
            document.getElementById("timerDiv").style.visibility="hidden";
        }
        
        var currentTicket = 0;

        ' . $javaFgl . '

        var totalTickets = tkts.length;     
        function printNext(){
            printTkt(tkts[currentTicket], currentTicket+1, totalTickets);
            //alert(tkts[currentTicket]);
            currentTicket++;
            if (currentTicket < totalTickets){
                window.setTimeout("printNext()", 1500);
            } else if (currentTicket == totalTickets) {
                window.setTimeout("printNext();", 5000);
            } else {
                //location.href="?task=willcall";
                location.href="' . $_SESSION['startUrl'] . '";
            }
        }
        function printTkt(fgl, currentTicket, totalTickets){
        
            //ignore "undefined message
            if (typeof(fgl) != "undefined"){

                document.getElementById(\'printingMessage\').innerHTML = "Printing " + currentTicket + " of " + totalTickets + "...";
                $.ajax({
                    url : "http://localhost/printTkts/printTkts.php",
                    type: "POST",
                    data : "fgl=" + fgl,
                    success: function(data, textStatus, jqXHR)
                    {
                        document.getElementById(\'printingMessage\').innerHTML = "Printing " + currentTicket + " of " + totalTickets + "...";
                    },
                    error: function(xhr, status, error) {
                        var err = eval("(" + xhr.responseText + ")");
                        document.write(err.Message);
                    }
                    /*error: function (jqXHR, textStatus, errorThrown)
                    {
                        document.write("ERROR");
                        document.write("jqXHR: " + jqXHR);
                        document.write("textStatus: " + textStatus);
                        document.write("errorThrown: " + errorThrown);
                    }*/
                });
            }    
        }

        </script>    
        ';
    
    $content .= ' 
    <section class="app-content bleed" ng-class="{ "container": breakpoint == "lg", "bleed": (fullscreen || bleed), "full-height": location.path() == "/welcome" }">
        <br><br>
        <div class="hidden-xs col-sm-3 col-md-3 col-lg-3"></div>
        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 text-center well" ng-class="{ "well": breakpoint != "xs" }">
        <br class="hidden-sm hidden-md hidden-lg">
        <h1 ng-class="{"text-xlg": breakpoint == "xs", "heading-xxl": breakpoint != "xs" }" class="ng-binding heading-xxl"><center>Thank you!</center></h1>
        <h2 class="sp-out-30px-bottom heading-xl ng-binding"><center>Your ticket(s) have been found and are now printing!</center></h2>
        <br><hr>
        <center>
            <table>
                <tr>
                    <td width=250 align=center><img onload="hideProcessing()" src="site_files/arrow-pointing-down.png" width="50%" height="65%"></td>
                    <td width=350 align=center><div align="center" id=printingMessage class="ticket-type-description text-lg flush pull-left sp-in-30px-top sp-in-20px-bottom ng-binding"></div></td>
                    <td width=250 align=center><img src="site_files/arrow-pointing-down.png" width="50%" height="65%"></td>
                </tr>
            </table>
        </center>
        <script>window.setTimeout("printNext()", 2000);</script>
        </section>';
    
    printHtmlStuffWillCall('willcallPrinting', $content, false);
}




function getFgl($tickets){
//    exit("here");
    
    $javaFgl = '
        var tkts = [ ' . "\n";
        
    // loop through tickets  $tickets
    
    //$design2068 = '"<F8><HW2,1><NR><RC10,30>~perfDescription~  <F8><HW2,1><NR><RC70,30>~perfInfo1~ <F8><HW2,1><NR><RC130,30>~perfBeginTime~ <F8><HW2,1><NR><RC200,30>~perfDate~  <F2><HW2,1><NR><RC300,30>~priceTypeDescription~  <F2><HW2,1><NR><RC360,70>~orderNo~   <F2><HW2,1><NR><RC360,100>    ~constituentId~        ~ticketNo~    ~priceTypeShortDesc~     ~ticketPrice~  <F2><HW2,1><RC20,850>Order Date    <F2><HW2,1><RC20,1050> ~orderDate~  <F2><HW2,1><RC50,850>Order #       <F2><HW2,1><RC50,1050> ~orderNo~  <F2><HW2,1><RC80,850>Customer #    <F2><HW2,1><RC80,1050> ~constituentId~  <F2><HW2,1><RC110,850>Ticket #     <F2><HW2,1><RC110,1050> ~ticketNo~  <F2><HW2,1><RC140,1050>~priceTypeShortDesc~  <F2><HW2,1><RC170,1070>~ticketPrice~  <F2><HW2,1><RC450,850><RL>~perfDescription~  <F2><HW2,1><RC450,890><RL>~perfCode~  <F2><HW2,1><RC450,930><RL>~perfDate~  <F2><HW1,1><RC350,1230><X3><FL9><BI>:~ticketNo~: <p>"';
    //- from text file: we know this one works.. $design2068 = '"<F8><HW2,1><NR><RC10,30>Near Death Experience <F8><HW2,1><NR><RC70,30>Remis Auditorium <F8><HW2,1><NR><RC130,30>3:30 PM<F8><HW2,1><NR><RC200,30>Thursday, July 16, 2015<F2><HW2,1><NR><RC300,30>Film Nonmember  <F2><HW2,1><NR><RC360,70>4820   <F2><HW2,1><NR><RC360,100> |   0     |   6936  |  ADU   |  25.00<F2><HW2,1><RC20,850>Order Date    <F2><HW2,1><RC20,1050> 07/16/15<F2><HW2,1><RC50,850>Order #       <F2><HW2,1><RC50,1050> 4821<F2><HW2,1><RC80,850>Customer #    <F2><HW2,1><RC80,1050> 12220<F2><HW2,1><RC110,850>Ticket #     <F2><HW2,1><RC110,1050> 6937<F2><HW2,1><RC140,1050>FL NM<F2><HW2,1><RC170,1070>$11.00<F2><HW2,1><RC450,850><RL>Near Death Experience<F2><HW2,1><RC450,890><RL>F150716F12<F2><HW2,1><RC450,930><RL>07/16/15<F2><HW1,1><RC350,1230><X3><FL9><BI>:6937:<p>"';
//    $design2068 = '"<F8><HW2,1><NR><RC10,30>~perfDescription~ <F8><HW2,1><NR><RC70,30>~perfInfo1~ <F8><HW2,1><NR><RC130,30>~perfBeginTime~<F8><HW2,1><NR><RC200,30>~perfDateLong~<F2><HW2,1><NR><RC300,30>~priceTypeDesc~  <F2><HW2,1><NR><RC360,70>~orderNo~   <F2><HW2,1><NR><RC360,100>     :   ~constituentId~     :   ~ticketNo~  :  ~priceTypeShortDesc~   :  ~ticketPrice~   <F2><HW2,1><RC20,850>Order Date    <F2><HW2,1><RC20,1050> ~orderDate~<F2><HW2,1><RC50,850>Order #       <F2><HW2,1><RC50,1050> ~orderNo~<F2><HW2,1><RC80,850>Customer #    <F2><HW2,1><RC80,1050> ~constituentId~<F2><HW2,1><RC110,850>Ticket #     <F2><HW2,1><RC110,1050> ~ticketNo~  <F2><HW2,1><RC140,1050>~priceTypeShortDesc~  <F2><HW2,1><RC170,1050>~ticketPrice~  <F2><HW2,1><RC450,850><RL>~perfDescription~<F2><HW2,1><RC450,890><RL>~perfCode~<F2><HW2,1><RC450,930><RL>~perfDate~<F2><HW1,1><RC50,1210><NXL5><X3><BI>*~ticketNo~*<p>"';
//    $design2071 = '"<F8><HW2,1><NR><RC10,30>~pkgDescription~  <F8><HW2,1><NR><RC70,30>Location:  <F8><HW2,1><NR><RC70,220>~perfInfo1~  <F8><HW2,1><NR><RC130,30>Time:      <F8><HW2,1><NR><RC130,150>~perfBeginTime~    <F2><HW2,1><NR><RC210,30>~perfDate0~  <F2><HW2,1><NR><RC250,30>~perfDate1~  <F2><HW2,1><NR><RC210,170>~perfDate2~  <F2><HW2,1><NR><RC250,170>~perfDate3~   <F2><HW2,1><NR><RC210,310>~perfDate4~  <F2><HW2,1><NR><RC250,310>~perfDate5~  <F2><HW2,1><NR><RC210,450>~perfDate6~  <F2><HW2,1><NR><RC250,450>~perfDate7~    <F2><HW2,1><NR><RC210,590>~perfDate8~  <F2><HW2,1><NR><RC250,590>~perfDate9~        <F2><HW2,1><NR><RC300,30>~priceTypeDesc~  <F2><HW2,1><NR><RC360,70>~orderNo~   <F2><HW2,1><NR><RC360,100>     :   ~constituentId~    :    ~ticketNo~  :  ~priceTypeShortDesc~  :   ~ticketPrice~  <F2><HW2,1><RC20,850>Order Date    <F2><HW2,1><RC20,1050> ~orderDate~  <F2><HW2,1><RC50,850>Order #       <F2><HW2,1><RC50,1050> ~orderNo~  <F2><HW2,1><RC80,850>Customer #    <F2><HW2,1><RC80,1050> ~constituentId~  <F2><HW2,1><RC50,850>Ticket #     <F2><HW2,1><RC110,1050> ~ticketNo~  <F2><HW2,1><RC140,1050>~priceTypeShortDesc~  <F2><HW2,1><RC170,1050>~ticketPrice~<p>"';
//    $design2073 = '"<F8><HW2,1><NR><RC10,30>~perfDescription~   <F2><HW2,1><NR><RC90,30>All-day access to galleries and special exhibitions.  <F2><HW1,1><NR><RC130,30>Includes one free repeat visit to the MFA Galleries within ten days.    <F8><HW2,1><NR><RC180,30>~perfDateLong~<F2><HW2,1><NR><RC300,30>~priceTypeDesc~  <F2><HW2,1><NR><RC360,70>~orderNo~   <F2><HW2,1><NR><RC360,100>     :  ~constituentId~    :    ~ticketNo~  :  ~priceTypeShortDesc~  :   ~ticketPrice~  <F2><HW2,1><RC20,850>Order Date    <F2><HW2,1><RC20,1050> ~orderDate~<F2><HW2,1><RC50,850>Order #       <F2><HW2,1><RC50,1050> ~orderNo~<F2><HW2,1><RC80,850>Customer #    <F2><HW2,1><RC80,1050> ~constituentId~<F2><HW2,1><RC110,850>Ticket #     <F2><HW2,1><RC110,1050> ~ticketNo~<F2><HW2,1><RC140,1050>~perfCode~<F2><HW2,1><RC170,1070>~priceTypeShortDesc~<F2><HW2,1><RC200,1060>~ticketPrice~<F2><HW1,1><RC50,1210><NXL5><X3><BI>*~ticketNo~*<p>"';
//    $design2078 = '"<F8><HW2,1><NR><RC10,30>~perfDescription~  <F2><HW2,1><NR><RC90,30>All-day access to galleries and special exhibitions.   <F2><HW1,1><NR><RC130,30>Includes one free repeat visit to the MFA Galleries within ten days.   <F2><HW2,1><NR><RC160,30>MUST BE 21+ (with Valid ID) TO ATTEND EVENT <F8><HW2,1><NR><RC180,30>~perfDateLong~   <F2><HW2,1><NR><RC300,30>~priceTypeDesc~  <F2><HW2,1><NR><RC360,70>~orderNo~   <F2><HW2,1><NR><RC360,100>     :  ~constituentId~    :    ~ticketNo~  :  ~priceTypeShortDesc~  :   ~ticketPrice~  <F2><HW2,1><RC20,850>Order Date    <F2><HW2,1><RC20,1050>~orderDate~   <F2><HW2,1><RC50,850>Order #       <F2><HW2,1><RC50,1050>~orderNo~   <F2><HW2,1><RC80,850>Customer #    <F2><HW2,1><RC80,1050> ~constituentId~   <F2><HW2,1><RC110,850>Ticket #     <F2><HW2,1><RC110,1050>~ticketNo~   <F2><HW2,1><RC140,1050>~perfCode~   <F2><HW2,1><RC170,1070>~priceTypeShortDesc~   <F2><HW2,1><RC200,1060>~ticketPrice~   <F2><HW1,1><RC50,1210><NXL5><X3><BI>*~ticketNo~*<p>"';
//    $design2080 = '"<F8><HW2,1><NR><RC10,30>~perfDescription~   <F2><HW2,1><NR><RC90,30>All-day access to galleries and special exhibitions.   <F2><HW2,1><NR><RC130,30>MUST BE 21+ (with Valid ID) TO ATTEND EVENT   <F2><HW2,2><NR><RC165,30>~constituentDisplayName~   <F2><HW2,1><NR><RC200,30>Mem Lvl:  ~userDefinedElement1~       <F2><HW2,1><NR><RC185,550>Exp Date: ~userDefinedElement2~   <F8><HW2,1><NR><RC220,30>~perfDateLong~   <F2><HW2,1><NR><RC300,30>~priceTypeDesc~  <F2><HW2,1><NR><RC360,70>~orderNo~   <F2><HW2,1><NR><RC360,100>     :  ~constituentId~    :    ~ticketNo~  :  ~priceTypeShortDesc~  :   ~ticketPrice~  <F2><HW2,1><RC20,850>Order Date    <F2><HW2,1><RC20,1050>~orderDate~   <F2><HW2,1><RC50,850>Order #       <F2><HW2,1><RC50,1050>~orderNo~   <F2><HW2,1><RC80,850>Customer #    <F2><HW2,1><RC80,1050> ~constituentId~   <F2><HW2,1><RC110,850>Ticket #     <F2><HW2,1><RC50,1050>~ticketNo~   <F2><HW2,1><RC140,850>~perfCode~   <F2><HW2,1><RC140,1060>~priceTypeShortDesc~   <F2><HW2,1><RC170,1060>~ticketPrice~   <F2><HW1,1><RC50,1210><NXL5><X3><BI>*~ticketNo~*<p>"';
//    $design2087 = '"<F8><HW2,1><NR><RC10,30>~perfDescription~  <F2><HW2,1><NR><RC90,30>Redeem at ticket desk for one MFA Guide.  <F2><HW2,1><NR><RC140,30>~priceZoneDesc~  <F2><HW2,1><NR><RC300,30>~priceTypeDesc~  <F2><HW2,1><NR><RC360,70>~orderNo~   <F2><HW2,1><NR><RC360,100>     :   ~constituentId~     :   ~ticketNo~  :  ~priceTypeShortDesc~   :  ~ticketPrice~   <F2><HW2,1><RC20,850>Order Date    <F2><HW2,1><RC20,1050> ~orderDate~  <F2><HW2,1><RC50,850>Order #       <F2><HW2,1><RC50,1050> ~orderNo~  <F2><HW2,1><RC80,850>Customer #    <F2><HW2,1><RC80,1050> ~constituentId~  <F2><HW2,1><RC110,850>Ticket #     <F2><HW2,1><RC110,1050> ~ticketNo~  <F2><HW2,1><RC140,850>~perfCode~  <F2><HW2,1><RC140,1070>~priceTypeShortDesc~  <F2><HW2,1><RC170,1060>~ticketPrice~  <F2><HW1,1><RC50,1210><NXL5><X3><BI>*~ticketNo~*<p>"';
//    $design2096 = '"<F8><HW2,1><NR><RC10,30>~perfDescription~  <F8><HW2,1><NR><RC70,30>Ticket admits Participant and Chaperone.  <F8><HW2,1><NR><RC130,30>~perfBeginTime~  <F8><HW2,1><NR><RC200,30>~perfDateLong~  <F2><HW2,1><NR><RC300,30>~priceTypeDesc~  <F2><HW2,1><NR><RC360,70>~orderNo~   <F2><HW2,1><NR><RC360,100>     :   ~constituentId~     :   ~ticketNo~  :  ~priceTypeShortDesc~   :  ~ticketPrice~   <F2><HW2,1><RC20,850>Order Date    <F2><HW2,1><RC20,1050> ~orderDate~  <F2><HW2,1><RC50,850>Order #       <F2><HW2,1><RC50,1050> ~orderNo~  <F2><HW2,1><RC80,850>Customer #    <F2><HW2,1><RC80,1050> ~constituentId~  <F2><HW2,1><RC110,850>Ticket #     <F2><HW2,1><RC110,1050> ~ticketNo~  <F2><HW2,1><RC140,1050>~priceTypeShortDesc~  <F2><HW2,1><RC170,1060>~ticketPrice~  <F2><HW2,1><RC450,850><RL>~priceTypeDesc~  <F2><HW2,1><RC450,890><RL>~perfCode~  <F2><HW2,1><RC450,930><RL>~perfDate~  <F2><HW1,1><RC50,1210><NXL5><X3><BI>*~ticketNo~*<p>"';
//    $design2097 = '"<F8><HW2,1><NR><RC10,30>~pkgDescription~  <F8><HW2,1><NR><RC70,30>Location:  <F8><HW2,1><NR><RC70,220>~perfInfo1~<F8><HW2,1><NR><RC130,30>Time:      <F8><HW2,1><NR><RC130,150>~perfBeginTime~  <F8><HW2,1><NR><RC190,30>Ticket admits Participants and Chaperone.  <F2><HW2,1><NR><RC260,30>~perfDate0~  <F2><HW2,1><NR><RC260,170>~perfDate1~  <F2><HW2,1><NR><RC260,310>~perfDate2~  <F2><HW2,1><NR><RC260,450>~perfDate3~   <F2><HW2,1><NR><RC260,590>~perfDate4~  <F2><HW2,1><NR><RC300,30>~priceTypeDesc~  <F2><HW2,1><NR><RC360,70>~orderNo~   <F2><HW2,1><NR><RC360,100>     :  ~constituentId~    :    ~ticketNo~  :  ~priceTypeShortDesc~  :   ~ticketPrice~  <F2><HW2,1><RC20,850>Order Date    <F2><HW2,1><RC20,1050> ~orderDate~  <F2><HW2,1><RC50,850>Order #       <F2><HW2,1><RC50,1050> ~orderNo~  <F2><HW2,1><RC80,850>Customer #    <F2><HW2,1><RC80,1050> ~constituentId~  <F2><HW2,1><RC110,850>Ticket #     <F2><HW2,1><RC50,1050> ~ticketNo~  <F2><HW2,1><RC140,1055>~priceTypeShortDesc~  <F2><HW2,1><RC170,1060>~ticketPrice~  <p>"';
//    $design2093 = '"<F8><HW2,1><NR><RC10,30>~perfDescription~   <F8><HW2,1><NR><RC90,30>Guest Of:  <F8><HW2,1><NR><RC150,30>~userDefinedElement1~    <F2><HW2,1><NR><RC225,30>Exp Date: ~userDefinedElement2~<F2><HW2,1><NR><RC300,30>~priceTypeDesc~  <F2><HW2,1><NR><RC360,70>~orderNo~   <F2><HW2,1><NR><RC360,100>     :  ~constituentId~    :    ~ticketNo~  :  ~priceTypeShortDesc~  :     <F2><HW2,1><RC20,850>Order Date    <F2><HW2,1><RC20,1050> ~orderDate~<F2><HW2,1><RC50,850>Order #       <F2><HW2,1><RC50,1050> ~orderNo~<F2><HW2,1><RC80,850>Customer #    <F2><HW2,1><RC80,1050> ~constituentId~<F2><HW2,1><RC110,850>Ticket #     <F2><HW2,1><RC110,1050> ~ticketNo~<F2><HW2,1><RC140,850>~perfCode~<F2><HW2,1><RC140,1050> ~priceTypeShortDesc~<F2><HW1,1><RC50,1210><NXL5><X3><BI>*~ticketNo~*<p>"';
//    $design2094 = '"<F8><HW2,1><NR><RC10,30>~perfDescription~   <F2><HW2,1><NR><RC90,30>All-day access to galleries and special exhibitions.  <F2><HW1,1><NR><RC130,30>  <F8><HW2,1><NR><RC180,30>~perfDateLong~<F2><HW2,1><NR><RC300,30>~priceTypeDesc~  <F2><HW2,1><NR><RC360,70>~orderNo~   <F2><HW2,1><NR><RC360,100>     :  ~constituentId~    :    ~ticketNo~  :  ~priceTypeShortDesc~  :   ~ticketPrice~  <F2><HW2,1><RC20,850>Order Date    <F2><HW2,1><RC20,1050> ~orderDate~<F2><HW2,1><RC50,850>Order #       <F2><HW2,1><RC50,1050> ~orderNo~<F2><HW2,1><RC80,850>Customer #    <F2><HW2,1><RC80,1050> ~constituentId~<F2><HW2,1><RC110,850>Ticket #     <F2><HW2,1><RC110,1050> ~ticketNo~<F2><HW2,1><RC140,1050>~perfCode~<F2><HW2,1><RC170,1070>~priceTypeShortDesc~<F2><HW2,1><RC200,1060>~ticketPrice~<F2><HW1,1><RC50,1210><NXL5><X3><BI>*~ticketNo~*<p>"';
    
    
    $design2076 = '"<F3><HW2,1><NR><RC250,30>~perfDescription~ <F8><HW2,1><NR><RC300,30>~perfInfo1~ <F8><HW2,1><NR><RC350,30>~perfBeginTime~<F8><HW2,1><NR><RC400,30>~perfDateLong~<F8><HW2,1><NR><RC450,30>~priceTypeDesc~  <F8><HW2,1><NR><RC510,70>OrderNo: ~orderNo~   <F8><HW2,1><NR><RC560,70>TicketNo: ~ticketNo~           ~ticketPrice~   <F2><HW2,1><RC250,550>Order Date    <F2><HW2,1><RC250,750> ~perfDate~<F2><HW2,1><RC280,550>Order #       <F2><HW2,1><RC280,750> ~orderNo~<F2><HW2,1><RC330,550>Ticket #     <F2><HW2,1><RC330,750> ~ticketNo~  <F2><HW2,1><RC360,750>~priceTypeShortDesc~  <F2><HW2,1><RC390,750>~ticketPrice~ <F2><HW2,1><RC600,920><RL>~perfDescription~<F2><HW2,1><RC600,960><RL>~perfCode~<F2><HW2,1><RC450,960><RL>~perfDate~<F2><HW2,1><RC550,1000><RL>~barcodeNo~<F9><HW2,2><RC400,1100><OL7>^~barcodeNo~^<p>"';
    
    
    
    
    
    //exit("perfdate0: " . $perfDate0);
    
    //echo "<pre>";
    //print_r($perfDates);
    //echo "</pre>";
    //exit;
    
    
    
    
    foreach($tickets as $oneTicket){
//        echo "<pre>";
//        print_r($oneTicket);
//        echo "<?/pre>";
        

        $perfDates = array();
        $perfDates = explode(",", $oneTicket['perfDate']);


        $perfDate0 = "";
        if (!empty($perfDates[0])) {
            $perfDate0 = date("m/d/y", strtotime($perfDates[0]));
        }
        $perfDate1 = "";
        if (!empty($perfDates[1])) {
            $perfDate1 = date("m/d/y", strtotime($perfDates[1]));
        }
        $perfDate2 = "";
        if (!empty($perfDates[2])) {
            $perfDate2 = date("m/d/y", strtotime($perfDates[2]));
        }
        $perfDate3 = "";
        if (!empty($perfDates[3])) {
            $perfDate3 = date("m/d/y", strtotime($perfDates[3]));
        }
        $perfDate4 = "";
        if (!empty($perfDates[4])) {
            $perfDate4 = date("m/d/y", strtotime($perfDates[4]));
        }
        $perfDate5 = "";
        if (!empty($perfDates[5])) {
            $perfDate5 = date("m/d/y", strtotime($perfDates[5]));
        }
        $perfDate6 = "";
        if (!empty($perfDates[6])) {
            $perfDate6 = date("m/d/y", strtotime($perfDates[6]));
        }
        $perfDate7 = "";
        if (!empty($perfDates[7])) {
            $perfDate7 = date("m/d/y", strtotime($perfDates[7]));
        }
        $perfDate8 = "";
        if (!empty($perfDates[8])) {
            $perfDate8 = date("m/d/y", strtotime($perfDates[8]));
        }
        $perfDate9 = "";
        if (!empty($perfDates[9])) {
            $perfDate9 = date("m/d/y", strtotime($perfDates[9]));
        }

        
        
        $ticketFgl = '';
        //$oneTicket['designNo'] = '2093'; //csn 20160506 testing only
        if (!empty($oneTicket['designNo'])) {
            //        $javaFgl .= "design #: " . $oneTicket['design_no'];
            if ($oneTicket['designNo'] == '2076' || $oneTicket['designNo'] == '2071'){
                $ticketFgl = $design2076;
            } else if ($oneTicket['designNo'] == '2019'){
                $ticketfgl = "";
            
//            } else if ($oneTicket['designNo'] == '2068'){
//                $ticketFgl = $design2068;
//            } elseif ($oneTicket['designNo'] == '2071' || $oneTicket['designNo'] == '2092' || $oneTicket['designNo'] == '2077'){
//                $ticketFgl = $design2071;
//            } elseif ($oneTicket['designNo'] == '2073'){
//                $ticketFgl = $design2073;   
//            } elseif ($oneTicket['designNo'] == '2078' || $oneTicket['designNo'] == '2013'){
//                $ticketFgl = $design2078;   
//            } elseif ($oneTicket['designNo'] == '2080'){
//                $ticketFgl = $design2080;   
//            } elseif ($oneTicket['designNo'] == '2087'){
//                $ticketFgl = $design2087;   
//            } elseif ($oneTicket['designNo'] == '2096'){
//                $ticketFgl = $design2096;   
//            } elseif ($oneTicket['designNo'] == '2097'){
//                $ticketFgl = $design2097;  
//            } elseif ($oneTicket['designNo'] == '2093'){
//                $ticketFgl = $design2093;  
//            } elseif ($oneTicket['designNo'] == '2094'){
//                $ticketFgl = $design2094;   
            } else {
                $_SESSION['request']->updateApiCommunication("\n\n\n\nINVALID DESIGN: " . $oneTicket['designNo'] . "\n\n\n");
            }    
        }
        
        

        
        if (empty($oneTicket['totalTicketPrice'])) {
            $oneTicket['totalTicketPrice'] = " ";
        } else {
            $oneTicket['totalTicketPrice'] = "$" . trim($oneTicket['totalTicketPrice']);
        }
        
//    echo "<pre>";
//    echo "ticketPrice: " ;
//    print_r($oneTicket['ticketPrice']);
//    echo "</pre>";
//    exit;
//    
   
        //$oneTicket['perfDate'] = date($oneTicket['perfDate'], 'g:ia \o\n l jS F Y');
        
        
        
        if (empty($oneTicket['pkgDescription'])){$oneTicket['pkgDescription'] = '';}
        if (empty($oneTicket['perfInfo1'])){$oneTicket['perfInfo1'] = '';}
        if (empty($oneTicket['perfBeginTime'])){$oneTicket['perfBeginTime'] = '';}
        if (empty($oneTicket['constituentDisplayName'])){$oneTicket['constituentDisplayName'] = '';}
        if (empty($oneTicket['userDefinedElement1'])){$oneTicket['userDefinedElement1'] = '';}
        if (empty($oneTicket['userDefinedElement2'])){$oneTicket['userDefinedElement2'] = '';}
        if (empty($oneTicket['priceZoneDesc'])){$oneTicket['priceZoneDesc'] = '';}
        if (empty($oneTicket['orderDate'])){$oneTicket['orderDate'] = '';}
        if (empty($oneTicket['perfDescription'])){$oneTicket['perfDescription'] = '';}
        if (empty($oneTicket['priceTypeDesc'])){$oneTicket['priceTypeDesc'] = '';}
        if (empty($oneTicket['constituentId'])){$oneTicket['constituentId'] = '';}
        if (empty($oneTicket['ticketNo'])){$oneTicket['ticketNo'] = '';}
        if (empty($oneTicket['barcodeNo'])){$oneTicket['barcodeNo'] = '';}
        if (empty($oneTicket['priceTypeShortDesc'])){$oneTicket['priceTypeShortDesc'] = '';}
        if (empty($oneTicket['perfCode'])){$oneTicket['perfCode'] = '';}
        
        
        $perfDate = date('l, F jS, Y', strtotime($oneTicket['perfDate']));
        $oneTicket['perfDate'] = date('m/d/y', strtotime($oneTicket['perfDate']));
        $oneTicket['orderDate'] = date('m/d/y', strtotime($oneTicket['orderDate']));
        //$oneTicket['userDefinedElement2'] = date('m/d/y', strtotime($oneTicket['userDefinedElement2']));
        $oneTicket['pkgDescription'] = str_replace('"', '', $oneTicket['pkgDescription']);
        $oneTicket['perfDescription'] = str_replace('"', '', $oneTicket['perfDescription']);       
        
        //exit($oneTicket['perfDate']);
        
        $find = array("~pkgDescription~", "~perfDescription~", "~perfInfo1~", "~perfBeginTime~", "~perfDate~", "~priceTypeDesc~", "~orderNo~", "~constituentId~", "~ticketNo~", "~priceTypeShortDesc~", "~ticketPrice~", "~orderDate~", "~orderNo~", "~perfCode~", "~perfDateLong~", "~perfDate0~", "~perfDate1~", "~perfDate2~", "~perfDate3~", "~perfDate4~", "~perfDate5~", "~perfDate6~", "~perfDate7~", "~perfDate8~", "~perfDate9~", "~constituentDisplayName~", "~userDefinedElement1~", "~userDefinedElement2~", "~priceZoneDesc~", "~barcodeNo~");
        $values = array($oneTicket['pkgDescription'], $oneTicket['perfDescription'], $oneTicket['perfInfo1'], $oneTicket['perfBeginTime'], $oneTicket['perfDate'], $oneTicket['priceTypeDesc'], $oneTicket['orderNo'], $oneTicket['constituentId'], $oneTicket['ticketNo'], $oneTicket['priceTypeShortDesc'], $oneTicket['totalTicketPrice'], $oneTicket['orderDate'], $oneTicket['orderNo'], $oneTicket['perfCode'], $perfDate, $perfDate0, $perfDate1, $perfDate2, $perfDate3, $perfDate4, $perfDate5, $perfDate6, $perfDate7, $perfDate8, $perfDate9, $oneTicket['constituentDisplayName'], $oneTicket['userDefinedElement1'], $oneTicket['userDefinedElement2'], $oneTicket['priceZoneDesc'], $oneTicket['barcodeNo']);
        $ticketFgl = str_replace($find, $values, $ticketFgl);
        
        //$ticketFgl = str_replace("~", " ", $ticketFgl);
        
        if (strlen($ticketFgl) > 2 ) {
            $javaFgl .= $ticketFgl . ",\n";
        }
//        echo $javaFgl;
        
    }
//    exit("done");
    
    $javaFgl = substr($javaFgl,0,-2);
    
    $javaFgl = str_replace('&', '%26', $javaFgl);
    
    $javaFgl .= ",\n";  //csn
    if (!empty($_SESSION['fglReceipt'])) {
        if ($_SESSION['fglReceipt'] > '') {
            $javaFgl .= $_SESSION['fglReceipt']; //csn
        }
    }
    $javaFgl .= '];';
//    echo "<textarea rows=80 cols=100>";
//    echo $javaFgl;
//    echo "</textarea>";
//    exit;    
    
    return $javaFgl;
}


function initLogin(){
    $_SESSION['loggedInTable'] = '';
    $_SESSION['logInMethod'] = '';
    $_SESSION['loginFirstName'] = '';
    $_SESSION['loginLastName'] = '';
    $_SESSION['loginEmail'] = '';
    $_SESSION['memberNumber'] = '';
    $_SESSION['email'] = '';
    $_SESSION['continueWithNonMemberPricing'] = false;
}




function validateMember(){
    
    $_SESSION['request'] = new Request();
    $_SESSION['request']->insert();
    
    
    initLogin();
    
    
    
    
    
    
    $_SESSION['memberNumber'] = '';
    
    
    $_SESSION['title'] = 'validateMember';
    // put all styles into the template...
    $_SESSION['javaScript'] = '
         
        

                <script>
                    
                    document.addEventListener("keypress", keyPressSwipe, false);
                    var textEntered = "";

                    var startKey = "[";
                    var endKey = "]";
                    var startKeyUnicode = 91;
                    var endKeyUnicode = 93;
                    
                    var collecting = false;

                    function keyPressSwipe(e) {
                        var unicode=e.charCode? e.charCode : e.keyCode;
                        var actualKey=String.fromCharCode(unicode);
                        
                        //alert("you pressed the " + actualKey + " key.");
                        //alert("unicode: " + unicode);
                        if (unicode == startKeyUnicode){
                            textEntered = "";
                            collecting = true;
                        } else if (unicode == endKeyUnicode) {
                                document.memberNumberForm.barcodeData.value = textEntered; 
                                collecting = false;
                                if (document.memberNumberForm.barcodeData.value.length > 10) {
                                    //document.getElementById("lastNameDiv").style.visibility="visible"; 
                                    //document.memberNumberForm.confirmationInput.readOnly = false;
                                    document.memberNumberForm.acctNbr.value.readOnly = false;
                                } else {
//                                    //alert("hi hi");
//                                    //alert("barcodeData: " + document.memberNumberForm.barcodeData.value);
//                                    document.memberNumberForm.acctNbr.value = document.memberNumberForm.barcodeData.value;
//                                    document.memberNumberForm.task.value="lookupByAcctNbr";
//                                    document.getElementById("timerDiv").style.visibility="visible";                         ' . "\n" . '
//                                    document.getElementById("processingMessage").innerHTML = "Please wait...";
//                                    setTimeout(function() {                                                                 ' . "\n" . '
//                                        //alert("about to submit");
//                                        document.memberNumberForm.submit();                                                          ' . "\n" . '
//                                        document.memberNumberForm.reset();                                                           ' . "\n" . '
//                                        return true;                                                                        ' . "\n" . '
//                                    },2500);                                                                                ' . "\n" . '
//                                    return true;   
                                    //alert("hi hi");
                                    //alert("barcodeData: " + document.memberNumberForm.barcodeData.value);
                                    document.getElementById("timerDiv").style.visibility="visible";                         ' . "\n" . '
                                    document.getElementById("processingMessage").innerHTML = "Please wait...";
                                    setTimeout(function() {                                                                 ' . "\n" . '
                                        //alert("about to submit");
                                        document.memberNumberForm.task.value="groups";
                                        document.memberNumberForm.acctNbr.value = document.memberNumberForm.barcodeData.value;
                                        document.memberNumberForm.submit();                                                          ' . "\n" . '
                                        return true;                                                                        ' . "\n" . '
                                    },2500);                                                                                ' . "\n" . '
                                }
                         } else {
                            textEntered += actualKey;
                            if (collecting){
                                //document.memberNumberForm.confirmationInput.value = "";
                                document.memberNumberForm.acctNbr.value = "";
                            }
                        }
                    }
                   

                 </script>
                 


        <script language="javascript">
            function postMemberNumberForm(task) {
                //alert("member number form post");
                document.getElementById("timerDiv").style.visibility="visible";
                document.getElementById("processingMessage").innerHTML = "Please wait...";
                setTimeout(function() {
                    document.memberNumberForm.task.value = task;
                    document.memberNumberForm.memberNumber.value = document.getElementById("acctNbr").value;
                    document.memberNumberForm.submit();
                    return true;
                },2500);
            }
        </script>



                <script>     
                                                                                                            ' . "\n" . '
//                     function postData() {                                                                       ' . "\n" . '
//                        //alert("barcode data: " . document.numForm.barcodeData.value);  ' . "\n" . '
//                        //displayFormValues();                                                                    ' . "\n" . '
//                        if (document.memberNumberForm.confirmationInput.value.length > 0){                               ' . "\n" . '
//                            document.memberNumberForm.task.value="lookupByConfirmation";                                 ' . "\n" . '
//                        } else if (document.memberNumberForm.acctNbr.value.length > 0){                                  ' . "\n" . '
//                            
//                            document.memberNumberForm.task.value="lookupByAcctNbr";                                      ' . "\n" . '
//                        } else if (document.memberNumberForm.barcodeData.value.length > 0){                                ' . "\n" . '
//                            
//                            document.memberNumberForm.ccLastName.value=document.ccForm.ccLastNameTemp.value;            ' . "\n" . '
//                            document.memberNumberForm.task.value="lookupByCC";                                            ' . "\n" . '
//                            //document.memberNumberForm.submit();                                                       ' . "\n" . '    
//                        } else {
//                            return false;
//                        }
//                        
//                        document.getElementById("timerDiv").style.visibility="visible";                        ' . "\n" . '
//                        document.getElementById("processingMessage").innerHTML = "Please wait...";               ' . "\n" . '
//
//                        setTimeout(function() {                                                                 ' . "\n" . '
//                            if (document.memberNumberForm.barcodeData.value.length > 0){                                ' . "\n" . '
//                                document.memberNumberForm.submit();                                                          ' . "\n" . '
//                            } else {
//                                document.memberNumberForm.submit();                                                          ' . "\n" . '
//                            }
//                            document.memberNumberForm.reset();                                                           ' . "\n" . '
//                            return true;                                                                        ' . "\n" . '
//                        },2500);                                                                                ' . "\n" . '
//                        return true;                                                                            ' . "\n" . '
//                    }                                                                                           ' . "\n" . '
                    function enforceMaxLength(fld,len) {                                                        ' . "\n" . '
                        if (fld.value.length > len){                                                            ' . "\n" . '
                            fld.value = fld.value.substr(0,len);                                                ' . "\n" . '
                        }
                    }                                                                                           ' . "\n" . '
                    
                 </script>                                                                               ' . "\n" . '










      ';

     

            
     $_SESSION['topTable'] = '
         


            <style>

                .labels{
                    font-family:helvetica;
                    font-weight:400;
                    font-size:30pt;
                }

                .textBoxes{
                    width:350px;
                    height:70px;
                    font-family:helvitica;
                    font-weight:400;
                    font-size:28pt;
                    text-align: center;
                }
                .continueButtons{
                    font-family:helvetica;
                    font-weight:700;
                    font-size:38pt;
                    color:#FFFFFF;
                    height:100px;
                    border: 2px solid black;
                }
                
                /*#lastNameDiv{
                    background-color: rgba(204,204,204,0.8);
                    position:fixed;
                    width:100%;
                    height:100%;
                    top:0px;
                    left:0px;
                    z-index:3;
                    visibility:hidden;
                }
                 .lastNamePos{
                    position:absolute;
                    left: 700px; 
                    top: 395px;
                    width:100%;
                    z-index:2;
                }*/
                #lastNameDiv{
                    /*background-color: rgba(204,204,204,0.8);*/
                    position:fixed;
                    /*width:100%;
                    height:100%;*/
                    top:0px;
                    left:0px;
                    z-index:3;
                    visibility:hidden;
                }

                .lastNamePos{
                    position:absolute;
                    left: 405px; 
                    top: 395px;
                    z-index:2;
                }
                #lastNamePos2{
                    position:absolute;
                    visibility:hidden;
                    left: 700px; 
                    top: 295px;
                    z-index:3;
                }
            </style>



            


         
            
                
            <form name="memberNumberForm" action="" method="POST">
                <input id="barcodeData" name="barcodeData" type=hidden value="">  
                <input id="task" name="task" type="hidden" value="">
                <input id="memberNumber" name="memberNumber" type="hidden" value="">
           



                <center>
                    <table>
                        <tr>
                            <td>
                                <center>
                                    <table height="900px" width="1250px">
                                        <tr><td valign=top class="surveyLabel" align=center><center></center></td></tr>
                                        <tr height=50px><td>&nbsp;</td></tr>
                                        <tr><td valign=top class="prompt3" align=center><center>Scan your membership card using the barcode scanner below.</center></td></tr>
                                        <!--<tr>
                                            <td align=center><img src="images/blackBar.png" height="20px"></td>
                                        </tr> -->
                                        <tr height="75px">
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td align=center><img src="images/Member-Card-Comp.gif" height="700px"></td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <input class="largeNumericTextBox" name="acctNbr" type=hidden id="acctNbr"  placeholder="Or Touch Here to Type"  type="text" onclick="getTextboxCoordinates(this);jaShowKeyboard(this, \'NumPad1\')" onkeyup="enforceMaxLength(this,6)">
                                            </td>
                                        </tr>
                                    </table>

                                    <!--<table>
                                        <tr height="100px">
                                            <td width=50px></td>
                                            <td>
                                                <input class="largeNumericTextBox" name="acctNbr" id="acctNbr"  placeholder="Or Touch Here to Type"  type="text" onclick="getTextboxCoordinates(this);jaShowKeyboard(this, \'NumPad1\')" onkeyup="enforceMaxLength(this,6)">
                                            </td>
                                        </tr>   
                                    </table> -->
                                </center>
                            </td>
                        </tr>
                   </table>
                 </center>
             </form>
          '; 
            
    
            
       $_SESSION['navigationTable'] =    '<!-- navigation is taken out -->';
       
        /*   '     <center>
                <table>
                <tr>
                    <td colspan=3>
                        <center>
                            <img id="loading" src="images/723.gif" style="visibility:hidden;">
                        </center>
                    </td>
                </tr>
                <tr>
                    <td width=100>&nbsp;</td>
                    <!-- td onclick="document.getElementById(\'skip\').src = \'images/skip_clicked.png\';postMyForm(\'survey\');" valign=bottom>
                        <img id="skip" src="images/skip.png">
                    </td -->
                    <td onclick="document.getElementById(\'continue\').src = \'images/continue_clicked.png\';postMemberNumberForm(\'groups\');" valign=bottom>
                        <img id="continue" src="images/continue.png">
                    </td>  
                    <td width=100>&nbsp;</td>    
                </tr>
                </table>
                </center>
            ';
            
            */
       
    printHtmlStuffSales();
   
   
   
    exit;
  
}


function validateMemberTemp(){
    
    initLogin();
    
    $_SESSION['memberEmail'] = '';
    
    
    $_SESSION['title'] = 'validateMemberTemp';
    // put all styles into the template...
    $_SESSION['javaScript'] = '
         <script language="javascript">
            function postMemberNumberForm(task) {
                document.getElementById("timerDiv").style.visibility="visible";
                document.getElementById("processingMessage").innerHTML = "Please wait...";
                setTimeout(function() {
                    document.memberNumberForm.task.value = task;
                    document.memberNumberForm.submit();
                    return true;
                },250);
            }



        </script>
      ';

     

            
     $_SESSION['topTable'] = '
         


            <style>

                .labels{
                    font-family:helvetica;
                    font-weight:400;
                    font-size:30pt;
                }

                .textBoxes{
                    width:350px;
                    height:70px;
                    font-family:helvitica;
                    font-weight:400;
                    font-size:28pt;
                    text-align: center;
                }
                .continueButtons{
                    font-family:helvetica;
                    font-weight:700;
                    font-size:38pt;
                    color:#FFFFFF;
                    height:100px;
                    border: 2px solid black;
                }
                
            </style>



            


         
            
                
            <form name="memberNumberForm" action="" method="POST">
                <input id="barcodeData" name="barcodeData" type=hidden value="">  
                <input id="task" name="task" type="hidden" value="">
                <input id="memberNumber" name="memberNumber" type="hidden" value="">
           



                <center>
                    <table>
                        <tr height="100px">
                            <td>
                                <center><font class="memberYesNo">VALIDATE MEMBERSHIP TEMP</center>
                                <br>
                                <center>
                                <table>
                                    <tr height="100px">
                                        <td class="surveyLabel">EMAIL</td>
                                        <td width=50px></td>
                                        <td>
                                            <input class="surveyInput" name="memberEmail" id="memberEmail" autocomplete="off" placeholder="Touch Here to Type"  type="text" onclick="getTextboxCoordinates(this);jaShowKeyboard(this, \'Keyboard1\')" onkeyup="enforceMaxLength(this,100)">
                                        </td>
                                    </tr>
                                </table>
                                </center>
                            </td>
                        </tr>
                   </table>
                 </center>
             </form>
          '; 
            
    
            
       $_SESSION['navigationTable'] = '
                <center>
                <table>
                <tr>
                    <td colspan=3>
                        <center>
                            <img id="loading" src="images/Loading-Animation.gif" style="visibility:hidden;">
                        </center>
                    </td>
                </tr>
                <tr>
                    <td width=100>&nbsp;</td>
                    <td onclick="document.getElementById(\'continue\').src = \'images/continue_clicked.png\';postMemberNumberForm(\'groups\');" valign=bottom>
                        <img id="continue" src="images/continue.png">
                    </td>
                    <td width=100>&nbsp;</td>    
                </tr>
                </table>
                </center>
            ';
            

       
    printHtmlStuffSales();
   
   
   
    exit;
  
}






function survey(){
    
            
    $oneMessage = new Message();
    $oneMessage->loadFromDb($_SESSION['mode'], "surveyTitle", $_REQUEST['client']);
    //exit('oneMessage: ' . $oneMessage->value);

    initLogin();
    
    $_SESSION['email'] = '';
    $_SESSION['title'] = 'survey';
    // put all styles into the template...
    $_SESSION['javaScript'] = '
         <script language="javascript">
            function skipSurvey(task) {
                document.getElementById("timerDiv").style.visibility="visible";
                document.getElementById("processingMessage").innerHTML = "Please wait...";
                setTimeout(function() {
                    document.myform.task.value = task;
                    document.myform.submit();
                    return true;
                },250);
            }
            
            function postSurvey(task) {
                document.getElementById("timerDiv").style.visibility="visible";
                document.getElementById("processingMessage").innerHTML = "Please wait...";
                setTimeout(function() {
                    document.mySurveyForm.task.value = task;
                    document.mySurveyForm.submit();
                    return true;
                },250);
            }



        </script>
      ';

     

            

      $_SESSION['topTable'] = '
          


            

            <form name="mySurveyForm" action="" method="post">
                <input id="task" name="task" type="hidden" value="">
                


                <center>
                    <table>
                        <tr height="100px">
                            <td>
                                <!-- center><font class="prompt3">' . $oneMessage->value . '</center -->
                                <center><font class="prompt3">Enter your e-mail address to view today\'s events.</center>
                                <br>
                                <center>
                                <table>
                                        <tr height="100px">
                                            <td>
                                                <input style="width:590px;" class="surveyInput" name="firstName" id="firstName" autocomplete="off" placeholder="First Name"  type="text" onclick="getTextboxCoordinates(this);jaShowKeyboard(this, \'Keyboard1\')" onkeyup="enforceMaxLength(this,100)">
                                            </td>
                                            <td>
                                                <input style="width:590px;" class="surveyInput" name="lastName" id="lastName" autocomplete="off" placeholder="Last Name"  type="text" onclick="getTextboxCoordinates(this);jaShowKeyboard(this, \'Keyboard1\')" onkeyup="enforceMaxLength(this,100)">
                                            </td>
                                        </tr>
                                        <tr height="100px">
                                            <td colspan="2">
                                                <input class="surveyInput" name="email" id="email" autocomplete="off" placeholder="Email"  type="text" onclick="getTextboxCoordinates(this);jaShowKeyboard(this, \'Keyboard1\')" onkeyup="enforceMaxLength(this,100)">
                                            </td>
                                        </tr>
                                    </table>
                                </center>
                            </td>
                        </tr>
                   </table>
                 </center>
                 

                <center>
                    <table>
                        <tr>
                            <td colspan=5>
                                <center>
                                    <img id="loading" src="images/loading.png" style="visibility:hidden;">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td width=100>&nbsp;</td>
                            <!--<td onclick="document.getElementById(\'skip\').src = \'images/skip_clicked.png\';skipSurvey(\'groups\');" valign=bottom>
                                <img id="skip" src="images/skip.png">
                                </td>
                            <td onclick="document.getElementById(\'continue\').src = \'images/continue_clicked.png\';postSurvey(\'groups\');" valign=bottom>
                                <img id="continue" src="images/continue.png">
                            </td> -->
                            <td id="skip" valign=middle class="button1" 
                                onMouseOut="document.getElementById(\'skip\').style.backgroundImage = \'url(images/redBar.png)\';" 
                                onclick="skipSurvey(\'groups\');"
                                valign=bottom>
                                <font class="buttonText">Skip</font>
                            </td>
                            <td width=50>&nbsp;</td>    
                            <td id="continue" valign=middle class="button1" 
                                onMouseOut="document.getElementById(\'continue\').style.backgroundImage = \'url(images/redBar.png)\';" 
                                onclick="postSurvey(\'groups\');" 
                                valign=bottom>
                                <font class="buttonText">Continue</font>
                            </td>
                            <td width=100>&nbsp;</td>    
                        </tr>
                    </table>
                </center>
                 
                 </form>
          '; 
            
           
      $_SESSION['navigationTable'] = '<!-- navigation is in top table -->';
      
      /*$_SESSION['navigationTable'] = '
                <center>
                <table>
                <tr>
                    <td colspan=4>
                        <center>
                            <img id="loading" src="images/723.gif" style="visibility:hidden;">
                        </center>
                    </td>
                </tr>
                <tr>
                    <td width=100>&nbsp;</td>
                    <!--<td onclick="document.getElementById(\'skip\').src = \'images/skip_clicked.png\';skipSurvey(\'groups\');" valign=bottom>
                        <img id="skip" src="images/skip.png">
                    </td>
                    <td onclick="document.getElementById(\'continue\').src = \'images/continue_clicked.png\';postSurvey(\'groups\');" valign=bottom>
                        <img id="continue" src="images/continue.png">
                    </td> -->
                    <td id="skip" valign=middle class="button1" 
                        onMouseOver="document.getElementById(\'skip\').style.backgroundImage = \'url(images/redBar_clicked.png)\';" 
                        onMouseOut="document.getElementById(\'skip\').style.backgroundImage = \'url(images/redBar.png)\';" 
                        onclick="document.getElementById(\'skip\').style.backgroundImage = \'url(images/redBar_clicked.png)\';skipSurvey(\'groups\');"
                        valign=bottom>
                        <font class="buttonText">Skip</font>
                    </td>
                    <td id="continue" valign=middle class="button1" 
                        onMouseOver="document.getElementById(\'continue\').style.backgroundImage = \'url(images/redBar_clicked.png)\';" 
                        onMouseOut="document.getElementById(\'continue\').style.backgroundImage = \'url(images/redBar.png)\';" 
                        onclick="document.getElementById(\'continue\').style.backgroundImage = \'url(images/redBar_clicked.png)\';postSurvey(\'groups\');" 
                        valign=bottom>
                        <font class="buttonText">Continue</font>
                    </td>
                    <td width=100>&nbsp;</td>    
                </tr>
                </table>
                </center>
            '; 
         */   

    $_SESSION['request']->updateApiCommunication("About to display survey page" . "\n");   
    printHtmlStuffSales();
   
   
   
    exit;
  
}

function continueWithNonMemberPricing(){
    $_SESSION['continueWithNonMemberPricing'] = true;
    groups(); 
}



function continueWithNonMemberPricingYesNo($message){
    
    $tempMemberNumber = $_SESSION['memberNumber'];
    initLogin();
    $_SESSION['memberNumber'] = $tempMemberNumber;
    
    if (empty($message)){
        $message = 'There was an error retrieving that member information.<br><br>Continue with non-member pricing?';
    }
    
    
    
    $_SESSION['title'] = 'continueWithMemberPricingYesNo';
    // put all styles into the template.
    $_SESSION['javaScript'] = '
           
         <script language="javascript">

            function postMyForm(task) {
                document.getElementById("timerDiv").style.visibility="visible";
                setTimeout(function() {
                    document.myform.task.value = task;
                    document.myform.submit();
                    return true;
                },250);
            }

        </script>
      ';

     

            

      $_SESSION['topTable'] = '
                <br><br>
                <center>
                    <table>
                        <tr height="100px">
                            <td colspan=4>
                                <center><font class="memberYesNo">' . $message . '</center>
                            </td>
                        </tr>
                        <tr>
                            <td colspan=4>
                                <center>
                                    <img id="loading" src="images/Loading-Animation.gif" style="visibility:hidden;">
                                </center>
                            </td>
                        </tr>
                        <!--<tr>
                            <td id="yes" class="button1" 
                                onclick="document.getElementById(\'yes\').style.backgroundImage = \'url(images/redBar_clicked.png)\';postMyForm(\'continueWithNonMemberPricing\');">
                                <font class="buttonText">Yes</font>
                            </td>
                            <td width=50>&nbsp;</td>
                            <td id="no" class="button1" 
                                onclick="document.getElementById(\'no\').style.backgroundImage = \'url(images/redBar_clicked.png)\';postMyForm(\'startPage\');">
                                <font class="buttonText">No</font>
                            </td>
                            <td width=100>&nbsp;</td>    
                        </tr> -->
                        <tr>
                            <td colspan=4 align=center>
                                <table>
                                    <td id="yes" valign=middle class="button1" 
                                        onclick="document.getElementById(\'yes\').style.backgroundImage = \'url(images/redBar_clicked.png)\';postMyForm(\'continueWithNonMemberPricing\');" 
                                        valign=bottom>
                                        <font class="buttonText">Continue</font>
                                    </td>
                                </table>
                            </td>
                        </tr>
                   </table>
                 </center>
          '; 
            
            
      $_SESSION['navigationTable'] = '<!--Navigation table has changed-->';
      
       /*$_SESSION['navigationTable'] = '
                <center>
                <table>
                <tr>
                    <td colspan=4>
                        <center>
                            <img id="loading" src="images/723.gif" style="visibility:hidden;">
                        </center>
                    </td>
                </tr>
                <!--<tr>
                    <td width=100>&nbsp;</td>
                    <td onclick="document.getElementById(\'no\').src = \'images/no_clicked.png\';postMyForm(\'startPage\');" valign=bottom>
                        <img id="no" src="images/no.png">
                    </td>
                    <td onclick="document.getElementById(\'yes\').src = \'images/yes_clicked.png\';postMyForm(\'groups\');" valign=bottom>
                        <img id="yes" src="images/yes.png">
                    </td>
                    <td width=100>&nbsp;</td>    
                </tr> -->
                <tr>
                    <td id="yes" valign=middle class="button1" 
                        onMouseOver="document.getElementById(\'yes\').style.backgroundImage = \'url(images/redBar_clicked.png)\';" 
                        onMouseOut="document.getElementById(\'yes\').style.backgroundImage = \'url(images/redBar.png)\';" 
                        onclick="document.getElementById(\'yes\').style.backgroundImage = \'url(images/redBar_clicked.png)\';postMyForm(\'groups\');" 
                        valign=bottom>
                        <font class="buttonText">Yes</font>
                    </td>
                     <td width=50>&nbsp;</td>
                    <td id="no" valign=middle class="button1" 
                        onMouseOver="document.getElementById(\'no\').style.backgroundImage = \'url(images/redBar_clicked.png)\';" 
                        onMouseOut="document.getElementById(\'no\').style.backgroundImage = \'url(images/redBar.png)\';" 
                        onclick="document.getElementById(\'no\').style.backgroundImage = \'url(images/redBar_clicked.png)\';postMyForm(\'startPage\');" 
                        valign=bottom>
                        <font class="buttonText">No</font>
                    </td>
                    <td width=100>&nbsp;</td>    
                </tr>
                </table>
                </center>
            ';
            */




             /*<div style="position:absolute;bottom:100px;width:1920px">
                <center>
                <table>
                <tr>
                    <td colspan=4>
                        <center>
                            <img id="loading" src="images/723.gif" style="visibility:hidden;">
                        </center>
                    </td>
                </tr>
                <tr>
                    <td width=100>&nbsp;</td>
                    <td onclick="document.getElementById(\'sales\').src = \'images/sales_clicked.png\';postMyForm(\'memberYesNo\');" valign=bottom>
                        <img id="sales" src="images/sales.png" width=672>
                    </td>
                    <td onclick="document.getElementById(\'willcall\').src = \'images/willcall_clicked.png\';postMyForm(\'willcall\');" valign=bottom>
                        <img id="willcall" src="images/willcall.png" width=672>
                    </td>
                    <td width=100>&nbsp;</td>    
                </tr>
                </table>
                </center>
            </div>';
*/




       
    printHtmlStuffSales();
   
   
   
    exit;
  
}



function memberYesNo(){
    
    initLogin();
    
   
    
    
    
    $_SESSION['title'] = 'memberYesNo';
    // put all styles into the template.
    $_SESSION['javaScript'] = '
        
    
        <script language="javascript">

            function postMyForm(task) {
                document.getElementById("timerDiv").style.visibility="visible";
                setTimeout(function() {
                    document.myform.task.value = task;
                    document.myform.submit();
                    return true;
                },250);
            }

        </script>';

     

            

      $_SESSION['topTable'] = '
          
            <style>

                .labels{
                    font-family:helvetica;
                    font-weight:400;
                    font-size:30pt;
                }

                .textBoxes{
                    width:350px;
                    height:70px;
                    font-family:helvitica;
                    font-weight:400;
                    font-size:28pt;
                    text-align: center;
                }
                .continueButtons{
                    font-family:helvetica;
                    font-weight:700;
                    font-size:38pt;
                    color:#FFFFFF;
                    height:100px;
                    border: 2px solid black;
                }
                /*#timerDiv{
                    background-color: rgba(204,204,204,0.8);
                    position:fixed;
                    width:100%;
                    height:100%;
                    top:0px;
                    left:0px;
                    z-index:1000;
                    visibility:hidden;
                }
                .timer{
                    position:absolute;
                    left: 700px; 
                    top: 395px;
                    z-index:2;
                }
                #lastNameDiv{
                    background-color: rgba(204,204,204,0.8);
                    position:fixed;
                    width:100%;
                    height:100%;
                    top:0px;
                    left:0px;
                    z-index:3;
                    visibility:hidden;
                }
                 .lastNamePos{
                    position:absolute;
                    left: 700px; 
                    top: 395px;
                    width:100%;
                    z-index:2;
                }*/
                #lastNameDiv{
                    /*background-color: rgba(204,204,204,0.8);*/
                    position:fixed;
                    /*width:100%;
                    height:100%;*/
                    top:0px;
                    left:0px;
                    z-index:3;
                    visibility:hidden;
                }

                .lastNamePos{
                    position:absolute;
                    left: 405px; 
                    top: 395px;
                    z-index:2;
                }
                #lastNamePos2{
                    position:absolute;
                    visibility:hidden;
                    left: 700px; 
                    top: 295px;
                    z-index:3;
                }
            </style>

            



                <br><br>
                <center>
                    <table>
                        <tr height="100px">
                            <td>
                                <center><font class="prompt3">Are you a current member of the Frost Museum?</center>
                            </td>
                        </tr>
                   </table>
                 </center>
                 


                 <center>
                <table>
                <tr>
                    <td colspan=3>
                        <center>
                            <img id="loading" src="images/Loading-Animation.gif" style="visibility:hidden;">
                        </center>
                    </td>
                </tr>
                <tr height="70px">
                    <td>&nbsp</td>
                </tr>
                <tr>
                    <!--<td id="yes" valign=middle class="button1" 
                        onMouseOver="document.getElementById(\'yes\').style.backgroundImage = \'url(images/redBar_clicked.png)\';" 
                        onMouseOut="document.getElementById(\'yes\').style.backgroundImage = \'url(images/redBar.png)\';" 
                        onclick="document.getElementById(\'yes\').style.backgroundImage = \'url(images/redBar_clicked.png)\';postMyForm(\'validateMember\');" 
                        valign=bottom>
                        <font class="buttonText">Yes</font>
                    </td>
                     <td width=50>&nbsp;</td>
                    <td id="no" valign=middle class="button1" 
                        onMouseOver="document.getElementById(\'no\').style.backgroundImage = \'url(images/redBar_clicked.png)\';" 
                        onMouseOut="document.getElementById(\'no\').style.backgroundImage = \'url(images/redBar.png)\';" 
                        onclick="document.getElementById(\'no\').style.backgroundImage = \'url(images/redBar_clicked.png)\';postMyForm(\'survey\');" 
                        valign=bottom>
                        <font class="buttonText">No</font>
                    </td> -->
                    <td id="yes" class="button1" 
                        onclick="postMyForm(\'validateMember\');">
                        <font class="buttonText">Yes</font>
                    </td>
                     <td width=50>&nbsp;</td>
                    <td id="no" class="button1" 
                        onclick="postMyForm(\'survey\');">
                        <font class="buttonText">No</font>
                    </td>
                </tr>
                </table>
                </center>
          '; 
            
            
       $_SESSION['navigationTable'] = '<!--Navigation table has changed-->';
           
                
            
       
       /*$_SESSION['navigationTable'] = '
                <center>
                <table>
                <tr>
                    <td colspan=4>
                        <center>
                            <img id="loading" src="images/723.gif" style="visibility:hidden;">
                        </center>
                    </td>
                </tr>
                <tr>
                    <td width=100>&nbsp;</td>
                    <td onclick="document.getElementById(\'no\').src = \'images/no_clicked.png\';postMyForm(\'survey\');" valign=bottom>
                        <!--<img id="no" src="images/no.png"> -->
                        <img id="no" src="images/redBar.png">Yes
                    </td>
                    <td onclick="document.getElementById(\'yes\').src = \'images/yes_clicked.png\';postMyForm(\'validateMember\');" valign=bottom>
                        <!--<img id="yes" src="images/yes.png"> -->
                        <img id="yes" src="images/redBar.png">
                    </td>
                    <td width=100>&nbsp;</td>    
                </tr>
                </table>
                </center>
            ';
            */



             /*<div style="position:absolute;bottom:100px;width:1920px">
                <center>
                <table>
                <tr>
                    <td colspan=4>
                        <center>
                            <img id="loading" src="images/723.gif" style="visibility:hidden;">
                        </center>
                    </td>
                </tr>
                <tr>
                    <td width=100>&nbsp;</td>
                    <td onclick="document.getElementById(\'sales\').src = \'images/sales_clicked.png\';postMyForm(\'memberYesNo\');" valign=bottom>
                        <img id="sales" src="images/sales.png" width=672>
                    </td>
                    <td onclick="document.getElementById(\'willcall\').src = \'images/willcall_clicked.png\';postMyForm(\'willcall\');" valign=bottom>
                        <img id="willcall" src="images/willcall.png" width=672>
                    </td>
                    <td width=100>&nbsp;</td>    
                </tr>
                </table>
                </center>
            </div>';
*/




       
    printHtmlStuffSales();
   
   
   
    exit;
  
}



function getLoggedInTable(){
     
    // TODO: ONCE I GET LOGGED IN, USE loginInfo in api to get the current login info.
    $loggedInConstituentId = getLoggedInConstituentId();
    
    
//    $_SESSION['loginFirstName'] = "";
//    $_SESSION['loginLastName'] = "";
    
    if ($loggedInConstituentId == '57421695') {
        $_SESSION['loginFirstName'] = "Guest";
        $_SESSION['loginLastName'] = "User";
    } else {
        $_SESSION['constituentId'] = $loggedInConstituentId;  //csn 20160607
        if (!empty($_REQUEST['firstName'])) {
            $_SESSION['loginFirstName'] = $_REQUEST['firstName'];
        }
        if (!empty($_REQUEST['lastName'])) {
            $_SESSION['loginLastName'] = $_REQUEST['lastName'];
        }
    }
    
    
    
    $loggedInTable = '
        
            <form name="myForm" action="" method="post">
                <input id="task" name="task" type="hidden" value="">
            </form>
          
            <script>

                    function postMyForm(task) {
                        document.getElementById("timerDiv").style.visibility="visible";
                        document.getElementById("processingMessage").innerHTML = "Logging Out...";
                        setTimeout(function() {
                            document.myform.task.value = task;
                            document.myform.submit();
                            return true;
                        },250);
                    }
            </script>
            
           
            
          
           

              <table bgcolor="#FFFFFF" style="border:1px solid black">
                        <tr>
                            <td width=10px></td>
                            <td class="loggedInMessage">
                                CURRENTLY LOGGED IN AS: <b><br>' . $loggedInConstituentId . '&nbsp;&nbsp;&nbsp' . $_SESSION['loginFirstName'] . '&nbsp;&nbsp;&nbsp' . $_SESSION['loginLastName'] . '</b><br>
                                 <table>
                                    <tr>
                                        <td>AUTOMATIC LOGOUT IN</td>
                                        <td width=30 align=right><b><font id="countdown" style="font-size:16pt;">60</font></b></td>
                                        <td>&nbsp;&nbsp;SECONDS. TO LOG OUT NOW, CLICK HERE ---></td>
                                    </tr>
                                 </table> 
                             </td>
                             <td onclick="document.getElementById(\'logOut\').src = \'images/logout_clicked.png\';postMyForm(\'logout\');" valign=bottom>
                                <img id="logOut" src="images/logout.png">
                            </td>
                        </tr>
                        <tr>
                            <td colspan = 4 ALIGN=CENTER>
                                <div id="restarting"></div>
                            </td>
                        </tr>
               </table>
               
               
               

            <script type="text/javascript">
              var seconds;
              var temp;

              function countdown() {
                seconds = document.getElementById(\'countdown\').innerHTML;
                seconds = parseInt(seconds, 10);

                if (seconds == 1) {
                  temp = document.getElementById(\'countdown\');
                  temp.innerHTML = "0";
                  document.getElementById(\'restarting\').innerHTML = "<b>RESTARTING...</b>";
                  window.open("' . $_SESSION['startUrl']  . '",\'_self\',false);
                  return;
                }

                seconds--;
                temp = document.getElementById(\'countdown\');
                temp.innerHTML = seconds;
                timeoutMyOswego = setTimeout(countdown, 1000);
              }

              countdown();
            </script>
                

        ';
    
        $loggedInTable = '';  // TEMPORARY
        $loggedInTable = '<!-- CURRENTLY LOGGED IN AS: ' . $loggedInConstituentId . '  ' . $_SESSION['loginFirstName'] . '   ' . $_SESSION['loginLastName'] . ' -->';
        
        
        
        return $loggedInTable;
    
}



function includeThisPrice($onePrice){
    
    // is this membership pricing?
    $includeThisPrice = false;
    $memberPrice = false;
    if (strstr($onePrice->description," Member")){
        $memberPrice = true;
    }
    if (strstr($onePrice->description,"Film Friends of Film")){
        $memberPrice = true;
    }
    if (strstr($onePrice->description,"Member Guest Pass")){
        $memberPrice = true;
    }
    // if it's a member price, suppressMemberPricing must be false.
    if ($memberPrice === true){
        if ($_SESSION['suppressMembershipPricing'] === true){
            $includeThisPrice = false;
        } else {
            $includeThisPrice = true;
        }
    } else {
        $includeThisPrice = true;
    }
    //echo "<pre>";
    //print_r($onePrice);
    //echo "</pre>";
    //echo "session['supressmembershippricing']: " . $_SESSION['suppressMembershipPricing'] . "<br>";
    //echo "memberPrice: " . $memberPrice . "<br>";
    //exit ('includeThisPrice: ' . $includeThisPrice);
    
    return $includeThisPrice;
    
}

function prices20160623($errorMessage){
    
    $errorDiv = '';
    if (strlen($errorMessage) > 1){
        $errorDiv = '<div id="errorDiv">
                <div class="error">
                    <table bgcolor="#ffffff" cellspacing=0 cellpadding=0 class="errorTable">
                        <tr>
                            <td width=20></td>
                            <td ><img id="error" src="images/error.jpg" width=150></td>
                            <td width=20></td>
                            <td width ="500" height="250" valign=top><br><font class="errorTitle">ERROR!</font><br><font class="errorMessage">' . $errorMessage . '</font></td>
                            <td width=20></td>
                        </tr>
                        <tr>
                            <td colspan=3></td>
                            <td>
                                <table width=100%>
                                    <tr>
                                        <td width=50%></td>
                                        <td width=50% align=right class="errorOKTd" onclick="this.className=\'errorOKTd_clicked\';document.getElementById(\'errorDiv\').style.visibility=\'hidden\';">OK</td>
                                    </tr>
                                </table>
                            </td>   
                            <td width=20></td>
                        </tr>
                    </table>
                </div>
            </div>';
    }
    
    // TODO: SUPPRESS MEMBER PRICING FOR NON-MEMBERS.
    
    
    
    
    // we will have $_REQUEST['mainCategory'];
    if (!empty($_REQUEST['mainCategory'])){
        $_SESSION['mainCategory'] = $_REQUEST['mainCategory'];
    }
    
    // now show all prices that match this main category.
    // loop through all performances. get all prices that match.
    
    
    if ($_SESSION['mainCategory'] == 'ga'){
        $_SESSION['pricingGroup'] = 'Museum Admission';
    } else {
        // it will either be in the request variable or we will use the session variable.
        if (!empty($_REQUEST['pricingGroup'])){
            $_SESSION['pricingGroup'] = $_REQUEST['pricingGroup'];
        }
    }
    
    
    
    
    // ------------------------------------------------------------------------------------------
    // AT THIS POINT, I NEED A mainCategory and a pricingGroup chosen. The rest of the information will come from the performances in the session.
    
    
    // how many prices will I have?
    $priceCount = 0;
    foreach ($_SESSION['performances'] as $onePerformance){
        if ($onePerformance->pricingGroup == $_SESSION['pricingGroup']){
            foreach ($onePerformance->prices as $onePrice){
                
                $includeThisPrice = includeThisPrice($onePrice);
                if ($includeThisPrice === true){
                    $priceCount++;
                }
                
            }  
        }
    }
    //exit('priceCount: ' . $priceCount);
    
    $classSize = 'Big';
    if ($priceCount > 3){
        $classSize = 'Small';
    }

    
    
    
    
    
    
    
    
    $maxQty = 10;
    
    $pricesTable = '
            <script>
                function changeValue(idx, addOrSubtract){
                    max = ' . $maxQty . ';
                    
                    currentValue = document.getElementById("qtyDisplay_" + idx).innerHTML;
                    
                        //alert("currentValue:" + currentValue);
                        if (addOrSubtract == "add"){
                            if (currentValue >= 0 && currentValue <= max){
                                document.getElementById("plus_" + idx).src="images/plusButton_clicked.png";
                            }   
                            currentValue = (currentValue*1)+1;

                        } else {
                            if (currentValue >= 0 && currentValue <= max){
                                document.getElementById("minus_" + idx).src="images/minusButton_clicked.png";
                            }   
                            currentValue = (currentValue*1)-1;
                        }
                        
                    if (currentValue >=0 && currentValue <= max){
                        document.getElementById("qtyDisplay_" + idx).innerHTML = currentValue;
                        document.getElementById("qty_" + idx).value = currentValue;
                    }
                    setTimeout(function () { reCalc(); }, 250);
                    return;
                }
                

                function reCalc(){
                    // go through all elements.
                    // TODO: how many idxs?
                    total = ' . $priceCount . ';
                    //
                    //
                    max = ' . $maxQty . ';

                    qtyTotal = 0;
                    totalTotal = 0;
                    for (idx=0;idx<total;idx++){
                        //alert("looking at " + idx);
                        //alert(document.getElementById("qty_" + idx).value);
                        currentValue = document.getElementById("qty_" + idx).value;
                        //alert("currentValue:" + currentValue);
                        if (currentValue <= 0){
                            // grey out minus button
                            currentValue = 0;
                            document.getElementById("qty_" + idx).value = 0;
                            document.getElementById("qtyDisplay_" + idx).innerHTML = 0;
                            document.getElementById("minus_" + idx).src="images/minusButton_greyed.png";
                            document.getElementById("plus_" + idx).src="images/plusButton.png";
                        } else if (currentValue >= max){
                            // grey out plus button
                            currentValue = max;
                            document.getElementById("qty_" + idx).value = max;
                            document.getElementById("qtyDisplay_" + idx).innerHTML = max;
                            document.getElementById("plus_" + idx).src="images/plusButton_greyed.png";
                            document.getElementById("minus_" + idx).src="images/minusButton.png";
                        } else {
                            document.getElementById("plus_" + idx).src="images/plusButton.png";
                            document.getElementById("minus_" + idx).src="images/minusButton.png";
                        }
                        // no matter what, we now have a currentValue
                        qtyTotal = (qtyTotal*1) + (currentValue*1);
                        document.getElementById("total_" + idx).value = document.getElementById("price_" + idx).value * document.getElementById("qty_" + idx).value;
                        document.getElementById("totalDisplay_" + idx).innerHTML = "$" + roundDecimals(document.getElementById("total_" + idx).value,2);
                        totalTotal = (totalTotal*1) + (document.getElementById("total_" + idx).value*1);
                    }
                    document.getElementById("qtyTotal").value = qtyTotal;
                    document.getElementById("qtyTotalDisplay").innerHTML = qtyTotal;
                    document.getElementById("totalTotal").value = totalTotal;
                    document.getElementById("totalTotalDisplay").innerHTML = "$" + roundDecimals(totalTotal,2);
                    //alert("qtyTotal: " + qtyTotal);
                    
                    if (qtyTotal > 0){
                        document.getElementById("continueTable").style.visibility = "visible";
                    } else {
                        document.getElementById("continueTable").style.visibility = "hidden";
                    }


                    return;
                }
                

                function roundDecimals(original_number, decimals) {
                    var result1 = original_number * Math.pow(10, decimals)
                    var result2 = Math.round(result1)
                    var result3 = result2 / Math.pow(10, decimals)
                    result3 = Math.abs(result3);
                    return padWithZeros(result3, decimals)
                }
                function padWithZeros(rounded_value, decimal_places) {
                    // Convert the number to a string
                    var value_string = rounded_value.toString()
                    // Locate the decimal point
                    var decimal_location = value_string.indexOf(".")
                    // Is there a decimal point?
                    if (decimal_location == -1) {
                        // If no, then all decimal places will be padded with 0s
                        decimal_part_length = 0

                        // If decimal_places is greater than zero, tack on a decimal point
                        value_string += decimal_places > 0 ? "." : ""
                    }
                    else {
                        // If yes, then only the extra decimal places will be padded with 0s
                        decimal_part_length = value_string.length - decimal_location - 1
                    }
                    // Calculate the number of decimal places that need to be padded with 0s
                    var pad_total = decimal_places - decimal_part_length
                    if (pad_total > 0) {
                        // Pad the string with 0s
                        for (var counter = 1; counter <= pad_total; counter++) 
                            value_string += "0"
                        }
                    return value_string
                }


            
            </script>
            
             <form name="myPricesForm" action="" method="post">
                <input id="task" name="task" type="hidden" value="reserve">
                
                
            


            <table width="70%">' . "\n";
    $idx = 0;
    foreach ($_SESSION['performances'] as $onePerformance){
        if ($onePerformance->pricingGroup == $_SESSION['pricingGroup']){
            foreach ($onePerformance->prices as $onePrice){
                
                $includeThisPrice = includeThisPrice($onePrice);
                if ($includeThisPrice === true){
                    $pricesTable .= '<input type="hidden" id="priceType_' . $idx . '" name="priceType_' . $idx . '" value="' . $onePrice->priceType . '">' . "\n";
                    $pricesTable .= '<input type="hidden" id="zoneNo_' . $idx . '" name="zoneNo_' . $idx . '" value="' . $onePrice->zoneNo . '">' . "\n";
                    $pricesTable .= '<input type="hidden" id="perfNo_' . $idx . '" name="perfNo_' . $idx . '" value="' . $onePerformance->perfNo . '">' . "\n";
                    $pricesTable .= '<input type="hidden" id="price_' . $idx . '" name="price_' . $idx . '" value="' . $onePrice->price . '">' . "\n";
                    $pricesTable .= '<input type="hidden" id="qty_' . $idx . '" name="qty_' . $idx . '" value="0">' . "\n";
                    $pricesTable .= '<input type="hidden" id="total_' . $idx . '" name="total_' . $idx . '" value="0">' . "\n";
                    $pricesTable .= '<tr><td colspan=8><hr></td></tr>' . "\n";
                    $pricesTable .= '<tr>' . "\n";
                    $pricesTable .= '<td class="prices' . $classSize . 'Label">' . $onePrice->description . '</td>' . "\n";
                    $pricesTable .= '<td id = "priceDisplay_' . $idx . '" class="prices' . $classSize . 'Price" width=100>$' . $onePrice->price . '</td>' . "\n";
                    $pricesTable .= '<td width=100></td>' . "\n";
                    $pricesTable .= '<td bgcolor="#DDDDDD" align=center width=60><img id="minus_' . $idx . '" src="images/minusButton.png" width="60px" onclick="changeValue(\'' . $idx . '\',\'subtract\');"></td>' . "\n";
                    $pricesTable .= '<td bgcolor="#DDDDDD" id = "qtyDisplay_' . $idx . '" class="prices' . $classSize . 'Qty" width=80>0</td>' . "\n";
                    $pricesTable .= '<td bgcolor="#DDDDDD" align=center width=60><img id="plus_' . $idx . '" src="images/plusButton.png" width="60px" onclick="changeValue(\'' . $idx . '\',\'add\');"></td>' . "\n";
                    $pricesTable .= '<td width=70></td>' . "\n";
                    $pricesTable .= '<td id = "totalDisplay_' . $idx . '" class="prices' . $classSize . 'Price" width=150>$0.00</td>' . "\n";
                    $pricesTable .= '</tr>' . "\n\n\n";    
                    $idx++;
                }
            }  
        }
    }
    
    $pricesTable .= '<input type="hidden" id="qtyTotal" name="qtyTotal" value="0">' . "\n";
    $pricesTable .= '<input type="hidden" id="totalTotal" name="totalTotal" value="0">' . "\n";
    $pricesTable .= '<tr><td colspan=8><hr></td></tr>' . "\n";
    $pricesTable .= '<tr>' . "\n";
    $pricesTable .= '<td colspan=4 class="prices' . $classSize . 'TotalLabel">Totals...</td>' . "\n";
    $pricesTable .= '<td id = "qtyTotalDisplay" class="prices' . $classSize . 'QtyTotalDisplay" width=80>0</td>' . "\n";
    $pricesTable .= '<td colspan = 3 id = "totalTotalDisplay" class="prices' . $classSize . 'TotalTotalDisplay" width=150>$0.00</td>' . "\n";
    $pricesTable .= '</tr>' . "\n\n\n"; 
    
    
    /*$pricesTable .= '<tr>';
    $pricesTable .= '<td colspan=8>
                        <table id="continueTable" width=100% style="visibility:hidden;">
                            <tr>
                                <td width=50%></td>
                                <td onclick="document.getElementById(\'continue\').src = \'images/continue_clicked.png\';postMyPricesForm();" valign=bottom>
                                    <img id="continue" src="images/continue.png" width=672>
                                </td>
                            </tr>
                        </table>
                     </td></tr>';
    $pricesTable .= '</tr>';
    
    $pricesTable .= '</table>
                     </form>
                     <script>reCalc()</script>' . "\n";
    
    */
    $pricesTable .= '</table></form>';
    
    
    
    
    
    $_SESSION['title'] = 'prices';
    
    $_SESSION['javaScript'] = '
        
    
        <script language="javascript">

            function postMyPricesForm() {
                document.getElementById("timerDiv").style.visibility="visible";
                document.getElementById("processingMessage").innerHTML = "Reserving...";
                setTimeout(function() {
                    document.getElementById("timerDiv").style.visibility="visible";
                    document.myPricesForm.submit();
                    return true;
                },250);
            }

        </script>
        


      ';
    

       $_SESSION['topTable'] = '
                <center>
                    <table>
                        <tr height="100px">
                            <td class="pricesMessage">
                                <center>CHOOSE TICKET QUANTITIES BELOW<br>' . $_SESSION['pricingGroup'] . '<br></center>
                            </td>
                        </tr>
                   </table>
                   ' . $pricesTable . '
                 </center>
                


        ' . $errorDiv . '     


         '; 
           
            
            
          
          
            $_SESSION['navigationTable'] = '
                        <table id="continueTable" width=100% style="visibility:hidden;">
                            <tr>
                                <td width=50%></td>
                                <td onclick="document.getElementById(\'continue\').src = \'images/continue_clicked.png\';postMyPricesForm();" valign=bottom>
                                    <img id="continue" src="images/continue.png" width=672>
                                </td>
                            </tr>
                       
                        </table>
                     <script>reCalc()</script>
                   ' . "\n";



    printHtmlStuffSales();
    
    
    
}


function performanceTimes($startNumber, $pricingGroup){
    
// TODO: add small class sizes.
//          test with many performances
//          confirm that the performances are in chronological order.
    

    
    
//    if (empty($_SESSION['pricingGroup'])){
//        exit("error, pricingGroup is empty");
//    }
    
//    echo "<pre>";
//    print_r($_SESSION['pricingGroup']);
//    echo "</pre>";
//    exit();


//    echo "<pre>";
//    print_r($_SESSION['performances']);
//    echo "</pre>";
//    exit('in performances');

    
    
    

    $timeCount = 0;
    $performances = array();
    $time = date("H:i");
    if (!empty($_SESSION['timeOverride'])){
        $time = $_SESSION['timeOverride'];
    }
    foreach ($_SESSION['performances'] as $onePerformance){
        if (strpos($onePerformance->pricingGroup, $_SESSION['pricingGroup']) !== false){
            foreach ($onePerformance->prices as $onePrice){
                $perfTime = $onePrice->description;
                if (in_array($perfTime, $performances) ) { //If in array, skip iteration
                    continue;
                } else if ($onePrice->availCount < 1){
                    continue;
                } else if (strtotime($perfTime) < strtotime($time)){
                    continue;
                } else {
                    $includeThisPrice = includeThisPrice($onePrice);
                    if ($includeThisPrice === true){
                        $performances[] = $onePrice->description; //Add performance to "used" performances array
                        $timeCount++;
                    }
                }
            }
        }
    }
//    exit();

    $classSize = 'Big';

    if ($timeCount == 0){
        $performancesTable = '
                            <form name="myPerformancesForm" action="" method="post">
                            <input id="task" name="task" type="hidden" value="pricesByTime">
                            <input id="performanceTime" name="performanceTime" type="hidden" value="">
                            </form>
                                <table width="60%">' . "\n";
        $performancesTable .= '<tr><td class="performancesNoneAvailable">There are no more events available today</td></tr>';
        $performancesTable .= "\n\n" . '</table></form>';

    } else {
        // display performances.
        $performancesTable = '
                            <form name="myPerformancesForm" action="" method="post">
                            <input id="task" name="task" type="hidden" value="pricesByTime">
                            <input id="performanceTime" name="performanceTime" type="hidden" value="">
                            <input type="hidden" id="startNumber" name="startNumber" value="0">
                            </form>
                                <table width="60%">' . "\n";


        $numberToShow = 4;
        $counter = 0;
        // may need to sort them first...
        $performances = array();
        foreach ($_SESSION['performances'] as $onePerformance){
            if (strpos($onePerformance->pricingGroup, $_SESSION['pricingGroup']) !== false){
                foreach ($onePerformance->prices as $onePrice){
                    $perfTime = $onePrice->description;
                    if (in_array($perfTime, $performances) ) { //If in array, skip iteration
                        continue;
                    } else if ($onePrice->availCount < 1){
                        continue;
                    } else if (strtotime($perfTime) < strtotime($time)){
                        continue;
                    } else {
                        $includeThisPrice = includeThisPrice($onePrice);
                        if ($includeThisPrice === true){
                            $performances[] = $perfTime; //Add performance to "used" performances array
                            if ($counter >= $startNumber && $counter < ($startNumber + $numberToShow)) {
                                $performancesTable .= ''
                                    . '<tr><td height="100" id="performance' . $counter . '" style="background:url(\'images/redBar.png\');background-size:100% 100%;" align="center" bgcolor="#f22613" class="prompt1" '
                                        . 'onMouseOver="document.getElementById(\'performance' . $counter . '\').style.backgroundImage = \'url(images/redBar_clicked.png)\';" '
                                        . 'onMouseOut="document.getElementById(\'performance' . $counter . '\').style.backgroundImage = \'url(images/redBar.png)\';" '
                                        . 'onclick="document.getElementById(\'performance' . $counter . '\').style.backgroundImage = \'url(images/redBar_clicked.png)\';'
                                        . 'postMyPerformancesForm(\'' . date('g:i A', strtotime($perfTime)) . '\');">'
                                        . '<font color="#ffffff"><b>' . date('g:i A', strtotime($perfTime)) . '</b></font>'
                                    . '</td></tr>';



                                $performancesTable .= '<tr><td height="10px"></td></tr>';
                            }
                            $counter++;
                        }
                        
                    }

                }
            }
        }
        $performancesTable .= "\n\n" . '</table></form>';
    }
    $_SESSION['title'] = 'performanceTimes';
    $_SESSION['javaScript'] = '
        <script language="javascript">

            function postMyPerformancesForm(performanceTime) {
                document.getElementById("timerDiv").style.visibility="visible";
                document.getElementById("processingMessage").innerHTML = "Getting Prices...";
                setTimeout(function() {
                document.getElementById("timerDiv").style.visibility="visible";
                    document.getElementById("performanceTime").value = performanceTime;
                    document.myPerformancesForm.submit();
                    return true;
                },250);
            }


            function moreButton(task, startNumber) {

                setTimeout(function() {
                    document.getElementById("startNumber").value = startNumber;
                    document.myPerformancesForm.task.value = task;
                    document.myPerformancesForm.submit();
                    return true;
                },250);
            }

            function backButton(task, startNumber) {
                setTimeout(function() {
                    document.getElementById("startNumber").value = startNumber;
                    document.myPerformancesForm.task.value = task;
                    document.myPerformancesForm.submit();
                    return true;
                },250);
            }
        </script>';


    $moreButton = '<td width="10%"></td>';
    $backButton = '<td width="10%"></td>';
    $nextStart = $startNumber + $numberToShow;
    $backStart = $startNumber - $numberToShow;
    
    if (($startNumber < 1) && ($counter > $numberToShow)){
        $moreButton = '<td id="more" valign=middle class="button1"
        onMouseOver="document.getElementById(\'more\').style.backgroundImage = \'url(images/redBar_clicked.png)\';"
        onMouseOut="document.getElementById(\'more\').style.backgroundImage = \'url(images/redBar.png)\';"
        onclick="document.getElementById(\'more\').style.backgroundImage = \'url(images/redBar_clicked.png)\';moreButton(\'performanceTimes\',' . $nextStart . ');;" valign=bottom>
                            More
                        </td>';
                //<td bgcolor="#df3c31" style="border-radius: 5px;font-family:Verdana;color:#FFFFFF;font-size:20px;" align="center" width="10%" id="more" onclick="moreButton(\'performances\',' . $nextStart . ')"> next >> </td>
    } else if ($counter > ($startNumber + $numberToShow)){
        $moreButton = '<td id="more" valign=middle class="button1"
        onMouseOver="document.getElementById(\'more\').style.backgroundImage = \'url(images/redBar_clicked.png)\';"
        onMouseOut="document.getElementById(\'more\').style.backgroundImage = \'url(images/redBar.png)\';"
        onclick="document.getElementById(\'more\').style.backgroundImage = \'url(images/redBar_clicked.png)\';moreButton(\'performanceTimes\',' . $nextStart . ');;" valign=bottom>
                            More
                        </td>';
        $backButton = '<td id="previous" valign=middle class="button1"
        onMouseOver="document.getElementById(\'previous\').style.backgroundImage = \'url(images/redBar_clicked.png)\';"
        onMouseOut="document.getElementById(\'previous\').style.backgroundImage = \'url(images/redBar.png)\';"
        onclick="document.getElementById(\'previous\').style.backgroundImage = \'url(images/redBar_clicked.png)\';backButton(\'performanceTimes\',' . $backStart . ');;" valign=bottom>
                            Back
                        </td>';
    } else {
        if ($counter > $numberToShow){
            $backButton = '<td id="back" valign=middle class="button1"
            onMouseOver="document.getElementById(\'back\').style.backgroundImage = \'url(images/redBar_clicked.png)\';"
            onMouseOut="document.getElementById(\'back\').style.backgroundImage = \'url(images/redBar.png)\';"
            onclick="document.getElementById(\'back\').style.backgroundImage = \'url(images/redBar_clicked.png)\';backButton(\'performanceTimes\',' . $backStart . ');;" valign=bottom>
                            Back
                        </td>';

            //$backButton = '<td bgcolor="#df3c31" style="border-radius: 5px;font-family:Verdana;color:#FFFFFF;font-size:20px;" align="center" width="10%" id="back" onclick="backButton(\'performances\',' . $backStart . ')"> << back </td>';
        }
    }

    $_SESSION['topTable'] = '
            <center>
                <table>
                    <tr height="100px">
                        <td class="prompt2"><center>' . $_SESSION['pricingGroup'] . '</center></td>
                    </tr>
                    <tr height="50px">
                        <td class="prompt1"><center>Select a Time for your Performance</center></td>
                    </tr>
                </table>
                ' . $performancesTable . '
            </center>

            <br>
            
            <div style="position:absolute;height:70px;top:650px;width:1920px;display:table;">
                <table height="60px" width="100%" align="center">
                    <tr align="center">
                        <td width="27.5%"></td>
                        ' . $backButton . '
                        <td width="25%"></td>
                        ' . $moreButton . '
                        <td width="27.5%"></td>
                    </tr>
                </table>
            </div>


            ';

    $_SESSION['navigationTable'] = '<table><tr><td></td></table>';



    printHtmlStuffSales();
    
    
}




function prices($errorMessage){

    
    $errorDiv = '';
    if (strlen($errorMessage) > 1){
//        $errorMessage = "No more Member Tickets left, Lorem renew, please <br> go to a ticket desk for assitance. To purchase tickets <br> as a nonmember, tap Continue.";
        $errorDiv = '<div id="errorDiv">
                <div class="error2">
                    <center>
                    <table width=1100px bgcolor="#ffffff" cellspacing=0 cellpadding=20px>
                        <tr>
                            <td><img id="error" src="images/exclamationBox.png" width=75></td>
                            <td width=20></td>
                            <td><img id="error" src="images/exclamationBox.png" width=75></td>
                        </tr>
                        <tr>
                            <td width=20></td>
                            <!--<td width ="500" height="200" valign=top><font class="prompt1">' . $errorMessage . '</font></td> -->
                            <td align=center valign=top><font class="prompt1">' . $errorMessage . '</font></td>    
                            <td width=20></td>
                        </tr>
                        <tr>
                            <td width=20></td>
                            <td align=center>
                                <!--<table width=100%> -->
                                <table>
                                    <td id="continue" align=center valign=middle  class="button1" 
                                        onclick="document.getElementById(\'errorDiv\').style.visibility=\'hidden\';"
                                        valign=bottom>
                                        <font class="buttonText">Continue</font>
                                    </td>
                                </table>
                            </td>
                            <td width=20></td>
                        </tr>
                        <tr>
                            <td><img id="error" src="images/exclamationBox.png" width=75></td>
                            <td width=20></td>
                            <td><img id="error" src="images/exclamationBox.png" width=75></td>
                        </tr>
                    </table>
                    </center>
                </div>
            </div>';
    }
    

    // TODO: SUPPRESS MEMBER PRICING FOR NON-MEMBERS.


    // we will have $_REQUEST['mainCategory'];
    if (!empty($_REQUEST['mainCategory'])){
        $_SESSION['mainCategory'] = $_REQUEST['mainCategory'];
    }

    // now show all prices that match this main category.
    // loop through all performances. get all prices that match.


    if ($_SESSION['mainCategory'] == 'ga'){
        $_SESSION['pricingGroup'] = 'Museum Admission';
    } else {
        // it will either be in the request variable or we will use the session variable.
        if (!empty($_REQUEST['pricingGroup'])){
            $_SESSION['pricingGroup'] = $_REQUEST['pricingGroup'];
        }
    }




    // ------------------------------------------------------------------------------------------
    // AT THIS POINT, I NEED A mainCategory and a pricingGroup chosen. The rest of the information will come from the performances in the session.


    // how many prices will I have?
    $priceCount = 0;
    foreach ($_SESSION['performances'] as $onePerformance){
        if ($onePerformance->pricingGroup == $_SESSION['pricingGroup']){
            foreach ($onePerformance->prices as $onePrice){
                $includeThisPrice = includeThisPrice($onePrice);
                if ($includeThisPrice === true){
                    $priceCount++;
                }

            }
        }
    }

    $classSize = 'Big';
    if ($priceCount > 3){
        $classSize = 'Small';
    }




    $maxQty = 10;

    $pricesTable = '
            <script>

                function changeValue(idx, addOrSubtract){
                    max = ' . $maxQty . ';

                    currentValue = document.getElementById("qtyDisplay_" + idx).innerHTML;

                        //alert("currentValue:" + currentValue);
                        if (addOrSubtract == "add"){
                            if (currentValue >= 0 && currentValue <= max){
                                document.getElementById("plus_" + idx).src="images/lightGreyPlusBar.png";
                            }
                            currentValue = (currentValue*1)+1;

                        } else {
                            if (currentValue >= 0 && currentValue <= max){
                                document.getElementById("minus_" + idx).src="images/lightGreyMinusBar.png";
                            }
                            currentValue = (currentValue*1)-1;
                        }

                    if (currentValue >=0 && currentValue <= max){
                        document.getElementById("qtyDisplay_" + idx).innerHTML = currentValue;
                        document.getElementById("qty_" + idx).value = currentValue;
                    }
                    setTimeout(function () { reCalc(); }, 250);
                    return;
                }

                




                function reCalc(){
                    // go through all elements.
                    // TODO: how many idxs?
                    total = ' . $priceCount . ';
                    //
                    //
                    max = ' . $maxQty . ';
                    min = 0;

                    qtyTotal = 0;
                    totalTotal = 0;
                    for (idx=0;idx<total;idx++){
                        //alert("looking at " + idx);
                        //alert(document.getElementById("qty_" + idx).value);
                        currentValue = document.getElementById("qty_" + idx).value;
                        //alert("currentValue:" + currentValue);
//                        if (document.getElementById("priceType_" + idx).value == "125" || document.getElementById("priceType_" + idx).value == "360"){
//                            currentValue = 1; // the minimum is 1.
//                            document.getElementById("minus_" + idx).src="images/lightGreyMinusBar.png";
//                            document.getElementById("plus_" + idx).src="images/lightGreyPlusBar.png";
//                        } else if (currentValue <= 0){
//                            // grey out minus button
//                            currentValue = 0;
//                            document.getElementById("qty_" + idx).value = 0;
//                            document.getElementById("qtyDisplay_" + idx).innerHTML = 0;
//                            document.getElementById("minus_" + idx).src="images/lightGreyMinusBar.png";
//                            document.getElementById("plus_" + idx).src="images/greenPlusBar.png";
//                        } else if (currentValue >= max){
//                            // grey out plus button
//                            currentValue = max;
//                            document.getElementById("qty_" + idx).value = max;
//                            document.getElementById("qtyDisplay_" + idx).innerHTML = max;
//                            document.getElementById("plus_" + idx).src="images/lightGreyPlusBar.png";
//                            document.getElementById("minus_" + idx).src="images/redMinusBar.png";
//                        } else {
//                            document.getElementById("plus_" + idx).src="images/greenPlusBar.png";
//                            document.getElementById("minus_" + idx).src="images/redMinusBar.png";
//                        }



                        
                        if (document.getElementById("priceType_" + idx).value == "125" || document.getElementById("priceType_" + idx).value == "360"){
                            min = 1;
                        }
                        


                        if (currentValue <= min){
                            // grey out minus button
                            currentValue = min;
                            document.getElementById("qty_" + idx).value = min;
                            document.getElementById("qtyDisplay_" + idx).innerHTML = min;
                            document.getElementById("minus_" + idx).src="images/lightGreyMinusBar.png";
                            document.getElementById("plus_" + idx).src="images/greenPlusBar.png";
                        } else if (currentValue >= max){
                            // grey out plus button
                            currentValue = max;
                            document.getElementById("qty_" + idx).value = max;
                            document.getElementById("qtyDisplay_" + idx).innerHTML = max;
                            document.getElementById("plus_" + idx).src="images/lightGreyPlusBar.png";
                            document.getElementById("minus_" + idx).src="images/redMinusBar.png";
                        } else {
                            document.getElementById("plus_" + idx).src="images/greenPlusBar.png";
                            document.getElementById("minus_" + idx).src="images/redMinusBar.png";
                        }


                        
                        // no matter what, we now have a currentValue
                        qtyTotal = (qtyTotal*1) + (currentValue*1);
                        document.getElementById("total_" + idx).value = document.getElementById("price_" + idx).value * document.getElementById("qty_" + idx).value;
                        document.getElementById("totalDisplay_" + idx).innerHTML = "$" + roundDecimals(document.getElementById("total_" + idx).value,2);
                        totalTotal = (totalTotal*1) + (document.getElementById("total_" + idx).value*1);
                    }
                    document.getElementById("qtyTotal").value = qtyTotal;
                    document.getElementById("qtyTotalDisplay").innerHTML = qtyTotal;
                    document.getElementById("totalTotal").value = totalTotal;
                    document.getElementById("totalTotalDisplay").innerHTML = "$" + roundDecimals(totalTotal,2);
                    //alert("qtyTotal: " + qtyTotal);

                    if (qtyTotal > 0){
                        document.getElementById("continueTable").style.visibility = "visible";
                    } else {
                        document.getElementById("continueTable").style.visibility = "hidden";
                    }


                    return;
                }


                function roundDecimals(original_number, decimals) {
                    var result1 = original_number * Math.pow(10, decimals)
                    var result2 = Math.round(result1)
                    var result3 = result2 / Math.pow(10, decimals)
                    result3 = Math.abs(result3);
                    return padWithZeros(result3, decimals)
                }


                function padWithZeros(rounded_value, decimal_places) {
                    // Convert the number to a string
                    var value_string = rounded_value.toString()
                    // Locate the decimal point
                    var decimal_location = value_string.indexOf(".")
                    // Is there a decimal point?
                    if (decimal_location == -1) {
                        // If no, then all decimal places will be padded with 0s
                        decimal_part_length = 0

                        // If decimal_places is greater than zero, tack on a decimal point
                        value_string += decimal_places > 0 ? "." : ""
                    }
                    else {
                        // If yes, then only the extra decimal places will be padded with 0s
                        decimal_part_length = value_string.length - decimal_location - 1
                    }
                    // Calculate the number of decimal places that need to be padded with 0s
                    var pad_total = decimal_places - decimal_part_length
                    if (pad_total > 0) {
                        // Pad the string with 0s
                        for (var counter = 1; counter <= pad_total; counter++)
                            value_string += "0"
                        }
                    return value_string
                }

            </script>

            <style>

                .labels{
                    font-family:helvetica;
                    font-weight:400;
                    font-size:30pt;
                }

                .textBoxes{
                    width:350px;
                    height:70px;
                    font-family:helvitica;
                    font-weight:400;
                    font-size:28pt;
                    text-align: center;
                }
                .continueButtons{
                    font-family:helvetica;
                    font-weight:700;
                    font-size:38pt;
                    color:#FFFFFF;
                    height:100px;
                    border: 2px solid black;
                }

            </style>


                <form name="myPricesForm" action="" method="post">
                <input id="task" name="task" type="hidden" value="reserve">
            <table width="1920px">' . "\n";
    $idx = 0;
    foreach ($_SESSION['performances'] as $onePerformance){
        if ($onePerformance->pricingGroup == $_SESSION['pricingGroup']){
            foreach ($onePerformance->prices as $onePrice){

                $includeThisPrice = includeThisPrice($onePrice);
                if ($includeThisPrice === true){
                    
                    
                    $pricesTable .= '<input type="hidden" id="description_' . $idx . '" name="description_' . $idx . '" value="' . $onePrice->description . '">' . "\n";
                    $pricesTable .= '<input type="hidden" id="priceType_' . $idx . '" name="priceType_' . $idx . '" value="' . $onePrice->priceType . '">' . "\n";
                    $pricesTable .= '<input type="hidden" id="zoneNo_' . $idx . '" name="zoneNo_' . $idx . '" value="' . $onePrice->zoneNo . '">' . "\n";
                    $pricesTable .= '<input type="hidden" id="perfNo_' . $idx . '" name="perfNo_' . $idx . '" value="' . $onePerformance->perfNo . '">' . "\n";
                    $pricesTable .= '<input type="hidden" id="price_' . $idx . '" name="price_' . $idx . '" value="' . $onePrice->price . '">' . "\n";
                    
                    
                    if ($onePrice->priceType == '125' || $onePrice->priceType == '360'){
                        $pricesTable .= '<input type="hidden" id="qty_' . $idx . '" name="qty_' . $idx . '" value="1">' . "\n";
                    } else {
                        $pricesTable .= '<input type="hidden" id="qty_' . $idx . '" name="qty_' . $idx . '" value="0">' . "\n";
                    }
                    
                    
                    $pricesTable .= '<input type="hidden" id="total_' . $idx . '" name="total_' . $idx . '" value="0">' . "\n";
                    $pricesTable .= '<tr>' . "\n";
                    $pricesTable .= '<td align="left" class="prompt1" style="font-size:30pt;padding:25px;border-top:1px solid #acacac;border-right:1px solid #acacac;padding-left: 400px"><nobr>' . str_replace('Admission', '',$onePrice->description) . '</nobr></td>' . "\n";
                    $pricesTable .= '<td align="center" id="priceDisplay_' . $idx . '" class="prompt1" style="font-size:30pt;border-top:1px solid #acacac;border-right:1px solid #acacac;" width="200"><b>$' . $onePrice->price . '</b></td>' . "\n";
                    
                    // force a qty of 1 for the open house price type - 125 in test, 360 in production
                    //exit('priceType:' . $onePrice->priceType);
//                    if ($onePrice->priceType == '125' || $onePrice->priceType ==' 360'){
//                        $pricesTable .= '<td align="center" height="94px" width="150px" style="border-top:1px solid #acacac;"><img height="70px" id="minus_' . $idx . '" src="images/lightGreyMinusBar.png"></td>' . "\n";
//                        $pricesTable .= '<td align="center" style="border-top:1px solid #acacac;" id="qtyDisplay_' . $idx . '" class="prompt2" width="100px">1</td>' . "\n";
//                        //$pricesTable .= '<td align="center" height="94px" width="150px" style="border-top:1px solid #acacac;border-right:1px solid #acacac;"><img height="70px" id="plus_' . $idx . '" src="images/lightGreyPlusBar.png"></td>' . "\n";
//                        $pricesTable .= '<td align="center" height="94px" width="150px" style="border-top:1px solid #acacac;border-right:1px solid #acacac;"><img height="70px" id="plus_' . $idx . '" src="images/greenPlusBar.png" onclick="changeValue(\'' . $idx . '\',\'add\');"></td>' . "\n";
//                    } else {
                        $pricesTable .= '<td align="center" height="94px" width="150px" style="border-top:1px solid #acacac;"><img height="70px" id="minus_' . $idx . '" src="images/redMinusBar.png" onclick="changeValue(\'' . $idx . '\',\'subtract\');"></td>' . "\n";
                        $pricesTable .= '<td align="center" style="border-top:1px solid #acacac;" id="qtyDisplay_' . $idx . '" class="prompt2" width="100px">0</td>' . "\n";
                        $pricesTable .= '<td align="center" height="94px" width="150px" style="border-top:1px solid #acacac;border-right:1px solid #acacac;"><img height="70px" id="plus_' . $idx . '" src="images/greenPlusBar.png" onclick="changeValue(\'' . $idx . '\',\'add\');"></td>' . "\n";
//                    }
                    
                    
                    $pricesTable .= '<td align="center" style="border-top:1px solid #acacac;" id="totalDisplay_' . $idx . '" class="prompt1" style="padding:25px" width="200px">$0.00</td>' . "\n";
                    $pricesTable .= '<td width="400px" style="border-top:1px solid #acacac;">&nbsp</td>' . "\n";
                    $pricesTable .= '</tr>' . "\n\n\n";
                    
                    
                    
                    
                    
                    
                    
                    
                    $idx++;
                }
            }
        }
    }

    
    $pricesTable .= '<input type="hidden" id="qtyTotal" name="qtyTotal" value="0">
                    <input type="hidden" id="totalTotal" name="totalTotal" value="0">
                    <tr height="120px">
                        <td style="border-top:1px solid #acacac;border-right:1px solid #acacac;border-bottom:1px solid #acacac;">&nbsp</td>
                        <td align="center" class="prompt2" style="border-top:1px solid #acacac;border-right:1px solid #acacac;border-bottom:1px solid #acacac;">Total</td>
                        <td align="center" class="prompt2" id="qtyTotalDisplay" colspan="3" style="border-top:1px solid #acacac;border-right:1px solid #acacac;border-bottom:1px solid #acacac;">0</td>
                        <td align="center" class="prompt2" id="totalTotalDisplay" style="color:#d91e18;border-top:1px solid #acacac;border-bottom:1px solid #acacac;">&nbsp</td>
                        <td colspan="2" style="border-top:1px solid #acacac;border-bottom:1px solid #acacac;">&nbsp</td>
                    </tr>';


    /*$pricesTable .= '<tr>';
    $pricesTable .= '<td colspan=8>
                        <table id="continueTable" width=100% style="visibility:hidden;">
                            <tr>
                                <td width=50%></td>
                                <td onclick="document.getElementById(\'continue\').src = \'images/continue_clicked.png\';postMyPricesForm();" valign=bottom>
                                    <img id="continue" src="images/continue.png" width=672>
                                </td>
                            </tr>
                        </table>
                     </td></tr>';
    $pricesTable .= '</tr>';

    $pricesTable .= '</table>
                     </form>
                     <script>reCalc()</script>' . "\n";

    */
    $pricesTable .= '</table></form>';





    $_SESSION['title'] = 'prices';

    $_SESSION['javaScript'] = '


        <script language="javascript">

            function postMyPricesForm() {
                document.getElementById("timerDiv").style.visibility="visible";
                document.getElementById("processingMessage").innerHTML = "Please wait...";
                setTimeout(function() {
                document.getElementById("timerDiv").style.visibility="visible";
                    document.myPricesForm.submit();
                    return true;
                },250);
            }

        </script>



      ';


    
       $_SESSION['topTable'] = '

                <center>
                    <table>
                        <tr height="100px">
                            <td>
                                <center><font class="prompt2">' . $_SESSION['pricingGroup'] . '</font></center>
                                <center><font class="prompt1" >Adjust Ticket Quantities</font></center>
                            </td>
                        </tr>
                   </table>
                   <br>
                   ' . $pricesTable . '
                    <table id="continueTable" width=100% style="visibility:hidden;">
                        <tr height="65px">
                            <td></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td id="continue" valign=middle class="button1"
                                onMouseOut="document.getElementById(\'continue\').style.backgroundImage = \'url(images/redBar.png)\';"
                                onclick="postMyPricesForm();;" valign=bottom>
                                Continue
                            </td>
                            <td></td>
                        </tr>
                    </table> 
                 </center>

        ' . $errorDiv . '
        ';





        $_SESSION['navigationTable'] = '
            <!--<table id="continueTable" width=100% style="visibility:hidden;">
                <tr>
                    <td></td>
                    <td id="continue" valign=middle class="button1"
                        onMouseOut="document.getElementById(\'continue\').style.backgroundImage = \'url(images/redBar.png)\';"
                        onclick="postMyPricesForm();;" valign=bottom>
                        Continue
                    </td>
                    <td></td>
                </tr>
                <tr height="100px">
                    <td></td>
                </tr> 
            </table> -->



             <script>reCalc()</script>
           ' . "\n";

        
    $_SESSION['request']->updateApiCommunication("Showing the Prices page" . "\n");
    printHtmlStuffSales();
    
} 



function pricesByTime($errorMessage, $performanceTime){
    
    if (!empty($_REQUEST['performanceTime'])){
        $_SESSION['performanceTime'] = $_REQUEST['performanceTime'];
    }
//    echo "<pre>";
////    print_r($_SESSION['performanceTime']);
//    print_r($_SESSION['performances']);
//    echo "</pre>";
//    exit('in performances');

    
    
    $errorDiv = '';
    if (strlen($errorMessage) > 1){
//        $errorMessage = "No more Member Tickets left, Lorem renew, please <br> go to a ticket desk for assitance. To purchase tickets <br> as a nonmember, tap Continue.";
        $errorDiv = '<div id="errorDiv">
                <div class="error2">
                    <center>
                    <table width=1100px bgcolor="#ffffff" cellspacing=0 cellpadding=20px>
                        <tr>
                            <td><img id="error" src="images/exclamationBox.png" width=75></td>
                            <td width=20></td>
                            <td><img id="error" src="images/exclamationBox.png" width=75></td>
                        </tr>
                        <tr>
                            <td width=20></td>
                            <!--<td width ="500" height="200" valign=top><font class="prompt1">' . $errorMessage . '</font></td> -->
                            <td align=center valign=top><font class="prompt1">' . $errorMessage . '</font></td>    
                            <td width=20></td>
                        </tr>
                        <tr>
                            <td width=20></td>
                            <td align=center>
                                <!--<table width=100%> -->
                                <table>
                                    <td id="continue" align=center valign=middle  class="button1" 
                                        onclick="document.getElementById(\'errorDiv\').style.visibility=\'hidden\';"
                                        valign=bottom>
                                        <font class="buttonText">Continue</font>
                                    </td>
                                </table>
                            </td>
                            <td width=20></td>
                        </tr>
                        <tr>
                            <td><img id="error" src="images/exclamationBox.png" width=75></td>
                            <td width=20></td>
                            <td><img id="error" src="images/exclamationBox.png" width=75></td>
                        </tr>
                    </table>
                    </center>
                </div>
            </div>';
    }
    

    // TODO: SUPPRESS MEMBER PRICING FOR NON-MEMBERS.


//    we will have $_REQUEST['mainCategory'];
//    if (!empty($_REQUEST['mainCategory'])){
//        $_SESSION['mainCategory'] = $_REQUEST['mainCategory'];
//    }
//
//    // now show all prices that match this main category.
//    // loop through all performances. get all prices that match.
//
//
//    if ($_SESSION['mainCategory'] == 'ga'){
//        $_SESSION['pricingGroup'] = 'Museum Admission';
//    } else {
//        // it will either be in the request variable or we will use the session variable.
//        if (!empty($_REQUEST['pricingGroup'])){
//            $_SESSION['pricingGroup'] = $_REQUEST['pricingGroup'];
//        }
//    }




    // ------------------------------------------------------------------------------------------
    // AT THIS POINT, I NEED A mainCategory and a pricingGroup chosen. The rest of the information will come from the performances in the session.


    // how many prices will I have?if (strpos($onePerformance->pricingGroup, $_SESSION['pricingGroup']) !== false){
                
    
    $priceCount = 0;
    $performanceDescriptions = array();
    foreach ($_SESSION['performances'] as $onePerformance){
//        if (strpos($onePerformance->pricingGroup, $_SESSION['genAdm']) !== false){
        if (strpos($onePerformance->pricingGroup, $_SESSION['pricingGroup']) !== false){
            foreach ($onePerformance->prices as $onePrice){
                if (in_array($onePrice->price_type_desc, $performanceDescriptions)){
//                    echo("here");
                    continue;
                } else {
                    $performanceDescriptions[] = $onePrice->price_type_desc; //Add performance to "used" performances array
                    $includeThisPrice = includeThisPrice($onePrice);
                    if ($includeThisPrice === true){
                        $priceCount++;
                    }
                }
            }
        }
    }
//    exit("pc = " . $priceCount);

    $classSize = 'Big';
    if ($priceCount > 3){
        $classSize = 'Small';
    }



    $maxQty = 10;

    $pricesTable = '
            <script>

                function changeValue(idx, addOrSubtract){
                    max = ' . $maxQty . ';

                    currentValue = document.getElementById("qtyDisplay_" + idx).innerHTML;

                        //alert("currentValue:" + currentValue);
                        if (addOrSubtract == "add"){
                            if (currentValue >= 0 && currentValue <= max){
                                document.getElementById("plus_" + idx).src="images/lightGreyPlusBar.png";
                            }
                            currentValue = (currentValue*1)+1;

                        } else {
                            if (currentValue >= 0 && currentValue <= max){
                                document.getElementById("minus_" + idx).src="images/lightGreyMinusBar.png";
                            }
                            currentValue = (currentValue*1)-1;
                        }

                    if (currentValue >=0 && currentValue <= max){
                        document.getElementById("qtyDisplay_" + idx).innerHTML = currentValue;
                        document.getElementById("qty_" + idx).value = currentValue;
                    }
                    setTimeout(function () { reCalc(); }, 250);
                    return;
                }

                




                function reCalc(){
                    // go through all elements.
                    // TODO: how many idxs?
                    total = ' . $priceCount . ';
                    //
                    //
                    max = ' . $maxQty . ';
                    min = 0;

                    qtyTotal = 0;
                    totalTotal = 0;
                    for (idx=0;idx<total;idx++){
                        currentValue = document.getElementById("qty_" + idx).value;
//                        alert("looking at " + idx);
//                        alert(document.getElementById("qty_" + idx).value);
//                        alert("currentValue:" + currentValue);
//                        if (document.getElementById("priceType_" + idx).value == "125" || document.getElementById("priceType_" + idx).value == "360"){
//                            currentValue = 1; // the minimum is 1.
//                            document.getElementById("minus_" + idx).src="images/lightGreyMinusBar.png";
//                            document.getElementById("plus_" + idx).src="images/lightGreyPlusBar.png";
//                        } else if (currentValue <= 0){
//                            // grey out minus button
//                            currentValue = 0;
//                            document.getElementById("qty_" + idx).value = 0;
//                            document.getElementById("qtyDisplay_" + idx).innerHTML = 0;
//                            document.getElementById("minus_" + idx).src="images/lightGreyMinusBar.png";
//                            document.getElementById("plus_" + idx).src="images/greenPlusBar.png";
//                        } else if (currentValue >= max){
//                            // grey out plus button
//                            currentValue = max;
//                            document.getElementById("qty_" + idx).value = max;
//                            document.getElementById("qtyDisplay_" + idx).innerHTML = max;
//                            document.getElementById("plus_" + idx).src="images/lightGreyPlusBar.png";
//                            document.getElementById("minus_" + idx).src="images/redMinusBar.png";
//                        } else {
//                            document.getElementById("plus_" + idx).src="images/greenPlusBar.png";
//                            document.getElementById("minus_" + idx).src="images/redMinusBar.png";
//                        }



                        
                        if (document.getElementById("priceType_" + idx).value == "125" || document.getElementById("priceType_" + idx).value == "360"){
                            min = 1;
                        }
                        


                        if (currentValue <= min){
                            // grey out minus button
                            currentValue = min;
                            document.getElementById("qty_" + idx).value = min;
                            document.getElementById("qtyDisplay_" + idx).innerHTML = min;
                            document.getElementById("minus_" + idx).src="images/lightGreyMinusBar.png";
                            document.getElementById("plus_" + idx).src="images/greenPlusBar.png";
                        } else if (currentValue >= max){
                            // grey out plus button
                            currentValue = max;
                            document.getElementById("qty_" + idx).value = max;
                            document.getElementById("qtyDisplay_" + idx).innerHTML = max;
                            document.getElementById("plus_" + idx).src="images/lightGreyPlusBar.png";
                            document.getElementById("minus_" + idx).src="images/redMinusBar.png";
                        } else {
                            document.getElementById("plus_" + idx).src="images/greenPlusBar.png";
                            document.getElementById("minus_" + idx).src="images/redMinusBar.png";
                        }


                        
                        // no matter what, we now have a currentValue
                        qtyTotal = (qtyTotal*1) + (currentValue*1);
                        document.getElementById("total_" + idx).value = document.getElementById("price_" + idx).value * document.getElementById("qty_" + idx).value;
                        document.getElementById("totalDisplay_" + idx).innerHTML = "$" + roundDecimals(document.getElementById("total_" + idx).value,2);
                        totalTotal = (totalTotal*1) + (document.getElementById("total_" + idx).value*1);
                    }
                    document.getElementById("qtyTotal").value = qtyTotal;
                    document.getElementById("qtyTotalDisplay").innerHTML = qtyTotal;
                    document.getElementById("totalTotal").value = totalTotal;
                    document.getElementById("totalTotalDisplay").innerHTML = "$" + roundDecimals(totalTotal,2);
                    //alert("qtyTotal: " + qtyTotal);

                    if (qtyTotal > 0){
                        document.getElementById("continueTable").style.visibility = "visible";
                    } else {
                        document.getElementById("continueTable").style.visibility = "hidden";
                    }


                    return;
                }


                function roundDecimals(original_number, decimals) {
                    var result1 = original_number * Math.pow(10, decimals)
                    var result2 = Math.round(result1)
                    var result3 = result2 / Math.pow(10, decimals)
                    result3 = Math.abs(result3);
                    return padWithZeros(result3, decimals)
                }


                function padWithZeros(rounded_value, decimal_places) {
                    // Convert the number to a string
                    var value_string = rounded_value.toString()
                    // Locate the decimal point
                    var decimal_location = value_string.indexOf(".")
                    // Is there a decimal point?
                    if (decimal_location == -1) {
                        // If no, then all decimal places will be padded with 0s
                        decimal_part_length = 0

                        // If decimal_places is greater than zero, tack on a decimal point
                        value_string += decimal_places > 0 ? "." : ""
                    }
                    else {
                        // If yes, then only the extra decimal places will be padded with 0s
                        decimal_part_length = value_string.length - decimal_location - 1
                    }
                    // Calculate the number of decimal places that need to be padded with 0s
                    var pad_total = decimal_places - decimal_part_length
                    if (pad_total > 0) {
                        // Pad the string with 0s
                        for (var counter = 1; counter <= pad_total; counter++)
                            value_string += "0"
                        }
                    return value_string
                }
                
                function postMyPricesForm() {
                    document.getElementById("timerDiv").style.visibility="visible";
                    document.getElementById("processingMessage").innerHTML = "Please wait...";
                    setTimeout(function() {
                    document.getElementById("timerDiv").style.visibility="visible";
                        document.myPricesForm.submit();
                        return true;
                    },250);
                }

            </script>

            <style>

                .labels{
                    font-family:helvetica;
                    font-weight:400;
                    font-size:30pt;
                }

                .textBoxes{
                    width:350px;
                    height:70px;
                    font-family:helvitica;
                    font-weight:400;
                    font-size:28pt;
                    text-align: center;
                }
                .continueButtons{
                    font-family:helvetica;
                    font-weight:700;
                    font-size:38pt;
                    color:#FFFFFF;
                    height:100px;
                    border: 2px solid black;
                }

            </style>


                <form name="myPricesForm" action="" method="post">
                <input id="task" name="task" type="hidden" value="reserve">
            <table width="1920px">' . "\n";
    
    
     $idx = 0;
     $performanceDescriptions = array();
    if (!empty($_SESSION['genAdm'])){
        $priceCount = 0;
        $performanceTypes = array();
        foreach ($_SESSION['performances'] as $onePerformance){
//        if (strpos($onePerformance->pricingGroup, $_SESSION['genAdm']) !== false){
        if (strpos($onePerformance->pricingGroup, $_SESSION['pricingGroup']) !== false){
                foreach ($onePerformance->prices as $onePrice){
                    if (in_array($onePrice->price_type_desc, $performanceTypes)){
//                        echo("here");
                        continue;
                    } else if(strtotime($onePrice->description) < strtotime($_SESSION['performanceTime'])){
                        continue;
                    } else {
                        $performanceTypes[] = $onePrice->price_type_desc; //Add performance to "used" performances array
                        $performanceDescriptions = includeThisPrice($onePrice);
                        if ($includeThisPrice === true){
                            $price = $onePrice->price;
                            if ($idx == 0){
                                $price = 28;
                            } else if ($idx == 1){
                                $price = 20;
                            } else {
                                $price = 0;
                            }
                            
                            
                            $pricesTable .= '<input type="hidden" id="description_' . $idx . '" name="description_' . $idx . '" value="' . $onePrice->price_type_desc . '">' . "\n";
                            $pricesTable .= '<input type="hidden" id="priceType_' . $idx . '" name="priceType_' . $idx . '" value="' . $onePrice->priceType . '">' . "\n";
                            $pricesTable .= '<input type="hidden" id="zoneNo_' . $idx . '" name="zoneNo_' . $idx . '" value="' . $onePrice->zoneNo . '">' . "\n";
                            $pricesTable .= '<input type="hidden" id="perfNo_' . $idx . '" name="perfNo_' . $idx . '" value="' . $onePerformance->perfNo . '">' . "\n";
                            $pricesTable .= '<input type="hidden" id="price_' . $idx . '" name="price_' . $idx . '" value="' . $price . '">' . "\n";


                            if ($onePrice->priceType == '125' || $onePrice->priceType == '360'){
                                $pricesTable .= '<input type="hidden" id="qty_' . $idx . '" name="qty_' . $idx . '" value="1">' . "\n";
                            } else {
                                $pricesTable .= '<input type="hidden" id="qty_' . $idx . '" name="qty_' . $idx . '" value="0">' . "\n";
                            }


                            $pricesTable .= '<input type="hidden" id="total_' . $idx . '" name="total_' . $idx . '" value="0">' . "\n";
                            $pricesTable .= '<tr>' . "\n";
                            $pricesTable .= '<td align="left" class="prompt1" style="font-size:30pt;padding:25px;border-top:1px solid #acacac;border-right:1px solid #acacac;padding-left: 400px"><nobr>' . $onePrice->price_type_desc . '</nobr></td>' . "\n";
                            $pricesTable .= '<td align="center" id="priceDisplay_' . $idx . '" class="prompt1" style="font-size:30pt;border-top:1px solid #acacac;border-right:1px solid #acacac;" width="200"><b>$' . $price . '</b></td>' . "\n";

                            $pricesTable .= '<td align="center" height="94px" width="150px" style="border-top:1px solid #acacac;"><img height="70px" id="minus_' . $idx . '" src="images/redMinusBar.png" onclick="changeValue(\'' . $idx . '\',\'subtract\');"></td>' . "\n";
                            $pricesTable .= '<td align="center" style="border-top:1px solid #acacac;" id="qtyDisplay_' . $idx . '" class="prompt2" width="100px">0</td>' . "\n";
                            $pricesTable .= '<td align="center" height="94px" width="150px" style="border-top:1px solid #acacac;border-right:1px solid #acacac;"><img height="70px" id="plus_' . $idx . '" src="images/greenPlusBar.png" onclick="changeValue(\'' . $idx . '\',\'add\');"></td>' . "\n";

                            $pricesTable .= '<td align="center" style="border-top:1px solid #acacac;" id="totalDisplay_' . $idx . '" class="prompt1" style="padding:25px" width="200px">$0.00</td>' . "\n";
                            $pricesTable .= '<td width="400px" style="border-top:1px solid #acacac;">&nbsp</td>' . "\n";
                            $pricesTable .= '</tr>' . "\n\n\n";

                            $idx++;
                        }
                    }
                }
            }
        }
    }
//    exit();
//    else {
//        foreach ($_SESSION['performances'] as $onePerformance){
//            $pieces = explode('-', $onePerformance->pricingGroup); 
//            $perfName = trim($pieces[0]);
//            $perfTime = trim($pieces[1]);
//
//            if ($perfName == $_SESSION['pricingGroup'] && $perfTime ==  $performanceTime){
//                foreach ($onePerformance->prices as $onePrice){
//
//                    $includeThisPrice = includeThisPrice($onePrice);
//                    if ($includeThisPrice === true){
//                        if (in_array($onePrice->description, $performanceTimes) ) { //If in array, skip iteration
//                            continue;
//                        } else {
//
//                            $pricesTable .= '<input type="hidden" id="description_' . $idx . '" name="description_' . $idx . '" value="' . $onePrice->description . '">' . "\n";
//                            $pricesTable .= '<input type="hidden" id="priceType_' . $idx . '" name="priceType_' . $idx . '" value="' . $onePrice->priceType . '">' . "\n";
//                            $pricesTable .= '<input type="hidden" id="zoneNo_' . $idx . '" name="zoneNo_' . $idx . '" value="' . $onePrice->zoneNo . '">' . "\n";
//                            $pricesTable .= '<input type="hidden" id="perfNo_' . $idx . '" name="perfNo_' . $idx . '" value="' . $onePerformance->perfNo . '">' . "\n";
//                            $pricesTable .= '<input type="hidden" id="price_' . $idx . '" name="price_' . $idx . '" value="' . $onePrice->price . '">' . "\n";
//
//
//                            if ($onePrice->priceType == '125' || $onePrice->priceType == '360'){
//                                $pricesTable .= '<input type="hidden" id="qty_' . $idx . '" name="qty_' . $idx . '" value="1">' . "\n";
//                            } else {
//                                $pricesTable .= '<input type="hidden" id="qty_' . $idx . '" name="qty_' . $idx . '" value="0">' . "\n";
//                            }
//
//
//                            $pricesTable .= '<input type="hidden" id="total_' . $idx . '" name="total_' . $idx . '" value="0">' . "\n";
//                            $pricesTable .= '<tr>' . "\n";
//                            $pricesTable .= '<td align="left" class="prompt1" style="font-size:30pt;padding:25px;border-top:1px solid #acacac;border-right:1px solid #acacac;padding-left: 400px"><nobr>' . str_replace('Admission', '',$onePrice->description) . '</nobr></td>' . "\n";
//                            $pricesTable .= '<td align="center" id="priceDisplay_' . $idx . '" class="prompt1" style="font-size:30pt;border-top:1px solid #acacac;border-right:1px solid #acacac;" width="200"><b>$' . $onePrice->price . '</b></td>' . "\n";
//
//                            $pricesTable .= '<td align="center" height="94px" width="150px" style="border-top:1px solid #acacac;"><img height="70px" id="minus_' . $idx . '" src="images/redMinusBar.png" onclick="changeValue(\'' . $idx . '\',\'subtract\');"></td>' . "\n";
//                            $pricesTable .= '<td align="center" style="border-top:1px solid #acacac;" id="qtyDisplay_' . $idx . '" class="prompt2" width="100px">0</td>' . "\n";
//                            $pricesTable .= '<td align="center" height="94px" width="150px" style="border-top:1px solid #acacac;border-right:1px solid #acacac;"><img height="70px" id="plus_' . $idx . '" src="images/greenPlusBar.png" onclick="changeValue(\'' . $idx . '\',\'add\');"></td>' . "\n";
//
//                            $pricesTable .= '<td align="center" style="border-top:1px solid #acacac;" id="totalDisplay_' . $idx . '" class="prompt1" style="padding:25px" width="200px">$0.00</td>' . "\n";
//                            $pricesTable .= '<td width="400px" style="border-top:1px solid #acacac;">&nbsp</td>' . "\n";
//                            $pricesTable .= '</tr>' . "\n\n\n";
//
//                            $idx++;
//                            $performanceTimes[] = $onePrice->description; //Add performance to "used" performances array
//                        }
//                    }
//                }
//            }
//        }
    $pricesTable .= '<input type="hidden" id="qtyTotal" name="qtyTotal" value="0">
                    <input type="hidden" id="totalTotal" name="totalTotal" value="0">
                    <tr height="120px">
                        <td style="border-top:1px solid #acacac;border-right:1px solid #acacac;border-bottom:1px solid #acacac;">&nbsp</td>
                        <td align="center" class="prompt2" style="border-top:1px solid #acacac;border-right:1px solid #acacac;border-bottom:1px solid #acacac;">Total</td>
                        <td align="center" class="prompt2" id="qtyTotalDisplay" colspan="3" style="border-top:1px solid #acacac;border-right:1px solid #acacac;border-bottom:1px solid #acacac;">0</td>
                        <td align="center" class="prompt2" id="totalTotalDisplay" style="color:#d91e18;border-top:1px solid #acacac;border-bottom:1px solid #acacac;">&nbsp</td>
                        <td colspan="2" style="border-top:1px solid #acacac;border-bottom:1px solid #acacac;">&nbsp</td>
                    </tr>';

    $pricesTable .= '</table></form>';





    $_SESSION['title'] = 'pricesByTime';

    


    
    $_SESSION['topTable'] = '

             <center>
                 <table>
                     <tr height="100px">
                         <td>
                             <center><font class="prompt2">' . $_SESSION['pricingGroup'] . ' - ' . $performanceTime . '</font></center>
                             <center><font class="prompt1" >Adjust Ticket Quantities</font></center>
                         </td>
                     </tr>
                </table>
                <br>
                ' . $pricesTable . '
                <div style="position:absolute;height:70px;top:600px;width:1920px;display:table;">
                        <table id="continueTable" width=100%>
                            <tr height="65px">
                                <td></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td id="continue" valign=middle class="button1"
                                    onMouseOut="document.getElementById(\'continue\').style.backgroundImage = \'url(images/redBar.png)\';"
                                    onclick="postMyPricesForm();;" valign=bottom>
                                    Continue
                                </td>
                                <td></td>
                            </tr>
                        </table>
                    </div>
              </center>

     ' . $errorDiv . '
     ';





        $_SESSION['navigationTable'] = '
            <!--<table id="continueTable" width=100% style="visibility:hidden;">
                <tr>
                    <td></td>
                    <td id="continue" valign=middle class="button1"
                        onMouseOut="document.getElementById(\'continue\').style.backgroundImage = \'url(images/redBar.png)\';"
                        onclick="postMyPricesForm();;" valign=bottom>
                        Continue
                    </td>
                    <td></td>
                </tr>
                <tr height="100px">
                    <td></td>
                </tr> 
            </table> -->



             <script>reCalc()</script>
           ' . "\n";

        
    $_SESSION['request']->updateApiCommunication("Showing the PricesByTime page" . "\n");
    printHtmlStuffSales();
    
} 


function performancesOld($startNumber){  //csn 20160419
// TODO: add small class sizes.
//          test with many performances
//          confirm that the performances are in chronological order.
    
    
    
    
    
    // we will have $_REQUEST['mainCategory'];
    if (!empty($_REQUEST['mainCategory'])){
        $_SESSION['mainCategory'] = $_REQUEST['mainCategory'];
    }
    
    if ($_SESSION['mainCategory'] == 'ga'){
        prices($errorMessage); // how the f did we get here? go to prices.
    }
    
    
//    echo "<pre>";
//    print_r($_SESSION);
//    echo "</pre>";
//    exit('in performances');
    

    $performanceCount = 0;
    foreach ($_SESSION['performances'] as $onePerformance){
        if ($onePerformance->mainCategory == 'other'){
                $performanceCount++;
        }
    }
    
    
    
    
    
    $classSize = 'Big';
//    if ($performanceCount > 4){
//        $classSize = 'Small';
//    }
    
    if ($performanceCount == 0){
        $performancesTable = '
                    <form name="myPerformancesForm" action="" method="post">
                    <input id="task" name="task" type="hidden" value="prices">
                    <input id="pricingGroup" name="pricingGroup" type="hidden" value="">
                    </form>
                        <table width="60%">' . "\n";

                $performancesTable = '<tr><td class="performancesNoneAvailable">There are no more events today.</td></tr>';
        
        $performancesTable .= "\n\n" . '</table></form>';
        
    } else {
    
    
        // display performances.
        $performancesTable = '
                    <form name="myPerformancesForm" action="" method="post">
                    <input id="task" name="task" type="hidden" value="prices">
                    <input id="pricingGroup" name="pricingGroup" type="hidden" value="">
                    </form>
                        <table width="60%">' . "\n";
        
//        // may need to sort them first...
//        foreach ($_SESSION['performances'] as $onePerformance){
//            if ($onePerformance->mainCategory == 'other'){
//                //$words = explode("T",$onePerformance->perfDateTime);
//                //$words2 = explode("-",$words[1]); // split the from and to time.
//                //$dateTime = strtotime($words[0] . $words2[0]);
//                //$timeFormatted = date('g:i a', $dateTime);
//                $onePerformance->pricingGroup = str_replace("'", "`", $onePerformance->pricingGroup);  //csn 20160405
//
//                $performancesTable .= '<tr><td class="performances' . $classSize . 'Td" onclick="this.className=\'performances' . $classSize . 'Td_clicked\'; postMyPerformancesForm(\'' . $onePerformance->pricingGroup . '\');">' . $onePerformance->pricingGroup . '</td></tr>';
//                $performancesTable .= '<tr><td height="10px"></td></tr>';
//            }
//        }
//        $startNumber = 5;
//        $numberToShow = 4;
        $startNumber = 0;
        $numberToShow = 5;
        // may need to sort them first...
        foreach ($_SESSION['performances'] as $onePerformance){
            if ($startNumber<$numberToShow) {
                if ($onePerformance->mainCategory == 'other'){
                    //$words = explode("T",$onePerformance->perfDateTime);
                    //$words2 = explode("-",$words[1]); // split the from and to time.
                    //$dateTime = strtotime($words[0] . $words2[0]);
                    //$timeFormatted = date('g:i a', $dateTime);
                    $onePerformance->pricingGroup = str_replace("'", "`", $onePerformance->pricingGroup);  //csn 20160405

                    $performancesTable .= '<tr><td class="performances' . $classSize . 'Td" onclick="this.className=\'performances' . $classSize . 'Td_clicked\'; postMyPerformancesForm(\'' . $onePerformance->pricingGroup . '\');">' . $onePerformance->pricingGroup . '</td></tr>';
                    $performancesTable .= '<tr><td height="10px"></td></tr>';
                }
                $startNumber++;
            }
        }
        
        
        
        
        $performancesTable .= "\n\n" . '</table></form>';
    }
    
    
    
    
    $_SESSION['title'] = 'performances';
    
    $_SESSION['javaScript'] = '
        
    
        <script language="javascript">

            function postMyPerformancesForm(pricingGroup) {
                document.getElementById("timerDiv").style.visibility="visible";
                document.getElementById("processingMessage").innerHTML = "Please wait...";
                setTimeout(function() {
                    document.getElementById("timerDiv").style.visibility="visible";
                    document.getElementById("pricingGroup").value = pricingGroup;
                    document.myPerformancesForm.submit();
                    return true;
                },250);
            }

        </script>
        


      ';
    

       $_SESSION['topTable'] = '
                <center>
                    <table>
                        <tr height="100px">
                            <td class="pricesMessage">
                                <center>FILMS, LECTURES, ETC.<br>CHOOSE A PERFORMANCE BELOW<br></center>
                            </td>
                        </tr>
                   </table>
                   
                   ' . $performancesTable . '
                 </center>
                


             


         '; 
           
            
            
          
          
    $_SESSION['navigationTable'] = '<table><tr><td></td></table>';



    printHtmlStuffSales();
    
    
    
    
}


function performances20160623($startNumber){
// TODO: add small class sizes.
//          test with many performances
//          confirm that the performances are in chronological order.
    
    
   
    
    
    // we will have $_REQUEST['mainCategory'];
    if (!empty($_REQUEST['mainCategory'])){
        $_SESSION['mainCategory'] = $_REQUEST['mainCategory'];
    }
    
    if ($_SESSION['mainCategory'] == 'ga'){
        prices($errorMessage); // how the f did we get here? go to prices.
    }
    
    
//    echo "<pre>";
//    print_r($_SESSION['performances']);
//    echo "</pre>";
//    exit('in performances');
    

    $performanceCount = 0;
    foreach ($_SESSION['performances'] as $onePerformance){
        
        if ($onePerformance->mainCategory == 'other'){
                $performanceCount++;
        }
    }
    
    
    
    
    
    $classSize = 'Big';
    
    if ($performanceCount == 0){
        $performancesTable = '
                            <form name="myPerformancesForm" action="" method="post">
                            <input id="task" name="task" type="hidden" value="prices">
                            <input id="pricingGroup" name="pricingGroup" type="hidden" value="">
                            </form>
                                <table width="60%">' . "\n";
        $performancesTable .= '<tr><td class="performancesNoneAvailable">There are no more events available today</td></tr>';        
        $performancesTable .= "\n\n" . '</table></form>';
        
    } else {
        // display performances.
        $performancesTable = '
                            <form name="myPerformancesForm" action="" method="post">
                            <input id="task" name="task" type="hidden" value="prices">
                            <input id="pricingGroup" name="pricingGroup" type="hidden" value="">
                            <input type="hidden" id="startNumber" name="startNumber" value="0">
                            </form>
                                <table width="60%">' . "\n";
        
        
        
        
        
//        // may need to sort them first...
//        foreach ($_SESSION['performances'] as $onePerformance){
//            if ($onePerformance->mainCategory == 'other'){
//                //$words = explode("T",$onePerformance->perfDateTime);
//                //$words2 = explode("-",$words[1]); // split the from and to time.
//                //$dateTime = strtotime($words[0] . $words2[0]);
//                //$timeFormatted = date('g:i a', $dateTime);
//                $onePerformance->pricingGroup = str_replace("'", "`", $onePerformance->pricingGroup);  //csn 20160405
//
//                $performancesTable .= '<tr><td class="performances' . $classSize . 'Td" onclick="this.className=\'performances' . $classSize . 'Td_clicked\'; postMyPerformancesForm(\'' . $onePerformance->pricingGroup . '\');">' . $onePerformance->pricingGroup . '</td></tr>';
//                $performancesTable .= '<tr><td height="10px"></td></tr>';
//            }
//        }
        
        $counter = 0;
        $numberToShow = 5;
        // may need to sort them first...
        foreach ($_SESSION['performances'] as $onePerformance){
//            if ($startNumber<$numberToShow) {
            
                if ($onePerformance->mainCategory == 'other'){
                    //$words = explode("T",$onePerformance->perfDateTime);
                    //$words2 = explode("-",$words[1]); // split the from and to time.
                    //$dateTime = strtotime($words[0] . $words2[0]);
                    //$timeFormatted = date('g:i a', $dateTime);
                    if ($counter >= $startNumber && $counter < ($startNumber + $numberToShow)) {
                        $onePerformance->pricingGroup = str_replace("'", "`", $onePerformance->pricingGroup);  //csn 20160405
                        $performancesTable .= '<tr><td class="performances' . $classSize . 'Td" onclick="this.className=\'performances' . $classSize . 'Td_clicked\'; postMyPerformancesForm(\'' . $onePerformance->pricingGroup . '\');">' . $onePerformance->pricingGroup . '</td></tr>';
                        $performancesTable .= '<tr><td height="10px"></td></tr>';
                    }
                    $counter++;
                }
                
            
        }
        $performancesTable .= "\n\n" . '</table></form>';
    }
    
//    exit("counter = " . $counter);
    
    
    
    $_SESSION['title'] = 'performances';

    $_SESSION['javaScript'] = '
        <script language="javascript">

            function postMyPerformancesForm(pricingGroup) {
                document.getElementById("timerDiv").style.visibility="visible";
                document.getElementById("processingMessage").innerHTML = "Getting Prices...";
                setTimeout(function() {
                    document.getElementById("timerDiv").style.visibility="visible";
                    document.getElementById("pricingGroup").value = pricingGroup;
                    document.myPerformancesForm.submit();
                    return true;
                },250);
            }


            function moreButton(task, startNumber) {
            
                setTimeout(function() {
                    document.getElementById("startNumber").value = startNumber;
                    document.myPerformancesForm.task.value = task;
                    document.myPerformancesForm.submit();
                    return true;
                },250);
            }

            function backButton(task, startNumber) {
                setTimeout(function() {
                    document.getElementById("startNumber").value = startNumber;
                    document.myPerformancesForm.task.value = task;
                    document.myPerformancesForm.submit();
                    return true;
                },250);
            }
        </script>';
    
    
    $moreButton = '<td width="10%"></td>';
    $backButton = '<td width="10%"></td>';
    $nextStart = $startNumber + $numberToShow;
    $backStart = $startNumber - $numberToShow;
//    exit("counter = " . $nextStart);
//    exit("next start = " . $nextStart);
    
//    exit($startNumber);
    if (($startNumber < 1) && ($counter > $numberToShow)){
        $moreButton = '<td bgcolor="#df3c31" style="border-radius: 5px;font-family:Verdana;color:#FFFFFF;font-size:20px;" align="center" width="10%" id="more" onclick="moreButton(\'performances\',' . $nextStart . ')"> next >> </td>';
    } else if ($counter > ($startNumber + $numberToShow)){
        $moreButton = '<td bgcolor="#df3c31" style="border-radius: 5px;font-family:Verdana;color:#FFFFFF;font-size:20px;" align="center" width="10%" id="more" onclick="moreButton(\'performances\',' . $nextStart . ')"> next >> </td>';
        $backButton = '<td bgcolor="#df3c31" style="border-radius: 5px;font-family:Verdana;color:#FFFFFF;font-size:20px;" align="center" width="10%" id="back" onclick="backButton(\'performances\',' . $backStart . ')"> << back </td>';
    } else {
        if ($counter > $numberToShow){
            $backButton = '<td bgcolor="#df3c31" style="border-radius: 5px;font-family:Verdana;color:#FFFFFF;font-size:20px;" align="center" width="10%" id="back" onclick="backButton(\'performances\',' . $backStart . ')"> << back </td>';
        }
    }

    $_SESSION['topTable'] = '
            <center>
                <table>
                    <tr height="100px">
                        <td class="pricesMessage"><center>FILMS, LECTURES, ETC.<br>CHOOSE A PERFORMANCE BELOW<br></center></td>
                    </tr>
                </table>
                ' . $performancesTable . '
            </center>

            <br>
            

            <div style="position:absolute;height:70px;top:725px;width:1920px;display:table;">
                <table height="60px" width="100%" align="center">
                    <tr align="center">
                        <td width="27.5%"></td>
                        ' . $backButton . '
                        <td width="25%"></td>
                        ' . $moreButton . '
                        <td width="27.5%"></td>
                    </tr>
                </table>
            </div>';

    $_SESSION['navigationTable'] = '<table><tr><td></td></table>';



    printHtmlStuffSales();
    
    
    
    
}

function performancesWithoutTimes($startNumber){
// TODO: add small class sizes.
//          test with many performances
//          confirm that the performances are in chronological order.

//    exit("hi");

    

    // we will have $_REQUEST['mainCategory'];
    if (!empty($_REQUEST['mainCategory'])){
        $_SESSION['mainCategory'] = $_REQUEST['mainCategory'];
    }

    if ($_SESSION['mainCategory'] == 'ga'){
        prices($errorMessage); // how the f did we get here? go to prices.
    }
    
    if ($_SESSION['mainCategory'] == 'other'){
        $_SESSION['genAdm'] = 'General Admission';
    }


//    echo "<pre>";
//    print_r($_SESSION['performances']);
//    echo "</pre>";
//    exit('in performances');


    $performanceCount = 0;
    foreach ($_SESSION['performances'] as $onePerformance){
        if ($onePerformance->mainCategory == 'other'){
                $performanceCount++;
        }
    }


//    echo("pc = " . $performanceCount);
//    exit();
    $classSize = 'Big';

    if ($performanceCount == 0){
        $performancesTable = '
                            <form name="myPerformancesForm" action="" method="post">
                                <input id="task" name="task" type="hidden" value="performanceTimes">
                                <input id="pricingGroup" name="pricingGroup" type="hidden" value="">
                            </form>
                            <table width="60%">' . "\n";
        $performancesTable .= '<tr><td class="performancesNoneAvailable">There are no more events available today</td></tr>';
        $performancesTable .= '</table>';

    } else {
        // display performances.
        $performancesTable = '
                            <form name="myPerformancesForm" action="" method="post">
                                <input id="task" name="task" type="hidden" value="performanceTimes">
                                <input id="pricingGroup" name="pricingGroup" type="hidden" value="">
                                <input type="hidden" id="startNumber" name="startNumber" value="0">
                            </form>
                            <table width="60%">';
        
        

        
        
        
        $counter = 0;
        $numberToShow = 4;
        // may need to sort them first...
        $performances = array();
        foreach ($_SESSION['performances'] as $onePerformance){

            if ($onePerformance->mainCategory == 'other'){
                $dateToday = $_SESSION['date'];
                if (!empty($_SESSION['dateOverride'])){
                    $dateToday = $_SESSION['dateOverride'];
                }
                $pieces = explode('-', $onePerformance->pricingGroup); 
                $performanceName = trim($pieces[0]);
                $words = explode("T",$onePerformance->perfDateTime);
                $perfDate = date("Ymd",strtotime($words[0]));
//                echo "<pre>";
//                print_r($onePerformance);
//                echo "</pre>";
                
//                echo $perfDate . $dateToday;
                $performancesTable .= '<!-- ' . $performanceName . '-->';
                if ($performanceName == $_SESSION['genAdm']){
                    continue;
                } else if (in_array($performanceName, $performances) ) { //If in array, skip iteration
                    continue;
                } else if ($perfDate != $dateToday){
                    continue;
                } else {
                    $performances[] = $onePerformance->description; //Add performance to "used" performances array
                    if ($counter >= $startNumber && $counter < ($startNumber + $numberToShow)) {
                        $onePerformance->description = str_replace("'", "`", $onePerformance->description);  //csn 20160405
                        $performancesTable .= ''
                                . '<tr><td height="100" id="performance' . $counter . '" style="background:url(\'images/redBar.png\');background-size:100% 100%;" align="center" bgcolor="#f22613" class="prompt1" '
                                    . 'onMouseOver="document.getElementById(\'performance' . $counter . '\').style.backgroundImage = \'url(images/redBar_clicked.png)\';" '
                                    . 'onMouseOut="document.getElementById(\'performance' . $counter . '\').style.backgroundImage = \'url(images/redBar.png)\';" '
                                    . 'onclick="document.getElementById(\'performance' . $counter . '\').style.backgroundImage = \'url(images/redBar_clicked.png)\';'
                                    . 'postMyPerformancesForm(\'' . $onePerformance->description . '\');">'
                                    . '<font color="#ffffff"><b>' . $onePerformance->description . '</b></font>'
                                . '</td></tr>';
                        
                        $performancesTable .= '<tr><td height="10px"></td></tr>';
                    }
                    $counter++;
                }
            }
        }
        $performancesTable .= "\n\n" . '</table></form>';
    }
//    exit();
    $_SESSION['title'] = 'performanceWithoutTimes';

    $_SESSION['javaScript'] = '
        <script language="javascript">

            function postMyPerformancesForm(pricingGroup) {
                document.getElementById("timerDiv").style.visibility="visible";
                document.getElementById("processingMessage").innerHTML = "Getting Times...";
                setTimeout(function() {
                document.getElementById("timerDiv").style.visibility="visible";
                    document.getElementById("pricingGroup").value = pricingGroup;
                    document.myPerformancesForm.submit();
                    return true;
                },250);
            }


            function moreButton(task, startNumber) {
                setTimeout(function() {
                    document.getElementById("startNumber").value = startNumber;
                    document.myPerformancesForm.task.value = task;
                    document.myPerformancesForm.submit();
                    return true;
                },250);
            }

            function backButton(task, startNumber) {
                setTimeout(function() {
                    document.getElementById("startNumber").value = startNumber;
                    document.myPerformancesForm.task.value = task;
                    document.myPerformancesForm.submit();
                    return true;
                },250);
            }
        </script>';


    $moreButton = '<td width="10%"></td>';
    $backButton = '<td width="10%"></td>';
    $nextStart = $startNumber + $numberToShow;
    $backStart = $startNumber - $numberToShow;
//    exit("counter = " . $nextStart);
//    exit("next start = " . $nextStart);

//    exit("counter = " . $counter);
//    exit($startNumber);
    if (($startNumber < 1) && ($counter > $numberToShow)){
        $moreButton = '<td id="more" valign=middle class="button1"
        onMouseOver="document.getElementById(\'more\').style.backgroundImage = \'url(images/redBar_clicked.png)\';"
        onMouseOut="document.getElementById(\'more\').style.backgroundImage = \'url(images/redBar.png)\';"
        onclick="document.getElementById(\'more\').style.backgroundImage = \'url(images/redBar_clicked.png)\';moreButton(\'performancesWithoutTimes\',' . $nextStart . ');;" valign=bottom>
                            More
                        </td>';
                //<td bgcolor="#df3c31" style="border-radius: 5px;font-family:Verdana;color:#FFFFFF;font-size:20px;" align="center" width="10%" id="more" onclick="moreButton(\'performances\',' . $nextStart . ')"> next >> </td>
    } else if ($counter > ($startNumber + $numberToShow)){
        $moreButton = '<td id="more" valign=middle class="button1"
        onMouseOver="document.getElementById(\'more\').style.backgroundImage = \'url(images/redBar_clicked.png)\';"
        onMouseOut="document.getElementById(\'more\').style.backgroundImage = \'url(images/redBar.png)\';"
        onclick="document.getElementById(\'more\').style.backgroundImage = \'url(images/redBar_clicked.png)\';moreButton(\'performancesWithoutTimes\',' . $nextStart . ');;" valign=bottom>
                            More
                        </td>';
        $backButton = '<td id="previous" valign=middle class="button1"
        onMouseOver="document.getElementById(\'previous\').style.backgroundImage = \'url(images/redBar_clicked.png)\';"
        onMouseOut="document.getElementById(\'previous\').style.backgroundImage = \'url(images/redBar.png)\';"
        onclick="document.getElementById(\'previous\').style.backgroundImage = \'url(images/redBar_clicked.png)\';backButton(\'performancesWithoutTimes\',' . $backStart . ');;" valign=bottom>
                            Back
                        </td>';
    } else {
        if ($counter > $numberToShow){
            $backButton = '<td id="back" valign=middle class="button1"
            onMouseOver="document.getElementById(\'back\').style.backgroundImage = \'url(images/redBar_clicked.png)\';"
            onMouseOut="document.getElementById(\'back\').style.backgroundImage = \'url(images/redBar.png)\';"
            onclick="document.getElementById(\'back\').style.backgroundImage = \'url(images/redBar_clicked.png)\';backButton(\'performancesWithoutTimes\',' . $backStart . ');;" valign=bottom>
                            Back
                        </td>';

            //$backButton = '<td bgcolor="#df3c31" style="border-radius: 5px;font-family:Verdana;color:#FFFFFF;font-size:20px;" align="center" width="10%" id="back" onclick="backButton(\'performances\',' . $backStart . ')"> << back </td>';
        }
    }

    $_SESSION['topTable'] = '
            <center>
                <table>
                    <tr height="100px">
                        <td class="prompt2"><center>Program Tickets</center></td>
                    </tr>
                    <tr height="50px">
                        <td class="prompt1"><center>Your General Admission includes your choice of the following shows</center></td>
                    </tr>
                </table>
                ' . $performancesTable . '
            </center>

            <br>


            <div style="position:absolute;height:70px;top:725px;width:1920px;display:table;">
                <table height="60px" width="100%" align="center">
                    <tr align="center">
                        <td width="27.5%"></td>
                        ' . $backButton . '
                        <td width="25%"></td>
                        ' . $moreButton . '
                        <td width="27.5%"></td>
                    </tr>
                </table>
            </div>';

    $_SESSION['navigationTable'] = '<table><tr><td></td></table>';



    printHtmlStuffSales();
    
    
}


function performances($startNumber){
// TODO: add small class sizes.
//          test with many performances
//          confirm that the performances are in chronological order.





    // we will have $_REQUEST['mainCategory'];
    if (!empty($_REQUEST['mainCategory'])){
        $_SESSION['mainCategory'] = $_REQUEST['mainCategory'];
    }

    if ($_SESSION['mainCategory'] == 'ga'){
        prices($errorMessage); // how the f did we get here? go to prices.
    }


//    echo "<pre>";
//    print_r($_SESSION['performances']);
//    echo "</pre>";
//    exit('in performances');


    $performanceCount = 0;
    foreach ($_SESSION['performances'] as $onePerformance){

        if ($onePerformance->mainCategory == 'other'){
                $performanceCount++;
        }
    }





    $classSize = 'Big';

    if ($performanceCount == 0){
        $counter = 0;
        $numberToShow = 0;
        $performancesHeadings = '
            <center>
                <table>
                    <tr height="75px">
                        <td class="programPrompt2"><center></center></td>
                    </tr>
                    <tr height="75px">
                        <td class="programPrompt1"><center></center></td>
                    </tr>
                </table>
        
        ';
        $performancesTable = '
                            <form name="myPerformancesForm" action="" method="post">
                            <input id="task" name="task" type="hidden" value="prices">
                            <input id="pricingGroup" name="pricingGroup" type="hidden" value="">
                            </form>
                                
                                <table width="60%"><center>' . "\n";
        //$performancesTable .= '<tr><td class="prompt2">There are no more events available today</td></tr>';
        $performancesTable .= '<tr><td class="prompt2">There are no more events today.</td></tr>';
        $performancesTable .= "\n\n" . '</center></table></form>';

    } else {
        // display performances.
        $performancesHeadings = '
            <center>
                <table>
                    <tr height="75px">
                        <td class="programPrompt2"><center>Program Tickets</center></td>
                    </tr>
                    <tr height="75px">
                        <td class="programPrompt1"><center>Select an Event</center></td>
                    </tr>
                </table>
        
        ';
        $performancesTable = '
                            <form name="myPerformancesForm" action="" method="post">
                            <input id="task" name="task" type="hidden" value="prices">
                            <input id="pricingGroup" name="pricingGroup" type="hidden" value="">
                            <input type="hidden" id="startNumber" name="startNumber" value="0">
                            </form>
                                <table width="60%">' . "\n";





//        // may need to sort them first...
//        foreach ($_SESSION['performances'] as $onePerformance){
//            if ($onePerformance->mainCategory == 'other'){
//                //$words = explode("T",$onePerformance->perfDateTime);
//                //$words2 = explode("-",$words[1]); // split the from and to time.
//                //$dateTime = strtotime($words[0] . $words2[0]);
//                //$timeFormatted = date('g:i a', $dateTime);
//                $onePerformance->pricingGroup = str_replace("'", "`", $onePerformance->pricingGroup);  //csn 20160405
//
//                $performancesTable .= '<tr><td class="performances' . $classSize . 'Td" onclick="this.className=\'performances' . $classSize . 'Td_clicked\'; postMyPerformancesForm(\'' . $onePerformance->pricingGroup . '\');">' . $onePerformance->pricingGroup . '</td></tr>';
//                $performancesTable .= '<tr><td height="10px"></td></tr>';
//            }
//        }

        $counter = 0;
        $numberToShow = 4;
        // may need to sort them first...
        foreach ($_SESSION['performances'] as $onePerformance){
//            if ($startNumber<$numberToShow) {

                if ($onePerformance->mainCategory == 'other'){
                    //$words = explode("T",$onePerformance->perfDateTime);
                    //$words2 = explode("-",$words[1]); // split the from and to time.
                    //$dateTime = strtotime($words[0] . $words2[0]);
                    //$timeFormatted = date('g:i a', $dateTime);
                    if ($counter >= $startNumber && $counter < ($startNumber + $numberToShow)) {
                        $onePerformance->pricingGroup = str_replace("'", "`", $onePerformance->pricingGroup);  //csn 20160405
                        $performancesTable .= '<tr><td height="100" id="performance' . $counter . '" style="background:url(\'images/redBar.png\');background-size:100% 100%;" align="center" bgcolor="#f22613" class="prompt1"
                        onMouseOut="document.getElementById(\'performance' . $counter . '\').style.backgroundImage = \'url(images/redBar.png)\';"
                        onclick="postMyPerformancesForm(\'' . $onePerformance->pricingGroup . '\');"><font color="#ffffff"><b>' . $onePerformance->pricingGroup . '</b></font></td></tr>';
                        $performancesTable .= '<tr><td height="10px"></td></tr>';
                    }
                    $counter++;
                }


        }
        $performancesTable .= "\n\n" . '</table></form>';
    }

//    exit("counter = " . $counter);



    $_SESSION['title'] = 'performances';

    $_SESSION['javaScript'] = '
        <script language="javascript">

            function postMyPerformancesForm(pricingGroup) {
                document.getElementById("timerDiv").style.visibility="visible";
                document.getElementById("processingMessage").innerHTML = "Please wait...";
                setTimeout(function() {
                document.getElementById("timerDiv").style.visibility="visible";
                    document.getElementById("pricingGroup").value = pricingGroup;
                    document.myPerformancesForm.submit();
                    return true;
                },250);
            }


            function moreButton(task, startNumber) {

                setTimeout(function() {
                    document.getElementById("startNumber").value = startNumber;
                    document.myPerformancesForm.task.value = task;
                    document.myPerformancesForm.submit();
                    return true;
                },250);
            }

            function backButton(task, startNumber) {
                setTimeout(function() {
                    document.getElementById("startNumber").value = startNumber;
                    document.myPerformancesForm.task.value = task;
                    document.myPerformancesForm.submit();
                    return true;
                },250);
            }
        </script>';


    $moreButton = '<td width="10%"></td>';
    $backButton = '<td width="10%"></td>';
    $nextStart = $startNumber + $numberToShow;
    $backStart = $startNumber - $numberToShow;
//    exit("counter = " . $nextStart);
//    exit("next start = " . $nextStart);

//    exit($startNumber);
    if (($startNumber < 1) && ($counter > $numberToShow)){
        $moreButton = '<td id="more" valign=middle class="button1"
        onMouseOut="document.getElementById(\'more\').style.backgroundImage = \'url(images/redBar.png)\';"
        onclick="moreButton(\'performances\',' . $nextStart . ');;" valign=bottom>
                            More
                        </td>';
                //<td bgcolor="#df3c31" style="border-radius: 5px;font-family:Verdana;color:#FFFFFF;font-size:20px;" align="center" width="10%" id="more" onclick="moreButton(\'performances\',' . $nextStart . ')"> next >> </td>
    } else if ($counter > ($startNumber + $numberToShow)){
        $moreButton = '<td id="more" valign=middle class="button1"
        onMouseOut="document.getElementById(\'more\').style.backgroundImage = \'url(images/redBar.png)\';"
        onclick="moreButton(\'performances\',' . $nextStart . ');;" valign=bottom>
                            More
                        </td>';
        $backButton = '<td id="previous" valign=middle class="button1"
        onMouseOut="document.getElementById(\'previous\').style.backgroundImage = \'url(images/redBar.png)\';"
        onclick="backButton(\'performances\',' . $backStart . ');;" valign=bottom>
                            Back
                        </td>';
    } else {
        if ($counter > $numberToShow){
            $backButton = '<td id="back" valign=middle class="button1"
            onMouseOut="document.getElementById(\'back\').style.backgroundImage = \'url(images/redBar.png)\';"
            onclick="backButton(\'performances\',' . $backStart . ');;" valign=bottom>
                            Back
                        </td>';

            //$backButton = '<td bgcolor="#df3c31" style="border-radius: 5px;font-family:Verdana;color:#FFFFFF;font-size:20px;" align="center" width="10%" id="back" onclick="backButton(\'performances\',' . $backStart . ')"> << back </td>';
        }
    }
    
    
    
    

    $_SESSION['topTable'] = 
                    $performancesHeadings 
                  . $performancesTable . '
            </center>

            <br>


            <div style="position:absolute;height:70px;top:725px;width:1920px;display:table;">
                <table height="60px" width="100%" align="center">
                    <tr align="center">
                        <td width="27.5%"></td>
                        ' . $backButton . '
                        <td width="25%"></td>
                        ' . $moreButton . '
                        <td width="27.5%"></td>
                    </tr>
                </table>
            </div>';

    $_SESSION['navigationTable'] = '<table><tr><td></td></table>';



    printHtmlStuffSales();




} 


function groups(){
    $_SESSION['request'] = new Request();
    $_SESSION['request']->insert();
    
    $_SESSION['request']->updateApiCommunication("About to display groups"  . "\n");
     
//TODO: disable "other" button if there are no other performances. Show a special message.
    
    
    
    
    
    // get latest session data...
    //Retreiving GetPerformances data from text file
    //if (!empty($_REQUEST['testmode'])){
    //    $_SESSION['performances'] = unserialize(file_get_contents('/ECommerceSessions/demo/sessionTest.txt'));
    //}else {
    //    $_SESSION['performances'] = unserialize(file_get_contents('/ECommerceSessions/8888/session.txt'));
   //}
    
    
    // verdana, 70 in gimp. italics and bold.
    
    
    
    /* based on request data, we'll do 1 of the following:
     
     * if $_REQUEST['memberNumber']
        * 1) Is this a member?
        * 
        *      No
        *          Back to Survey();
        * 
        *      Yes
        *          Is it an active member?
        *              No
        *                  show groups - no member pricing
        *              Yes
                           show groups
        * 
        * else 
        * 
        *      we came here from survey...
        *      look up constituent
        *      Is found?
        *      No
        *          create new constituent and log in
        *      Yes
        *          log in
        * 

        * 
    
    */
    
//    $_SESSION['logInMethod'] = '';
//    $_SESSION['loginFirstName'] = '';
//    $_SESSION['loginLastName'] = '';
//    $_SESSION['loginEmail'] = '';
    
    // replace session member number if we are going forward. If we go backward, we will already have it.
//    if (!empty($_REQUEST['memberNumber'])){   //csn 20160623  needed to get account number
//        $_SESSION['memberNumber'] = $_REQUEST['memberNumber'];
//    }
    if (!empty($_REQUEST['acctNbr'])){
        $_SESSION['memberNumber'] = $_REQUEST['acctNbr'];
    }
    
    
    // -------- temporary ----------------
    if (!empty($_REQUEST['memberEmail'])){
        $_SESSION['memberEmail'] = $_REQUEST['memberEmail'];
    }
    //=====================================
    
    if (!empty($_REQUEST['email'])){
        $_SESSION['email'] = $_REQUEST['email'];
    }
    if (!empty($_REQUEST['firstName'])){
        $_SESSION['firstName'] = $_REQUEST['firstName'];
    }
    if (!empty($_REQUEST['lastName'])){
        $_SESSION['lastName'] = $_REQUEST['lastName'];
    }
    
    
    
    echo "<!-- time before getNewSessionKey: " . date('Y-m-d H:i:s' . " -->");
    getNewSessionKeyEx(); // get new session key, because no matter what, we're going to be using it.
    echo "<!-- sessionkey: " . $_SESSION['sessionKey'] . " -->";
    echo "<!-- time after getNewSessionKey: " . date('Y-m-d H:i:s' . " -->");
    
    if (!empty($_SESSION['memberNumber'])){
        //exit('memberNumber: ' . $_SESSION['memberNumber']);
        
         
        
        //$constituentId = getConstituentIdFromEnterpriseId($data); // use 343434 as a test member number.
        $returnArray = array();
        $returnArray = getConstituentIdAndLoginFromEnterpriseId($_SESSION['memberNumber']); // use 343434 as a test member number.
        $constituentId = $returnArray[0];
        $login = $returnArray[1];
        if (!empty($constituentId)) {
            $_SESSION['constituentId'] = $constituentId;
        } else {
            $_SESSION['constituentId'] = "156234";
        }
        //exit('constituentId: ' . $constituentId);
        //$loggedIn = loggedIn();
        //exit("loggedIn: " . $loggedIn);
        //$data['customerNbr'] = $_SESSION['customerNbr'];
        
        // it will either
        //  1) blank (meaning not found)
        //  2) I will get a valid constituent Id.
        if (strlen($constituentId) > 0){
            // now, determine whether the member is active.
            // login.
            //loginConstituent();
            //getConstituentInfo();
            //exit('constituentId: ' . $constituentId);
            
            // TODO: now, we must log in the constituent. currently, we need the constituent id, phone number, and zip code.
            // //////////// NEED TO LOG IN HERE /////////////////////////
            //exit('login: ' . $login);
            if (empty($login)) {
                continueWithNonMemberPricingYesNo("Member number, " . $_SESSION['memberNumber'] . " - does not have a login.<br><br>Would you like to continue with non-member pricing?");
            }
            
            loginUsingEmail($login);
            $_SESSION['email'] = $login;
            
            $_SESSION['logInMethod'] = 'loginUsingEmail';
            $_SESSION['loginEmail'] = $_SESSION['email'];
            $_SESSION['suppressMembershipPricing'] = true;
            
            $loggedIn = loggedIn();
            if ($loggedIn == 'false'){
                $_SESSION['request']->updateApiCommunication("Groups: Unable to validate member " . $_SESSION['memberNumber'] . " Continue with non-member pricing?\n");
                continueWithNonMemberPricingYesNo("Member number, " . $_SESSION['memberNumber'] . " - unable to validate member id.<br><br>Would you like to continue with non-member pricing?");
            } else {
                $activeMember = false; // initialize. active member = false.
                // TODO: new api to determine if it is an active member or not.
                $memberStatus = getConstituentInfoEx();
                $activeMember = $memberStatus;
                //exit("activeMember " . $activeMember);
                
                if ($activeMember === true){
                    $_SESSION['logInMethod'] = 'loginUsingEmail'; //csn
                    $_SESSION['loginEmail'] = $_SESSION['email']; //csn
                    $_SESSION['suppressMembershipPricing'] = false;    // or false - we still need to determine if the membership is active
                    
                } else {
                    $_SESSION['suppressMembershipPricing'] = true; 
                    $_SESSION['request']->updateApiCommunication("Groups: Member not currently active " . $_SESSION['memberNumber'] . " Continue with non-member pricing?\n");
                    continueWithNonMemberPricingYesNo("Member number, " . $_SESSION['memberNumber'] . ", is not currently active.<br><br>Would you like to continue with non-member pricing?");
                }
            }
            
        } else {
            // member number not found - give the option to continue with nonmember pricing.
            $_SESSION['suppressMembershipPricing'] = true;
            $_SESSION['request']->updateApiCommunication("Groups: Member not found in our system " . $_SESSION['memberNumber'] . " Prompting to continue with member pricing?\n");
            continueWithNonMemberPricingYesNo("Member number, " . $_SESSION['memberNumber'] . ", was not found in our system.<br><br>Would you like to continue with non-member pricing?");
        }
        
    } else {
        // if skip, just use kiosk anonymous user.
        // if we have an email, attempt to log in.
        //echo "<pre>";
        //print_r($_REQUEST);
        //echo "</pre>";
        //exit;
        if (!empty($_SESSION['email'])){
            // attempt to log in.
             
            loginUsingEmail($_SESSION['email']);
            $_SESSION['logInMethod'] = 'loginUsingEmail';
            $_SESSION['loginEmail'] = $_SESSION['email'];
            $_SESSION['suppressMembershipPricing'] = true;
            
            $loggedIn = loggedIn();
            //exit('loggedIn: ' . $loggedIn);
            if ($loggedIn == 'false'){
                
                // check request parameters. if we do not have first and last, go to error page.
                // First and last name must be entered. 
                if (empty($_REQUEST['firstName'])){
                    errorPageSales("We need to register you as a new user.<br>You must enter a first name to continue.<br>Please press the back button to correct.");
                } 
                if (empty($_REQUEST['lastName'])){
                    errorPageSales("We need to register you as a new user.<br>You must enter a last name to continue.<br>Please press the back button to correct.");
                } 
                
                // TODO: CREATE ACCOUNT. WAITING FOR MATT B. TO GIVE ME THE API TO USE. DONE
                $_SESSION['loginFirstName'] = $_REQUEST['firstName'];
                $_SESSION['loginLastName'] = $_REQUEST['lastName'];
                $_SESSION['loginEmail'] = $_SESSION['email'];
                $registered = registerWithPromoCodeEx();
                //exit('registered: ' . $registered);
                
                loginUsingEmail($_SESSION['email']);
                $_SESSION['logInMethod'] = 'loginUsingEmail';
                $_SESSION['loginEmail'] = $_SESSION['email'];
                $_SESSION['suppressMembershipPricing'] = true;
                //$loggedIn = loggedIn();
                
            } else {
                $_SESSION['logInMethod'] = 'loginUsingEmail';
                $_SESSION['loginEmail'] = $_SESSION['email'];
            }
        } else {
//            exit("login as anonymous user");
            // log in as kiosk anonymous user.
            loginUsingEmail('kioskAnonymousUser@justarrive.com');
            $_SESSION['logInMethod'] = 'loginUsingEmail';
            $_SESSION['loginEmail'] = 'kioskAnonymousUser@justarrive.com';
            $_SESSION['suppressMembershipPricing'] = true;
            $_SESSION['constituentId'] = '156234';
        }  
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    $loggedIn = loggedIn();
    if ($loggedIn == 'false'){
        // log in as kiosk anyonymous user.
        
        loginUsingEmail('kioskAnonymousUser@justarrive.org');
        $_SESSION['suppressMembershipPricing'] = true;
        //changeModeOfSaleEx($_SESSION['modeOfSale']);
        $loggedIn = loggedIn();
        if ($loggedIn == 'false'){
            errorPageSales("Unable to login with anonymous user. See Visitor's Services");
        } else {
            $_SESSION['logInMethod'] = 'loginUsingEmail';
            $_SESSION['loginEmail'] = $_REQUEST['kioskAnonymousUser@justarrive.org'];
        }
    } 
    
//    exit("mode of sale = " . $_SESSION['modeOfSale']);
    $modeOfSale = changeModeOfSaleEx($_SESSION['modeOfSale']);
//    exit("mos = " . $modeOfSale);
    if ($modeOfSale <> 9){
        $_SESSION['request']->updateApiCommunication("Groups: Change mode of sale is " . print_r($modeOfSale,true));
        errorPageSales("MODE OF SALE IS NOT 9.");
    }
    
    // WE HAVE TO BE LOGGED IN TO GET PAST HERE!!!
    
    
    $_SESSION['loggedInTable'] = getLoggedInTable();
    
    
    
    $_SESSION['performances'] = array();
    $_SESSION['performances'] = unserialize(file_get_contents($_SESSION['salesCacheFile']));

    //exit('salescachefile ' . $_SESSION['salesCacheFile']);
    
    
    // NOW DISPLAY THE GROUPS... WE SHOULD HAVE LOGIN INFO AT THIS TIME.
    // ... 2016-09-12 - OR JUST DISPLAY THE OPTION TO PURCHASE MUSEUM ADMISSION.
    $performanceCount = 0;
    $gaPerformanceCount = 0;
    foreach ($_SESSION['performances'] as $onePerformance){
        if ($onePerformance->mainCategory == 'other'){
                $performanceCount++;
        }
        if ($onePerformance->mainCategory == 'ga'){
                $gaPerformanceCount++;
        }
    }

    
    
    
    
    
    
    
    
    
    $_SESSION['title'] = 'groups';
    
    $_SESSION['javaScript'] = '
        
    
        <script language="javascript">

            function postMyGroupForm(task,mainCategory) {
                document.getElementById("loading").style.visibility = "visible";
                setTimeout(function() {
                    document.myGroupForm.task.value = task;
                    document.myGroupForm.mainCategory.value = mainCategory;
                    document.myGroupForm.submit();
                    return true;
                },250);
            }

        </script>
        <form name="myGroupForm" action="" method="post">
                <input id="task" name="task" type="hidden" value="">
                <input id="mainCategory" name="mainCategory" type="hidden" value="">
         </form>


      ';
    
    
     // if there is only one performance and it is 'ga', go directly to the prices page.
     if ($performanceCount == 0 && $gaPerformanceCount == 1){
        $_REQUEST['mainCategory'] = 'ga';
        $_REQUEST['task'] = 'prices';
        prices('');
         
         
        /* $_SESSION['topTable'] = '
                <center>
                    <table>
                        <tr height="100px">
                            <td colspan=1>&nbsp;</td>
                                <center>
                                    <img id="loading" src="images/Loading-Animation.gif" style="visibility:hidden;">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td width=772 valign=top>
                                <center>
                                <table width=600>
                                    <tr height="150px">
                                        <td id="museumAdmission" valign=middle align=center class="button1" 
                                            onMouseOut="document.getElementById(\'museumAdmission\').style.backgroundImage = \'url(images/redBar.png)\';" 
                                            onclick="postMyGroupForm(\'prices\',\'ga\');"
                                            valign=bottom>
                                            Museum Admission
                                        </td>
                                    </tr>
                                    <tr height="50px"></tr>
                                    <tr height=200px>
                                        <td valign=top class="groupsLabels" style="padding-left: 30px">
                                            Purchase adult, senior, or student <br>
                                            admission for today.  Includes <br>
                                            access to all open exhibitions. 
                                             
                                        </td>
                                    </tr>
                                </table>
                                </center>
                            </td>
                        </tr>
                   </table>
                 </center>


         '; */
         
         
    } else if ($performanceCount > 0 && $gaPerformanceCount == 0){
        $_REQUEST['mainCategory'] = 'other';
        $_REQUEST['task'] = 'performancesWithoutTimes';
        performancesWithoutTimes(''); 
    } else {
         $_SESSION['topTable'] = '
                <center>
                    <table>
                        <tr height="100px">
                            <td colspan=2>&nbsp;</td>
                                <center>
                                    <img id="loading" src="images/Loading-Animation.gif" style="visibility:hidden;">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td width=772 valign=top>
                                <center>
                                <table width=600>
                                    <tr height="150px">
                                        <td id="museumAdmission" valign=middle align=center class="button1" 
                                            onMouseOut="document.getElementById(\'museumAdmission\').style.backgroundImage = \'url(images/redBar.png)\';" 
                                            onclick="postMyGroupForm(\'prices\',\'ga\');"
                                            valign=bottom>
                                            Museum Admission
                                        </td>
                                    </tr>
                                    <tr height="50px"></tr>
                                    <tr height=200px>
                                        <td valign=top class="groupsLabels" style="padding-left: 30px">
                                            Purchase adult, senior, or student <br>
                                            admission for today.  Includes <br>
                                            access to all open exhibitions. 
                                             
                                        </td>
                                    </tr>
                                </table>
                                </center>
                            </td>
                            <td width=772 valign=top>
                                <center>
                                <table width=600>
                                    <tr height="150px">
                                        <td id="programTickets" valign=middle align=center class="button1" 
                                            onMouseOut="document.getElementById(\'programTickets\').style.backgroundImage = \'url(images/redBar.png)\';" 
                                            onclick="document.getElementById(\'programTickets\').style.backgroundImage = \'url(images/redBar_clicked.png)\';postMyGroupForm(\'performancesWithoutTimes\',\'other\');"
                                            valign=bottom>
                                            Program Tickets
                                        </td>
                                    </tr>
                                    <tr height="50px"></tr>
                                    <tr height=200px>
                                        <td valign=top class="groupsLabels" style="padding-left: 30px">
                                            Purchase tickets for today\'s <br>
                                            films, lectures, courses, <br>
                                            concerts, or other public programs.

                                        </td>
                                    </tr>
                                </table>
                                </center>

                            </td>
                        </tr>
                   </table>
                 </center>


         '; 
         
         
         
     }
    
    
    
       
       
       
       
       
       
       
       
      
       
          $_SESSION['navigationTable'] =  '<center>
                <table>
                <tr>
                    <td class="groupsLabels2" colspan=2 height=30px>
                        <center>
                            For youth admission ages 17 and under, please go to the ticket desk.
                        </center>
                    </td>
                </tr>
                </table></center>' ;
                
        


    $_SESSION['request']->updateApiCommunication("Finish displaying groups" . "\n");
    printHtmlStuffSales();
    
    
}


function groupsOld(){
   
//    getNewSessionKey($_SESSION['loginData']);
   
   
    //Calls GetPerformances.php to retreive fresh data from text file
    //getData();
   
    //Retreiving GetPerformances data from text file
    if (!empty($_REQUEST['testmode'])){
        $_SESSION['performances'] = unserialize(file_get_contents('/ECommerceSessions/demo/sessionTest.txt'));
    }else {
        $_SESSION['performances'] = unserialize(file_get_contents('/ECommerceSessions/8888/session.txt'));
   }
   
    //Gets GA Performance to be used to add pricing combo with Star Wars;
    $j = 0;
    $_SESSION['gaPerformance'] = '';
    while(!empty($_SESSION['performances'][$j])){
        if (strpos($_SESSION['performances'][$j]['description'],"Museum Admission 2015") > -1) {
            $_SESSION['gaPerformance'] = $_SESSION['performances'][$j];
        }
        $j++;
    }
   

    if (empty($_SESSION['performances'])){
        errorPageSales("No Performances found.");
    }
    
    // verdana, 70 in gimp. italics and bold.
    
    $content = '
           
        <script>
            function postMyForm(group) {                                                               ' . "\n" . '
                document.myform.group.value = group;                                                  ' . "\n" . '
                document.myform.submit();                                                               ' . "\n" . '
                return true;                                                                            ' . "\n" . '
            }                                                                                           ' . "\n" . '
                

            
        </script> 
        <style>
        
            #upDiv {
                background-color: rgba(204,204,204,0.8);
                position:fixed;
                width:100%;
                height:100%;
                top:0px;
                left:0px;
                z-index:1000;
                visibility:hidden;
            }
            
            .groupTitle {
                font-family:arial;
                font-size:22pt;
                font-weight:600;
            }
        
        </style>
        

        <form name="myform" action="" method="POST">
            <input type="hidden" name="task" value="prices">
            <input type="hidden" name="group" value="">
        </form>

        <br><br><br><br>
        

                <center>
                    <table>
                        <tr height="100px">
                            <td colspan=4>&nbsp;</td>
                        </tr>
                        <tr>
                            <td width=100>&nbsp;</td>
                            <td onclick="document.getElementById("othergroup").src = "images/buyTickets_clicked.png";postMyForm(\'othergroup\');" valign=bottom>
                                <center>
                                <table width=400>
                                    <tr height="50px">
                                        <td>
                                            <center><font class="groupTitle">Other Program</font></center>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td height="200px" valign=top>
                                            Entitles you to blah, blah, blah, blah, blah, blah,
                                            blah, blah, blah, blah, blah, blah. blah, blah, blah,
                                            blah, blah, blah, blah, blah, blah. blah, blah, blah,
                                            blah, blah, blah, blah, blah, blah. blah, blah, blah,
                                            blah, blah, blah, blah, blah, blah. blah, blah, blah,
                                            blah, blah, blah, blah, blah, blah. blah, blah, blah<br><br>
                                            and more blah... blah, blah, blah, blah, blah, blah. blah, blah, blah,
                                        </td>
                                    </tr>
                                </table>
                                </center>
                                <img id="othergroup" src="images/buyTickets.png">
                            </td>' . "\n\n" . '
                            <td onclick="document.getElementById("museumAdm").src = "images/buyTickets_clicked.png";postMyForm(\'othergroup\');" valign=bottom>
                                
                                <center>
                                <table width=400>
                                    <tr height="50px">
                                        <td>
                                            <center><font class="groupTitle">MUSEUM ADMISSION</font></center>
                                        </td>
                                    </tr>
                                    <tr height="200px" valign=top>
                                        <td>
                                            Entitles you to blah, blah, blah, blah, blah, blah,
                                            blah, blah, blah, blah, blah, blah. blah, blah, blah,
                                            blah, blah, blah, blah, blah, blah. blah, blah, blah,
                                            blah, blah, blah, blah, blah, blah. blah, blah, blah,
                                            blah, blah, blah, blah, blah, blah. blah, blah, blah,
                                            blah, blah, blah, blah, blah, blah. blah, blah, blah,

                                        </td>
                                    </tr>
                                </table>
                                </center>



                                <img id="museumAdm" src="images/buyTickets.png">
                            </td>' . "\n\n" . '
                            <td width=100>&nbsp;</td>    
                        </tr>
                   </table>
                 </center>';
        
    printHtmlStuffSales('groups', $content);
   
   
   
    exit;
  
}





/*
function items(){
    $content = '<!-- Content -->
        
    <script>
        var maximum = 10;
        
        function increment(number){
            var display = "qty" + number + "Display";
            var originalNumber = document.getElementById(display).innerHTML;
            newNumber = (originalNumber*1) + 1;
            if (newNumber >= maximum){
                newNumber = maximum;
            }
            update(number, newNumber);
        }
        function decrement(number){
            var display = "qty" + number + "Display";
            var originalNumber = document.getElementById(display).innerHTML;
            newNumber = (originalNumber*1) - 1;
            if (newNumber < 0){
                newNumber = 0;
            }
            update(number, newNumber);
        }
        function update(number, newValue){
            var hiddenInput = "qty" + number;
            var display = "qty" + number + "Display";
            document.getElementById(display).innerHTML = newValue;
            document.getElementById(hiddenInput).value = newValue;
            var minusImage = "minus" + number;
            if (newValue > 0){
                document.getElementById(minusImage).src="minus.png";
            } else {
                document.getElementById(minusImage).src="minus-deactive.png";
            }
            var plusImage = "plus" + number;
            if (newValue >= maximum){
                document.getElementById(plusImage).src="plus-deactive.png";
            } else {
                document.getElementById(plusImage).src="plus.png";
            }
            
            // ----- price update --------------
            var hiddenPrice = "price" + number;
            var hiddenOriginalPrice = "originalPrice" + number;
            var displayPrice = "price" + number + "Display";
            newPrice = roundDecimals(document.getElementById(hiddenInput).value * document.getElementById(hiddenOriginalPrice).value,2);
            document.getElementById(hiddenPrice).value = newPrice;
            document.getElementById(displayPrice).innerHTML = "$" + newPrice;
            

            // ------- total updates --------------
            // totalQtyDisplay
            // totalPriceDisplay
            var totalQty = 0;
            var totalPrice = 0;
            var all = document.getElementsByTagName("*");
            //var names = "";
            for (var i=0, max=all.length; i < max; i++) {
                    tempName = document.all(i).name;
                    if (tempName){
                        if (tempName.length > 2){
                            //names = names + "<br>" + tempName
                            

                            first3 = tempName.substring(0,3);
                            if (first3 == "qty"){
                                totalQty = (totalQty) + (+document.all(i).value);
                                //alert("found one " + document.all(i));
                            }
                            first5 = tempName.substring(0,5);
                            if (first5 == "price"){
                                totalPrice = (totalPrice) + (+document.all(i).value);
                                //alert("found one " + document.all(i));
                            }
                        }
            }
            }
            //document.write(names);
            document.getElementById("totalQtyDisplay").innerHTML = totalQty;
            document.getElementById("totalPriceDisplay").innerHTML = "$" + roundDecimals(totalPrice,2);
            
            document.getElementById("totalQty").value = totalQty;
            document.getElementById("totalPrice").value = roundDecimals(totalPrice,2);
            }


        function roundDecimals(original_number, decimals) {
            var result1 = original_number * Math.pow(10, decimals)
            var result2 = Math.round(result1)
            var result3 = result2 / Math.pow(10, decimals)
            //result3 = Math.abs(result3);
            return pad_with_zeros(result3, decimals)
        }
        function pad_with_zeros(rounded_value, decimal_places) {

            var value_string = rounded_value.toString()         // Convert the number to a string
            var decimal_location = value_string.indexOf(".")    // Locate the decimal point
            if (decimal_location == -1) {                       // Is there a decimal point?
                decimal_part_length = 0                         // If no, then all decimal places will be padded with 0s
                value_string += decimal_places > 0 ? "." : ""   // If decimal_places is greater than zero, tack on a decimal point

            }
            else {
                decimal_part_length = value_string.length - decimal_location - 1;   // If yes, then only the extra decimal places will be padded with 0s
            }
            var pad_total = decimal_places - decimal_part_length                    // Calculate the number of decimal places that need to be padded with 0s

            if (pad_total > 0) {
                // Pad the string with 0s
                for (var counter = 1; counter <= pad_total; counter++)
                    value_string += "0"
                }
            return value_string
        }
       
    </script>
    
            
    <form action="" method="POST">
        <input id="task" name=task type=hidden value="groups">
        <input id="group" name="group" type="hidden" value="'. $_REQUEST['group'] .'">
        <input id="totalQty" name="totalQty" type=hidden value="">
        <input id="totalPrice" name="totalPrice" type=hidden value="">

        <section class="app-content" ng-class="{ "container": breakpoint == "lg", "bleed": (fullscreen || bleed), "full-height": location.path() == "/welcome" }">
            <ng-view class="app ng-scope">
                <section class="row ng-scope row-center sp-in-40px-top" ng-class="{ "row-center sp-in-40px-top": screen.width &gt; 1210, "sp-in-20px-top": screen.width &lt; 768 }">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 overflow-hidden header-offset position-relative text-white sp-in-40px-left sp-in-40px-right sp-in-30px-top sp-in-30px-bottom sp-out-40px-bottom visible-sm visible-md">
                        <div class="background-header"></div>
                        <h1 class="sp-out-4px-bottom ng-binding">Complete Access: Discount</h1>
                    </div>
                    <div class="col-xs-12 col-sm-4 col-md-4 col-lg-3 sp-out-20px-bottom" ng-class="{ "sp-in-0px-left sp-in-0px-right": (screen.width &lt; 768), "sp-in-80px-right": (screen.width &gt; 768 &amp;&amp; screen.width &lt; 1210) }"></div>
                    <div class="col-xs-12 col-sm-8 col-md-7 col-lg-5 text-center sp-in-0px-left sp-out-70px-top" ng-class="{ "sp-in-0px-right": (screen.width &lt; 768), "sp-in-40px-right": (screen.width &gt; 768 &amp;&amp; screen.width &lt; 1210), "sp-out-70px-top": (screen.width &gt; 1210) }">

                        <header class="hidden-xs&quot; ng-scope sp-out-30px-bottom" ng-if="(screen.width &gt; 768)" ng-class="{ "text-left sp-in-80px-right sp-out-30px-bottom": (screen.width &lt; 1210), "sp-out-30px-bottom": (screen.width &gt; 1210) }">
                            <h1 class="ng-binding heading-xl sp-out-6px-bottom" ng-class="{ "heading-arial text-md text-bold sp-out-0px-top": (screen.width &lt; 1210), "heading-xl sp-out-6px-bottom": (screen.width &gt; 1210), "text-white": routeParams.events }">Choose Tickets for<br>' . $_REQUEST['group'] . '</h1>
                            <p class="sp-out-0px-bottom ng-binding" ng-class="{ "text-white": routeParams.events }">Please enter the number of tickets you would like in the quantity box(es) below.</p>
                        </header>

                        <price-types class="ng-scope" max-quantity="999" compact-mode="true" ng-if="(screen.width &gt; 768)">
                            <section ng-if="ticketPrices" class="price-types ng-scope xl" ng-class="breakpoint">

                                <div class="text-lg" ng-show="!dropdownMode || (dropdownMode &amp;&amp; showContent)" ng-class="{ "border-thin shadow-slight border-radius-2px dropdown-content position-absolute background-white text-gray": dropdownMode, "text-lg": !dropdownMode, "spotlight": (screen.width &lt; 768 &amp;&amp; dropdownMode) }">
                                    <table class="table price-types-table sp-out-0px-bottom table-grid" ng-class="{ "table-striped table-borderless": dropdownMode, "table-grid": !dropdownMode }">
                                        <thead>
                                            <tr class="ng-scope" ng-if="!dropdownMode">
                                                <th class="text-uppercase text-apertura text-left ng-binding text-xmd" ng-attr-width="{{ hidePrices ?  "52%" : "42%" }}" ng-class="{ "text-md": (screen.width &lt; 1210), "text-xmd": (screen.width &gt; 1210) }" width="42%">Price Level</th>
                                                <th class="text-uppercase text-apertura ng-binding text-xmd text-center" ng-attr-width="{{ hidePrices ? "48%" : "38%" }}" ng-class="{ "text-md  text-left": (screen.width &lt; 1210), "text-xmd text-center": (screen.width &gt; 1210) }" width="38%">Choose Attendees</th>
                                                <th ng-hide="hidePrices" class="text-uppercase text-apertura text-left ng-binding text-xmd" ng-class="{ "text-md": (screen.width &lt; 1210), "text-xmd": (screen.width &gt; 1210) }" width="20%">Price</th>
                                            </tr>
                                        </thead>
                                        <tfoot>
                                            <tr ng-class="{ "background-gray": dropdownMode }">
                                                <td class="text-bold text-left ng-binding" ng-class="{ "sp-in-14px-top sp-in-14px-bottom": dropdownMode, "text-md": (screen.width &lt; 1210) }">Total</td>
                                                <td class="text-bold text-center sp-in-8px-top" ng-class="{ "sp-in-14px-top sp-in-14px-bottom": dropdownMode }">
                                                    <div id="totalQtyDisplay">0</div>
                                                </td>
                                                <td ng-hide="hidePrices" class="text-bold text-left ng-binding" ng-class="{ "sp-in-14px-top sp-in-14px-bottom text-center": dropdownMode, "text-md": (screen.width &lt; 1210) }">
                                                <div id="totalPriceDisplay">$0.00</div>
                                                </td>
                                            </tr>
                                        </tfoot>
                                        <tbody>
                                            ' . buildQtyRow("Individual Complete Access Discount Adult","1", "35.00")  . '

                                            ' . buildQtyRow("Individual Complete Access Discount Senior","2", "28.00")  . '

                                            ' . buildQtyRow("Individual Complete Access Discount Student","3", "28.00")  . '

                                            ' . buildQtyRow("Individual Complete Access Discount Child","4", "22.00")  . '
                                        </tbody>
                                    </table>
                                </div>
                            </section>
                        </price-types>

                            ' . buildFooter() . '

                    </div>
                    <div class="hidden-xs hidden-sm col-md-1 col-lg-3">&nbsp;</div>
                </section>
            </ng-view>
        </section>
    </form>
    ';
    
//    echo("group1: " . $_SESSION['shoppingCartItem']->group);
    printHtmlStuff('items', $content);
}*/


/*
function buildFooter(){
    $footer = '
        
        <script>
        function postItems(itemName) {                                                                  ' . "\n" . '
            document.items.item.value = itemName;
            document.groups.submit();                                                          ' . "\n" . '
            return true;                                                                        ' . "\n" . '
        }
    </script>

    <form action="" method="POST">
    <input id="task" name=task type=hidden value="payment">
    <input id="item" name="item" type="hidden" value="'. $_REQUEST['items'] .'">
        <footer class="row sp-out-30px-top sp-in-60px-bottom text-right">
            <hr class="hr-sm clearfix block-center visible-xs sp-out-15px-top sp-out-25px-bottom">
            <table>
                <tr>
                    <td>
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-right sp-in-0px-left sp-in-0px-right">
                            <div class="display-inline" ng-class="{ "col-xs-6 sp-in-0px-left sp-in-0px-right": (screen.width &lt; 768), "display-inline": (screen.width &gt; 768) }">
                            <button class="btn btn-lg btn-success btn-block" onclick="postItems(\'payment\');"
                                <span class="sp-in-20px-left pull-left text-apertura text-uppercase ng-binding">Continue</span>
                            </button>
                        </div>
                    </td>
                </tr>
            </table>
        </footer>
    </form>
    ';
    return $footer;
}
*/
function buildQtyRow($title, $number, $price){
    $qtyTable = '
                <input name="qty' . $number . '" type="hidden">
                <input name="price' . $number . '" type="hidden">
                <input name="item' . $number . '" type="hidden" value="' . $title . '">
                <tr class="ng-scope" ng-repeat="price in ticketPrices | orderBy: "-price" ">
                    <td class="text-left" ng-class="{ "sp-in-12px-top sp-in-12px-bottom": dropdownMode }">
                        <div class="ng-binding" ng-class="{ "text-md": (screen.width &lt; 1210) }">' . $title . ' $' . $price . '</div>
                        <div class="text-gold ng-scope ng-binding text-md" ng-if="!dropdownMode" ng-class="{ "text-sm": (screen.width &lt; 1210), "text-md": (screen.width &gt; 1210) }"></div>
                        <!-- end ngIf: !dropdownMode -->
                    </td>
                    <td class="text-center row ng-scope" ng-if="!dropdownMode">



                        <input name="qty' . $number . '" id="qty' . $number . '" type="hidden">
                        <center>
                            <table width=100%>
                                <tr>
                                    <td><img id="minus' . $number . '" src="minus-deactive.png" onclick="decrement(\'' . $number . '\');" width="40"></td>
                                    <td class="qty"><div id="qty' . $number . 'Display">0</div></td>
                                    <td><img id="plus' . $number . '" src="plus.png" onclick="increment(\'' . $number . '\');" width="40"></td>
                                </tr>
                            </table>
                        </center>
                        

                      </td>
                      <td  width="250" id="price" class="text-left ng-binding" ng-class="{ "sp-in-12px-top sp-in-12px-bottom text-center": dropdownMode, "text-md": (screen.width &lt; 1210) }">
                        <div id="price' . $number . 'Display">$0.00</div>
                         <input type="hidden" id="price' . $number . '" name="price' . $number . '" value="0.00" />
                         <input type="hidden" id="originalPrice' . $number . '" name="originalPrice' . $number . '" value="' . $price . '" />
                      </td>
                </tr>
    
    ';
    return $qtyTable;
}

function payment(){
    
    $itemsArray = array($_REQUEST['item1'], $_REQUEST['item2'], $_REQUEST['item3'], $_REQUEST['item4']);
    $qtyArray = array($_REQUEST['qty1'], $_REQUEST['qty2'], $_REQUEST['qty3'], $_REQUEST['qty4']);
    $priceArray = array($_REQUEST['price1'], $_REQUEST['price2'], $_REQUEST['price3'], $_REQUEST['price4']);

    $shoppingCartTable = '<table>
            <tr>
                <td class="shoppingCartHeader">Item</td>
                <td class="shoppingCartHeader" align="center" width="40">Qty</td>
                <td class="shoppingCartHeader" align="center" width="60">Price</td>
            </tr>';

    $rowClass = 'tableRowOdd';

        for ($i = 0; $i <= 4; $i++){
            if($qtyArray[$i] > 0){
                if($rowClass == 'tableRowOdd'){
                    $rowClass = 'tableRowEven';
                } else {
                    $rowClass = 'tableRowOdd';
                }

                $shoppingCartTable .= '                        
                    <tr class=' . $rowClass .'>
                        <td>'. $itemsArray[$i] .'</td>
                        <td align="center">'. $qtyArray[$i] .'</td>
                        <td align="center">'. $priceArray[$i] .'</td>
                    </tr>';
            }
        }
    $shoppingCartTable .= '
            <tr class="shoppingCartTotal">
                <td>Total:</td>
                <td align="center">' . $_REQUEST['totalQty'] .'</td>
                <td align="center">' . $_REQUEST['totalPrice'] .'</td>
            </tr></table>';

    $content = '

        <!-- Script -->

        <script>                                                                                ' . "\n" . '
        function postPaymentData() {                                                            ' . "\n" . '
            if (document.paymentForm.firstname.value.length > 0){                               ' . "\n" . '
                document.paymentForm.task.value="salesprinting";                                ' . "\n" . '
            } else {                                                                            ' . "\n" . '
                return false;                                                                   ' . "\n" . '
            }                                                                                   ' . "\n" . '
            if (document.paymentForm.lastname.value.length > 0){                                ' . "\n" . '
                document.paymentForm.task.value="salesprinting";                                ' . "\n" . '
            } else {                                                                            ' . "\n" . '
                return false;                                                                   ' . "\n" . '
            }                                                                                   ' . "\n" . '
            if (document.paymentForm.email1.value.length > 0){                                  ' . "\n" . '
                document.paymentForm.task.value="salesprinting";                                ' . "\n" . '
            } else {                                                                            ' . "\n" . '
                return false;                                                                   ' . "\n" . '
            }
            if (document.paymentForm.address1.value.length > 0){                                ' . "\n" . '
                document.paymentForm.task.value="salesprinting";                                ' . "\n" . '
            } else {                                                                            ' . "\n" . '
                return false;                                                                   ' . "\n" . '
            }
            if (document.paymentForm.city.value.length > 0){                                    ' . "\n" . '
                document.paymentForm.task.value="salesprinting";                                ' . "\n" . '
            } else {                                                                            ' . "\n" . '
                return false;                                                                   ' . "\n" . '
            }
            if (document.paymentForm.zip.value.length > 0){                                     ' . "\n" . '
                document.paymentForm.task.value="salesprinting";                                ' . "\n" . '
            } else {                                                                            ' . "\n" . '
                return false;                                                                   ' . "\n" . '
            }
            if (document.paymentForm.cc.value.length > 0){                                      ' . "\n" . '
                document.paymentForm.task.value="salesprinting";                                ' . "\n" . '
            } else {                                                                            ' . "\n" . '
                return false;                                                                   ' . "\n" . '
            }if (document.paymentForm.cvv.value.length > 0){                                    ' . "\n" . '
                document.paymentForm.task.value="salesprinting";                                ' . "\n" . '
            } else {                                                                            ' . "\n" . '
                return false;                                                                   ' . "\n" . '
            }                                                                                   ' . "\n" . '
            document.paymentForm.submit();                                                      ' . "\n" . '
            document.paymentForm.reset();                                                       ' . "\n" . '
            return true;                                                                        ' . "\n" . '
        }
        
        function prePopulate() {
            document.paymentForm.firstname.value = "Jim";
            document.paymentForm.lastname.value = "Walcott";
            document.paymentForm.email1.value = "jwalcott@wwlinc.com";
            document.paymentForm.address1.value = "711 North A Street";
            document.paymentForm.city.value = "Fort Smith";
            document.paymentForm.zip.value = "72901";
            document.paymentForm.cc.value = "1234123412341234";
            document.paymentForm.cvv.value = "123";
            return true;
        }
        
            
        </script>


        <!-- Content -->

        <section class="app-content" ng-class="{ "container": breakpoint == "lg", "bleed": (fullscreen || bleed), "full-height": location.path() == "/welcome" }">

            <table>
                <tr>
                    <td>
                        <header class="row">
                            <h1 class="sp-out-30px-top sp-out-35px-bottom" ng-class="{ "sp-out-15px-bottom sp-out-80px-top": breakpoint == "xs", "sp-out-35px-bottom": breakpoint != "xs" }"><span class="ng-scope ng-binding" ng-if="paymentRequired">Payment</span></h1>
                        </header>
                    </td>
                    <td width="80"></td>
                    <td>
                        <button align="center" class="btn btn-lg btn-success btn-block" onclick="onClick=prePopulate();">
                        <span>Pre-Populate Fields</span>
                        </button>
                    </td>
                </tr>
            </table>

            <form name="paymentForm" action="" method="POST"  onSubmit="postPaymentData();">
            <input id="task" name="task" type="hidden" value="salesprinting">
                <div class="col-xs-12 col-sm-9 col-md-9 col-lg-9 sp-in-0px-left sp-in-22px-right" ng-class="{ "sp-in-22px-right": breakpoint != "xs", "sp-in-0px-right": breakpoint == "xs" }">
                    <div class="payment-form sp-out-40px-bottom">
                        <fieldset class="row ng-scope" ng-if="formModules.contact.show">
                            <aside class="col-xs-12 col-md-3 sp-in-52px-top sp-in-20px-left sp-in-20px-right sp-in-0px-bottom sp-in-0px-left text-center" ng-class="{ "sp-in-52px-top sp-in-20px-left sp-in-20px-right sp-in-0px-bottom sp-in-0px-left text-center": breakpoint != "xs", "sp-in-4px-top sp-in-3pr-left sp-in-3pr-right": breakpoint == "xs" }">
                                <img src="site_files/person.png" width="50%">
                                <h2 class="text-lg ng-binding sp-out-0px-top" ng-class="{ "sp-out-0px-top": breakpoint != "xs" }">Contact Information</h2>
                            </aside>
                            <section class="col-xs-12 col-md-9 sp-in-30px-bottom sp-in-20px-top sp-in-20px-right sp-in-20px-left" ng-class="{ "sp-in-20px-top sp-in-20px-right sp-in-20px-left": breakpoint != "xs", "sp-in-3pr-left sp-in-3pr-right sp-in-14px-top": breakpoint == "xs" }">
                                <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 form-group sp-in-0px-left sp-in-0px-right sp-out-0px-bottom row">
                                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 form-group no-padding-left" ng-class="{ "sp-in-0px-right": (screen.width &lt; 768) }">
                                            <label class="ng-binding">First Name</label>
                                            <input id="firstname" name="firstname" class="form-control ng-pristine ng-invalid ng-invalid-required" required="" type="text" placeholder="John" onclick="getTextboxCoordinates(this);jaShowKeyboard(this, \'Keyboard1\')">
                                        </div>
                                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 form-group sp-in-0px-right" ng-class="{ "sp-in-0px-left": (screen.width &lt; 768) }">
                                            <label class="ng-binding">Last Name</label>
                                            <input id="lastname" name="lastname" class="form-control ng-pristine ng-invalid ng-invalid-required" ng-model="$storage.user.lastName" placeholder="Smith" required="" type="text" onclick="getTextboxCoordinates(this);jaShowKeyboard(this, \'Keyboard1\')">
                                        </div>
                                    </div>
                                </div>
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 form-group sp-in-0px-left sp-in-0px-right sp-out-0px-bottom">
                                            <label class="ng-binding">Email Address</label>
                                            <input  id="email1" name="email1" ng-blur="isEmailUnique($storage.user.emailAddress)" class="form-control ng-pristine ng-valid-email ng-invalid ng-invalid-required" placeholder="johnsmith@gmail.com" required="" type="email" onclick="getTextboxCoordinates(this);jaShowKeyboard(this, \'Keyboard1\')">
                                            <p class="help-block text-sm sp-out-0px-bottom ng-binding">(Used for sending your purchase receipt.)</p>
                                            <p ng-show="paymentForm.email.$error.email" class="help-block text-sm sp-out-0px-bottom ng-binding ng-hide">Invalid email format</p>
                                        </div>
                                    </div>
                            </section>
                        </fieldset>
                        <fieldset class="row ng-scope" ng-if="paymentRequired &amp;&amp; formModules.billingAddress.show">
                                <aside class="col-xs-12 col-md-3 sp-in-100px-top sp-in-20px-left sp-in-20px-right sp-in-0px-bottom sp-in-0px-left text-center" ng-class="{ "sp-in-100px-top sp-in-20px-left sp-in-20px-right sp-in-0px-bottom sp-in-0px-left text-center": breakpoint != "xs", "sp-in-4px-top sp-in-3pr-left sp-in-3pr-right": breakpoint == "xs" }">
                                    <img src="site_files/home.png" width="50%">
                                    <h2 class="text-lg ng-binding sp-out-0px-top" ng-class="{ "sp-out-0px-top": breakpoint != "xs" }">Billing Address</h2>
                                </aside>
                                <section class="col-xs-12 col-md-9 sp-in-30px-bottom sp-in-20px-top sp-in-20px-right sp-in-20px-left" ng-class="{ "sp-in-20px-top sp-in-20px-right sp-in-20px-left": breakpoint != "xs", "sp-in-3pr-left sp-in-3pr-right sp-in-14px-top": breakpoint == "xs" }">
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 form-group sp-in-0px-left sp-in-0px-right">
                                            <label class="ng-binding">Address 1</label>
                                            <input id="address1" name="address1" ng-model="$storage.user.address1" class="form-control ng-pristine ng-invalid ng-invalid-required" placeholder="1234 Garden Way" required="" type="text" onclick="getTextboxCoordinates(this);jaShowKeyboard(this, \'Keyboard1\')">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 form-group sp-in-0px-left sp-in-0px-right">
                                            <label class="ng-binding">Address 2</label>
                                            <input ng-model="$storage.user.address2" name="address2" class="form-control ng-pristine ng-valid" placeholder="Suite 1101" type="text" onclick="getTextboxCoordinates(this);jaShowKeyboard(this, \'Keyboard1\')">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 form-group sp-in-0px-left">
                                            <label class="ng-binding">Country</label>
                                            <select class="form-control ng-pristine ng-valid ng-valid-required" ng-model="user.country" name="country" ng-change="getStatesFromCountry(user.country.id)" ng-options="country.name for country in countries | orderBy: "name" " required=""><option class="ng-binding" value="">Select</option><option selected="selected" value="0"> USA</option><option value="1">Afghanistan</option><option value="2">Albania</option><option value="3">Algeria</option><option value="4">Andorra</option><option value="5">Angola</option><option value="6">Antigua and Barbuda</option><option value="7">Argentina</option><option value="8">Armenia</option><option value="9">Aruba</option><option value="10">Australia</option><option value="11">Austria</option><option value="12">Azerbaijan</option><option value="13">Azores</option><option value="14">Bahamas</option><option value="15">Bahrain</option><option value="16">Bangladesh</option><option value="17">Barbados</option><option value="18">Belarus</option><option value="19">Belgium</option><option value="20">Belize</option><option value="21">Benin</option><option value="22">Bermuda</option><option value="23">Bhutan</option><option value="24">Bolivia</option><option value="25">Bosnia and Herzegovina</option><option value="26">Botswana</option><option value="27">Brazil</option><option value="28">British Virgin Islnd</option><option value="29">Brunei Darussalam</option><option value="30">Bulgaria</option><option value="31">Burkina Faso</option><option value="32">Burma</option><option value="33">Burundi</option><option value="34">Cambodia</option><option value="35">Cameroon</option><option value="36">Canada</option><option value="37">Canal Zone</option><option value="38">Canary Islands</option><option value="39">Cape Verde</option><option value="40">Cayman Islands</option><option value="41">Central African Rep</option><option value="42">Chad</option><option value="43">Channel Islands</option><option value="44">Chile</option><option value="45">China</option><option value="46">Colombia</option><option value="47">Comoros</option><option value="48">Confed of Senegambia</option><option value="49">Congo</option><option value="50">Cook Islands</option><option value="51">Costa Rica</option><option value="52">Croatia</option><option value="53">Cuba</option><option value="54">Curacao</option><option value="55">Cyprus</option><option value="56">Czech Republic</option><option value="57">Czechoslovakia</option><option value="58">Dahomey</option><option value="59">Denmark</option><option value="60">Djibouti</option><option value="61">Dm People"s Rp Korea</option><option value="62">Dominica</option><option value="63">Dominican Republic</option><option value="64">East Timor</option><option value="65">Ecuador</option><option value="66">Egypt</option><option value="67">El Salvador</option><option value="68">England</option><option value="69">Equatorial Guinea</option><option value="70">Eritrea</option><option value="71">Estonia</option><option value="72">Ethiopia</option><option value="73">Faeroe Islands</option><option value="74">Falkland Islands</option><option value="75">Faroe Islands</option><option value="76">Fed Rep of Germany</option><option value="77">Federated States of Micronesia</option><option value="78">Fiji</option><option value="79">Finland</option><option value="80">France</option><option value="81">French Guiana</option><option value="82">French Polynesia</option><option value="83">Gabon</option><option value="84">Georgia</option><option value="85">Germany</option><option value="86">Ghana</option><option value="87">Gibraltar</option><option value="88">Gilbert &amp; Ellice Is</option><option value="89">Greece</option><option value="90">Greenland</option><option value="91">Grenada</option><option value="92">Guadeloupe</option><option value="93">Guatemala</option><option value="94">Guinea</option><option value="95">Guinea-Bissau</option><option value="96">Guyana</option><option value="97">Haiti</option><option value="98">Honduras</option><option value="99">Hong Kong</option><option value="100">Hungary</option><option value="101">Iceland</option><option value="102">India</option><option value="103">Indonesia</option><option value="104">Iran</option><option value="105">Iraq</option><option value="106">Ireland</option><option value="107">Isle Of Man</option><option value="108">Israel</option><option value="109">Italy</option><option value="110">Ivory Coast</option><option value="111">Jamaica</option><option value="112">Japan</option><option value="113">Jordan</option><option value="114">Kampuchea</option><option value="115">Kazakhstan</option><option value="116">Kenya</option><option value="117">Kiribati</option><option value="118">Kosovo</option><option value="119">Kuwait</option><option value="120">Kyrgyzstan</option><option value="121">Laos</option><option value="122">Latvia</option><option value="123">Lebanon</option><option value="124">Lesotho</option><option value="125">Liberia</option><option value="126">Libya</option><option value="127">Liechtenstein</option><option value="128">Lithuania</option><option value="129">Luxembourg</option><option value="130">Macau</option><option value="131">Macedonia</option><option value="132">Madagascar</option><option value="133">Malawi</option><option value="134">Malaysia</option><option value="135">Maldives</option><option value="136">Mali</option><option value="137">Malta</option><option value="138">Marshall Islands</option><option value="139">Martinique</option><option value="140">Mauritania</option><option value="141">Mauritius</option><option value="142">Mexico</option><option value="143">Moldova</option><option value="144">Monaco</option><option value="145">Mongolia</option><option value="146">Montenegro</option><option value="147">Montserrat Island</option><option value="148">Morocco</option><option value="149">Mozambique</option><option value="150">Myanmar</option><option value="151">Namibia</option><option value="152">Nauru</option><option value="153">Nepal</option><option value="154">Netherlands</option><option value="155">Netherlands Antilles</option><option value="156">Nevis</option><option value="157">New Caledonia</option><option value="158">New Guinea</option><option value="159">New Hebrides</option><option value="160">New Zealand</option><option value="161">Nicaragua</option><option value="162">Niger</option><option value="163">Nigeria</option><option value="164">Niue</option><option value="165">North Korea</option><option value="166">Northern Ireland, UK</option><option value="167">Norway</option><option value="168">Okinawa</option><option value="169">Oman</option><option value="170">Pacific Islands</option><option value="171">Pakistan</option><option value="172">Palau</option><option value="173">Panama</option><option value="174">Papua New Guinea</option><option value="175">Paraguay</option><option value="176">People"s Dm Rp Yemen</option><option value="177">People"s Rp of China</option><option value="178">Peru</option><option value="179">Philippines</option><option value="180">Pitcairn</option><option value="181">Poland</option><option value="182">Portugal</option><option value="183">Portuguese Guinea</option><option value="184">Qatar</option><option value="185">Republic of Korea</option><option value="186">Republic of Maldives</option><option value="187">Republic of the Congo</option><option value="188">Reunion Islands</option><option value="189">Romania</option><option value="190">Russia</option><option value="191">Rwanda</option><option value="192">Ryukyu Islands</option><option value="193">Saint Lucia</option><option value="194">Samoa</option><option value="195">San Marino</option><option value="196">Sao Tome &amp; Principe</option><option value="197">Saudi Arabia</option><option value="198">Scotland</option><option value="199">Senegal</option><option value="200">Serbia</option><option value="201">Seychelles</option><option value="202">Sierra Leone</option><option value="203">Singapore</option><option value="204">Slovakia</option><option value="205">Slovenia</option><option value="206">Solomon Islands</option><option value="207">Somalia</option><option value="208">South Africa</option><option value="209">South Korea</option><option value="210">South Sudan</option><option value="211">Spain</option><option value="212">Sri Lanka</option><option value="213">St Martin</option><option value="214">St Vincent &amp; Grenad</option><option value="215">St. Kitts and Nevis</option><option value="216">Sudan</option><option value="217">Suriname</option><option value="218">Swaziland</option><option value="219">Sweden</option><option value="220">Switzerland</option><option value="221">Syria</option><option value="222">Tahiti</option><option value="223">Taiwan</option><option value="224">Tajikistan</option><option value="225">Tanzania</option><option value="226">Thailand</option><option value="227">The Bahamas</option><option value="228">The Gambia</option><option value="229">Tibet</option><option value="230">Timor</option><option value="231">Togo</option><option value="232">Tonga</option><option value="233">Trinidad &amp; Tobago</option><option value="234">Tunisia</option><option value="235">Turkey</option><option value="236">Turkmenistan</option><option value="237">Tuvalu</option><option value="238">Uganda</option><option value="239">Ukraine</option><option value="240">United Arab Emirates</option><option value="241">United Kingdom</option><option value="242">Upper Volta</option><option value="243">Uruguay</option><option value="244">Uzbekistan</option><option value="245">Vanuatu</option><option value="246">Vatican City</option><option value="247">Venezuela</option><option value="248">Vietnam</option><option value="249">Wales</option><option value="250">Western Samoa</option><option value="251">Yemen Arab Republic</option><option value="252">Yugoslavia</option><option value="253">Zaire</option><option value="254">Zambia</option><option value="255">Zimbabwe</option></select>
                                        </div>
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 form-group sp-in-0px-right ng-scope" ng-if="states.length">
                                            <label class="ng-binding">State</label>
                                            <select class="form-control ng-pristine ng-invalid ng-invalid-required" ng-model="user.state" name="state" ng-options="state.name for state in states | orderBy: "name" " required=""><option selected="selected" class="ng-binding" value="?">Select</option><option value="0">Alabama</option><option value="1">Alaska</option><option value="2">American Embassy</option><option value="3">American Embassy</option><option value="4">American Samoa</option><option value="5">Arizona</option><option value="6">Arkansas</option><option value="7">Armed Forces</option><option value="8">California</option><option value="9">Colorado</option><option value="10">Connecticut</option><option value="11">D.C.</option><option value="12">Delaware</option><option value="13">Florida</option><option value="14">Georgia</option><option value="15">Guam</option><option value="16">Hawaii</option><option value="17">Idaho</option><option value="18">Illinois</option><option value="19">Indiana</option><option value="20">Iowa</option><option value="21">Kansas</option><option value="22">Kentucky</option><option value="23">Louisiana</option><option value="24">Maine</option><option value="25">Marshall Islands</option><option value="26">Maryland</option><option value="27">Massachusetts</option><option value="28">Michigan</option><option value="29">Micronesia</option><option value="30">Minnesota</option><option value="31">Mississippi</option><option value="32">Missouri</option><option value="33">Montana</option><option value="34">Nebraska</option><option value="35">Nevada</option><option value="36">New Hampshire</option><option value="37">New Jersey</option><option value="38">New Mexico</option><option value="39">New York</option><option value="40">North Carolina</option><option value="41">North Dakota</option><option value="42">Northern Mariana Is.</option><option value="43">Ohio</option><option value="44">Oklahoma</option><option value="45">Oregon</option><option value="46">Palau</option><option value="47">Pennsylvania</option><option value="48">Please Update</option><option value="49">Puerto Rico</option><option value="50">Rhode Island</option><option value="51">South Carolina</option><option value="52">South Dakota</option><option value="53">Tennessee</option><option value="54">Texas</option><option value="55">Trust Territories</option><option value="56">Utah</option><option value="57">Vermont</option><option value="58">Virgin Islands</option><option value="59">Virginia</option><option value="60">Washington</option><option value="61">West Virginia</option><option value="62">Wisconsin</option><option value="63">Wyoming</option></select>
                                        </div>
                                    </div>
                                <div class="row">
                                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 form-group sp-in-0px-left sp-out-0px-bottom">
                                        <label class="ng-binding">City</label>
                                        <input  id="city" name="city" ng-model="$storage.user.city" name="city" class="form-control ng-pristine ng-invalid ng-invalid-required" placeholder="New York" required="" type="text" onclick="getTextboxCoordinates(this);jaShowKeyboard(this, \'Keyboard1\')">
                                    </div>
                                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 form-group sp-in-0px-right sp-out-0px-bottom">
                                        <label class="ng-binding">Postal Code</label>
                                        <input id="zip" name="zip" class="form-control ng-pristine ng-valid ng-valid-required" ng-model="$storage.user.postalCode" name="postalCode" placeholder="10001" ng-keypress="checkForUpsell()" required="" type="text" onclick="getTextboxCoordinates(this);jaShowKeyboard(this, \'NumPad1\')">
                                    </div>
                                </div>
                            </section>
                        </fieldset>

                        <fieldset class="row ng-scope" ng-if="paymentRequired &amp;&amp; formModules.creditCard.show">
                            <aside class="col-xs-12 col-md-3 sp-in-30px-top sp-in-20px-left sp-in-20px-right sp-in-0px-bottom sp-in-0px-left text-center" ng-class="{ "sp-in-30px-top sp-in-20px-left sp-in-20px-right sp-in-0px-bottom sp-in-0px-left text-center": breakpoint != "xs", "sp-in-4px-top sp-in-3pr-left sp-in-3pr-right": breakpoint == "xs" }">
                                <img src="site_files/money.png" width="50%">
                                <h2 class="text-lg ng-binding sp-out-0px-top" ng-class="{ "sp-out-0px-top": breakpoint != "xs" }">Payment Information</h2>
                            </aside>
                            <section class="col-xs-12 col-md-9 sp-in-30px-bottom sp-in-20px-top sp-in-20px-right sp-in-20px-left" ng-class="{ "sp-in-20px-top sp-in-20px-right sp-in-20px-left": breakpoint != "xs", "sp-in-3pr-left sp-in-3pr-right sp-in-14px-top": breakpoint == "xs" }">
                                <div class="row">
                                    <div class="col-xs-12 col-sm-8 col-md-8 col-lg-8 form-group sp-in-0px-left">
                                        <label class="ng-binding">Credit Card Number</label>
                                        <input id="cc" name="cc" class="form-control ng-pristine ng-invalid ng-invalid-required" ng-model="payment.cardNo" placeholder="123412341234" required="" type="text" onclick="getTextboxCoordinates(this);jaShowKeyboard(this, \'NumPad1\')">
                                    </div>
                                    <div class="col-xs-11 col-sm-3 col-md-3 col-lg-3 form-group sp-in-0px-left">
                                        <label class="ng-binding">CVV</label>
                                        <input id="cvv" name="cvv" class="form-control ng-pristine ng-invalid ng-invalid-required" ng-model="payment.cardSecurityCode" placeholder="123" maxlength="4" required="" type="text" onclick="getTextboxCoordinates(this);jaShowKeyboard(this, \'NumPad1\')">
                                    </div>
                                    <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1 form-group sp-in-0px-left sp-out-0px-bottom sp-in-30px-top">
                                        <i class="icon-lock icon-beige-dark icon-xs sp-out-8px-bottom"></i>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-6 col-sm-4 col-md-4 col-lg-4 form-group sp-in-0px-left sp-out-0px-bottom">
                                        <label class="ng-binding">Expiration Date</label>
                                        <select name="cardExpirationMonth" class="form-control ng-pristine ng-invalid ng-invalid-required" ng-model="payment.cardExpiryMonth" ng-options="month.code as month.name for month in months(true)" required=""><option selected="selected" value="1"></option><option value="0">January</option><option value="1">February</option><option value="2">March</option><option value="3">April</option><option value="4">May</option><option value="5">June</option><option value="6">July</option><option value="7">August</option><option value="8">September</option><option value="9">October</option><option value="10">November</option><option value="11">December</option></select>
                                    </div>
                                    <div class="col-xs-6 col-sm-4 col-md-4 col-lg-4 form-group sp-in-0px-left sp-out-0px-bottom">
                                        <label class="hidden-visibility ng-binding">Expiration Year</label>
                                        <select name="cardExperiationYear" class="form-control ng-pristine ng-invalid ng-invalid-required" ng-model="payment.cardExpiryYear" ng-options="code as name for (code, name) in years()" required=""><option selected="selected" class="ng-binding" value="?">Select</option><option value="2014">2014</option><option value="2015">2015</option><option value="2016">2016</option><option value="2017">2017</option><option value="2018">2018</option><option value="2019">2019</option><option value="2020">2020</option><option value="2021">2021</option><option value="2022">2022</option><option value="2023">2023</option><option value="2024">2024</option><option value="2025">2025</option><option value="2026">2026</option><option value="2027">2027</option></select>
                                    </div>
                                </div>
                            </section>
                        </fieldset>

                        <fieldset class="row ng-scope" ng-if="formModules.ccEmails.show">
                            <aside class="col-xs-12 col-md-3 sp-in-52px-top sp-in-20px-left sp-in-20px-right sp-in-0px-bottom sp-in-0px-left text-center" ng-class="{ "sp-in-52px-top sp-in-20px-left sp-in-20px-right sp-in-0px-bottom sp-in-0px-left text-center": breakpoint != "xs", "sp-in-4px-top sp-in-3pr-left sp-in-3pr-right": breakpoint == "xs" }">
                                <img src="site_files/email.png" width="50%">
                                <h2 class="text-lg ng-binding sp-out-0px-top" ng-class="{ "sp-out-0px-top": breakpoint != "xs" }">Email Confirmation</h2>
                            </aside>
                            <section class="col-xs-12 col-md-9 sp-in-30px-bottom sp-in-20px-top sp-in-20px-right sp-in-20px-left" ng-class="{ "sp-in-20px-top sp-in-20px-right sp-in-20px-left": breakpoint != "xs", "sp-in-3pr-left sp-in-3pr-right sp-in-14px-top": breakpoint == "xs" }">
                                <div class="row ng-scope" ng-repeat="ccEmail in $storage.user.ccEmails">
                                    <div class="col-xs-12 col-sm-10 col-md-10 col-lg-10 form-group cc-email sp-in-0px-left sp-in-0px-right sp-out-20px-bottom">
                                        <label class="ng-scope" ng-if="$index == 0">Please enter the additional e-mail addresses that you would like to receive the purchase receipt.</label>
                                        <ng-form class="ng-pristine ng-valid" name="ccEmailForm">
                                            <input name="ccemail" class="form-control display-inline ng-pristine ng-valid ng-valid-email" ng-model="ccEmail.value" placeholder="lisa.mahony@gmail.com" type="email" onclick="getTextboxCoordinates(this);jaShowKeyboard(this, \'Keyboard1\')">
                                            <p ng-show="ccEmailForm.ccemail.$error.email" class="help-block text-sm sp-out-0px-bottom ng-binding ng-hide">Invalid email format</p>
                                        </ng-form>
                                    </div>
                                </div>
                                <div class="row ng-scope" ng-repeat="ccEmail in $storage.user.ccEmails">
                                    <div class="col-xs-12 col-sm-10 col-md-10 col-lg-10 form-group cc-email sp-in-0px-left sp-in-0px-right sp-out-20px-bottom">
                                        <ng-form class="ng-pristine ng-valid" name="ccEmailForm">
                                            <input name="ccemail" class="form-control display-inline ng-pristine ng-valid ng-valid-email" ng-model="ccEmail.value" placeholder="lisa.mahony@gmail.com" type="email" onclick="getTextboxCoordinates(this);jaShowKeyboard(this, \'Keyboard1\')">
                                            <i class="cc-email-icon position-relative icon-circle-plus-icon icon-orange icon-sm sp-out-8px-bottom display-inline ng-scope" ng-if="$index == $storage.user.ccEmails.length - 1" ng-click="addEmailCC()"></i>
                                            <p ng-show="ccEmailForm.ccemail.$error.email" class="help-block text-sm sp-out-0px-bottom ng-binding ng-hide">Invalid email format</p>
                                        </ng-form>
                                    </div>
                                </div>
                            </section>
                        </fieldset>
                    </div>
                </div>
            </form>


            <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3 sp-in-0px-left sp-in-0px-right">
            <center>
                ' . $shoppingCartTable . '
            </center>
            <hr>

                <aside class="cart-sidebar">
                    <article class="background-kiosk full-height ng-scope" ng-if="breakpoint == "xl" ">
                        <h1 class="heading-lg heading-bold sp-out-0px-top sp-out-40px-bottom sp-in-40px-left sp-in-40px-right text-center">
                        <span class="ng-scope" ng-if="paymentRequired">Swipe your card<br>to Check Out</span></h1>
                        <div class="cart-total">
                            <div class="cart-line-item row background-gray sp-in-10px-left sp-in-16px-right">
                                <div class="col-xs-6 text-left text-md ng-binding">Subtotal</div>
                                <div class="col-xs-6 text-right text-md sp-out-6px-top ng-binding">' . $_REQUEST['totalPrice'] .'</div>
                            </div>
                            <div class=" cart-line-item row background-white sp-in-10px-left sp-in-16px-right">
                                <div class="col-xs-6 text-left text-md text-bold ng-binding">Grand Total</div>
                                <div class="col-xs-6 text-right text-lg text-orange text-bold ng-binding">' . $_REQUEST['totalPrice'] .'</div>
                            </div>
                        </div>
                        <br>
                        <br>
                        <div class="faux-border"></div>
                    </article>
                </aside>
                <footer class="sp-out-20px-top sp-out-25px-bottom">
                    <button class="btn btn-lg btn-success btn-block" onclick="postPaymentData();">
                        <span class="sp-in-20px-left pull-left text-apertura text-uppercase ng-binding">COMPLETE MY CHECKOUT</span>
                    </button>
                </footer>
            </div>
        </section>';
    
    printHtmlStuff('payment', $content);
}


function membership(){
    $content = '<!-- Content -->
    <style>
        ::-webkit-scrollbar{display:none;}
    </style>    
    <section class="app-content" ng-class="{ "container": breakpoint == "lg", "bleed": (fullscreen || bleed), "full-height": location.path() == "/welcome" }">

    <ng-view class="app ng-scope">
    <div class="ng-scope sp-out-60px-top" ng-class="{ "sp-out-60px-top": screen.width &gt; 768, "sp-out-10px-top": screen.width &lt; 768 }">
            <div class="flex-cols" ng-class="{ details: (membershipLevels | filter: { active: true }).length }">
                <section class="flex-col sp-out-22px-right membership-card ng-scope" ng-if="!(membershipLevels | filter: { active: true }).length">
                    <h1 class="heading-xxl sp-out-0px-top sp-out-20px-bottom ng-binding">Purchase Membership</h1>
                    <p class="text-md ng-binding">Membership provides great 
                    benefits and privileges - free admission, no waiting in lines and 
                    special access to behind-the-scenes tours - to name a few. Most 
                    importantly, Member"s fees support our world-class research and 
                    education programs.</p>
                </section>
                <section class="flex-col ng-scope sp-out-22px-right" ng-class="{ active: membershipLevel.active, "sp-out-22px-right": !$last, "sp-out-0px-right": $last }" ng-repeat="membershipLevel in membershipLevels" ng-hide="!membershipLevel.active &amp;&amp; (membershipLevels | filter: { active: true }).length" ng-if="(memberships | filter: { levelId: membershipLevel.id, hideOnKiosk: false }).length">
                    <div class="input-hidden-parent background-white shadow-slight membership-card" ng-class="{ "": screen.width &lt; 768 }" ng-show="!membershipLevel.active">
                        <img src="site_files/family.jpg" class="img-fluid">
                        <header class="sp-in-20px-top sp-in-20px-right sp-in-20px-bottom sp-in-20px-left">
                            <h2 class="heading-lg sp-out-20px-bottom ng-binding">2015 Family Membership</h2>
                            <p class="center-block text-md ng-binding">Admission to all museums and parks for two adults, their children up to age 18, and a guest, 
                            plus unlimited access to Rick and Morty attractions and free parking for the duration of your visits.</p>
                        </header>
                        <footer style="position: absolute; bottom: 0px; width: 100%; border-top: 1px dotted rgb(238, 238, 238);" class="sp-in-20px-top sp-in-20px-right sp-in-20px-bottom sp-in-20px-left" ng-style="{ position: "absolute", bottom: 0, width: "100%", "border-top": "1px dotted #eeeeee" }">
                            <button class="btn btn-arrow btn-info btn-lg btn-block " ng-class="{}">
                                <span class="sp-in-20px-left ng-binding">View Options</span>
                                
                            </button>
                        </footer>
                        <input class="input-hidden ng-pristine ng-valid" ng-model="membershipLevel.active" type="checkbox">
                    </div>
                    <div class="memberships">
                        <article class="membership flex-col background-white shadow-slight membership-card ng-scope sp-out-22px-right" ng-class="{ "sp-out-22px-right": !$last, "sp-out-0px-right": $last }" ng-repeat="membership in memberships | filter: { levelId: membershipLevel.id } " ng-click="goTo("/memberships/" + membership.id + "/details")" ng-if="!membership.hideOnKiosk">
                            <header class="sp-in-20px-top sp-in-20px-right sp-in-20px-left">
                                <h3 class="heading-lg sp-out-10px-bottom sp-out-0px-top ng-binding">Family Membership</h3>
                                <h4 class="heading-lg text-orange ng-binding">$140.00</h4>
                            </header>
                            <hr class="hr-sm hr-light hr-dotted">
                            <div class="sp-in-20px-right sp-in-20px-bottom sp-in-20px-left">
                                <p class="benefits small ng-scope ng-binding" ng-repeat="benefit in membership.benefits track by $index">
                                Unlimited general admission and free special exhibition tickets for one or two 
                               adults and up to four children under the age of 18 LEARN MORE</p>
                               <p class="benefits small ng-scope ng-binding" ng-repeat="benefit in membership.benefits track by $index">Four free adult and eight free children"s tickets to IMAX films or seasonal live-animal exhibits</p>
                               <p class="benefits small ng-scope ng-binding" ng-repeat="benefit in membership.benefits track by $index">Unlimited discounts on the Space Show, additional IMAX films or live-animal exhibits for two adults and four children</p>
                               <p class="benefits small ng-scope ng-binding" ng-repeat="benefit in membership.benefits track by $index">A one-year subscription to Rotunda, our Member"s magazine LEARN MORE</p>
                               <p class="benefits small ng-scope ng-binding" ng-repeat="benefit in membership.benefits track by $index">10% discount on Museum Shop purchases</p>
                               <p class="benefits small ng-scope ng-binding" ng-repeat="benefit in membership.benefits track by $index">15% discount on Museum restaurant purchases</p>
                               <p class="benefits small ng-scope ng-binding" ng-repeat="benefit in membership.benefits track by $index">Discounts on selected Museum educational programs</p>
                               <p class="benefits small ng-scope ng-binding" ng-repeat="benefit in membership.benefits track by $index">Invitations to special exhibition previews for your family</p>
                               <p class="benefits small ng-scope ng-binding" ng-repeat="benefit in membership.benefits track by $index">100% tax deductible to the extent allowable by law</p>                               
                            </div>
                            <footer style="position: absolute; bottom: 0px; width: 100%; border-top: 1px dotted rgb(238, 238, 238);" class="sp-in-20px-top sp-in-20px-right sp-in-20px-bottom sp-in-20px-left" ng-style="{ position: "absolute", bottom: 0, width: "100%", "border-top": "1px dotted #eeeeee" }">
                                <button class="btn btn-arrow btn-info btn-lg btn-block " ng-class="{}">
                                    <span class="sp-in-20px-left ng-binding">Join</span>
                                    
                                </button>
                            </footer>
                        </article>
                        <article class="membership flex-col background-white shadow-slight membership-card ng-scope sp-out-22px-right" ng-class="{ "sp-out-22px-right": !$last, "sp-out-0px-right": $last }" ng-repeat="membership in memberships | filter: { levelId: membershipLevel.id } " ng-click="goTo("/memberships/" + membership.id + "/details")" ng-if="!membership.hideOnKiosk">
                            <header class="sp-in-20px-top sp-in-20px-right sp-in-20px-left">
                                <h3 class="heading-lg sp-out-10px-bottom sp-out-0px-top ng-binding">Family Adventurer</h3>
                                <h4 class="heading-lg text-orange ng-binding">$250.00</h4>
                            </header>
                            <hr class="hr-sm hr-light hr-dotted">
                            <div class="sp-in-20px-right sp-in-20px-bottom sp-in-20px-left">
                                <p class="benefits small ng-scope ng-binding" ng-repeat="benefit in membership.benefits track by $index">All that Family Membership has to offer PLUS:</p>
                                <p class="benefits small ng-scope ng-binding" ng-repeat="benefit in membership.benefits track by $index">All benefits for four additional guests or family members when accompanied by Member (total of 10 people)</p>
                                <p class="benefits small ng-scope ng-binding" ng-repeat="benefit in membership.benefits track by $index">Monthly Calendar Highlights email, featuring exclusive upcoming programs</p>
                                <p class="benefits small ng-scope ng-binding" ng-repeat="benefit in membership.benefits track by $index">Invitation for 6 to the Holiday Party for Young Members</p>
                                <p class="benefits small ng-scope ng-binding" ng-repeat="benefit in membership.benefits track by $index">Invitation for 6 to the Member Open House</p>
                                <p class="benefits small ng-scope ng-binding" ng-repeat="benefit in membership.benefits track by $index">Invitation for 6 to the Member Breakfast</p>
                                <p class="benefits small ng-scope ng-binding" ng-repeat="benefit in membership.benefits track by $index">Membership cards for up to 4 children or caretaker</p>                                
                            </div>
                            <footer style="position: absolute; bottom: 0px; width: 100%; border-top: 1px dotted rgb(238, 238, 238);" class="sp-in-20px-top sp-in-20px-right sp-in-20px-bottom sp-in-20px-left" ng-style="{ position: "absolute", bottom: 0, width: "100%", "border-top": "1px dotted #eeeeee" }">
                                <button class="btn btn-arrow btn-info btn-lg btn-block " ng-class="{}">
                                    <span class="sp-in-20px-left ng-binding">Join</span>
                                    
                                </button>
                            </footer>
                        </article>
                        <article class="membership flex-col background-white shadow-slight membership-card ng-scope sp-out-22px-right" ng-class="{ "sp-out-22px-right": !$last, "sp-out-0px-right": $last }" ng-repeat="membership in memberships | filter: { levelId: membershipLevel.id } " ng-click="goTo("/memberships/" + membership.id + "/details")" ng-if="!membership.hideOnKiosk">
                            <header class="sp-in-20px-top sp-in-20px-right sp-in-20px-left">
                                <h3 class="heading-lg sp-out-10px-bottom sp-out-0px-top ng-binding">Family Voyager</h3>
                                <h4 class="heading-lg text-orange ng-binding">$600.00</h4>
                            </header>
                            <hr class="hr-sm hr-light hr-dotted">
                            <div class="sp-in-20px-right sp-in-20px-bottom sp-in-20px-left">
                                <p class="benefits small ng-scope ng-binding" ng-repeat="benefit in membership.benefits track by $index">All that Adventurer Family Membership has to offer PLUS:</p>
                                <p class="benefits small ng-scope ng-binding" ng-repeat="benefit in membership.benefits track by $index">Concierge service, express ticketing, free coat check and a dedicated phone line in the Member Lounge</p>
                                <p class="benefits small ng-scope ng-binding" ng-repeat="benefit in membership.benefits track by $index">Unlimited complimentary tickets to IMAX films or live-animal exhibits for 8</p>
                                <p class="benefits small ng-scope ng-binding" ng-repeat="benefit in membership.benefits track by $index">Invitation to Star Party in the Rose Center</p>
                                <p class="benefits small ng-scope ng-binding" ng-repeat="benefit in membership.benefits track by $index">Early registration for Adventures in Science</p>
                                <p class="benefits small ng-scope ng-binding" ng-repeat="benefit in membership.benefits track by $index">Weekend morning tours of special exhibition by Museum guide with child"s take-home activity</p>
                                <p class="benefits small ng-scope ng-binding" ng-repeat="benefit in membership.benefits track by $index">Educators guides for families LEARN MORE</p>
                            </div>
                            <footer style="position: absolute; bottom: 0px; width: 100%; border-top: 1px dotted rgb(238, 238, 238);" class="sp-in-20px-top sp-in-20px-right sp-in-20px-bottom sp-in-20px-left" ng-style="{ position: "absolute", bottom: 0, width: "100%", "border-top": "1px dotted #eeeeee" }">
                                <button class="btn btn-arrow btn-info btn-lg btn-block " ng-class="{}">
                                    <span class="sp-in-20px-left ng-binding">Join</span>
                                    
                                </button>
                            </footer>
                        </article>
                        <article class="membership flex-col background-white shadow-slight membership-card ng-scope sp-out-22px-right" ng-class="{ "sp-out-22px-right": !$last, "sp-out-0px-right": $last }" ng-repeat="membership in memberships | filter: { levelId: membershipLevel.id } " ng-click="goTo("/memberships/" + membership.id + "/details")" ng-if="!membership.hideOnKiosk">
                            <header class="sp-in-20px-top sp-in-20px-right sp-in-20px-left">
                                <h3 class="heading-lg sp-out-10px-bottom sp-out-0px-top ng-binding">Family Insider</h3>
                                <h4 class="heading-lg text-orange ng-binding">$1,200.00</h4>
                            </header>
                            <hr class="hr-sm hr-light hr-dotted">
                            <div class="sp-in-20px-right sp-in-20px-bottom sp-in-20px-left">
                                <p class="benefits small ng-scope ng-binding" ng-repeat="benefit in membership.benefits track by $index">All that Voyager Family Membership has to offer PLUS:</p>
                                <p class="benefits small ng-scope ng-binding" ng-repeat="benefit in membership.benefits track by $index">Family-friendly after-hours tours of special exhibition with Museum educators</p>
                                <p class="benefits small ng-scope ng-binding" ng-repeat="benefit in membership.benefits track by $index">6 free tickets to family-friendly "Behind the Scenes" tours of one of the Museum"s science divisions</p>
                                <p class="benefits small ng-scope ng-binding" ng-repeat="benefit in membership.benefits track by $index">10 free tickets to the Space Show</p>
                            </div>
                            <footer style="position: absolute; bottom: 0px; width: 100%; border-top: 1px dotted rgb(238, 238, 238);" class="sp-in-20px-top sp-in-20px-right sp-in-20px-bottom sp-in-20px-left" ng-style="{ position: "absolute", bottom: 0, width: "100%", "border-top": "1px dotted #eeeeee" }">
                                <button class="btn btn-arrow btn-info btn-lg btn-block " ng-class="{}">
                                    <span class="sp-in-20px-left ng-binding">Join</span>
                                    
                                </button>
                            </footer>
                        </article>
                        <article class="membership flex-col background-white shadow-slight membership-card ng-scope sp-out-22px-right" ng-class="{ "sp-out-22px-right": !$last, "sp-out-0px-right": $last }" ng-repeat="membership in memberships | filter: { levelId: membershipLevel.id } " ng-click="goTo("/memberships/" + membership.id + "/details")" ng-if="!membership.hideOnKiosk">
                            <header class="sp-in-20px-top sp-in-20px-right sp-in-20px-left">
                                <h3 class="heading-lg sp-out-10px-bottom sp-out-0px-top ng-binding">Family Scientist</h3>
                                <h4 class="heading-lg text-orange ng-binding">$5,000.00</h4>
                            </header>
                            <hr class="hr-sm hr-light hr-dotted">
                            <div class="sp-in-20px-right sp-in-20px-bottom sp-in-20px-left">
                                <p class="benefits small ng-scope ng-binding" ng-repeat="benefit in membership.benefits track by $index">This
                                is a premier level of Family Membership that features a personalized 
                               introduction to the Museum"s extensive collections and valuable 
                               opportunities for an enhanced science education. Family Scientist 
                               members also enjoy the exclusive privileges of the Patron Circle 
                               Collector level in recognition of their philanthropic commitment to the 
                               Museum. For more information, please call (212) 769-5153.</p>
                            </div>
                            <footer style="position: absolute; bottom: 0px; width: 100%; border-top: 1px dotted rgb(238, 238, 238);" class="sp-in-20px-top sp-in-20px-right sp-in-20px-bottom sp-in-20px-left" ng-style="{ position: "absolute", bottom: 0, width: "100%", "border-top": "1px dotted #eeeeee" }">
                                <button class="btn btn-arrow btn-info btn-lg btn-block " ng-class="{}">
                                    <span class="sp-in-20px-left ng-binding">Join</span>
                                    
                                </button>
                            </footer>
                        </article><article class="membership flex-col background-white shadow-slight membership-card ng-scope sp-out-0px-right" ng-class="{ "sp-out-22px-right": !$last, "sp-out-0px-right": $last }" ng-repeat="membership in memberships | filter: { levelId: membershipLevel.id } " ng-click="goTo("/memberships/" + membership.id + "/details")" ng-if="!membership.hideOnKiosk">
                            <header class="sp-in-20px-top sp-in-20px-right sp-in-20px-left">
                                <h3 class="heading-lg sp-out-10px-bottom sp-out-0px-top ng-binding">Family Extra</h3>
                                <h4 class="heading-lg text-orange ng-binding">$0.00</h4>
                            </header>
                            <hr class="hr-sm hr-light hr-dotted">
                            <div class="sp-in-20px-right sp-in-20px-bottom sp-in-20px-left">
                                <p class="benefits small ng-scope ng-binding" ng-repeat="benefit in membership.benefits track by $index">Employee</p>
                            </div>
                            <footer style="position: absolute; bottom: 0px; width: 100%; border-top: 1px dotted rgb(238, 238, 238);" class="sp-in-20px-top sp-in-20px-right sp-in-20px-bottom sp-in-20px-left" ng-style="{ position: "absolute", bottom: 0, width: "100%", "border-top": "1px dotted #eeeeee" }">
                                <button class="btn btn-arrow btn-info btn-lg btn-block " ng-class="{}">
                                    <span class="sp-in-20px-left ng-binding">Join</span>
                                    
                                </button>
                            </footer>
                        </article>
                    </div>
                </section>                
                <section class="flex-col ng-scope sp-out-22px-right" ng-class="{ active: membershipLevel.active, "sp-out-22px-right": !$last, "sp-out-0px-right": $last }" ng-repeat="membershipLevel in membershipLevels" ng-hide="!membershipLevel.active &amp;&amp; (membershipLevels | filter: { active: true }).length" ng-if="(memberships | filter: { levelId: membershipLevel.id, hideOnKiosk: false }).length">
                    <div class="input-hidden-parent background-white shadow-slight membership-card" ng-class="{ "": screen.width &lt; 768 }" ng-show="!membershipLevel.active">
                        <img src="site_files/phone.jpg" class="img-fluid">
                        <header class="sp-in-20px-top sp-in-20px-right sp-in-20px-bottom sp-in-20px-left">
                            <h2 class="heading-lg sp-out-20px-bottom ng-binding">2015 Mobile Membership</h2>
                            <p class="center-block text-md ng-binding">Join our mobile text club to be up to date with all current events, promotions, and discounts.</p>
                        </header>
                        <footer style="position: absolute; bottom: 0px; width: 100%; border-top: 1px dotted rgb(238, 238, 238);" class="sp-in-20px-top sp-in-20px-right sp-in-20px-bottom sp-in-20px-left" ng-style="{ position: "absolute", bottom: 0, width: "100%", "border-top": "1px dotted #eeeeee" }">
                            <button class="btn btn-arrow btn-info btn-lg btn-block " ng-class="{}">
                                <span class="sp-in-20px-left ng-binding">View Options</span>
                                
                            </button>
                        </footer>
                        <input class="input-hidden ng-pristine ng-valid" ng-model="membershipLevel.active" type="checkbox">
                    </div>
                    <div class="memberships">
                        <article class="membership flex-col background-white shadow-slight membership-card ng-scope sp-out-22px-right" ng-class="{ "sp-out-22px-right": !$last, "sp-out-0px-right": $last }" ng-repeat="membership in memberships | filter: { levelId: membershipLevel.id } " ng-click="goTo("/memberships/" + membership.id + "/details")" ng-if="!membership.hideOnKiosk">
                            <header class="sp-in-20px-top sp-in-20px-right sp-in-20px-left">
                                <h3 class="heading-lg sp-out-10px-bottom sp-out-0px-top ng-binding">Digital Member</h3>
                                <h4 class="heading-lg text-orange ng-binding">$75.00</h4>
                            </header>
                            <hr class="hr-sm hr-light hr-dotted">
                            <div class="sp-in-20px-right sp-in-20px-bottom sp-in-20px-left">
                                <p class="benefits small ng-scope ng-binding" ng-repeat="benefit in membership.benefits track by $index">Discounts on additional tickets</p>
                                <p class="benefits small ng-scope ng-binding" ng-repeat="benefit in membership.benefits track by $index">2 complimentary special exhibition tickets</p>
                                <p class="benefits small ng-scope ng-binding" ng-repeat="benefit in membership.benefits track by $index">Unlimited complimentary general admission for Member and 1 guest</p>
                                <p class="benefits small ng-scope ng-binding" ng-repeat="benefit in membership.benefits track by $index">Discounts in the Museum"s online store</p>
                                <p class="benefits small ng-scope ng-binding" ng-repeat="benefit in membership.benefits track by $index">Monthly Museum update via email and targeted content from AMNH website</p>                                
                            </div>
                            <footer style="position: absolute; bottom: 0px; width: 100%; border-top: 1px dotted rgb(238, 238, 238);" class="sp-in-20px-top sp-in-20px-right sp-in-20px-bottom sp-in-20px-left" ng-style="{ position: "absolute", bottom: 0, width: "100%", "border-top": "1px dotted #eeeeee" }">
                                <button class="btn btn-arrow btn-info btn-lg btn-block " ng-class="{}">
                                    <span class="sp-in-20px-left ng-binding">Join</span>
                                    
                                </button>
                            </footer>
                        </article>
                        <article class="membership flex-col background-white shadow-slight membership-card ng-scope sp-out-0px-right" ng-class="{ "sp-out-22px-right": !$last, "sp-out-0px-right": $last }" ng-repeat="membership in memberships | filter: { levelId: membershipLevel.id } " ng-click="goTo("/memberships/" + membership.id + "/details")" ng-if="!membership.hideOnKiosk">
                            <header class="sp-in-20px-top sp-in-20px-right sp-in-20px-left">
                                <h3 class="heading-lg sp-out-10px-bottom sp-out-0px-top ng-binding">Digital Adventurer</h3>
                                <h4 class="heading-lg text-orange ng-binding">$150.00</h4>
                            </header>
                            <hr class="hr-sm hr-light hr-dotted">
                            <div class="sp-in-20px-right sp-in-20px-bottom sp-in-20px-left">
                                <p class="benefits small ng-scope ng-binding" ng-repeat="benefit in membership.benefits track by $index">All that Digital has to offer PLUS:</p>
                                <p class="benefits small ng-scope ng-binding" ng-repeat="benefit in membership.benefits track by $index">4 complimentary special exhibition tickets</p>
                                <p class="benefits small ng-scope ng-binding" ng-repeat="benefit in membership.benefits track by $index">Unlimited complimentary general admission for Member and 3 guests</p>
                                <p class="benefits small ng-scope ng-binding" ng-repeat="benefit in membership.benefits track by $index">Discounts in the Museum"s online store</p>
                                <p class="benefits small ng-scope ng-binding" ng-repeat="benefit in membership.benefits track by $index">Monthly Museum update from our scientists via email and targeted content from AMNH website</p>
                            </div>
                            <footer style="position: absolute; bottom: 0px; width: 100%; border-top: 1px dotted rgb(238, 238, 238);" class="sp-in-20px-top sp-in-20px-right sp-in-20px-bottom sp-in-20px-left" ng-style="{ position: "absolute", bottom: 0, width: "100%", "border-top": "1px dotted #eeeeee" }">
                                <button class="btn btn-arrow btn-info btn-lg btn-block " ng-class="{}">
                                    <span class="sp-in-20px-left ng-binding">Join</span>
                                    
                                </button>
                            </footer>
                        </article>
                    </div>
                </section>
                <section class="flex-col ng-scope sp-out-22px-right" ng-class="{ active: membershipLevel.active, "sp-out-22px-right": !$last, "sp-out-0px-right": $last }" ng-repeat="membershipLevel in membershipLevels" ng-hide="!membershipLevel.active &amp;&amp; (membershipLevels | filter: { active: true }).length" ng-if="(memberships | filter: { levelId: membershipLevel.id, hideOnKiosk: false }).length">
                    <div class="input-hidden-parent background-white shadow-slight membership-card" ng-class="{ "": screen.width &lt; 768 }" ng-show="!membershipLevel.active">
                        <img src="site_files/youth.jpg" class="img-fluid">
                        <header class="sp-in-20px-top sp-in-20px-right sp-in-20px-bottom sp-in-20px-left">
                            <h2 class="heading-lg sp-out-20px-bottom ng-binding">2015 Youth<br>Membership</h2>
                            <p class="center-block text-md ng-binding">Our Youth Membership offers special benefits for young men and women between the ages of 13 and 19.</p>
                        </header>
                        <footer style="position: absolute; bottom: 0px; width: 100%; border-top: 1px dotted rgb(238, 238, 238);" class="sp-in-20px-top sp-in-20px-right sp-in-20px-bottom sp-in-20px-left" ng-style="{ position: "absolute", bottom: 0, width: "100%", "border-top": "1px dotted #eeeeee" }">
                            <button class="btn btn-arrow btn-info btn-lg btn-block " ng-class="{}">
                                <span class="sp-in-20px-left ng-binding">View Options</span>
                                
                            </button>
                        </footer>
                        <input class="input-hidden ng-pristine ng-valid" ng-model="membershipLevel.active" type="checkbox">
                    </div>
                    <div class="memberships">
                        <article class="membership flex-col background-white shadow-slight membership-card ng-scope sp-out-22px-right" ng-class="{ "sp-out-22px-right": !$last, "sp-out-0px-right": $last }" ng-repeat="membership in memberships | filter: { levelId: membershipLevel.id } " ng-click="goTo("/memberships/" + membership.id + "/details")" ng-if="!membership.hideOnKiosk">
                            <header class="sp-in-20px-top sp-in-20px-right sp-in-20px-left">
                                <h3 class="heading-lg sp-out-10px-bottom sp-out-0px-top ng-binding">JC Friend Individual</h3>
                                <h4 class="heading-lg text-orange ng-binding">$112.00</h4>
                            </header>
                            <hr class="hr-sm hr-light hr-dotted">
                            <div class="sp-in-20px-right sp-in-20px-bottom sp-in-20px-left">
                                <p class="benefits small ng-scope ng-binding" ng-repeat="benefit in membership.benefits track by $index">Invitation and free admission to all Junior Council events LEARN MORE</p>
                                <p class="benefits small ng-scope ng-binding" ng-repeat="benefit in membership.benefits track by $index">Invitations to special exhibition previews and member-only events for you and a guest</p>
                                <p class="benefits small ng-scope ng-binding" ng-repeat="benefit in membership.benefits track by $index">Unlimited complimentary general admission and special exhibition tickets for two</p>
                                <p class="benefits small ng-scope ng-binding" ng-repeat="benefit in membership.benefits track by $index">Four complimentary tickets to IMAX or 3D films or for seasonal live-animal exhibits</p>
                                <p class="benefits small ng-scope ng-binding" ng-repeat="benefit in membership.benefits track by $index">Discounts on Space Shows, additional IMAX or 3D films, or live animal exhibits for two</p>
                                <p class="benefits small ng-scope ng-binding" ng-repeat="benefit in membership.benefits track by $index">One-year subscription to Rotunda, our Member"s magazine LEARN MORE</p>
                                <p class="benefits small ng-scope ng-binding" ng-repeat="benefit in membership.benefits track by $index">10% discount on Museum Shop purchases</p>
                                <p class="benefits small ng-scope ng-binding" ng-repeat="benefit in membership.benefits track by $index">15% discount on Museum restaurant purchases</p>
                                <p class="benefits small ng-scope ng-binding" ng-repeat="benefit in membership.benefits track by $index">Opportunities to participate in special tours for members only</p>
                                <p class="benefits small ng-scope ng-binding" ng-repeat="benefit in membership.benefits track by $index">$100 of this membership is tax deductible</p>
                                
                            </div>
                            <footer style="position: absolute; bottom: 0px; width: 100%; border-top: 1px dotted rgb(238, 238, 238);" class="sp-in-20px-top sp-in-20px-right sp-in-20px-bottom sp-in-20px-left" ng-style="{ position: "absolute", bottom: 0, width: "100%", "border-top": "1px dotted #eeeeee" }">
                                <button class="btn btn-arrow btn-info btn-lg btn-block " ng-class="{}">
                                    <span class="sp-in-20px-left ng-binding">Join</span>
                                    
                                </button>
                            </footer>
                        </article>
                        <article class="membership flex-col background-white shadow-slight membership-card ng-scope sp-out-22px-right" ng-class="{ "sp-out-22px-right": !$last, "sp-out-0px-right": $last }" ng-repeat="membership in memberships | filter: { levelId: membershipLevel.id } " ng-click="goTo("/memberships/" + membership.id + "/details")" ng-if="!membership.hideOnKiosk">
                            <header class="sp-in-20px-top sp-in-20px-right sp-in-20px-left">
                                <h3 class="heading-lg sp-out-10px-bottom sp-out-0px-top ng-binding">JC Friend Dual</h3>
                                <h4 class="heading-lg text-orange ng-binding">$105.00</h4>
                            </header>
                            <hr class="hr-sm hr-light hr-dotted">
                            <div class="sp-in-20px-right sp-in-20px-bottom sp-in-20px-left">
                                <p class="benefits small ng-scope ng-binding" ng-repeat="benefit in membership.benefits track by $index">All that FRIEND Junior Council Individual Membership has to offer, for two people</p>                                
                            </div>
                            <footer style="position: absolute; bottom: 0px; width: 100%; border-top: 1px dotted rgb(238, 238, 238);" class="sp-in-20px-top sp-in-20px-right sp-in-20px-bottom sp-in-20px-left" ng-style="{ position: "absolute", bottom: 0, width: "100%", "border-top": "1px dotted #eeeeee" }">
                                <button class="btn btn-arrow btn-info btn-lg btn-block " ng-class="{}">
                                    <span class="sp-in-20px-left ng-binding">Join</span>
                                    
                                </button>
                            </footer>
                        </article>
                        <article class="membership flex-col background-white shadow-slight membership-card ng-scope sp-out-22px-right" ng-class="{ "sp-out-22px-right": !$last, "sp-out-0px-right": $last }" ng-repeat="membership in memberships | filter: { levelId: membershipLevel.id } " ng-click="goTo("/memberships/" + membership.id + "/details")" ng-if="!membership.hideOnKiosk">
                            <header class="sp-in-20px-top sp-in-20px-right sp-in-20px-left">
                                <h3 class="heading-lg sp-out-10px-bottom sp-out-0px-top ng-binding">JC Partner Individual</h3>
                                <h4 class="heading-lg text-orange ng-binding">$105.00</h4>
                            </header>
                            <hr class="hr-sm hr-light hr-dotted">
                            <div class="sp-in-20px-right sp-in-20px-bottom sp-in-20px-left">
                                <p class="benefits small ng-scope ng-binding" ng-repeat="benefit in membership.benefits track by $index">All that benefits of a FRIEND Junior Council Individual Membership PLUS:</p>
                                <p class="benefits small ng-scope ng-binding" ng-repeat="benefit in membership.benefits track by $index">Opportunity
                                to join the Junior Council Steering Committee (Any Member interested in
                                the Junior Council Steering Committee must send a list of 10 personal 
                               contact names, mailing addresses, and e-mail addresses of people who may
                                be interested in joining to jcmembership@amnh.org)</p><p class="benefits small ng-scope ng-binding" ng-repeat="benefit in membership.benefits track by $index">Opportunity to take a guided tour for two of the Museum LEARN MORE</p>
                                <p class="benefits small ng-scope ng-binding" ng-repeat="benefit in membership.benefits track by $index">$125 of this membership is tax deductible</p>
                            </div>
                            <footer style="position: absolute; bottom: 0px; width: 100%; border-top: 1px dotted rgb(238, 238, 238);" class="sp-in-20px-top sp-in-20px-right sp-in-20px-bottom sp-in-20px-left" ng-style="{ position: "absolute", bottom: 0, width: "100%", "border-top": "1px dotted #eeeeee" }">
                                <button class="btn btn-arrow btn-info btn-lg btn-block " ng-class="{}">
                                    <span class="sp-in-20px-left ng-binding">Join</span>
                                    
                                </button>
                            </footer>
                        </article>
                        <article class="membership flex-col background-white shadow-slight membership-card ng-scope sp-out-22px-right" ng-class="{ "sp-out-22px-right": !$last, "sp-out-0px-right": $last }" ng-repeat="membership in memberships | filter: { levelId: membershipLevel.id } " ng-click="goTo("/memberships/" + membership.id + "/details")" ng-if="!membership.hideOnKiosk">
                            <header class="sp-in-20px-top sp-in-20px-right sp-in-20px-left">
                                <h3 class="heading-lg sp-out-10px-bottom sp-out-0px-top ng-binding">JC Partner Dual</h3>
                                <h4 class="heading-lg text-orange ng-binding">$105.00</h4>
                            </header>
                            <hr class="hr-sm hr-light hr-dotted">
                            <div class="sp-in-20px-right sp-in-20px-bottom sp-in-20px-left">
                                <p class="benefits small ng-scope ng-binding" ng-repeat="benefit in membership.benefits track by $index">All that PARTNER Junior Council Individual Membership has to offer, for two people</p>
                            </div>
                            <footer style="position: absolute; bottom: 0px; width: 100%; border-top: 1px dotted rgb(238, 238, 238);" class="sp-in-20px-top sp-in-20px-right sp-in-20px-bottom sp-in-20px-left" ng-style="{ position: "absolute", bottom: 0, width: "100%", "border-top": "1px dotted #eeeeee" }">
                                <button class="btn btn-arrow btn-info btn-lg btn-block " ng-class="{}">
                                    <span class="sp-in-20px-left ng-binding">Join</span>
                                    
                                </button>
                            </footer>
                        </article>
                        <article class="membership flex-col background-white shadow-slight membership-card ng-scope sp-out-22px-right" ng-class="{ "sp-out-22px-right": !$last, "sp-out-0px-right": $last }" ng-repeat="membership in memberships | filter: { levelId: membershipLevel.id } " ng-click="goTo("/memberships/" + membership.id + "/details")" ng-if="!membership.hideOnKiosk">
                            <header class="sp-in-20px-top sp-in-20px-right sp-in-20px-left">
                                <h3 class="heading-lg sp-out-10px-bottom sp-out-0px-top ng-binding">JC Patron</h3>
                                <h4 class="heading-lg text-orange ng-binding">$75.00</h4>
                            </header>
                            <hr class="hr-sm hr-light hr-dotted">
                            <div class="sp-in-20px-right sp-in-20px-bottom sp-in-20px-left">
                                <p class="benefits small ng-scope ng-binding" ng-repeat="benefit in membership.benefits track by $index">All the benefits of a Junior Council PARTNER membership PLUS:</p>
                                <p class="benefits small ng-scope ng-binding" ng-repeat="benefit in membership.benefits track by $index">Invitation to all Junior Council events for you and two guests</p>
                                <p class="benefits small ng-scope ng-binding" ng-repeat="benefit in membership.benefits track by $index">Cocktail reception with Museum scientists</p>
                                <p class="benefits small ng-scope ng-binding" ng-repeat="benefit in membership.benefits track by $index">Unlimited complimentary admission to the Museum, special exhibitions, Space Show, and LeFrak Theater for up to six people</p>
                                <p class="benefits small ng-scope ng-binding" ng-repeat="benefit in membership.benefits track by $index">Invitations to special exhibition previews, gala openings, and lectures</p>
                                <p class="benefits small ng-scope ng-binding" ng-repeat="benefit in membership.benefits track by $index">Complimentary exhibition catalogue and Museum calendar</p>
                                <p class="benefits small ng-scope ng-binding" ng-repeat="benefit in membership.benefits track by $index">Invitation to the annual Patrons Circle Luncheon</p>
                                <p class="benefits small ng-scope ng-binding" ng-repeat="benefit in membership.benefits track by $index">Private behind-the-scenes tour for you and your guests</p>
                                <p class="benefits small ng-scope ng-binding" ng-repeat="benefit in membership.benefits track by $index">Dedicated membership phone line and concierge services at the Member Lounge</p>
                                <p class="benefits small ng-scope ng-binding" ng-repeat="benefit in membership.benefits track by $index">Ten free Museum guest passes for two people</p>
                                <p class="benefits small ng-scope ng-binding" ng-repeat="benefit in membership.benefits track by $index">Free coat check</p>
                                <p class="benefits small ng-scope ng-binding" ng-repeat="benefit in membership.benefits track by $index">Invitation to the Members-only Open House and Members Breakfast</p>
                                <p class="benefits small ng-scope ng-binding" ng-repeat="benefit in membership.benefits track by $index">Advance notice of special programs and AMNH Expeditions</p>
                                <p class="benefits small ng-scope ng-binding" ng-repeat="benefit in membership.benefits track by $index">Invitation to the Annual Family Party and the Museum Ball</p>
                                <p class="benefits small ng-scope ng-binding" ng-repeat="benefit in membership.benefits track by $index">Recognition in the Museum"s Annual Report</p>
                                <p class="benefits small ng-scope ng-binding" ng-repeat="benefit in membership.benefits track by $index">Opportunity to host a children"s birthday party in the Museum</p>
                                <p class="benefits small ng-scope ng-binding" ng-repeat="benefit in membership.benefits track by $index">$1,645 of this membership is tax deductible</p>                                
                            </div>
                            <footer style="position: absolute; bottom: 0px; width: 100%; border-top: 1px dotted rgb(238, 238, 238);" class="sp-in-20px-top sp-in-20px-right sp-in-20px-bottom sp-in-20px-left" ng-style="{ position: "absolute", bottom: 0, width: "100%", "border-top": "1px dotted #eeeeee" }">
                                <button class="btn btn-arrow btn-info btn-lg btn-block " ng-class="{}">
                                    <span class="sp-in-20px-left ng-binding">Join</span>
                                    
                                </button>
                            </footer>
                        </article>
                        <article class="membership flex-col background-white shadow-slight membership-card ng-scope sp-out-0px-right" ng-class="{ "sp-out-22px-right": !$last, "sp-out-0px-right": $last }" ng-repeat="membership in memberships | filter: { levelId: membershipLevel.id } " ng-click="goTo("/memberships/" + membership.id + "/details")" ng-if="!membership.hideOnKiosk">
                            <header class="sp-in-20px-top sp-in-20px-right sp-in-20px-left">
                                <h3 class="heading-lg sp-out-10px-bottom sp-out-0px-top ng-binding">JC Leadership Circle</h3>
                                <h4 class="heading-lg text-orange ng-binding">$750.00</h4>
                            </header>
                            <hr class="hr-sm hr-light hr-dotted">
                            <div class="sp-in-20px-right sp-in-20px-bottom sp-in-20px-left">
                                <p class="benefits small ng-scope ng-binding" ng-repeat="benefit in membership.benefits track by $index">All the benefits of a Junior Council PATRON membership PLUS:</p>
                                <p class="benefits small ng-scope ng-binding" ng-repeat="benefit in membership.benefits track by $index">Invitation to all Junior Council events for you and four guests</p>
                                <p class="benefits small ng-scope ng-binding" ng-repeat="benefit in membership.benefits track by $index">$1,995 of this membership is tax deductible</p>
                            </div>
                            <footer style="position: absolute; bottom: 0px; width: 100%; border-top: 1px dotted rgb(238, 238, 238);" class="sp-in-20px-top sp-in-20px-right sp-in-20px-bottom sp-in-20px-left" ng-style="{ position: "absolute", bottom: 0, width: "100%", "border-top": "1px dotted #eeeeee" }">
                                <button class="btn btn-arrow btn-info btn-lg btn-block " ng-class="{}">
                                    <span class="sp-in-20px-left ng-binding">Join</span>
                                    
                                </button>
                            </footer>
                        </article>
                    </div>
                </section><section class="flex-col ng-scope sp-out-0px-right" ng-class="{ active: membershipLevel.active, "sp-out-22px-right": !$last, "sp-out-0px-right": $last }" ng-repeat="membershipLevel in membershipLevels" ng-hide="!membershipLevel.active &amp;&amp; (membershipLevels | filter: { active: true }).length" ng-if="(memberships | filter: { levelId: membershipLevel.id, hideOnKiosk: false }).length">
                    <div class="input-hidden-parent background-white shadow-slight membership-card" ng-class="{ "": screen.width &lt; 768 }" ng-show="!membershipLevel.active">
                        <img src="site_files/senior.jpg" class="img-fluid">
                        <header class="sp-in-20px-top sp-in-20px-right sp-in-20px-bottom sp-in-20px-left">
                            <h2 class="heading-lg sp-out-20px-bottom ng-binding">Senior Citiczen Membership</h2>
                            <p class="center-block text-md ng-binding">As a thank you for your continued support, we love to offer our senior citizens membership cards, plus up to 6 membership cards for children under 18 years of age. You also receive two guest passes and unlimited free admission!</p>
                        </header>
                        <footer style="position: absolute; bottom: 0px; width: 100%; border-top: 1px dotted rgb(238, 238, 238);" class="sp-in-20px-top sp-in-20px-right sp-in-20px-bottom sp-in-20px-left" ng-style="{ position: "absolute", bottom: 0, width: "100%", "border-top": "1px dotted #eeeeee" }">
                            <button class="btn btn-arrow btn-info btn-lg btn-block " ng-class="{}">
                                <span class="sp-in-20px-left ng-binding">View Options</span>
                                
                            </button>
                        </footer>
                        <input class="input-hidden ng-pristine ng-valid" ng-model="membershipLevel.active" type="checkbox">
                    </div>
                    <div class="memberships">
                        <article class="membership flex-col background-white shadow-slight membership-card ng-scope sp-out-22px-right" ng-class="{ "sp-out-22px-right": !$last, "sp-out-0px-right": $last }" ng-repeat="membership in memberships | filter: { levelId: membershipLevel.id } " ng-click="goTo("/memberships/" + membership.id + "/details")" ng-if="!membership.hideOnKiosk">
                            <header class="sp-in-20px-top sp-in-20px-right sp-in-20px-left">
                                <h3 class="heading-lg sp-out-10px-bottom sp-out-0px-top ng-binding">PC Collector MSP</h3>
                                <h4 class="heading-lg text-orange ng-binding">$1,750.00</h4>
                            </header>
                            <hr class="hr-sm hr-light hr-dotted">
                            <div class="sp-in-20px-right sp-in-20px-bottom sp-in-20px-left">
                                <p class="benefits small ng-scope ng-binding" ng-repeat="benefit in membership.benefits track by $index">Unlimited complimentary admission to the Museum, special exhibitions, Space Show, and LeFrak Theater films for up to six people</p>
                                <p class="benefits small ng-scope ng-binding" ng-repeat="benefit in membership.benefits track by $index">Invitations to special exhibition previews, gala openings, and lectures</p>
                                <p class="benefits small ng-scope ng-binding" ng-repeat="benefit in membership.benefits track by $index">Invitation to the annual PATRONS CIRCLE Luncheon</p>
                                <p class="benefits small ng-scope ng-binding" ng-repeat="benefit in membership.benefits track by $index">Private tour for you and your guests</p>
                                <p class="benefits small ng-scope ng-binding" ng-repeat="benefit in membership.benefits track by $index">Complimentary Museum calendar and book</p>
                                <p class="benefits small ng-scope ng-binding" ng-repeat="benefit in membership.benefits track by $index">Invitation to PATRONS CIRCLE Breakfasts</p>
                                <p class="benefits small ng-scope ng-binding" ng-repeat="benefit in membership.benefits track by $index">Ten free Museum admission guest passes</p>
                                <p class="benefits small ng-scope ng-binding" ng-repeat="benefit in membership.benefits track by $index">Invitation to the Members-Only Open House, Star Party, and Members Breakfast</p>
                                <p class="benefits small ng-scope ng-binding" ng-repeat="benefit in membership.benefits track by $index">Discounts on Museum shop and restaurant purchases</p>
                                <p class="benefits small ng-scope ng-binding" ng-repeat="benefit in membership.benefits track by $index">Advance notice of special programs and AMNH Expeditions</p>
                                <p class="benefits small ng-scope ng-binding" ng-repeat="benefit in membership.benefits track by $index">Mention in the Museum"s annual report</p>
                                <p class="benefits small ng-scope ng-binding" ng-repeat="benefit in membership.benefits track by $index">Dedicated membership phone line and concierge services in the Member Lounge and free coat check</p>
                                <p class="benefits small ng-scope ng-binding" ng-repeat="benefit in membership.benefits track by $index">One-year subscription to Rotunda magazine LEARN MORE</p>
                                <p class="benefits small ng-scope ng-binding" ng-repeat="benefit in membership.benefits track by $index">$1,645 is tax deductible</p>
                            </div>
                            <footer style="position: absolute; bottom: 0px; width: 100%; border-top: 1px dotted rgb(238, 238, 238);" class="sp-in-20px-top sp-in-20px-right sp-in-20px-bottom sp-in-20px-left" ng-style="{ position: "absolute", bottom: 0, width: "100%", "border-top": "1px dotted #eeeeee" }">
                                <button class="btn btn-arrow btn-info btn-lg btn-block " ng-class="{}">
                                    <span class="sp-in-20px-left ng-binding">Join</span>
                                    
                                </button>
                            </footer>
                        </article>
                        <article class="membership flex-col background-white shadow-slight membership-card ng-scope sp-out-22px-right" ng-class="{ "sp-out-22px-right": !$last, "sp-out-0px-right": $last }" ng-repeat="membership in memberships | filter: { levelId: membershipLevel.id } " ng-click="goTo("/memberships/" + membership.id + "/details")" ng-if="!membership.hideOnKiosk">
                            <header class="sp-in-20px-top sp-in-20px-right sp-in-20px-left">
                                <h3 class="heading-lg sp-out-10px-bottom sp-out-0px-top ng-binding">PC Naturalist MSP</h3>
                                <h4 class="heading-lg text-orange ng-binding">$3,500.00</h4>
                            </header>
                            <hr class="hr-sm hr-light hr-dotted">
                            <div class="sp-in-20px-right sp-in-20px-bottom sp-in-20px-left">
                                <p class="benefits small ng-scope ng-binding" ng-repeat="benefit in membership.benefits track by $index">All of the benefits of Collector, plus:</p>
                                <p class="benefits small ng-scope ng-binding" ng-repeat="benefit in membership.benefits track by $index">Invitation to a Science-at-Work luncheon or reception hosted by Museum scientists</p>
                                <p class="benefits small ng-scope ng-binding" ng-repeat="benefit in membership.benefits track by $index">Opportunity to receive two complimentary passes</p>
                                <p class="benefits small ng-scope ng-binding" ng-repeat="benefit in membership.benefits track by $index">to the Museum"s annual Margaret Mead Film Festival</p>
                                <p class="benefits small ng-scope ng-binding" ng-repeat="benefit in membership.benefits track by $index">$3,385 is tax deductible</p>
                                
                            </div>
                            <footer style="position: absolute; bottom: 0px; width: 100%; border-top: 1px dotted rgb(238, 238, 238);" class="sp-in-20px-top sp-in-20px-right sp-in-20px-bottom sp-in-20px-left" ng-style="{ position: "absolute", bottom: 0, width: "100%", "border-top": "1px dotted #eeeeee" }">
                                <button class="btn btn-arrow btn-info btn-lg btn-block " ng-class="{}">
                                    <span class="sp-in-20px-left ng-binding">Join</span>
                                    
                                </button>
                            </footer>
                        </article>
                        <article class="membership flex-col background-white shadow-slight membership-card ng-scope sp-out-22px-right" ng-class="{ "sp-out-22px-right": !$last, "sp-out-0px-right": $last }" ng-repeat="membership in memberships | filter: { levelId: membershipLevel.id } " ng-click="goTo("/memberships/" + membership.id + "/details")" ng-if="!membership.hideOnKiosk">
                            <header class="sp-in-20px-top sp-in-20px-right sp-in-20px-left">
                                <h3 class="heading-lg sp-out-10px-bottom sp-out-0px-top ng-binding">PC Explorer MSP</h3>
                                <h4 class="heading-lg text-orange ng-binding">$6,500.00</h4>
                            </header>
                            <hr class="hr-sm hr-light hr-dotted">
                            <div class="sp-in-20px-right sp-in-20px-bottom sp-in-20px-left">
                                <p class="benefits small ng-scope ng-binding" ng-repeat="benefit in membership.benefits track by $index">All of the benefits of Naturalist, plus:</p>
                                <p class="benefits small ng-scope ng-binding" ng-repeat="benefit in membership.benefits track by $index">Special invitations to receptions with Museum trustees, curators, and scientists</p>
                                <p class="benefits small ng-scope ng-binding" ng-repeat="benefit in membership.benefits track by $index">Opportunity to host parties and events in select Museum venues</p>
                                <p class="benefits small ng-scope ng-binding" ng-repeat="benefit in membership.benefits track by $index">$6,355 is tax deductible</p>
                            </div>
                            <footer style="position: absolute; bottom: 0px; width: 100%; border-top: 1px dotted rgb(238, 238, 238);" class="sp-in-20px-top sp-in-20px-right sp-in-20px-bottom sp-in-20px-left" ng-style="{ position: "absolute", bottom: 0, width: "100%", "border-top": "1px dotted #eeeeee" }">
                                <button class="btn btn-arrow btn-info btn-lg btn-block " ng-class="{}">
                                    <span class="sp-in-20px-left ng-binding">Join</span>
                                </button>
                            </footer>
                        </article>
                        <article class="membership flex-col background-white shadow-slight membership-card ng-scope sp-out-22px-right" ng-class="{ "sp-out-22px-right": !$last, "sp-out-0px-right": $last }" ng-repeat="membership in memberships | filter: { levelId: membershipLevel.id } " ng-click="goTo("/memberships/" + membership.id + "/details")" ng-if="!membership.hideOnKiosk">
                            <header class="sp-in-20px-top sp-in-20px-right sp-in-20px-left">
                                <h3 class="heading-lg sp-out-10px-bottom sp-out-0px-top ng-binding">PC Curator MSP</h3>
                                <h4 class="heading-lg text-orange ng-binding">$10,000.00</h4>
                            </header>
                            <hr class="hr-sm hr-light hr-dotted">
                            <div class="sp-in-20px-right sp-in-20px-bottom sp-in-20px-left">
                                <p class="benefits small ng-scope ng-binding" ng-repeat="benefit in membership.benefits track by $index">All of the benefits of Explorer, plus:</p>
                                <p class="benefits small ng-scope ng-binding" ng-repeat="benefit in membership.benefits track by $index">Invitation to dinner hosted by the Chairman of the Board of Trustees</p>
                                <p class="benefits small ng-scope ng-binding" ng-repeat="benefit in membership.benefits track by $index">Opportunity to attend two Science-at-Work events</p>
                                <p class="benefits small ng-scope ng-binding" ng-repeat="benefit in membership.benefits track by $index">$9,755 is tax deductible</p>
                            </div>
                            <footer style="position: absolute; bottom: 0px; width: 100%; border-top: 1px dotted rgb(238, 238, 238);" class="sp-in-20px-top sp-in-20px-right sp-in-20px-bottom sp-in-20px-left" ng-style="{ position: "absolute", bottom: 0, width: "100%", "border-top": "1px dotted #eeeeee" }">
                                <button class="btn btn-arrow btn-info btn-lg btn-block " ng-class="{}">
                                    <span class="sp-in-20px-left ng-binding">Join</span>
                                    
                                </button>
                            </footer>
                        </article>
                        <article class="membership flex-col background-white shadow-slight membership-card ng-scope sp-out-22px-right" ng-class="{ "sp-out-22px-right": !$last, "sp-out-0px-right": $last }" ng-repeat="membership in memberships | filter: { levelId: membershipLevel.id } " ng-click="goTo("/memberships/" + membership.id + "/details")" ng-if="!membership.hideOnKiosk">
                            <header class="sp-in-20px-top sp-in-20px-right sp-in-20px-left">
                                <h3 class="heading-lg sp-out-10px-bottom sp-out-0px-top ng-binding">PC Discoverer MSP</h3>
                                <h4 class="heading-lg text-orange ng-binding">$25,000.00</h4>
                            </header>
                            <hr class="hr-sm hr-light hr-dotted">
                            <div class="sp-in-20px-right sp-in-20px-bottom sp-in-20px-left">
                                <p class="benefits small ng-scope ng-binding" ng-repeat="benefit in membership.benefits track by $index">Unknown</p>
                            </div>
                            <footer style="position: absolute; bottom: 0px; width: 100%; border-top: 1px dotted rgb(238, 238, 238);" class="sp-in-20px-top sp-in-20px-right sp-in-20px-bottom sp-in-20px-left" ng-style="{ position: "absolute", bottom: 0, width: "100%", "border-top": "1px dotted #eeeeee" }">
                                <button class="btn btn-arrow btn-info btn-lg btn-block " ng-class="{}">
                                    <span class="sp-in-20px-left ng-binding">Join</span>
                                    
                                </button>
                            </footer>
                        </article>
                        <article class="membership flex-col background-white shadow-slight membership-card ng-scope sp-out-22px-right" ng-class="{ "sp-out-22px-right": !$last, "sp-out-0px-right": $last }" ng-repeat="membership in memberships | filter: { levelId: membershipLevel.id } " ng-click="goTo("/memberships/" + membership.id + "/details")" ng-if="!membership.hideOnKiosk">
                            <header class="sp-in-20px-top sp-in-20px-right sp-in-20px-left">
                                <h3 class="heading-lg sp-out-10px-bottom sp-out-0px-top ng-binding">PC President"s Society MSP</h3>
                                <h4 class="heading-lg text-orange ng-binding">$50,000.00</h4>
                            </header>
                            <hr class="hr-sm hr-light hr-dotted">
                            <div class="sp-in-20px-right sp-in-20px-bottom sp-in-20px-left">
                                <p class="benefits small ng-scope ng-binding" ng-repeat="benefit in membership.benefits track by $index">For more information regarding these membership levels, please call 212-769-5286.</p>
                            </div>
                            <footer style="position: absolute; bottom: 0px; width: 100%; border-top: 1px dotted rgb(238, 238, 238);" class="sp-in-20px-top sp-in-20px-right sp-in-20px-bottom sp-in-20px-left" ng-style="{ position: "absolute", bottom: 0, width: "100%", "border-top": "1px dotted #eeeeee" }">
                                <button class="btn btn-arrow btn-info btn-lg btn-block " ng-class="{}">
                                    <span class="sp-in-20px-left ng-binding">Join</span>
                                    
                                </button>
                            </footer>
                        </article>
                        <article class="membership flex-col background-white shadow-slight membership-card ng-scope sp-out-0px-right" ng-class="{ "sp-out-22px-right": !$last, "sp-out-0px-right": $last }" ng-repeat="membership in memberships | filter: { levelId: membershipLevel.id } " ng-click="goTo("/memberships/" + membership.id + "/details")" ng-if="!membership.hideOnKiosk">
                            <header class="sp-in-20px-top sp-in-20px-right sp-in-20px-left">
                                <h3 class="heading-lg sp-out-10px-bottom sp-out-0px-top ng-binding">PC Chairman"s Circle MSP</h3>
                                <h4 class="heading-lg text-orange ng-binding">$75,000.00</h4>
                            </header>
                            <hr class="hr-sm hr-light hr-dotted">
                            <div class="sp-in-20px-right sp-in-20px-bottom sp-in-20px-left">
                                <p class="benefits small ng-scope ng-binding" ng-repeat="benefit in membership.benefits track by $index">For more information regarding these membership levels, please call 212-769-5286.</p>
                            </div>
                            <footer style="position: absolute; bottom: 0px; width: 100%; border-top: 1px dotted rgb(238, 238, 238);" class="sp-in-20px-top sp-in-20px-right sp-in-20px-bottom sp-in-20px-left" ng-style="{ position: "absolute", bottom: 0, width: "100%", "border-top": "1px dotted #eeeeee" }">
                                <button class="btn btn-arrow btn-info btn-lg btn-block " ng-class="{}">
                                    <span class="sp-in-20px-left ng-binding">Join</span>
                                    
                                </button>
                            </footer>
                        </article>
                    </div>
                </section>
            </div>
        </div>        
    </ng-view>
    </section>';
        
    
    printHtmlStuff('membership', $content);
}


function errorPageWillCall($errorMessage){
    if (empty($errorMessage)){
        $errorMessage = 'Unexpected error.';
    }

//    $content = '<center><br><br><br><font class="errorMessage">' . $errorMessage . '</font></center>';

    $_SESSION['title'] = 'errorPageWillCall';

    $_SESSION['javaScript'] = '


        <script language="javascript">

            function postMyForm(task) {
                setTimeout(function() {
                    document.myform.task.value = task;
                    document.myform.submit();
                    return true;
                },250);
            }
        </script>';


       $_SESSION['topTable'] = '



            <style>

                .labels{
                    font-family:helvetica;
                    font-weight:400;
                    font-size:30pt;
                }

                .textBoxes{
                    width:350px;
                    height:70px;
                    font-family:helvitica;
                    font-weight:400;
                    font-size:28pt;
                    text-align: center;
                }
                .continueButtons{
                    font-family:helvetica;
                    font-weight:700;
                    font-size:38pt;
                    color:#FFFFFF;
                    height:100px;
                    border: 2px solid black;
                }

            </style>


                <center>
                    <table>
                        <tr>
                            <td height="150px"></td>
                        </tr>
                        <tr height="100px">
                            <td>
                                <center><font class="prompt1">
                                            ' . $errorMessage . '<br>
                                            <font class="prompt2">Please go to a ticket desk for assistance.</font>
                                        </font>
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td height="100px"></td>
                        </tr>
                   </table>
                 </center>






                <!-- BEGIN NAVIGATION (NEW DESIGN HAS NAVIGATION FLOATED UP INTO TOP TABLE -->
                 <center>
                <table>
                <tr>
                    <td colspan=5>
                        <center>

                        </center>
                    </td>
                </tr>


                <tr>
                    <td width=100>&nbsp;</td>

                    <td id="tryagain" valign=middle class="button1"
                        onclick="postMyForm(\'startPage\');" valign=bottom>
                        Try Again
                    </td>

                    <td width=100>&nbsp;</td>
                </tr>
                </table>
                </center>';

         $_SESSION['navigationTable'] = '<!-- navigation is in top table -->';

    printHtmlStuffSales();
} 

function errorPageSales($errorMessage){
    if (empty($errorMessage)){
        $errorMessage = 'General Error.';
    } else if (!empty($_REQUEST['error'])){
        $errorMessage = $_REQUEST['error'];
    }

//    $content = '<center><br><br><br><font class="errorMessage">' . $errorMessage . '</font></center>';

    $_SESSION['title'] = 'errorPageSales';

    $_SESSION['javaScript'] = '


        <script language="javascript">

            function postMyForm(task) {
                setTimeout(function() {
                    document.myform.task.value = task;
                    document.myform.submit();
                    return true;
                },250);
            }
        </script>';


       $_SESSION['topTable'] = '



            <style>

                .labels{
                    font-family:helvetica;
                    font-weight:400;
                    font-size:30pt;
                }

                .textBoxes{
                    width:350px;
                    height:70px;
                    font-family:helvitica;
                    font-weight:400;
                    font-size:28pt;
                    text-align: center;
                }
                .continueButtons{
                    font-family:helvetica;
                    font-weight:700;
                    font-size:38pt;
                    color:#FFFFFF;
                    height:100px;
                    border: 2px solid black;
                }

            </style>


                <center>
                    <table>
                        <tr>
                            <td height="150px"></td>
                        </tr>
                        <tr height="100px">
                            <td>
                                <center><font class="prompt1">
                                            <b>' . $errorMessage . '</b><br>
                                            <font class="prompt2">Please go to a ticket desk for assistance.</font>
                                        </font>
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td height="100px"></td>
                        </tr>
                   </table>
                 </center>






                <!-- BEGIN NAVIGATION (NEW DESIGN HAS NAVIGATION FLOATED UP INTO TOP TABLE -->
                 <center>
                <table>
                <tr>
                    <td colspan=5>
                        <center>

                        </center>
                    </td>
                </tr>


                <tr>
                    <td width=100>&nbsp;</td>

                    <td id="tryagain" valign=middle class="button1"
                        onMouseOver="document.getElementById(\'tryagain\').style.backgroundImage = \'url(images/redBar_clicked.png)\';"
                        onMouseOut="document.getElementById(\'tryagain\').style.backgroundImage = \'url(images/redBar.png)\';"
                        onclick="document.getElementById(\'tryagain\').style.backgroundImage = \'url(images/redBar_clicked.png)\';postMyForm(\'startPage\');" valign=bottom>
                        Try Again
                    </td>

                    <td width=100>&nbsp;</td>
                </tr>
                </table>
                </center>';

         $_SESSION['navigationTable'] = '<!-- navigation is in top table -->';

    printHtmlStuffSales();
} 



function thanksThirdParty($mskPAN){  //This is receipt information returned from third party transactions
    
    //Will need to connect to one more API which will basically 
    //purchase the tickets for $0.00.
    //This method will also get back data which will help build the fgl.
    //Will show 'printing tickets' until they are all printed and then it will show thanks.
    
    //To do:  will need to design the thank you page.
    
    //Call CheckoutEx4 API;
    //Will return Save in progress, Completed Save, or Error when saving;
    //To do: handle on failure message;
    $data['mskPAN'] = $mskPAN;
    $data['CCName'] = '';
    $data['amount'] = $_SESSION['orderTotal']; // should match the cart. If we have to get cartinfo, we can.
    //$data['paymentReference'] = $_SESSION['ref']; // added reference number from element
    $data['ccNumber'] = $_SESSION['ccNumber'];  // added unmasked cc number
    
    // TODO: GET ccMonth, ccYear
    $data['ccOwner'] = $_SESSION['ccOwner'];
    $data['ccMonth'] = $_SESSION['expMonth'];
    $data['ccYear'] = $_SESSION['expYear'];
           
    //exit('here');                    
                        
    $checkedOut = checkoutEx4($data);  // should return a true or a "1" if checked out.
    
//    echo "<textarea rows=20 cols=120>";
//    print_r($checkedOut);
//    echo "</textarea>";
//    exit;
//    
    
     
    if ($checkedOut === false){
        errorPageSales("ERROR CHECKING OUT. PLEASE SEE VISITOR'S SERVICES. <br>YOUR CREDIT CARD CHARGES WILL NEED TO BE REFUNDED.");
    }
    
    //exit('here'); //need to exit out when testing to place an order for will call pickup
    
    
    $_SESSION['fglReceipt'] = '"<F9><HW1,1><RC367,160><RL>BAM<F9><HW1,1><RC365,190><RL>30 Lafayette Ave<F9><HW1,1><RC360,220><RL>Brooklyn, Ny  11217<F9><HW1,1><RC380,250><RL>PHONE # 718.636.4182<F9><HW1,1><RC400,280><RL>~tranDate~    ~tranTime~<F9><HW1,1><RC440,340><RL>MERCH LOC CODE:          ~merchLocCode~<F9><HW1,1><RC440,400><RL>CLERK : ~clerk~<F9><HW1,1><RC440,430><RL>REF # : ~ref~<F9><HW1,1><RC440,460><RL>ACT # : ~acctNbr~<F9><HW1,1><RC440,490><RL>EXP   : ~exp~<F9><HW1,1><RC440,520><RL>CARD  : ~card~<F9><HW1,1><RC440,550><RL>ENTRY : ~entry~<F9><HW1,1><RC440,610><RL>CREDIT PURCHASE    $~creditPurchase~<F9><HW1,1><RC440,670><RL>APPROVAL CODE:     ~approvalCode~<F9><HW1,1><RC440,700><RL>TRAN ID:   ~transactionId~<F9><HW1,1><RC350,860><RL> THANK YOU<F9><HW1,1><RC375,890><RL>CARDHOLDER COPY<p>"';
    
    // TODO: MUST GET A VALID RECEIPT.
    //$find = array("~tranDate~", "~tranTime~", "~merchLocCode~", "~clerk~", "~ref~", "~acctNbr~", "~exp~", "~card~", "~entry~", "~creditPurchase~", "~approvalCode~", "~transactionId~");
    //$values = array($_SESSION['tranDate'], $_SESSION['tranTime'], $_SESSION['merchLocCode'], $_SESSION['clerk'], $_SESSION['ref'], $_SESSION['acctNbr'], $_SESSION['exp'], $_SESSION['card'], $_SESSION['entry'], $_SESSION['creditPurchase'], $_SESSION['approvalCode'], $_SESSION['transactionId']);
    //$_SESSION['fglReceipt'] = str_replace($find, $values, $_SESSION['fglReceipt']);
    
    // now, using this confirmation number, get all tickets associated with this order number.
    //exit('orderNo' . $_SESSION['orderNo']);
    lookupByConfirmation($_SESSION['orderNo'], 'sales');
    
    
    
    
    
    
    
    
    //nothing below is being used beyond this point
    
    
    
    
    
    
    
    
    
    
    
    
    //Call GetTicketPrintInformation API;
    //To Do: Order number is invalid error
    //Get order number from GetCart
    //To Do: Handle on failure message;
    $data['ordernumber']  ;
    $data['headerdesign'] = '';
    $data['ticketdesign'] = '';
    $data['receipt'] = 'Y';
   
    

    
    //Print receipts here!!!
    $fglTickets = array();  
    //push receipt into the fgl into the first element
    //$fglTickets[0] = "<RL><RC300,30><F3><HW1,1>JUST ARRIVE<RC310,76><F6><HW1,1><BS26,44>TESSITURA<F2><RC348,130><F6><HW1,1><BS42,44>PASSPORT<RC324,240><RL><F6><HW2,2>6<RC210,240><HW1,1>DAY<RC230,290><F3><HW1,1>ADMIT ONE<RC230,320><F6><HW1,1>GUEST<F1><RC230,370><F1><HW1,1>VERY SMALL PRINT<RC24,530><LT2><BX340,50><RC25,528><LT2><VX338><RC216,550><HW1,1><F2>DAY  1<RC24,580><LT2><BX340,50><RC216,600>DAY  2<RC24,630><LT2><BX340,50><RC216,650>DAY  3<RC24,680><LT2><BX340,50><RC216,700>DAY  4<RC24,730><LT2><BX340,50><RC25,780><LT2><VX338><RC216,760>DAY  5<RC340,400><RL><F6><BS36,44><HW1,1>DAY GUEST<RC260,450><F3><HW1,1>$112.00<RC260,482><F3>PLUS TAX<RC280,1010><F3><HW1,1>12345678<RC60,990><NL10><X2>*01000407*<RC360,820><F9><HW1,1>VALID ONLY ON DATE STAMPED<F1><RC300,840><F1><HW1,1>NONTRANSFERABLE NONREFUNDABLE<RC280,870><F3><HW1,1>01000407<RC100,1079><RR><F3><HW1,1>JUST ARRIVE<p>";
    



    //getTicketPrintInformation($data);
    // push each ticket into fglTickets.
    
    
    // build receipt.
    $receiptFgl = 'var tkts = [
            "<F3><HW1,1><RC450,250><RL>EMP Museum<F3><HW1,1><RC500,290><RL>325 5th Avenue N<F3><HW1,1><RC520,330><RL>Seattle, WA  98109<F3><HW1,1><RC542,370><RL>PHONE # (206)770-2700<F3><HW1,1><RC570,410><RL>~tranDate~     ~tranTime~<F3><HW1,1><RC590,490><RL>MERCH LOC CODE:   ~merchLocCode~<F3><HW1,1><RC590,570><RL>CLERK : ~clerk~<F3><HW1,1><RC590,610><RL>REF # : ~ref~<F3><HW1,1><RC590,650><RL>ACT # : ~acctNbr~<F3><HW1,1><RC590,690><RL>EXP   : ~exp~<F3><HW1,1><RC590,730><RL>CARD  : ~card~<F3><HW1,1><RC590,770><RL>ENTRY : ~entry~<F3><HW1,1><RC590,850><RL>CREDIT PURCHASE    $~creditPurchase~<F3><HW1,1><RC590,930><RL>APPROVAL CODE:     ~approvalCode~<F3><HW1,1><RC590,970><RL>TRAN ID: ~transactionId~<F3><HW1,1><RC460,1100><RL> THANK YOU<F3><HW1,1><RC500,1140><RL>CARDHOLDER COPY<p>"
             ];';
    $find = array("~tranDate~", "~tranTime~", "~merchLocCode~", "~clerk~", "~ref~", "~acctNbr~", "~exp~", "~card~", "~entry~", "~creditPurchase~", "~approvalCode~", "~transactionId~");
    $values = array($_SESSION['tranDate'], $_SESSION['tranTime'], $_SESSION['merchLocCode'], $_SESSION['clerk'], $_SESSION['ref'], $_SESSION['acctNbr'], $_SESSION['exp'], $_SESSION['card'], $_SESSION['entry'], $_SESSION['creditPurchase'], $_SESSION['approvalCode'], $_SESSION['transactionId']);
    $receiptFgl = str_replace($find, $values, $receiptFgl);
    
    
    
    
    array_push($fglTickets,$receiptFgl);
    
    
    
    
    // build tkts
    //$tkts = 'var tkts = [
    $javaFgl = 'var tkts = [
        "<RL><RC300,30><F3><HW1,1>JUST ARRIVE<RC310,76><F6><HW1,1><BS26,44>TESSITURA<F2><RC348,130><F6><HW1,1><BS42,44>PASSPORT<RC324,240><RL><F6><HW2,2>6<RC210,240><HW1,1>DAY<RC230,290><F3><HW1,1>ADMIT ONE<RC230,320><F6><HW1,1>GUEST<F1><RC230,370><F1><HW1,1>VERY SMALL PRINT<RC24,530><LT2><BX340,50><RC25,528><LT2><VX338><RC216,550><HW1,1><F2>DAY  1<RC24,580><LT2><BX340,50><RC216,600>DAY  2<RC24,630><LT2><BX340,50><RC216,650>DAY  3<RC24,680><LT2><BX340,50><RC216,700>DAY  4<RC24,730><LT2><BX340,50><RC25,780><LT2><VX338><RC216,760>DAY  5<RC340,400><RL><F6><BS36,44><HW1,1>DAY GUEST<RC260,450><F3><HW1,1>$112.00<RC260,482><F3>PLUS TAX<RC280,1010><F3><HW1,1>12345678<RC60,990><NL10><X2>*01000407*<RC360,820><F9><HW1,1>VALID ONLY ON DATE STAMPED<F1><RC300,840><F1><HW1,1>NONTRANSFERABLE NONREFUNDABLE<RC280,870><F3><HW1,1>01000407<RC100,1079><RR><F3><HW1,1>JUST ARRIVE<p>",
        "<RL><RC300,30><F3><HW1,1>JUST ARRIVE<RC310,76><F6><HW1,1><BS26,44>TESSITURA<F2><RC348,130><F6><HW1,1><BS42,44>PASSPORT<RC324,240><RL><F6><HW2,2>6<RC210,240><HW1,1>DAY<RC230,290><F3><HW1,1>ADMIT ONE<RC230,320><F6><HW1,1>GUEST<F1><RC230,370><F1><HW1,1>VERY SMALL PRINT<RC24,530><LT2><BX340,50><RC25,528><LT2><VX338><RC216,550><HW1,1><F2>DAY  1<RC24,580><LT2><BX340,50><RC216,600>DAY  2<RC24,630><LT2><BX340,50><RC216,650>DAY  3<RC24,680><LT2><BX340,50><RC216,700>DAY  4<RC24,730><LT2><BX340,50><RC25,780><LT2><VX338><RC216,760>DAY  5<RC340,400><RL><F6><BS36,44><HW1,1>DAY GUEST<RC260,450><F3><HW1,1>$112.00<RC260,482><F3>PLUS TAX<RC280,1010><F3><HW1,1>12345678<RC60,990><NL10><X2>*01000407*<RC360,820><F9><HW1,1>VALID ONLY ON DATE STAMPED<F1><RC300,840><F1><HW1,1>NONTRANSFERABLE NONREFUNDABLE<RC280,870><F3><HW1,1>01000407<RC100,1079><RR><F3><HW1,1>JUST ARRIVE<p>",
        "<RL><RC300,30><F3><HW1,1>JUST ARRIVE<RC310,76><F6><HW1,1><BS26,44>TESSITURA<F2><RC348,130><F6><HW1,1><BS42,44>PASSPORT<RC324,240><RL><F6><HW2,2>6<RC210,240><HW1,1>DAY<RC230,290><F3><HW1,1>ADMIT ONE<RC230,320><F6><HW1,1>GUEST<F1><RC230,370><F1><HW1,1>VERY SMALL PRINT<RC24,530><LT2><BX340,50><RC25,528><LT2><VX338><RC216,550><HW1,1><F2>DAY  1<RC24,580><LT2><BX340,50><RC216,600>DAY  2<RC24,630><LT2><BX340,50><RC216,650>DAY  3<RC24,680><LT2><BX340,50><RC216,700>DAY  4<RC24,730><LT2><BX340,50><RC25,780><LT2><VX338><RC216,760>DAY  5<RC340,400><RL><F6><BS36,44><HW1,1>DAY GUEST<RC260,450><F3><HW1,1>$112.00<RC260,482><F3>PLUS TAX<RC280,1010><F3><HW1,1>12345678<RC60,990><NL10><X2>*01000407*<RC360,820><F9><HW1,1>VALID ONLY ON DATE STAMPED<F1><RC300,840><F1><HW1,1>NONTRANSFERABLE NONREFUNDABLE<RC280,870><F3><HW1,1>01000407<RC100,1079><RR><F3><HW1,1>JUST ARRIVE<p>"
        ];';
    
    //salesPrinting($javaFgl);
    salesPrinting($receiptFgl);

    
    $tkts = 'var tkts = [';
            // add elements
            foreach ($fglTickets as $thisFglTicket){
                $tkts .= $thisFglTicket . "\n";
            }
    $tkts .= '];';       
            
    
    
    
    if (strpos($_SESSION['performanceChosen']['description'],"Museum Admission") > -1){
        $printTitle = '<img src="site_files/admissionPrinting.png">';
    } else if (strpos($_SESSION['performanceChosen']['description'],"Star Wars") > -1) {
        $printTitle = '<img src="site_files/starWarsPrinting.png">';
    }
    
    $content = '
                

        <!--<script src="jquery.min.js"></script>-->
        <script src="/printTkts/jquery.min.js"></script>
        <script>       
        var currentTicket = 0;

        ' . $tkts . '
        var totalTickets = tkts.length;     
        function printNext(){
            printTkt(tkts[currentTicket], currentTicket+1, totalTickets);
            currentTicket++;
            if (currentTicket < totalTickets){
                window.setTimeout("printNext()", 1500);
            } else if (currentTicket == totalTickets) {
                window.setTimeout("printNext();", 5000);
            } else {
                location.href="?task=startPage";
            }
        }
        function printTkt(fgl, currentTicket, totalTickets){
            document.getElementById(\'printingMessage\').innerHTML = "Printing " + currentTicket + " of " + totalTickets + ".";
            $.ajax({
                url : "https://localhost/printTkts/printTkts.php",
                type: "POST",
                data : "fgl=" + fgl,
                success: function(data, textStatus, jqXHR)
                {
                    document.getElementById(\'printingMessage\').innerHTML = "Printed " + currentTicket + " of " + totalTickets + ".";
                },
                error: function(xhr, status, error) {
                    var err = eval("(" + xhr.responseText + ")");
                    document.write(err.Message);
                }
                /*error: function (jqXHR, textStatus, errorThrown)
                {
                    document.write("ERROR");
                    document.write("jqXHR: " + jqXHR);
                    document.write("textStatus: " + textStatus);
                    document.write("errorThrown: " + errorThrown);
                }*/
            });
        }

        </script>    
        


                

                <form name="myform" method="POST" action="">
                    <input type="hidden" name="task" value="enjoy">
                </form>
                
                


           <center>
           <table cellpadding=0 cellspacing=0>
           <tr>
                <td onclick="document.myform.submit()"> ' . $printTitle . ' 
                </td>
           </tr>
           </table>
           </center>
            <script>window.setTimeout("printNext()", 2000);</script>
            

            ';
    
    
        
        printHtmlStuff('thanks', $content);
        
        
        
        
        
        
        
        
        

        exit;
    
    
}


function shortCenter($text){
    $fgl = "";
    $row = "250";
    $col = "640";
    $center = "250";
    
    $length = strlen($text);
    //exit('text: ' . $text);
    //exit('length ' . $length);
    // you have length. You need row and col.
    if ($length <= 16) {
        //exit('length is 10');
        $row = $center + ($length * 9);  //10
    } else if ($length >= 17) {
        //exit('length is 17');
        $row = $center + ($length * 10);
        //$row = 460;
        //exit('row: ' . $row);
    }
    
    $fgl = "<F13><HW1,1><RC" . $row . "," . $col . "><RL>" . $text;
    
    return $fgl;
}

function longCenter($text){
    $fgl = "";
    $row = "460";
    $col = "670";
    $center = "250";
    
    $length = strlen($text);
    //exit('length: ' . $length);
    $row = $center + ($length * 8);
    
    $fgl = "<F9><HW1,1><RC" . $row . "," . $col . "><RL>" . $text;
    
    return $fgl;
}



function thanks($mskPAN){
//    exit("here");
    //Will need to connect to one more API which will basically 
    //purchase the tickets for $0.00.
    //This method will also get back data which will help build the fgl.
    //Will show 'printing tickets' until they are all printed and then it will show thanks.
    
    //To do:  will need to design the thank you page.
    
    //Call CheckoutEx4 API;
    //Will return Save in progress, Completed Save, or Error when saving;
    //To do: handle on failure message;
    
    getAddressAndShippingMethod();
    setShippingInformation();
    
    if ($_SESSION['requireSwipe']) {
        $data['mskPAN'] = $mskPAN;
        $data['CCName'] = '';
        $data['amount'] = $_SESSION['orderTotal']; // should match the cart. If we have to get cartinfo, we can.
        //$data['paymentReference'] = $_SESSION['ref']; // added reference number from element
        $data['ccNumber'] = $_SESSION['ccNumber'];  // added unmasked cc number

        // TODO: GET ccMonth, ccYear
        $data['ccOwner'] = $_SESSION['ccOwner'];
        $data['ccMonth'] = $_SESSION['expMonth'];
        $data['ccYear'] = $_SESSION['expYear'];

        $lengthLogin = strlen(trim($_SESSION['login']));
        //exit('lengthlogin: ' . $lengthLogin);
        if (strlen(trim($_SESSION['login'])) > 33) {
            $newLogin = substr($_SESSION['login'],0,33) . '...';
            $_SESSION['login'] = $newLogin; 
        }

    //    $_SESSION['login'] = explode("@",$_SESSION['login']);
    //    $_SESSION['userName'] = $_SESSION['login'][0];
    //    $_SESSION['domain'] = $_SESSION['login'][1];

//        exit('here');

        $startWith = substr($_SESSION['ccNumber'],0,2);
//        exit('startWith: ' . $startWith);

        if ($startWith >= '40' && $startWith <= '49') {
            $data['ccType'] = '43';
            $_SESSION['card'] = 'VISA';
        } else if ($startWith >= '51' && $startWith <= '55') {   
            $data['ccType'] = '45';
            $_SESSION['card'] = 'MASTERCARD';
        } else if ($startWith == '34' || $startWith == '37') {   
            $data['ccType'] = '44';
            $_SESSION['card'] = 'AMERICAN EXPRESS';
        } else {
            displayCart("Invalid card type.<br>Your card was not charged.<br>Please try a different card or the same one again.");
        }
        
        
        $checkedOut = checkoutEx4($data);
        
//        echo "<textarea rows=20 cols=120>";
//        print_r($checkedOut);
//        echo "</textarea>";
//        exit;
    } else {
        $_SESSION['tranDate'] = date('F j, Y');
        $_SESSION['tranTime'] = date('g:iA');
        $_SESSION['clerk'] = $_SESSION['kiosk'];
        $_SESSION['login'] = $_SESSION['loginEmail'];
        $_SESSION['exp'] = '';
        $_SESSION['card'] = '';
        $_SESSION['entry'] = 'NONE';
        $checkedOut = checkoutEx4('');  // should return a true or a "1" if checked out.
    }
//    echo "<textarea rows=20 cols=120>";
//    print_r($checkedOut);
//    echo "</textarea>";
//    exit;
    


    if ($checkedOut === false){
        errorPageSales("ERROR CHECKING OUT. PLEASE SEE VISITOR'S SERVICES. <br>YOUR CREDIT CARD CHARGES WILL NEED TO BE REFUNDED.");
    }
    
    
    //exit('here'); //need to exit out when testing to place an order for will call pickup
    
    
    //$_SESSION['fglReceipt'] = '"<F9><HW1,1><RC367,160><RL>Museum of Fine Arts<F9><HW1,1><RC365,190><RL>465 Huntington Ave<F9><HW1,1><RC360,220><RL>Boston, MA  02115<F9><HW1,1><RC380,250><RL>PHONE # (617)267-9300<F9><HW1,1><RC390,280><RL>~tranDate~    ~tranTime~<F9><HW1,1><RC420,400><RL>CLERK : ~clerk~<F9><HW1,1><RC420,430><RL>CONSTITUENT ID: ~constituentId~<F9><HW1,1><RC420,460><RL>EXP   : ~exp~<F9><HW1,1><RC420,490><RL>CARD  : ~card~<F9><HW1,1><RC420,520><RL>ENTRY : ~entry~<F9><HW1,1><RC420,580><RL>CREDIT PURCHASE:    $~creditPurchase~<F9><HW1,1><RC420,640><RL>LOGIN : <F9><HW1,1><RC460,670><RL>~login~<F9><HW1,1><RC350,860><RL> THANK YOU<F9><HW1,1><RC375,890><RL>CARDHOLDER COPY<p>"';
    $_SESSION['fglReceipt'] = '"<F9><HW1,1><RC367,160><RL>Museum of Fine Arts<F9><HW1,1><RC365,190><RL>465 Huntington Ave<F9><HW1,1><RC360,220><RL>Boston, MA  02115<F9><HW1,1><RC380,250><RL>PHONE # (617)267-9300<F9><HW1,1><RC390,280><RL>~tranDate~    ~tranTime~<F9><HW1,1><RC420,400><RL>CLERK : ~clerk~<F9><HW1,1><RC420,430><RL>CONSTITUENT ID: ~constituentId~<F9><HW1,1><RC420,460><RL>EXP   : ~exp~<F9><HW1,1><RC420,490><RL>CARD  : ~card~<F9><HW1,1><RC420,520><RL>ENTRY : ~entry~<F9><HW1,1><RC420,580><RL>CREDIT PURCHASE:    $~creditPurchase~~loginVariable~<F9><HW1,1><RC350,860><RL> THANK YOU<F9><HW1,1><RC375,890><RL>CARDHOLDER COPY<p>"';

    
    //evaluate whether to put login on 2 lines or 1 based upon length of login
    $find = array("~loginVariable~");
    $loginDisplay = "";
    if (strlen($_SESSION['login']) <= 22) {
        //$loginDisplay = shortCenter("~login~"); 
        $loginDisplay = '';//shortCenter($_SESSION['login']); 
        //$loginDisplay = array("<F13><HW1,1><RC300,640><RL>~login~"); // short
    } else {
        //$loginDisplay = longCenter("~login~"); 
        $loginDisplay = '';//longCenter($_SESSION['login']); 
        //$loginDisplay = "<F9><HW1,1><RC460,670><RL>~login~"; // long
    }
    $_SESSION['fglReceipt'] = str_replace($find, $loginDisplay, $_SESSION['fglReceipt']);
    
    
    //exit('sessionconstituentId: ' . $_SESSION['constituentId']);
    // TODO: MUST GET A VALID RECEIPT.
    $find = array("~tranDate~", "~tranTime~", "~clerk~", "~loginSaveForOtherClients~", "~constituentId~", "~exp~", "~card~", "~entry~", "~creditPurchase~");
    $values = array($_SESSION['tranDate'], $_SESSION['tranTime'], $_SESSION['clerk'], $_SESSION['login'], $_SESSION['constituentId'], $_SESSION['exp'], $_SESSION['card'], $_SESSION['entry'], $_SESSION['orderTotal']);
    $_SESSION['fglReceipt'] = str_replace($find, $values, $_SESSION['fglReceipt']);
    
    
    //change FGL font for the @ sign in email
    $find = array("@");
    if (strlen($_SESSION['login']) >= 22) {
        $values = array("<F13><HW1,1>@<F9><HW1,1>");
        $_SESSION['fglReceipt'] = str_replace($find, $values, $_SESSION['fglReceipt']);
    }
    
    // now, using this confirmation number, get all tickets associated with this order number.
    //exit('orderNo' . $_SESSION['orderNo']);
    
    
    // 2016-09-13 - tkp, surpress the receipt on the free ones.
    if (!$_SESSION['requireSwipe']) {
        $_SESSION['fglReceipt'] = '';
    }
    
    
    lookupByConfirmation($_SESSION['orderNo'], 'sales');
    
    
    
    
    
    
    //nothing below is being used beyond this point
    
    
    
    
    
    
    
    
    
    
    
    
    //Call GetTicketPrintInformation API;
    //To Do: Order number is invalid error
    //Get order number from GetCart
    //To Do: Handle on failure message;
    $data['ordernumber']  ;
    $data['headerdesign'] = '';
    $data['ticketdesign'] = '';
    $data['receipt'] = 'Y';
   
    

    
    //Print receipts here!!!
    $fglTickets = array();  
    //push receipt into the fgl into the first element
    //$fglTickets[0] = "<RL><RC300,30><F3><HW1,1>JUST ARRIVE<RC310,76><F6><HW1,1><BS26,44>TESSITURA<F2><RC348,130><F6><HW1,1><BS42,44>PASSPORT<RC324,240><RL><F6><HW2,2>6<RC210,240><HW1,1>DAY<RC230,290><F3><HW1,1>ADMIT ONE<RC230,320><F6><HW1,1>GUEST<F1><RC230,370><F1><HW1,1>VERY SMALL PRINT<RC24,530><LT2><BX340,50><RC25,528><LT2><VX338><RC216,550><HW1,1><F2>DAY  1<RC24,580><LT2><BX340,50><RC216,600>DAY  2<RC24,630><LT2><BX340,50><RC216,650>DAY  3<RC24,680><LT2><BX340,50><RC216,700>DAY  4<RC24,730><LT2><BX340,50><RC25,780><LT2><VX338><RC216,760>DAY  5<RC340,400><RL><F6><BS36,44><HW1,1>DAY GUEST<RC260,450><F3><HW1,1>$112.00<RC260,482><F3>PLUS TAX<RC280,1010><F3><HW1,1>12345678<RC60,990><NL10><X2>*01000407*<RC360,820><F9><HW1,1>VALID ONLY ON DATE STAMPED<F1><RC300,840><F1><HW1,1>NONTRANSFERABLE NONREFUNDABLE<RC280,870><F3><HW1,1>01000407<RC100,1079><RR><F3><HW1,1>JUST ARRIVE<p>";
    



    //getTicketPrintInformation($data);
    // push each ticket into fglTickets.
    
    
    // build receipt.
    $receiptFgl = 'var tkts = [
            "<F3><HW1,1><RC450,250><RL>EMP Museum<F3><HW1,1><RC500,290><RL>325 5th Avenue N<F3><HW1,1><RC520,330><RL>Seattle, WA  98109<F3><HW1,1><RC542,370><RL>PHONE # (206)770-2700<F3><HW1,1><RC570,410><RL>~tranDate~     ~tranTime~<F3><HW1,1><RC590,490><RL>MERCH LOC CODE:   ~merchLocCode~<F3><HW1,1><RC590,570><RL>CLERK : ~clerk~<F3><HW1,1><RC590,610><RL>REF # : ~ref~<F3><HW1,1><RC590,650><RL>ACT # : ~acctNbr~<F3><HW1,1><RC590,690><RL>EXP   : ~exp~<F3><HW1,1><RC590,730><RL>CARD  : ~card~<F3><HW1,1><RC590,770><RL>ENTRY : ~entry~<F3><HW1,1><RC590,850><RL>CREDIT PURCHASE    $~creditPurchase~<F3><HW1,1><RC590,930><RL>APPROVAL CODE:     ~approvalCode~<F3><HW1,1><RC590,970><RL>TRAN ID: ~transactionId~<F3><HW1,1><RC460,1100><RL> THANK YOU<F3><HW1,1><RC500,1140><RL>CARDHOLDER COPY<p>"
             ];';
    $find = array("~tranDate~", "~tranTime~", "~merchLocCode~", "~clerk~", "~ref~", "~acctNbr~", "~exp~", "~card~", "~entry~", "~creditPurchase~", "~approvalCode~", "~transactionId~");
    $values = array($_SESSION['tranDate'], $_SESSION['tranTime'], $_SESSION['merchLocCode'], $_SESSION['clerk'], $_SESSION['ref'], $_SESSION['acctNbr'], $_SESSION['exp'], $_SESSION['card'], $_SESSION['entry'], $_SESSION['creditPurchase'], $_SESSION['approvalCode'], $_SESSION['transactionId']);
    $receiptFgl = str_replace($find, $values, $receiptFgl);
    
    
    
    
    array_push($fglTickets,$receiptFgl);
    
    
    
    
    // build tkts
    //$tkts = 'var tkts = [
    $javaFgl = 'var tkts = [
        "<RL><RC300,30><F3><HW1,1>JUST ARRIVE<RC310,76><F6><HW1,1><BS26,44>TESSITURA<F2><RC348,130><F6><HW1,1><BS42,44>PASSPORT<RC324,240><RL><F6><HW2,2>6<RC210,240><HW1,1>DAY<RC230,290><F3><HW1,1>ADMIT ONE<RC230,320><F6><HW1,1>GUEST<F1><RC230,370><F1><HW1,1>VERY SMALL PRINT<RC24,530><LT2><BX340,50><RC25,528><LT2><VX338><RC216,550><HW1,1><F2>DAY  1<RC24,580><LT2><BX340,50><RC216,600>DAY  2<RC24,630><LT2><BX340,50><RC216,650>DAY  3<RC24,680><LT2><BX340,50><RC216,700>DAY  4<RC24,730><LT2><BX340,50><RC25,780><LT2><VX338><RC216,760>DAY  5<RC340,400><RL><F6><BS36,44><HW1,1>DAY GUEST<RC260,450><F3><HW1,1>$112.00<RC260,482><F3>PLUS TAX<RC280,1010><F3><HW1,1>12345678<RC60,990><NL10><X2>*01000407*<RC360,820><F9><HW1,1>VALID ONLY ON DATE STAMPED<F1><RC300,840><F1><HW1,1>NONTRANSFERABLE NONREFUNDABLE<RC280,870><F3><HW1,1>01000407<RC100,1079><RR><F3><HW1,1>JUST ARRIVE<p>",
        "<RL><RC300,30><F3><HW1,1>JUST ARRIVE<RC310,76><F6><HW1,1><BS26,44>TESSITURA<F2><RC348,130><F6><HW1,1><BS42,44>PASSPORT<RC324,240><RL><F6><HW2,2>6<RC210,240><HW1,1>DAY<RC230,290><F3><HW1,1>ADMIT ONE<RC230,320><F6><HW1,1>GUEST<F1><RC230,370><F1><HW1,1>VERY SMALL PRINT<RC24,530><LT2><BX340,50><RC25,528><LT2><VX338><RC216,550><HW1,1><F2>DAY  1<RC24,580><LT2><BX340,50><RC216,600>DAY  2<RC24,630><LT2><BX340,50><RC216,650>DAY  3<RC24,680><LT2><BX340,50><RC216,700>DAY  4<RC24,730><LT2><BX340,50><RC25,780><LT2><VX338><RC216,760>DAY  5<RC340,400><RL><F6><BS36,44><HW1,1>DAY GUEST<RC260,450><F3><HW1,1>$112.00<RC260,482><F3>PLUS TAX<RC280,1010><F3><HW1,1>12345678<RC60,990><NL10><X2>*01000407*<RC360,820><F9><HW1,1>VALID ONLY ON DATE STAMPED<F1><RC300,840><F1><HW1,1>NONTRANSFERABLE NONREFUNDABLE<RC280,870><F3><HW1,1>01000407<RC100,1079><RR><F3><HW1,1>JUST ARRIVE<p>",
        "<RL><RC300,30><F3><HW1,1>JUST ARRIVE<RC310,76><F6><HW1,1><BS26,44>TESSITURA<F2><RC348,130><F6><HW1,1><BS42,44>PASSPORT<RC324,240><RL><F6><HW2,2>6<RC210,240><HW1,1>DAY<RC230,290><F3><HW1,1>ADMIT ONE<RC230,320><F6><HW1,1>GUEST<F1><RC230,370><F1><HW1,1>VERY SMALL PRINT<RC24,530><LT2><BX340,50><RC25,528><LT2><VX338><RC216,550><HW1,1><F2>DAY  1<RC24,580><LT2><BX340,50><RC216,600>DAY  2<RC24,630><LT2><BX340,50><RC216,650>DAY  3<RC24,680><LT2><BX340,50><RC216,700>DAY  4<RC24,730><LT2><BX340,50><RC25,780><LT2><VX338><RC216,760>DAY  5<RC340,400><RL><F6><BS36,44><HW1,1>DAY GUEST<RC260,450><F3><HW1,1>$112.00<RC260,482><F3>PLUS TAX<RC280,1010><F3><HW1,1>12345678<RC60,990><NL10><X2>*01000407*<RC360,820><F9><HW1,1>VALID ONLY ON DATE STAMPED<F1><RC300,840><F1><HW1,1>NONTRANSFERABLE NONREFUNDABLE<RC280,870><F3><HW1,1>01000407<RC100,1079><RR><F3><HW1,1>JUST ARRIVE<p>"
        ];';
    
    //salesPrinting($javaFgl);
    
    exit($receiptFgl);
    salesPrinting($receiptFgl);

    
    $tkts = 'var tkts = [';
            // add elements
            foreach ($fglTickets as $thisFglTicket){
                $tkts .= $thisFglTicket . "\n";
            }
    $tkts .= '];';       
            
    
    
    
    if (strpos($_SESSION['performanceChosen']['description'],"Museum Admission") > -1){
        $printTitle = '<img src="site_files/admissionPrinting.png">';
    } else if (strpos($_SESSION['performanceChosen']['description'],"Star Wars") > -1) {
        $printTitle = '<img src="site_files/starWarsPrinting.png">';
    }
    
    $content = '
                

        <!--<script src="jquery.min.js"></script>-->
        <script src="/printTkts/jquery.min.js"></script>
        <script>       
        var currentTicket = 0;

        ' . $tkts . '
        var totalTickets = tkts.length;     
        function printNext(){
            printTkt(tkts[currentTicket], currentTicket+1, totalTickets);
            currentTicket++;
            if (currentTicket < totalTickets){
                window.setTimeout("printNext()", 1500);
            } else if (currentTicket == totalTickets) {
                window.setTimeout("printNext();", 5000);
            } else {
                location.href="?task=startPage";
            }
        }
        function printTkt(fgl, currentTicket, totalTickets){
            document.getElementById(\'printingMessage\').innerHTML = "Printing " + currentTicket + " of " + totalTickets + "...";
            $.ajax({
                url : "https://localhost/printTkts/printTkts.php",
                type: "POST",
                data : "fgl=" + fgl,
                success: function(data, textStatus, jqXHR)
                {
                    document.getElementById(\'printingMessage\').innerHTML = "Printing " + currentTicket + " of " + totalTickets + "...";
                },
                error: function(xhr, status, error) {
                    var err = eval("(" + xhr.responseText + ")");
                    document.write(err.Message);
                }
                /*error: function (jqXHR, textStatus, errorThrown)
                {
                    document.write("ERROR");
                    document.write("jqXHR: " + jqXHR);
                    document.write("textStatus: " + textStatus);
                    document.write("errorThrown: " + errorThrown);
                }*/
            });
        }

        </script>    
        


                

                <form name="myform" method="POST" action="">
                    <input type="hidden" name="task" value="enjoy">
                </form>
                
                


           <center>
           <table cellpadding=0 cellspacing=0>
           <tr>
                <td onclick="document.myform.submit()"> ' . $printTitle . ' 
                </td>
           </tr>
           </table>
           </center>
            <script>window.setTimeout("printNext()", 2000);</script>
            

            ';
    
    
        
        printHtmlStuff('thanks', $content);
        
        
        
        
        
        
        
        
        

        exit;
    
    
}





function printHtmlStuffWillCall($title, $content, $displayHome){
    
//    $_SESSION['header'] = '<!-- start URL ' . $_SESSION['startUrl'] .'--> <!-- Header -->
//        <table cellspacing=0 cellpadding=0 style="background: #f0efec" width="100%">
//            <tr>
//                <td valign=top><img src="site_files/mfaLogo.png" height="60"></td>
//                <td></td>
//                <td></td>
//                <td</td>
//             <td width=100 align=right onclick="document.startover.submit();"><form name="startover" method="post" action""><input type=hidden name=task value=startPage><img src="images/home.png" height=100></form></td>
//             </tr>
//        </table>
//
//
//    ';
    
//            <form name="startover" method="post" action"">
//               <input type=hidden name=task value=startPage>
//               </form>
//                <td id="exit" valign=middle class="exit" 
//                onMouseOver="document.getElementById(\'exit\').style.backgroundImage = \'url(images/exit_clicked.png)\';" 
//                onMouseOut="document.getElementById(\'exit\').style.backgroundImage = \'url(images/exit.png)\';" 
//                onclick="document.getElementById(\'exit\').style.backgroundImage = \'url(images/exit_clicked.png)\';postHome();" 
//                valign=bottom>
//                Exit
//               </td>';
    
    /*
     * <table cellspacing=0 cellpadding=0 width="100%">
        <tr>
            <td colspan=6 height=25></td>
        </tr>
        <tr>
            <td align=left valign=top>' . $loggedInTable . '</td>
            <td>&nbsp;</td>    
           ' . $backTd . '
            
          

           <td width=40>&nbsp;</td>

          ' . $startOverTd . '
           
            <td width=30>&nbsp;</td>
         </tr>
    </table>
     */
    
    
    
    
    
    
    
    
    if ($displayHome === true){
        $_SESSION['header'] = '<!-- start URL ' . $_SESSION['startUrl'] .'--> <!-- Header -->
            <table cellspacing=0 cellpadding=0 width="100%">
                <tr>
                    <td colspan=6 height=25></td>
                </tr>
                <tr>
                    <td align=left valign=top>&nbsp;</td>
                    <td>&nbsp;</td>    
                    
            
                   <form name="startover" method="post" action"">
                   <input type=hidden name=task value=startPage>
                   </form>

                    <td width=40>&nbsp;</td>
                    <td id="exit" valign=middle class="exit" 
                        onclick="postHome();" style="padding-top:10px;padding-left:19px;">
                        Exit
                    </td>
                    <td width=30>&nbsp;</td>
                </tr>
            </table>
        ';
    } else {
        $_SESSION['header'] = '<!-- start URL ' . $_SESSION['startUrl'] .'--> <!-- Header -->
            <table cellspacing=0 cellpadding=0 width="100%">
                <tr>
                    <!--<td valign=top><img src="site_files/mfaLogo.png" height="60"></td> -->
                    <td></td>
                    <td></td>
                    <td</td>
                 <td width=100></td> 
                 </tr>
            </table>
        ';
    }
    
   
    $templateFile = 'html-template-willcallTest.html';
    
    $file_handle = fopen($templateFile, "r");
    $result = '';
    while (!feof($file_handle)) {
       $result .= fgets($file_handle);
    }
    fclose($file_handle);
    
    $result = str_replace("~header~", $_SESSION['header'], $result);
    
    $result = str_replace("~title~", $title, $result);
    $result = str_replace("~content~", $content, $result);
    $result = str_replace("~metaTag~", $_SESSION['metaTag'], $result);
    $result = str_replace("~backgroundCss~", $_SESSION['backgroundCss'], $result);
    
    exit($result);
    
}


function printHtmlStuffSales(){
    
    
    
    if (empty($_SESSION['title'])){
        exit('Missing title');
    }
    if (empty($_SESSION['javaScript'])){
        exit('Missing javaScript');
    }
    if (empty($_SESSION['topTable'])){
        exit('Missing topTable');
    }
    if (empty($_SESSION['navigationTable'])){
        exit('Missing navigationTable');
    }
    
    
    $startOverTd = "<td>&nbsp;</td>";
    $backTd = '<td>&nbsp;</td>';
    
    
    if (!empty($_SESSION['lastTask'])){
        $backTask = $_SESSION['lastTask'];
    } else {
        $backTask = 'startPage';
    }
    




    // special cases where we don't just want to go back one...
    if ($_SESSION['title'] == 'displayCart'){
        $backTask = 'pricesByTime';
//    } else if ($_SESSION['title'] == 'prices'){  //csn 20160722
//        $backTask = 'groups';
    } else if ($_SESSION['title'] == 'performances'){
        $backTask = 'groups';
    } else if ($_SESSION['title'] == 'performanceWithoutTimes'){  //csn 20160722
        $backTask = 'groups';
    } else if ($_SESSION['title'] == 'performanceTimes'){  //csn 20160722
        $backTask = 'performancesWithoutTimes';
    } else if ($_SESSION['title'] == 'pricesByTime'){  //csn 20160722
        $backTask = 'groups';
    } else if ($_SESSION['title'] == 'groups'){
        $backTask = 'memberYesNo';
    } else if ($_SESSION['title'] == 'memberYesNo'){
        $backTask = 'startPage';
    } else if ($_SESSION['title'] == 'survey'){
        $backTask = 'memberYesNo';
    } else if ($_SESSION['title'] == 'validateMember'){
        $backTask = 'memberYesNo';
    } else if ($_SESSION['title'] == 'continueWithMemberPricingYesNo'){
        $backTask = 'validateMember';
    } else if ($_SESSION['title'] == 'prices' && $_SESSION['pricingGroup'] == 'Museum Admission'){  //csn 20160722
        $backTask = 'groups';
    } else if ($_SESSION['title'] == 'prices'){  //csn 20160722
        $backTask = 'performances';
    }
    
   
    
    
    
    if ($_SESSION['title'] != 'startPage'){
        //$backTd = '<td class="headerButton" onclick="document.back.submit();">
        //        << Back
        //    </td>';
        $backTd = '<td width=75 align=right onclick="postBack();">'
                . '<form name="back" method=post action="">'
                . '<input type="hidden" name="task" value="' . $backTask . '"><img src="images/back2.png" height=100></form></td>';
        
        $backTd = ' 
            

                        <form name="back" method=post action="">
                        <input type="hidden" name="task" value="' . $backTask . '">
                        </form>
        <td id="back" valign=middle class="back" 
                        onclick="postBack();" style="padding-top:10px;padding-left:19px;">
                        <font class="buttonText">Back</font>
           </td>';
        
        //$startOverTd = '<td width=100 align=right onclick="postHome();"><form name="startover" method="post" action""><input type=hidden name=task value=startPage><img src="images/exit2.png" height=100></form></td>';
        $startOverTd = '
            
               <form name="startover" method="post" action"">
               <input type=hidden name=task value=startPage>
               </form>
               <td id="exit" valign=middle class="exit" 
                    onclick="postHome();" style="padding-top:10px;padding-left:19px;">
                    Exit
               </td>';
    }
    
    
    
    
    
    $loggedInTable = '';
    if (!empty($_SESSION['loggedInTable'])){
        $loggedInTable = $_SESSION['loggedInTable'];
    } else {
        $loggedInTable = '';
    }
    
    
    
    
    
    
    
    
    
    $displayKiosk = '';
    $displayClient = '';
    
    if (!empty($_SESSION['kiosk'])) {
        $displayKiosk = $_SESSION['kiosk'];
    }
    if (!empty($_SESSION['client'])) {
        $displayClient = $_SESSION['client'];
    }
    
    
    
    $_SESSION['header'] = 
//            <!-- start URL ' . $_SESSION['startUrl'] . ' sessionKiosk ' . $displayKiosk . ' sessionClient ' . $displayClient .'--> <!-- Header -->
    '<table cellspacing=0 cellpadding=0 width="100%">
        <tr>
            <td colspan=6 height=25></td>
        </tr>
        <tr>
            <td align=left valign=top>' . $loggedInTable . '</td>
            <td>&nbsp;</td>    
           ' . $backTd . '
            
          

           <td width=40>&nbsp;</td>

          ' . $startOverTd . '
           
            <td width=30>&nbsp;</td>
         </tr>
    </table>
    ';
    
    
    
    
    
    
    /*$_SESSION['header'] = '<!-- start URL ' . $_SESSION['startUrl'] .'--> <!-- Header -->
    <table cellspacing=0 cellpadding=0 width="100%">
        <tr>
            <!--<td valign=top width=600><img src="site_files/mfaLogo.png" height="60"></td> -->
            <td align=left valign=top>' . $loggedInTable . '</td>
            <td>&nbsp;</td>    
            ' . $backTd . '
            <td width=100></td>
            ' . $startOverTd . '
         </tr>
    </table>
    ';*/
    
    
    
    
    
    
    

    /*<form name=startover method=post action="">
    <input type=hidden name=task value=startPage>
    </form>

    <form name="back" method="post" action="">
    <input type="hidden" name="task" value="' . $_SESSION['lastTask'] . '">
    </form>';*/
    
  if (!empty($_REQUEST['task'])){
    $_SESSION['lastTask'] = $_REQUEST['task'];
  }  
    
    
    
    
    
    $templateFile = 'html-template-salesTest.html';
    
    
    $file_handle = fopen($templateFile, "r");
    $result = '';
    while (!feof($file_handle)) {
       $result .= fgets($file_handle);
    }
    fclose($file_handle);
    
    $result = str_replace("~header~", $_SESSION['header'], $result);
    $result = str_replace("~title~", $_SESSION['title'], $result);
    //$result = str_replace("~content~", $content, $result);
    $result = str_replace("~metaTag~", $_SESSION['metaTag'], $result);
    $result = str_replace("~javaScript~", $_SESSION['javaScript'], $result);
    $result = str_replace("~topTable~", $_SESSION['topTable'], $result);
    $result = str_replace("~navigationTable~", $_SESSION['navigationTable'], $result);
    $result = str_replace("~backgroundCss~", $_SESSION['backgroundCss'], $result);
    
    exit($result);
    
}





?>

