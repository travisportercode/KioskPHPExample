<?php


class Organizer
{
    // property declaration
    public $id = 0;
    public $acct = '';
    public $organizerAcct = '';
    public $legalName = '';
    public $dba = '';
    public $federalId = '';
    public $sfirst = '';
    public $slast = '';
    public $sbus = '';
    public $sadd1 = '';
    public $sadd2 = '';
    public $scity = '';
    public $sst = '';
    public $szip = '';
    public $bfirst = '';
    public $blast = '';
    public $bbus = '';
    public $badd1 = '';
    public $badd2 = '';
    public $bcity = '';
    public $bst = '';
    public $bzip = '';
    public $contactName = '';
    public $contactPhone = '';
    public $contactFax = '';
    public $reseller = 0;
    public $taxExempt = 0;
    public $discount = 0.00;
    public $gatewayAVSLevel = 0.00;
    
    
    
    public function loadFromDb ($organizerAcct){
        
          $sql = "SELECT * FROM organizers WHERE acct = ? AND organizer_acct = ? LIMIT 1";
          $mysqli = new mysqli('localhost', $_SESSION['data']['user'], $_SESSION['data']['pass'], $_SESSION['data']['db']);
          if (mysqli_connect_errno()) {printf("Connect failed: %s\n", mysqli_connect_error());exit();
          }
          $recordCount = 0;
          if ($stmt = $mysqli->prepare($sql)){
              $stmt->bind_param('ss',$_SESSION['account']->acct, $organizerAcct);
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
                    $this->acct = $row['acct'];
                    $this->organizerAcct = $row['organizer_acct'];
                    $this->legalName = $row['legal_name'];
                    $this->dba = $row['dba'];
                    $this->federalId = $row['federal_id'];
                    $this->sfirst = $row['sfirst'];
                    $this->slast = $row['slast'];
                    $this->sbus = $row['sbus'];
                    $this->sadd1 = $row['sadd1'];
                    $this->sadd2 = $row['sadd2'];
                    $this->scity = $row['scity'];
                    $this->sst = $row['sst'];
                    $this->szip = $row['szip'];
                    $this->bfirst = $row['bfirst'];
                    $this->blast = $row['blast'];
                    $this->bbus = $row['bbus'];
                    $this->badd1 = $row['badd1'];
                    $this->badd2 = $row['badd2'];
                    $this->bcity = $row['bcity'];
                    $this->bst = $row['bst'];
                    $this->bzip = $row['bzip'];
                    $this->contactName = $row['contact_name'];
                    $this->contactPhone = $row['contact_phone'];
                    $this->contactFax = $row['contact_fax'];
                    $this->reseller = $row['reseller'];
                    $this->taxExempt = $row['tax_exempt'];
                    $this->discount = $row['discount'];
                    $this->gatewayAVSLevel = $row['gateway_AVS_level'];
                    
                    
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
