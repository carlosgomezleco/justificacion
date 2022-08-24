<?php 

Class Conf{
   private $_userdb;
   private $_passdb;
   private $_hostdb;
   private $_db;

   static $_instance;

   private function __construct(){
      require 'config.php';
      $this->_userdb = DB_USER;
      $this->_passdb = DB_PASS;
      $this->_hostdb = DB_HOST;
      $this->_db = DB_NAME;
   }

   private function __clone(){ }

   public static function getInstance(){
      if (!(self::$_instance instanceof self)){
         self::$_instance=new self();
      }
      return self::$_instance;
   }

   public function getUserDB(){
      $var = $this->_userdb;
      return $var;
   }

   public function getHostDB(){
      $var = $this->_hostdb;
      return $var;
   }

   public function getPassDB(){
      $var = $this->_passdb;
      return $var;
   }

   public function getDB(){
      $var = $this->_db;
      return $var;
   }
}
?>