<?php


class Promo
{
    // property declaration
    public $id = 0;
    public $acct = '';
    public $eventCode = '';
    public $promoCode = '';
    public $type = '';
    public $dateAvailableStart = '';
    public $dateAvailableEnd = '';
    public $totalAvailable = '';
    
    
    
    // THIS ONLY RETRIEVES ACTIVE LIST!!!!!
    public function retrieveListFromDb ($acct, $eventCode){
        
        
          $promos = array();  
        
          $sql = "SELECT * FROM promos WHERE acct = ? AND event_code = ?";
          $mysqli = new mysqli('localhost', $_SESSION['data']['user'], $_SESSION['data']['pass'], $_SESSION['data']['db']);
          if (mysqli_connect_errno()) {printf("Connect failed: %s\n", mysqli_connect_error());exit();
          }
          if ($stmt = $mysqli->prepare($sql)){
              $stmt->bind_param('ss',$acct, $eventCode);
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

                    $promo = new Promo();
                    $promo->id = $row['id'];
                    $promo->acct = $row['acct'];
                    $promo->eventCode = $row['event_code'];
                    $promo->promoCode = $row['promo_code'];
                    $promo->type = $row['type'];
                    $promo->dateAvailableStart = $row['date_available_start'];
                    $promo->dateAvailableEnd = $row['date_available_end'];
                    $promo->totalAvailable = $row['total_available'];
                    
                    
                    if  (
                            (date('Y-m-d H:i:s') >= $promo->dateAvailableStart && date('Y-m-d H:i:s') <= $promo->dateAvailableEnd)
                             || $promo->dateAvailableStart == ''
                        ){
                            array_push($promos,$promo);
                        }
                    
              }
              $stmt->close();
          } else {
              exit('Failed prepare: ' . $sql . '<br/>' . mysql_error()) ;
          }
          $mysqli->close();
          
          
         return $promos;

    }
    
    
    public function retrieveAllFromDbByEvent ($acct, $eventCode){
          $promos = array();  
        
          $sql = "SELECT * FROM promos WHERE acct = ? AND event_code = ?";
          $mysqli = new mysqli('localhost', $_SESSION['data']['user'], $_SESSION['data']['pass'], $_SESSION['data']['db']);
          if (mysqli_connect_errno()) {printf("Connect failed: %s\n", mysqli_connect_error());exit();
          }
          if ($stmt = $mysqli->prepare($sql)){
              $stmt->bind_param('ss',$acct, $eventCode);
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

                    $promo = new Promo();
                    $promo->id = $row['id'];
                    $promo->acct = $row['acct'];
                    $promo->eventCode = $row['event_code'];
                    $promo->promoCode = $row['promo_code'];
                    $promo->type = $row['type'];
                    $promo->dateAvailableStart = $row['date_available_start'];
                    $promo->dateAvailableEnd = $row['date_available_end'];
                    $promo->totalAvailable = $row['total_available'];
                    
                    
                    
                    array_push($promos,$promo);
                     
                    
              }
              $stmt->close();
          } else {
              exit('Failed prepare: ' . $sql . '<br/>' . mysql_error()) ;
          }
          $mysqli->close();
          
          
         return $promos;

        
    }
    
    

}



?>
