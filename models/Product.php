<?php


class Product
{
    // property declaration
    public $id = 0;
    public $acct = '';
    public $eventCode = '';
    public $code = '';
    public $categoryPrefix = '';
    public $description = '';
    public $description2 = '';
    public $price = 0;
    public $value = 0;
    public $linkData = '';
    public $status = 0;
    public $img1 = ''; // small icon - should be uploaded as 64x64.
    public $img2 = '';
    public $qty = 0;
    public $total = 0;
    public $totalAvailable = 0;
    public $dateAvailableStart = '';
    public $dateAvailableEnd = '';
    
    public $pahSmallLine1 = '';
    public $pahSmallLine2 = '';
    public $pahLine1 = '';
    public $pahLine2 = '';
    public $pahLine3 = '';
    public $pahInstructions = '';
    
    
    public function retrieveListFromDb ($acct, $eventCode){
        
        
          $products = array();  
        
          $sql = "SELECT * FROM products WHERE acct = ? AND event_code = ? AND list_on_site=1 order by listing_order";
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

                    $product = new Product();
                    $product->id = $row['id'];
                    $product->acct = $row['acct'];
                    $product->eventCode = $row['event_code'];
                    $product->categoryPrefix = $row['category_prefix'];
                    $product->code = $row['code'];
                    $product->description = $row['description'];
                    $product->description2 = $row['description2'];
                    $product->price = $row['price'];
                    $product->value = $row['value'];
                    $product->linkData = $row['link_data'];
                    $product->img1 = $row['img1'];
                    $product->img2 = $row['img2'];
                    $product->status = $row['status'];
                    $product->totalAvailable = $row['total_available'];
                    $product->dateAvailableStart = $row['date_available_start'];
                    $product->dateAvailableEnd = $row['date_available_end'];
                    
                    
                    $product->pahSmallLine1 = $row['pah_small_line1'];
                    $product->pahSmallLine2 = $row['pah_small_line2'];
                    $product->pahLine1 = $row['pah_small_line1'];
                    $product->pahLine2 = $row['pah_small_line2'];
                    $product->pahLine3 = $row['pah_small_line3'];
                    $product->pahInstructions = $row['pah_instructions'];
                    
                    
                    //echo '<!-- product: ';
                    //print_r($product); 
                    //echo '-->';
                    
                    array_push($products,$product);
                    
                    
                    //echo '<!-- products: ';
                    //print_r($products); 
                    //echo '-->';
                    
              }
              $stmt->close();
          } else {
              exit('Failed prepare: ' . $sql . '<br/>' . mysql_error()) ;
          }
          $mysqli->close();

        

         return $products;

    }
    
    
    
    public function retrieveActiveListFromDb ($acct, $eventCode){
        
        
          $products = array();  
        
          $sql = "SELECT * FROM products WHERE acct = ? AND event_code = ? AND status = 1 AND list_on_site=1 order by listing_order";
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

                    $product = new Product();
                    $product->id = $row['id'];
                    $product->acct = $row['acct'];
                    $product->eventCode = $row['event_code'];
                    $product->categoryPrefix = $row['category_prefix'];
                    $product->code = $row['code'];
                    $product->description = $row['description'];
                    $product->description2 = $row['description2'];
                    $product->price = $row['price'];
                    $product->value = $row['value'];
                    $product->linkData = $row['link_data'];
                    $product->img1 = $row['img1'];
                    $product->img2 = $row['img2'];
                    $product->status = $row['status'];
                    $product->totalAvailable = $row['total_available'];
                    $product->dateAvailableStart = $row['date_available_start'];
                    $product->dateAvailableEnd = $row['date_available_end'];
                    
                    $product->pahSmallLine1 = $row['pah_small_line1'];
                    $product->pahSmallLine2 = $row['pah_small_line2'];
                    $product->pahLine1 = $row['pah_line1'];
                    $product->pahLine2 = $row['pah_line2'];
                    $product->pahLine3 = $row['pah_line3'];
                    $product->pahInstructions = $row['pah_instructions'];
                    //echo '<!-- product: ';
                    //print_r($product); 
                    //echo '-->';
                    
                    array_push($products,$product);
                    
                    
                    //echo '<!-- products: ';
                    //print_r($products); 
                    //echo '-->';
                    
              }
              $stmt->close();
          } else {
              exit('Failed prepare: ' . $sql . '<br/>' . mysql_error()) ;
          }
          $mysqli->close();

        

         return $products;

    }
    
    
    
    
    public function retrieveActiveListFromDbTemp ($acct, $eventCode){
        
        
          $products = array();  
        
          $sql = "SELECT * FROM products WHERE acct = ? AND event_code = ? AND status = 1 order by listing_order";
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

                    $product = new Product();
                    $product->id = $row['id'];
                    $product->acct = $row['acct'];
                    $product->eventCode = $row['event_code'];
                    $product->categoryPrefix = $row['category_prefix'];
                    $product->code = $row['code'];
                    $product->description = $row['description'];
                    $product->description2 = $row['description2'];
                    $product->price = $row['price'];
                    $product->value = $row['value'];
                    $product->linkData = $row['link_data'];
                    $product->img1 = $row['img1'];
                    $product->img2 = $row['img2'];
                    $product->status = $row['status'];
                    $product->totalAvailable = $row['total_available'];
                    $product->dateAvailableStart = $row['date_available_start'];
                    $product->dateAvailableEnd = $row['date_available_end'];
                    
                    $product->pahSmallLine1 = $row['pah_small_line1'];
                    $product->pahSmallLine2 = $row['pah_small_line2'];
                    $product->pahLine1 = $row['pah_small_line1'];
                    $product->pahLine2 = $row['pah_small_line2'];
                    $product->pahLine3 = $row['pah_small_line3'];
                    $product->pahInstructions = $row['pah_instructions'];
                    //echo '<!-- product: ';
                    //print_r($product); 
                    //echo '-->';
                    
                    array_push($products,$product);
                    
                    
                    //echo '<!-- products: ';
                    //print_r($products); 
                    //echo '-->';
                    
              }
              $stmt->close();
          } else {
              exit('Failed prepare: ' . $sql . '<br/>' . mysql_error()) ;
          }
          $mysqli->close();

        

         return $products;

    }
    
    
    
    
    public function loadFromDbUsingId ($id){
        
        
          $sql = "SELECT * FROM products WHERE id = ?";
          $mysqli = new mysqli('localhost', $_SESSION['data']['user'], $_SESSION['data']['pass'], $_SESSION['data']['db']);
          if (mysqli_connect_errno()) {printf("Connect failed: %s\n", mysqli_connect_error());exit();
          }
          if ($stmt = $mysqli->prepare($sql)){
              $stmt->bind_param('d',$id);
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
                    $this->categoryPrefix = $row['category_prefix'];
                    $this->code = $row['code'];
                    $this->description = $row['description'];
                    $this->description2 = $row['description2'];
                    $this->price = $row['price'];
                    $this->value = $row['value'];
                    $this->linkData = $row['link_data'];
                    $this->img1 = $row['img1'];
                    $this->img2 = $row['img2'];
                    $this->status = $row['status'];
                    $this->totalAvailable = $row['total_available'];
                    $this->dateAvailableStart = $row['date_available_start'];
                    $this->dateAvailableEnd = $row['date_available_end'];
                    
                    
                    $this->pahSmallLine1 = $row['pah_small_line1'];
                    $this->pahSmallLine2 = $row['pah_small_line2'];
                    $this->pahLine1 = $row['pah_line1'];
                    $this->pahLine2 = $row['pah_line2'];
                    $this->pahLine3 = $row['pah_line3'];
                    $this->pahInstructions = $row['pah_instructions'];
                    
              }
              $stmt->close();
          } else {
              exit('Failed prepare: ' . $sql . '<br/>' . mysql_error()) ;
          }
          $mysqli->close();

        

         return;

    }
    
    
    public function loadFromDb ($acct, $eventCode, $code){
        
        
          $sql = "SELECT * FROM products WHERE acct = ? AND event_code = ? AND code = ?";
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
                    $this->categoryPrefix = $row['category_prefix'];
                    $this->code = $row['code'];
                    $this->description = $row['description'];
                    $this->description2 = $row['description2'];
                    $this->price = $row['price'];
                    $this->value = $row['value'];
                    $this->linkData = $row['link_data'];
                    $this->img1 = $row['img1'];
                    $this->img2 = $row['img2'];
                    $this->status = $row['status'];
                    $this->totalAvailable = $row['total_available'];
                    $this->dateAvailableStart = $row['date_available_start'];
                    $this->dateAvailableEnd = $row['date_available_end'];
                    
                    
                    $this->pahSmallLine1 = $row['pah_small_line1'];
                    $this->pahSmallLine2 = $row['pah_small_line2'];
                    $this->pahLine1 = $row['pah_small_line1'];
                    $this->pahLine2 = $row['pah_small_line2'];
                    $this->pahLine3 = $row['pah_small_line3'];
                    $this->pahInstructions = $row['pah_instructions'];
                    
                    
              }
              $stmt->close();
          } else {
              exit('Failed prepare: ' . $sql . '<br/>' . mysql_error()) ;
          }
          $mysqli->close();

        

         return;

    }
    
    
    
    
    public function loadFromDbUsingPrefix ($acct, $eventCode, $prefix){
        
        
          //echo '<!-- Product->loadFromDbUsingPrefix(): acct: ' . $acct . ' eventCode: ' . $eventCode . ' code: ' . $prefix . '-->';  
          $sql = "SELECT * FROM products WHERE acct = ? AND event_code = ? AND category_prefix = ?";
          $mysqli = new mysqli('localhost', $_SESSION['data']['user'], $_SESSION['data']['pass'], $_SESSION['data']['db']);
          if (mysqli_connect_errno()) {printf("Connect failed: %s\n", mysqli_connect_error());exit();
          }
          if ($stmt = $mysqli->prepare($sql)){
              $stmt->bind_param('sss',$acct, $eventCode, $prefix);
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
                    $this->categoryPrefix = $row['category_prefix'];
                    $this->code = $row['code'];
                    $this->description = $row['description'];
                    $this->description2 = $row['description2'];
                    $this->price = $row['price'];
                    $this->value = $row['value'];
                    $this->linkData = $row['link_data'];
                    $this->img1 = $row['img1'];
                    $this->img2 = $row['img2'];
                    $this->status = $row['status'];
                    $this->totalAvailable = $row['total_available'];
                    $this->dateAvailableStart = $row['date_available_start'];
                    $this->dateAvailableEnd = $row['date_available_end'];
                    
                    
                    
                    $this->pahSmallLine1 = $row['pah_small_line1'];
                    $this->pahSmallLine2 = $row['pah_small_line2'];
                    $this->pahLine1 = $row['pah_small_line1'];
                    $this->pahLine2 = $row['pah_small_line2'];
                    $this->pahLine3 = $row['pah_small_line3'];
                    $this->pahInstructions = $row['pah_instructions'];
                    
              }
              $stmt->close();
          } else {
              exit('Failed prepare: ' . $sql . '<br/>' . $mysqli->error) ;
          }
          $mysqli->close();

        

         return;

    }
    
    
    
    
    public function isActive (){
        $status = 0;

        // determine if the event is active based on status.

         $sql = "SELECT status FROM products WHERE id = ?";
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

         echo "<!-- Product->isActive().. id: " . $id . " status: " . $status . " -->";

         return $status;

    }


function orderedAtLeastOneOfTheseProducts($prodCode){
    
    foreach($_SESSION['products'] as $product){
        if ($product->code == $prodCode){
            if ($product->qty > 0){
                return true;
            }
        }
    }
    
    
    return false;
}


function updateProductsInSession(){
    
    // update all product quantities.

    // if there is no task, initialize, because we are on step 1.
    if (empty($_REQUEST['task'])){
        
        $_SESSION['products'] = new Product();
        $_SESSION['products'] = $_SESSION['products']->retrieveActiveListFromDb($_SESSION['account']->acct, $_SESSION['event']->code);
    } else {
        if ($_REQUEST['task'] == 'step1'){
            $_SESSION['products'] = new Product();
            $_SESSION['products'] = $_SESSION['products']->retrieveActiveListFromDb($_SESSION['account']->acct, $_SESSION['event']->code);
        } else {   
                
                $allKeysAndValues = '';
                    foreach($_POST as $key => $val){
                        $allKeysAndValues .= $key . '=' . $val . '<br>';
                        if (strstr($key,'prodQty_')){
                            // get the idx.
                            $idx = substr($key,8);
                            $productQty = $val;
                            if ($productQty == ""){
                                $productQty = 0;
                            }
                            //exit('found. ' . $key . '=' . $val . '<br> idx: ' . $idx);
                            $productCode = $_REQUEST['prodCode_' . $idx];
                            for($i=0;$i < count($_SESSION['products']); $i++){
                                if ($productCode == $_SESSION['products'][$i]->code){
                                    $_SESSION['products'][$i]->qty = $productQty;
                                    $_SESSION['products'][$i]->total = sprintf("%.2f",$productQty * $_SESSION['products'][$i]->price);
                                }
                            }
                        }
                    }
                    if (!empty($_POST['seasonPassFirst_0'])){
                        $_SESSION['seasonPassNames'] = array();
                        foreach($_POST as $key => $val){
                            $allKeysAndValues .= $key . '=' . $val . '<br>';
                            if (strstr($key,'seasonPassFirst_')){
                                // get the idx.
                                $idx = substr($key,16);
                                $_SESSION['seasonPassNames'][$idx]['first'] = $_POST['seasonPassFirst_' . $idx];
                                $_SESSION['seasonPassNames'][$idx]['last'] = $_POST['seasonPassLast_' . $idx];
                            }
                        }
                    }

   
        }    
    }
    $this->buildProductHash();
    
    $_SESSION['seasonPassQty'] = 0;
    if ($this->orderedAtLeastOneOfTheseProducts('SEAS')){
        $this->buildSeasonPassNameHash();
    }
    if ($this->orderedAtLeastOneOfTheseProducts('ADVSEA')){
        $this->buildSeasonPassNameHash();
    }
    
}



function updateProductsInSessionForEventbrite(){
    
    // update all product quantities.

    // if there is no task, initialize, because we are on step 1.
    if (empty($_REQUEST['task'])){
        
        $_SESSION['products'] = new Product();
        $_SESSION['products'] = $_SESSION['products']->retrieveActiveListFromDb($_SESSION['account']->acct, $_SESSION['event']->code);
    } else {
        if ($_REQUEST['task'] == 'step1'){
            $_SESSION['products'] = new Product();
            $_SESSION['products'] = $_SESSION['products']->retrieveActiveListFromDb($_SESSION['account']->acct, $_SESSION['event']->code);
        } else {   
                
                $allKeysAndValues = '';
                    foreach($_POST as $key => $val){
                        $allKeysAndValues .= $key . '=' . $val . '<br>';
                        if (strstr($key,'prodQty_')){
                            // get the idx.
                            $idx = substr($key,8);
                            $productQty = $val;
                            if ($productQty == ""){
                                $productQty = 0;
                            }
                            //exit('found. ' . $key . '=' . $val . '<br> idx: ' . $idx);
                            $productCode = $_REQUEST['prodCode_' . $idx];
                            $productPrice = $_REQUEST['prodPrice_' . $idx];
                            for($i=0;$i < count($_SESSION['products']); $i++){
                                if ($productCode == $_SESSION['products'][$i]->code){
                                    $_SESSION['products'][$i]->qty = $productQty;
                                    $_SESSION['products'][$i]->price = $productPrice;
                                    $_SESSION['products'][$i]->total = sprintf("%.2f",$productQty * $_SESSION['products'][$i]->price);
                                }
                            }
                        }
                    }
                    if (!empty($_POST['seasonPassFirst_0'])){
                        $_SESSION['seasonPassNames'] = array();
                        foreach($_POST as $key => $val){
                            $allKeysAndValues .= $key . '=' . $val . '<br>';
                            if (strstr($key,'seasonPassFirst_')){
                                // get the idx.
                                $idx = substr($key,16);
                                $_SESSION['seasonPassNames'][$idx]['first'] = $_POST['seasonPassFirst_' . $idx];
                                $_SESSION['seasonPassNames'][$idx]['last'] = $_POST['seasonPassLast_' . $idx];
                            }
                        }
                    }

   
        }    
    }
    $this->buildProductHash();
    
    $_SESSION['seasonPassQty'] = 0;
    if ($this->orderedAtLeastOneOfTheseProducts('SEAS')){
        $this->buildSeasonPassNameHash();
    }
    if ($this->orderedAtLeastOneOfTheseProducts('ADVSEA')){
        $this->buildSeasonPassNameHash();
    }
    
}


function updateProductsInSessionTemp(){
    
    // update all product quantities.

    // if there is no task, initialize, because we are on step 1.
    if (empty($_REQUEST['task'])){
        
        $_SESSION['products'] = new Product();
        $_SESSION['products'] = $_SESSION['products']->retrieveActiveListFromDbTemp($_SESSION['account']->acct, $_SESSION['event']->code);
    } else {
        if ($_REQUEST['task'] == 'step1'){
            $_SESSION['products'] = new Product();
            $_SESSION['products'] = $_SESSION['products']->retrieveActiveListFromDbTemp($_SESSION['account']->acct, $_SESSION['event']->code);
        } else {   
                
                $allKeysAndValues = '';
                    foreach($_POST as $key => $val){
                        $allKeysAndValues .= $key . '=' . $val . '<br>';
                        if (strstr($key,'prodQty_')){
                            // get the idx.
                            $idx = substr($key,8);
                            $productQty = $val;
                            if ($productQty == ""){
                                $productQty = 0;
                            }
                            //exit('found. ' . $key . '=' . $val . '<br> idx: ' . $idx);
                            $productCode = $_REQUEST['prodCode_' . $idx];
                            for($i=0;$i < count($_SESSION['products']); $i++){
                                if ($productCode == $_SESSION['products'][$i]->code){
                                    $_SESSION['products'][$i]->qty = $productQty;
                                    $_SESSION['products'][$i]->total = sprintf("%.2f",$productQty * $_SESSION['products'][$i]->price);
                                }
                            }
                        }
                    }
                    if (!empty($_POST['seasonPassFirst_0'])){
                        $_SESSION['seasonPassNames'] = array();
                        foreach($_POST as $key => $val){
                            $allKeysAndValues .= $key . '=' . $val . '<br>';
                            if (strstr($key,'seasonPassFirst_')){
                                // get the idx.
                                $idx = substr($key,16);
                                $_SESSION['seasonPassNames'][$idx]['first'] = $_POST['seasonPassFirst_' . $idx];
                                $_SESSION['seasonPassNames'][$idx]['last'] = $_POST['seasonPassLast_' . $idx];
                            }
                        }
                    }

   
        }    
    }
    $this->buildProductHash();
    
    $_SESSION['seasonPassQty'] = 0;
    if ($this->orderedAtLeastOneOfTheseProducts('SEAS')){
        $this->buildSeasonPassNameHash();
    }
    if ($this->orderedAtLeastOneOfTheseProducts('ADVSEA')){
        $this->buildSeasonPassNameHash();
    }
    
}




function checkPassNames(){
    // if at anytime, we don't have enough names, we need to start over.
    
     foreach($_SESSION['products'] as $oneProduct){
        if ($oneProduct->code == 'SEAS' || $oneProduct->code == 'ADVSEA'){
            for($i = 0;$i < $oneProduct->qty;$i++){
                    if (empty($_SESSION['seasonPassNames'][$i]['first']) || empty($_SESSION['seasonPassNames'][$i]['first']) ){  
                        return false;
                    } 
                    if (strlen($_SESSION['seasonPassNames'][$i]['first'])  < 2 || strlen($_SESSION['seasonPassNames'][$i]['first']) < 2){ 
                        return false;
                    }
            }
     
        }
     }
    return true;
}




function convertSeasonPassHashToList($seasonPassHash){
    // if at anytime, we don't have enough names, we need to start over.
    $seasonPassList = array();
    
    $i = 0;
    $seasonPassLines = explode("\n",$seasonPassHash);
    foreach($seasonPassLines as $oneSeasonPassLine){
        $keysAndValues = explode("&",$oneSeasonPassLine);
        foreach ($keysAndValues as $oneProductKeyAndValue){
            $words = explode("=",$oneProductKeyAndValue);
            if ($words[0] == 'first'){
                $seasonPassList[$i]['first'] = $words[1];
            } else if ($words[0] == 'last'){
                $seasonPassList[$i]['last'] = $words[1];
            }
        }
        $i++;
    }
    
    return $seasonPassList;
}


function buildProductContentsTableForEventbrite(){
    //echo "<pre>";
    //print_r($_SESSION);
    //echo "</pre>";
    //exit;
    
    
    
    $_SESSION['productTotal'] = 0.00;
    
    
    $productSubTotal = 0;
    //$discount = 0;
    
    
    $productContentsHeaderStyle = 'style="font-family: Arial, Geneva, sans-serif;font-size: 11pt;font-weight: bold;color: #000000;background-color:#eeeeee;"';
    $productContentsDetailStyle = 'style="font-family: Arial, Geneva, sans-serif;font-size: 10pt;color: #000000;background-color:#ffffff;"';
    $productContentsTotalStyle = 'style="font-family: Arial, Geneva, sans-serif;font-size: 10pt;color: #000000;background-color:#eeeeee;"';
 
    
    $seasonPassNamesOutputStyle = 'style="font-family: Arial, Geneva, sans-serif;font-size: 12pt;font-weight:normal;color: #000000;background-color:#eeeeee;"';
    $seasonPassNamesHeaderStyle = 'style="font-family: Arial, Geneva, sans-serif;font-size: 10pt;font-weight:normal;color: #000000;background-color:#cccccc;"';



   
    
    
    $productContentsTable = '
        <center><table bgcolor=#FFFFFF width=98% style="border: 1px solid black">
                <tr valign=bottom bgcolor=#dddddd>
                    <td ' . $productContentsHeaderStyle . ' width=50% align=left>Selected Products</td>
                    <td ' . $productContentsHeaderStyle . 'align=center valign=bottom>Unit Price</td>
                    <td ' . $productContentsHeaderStyle . ' align=center valign=bottom>Qty</td>
                    <td ' . $productContentsHeaderStyle . ' bgcolor="#e1e1e1" align=right valign=bottom>Total Cost</td>
                </tr>
      ';
    
    // $_SESSION['products'] contains all the products and a quantity of each.
    // the ->qty contains the quantity. We only want a product to appear if the qty > 0.
    
    foreach($_SESSION['products'] as $oneProduct){
        if ($oneProduct->qty > 0){
            
            
            $productSubTotal += $oneProduct->total;
            $productContentsTable .= '
            <tr>
                <td ' . $productContentsDetailStyle . ' align=left>' . $oneProduct->description . '</td>
                <td ' . $productContentsDetailStyle . ' align=center valign=bottom><nobr>$' . $oneProduct->price . '</nobr></td>
                <td ' . $productContentsDetailStyle . ' align=center valign=bottom>
                ' . $oneProduct->qty . '
                </td>
                <td ' . $productContentsDetailStyle . ' bgcolor="#e1e1e1" align=right valign=bottom>
                $' . $oneProduct->total . '
                </td>
            </tr>
            ';
            
           
        }
    }
    
    $productSubTotal = sprintf ("%.2f", $productSubTotal);  // make sure there are only 2 decimals
    if (!empty($_SESSION['discount'])){
        $_SESSION['discount'] = sprintf ("%.2f", $_SESSION['discount']);
    } else {
        $_SESSION['discount'] = 0.00;
    }
    $total = sprintf("%.2f",$productSubTotal + $_SESSION['discount']); // discount is negative.
    $_SESSION['productTotal'] = $total;
    
    
    //exit('discount:' . $_SESSION['discount']);
    
    $productContentsTable .= '
        <tr>
            <td ' . $productContentsDetailStyle . ' align=left colspan=3>Product Tax</td>
            <td ' . $productContentsDetailStyle . ' bgcolor="#e1e1e1" align=right valign=bottom><nobr>
                $ ' . $_SESSION['tax'] . '</nobr>
            </td>
        </tr>
        <tr>
            <td ' . $productContentsDetailStyle . ' align=left colspan=2>Shipping</td>
            <td ' . $productContentsDetailStyle . ' bgcolor="#e1e1e1" align=right valign=bottom><nobr>
                ' . $_SESSION['shippingMethod'] . '</td>
            <td ' . $productContentsDetailStyle . ' bgcolor="#e1e1e1" align=right valign=bottom><nobr>
                $ ' . $_SESSION['shipping'] . '</nobr>
            </td>
        </tr>
        <tr>
            <td ' . $productContentsDetailStyle . ' align=left colspan=3>Shipping Tax</td>
            <td ' . $productContentsDetailStyle . ' bgcolor="#e1e1e1" align=right valign=bottom><nobr>
                $ ' . $_SESSION['shippingTax'] . '</nobr>
            </td>
        </tr>


        ';

    
    
    $productSubTotal = sprintf ("%.2f", $productSubTotal);  // make sure there are only 2 decimals
    $total = sprintf("%.2f",$productSubTotal + $_SESSION['tax'] + $_SESSION['shipping'] + $_SESSION['shippingTax']); // discount is negative.
    
    
    
    
    

        
        $productContentsTable .= '
        <tr>
            <td ' . $productContentsTotalStyle . ' align=left colspan=3><b>Total</b></td>
            <td ' . $productContentsTotalStyle . '  bgcolor="#e1e1e1" align=right valign=bottom>
                    <input type=hidden name=productTotal value="' . $total . '"> 
                    <nobr><font size=+1><b>$ ' . $total . '</b></font></nobr>
            </td>
        </tr>



        </table></center>';


return $productContentsTable;





} 





function buildProductContentsTable(){
    /*$_SESSION['threeDollarDiscounts'] = array(
            'EE2013HLT', 
            'EE2013HN',
            'EE2013CHB',
            'EE2013HT',
            'EE2013VHL',
            'EE2013HST',
            'EE2013HT',
            'EE2013FL',
            'EE2013PC',
            'EE2013PH','
             EE2013PAE');    

    $_SESSION['fiveDollarDiscounts'] = array('EE2013SS');

    */
    
    
    //echo "<pre>";
    //print_r($_SESSION);
    //echo "</pre>";
    //exit;
    
    
    
    $_SESSION['productTotal'] = 0.00;
    
    
    $productSubTotal = 0;
    //$discount = 0;
    
    
    $productContentsHeaderStyle = 'style="font-family: Arial, Geneva, sans-serif;font-size: 11pt;font-weight: bold;color: #000000;background-color:#eeeeee;"';
    $productContentsDetailStyle = 'style="font-family: Arial, Geneva, sans-serif;font-size: 10pt;color: #000000;background-color:#ffffff;"';
    $productContentsTotalStyle = 'style="font-family: Arial, Geneva, sans-serif;font-size: 10pt;color: #000000;background-color:#eeeeee;"';
 
    
    $seasonPassNamesOutputStyle = 'style="font-family: Arial, Geneva, sans-serif;font-size: 12pt;font-weight:normal;color: #000000;background-color:#eeeeee;"';
    $seasonPassNamesHeaderStyle = 'style="font-family: Arial, Geneva, sans-serif;font-size: 10pt;font-weight:normal;color: #000000;background-color:#cccccc;"';



   
    
    
    $productContentsTable = '
        <center><table bgcolor=#FFFFFF width=98% style="border: 1px solid black">
                <tr valign=bottom bgcolor=#dddddd>
                    <td ' . $productContentsHeaderStyle . ' width=50% align=left>Selected Products</td>
                    <td ' . $productContentsHeaderStyle . 'align=center valign=bottom>Unit Price</td>
                    <td ' . $productContentsHeaderStyle . ' align=center valign=bottom>Qty</td>
                    <td ' . $productContentsHeaderStyle . ' bgcolor="#e1e1e1" align=right valign=bottom>Total Cost</td>
                </tr>
      ';
    
    // $_SESSION['products'] contains all the products and a quantity of each.
    // the ->qty contains the quantity. We only want a product to appear if the qty > 0.
    foreach($_SESSION['products'] as $oneProduct){
        if ($oneProduct->qty > 0){
            $productSubTotal += $oneProduct->total;
            $productContentsTable .= '
            <tr>
                <td ' . $productContentsDetailStyle . ' align=left>' . $oneProduct->description . '</td>
                <td ' . $productContentsDetailStyle . ' align=center valign=bottom><nobr>$' . $oneProduct->price . '</nobr></td>
                <td ' . $productContentsDetailStyle . ' align=center valign=bottom>
                ' . $oneProduct->qty . '
                </td>
                <td ' . $productContentsDetailStyle . ' bgcolor="#e1e1e1" align=right valign=bottom>
                $' . $oneProduct->total . '
                </td>
            </tr>
            ';
            
            
            
            
            
            
            /*if ($_SESSION['event']->code == '2013EE'){
                if (strstr($oneProduct->code,"ADULT")){
                    foreach($_SESSION['threeDollarDiscounts'] as $oneThreeDollarDiscount){
                        if (trim(strtolower($oneThreeDollarDiscount)) == trim(strtolower($_SESSION['promoCode']))){
                            $discount += (3.00 * $oneProduct->qty);
                        }
                    }

                    foreach($_SESSION['fiveDollarDiscounts'] as $oneFiveDollarDiscount){
                        if (trim(strtolower($oneFiveDollarDiscount)) == trim(strtolower($_SESSION['promoCode']))){
                            $discount += (5.00 * $oneProduct->qty);
                        }
                    }
                }
            }   */ 
                

            
            
            
            
             
            // handle promo. 5.00 * qty. with a maximum of 30.00.
            if ($oneProduct->code == 'SEAS' || $oneProduct->code == 'ADVSEA'){
                //if ($_SESSION['promoCode'] == 'blueribbon158'){
                //    $discount += (5.00 * $oneProduct->qty);
                //    if ($discount > 30.00){
                //        $discount = 30.00;
                //    }
               // }
                if ($oneProduct->qty > 0){
                    // loop through all passes and show the names.
                    
                   
                    $productContentsTable .= '
                        <tr>
                            <td colspan=4>
                                <center>
                                <table cellspacing=0>
                                    <tr>
                                        <td colspan=4 ' . $seasonPassNamesHeaderStyle . '>
                                            <center>
                                                <font size=-1>
                                                    <b>SEASON PASS NAMES</b><br>
                                                    (A picture ID will be required for admission of each season pass.)
                                                 </font>
                                            </center>
                                        </td>
                                    </tr>
                                ';
                            
                    for($i = 0;$i < $oneProduct->qty;$i++){
                            
                            $productContentsTable .='
                                    <tr>
                                        <td colspan=4 ' . $seasonPassNamesOutputStyle . '>
                                            <center>' . $_SESSION['seasonPassNames'][$i]['first'] . ' ' . $_SESSION['seasonPassNames'][$i]['last'] . '</center>
                                        </td>
                                    </tr>';
                    }
                    
                    $productContentsTable .='
                                </table>
                                <br>
                                </center>
                            </td>
                        </tr>
                     ';
                }
            }
            
            
            
            
        }
    }
    
    $productSubTotal = sprintf ("%.2f", $productSubTotal);  // make sure there are only 2 decimals
    $_SESSION['discount'] = sprintf ("%.2f", $_SESSION['discount']);
    $total = sprintf("%.2f",$productSubTotal + $_SESSION['discount']); // discount is negative.
    $_SESSION['productTotal'] = $total;
    
    //exit('discount:' . $_SESSION['discount']);
    
    $productContentsTable .= '
        <tr>
            <td ' . $productContentsTotalStyle . ' align=left colspan=3><b>Product Sub Total...</b></td>
            <td ' . $productContentsTotalStyle . ' bgcolor="#e1e1e1" align=right valign=bottom><nobr>
                $ ' . $productSubTotal . '</nobr>
            </td>
        </tr>
        ';


        if ($_SESSION['discount'] < 0){
            $productContentsTable .= '   
                <tr>
                    <td ' . $productContentsTotalStyle . ' align=left colspan=3>Discounts...</td>
                    <td ' . $productContentsTotalStyle . ' bgcolor="#e1e1e1" align=right valign=bottom>
                        <font color=red><nobr>
                            $ ' . $_SESSION['discount'] . '</nobr>
                        </font>
                    </td>
                </tr>
            ';
        }   
        
        
        $productContentsTable .= '
        <tr>
            <td ' . $productContentsTotalStyle . ' align=left colspan=3><font size=-1>' . $_SESSION['salesTaxMessage'] . '</font></td>
            <td ' . $productContentsTotalStyle . ' bgcolor="#e1e1e1" align=right valign=bottom>
                    <font size=-1><nobr>$ 0.00</nobr></font>
            </td>
        </tr>

        <tr>
            <td ' . $productContentsTotalStyle . ' align=left colspan=3><b>Total</b></td>
            <td ' . $productContentsTotalStyle . '  bgcolor="#e1e1e1" align=right valign=bottom>
                    <input type=hidden name=productTotal value="' . $total . '"> 
                    <nobr><font size=+1><b>$ ' . $total . '</b></font></nobr>
            </td>
        </tr>



        </table></center>';


return $productContentsTable;





} 

function countNotSeasonPassOrderQty($products){
    
    $notSeasonPassOrderQty = 0;
    foreach($products as $oneProduct){
        if ($oneProduct->qty > 0){
            // handle promo. 5.00 * qty. with a maximum of 30.00.
            if ($oneProduct->code != 'SEAS' && $oneProduct->code != 'ADVSEA'){
                $notSeasonPassOrderQty = $oneProduct->qty;
            }
        }
    }
    return $notSeasonPassOrderQty;
    
}

function countSeasonPassOrderQty(){
    
    $seasonPassOrderQty = 0;
    foreach($_SESSION['products'] as $oneProduct){
        
        if ($oneProduct->qty > 0){
            // handle promo. 5.00 * qty. with a maximum of 30.00.
            if ($oneProduct->code == 'SEAS' || $oneProduct->code == 'ADVSEA'){
                $seasonPassOrderQty = $oneProduct->qty;
            }
        }
    }
    return $seasonPassOrderQty;
    
}



function countAdultOrderQty(){
    
    $adultOrderQty = 0;
    foreach($_SESSION['products'] as $oneProduct){
        if ($oneProduct->qty > 0){
            // handle promo. 5.00 * qty. with a maximum of 30.00.
        if (strstr($oneProduct->code,'PAHAAA')){
                $adultOrderQty = $oneProduct->qty;
            }
        }
    }
    return $adultOrderQty;
    
}


function countChildOrderQty(){
    
    $childOrderQty = 0;
    foreach($_SESSION['products'] as $oneProduct){
        if ($oneProduct->qty > 0){
            // handle promo. 5.00 * qty. with a maximum of 30.00.
            if (strstr($oneProduct->code,'PAHACS')){
                $childOrderQty = $oneProduct->qty;
            }
        }
    }
    return $childOrderQty;
    
}



function buildProductContentsTableForAdmin($productTotal){
    // calculates the discount based on $row['SUBTOTAL']
    
    
    $productSubTotal = 0;
    $discount = 0;
    
    
    $productContentsHeaderStyle = 'style="font-family: Arial, Geneva, sans-serif;font-size: 11pt;font-weight: bold;color: #000000;background-color:#eeeeee;"';
    $productContentsDetailStyle = 'style="font-family: Arial, Geneva, sans-serif;font-size: 10pt;color: #000000;background-color:#ffffff;"';
    $productContentsTotalStyle = 'style="font-family: Arial, Geneva, sans-serif;font-size: 10pt;color: #000000;background-color:#eeeeee;"';
 
    
    $seasonPassNamesOutputStyle = 'style="font-family: Arial, Geneva, sans-serif;font-size: 12pt;font-weight:normal;color: #000000;background-color:#eeeeee;"';
    $seasonPassNamesHeaderStyle = 'style="font-family: Arial, Geneva, sans-serif;font-size: 10pt;font-weight:normal;color: #000000;background-color:#cccccc;"';



   
    
    
    $productContentsTable = '
        <center><table bgcolor=#FFFFFF width=98% style="border: 1px solid black">
                <tr valign=bottom bgcolor=#dddddd>
                    <td ' . $productContentsHeaderStyle . ' width=50% align=left>Selected Products</td>
                    <td ' . $productContentsHeaderStyle . 'align=center valign=bottom>Unit Price</td>
                    <td ' . $productContentsHeaderStyle . ' align=center valign=bottom>Qty</td>
                    <td ' . $productContentsHeaderStyle . ' bgcolor="#e1e1e1" align=right valign=bottom>Total Cost</td>
                </tr>
      ';
    
    // $_SESSION['products'] contains all the products and a quantity of each.
    // the ->qty contains the quantity. We only want a product to appear if the qty > 0.
    foreach($_SESSION['products'] as $oneProduct){
        if ($oneProduct->qty > 0){
            $productSubTotal += $oneProduct->total;
            $productContentsTable .= '
            <tr>
                <td ' . $productContentsDetailStyle . ' align=left>' . $oneProduct->description . '</td>
                <td ' . $productContentsDetailStyle . ' align=center valign=bottom>$' . $oneProduct->price . '</td>
                <td ' . $productContentsDetailStyle . ' align=center valign=bottom>
                ' . $oneProduct->qty . '
                </td>
                <td ' . $productContentsDetailStyle . ' bgcolor="#e1e1e1" align=right valign=bottom>
                $' . $oneProduct->total . '
                </td>
            </tr>
            ';
            
            
            
            
            if ($_SESSION['event']->code == '2013EE'){
                if (strstr($oneProduct->code,"ADULT")){
                    foreach($_SESSION['threeDollarDiscounts'] as $oneThreeDollarDiscount){
                        if (trim(strtolower($oneThreeDollarDiscount)) == trim(strtolower($_SESSION['promoCode']))){
                            $discount += (3.00 * $oneProduct->qty);
                        }
                    }

                    foreach($_SESSION['fiveDollarDiscounts'] as $oneFiveDollarDiscount){
                        if (trim(strtolower($oneFiveDollarDiscount)) == trim(strtolower($_SESSION['promoCode']))){
                            $discount += (5.00 * $oneProduct->qty);
                        }
                    }
                }
            }    
            
            
            // handle promo. 5.00 * qty. with a maximum of 30.00.
            if ($oneProduct->code == 'SEAS' || $oneProduct->code == 'ADVSEA'){
                if ($oneProduct->qty > 0){
                    // loop through all passes and show the names.
                    
                   
                    $productContentsTable .= '
                        <tr>
                            <td colspan=4>
                                <center>
                                <table cellspacing=0>
                                    <tr>
                                        <td colspan=4 ' . $seasonPassNamesHeaderStyle . '>
                                            <center>
                                                <font size=-1>
                                                    <b>SEASON PASS NAMES</b><br>
                                                    (A picture ID will be required for admission of each season pass.)
                                                 </font>
                                            </center>
                                        </td>
                                    </tr>
                                ';
                            
                    for($i = 0;$i < $oneProduct->qty;$i++){
                            
                            $productContentsTable .='
                                    <tr>
                                        <td colspan=4 ' . $seasonPassNamesOutputStyle . '>
                                            <center>' . $_SESSION['seasonPassNames'][$i]['first'] . ' ' . $_SESSION['seasonPassNames'][$i]['last'] . '</center>
                                        </td>
                                    </tr>';
                    }
                    
                    $productContentsTable .='
                                </table>
                                <br>
                                </center>
                            </td>
                        </tr>
                     ';
                }
            }
        }
    }
    
    $productSubTotal = sprintf ("%.2f", $productSubTotal);  // make sure there are only 2 decimals
    $discount = sprintf ("%.2f", $productSubTotal - $productTotal);
    $productTotal = sprintf("%.2f",$productTotal);
    
    $productContentsTable .= '
        <tr>
            <td ' . $productContentsTotalStyle . ' align=left colspan=3><b>Product Sub Total...</b></td>
            <td ' . $productContentsTotalStyle . ' bgcolor="#e1e1e1" align=right valign=bottom>
                $ ' . $productSubTotal . '
            </td>
        </tr>
        ';


        if ($discount > 0){
            $productContentsTable .= '   
                <tr>
                    <td ' . $productContentsTotalStyle . ' align=left colspan=3>Discounts...</td>
                    <td ' . $productContentsTotalStyle . ' bgcolor="#e1e1e1" align=right valign=bottom>
                        <font color=red>
                            - $ ' . $discount . '
                        </font>
                    </td>
                </tr>
            ';
        }   
        
        
        $productContentsTable .= '
        <tr>
            <td ' . $productContentsTotalStyle . ' align=left colspan=3><font size=-1>' . $_SESSION['salesTaxMessage'] . '</font></td>
            <td ' . $productContentsTotalStyle . ' bgcolor="#e1e1e1" align=right valign=bottom>
                    <font size=-1>$ 0.00</font>
            </td>
        </tr>

        <tr>
            <td ' . $productContentsTotalStyle . ' align=left colspan=3><b>Total</b></td>
            <td ' . $productContentsTotalStyle . ' bgcolor="#e1e1e1" align=right valign=bottom>
                    <input type=hidden name=productTotal value="' . $productTotal . '"> 
                    <font size=+1><b>$ ' . $productTotal . '</b></font>
            </td>
        </tr>



        </table></center>';


return $productContentsTable;





} 



function buildProductTableForAdminSetup($acct, $eventCode){
    
    
    $_SESSION['product'] = new Product();
    $_SESSION['products'] = $_SESSION['product']->retrieveListFromDb($acct, $eventCode);
    
    
    $productContentsHeaderStyle = 'style="font-family: Arial, Geneva, sans-serif;font-size: 11pt;font-weight: bold;color: #000000;background-color:#eeeeee;"';
    $productContentsDetailStyle = 'style="font-family: Arial, Geneva, sans-serif;font-size: 10pt;color: #000000;background-color:#ffffff;"';
    $productContentsTotalStyle = 'style="font-family: Arial, Geneva, sans-serif;font-size: 10pt;color: #000000;background-color:#eeeeee;"';
 
    
    $seasonPassNamesOutputStyle = 'style="font-family: Arial, Geneva, sans-serif;font-size: 12pt;font-weight:normal;color: #000000;background-color:#eeeeee;"';
    $seasonPassNamesHeaderStyle = 'style="font-family: Arial, Geneva, sans-serif;font-size: 10pt;font-weight:normal;color: #000000;background-color:#cccccc;"';

    
    $productContentsTable = '
        <center><table bgcolor=#FFFFFF width=98% style="border: 1px solid black">
                <tr valign=bottom bgcolor=#dddddd>
                    <td width=200 ' . $productContentsHeaderStyle . ' align=left>Small Image</td>
                    <td width=50% ' . $productContentsHeaderStyle . ' align=left>Selected Products</td>
                    <td width=20% ' . $productContentsHeaderStyle . ' align=left>Start Date</td>
                    <td width=20% ' . $productContentsHeaderStyle . ' align=left>End Date</td>    
                    <td width=200 ' . $productContentsHeaderStyle . 'align=center valign=bottom>Unit Price</td>
                </tr>
      ';
    
    // $_SESSION['products'] contains all the products and a quantity of each.
    // the ->qty contains the quantity. We only want a product to appear if the qty > 0.
    foreach($_SESSION['products'] as $oneProduct){
            $img1 = '(no image)';
            if ($oneProduct->img1 != null){
                $img1 = '<img src="data:image/png;base64,' . base64_encode($oneProduct->img1) . '"/>';
            } 
            
            $dateAvailableStart = strtotime($oneProduct->dateAvailableStart) + 3600;
            $dateAvailableStart = date('Y-m-d h:i:s',$dateAvailableStart);
            $dateAvailableEnd = strtotime($oneProduct->dateAvailableEnd) + 3600;
            $dateAvailableEnd = date('Y-m-d h:i:s',$dateAvailableEnd);
            $productContentsTable .= '
            <tr>
                <td valign=top ' . $productContentsDetailStyle . ' align=left>
                 <form action="?task=uploadImg1&eventCode=' . $eventCode . '&productCode=' . $oneProduct->code . '" method="post" enctype="multipart/form-data">
                    ' . $img1 . '
                    <input type="file" name="file" id="file" onchange="this.form.submit()">
                 </form>
                </td>
                <td ' . $productContentsDetailStyle . ' align=left valign=top>' . $oneProduct->description . '</td>
                <td ' . $productContentsDetailStyle . ' align=left valign=top>' . substr($dateAvailableStart,0,10) . '<br>' . date('h:i:s a T',strtotime($oneProduct->dateAvailableStart) + 3600) . '</td>
                <td ' . $productContentsDetailStyle . ' align=left valign=top>' . substr($dateAvailableEnd,0,10) . '<br>' . date('h:i:s a T',strtotime($oneProduct->dateAvailableEnd) + 3600) . '</td>   
                <td ' . $productContentsDetailStyle . ' align=center valign=top>$' . $oneProduct->price . '</td>
                
            </tr>
            ';
            
            
        
    }
    
    


        $productContentsTable .= '</table></center>';


    return $productContentsTable;





} 
function uploadImg1($acct, $eventCode, $productCode, $blobData){
     
    
     //echo '<img src="data:image/png;base64,' . base64_encode($blobData) . '"/>';
     //echo 'acct: ' . $acct . '<br>';
     //echo 'eventCode: ' . $eventCode . '<br>';
     //echo 'productCode: ' . $productCode . '<br>';
     //exit;
     
     

     $mysqli = new mysqli('localhost', $_SESSION['data']['user'], $_SESSION['data']['pass'], $_SESSION['data']['db']);
     if (mysqli_connect_errno()) {printf("Connect failed: %s\n", mysqli_connect_error());exit();}
     $sql = "UPDATE products SET
                img1=?
                WHERE acct = ? AND event_code = ? AND code = ?;";
     $stmt = $mysqli->prepare($sql) or exit('Failed prepare: ' . $sql . '<br/>' . $mysqli->error) ;
     
     $stmt->bind_param(
     'bsss',
     $blobData,
     $acct,
     $eventCode,
     $productCode
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




function findProductFromCode($code, $code2){
        foreach($_SESSION['products'] as $oneProduct){
            if ($oneProduct->code == $code || $oneProduct->code == $code2){
                return $oneProduct;
            }
        }
        
        return false;
}



    // old school...
    function buildProductHash(){
        /*$prodNumbers = array();
        foreach( $_POST as $pkey_request => $pval_request){
            if (substr($pkey_request,0,9) == 'prodTotal'){
                if ($pval_request > 0){
                    array_push($prodNumbers, substr($pkey_request,9,2));
                }
            }
            if (substr($pkey_request,0,4) == 'prod'){
                $productHash .= $pkey_request . '=' . $pval_request . '&';
            }
        }
        $productHash = substr($productHash,0,-1); // get rid of the last &.
        // needs to be key=value...*/



    
        $productHash = '';
        foreach($_SESSION['products'] as $oneProduct){
            if ($oneProduct->qty > 0){
                foreach($oneProduct as $key => $val){
                        if ($key != 'id' && $key != 'acct' && $key != 'eventCode' && $key!= 'img1'){
                            $productHash .= $key . '=' . $val . '&';
                        }
                        
                }
                $productHash = substr($productHash,0,-1); // get rid of last & 
                $productHash .= "\n";
            }




        }
        if (!empty($productHash)){
            $_SESSION['productHash'] = substr($productHash,0,-1); // get rid of the last \n.
        }
    
    }


    function buildSeasonPassNameHash(){


        $_SESSION['seasonPassNameHash'] = '';
        $oneProduct = $this->findProductFromCode('SEAS', 'ADVSEA');
        $_SESSION['seasonPassQty'] = $oneProduct->qty;
        if (!empty($_SESSION['seasonPassNames'])){
            for($i = 0;$i < $oneProduct->qty;$i++){
                $_SESSION['seasonPassNameHash'] .= 'first=' . $_SESSION['seasonPassNames'][$i]['first'] . '&last=' . $_SESSION['seasonPassNames'][$i]['last'] . "\n";
            }
        }
        $_SESSION['seasonPassNameHash'] = trim($_SESSION['seasonPassNameHash']); // get rid of the last \n.
        //exit('seasonPassNameHash: ' . "\n" . $_SESSION['seasonPassNameHash']);






    }


    function convertHashToObject($productHash){
        $prodRows = explode("\n",$productHash);
        $products = array();
        foreach ($prodRows as $thisProdRow){
              $productKeysAndValues = explode("&",$thisProdRow);
              $oneProduct = new Product();
              foreach ($productKeysAndValues as $thisProductKeyAndValue){
                        $productWords = explode("=",$thisProductKeyAndValue);
                        $oneProduct->$productWords[0] = $productWords[1];
              }
              array_push($products,$oneProduct);
         }

         return $products;
         
    }
    


    function calculateDiscount(){
        $_SESSION['discount'] = 0.00;
        $_SESSION['promoCode'] = '';
        $_SESSION['promptForMemberNumber'] = false;
        $promo = 0;
        $promoCode = '';  
        
//        if (empty($_REQUEST['prodCode_6'])) {
//            $_REQUEST['prodQty06'] = 0;
//        }

        $threeDollarDiscountOnOff = 0;
        $fiveDollarDiscountOnOff = 0;
        if (!empty($_REQUEST['promoCode'])){
            $_SESSION['promoCode'] = $_REQUEST['promoCode'];


            // first we need to confirm that it's a promo code that's part of this event.
            // look it up in $_SESSION['promos']
            $found = false;
            $promo = new Promo();
            foreach ($_SESSION['promos'] as $onePromo){
                if (strtolower($_REQUEST['promoCode']) == strtolower($onePromo->promoCode)){
                    $promo = $onePromo;
                    $found = true;
                    break;
                }
            }


            //echo "<pre>";
            //print_r($_SESSION['promos']);
            //echo "</pre>";
            //echo "<pre>promoCode: " . $_REQUEST['promoCode'] . "</pre>";
            //exit;


            if ($found){




                
                
                
                //csn 2/4/15
                //Check to see how many are used for each promocode. And check to see if it is less than
                //the total_available in the promos table.  If so, then do the discount checks.
                
                $countPromoCode = 0;

                $sql = "SELECT COUNT(*) FROM assigned WHERE acct = ? and event_code = ? and promo_code = ?";
                $mysqli = new mysqli('localhost', $_SESSION['data']['user'], $_SESSION['data']['pass'], $_SESSION['data']['db']);
                if (mysqli_connect_errno()) {printf("Connect failed: %s\n", mysqli_connect_error());exit();}
                $stmt = $mysqli->prepare($sql) or exit('Failed prepare: ' . $sql . '<br/>' . $mysqli->error) ;
                $stmt->bind_param('sss',$_SESSION['account']->acct, $_SESSION['event']->code, strtolower($_REQUEST['promoCode']));
                if (!$stmt->execute()){echo 'Invalid query: ' . $sql . '<br/><br/>';printf("Errormessage: %s\n", $mysqli->error);}
                $stmt->bind_result($countPromoCode);
                while ($stmt->fetch()) {

                }
                $stmt->close();
                $mysqli->close();
                //exit('$countPromoCode: ' . $countPromoCode);


                $totalAvailable = 0;

                $sql = "SELECT total_available FROM promos WHERE acct = ? and event_code = ? and promo_code = ?";
                $mysqli = new mysqli('localhost', $_SESSION['data']['user'], $_SESSION['data']['pass'], $_SESSION['data']['db']);
                if (mysqli_connect_errno()) {printf("Connect failed: %s\n", mysqli_connect_error());exit();}
                $stmt = $mysqli->prepare($sql) or exit('Failed prepare: ' . $sql . '<br/>' . $mysqli->error) ;
                $stmt->bind_param('sss',$_SESSION['account']->acct, $_SESSION['event']->code, strtolower($_REQUEST['promoCode']));
                if (!$stmt->execute()){echo 'Invalid query: ' . $sql . '<br/><br/>';printf("Errormessage: %s\n", $mysqli->error);}
                $stmt->bind_result($totalAvailable);
                while ($stmt->fetch()) {

                }
                $stmt->close();
                $mysqli->close();
                //exit('$totalAvailable: ' . $totalAvailable);




                //csn Do the discount checks if promo_code count is less than total_available
                //or if total_available is null
                if ($countPromoCode < $totalAvailable || $totalAvailable == null){
                
                
                
                    // fbmember promo code was onedollar1 until about 3 days before the fair.
                    // for fair. $1 off max. of 2 adult and 2 children. membership number required.
                    if (strtolower($promo->type) == 'onedollar1'){
                        $_SESSION['promptForMemberNumber'] = true;
                        if (strlen($_REQUEST['memberNumber']) == 6){
                            //exit('here.');
                            $product = new Product();
                            $adultOrderQty = $product->countAdultOrderQty();
                            $childOrderQty = $product->countChildOrderQty();


                            $totalDiscount = (1.00 * ($adultOrderQty+$childOrderQty));
                            if ($totalDiscount > 6.00){
                                $totalDiscount = 6.00;
                            }

                            //$_SESSION['discount'] = $adultDiscount + $childDiscount;
                            $_SESSION['discount'] = $totalDiscount;
                        } else {
                            $_REQUEST['task'] = 'step1'; // force the membership number entry to continue.
                        }
                    }



                    if (strtolower($promo->type) == 'twodollar1'){
                        $_SESSION['promptForMemberNumber'] = true;
                        if (strlen($_REQUEST['memberNumber']) == 6){
                            //exit('here.');
                            //$product = new Product();
                            //$carWeOrderQty = $product->countCarWeOrderQty();
                            //$carWdOrderQty = $product->countCarWdOrderQty();

                            //echo "<pre>";
                            //print_r($_REQUEST);
                            //echo "</pre>";
                            //exit;


                            $carWeOrderQty = 0;
                            $carWdOrderQty = 0;
                            $adultAdvOrderQty = 0;
                            $childAdvOrderQty = 0;
                            $adultOrderQty = 0;
                            $childOrderQty = 0;
                            $childweaOrderQty = 0;
                            $childwdaOrderQty = 0;
                            $seasonPassOrderQty = 0;
                            
                            if ($_REQUEST['prodCode_0'] == 'CARWE' || $_REQUEST['prodCode_0'] == 'ADULTWEA'){
                                $carWeOrderQty = $_REQUEST['prodQty_0'];
                            } 
                            if ($_REQUEST['prodCode_1'] == 'CARWD' || $_REQUEST['prodCode_1'] == 'ADULTWDA'){
                                $carWdOrderQty = $_REQUEST['prodQty_1'];
                            } 
                            if ($_REQUEST['prodCode_0'] == 'ADULTADV'){    
                                $adultAdvOrderQty = $_REQUEST['prodQty_0'];
                            } 
                            if ($_REQUEST['prodCode_1'] == 'CHILDADV'){
                                $childAdvOrderQty = $_REQUEST['prodQty_1'];
                            } 
                            if ($_REQUEST['prodCode_0'] == 'ADULT'){    
                                $adultOrderQty = $_REQUEST['prodQty_0'];
                            }  
                            if ($_REQUEST['prodCode_1'] == 'CHILD'){
                                $childOrderQty = $_REQUEST['prodQty_1'];
                            } 
                            if ($_REQUEST['prodCode_2'] == 'CHILDWEA'){    
                                $childweaOrderQty = $_REQUEST['prodQty_2'];
                            }  
                            if ($_REQUEST['prodCode_3'] == 'CHILDWDA'){
                                $childwdaOrderQty = $_REQUEST['prodQty_3'];
                            } 


                            
                            
                            // look for SEAS in any of the products.
                            if (!empty($_REQUEST['prodCode_0'])){
                                if ($_REQUEST['prodCode_0'] == 'SEAS'){
                                    $seasonPassOrderQty = $_REQUEST['prodQty_0'];
                                } 
                            }
                            if (!empty($_REQUEST['prodCode_1'])){
                                if ($_REQUEST['prodCode_1'] == 'SEAS'){
                                    $seasonPassOrderQty = $_REQUEST['prodQty_1'];
                                } 
                            }
                            if (!empty($_REQUEST['prodCode_2'])){
                                if ($_REQUEST['prodCode_2'] == 'SEAS'){
                                    $seasonPassOrderQty = $_REQUEST['prodQty_2'];
                                } 
                            }
                            if (!empty($_REQUEST['prodCode_3'])){
                                if ($_REQUEST['prodCode_3'] == 'SEAS'){
                                    $seasonPassOrderQty = $_REQUEST['prodQty_3'];
                                } 
                            }
                            if (!empty($_REQUEST['prodCode_4'])){
                                if ($_REQUEST['prodCode_4'] == 'SEAS'){
                                    $seasonPassOrderQty = $_REQUEST['prodQty_4'];
                                } 
                            }
                            
                            
                            
                            //$totalDiscount = (2.00 * ($carWeOrderQty + $carWdOrderQty));
                            $totalDiscount = (2.00 * ($carWeOrderQty + $carWdOrderQty + $adultAdvOrderQty + $childAdvOrderQty + $seasonPassOrderQty + $adultOrderQty + $childOrderQty + $childweaOrderQty + $childwdaOrderQty));
                            //if ($totalDiscount > 6.00){
                            //    $totalDiscount = 6.00;
                           // }
                            //$_SESSION['discount'] = $adultDiscount + $childDiscount;
                            //exit('carWeOrderQty:' . $carWeOrderQty . '<br>carWdOrderQty:' . $carWdOrderQty);
                            $_SESSION['discount'] = $totalDiscount;
                        } else {
                            $_REQUEST['task'] = 'step1'; // force the membership number entry to continue.
                        }
                    }


                    
                    //Does not require membership number entry
                    if (strtolower($promo->type) == 'twodollar2'){
                        //$_SESSION['promptForMemberNumber'] = true;
                        //if (strlen($_REQUEST['memberNumber']) == 6){
                            //exit('here.');

                            $adultAdvOrderQty = 0;
                            $childAdvOrderQty = 0;
                            $adultOrderQty = 0;
                            $childOrderQty = 0;
                            $carOrderQty = 0;
                            if (!empty($_REQUEST['prodCode_0'])){   
                                if ($_REQUEST['prodCode_0'] == 'ADULTADV'){  
                                    $adultAdvOrderQty = $_REQUEST['prodQty_0'];
                                }
                            } 
                            if (!empty($_REQUEST['prodCode_1'])){
                                if ($_REQUEST['prodCode_1'] == 'CHILDADV'){
                                    $childAdvOrderQty = $_REQUEST['prodQty_1'];
                                }
                            } 
                            if (!empty($_REQUEST['prodCode_0'])){   
                                if ($_REQUEST['prodCode_0'] == 'ADULT'){  
                                    $adultOrderQty = $_REQUEST['prodQty_0'];
                                }
                            } 
                            if (!empty($_REQUEST['prodCode_1'])){
                                if ($_REQUEST['prodCode_1'] == 'CHILD'){
                                    $childOrderQty = $_REQUEST['prodQty_1'];
                                }
                            }
                            if (!empty($_REQUEST['prodCode_0'])) {
                                if ($_REQUEST['prodCode_0'] == 'CAR'){
                                    $carOrderQty = $_REQUEST['prodQty_0'];
                                }
                            }


                            $totalDiscount = (2.00 * ($adultAdvOrderQty + $childAdvOrderQty + $adultOrderQty + $childOrderQty + $carOrderQty));
                            $_SESSION['discount'] = $totalDiscount;
                        //} else {
                        //    $_REQUEST['task'] = 'step1'; // force the membership number entry to continue.
                       // }
                    }
                    
                    
                    
                    //Does not require membership number entry
                    if (strtolower($promo->type) == 'threedollar2'){
                        //$_SESSION['promptForMemberNumber'] = true;
                        //if (strlen($_REQUEST['memberNumber']) == 6){
                            //exit('here.');

//                            $adultAdvOrderQty = 0;
//                            if (!empty($_REQUEST['prodCode_0'])){
//                                if ($_REQUEST['prodCode_0'] == 'ADULT'){    
//                                    $adultAdvOrderQty = $_REQUEST['prodQty_0'];
//                                } 
//                            }
                        
                        
                            $adultWeOrderQty = 0;
                            $adultWdOrderQty = 0;
                            $childWeOrderQty = 0;
                            $childWdOrderQty = 0;
                            $wristbandWeOrderQty = 0;
                            $wristbandWeOrderQty = 0;
                            if ($_REQUEST['prodCode_0'] == 'ADULTWE'){    
                                $adultWeOrderQty = $_REQUEST['prodQty_0'];
                            } 
                            if ($_REQUEST['prodCode_1'] == 'ADULTWD'){    
                                $adultWdOrderQty = $_REQUEST['prodQty_1'];
                            } 
                            if ($_REQUEST['prodCode_2'] == 'CHILDWE'){
                                $childWeOrderQty = $_REQUEST['prodQty_2'];
                            }
                            if ($_REQUEST['prodCode_3'] == 'CHILDWD'){
                                $childWdOrderQty = $_REQUEST['prodQty_3'];
                            }
                            if ($_REQUEST['prodCode_4'] == 'WBWE'){    
                                $wristbandWeOrderQty = $_REQUEST['prodQty_4'];
                            } 
                            if ($_REQUEST['prodCode_5'] == 'WBWD'){    
                                $wristbandWdOrderQty = $_REQUEST['prodQty_5'];
                            }


                            $totalDiscount = (3.00 * ($adultWeOrderQty + $adultWdOrderQty + $childWeOrderQty + $childWdOrderQty + $wristbandWeOrderQty + $wristbandWdOrderQty));
                            $_SESSION['discount'] = $totalDiscount;
                        //} else {
                        //    $_REQUEST['task'] = 'step1'; // force the membership number entry to continue.
                       // }
                    }
                    
                    
                    
                    if (strtolower($promo->type) == 'fivedollar1'){
                        $_SESSION['promptForMemberNumber'] = true;
                        if (strlen($_REQUEST['memberNumber']) == 6){
                            //exit('here.');

                            $seasonPassOrderQty = 0;
                            if ($_REQUEST['prodCode_4'] == 'SEAS'){
                                $seasonPassOrderQty = $_REQUEST['prodQty_4'];
                            } else if ($_REQUEST['prodCode_0'] == 'ADULT'){
                                $seasonPassOrderQty = $_REQUEST['prodQty_0'];
                            } 


                            $totalDiscount = (5.00 * ($seasonPassOrderQty));
                            $_SESSION['discount'] = $totalDiscount;
                        } else {
                            $_REQUEST['task'] = 'step1'; // force the membership number entry to continue.
                        }
                    }
                    
                    
                    
                    //Does not require membership number entry
                    if (strtolower($promo->type) == 'fivedollar2'){
                        //$_SESSION['promptForMemberNumber'] = true;
                        //if (strlen($_REQUEST['memberNumber']) == 6){
                            //exit('here.');

                            $seasonPassOrderQty = 0;
                            if ($_REQUEST['prodCode_2'] == 'SEAS'){
                                $seasonPassOrderQty = $_REQUEST['prodQty_2'];
                            } else if ($_REQUEST['prodCode_0'] == 'ADULTWEA'){
                                $seasonPassOrderQty = $_REQUEST['prodQty_0'];
//                            } else if ($_REQUEST['prodCode_6'] == 'SEAS'){
//                                $seasonPassOrderQty = $_REQUEST['prodQty_6'];
                            } 


                            $totalDiscount = (5.00 * ($seasonPassOrderQty));
                            $_SESSION['discount'] = $totalDiscount;
                        //} else {
                        //    $_REQUEST['task'] = 'step1'; // force the membership number entry to continue.
                       // }
                    }
                    
                    
                    //$12 off up to 4 tickets, ADULT WE or ADULT WD advance tickets valid 8/3/15-8/10/15
                    //Does not require membership number entry
                    if (strtolower($promo->type) == 'fourfree'){
                        //$_SESSION['promptForMemberNumber'] = true;
                        //if (strlen($_REQUEST['memberNumber']) == 6){
                            //exit('here.');

                            $adultweOrderQty = 0;
                            $adultwdOrderQty = 0;
                            if ($_REQUEST['prodCode_0'] == 'ADULTWEA'){
                                $adultweOrderQty = $_REQUEST['prodQty_0'];
                            } 
                            if ($_REQUEST['prodCode_1'] == 'ADULTWDA'){
                                $adultwdOrderQty = $_REQUEST['prodQty_1'];
                            } 

                            if ($adultweOrderQty > 4) {
                                $adultweOrderQty = 4;
                                $adultwdOrderQty = 0;
                            } else if ($adultwdOrderQty > 4) {
                                $adultwdOrderQty = 4;
                                $adultweOrderQty = 0;
                            } else if ($adultweOrderQty + $adultwdOrderQty > 4) {
                                if ($adultweOrderQty == 1) {
                                    $adultweOrderQty = 1;
                                    $adultwdOrderQty = 3;
                                } else if ($adultweOrderQty == 2) {
                                    $adultweOrderQty = 2;
                                    $adultwdOrderQty = 2;
                                } else if ($adultweOrderQty == 3) {
                                    $adultweOrderQty = 3;
                                    $adultwdOrderQty = 1;
                                } else if ($adultweOrderQty == 4) {
                                    $adultweOrderQty = 4;
                                    $adultwdOrderQty = 0;    
                                }
                            }
                            
                            
                            $totalDiscount = ((12.00 * $adultweOrderQty) + (9.00 * $adultwdOrderQty));
                            $_SESSION['discount'] = $totalDiscount;
                        //} else {
                        //    $_REQUEST['task'] = 'step1'; // force the membership number entry to continue.
                       // }
                    }

                    
                    
                    //Buy one get one free, ADULT WE or ADULT WD advance tickets valid 9/1/15-9/24/15
                    //Only one free ticket per transaction
                    //Does not require membership number entry
                    if (strtolower($promo->type) == 'bogofree'){
                        //$_SESSION['promptForMemberNumber'] = true;
                        //if (strlen($_REQUEST['memberNumber']) == 6){
                            //exit('here.');

                            $adultweOrderQty = 0;
                            $adultwdOrderQty = 0;
                            if ($_REQUEST['prodCode_0'] == 'ADULTWEA'){
                                $adultweOrderQty = $_REQUEST['prodQty_0'];
                            } 
                            if ($_REQUEST['prodCode_1'] == 'ADULTWDA'){
                                $adultwdOrderQty = $_REQUEST['prodQty_1'];
                            } 

                            
                            if ($adultweOrderQty >= 2) {
                                $adultweOrderQty = 1;
                                $adultwdOrderQty = 0;
                            } else if ($adultwdOrderQty >= 2) {
                                $adultwdOrderQty = 1;
                                $adultweOrderQty = 0;
                            } else if ($adultweOrderQty > 0 && $adultwdOrderQty > 0) {
                                $adultweOrderQty = 0;
                                $adultwdOrderQty = 1;
                            } else if ($adultweOrderQty < 2) {
                                $adultwdOrderQty = 0;
                                $adultweOrderQty = 0;
                            } else if ($adultwdOrderQty < 2) {
                                $adultwdOrderQty = 0;
                                $adultweOrderQty = 0;
                            }
                            
                            
                            $totalDiscount = ((12.00 * $adultweOrderQty) + (9.00 * $adultwdOrderQty));
                            $_SESSION['discount'] = $totalDiscount;
                        //} else {
                        //    $_REQUEST['task'] = 'step1'; // force the membership number entry to continue.
                       // }
                    }



                    //We also then need the fbmember promo code to prompt the 
                    // 6 character member number and they need to get 
                    // $4 off the $15 - ADULTWE
                    // $3 off the $10 - CHILDWE
                    // $1 off the $12 - ADULTWD
                    // $1 OFF THE $8 - CHILDWD
                    if (strtolower($promo->type) == 'duringfair'){








                        $_SESSION['promptForMemberNumber'] = true;
                        if (strlen($_REQUEST['memberNumber']) == 6){


                                    $adultWeOrderQty = 0;
                                    foreach($_SESSION['products'] as $oneProduct){
                                        if ($oneProduct->qty > 0){
                                            if (strstr($oneProduct->code,'ADULTWE')){
                                                $adultWeOrderQty = $oneProduct->qty;
                                            }
                                        }
                                    }

                                    $childWeOrderQty = 0;
                                    foreach($_SESSION['products'] as $oneProduct){
                                        if ($oneProduct->qty > 0){
                                           if (strstr($oneProduct->code,'CHILDWE')){
                                                $childWeOrderQty = $oneProduct->qty;
                                            }
                                        }
                                    }


                                    $adultWdOrderQty = 0;
                                    foreach($_SESSION['products'] as $oneProduct){
                                        if ($oneProduct->qty > 0){
                                           if (strstr($oneProduct->code,'ADULTWD')){
                                                $adultWdOrderQty = $oneProduct->qty;
                                            }
                                        }
                                    }

                                    $childWdOrderQty = 0;
                                    foreach($_SESSION['products'] as $oneProduct){
                                        if ($oneProduct->qty > 0){
                                           if (strstr($oneProduct->code,'CHILDWD')){
                                                $childWdOrderQty = $oneProduct->qty;
                                            }
                                        }
                                    }



                            //exit('here.');
                            $product = new Product();

                            $totalDiscount = (4.00 * ($adultWeOrderQty));
                            $totalDiscount = $totalDiscount + (3.00 * ($childWeOrderQty));
                            $totalDiscount = $totalDiscount + (1.00 * ($adultWdOrderQty));
                            $totalDiscount = $totalDiscount + (1.00 * ($childWdOrderQty));

                            if ($totalDiscount > 20.00){
                                $totalDiscount = 20.00;
                            }

                            //$_SESSION['discount'] = $adultDiscount + $childDiscount;
                            $_SESSION['discount'] = $totalDiscount;
                        } else {
                            $_REQUEST['task'] = 'step1'; // force the membership number entry to continue.
                        }
                    }



                    // season1 used for the fair. maximum of 6 per order. 5000 per event.
                    // is for a $5 discount.
                    if (strtolower($promo->type) == 'season1'){
                        // First, verify there are not already 5000 in the transactions table.
                        $seasonPasses = 0;
                        $sql = "SELECT SUM(season_pass_qty) FROM TRANSACTIONS WHERE CACCT = ? and event_code = ?";
                        $mysqli = new mysqli('localhost', $_SESSION['data']['user'], $_SESSION['data']['pass'], $_SESSION['data']['db']);
                        if (mysqli_connect_errno()) {printf("Connect failed: %s\n", mysqli_connect_error());exit();}
                        $stmt = $mysqli->prepare($sql) or exit('Failed prepare: ' . $sql . '<br/>' . $mysqli->error) ;
                        $stmt->bind_param('ss',$_SESSION['account']->acct, $_SESSION['event']->code);
                        if (!$stmt->execute()){echo 'Invalid query: ' . $sql . '<br/><br/>';printf("Errormessage: %s\n", $mysqli->error);}
                        $stmt->bind_result($seasonPasses);
                        while ($stmt->fetch()) {

                        }
                        $stmt->close();
                        $mysqli->close();
                        //exit('seasonPasses: ' . $seasonPasses);


                        if ($seasonPasses < 5000){
                            // multiply the number of season passes in this order by 5 for the discount.
                            // if the number of season passes in this order is 6 or more, just use 30 (max of 6 passes).


                            // need to know the number of season passes in this order....

                            $product = new Product();
                            $seasonPassOrderQty = $product->countSeasonPassOrderQty();
                            //exit('seasonPassOrderQty:' . $seasonPassOrderQty);

                            $_SESSION['discount'] += (5.00 * $seasonPassOrderQty);
                            //if ($_SESSION['discount'] > 30.00){
                            //    $_SESSION['discount'] = 30.00;
                            //}

                            //exit('discount:' . $_SESSION['discount']);


                        }
                    }
                
                

                }//csn
                
            }


        }    
        $_SESSION['discount'] = 0 - $_SESSION['discount'];
        $_SESSION['discount'] = sprintf ("%.2f", $_SESSION['discount']); 

    }
    
    

    
    
    
    
}







?>
