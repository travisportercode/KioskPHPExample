<?php


class Kiosk
{
    public $id = 0;
    public $clientName = '';
    public $description = '';
    public $macAddress = '';
    public $name = '';
    public $ipAddress = '';
    public $startUrl = '';
    public $status = '';
    public $ewwl2Port = '';
    public $browser = '';
    public $convertedToLinuxStatusServer = '';
    public $notes = '';
    public $outOfOrder = 0;
    public $printerError = '';
    public $printerErrorNotified = '';
    public $location = '';
    
    
    public function loadFromDb ($name, $clientName){
        
          $sql = "SELECT * FROM kiosks WHERE name = ? AND client_name = ? LIMIT 1";
          $mysqli = new mysqli('localhost', $_SESSION['data']['user'], $_SESSION['data']['pass'], $_SESSION['data']['db']);
          if (mysqli_connect_errno()) {printf("Connect failed: %s\n", mysqli_connect_error());exit();
          }
          $recordCount = 0;
          if ($stmt = $mysqli->prepare($sql)){
              $stmt->bind_param('ss', $name, $clientName);
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
                    $this->clientName = $row['client_name'];
                    $this->description = $row['description'];
                    $this->macAddress = $row['mac_address'];
                    $this->name = $row['name'];
                    $this->ipAddress = $row['ip_address'];
                    $this->startUrl = $row['start_url'];
                    $this->status = $row['status'];
                    $this->ewwl2Port = $row['ewwl2_port'];
                    $this->browser = $row['browser'];
                    $this->convertedToLinuxStatusServer = $row['converted_to_linuxstatusserver'];
                    $this->notes = $row['notes'];
                    $this->outOfOrder = $row['out_of_order'];
                    $this->printerError = $row['printer_error'];
                    $this->printerErrorNotified = $row['printer_error_notified'];
                    $this->location = $row['location'];
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
    
    
    public function retrieveListFromDb ($clientName){
        
        
          $kiosks = array();  
        
          $sql = "SELECT * FROM kiosks WHERE client_name = ? order by ewwl2_port";
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

                    $kiosk = new Kiosk();
                    $kiosk->id = $row['id'];
                    $kiosk->clientName = $row['client_name'];
                    $kiosk->description = $row['description'];
                    $kiosk->macAddress = $row['mac_address'];
                    $kiosk->name = $row['name'];
                    $kiosk->ipAddress = $row['ip_address'];
                    $kiosk->startUrl = $row['start_url'];
                    $kiosk->status = $row['status'];
                    $kiosk->ewwl2Port = $row['ewwl2_port'];
                    $kiosk->browser = $row['browser'];
                    $kiosk->convertedToLinuxStatusServer = $row['converted_to_linuxstatusserver'];
                    $kiosk->notes = $row['notes'];
                    $kiosk->outOfOrder = $row['out_of_order'];
                    $kiosk->printerError = $row['printer_error'];
                    $kiosk->printerErrorNotified = $row['printer_error_notified'];
                    $kiosk->location = $row['location'];
    
                    
                    array_push($kiosks,$kiosk);
                    
                    
              }
              $stmt->close();
          } else {
              exit('Failed prepare: ' . $sql . '<br/>' . $mysqli->error) ;
          }
          $mysqli->close();

        

         return $kiosks;

    }
    
    public function updateStartUrl($clientName, $kioskName, $startUrl){
        
        $mysqli = new mysqli('localhost', $_SESSION['data']['user'], $_SESSION['data']['pass'], $_SESSION['data']['db']);
        if (mysqli_connect_errno()) {printf("Connect failed: %s\n", mysqli_connect_error());exit();}
        $sql = "UPDATE kiosks SET
           start_url = ?
           WHERE client_name = ? and name = ?;";
        $stmt = $mysqli->prepare($sql) or exit('Failed prepare: ' . $sql . '<br/>' . $mysqli->error) ;
        $stmt->bind_param(
        'sss',
        $startUrl,
        $clientName,
        $kioskName
        );
        if (!$stmt->execute()){echo 'Invalid query: ' . $sql . '<br/><br/>';printf("Errormessage: %s\n", $mysqli->error);exit;}
        $stmt->close();
        $mysqli->close();
        
        return;
    }
    
    
    
    
    public function updateOfflineMode($clientName, $kioskName, $outOfOrder){
        
        $mysqli = new mysqli('localhost', $_SESSION['data']['user'], $_SESSION['data']['pass'], $_SESSION['data']['db']);
        if (mysqli_connect_errno()) {printf("Connect failed: %s\n", mysqli_connect_error());exit();}
        $sql = "UPDATE kiosks SET
           out_of_order = ?
           WHERE client_name = ? and name = ?;";
        $stmt = $mysqli->prepare($sql) or exit('Failed prepare: ' . $sql . '<br/>' . $mysqli->error) ;
        $stmt->bind_param(
        'sss',
        $outOfOrder,
        $clientName,
        $kioskName
        );
        if (!$stmt->execute()){echo 'Invalid query: ' . $sql . '<br/><br/>';printf("Errormessage: %s\n", $mysqli->error);exit;}
        $stmt->close();
        $mysqli->close();
        
        return;
    }
    
    

}





?>
