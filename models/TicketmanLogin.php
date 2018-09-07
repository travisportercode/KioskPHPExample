<?php


class TicketmanLogin
{
    // property declaration
    public $id = 0;
    public $email = '';
    public $password = '';
    public $first = '';
    public $last = '';
    public $acct = '';
    public $role = '';
    public $errorNotify = 0;
    
    public function loadFromDb ($email, $password){
        
          $sql = "SELECT * FROM ticketman_logins WHERE email = ? and password = ? LIMIT 1";
          $mysqli = new mysqli('localhost', $_SESSION['data']['user'], $_SESSION['data']['pass'], $_SESSION['data']['db']);
          if (mysqli_connect_errno()) {printf("Connect failed: %s\n", mysqli_connect_error());exit();
          }
          $recordCount = 0;
          if ($stmt = $mysqli->prepare($sql)){
              $stmt->bind_param('ss', $email, $password);
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
                    $this->email = $row['email'];
                    $this->password = $row['password'];
                    $this->first = $row['first'];
                    $this->last = $row['last'];
                    $this->acct = $row['acct'];
                    $this->role = $row['role'];
                    $this->errorNotify = $row['error_notify'];
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
    
    public function loadFromDbNoPassword ($email){

          $sql = "SELECT * FROM ticketman_logins WHERE email = ? LIMIT 1";
          $mysqli = new mysqli('localhost', $_SESSION['data']['user'], $_SESSION['data']['pass'], $_SESSION['data']['db']);
          if (mysqli_connect_errno()) {printf("Connect failed: %s\n", mysqli_connect_error());exit();
          }
          $recordCount = 0;
          if ($stmt = $mysqli->prepare($sql)){
              $stmt->bind_param('s', $email);
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
                    $this->email = $row['email'];
                    $this->password = $row['password'];
                    $this->first = $row['first'];
                    $this->last = $row['last'];
                    $this->acct = $row['acct'];
                    $this->role = $row['role'];
                    $this->errorNotify = $row['error_notify'];
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

    public function retrieveListFromDb (){
        
          $logins = array();  
        

          $sql = "SELECT * FROM ticketman_logins WHERE acct='". $_SESSION['login']->acct . "'";
          $mysqli = new mysqli('localhost', $_SESSION['data']['user'], $_SESSION['data']['pass'], $_SESSION['data']['db']);
          if (mysqli_connect_errno()) {printf("Connect failed: %s\n", mysqli_connect_error());exit();
          }
          if ($stmt = $mysqli->prepare($sql)){
              //$stmt->bind_param('s',$clientName);
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

                    $login = new Login();
                    $login->id = $row['id'];
                    $login->email = $row['email'];
                    $login->password = $row['password'];
                    $login->first = $row['first'];
                    $login->last = $row['last'];
                    $login->acct = $row['acct'];
                    $login->role = $row['role'];
                    $login->errorNotify = $row['error_notify'];
    
                    
                    array_push($logins,$login);
                    
                    
              }
              $stmt->close();
          } else {
              exit('Failed prepare: ' . $sql . '<br/>' . $mysqli->error) ;
          }
          $mysqli->close();    

         return $logins;

    }
    
    
    public function loadFromDbForgotPassword ($email){
        
          $sql = "SELECT * FROM ticketman_logins WHERE email = ? LIMIT 1";
          $mysqli = new mysqli('localhost', $_SESSION['data']['user'], $_SESSION['data']['pass'], $_SESSION['data']['db']);
          if (mysqli_connect_errno()) {printf("Connect failed: %s\n", mysqli_connect_error());exit();
          }
          $recordCount = 0;
          if ($stmt = $mysqli->prepare($sql)){
              $stmt->bind_param('s',$email);
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
                    $this->email = $row['email'];
                    $this->password = $row['password'];
                    $this->first = $row['first'];
                    $this->last = $row['last'];
                    $this->acct = $row['acct'];
                    $this->role = $row['role'];
                    $this->errorNotify = $row['error_notify'];
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
    

}





?>
