<?php


class RunNotes
{
    // property declaration
    public $id = 0;
    public $email = '';
    public $first = '';
    public $last = '';
    public $updated = '';
    public $subject = '';
    public $note = '';
    
    public function loadNotesFromDb (){
        
          $runNotes = array();
          
          $sql = "SELECT * FROM run_notes ORDER BY updated DESC";
          $mysqli = new mysqli('localhost', $_SESSION['data']['user'], $_SESSION['data']['pass'], $_SESSION['data']['db']);
          if (mysqli_connect_errno()) {printf("Connect failed: %s\n", mysqli_connect_error());exit();
          }
          if ($stmt = $mysqli->prepare($sql)){
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
                    
                    $runNote = new RunNotes();
                    $runNote->id = $row['id'];
                    $runNote->email = $row['email'];
                    $runNote->first = $row['first'];
                    $runNote->last = $row['last'];
                    $runNote->updated = $row['updated'];
                    $runNote->subject = $row['subject'];
                    $runNote->note = $row['note'];
                    array_push($runNotes,$runNote);
              }
              $stmt->close();
          } else {
              exit('Failed prepare: ' . $sql . '<br/>' . mysql_error()) ;
          }
          
          $mysqli->close();

     return $runNotes;
    }        
    
    public function loadEditNoteFromDb ($id) {
                
          $sql = "SELECT * FROM run_notes WHERE id = ? LIMIT 1";
          $mysqli = new mysqli('localhost', $_SESSION['data']['user'], $_SESSION['data']['pass'], $_SESSION['data']['db']);
          if (mysqli_connect_errno()) {printf("Connect failed: %s\n", mysqli_connect_error());exit();
          }
          $recordCount = 0;
          if ($stmt = $mysqli->prepare($sql)){
              $stmt->bind_param('i', $id);
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
                    $this->updated = $row['updated'];
                    $runNote->subject = $row['subject'];
                    $this->note = $row['note'];
        
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
