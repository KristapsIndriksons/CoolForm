<?php

class DB {
  private static $_instance = null;
  private $_pdo, $_query, $_error = false, $_count = 0, $_results;

  private function __construct(){
    try{
      $this->_pdo = new PDO('mysql:host=127.0.0.1'.';dbname=ADAS', 'root','');
    } catch(PDOException $e){
        die($e->getMessage());
        print_r($e);
    }
  }
  //Izveido savienojumu ar DB, ja tāds nav izveidots
  public static function getInstance(){
    if(!isset(self::$_instance)){
      self::$_instance = new DB();
    }
    return self::$_instance;
  }
  // Savieno ? -> parametriem un izpilda vaicājumu DB
  public function query($sql, $params = array()){
    $this->_error = false;
    if($this->_query = $this->_pdo->prepare($sql)){
      $x = 1;
      // echo count($params);
      if(count($params)){
        foreach($params as $param){
          $this->_query->bindValue($x, $param);
          $x++;
        }
      }
      if($this->_query->execute()){
        $this->_results = $this->_query->fetchAll(PDO::FETCH_OBJ);
        $this->_count = $this->_query->rowCount();
      } else {
        $this->_error = true;
      }
    }
    return $this;
  }
  //Izveido SELECT vaicājumu un padod Query metodei SQL un parametrus
  public function action($action, $table, $where = array(), $logic = array()){
    if(count($where) == 3){
      $operators = array('=','>','<','>=','<=');
      $field = $where[0];
      $operator = $where[1];
      $value = $where[2];
      if(in_array($operator, $operators)){
        $sql = "{$action} FROM {$table} WHERE {$field} {$operator} ?";
        if(!$this->query($sql, array($value))->error()){
          return $this;
        }
      }
    }else if(count($where) == 6 && count($logic) == 1){
      $operators = array('=','>','<','>=','<=');
      $logics = array('and','or');
      $field1 = $where[0];
      $operator1 = $where[1];
      $value1 = $where[2];
      $field2 = $where[3];
      $operator2 = $where[4];
      $value2 = $where[5];
      $logic_op = $logic[0];
      if(in_array($operator1, $operators) && in_array($operator2, $operators) && in_array($logic_op, $logics)){
        $sql = "{$action} FROM {$table} WHERE {$field1} {$operator1} ? {$logic_op} {$field2} {$operator2} ?";
        if(!$this->query($sql, array($value1,$value2))->error()){
          return $this;
        }
      }
    } else {
      $sql = "{$action} FROM {$table}";
      if(!$this->query($sql)->error()){
        return $this;
      }
    }
    return false;
  }
  //Insert vaicājuma izveide
  public function insert($table, $fields = array()){
    if(count($fields)){
      $keys = array_keys($fields);
      $values = null;
      $x = 1; //counter

      foreach ($fields as $field) {
        $values .= "?";
        if($x < count($fields)){
          $values .= ", ";
        }
        $x++;
      }
      // implode - pārvēš masīva elementus par string
      $sql = "INSERT INTO {$table}(`". implode('`, `',$keys) ."`) VALUES({$values})";
      // Izpilda vaicājumu un pārbauda vai tas bija veiksmīgs
      if(!$this->query($sql, $fields)->error()){
        return true;
      }
    }
    return false;
  }
  // Update vaicājuma izveidi
  public function update($table, $id, $fields = array()){
    $set = "";
    $x = 1;

    foreach($fields as $name => $value){
      $set .= "{$name} = ?";
      if($x < count($fields)){
        $set .= ", ";
      }
      $x++;
    }
    
    $sql = "UPDATE {$table} SET {$set} WHERE user_id = {$id}";
    
    if(!$this->query($sql, $fields)->error()){
      return true;
    }
    return false;
  }
  //Atgriež objekta _results vērtību, results ir objektu masīvs
  public function results(){
    return $this->_results;
  }
  // izsauc action funkciju
  public function get($table, $where, $logic){
    return $this->action('SELECT *', $table ,$where,$logic);
  }

  public function delete($table, $where){
    return $this->action('DELETE', $table ,$where);
  }

  public function count(){
    return $this->_count;
  }

  public function first(){
    return $this->results()[0];
  }

  public function error(){
    return $this->_error;
  }
}