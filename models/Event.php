<?php


class Event
{
    // property declaration
    public $id = 0;
    public $acct = '';
    public $code = '';
    public $description = '';
    public $title = '';
    public $status = 0;
    public $img1 = '';
    public $img1Map = '';
    public $img2 = '';
    
    public $barceDigits = '';
    public $startDate = '';
    public $endDate = '';
    public $lastPrintAtHomeSend = '';
    public $printAtHomeMode = '';
    public $printAtHomeEmailSubject = '';
    public $emailFrom = '';
    public $emailFromName = '';
    public $emailReply = '';
    public $emailReplyName = '';
    public $emailTestTo = '';
    public $emailCc = '';
    public $emailBcc = '';
    public $publicContactTelephone = '';
    public $publicContactEmail = '';
    public $listMemberAssociations = 0;
    public $landingPagePixel = '';
    
    
    
    
    
    public function assignValues($event, $row){
        
        $event->id = $row['id'];
        $event->acct = $row['acct'];
        $event->code = $row['code'];
        $event->description = $row['description'];
        $event->title = $row['title'];
        $event->status = $row['status'];
        $event->img1 = $row['img1'];
        $event->img1Map = $row['img1_map'];
        $event->img2 = $row['img2'];

        $event->barceDigits = $row['barcode_digits'];
        $event->startDate = $row['start_date'];
        $event->endDate = $row['end_date'];
        $event->lastPrintAtHomeSend = $row['last_print_at_home_send'];
        $event->printAtHomeMode = $row['print_at_home_mode'];
        $event->printAtHomeEmailSubject = $row['print_at_home_email_subject'];
        $event->emailFrom = $row['email_from'];
        $event->emailFromName = $row['email_from_name'];
        $event->emailReply = $row['email_reply'];
        $event->emailReplyName = $row['email_reply_name'];
        $event->emailTestTo = $row['email_test_to'];
        $event->emailCc = $row['email_cc'];
        $event->emailBcc = $row['email_bcc'];
        $event->publicContactTelephone = $row['public_contact_telephone'];
        $event->publicContactEmail = $row['public_contact_email'];
        $event->listMemberAssociations = $row['list_member_associations'];
        $event->landingPagePixel = $row['landing_page_pixel'];
        
        return $event;
    }
    
    
    public function loadFromDb ($acct, $code){
        
          $status = false;
          $sql = "SELECT * FROM events WHERE acct = ? AND code = ?";
          $mysqli = new mysqli('localhost', $_SESSION['data']['user'], $_SESSION['data']['pass'], $_SESSION['data']['db']);
          if (mysqli_connect_errno()) {printf("Connect failed: %s\n", mysqli_connect_error());exit();
          }
          if ($stmt = $mysqli->prepare($sql)){
              $stmt->bind_param('ss',$acct, $code);
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

                    //echo '<!-- found row: ';
                    //print_r($row); 
                    //echo '-->';
                    
                    $this->assignValues($this,$row);
                    

              }
              $stmt->close();
              $mysqli->close();
              return true;
          } else {
              exit('Failed prepare: ' . $sql . '<br/>' . mysql_error()) ;
          }
          

        

         return $status;

    }
    
    
    
    public function retrieveActiveListFromDb ($acct){
        
        
          $events = array();  
        
          $sql = "SELECT * FROM events WHERE acct = ? AND status = 1";
          $mysqli = new mysqli('localhost', $_SESSION['data']['user'], $_SESSION['data']['pass'], $_SESSION['data']['db']);
          if (mysqli_connect_errno()) {printf("Connect failed: %s\n", mysqli_connect_error());exit();
          }
          if ($stmt = $mysqli->prepare($sql)){
              $stmt->bind_param('s',$acct);
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
                    
                    $event = new Event();
                    $event = $this->assignValues($event,$row);
                    //print_r($this);
                    //exit('done');
                    
                    array_push($events,$event);
                    
              }
              $stmt->close();
          } else {
              exit('Failed prepare: ' . $sql . '<br/>' . mysql_error()) ;
          }
          $mysqli->close();
          //print_r($events);
          //exit('done');
          return $events;

    }
    
    
    public function retrieveListFromDbNew ($acct,$sort){
        // $sort = "ORDER BY code DESC";
        
        
         
          $events = array();  
        
          //$sql = "SELECT * FROM events WHERE acct = ? order by code DESC";
           $sql = "SELECT * FROM events WHERE acct = ? " . $sort;
          $mysqli = new mysqli('localhost', $_SESSION['data']['user'], $_SESSION['data']['pass'], $_SESSION['data']['db']);
          if (mysqli_connect_errno()) {printf("Connect failed: %s\n", mysqli_connect_error());exit();
          }
          if ($stmt = $mysqli->prepare($sql)){
              $stmt->bind_param('s',$acct);
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
                    
                    $event = new Event();
                    $event = $this->assignValues($event,$row);
                    //print_r($this);
                    //exit('done');
                    
                    array_push($events,$event);
                    
              }
              $stmt->close();
          } else {
              exit('Failed prepare: ' . $sql . '<br/>' . mysql_error()) ;
          }
          $mysqli->close();
          //print_r($events);
          //exit('done');
          return $events;

    }
    
    
    
    public function retrieveListFromDb ($acct){
        $events = array();   
        $events = $this->retrieveListFromDbNew($acct, "ORDER BY code DESC");
        return $events;

    }
    
    public function retrieveListFromDbDateDesc ($acct){
        
        
          $events = array();  
        
          $sql = "SELECT * FROM events WHERE acct = ? order by start_date DESC";
          $mysqli = new mysqli('localhost', $_SESSION['data']['user'], $_SESSION['data']['pass'], $_SESSION['data']['db']);
          if (mysqli_connect_errno()) {printf("Connect failed: %s\n", mysqli_connect_error());exit();
          }
          if ($stmt = $mysqli->prepare($sql)){
              $stmt->bind_param('s',$acct);
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
                    
                    $event = new Event();
                    $event = $this->assignValues($event,$row);
                    //print_r($this);
                    //exit('done');
                    
                    array_push($events,$event);
                    
              }
              $stmt->close();
          } else {
              exit('Failed prepare: ' . $sql . '<br/>' . mysql_error()) ;
          }
          $mysqli->close();
          //print_r($events);
          //exit('done');
          return $events;

    }
    
    public function retrieveLatestEvent ($acct){
        
        
          $eventString = "";
        
          $sql = "SELECT * FROM events WHERE acct = ? order by start_date DESC limit 1";
          $mysqli = new mysqli('localhost', $_SESSION['data']['user'], $_SESSION['data']['pass'], $_SESSION['data']['db']);
          if (mysqli_connect_errno()) {printf("Connect failed: %s\n", mysqli_connect_error());exit();
          }
          if ($stmt = $mysqli->prepare($sql)){
              $stmt->bind_param('s',$acct);
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
                    
                    $event = new Event();
                    $event = $this->assignValues($event,$row);
                    
                    $eventString = $event->code;
//                    exit("event string:" . $eventString);

                    //print_r($this);
                    //exit('done');
                    
//                    array_push($events,$event);
                    
              }
              $stmt->close();
          } else {
              exit('Failed prepare: ' . $sql . '<br/>' . mysql_error()) ;
          }
          $mysqli->close();
          //print_r($events);
//          exit("event string:" . $eventString);
          return $eventString;

    }


    
    
    
    public function isEventActive (){
        $status = 0;

        // determine if the event is active based on status.

         $sql = "SELECT status FROM events WHERE code = ?";
         $mysqli = new mysqli('localhost', $_SESSION['data']['user'], $_SESSION['data']['pass'], $_SESSION['data']['db']);
         if (mysqli_connect_errno()) {printf("Connect failed: %s\n", mysqli_connect_error());exit();}
         $stmt = $mysqli->prepare($sql) or exit('Failed prepare: ' . $sql . '<br/>' . $mysqli->error) ;
         $stmt->bind_param('s',$this->eventCode);
         if (!$stmt->execute()){echo 'Invalid query: ' . $sql . '<br/><br/>';printf("Errormessage: %s\n", $mysqli->error);}
         $stmt->bind_result($status);
         while ($stmt->fetch()) {
                 // do nothing.
         }
         $stmt->close();
         $mysqli->close();

         echo "<!-- isEventActive().. event: " . $eventCode . " status: " . $status . " -->";

         return $status;

    }


    
    
    
    
    
    function buildAdminSelectBox($acct){
        if (!empty($_REQUEST['event_code'])){
            $_SESSION['event_code'] = $_REQUEST['event_code'];
            $this->loadFromDb($acct, $_REQUEST['event_code']);
            $_SESSION['event'] = $this;
            //print_r($_SESSION['event']);
            //exit('done.');
        }
        
        
        
        $selectBox = '<form id="eventForm" action="">
            <input type="hidden" name="task" value="showDataMenu">
            <select name="event_code" style="font-face: arial; align: center;" onChange=\'document.getElementById("eventForm").submit();\'>
            ' . "\n";
        
        //$events = $this->retrieveActiveListFromDb($acct);
        $events = $this->retrieveListFromDbDateDesc($acct);
        $counter = 0;
        foreach ($events as $event){
            if ($_SESSION['event_code'] == $event->code){
                $selectBox .= '<option value="' . $event->code . '" SELECTED>' . $event->code . ' - ' . $event->description . '</option>' . "\n";
            } else {
                $selectBox .= '<option value="' . $event->code . '">' . $event->code . ' - ' . $event->description . '</option>' . "\n";
            }
            $counter++;
        }
        
        
        if ($counter > 0){
            $selectBox .= '</select></form>';
        } else {
            $selectBox = '';
        }
        
        return $selectBox;
    }

    
    
    function uploadEventImg1($acct, $eventCode, $blobData){
     
    
     //echo '<img src="data:image/png;base64,' . base64_encode($blobData) . '"/>';
     //echo 'acct: ' . $acct . '<br>';
     //echo 'eventCode: ' . $eventCode . '<br>';
     //echo 'productCode: ' . $productCode . '<br>';
     //exit;
     
     

     $mysqli = new mysqli('localhost', $_SESSION['data']['user'], $_SESSION['data']['pass'], $_SESSION['data']['db']);
     if (mysqli_connect_errno()) {printf("Connect failed: %s\n", mysqli_connect_error());exit();}
     $sql = "UPDATE events SET
                img1=?
                WHERE acct = ? AND code = ?;";
     $stmt = $mysqli->prepare($sql) or exit('Failed prepare: ' . $sql . '<br/>' . $mysqli->error) ;
     
     $stmt->bind_param(
     'bss',
     $blobData,
     $acct,
     $eventCode
     );
     $stmt->send_long_data(0, $blobData);
     if (!$stmt->execute()){
         echo 'Invalid query: ' . $sql . '<br/><br/>';
         printf("Errormessage: %s\n", $mysqli->error);
         exit;
     }
     $stmt->close();
     $mysqli->close();

}


    
    function uploadEventImg1Map($acct, $eventCode, $img1Map){
      
        
        
      //echo 'acct: ' . $acct . '<br>';
      //echo 'eventCode: ' . $eventCode . '<br>';
      //exit;
      
     //echo 'img1Map: <textarea>' . $img1Map . '</textarea><br>';
     //exit;
     
     

     $mysqli = new mysqli('localhost', $_SESSION['data']['user'], $_SESSION['data']['pass'], $_SESSION['data']['db']);
     if (mysqli_connect_errno()) {printf("Connect failed: %s\n", mysqli_connect_error());exit();}
     $sql = "UPDATE events SET
                img1_map=?
                WHERE acct = ? AND code = ?;";
     $stmt = $mysqli->prepare($sql) or exit('Failed prepare: ' . $sql . '<br/>' . $mysqli->error) ;
     
     $stmt->bind_param(
     'sss',
     $img1Map,
     $acct,
     $eventCode
     );
     if (!$stmt->execute()){
         echo 'Invalid query: ' . $sql . '<br/><br/>';
         printf("Errormessage: %s\n", $mysqli->error);
         exit;
     }
     $stmt->close();
     $mysqli->close();

}

    
    
    
    
}





?>
