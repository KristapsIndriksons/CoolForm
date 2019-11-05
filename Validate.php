<?php
require './DB.php';
class Validate{
  private $_passed = false,
          $_errors = array(),
          $_db = null;

          
  public function __construct() {
    $this->_db = DB::getInstance();
  }

  public function check($source, $items = array()){
    foreach ($items as $item => $rules) {
      foreach ($rules as $rule => $rule_value){
        $value = trim($source[$item]);
        if($rule === 'required' && empty($value)){
          $this->addError("{$item} is required");
        } else if(!empty($value)) {
          switch($rule){
            case 'min':
              if(strlen($value) < $rule_value){
                $this->addError("{$item} must be a min of {$rule_value} chars");
              }
            break;
            case 'max':
            if(strlen($value) > $rule_value){
              $this->addError("{$item} must be a max of {$rule_value} chars");
            }
            break;
            case 'unique':
              $check = $this->_db->get($rule_value,array($item,'=',$value));
              if($check->count()){
                $this->addError("{$item} already exists");
              }
            break;
            case 'length':
            if(strlen($value) !== $rule_value){
              $this->addError("{$item} must be a lenght of {$rule_value} numbers");
            }
            break;
            default:
            break;
          }
        }
      }
    }
    if(empty($this->_errors)){
      $this->_passed = true;
    }
    return $this;
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
