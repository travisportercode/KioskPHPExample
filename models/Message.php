<?php


class Message
{
    public $id = 0;
    public $clientName = '';
    public $kiosk = '';
    public $description = '';
    public $value = '';
    public $dateChanged = '';
    public $userChanged = '';
    
    
    public function loadFromDb ($kiosk, $description, $clientName){
        
          $sql = "SELECT * FROM messages WHERE kiosk = ? AND description = ? AND client_name = ? LIMIT 1";
          $mysqli = new mysqli('localhost', $_SESSION['data']['user'], $_SESSION['data']['pass'], $_SESSION['data']['db']);
          if (mysqli_connect_errno()) {printf("Connect failed: %s\n", mysqli_connect_error());exit();
          }
          $recordCount = 0;
          if ($stmt = $mysqli->prepare($sql)){
              $stmt->bind_param('sss', $kiosk, $description, $clientName);
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
                    $this->kiosk = $row['kiosk'];
                    $this->description = $row['description'];
                    $this->value = $row['value'];
                    $this->dateChanged = $row['date_changed'];
                    $this->userChanged = $row['user_changed'];
                     
                    
                     
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
        
         
          $messages = array();  
        
          $sql = "SELECT * FROM messages WHERE client_name = ? order by kiosk";
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

                    $message = new Message();
                    $message->id = $row['id'];
                    $message->clientName = $row['client_name'];
                    $message->kiosk = $row['kiosk'];
                    $message->description = $row['description'];
                    $message->value = $row['value'];
                    $message->dateChanged = $row['date_changed'];
                    $message->userChanged = $row['user_changed'];
    
                    
                    array_push($messages,$message);
                    
                    
              }
              $stmt->close();
          } else {
              exit('Failed prepare: ' . $sql . '<br/>' . $mysqli->error) ;
          }
          $mysqli->close();

        
           
         return $messages;

    }
    
    
    
    

}





?>
