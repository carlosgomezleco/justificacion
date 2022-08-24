<?php
/* Clase encargada de gestionar las conexiones a la base de datos */

Class Db{

   private $servidor;
   private $usuario;
   private $password;
   private $base_datos;
   private $conn;
   private $stmt;
   private $datos;

   static $_instance;

   /*La función construct es privada para evitar que el objeto pueda ser creado mediante new*/
   private function __construct(){
      $this->setConexion();
      $this->conectar();
   }

   /*Método para establecer los parámetros de la conexión*/
   private function setConexion(){
      $conf = Conf::getInstance();
      $this->servidor = $conf->getHostDB();
      $this->base_datos = $conf->getDB();
      $this->usuario = $conf->getUserDB();
      $this->password = $conf->getPassDB();
   }

   /*Evitamos el clonaje del objeto. Patrón Singleton*/
   private function __clone(){ }

   /*Función encargada de crear, si es necesario, el objeto. Esta es la función que debemos llamar desde fuera de la clase para instanciar el objeto, y así, poder utilizar sus métodos*/
   public static function getInstance(){
      if (!(self::$_instance instanceof self)){
         self::$_instance=new self();
      }
         return self::$_instance;
   }

   /*Realiza la conexión a la base de datos.*/
   private function conectar(){
      $this->conn = mysqli_connect($this->servidor, $this->usuario, $this->password);
      mysqli_select_db($this->conn, $this->base_datos);
      mysqli_query($this->conn, "SET NAMES 'utf8'");
   }

   /*Método para ejecutar una sentencia sql*/
   public function ejecutar($sql){
      $this->stmt = mysqli_query($this->conn, $sql);
      return $this->stmt;
   }

   /*Método para obtener una fila de resultados de la sentencia sql*/
   public function obtener_fila($stmt){
		return $this->datos = mysqli_fetch_array($stmt, MYSQLI_ASSOC);
   }

   //Devuelve el último id del insert introducido
   public function lastID(){
      return mysqli_insert_id($this->conn);
   }
   
   //Número de registros afectados por un SELECT
   public function totalRegistros($stmt){
	   return mysqli_num_rows($stmt);
   }
   
   //Número de registros afectados por un INSERT, UPDATE o DELETE
   public function totalAfectados(){
	   return mysqli_affected_rows($this->conn);
   }
   
   //Escapa los caracteres especiales de una cadena para usarla en una sentencia SQL
   public function escapar($cadena){
	   return mysqli_real_escape_string($this->conn,$cadena);
   }
   
   public function desconectar(){
	   return mysqli_close($this->conn);
   }

}
?>