<?php


class Account
{
    // property declaration
    public $id = 0;
    public $acct = '';
    public $description = '';
    
    public $contact = '';
    public $email = '';
    public $telephone = '';
    
    public $useGateAccess = 0;
    public $usePrintAtHome = 0;
    
    public $useDirectFulfillment = 0;
    
    public $status = 0;
    public $img1 = '';
    public $img2 = '';
    
    public $emailFrom = '';
    public $emailFromName = '';
    public $emailReply = '';
    public $emailReplyName = '';
    
    public $emailsTestTo = '';
    public $emailsCc = '';
    public $emailsBcc = '';
    public $smtpHost = '';
    public $smtpAuthenticationRequired = '';
    public $smtpUser = '';
    public $smtpPass = '';
    public $emailTimeOut = '';
    public $customerTimeZone = '';
    
    public $publicContactTelephone = '';
    public $publicContactEmail = '';
    public $gatewayScript = '';
    public $gatewayLogin = '';
    public $gatewayTransactionKey = '';
    
    public $gatewayClientCode = '';
    public $gatewayUserName = '';
    public $gatewayPassword = '';   
    
    public $gatewayMerchantCode = '';
    public $gatewayLocationCode = '';
    public $gatewayTerminalCode = '';   
    public $gatewayAVSLevel = '';   
    
    
    
    public function loadFromDb ($acct){
        
        $sql = "SELECT * FROM accounts WHERE acct = ?";
        $mysqli = new mysqli('localhost', $_SESSION['data']['user'], $_SESSION['data']['pass'], $_SESSION['data']['db']);
        if (mysqli_connect_errno()) {printf("Connect failed: %s\n", mysqli_connect_error());exit();
        }
        if ($stmt = $mysqli->prepare($sql)){
            $stmt->bind_param('s',$acct);
            if (!$stmt->execute()){echo 'Invalid query: ' . $sql . '<br/><br/>';printf("Errormessage: %s\n", $mysqli->error);exit;}
            $stmt->store_result();

            $variables = array();
            $data = array();
            $meta = $stmt->result_metadata();

            while($field = $meta->fetch_field()){
                $variables[] = &$data[$field->name]; // pass by reference
            }   
            call_user_func_array(array($stmt, 'bind_result'), $variables);
            while($stmt->fetch()) {
                $row = array();
                foreach($data as $k=>$v)
                $row[$k] = $v;


                $this->id = $row['id'];
                $this->acct = $row['acct'];
                $this->description = $row['description'];
                $this->useGateAccess = $row['use_gate_access'];
                $this->usePrintAtHome = $row['use_print_at_home'];
                $this->useDirectFulfillment = $row['use_direct_fulfillment'];
                $this->status = $row['status'];
                $this->img1 = $row['img1'];
                $this->img2 = $row['img2'];
                $this->contact = $row['contact'];
                $this->email = $row['email'];
                $this->telephone = $row['telephone']; 


                $this->emailFrom = $row['email_from'];
                $this->emailFromName = $row['email_from_name'];
                $this->emailReply = $row['email_reply'];
                $this->emailReplyName = $row['email_reply_name'];

                $this->emailsTestTo = $row['emails_test_to'];
                $this->emailsCc = $row['emails_cc'];
                $this->emailsBcc = $row['emails_bcc'];
                $this->smtpHost = $row['smtp_host'];
                $this->smtpAuthenticationRequired = $row['smtp_host'];
                $this->smtpUser = $row['smtp_user'];
                $this->smtpPass = $row['smtp_pass'];
                $this->emailTimeOut = $row['email_time_out'];
                $this->customerTimeZone = $row['customer_time_zone'];

                $this->publicContactTelephone = $row['public_contact_telephone'];
                $this->publicContactEmail = $row['public_contact_email'];
                $this->gatewayScript = $row['gateway_script'];
                $this->gatewayLogin = $row['gateway_login'];
                $this->gatewayTransactionKey = $row['gateway_transaction_key'];
                $this->gatewayClientCode = $row['gateway_client_code'];
                $this->gatewayUserName = $row['gateway_user_name'];
                $this->gatewayPassword = $row['gateway_password'];
                $this->gatewayMerchantCode = $row['gateway_merchant_code'];
                $this->gatewayLocationCode = $row['gateway_location_code'];
                $this->gatewayTerminalCode = $row['gateway_terminal_code'];
                $this->gatewayAVSLevel = $row['gateway_AVS_level'];

            }
            $stmt->close();
        } else {
            exit('Failed prepare: ' . $sql . '<br/>' . mysql_error()) ;
        }
        $mysqli->close();

        return;

    }

    
    




    public function loadFromDbUsingClient($client){

        $sql = "SELECT * FROM accounts WHERE client = ?";
        $mysqli = new mysqli('localhost', $_SESSION['data']['user'], $_SESSION['data']['pass'], $_SESSION['data']['db']);
        if (mysqli_connect_errno()) {printf("Connect failed: %s\n", mysqli_connect_error());exit();
        }
        if ($stmt = $mysqli->prepare($sql)){
            $stmt->bind_param('s',$client);
            if (!$stmt->execute()){echo 'Invalid query: ' . $sql . '<br/><br/>';printf("Errormessage: %s\n", $mysqli->error);exit;}
            $stmt->store_result();

            $variables = array();
            $data = array();
            $meta = $stmt->result_metadata();

            while($field = $meta->fetch_field()){
                $variables[] = &$data[$field->name]; // pass by reference
            }   
            call_user_func_array(array($stmt, 'bind_result'), $variables);
            while($stmt->fetch()) {

                $row = array();
                foreach($data as $k=>$v)
                $row[$k] = $v;
                
//                exit("we made it");

                $this->id = $row['id'];
                $this->acct = $row['acct'];
                $this->description = $row['description'];
                $this->useGateAccess = $row['use_gate_access'];
                $this->usePrintAtHome = $row['use_print_at_home'];
                $this->useDirectFulfillment = $row['use_direct_fulfillment'];
                $this->status = $row['status'];
                $this->img1 = $row['img1'];
                $this->img2 = $row['img2'];
                $this->contact = $row['contact'];
                $this->email = $row['email'];
                $this->telephone = $row['telephone']; 


                $this->emailFrom = $row['email_from'];
                $this->emailFromName = $row['email_from_name'];
                $this->emailReply = $row['email_reply'];
                $this->emailReplyName = $row['email_reply_name'];

                $this->emailsTestTo = $row['emails_test_to'];
                $this->emailsCc = $row['emails_cc'];
                $this->emailsBcc = $row['emails_bcc'];
                $this->smtpHost = $row['smtp_host'];
                $this->smtpAuthenticationRequired = $row['smtp_host'];
                $this->smtpUser = $row['smtp_user'];
                $this->smtpPass = $row['smtp_pass'];
                $this->emailTimeOut = $row['email_time_out'];
                $this->customerTimeZone = $row['customer_time_zone'];

                $this->publicContactTelephone = $row['public_contact_telephone'];
                $this->publicContactEmail = $row['public_contact_email'];
                $this->gatewayScript = $row['gateway_script'];
                $this->gatewayLogin = $row['gateway_login'];
                $this->gatewayTransactionKey = $row['gateway_transaction_key'];
                $this->gatewayClientCode = $row['gateway_client_code'];
                $this->gatewayUserName = $row['gateway_user_name'];
                $this->gatewayPassword = $row['gateway_password'];
                $this->gatewayMerchantCode = $row['gateway_merchant_code'];
                $this->gatewayLocationCode = $row['gateway_location_code'];
                $this->gatewayTerminalCode = $row['gateway_terminal_code'];
                $this->gatewayAVSLevel = $row['gateway_AVS_level'];

            }
            $stmt->close();
        } else {
            exit('Failed prepare: ' . $sql . '<br/>' . mysql_error()) ;
        }
        $mysqli->close();

       return;

    }
    
    
    
    
    
    
    

}





?>
