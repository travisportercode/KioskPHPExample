<?php


class MemberAssociation
{
    // property declaration
    public $id = 0;
    public $acct = '';
    public $eventCode = '';
    public $assocation = '';
    public $status = 0;
    public $listingOrder = 0;
    
    
    
    
    public function retrieveActiveListFromDb ($acct, $eventCode){
        
        
          $memberAssociations = array();  
          
          $sql = "SELECT * FROM member_associations WHERE acct = ? AND event_code = ? AND status = 1 order by listing_order";
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

                    $memberAssociation = new Product();
                    $memberAssociation->id = $row['id'];
                    $memberAssociation->acct = $row['acct'];
                    $memberAssociation->eventCode = $row['event_code'];
                    $memberAssociation->association = $row['association'];
                    $memberAssociation->listingOrder = $row['listing_order'];
                    $memberAssociation->status = $row['status'];
                    
                    
                    array_push($memberAssociations,$memberAssociation);
                    
                    
                    
              }
              $stmt->close();
          } else {
              exit('Failed prepare: ' . $sql . '<br/>' . $mysqli->error) ;
          }
          $mysqli->close();

        

         return $memberAssociations;

    }
    
    
  
    function buildSelectBox($acct, $eventCode){
        $selectBox = '<select name="membershipAssociation" style="font-face: arial; align: center;">
            <option value="">Select a member association</option>' . "\n";
        
        $memberAssociations = $this->retrieveActiveListFromDb($acct, $eventCode);
        //print_r($memberAssociations);
        //exit('done');
        
        $counter = 0;
        foreach ($memberAssociations as $memberAssociation){
            if ($_REQUEST['membershipAssociation'] == $memberAssociation->association){
                $selectBox .= '<option value="' . $memberAssociation->association . '" SELECTED>' . $memberAssociation->association . '</option>' . "\n";
            } else {
                $selectBox .= '<option value="' . $memberAssociation->association . '">' . $memberAssociation->association . '</option>' . "\n";
            }
            
            
            $counter++;
        }
        
        
        if ($counter > 0){
            $selectBox .= '</select>';
        } else {
            $selectBox = '';
        }
        
        return $selectBox;
    }




}

?>
