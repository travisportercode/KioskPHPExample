<?php


class Login
{
    // property declaration
    public $id = 0;
    public $email = '';
    public $first = '';
    public $last = '';
    public $password = '';
    public $organizerAcct = '';
    public $role = '';
    
    public function loadFromDb ($email, $password){
        
          $sql = "SELECT * FROM logins WHERE acct = ? AND email = ? and password = ? LIMIT 1";
          $mysqli = new mysqli('localhost', $_SESSION['data']['user'], $_SESSION['data']['pass'], $_SESSION['data']['db']);
          if (mysqli_connect_errno()) {printf("Connect failed: %s\n", mysqli_connect_error());exit();
          }
          $recordCount = 0;
          if ($stmt = $mysqli->prepare($sql)){
              $stmt->bind_param('sss',$_SESSION['account']->acct, $email, $password);
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
                    $this->first = $row['first'];
                    $this->last = $row['last'];
                    $this->password = $row['password'];
                    $this->organizerAcct = $row['organizer_acct'];
                    $this->role = $row['role'];
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
