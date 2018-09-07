<?php

// ALL FUTURE METHODS SHOULD USE CURL - SEE CHECKOUTEX4. IF WE USE FILE_GET_CONTENTS, WE ONLY GET BACK AN INTERNAL SERVER ERROR. 
// IF WE USE THE SOAP CLIENT, THE ERRORS ARE SHOWN IN THE BROWSER AND WE CAN'T CAPTURE THEM.
// IF WE USE CURL, WE GET THE ERROR BACK IN THE RESPONSE - PERFECT!!!






//function clearCart() {
//    //$url = $_SESSION['soapUrlDirect'] . "/GetNewSessionKeyEx?sIP=" . $_SESSION['loginData']['IP'] . "&iBusinessUnit=0";
//    $url = $_SESSION['soapUrlDirect'] . "/ClearCart?sSessionKey=" . $_SESSION['sessionKey'] . '&iOrderNumber=' . $_SESSION['orderNo'];
//    ini_set('default_socket_timeout', 900);
//    exit('url: ' . $url);
//    
//    
//    $response = (string) file_get_contents($url);
//    dumpinfo($url, $response);
//    //$startPos = strpos($response, "tessiturasoftware") + 24;
//    //$endPos = strpos($response, "string>") - 2;
//    //$_SESSION['sessionKey'] = substr($response,$startPos,$endPos - $startPos);
//    
//}


function clearCart() {
    
        $ch = curl_init(); 
         
        $url = $_SESSION['soapUrlDirect'] . "/ClearCart?" . 
            "sSessionKey=" . $_SESSION['sessionKey'] . 
            "&iOrderNumber=" . $_SESSION['orderNo'];
        
        curl_setopt($ch, CURLOPT_URL, $url);  // was "example.com"
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
        $response = curl_exec($ch); 
        curl_close($ch);  
//        echo "session orderNo: " . $_SESSION['orderNo'];
        dumpInfo($url, $response); // have not yet seen a valid response.
        // if response is blank, everything is good.
//        if (strlen($response > 3)){
//            return false;
//        } else {
//            return true;
//        }
        
}




function dumpInfo($url,$response){
    
    echo "request: <br>";
    echo "<textarea rows=30 cols=120>" . $url . '</textarea><br><br>';
    
    echo "response: <br>";
    echo "<textarea rows=30 cols=120>" . $response . '</textarea>';
    exit;
    
}    



function logOut() {
    
    
    if (!empty($_SESSION['sessionKey'])){
    
        $url = $_SESSION['soapUrlDirect'] . "/Logout?sSessionKey=" . $_SESSION['sessionKey'];
        ini_set('default_socket_timeout', 300);
        $response = (string) file_get_contents($url);
        //dumpInfo($url,$response);
        //exit;

        // returns a blank response.
        
        if (!empty($response)){
            echo ('error logging out: ');
            dumpInfo($url,$response);
        }
        
        //$startPos = strpos($response, "tessiturasoftware");
        //$loggedIn = 'false';
        //if ($startPos > -1){
        //    $startPos += 24;
        //    $endPos = strpos($response, "boolean>") - 2;
        //    $loggedIn = substr($response,$startPos,$endPos - $startPos);
       // }
        //echo "loggedIn: " . $loggedIn;

        //exit;
    }
    return;
    
}



function logoutOld($data) {
    //$client = new SoapClient("http://dbradp6.tessituranetworkramp.com/wwl1api/tessitura.asmx?WSDL");
    $client = new SoapClient("https://tesstestgw1.empmuseum.org/tessitura.asmx?WSDL");
    
    $params = array( 
           'sSessionKey' => $_SESSION['sessionKey']
    );
    
    $response=$client->Logout($params); 
    
    $result = $response->LogoutResult;
    
    echo "<pre>";
    echo "logout Params: \n";
    print_r($params);
    echo "</pre>";
   
   
    echo "<pre>";
    echo "logout Response: \n";
    print_r($response);
    echo "</pre>";
    
    echo "<pre>";
    echo "logout: \n";
    print_r($result);
    echo "</pre>";
    
}


function loggedIn() {
    $ch = curl_init(); 
    $url = $_SESSION['soapUrlDirect'] . "/LoggedIn?sSessionKey=" . $_SESSION['sessionKey'];
    $_SESSION['request']->updateApiCommunication("LoggedIn request: " . $url . "\n");    
    ini_set('default_socket_timeout', 900);
//    $response = (string) file_get_contents($url);
    
    curl_setopt($ch, CURLOPT_URL, $url);  // was "example.com"
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
    $response = curl_exec($ch); 
    curl_close($ch); 
    //dumpInfo($url,$response);
    $startPos = strpos($response, "tessiturasoftware");
    $loggedIn = 'false';
    if ($startPos > -1){
        $startPos += 24;
        $endPos = strpos($response, "boolean>") - 2;
        $loggedIn = substr($response,$startPos,$endPos - $startPos);
    }
    $_SESSION['request']->updateApiCommunication("LoggedIn response: " . $response . "\n");   
    //echo "loggedIn: " . $loggedIn;
    
    //exit;
    return $loggedIn;
    
}


function getLoggedInConstituentId(){
    $url = $_SESSION['soapUrlDirect'] . "/LoginInfo?sSessionKey=" . $_SESSION['sessionKey'];
    ini_set('default_socket_timeout', 900);
    $response = (string) file_get_contents($url);
    //dumpInfo($url,$response);
    
    
    // the response contains customer_no, MOS (mode of sale), promotion_code, status, OriginalMOS
    // 1281884 is the kiosk anonymous user.
    
    
    
    
    
    
    /*<DataSet xmlns="http://tessiturasoftware.com/">
        <xs:schema xmlns="" xmlns:xs="http://www.w3.org/2001/XMLSchema" xmlns:msdata="urn:schemas-microsoft-com:xml-msdata" id="LoginInfoResults">
        <xs:element name="LoginInfoResults" msdata:IsDataSet="true" msdata:UseCurrentLocale="true">
        <xs:complexType>
        <xs:choice minOccurs="0" maxOccurs="unbounded">
        <xs:element name="LoginInformation">
        <xs:complexType>
        <xs:sequence>
        <xs:element name="customer_no" type="xs:int" minOccurs="0"/>
        <xs:element name="MOS" type="xs:int" minOccurs="0"/>
        <xs:element name="promotion_code" type="xs:int" minOccurs="0"/>
        <xs:element name="batch_no" type="xs:int" minOccurs="0"/>
        <xs:element name="bu" type="xs:int" minOccurs="0"/>
        <xs:element name="status" type="xs:string" minOccurs="0"/>
        <xs:element name="OriginalMOS" type="xs:int" minOccurs="0"/>
        </xs:sequence>
        </xs:complexType>
        </xs:element>
        </xs:choice>
        </xs:complexType>
        </xs:element>
        </xs:schema>
        <diffgr:diffgram xmlns:msdata="urn:schemas-microsoft-com:xml-msdata" xmlns:diffgr="urn:schemas-microsoft-com:xml-diffgram-v1">
        <LoginInfoResults xmlns="">
        <LoginInformation diffgr:id="LoginInformation1" msdata:rowOrder="0">
        <customer_no>1281884</customer_no>
        <MOS>7</MOS>
        <promotion_code>25</promotion_code>
        <bu>1</bu>
        <status>T</status>
        <OriginalMOS>7</OriginalMOS>
        </LoginInformation>
        </LoginInfoResults>
        </diffgr:diffgram>
        </DataSet>*/
    
    
    $loggedInConstituentId = '';
    
    $startPos = strpos($response, "<customer_no");
    if ($startPos > -1){
        $startPos += 13;
        $endPos = strpos($response, "/customer_no>") - 1;
        $loggedInConstituentId = trim(substr($response,$startPos,$endPos - $startPos));
    }
    //exit('loggedinConstituentId: ' . $loggedInConstituentId);
    
    //exit;
    return $loggedInConstituentId;
}

function loginConstituent() {
    
    
}

function loginUsingEmail($email) {
    $_SESSION['sUID'] = $email;
    loginEx3();
}




function loginEx3() {
    if (empty($_SESSION['sUID'])){
        exit('sUID must not be blank');
    }
    
    
//    $params = array( 
//            'sSessionKey' => $_SESSION['sessionKey'],
//            'sUID' => $_SESSION['sUID'],
//            'sPwd' => '',
//            'iLoginType' => '1',
//            'iPromotionCode' => '0',
//            'sEmail' => '',
//            'sPhone' => '',
//            'sPostalCode' => '',
//            'iCustomerNo' => '0',
//            'iN1N2' => '0',
//            'sForgotLoginToken' => '0',
//            'bAlreadyAuthenticated' => 'true'
//    );
    
    $ch = curl_init(); 
    $url = $_SESSION['soapUrlDirect'] . "/LoginEx3?" .
            "sSessionKey=" . $_SESSION['sessionKey'] . 
            "&sUID=" . $_SESSION['sUID'] .
            "&sPwd=" . 
            "&iLoginType=1" .
            "&iPromotionCode=0" .
            "&sEmail=" .
            "&sPhone=" .
            "&sPostalCode=" .
            "&iCustomerNo=0" .
            "&iN1N2=0" . 
            "&sForgotLoginToken=0" .
            "&bAlreadyAuthenticated=true";
    $_SESSION['request']->updateApiCommunication("LoginEx3 request: " . $url . "\n");    
    
//    $response=$_SESSION['client']->LoginEx3($params); 
//    $paramsData = print_r($params, true);
//    $_SESSION['request']->updateApiCommunication("LoginEx3 request: " . $paramsData . "\n");
    
    curl_setopt($ch, CURLOPT_URL, $url);  // was "example.com"
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
    $response = curl_exec($ch); 
    curl_close($ch); 
    //dumpInfo($url, $response); 
    
//    $result = $response->LoginEx3Result;
    //$resultData = print_r($result, true);
    $_SESSION['request']->updateApiCommunication("LoginEx3 response: " . $response . "\n");
    
    //echo "<textarea rows=10 cols=120>";
    //echo $result;
    //echo "</textarea>";
    //exit;
    
    
    
    
//    echo "<pre>";
//    echo "loginEx3 Params: \n";
//    print_r($params);
//    echo "</pre>";
//    exit;
//   
//    echo "<pre>";
//    echo "loginEx3 Response: \n";
//    print_r($response);
//    echo "</pre>";
//    
//    echo "<pre>";
//    echo "loginEx3: \n";
//    print_r($result);
//    echo "</pre>";
    
    //echo "<pre>";
    //echo "loginEx3: \n";
    //print_r($_SESSION['sessionKey']);
    //echo "</pre>";

    
    
    
    
}

function getConstituentInfoEx() {
    // requires a session key and a login.
    $ch = curl_init(); 
    $url = $_SESSION['soapUrlDirect'] . "/GetConstituentInfoEx?SessionKey=" . $_SESSION['sessionKey'] . '&TableListTokens=';
    $_SESSION['request']->updateApiCommunication("GetConstituentInfoEx request: " . $url . "\n");    
    
    //$_SESSION['request']->updateApiCommunication("ExecuteLocalProcedure request: " . $url . "\n");
    //$response = (string) @file_get_contents($url);
    //$responseData = print_r($response,true);
    //$_SESSION['request']->updateApiCommunication("ExecuteLocalProcedure raw response: " . $responseData . "\n");
    //$startPos = strpos($response, "<customer_no") + 13;
    //$endPos = strpos($response, "</customer_no");
    //$constituentId = substr($response,$startPos,$endPos - $startPos);
    
    curl_setopt($ch, CURLOPT_URL, $url);  // was "example.com"
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
    $response = curl_exec($ch); 
    curl_close($ch); 
    //dumpInfo($url, $response); 
    
    $memberStatus = '';
    //$response = (string) file_get_contents($url);
    $startPos = strpos($response, "<current_status");
    if ($startPos > -1){
        $startPos += 16;
        $endPos = strpos($response, "</current_status");
        $memberStatus = substr($response,$startPos,$endPos - $startPos);
    }
//    $startPos = strpos($response, "<inactive>");
//    if ($startPos > -1){
//        $startPos += 10;
//        $endPos = strpos($response, "</inactive>");
//        $memberStatus = substr($response,$startPos,$endPos - $startPos);
//    }
    $_SESSION['request']->updateApiCommunication("GetConstituentInfoEx response: " . $response . "\n");   
//    exit('memberStatus: ' . $memberStatus);
//    echo "request: <br>";
//    echo "<textarea rows=30 cols=120>" . $url . '</textarea>';
//    
//    echo "response: <br>";
//    echo "<textarea rows=30 cols=120>" . $response . '</textarea>';
    
    if ($memberStatus == 'Active') {  //use this with current_status
//    if ($memberStatus == 'N') {  //use this with inactive
        $memberStatus = true;
    } else {
        $memberStatus = '';
        $startPos = strpos($response, "<inactive>");
        if ($startPos > -1){
            $startPos += 10;
            $endPos = strpos($response, "</inactive>");
            $memberStatus = substr($response,$startPos,$endPos - $startPos);
        }
        if ($memberStatus == 'N') {
            $memberStatus = true;
        } else {
            $memberStatus = false;
        }
    }
//    exit('memberStatus: ' . $memberStatus);
    return $memberStatus;
}



function getConstituentInfo($data) {
    // requires a session key and a login.
    
    $url = $_SESSION['soapUrlDirect'] . "/GetConstituent?SessionKey=" . $_SESSION['sessionKey'];
            
    
    
    //$_SESSION['request']->updateApiCommunication("ExecuteLocalProcedure request: " . $url . "\n");
    //$response = (string) @file_get_contents($url);
    //$responseData = print_r($response,true);
    //$_SESSION['request']->updateApiCommunication("ExecuteLocalProcedure raw response: " . $responseData . "\n");
    //$startPos = strpos($response, "<customer_no") + 13;
    //$endPos = strpos($response, "</customer_no");
    //$constituentId = substr($response,$startPos,$endPos - $startPos);
    
    
    $constituentId = '';
    $response = (string) file_get_contents($url);
    $startPos = strpos($response, "<customer_no");
    if ($startPos > -1){
        $startPos += 13;
        $endPos = strpos($response, "</customer_no");
        $constituentId = substr($response,$startPos,$endPos - $startPos);
    }
    /*echo "request: <br>";
    echo "<textarea rows=30 cols=120>" . $url . '</textarea>';
    
    echo "response: <br>";
    echo "<textarea rows=30 cols=120>" . $response . '</textarea>';
    */
    
    return $constituentId;
}

function getConstituentIdAndLoginFromEnterpriseId($enterpriseId) {
    $ch = curl_init(); 
    $url = $_SESSION['soapUrlDirect'] . "/ExecuteLocalProcedure?SessionKey=" . $_SESSION['sessionKey'] . 
            '&LocalProcedureId=48&LocalProcedureValues=@Keyword_no=405%26@Key_value=' . $enterpriseId . '%26@Single_customer=Y';
    $_SESSION['request']->updateApiCommunication("ExecuteLocalProcedure request: " . $url . "\n");
    //exit('url: ' . $url);
    
    //$_SESSION['request']->updateApiCommunication("ExecuteLocalProcedure request: " . $url . "\n");
    //$response = (string) @file_get_contents($url);
    //$responseData = print_r($response,true);
    //$_SESSION['request']->updateApiCommunication("ExecuteLocalProcedure raw response: " . $responseData . "\n");
    //$startPos = strpos($response, "<customer_no") + 13;
    //$endPos = strpos($response, "</customer_no");
    //$constituentId = substr($response,$startPos,$endPos - $startPos);
    
    curl_setopt($ch, CURLOPT_URL, $url);  // was "example.com"
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
    $response = curl_exec($ch); 
    curl_close($ch); 
    //dumpInfo($url, $response); 
    
    //$response = (string) file_get_contents($url);
    
    
    
    $constituentId = '';
    $startPos = strpos($response, "<customer_no");
    if ($startPos > -1){
        $startPos += 13;
        $endPos = strpos($response, "</customer_no");
        $constituentId = substr($response,$startPos,$endPos - $startPos);
    }
    
    $login = '';
    $startPos = strpos($response, "<login");
    if ($startPos > -1){
        $startPos += 7;
        $endPos = strpos($response, "</login");
        $login = substr($response,$startPos,$endPos - $startPos);
    }
    $_SESSION['request']->updateApiCommunication("ExecuteLocalProcedure response: " . $response . "\n");
    
    
    //echo "request: <br>";
    //echo "<textarea rows=30 cols=120>" . $url . '</textarea>';
    
    //echo "response: <br>";
    //echo "<textarea rows=30 cols=120>" . $response . '</textarea>';
    //exit;
    
    //return $constituentId;
    return array($constituentId, $login);
    
}






function getConstituentIdFromEnterpriseId($enterpriseId) {
    $ch = curl_init(); 
    
    $url = $_SESSION['soapUrlDirect'] . "/ExecuteLocalProcedure?SessionKey=" . $_SESSION['sessionKey'] . 
            '&LocalProcedureId=48&LocalProcedureValues=@Keyword_no=405%26@Key_value=' . $enterpriseId . '%26@Single_customer=Y';
     $_SESSION['request']->updateApiCommunication("ExecuteLocalProcedure getConstituentIdFromEnterpriseId request: " . $url . "\n");    
    //exit('url: ' . $url);
    
    //$_SESSION['request']->updateApiCommunication("ExecuteLocalProcedure request: " . $url . "\n");
    //$response = (string) @file_get_contents($url);
    //$responseData = print_r($response,true);
    //$_SESSION['request']->updateApiCommunication("ExecuteLocalProcedure raw response: " . $responseData . "\n");
    //$startPos = strpos($response, "<customer_no") + 13;
    //$endPos = strpos($response, "</customer_no");
    //$constituentId = substr($response,$startPos,$endPos - $startPos);
    
    curl_setopt($ch, CURLOPT_URL, $url);  // was "example.com"
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
    $response = curl_exec($ch); 
    curl_close($ch); 
    //dumpInfo($url, $response); 
    
    $_SESSION['request']->updateApiCommunication("ExecuteLocalProcedure getConstituentIdFromEnterpriseId response: " . $response . "\n");
     
    $constituentId = '';
    //$response = (string) file_get_contents($url);
    $startPos = strpos($response, "<customer_no");
    if ($startPos > -1){
        $startPos += 13;
        $endPos = strpos($response, "</customer_no");
        $constituentId = substr($response,$startPos,$endPos - $startPos);
    }
    //echo "request: <br>";
    //echo "<textarea rows=30 cols=120>" . $url . '</textarea>';
    
//    echo "response: <br>";
//    echo "<textarea rows=30 cols=120>" . $response . '</textarea>';
//    exit;
    
    //return $constituentId;
    return ($constituentId);
}





function getNewSessionKeyEx() {
    //$url = $_SESSION['soapUrlDirect'] . "/GetNewSessionKeyEx?sIP=" . $_SESSION['loginData']['IP'] . "&iBusinessUnit=0";
    $url = $_SESSION['soapUrlDirect'] . "/GetNewSessionKeyEx?sIP=98.172.11.227&iBusinessUnit=0";
    ini_set('default_socket_timeout', 900);
    $response = (string) file_get_contents($url);
    $startPos = strpos($response, "tessiturasoftware") + 24;
    $endPos = strpos($response, "string>") - 2;
    $_SESSION['sessionKey'] = substr($response,$startPos,$endPos - $startPos);
}


function getNewSessionKey() {
    $url = $_SESSION['soapUrlDirect'] . "/GetNewSessionKey?sIP=" . $_SESSION['loginData']['IP'];
    $_SESSION['request']->updateApiCommunication("GetNewSessionKey Request: " . $url . "\n");
    $response = (string) file_get_contents($url);
    $responseData = print_r($response,true);
    $_SESSION['request']->updateApiCommunication("GetNewSessionKey raw response: " . $responseData . "\n");
    $startPos = strpos($response, "tessiturasoftware") + 24;
    $endPos = strpos($response, "string>") - 2;
    $_SESSION['sessionKey'] = substr($response,$startPos,$endPos - $startPos);
}




function changeModeOfSaleEx() {
    //$client = new SoapClient("http://dbradp6.tessituranetworkramp.com/wwl1api/tessitura.asmx?WSDL");
    //$client = new SoapClient("https://tesstestgw1.empmuseum.org/tessitura.asmx?WSDL");
    
//    $params = array( 
//            'SessionKey' => $_SESSION['sessionKey'],
//            //'NewModeOfSale' => $_SESSION['loginData']['newModeOfSale']
//            'NewModeOfSale' => $_SESSION['modeOfSale']
//    );
//    
//    
//    
////    echo "<pre>";
////    echo "changeModeOfSaleEx: \n";
////    print_r($params);
////    echo "</pre>";
//    
//    $response=$_SESSION['client']->ChangeModeOfSaleEx($params); 
//    
//    $result = $response->ChangeModeOfSaleExResult;
//    
////    echo "<pre>";
////    echo "changeModeOfSaleEx: \n";
////    print_r($params);
////    echo "</pre>";
////   
////   
////    echo "<pre>";
////    echo "changeModeOfSaleEx: \n";
////    print_r($response);
////    echo "</pre>";
////    
////    echo "<pre>";
////    echo "changeModeOfSaleEx: \n";
////    print_r($result);
////    echo "</pre>";
////    exit();
//    
        $ch = curl_init(); 
         
        //"&sCCNumber=0000000000000000" . 
        
        $url = $_SESSION['soapUrlDirect'] . "/ChangeModeOfSaleEx?" . 
            "SessionKey=" . $_SESSION['sessionKey'] .
            "&NewModeOfSale=" . $_SESSION['modeOfSale'];
        $_SESSION['request']->updateApiCommunication("ChangeModeOfSaleEx request: " . $url . "\n");
        
        curl_setopt($ch, CURLOPT_URL, $url);  // was "example.com"
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
        $response = curl_exec($ch); 
        //$responseDate = print_r($response, true);
        curl_close($ch);  
//        echo "session orderNo: " . $_SESSION['orderNo'];
        //dumpInfo($url, $response); // have not yet seen a valid response.
        // if response is blank, everything is good.
        $startPos = strpos($response, "tessiturasoftware");
        $modeOfSale = 99;
        if ($startPos > -1){
            $startPos += 24;
            $endPos = strpos($response, "int>") - 2;
            $modeOfSale = substr($response,$startPos,$endPos - $startPos);
        } else {
            $modeOfSale = $response;
        }
        $_SESSION['request']->updateApiCommunication("ChangeModeOfSaleEx response: " . $response . "\n");
//        if ($modeOfSale == 23){
//            $changeModeOfSale = true;
//        } else {
//            $changeModeOfSale = false;
//        }
//        echo "modeOfSale: " . $modeOfSale;
//        exit;
        return $modeOfSale;  
}



function updateOrderDetails($data) {
        $ch = curl_init(); 
         
        $url = $_SESSION['soapUrlDirect'] . "/UpdateOrderDetails?" . 
            "sSessionKey=" . $_SESSION['sessionKey'] .
            "&sSolicitor=" . $data['solicitor'] .
            "&iCategory=0" .
            "&iChannel=" . $data['channel'] .    
            "&sHoldUntilDateTime="     ;
        $_SESSION['request']->updateApiCommunication("UpdateOrderDetails request: " . $url . "\n");
        
        curl_setopt($ch, CURLOPT_URL, $url);  // was "example.com"
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
        $response = curl_exec($ch); 
        //$responseDate = print_r($response, true);
        curl_close($ch);  
//        echo "session orderNo: " . $_SESSION['orderNo'];
//        dumpInfo($url, $response); // have not yet seen a valid response.
        // if response is blank, everything is good.
        if (strlen($response) > 3){
            $_SESSION['request']->updateApiCommunication("UpdateOrderDetails request error: " . $response . "\n");
            if (strpos($response, "Invalid @channel value") > -1) {
                errorPageSales("ERROR. Invalid channel value. PLEASE SEE VISITOR'S SERVICES.");
            }
            return false;
        } else {
            $_SESSION['request']->updateApiCommunication("UpdateOrderDetails response: blank means successful" . "\n");
            return true;
        }
    
}


function updateSourceCode($data) {
        $ch = curl_init(); 
         
        $url = $_SESSION['soapUrlDirect'] . "/UpdateSourceCode?" . 
            "SessionKey=" . $_SESSION['sessionKey'] .
            "&NewPromoCode=" . $data['newPromoCode']    ;
        $_SESSION['request']->updateApiCommunication("UpdateSourceCode request: " . $url . "\n");
        
        curl_setopt($ch, CURLOPT_URL, $url);  // was "example.com"
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
        $response = curl_exec($ch); 
        //$responseDate = print_r($response, true);
        curl_close($ch);  
//        echo "session orderNo: " . $_SESSION['orderNo'];
        //dumpInfo($url, $response); // have not yet seen a valid response.
        // if response is blank, everything is good.
        if (strlen($response) > 3){
            $_SESSION['request']->updateApiCommunication("UpdateSourceCode request error, this location did not get inserted: " . $response . "\n");
//            if (strpos($response, "Invalid @channel value") > -1) {
//                errorPageSales("ERROR. Invalid channel value. PLEASE SEE VISITOR'S SERVICES.");
//            }
            return false;
        } else {
            $_SESSION['request']->updateApiCommunication("UpdateSourceCode response: blank means successful" . "\n");
            return true;
        }   
}


function reserveTicketsEx($data) {
//    //$client = new SoapClient("http://dbradp6.tessituranetworkramp.com/wwl1api/tessitura.asmx?WSDL");
//    //$client = new SoapClient("https://tesstestgw1.empmuseum.org/tessitura.asmx?WSDL");
//    
//    $params = array( 
//            'sWebSessionID' => $_SESSION['sessionKey'],
//            'sPriceType' => $data['priceType'],
//            'iPerformanceNumber' => $data['perfNo'],
//            'iNumberOfSeats' => $data['numberOfSeats'],
//            'iZone' => $data['zoneNo'],
//            'sSpecialRequests' => $data['specialRequests']
//    );
//    
//    $response=$_SESSION['client']->ReserveTicketsEx($params); 
//    $ticketsReserved = $response->ReserveTicketsExResult;
//    
////    echo "<pre>";
////    echo "reserveTicketsEx: \n";
////    print_r($params);
////    echo "</pre>";
////    exit;
//    
//    return $ticketsReserved;
    
    
        $ch = curl_init(); 
         
        $url = $_SESSION['soapUrlDirect'] . "/ReserveTicketsEx?" . 
            "sWebSessionID=" . $_SESSION['sessionKey'] .
            "&sPriceType=" . $data['priceType'] .
            "&iPerformanceNumber=" . $data['perfNo'] .
            "&iNumberOfSeats=" . $data['numberOfSeats'] .
            "&iZone=" . $data['zoneNo'] .
            "&sSpecialRequests=" . $data['specialRequests'];
        $_SESSION['request']->updateApiCommunication("ReserveTicketsEx request: " . $url . "\n");
        curl_setopt($ch, CURLOPT_URL, $url);  // was "example.com"
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
        $response = curl_exec($ch); 
        $responseData = print_r($response, true);
        $_SESSION['request']->updateApiCommunication("ReserveTicketsEx response: " . $responseData . "\n");
        
        curl_close($ch);  
//        echo "session orderNo: " . $_SESSION['orderNo'];
        //dumpInfo($url, $response); // have not yet seen a valid response.
        // if response is blank, everything is good.
        $startPos = strpos($response, "tessiturasoftware");
        if ($startPos > -1){
            $startPos += 24;
            $endPos = strpos($response, "int>") - 2;
            $ticketsReserved = substr($response,$startPos,$endPos - $startPos);
            $_SESSION['request']->updateApiCommunication("ReserveTicketsEx response: " . $response . "\n");
        } else {
            $_SESSION['request']->updateApiCommunication("ReserveTicketsEx request error: " . $response . "\n");
            errorPageSales("There was an error trying to reserve. Please see a ticket desk for assistance.");
        }
        
        //exit('ticketsReserved: ' . $ticketsReserved);
        return $ticketsReserved;     
    
    
    
}

function getCartOld() {  //csn 20160419
    //$client = new SoapClient("http://dbradp6.tessituranetworkramp.com/wwl1api/tessitura.asmx?WSDL");
    //$client = new SoapClient("https://tesstestgw1.empmuseum.org/tessitura.asmx?WSDL");
    
    $params = array( 
           'sWebSessionID' => $_SESSION['sessionKey']
    );
    $paramsData = print_r($params, true);
    
    $_SESSION['request']->updateApiCommunication("GetCart request: " . $paramsData . "\n");
    
    $response = $_SESSION['client']->GetCart($params);
    $responseData = print_r($response, true);
    $_SESSION['request']->updateApiCommunication("GetCart raw response: " . $responseData . "\n");
//    echo "<textarea rows=20 cols=120>";
//    print_r($response);
//    echo "</textarea>";
//    exit;
//    $response = print_r($response, true);  //csn this is to convert object to string
//    if (strpos($response, "[any] => <diffgr:diffgram") > -1){  //checking to see if $response is empty or not
//        $_SESSION['request']->updateApiCommunication("CheckOutEx4 request error: " . $response . "\n");
//        errorPageSales("If the time is before 9:30am or after 3pm, then it is too early or too late <br>to process transaction. Please see a ticket desk for assistance.");
//    } else {
//        $cartObject = simplexml_load_string($response->GetCartResult->any);
//    }  //csn 20160412 Need to add this check to prevent processing error before 9:30am or after 3pm
    
    $cartObject = simplexml_load_string($response->GetCartResult->any);  //csn 20160412
     
//    echo "<textarea rows=20 cols=120>";
//    print_r($cartObject);
//    echo "</textarea>";
//    exit;
    
    return $cartObject;
    
    /*
     * SimpleXMLElement Object
(
    [GetCartResults] => SimpleXMLElement Object
        (
            [Order] => SimpleXMLElement Object
                (
                    [sessionkey] => FVN73P91Q2GY6MUO30GB4U6FSSROFTJYCJ11SNTDELWKVRCISN8XA0GKVWEAL011
                    [order_no] => 280716
                    [appeal_no] => 10
                    [source_no] => 25
                    [customer_no] => 1281884
                    [solicitor] => webAPI  
                    [MOS] => 7
                    [order_dt] => 2015-12-29T15:37:53.427-05:00
                    [order_total] => 48.0000
                    [order_value] => 48.0000
                    [db_status] => 1
                    [amt_to_charge] => 48.0000
                    [first_seat_added_dt] => 2015-12-29T15:37:53.427-05:00
                    [amt_paid_to_dt] => 0.0000
                    [amt_paid_now] => 0.0000
                    [balance_to_charge] => 48.0000
                    [SubTotal] => 48.0000
                    [HandlingCharges] => 0.0000
                )

            [LineItem] => Array
                (
                    [0] => SimpleXMLElement Object
                        (
                            [li_seq_no] => 355511
                            [li_no] => 0
                            [order_no] => 280716
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
                            [li_seq_no] => 355512
                            [li_no] => 0
                            [order_no] => 280716
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
                            [sli_no] => 837599
                            [li_seq_no] => 355511
                            [due_amt] => 25.0000
                            [paid_amt] => 0.0000
                            [price_type] => 70
                            [seat_no] => 90791
                            [perf_no] => 2456
                            [pkg_no] => 0
                            [zone_no] => 54
                            [order_no] => 280716
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
                            [sli_no] => 837600
                            [li_seq_no] => 355512
                            [due_amt] => 23.0000
                            [paid_amt] => 0.0000
                            [price_type] => 71
                            [seat_no] => 90792
                            [perf_no] => 2456
                            [pkg_no] => 0
                            [zone_no] => 54
                            [order_no] => 280716
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
     * 
     */
    
    
    
}


function getCart() {
    //$client = new SoapClient("http://dbradp6.tessituranetworkramp.com/wwl1api/tessitura.asmx?WSDL");
    //$client = new SoapClient("https://tesstestgw1.empmuseum.org/tessitura.asmx?WSDL");
    
    $ch = curl_init(); 
    
    $url = $_SESSION['soapUrlDirect'] . "/GetCart?" .
            "sWebSessionID=" . $_SESSION['sessionKey'];    
    $_SESSION['request']->updateApiCommunication("GetCart request: " . $url . "\n");
    
//    $response = $_SESSION['client']->GetCart($params);
//    $responseData = print_r($response, true);
    
//    echo "<textarea rows=20 cols=120>";
//    print_r($response);
//    echo "</textarea>";
//    exit;
//    $response = print_r($response, true);  //csn this is to convert object to string
//    if (strpos($response, "[any] => <diffgr:diffgram") > -1){  //checking to see if $response is empty or not
//        $_SESSION['request']->updateApiCommunication("CheckOutEx4 request error: " . $response . "\n");
//        errorPageSales("If the time is before 9:30am or after 3pm, then it is too early or too late <br>to process transaction. Please see a ticket desk for assistance.");
//    } else {
//        $cartObject = simplexml_load_string($response->GetCartResult->any);
//    }  //csn 20160412 Need to add this check to prevent processing error before 9:30am or after 3pm
    
    curl_setopt($ch, CURLOPT_URL, $url);  // was "example.com"
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
    $response = curl_exec($ch); 
    curl_close($ch); 
    //dumpInfo($url, $response); 
    
//    $cartObject = simplexml_load_string($response->GetCartResult->any);  //csn 20160412
    $startPos = strpos($response, "<DataSet");
    $endPos = strpos($response, "<diffgr:diffgram");
    $response = substr($response,0,$startPos) . substr($response,$endPos);
    //exit($response);
    $response = substr($response,0,strpos($response, "<GetCartResults")) . substr($response,strpos($response, "<Order"));
    $response = substr($response,0,strpos($response, "</GetCartResults>")) . substr($response,strpos($response, "</diffgr:diffgram>"),18);
    
    $cartObject = simplexml_load_string($response);
    $_SESSION['request']->updateApiCommunication("GetCart raw response: " . $response . "\n");
     
//    echo "<textarea rows=20 cols=120>";
//    print_r($cartObject);
//    echo "</textarea>";
//    exit;
    
    return $cartObject;
    
    /*
     * SimpleXMLElement Object
(
    [GetCartResults] => SimpleXMLElement Object
        (
            [Order] => SimpleXMLElement Object
                (
                    [sessionkey] => FVN73P91Q2GY6MUO30GB4U6FSSROFTJYCJ11SNTDELWKVRCISN8XA0GKVWEAL011
                    [order_no] => 280716
                    [appeal_no] => 10
                    [source_no] => 25
                    [customer_no] => 1281884
                    [solicitor] => webAPI  
                    [MOS] => 7
                    [order_dt] => 2015-12-29T15:37:53.427-05:00
                    [order_total] => 48.0000
                    [order_value] => 48.0000
                    [db_status] => 1
                    [amt_to_charge] => 48.0000
                    [first_seat_added_dt] => 2015-12-29T15:37:53.427-05:00
                    [amt_paid_to_dt] => 0.0000
                    [amt_paid_now] => 0.0000
                    [balance_to_charge] => 48.0000
                    [SubTotal] => 48.0000
                    [HandlingCharges] => 0.0000
                )

            [LineItem] => Array
                (
                    [0] => SimpleXMLElement Object
                        (
                            [li_seq_no] => 355511
                            [li_no] => 0
                            [order_no] => 280716
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
                            [li_seq_no] => 355512
                            [li_no] => 0
                            [order_no] => 280716
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
                            [sli_no] => 837599
                            [li_seq_no] => 355511
                            [due_amt] => 25.0000
                            [paid_amt] => 0.0000
                            [price_type] => 70
                            [seat_no] => 90791
                            [perf_no] => 2456
                            [pkg_no] => 0
                            [zone_no] => 54
                            [order_no] => 280716
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
                            [sli_no] => 837600
                            [li_seq_no] => 355512
                            [due_amt] => 23.0000
                            [paid_amt] => 0.0000
                            [price_type] => 71
                            [seat_no] => 90792
                            [perf_no] => 2456
                            [pkg_no] => 0
                            [zone_no] => 54
                            [order_no] => 280716
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
     * 
     */
    
    
    
}


function addOrderCommentsEx2($data) {
    //$client = new SoapClient("http://dbradp6.tessituranetworkramp.com/wwl1api/tessitura.asmx?WSDL");
    $client = new SoapClient("https://tesstestgw1.empmuseum.org/tessitura.asmx?WSDL");
    
    $params = array( 
           'SessionKey' => $_SESSION['sessionKey'],
            'Comment' => $data['comment'],
            'LineItemID' => $data['lineItemID'],
            'LineItemType' => $data['lineItemType'],
            'CustomerNo' => $data['customerNo'],
            'CategoryNo' => $data['categorNo']
    );
    
    echo "<pre>";
    echo "addOrderCommentsEx2 Params: \n";
    print_r($params);
    echo "</pre>";
   
    
    $response=$client->AddOrderCommentsEx2($params); 
    
    $result = $response->AddOrderCommentsEx2Result;
    
    echo "<pre>";
    echo "addOrderCommentsEx2 Params: \n";
    print_r($params);
    echo "</pre>";
   
   
    echo "<pre>";
    echo "addOrderCommentsEx2 Response: \n";
    print_r($response);
    echo "</pre>";
    
}

function registerWithPromoCodeEx() {
    
       $ch = curl_init(); 
         
        //"&sCCNumber=0000000000000000" . 
        
        $url = $_SESSION['soapUrlDirect'] . "/RegisterWithPromoCodeEx?" . 
            "sSessionKey=" . $_SESSION['sessionKey'] . 
            "&sUID=" . $_SESSION['loginEmail'] .
            "&sPwd=" .  
            "&iLoginType=1" . 
            "&sEmail=" . $_SESSION['loginEmail'] .
            "&sFName=" . $_SESSION['loginFirstName'] .
            "&sLName=" . $_SESSION['loginLastName'] .
            "&iPromoCode=0" . 
            "&bAlreadyAuthenticated=true";
         $_SESSION['request']->updateApiCommunication("RegisterWithPromoCodeEx request: " . $url . "\n");   
        
        curl_setopt($ch, CURLOPT_URL, $url);  // was "example.com"
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
        $response = curl_exec($ch); 
        curl_close($ch);  
//        echo "session orderNo: " . $_SESSION['orderNo'];
        //dumpInfo($url, $response); // have not yet seen a valid response.
        $startPos = strpos($response, "tessiturasoftware");
        $registered = 'false';
          if ($startPos > -1){
            $startPos += 24;
            $endPos = strpos($response, "boolean>") - 2;
            $registered = substr($response,$startPos,$endPos - $startPos);
        }
        $_SESSION['request']->updateApiCommunication("RegisterWithPromoCodeEx response: " . $response . "\n");
        //echo "registered: " . $registered;

        //exit;
        return $registered;      
        
        
//        $url = $_SESSION['soapUrlDirect'] . "/LoggedIn?sSessionKey=" . $_SESSION['sessionKey'];
//        ini_set('default_socket_timeout', 900);
//        $response = (string) file_get_contents($url);
//        //dumpInfo($url,$response);
//        $startPos = strpos($response, "tessiturasoftware");
//        $loggedIn = 'false';
//        if ($startPos > -1){
//            $startPos += 24;
//            $endPos = strpos($response, "boolean>") - 2;
//            $loggedIn = substr($response,$startPos,$endPos - $startPos);
//        }
//        //echo "loggedIn: " . $loggedIn;
//
//        //exit;
//        return $loggedIn;        
        
    
}

function checkoutEx4ProductionVersion($data) {  //csn 20160420
    
    
        $ch = curl_init(); 
         
        //"&sCCNumber=0000000000000000" . 
        //$data['mskPAN'] = '4111********1111';   //4239********8145.
        //$data['mskPAN'] = '4111111111111111'; 
        
        $url = $_SESSION['soapUrlDirect'] . "/CheckoutEx4?" . 
            "sSessionKey=" . $_SESSION['sessionKey'] . 
            "&sCCOwner=" . 
            "&sCCNumber=" . $data['mskPAN'] .
            "&iCCType=33" .  //"&iCCType=2" . 
            "&iCCMonth=12" . 
            "&iCCYear=" . date('Y') . 
            "&dAmount=" . $data['amount'] . 
            "&bAllowUnderPayment=false" . 
            "&sCardAuthenticationCode=" . 
            "&iCCIssueNumber=0" . 
            "&iCCStartMonth=0" . 
            "&iCCStartYear=0" .   
            "&bAuthorize=false" . 
            "&sAuthorizationCode=0" . 
            "&s3DSecureValues=" . 
            "&iAccountId=-1" . 
            "&sZipCode=" . 
            "&sAddress=" . 
            "&sPaymentReference=" . $data['paymentReference'];
        $_SESSION['request']->updateApiCommunication("CheckOutEx4 request: " . $url . "\n");
        
        curl_setopt($ch, CURLOPT_URL, $url);  // was "example.com"
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
        $response = curl_exec($ch); 
        curl_close($ch);  
//        echo "session orderNo: " . $_SESSION['orderNo'];
        //dumpInfo($url, $response); // have not yet seen a valid response.
        // if response is blank, everything is good.
        if (strlen($response) > 3){
            $_SESSION['request']->updateApiCommunication("CheckOutEx4 request error: " . $response . "\n");
            if (strpos($response, "No Valid Address exists for this Constituent") > -1) {
                errorPageSales("ERROR CHECKING OUT. NO VALID ADDRESS EXISTS FOR THIS CONSTITUENT. PLEASE SEE VISITOR'S SERVICES. <br>YOUR CREDIT CARD CHARGES WILL NEED TO BE REFUNDED.");
            }
            return false;
        } else {
            $_SESSION['request']->updateApiCommunication("CheckOutEx4 response: blank means successful" . "\n");
            return true;
        }
        
        
       
}


function checkoutEx4($data) {
//        $startWith = substr($_SESSION['ccNumber'],0,2);
//        //exit('startWith: ' . $startWith);
//        
//        if ($startWith >= '40' && $startWith <= '49') {
//            $ccType = '33';
//            $_SESSION['card'] = 'VISA';
//        } else if ($startWith >= '51' && $startWith <= '55') {   
//            $ccType = '34';
//            $_SESSION['card'] = 'MASTERCARD';
//        } else if ($startWith == '34' || $startWith == '37') {   
//            $ccType = '35';
//            $_SESSION['card'] = 'AMERICAN EXPRESS';
//        } else {
//            displayCart("Invalid card type.<br>Your card was not charged.<br>Please try a different card or the same one again.");
//        }
        
        //exit('startWith ' . $startWith);
        
        
    
        $ch = curl_init(); 
         
        //"&sCCNumber=0000000000000000" . 
        //$data['mskPAN'] = '4111********1111';   //4239********8145.
        //$data['mskPAN'] = '4111111111111111'; 
        //$data['ccNumber'] = '4111111111111111'; 
        
        $data['ccOwner'] = str_replace(" ","%20",$data['ccOwner']);
        $url = $_SESSION['soapUrlDirect'] . "/CheckoutEx4?" . 
            "sSessionKey=" . $_SESSION['sessionKey'] . 
            "&sCCOwner=" . $data['ccOwner'] .
            "&sCCNumber=" . $data['ccNumber'] .
            "&iCCType=" .  $data['ccType'] . 
//            "&iCCMonth=" . $data['ccMonth'] . 
//            "&iCCYear=" . $data['ccYear'] . 
            "&iCCMonth=12" .  
            "&iCCYear=16" .   
            "&dAmount=" . $data['amount'] . 
            "&bAllowUnderPayment=false" . 
            "&sCardAuthenticationCode=" . 
            "&iCCIssueNumber=0" . 
            "&iCCStartMonth=0" . 
            "&iCCStartYear=0" .   
            "&bAuthorize=true" .//"&bAuthorize=false" . 
            "&sAuthorizationCode=0" . 
            "&s3DSecureValues=" . 
            "&iAccountId=-1" . 
            "&sZipCode=" . 
            "&sAddress=" . 
            "&sPaymentReference="; //"&sPaymentReference=" . $data['paymentReference'];
        //$url = urlencode($url);
        $_SESSION['request']->updateApiCommunication("CheckOutEx4 request: " . $url . "\n");
        
        curl_setopt($ch, CURLOPT_URL, $url);  // was "example.com"
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
        $response = curl_exec($ch); 
        curl_close($ch);  
//        echo "session orderNo: " . $_SESSION['orderNo'];
        //dumpInfo($url, $response); // have not yet seen a valid response.
        // if response is blank, everything is good.
        if (strlen($response) > 3){
            $_SESSION['request']->updateApiCommunication("CheckOutEx4 request error: " . $response . "\n");
            if (strpos($response, "No Valid Address exists for this Constituent") > -1) {
                errorPageSales("ERROR CHECKING OUT. NO VALID ADDRESS EXISTS FOR THIS CONSTITUENT. PLEASE SEE VISITOR'S SERVICES. <br>YOUR CREDIT CARD CHARGES WILL NEED TO BE REFUNDED.");
            }
            return false;
        } else {
            $_SESSION['request']->updateApiCommunication("CheckOutEx4 response: blank means successful" . "\n");
            return true;
        }
        
        
       
}


function checkoutEx4Old20160504($data) {
    
    
        $ch = curl_init(); 
         
        //"&sCCNumber=0000000000000000" . 
        //$data['mskPAN'] = '4111********1111';   //4239********8145.
        $data['mskPAN'] = '4111111111111111'; 
        
        $url = $_SESSION['soapUrlDirect'] . "/CheckoutEx4?" . 
            "sSessionKey=" . $_SESSION['sessionKey'] . 
            "&sCCOwner=CCOwnerName" . 
            "&sCCNumber=" . $data['mskPAN'] .
            "&iCCType=33" .  //"&iCCType=2" . 
            "&iCCMonth=12" . 
            "&iCCYear=" . date('Y') . 
            "&dAmount=" . $data['amount'] . 
            "&bAllowUnderPayment=false" . 
            "&sCardAuthenticationCode=" . 
            "&iCCIssueNumber=0" . 
            "&iCCStartMonth=0" . 
            "&iCCStartYear=0" .   
            "&bAuthorize=true" .//"&bAuthorize=false" . 
            "&sAuthorizationCode=0" . 
            "&s3DSecureValues=" . 
            "&iAccountId=-1" . 
            "&sZipCode=" . 
            "&sAddress=" . 
            "&sPaymentReference="; //"&sPaymentReference=" . $data['paymentReference'];
        $_SESSION['request']->updateApiCommunication("CheckOutEx4 request: " . $url . "\n");
        
        curl_setopt($ch, CURLOPT_URL, $url);  // was "example.com"
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
        $response = curl_exec($ch); 
        curl_close($ch);  
//        echo "session orderNo: " . $_SESSION['orderNo'];
//        dumpInfo($url, $response); // have not yet seen a valid response.
        // if response is blank, everything is good.
        if (strlen($response) > 3){
            $_SESSION['request']->updateApiCommunication("CheckOutEx4 request error: " . $response . "\n");
            if (strpos($response, "No Valid Address exists for this Constituent") > -1) {
                errorPageSales("ERROR CHECKING OUT. NO VALID ADDRESS EXISTS FOR THIS CONSTITUENT. PLEASE SEE VISITOR'S SERVICES. <br>YOUR CREDIT CARD CHARGES WILL NEED TO BE REFUNDED.");
            }
            return false;
        } else {
            $_SESSION['request']->updateApiCommunication("CheckOutEx4 response: blank means successful" . "\n");
            return true;
        }
        
        
       
}



function checkoutEx4ThirdParty($data) {
    
    
        $ch = curl_init(); 
         
        //"&sCCNumber=0000000000000000" . 
        //$data['mskPAN'] = '4111********1111';   //4239********8145.
        //$data['mskPAN'] = '4111111111111111'; 
        
        $url = $_SESSION['soapUrlDirect'] . "/CheckoutEx4?" . 
            "sSessionKey=" . $_SESSION['sessionKey'] . 
            "&sCCOwner=" . 
            "&sCCNumber=0000000000000000" .
            "&iCCType=2" . 
            "&iCCMonth=12" . 
            "&iCCYear=" . date('Y') . 
            "&dAmount=" . $data['amount'] . 
            "&bAllowUnderPayment=false" . 
            "&sCardAuthenticationCode=" . 
            "&iCCIssueNumber=0" . 
            "&iCCStartMonth=0" . 
            "&iCCStartYear=0" .   
            "&bAuthorize=false" . 
            "&sAuthorizationCode=0" . 
            "&s3DSecureValues=" . 
            "&iAccountId=-1" . 
            "&sZipCode=" . 
            "&sAddress=" . 
            "&sPaymentReference="; //"&sPaymentReference=" . $data['paymentReference'];
        $_SESSION['request']->updateApiCommunication("CheckOutEx4 request: " . $url . "\n");
        
        curl_setopt($ch, CURLOPT_URL, $url);  // was "example.com"
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
        $response = curl_exec($ch); 
        curl_close($ch);  
//        echo "session orderNo: " . $_SESSION['orderNo'];
//        dumpInfo($url, $response); // have not yet seen a valid response.
        // if response is blank, everything is good.
        if (strlen($response) > 3){
            $_SESSION['request']->updateApiCommunication("CheckOutEx4 request error: " . $response . "\n");
            if (strpos($response, "No Valid Address exists for this Constituent") > -1) {
                errorPageSales("ERROR CHECKING OUT. NO VALID ADDRESS EXISTS FOR THIS CONSTITUENT. PLEASE SEE VISITOR'S SERVICES. <br>YOUR CREDIT CARD CHARGES WILL NEED TO BE REFUNDED.");
            }
            return false;
        } else {
            $_SESSION['request']->updateApiCommunication("CheckOutEx4 response: blank means successful" . "\n");
            return true;
        }
        
        
       
}


function checkoutEx4new2($data) {
    $url = $_SESSION['soapUrlDirect'] . "/CheckoutEx4?" . 
            "sSessionKey=" . $_SESSION['sessionKey'] . 
            "&sCCOwner=" . 
            "&sCCNumber=0000000000000000" . 
            "&iCCType=2" . 
            "&iCCMonth=12" . 
            "&iCCYear=" . date('Y') . 
            "&dAmount=" . $data['amount'] . 
            "&bAllowUnderPayment=false" . 
            "&sCardAuthenticationCode=" . 
            "&iCCIssueNumber=0" . 
            "&iCCStartMonth=0" . 
            "&iCCStartYear=0" .   
            "&bAuthorize=false" . 
            "&sAuthorizationCode=0" . 
            "&s3DSecureValues=" . 
            "&iAccountId=-1" . 
            "&sZipCode=" . 
            "&sAddress=" . 
            "&sPaymentReference=" . $data['paymentReference'];
    
    
    $response = (string) file_get_contents($url);
    dumpInfo($url, $response);
//$startPos = strpos($response, "<customer_no");
    //if ($startPos > -1){
    //    $startPos += 13;
    //    $endPos = strpos($response, "</customer_no");
    //    $constituentId = substr($response,$startPos,$endPos - $startPos);
   // }
    
    
    
}


function checkoutEx4Old($data) {
    
    
    // keep getting invalid card type.
    
    /*$params = array( 
            'sSessionKey' => $_SESSION['sessionKey'],
            'sCCOwner' => $data['CCName'],
            'sCCNumber' => $data['CCNo'],
            'iCCType' => $data['CCType'],
            'iCCMonth' => $data['CCMonth'],
            'iCCYear' => $data['CCYear'],
            'dAmount' => $data['amount'],
            'bAllowUnderPayment' => $data['allowUnderPayment'],
            'sCardAuthenticationCode' => $data['cardAuthenticationCode'],
            'iCCIssueNumber' => $data['CCIssueNo'],
            'iCCStartMonth' => $data['CCStartMonth'],
            'iCCStartYear' => $data['CCStartYear'],  
            'bAuthorize' => $data['authorize'],
            'sAuthorizationCode' => $data['authorizationCode'],
            's3DSecureValues' => $data['3DSecureValues'],
            'iAccountId' => $data['accountID'],
            'sZipCode' => $data['zipCode'],
            'sAddress' => $data['address'],
            'sPaymentReference' => $data['paymentReference']
    );*/
    
    $params = array( 
            'sSessionKey' => $_SESSION['sessionKey'],
            'sCCOwner' => '',
            /*'sCCNumber' => '0000000000000000',*/
            'sCCNumber' => 4253160263138479,
            'iCCType' =>1,
            'iCCMonth' => 12,
            'iCCYear' => date('Y'),
            'dAmount' => $data['amount'],
            'bAllowUnderPayment' => 'false',
            'sCardAuthenticationCode' => '',
            'iCCIssueNumber' => 0,
            'iCCStartMonth' => 0,
            'iCCStartYear' => 0,  
            'bAuthorize' => 'false',
            'sAuthorizationCode' => '0',
            's3DSecureValues' => '',
            'iAccountId' => -1,
            'sZipCode' => '',
            'sAddress' => '',
            'sPaymentReference' => $data['paymentReference']
    );
    
    
    
    
    echo "request: <br>";
    echo "<textarea rows=30 cols=120>";
    print_r($params);
    echo '</textarea><br><br>';
    
    
    
    $response=$_SESSION['client']->CheckoutEx4($params); 
    
    
    
    
    echo "response: <br>";
    echo "<textarea rows=30 cols=120>";
    print_r($response);
    echo '</textarea>';
    exit;
    
    
    
    //$response=$client->CheckoutEx4($params); 
    //$result = $client->CheckoutEx4($params);
    
    //
    //$header = new SOAPHeader($ns, 'CheckoutStatusHeader', $params);
    //$result = $client->__setSoapHeaders($header);
    //
    //
    //$result = $client->__soapCall(CheckoutEx4($params),$responseHeaders);
    //$responseHeaders['CheckoutStatusHeader']->Status;
 
    
    //echo "<pre>";
    //echo "checkoutEx4 result: \n";
    //print_r($result);
    //echo "</pre>";
   
    //$result = $response->CheckoutEx4Result;
    
    //$xml = simplexml_load_string($response->GetCartResult->any);
    
//    echo "<pre>";
//    echo "checkoutEx4 getLastResponseHeaders: \n";
//    //print_r($params);
//    print_r($client->__getLastResponseHeaders());
//    echo "</pre>";
   exit;
   
    echo "<pre>";
    echo "checkoutEx4 Response: \n";
    print_r($response);
    echo "</pre>";
    
    echo "<pre>";
    echo "checkoutEx4 result: \n";
    print_r($result);
    echo "</pre>";
    
    echo "<pre>";
    echo "checkoutEx4 xml: \n";
    print_r($xml);
    echo "</pre>";
    
    
}




//This is to pull up the notes that was added to the order using AddOrderCommentsEx2 by the order number
function getOrderDetails() {
        $ch = curl_init(); 
         
        $url = $_SESSION['soapUrlDirect'] . "/GetOrderDetails?" . 
            "SessionKey=" . $_SESSION['sessionKey'] .
            "&OrderNumber=" . $_SESSION['orderNo'] ;
        $_SESSION['request']->updateApiCommunication("GetOrderDetails request: " . $url . "\n");
        
        curl_setopt($ch, CURLOPT_URL, $url);  // was "example.com"
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
        $response = curl_exec($ch); 
        //$responseDate = print_r($response, true);
        curl_close($ch);  
//        echo "session orderNo: " . $_SESSION['orderNo'];
//        dumpInfo($url, $response); // have not yet seen a valid response.
        // if response is blank, everything is good.
        $_SESSION['request']->updateApiCommunication("GetOrderDetails order number: " . $_SESSION['orderNo'] . "\n");
        $_SESSION['request']->updateApiCommunication("GetOrderDetails request: " . $response . "\n");
        if (strlen($response) > 3){
            $_SESSION['request']->updateApiCommunication("GetOrderDetails request error: " . $response . "\n");
            if (strpos($response, "Invalid @channel value") > -1) {
                errorPageSales("ERROR. Invalid channel value. PLEASE SEE VISITOR'S SERVICES.");
            }
            return false;
        } else {
            $_SESSION['request']->updateApiCommunication("GetOrderDetails response: blank means successful" . "\n");
            return true;
        }
    

    
    
    
//    //$client = new SoapClient("http://dbradp6.tessituranetworkramp.com/wwl1api/tessitura.asmx?WSDL");
//    $client = new SoapClient("https://tesstestgw1.empmuseum.org/tessitura.asmx?WSDL");
//    
//    $params = array( 
//           'SessionKey' => $_SESSION['sessionKey'],
//            'OrderNumber' => $_SESSION['orderNo']
//    );
//    
//    $response=$client->GetOrderDetails($params); 
//    
//    $_SESSION['sessionKey'] = $response->GetOrderDetailsResult;
//    
//    echo "<pre>";
//    echo "getOrderDetails Params: \n";
//    print_r($params);
//    echo "</pre>";
//   
//   
//    echo "<pre>";
//    echo "getOrderDetails Response: \n";
//    print_r($response);
//    echo "</pre>";
    
}





function executeLocalProcedure($data) {
    $ch = curl_init(); 
    
    $url = $_SESSION['soapUrlDirect'] . "/ExecuteLocalProcedure?SessionKey=" . $_SESSION['sessionKey'] . 
            "&LocalProcedureId=" . $data['localProcedureId'] . 
            "&LocalProcedureValues=" . $data['localProcedureValues'];
    $_SESSION['request']->updateApiCommunication("ExecuteLocalProcedure request: " . $url . "\n");
    curl_setopt($ch, CURLOPT_URL, $url);  // was "example.com"
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
    $response = curl_exec($ch); 
    curl_close($ch);  
    
//    $response = (string) @file_get_contents($url);
    
    $responseData = print_r($response,true);
      
    $_SESSION['request']->updateApiCommunication("ExecuteLocalProcedure raw response: " . $responseData . "\n");
    
//    $startPos = strpos($response, "<message") + 9;
//    $endPos = strpos($response, "</message");
//    $_SESSION['message'] = substr($response,$startPos,$endPos - $startPos);
    //dumpinfo($url, $response);
//    echo "<textarea rows=60 cols=80>";
//    print_r($response);
//    echo "</textarea>";
//    exit();
    
    $startPos = strpos($response, "<DataSet");
    $endPos = strpos($response, "<diffgr:diffgram");
    $response = substr($response,0,$startPos) . substr($response,$endPos);
    $response = substr($response,0,strpos($response, "<ExecuteLocalProcedureResults")) . substr($response,strpos($response, "<LocalProcedure"));
    $response = substr($response,0,strpos($response, "</ExecuteLocalProcedureResults>")) . substr($response,strpos($response, "</diffgr:diffgram>"),18);
    
    $entitlementObject = simplexml_load_string($response);
    
//    echo "<textarea rows=20 cols=120>";
//    print_r($entitlementObject);
//    echo "</textarea>";
//    exit;
    
    return $entitlementObject;
}


// FUNCTION IS TO RETURN ORDERS
function getOrdersEx($data) {
    // whole function should be to return a list of orders.
    
    $orders = array();
    $data['websessionId'] = '';
    
    //$url = "http://tessappprod.mfa.org/TessituraWebAPI/Tessitura.asmx/GetOrdersEx?sWebSessionId=" . $data['websessionId'] . 
    /*$url = "http://tessapptest.mfa.org/TessituraWebAPI/Tessitura.asmx/GetOrdersEx?sWebSessionId=" . $data['websessionId'] . 
            "&iOrderNumber=" . $data['ordernumber'] . 
            "&sPhoneNumber=" . 
            "&cPrint=" . $data['print'] . 
            "&dtStartDate=" . $data['startDate'] . 
            "&dtEndDate=" . $data['endDate'] . 
            "&iSeason=" . $data['season'] . 
            "&iCustomerNumber=" . $data['customerNbr'] . 
            "&iMos=" . $data['mos'] . 
            "&cRenewals=N"  . 
            "&iDeliveryMethod=" . $data['deliveryMethod'];/
     * 
     */
    $ch = curl_init(); 
    
    $url = $_SESSION['soapUrlDirect'] . "/GetOrdersEx?sWebSessionId=" . $data['websessionId'] . 
            "&iOrderNumber=" . $data['ordernumber'] . 
            "&sPhoneNumber=" . 
            "&cPrint=" . $data['print'] . 
            "&dtStartDate=" . $data['startDate'] . 
            "&dtEndDate=" . $data['endDate'] . 
            "&iSeason=" . $data['season'] . 
            "&iCustomerNumber=" . $data['customerNbr'] . 
            "&iMos=" . $data['mos'] . 
            "&cRenewals=N"  . 
            "&iDeliveryMethod=" . $data['deliveryMethod'];
    
    $_SESSION['request']->updateApiCommunication("GetOrdersEx request: " . $url . "\n");
    
    curl_setopt($ch, CURLOPT_URL, $url);  // was "example.com"
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
    $response = curl_exec($ch); 
    curl_close($ch); 
    //dumpInfo($url, $response); 
    
    $_SESSION['request']->updateApiCommunication("GetOrdersEx response: " . $response . "\n");
    
    
    //$response = (string) @file_get_contents($url);
    
    
    
    
//    $responseData = print_r($response,true);
//    $_SESSION['request']->updateApiCommunication("GetOrdersEx Raw Response: " . $responseData . "\n");
    
    
    if (strpos($response,'<GetOrdersExResults') == false) {  
        //echo "response is empty";
        //return array(false,$orderId,"This member number did not return an order.");
        $_SESSION['request']->updateApiCommunication("GetOrdersEx: No orders returned from Tessitura API.\n");
        errorPageSales("No orders found. Please see a ticket desk for assistance.");
    }
    
//    echo "<textarea rows=60 cols=80>";
//    print_r($response);
//    echo "</textarea>";
//    exit();

    
    $startPos = strpos($response, "<DataSet");
    $endPos = strpos($response, "<diffgr:diffgram");
    $response = substr($response,0,$startPos) . substr($response,$endPos);
    $response = substr($response,0,strpos($response, "<GetOrdersExResults")) . substr($response,strpos($response, "<Order"));
    $response = substr($response,0,strpos($response, "</GetOrdersExResults>")) . substr($response,strpos($response, "</diffgr:diffgram>"),18);

    /*$response = "<?xml version='1.0' encoding='utf-8'?>
  <diffgr:diffgram xmlns:msdata='urn:schemas-microsoft-com:xml-msdata' xmlns:diffgr='urn:schemas-microsoft-com:xml-diffgram-v1'>
      <Order diffgr:id='Order1' msdata:rowOrder='0'>
        <customer_no>28527</customer_no>
        <order_no>41202</order_no>
        <order_dt>2015-07-23T08:52:24.05-04:00</order_dt>
        <mos>7</mos>
        <tot_due_amt>10.0000</tot_due_amt>
        <tot_paid_amt>10.0000</tot_paid_amt>
        <status>4</status>
        <create_dt>2015-07-23T08:56:50.47-04:00</create_dt>
        <sessionkey>IWQ6U8020ECKKU7M10S8XNH4TV2DEJNAE243P4QTB3Y7M7LYA8NSB3A3YJUY7008</sessionkey>
        <ok_to_print>Y</ok_to_print>
        <unprinted_seats>0</unprinted_seats>
      </Order>
      <Order diffgr:id='Order2' msdata:rowOrder='1'>
        <customer_no>28527</customer_no>
        <order_no>42724</order_no>
        <order_dt>2015-07-23T23:15:18.473-04:00</order_dt>
        <mos>7</mos>
        <tot_due_amt>10.0000</tot_due_amt>
        <tot_paid_amt>10.0000</tot_paid_amt>
        <status>4</status>
        <create_dt>2015-07-23T23:17:28.36-04:00</create_dt>
        <sessionkey>71NDAQXC3FKVRLOV10VFD1LK3N1EOAUPA644SHIX72SGUC8J42YL01R8M4OCN971</sessionkey>
        <ok_to_print>Y</ok_to_print>
        <unprinted_seats>0</unprinted_seats>
      </Order>
  </diffgr:diffgram>";*/
    
    
    $xml =  simplexml_load_string($response);
//    echo "<pre>";
//    echo "getOrdersEX Response: \n";
//    print_r($xml);
//    echo "</pre>";
//    exit();
    
    
    $orders = array();
    if (empty($xml)) {  
       //echo "didn't return an order";
       //exit("Didn't return an order");
        $_SESSION['request']->updateApiCommunication("GetOrdersEx did not return data.\n");
        return array(false,$orderId,"This member number did not return an order. Please see a ticket desk for assistance.");
       
    } else {
       $ordersMeetingCriteria = 0;
       for($i = 0;$i < sizeof($xml->Order);$i++){
       //for($i = 0;$i < sizeof($xml);$i++){
//           echo "<pre>";
//           print_r($xml->Order[$i]->order_no);
//           echo "</pre>";
//           echo "order_no: " . $xml->Order[$i]->order_no . "<br>";
           //if ($xml->Order[$i]->ok_to_print == 'Y' && $xml->Order[$i]->unprinted_seats > 0){
           //if ($xml->Order[$i]->ok_to_print == 'Y'){
           if ($xml->Order[$i]->ok_to_print == 'Y'){ 
               $ordersMeetingCriteria++;
               array_push($orders,$xml->Order[$i]->order_no);
           }
           
       }
       //exit('done');
       // at this point, I have an array of orders. 
       //if ($ordersMeetingCriteria > 0){
            //foreach($orders as $oneOrder){
                // getTickets for $oneOrder
                //if ($oneOrder[0] <> '406') {
                    //$data['ordernumber'] = $oneOrder[0];
                    //echo "order number: " . $data['ordernumber'];
                //}
                //getTicketPrintInformation($data);
           // }
       //} else {
         //  return $orders;
           //return array(false,$orderId,"This member number did not return an order.");
       //}
    
        
    } 
    
//    echo "<pre>";
//    echo "orders: \n";
//    print_r($orders);
//    echo "</pre>";
//    exit();
    
    
    return $orders;    
    
    
   
    
    
    /*
    $data['websessionId'] = '';
    
    $url = "http://tessapptest.mfa.org/TessituraWebAPI/Tessitura.asmx/GetOrdersEx?sWebSessionId=" . $data['websessionId'] . 
            "&iOrderNumber=" . $data['ordernumber'] . 
            "&sPhoneNumber=" . 
            "&cPrint=" . $data['print'] . 
            "&dtStartDate=" . $data['startDate'] . 
            "&dtEndDate=" . $data['endDate'] . 
            "&iSeason=" . $data['season'] . 
            "&iCustomerNumber=" . $data['customerNbr'] . 
            "&iMos=" . $data['mos'] . 
            "&cRenewals=N"  . 
            "&iDeliveryMethod=" . $data['deliveryMethod'];
    
    $response = (string) file_get_contents($url);
    
    $startPos = strpos($response, "<DataSet");
    $endPos = strpos($response, "<diffgr:diffgram");
    $response = substr($response,0,$startPos) . substr($response,$endPos);
    $response = substr($response,0,strpos($response, "<GetOrdersExResults")) . substr($response,strpos($response, "<Order"));
    $response = substr($response,0,strpos($response, "</GetOrdersExResults>")) . substr($response,strpos($response, "</diffgr:diffgram>"),18);
    $xml = simplexml_load_string($response);
    
    
    
    
    $orders = array();
    if (empty($xml)) {  
       //echo "didn't return an order";
       //exit("Didn't return an order");
       return array(false,$orderId,"This member number did not return an order.");
       
    } else {
       $ordersMeetingCritera = 0;
       for($i = 0;$i < sizeof($xml->Order);$i++){
            if ($xml->Order[$i]->ok_to_print == 'Y'){
               $ordersMeetingCriteria++;
               array_push($orders,$xml->Order[$i]->order_no);
           }
           
       }
       if ($ordersMeetingCriteria > 0){
            foreach($orders as $oneOrder){
                // getTickets for $oneOrder
                if ($oneOrder[0] <> '406') {
                    $data['ordernumber'] = $oneOrder[0];
                    echo "order number: " . $data['ordernumber'];
                }
                getTicketPrintInformation($data);
            }
       } else{ 
           return array(false,$orderId,"This member number did not return an order.");
       }
    echo "<pre>";
    echo "orders: \n";
    print_r($orders);
    echo "</pre>";
    exit();
        
    }    
    
    
  return array(true,$orderId,"");  */
  
  
  
  
  
  
  
}


//function getAllOrdersForAcct(){
//    
//    $returnArray = array();
//    
//    
//    $orders = getOrdersEx($data);
//    if (sizeof($orders) < 1){
//        return array(false,$orderId,"This member number did not return an order.");
//    }
//    
//    
//    
//    
//    
//    
//    
//    
//    
//    
//    
//    
//    
//    $returnArray = getOrdersEx($data);
//    
//    $orders = array();
//    if (empty($xml)) {  
//       //echo "didn't return an order";
//       //exit("Didn't return an order");
//       return array(false,$orderId,"This member number did not return an order.");
//       
//    } else {
//       $ordersMeetingCritera = 0;
//       for($i = 0;$i < sizeof($xml->Order);$i++){
//           //echo "<pre>";
//           //print_r($xml->Order[$i]->order_no);
//           //echo "</pre>";
//           //echo "order_no: " . $xml->Order[$i]->order_no . "<br>";
//           //if ($xml->Order[$i]->ok_to_print == 'Y' && $xml->Order[$i]->unprinted_seats > 0){
//           if ($xml->Order[$i]->ok_to_print == 'Y'){
//               $ordersMeetingCriteria++;
//               array_push($orders,$xml->Order[$i]->order_no);
//           }
//           
//       }
//       //exit('done');
//       // at this point, I have an array of orders. 
//       if ($ordersMeetingCriteria > 0){
//            foreach($orders as $oneOrder){
//                // getTickets for $oneOrder
//                if ($oneOrder[0] <> '406') {
//                    $data['ordernumber'] = $oneOrder[0];
//                    echo "order number: " . $data['ordernumber'];
//                }
//                $returnArray = getTicketPrintInformation($data);
//            }
//       } else{ 
//           return array(false,$orderId,"This member number did not return an order.");
//       }
//    echo "<pre>";
//    echo "orders: \n";
//    print_r($orders);
//    echo "</pre>";
//    exit();
//        
//    }    
//    
//    
//}



function getTickets($data){
    //$_SESSION['output'] = "Just entered getTickets() Time: " . date('m/d/Y H:i:s') . "<br>";
    
    getNewSessionKey();
    
    $returnArray = array();
    
    if ($_SESSION['lookup'] == "orderNo") {
//        $returnArray = getConstituentsEx($data);
        $approved = true;
    } else if ($_SESSION['lookup'] == "accountNo") {
        $returnArray = getOrdersEx($data);
        $approved = $returnArray[0];
        //echo "<pre>";
        //print_r($returnArray);
        //echo "</pre>";
        //exit('done');
        
        if ($approved === false){
            return($returnArray);
        } 
    } else if ($_SESSION['lookup'] == "CC") {
        $returnArray = getConstituentsEx($data);
        if ($approved == true) { 
            $returnArray = getOrdersEx($data);
        }
        $approved = $returnArray[0];
    }
    
    
    if ($approved == true) {  //?? $approved == true did not work
        //exit("hello");
        $returnArray = getTicketPrintInformation($data);
        if ($returnArray[0] == true){
            $approved = $returnArray[0];
            $orderId = $returnArray[1];
            $errorMessage = $returnArray[2];
            return array($approved,$orderId,$errorMessage);
    
        } else{
            //exit("unable to get ticket print information");
            $approved = false;
            $orderId = '';
            $errorMessage = 'Error getting ticket print information.';
            return array($approved,$orderId,$errorMessage);
        }
    } else {
        //exit("order did not pull up");
        $approved = false;
        $orderId = '';
        $errorMessage = 'Error: order did not pull up';
        return array($approved,$orderId,$errorMessage);
        
        
        
    }
    
    
    
    

}


function getPerformancesOld($data) {
    $client = new SoapClient("https://tesstestgw1.empmuseum.org/tessitura.asmx?WSDL");
    
    $returnArray = getPerformancesEx4($client,$data);
    
    getPerformanceDetailWithDiscountingEx($client,$data);
    
    $approved = $returnArray[0];
    $orderId = $returnArray[1];
    $errorMessage = $returnArray[2];
    

    return array($approved,$orderId,$errorMessage);

    
}





//This returns dataset
function getConstituentsExOld($data) {  //csn 20160424
    
    //$client = new SoapClient("http://dbradp6.tessituranetworkramp.com/wwl1api/tessitura.asmx?WSDL");
//    $client = new SoapClient("https://tesstestgw1.empmuseum.org/tessitura.asmx?WSDL");
    
    //$orderId = $data['ordernumber'];
    //$lastName = $data['lastname'];
    
    
    
    // NullReferenceException
    /*$params = array( 
            'sWebSessionId' => $_SESSION['sessionKey'],
            'sCCNumber' => $data['ccnumber'],
            'sPhoneNumber' => $data['phonenumber'],
            'iCustomerServiceNumber' => $data['customerservicenumber'],
            'iOrderNumber' => $data['ordernumber'],
            'sEmailAddress' => $data['emailaddress'],
            'sLastName' => $data['lastname']
    );*/
    
    
    // get the new session key.
//    $url = "http://tessappprod.mfa.org/TessituraWebAPI/Tessitura.asmx/GetNewSessionKey?sIP=" . $_SESSION['loginData']['IP'];
//    $response = (string) file_get_contents($url);
//    $startPos = strpos($response, "tessiturasoftware") + 24;
//    $endPos = strpos($response, "string>") - 2;
//    $_SESSION['sessionKey'] = substr($response,$startPos,$endPos - $startPos);
    
    //$data['ccnumber'] = '4264289828774820'; //csntest
    //$_REQUEST['ccLastName'] = 'mosier';  //csntest
    // this works
    $params = array( 
            'sWebSessionId' => '',
            'sCCNumber' => $data['ccnumber'],
            'sPhoneNumber' => '',
            'iCustomerServiceNumber' => '0',
            'iOrderNumber' => '0',
            'sEmailAddress' => '',
            'sLastName' => ''
    );
    
    
    $inputData = print_r($params,true);
    
    $_SESSION['request']->updateApiCommunication("GetConstituentsEx Input Data: " . $inputData . "\n");
    
    //exit('done.');
    
    
    
    /*$params = array( 
            'sWebSessionId' => $_SESSION['sessionKey'],
            'sCCNumber' => '',
            'sPhoneNumber' => $data['phonenumber'],
            'iCustomerServiceNumber' => $data['customerservicenumber'],
            'iOrderNumber' => $data['ordernumber'],
            'sEmailAddress' => $data['emailaddress'],
            'sLastName' => $data['lastname']
    );*/
    
    
    
    
    
    
    
    
    /*GetConstituentsEx(
     *      String sWebSessionId, 
     *      String sCCNumber, 
     *      String sPhoneNumber, 
     *      Int32 iCustomerServiceNumber, 
     *      Int32 iOrderNumber, 
     *      String sEmailAddress, 
     *      String sLastName)
    */
    
    
    
    
     /* $params = array( 
            'sWebSessionId' => $_SESSION['sessionKey'],
            'sCCNumber' => ' ',
            'sPhoneNumber' => ' ',
            'iCustomerServiceNumber' => 0,
            'iOrderNumber' => $data['ordernumber'],
            'sEmailAddress' => $data['emailaddress'],
            'sLastName' => $data['lastname']
    );*/
    
    
    
//    echo "<pre>";
//    echo "GetConstituentsEx Params: \n";
//    print_r($params);
////    print_r($_SESSION['client']);
////    print_r($data);
//    echo "</pre>";
//    exit();
    
//    ob_start();
    
    
    /*$logFile = '/ECommerceSessions/15864/GetConstituentsEx/xml_' . $_SESSION['cc'] . '_' . date("Ymd_His") . '.txt';
    $myFile = file_put_contents($logFile,"----------- " . date('Y-m-d H:i:s' . " --------------------\n\n\n\n\n"), FILE_APPEND);
    $paramsText = print_r($params,true);
    $myFile = file_put_contents($logFile, $paramsText, FILE_APPEND);*/
    
    //$client = new SoapClient("http://tessappprod.mfa.org/TessituraWebAPI/Tessitura.asmx?WSDL"); 
    //$response=$_SESSION['client']->GetConstituentsEx($params); 
    
    //$response=$client->GetConstituentsEx($params); 
    $response=$_SESSION['client']->GetConstituentsEx($params);
    
    $responseData = print_r($response,true);
    $_SESSION['request']->updateApiCommunication("GetConstituentsEx raw response: " . $responseData . "\n");
    
    
    
    
//    echo "<pre>";
//    echo "GetConstituentsEx Params: \n";
//    print_r($params);
//    echo "</pre>";
//    
//    echo "<pre>";
//    echo "GetConstituentsEx Response: \n";
//    print_r($response);
//    echo "</pre>";
//    exit();
    
    //$myFile = file_put_contents($logFile, serialize($response), FILE_APPEND);
//    $list = ob_get_contents(); // Store buffer in variable
//    ob_end_clean(); // End buffering and clean up
    
    
    //try {
    //    $response=$_SESSION['client']->GetConstituentsEx($params); 
    //} catch (SoapFault $e){
    //    //exit('Caught Exception: ' . $e->getMessage());
    //    echo "<pre>";
    //    print_r($e);
    //    echo "</pre>";
    //    exit;
    //    
    //}
    
    //if (strstr($response->GetConstituentsExResult->any,"Fatal")){
    //    exit('caught a fatal exception');
    //}
    
    //if (strstr($response->GetConstituentsExResult->any,"no seats")){
    //    exit('no seats found');
    // }
    //ob_start();
    //$xml = simplexml_load_string($response->GetConstituentsExResult->any);
    //$echoStuff = ob_get_contents(); // Store buffer in variable
    //ob_end_clean(); // End buffering and clean up
    
    //exit('echoStuff: ' . $echoStuff);
    
    $xml = simplexml_load_string($response->GetConstituentsExResult->any);
           
//    echo "<pre>";
//    echo "GetConstituentsEx Params: \n";
//    print_r($params);
//    echo "</pre>";
    
//    echo "<pre>";
//    echo "GetConstituentsEx xml: \n";
//    print_r($xml);
//    echo "</pre>";
//    exit();
   
        
    //if (strpos($xml,"no seats to be ticketed") > -1){
    //    exit('no seats');
    //}
   
//    echo "<pre>";
//    echo "GetConstituentsEx Response: \n";
//    print_r($response);
//    echo "</pre>";
    
    //echo "<pre>";
    //echo "GetConstituentsEx Response Any: \n";
    //$any = print_r($response->GetConstituentsExResult->any,true);
    //if (strpos($any,"no seats to be ticketed") > -1){
    //    exit('no seats');
    //}
    //echo "any: " . $any;
    //echo "</pre>";
    
//    echo "<pre>";
//    echo "xml: \n";
//    print_r($xml);
//    echo "</pre>";
    
    
   if (empty($xml)) {  
//       echo "didn't return an order";
//       exit("Didn't return an order");
       $_SESSION['request']->updateApiCommunication("GetConstituentsEx did not return any data" . "\n");
       
       return array(false,$constituentId,"Did not return an order. Please see a ticket desk for assistance.");
       
   } else {
       $constituentId = '';
       foreach ($xml->GetConstituentsExResults->Constituent as $oneConstituent){
            $oneConstituentData = print_r($oneConstituent,true);
            $_SESSION['request']->updateApiCommunication("GetConstituentsEx oneConstituent: " . $oneConstituentData);
            $constituentId = $oneConstituent->customer_no;
            //echo "constituentID: " . $constituentId;
    //       if (strtolower($xml->GetConstituentsExResults->Constituent->lname) != trim(strtolower($_SESSION['lastName']))) {
           //echo "Last name did not match";
           //echo "sessionLastName: " . $_SESSION['lastname'];
           //exit("Last name did not match");
           $forSort = $oneConstituent->for_sort;
           $lastName = $oneConstituent->lname;
           $slashPos = strpos($forSort,'/');
           //if (strtolower(substr($forSort,0,$slashPos)) == trim(strtolower($_REQUEST['ccLastName']))) {
           $pos = strpos(strtolower($lastName),strtolower($_REQUEST['ccLastName']));
           if (strtolower(substr($forSort,0,$slashPos)) == trim(strtolower($_REQUEST['ccLastName'])) || $pos !== false) {
               //exit("last name matches!!" . "lastname :" . $lastName . "ccLastName: " . $_REQUEST['ccLastName']);
               $_SESSION['request']->updateApiCommunication("GetConstituentsEx last name matches.");
               return array(true,$constituentId,"Last name matches.");
            } 
//            else {
//                return array(false,$constituentId,"Last name did not match.");
//            }
       } 
       $_SESSION['request']->updateApiCommunication("GetConstituentsEx - last name did not match any constituents found. name entered: " . $_REQUEST['ccLastName']);
       return array(false,$constituentId,"No order found. <br> Please enter last name associated with this order. <br> Name entered: '" . $_REQUEST['ccLastName'] . "' does not match.");
   }
   
  //exit("pass constituents");         
  //return array(true,$constituentId,"");  
    
        
  
}



//This returns dataset
function getConstituentsEx($data) {
    
    //$client = new SoapClient("http://dbradp6.tessituranetworkramp.com/wwl1api/tessitura.asmx?WSDL");
//    $client = new SoapClient("https://tesstestgw1.empmuseum.org/tessitura.asmx?WSDL");
    
    //$orderId = $data['ordernumber'];
    //$lastName = $data['lastname'];
    
    
    
    // NullReferenceException
    /*$params = array( 
            'sWebSessionId' => $_SESSION['sessionKey'],
            'sCCNumber' => $data['ccnumber'],
            'sPhoneNumber' => $data['phonenumber'],
            'iCustomerServiceNumber' => $data['customerservicenumber'],
            'iOrderNumber' => $data['ordernumber'],
            'sEmailAddress' => $data['emailaddress'],
            'sLastName' => $data['lastname']
    );*/
    
    
    // get the new session key.
//    $url = "http://tessappprod.mfa.org/TessituraWebAPI/Tessitura.asmx/GetNewSessionKey?sIP=" . $_SESSION['loginData']['IP'];
//    $response = (string) file_get_contents($url);
//    $startPos = strpos($response, "tessiturasoftware") + 24;
//    $endPos = strpos($response, "string>") - 2;
//    $_SESSION['sessionKey'] = substr($response,$startPos,$endPos - $startPos);
    
    //$data['ccnumber'] = '4264289828774820'; //csntest
    //$_REQUEST['ccLastName'] = 'mosier';  //csntest
    // this works
//    $params = array( 
//            'sWebSessionId' => '',
//            'sCCNumber' => $data['ccnumber'],
//            'sPhoneNumber' => '',
//            'iCustomerServiceNumber' => '0',
//            'iOrderNumber' => '0',
//            'sEmailAddress' => '',
//            'sLastName' => ''
//    );
//    
//    
//    $inputData = print_r($params,true);
//    
//    $_SESSION['request']->updateApiCommunication("GetConstituentsEx Input Data: " . $inputData . "\n");
    
    
    $ch = curl_init(); 
    $url = $_SESSION['soapUrlDirect'] . "/GetConstituentsEx?" .
            "sWebSessionId=" .  
            "&sCCNumber=" . $data['ccnumber'] .
            "&sPhoneNumber=" . 
            "&iCustomerServiceNumber=0" .
            "&iOrderNumber=0" .
            "&sEmailAddress=" .
            "&sLastName=";
    $_SESSION['request']->updateApiCommunication("GetConstituentsEx request: " . $url . "\n");    
    
    //exit('done.');
    
    curl_setopt($ch, CURLOPT_URL, $url);  // was "example.com"
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
    $response = curl_exec($ch); 
    curl_close($ch); 
    //dumpInfo($url, $response); 
    
    $_SESSION['request']->updateApiCommunication("GetConstituentsEx response: " . $response . "\n");
    
    /*$params = array( 
            'sWebSessionId' => $_SESSION['sessionKey'],
            'sCCNumber' => '',
            'sPhoneNumber' => $data['phonenumber'],
            'iCustomerServiceNumber' => $data['customerservicenumber'],
            'iOrderNumber' => $data['ordernumber'],
            'sEmailAddress' => $data['emailaddress'],
            'sLastName' => $data['lastname']
    );*/
    
    
    
    
    
    
    
    
    /*GetConstituentsEx(
     *      String sWebSessionId, 
     *      String sCCNumber, 
     *      String sPhoneNumber, 
     *      Int32 iCustomerServiceNumber, 
     *      Int32 iOrderNumber, 
     *      String sEmailAddress, 
     *      String sLastName)
    */
    
    
    
    
     /* $params = array( 
            'sWebSessionId' => $_SESSION['sessionKey'],
            'sCCNumber' => ' ',
            'sPhoneNumber' => ' ',
            'iCustomerServiceNumber' => 0,
            'iOrderNumber' => $data['ordernumber'],
            'sEmailAddress' => $data['emailaddress'],
            'sLastName' => $data['lastname']
    );*/
    
    
    
//    echo "<pre>";
//    echo "GetConstituentsEx Params: \n";
//    print_r($params);
////    print_r($_SESSION['client']);
////    print_r($data);
//    echo "</pre>";
//    exit();
    
//    ob_start();
    
    
    /*$logFile = '/ECommerceSessions/15864/GetConstituentsEx/xml_' . $_SESSION['cc'] . '_' . date("Ymd_His") . '.txt';
    $myFile = file_put_contents($logFile,"----------- " . date('Y-m-d H:i:s' . " --------------------\n\n\n\n\n"), FILE_APPEND);
    $paramsText = print_r($params,true);
    $myFile = file_put_contents($logFile, $paramsText, FILE_APPEND);*/
    
    //$client = new SoapClient("http://tessappprod.mfa.org/TessituraWebAPI/Tessitura.asmx?WSDL"); 
    //$response=$_SESSION['client']->GetConstituentsEx($params); 
    
    //$response=$client->GetConstituentsEx($params); 
//    $response=$_SESSION['client']->GetConstituentsEx($params);
//    
//    $responseData = print_r($response,true);
//    $_SESSION['request']->updateApiCommunication("GetConstituentsEx raw response: " . $responseData . "\n");
    
    
    
    
//    echo "<pre>";
//    echo "GetConstituentsEx Params: \n";
//    print_r($params);
//    echo "</pre>";
//    
//    echo "<pre>";
//    echo "GetConstituentsEx Response: \n";
//    print_r($response);
//    echo "</pre>";
//    exit();
    
    //$myFile = file_put_contents($logFile, serialize($response), FILE_APPEND);
//    $list = ob_get_contents(); // Store buffer in variable
//    ob_end_clean(); // End buffering and clean up
    
    
    //try {
    //    $response=$_SESSION['client']->GetConstituentsEx($params); 
    //} catch (SoapFault $e){
    //    //exit('Caught Exception: ' . $e->getMessage());
    //    echo "<pre>";
    //    print_r($e);
    //    echo "</pre>";
    //    exit;
    //    
    //}
    
    //if (strstr($response->GetConstituentsExResult->any,"Fatal")){
    //    exit('caught a fatal exception');
    //}
    
    //if (strstr($response->GetConstituentsExResult->any,"no seats")){
    //    exit('no seats found');
    // }
    //ob_start();
    //$xml = simplexml_load_string($response->GetConstituentsExResult->any);
    //$echoStuff = ob_get_contents(); // Store buffer in variable
    //ob_end_clean(); // End buffering and clean up
    
    //exit('echoStuff: ' . $echoStuff);
    $startPos = strpos($response, "<DataSet");
    $endPos = strpos($response, "<diffgr:diffgram");
    $response = substr($response,0,$startPos) . substr($response,$endPos);
    //exit($response);
    $response = substr($response,0,strpos($response, "<GetConstituentsExResults")) . substr($response,strpos($response, "<Constituent"));
    $response = substr($response,0,strpos($response, "</GetConstituentsExResults>")) . substr($response,strpos($response, "</diffgr:diffgram>"),18);
    
    $xml = simplexml_load_string($response);
    
    
//    $xml = simplexml_load_string($response->GetConstituentsExResult->any);
           
//    echo "<pre>";
//    echo "GetConstituentsEx Params: \n";
//    print_r($params);
//    echo "</pre>";
    
//    echo "<pre>";
//    echo "GetConstituentsEx xml: \n";
//    print_r($xml);
//    echo "</pre>";
//    exit();
   
        
    //if (strpos($xml,"no seats to be ticketed") > -1){
    //    exit('no seats');
    //}
   
//    echo "<pre>";
//    echo "GetConstituentsEx Response: \n";
//    print_r($response);
//    echo "</pre>";
    
    //echo "<pre>";
    //echo "GetConstituentsEx Response Any: \n";
    //$any = print_r($response->GetConstituentsExResult->any,true);
    //if (strpos($any,"no seats to be ticketed") > -1){
    //    exit('no seats');
    //}
    //echo "any: " . $any;
    //echo "</pre>";
    
//    echo "<pre>";
//    echo "xml: \n";
//    print_r($xml);
//    echo "</pre>";
    
    
   if (empty($xml)) {  
//       echo "didn't return an order";
//       exit("Didn't return an order");
       $_SESSION['request']->updateApiCommunication("GetConstituentsEx did not return any data.");
       return array(false,$constituentId,"Did not return an order. Please see a ticket desk for assistance.");
       
   } else {
       $constituentId = '';
       foreach ($xml->Constituent as $oneConstituent){
            $oneConstituentData = print_r($oneConstituent,true);
            $_SESSION['request']->updateApiCommunication("GetConstituentsEx oneConstituent: " . $oneConstituentData);
            $constituentId = $oneConstituent->customer_no;
            //echo "constituentID: " . $constituentId;
    //       if (strtolower($xml->GetConstituentsExResults->Constituent->lname) != trim(strtolower($_SESSION['lastName']))) {
           //echo "Last name did not match";
           //echo "sessionLastName: " . $_SESSION['lastname'];
           //exit("Last name did not match");
           $forSort = $oneConstituent->for_sort;
           $lastName = $oneConstituent->lname;
           $slashPos = strpos($forSort,'/');
           //if (strtolower(substr($forSort,0,$slashPos)) == trim(strtolower($_REQUEST['ccLastName']))) {
           $pos = strpos(strtolower($lastName),strtolower($_REQUEST['ccLastName']));
           if (strtolower(substr($forSort,0,$slashPos)) == trim(strtolower($_REQUEST['ccLastName'])) || $pos !== false) {
               //exit("last name matches!!" . "lastname :" . $lastName . "ccLastName: " . $_REQUEST['ccLastName']);
               $_SESSION['request']->updateApiCommunication("GetConstituentsEx last name matches.");
               return array(true,$constituentId,"Last name matches.");
            } 
//            else {
//                return array(false,$constituentId,"Last name did not match.");
//            }
       }
       //exit('last name did not match');
       $_SESSION['request']->updateApiCommunication("GetConstituentsEx - last name did not match any constituents found. name entered: " . $_REQUEST['ccLastName']);
       return array(false,$constituentId,"No order found. <br> Please enter last name associated with this order. <br> Name entered: '" . $_REQUEST['ccLastName'] . "' does not match.");
   }
   
  //exit("pass constituents");         
  //return array(true,$constituentId,"");  
    
        
  
}



//Returns fgl.
function getTicketPrintInformationOld($data) {  //csn 20160419
    $tickets = array();
    $data['websessionId'] = '';
    
    
//    echo "<pre>";
//    print_r($data);
//    echo "<pre>";
//    exit;
    
    
    if ($_SESSION['reprint'] == false) {
        $data['reprint'] = 'N'; 
    } else {
        $data['reprint'] = 'Y'; 
    }
    
    $params = array( 
            'sWebSessionID' =>  '',
            'iOrderId' => $data['ordernumber'],
            'sHeaderDesign' => $data['headerdesign'],
            'sTicketDesign' => $data['ticketdesign'],
            'cReceipt' => $data['receipt'],
            'cReprint' => $data['reprint']
    );
    
    $xml = '';
    try{
//        if ($data['ordernumber'] == '161638'){
//        exit('found it.');
//    }
        
       $inputData = print_r($params,true); 
       $_SESSION['request']->updateApiCommunication("GetTicketPrintInformation Input Data: " . $inputData . "\n"); 
        
        if (file_exists('/ECommerceSessions/15864/GetTicketPrintInformation/xml_test.txt')){
            //echo "reading file instead.";
            $response = unserialize(file_get_contents('/ECommerceSessions/15864/GetTicketPrintInformation/xml_test.txt'));
//            echo "<textarea rows=20 cols=80>";
//            echo serialize($response);
//            echo "</textarea>";
//            exit;
        } else {
            $response=$_SESSION['client']->GetTicketPrintInformation($params);
//            echo "<textarea rows=20 cols=80>";
//            echo serialize($response);
            //$myFile = file_put_contents('/ECommerceSessions/15864/GetTicketPrintInformation/xml_174978_20151016_125600.txt', serialize($response));
            $myFile = file_put_contents('/ECommerceSessions/15864/GetTicketPrintInformation/xml_' . date("Ymd_His") . '.txt', serialize($response));
//            echo "</textarea>";
//            exit;
        }
        //exit('hello');
        
        
        $responseData = print_r($response,true);
        $_SESSION['request']->updateApiCommunication("GetTicketPrintInformation response: " . $responseData . "\n"); 
        
//        echo "<pre>";
//        echo "getTicketPrintInformation Response: \n";
//        print_r($response);
//        echo "</pre>";
//        exit();
        
        $xml = @simplexml_load_string($response->GetTicketPrintInformationResult->any);
        
    } catch (SoapFault $e){
        //exit('got error' . $e->getMessage());
        $responseData = print_r($e->getMessage(),true);
        $_SESSION['request']->updateApiCommunication("GetTicketPrintInformation SoapFault response: " . $responseData . "\n"); 
        
        if (strpos($e->getMessage(),"no seats to be ticketed")){
            
            //echo "No seats to be ticketed for order #" . $data['ordernumber'] . "<br>";
            //array_push($noSeatOrders, $data['ordernumber']);
            $_SESSION['noSeatOrders']++;
            return $tickets;
//            errorPage("No seats to be ticketed for order #" . $data['ordernumber'] . "<br>");
//            exit('got no seats: ' . $e->getMessage());
            //return error code for no seats.
            //return array
        } //else {
           
        //}
        
    }
    
//    echo "<textarea rows=200 cols=80>";
//    print_r($xml);
//    echo "</textarea>";
//    exit();
    
//    echo "<pre>";
//    print_r($xml);
//    echo "</pre>";
//    exit();
     
//    echo "<pre>";
//    echo "getTicketPrintInformation Params: \n";
//    print_r($params);
//    echo "</pre>";
//    exit();
        
       
//    echo "<pre>";
//    echo "getTicketPrintInformation Response: \n";
//    print_r($response);
//    echo "</pre>";
//    exit();
//    
//    
//    

    
    //echo "<pre>";
    //echo "getTicketPrintInformation Response Any: \n";
    //print_r($response->GetTicketPrintInformationResult->any);
    //echo "</pre>";
    
//    echo "<pre>";
//    echo "xml: \n";
//    print_r($xml);
//    echo "</pre>";
//    exit();
    
//   $url = "http://tessappprod.mfa.org/TessituraWebAPI/Tessitura.asmx/GetTicketPrintInformation?sWebSessionID=" . $data['websessionId'] . 
//        "&iOrderId=" . $data['ordernumber'] . 
//        "&sHeaderDesign=" . $data['headerdesign'] .
//        "&sTicketDesign=" . $data['ticketdesign'] . 
//        "&cReceipt=" . $data['receipt'] . 
//        "&cReprint=" . $data['reprint'];
//    
//    $response = (string) @file_get_contents($url);
//    
//    if (strpos($response,'<GetTicketPrintInformationResults') == false) {  
//        //echo "response is empty";
//        //return array(false,$orderId,"This member number did not return an order.");
//        errorPage("This order number not found.");
//    }
//    
//    echo "<textarea rows=60 cols=80>";
//    print_r($response);
//    echo "</textarea>";
//    exit();
//    echo "<textarea rows=60 cols=80>";
//    print_r($xml);
//    echo "</textarea>";
//    exit();
    
    $uniqueSliNos = array();
    for ($i=0;$i<sizeof($xml->GetTicketPrintInformationResults->Table);$i++){
        //$_SESSION['tickets'][$i]['sli_no'] = (string) $xml->GetTicketPrintInformationResults->Table[$i]->sli_no;
        $tempSli =  (string) $xml->GetTicketPrintInformationResults->Table[$i]->sli_no[0];
        // search for tempSli in uniqueSliNos. If not found, add.
        $found = 'no';
        foreach($uniqueSliNos as $oneUniqueSliNo){
            if ($oneUniqueSliNo == $tempSli){
                $found = 'yes';
                break;
            } 
        }
        if ($found == 'no'){
                /*echo "Not found:<br>";
                echo "before push:<br>";
                echo "<pre>";
                echo "uniqueSliNos: <br>";
                print_r($uniqueSliNos);
                echo "</pre>";
                
                echo "oneUniqueSliNo: " . $oneUniqueSliNo . "<br>"; 
                echo "tempSli: " . $tempSli . "<br><br><br><br>";
                */
                array_push($uniqueSliNos,$tempSli);
                //$_SESSION['tickets'][$i]['sli_no'] = (string) $xml->GetTicketPrintInformationResults->Table[$i]->sli_no; //?
                
                
//                echo "After push:<br>";
//                echo "<pre>";
//                echo "uniqueSliNos: <br>";
//                print_r($uniqueSliNos);
//                echo "</pre>";
//                exit;
        }

      
        //$_SESSION['performances'][$i]['perfNo'] = (string) $xml->GetPerformancesEx4Result->Performance[$i]->perf_no;
        //$_SESSION['performances'][$i]['prices'] = getPrices($_SESSION['performances'][$i]['perfNo'],$data ); // get all prices for this performance.
    }
    // at this point, we have uniqueSliNos.
    
//    echo "<pre>";
//    echo "uniqueSliNos: \n";
//    print_r($uniqueSliNos);
//    echo "</pre>";
//    exit();
    
    
    $ticketElement = 0;
    $lastFullTicketElement = 0;
    foreach ($uniqueSliNos as $oneUniqueSliNo){
        // we have oneUniqueSliNo. 
        // find ticketno that matches.
        //echo "searching for oneUniqueSliNo: " . $oneUniqueSliNo . "<br>";
        
        $tickets[$ticketElement]['sliNo'] = $oneUniqueSliNo;
        for ($i=0;$i<sizeof($xml->GetTicketPrintInformationResults->Table);$i++){
            $tempSli =  (string) $xml->GetTicketPrintInformationResults->Table[$i]->sli_no[0];
            $description = (string) $xml->GetTicketPrintInformationResults->Table[$i]->description[0];
            $val = (string) $xml->GetTicketPrintInformationResults->Table[$i]->val[0];
            $designNo = (string) $xml->GetTicketPrintInformationResults->Table[$i]->design_no[0];

//            if ($tempSli == $oneUniqueSliNo && $description == 'Ticket No'){
//                //exit('FOUND!!!! tempSli: ' . $tempSli . '<br>val: ' . $val . '<br>description: ' . $description . '<br>');
//                $_SESSION['tickets'][$ticketElement]['designNo'] = $val;
//            }
            if (($tempSli == $oneUniqueSliNo && $description == 'Ticket No') || ($tempSli == $oneUniqueSliNo && $description == 'Composite Ticket No')){
                //exit('FOUND!!!! tempSli: ' . $tempSli . '<br>val: ' . $val . '<br>description: ' . $description . '<br>');
                $tickets[$ticketElement]['ticketNo'] = $val;
                $tickets[$ticketElement]['designNo'] = $designNo;
            }
            if ($tempSli == $oneUniqueSliNo && $description == 'Constituent Id'){
                //exit('FOUND!!!! tempSli: ' . $tempSli . '<br>val: ' . $val . '<br>description: ' . $description . '<br>');
                $tickets[$ticketElement]['constituentId'] = $val;
            }
            if ($tempSli == $oneUniqueSliNo && $description == 'Order No'){
                //exit('FOUND!!!! tempSli: ' . $tempSli . '<br>val: ' . $val . '<br>description: ' . $description . '<br>');
                $tickets[$ticketElement]['orderNo'] = $val;
            }
            if ($tempSli == $oneUniqueSliNo && $description == 'Order Date'){
                //exit('FOUND!!!! tempSli: ' . $tempSli . '<br>val: ' . $val . '<br>description: ' . $description . '<br>');
                $tickets[$ticketElement]['orderDate'] = $val;
            }
            if ($tempSli == $oneUniqueSliNo && $description == 'Perf. Description'){
                //exit('FOUND!!!! tempSli: ' . $tempSli . '<br>val: ' . $val . '<br>description: ' . $description . '<br>');
                $tickets[$ticketElement]['perfDescription'] = $val;
            }
            if ($tempSli == $oneUniqueSliNo && $description == 'Pkg Description'){
                //exit('FOUND!!!! tempSli: ' . $tempSli . '<br>val: ' . $val . '<br>description: ' . $description . '<br>');
                $tickets[$ticketElement]['pkgDescription'] = $val;
            }
            if ($tempSli == $oneUniqueSliNo && $description == 'Price Type Short Desc'){
                //exit('FOUND!!!! tempSli: ' . $tempSli . '<br>val: ' . $val . '<br>description: ' . $description . '<br>');
                $tickets[$ticketElement]['priceTypeShortDesc'] = $val;
            }
            if ($tempSli == $oneUniqueSliNo && $description == 'Price Type Description'){
                //exit('FOUND!!!! tempSli: ' . $tempSli . '<br>val: ' . $val . '<br>description: ' . $description . '<br>');
                $tickets[$ticketElement]['priceTypeDesc'] = $val;
            }
            if ($tempSli == $oneUniqueSliNo && $description == 'Perf. Code'){
                //exit('FOUND!!!! tempSli: ' . $tempSli . '<br>val: ' . $val . '<br>description: ' . $description . '<br>');
                $tickets[$ticketElement]['perfCode'] = $val;
            }
            if ($tempSli == $oneUniqueSliNo && $description == 'Perf. Date'){
                //exit('FOUND!!!! tempSli: ' . $tempSli . '<br>val: ' . $val . '<br>description: ' . $description . '<br>');
                $tickets[$ticketElement]['perfDate'] = $val;
            }
            if ($tempSli == $oneUniqueSliNo && $description == 'Perf. Begin Time'){
                //exit('FOUND!!!! tempSli: ' . $tempSli . '<br>val: ' . $val . '<br>description: ' . $description . '<br>');
                $tickets[$ticketElement]['perfBeginTime'] = $val;
            }
            if ($tempSli == $oneUniqueSliNo && $description == 'Perf.Info-1'){
                //exit('FOUND!!!! tempSli: ' . $tempSli . '<br>val: ' . $val . '<br>description: ' . $description . '<br>');
                $tickets[$ticketElement]['perfInfo1'] = $val;
            }
            if ($tempSli == $oneUniqueSliNo && $description == 'Total Ticket Price'){
                //exit('FOUND!!!! tempSli: ' . $tempSli . '<br>val: ' . $val . '<br>description: ' . $description . '<br>');
                $tickets[$ticketElement]['totalTicketPrice'] = $val;
            }
            if ($tempSli == $oneUniqueSliNo && $description == 'Constituent Display Name'){
                //exit('FOUND!!!! tempSli: ' . $tempSli . '<br>val: ' . $val . '<br>description: ' . $description . '<br>');
                $tickets[$ticketElement]['constituentDisplayName'] = $val;
            }
            if ($tempSli == $oneUniqueSliNo && $description == 'User-Defined Element 1'){
                //exit('FOUND!!!! tempSli: ' . $tempSli . '<br>val: ' . $val . '<br>description: ' . $description . '<br>');
                $tickets[$ticketElement]['userDefinedElement1'] = $val;
            }
            if ($tempSli == $oneUniqueSliNo && $description == 'User-Defined Element 2'){
                //exit('FOUND!!!! tempSli: ' . $tempSli . '<br>val: ' . $val . '<br>description: ' . $description . '<br>');
                $tickets[$ticketElement]['userDefinedElement2'] = $val;
            }
            if ($tempSli == $oneUniqueSliNo && $description == 'Price Zone Description'){
                //exit('FOUND!!!! tempSli: ' . $tempSli . '<br>val: ' . $val . '<br>description: ' . $description . '<br>');
                $tickets[$ticketElement]['priceZoneDesc'] = $val;
            }
            
        }
        
        //exit('designNo: ' . $designNo);
        /*if ($designNo == '2071' || $designNo == '2092' || empty($designNo)) {
            if (empty($tickets[$ticketElement]['totalTicketPrice'])){
                 //if (empty($tickets[$ticketElement]['pkgDescription']) && !empty($tickets[$ticketElement]['perfDate'])){
                if (!empty($tickets[$ticketElement]['perfDate'])){    
                    $tickets[$lastFullTicketElement]['perfDate'] .= ',' . $tickets[$ticketElement]['perfDate'];
                    unset($tickets[$ticketElement]);
                    $ticketElement--;
                } else if(!empty($tickets[$ticketElement]['pkgDescription']) && !empty($tickets[$ticketElement]['perfDate'])){
                    $lastFullTicketElement = $ticketElement;
                }
            }
        }*/
        //explode(",", $tickets[$ticketElement]['perfDate'])
        
        
        $ticketElement++;
    }
    
    
    
//    echo "<pre>";
//    echo "xml: \n";
//    print_r($xml);
//    echo "</pre>";
    
    
    
    
    
    
//    echo "tickets before merge:<br>";
//    echo "<pre>";
//    print_r($tickets);
//    echo "</pre>";
   
    
//    // combine perf dates.
//    $newTickets = array();
//    $lastFullTicketElement = 0;
//    for ($i = 0;$i < sizeof($tickets);$i++){
//        if (empty($tickets[$i]['designNo']) && !empty($tickets[$i]['perfDate'])) {
//            $newTickets[$lastFullTicketElement]['perfDate'] .= ',' . $tickets[$i]['perfDate'];
//         } else {
//             array_push($newTickets, $tickets[$i]);
//             $lastFullTicketElement = $i;
//         }
//     }
     
    // combine perf dates.
    $newTickets = array();
    $lastFullTicketElement = 0;
    $today = date('Y-m-d');
    for ($i = 0;$i < sizeof($tickets);$i++){
        if (empty($tickets[$i]['designNo']) && !empty($tickets[$i]['perfDate'])) {
            $newTickets[$lastFullTicketElement]['perfDate'] .= ',' . $tickets[$i]['perfDate'];
         } else {
            if ($_SESSION['printToday'] == true) {
                if ($tickets[$i]['perfDate'] >= $today)  {
                     array_push($newTickets, $tickets[$i]);
                     $lastFullTicketElement = $i;
                }
            } else {
              array_push($newTickets, $tickets[$i]);
                     $lastFullTicketElement = $i;          
            }
         }
     }    
    
    $tickets = $newTickets;
     
    
    
//    echo "tickets after merge:<br>";
//    echo "<pre>";
//    print_r($tickets);
//    echo "</pre>";
//    exit;
    
    
    
    
    
    
    
    
    
    
    
    
    //echo "<pre>";
    //echo "sessionTickets: \n";
    //print_r($tickets);
    //echo "</pre>";
    
    //echo "<pre>";
    //echo "uniqueSliNos: \n";
    //print_r($uniqueSliNos);
    //echo "</pre>";
    //exit();
    
//    $orderNo = "";
//    foreach ($xml->GetTicketPrintInformationResults->Table as $oneObject) {
//        if ($oneObject->description == 'Order No'){
//            $orderNo = $oneObject->val;
//        }
//    }
    
//    echo "orderNo = " . $orderNo;
    
 
    
    
/*    if (empty($xml)) {  
       return array(false,$orderId,"Did not return ticket information.");
       
    } else {
       return array(true,$orderId,"Found ticket information.");
       
    }
   return array(true,$orderId,"");  
 * 
 */

    return $tickets;
    
    
}


function getTicketPrintInformationFromFile() {
     
    $tickets = array();
    $file = file_get_contents('/ECommerceSessions/15864/getTicketData5.txt', true);
    $file = iconv('utf-8', 'us-ascii//TRANSLIT', $file);
//    echo "<textarea rows=20 cols=80>";
//    print_r($file);
//    echo "</textarea>";
//    exit;
    $response = $file;
    
    $oldResponse = $response;
    
    
//    echo "<textarea rows=80 cols=80>";
//    print_r($file);
//    echo "</textarea>";
//    exit;
    
    $startPos = strpos($response, "<DataSet");
    $endPos = strpos($response, "<diffgr:diffgram");
    $response = substr($response,0,$startPos) . substr($response,$endPos);
    
//    echo "<textarea rows=60 cols=80>";
//    print_r($response);
//    echo "</textarea>";
//    exit();
    
    //exit($response);
    //$response = substr($response,0,strpos($response, "<GetTicketPrintInformationResults")) . substr($response,strpos($response, "<Table"));
    //$response = substr($response,0,strpos($response, "<GetTicketPrintInformationResults")) . substr($response,strpos($response, "<Table"));
    //$response = substr($response,0,strpos($response, "</GetTicketPrintInformationResults>")) . substr($response,strpos($response, "</diffgr:diffgram>"),18);
    $startPos = strpos($response, "<GetTicketPrintInformationResults xmlns=");
    
    
//    echo "<textarea rows=60 cols=80>";
//    print_r($response);
//    echo "</textarea>";
    
    
    $response = '<?xml version="1.0" encoding="utf-8"?>' . "\n" . '<GetTicketPrintInformationResults>'  . "\n" . substr($response,$startPos+44);
    
//    echo "<textarea rows=60 cols=80>";
//    print_r($response);
//    echo "</textarea>";
//    echo "startPos: " . $startPos;
//    exit;
//    
    
    
    
    
    
//    echo "<textarea rows=60 cols=80>";
//    print_r($response);
//    echo "</textarea>";
//    exit();
    
    
    
    $endPos = strpos($response, "</GetTicketPrintInformationResults>");
    $response = substr($response,0,$endPos) . '</GetTicketPrintInformationResults>';
    
//    echo "<textarea rows=60 cols=80>";
//    print_r($response);
//    echo "</textarea>";
//    exit();
    
    
    
    
    
    
    //$oldResponse = $response;
    
    $newResponse = '';
    $lines = explode("\n",$response);
    foreach($lines as $thisLine){
        $diffPos = strpos($thisLine,"<Table diffgr");
        //echo $thisLine . "<br>";
        if ($diffPos > -1){
            $thisLine = '<Table>' . "\n";
            //echo "...found" . "<br>";
        } //elseif ($lines > ""){
          //  $thisLine .= "\n";
        else {
            $thisLine .= "\n";
        }
        $newResponse .= $thisLine;
    }
    
    $newResponse = trim($newResponse);
    
//    
//    echo"<table><tr><td>";
//    echo "<textarea rows=60 cols=80>";
//    print_r($oldResponse);
//    echo "</textarea>";
//    echo "</td><td>";
//    echo "<td>";
//    echo "<textarea rows=60 cols=80>";
//    print_r($newResponse);
//    echo "</textarea>";
//    echo "</td></tr></table>";
//    exit();
    
    $response = $newResponse;
    
//    echo "<textarea rows=60 cols=80>";
//    print_r($response);
//    echo "</textarea>";
//    exit();
    
    
    
    
    $xml = simplexml_load_string($response);
    //$xml = simplexml_load_string($oldResponse);
    
//    exit('done');
//    
//    echo "<textarea rows=60 cols=80>";
//    print_r($xml);
//    echo "</textarea>";
    //exit();
    
    $uniqueSliNos = array();
    for ($i=0;$i<sizeof($xml->Table);$i++){
        //$_SESSION['tickets'][$i]['sli_no'] = (string) $xml->GetTicketPrintInformationResults->Table[$i]->sli_no;
        $tempSli =  (string) $xml->Table[$i]->sli_no[0];
        // search for tempSli in uniqueSliNos. If not found, add.
        $found = 'no';
        foreach($uniqueSliNos as $oneUniqueSliNo){
            if ($oneUniqueSliNo == $tempSli){
                $found = 'yes';
                break;
            } 
        }
        if ($found == 'no'){
                /*echo "Not found:<br>";
                echo "before push:<br>";
                echo "<pre>";
                echo "uniqueSliNos: <br>";
                print_r($uniqueSliNos);
                echo "</pre>";
                
                echo "oneUniqueSliNo: " . $oneUniqueSliNo . "<br>"; 
                echo "tempSli: " . $tempSli . "<br><br><br><br>";
                */
                array_push($uniqueSliNos,$tempSli);
                //$_SESSION['tickets'][$i]['sli_no'] = (string) $xml->GetTicketPrintInformationResults->Table[$i]->sli_no; //?
                
                
//                echo "After push:<br>";
//                echo "<pre>";
//                echo "uniqueSliNos: <br>";
//                print_r($uniqueSliNos);
//                echo "</pre>";
//                exit;
        }

      
        //$_SESSION['performances'][$i]['perfNo'] = (string) $xml->GetPerformancesEx4Result->Performance[$i]->perf_no;
        //$_SESSION['performances'][$i]['prices'] = getPrices($_SESSION['performances'][$i]['perfNo'],$data ); // get all prices for this performance.
    }
    // at this point, we have uniqueSliNos.
    
//    echo "<pre>";
//    echo "uniqueSliNos: \n";
//    print_r($uniqueSliNos);
//    echo "</pre>";
//    exit();
    
    
    $ticketElement = 0;
    $lastFullTicketElement = 0;
    foreach ($uniqueSliNos as $oneUniqueSliNo){
        // we have oneUniqueSliNo. 
        // find ticketno that matches.
        //echo "searching for oneUniqueSliNo: " . $oneUniqueSliNo . "<br>";
        
        $tickets[$ticketElement]['sliNo'] = $oneUniqueSliNo;
        
        //echo "<textarea rows=20 cols=80>";
        //print_r($xml);
        //echo "</textarea>";
        //exit;
        
        for ($i=0;$i<sizeof($xml->Table);$i++){
            $tempSli =  (string) $xml->Table[$i]->sli_no[0];
            $description = (string) $xml->Table[$i]->description[0];
            $val = (string) $xml->Table[$i]->val[0];
            $designNo = (string) $xml->Table[$i]->design_no[0];

//            if ($tempSli == $oneUniqueSliNo && $description == 'Ticket No'){
//                //exit('FOUND!!!! tempSli: ' . $tempSli . '<br>val: ' . $val . '<br>description: ' . $description . '<br>');
//                $_SESSION['tickets'][$ticketElement]['designNo'] = $val;
//            }
            if (($tempSli == $oneUniqueSliNo && $description == 'Ticket No') || ($tempSli == $oneUniqueSliNo && $description == 'Composite Ticket No')){
                //exit('FOUND!!!! tempSli: ' . $tempSli . '<br>val: ' . $val . '<br>description: ' . $description . '<br>');
                $tickets[$ticketElement]['ticketNo'] = $val;
                $tickets[$ticketElement]['designNo'] = $designNo;
            }
            if ($tempSli == $oneUniqueSliNo && $description == 'Constituent Id'){
                //exit('FOUND!!!! tempSli: ' . $tempSli . '<br>val: ' . $val . '<br>description: ' . $description . '<br>');
                $tickets[$ticketElement]['constituentId'] = $val;
            }
            if ($tempSli == $oneUniqueSliNo && $description == 'Order No'){
                //exit('FOUND!!!! tempSli: ' . $tempSli . '<br>val: ' . $val . '<br>description: ' . $description . '<br>');
                $tickets[$ticketElement]['orderNo'] = $val;
            }
            if ($tempSli == $oneUniqueSliNo && $description == 'Order Date'){
                //exit('FOUND!!!! tempSli: ' . $tempSli . '<br>val: ' . $val . '<br>description: ' . $description . '<br>');
                $tickets[$ticketElement]['orderDate'] = $val;
            }
            if ($tempSli == $oneUniqueSliNo && $description == 'Perf. Description'){
                //exit('FOUND!!!! tempSli: ' . $tempSli . '<br>val: ' . $val . '<br>description: ' . $description . '<br>');
                $tickets[$ticketElement]['perfDescription'] = $val;
            }
            if ($tempSli == $oneUniqueSliNo && $description == 'Pkg Description'){
                //exit('FOUND!!!! tempSli: ' . $tempSli . '<br>val: ' . $val . '<br>description: ' . $description . '<br>');
                $tickets[$ticketElement]['pkgDescription'] = $val;
            }
            if ($tempSli == $oneUniqueSliNo && $description == 'Price Type Short Desc'){
                //exit('FOUND!!!! tempSli: ' . $tempSli . '<br>val: ' . $val . '<br>description: ' . $description . '<br>');
                $tickets[$ticketElement]['priceTypeShortDesc'] = $val;
            }
            if ($tempSli == $oneUniqueSliNo && $description == 'Price Type Description'){
                //exit('FOUND!!!! tempSli: ' . $tempSli . '<br>val: ' . $val . '<br>description: ' . $description . '<br>');
                $tickets[$ticketElement]['priceTypeDesc'] = $val;
            }
            if ($tempSli == $oneUniqueSliNo && $description == 'Perf. Code'){
                //exit('FOUND!!!! tempSli: ' . $tempSli . '<br>val: ' . $val . '<br>description: ' . $description . '<br>');
                $tickets[$ticketElement]['perfCode'] = $val;
            }
            if ($tempSli == $oneUniqueSliNo && $description == 'Perf. Date'){
                //exit('FOUND!!!! tempSli: ' . $tempSli . '<br>val: ' . $val . '<br>description: ' . $description . '<br>');
                $tickets[$ticketElement]['perfDate'] = $val;
            }
            if ($tempSli == $oneUniqueSliNo && $description == 'Perf. Begin Time'){
                //exit('FOUND!!!! tempSli: ' . $tempSli . '<br>val: ' . $val . '<br>description: ' . $description . '<br>');
                $tickets[$ticketElement]['perfBeginTime'] = $val;
            }
            if ($tempSli == $oneUniqueSliNo && $description == 'Perf.Info-1'){
                //exit('FOUND!!!! tempSli: ' . $tempSli . '<br>val: ' . $val . '<br>description: ' . $description . '<br>');
                $tickets[$ticketElement]['perfInfo1'] = $val;
            }
            if ($tempSli == $oneUniqueSliNo && $description == 'Total Ticket Price'){
                //exit('FOUND!!!! tempSli: ' . $tempSli . '<br>val: ' . $val . '<br>description: ' . $description . '<br>');
                $tickets[$ticketElement]['totalTicketPrice'] = $val;
            }
            if ($tempSli == $oneUniqueSliNo && $description == 'Constituent Display Name'){
                //exit('FOUND!!!! tempSli: ' . $tempSli . '<br>val: ' . $val . '<br>description: ' . $description . '<br>');
                $tickets[$ticketElement]['constituentDisplayName'] = $val;
            }
            if ($tempSli == $oneUniqueSliNo && $description == 'User-Defined Element 1'){
                //exit('FOUND!!!! tempSli: ' . $tempSli . '<br>val: ' . $val . '<br>description: ' . $description . '<br>');
                $tickets[$ticketElement]['userDefinedElement1'] = $val;
            }
            if ($tempSli == $oneUniqueSliNo && $description == 'User-Defined Element 2'){
                //exit('FOUND!!!! tempSli: ' . $tempSli . '<br>val: ' . $val . '<br>description: ' . $description . '<br>');
                $tickets[$ticketElement]['userDefinedElement2'] = $val;
            }
            if ($tempSli == $oneUniqueSliNo && $description == 'Price Zone Description'){
                //exit('FOUND!!!! tempSli: ' . $tempSli . '<br>val: ' . $val . '<br>description: ' . $description . '<br>');
                $tickets[$ticketElement]['priceZoneDesc'] = $val;
            }
            
        }
        
        //exit('designNo: ' . $designNo);
        /*if ($designNo == '2071' || $designNo == '2092' || empty($designNo)) {
            if (empty($tickets[$ticketElement]['totalTicketPrice'])){
                 //if (empty($tickets[$ticketElement]['pkgDescription']) && !empty($tickets[$ticketElement]['perfDate'])){
                if (!empty($tickets[$ticketElement]['perfDate'])){    
                    $tickets[$lastFullTicketElement]['perfDate'] .= ',' . $tickets[$ticketElement]['perfDate'];
                    unset($tickets[$ticketElement]);
                    $ticketElement--;
                } else if(!empty($tickets[$ticketElement]['pkgDescription']) && !empty($tickets[$ticketElement]['perfDate'])){
                    $lastFullTicketElement = $ticketElement;
                }
            }
        }*/
        //explode(",", $tickets[$ticketElement]['perfDate'])
        
        
        $ticketElement++;
    }
    
    
    
//    echo "<pre>";
//    echo "xml: \n";
//    print_r($xml);
//    echo "</pre>";
    
    
    
    
    
    
//    echo "tickets before merge:<br>";
//    echo "<pre>";
//    print_r($tickets);
//    echo "</pre>";
//    exit;
    $_SESSION['request']->updateApiCommunication("GetTicketPrintInformation tickets to be printed: " . print_r($tickets,true) . "\n");
    
//    // combine perf dates.
//    $newTickets = array();
//    $lastFullTicketElement = 0;
//    for ($i = 0;$i < sizeof($tickets);$i++){
//        if (empty($tickets[$i]['designNo']) && !empty($tickets[$i]['perfDate'])) {
//            $newTickets[$lastFullTicketElement]['perfDate'] .= ',' . $tickets[$i]['perfDate'];
//         } else {
//             array_push($newTickets, $tickets[$i]);
//             $lastFullTicketElement = $i;
//         }
//     }
     
    // combine perf dates.
    $newTickets = array();
    $lastFullTicketElement = 0;
    $today = date('Y-m-d');
    for ($i = 0;$i < sizeof($tickets);$i++){
        if (empty($tickets[$i]['designNo']) && !empty($tickets[$i]['perfDate'])) {
            $newTickets[$lastFullTicketElement]['perfDate'] .= ',' . $tickets[$i]['perfDate'];
         } else {
            if ($_SESSION['printToday'] == true) {
                //exit("perfDate: " . $tickets[$i]['perfDate']);
                if (empty($tickets[$i]['perfDate'])){
                    $tickets[$i]['perfDate'] = $today;
                } 
                
                //if ($tickets[$i]['perfDate'] >= $today)  { // ***TEMPORARILY COMMENT OUT FOR TESTING A PERFROMANCE DATE EARLIER THAN TODAY
                         $_SESSION['request']->updateApiCommunication("Adding Ticket to Print: " . print_r($tickets[$i],true) . "\n");
                         array_push($newTickets, $tickets[$i]);
                         $lastFullTicketElement = $i;
                //}
            } else {
              array_push($newTickets, $tickets[$i]);
                     $lastFullTicketElement = $i;          
            }
         }
     }    
    
    $tickets = $newTickets;
    
//    echo "tickets after merge:<br>";
//    echo "<pre>";
//    print_r($tickets);
//    echo "</pre>";
//    exit;
    return $tickets;
}



//Returns fgl.
function getTicketPrintInformation($data) {
    $tickets = array();
    $data['websessionId'] = '';
    
    
//    echo "<pre>";
//    print_r($data);
//    echo "<pre>";
//    exit;
    
    
    if ($_SESSION['reprint'] == false) {
        $data['reprint'] = 'N'; 
    } else {
        $data['reprint'] = 'Y'; 
    }
    
    /*
    $params = array( 
            'sWebSessionID' =>  '',
            'iOrderId' => $data['ordernumber'],
            'sHeaderDesign' => $data['headerdesign'],
            'sTicketDesign' => $data['ticketdesign'],
            'cReceipt' => $data['receipt'],
            'cReprint' => $data['reprint']
    );
    
    $xml = '';
    try{
//        if ($data['ordernumber'] == '161638'){
//        exit('found it.');
//    }
        
       $inputData = print_r($params,true); 
       $_SESSION['request']->updateApiCommunication("GetTicketPrintInformation Input Data: " . $inputData . "\n"); 
        
        if (file_exists('/ECommerceSessions/15864/GetTicketPrintInformation/xml_test.txt')){
            //echo "reading file instead.";
            $response = unserialize(file_get_contents('/ECommerceSessions/15864/GetTicketPrintInformation/xml_test.txt'));
//            echo "<textarea rows=20 cols=80>";
//            echo serialize($response);
//            echo "</textarea>";
//            exit;
        } else {
            $response=$_SESSION['client']->GetTicketPrintInformation($params);
//            echo "<textarea rows=20 cols=80>";
//            echo serialize($response);
            //$myFile = file_put_contents('/ECommerceSessions/15864/GetTicketPrintInformation/xml_174978_20151016_125600.txt', serialize($response));
            $myFile = file_put_contents('/ECommerceSessions/15864/GetTicketPrintInformation/xml_' . date("Ymd_His") . '.txt', serialize($response));
//            echo "</textarea>";
//            exit;
        }
        //exit('hello');
        
        
        $responseData = print_r($response,true);
        $_SESSION['request']->updateApiCommunication("GetTicketPrintInformation response: " . $responseData . "\n"); 
        
//        echo "<pre>";
//        echo "getTicketPrintInformation Response: \n";
//        print_r($response);
//        echo "</pre>";
//        exit();
        
        $xml = @simplexml_load_string($response->GetTicketPrintInformationResult->any);
        
    } catch (SoapFault $e){
        //exit('got error' . $e->getMessage());
        $responseData = print_r($e->getMessage(),true);
        $_SESSION['request']->updateApiCommunication("GetTicketPrintInformation SoapFault response: " . $responseData . "\n"); 
        
        if (strpos($e->getMessage(),"no seats to be ticketed")){
            
            //echo "No seats to be ticketed for order #" . $data['ordernumber'] . "<br>";
            //array_push($noSeatOrders, $data['ordernumber']);
            $_SESSION['noSeatOrders']++;
            return $tickets;
//            errorPage("No seats to be ticketed for order #" . $data['ordernumber'] . "<br>");
//            exit('got no seats: ' . $e->getMessage());
            //return error code for no seats.
            //return array
        } //else {
           
        //}
        
    }   */
    
//    echo "<textarea rows=200 cols=80>";
//    print_r($xml);
//    echo "</textarea>";
//    exit();
    
//    echo "<pre>";
//    print_r($xml);
//    echo "</pre>";
//    exit();
     
//    echo "<pre>";
//    echo "getTicketPrintInformation Params: \n";
//    print_r($params);
//    echo "</pre>";
//    exit();
        
       
//    echo "<pre>";
//    echo "getTicketPrintInformation Response: \n";
//    print_r($response);
//    echo "</pre>";
//    exit();
//    
//    
//    

    
    //echo "<pre>";
    //echo "getTicketPrintInformation Response Any: \n";
    //print_r($response->GetTicketPrintInformationResult->any);
    //echo "</pre>";
    
//    echo "<pre>";
//    echo "xml: \n";
//    print_r($xml);
//    echo "</pre>";
//    exit();
    
    $ch = curl_init(); 
     
    $url = $_SESSION['soapUrlDirect'] . "/GetTicketPrintInformation?sWebSessionID=" . $data['websessionId'] . 
        "&iOrderId=" . $data['ordernumber'] . 
        "&sHeaderDesign=" . $data['headerdesign'] .
        "&sTicketDesign=" . $data['ticketdesign'] . 
        "&cReceipt=" . $data['receipt'] . 
        "&cReprint=" . $data['reprint'];
    $_SESSION['request']->updateApiCommunication("GetTicketPrintInformation request: " . $url . "\n");
    
    //$response = (string) @file_get_contents($url);
    
    curl_setopt($ch, CURLOPT_URL, $url);  // was "example.com"
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
    $response = curl_exec($ch); 
    $response = iconv('utf-8', 'us-ascii//TRANSLIT', $response);
    
    curl_close($ch); 
    //dumpInfo($url, $response); 
    
    
    $invalidOrderNoStartPos = strpos($response, "Invalid Order_no");
    if ($invalidOrderNoStartPos > -1){
        errorPageWillCall("Invalid Order #: " . $_SESSION['orderNo']);
    }
    
    
    
    
    
    
    $_SESSION['request']->updateApiCommunication("GetTicketPrintInformation response: " . $response . "\n");
    
   
    if (strpos($response,'<GetTicketPrintInformationResults') == false) {  
        //echo "response is empty";
        //return array(false,$orderId,"This member number did not return an order.");
        if ($_REQUEST['task'] == 'lookupByConfirmation') {
            $_SESSION['request']->updateApiCommunication("GetTicketPrintInformation response: " . $response . "\n");
            errorPageWillCall("Order number: " .  $data['ordernumber'] . " not found.");
        } else {
            if (strpos($response,'Order has no seats to be ticketed') == true) {
                $_SESSION['request']->updateApiCommunication("GetTicketPrintInformation response: " . $response . "\n");
                return null;
            }
        }
    }
    
//    echo "<textarea rows=60 cols=80>";
//    print_r($response);
//    echo "</textarea>";
//    exit();
    
    $startPos = strpos($response, "<DataSet");
    $endPos = strpos($response, "<diffgr:diffgram");
    $response = substr($response,0,$startPos) . substr($response,$endPos);
    
//    echo "<textarea rows=60 cols=80>";
//    print_r($response);
//    echo "</textarea>";
//    exit();
    
    //exit($response);
    //$response = substr($response,0,strpos($response, "<GetTicketPrintInformationResults")) . substr($response,strpos($response, "<Table"));
    //$response = substr($response,0,strpos($response, "<GetTicketPrintInformationResults")) . substr($response,strpos($response, "<Table"));
    //$response = substr($response,0,strpos($response, "</GetTicketPrintInformationResults>")) . substr($response,strpos($response, "</diffgr:diffgram>"),18);
    $startPos = strpos($response, "<GetTicketPrintInformationResults xmlns=");
    $response = '<?xml version="1.0" encoding="utf-8"?>' . "\n" . '<GetTicketPrintInformationResults>'  . substr($response,$startPos+44);
    
//    echo "<textarea rows=60 cols=80>";
//    print_r($response);
//    echo "</textarea>";
//    exit();
//    
    
    
    $endPos = strpos($response, "</GetTicketPrintInformationResults>");
    $response = substr($response,0,$endPos) . '</GetTicketPrintInformationResults>';
    
    
    $newResponse = '';
    $lines = explode("\n",$response);
    foreach($lines as $thisLine){
        $diffPos = strpos($thisLine,"<Table diffgr");
        //echo $thisLine . "<br>";
        if ($diffPos > -1){
            $thisLine = '<Table>' . "\n";
            //echo "...found" . "<br>";
        }
        $newResponse .= $thisLine;
    }
    
//    echo "<textarea rows=60 cols=80>";
//    print_r($newResponse);
//    echo "</textarea>";
//    exit();
    
    $response = $newResponse;
    
//    echo "<textarea rows=60 cols=80>";
//    print_r($response);
//    echo "</textarea>";
//    exit();
    
    
    
    
    $xml = simplexml_load_string($response);
    
    
//    echo "<textarea rows=60 cols=80>";
//    print_r($xml);
//    echo "</textarea>";
//    exit();
    
    $uniqueSliNos = array();
    for ($i=0;$i<sizeof($xml->Table);$i++){
        //$_SESSION['tickets'][$i]['sli_no'] = (string) $xml->GetTicketPrintInformationResults->Table[$i]->sli_no;
        $tempSli =  (string) $xml->Table[$i]->sli_no[0];
        // search for tempSli in uniqueSliNos. If not found, add.
        $found = 'no';
        foreach($uniqueSliNos as $oneUniqueSliNo){
            if ($oneUniqueSliNo == $tempSli){
                $found = 'yes';
                break;
            } 
        }
        if ($found == 'no'){
                /*echo "Not found:<br>";
                echo "before push:<br>";
                echo "<pre>";
                echo "uniqueSliNos: <br>";
                print_r($uniqueSliNos);
                echo "</pre>";
                
                echo "oneUniqueSliNo: " . $oneUniqueSliNo . "<br>"; 
                echo "tempSli: " . $tempSli . "<br><br><br><br>";
                */
                array_push($uniqueSliNos,$tempSli);
                //$_SESSION['tickets'][$i]['sli_no'] = (string) $xml->GetTicketPrintInformationResults->Table[$i]->sli_no; //?
                
                
//                echo "After push:<br>";
//                echo "<pre>";
//                echo "uniqueSliNos: <br>";
//                print_r($uniqueSliNos);
//                echo "</pre>";
//                exit;
        }

      
        //$_SESSION['performances'][$i]['perfNo'] = (string) $xml->GetPerformancesEx4Result->Performance[$i]->perf_no;
        //$_SESSION['performances'][$i]['prices'] = getPrices($_SESSION['performances'][$i]['perfNo'],$data ); // get all prices for this performance.
    }
    // at this point, we have uniqueSliNos.
    
//    echo "<pre>";
//    echo "uniqueSliNos: \n";
//    print_r($uniqueSliNos);
//    echo "</pre>";
//    exit();
    
    
    $ticketElement = 0;
    $lastFullTicketElement = 0;
    foreach ($uniqueSliNos as $oneUniqueSliNo){
        // we have oneUniqueSliNo. 
        // find ticketno that matches.
        //echo "searching for oneUniqueSliNo: " . $oneUniqueSliNo . "<br>";
        
        $tickets[$ticketElement]['sliNo'] = $oneUniqueSliNo;
        
        //echo "<textarea rows=20 cols=80>";
        //print_r($xml);
        //echo "</textarea>";
        //exit;
        
        for ($i=0;$i<sizeof($xml->Table);$i++){
            $tempSli =  (string) $xml->Table[$i]->sli_no[0];
            $description = (string) $xml->Table[$i]->description[0];
            $val = (string) $xml->Table[$i]->val[0];
            $designNo = (string) $xml->Table[$i]->design_no[0];

//            if ($tempSli == $oneUniqueSliNo && $description == 'Ticket No'){
//                //exit('FOUND!!!! tempSli: ' . $tempSli . '<br>val: ' . $val . '<br>description: ' . $description . '<br>');
//                $_SESSION['tickets'][$ticketElement]['designNo'] = $val;
//            }
            if (($tempSli == $oneUniqueSliNo && $description == 'Ticket No') || ($tempSli == $oneUniqueSliNo && $description == 'Composite Ticket No')){
                //exit('FOUND!!!! tempSli: ' . $tempSli . '<br>val: ' . $val . '<br>description: ' . $description . '<br>');
                $tickets[$ticketElement]['ticketNo'] = $val;
                $tickets[$ticketElement]['designNo'] = $designNo;
            }
            if ($tempSli == $oneUniqueSliNo && $description == 'Constituent Id'){
                //exit('FOUND!!!! tempSli: ' . $tempSli . '<br>val: ' . $val . '<br>description: ' . $description . '<br>');
                $tickets[$ticketElement]['constituentId'] = $val;
            }
            if ($tempSli == $oneUniqueSliNo && $description == 'Order No'){
                //exit('FOUND!!!! tempSli: ' . $tempSli . '<br>val: ' . $val . '<br>description: ' . $description . '<br>');
                $tickets[$ticketElement]['orderNo'] = $val;
            }
            if ($tempSli == $oneUniqueSliNo && $description == 'Order Date'){
                //exit('FOUND!!!! tempSli: ' . $tempSli . '<br>val: ' . $val . '<br>description: ' . $description . '<br>');
                $tickets[$ticketElement]['orderDate'] = $val;
            }
            if ($tempSli == $oneUniqueSliNo && $description == 'Perf. Description'){
                //exit('FOUND!!!! tempSli: ' . $tempSli . '<br>val: ' . $val . '<br>description: ' . $description . '<br>');
                $tickets[$ticketElement]['perfDescription'] = $val;
            }
            if ($tempSli == $oneUniqueSliNo && $description == 'Pkg Description'){
                //exit('FOUND!!!! tempSli: ' . $tempSli . '<br>val: ' . $val . '<br>description: ' . $description . '<br>');
                $tickets[$ticketElement]['pkgDescription'] = $val;
            }
            if ($tempSli == $oneUniqueSliNo && $description == 'Price Type Short Desc'){
                //exit('FOUND!!!! tempSli: ' . $tempSli . '<br>val: ' . $val . '<br>description: ' . $description . '<br>');
                $tickets[$ticketElement]['priceTypeShortDesc'] = $val;
            }
            if ($tempSli == $oneUniqueSliNo && $description == 'Price Type Description'){
                //exit('FOUND!!!! tempSli: ' . $tempSli . '<br>val: ' . $val . '<br>description: ' . $description . '<br>');
                $tickets[$ticketElement]['priceTypeDesc'] = $val;
            }
            if ($tempSli == $oneUniqueSliNo && $description == 'Perf. Code'){
                //exit('FOUND!!!! tempSli: ' . $tempSli . '<br>val: ' . $val . '<br>description: ' . $description . '<br>');
                $tickets[$ticketElement]['perfCode'] = $val;
            }
            if ($tempSli == $oneUniqueSliNo && $description == 'Perf. Date'){
                //exit('FOUND!!!! tempSli: ' . $tempSli . '<br>val: ' . $val . '<br>description: ' . $description . '<br>');
                $tickets[$ticketElement]['perfDate'] = $val;
            }
            if ($tempSli == $oneUniqueSliNo && $description == 'Perf. Begin Time'){
                //exit('FOUND!!!! tempSli: ' . $tempSli . '<br>val: ' . $val . '<br>description: ' . $description . '<br>');
                $tickets[$ticketElement]['perfBeginTime'] = $val;
            }
            if ($tempSli == $oneUniqueSliNo && $description == 'Perf.Info-1'){
                //exit('FOUND!!!! tempSli: ' . $tempSli . '<br>val: ' . $val . '<br>description: ' . $description . '<br>');
                $tickets[$ticketElement]['perfInfo1'] = $val;
            }
            if ($tempSli == $oneUniqueSliNo && $description == 'Total Ticket Price'){
                //exit('FOUND!!!! tempSli: ' . $tempSli . '<br>val: ' . $val . '<br>description: ' . $description . '<br>');
                $tickets[$ticketElement]['totalTicketPrice'] = $val;
            }
            if ($tempSli == $oneUniqueSliNo && $description == 'Constituent Display Name'){
                //exit('FOUND!!!! tempSli: ' . $tempSli . '<br>val: ' . $val . '<br>description: ' . $description . '<br>');
                $tickets[$ticketElement]['constituentDisplayName'] = $val;
            }
            if ($tempSli == $oneUniqueSliNo && $description == 'User-Defined Element 1'){
                //exit('FOUND!!!! tempSli: ' . $tempSli . '<br>val: ' . $val . '<br>description: ' . $description . '<br>');
                $tickets[$ticketElement]['userDefinedElement1'] = $val;
            }
            if ($tempSli == $oneUniqueSliNo && $description == 'User-Defined Element 2'){
                //exit('FOUND!!!! tempSli: ' . $tempSli . '<br>val: ' . $val . '<br>description: ' . $description . '<br>');
                $tickets[$ticketElement]['userDefinedElement2'] = $val;
            }
            if ($tempSli == $oneUniqueSliNo && $description == 'Price Zone Description'){
                //exit('FOUND!!!! tempSli: ' . $tempSli . '<br>val: ' . $val . '<br>description: ' . $description . '<br>');
                $tickets[$ticketElement]['priceZoneDesc'] = $val;
            }
            
        }
        
        //exit('designNo: ' . $designNo);
        /*if ($designNo == '2071' || $designNo == '2092' || empty($designNo)) {
            if (empty($tickets[$ticketElement]['totalTicketPrice'])){
                 //if (empty($tickets[$ticketElement]['pkgDescription']) && !empty($tickets[$ticketElement]['perfDate'])){
                if (!empty($tickets[$ticketElement]['perfDate'])){    
                    $tickets[$lastFullTicketElement]['perfDate'] .= ',' . $tickets[$ticketElement]['perfDate'];
                    unset($tickets[$ticketElement]);
                    $ticketElement--;
                } else if(!empty($tickets[$ticketElement]['pkgDescription']) && !empty($tickets[$ticketElement]['perfDate'])){
                    $lastFullTicketElement = $ticketElement;
                }
            }
        }*/
        //explode(",", $tickets[$ticketElement]['perfDate'])
        
        
        $ticketElement++;
    }
    
    
    
//    echo "<pre>";
//    echo "xml: \n";
//    print_r($xml);
//    echo "</pre>";
    
    
    
    
    
    
//    echo "tickets before merge:<br>";
//    echo "<pre>";
//    print_r($tickets);
//    echo "</pre>";
//    exit;
    $_SESSION['request']->updateApiCommunication("GetTicketPrintInformation tickets to be printed: " . print_r($tickets,true) . "\n");
    
//    // combine perf dates.
//    $newTickets = array();
//    $lastFullTicketElement = 0;
//    for ($i = 0;$i < sizeof($tickets);$i++){
//        if (empty($tickets[$i]['designNo']) && !empty($tickets[$i]['perfDate'])) {
//            $newTickets[$lastFullTicketElement]['perfDate'] .= ',' . $tickets[$i]['perfDate'];
//         } else {
//             array_push($newTickets, $tickets[$i]);
//             $lastFullTicketElement = $i;
//         }
//     }
     
    // combine perf dates.
    $newTickets = array();
    $lastFullTicketElement = 0;
    $today = date('Y-m-d');
    for ($i = 0;$i < sizeof($tickets);$i++){
        if (empty($tickets[$i]['designNo']) && !empty($tickets[$i]['perfDate'])) {
            $newTickets[$lastFullTicketElement]['perfDate'] .= ',' . $tickets[$i]['perfDate'];
         } else {
            if ($_SESSION['printToday'] == true) {
                //exit("perfDate: " . $tickets[$i]['perfDate']);
                if (empty($tickets[$i]['perfDate'])){
                    $tickets[$i]['perfDate'] = $today;
                } 
                
                if ($tickets[$i]['perfDate'] >= $today)  {
                         $_SESSION['request']->updateApiCommunication("Adding Ticket to Print: " . print_r($tickets[$i],true) . "\n");
                         array_push($newTickets, $tickets[$i]);
                         $lastFullTicketElement = $i;
                }
            } else {
              array_push($newTickets, $tickets[$i]);
                     $lastFullTicketElement = $i;          
            }
         }
     }    
    
    $tickets = $newTickets;
     
    
    
//    echo "tickets after merge:<br>";
//    echo "<pre>";
//    print_r($tickets);
//    echo "</pre>";
//    exit;
    
    
    
    
    
    
    
    
    
    
    
    
    //echo "<pre>";
    //echo "sessionTickets: \n";
    //print_r($tickets);
    //echo "</pre>";
    
    //echo "<pre>";
    //echo "uniqueSliNos: \n";
    //print_r($uniqueSliNos);
    //echo "</pre>";
    //exit();
    
//    $orderNo = "";
//    foreach ($xml->GetTicketPrintInformationResults->Table as $oneObject) {
//        if ($oneObject->description == 'Order No'){
//            $orderNo = $oneObject->val;
//        }
//    }
    
//    echo "orderNo = " . $orderNo;
    
 
    
    
/*    if (empty($xml)) {  
       return array(false,$orderId,"Did not return ticket information.");
       
    } else {
       return array(true,$orderId,"Found ticket information.");
       
    }
   return array(true,$orderId,"");  
 * 
 */

    return $tickets;
    
    
}



function getPerformancesEx4($data) {
    $client = new SoapClient("https://tesstestgw1.empmuseum.org/tessitura.asmx?WSDL");
    
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
    
    echo "<pre>";
    echo "getPerformancesEx4 Params: \n";
    print_r($params);
    echo "</pre>";
   
   
    echo "<pre>";
    echo "getPerformancesEx4 Response: \n";
    print_r($response);
    echo "</pre>";
    
    echo "<pre>";
    echo "getPerformancesEx4 Response Any: \n";
    print_r($response->GetPerformancesEx4Result->any);
    echo "</pre>";
    
    echo "<pre>";
    echo "xml: \n";
    print_r($xml);
    echo "</pre>";
    
}


function getPerformanceDetailWithDiscountingEx($data) {
    $client = new SoapClient("https://tesstestgw1.empmuseum.org/tessitura.asmx?WSDL");
    
    $params = array
    (
       'SessionKey' => $data['websessionId'],
       'iPerf_no' => $data['perfno'],
       'iModeOfSale' => $data['modeofsale'],
       'sContentType' => $data['contenttype'],
    );
    
    $response=$client->GetPerformanceDetailWithDiscountingEx($params); 
    
    $xml = simplexml_load_string($response->GetPerformanceDetailWithDiscountExResult->any);
    
    echo "<pre>";
    echo "getPerformanceDetailWithDiscountingEx Params: \n";
    print_r($params);
    echo "</pre>";
   
   
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
    
}



























function getPackagesEx3($data){
   
   $client = new SoapClient("http://dbradp6.tessituranetworkramp.com/wwl1api/tessitura.asmx?WSDL");
   
   
   
   
   $params = array
    (
       'sWebSessionId' => $data['websessionId'],//$data['websessionId'],
       'sStartDate' => $data['startdate'],
       'sEndDate' => $data['enddate'],
       'iVenueID' => $data['venueID'],
       'iModeOfSale' => $data['modeofsale'],
       'iBusinessUnit' => $data['businessunit'],
       'iSortString' => $data['sortstring'],
       'sKeywords' => $data['keywords'],
       'cKeywordAndOrStatement' => $data['keywordandorstatement'],
       'sArtistLastName' => $data['artistlastname'],
       'iSeason' => $data['season'],
       'iPackageType' => $data['packagetype'],
       'bMatchAllPerformances' => $data['matchallperformances'],
       'sFullText' => $data['fulltext'],
       'sFullTextTye' => $data['fulltexttype'],
       'sContentType' => $data['contenttype'],
       'sPackageNumbers' => $data['packagenumbers']
    );
   
//   $params = array
//    (
//       "clientCredentials" => $clientCredentials,
//       "cardVerificationParams" => $cardVerificationParams 
//    );
   
   //logData($params);
   
   //$response=$client->AuthorizeAndCapture($params);  //call this to get response from server
   $response=$client->GetPackagesEx3($params); 
   
   //logData($response);
   $_SESSION['sessionKey'] = $response->GetPackagesEx3Result;
   
//   echo $_SESSION['account']->gatewayClientCode . '-' . $_SESSION['account']->gatewayUserName . '-' . $_SESSION['account']->gatewayPassword . $_SESSION['account']->gatewayAVSLevel ."\n";
//   
   echo "<pre>";
   echo "GetPackagesEx3: \n";
   print_r($params);
   echo "</pre>";
//   
//   
   echo "<pre>";
   echo "GetPackagesEx3: \n";
   print_r($response);
   echo "</pre>";
//   
//   echo "<pre>";
//   echo "test: \n";
//   print_r($response->AuthorizeAndCaptureResult->CreditCardResponseStatus);
//   echo "</pre>";
   
   
//   echo "<pre>";
//   echo "failure reason: \n";
//   print_r($response->AuthorizeAndCaptureResult->FailureReason);
//    echo "</pre>";
//   if ($response->AuthorizeAndCaptureResult->Succeeded == "" || $response->AuthorizeAndCaptureResult->Succeeded == "0") {
//       return array(false,"",$response->AuthorizeAndCaptureResult->FailureReason);
//   }
   
//   echo "<pre>";
//   echo "test: \n";
//   print_r($response->AuthorizeAndCaptureResult);
//   echo "</pre>";
//   
//   echo "<pre>";
//   echo "test: \n";
//   print_r($response->AuthorizeAndCaptureResult->CreditCardResponseStatus);
//   echo "</pre>";
   
    

        
//    return array(true,$orderId,"");

}


function getNFSPakageDetailEx($data){
   
   $client = new SoapClient("http://dbradp6.tessituranetworkramp.com/wwl1api/tessitura.asmx?WSDL");
   
   
   
   
   $params = array
    (
       'sWebSessionId' => $data['websessionId'],//$data['websessionId'],
       'sStartDate' => $data['startdate'],
       'sEndDate' => $data['enddate'],
       'iVenueID' => $data['venueID'],
       'iModeOfSale' => $data['modeofsale'],
       'iBusinessUnit' => $data['businessunit'],
       'iSortString' => $data['sortstring'],
       'sKeywords' => $data['keywords'],
       'cKeywordAndOrStatement' => $data['keywordandorstatement'],
       'sArtistLastName' => $data['artistlastname'],
       'iSeason' => $data['season'],
       'iPackageType' => $data['packagetype'],
       'bMatchAllPerformances' => $data['matchallperformances'],
       'sFullText' => $data['fulltext'],
       'sFullTextTye' => $data['fulltexttype'],
       'sContentType' => $data['contenttype'],
       'sPackageNumbers' => $data['packagenumbers']
    );
   
//   $params = array
//    (
//       "clientCredentials" => $clientCredentials,
//       "cardVerificationParams" => $cardVerificationParams 
//    );
   
   //logData($params);
   
   //$response=$client->AuthorizeAndCapture($params);  //call this to get response from server
   $response=$client->GetPackagesEx3($params); 
   
   //logData($response);
   $_SESSION['sessionKey'] = $response->GetPackagesEx3Result;
   
//   echo $_SESSION['account']->gatewayClientCode . '-' . $_SESSION['account']->gatewayUserName . '-' . $_SESSION['account']->gatewayPassword . $_SESSION['account']->gatewayAVSLevel ."\n";
//   
   echo "<pre>";
   echo "GetPackagesEx3: \n";
   print_r($params);
   echo "</pre>";
//   
//   
   echo "<pre>";
   echo "GetPackagesEx3: \n";
   print_r($response);
   echo "</pre>";
//   
//   echo "<pre>";
//   echo "test: \n";
//   print_r($response->AuthorizeAndCaptureResult->CreditCardResponseStatus);
//   echo "</pre>";
   
   
//   echo "<pre>";
//   echo "failure reason: \n";
//   print_r($response->AuthorizeAndCaptureResult->FailureReason);
//    echo "</pre>";
//   if ($response->AuthorizeAndCaptureResult->Succeeded == "" || $response->AuthorizeAndCaptureResult->Succeeded == "0") {
//       return array(false,"",$response->AuthorizeAndCaptureResult->FailureReason);
//   }
   
//   echo "<pre>";
//   echo "test: \n";
//   print_r($response->AuthorizeAndCaptureResult);
//   echo "</pre>";
//   
//   echo "<pre>";
//   echo "test: \n";
//   print_r($response->AuthorizeAndCaptureResult->CreditCardResponseStatus);
//   echo "</pre>";
   
    

        
//    return array(true,$orderId,"");

}











//function doVoid($reference) {
//   //$client = new SoapClient("https://services.PWSDemo.com/CreditCardTransactionService.svc?wsdl");
//   $client = new SoapClient("https://services.PaymentWorkSuite.com/CreditCardTransactionService.svc?wsdl");
//   
//   $clientCredentials = array(
//        'ClientCode' => $_SESSION['account']->gatewayClientCode,
//        'UserName' => $_SESSION['account']->gatewayUserName,
//        'Password' => $_SESSION['account']->gatewayPassword
//   );
//   
//   // merchant, location, terminal
//   $terminalIdentifier = array(
//       'MerchantCode' => $_SESSION['account']->gatewayMerchantCode, 
//       'LocationCode' => $_SESSION['account']->gatewayLocationCode, 
//       'TerminalCode' => $_SESSION['account']->gatewayTerminalCode
//   );
//
//    $originalTransactionKey = $reference;
//           
//    
//   $voidParams = array(
//       'TerminalIdentifier' => $terminalIdentifier,
//       'OriginalTransactionKey' => $originalTransactionKey
//   ); 
//    
//    
//    $params = array(
//       'clientCredentials' => $clientCredentials,
//       'voidParams' => $voidParams 
//    );
//    
//    $response=$client->Void($params);  //call this to get response from server
//   
//   
//    logData($response);
//    
////    echo "<pre>";
////    echo "test: \n";
////    print_r($response);
////    echo "</pre>";
//  
//}



//not being used but it works, created just for the certification
function doCardVerification($ccData){
   
   //$client = new SoapClient("https://services.PWSDemo.com/CreditCardTransactionService.svc?wsdl");
   $client = new SoapClient("https://services.PaymentWorkSuite.com/CreditCardTransactionService.svc?wsdl");
   
   $clientCredentials = array(
        'ClientCode' => $_SESSION['account']->gatewayClientCode,
        'UserName' => $_SESSION['account']->gatewayUserName,
        'Password' => $_SESSION['account']->gatewayPassword
   );
   
   
   // merchant, location, terminal
   $terminalIdentifier = array(
       'MerchantCode' => $_SESSION['account']->gatewayMerchantCode, 
       'LocationCode' => $_SESSION['account']->gatewayLocationCode, 
       'TerminalCode' => $_SESSION['account']->gatewayTerminalCode
   );
   
   $billingAddress = array(
       'AddressLine1' => $ccData['billaddr1'],  //14151 provokes NotMatched AVS response
       'PostalCode' => $ccData['billzip']  //20151 provokes NotMatched AVS response
   );
   
   $contact = array(
       'FirstName' => $ccData['billfirst'],
       'LastName' => $ccData['billlast']
   );
   
   $orderId = date('YmdHis') . substr((string)microtime(), 2, 2);
   $transactionKey = date('YmdHis') . substr((string)microtime(), 2, 2);
   
   
   $creditCard = array(
       "CardType" => $ccData['ccType'],
       "NameOnCard" => $ccData['name'],
       "CardAccountNumber" => $ccData['ccnum'],
       "ExpirationMonth" => $ccData['expmon'],
       "ExpirationYear" => $ccData['expyear'],
       "Cardholder" => $contact,
       "BillingAddress" => $billingAddress,
       "CardSecurityCodeIndicator" => $ccData['cvvindicator'],//Chansouda
       "CardSecurityCode" => $ccData['cvv2'], //requires 3-digit code except for American Express. AE require 4-digit code, 123 provokes NotMatched for Visa or Mastercard
       "OrderNumber" => $orderId
   );
   
   
   $cardVerificationParams = array(
       "TransactionKey" => $transactionKey,
       "TerminalIdentifier" => $terminalIdentifier,
       "CreditCard" => $creditCard
   );
   

   
   $params = array
    (
       "clientCredentials" => $clientCredentials,
       "cardVerificationParams" => $cardVerificationParams 
    );
   
   logData($params);
   
   $response=$client->CardVerification($params);  //call this to get response from server
   
   logData($response);
   
//   echo $_SESSION['account']->gatewayClientCode . '-' . $_SESSION['account']->gatewayUserName . '-' . $_SESSION['account']->gatewayPassword . "\n";
//   
//   echo "<pre>";
//   echo "test: \n";
//   print_r($params);
//   echo "</pre>";
//   
//   
//   echo "<pre>";
//   echo "test: \n";
//   print_r($response);
//   echo "</pre>";
   
}


  



?>


