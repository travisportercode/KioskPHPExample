<?php

//Tessitura Test, used to test Tessitura.php
//will contain test methods
//Started 12/10/14 CSN



// has to go before the session start.
foreach (glob('../../models/*.php') as $filename)
{
    include $filename;
}


include ('Tessitura.php');

//session_start();



//echo $_SESSION['account']->gatewayClientCode . '-' . $_SESSION['account']->gatewayUserName . '-' . $_SESSION['account']->gatewayPassword;
   

//exit('gatewayScript: ' . $_SESSION['account']->gatewayScript);
//
        //$data['websessionId'] = 'GUCHP51V14F2QAM410HOTA2YOO9CHYSVM40017V18EJ2YLOEF8Q1187PMHFGP003';
        $data['websessionId'] = '';
        //$data['startdate'] = '2015-05-07';
        //$data['enddate'] = '2015-05-07';
        
        $data['startdate'] = date('Y-m-d');
        $data['enddate'] = date('Y-m-d');
        
        
        
        $data['venueID'] = '-1';
        $data['modeofsale'] = '3';
        $data['businessunit'] = '-1';
        $data['sortstring'] = '';
        $data['keywords'] = '';
        $data['keywordandorstatement'] = '';
        $data['artistlastname'] = '';
        $data['season'] = '0';
        $data['packagetype'] = '0';
        $data['matchallperformances'] = 'True';
        $data['fulltext'] = '';
        $data['fulltexttype'] = '';
        $data['contenttype'] = '';
        $data['contenttypes'] = '';
        $data['performancecontenttypes'] = '';
        $data['packagenumbers'] = '';
        $data['performanceids'] = '';
        $data['seasonids'] = '';
        $data['includeseatcounts'] = 'False';
        $data['packageid'] = '98';
        $data['perfno'] = '';
        
        $data['IP'] = '184.191.224.22';
        
        $data['CountryIds'] = '1'; 
        
        $data['ccnumber'] = '';
        $data['phonenumber'] = '';
        $data['customerservicenumber'] = '0';
        $data['customernumber'] = '461161';//'0';
        $data['ordernumber'] = '317072';//'317090';//'317072';//'317072';
        $data['emailaddress'] = '';
        $data['lastname'] = 'Jon leland household  ';
        $data['contextcustomerno'] = '163230';
        $data['postalcode'] = '72956';
        $data['country'] = '1';
        $data['phonenumber'] = '';
        $data['print'] = 'Y';
        $data['mos'] = '0';//8;
        $data['renewals'] = 'N';
        $data['deliverymethod'] = '0';
        
        $data['headerdesign'] = '';
        $data['ticketdesign'] = '';
        $data['receipt'] = 'Y';
        $data['reprint'] = 'N';
        


// like index.php in 70392


//        $ccData['ccType'] = 'Visa';
//
//        $ccData['ccnum'] = '4111111111111111';
//        
//        $ccData['expmon'] = '06';
//        $ccData['expyear'] = '2017';
//        $ccData['expmmyy'] = '0617';
//        $ccData['expmmyyyy'] = '062017';
//        $ccData['amount'] =  '105.75';  // make sure there are only 2 decimal places
//        $ccData['name'] = 'John Smith';
//
//        $ccData['billbus'] = 'Smith & Sons';
//        $ccData['billfirst'] = 'John';
//        $ccData['billlast'] = 'Smith';
//
//        $ccData['billaddr1'] = '1200 Parkway'; //14151 provokes NotMatched AVS response
//        $ccData['billaddr2'] = '';
//        $ccData['billcity'] = 'Fort Smith';
//        $ccData['billstate'] = 'AR';
//        $ccData['billzip'] = '72903'; //20151 provokes NotMatched AVS response
//        $ccData['billcountry'] = 'USA';
//        
//        $ccData['shipname'] = 'Smith Furniture';
//        $ccData['shipaddr1'] = '105 Smith Lane';
//        $ccData['shipaddr2'] = '';
//        $ccData['shipcity'] = 'Fort Smith';
//        $ccData['shipstate'] = 'AR';
//        $ccData['shipzip'] = '72903';
//        $ccData['shipcountry'] = 'USA';
//        
//        $ccData['phone'] = '(479)783-1234';0
//        $ccData['email'] = 'johnsmith@smithsons.com';
//        $ccData['ipaddress'] = '10123457';
//        $ccData['cvv2'] = '123'; //123 provokes NotMatched for Visa or Mastercard
//        $ccData['comment'] = 'ECOMMERCE: 70392' ;
//        $ccData['cvvindicator'] ='Provided'; //chansouda
        
        
        //getNewSessionKey($data);
        //$returnList = doWillCall($data);
        //doWillCall($data);
        //getStateProvinceEx($data);
        //getConstituentsEx($data);
        //shiftContext($data);
        //getCityStateProvinceByPostalCode($data);
        //getOrdersEx($data);
        //getTicketPrintInformation($data);
        //getPackagesEx3($data);
        //getOrdersEx($data);
        //getPerformancesEx4($data);
        getPerformances($data); // not sure what is in data, but passing it anyway.
        //getPerformanceDetailWithDiscountingEx($data);
        
        
        //$approved = $returnList[0];
        //$orderId = $returnList[1];
        //$errorMessage = $returnList[2];
        //print_r($returnList);
        //exit("approved: " . $approved . " orderId: " . $orderId . " errorMessage: " . $errorMessage);


?>


