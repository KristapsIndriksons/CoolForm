<?php
require './DB.php';
class Validate{
  private $_passed = false,
          $_errors = array(),
          $_db = null;

          
  public function __construct() {
    $this->_db = DB::getInstance();
  }

  public function check_user($source, $items = array()){
      $username = trim($source['username']);
      $password = trim($source['password']);
      echo $username.' '.$password;

  $sql = "SELECT * FROM users WHERE username = ? and password = ?";
  $check = $this->_db->query($sql, array($username,$password));
              if($check->count()){
                echo "User found!";
              }else{
                $this->addError("User not found!");
              }
              if(empty($this->_errors)){
                $this->_passed = true;
              }
              return $this;
  }

  private function addError($error){
    $this->_errors[] = $error;
  }

  public function errors(){
    return $this->_errors;
  }

  public function passed(){
    return $this->_passed;
  }
}

?>
