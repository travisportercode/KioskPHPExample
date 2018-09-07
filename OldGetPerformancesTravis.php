<?php

// has to go before the session start.

// include ECommerce/Tessitura/models/
foreach (glob('../models/*.php') as $filename)
{
    include $filename;
}

//include ('../Tessitura.php');

session_start();


$_SESSION['startDate'] = date('Y-m-d');
$_SESSION['endDate'] = date('Y-m-d');


// the timed events dissappear from the list after they start.
//$_SESSION['startDate'] = '2015-12-25';
//$_SESSION['endDate'] = '2015-12-25';

if (!empty($_REQUEST['date'])){
    $_SESSION['startDate'] = $_REQUEST['date'];
    $_SESSION['endDate'] = $_REQUEST['date'];
} 





$_SESSION['modeSale'] = '22'; // 7 is not kiosk, they will be providing a new one for the kiosks.

$_SESSION['soapUrl'] = 'http://tessapptest.mfa.org/TessituraWebAPI/Tessitura.asmx?WSDL';
$_SESSION['soapUrlDirect'] = 'http://tessapptest.mfa.org/TessituraWebAPI/Tessitura.asmx';
//$_SESSION['soapUrl'] = 'http://tessappprod.mfa.org/TessituraWebAPI/Tessitura.asmx?WSDL';

$environmentUrl = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
if (strpos($environmentUrl,"GetPerformancesTest") > 1){
    //include ('../TessituraApiTest.php');
    $_SESSION['salesCacheFile'] = '/ECommerceSessions/15864/Performances_' . date('Ymd') . '.txt';
} else {
    //include ('../TessituraApi.php');
    $_SESSION['salesCacheFile'] = '/ECommerceSessions/15864/Performances_' . date('Ymd') . '.txt';
}

$performances = getPerformances();

echo "Performances for : " . $_SESSION['startDate'] . '<br><hr><br>';


foreach ($performances as $onePerformance){
        echo "<b>Performance</b></br>";
        echo "<textarea rows=40 cols=100>";
        print_r($onePerformance);
        echo "</textarea>";
        echo "<hr>";    
}

//echo "<pre>";
//print_r($_SESSION);
//ECHO "</pre>";
//exit;

//Storing data in a text file
file_put_contents($_SESSION['salesCacheFile'], serialize($performances));

//Retreiving data from text file 
$performancesFromFile = array();
$performancesFromFile = unserialize(file_get_contents($_SESSION['salesCacheFile']));

echo "<b>From file: </b><br>";
echo "<textarea rows=50 cols=120>";
print_r($performancesFromFile);
echo "</textarea>";
exit();





function getPerformances(){
     $url = $_SESSION['soapUrlDirect'] . '/GetPerformancesEx?'
             . 'sWebSessionId=&'
             . 'sStartDate=' . $_SESSION['startDate'] . '&'
             . 'sEndDate=' . $_SESSION['endDate'] . '&'
             . 'iVenueID=-1&'
             . 'iModeOfSale=' . $_SESSION['modeSale'] . '&'
             . 'iBusinessUnit=-1&'
             . 'sSortString=&'
             . 'sKeywords=&'
             . 'cKeywordAndOrStatement=&'
             . 'sArtistLastName=';
    
    
    //$_SESSION['request']->updateApiCommunication("GetNewSessionKey Request: " . $url . "\n");
    
    //exit($url); // http://tessapptest.mfa.org/TessituraWebAPI/Tessitura.asmx/GetPerformancesEx?sStartDate=0&sEndDate=0&siVenueId=-1&iModeOfSale=13&iBusinessUnit=-1
    //ini_set('default_socket_timeout', 900);
    $response = (string) file_get_contents($url);
    
    
    //$responseData = print_r($response,true);
    //$_SESSION['request']->updateApiCommunication("GetNewSessionKey raw response: " . $responseData . "\n");
    
//    echo "url: " . $url . "<br><br>";
//    echo "Response: <br><br>";
//    echo "<textarea rows=100 cols=120>";
//    echo $response;
//    echo "</textarea><br>";
//    exit();
    
    $startPos = strpos($response, "<Performance");
    $endPos = strrpos($response, "</Performance>");
    //$_SESSION['sessionKey'] = substr($response,$startPos,$endPos - $startPos);
    //$response = "<Performances>\n" . substr($response,$startPos,$endPos-$startPos) . "</Performances>\n"; // get rid of everything else.
    $response = substr($response,$startPos,$endPos-$startPos) . "</Performance>"; // get rid of everything else.
    
    // now loop through each line. if '<Performance diff' is found, convert the entire line to '<Performance>'
    $responseLines = explode("\n",$response);
    //$response = '';
    $response = "<Performances>\n";
    foreach($responseLines as $oneResponseLine){
        if (strstr($oneResponseLine,"<Performance diff")){
            $response .= "<Performance>" . "\n";
        } else {
            $response .= $oneResponseLine;
        }
    }
    $response .= "\n</Performances>\n";
    
//    echo "Response after converted: <br><br>";
//    echo "<textarea rows=100 cols=120>";
//    echo $response;
//    echo "</textarea>";
    
    
    $simpleObjects = simplexml_load_string($response);
//    echo "<textarea rows=40 cols=100>";
//    print_r($simpleObjects);
//    echo "</textarea>";
//    exit;
    
//    $simpleObjectString = print_r($xml,true);
//    echo "<textarea rows=40 cols=100>";
//    echo $simpleObjectString;
//    echo "</textarea>";
//    exit;
   
    
    
    //echo "<textarea rows=40 cols=100>";
    //print_r($performances);
    //echo "</textarea>";
    //echo "\nperformances size: " . sizeof($performances);
    //exit;
    
    
    $performances = array();
    
    foreach($simpleObjects as $onePerformanceSimpleObject){
       // echo "<textarea rows=40 cols=100>";
       // print_r($onePerformanceSimpleObject);
       // echo "</textarea>";
       // exit;
        
        
        $performance = new Performance();
        $performance->perfNo = (string) $onePerformanceSimpleObject->perf_no;
        $performance->facilityDesc = (string) $onePerformanceSimpleObject->facility_desc;
        $performance->description = (string) $onePerformanceSimpleObject->description;
        $performance->perfDateTime = (string) $onePerformanceSimpleObject->perf_date;
        
        // put all the identifiable museum admisions here.
        if ($performance->description == 'Museum Admission'){
            $performance->mainCategory = 'ga'; 
            $performance->pricingGroup = 'Museum Admission'; // put in the performance description that the prices should appear under.
        } else if ($performance->description == 'Member Guest Pass'){
            $performance->mainCategory = 'ga';
            $performance->pricingGroup = 'Museum Admission'; // put in the performance description that this should appear under.
        } else {
            $performance->mainCategory = 'other';
            //$performance->pricingGroup = $performance->description; // put in the performance description that this should appear under.
            $words = explode("T",$performance->perfDateTime);
            $words2 = explode("-",$words[1]); // split the from and to time.
            $dateTime = strtotime($words[0] . $words2[0]);
            $timeFormatted = date('g:i a', $dateTime);
            $performance->pricingGroup = $performance->description . ' - ' . $timeFormatted;
            
            
            
        }
        
        
        $performance->rawPricesResponse = (string) getRawPricesResponse($onePerformanceSimpleObject->perf_no); // get all prices for this performance.
        $performance->prices = getPrices($onePerformanceSimpleObject->perf_no); // get all prices for this performance.
        
        // exclude certain performances
        if ($performance->description == 'MFA Guide'){
            echo "<b>'MFA Guide' has been excluded from the list.</b><br><br>";
        } else {
            // if I want to see the raw performance data.
            //echo "<b>onePerformanceSimpleObject</b></br>";
            //echo "<textarea rows=40 cols=100>";
            //print_r($onePerformanceSimpleObject);
            //echo "</textarea>";
            //echo "<hr>";
            $performance->rawPerformanceResponse =  print_r($onePerformanceSimpleObject,true);
            array_push($performances,$performance);
        }
       
    
    }
    
    
    
      
    
    
    //for ($i=0;$i<sizeof($performances);$i++){
    //    exit('performance no:' . $performance[$i]['perfNo']);
    //    $performances[$i]['prices'] = getPrices($performances[$i]['perfNo']); // get all prices for this performance.
   // }
    
    
    
    
    
    return $performances;
    
}



function getRawPricesResponse($performanceNo) {
    $url = $_SESSION['soapUrlDirect'] . '/GetPerformanceDetailWithDiscountingEx?'
            . 'SessionKey=' . '&'
            . 'iPerf_no=' . $performanceNo . '&'
            . 'iModeOfSale=' . $_SESSION['modeSale'] . '&'
            . 'sContentType=';
    $response = (string) file_get_contents($url);
  
   //     echo "<textarea rows=40 cols=100>";
   //     print_r($response);
   //     echo "</textarea>";
   //     exit;
    
    $startPos = strpos($response, "<Performance");
    $endPos = strrpos($response, "</AllPrice>");

    if ($startPos > -1 && $endPos > -1){
    	$response = substr($response,$startPos,$endPos-$startPos) . "</AllPrice>"; // get rid of everything else.
    } else {
	echo ('PRICES NOT FOUND FOR PERFORMANCE: ' . $performanceNo . "<br>");
	return null;
    }


    echo "<textarea rows=40 cols=100>";
    print_r($response);
    echo "</textarea>";
    exit;




 
    // now loop through each line. if '<Performance diff' is found, convert the entire line to '<Performance>'
    $responseLines = explode("\n",$response);
    //$response = '';
    $response = "<Detail>\n";
    foreach($responseLines as $oneResponseLine){
        if (strstr($oneResponseLine,"<Performance diff")){
            $response .= "<Performance>" . "\n";
        } else if (strstr($oneResponseLine,"<PriceType diffg")){
            $response .= "<PriceType>" . "\n";
        } else if (strstr($oneResponseLine,"<Price diffg")){
            $response .= "<Price>" . "\n";
        } else if (strstr($oneResponseLine,"<AllPrice diffg")){
            $response .= "<AllPrice>" . "\n";
        } else {
            $response .= $oneResponseLine;
        }
    }
    $response .= "\n</Detail>\n";
    
    
    $simpleObject = simplexml_load_string($response);
    
    $simpleObjectString = print_r($simpleObject,true);
    //echo "<textarea rows=40 cols=100>";
    //echo $simpleObjectString;
    //echo "</textarea>";
    //exit;
    
    
    return $simpleObjectString;
    
    
}



function getPrices($performanceNo) {
    $url = $_SESSION['soapUrlDirect'] . '/GetPerformanceDetailWithDiscountingEx?'
            . 'SessionKey=' . '&'
            . 'iPerf_no=' . $performanceNo . '&'
            . 'iModeOfSale=' . $_SESSION['modeSale'] . '&'
            . 'sContentType=';
    $response = (string) file_get_contents($url);
    //echo "url: " . $url . "<br><br>";
    //echo "Response: <br><br>";
    //echo "<textarea rows=100 cols=120>";
    //echo $response;
    //echo "</textarea><br>";
    //exit();
    
    
    
    $startPos = strpos($response, "<Performance");
    $endPos = strrpos($response, "</AllPrice>");
    $response = substr($response,$startPos,$endPos-$startPos) . "</AllPrice>"; // get rid of everything else.
    
    // now loop through each line. if '<Performance diff' is found, convert the entire line to '<Performance>'
    $responseLines = explode("\n",$response);
    //$response = '';
    $response = "<Detail>\n";
    foreach($responseLines as $oneResponseLine){
        if (strstr($oneResponseLine,"<Performance diff")){
            $response .= "<Performance>" . "\n";
        } else if (strstr($oneResponseLine,"<PriceType diffg")){
            $response .= "<PriceType>" . "\n";
        } else if (strstr($oneResponseLine,"<Price diffg")){
            $response .= "<Price>" . "\n";
        } else if (strstr($oneResponseLine,"<AllPrice diffg")){
            $response .= "<AllPrice>" . "\n";
        } else {
            $response .= $oneResponseLine;
        }
    }
    $response .= "\n</Detail>\n";
    
    
    
    //echo "Response after converted: <br><br>";
    //echo "<textarea rows=100 cols=120>";
    //echo $response;
    //echo "</textarea>";
    
    
    $simpleObject = simplexml_load_string($response);
    
    
    $simpleObjectString = print_r($simpleObject,true);
    //echo "<textarea rows=40 cols=100>";
    //echo $simpleObjectString;
    //echo "</textarea>";
    //exit;
    
    
    
    $prices = array();
 
    
    //$elementIdx =  0;
    foreach($simpleObject->AllPrice as $oneSimpleAllPrice){
        //$prices[$elementIdx]['originalDesc'] = (string) $oneSimpleObjectPriceType->description;
        //$prices[$elementIdx]['priceTypeDesc'] = (string) $oneSimpleAllPrice->price_type_desc;
        //$prices[$elementIdx]['price'] = sprintf ("%.2f", $oneSimpleAllPrice->price);
        //$prices[$elementIdx]['zoneNo'] = (string) $oneSimpleAllPrice->zone_no;
        //$prices[$elementIdx]['available'] = (string) $oneSimpleAllPrice->available;
        //$prices[$elementIdx]['priceType'] = (string) $oneSimpleAllPrice->price_type;
        
        $price = new Price();
        $price->description = (string) $oneSimpleAllPrice->price_type_desc;
        $price->availCount = (string) $oneSimpleAllPrice->avail_count;
        $price->price = sprintf ("%.2f", $oneSimpleAllPrice->price);
        $price->priceType = (string) $oneSimpleAllPrice->price_type;
        $price->zoneNo = (string) $oneSimpleAllPrice->zone_no;
        //echo "<pre>";
        //print_r($price);
        //echo "</pre>";
        //exit;
        
        //$elementIdx++;
        array_push($prices,$price);
    }
    
    // may need to get times here.
    //foreach($simpleObject->AllPrice as $oneSimpleObjectPriceType){
    //}
    
    
    return $prices;
    
}








function getPerformancesOld($data) {
    $client = new SoapClient($_SESSION['soapUrl']);
    
    $params = array
    (
       'sWebSessionId' => $data['websessionId'],
       'sStartDate' => $data['startdate'],
       'sEndDate' => $data['enddate'],
       'iVenueID' => $data['venueID'],
       'iModeOfSale' => $data['modeofsale'],
       'iBusinessUnit' => $data['businessunit'],
       'sSortString' => $data['sortstring'],
       'sKeywords' => $data['keywords'],
       'cKeywordAndOrStatement' => $data['keywordandorstatement'],
       'sArtistLastName' => $data['artistlastname'],
       'sFullText' => $data['fulltext'],
       'sFullTextTye' => $data['fulltexttype'],
       'sContentType' => $data['contenttype'],
       'sPerformanceIds' => $data['performanceids'],
       'sSeasonIds' => $data['seasonids'],
       'bIncludeSeatCounts' => $data['includeseatcounts']
    );
    
    $response=$client->GetPerformancesEx4($params); 
    
    
    
    
    
    $xml = simplexml_load_string($response->GetPerformancesEx4Result->any);
    
    
    echo "<textarea rows=40 cols=100>";
    $paramsString = print_r($params,true);
    echo $paramsString;
    echo "</textarea>";
    
    echo "<textarea rows=40 cols=100>";
    echo $xml;
    echo "</textarea>";
    exit;
    
    
    // we can assume that all performances exist at this point.
    for ($i=0;$i<sizeof($xml->GetPerformancesEx4Result->Performance);$i++){
        $_SESSION['performances'][$i]['description'] = (string) $xml->GetPerformancesEx4Result->Performance[$i]->description;
        $_SESSION['performances'][$i]['perfNo'] = (string) $xml->GetPerformancesEx4Result->Performance[$i]->perf_no;
        $_SESSION['performances'][$i]['prices'] = getPrices($_SESSION['performances'][$i]['perfNo'],$data ); // get all prices for this performance.
    }
//    echo "<pre>";
//    print_r($_SESSION['performances']);
//    echo "</pre>";
//    exit;   
    
  
}


function getPricesOld($performanceNo,$data) {
    $client = new SoapClient($_SESSION['soapUrl']);
    
    $params = array
    (
       'SessionKey' => $data['websessionId'],
       'iPerf_no' =>  $performanceNo,
       'iModeOfSale' => $data['modeofsale'],
       'sContentType' => $data['contenttype'],
    );
    
//    echo "<pre>";
//    echo "getPerformanceDetailWithDiscountingEx Params: \n";
//    print_r($params);
//    echo "</pre>";
    
    $response=$client->GetPerformanceDetailWithDiscountingEx($params); 
    
    $xml = simplexml_load_string($response->GetPerformanceDetailWithDiscountingExResult->any);
    
   
    echo "<pre>";
    echo "getPerformanceDetailWithDiscountingEx Response: \n";
    print_r($response);
    echo "</pre>";
    
    echo "<pre>";
    echo "getPerformanceDetailWithDiscountingEx Response Any: \n";
    print_r($response->GetPerformanceDetailWithDiscountingExResult->any);
    echo "</pre>";
    
    echo "<pre>";
    echo "xml: \n";
    print_r($xml);
    echo "</pre>";
  
    $prices = array();
    
 
    
    $j = 0;
    $elementIdx =  0;
    while (!empty($xml->GetPerformanceDetailWithDiscountingExResult->AllPrice[$j])){
   
        $temp = $xml->GetPerformanceDetailWithDiscountingExResult->AllPrice[$j]->description;
        
        
        if (stripos($temp,":") > -1){
            $prices[$elementIdx]['description'] = "Time";
            $prices[$elementIdx]['time'] = (string) $temp;
        } else {
            $prices[$elementIdx]['description'] = (string) $temp;
            $prices[$elementIdx]['time'] = "ALL DAY";
        }
        $prices[$elementIdx]['originalDesc'] = (string) $temp;
        $prices[$elementIdx]['priceTypeDesc'] = (string) $xml->GetPerformanceDetailWithDiscountingExResult->AllPrice[$j]->price_type_desc;
        $prices[$elementIdx]['price'] = sprintf ("%.2f", $xml->GetPerformanceDetailWithDiscountingExResult->AllPrice[$j]->price);
        $prices[$elementIdx]['zoneNo'] = (string) $xml->GetPerformanceDetailWithDiscountingExResult->AllPrice[$j]->zone_no;
        $prices[$elementIdx]['available'] = (string) $xml->GetPerformanceDetailWithDiscountingExResult->AllPrice[$j]->available;
        $prices[$elementIdx]['priceType'] = (string) $xml->GetPerformanceDetailWithDiscountingExResult->AllPrice[$j]->price_type;
        
        $elementIdx++;
        $j++;
    }
    
    return $prices;
    
}










/*
 * [performances] => Array
        (
            [0] => Array
                (
                    [description] => Museum Admission 2015
                    [perfNo] => 2751
                    [prices] => Array
                        (
                            [0] => Array
                                (
                                    [description] => GA
                                    [time] => ALL DAY
                                    [originalDesc] => GA
                                    [priceTypeDesc] => ADULT (18-64)
                                    [price] => 25.00
                                )

                            [1] => Array
                                (
                                    [description] => GA
                                    [time] => ALL DAY
                                    [originalDesc] => GA
                                    [priceTypeDesc] => SENIOR (65+)
                                    [price] => 22.00
                                )

                            [2] => Array
                                (
                                    [description] => GA
                                    [time] => ALL DAY
                                    [originalDesc] => GA
                                    [priceTypeDesc] => STUDENT w/ID
                                    [price] => 22.00
                                )

                            [3] => Array
                                (
                                    [description] => GA
                                    [time] => ALL DAY
                                    [originalDesc] => GA
                                    [priceTypeDesc] => MILITARY w/ID
                                    [price] => 19.00
                                )

                            [4] => Array
                                (
                                    [description] => GA
                                    [time] => ALL DAY
                                    [originalDesc] => GA
                                    [priceTypeDesc] => YOUTH (5-17)
                                    [price] => 16.00
                                )

                            [5] => Array
                                (
                                    [description] => GA
                                    [time] => ALL DAY
                                    [originalDesc] => GA
                                    [priceTypeDesc] => CHILD (0-4)
                                    [price] => 0.00
                                )

                            [6] => Array
                                (
                                    [description] => GA
                                    [time] => ALL DAY
                                    [originalDesc] => GA
                                    [priceTypeDesc] => Comp Voucher
                                    [price] => 0.00
                                )

                            [7] => Array
                                (
                                    [description] => GA
                                    [time] => ALL DAY
                                    [originalDesc] => GA
                                    [priceTypeDesc] => 2 Day Return
                                    [price] => 0.00
                                )

                            [8] => Array
                                (
                                    [description] => GA
                                    [time] => ALL DAY
                                    [originalDesc] => GA
                                    [priceTypeDesc] => Seattle Jazz Experience
                                    [price] => 5.00
                                )

                            [9] => Array
                                (
                                    [description] => GA
                                    [time] => ALL DAY
                                    [originalDesc] => GA
                                    [priceTypeDesc] => PocketPicks
                                    [price] => 18.00
                                )

                            [10] => Array
                                (
                                    [description] => GA
                                    [time] => ALL DAY
                                    [originalDesc] => GA
                                    [priceTypeDesc] => Adult Gift Redeem
                                    [price] => 20.00
                                )

                            [11] => Array
                                (
                                    [description] => GA
                                    [time] => ALL DAY
                                    [originalDesc] => GA
                                    [priceTypeDesc] => TeenTix
                                    [price] => 5.00
                                )

                            [12] => Array
                                (
                                    [description] => GA
                                    [time] => ALL DAY
                                    [originalDesc] => GA
                                    [priceTypeDesc] => Youth Gift Redeem
                                    [price] => 14.00
                                )

                            [13] => Array
                                (
                                    [description] => GA
                                    [time] => ALL DAY
                                    [originalDesc] => GA
                                    [priceTypeDesc] => TRAVEL PART. ADULT
                                    [price] => 16.00
                                )

                            [14] => Array
                                (
                                    [description] => GA
                                    [time] => ALL DAY
                                    [originalDesc] => GA
                                    [priceTypeDesc] => TRAVEL PART. YOUTH
                                    [price] => 11.20
                                )

                            [15] => Array
                                (
                                    [description] => GA
                                    [time] => ALL DAY
                                    [originalDesc] => GA
                                    [priceTypeDesc] => MEMBER Adult
                                    [price] => 0.00
                                )

                            [16] => Array
                                (
                                    [description] => GA
                                    [time] => ALL DAY
                                    [originalDesc] => GA
                                    [priceTypeDesc] => MEMBER Youth
                                    [price] => 0.00
                                )

                            [17] => Array
                                (
                                    [description] => GA
                                    [time] => ALL DAY
                                    [originalDesc] => GA
                                    [priceTypeDesc] => STAFF ADMIT
                                    [price] => 0.00
                                )

                            [18] => Array
                                (
                                    [description] => GA
                                    [time] => ALL DAY
                                    [originalDesc] => GA
                                    [priceTypeDesc] => Guest Comp
                                    [price] => 0.00
                                )

                            [19] => Array
                                (
                                    [description] => GA
                                    [time] => ALL DAY
                                    [originalDesc] => GA
                                    [priceTypeDesc] => SPL Tix Redeem
                                    [price] => 0.00
                                )

                            [20] => Array
                                (
                                    [description] => GA
                                    [time] => ALL DAY
                                    [originalDesc] => GA
                                    [priceTypeDesc] => Member For a Day
                                    [price] => 0.00
                                )

                            [21] => Array
                                (
                                    [description] => GA
                                    [time] => ALL DAY
                                    [originalDesc] => GA
                                    [priceTypeDesc] => PSC Employee
                                    [price] => 0.00
                                )

                            [22] => Array
                                (
                                    [description] => GA
                                    [time] => ALL DAY
                                    [originalDesc] => GA
                                    [priceTypeDesc] => ESL Student
                                    [price] => 16.00
                                )

                            [23] => Array
                                (
                                    [description] => GA
                                    [time] => ALL DAY
                                    [originalDesc] => GA
                                    [priceTypeDesc] => ESL Youth
                                    [price] => 13.00
                                )

                            [24] => Array
                                (
                                    [description] => GA
                                    [time] => ALL DAY
                                    [originalDesc] => GA
                                    [priceTypeDesc] => Guest STAR
                                    [price] => 5.00
                                )

                            [25] => Array
                                (
                                    [description] => GA
                                    [time] => ALL DAY
                                    [originalDesc] => GA
                                    [priceTypeDesc] => Teen Tix Plus Guest
                                    [price] => 5.00
                                )

                            [26] => Array
                                (
                                    [description] => GA
                                    [time] => ALL DAY
                                    [originalDesc] => GA
                                    [priceTypeDesc] => MS Prime Adult
                                    [price] => 6.00
                                )

                            [27] => Array
                                (
                                    [description] => GA
                                    [time] => ALL DAY
                                    [originalDesc] => GA
                                    [priceTypeDesc] => MS Prime Youth
                                    [price] => 6.00
                                )

                            [28] => Array
                                (
                                    [description] => GA
                                    [time] => ALL DAY
                                    [originalDesc] => GA
                                    [priceTypeDesc] => Adult Camp Upsell
                                    [price] => 5.00
                                )

                        )

                )

            [1] => Array
                (
                    [description] => Star Wars 2015
                    [perfNo] => 2758
                    [prices] => Array
                        (
                            [0] => Array
                                (
                                    [description] => Time
                                    [time] => 10:00:00 AM
                                    [originalDesc] => 10:00:00 AM
                                    [priceTypeDesc] => ADULT (18-64)
                                    [price] => 8.00
                                )

                            [1] => Array
                                (
                                    [description] => Time
                                    [time] => 10:20:00 AM
                                    [originalDesc] => 10:20:00 AM
                                    [priceTypeDesc] => ADULT (18-64)
                                    [price] => 8.00
                                )

                            [2] => Array
                                (
                                    [description] => Time
                                    [time] => 10:40:00 AM
                                    [originalDesc] => 10:40:00 AM
                                    [priceTypeDesc] => ADULT (18-64)
                                    [price] => 8.00
                                )

                            [3] => Array
                                (
                                    [description] => Time
                                    [time] => 11:00:00 AM
                                    [originalDesc] => 11:00:00 AM
                                    [priceTypeDesc] => ADULT (18-64)
                                    [price] => 8.00
                                )

                            [4] => Array
                                (
                                    [description] => Time
                                    [time] => 11:20:00 AM
                                    [originalDesc] => 11:20:00 AM
                                    [priceTypeDesc] => ADULT (18-64)
                                    [price] => 8.00
                                )

                            [5] => Array
                                (
                                    [description] => Time
                                    [time] => 11:40:00 AM
                                    [originalDesc] => 11:40:00 AM
                                    [priceTypeDesc] => ADULT (18-64)
                                    [price] => 8.00
                                )

                            [6] => Array
                                (
                                    [description] => Time
                                    [time] => 12:00:00 PM
                                    [originalDesc] => 12:00:00 PM
                                    [priceTypeDesc] => ADULT (18-64)
                                    [price] => 8.00
                                )

                            [7] => Array
                                (
                                    [description] => Time
                                    [time] => 12:20:00 PM
                                    [originalDesc] => 12:20:00 PM
                                    [priceTypeDesc] => ADULT (18-64)
                                    [price] => 8.00
                                )

                            [8] => Array
                                (
                                    [description] => Time
                                    [time] => 12:40:00 PM
                                    [originalDesc] => 12:40:00 PM
                                    [priceTypeDesc] => ADULT (18-64)
                                    [price] => 8.00
                                )

                            [9] => Array
                                (
                                    [description] => Time
                                    [time] => 01:00:00 PM
                                    [originalDesc] => 01:00:00 PM
                                    [priceTypeDesc] => ADULT (18-64)
                                    [price] => 8.00
                                )

                            [10] => Array
                                (
                                    [description] => Time
                                    [time] => 01:20:00 PM
                                    [originalDesc] => 01:20:00 PM
                                    [priceTypeDesc] => ADULT (18-64)
                                    [price] => 8.00
                                )

                            [11] => Array
                                (
                                    [description] => Time
                                    [time] => 01:40:00 PM
                                    [originalDesc] => 01:40:00 PM
                                    [priceTypeDesc] => ADULT (18-64)
                                    [price] => 8.00
                                )

                            [12] => Array
                                (
                                    [description] => Time
                                    [time] => 02:00:00 PM
                                    [originalDesc] => 02:00:00 PM
                                    [priceTypeDesc] => ADULT (18-64)
                                    [price] => 8.00
                                )

                            [13] => Array
                                (
                                    [description] => Time
                                    [time] => 02:20:00 PM
                                    [originalDesc] => 02:20:00 PM
                                    [priceTypeDesc] => ADULT (18-64)
                                    [price] => 8.00
                                )

                            [14] => Array
                                (
                                    [description] => Time
                                    [time] => 02:40:00 PM
                                    [originalDesc] => 02:40:00 PM
                                    [priceTypeDesc] => ADULT (18-64)
                                    [price] => 8.00
                                )

                            [15] => Array
                                (
                                    [description] => Time
                                    [time] => 03:00:00 PM
                                    [originalDesc] => 03:00:00 PM
                                    [priceTypeDesc] => ADULT (18-64)
                                    [price] => 8.00
                                )

                            [16] => Array
                                (
                                    [description] => Time
                                    [time] => 03:20:00 PM
                                    [originalDesc] => 03:20:00 PM
                                    [priceTypeDesc] => ADULT (18-64)
                                    [price] => 8.00
                                )

                            [17] => Array
                                (
                                    [description] => Time
                                    [time] => 03:40:00 PM
                                    [originalDesc] => 03:40:00 PM
                                    [priceTypeDesc] => ADULT (18-64)
                                    [price] => 8.00
                                )

                            [18] => Array
                                (
                                    [description] => Time
                                    [time] => 04:00:00 PM
                                    [originalDesc] => 04:00:00 PM
                                    [priceTypeDesc] => ADULT (18-64)
                                    [price] => 8.00
                                )

                            [19] => Array
                                (
                                    [description] => Time
                                    [time] => 04:20:00 PM
                                    [originalDesc] => 04:20:00 PM
                                    [priceTypeDesc] => ADULT (18-64)
                                    [price] => 8.00
                                )

                            [20] => Array
                                (
                                    [description] => Time
                                    [time] => 04:40:00 PM
                                    [originalDesc] => 04:40:00 PM
                                    [priceTypeDesc] => ADULT (18-64)
                                    [price] => 8.00
                                )

                            [21] => Array
                                (
                                    [description] => Time
                                    [time] => 05:00:00 PM
                                    [originalDesc] => 05:00:00 PM
                                    [priceTypeDesc] => ADULT (18-64)
                                    [price] => 8.00
                                )

                            [22] => Array
                                (
                                    [description] => Time
                                    [time] => 05:20:00 PM
                                    [originalDesc] => 05:20:00 PM
                                    [priceTypeDesc] => ADULT (18-64)
                                    [price] => 8.00
                                )

                            [23] => Array
                                (
                                    [description] => Time
                                    [time] => 05:40:00 PM
                                    [originalDesc] => 05:40:00 PM
                                    [priceTypeDesc] => ADULT (18-64)
                                    [price] => 8.00
                                )

                            [24] => Array
                                (
                                    [description] => Time
                                    [time] => 06:00:00 PM
                                    [originalDesc] => 06:00:00 PM
                                    [priceTypeDesc] => ADULT (18-64)
                                    [price] => 8.00
                                )

                            [25] => Array
                                (
                                    [description] => Time
                                    [time] => 06:20:00 PM
                                    [originalDesc] => 06:20:00 PM
                                    [priceTypeDesc] => ADULT (18-64)
                                    [price] => 8.00
                                )

                            [26] => Array
                                (
                                    [description] => Time
                                    [time] => 06:40:00 PM
                                    [originalDesc] => 06:40:00 PM
                                    [priceTypeDesc] => ADULT (18-64)
                                    [price] => 8.00
                                )

                            [27] => Array
                                (
                                    [description] => Time
                                    [time] => 07:00:00 PM
                                    [originalDesc] => 07:00:00 PM
                                    [priceTypeDesc] => ADULT (18-64)
                                    [price] => 8.00
                                )

                            [28] => Array
                                (
                                    [description] => Time
                                    [time] => 07:20:00 PM
                                    [originalDesc] => 07:20:00 PM
                                    [priceTypeDesc] => ADULT (18-64)
                                    [price] => 8.00
                                )

                            [29] => Array
                                (
                                    [description] => Time
                                    [time] => 07:40:00 PM
                                    [originalDesc] => 07:40:00 PM
                                    [priceTypeDesc] => ADULT (18-64)
                                    [price] => 8.00
                                )

                            [30] => Array
                                (
                                    [description] => Time
                                    [time] => 08:00:00 PM
                                    [originalDesc] => 08:00:00 PM
                                    [priceTypeDesc] => ADULT (18-64)
                                    [price] => 8.00
                                )

                            [31] => Array
                                (
                                    [description] => Time
                                    [time] => 08:20:00 PM
                                    [originalDesc] => 08:20:00 PM
                                    [priceTypeDesc] => ADULT (18-64)
                                    [price] => 8.00
                                )

                            [32] => Array
                                (
                                    [description] => Time
                                    [time] => 08:40:00 PM
                                    [originalDesc] => 08:40:00 PM
                                    [priceTypeDesc] => ADULT (18-64)
                                    [price] => 8.00
                                )

                            [33] => Array
                                (
                                    [description] => Time
                                    [time] => 09:00:00 PM
                                    [originalDesc] => 09:00:00 PM
                                    [priceTypeDesc] => ADULT (18-64)
                                    [price] => 0.00
                                )

                            [34] => Array
                                (
                                    [description] => Time
                                    [time] => 09:20:00 PM
                                    [originalDesc] => 09:20:00 PM
                                    [priceTypeDesc] => ADULT (18-64)
                                    [price] => 0.00
                                )

                            [35] => Array
                                (
                                    [description] => Time
                                    [time] => 09:40:00 PM
                                    [originalDesc] => 09:40:00 PM
                                    [priceTypeDesc] => ADULT (18-64)
                                    [price] => 0.00
                                )

                            [36] => Array
                                (
                                    [description] => Time
                                    [time] => 10:00:00 PM
                                    [originalDesc] => 10:00:00 PM
                                    [priceTypeDesc] => ADULT (18-64)
                                    [price] => 0.00
                                )

                            [37] => Array
                                (
                                    [description] => Time
                                    [time] => 10:20:00 PM
                                    [originalDesc] => 10:20:00 PM
                                    [priceTypeDesc] => ADULT (18-64)
                                    [price] => 0.00
                                )

                            [38] => Array
                                (
                                    [description] => Time
                                    [time] => 10:40:00 PM
                                    [originalDesc] => 10:40:00 PM
                                    [priceTypeDesc] => ADULT (18-64)
                                    [price] => 0.00
                                )

                            [39] => Array
                                (
                                    [description] => Time
                                    [time] => 11:00:00 PM
                                    [originalDesc] => 11:00:00 PM
                                    [priceTypeDesc] => ADULT (18-64)
                                    [price] => 0.00
                                )

                            [40] => Array
                                (
                                    [description] => Time
                                    [time] => 11:20:00 PM
                                    [originalDesc] => 11:20:00 PM
                                    [priceTypeDesc] => ADULT (18-64)
                                    [price] => 0.00
                                )

                            [41] => Array
                                (
                                    [description] => Time
                                    [time] => 11:40:00 PM
                                    [originalDesc] => 11:40:00 PM
                                    [priceTypeDesc] => ADULT (18-64)
                                    [price] => 0.00
                                )

                            [42] => Array
                                (
                                    [description] => Time
                                    [time] => 09:00:00 AM
                                    [originalDesc] => 09:00:00 AM
                                    [priceTypeDesc] => ADULT (18-64)
                                    [price] => 0.00
                                )

                            [43] => Array
                                (
                                    [description] => Time
                                    [time] => 10:00:00 AM
                                    [originalDesc] => 10:00:00 AM
                                    [priceTypeDesc] => SENIOR (65+)
                                    [price] => 8.00
                                )

                            [44] => Array
                                (
                                    [description] => Time
                                    [time] => 10:20:00 AM
                                    [originalDesc] => 10:20:00 AM
                                    [priceTypeDesc] => SENIOR (65+)
                                    [price] => 8.00
                                )

                            [45] => Array
                                (
                                    [description] => Time
                                    [time] => 10:40:00 AM
                                    [originalDesc] => 10:40:00 AM
                                    [priceTypeDesc] => SENIOR (65+)
                                    [price] => 8.00
                                )

                            [46] => Array
                                (
                                    [description] => Time
                                    [time] => 11:00:00 AM
                                    [originalDesc] => 11:00:00 AM
                                    [priceTypeDesc] => SENIOR (65+)
                                    [price] => 8.00
                                )

                            [47] => Array
                                (
                                    [description] => Time
                                    [time] => 11:20:00 AM
                                    [originalDesc] => 11:20:00 AM
                                    [priceTypeDesc] => SENIOR (65+)
                                    [price] => 8.00
                                )

                            [48] => Array
                                (
                                    [description] => Time
                                    [time] => 11:40:00 AM
                                    [originalDesc] => 11:40:00 AM
                                    [priceTypeDesc] => SENIOR (65+)
                                    [price] => 8.00
                                )

                            [49] => Array
                                (
                                    [description] => Time
                                    [time] => 12:00:00 PM
                                    [originalDesc] => 12:00:00 PM
                                    [priceTypeDesc] => SENIOR (65+)
                                    [price] => 8.00
                                )

                            [50] => Array
                                (
                                    [description] => Time
                                    [time] => 12:20:00 PM
                                    [originalDesc] => 12:20:00 PM
                                    [priceTypeDesc] => SENIOR (65+)
                                    [price] => 8.00
                                )

                            [51] => Array
                                (
                                    [description] => Time
                                    [time] => 12:40:00 PM
                                    [originalDesc] => 12:40:00 PM
                                    [priceTypeDesc] => SENIOR (65+)
                                    [price] => 8.00
                                )

                            [52] => Array
                                (
                                    [description] => Time
                                    [time] => 01:00:00 PM
                                    [originalDesc] => 01:00:00 PM
                                    [priceTypeDesc] => SENIOR (65+)
                                    [price] => 8.00
                                )

                            [53] => Array
                                (
                                    [description] => Time
                                    [time] => 01:20:00 PM
                                    [originalDesc] => 01:20:00 PM
                                    [priceTypeDesc] => SENIOR (65+)
                                    [price] => 8.00
                                )

                            [54] => Array
                                (
                                    [description] => Time
                                    [time] => 01:40:00 PM
                                    [originalDesc] => 01:40:00 PM
                                    [priceTypeDesc] => SENIOR (65+)
                                    [price] => 8.00
                                )

                            [55] => Array
                                (
                                    [description] => Time
                                    [time] => 02:00:00 PM
                                    [originalDesc] => 02:00:00 PM
                                    [priceTypeDesc] => SENIOR (65+)
                                    [price] => 8.00
                                )

                            [56] => Array
                                (
                                    [description] => Time
                                    [time] => 02:20:00 PM
                                    [originalDesc] => 02:20:00 PM
                                    [priceTypeDesc] => SENIOR (65+)
                                    [price] => 8.00
                                )

                            [57] => Array
                                (
                                    [description] => Time
                                    [time] => 02:40:00 PM
                                    [originalDesc] => 02:40:00 PM
                                    [priceTypeDesc] => SENIOR (65+)
                                    [price] => 8.00
                                )

                            [58] => Array
                                (
                                    [description] => Time
                                    [time] => 03:00:00 PM
                                    [originalDesc] => 03:00:00 PM
                                    [priceTypeDesc] => SENIOR (65+)
                                    [price] => 8.00
                                )

                            [59] => Array
                                (
                                    [description] => Time
                                    [time] => 03:20:00 PM
                                    [originalDesc] => 03:20:00 PM
                                    [priceTypeDesc] => SENIOR (65+)
                                    [price] => 8.00
                                )

                            [60] => Array
                                (
                                    [description] => Time
                                    [time] => 03:40:00 PM
                                    [originalDesc] => 03:40:00 PM
                                    [priceTypeDesc] => SENIOR (65+)
                                    [price] => 8.00
                                )

                            [61] => Array
                                (
                                    [description] => Time
                                    [time] => 04:00:00 PM
                                    [originalDesc] => 04:00:00 PM
                                    [priceTypeDesc] => SENIOR (65+)
                                    [price] => 8.00
                                )

                            [62] => Array
                                (
                                    [description] => Time
                                    [time] => 04:20:00 PM
                                    [originalDesc] => 04:20:00 PM
                                    [priceTypeDesc] => SENIOR (65+)
                                    [price] => 8.00
                                )

                            [63] => Array
                                (
                                    [description] => Time
                                    [time] => 04:40:00 PM
                                    [originalDesc] => 04:40:00 PM
                                    [priceTypeDesc] => SENIOR (65+)
                                    [price] => 8.00
                                )

                            [64] => Array
                                (
                                    [description] => Time
                                    [time] => 05:00:00 PM
                                    [originalDesc] => 05:00:00 PM
                                    [priceTypeDesc] => SENIOR (65+)
                                    [price] => 8.00
                                )

                            [65] => Array
                                (
                                    [description] => Time
                                    [time] => 05:20:00 PM
                                    [originalDesc] => 05:20:00 PM
                                    [priceTypeDesc] => SENIOR (65+)
                                    [price] => 8.00
                                )

                            [66] => Array
                                (
                                    [description] => Time
                                    [time] => 05:40:00 PM
                                    [originalDesc] => 05:40:00 PM
                                    [priceTypeDesc] => SENIOR (65+)
                                    [price] => 8.00
                                )

                            [67] => Array
                                (
                                    [description] => Time
                                    [time] => 06:00:00 PM
                                    [originalDesc] => 06:00:00 PM
                                    [priceTypeDesc] => SENIOR (65+)
                                    [price] => 8.00
                                )

                            [68] => Array
                                (
                                    [description] => Time
                                    [time] => 06:20:00 PM
                                    [originalDesc] => 06:20:00 PM
                                    [priceTypeDesc] => SENIOR (65+)
                                    [price] => 8.00
                                )

                            [69] => Array
                                (
                                    [description] => Time
                                    [time] => 06:40:00 PM
                                    [originalDesc] => 06:40:00 PM
                                    [priceTypeDesc] => SENIOR (65+)
                                    [price] => 8.00
                                )

                            [70] => Array
                                (
                                    [description] => Time
                                    [time] => 07:00:00 PM
                                    [originalDesc] => 07:00:00 PM
                                    [priceTypeDesc] => SENIOR (65+)
                                    [price] => 8.00
                                )

                            [71] => Array
                                (
                                    [description] => Time
                                    [time] => 07:20:00 PM
                                    [originalDesc] => 07:20:00 PM
                                    [priceTypeDesc] => SENIOR (65+)
                                    [price] => 8.00
                                )

                            [72] => Array
                                (
                                    [description] => Time
                                    [time] => 07:40:00 PM
                                    [originalDesc] => 07:40:00 PM
                                    [priceTypeDesc] => SENIOR (65+)
                                    [price] => 8.00
                                )

                            [73] => Array
                                (
                                    [description] => Time
                                    [time] => 08:00:00 PM
                                    [originalDesc] => 08:00:00 PM
                                    [priceTypeDesc] => SENIOR (65+)
                                    [price] => 8.00
                                )

                            [74] => Array
                                (
                                    [description] => Time
                                    [time] => 08:20:00 PM
                                    [originalDesc] => 08:20:00 PM
                                    [priceTypeDesc] => SENIOR (65+)
                                    [price] => 8.00
                                )

                            [75] => Array
                                (
                                    [description] => Time
                                    [time] => 08:40:00 PM
                                    [originalDesc] => 08:40:00 PM
                                    [priceTypeDesc] => SENIOR (65+)
                                    [price] => 8.00
                                )

                            [76] => Array
                                (
                                    [description] => Time
                                    [time] => 09:00:00 PM
                                    [originalDesc] => 09:00:00 PM
                                    [priceTypeDesc] => SENIOR (65+)
                                    [price] => 0.00
                                )

                            [77] => Array
                                (
                                    [description] => Time
                                    [time] => 09:20:00 PM
                                    [originalDesc] => 09:20:00 PM
                                    [priceTypeDesc] => SENIOR (65+)
                                    [price] => 0.00
                                )

                            [78] => Array
                                (
                                    [description] => Time
                                    [time] => 09:40:00 PM
                                    [originalDesc] => 09:40:00 PM
                                    [priceTypeDesc] => SENIOR (65+)
                                    [price] => 0.00
                                )

                            [79] => Array
                                (
                                    [description] => Time
                                    [time] => 10:00:00 PM
                                    [originalDesc] => 10:00:00 PM
                                    [priceTypeDesc] => SENIOR (65+)
                                    [price] => 0.00
                                )

                            [80] => Array
                                (
                                    [description] => Time
                                    [time] => 10:20:00 PM
                                    [originalDesc] => 10:20:00 PM
                                    [priceTypeDesc] => SENIOR (65+)
                                    [price] => 0.00
                                )

                            [81] => Array
                                (
                                    [description] => Time
                                    [time] => 10:40:00 PM
                                    [originalDesc] => 10:40:00 PM
                                    [priceTypeDesc] => SENIOR (65+)
                                    [price] => 0.00
                                )

                            [82] => Array
                                (
                                    [description] => Time
                                    [time] => 11:00:00 PM
                                    [originalDesc] => 11:00:00 PM
                                    [priceTypeDesc] => SENIOR (65+)
                                    [price] => 0.00
                                )

                            [83] => Array
                                (
                                    [description] => Time
                                    [time] => 11:20:00 PM
                                    [originalDesc] => 11:20:00 PM
                                    [priceTypeDesc] => SENIOR (65+)
                                    [price] => 0.00
                                )

                            [84] => Array
                                (
                                    [description] => Time
                                    [time] => 11:40:00 PM
                                    [originalDesc] => 11:40:00 PM
                                    [priceTypeDesc] => SENIOR (65+)
                                    [price] => 0.00
                                )

                            [85] => Array
                                (
                                    [description] => Time
                                    [time] => 09:00:00 AM
                                    [originalDesc] => 09:00:00 AM
                                    [priceTypeDesc] => SENIOR (65+)
                                    [price] => 0.00
                                )

                            [86] => Array
                                (
                                    [description] => Time
                                    [time] => 10:00:00 AM
                                    [originalDesc] => 10:00:00 AM
                                    [priceTypeDesc] => STUDENT w/ID
                                    [price] => 8.00
                                )

                            [87] => Array
                                (
                                    [description] => Time
                                    [time] => 10:20:00 AM
                                    [originalDesc] => 10:20:00 AM
                                    [priceTypeDesc] => STUDENT w/ID
                                    [price] => 8.00
                                )

                            [88] => Array
                                (
                                    [description] => Time
                                    [time] => 10:40:00 AM
                                    [originalDesc] => 10:40:00 AM
                                    [priceTypeDesc] => STUDENT w/ID
                                    [price] => 8.00
                                )

                            [89] => Array
                                (
                                    [description] => Time
                                    [time] => 11:00:00 AM
                                    [originalDesc] => 11:00:00 AM
                                    [priceTypeDesc] => STUDENT w/ID
                                    [price] => 8.00
                                )

                            [90] => Array
                                (
                                    [description] => Time
                                    [time] => 11:20:00 AM
                                    [originalDesc] => 11:20:00 AM
                                    [priceTypeDesc] => STUDENT w/ID
                                    [price] => 8.00
                                )

                            [91] => Array
                                (
                                    [description] => Time
                                    [time] => 11:40:00 AM
                                    [originalDesc] => 11:40:00 AM
                                    [priceTypeDesc] => STUDENT w/ID
                                    [price] => 8.00
                                )

                            [92] => Array
                                (
                                    [description] => Time
                                    [time] => 12:00:00 PM
                                    [originalDesc] => 12:00:00 PM
                                    [priceTypeDesc] => STUDENT w/ID
                                    [price] => 8.00
                                )

                            [93] => Array
                                (
                                    [description] => Time
                                    [time] => 12:20:00 PM
                                    [originalDesc] => 12:20:00 PM
                                    [priceTypeDesc] => STUDENT w/ID
                                    [price] => 8.00
                                )

                            [94] => Array
                                (
                                    [description] => Time
                                    [time] => 12:40:00 PM
                                    [originalDesc] => 12:40:00 PM
                                    [priceTypeDesc] => STUDENT w/ID
                                    [price] => 8.00
                                )

                            [95] => Array
                                (
                                    [description] => Time
                                    [time] => 01:00:00 PM
                                    [originalDesc] => 01:00:00 PM
                                    [priceTypeDesc] => STUDENT w/ID
                                    [price] => 8.00
                                )

                            [96] => Array
                                (
                                    [description] => Time
                                    [time] => 01:20:00 PM
                                    [originalDesc] => 01:20:00 PM
                                    [priceTypeDesc] => STUDENT w/ID
                                    [price] => 8.00
                                )

                            [97] => Array
                                (
                                    [description] => Time
                                    [time] => 01:40:00 PM
                                    [originalDesc] => 01:40:00 PM
                                    [priceTypeDesc] => STUDENT w/ID
                                    [price] => 8.00
                                )

                            [98] => Array
                                (
                                    [description] => Time
                                    [time] => 02:00:00 PM
                                    [originalDesc] => 02:00:00 PM
                                    [priceTypeDesc] => STUDENT w/ID
                                    [price] => 8.00
                                )

                            [99] => Array
                                (
                                    [description] => Time
                                    [time] => 02:20:00 PM
                                    [originalDesc] => 02:20:00 PM
                                    [priceTypeDesc] => STUDENT w/ID
                                    [price] => 8.00
                                )

                            [100] => Array
                                (
                                    [description] => Time
                                    [time] => 02:40:00 PM
                                    [originalDesc] => 02:40:00 PM
                                    [priceTypeDesc] => STUDENT w/ID
                                    [price] => 8.00
                                )

                            [101] => Array
                                (
                                    [description] => Time
                                    [time] => 03:00:00 PM
                                    [originalDesc] => 03:00:00 PM
                                    [priceTypeDesc] => STUDENT w/ID
                                    [price] => 8.00
                                )

                            [102] => Array
                                (
                                    [description] => Time
                                    [time] => 03:20:00 PM
                                    [originalDesc] => 03:20:00 PM
                                    [priceTypeDesc] => STUDENT w/ID
                                    [price] => 8.00
                                )

                            [103] => Array
                                (
                                    [description] => Time
                                    [time] => 03:40:00 PM
                                    [originalDesc] => 03:40:00 PM
                                    [priceTypeDesc] => STUDENT w/ID
                                    [price] => 8.00
                                )

                            [104] => Array
                                (
                                    [description] => Time
                                    [time] => 04:00:00 PM
                                    [originalDesc] => 04:00:00 PM
                                    [priceTypeDesc] => STUDENT w/ID
                                    [price] => 8.00
                                )

                            [105] => Array
                                (
                                    [description] => Time
                                    [time] => 04:20:00 PM
                                    [originalDesc] => 04:20:00 PM
                                    [priceTypeDesc] => STUDENT w/ID
                                    [price] => 8.00
                                )

                            [106] => Array
                                (
                                    [description] => Time
                                    [time] => 04:40:00 PM
                                    [originalDesc] => 04:40:00 PM
                                    [priceTypeDesc] => STUDENT w/ID
                                    [price] => 8.00
                                )

                            [107] => Array
                                (
                                    [description] => Time
                                    [time] => 05:00:00 PM
                                    [originalDesc] => 05:00:00 PM
                                    [priceTypeDesc] => STUDENT w/ID
                                    [price] => 8.00
                                )

                            [108] => Array
                                (
                                    [description] => Time
                                    [time] => 05:20:00 PM
                                    [originalDesc] => 05:20:00 PM
                                    [priceTypeDesc] => STUDENT w/ID
                                    [price] => 8.00
                                )

                            [109] => Array
                                (
                                    [description] => Time
                                    [time] => 05:40:00 PM
                                    [originalDesc] => 05:40:00 PM
                                    [priceTypeDesc] => STUDENT w/ID
                                    [price] => 8.00
                                )

                            [110] => Array
                                (
                                    [description] => Time
                                    [time] => 06:00:00 PM
                                    [originalDesc] => 06:00:00 PM
                                    [priceTypeDesc] => STUDENT w/ID
                                    [price] => 8.00
                                )

                            [111] => Array
                                (
                                    [description] => Time
                                    [time] => 06:20:00 PM
                                    [originalDesc] => 06:20:00 PM
                                    [priceTypeDesc] => STUDENT w/ID
                                    [price] => 8.00
                                )

                            [112] => Array
                                (
                                    [description] => Time
                                    [time] => 06:40:00 PM
                                    [originalDesc] => 06:40:00 PM
                                    [priceTypeDesc] => STUDENT w/ID
                                    [price] => 8.00
                                )

                            [113] => Array
                                (
                                    [description] => Time
                                    [time] => 07:00:00 PM
                                    [originalDesc] => 07:00:00 PM
                                    [priceTypeDesc] => STUDENT w/ID
                                    [price] => 8.00
                                )

                            [114] => Array
                                (
                                    [description] => Time
                                    [time] => 07:20:00 PM
                                    [originalDesc] => 07:20:00 PM
                                    [priceTypeDesc] => STUDENT w/ID
                                    [price] => 8.00
                                )

                            [115] => Array
                                (
                                    [description] => Time
                                    [time] => 07:40:00 PM
                                    [originalDesc] => 07:40:00 PM
                                    [priceTypeDesc] => STUDENT w/ID
                                    [price] => 8.00
                                )

                            [116] => Array
                                (
                                    [description] => Time
                                    [time] => 08:00:00 PM
                                    [originalDesc] => 08:00:00 PM
                                    [priceTypeDesc] => STUDENT w/ID
                                    [price] => 8.00
                                )

                            [117] => Array
                                (
                                    [description] => Time
                                    [time] => 08:20:00 PM
                                    [originalDesc] => 08:20:00 PM
                                    [priceTypeDesc] => STUDENT w/ID
                                    [price] => 8.00
                                )

                            [118] => Array
                                (
                                    [description] => Time
                                    [time] => 08:40:00 PM
                                    [originalDesc] => 08:40:00 PM
                                    [priceTypeDesc] => STUDENT w/ID
                                    [price] => 8.00
                                )

                            [119] => Array
                                (
                                    [description] => Time
                                    [time] => 09:00:00 PM
                                    [originalDesc] => 09:00:00 PM
                                    [priceTypeDesc] => STUDENT w/ID
                                    [price] => 0.00
                                )

                            [120] => Array
                                (
                                    [description] => Time
                                    [time] => 09:20:00 PM
                                    [originalDesc] => 09:20:00 PM
                                    [priceTypeDesc] => STUDENT w/ID
                                    [price] => 0.00
                                )

                            [121] => Array
                                (
                                    [description] => Time
                                    [time] => 09:40:00 PM
                                    [originalDesc] => 09:40:00 PM
                                    [priceTypeDesc] => STUDENT w/ID
                                    [price] => 0.00
                                )

                            [122] => Array
                                (
                                    [description] => Time
                                    [time] => 10:00:00 PM
                                    [originalDesc] => 10:00:00 PM
                                    [priceTypeDesc] => STUDENT w/ID
                                    [price] => 0.00
                                )

                            [123] => Array
                                (
                                    [description] => Time
                                    [time] => 10:20:00 PM
                                    [originalDesc] => 10:20:00 PM
                                    [priceTypeDesc] => STUDENT w/ID
                                    [price] => 0.00
                                )

                            [124] => Array
                                (
                                    [description] => Time
                                    [time] => 10:40:00 PM
                                    [originalDesc] => 10:40:00 PM
                                    [priceTypeDesc] => STUDENT w/ID
                                    [price] => 0.00
                                )

                            [125] => Array
                                (
                                    [description] => Time
                                    [time] => 11:00:00 PM
                                    [originalDesc] => 11:00:00 PM
                                    [priceTypeDesc] => STUDENT w/ID
                                    [price] => 0.00
                                )

                            [126] => Array
                                (
                                    [description] => Time
                                    [time] => 11:20:00 PM
                                    [originalDesc] => 11:20:00 PM
                                    [priceTypeDesc] => STUDENT w/ID
                                    [price] => 0.00
                                )

                            [127] => Array
                                (
                                    [description] => Time
                                    [time] => 11:40:00 PM
                                    [originalDesc] => 11:40:00 PM
                                    [priceTypeDesc] => STUDENT w/ID
                                    [price] => 0.00
                                )

                            [128] => Array
                                (
                                    [description] => Time
                                    [time] => 09:00:00 AM
                                    [originalDesc] => 09:00:00 AM
                                    [priceTypeDesc] => STUDENT w/ID
                                    [price] => 0.00
                                )

                            [129] => Array
                                (
                                    [description] => Time
                                    [time] => 10:00:00 AM
                                    [originalDesc] => 10:00:00 AM
                                    [priceTypeDesc] => MILITARY w/ID
                                    [price] => 8.00
                                )

                            [130] => Array
                                (
                                    [description] => Time
                                    [time] => 10:20:00 AM
                                    [originalDesc] => 10:20:00 AM
                                    [priceTypeDesc] => MILITARY w/ID
                                    [price] => 8.00
                                )

                            [131] => Array
                                (
                                    [description] => Time
                                    [time] => 10:40:00 AM
                                    [originalDesc] => 10:40:00 AM
                                    [priceTypeDesc] => MILITARY w/ID
                                    [price] => 8.00
                                )

                            [132] => Array
                                (
                                    [description] => Time
                                    [time] => 11:00:00 AM
                                    [originalDesc] => 11:00:00 AM
                                    [priceTypeDesc] => MILITARY w/ID
                                    [price] => 8.00
                                )

                            [133] => Array
                                (
                                    [description] => Time
                                    [time] => 11:20:00 AM
                                    [originalDesc] => 11:20:00 AM
                                    [priceTypeDesc] => MILITARY w/ID
                                    [price] => 8.00
                                )

                            [134] => Array
                                (
                                    [description] => Time
                                    [time] => 11:40:00 AM
                                    [originalDesc] => 11:40:00 AM
                                    [priceTypeDesc] => MILITARY w/ID
                                    [price] => 8.00
                                )

                            [135] => Array
                                (
                                    [description] => Time
                                    [time] => 12:00:00 PM
                                    [originalDesc] => 12:00:00 PM
                                    [priceTypeDesc] => MILITARY w/ID
                                    [price] => 8.00
                                )

                            [136] => Array
                                (
                                    [description] => Time
                                    [time] => 12:20:00 PM
                                    [originalDesc] => 12:20:00 PM
                                    [priceTypeDesc] => MILITARY w/ID
                                    [price] => 8.00
                                )

                            [137] => Array
                                (
                                    [description] => Time
                                    [time] => 12:40:00 PM
                                    [originalDesc] => 12:40:00 PM
                                    [priceTypeDesc] => MILITARY w/ID
                                    [price] => 8.00
                                )

                            [138] => Array
                                (
                                    [description] => Time
                                    [time] => 01:00:00 PM
                                    [originalDesc] => 01:00:00 PM
                                    [priceTypeDesc] => MILITARY w/ID
                                    [price] => 8.00
                                )

                            [139] => Array
                                (
                                    [description] => Time
                                    [time] => 01:20:00 PM
                                    [originalDesc] => 01:20:00 PM
                                    [priceTypeDesc] => MILITARY w/ID
                                    [price] => 8.00
                                )

                            [140] => Array
                                (
                                    [description] => Time
                                    [time] => 01:40:00 PM
                                    [originalDesc] => 01:40:00 PM
                                    [priceTypeDesc] => MILITARY w/ID
                                    [price] => 8.00
                                )

                            [141] => Array
                                (
                                    [description] => Time
                                    [time] => 02:00:00 PM
                                    [originalDesc] => 02:00:00 PM
                                    [priceTypeDesc] => MILITARY w/ID
                                    [price] => 8.00
                                )

                            [142] => Array
                                (
                                    [description] => Time
                                    [time] => 02:20:00 PM
                                    [originalDesc] => 02:20:00 PM
                                    [priceTypeDesc] => MILITARY w/ID
                                    [price] => 8.00
                                )

                            [143] => Array
                                (
                                    [description] => Time
                                    [time] => 02:40:00 PM
                                    [originalDesc] => 02:40:00 PM
                                    [priceTypeDesc] => MILITARY w/ID
                                    [price] => 8.00
                                )

                            [144] => Array
                                (
                                    [description] => Time
                                    [time] => 03:00:00 PM
                                    [originalDesc] => 03:00:00 PM
                                    [priceTypeDesc] => MILITARY w/ID
                                    [price] => 8.00
                                )

                            [145] => Array
                                (
                                    [description] => Time
                                    [time] => 03:20:00 PM
                                    [originalDesc] => 03:20:00 PM
                                    [priceTypeDesc] => MILITARY w/ID
                                    [price] => 8.00
                                )

                            [146] => Array
                                (
                                    [description] => Time
                                    [time] => 03:40:00 PM
                                    [originalDesc] => 03:40:00 PM
                                    [priceTypeDesc] => MILITARY w/ID
                                    [price] => 8.00
                                )

                            [147] => Array
                                (
                                    [description] => Time
                                    [time] => 04:00:00 PM
                                    [originalDesc] => 04:00:00 PM
                                    [priceTypeDesc] => MILITARY w/ID
                                    [price] => 8.00
                                )

                            [148] => Array
                                (
                                    [description] => Time
                                    [time] => 04:20:00 PM
                                    [originalDesc] => 04:20:00 PM
                                    [priceTypeDesc] => MILITARY w/ID
                                    [price] => 8.00
                                )

                            [149] => Array
                                (
                                    [description] => Time
                                    [time] => 04:40:00 PM
                                    [originalDesc] => 04:40:00 PM
                                    [priceTypeDesc] => MILITARY w/ID
                                    [price] => 8.00
                                )

                            [150] => Array
                                (
                                    [description] => Time
                                    [time] => 05:00:00 PM
                                    [originalDesc] => 05:00:00 PM
                                    [priceTypeDesc] => MILITARY w/ID
                                    [price] => 8.00
                                )

                            [151] => Array
                                (
                                    [description] => Time
                                    [time] => 05:20:00 PM
                                    [originalDesc] => 05:20:00 PM
                                    [priceTypeDesc] => MILITARY w/ID
                                    [price] => 8.00
                                )

                            [152] => Array
                                (
                                    [description] => Time
                                    [time] => 05:40:00 PM
                                    [originalDesc] => 05:40:00 PM
                                    [priceTypeDesc] => MILITARY w/ID
                                    [price] => 8.00
                                )

                            [153] => Array
                                (
                                    [description] => Time
                                    [time] => 06:00:00 PM
                                    [originalDesc] => 06:00:00 PM
                                    [priceTypeDesc] => MILITARY w/ID
                                    [price] => 8.00
                                )

                            [154] => Array
                                (
                                    [description] => Time
                                    [time] => 06:20:00 PM
                                    [originalDesc] => 06:20:00 PM
                                    [priceTypeDesc] => MILITARY w/ID
                                    [price] => 8.00
                                )

                            [155] => Array
                                (
                                    [description] => Time
                                    [time] => 06:40:00 PM
                                    [originalDesc] => 06:40:00 PM
                                    [priceTypeDesc] => MILITARY w/ID
                                    [price] => 8.00
                                )

                            [156] => Array
                                (
                                    [description] => Time
                                    [time] => 07:00:00 PM
                                    [originalDesc] => 07:00:00 PM
                                    [priceTypeDesc] => MILITARY w/ID
                                    [price] => 8.00
                                )

                            [157] => Array
                                (
                                    [description] => Time
                                    [time] => 07:20:00 PM
                                    [originalDesc] => 07:20:00 PM
                                    [priceTypeDesc] => MILITARY w/ID
                                    [price] => 8.00
                                )

                            [158] => Array
                                (
                                    [description] => Time
                                    [time] => 07:40:00 PM
                                    [originalDesc] => 07:40:00 PM
                                    [priceTypeDesc] => MILITARY w/ID
                                    [price] => 8.00
                                )

                            [159] => Array
                                (
                                    [description] => Time
                                    [time] => 08:00:00 PM
                                    [originalDesc] => 08:00:00 PM
                                    [priceTypeDesc] => MILITARY w/ID
                                    [price] => 8.00
                                )

                            [160] => Array
                                (
                                    [description] => Time
                                    [time] => 08:20:00 PM
                                    [originalDesc] => 08:20:00 PM
                                    [priceTypeDesc] => MILITARY w/ID
                                    [price] => 8.00
                                )

                            [161] => Array
                                (
                                    [description] => Time
                                    [time] => 08:40:00 PM
                                    [originalDesc] => 08:40:00 PM
                                    [priceTypeDesc] => MILITARY w/ID
                                    [price] => 8.00
                                )

                            [162] => Array
                                (
                                    [description] => Time
                                    [time] => 09:00:00 PM
                                    [originalDesc] => 09:00:00 PM
                                    [priceTypeDesc] => MILITARY w/ID
                                    [price] => 0.00
                                )

                            [163] => Array
                                (
                                    [description] => Time
                                    [time] => 09:20:00 PM
                                    [originalDesc] => 09:20:00 PM
                                    [priceTypeDesc] => MILITARY w/ID
                                    [price] => 0.00
                                )

                            [164] => Array
                                (
                                    [description] => Time
                                    [time] => 09:40:00 PM
                                    [originalDesc] => 09:40:00 PM
                                    [priceTypeDesc] => MILITARY w/ID
                                    [price] => 0.00
                                )

                            [165] => Array
                                (
                                    [description] => Time
                                    [time] => 10:00:00 PM
                                    [originalDesc] => 10:00:00 PM
                                    [priceTypeDesc] => MILITARY w/ID
                                    [price] => 0.00
                                )

                            [166] => Array
                                (
                                    [description] => Time
                                    [time] => 10:20:00 PM
                                    [originalDesc] => 10:20:00 PM
                                    [priceTypeDesc] => MILITARY w/ID
                                    [price] => 0.00
                                )

                            [167] => Array
                                (
                                    [description] => Time
                                    [time] => 10:40:00 PM
                                    [originalDesc] => 10:40:00 PM
                                    [priceTypeDesc] => MILITARY w/ID
                                    [price] => 0.00
                                )

                            [168] => Array
                                (
                                    [description] => Time
                                    [time] => 11:00:00 PM
                                    [originalDesc] => 11:00:00 PM
                                    [priceTypeDesc] => MILITARY w/ID
                                    [price] => 0.00
                                )

                            [169] => Array
                                (
                                    [description] => Time
                                    [time] => 11:20:00 PM
                                    [originalDesc] => 11:20:00 PM
                                    [priceTypeDesc] => MILITARY w/ID
                                    [price] => 0.00
                                )

                            [170] => Array
                                (
                                    [description] => Time
                                    [time] => 11:40:00 PM
                                    [originalDesc] => 11:40:00 PM
                                    [priceTypeDesc] => MILITARY w/ID
                                    [price] => 0.00
                                )

                            [171] => Array
                                (
                                    [description] => Time
                                    [time] => 09:00:00 AM
                                    [originalDesc] => 09:00:00 AM
                                    [priceTypeDesc] => MILITARY w/ID
                                    [price] => 0.00
                                )

                            [172] => Array
                                (
                                    [description] => Time
                                    [time] => 10:00:00 AM
                                    [originalDesc] => 10:00:00 AM
                                    [priceTypeDesc] => YOUTH (5-17)
                                    [price] => 8.00
                                )

                            [173] => Array
                                (
                                    [description] => Time
                                    [time] => 10:20:00 AM
                                    [originalDesc] => 10:20:00 AM
                                    [priceTypeDesc] => YOUTH (5-17)
                                    [price] => 8.00
                                )

                            [174] => Array
                                (
                                    [description] => Time
                                    [time] => 10:40:00 AM
                                    [originalDesc] => 10:40:00 AM
                                    [priceTypeDesc] => YOUTH (5-17)
                                    [price] => 8.00
                                )

                            [175] => Array
                                (
                                    [description] => Time
                                    [time] => 11:00:00 AM
                                    [originalDesc] => 11:00:00 AM
                                    [priceTypeDesc] => YOUTH (5-17)
                                    [price] => 8.00
                                )

                            [176] => Array
                                (
                                    [description] => Time
                                    [time] => 11:20:00 AM
                                    [originalDesc] => 11:20:00 AM
                                    [priceTypeDesc] => YOUTH (5-17)
                                    [price] => 8.00
                                )

                            [177] => Array
                                (
                                    [description] => Time
                                    [time] => 11:40:00 AM
                                    [originalDesc] => 11:40:00 AM
                                    [priceTypeDesc] => YOUTH (5-17)
                                    [price] => 8.00
                                )

                            [178] => Array
                                (
                                    [description] => Time
                                    [time] => 12:00:00 PM
                                    [originalDesc] => 12:00:00 PM
                                    [priceTypeDesc] => YOUTH (5-17)
                                    [price] => 8.00
                                )

                            [179] => Array
                                (
                                    [description] => Time
                                    [time] => 12:20:00 PM
                                    [originalDesc] => 12:20:00 PM
                                    [priceTypeDesc] => YOUTH (5-17)
                                    [price] => 8.00
                                )

                            [180] => Array
                                (
                                    [description] => Time
                                    [time] => 12:40:00 PM
                                    [originalDesc] => 12:40:00 PM
                                    [priceTypeDesc] => YOUTH (5-17)
                                    [price] => 8.00
                                )

                            [181] => Array
                                (
                                    [description] => Time
                                    [time] => 01:00:00 PM
                                    [originalDesc] => 01:00:00 PM
                                    [priceTypeDesc] => YOUTH (5-17)
                                    [price] => 8.00
                                )

                            [182] => Array
                                (
                                    [description] => Time
                                    [time] => 01:20:00 PM
                                    [originalDesc] => 01:20:00 PM
                                    [priceTypeDesc] => YOUTH (5-17)
                                    [price] => 8.00
                                )

                            [183] => Array
                                (
                                    [description] => Time
                                    [time] => 01:40:00 PM
                                    [originalDesc] => 01:40:00 PM
                                    [priceTypeDesc] => YOUTH (5-17)
                                    [price] => 8.00
                                )

                            [184] => Array
                                (
                                    [description] => Time
                                    [time] => 02:00:00 PM
                                    [originalDesc] => 02:00:00 PM
                                    [priceTypeDesc] => YOUTH (5-17)
                                    [price] => 8.00
                                )

                            [185] => Array
                                (
                                    [description] => Time
                                    [time] => 02:20:00 PM
                                    [originalDesc] => 02:20:00 PM
                                    [priceTypeDesc] => YOUTH (5-17)
                                    [price] => 8.00
                                )

                            [186] => Array
                                (
                                    [description] => Time
                                    [time] => 02:40:00 PM
                                    [originalDesc] => 02:40:00 PM
                                    [priceTypeDesc] => YOUTH (5-17)
                                    [price] => 8.00
                                )

                            [187] => Array
                                (
                                    [description] => Time
                                    [time] => 03:00:00 PM
                                    [originalDesc] => 03:00:00 PM
                                    [priceTypeDesc] => YOUTH (5-17)
                                    [price] => 8.00
                                )

                            [188] => Array
                                (
                                    [description] => Time
                                    [time] => 03:20:00 PM
                                    [originalDesc] => 03:20:00 PM
                                    [priceTypeDesc] => YOUTH (5-17)
                                    [price] => 8.00
                                )

                            [189] => Array
                                (
                                    [description] => Time
                                    [time] => 03:40:00 PM
                                    [originalDesc] => 03:40:00 PM
                                    [priceTypeDesc] => YOUTH (5-17)
                                    [price] => 8.00
                                )

                            [190] => Array
                                (
                                    [description] => Time
                                    [time] => 04:00:00 PM
                                    [originalDesc] => 04:00:00 PM
                                    [priceTypeDesc] => YOUTH (5-17)
                                    [price] => 8.00
                                )

                            [191] => Array
                                (
                                    [description] => Time
                                    [time] => 04:20:00 PM
                                    [originalDesc] => 04:20:00 PM
                                    [priceTypeDesc] => YOUTH (5-17)
                                    [price] => 8.00
                                )

                            [192] => Array
                                (
                                    [description] => Time
                                    [time] => 04:40:00 PM
                                    [originalDesc] => 04:40:00 PM
                                    [priceTypeDesc] => YOUTH (5-17)
                                    [price] => 8.00
                                )

                            [193] => Array
                                (
                                    [description] => Time
                                    [time] => 05:00:00 PM
                                    [originalDesc] => 05:00:00 PM
                                    [priceTypeDesc] => YOUTH (5-17)
                                    [price] => 8.00
                                )

                            [194] => Array
                                (
                                    [description] => Time
                                    [time] => 05:20:00 PM
                                    [originalDesc] => 05:20:00 PM
                                    [priceTypeDesc] => YOUTH (5-17)
                                    [price] => 8.00
                                )

                            [195] => Array
                                (
                                    [description] => Time
                                    [time] => 05:40:00 PM
                                    [originalDesc] => 05:40:00 PM
                                    [priceTypeDesc] => YOUTH (5-17)
                                    [price] => 8.00
                                )

                            [196] => Array
                                (
                                    [description] => Time
                                    [time] => 06:00:00 PM
                                    [originalDesc] => 06:00:00 PM
                                    [priceTypeDesc] => YOUTH (5-17)
                                    [price] => 8.00
                                )

                            [197] => Array
                                (
                                    [description] => Time
                                    [time] => 06:20:00 PM
                                    [originalDesc] => 06:20:00 PM
                                    [priceTypeDesc] => YOUTH (5-17)
                                    [price] => 8.00
                                )

                            [198] => Array
                                (
                                    [description] => Time
                                    [time] => 06:40:00 PM
                                    [originalDesc] => 06:40:00 PM
                                    [priceTypeDesc] => YOUTH (5-17)
                                    [price] => 8.00
                                )

                            [199] => Array
                                (
                                    [description] => Time
                                    [time] => 07:00:00 PM
                                    [originalDesc] => 07:00:00 PM
                                    [priceTypeDesc] => YOUTH (5-17)
                                    [price] => 8.00
                                )

                            [200] => Array
                                (
                                    [description] => Time
                                    [time] => 07:20:00 PM
                                    [originalDesc] => 07:20:00 PM
                                    [priceTypeDesc] => YOUTH (5-17)
                                    [price] => 8.00
                                )

                            [201] => Array
                                (
                                    [description] => Time
                                    [time] => 07:40:00 PM
                                    [originalDesc] => 07:40:00 PM
                                    [priceTypeDesc] => YOUTH (5-17)
                                    [price] => 8.00
                                )

                            [202] => Array
                                (
                                    [description] => Time
                                    [time] => 08:00:00 PM
                                    [originalDesc] => 08:00:00 PM
                                    [priceTypeDesc] => YOUTH (5-17)
                                    [price] => 8.00
                                )

                            [203] => Array
                                (
                                    [description] => Time
                                    [time] => 08:20:00 PM
                                    [originalDesc] => 08:20:00 PM
                                    [priceTypeDesc] => YOUTH (5-17)
                                    [price] => 8.00
                                )

                            [204] => Array
                                (
                                    [description] => Time
                                    [time] => 08:40:00 PM
                                    [originalDesc] => 08:40:00 PM
                                    [priceTypeDesc] => YOUTH (5-17)
                                    [price] => 8.00
                                )

                            [205] => Array
                                (
                                    [description] => Time
                                    [time] => 09:00:00 PM
                                    [originalDesc] => 09:00:00 PM
                                    [priceTypeDesc] => YOUTH (5-17)
                                    [price] => 0.00
                                )

                            [206] => Array
                                (
                                    [description] => Time
                                    [time] => 09:20:00 PM
                                    [originalDesc] => 09:20:00 PM
                                    [priceTypeDesc] => YOUTH (5-17)
                                    [price] => 0.00
                                )

                            [207] => Array
                                (
                                    [description] => Time
                                    [time] => 09:40:00 PM
                                    [originalDesc] => 09:40:00 PM
                                    [priceTypeDesc] => YOUTH (5-17)
                                    [price] => 0.00
                                )

                            [208] => Array
                                (
                                    [description] => Time
                                    [time] => 10:00:00 PM
                                    [originalDesc] => 10:00:00 PM
                                    [priceTypeDesc] => YOUTH (5-17)
                                    [price] => 0.00
                                )

                            [209] => Array
                                (
                                    [description] => Time
                                    [time] => 10:20:00 PM
                                    [originalDesc] => 10:20:00 PM
                                    [priceTypeDesc] => YOUTH (5-17)
                                    [price] => 0.00
                                )

                            [210] => Array
                                (
                                    [description] => Time
                                    [time] => 10:40:00 PM
                                    [originalDesc] => 10:40:00 PM
                                    [priceTypeDesc] => YOUTH (5-17)
                                    [price] => 0.00
                                )

                            [211] => Array
                                (
                                    [description] => Time
                                    [time] => 11:00:00 PM
                                    [originalDesc] => 11:00:00 PM
                                    [priceTypeDesc] => YOUTH (5-17)
                                    [price] => 0.00
                                )

                            [212] => Array
                                (
                                    [description] => Time
                                    [time] => 11:20:00 PM
                                    [originalDesc] => 11:20:00 PM
                                    [priceTypeDesc] => YOUTH (5-17)
                                    [price] => 0.00
                                )

                            [213] => Array
                                (
                                    [description] => Time
                                    [time] => 11:40:00 PM
                                    [originalDesc] => 11:40:00 PM
                                    [priceTypeDesc] => YOUTH (5-17)
                                    [price] => 0.00
                                )

                            [214] => Array
                                (
                                    [description] => Time
                                    [time] => 09:00:00 AM
                                    [originalDesc] => 09:00:00 AM
                                    [priceTypeDesc] => YOUTH (5-17)
                                    [price] => 0.00
                                )

                            [215] => Array
                                (
                                    [description] => Time
                                    [time] => 10:00:00 AM
                                    [originalDesc] => 10:00:00 AM
                                    [priceTypeDesc] => CHILD (0-4)
                                    [price] => 0.00
                                )

                            [216] => Array
                                (
                                    [description] => Time
                                    [time] => 10:20:00 AM
                                    [originalDesc] => 10:20:00 AM
                                    [priceTypeDesc] => CHILD (0-4)
                                    [price] => 0.00
                                )

                            [217] => Array
                                (
                                    [description] => Time
                                    [time] => 10:40:00 AM
                                    [originalDesc] => 10:40:00 AM
                                    [priceTypeDesc] => CHILD (0-4)
                                    [price] => 0.00
                                )

                            [218] => Array
                                (
                                    [description] => Time
                                    [time] => 11:00:00 AM
                                    [originalDesc] => 11:00:00 AM
                                    [priceTypeDesc] => CHILD (0-4)
                                    [price] => 0.00
                                )

                            [219] => Array
                                (
                                    [description] => Time
                                    [time] => 11:20:00 AM
                                    [originalDesc] => 11:20:00 AM
                                    [priceTypeDesc] => CHILD (0-4)
                                    [price] => 0.00
                                )

                            [220] => Array
                                (
                                    [description] => Time
                                    [time] => 11:40:00 AM
                                    [originalDesc] => 11:40:00 AM
                                    [priceTypeDesc] => CHILD (0-4)
                                    [price] => 0.00
                                )

                            [221] => Array
                                (
                                    [description] => Time
                                    [time] => 12:00:00 PM
                                    [originalDesc] => 12:00:00 PM
                                    [priceTypeDesc] => CHILD (0-4)
                                    [price] => 0.00
                                )

                            [222] => Array
                                (
                                    [description] => Time
                                    [time] => 12:20:00 PM
                                    [originalDesc] => 12:20:00 PM
                                    [priceTypeDesc] => CHILD (0-4)
                                    [price] => 0.00
                                )

                            [223] => Array
                                (
                                    [description] => Time
                                    [time] => 12:40:00 PM
                                    [originalDesc] => 12:40:00 PM
                                    [priceTypeDesc] => CHILD (0-4)
                                    [price] => 0.00
                                )

                            [224] => Array
                                (
                                    [description] => Time
                                    [time] => 01:00:00 PM
                                    [originalDesc] => 01:00:00 PM
                                    [priceTypeDesc] => CHILD (0-4)
                                    [price] => 0.00
                                )

                            [225] => Array
                                (
                                    [description] => Time
                                    [time] => 01:20:00 PM
                                    [originalDesc] => 01:20:00 PM
                                    [priceTypeDesc] => CHILD (0-4)
                                    [price] => 0.00
                                )

                            [226] => Array
                                (
                                    [description] => Time
                                    [time] => 01:40:00 PM
                                    [originalDesc] => 01:40:00 PM
                                    [priceTypeDesc] => CHILD (0-4)
                                    [price] => 0.00
                                )

                            [227] => Array
                                (
                                    [description] => Time
                                    [time] => 02:00:00 PM
                                    [originalDesc] => 02:00:00 PM
                                    [priceTypeDesc] => CHILD (0-4)
                                    [price] => 0.00
                                )

                            [228] => Array
                                (
                                    [description] => Time
                                    [time] => 02:20:00 PM
                                    [originalDesc] => 02:20:00 PM
                                    [priceTypeDesc] => CHILD (0-4)
                                    [price] => 0.00
                                )

                            [229] => Array
                                (
                                    [description] => Time
                                    [time] => 02:40:00 PM
                                    [originalDesc] => 02:40:00 PM
                                    [priceTypeDesc] => CHILD (0-4)
                                    [price] => 0.00
                                )

                            [230] => Array
                                (
                                    [description] => Time
                                    [time] => 03:00:00 PM
                                    [originalDesc] => 03:00:00 PM
                                    [priceTypeDesc] => CHILD (0-4)
                                    [price] => 0.00
                                )

                            [231] => Array
                                (
                                    [description] => Time
                                    [time] => 03:20:00 PM
                                    [originalDesc] => 03:20:00 PM
                                    [priceTypeDesc] => CHILD (0-4)
                                    [price] => 0.00
                                )

                            [232] => Array
                                (
                                    [description] => Time
                                    [time] => 03:40:00 PM
                                    [originalDesc] => 03:40:00 PM
                                    [priceTypeDesc] => CHILD (0-4)
                                    [price] => 0.00
                                )

                            [233] => Array
                                (
                                    [description] => Time
                                    [time] => 04:00:00 PM
                                    [originalDesc] => 04:00:00 PM
                                    [priceTypeDesc] => CHILD (0-4)
                                    [price] => 0.00
                                )

                            [234] => Array
                                (
                                    [description] => Time
                                    [time] => 04:20:00 PM
                                    [originalDesc] => 04:20:00 PM
                                    [priceTypeDesc] => CHILD (0-4)
                                    [price] => 0.00
                                )

                            [235] => Array
                                (
                                    [description] => Time
                                    [time] => 04:40:00 PM
                                    [originalDesc] => 04:40:00 PM
                                    [priceTypeDesc] => CHILD (0-4)
                                    [price] => 0.00
                                )

                            [236] => Array
                                (
                                    [description] => Time
                                    [time] => 05:00:00 PM
                                    [originalDesc] => 05:00:00 PM
                                    [priceTypeDesc] => CHILD (0-4)
                                    [price] => 0.00
                                )

                            [237] => Array
                                (
                                    [description] => Time
                                    [time] => 05:20:00 PM
                                    [originalDesc] => 05:20:00 PM
                                    [priceTypeDesc] => CHILD (0-4)
                                    [price] => 0.00
                                )

                            [238] => Array
                                (
                                    [description] => Time
                                    [time] => 05:40:00 PM
                                    [originalDesc] => 05:40:00 PM
                                    [priceTypeDesc] => CHILD (0-4)
                                    [price] => 0.00
                                )

                            [239] => Array
                                (
                                    [description] => Time
                                    [time] => 06:00:00 PM
                                    [originalDesc] => 06:00:00 PM
                                    [priceTypeDesc] => CHILD (0-4)
                                    [price] => 0.00
                                )

                            [240] => Array
                                (
                                    [description] => Time
                                    [time] => 06:20:00 PM
                                    [originalDesc] => 06:20:00 PM
                                    [priceTypeDesc] => CHILD (0-4)
                                    [price] => 0.00
                                )

                            [241] => Array
                                (
                                    [description] => Time
                                    [time] => 06:40:00 PM
                                    [originalDesc] => 06:40:00 PM
                                    [priceTypeDesc] => CHILD (0-4)
                                    [price] => 0.00
                                )

                            [242] => Array
                                (
                                    [description] => Time
                                    [time] => 07:00:00 PM
                                    [originalDesc] => 07:00:00 PM
                                    [priceTypeDesc] => CHILD (0-4)
                                    [price] => 0.00
                                )

                            [243] => Array
                                (
                                    [description] => Time
                                    [time] => 07:20:00 PM
                                    [originalDesc] => 07:20:00 PM
                                    [priceTypeDesc] => CHILD (0-4)
                                    [price] => 0.00
                                )

                            [244] => Array
                                (
                                    [description] => Time
                                    [time] => 07:40:00 PM
                                    [originalDesc] => 07:40:00 PM
                                    [priceTypeDesc] => CHILD (0-4)
                                    [price] => 0.00
                                )

                            [245] => Array
                                (
                                    [description] => Time
                                    [time] => 08:00:00 PM
                                    [originalDesc] => 08:00:00 PM
                                    [priceTypeDesc] => CHILD (0-4)
                                    [price] => 0.00
                                )

                            [246] => Array
                                (
                                    [description] => Time
                                    [time] => 08:20:00 PM
                                    [originalDesc] => 08:20:00 PM
                                    [priceTypeDesc] => CHILD (0-4)
                                    [price] => 0.00
                                )

                            [247] => Array
                                (
                                    [description] => Time
                                    [time] => 08:40:00 PM
                                    [originalDesc] => 08:40:00 PM
                                    [priceTypeDesc] => CHILD (0-4)
                                    [price] => 0.00
                                )

                            [248] => Array
                                (
                                    [description] => Time
                                    [time] => 09:00:00 PM
                                    [originalDesc] => 09:00:00 PM
                                    [priceTypeDesc] => CHILD (0-4)
                                    [price] => 0.00
                                )

                            [249] => Array
                                (
                                    [description] => Time
                                    [time] => 09:20:00 PM
                                    [originalDesc] => 09:20:00 PM
                                    [priceTypeDesc] => CHILD (0-4)
                                    [price] => 0.00
                                )

                            [250] => Array
                                (
                                    [description] => Time
                                    [time] => 09:40:00 PM
                                    [originalDesc] => 09:40:00 PM
                                    [priceTypeDesc] => CHILD (0-4)
                                    [price] => 0.00
                                )

                            [251] => Array
                                (
                                    [description] => Time
                                    [time] => 10:00:00 PM
                                    [originalDesc] => 10:00:00 PM
                                    [priceTypeDesc] => CHILD (0-4)
                                    [price] => 0.00
                                )

                            [252] => Array
                                (
                                    [description] => Time
                                    [time] => 10:20:00 PM
                                    [originalDesc] => 10:20:00 PM
                                    [priceTypeDesc] => CHILD (0-4)
                                    [price] => 0.00
                                )

                            [253] => Array
                                (
                                    [description] => Time
                                    [time] => 10:40:00 PM
                                    [originalDesc] => 10:40:00 PM
                                    [priceTypeDesc] => CHILD (0-4)
                                    [price] => 0.00
                                )

                            [254] => Array
                                (
                                    [description] => Time
                                    [time] => 11:00:00 PM
                                    [originalDesc] => 11:00:00 PM
                                    [priceTypeDesc] => CHILD (0-4)
                                    [price] => 0.00
                                )

                            [255] => Array
                                (
                                    [description] => Time
                                    [time] => 11:20:00 PM
                                    [originalDesc] => 11:20:00 PM
                                    [priceTypeDesc] => CHILD (0-4)
                                    [price] => 0.00
                                )

                            [256] => Array
                                (
                                    [description] => Time
                                    [time] => 11:40:00 PM
                                    [originalDesc] => 11:40:00 PM
                                    [priceTypeDesc] => CHILD (0-4)
                                    [price] => 0.00
                                )

                            [257] => Array
                                (
                                    [description] => Time
                                    [time] => 09:00:00 AM
                                    [originalDesc] => 09:00:00 AM
                                    [priceTypeDesc] => CHILD (0-4)
                                    [price] => 0.00
                                )

                            [258] => Array
                                (
                                    [description] => Time
                                    [time] => 10:00:00 AM
                                    [originalDesc] => 10:00:00 AM
                                    [priceTypeDesc] => MEMBER Adult
                                    [price] => 8.00
                                )

                            [259] => Array
                                (
                                    [description] => Time
                                    [time] => 10:20:00 AM
                                    [originalDesc] => 10:20:00 AM
                                    [priceTypeDesc] => MEMBER Adult
                                    [price] => 8.00
                                )

                            [260] => Array
                                (
                                    [description] => Time
                                    [time] => 10:40:00 AM
                                    [originalDesc] => 10:40:00 AM
                                    [priceTypeDesc] => MEMBER Adult
                                    [price] => 8.00
                                )

                            [261] => Array
                                (
                                    [description] => Time
                                    [time] => 11:00:00 AM
                                    [originalDesc] => 11:00:00 AM
                                    [priceTypeDesc] => MEMBER Adult
                                    [price] => 8.00
                                )

                            [262] => Array
                                (
                                    [description] => Time
                                    [time] => 11:20:00 AM
                                    [originalDesc] => 11:20:00 AM
                                    [priceTypeDesc] => MEMBER Adult
                                    [price] => 8.00
                                )

                            [263] => Array
                                (
                                    [description] => Time
                                    [time] => 11:40:00 AM
                                    [originalDesc] => 11:40:00 AM
                                    [priceTypeDesc] => MEMBER Adult
                                    [price] => 8.00
                                )

                            [264] => Array
                                (
                                    [description] => Time
                                    [time] => 12:00:00 PM
                                    [originalDesc] => 12:00:00 PM
                                    [priceTypeDesc] => MEMBER Adult
                                    [price] => 8.00
                                )

                            [265] => Array
                                (
                                    [description] => Time
                                    [time] => 12:20:00 PM
                                    [originalDesc] => 12:20:00 PM
                                    [priceTypeDesc] => MEMBER Adult
                                    [price] => 8.00
                                )

                            [266] => Array
                                (
                                    [description] => Time
                                    [time] => 12:40:00 PM
                                    [originalDesc] => 12:40:00 PM
                                    [priceTypeDesc] => MEMBER Adult
                                    [price] => 8.00
                                )

                            [267] => Array
                                (
                                    [description] => Time
                                    [time] => 01:00:00 PM
                                    [originalDesc] => 01:00:00 PM
                                    [priceTypeDesc] => MEMBER Adult
                                    [price] => 8.00
                                )

                            [268] => Array
                                (
                                    [description] => Time
                                    [time] => 01:20:00 PM
                                    [originalDesc] => 01:20:00 PM
                                    [priceTypeDesc] => MEMBER Adult
                                    [price] => 8.00
                                )

                            [269] => Array
                                (
                                    [description] => Time
                                    [time] => 01:40:00 PM
                                    [originalDesc] => 01:40:00 PM
                                    [priceTypeDesc] => MEMBER Adult
                                    [price] => 8.00
                                )

                            [270] => Array
                                (
                                    [description] => Time
                                    [time] => 02:00:00 PM
                                    [originalDesc] => 02:00:00 PM
                                    [priceTypeDesc] => MEMBER Adult
                                    [price] => 8.00
                                )

                            [271] => Array
                                (
                                    [description] => Time
                                    [time] => 02:20:00 PM
                                    [originalDesc] => 02:20:00 PM
                                    [priceTypeDesc] => MEMBER Adult
                                    [price] => 8.00
                                )

                            [272] => Array
                                (
                                    [description] => Time
                                    [time] => 02:40:00 PM
                                    [originalDesc] => 02:40:00 PM
                                    [priceTypeDesc] => MEMBER Adult
                                    [price] => 8.00
                                )

                            [273] => Array
                                (
                                    [description] => Time
                                    [time] => 03:00:00 PM
                                    [originalDesc] => 03:00:00 PM
                                    [priceTypeDesc] => MEMBER Adult
                                    [price] => 8.00
                                )

                            [274] => Array
                                (
                                    [description] => Time
                                    [time] => 03:20:00 PM
                                    [originalDesc] => 03:20:00 PM
                                    [priceTypeDesc] => MEMBER Adult
                                    [price] => 8.00
                                )

                            [275] => Array
                                (
                                    [description] => Time
                                    [time] => 03:40:00 PM
                                    [originalDesc] => 03:40:00 PM
                                    [priceTypeDesc] => MEMBER Adult
                                    [price] => 8.00
                                )

                            [276] => Array
                                (
                                    [description] => Time
                                    [time] => 04:00:00 PM
                                    [originalDesc] => 04:00:00 PM
                                    [priceTypeDesc] => MEMBER Adult
                                    [price] => 8.00
                                )

                            [277] => Array
                                (
                                    [description] => Time
                                    [time] => 04:20:00 PM
                                    [originalDesc] => 04:20:00 PM
                                    [priceTypeDesc] => MEMBER Adult
                                    [price] => 8.00
                                )

                            [278] => Array
                                (
                                    [description] => Time
                                    [time] => 04:40:00 PM
                                    [originalDesc] => 04:40:00 PM
                                    [priceTypeDesc] => MEMBER Adult
                                    [price] => 8.00
                                )

                            [279] => Array
                                (
                                    [description] => Time
                                    [time] => 05:00:00 PM
                                    [originalDesc] => 05:00:00 PM
                                    [priceTypeDesc] => MEMBER Adult
                                    [price] => 8.00
                                )

                            [280] => Array
                                (
                                    [description] => Time
                                    [time] => 05:20:00 PM
                                    [originalDesc] => 05:20:00 PM
                                    [priceTypeDesc] => MEMBER Adult
                                    [price] => 8.00
                                )

                            [281] => Array
                                (
                                    [description] => Time
                                    [time] => 05:40:00 PM
                                    [originalDesc] => 05:40:00 PM
                                    [priceTypeDesc] => MEMBER Adult
                                    [price] => 8.00
                                )

                            [282] => Array
                                (
                                    [description] => Time
                                    [time] => 06:00:00 PM
                                    [originalDesc] => 06:00:00 PM
                                    [priceTypeDesc] => MEMBER Adult
                                    [price] => 8.00
                                )

                            [283] => Array
                                (
                                    [description] => Time
                                    [time] => 06:20:00 PM
                                    [originalDesc] => 06:20:00 PM
                                    [priceTypeDesc] => MEMBER Adult
                                    [price] => 8.00
                                )

                            [284] => Array
                                (
                                    [description] => Time
                                    [time] => 06:40:00 PM
                                    [originalDesc] => 06:40:00 PM
                                    [priceTypeDesc] => MEMBER Adult
                                    [price] => 8.00
                                )

                            [285] => Array
                                (
                                    [description] => Time
                                    [time] => 07:00:00 PM
                                    [originalDesc] => 07:00:00 PM
                                    [priceTypeDesc] => MEMBER Adult
                                    [price] => 8.00
                                )

                            [286] => Array
                                (
                                    [description] => Time
                                    [time] => 07:20:00 PM
                                    [originalDesc] => 07:20:00 PM
                                    [priceTypeDesc] => MEMBER Adult
                                    [price] => 8.00
                                )

                            [287] => Array
                                (
                                    [description] => Time
                                    [time] => 07:40:00 PM
                                    [originalDesc] => 07:40:00 PM
                                    [priceTypeDesc] => MEMBER Adult
                                    [price] => 8.00
                                )

                            [288] => Array
                                (
                                    [description] => Time
                                    [time] => 08:00:00 PM
                                    [originalDesc] => 08:00:00 PM
                                    [priceTypeDesc] => MEMBER Adult
                                    [price] => 8.00
                                )

                            [289] => Array
                                (
                                    [description] => Time
                                    [time] => 08:20:00 PM
                                    [originalDesc] => 08:20:00 PM
                                    [priceTypeDesc] => MEMBER Adult
                                    [price] => 8.00
                                )

                            [290] => Array
                                (
                                    [description] => Time
                                    [time] => 08:40:00 PM
                                    [originalDesc] => 08:40:00 PM
                                    [priceTypeDesc] => MEMBER Adult
                                    [price] => 8.00
                                )

                            [291] => Array
                                (
                                    [description] => Time
                                    [time] => 09:00:00 PM
                                    [originalDesc] => 09:00:00 PM
                                    [priceTypeDesc] => MEMBER Adult
                                    [price] => 0.00
                                )

                            [292] => Array
                                (
                                    [description] => Time
                                    [time] => 09:20:00 PM
                                    [originalDesc] => 09:20:00 PM
                                    [priceTypeDesc] => MEMBER Adult
                                    [price] => 0.00
                                )

                            [293] => Array
                                (
                                    [description] => Time
                                    [time] => 09:40:00 PM
                                    [originalDesc] => 09:40:00 PM
                                    [priceTypeDesc] => MEMBER Adult
                                    [price] => 0.00
                                )

                            [294] => Array
                                (
                                    [description] => Time
                                    [time] => 10:00:00 PM
                                    [originalDesc] => 10:00:00 PM
                                    [priceTypeDesc] => MEMBER Adult
                                    [price] => 0.00
                                )

                            [295] => Array
                                (
                                    [description] => Time
                                    [time] => 10:20:00 PM
                                    [originalDesc] => 10:20:00 PM
                                    [priceTypeDesc] => MEMBER Adult
                                    [price] => 0.00
                                )

                            [296] => Array
                                (
                                    [description] => Time
                                    [time] => 10:40:00 PM
                                    [originalDesc] => 10:40:00 PM
                                    [priceTypeDesc] => MEMBER Adult
                                    [price] => 0.00
                                )

                            [297] => Array
                                (
                                    [description] => Time
                                    [time] => 11:00:00 PM
                                    [originalDesc] => 11:00:00 PM
                                    [priceTypeDesc] => MEMBER Adult
                                    [price] => 0.00
                                )

                            [298] => Array
                                (
                                    [description] => Time
                                    [time] => 11:20:00 PM
                                    [originalDesc] => 11:20:00 PM
                                    [priceTypeDesc] => MEMBER Adult
                                    [price] => 0.00
                                )

                            [299] => Array
                                (
                                    [description] => Time
                                    [time] => 11:40:00 PM
                                    [originalDesc] => 11:40:00 PM
                                    [priceTypeDesc] => MEMBER Adult
                                    [price] => 0.00
                                )

                            [300] => Array
                                (
                                    [description] => Time
                                    [time] => 09:00:00 AM
                                    [originalDesc] => 09:00:00 AM
                                    [priceTypeDesc] => MEMBER Adult
                                    [price] => 0.00
                                )

                            [301] => Array
                                (
                                    [description] => Time
                                    [time] => 10:00:00 AM
                                    [originalDesc] => 10:00:00 AM
                                    [priceTypeDesc] => MEMBER Youth
                                    [price] => 8.00
                                )

                            [302] => Array
                                (
                                    [description] => Time
                                    [time] => 10:20:00 AM
                                    [originalDesc] => 10:20:00 AM
                                    [priceTypeDesc] => MEMBER Youth
                                    [price] => 8.00
                                )

                            [303] => Array
                                (
                                    [description] => Time
                                    [time] => 10:40:00 AM
                                    [originalDesc] => 10:40:00 AM
                                    [priceTypeDesc] => MEMBER Youth
                                    [price] => 8.00
                                )

                            [304] => Array
                                (
                                    [description] => Time
                                    [time] => 11:00:00 AM
                                    [originalDesc] => 11:00:00 AM
                                    [priceTypeDesc] => MEMBER Youth
                                    [price] => 8.00
                                )

                            [305] => Array
                                (
                                    [description] => Time
                                    [time] => 11:20:00 AM
                                    [originalDesc] => 11:20:00 AM
                                    [priceTypeDesc] => MEMBER Youth
                                    [price] => 8.00
                                )

                            [306] => Array
                                (
                                    [description] => Time
                                    [time] => 11:40:00 AM
                                    [originalDesc] => 11:40:00 AM
                                    [priceTypeDesc] => MEMBER Youth
                                    [price] => 8.00
                                )

                            [307] => Array
                                (
                                    [description] => Time
                                    [time] => 12:00:00 PM
                                    [originalDesc] => 12:00:00 PM
                                    [priceTypeDesc] => MEMBER Youth
                                    [price] => 8.00
                                )

                            [308] => Array
                                (
                                    [description] => Time
                                    [time] => 12:20:00 PM
                                    [originalDesc] => 12:20:00 PM
                                    [priceTypeDesc] => MEMBER Youth
                                    [price] => 8.00
                                )

                            [309] => Array
                                (
                                    [description] => Time
                                    [time] => 12:40:00 PM
                                    [originalDesc] => 12:40:00 PM
                                    [priceTypeDesc] => MEMBER Youth
                                    [price] => 8.00
                                )

                            [310] => Array
                                (
                                    [description] => Time
                                    [time] => 01:00:00 PM
                                    [originalDesc] => 01:00:00 PM
                                    [priceTypeDesc] => MEMBER Youth
                                    [price] => 8.00
                                )

                            [311] => Array
                                (
                                    [description] => Time
                                    [time] => 01:20:00 PM
                                    [originalDesc] => 01:20:00 PM
                                    [priceTypeDesc] => MEMBER Youth
                                    [price] => 8.00
                                )

                            [312] => Array
                                (
                                    [description] => Time
                                    [time] => 01:40:00 PM
                                    [originalDesc] => 01:40:00 PM
                                    [priceTypeDesc] => MEMBER Youth
                                    [price] => 8.00
                                )

                            [313] => Array
                                (
                                    [description] => Time
                                    [time] => 02:00:00 PM
                                    [originalDesc] => 02:00:00 PM
                                    [priceTypeDesc] => MEMBER Youth
                                    [price] => 8.00
                                )

                            [314] => Array
                                (
                                    [description] => Time
                                    [time] => 02:20:00 PM
                                    [originalDesc] => 02:20:00 PM
                                    [priceTypeDesc] => MEMBER Youth
                                    [price] => 8.00
                                )

                            [315] => Array
                                (
                                    [description] => Time
                                    [time] => 02:40:00 PM
                                    [originalDesc] => 02:40:00 PM
                                    [priceTypeDesc] => MEMBER Youth
                                    [price] => 8.00
                                )

                            [316] => Array
                                (
                                    [description] => Time
                                    [time] => 03:00:00 PM
                                    [originalDesc] => 03:00:00 PM
                                    [priceTypeDesc] => MEMBER Youth
                                    [price] => 8.00
                                )

                            [317] => Array
                                (
                                    [description] => Time
                                    [time] => 03:20:00 PM
                                    [originalDesc] => 03:20:00 PM
                                    [priceTypeDesc] => MEMBER Youth
                                    [price] => 8.00
                                )

                            [318] => Array
                                (
                                    [description] => Time
                                    [time] => 03:40:00 PM
                                    [originalDesc] => 03:40:00 PM
                                    [priceTypeDesc] => MEMBER Youth
                                    [price] => 8.00
                                )

                            [319] => Array
                                (
                                    [description] => Time
                                    [time] => 04:00:00 PM
                                    [originalDesc] => 04:00:00 PM
                                    [priceTypeDesc] => MEMBER Youth
                                    [price] => 8.00
                                )

                            [320] => Array
                                (
                                    [description] => Time
                                    [time] => 04:20:00 PM
                                    [originalDesc] => 04:20:00 PM
                                    [priceTypeDesc] => MEMBER Youth
                                    [price] => 8.00
                                )

                            [321] => Array
                                (
                                    [description] => Time
                                    [time] => 04:40:00 PM
                                    [originalDesc] => 04:40:00 PM
                                    [priceTypeDesc] => MEMBER Youth
                                    [price] => 8.00
                                )

                            [322] => Array
                                (
                                    [description] => Time
                                    [time] => 05:00:00 PM
                                    [originalDesc] => 05:00:00 PM
                                    [priceTypeDesc] => MEMBER Youth
                                    [price] => 8.00
                                )

                            [323] => Array
                                (
                                    [description] => Time
                                    [time] => 05:20:00 PM
                                    [originalDesc] => 05:20:00 PM
                                    [priceTypeDesc] => MEMBER Youth
                                    [price] => 8.00
                                )

                            [324] => Array
                                (
                                    [description] => Time
                                    [time] => 05:40:00 PM
                                    [originalDesc] => 05:40:00 PM
                                    [priceTypeDesc] => MEMBER Youth
                                    [price] => 8.00
                                )

                            [325] => Array
                                (
                                    [description] => Time
                                    [time] => 06:00:00 PM
                                    [originalDesc] => 06:00:00 PM
                                    [priceTypeDesc] => MEMBER Youth
                                    [price] => 8.00
                                )

                            [326] => Array
                                (
                                    [description] => Time
                                    [time] => 06:20:00 PM
                                    [originalDesc] => 06:20:00 PM
                                    [priceTypeDesc] => MEMBER Youth
                                    [price] => 8.00
                                )

                            [327] => Array
                                (
                                    [description] => Time
                                    [time] => 06:40:00 PM
                                    [originalDesc] => 06:40:00 PM
                                    [priceTypeDesc] => MEMBER Youth
                                    [price] => 8.00
                                )

                            [328] => Array
                                (
                                    [description] => Time
                                    [time] => 07:00:00 PM
                                    [originalDesc] => 07:00:00 PM
                                    [priceTypeDesc] => MEMBER Youth
                                    [price] => 8.00
                                )

                            [329] => Array
                                (
                                    [description] => Time
                                    [time] => 07:20:00 PM
                                    [originalDesc] => 07:20:00 PM
                                    [priceTypeDesc] => MEMBER Youth
                                    [price] => 8.00
                                )

                            [330] => Array
                                (
                                    [description] => Time
                                    [time] => 07:40:00 PM
                                    [originalDesc] => 07:40:00 PM
                                    [priceTypeDesc] => MEMBER Youth
                                    [price] => 8.00
                                )

                            [331] => Array
                                (
                                    [description] => Time
                                    [time] => 08:00:00 PM
                                    [originalDesc] => 08:00:00 PM
                                    [priceTypeDesc] => MEMBER Youth
                                    [price] => 8.00
                                )

                            [332] => Array
                                (
                                    [description] => Time
                                    [time] => 08:20:00 PM
                                    [originalDesc] => 08:20:00 PM
                                    [priceTypeDesc] => MEMBER Youth
                                    [price] => 8.00
                                )

                            [333] => Array
                                (
                                    [description] => Time
                                    [time] => 08:40:00 PM
                                    [originalDesc] => 08:40:00 PM
                                    [priceTypeDesc] => MEMBER Youth
                                    [price] => 8.00
                                )

                            [334] => Array
                                (
                                    [description] => Time
                                    [time] => 09:00:00 PM
                                    [originalDesc] => 09:00:00 PM
                                    [priceTypeDesc] => MEMBER Youth
                                    [price] => 0.00
                                )

                            [335] => Array
                                (
                                    [description] => Time
                                    [time] => 09:20:00 PM
                                    [originalDesc] => 09:20:00 PM
                                    [priceTypeDesc] => MEMBER Youth
                                    [price] => 0.00
                                )

                            [336] => Array
                                (
                                    [description] => Time
                                    [time] => 09:40:00 PM
                                    [originalDesc] => 09:40:00 PM
                                    [priceTypeDesc] => MEMBER Youth
                                    [price] => 0.00
                                )

                            [337] => Array
                                (
                                    [description] => Time
                                    [time] => 10:00:00 PM
                                    [originalDesc] => 10:00:00 PM
                                    [priceTypeDesc] => MEMBER Youth
                                    [price] => 0.00
                                )

                            [338] => Array
                                (
                                    [description] => Time
                                    [time] => 10:20:00 PM
                                    [originalDesc] => 10:20:00 PM
                                    [priceTypeDesc] => MEMBER Youth
                                    [price] => 0.00
                                )

                            [339] => Array
                                (
                                    [description] => Time
                                    [time] => 10:40:00 PM
                                    [originalDesc] => 10:40:00 PM
                                    [priceTypeDesc] => MEMBER Youth
                                    [price] => 0.00
                                )

                            [340] => Array
                                (
                                    [description] => Time
                                    [time] => 11:00:00 PM
                                    [originalDesc] => 11:00:00 PM
                                    [priceTypeDesc] => MEMBER Youth
                                    [price] => 0.00
                                )

                            [341] => Array
                                (
                                    [description] => Time
                                    [time] => 11:20:00 PM
                                    [originalDesc] => 11:20:00 PM
                                    [priceTypeDesc] => MEMBER Youth
                                    [price] => 0.00
                                )

                            [342] => Array
                                (
                                    [description] => Time
                                    [time] => 11:40:00 PM
                                    [originalDesc] => 11:40:00 PM
                                    [priceTypeDesc] => MEMBER Youth
                                    [price] => 0.00
                                )

                            [343] => Array
                                (
                                    [description] => Time
                                    [time] => 09:00:00 AM
                                    [originalDesc] => 09:00:00 AM
                                    [priceTypeDesc] => MEMBER Youth
                                    [price] => 0.00
                                )

                        )

                )

        )
 */


?>

