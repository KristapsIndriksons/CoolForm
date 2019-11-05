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
  //Atgriež objekta _results vērtību, results ir objektu masīvs
  public function results(){
    return $this->_results;
  }

  public function count(){
    return $this->_count;
  }

  public function error(){
    return $this->_error;
  }
}