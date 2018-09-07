<?php


class RequestTemp
{
    


    // property declaration
    public $id = 0;
    public $client = '';
    public $kiosk = '';
    public $task = '';
    public $environmentData = '';
    public $apiCommunication = '';
    public $ticketsPrinted = 0;
    public $created = '';
    public $updated = '';
    
    // constructor of Request
    function Request(){
        
        
        $_SESSION['request'] = ''; // initialize.
        
        $this->created = date('Y-m-d H:i:s');
        
       
        
        
        
    }
    
    
    public function retrieveListFromDb ($clientName){
        
        
          $requests = array();  
        
          $sql = "SELECT * FROM requests WHERE client = ? order by created desc";
          $mysqli = new mysqli('localhost', $_SESSION['data']['user'], $_SESSION['data']['pass'], $_SESSION['data']['db']);
          if (mysqli_connect_errno()) {printf("Connect failed: %s\n", mysqli_connect_error());exit();
          }
          if ($stmt = $mysqli->prepare($sql)){
              $stmt->bind_param('s',$clientName);
              if (!$stmt->execute()){echo 'Invalid query: ' . $sql . '<br/><br/>';printf("Errormessage: %s\n", $mysqli->error);exit;}
              $stmt->store_result();

              $variables = array();
              $data = array();
              $meta = $stmt->result_metadata();

              while($field = $meta->fetch_field()){
                  $variables[] = &$data[$field->name]; // pass by reference
              }   
              call_user_func_array(array($stmt, 'bind_result'), $variables);
              while($stmt->fetch()){
                    
                    $row = array();
                    foreach($data as $k=>$v)
                    $row[$k] = $v;

                    $request = new Request();
                    $request->id = $row['id'];
                    $request->client = $row['client'];
                    $request->kiosk = $row['kiosk'];
                    $request->task = $row['task'];
                    $request->environmentData = $row['environment_data'];
                    $request->apiCommunication = $row['api_communication'];
                    $request->ticketsPrinted = $row['tickets_printed'];
                    $request->created = $row['created'];
                    $request->updated = $row['updated'];
    
                    
                    array_push($requests,$request);
                    
                    
              }
              $stmt->close();
          } else {
              exit('Failed prepare: ' . $sql . '<br/>' . $mysqli->error) ;
          }
          $mysqli->close();

        

         return $requests;

    }
    
    
    
    
    
    public function retrieveListFromDbByDate ($clientName, $created){
        
        
          $requests = array();  
        
          $sql = "SELECT * FROM requests WHERE client = ? and created > ? order by created desc";
          $mysqli = new mysqli('localhost', $_SESSION['data']['user'], $_SESSION['data']['pass'], $_SESSION['data']['db']);
          if (mysqli_connect_errno()) {printf("Connect failed: %s\n", mysqli_connect_error());exit();
          }
          if ($stmt = $mysqli->prepare($sql)){
              $stmt->bind_param('ss',$clientName, $created);
              if (!$stmt->execute()){echo 'Invalid query: ' . $sql . '<br/><br/>';printf("Errormessage: %s\n", $mysqli->error);exit;}
              $stmt->store_result();

              $variables = array();
              $data = array();
              $meta = $stmt->result_metadata();

              while($field = $meta->fetch_field()){
                  $variables[] = &$data[$field->name]; // pass by reference
              }   
              call_user_func_array(array($stmt, 'bind_result'), $variables);
              while($stmt->fetch()){
                    
                    $row = array();
                    foreach($data as $k=>$v)
                    $row[$k] = $v;

                    $request = new Request();
                    $request->id = $row['id'];
                    $request->client = $row['client'];
                    $request->kiosk = $row['kiosk'];
                    $request->task = $row['task'];
                    $request->environmentData = $row['environment_data'];
                    $request->apiCommunication = $row['api_communication'];
                    $request->ticketsPrinted = $row['tickets_printed'];
                    $request->created = $row['created'];
                    $request->updated = $row['updated'];
    
                    
                    array_push($requests,$request);
                    
                    
              }
              $stmt->close();
          } else {
              exit('Failed prepare: ' . $sql . '<br/>' . $mysqli->error) ;
          }
          $mysqli->close();

        

         return $requests;

    }
    
    
    
    public function retrieveListFromDbByDateRange ($clientName, $fromDate, $toDate){
        
        
          $requests = array();  
        
          $sql = "SELECT * FROM requests WHERE client = ? and created > ? and created < ? order by created desc";
          $mysqli = new mysqli('localhost', $_SESSION['data']['user'], $_SESSION['data']['pass'], $_SESSION['data']['db']);
          if (mysqli_connect_errno()) {printf("Connect failed: %s\n", mysqli_connect_error());exit();
          }
          if ($stmt = $mysqli->prepare($sql)){
              $stmt->bind_param('sss',$clientName, $fromDate, $toDate);
              if (!$stmt->execute()){echo 'Invalid query: ' . $sql . '<br/><br/>';printf("Errormessage: %s\n", $mysqli->error);exit;}
              $stmt->store_result();

              $variables = array();
              $data = array();
              $meta = $stmt->result_metadata();

              while($field = $meta->fetch_field()){
                  $variables[] = &$data[$field->name]; // pass by reference
              }   
              call_user_func_array(array($stmt, 'bind_result'), $variables);
              while($stmt->fetch()){
                    
                    $row = array();
                    foreach($data as $k=>$v)
                    $row[$k] = $v;

                    $request = new Request();
                    $request->id = $row['id'];
                    $request->client = $row['client'];
                    $request->kiosk = $row['kiosk'];
                    $request->task = $row['task'];
                    $request->environmentData = $row['environment_data'];
                    $request->apiCommunication = $row['api_communication'];
                    $request->ticketsPrinted = $row['tickets_printed'];
                    $request->created = $row['created'];
                    $request->updated = $row['updated'];
    
                    
                    array_push($requests,$request);
                    
                    
              }
              $stmt->close();
          } else {
              exit('Failed prepare: ' . $sql . '<br/>' . $mysqli->error) ;
          }
          $mysqli->close();

        

         return $requests;

    }
    
    
    
    
    public function loadDetailFromDb($clientName, $created){

        $sql = "SELECT * FROM requests WHERE client = ? and created = ?";
        $mysqli = new mysqli('localhost', $_SESSION['data']['user'], $_SESSION['data']['pass'], $_SESSION['data']['db']);
        if (mysqli_connect_errno()) {printf("Connect failed: %s\n", mysqli_connect_error());exit();
        }
        $recordCount = 0;
        if ($stmt = $mysqli->prepare($sql)){
            $stmt->bind_param('ss',$clientName, $created);
            if (!$stmt->execute()){echo 'Invalid query: ' . $sql . '<br/><br/>';printf("Errormessage: %s\n", $mysqli->error);exit;}
            $stmt->store_result();

            $variables = array();
            $data = array();
            $meta = $stmt->result_metadata();

            while($field = $meta->fetch_field()){
                $variables[] = &$data[$field->name]; // pass by reference
            }   
            call_user_func_array(array($stmt, 'bind_result'), $variables);
            while($stmt->fetch())
            {
                  $row = array();
                  foreach($data as $k=>$v)
                  $row[$k] = $v;
                  $recordCount++;


                  $this->id = $row['id'];
                  $this->client = $row['client'];
                  $this->kiosk = $row['kiosk'];
                  $this->task = $row['task'];
                  $this->environmentData = $row['environment_data'];
                  $this->apiCommunication = $row['api_communication'];
                  $this->ticketsPrinted = $row['tickets_printed'];
                  $this->created = $row['created'];
                  $this->updated = $row['updated'];

            }
            $stmt->close();
        } else {
            exit('Failed prepare: ' . $sql . '<br/>' . mysql_error()) ;
        }
        $mysqli->close();

       if ($recordCount > 0){
           return true;
       } else {
           return false; // don't use object. it contains nothing.
       }

    }
    
    
    
    
    
    function insert(){
        
        
        //if (!empty($_REQUEST['client'])){
        //    $this->client = $_REQUEST['client'];
        //}
        if (!empty($_SESSION['clientName'])){
            $this->client = $_SESSION['clientName'];
        }
        
        if (!empty($_REQUEST['kiosk'])){
            $this->kiosk = $_REQUEST['kiosk'];
        }
        if (!empty($_REQUEST['task'])){
            $this->task = $_REQUEST['task'];
        }
        
        
        $this->environmentData = ''; 
        $this->environmentData .= "REQUEST DATA: \n" . print_r($_REQUEST, true);
        //$this->environmentData .= "SESSION DATA: \n" . print_r($_SESSION, true);
        
        $this->environmentData = $this->maskCCData($this->environmentData);
        
        echo "<textarea rows=40 cols=120>";
        print_r($this);
        echo "</textarea>";
        exit;
        
        
        $mysqli = new mysqli('localhost', $_SESSION['data']['user'], $_SESSION['data']['pass'], $_SESSION['data']['db']);
        if (mysqli_connect_errno()) {printf("Connect failed: %s\n", mysqli_connect_error());exit();}
        $sql = "INSERT INTO requests
             (client,kiosk,task,environment_data, api_communication,created, updated)
             VALUES(?,?,?,?,?,?, NOW());";
        $stmt = $mysqli->prepare($sql) or exit('Failed prepare: ' . $sql . '<br/>' . $mysqli->error) ;
        $stmt->bind_param(
                'ssssss',
                $this->client,
                $this->kiosk,
                $this->task,
                $this->environmentData,
                $this->apiCommunication,
                $this->created
        );
        if (!$stmt->execute()){echo 'Invalid query: ' . $sql . '<br/><br/>';printf("Errormessage: %s\n", $mysqli->error);exit;}
        $stmt->close();
        $mysqli->close();
    }
    
    
    
    
    function updateTicketsPrinted($tickets){
            $mysqli = new mysqli('localhost', $_SESSION['data']['user'], $_SESSION['data']['pass'], $_SESSION['data']['db']);
            if (mysqli_connect_errno()) {printf("Connect failed: %s\n", mysqli_connect_error());exit();}
            $sql = "UPDATE requests set tickets_printed = ? WHERE created = ?;";
            ////exit('sql:' . $sql);
            $stmt = $mysqli->prepare($sql) or exit('Failed prepare: ' . $sql . '<br/>' . $mysqli->error) ;
            $stmt->bind_param(
                'ds',
                $tickets,
                $this->created
        );
            if (!$stmt->execute()){echo 'Invalid query: ' . $sql . '<br/><br/>';printf("Errormessage: %s\n", $mysqli->error);exit;}
            $stmt->close();
            $mysqli->close();

            return;
        
    }
    function updateApiCommunication($apiCommunication){
        
        
            $apiCommunication = $this->maskCCData($apiCommunication);
        
        
            $apiCommunication = '-----------------' . date('Y-m-d H:i:s') . '-----------------' . "\n" . $apiCommunication . "\n\n";
            
            
            $mysqli = new mysqli('localhost', $_SESSION['data']['user'], $_SESSION['data']['pass'], $_SESSION['data']['db']);
            if (mysqli_connect_errno()) {printf("Connect failed: %s\n", mysqli_connect_error());exit();}
            $sql = "UPDATE requests set api_communication = CONCAT(api_communication,'" . $apiCommunication . "') WHERE created = '" . $this->created . "';";
            ////exit('sql:' . $sql);
            $stmt = $mysqli->prepare($sql) or exit('Failed prepare: ' . $sql . '<br/>' . $mysqli->error) ;
            if (!$stmt->execute()){echo 'Invalid query: ' . $sql . '<br/><br/>';printf("Errormessage: %s\n", $mysqli->error);exit;}
            $stmt->close();
            $mysqli->close();

            return;
        
    }
    
    
    
    
    
    
    function maskCCData($data){
        
        
        $trk1StartPos = strpos($data,'ETrk1="') + 7;
        if ($trk1StartPos > 8){
            $ccEndPos = strpos($data,'^',$trk1StartPos) - 1;
            $cc = substr($data,$trk1StartPos,$ccEndPos-$trk1StartPos+1);
            //exit('cc: ' . $cc); 
            $maskedCc = '';
            for($i=0;$i<strlen($cc)-4;$i++){
                $maskedCc .= '*';
            }
            $maskedCc .= substr($cc,-4);
            //exit('maskedCc: ' . $maskedCc);
            $data = substr($data,0,$trk1StartPos) . $maskedCc . substr($data,$ccEndPos+1);
            //exit('data:' . $data);
        } 
        
        
        
        $trk2StartPos = strpos($data,'ETrk2="') + 7;
        if ($trk2StartPos > 8){
            $ccEndPos = strpos($data,'=',$trk2StartPos) - 1;
            $cc = substr($data,$trk2StartPos,$ccEndPos-$trk2StartPos+1);
            //exit('cc: ' . $cc); 
            $maskedCc = '';
            for($i=0;$i<strlen($cc)-4;$i++){
                $maskedCc .= '*';
            }
            $maskedCc .= substr($cc,-4);
            //exit('maskedCc: ' . $maskedCc);
            $data = substr($data,0,$trk2StartPos) . $maskedCc . substr($data,$ccEndPos+1);
            //exit('data:' . $data);
        } 
        
        
        $sCCStartPos = strpos($data,'[sCCNumber]') + 15;
        if ($sCCStartPos > 15){
            //exit('data:' . $data);
            $ccEndPos = strpos($data,' ',$sCCStartPos + 2) - 1;
            $cc = trim(substr($data,$sCCStartPos,$ccEndPos-$sCCStartPos+1));
            //exit('cc: ' . $cc); 
            $maskedCc = '';
            for($i=0;$i<strlen($cc)-4;$i++){
                $maskedCc .= '*';
            }
            $maskedCc .= substr($cc,-4);
            //exit('maskedCc: ' . $maskedCc);
            $data = substr($data,0,$sCCStartPos) . $maskedCc . "\n" . substr($data,$ccEndPos+1);
            //exit('data:' . $data);
        } 
       
        
        
        
        
        return $data;
    }
    
    
        
        
    
        
       
    
    
    
    
    
    
    

}





?>
