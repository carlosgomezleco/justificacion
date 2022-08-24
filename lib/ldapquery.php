<?php

	session_start();
	
	define('SISIUS_LDAP_SERVER', 'ldap.uhu.es');
	define('SISIUS_LDAP_USEARCH_DN','ou=personal,dc=uhu,dc=es');            
	define('SISIUS_LDAP_ATT_NIF', 'uhuuserDni');     
	define('SISIUS_LDAP_ATT_MAIL','mail');      
	define('SISIUS_LDAP_ATT_PHONE', 'telephoneNumber');     
	define('SISIUS_LDAP_ATT_OU', 'ou');     
	define('SISIUS_LDAP_ATT_CN', 'cn');     
	define('SISIUS_LDAP_BIND_DN',  ''); // UHU: anonymous
	define('SISIUS_LDAP_PWD', '');
	require_once('lib/Db.php');
	require_once('lib/Conf.class.php');
 
	class LDAPQuery{
		protected $server;
		protected $conn;
		protected $bind;
		protected $entry;
		protected $attributes;
		protected $search_res;
  
  /**
   * construct object - connect to the server (usually doesn't fail, errors ocurr at bind)
   */  
	public function __construct($server) {              
		
		$this->server=$server;
		$this->conn = ldap_connect($this->server);
    
		if (!$this->conn){
			echo "error de conexion";
			exit -1;
		}
     
		$this->bind = FALSE;
		$this->attributes=array();
	}
  
  /**
   * detsruct object - close connection
   */          
	public function __destruct() {    
		if ($this->conn!==FALSE)
			ldap_close($this->conn);           
	}
	
  /**
   * Add an attribute to the list of attributes to obtain by next search
   */
	public function AddScalarAttribute($attrib) {
		if (!in_array($attrib,$this->attributes))
			array_push($this->attributes,$attrib);
	}
  
  /**
   * Clear attribute list so we can use AddScalarAttribute() to fill with new attributes
   */
	public function ClearScalarAttributes() {
		$this->attributes=array();
	}
	
  /**
   * Do a bind - anonymous if rdn is empty or with password otherwise
   */
	public function BindServer($rdn='',$password='') {
		if ($this->conn===FALSE) {
			return FALSE;
    }	 
    if (strlen($rdn)==0) //anonymous
      $this->bind = ldap_bind($this->conn);
    else //user and password
      $this->bind = ldap_bind($this->conn, $rdn, $password);
    return $this->bind;
	}
	
  /**
   * Do a search but only return dn of first found entry
   */
  
  
	public function GetFirstDNSearch($dn,$filter) {
		if ($this->bind===FALSE) {
			return FALSE;
		}
		$this->search_res=ldap_search($this->conn, $dn, $filter, $this->attributes);

		if ($this->search_res===FALSE) {
			return FALSE;
		}
		else if( ldap_count_entries($this->conn, $this->search_res) != 1 ) {
			return FALSE;
		}

		$this->entry = ldap_first_entry($this->conn, $this->search_res);
		if ($this->entry)
			return ldap_get_dn($this->conn,$this->entry);
		else
			return FALSE;
	} 
  
  
  /**
   * Do a search and return array of attribute values as specified by AddScalarAttribute() calls
   */
	public function DoSearch($dn,$filter) {
		if ($this->bind===FALSE){
			return FALSE;
		} 
    
		$this->search_res=ldap_search($this->conn, $dn, $filter,$this->attributes); 
    
		if ($this->search_res===FALSE) {
			return FALSE;
		}     
    
		$this->entry = ldap_first_entry($this->conn, $this->search_res);
		$result=array();
		if ($this->entry) {
			do {
				$res=array();
				$atts=ldap_get_attributes($this->conn,$this->entry);
         
				foreach ($this->attributes as $attribute) {
					$vals=ldap_get_values($this->conn,$this->entry,$attribute);
					$res[$attribute]=$vals[0];
				}
				array_push($result,$res);
			}while ($this->entry = ldap_next_entry($this->conn, $this->entry));
		}
		return $result;
	}
	
  /**
   * obtain attributes from last $this->entry that was returned by ldap
   */
	public function GetAttributesFromLastEntry() {
		if ($this->entry) {
		   $res=array();
		   $atts=ldap_get_attributes($this->conn,$this->entry);
			   
		   foreach ($this->attributes as $attribute) {
			   $vals=@ldap_get_values($this->conn,$this->entry,$attribute);
			   $res[$attribute]=$vals[0];
		   }
		   return $res;
		}
		else{
			return FALSE;
		}
	  }
} //end class

	/*Función que realiza la validación de los datos introducidos por el usuario*/
	function validar($investigador, $password){
		// connect
		$lquery=new LDAPQuery(SISIUS_LDAP_SERVER);
	  
		// do a bind for search
		$res=$lquery->BindServer(SISIUS_LDAP_BIND_DN,SISIUS_LDAP_PWD);

		if (!$res)
			return false;
		
		// for subsequent calls, contruct list of attributes we are interested in	  
		$lquery->AddScalarAttribute(SISIUS_LDAP_ATT_NIF);
		$lquery->AddScalarAttribute(SISIUS_LDAP_ATT_MAIL);
		$lquery->AddScalarAttribute(SISIUS_LDAP_ATT_PHONE);
		$lquery->AddScalarAttribute(SISIUS_LDAP_ATT_OU);
		$lquery->AddScalarAttribute(SISIUS_LDAP_ATT_CN);

	  
		// construct search string for user uid and get dn for first search result
		$qstring="(uid=".$investigador.')';
		//$qstring = "(&(uid=".TEST_UID.")(objectClass=inetOrgPerson))";

		$dn=  $lquery->GetFirstDNSearch(SISIUS_LDAP_USEARCH_DN, $qstring);
	  
		if (!$dn)
			return false;

		$res=$lquery->BindServer($dn,$password);

		if($res) {  
			// bind successful -> authentication ok   
			// get attributes from last entry received (user)    
			$result=$lquery->GetAttributesFromLastEntry();
		  
			$_SESSION['uhuuserDni']=$result[SISIUS_LDAP_ATT_NIF];
			$_SESSION['mail']=$result[SISIUS_LDAP_ATT_MAIL];
			$_SESSION['phone']=$result[SISIUS_LDAP_ATT_PHONE];
			$_SESSION['ou']=$result[SISIUS_LDAP_ATT_OU];
			$_SESSION['cn']=$result[SISIUS_LDAP_ATT_CN];
			
			return true;
		}
		return false;
	} //end function validar
	
	function comprobarUsuario($usuario){

		$bd = Db::getInstance();
		$select = "SELECT * FROM jp_usuarios WHERE usuario = '". $usuario."'"; 
		$c = $bd->ejecutar($select);
		if($row = $bd->obtener_fila($c)){
			if($row['usuario'] == $usuario){
				$_SESSION['permisos'] = $row['permisos'];
				return true;
			}
		} 
		return false;
	}
?>