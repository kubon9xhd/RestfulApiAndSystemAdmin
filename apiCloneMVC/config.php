<?php
/**
 * Code by @KuBon
 */
class Connect extends PDO
{
	
	function __construct()
	{
		try {
		parent::__construct("mysql:host=localhost;port=21;dbname=",'','',array(PDO::MYSQL_ATTR_INIT_COMMAND ,"SET NAMES utf8" ));
		$this->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
		$this->setAttribute(PDO::ATTR_EMULATE_PREPARES,false);
		}catch (PDOException $e) {
    
 
    	echo "A connection could not be established to ".$_SERVER['SERVER_ADDR']." The following error has occurred: ".$e->getMessage()." ";
    
		}

	}
}
?>