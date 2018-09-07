<?php


class ShippingMethod
{
    // property declaration
    public $id = 0;
    public $acct = '';
    public $eventCode = '';
    public $code = '';
    public $description = '';
    public $price = 0;
    public $message = '';
    
    
    
    public function findShippingMethodFromCode($code){
        foreach($_SESSION['shippingMethods'] as $shippingMethod){
            if ($shippingMethod->code == $code){
                return $shippingMethod;
            }
        }
        
        return false;
    }
    
    public function retrieveActiveListFromDb ($acct, $eventCode){
        
          $shippingMethods = array();  
        
          $sql = "SELECT * FROM shipping_methods WHERE acct = ? AND event_code = ? AND status = 1 order by listing_order";
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

                    $shippingMethod = new ShippingMethod();
                    $shippingMethod->id = $row['id'];
                    $shippingMethod->acct = $row['acct'];
                    $shippingMethod->eventCode = $row['event_code'];
                    $shippingMethod->code = $row['code'];
                    $shippingMethod->description = $row['description'];
                    $shippingMethod->price = $row['price'];
                    $shippingMethod->status = $row['status'];
                    $shippingMethod->message = $row['message'];
                    
                    if ($_SESSION['account']->acct == '71656' && $_SESSION['event']->code == '2013FAIR'){
                        $oneProduct = new Product();
                        if ($oneProduct->orderedAtLeastOneOfTheseProducts('SEAS') || $oneProduct->orderedAtLeastOneOfTheseProducts('ADVSEA')){
                            if ($shippingMethod->code == 'PAH'){
                                // do not add PAH if season pass qty = 0.
                            } else {
                                array_push($shippingMethods,$shippingMethod);
                            }
                        } else {
                            array_push($shippingMethods,$shippingMethod); // season pass qty = 0.
                        }
                    } else {    
                        array_push($shippingMethods,$shippingMethod);
                    }
                    
                   
                    
              }
              $stmt->close();
          } else {
              exit('Failed prepare: ' . $sql . '<br/>' . mysql_error()) ;
          }
          $mysqli->close();

        //echo "<pre>";
        //print_r($shippingMethods);
        //echo "</pre>";
        //exit;
        

        return $shippingMethods;

    }
    
    
    
    
    public function loadFromDb ($acct, $eventCode, $code){
        
        
          $sql = "SELECT * FROM shipping_method WHERE acct = ? AND event_code = ? AND code = ?";
          $mysqli = new mysqli('localhost', $_SESSION['data']['user'], $_SESSION['data']['pass'], $_SESSION['data']['db']);
          if (mysqli_connect_errno()) {printf("Connect failed: %s\n", mysqli_connect_error());exit();
          }
          if ($stmt = $mysqli->prepare($sql)){
              $stmt->bind_param('sss',$acct, $eventCode, $code);
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
                    
                    
                    $this->id = $row['id'];
                    $this->acct = $row['acct'];
                    $this->eventCode = $row['event_code'];
                    $this->code = $row['code'];
                    $this->description = $row['description'];
                    $this->price = $row['price'];
                    $this->status = $row['status'];
                    $this->message = $row['message'];
                    
                    
                   
                    
              }
              $stmt->close();
          } else {
              exit('Failed prepare: ' . $sql . '<br/>' . mysql_error()) ;
          }
          $mysqli->close();

        

         return;

    }
    
    
    
    
    public function isActive (){
        $status = 0;

        // determine if the event is active based on status.

         $sql = "SELECT status FROM shipping_method WHERE id = ?";
         $mysqli = new mysqli('localhost', $_SESSION['data']['user'], $_SESSION['data']['pass'], $_SESSION['data']['db']);
         if (mysqli_connect_errno()) {printf("Connect failed: %s\n", mysqli_connect_error());exit();}
         $stmt = $mysqli->prepare($sql) or exit('Failed prepare: ' . $sql . '<br/>' . $mysqli->error) ;
         $stmt->bind_param('s',$this->id);
         if (!$stmt->execute()){echo 'Invalid query: ' . $sql . '<br/><br/>';printf("Errormessage: %s\n", $mysqli->error);}
         $stmt->bind_result($status);
         while ($stmt->fetch()) {
                 // do nothing.
         }
         $stmt->close();
         $mysqli->close();

         echo "<!-- ShippingMethod->isActive().. id: " . $id . " status: " . $status . " -->";

         return $status;

    }


}



function buildShippingSelect(){
   
    $shippingSelect = '<select name="shipping_method" class="superlongtextinput">';
    foreach($_SESSION['shippingMethods'] as $shippingMethod){
        
        $seasonPassOrderQty = 0;
        $product = new Product();
        $seasonPassOrderQty = $product->countSeasonPassOrderQty();
        // only allow WC shipping code if the season pass qty is greater than 0.
        //exit('seasonPassOrderQty: ' . $seasonPassOrderQty);
        if ($shippingMethod->code == 'WC' || $shippingMethod->code == 'USPS'){
            if ($seasonPassOrderQty > 0){
                $shippingSelect .= '<option value="' . $shippingMethod->code . '">' . $shippingMethod->description . ' - $' . $shippingMethod->price . '</option>';
            }
        } else{
            //exit('seasonPassOrderQty: ' . $seasonPassOrderQty);
            if ($seasonPassOrderQty == 0){
                $shippingSelect .= '<option value="' . $shippingMethod->code . '">' . $shippingMethod->description . ' - $' . $shippingMethod->price . '</option>';
            }
        }
        
    }
    $shippingSelect .= '</select>';
    
    return $shippingSelect;
}


        

        



?>
