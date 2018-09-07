<?php


class Assigned
{
    // property declaration
    public $id = 0;
    public $acct = '';
    public $eventCode = '';
    public $conf = '';
    public $productCode = '';
    public $assigned = '';
    public $barcode = '';
    public $printed = '';
    public $promoCode = '';
    
    
    
    
    
    public function loadAllForAConf ($acct, $eventCode, $conf){
          $assigneds = array();  
        
          $sql = "SELECT * FROM assigned 
                    WHERE acct = ? AND event_code = ? and conf = ?";
          $mysqli = new mysqli('localhost', $_SESSION['data']['user'], $_SESSION['data']['pass'], $_SESSION['data']['db']);
          if (mysqli_connect_errno()) {printf("Connect failed: %s\n", mysqli_connect_error());exit();}
          if ($stmt = $mysqli->prepare($sql)){
              $stmt->bind_param('sss',$acct, $eventCode, $conf);
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

                    $assigned = new Assigned();
                    $assigned->id = $row['id'];
                    $assigned->acct = $row['acct'];
                    $assigned->eventCode = $row['event_code'];
                    $assigned->conf = $row['conf'];
                    $assigned->productCode = $row['product_code'];
                    $assigned->assigned = $row['assigned'];
                    $assigned->barcode = $row['barcode'];
                    $assigned->printed = $row['printed'];
                    $assigned->promoCode = $row['promo_code'];
                    
                    
                    
                    array_push($assigneds,$assigned);
                    
                    
                    //echo '<!-- products: ';
                    //print_r($products); 
                    //echo '-->';
                    
              }
              $stmt->close();
          } else {
              exit('Failed prepare: ' . $sql . '<br/>' . mysql_error()) ;
          }
          $mysqli->close();

        

         return $assigneds;

    }
    

    function buildAssignedTableForAdmin($assigneds){


        $headerStyle = 'style="font-family: Arial, Geneva, sans-serif;font-size: 11pt;font-weight: bold;color: #000000;background-color:#eeeeee;"';
        $detailStyle = 'style="font-family: Arial, Geneva, sans-serif;font-size: 10pt;color: #000000;background-color:#ffffff;"';
        


        $assignedTable = '
            <center><table bgcolor=#FFFFFF width=98% style="border: 1px solid black">
                    <tr valign=bottom bgcolor=#dddddd>
                        <td ' . $headerStyle . ' align=center>Assigned Products</td>
                        <td ' . $headerStyle . ' align=center valign=bottom>Assigned Number</td>
                        <td ' . $headerStyle . ' align=center valign=bottom>Barcode</td>
                    </tr>
          ';
        $counter = 0;
        foreach($assigneds as $oneAssigned){
                $assignedTable .= '
                <tr>
                    <td ' . $detailStyle . ' align=center>' . $oneAssigned->productCode . '</td>
                    <td ' . $detailStyle . ' align=center>' . $oneAssigned->assigned . '</td>
                    <td ' . $detailStyle . ' align=center>' . $oneAssigned->barcode . '</td>
                </tr>
                ';
                $counter++;
        }

        
        $assignedTable .= '</table></center>';

        if ($counter == 0){
            $assignedTable = '';
        }

        return $assignedTable;
    } 
}

?>
