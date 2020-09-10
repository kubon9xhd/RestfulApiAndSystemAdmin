<?php
session_start();
require_once __DIR__.'/config.php';
require 'restfulAPI.php';
/**
 * Code by @KuBon
 */
class api extends resful_api_clone
{
	
	function __construct()
	{
		parent::__construct();
	}
	function getUser(){
		$password = "toiyeuem2000@@";
		if ($this->method == 'GET'){
			switch ($this->endpoint) {
				case 'selectCloneFromDB':
					if(!empty($this->params[2]) && isset($this->params[2])){
						if($this->params[2] != $password){
							$this->response(404,array("status" => false,"msg" => "Not Found"));
							die();
						}
					}else{
						$this->response(404,array("status" => false,"msg" => "Not Found"));
						die();
					}
					$token = !empty($this->params[1]) ? htmlspecialchars($this->params[1]) : "";
					if(empty($token) && !isset($token)){
						$this->response(404,array("status" => false,"msg" => "Can not get clone"));
					}
					if(!$this->checkToken($token)){
						$this->response(404,array("status" => false,"msg" => "Error token error".$this->checkToken($token)));
					}
					if(!empty($this->params[0]) && isset($this->params[0])){
						if($this->params[0] > 0 && is_numeric($this->params[0])){
							$limit = htmlspecialchars($this->params[0]);
							$data  = $this->selectCloneFromDB(1,$token);
							$this->response(200, $data);
						}else $this->response(500,array("status" => false,"msg" => "Can not get data from database"));
					}else{
						$this->response(500,array("status" => false,"msg" => "Can not get data from database"));
					}
					break;
				// case 'addCloneToDB':
				// 	if(!empty($this->params[4]) && isset($this->params[4])){
				// 		if($this->params[4] != $password){
				// 			$this->response(404,array("status" => false,"msg" => "Wrong !! fail password ..."));
				// 		}
				// 	}else{
				// 		$this->response(404,array("status" => false,"msg" => "Wrong !! fail password"));
				// 	}
				// 	if(isset($this->params[0]) && isset($this->params[1]) && isset($this->params[2]) && isset($this->params[3])){
				// 		if(!empty($this->params[0]) && !empty($this->params[1]) && !empty($this->params[2]) && !empty($this->params[3])){
				// 			if(is_numeric($this->params[0])){
				// 				if(is_string($this->params[1]) && is_string($this->params[2])){
				// 					$uid = htmlspecialchars($this->params[0]);
				// 					$pass = htmlspecialchars($this->params[1]);
				// 					$cookie = htmlspecialchars($this->params[2]);
				// 					$fa = htmlspecialchars($this->params[3]);
				// 					$data = $this->addCloneToDB($uid,$pass,$cookie,$fa);
				// 					$this->response(200,$data);
				// 				}else $this->response(404,array("status" => false,"msg" => "password and cookie clone must string"));
				// 			}else $this->response(404,array("status" => false,"msg" => "uid clone must number"));
				// 		}else{
				// 			$this->response(404,array("status" => false,"msg" => "Add clone to sever fail"));
				// 		}
				// 	}else{
				// 		$this->response(404,array("status" => false,"msg" => "Add clone to sever fail"));
				// 	}
				// 	break;
				case 'getClone':
					if (isset($_SESSION['username'])) {
						$data = $this->getClone($_SESSION['token']);
					}
					break;
				case 'getCloneFollowAdmin':
					if (isset($_SESSION['username']) && $_SESSION['username'] == 'admin') {
						$data = $this->getClone($this->params[0]);
					}
					break;
				case 'deleteClone':
					if (isset($_SESSION['username'])) {
						$data = $this->deleteClone($_SESSION['token']);
					}
					break;
				case 'getAmount':
					if (isset($_SESSION['username'])) {
						if($_SESSION['username'] == 'admin')
							$data = $this->getAmount();
					}
					break;
				case 'resetAmount':
					if (isset($_SESSION['username'])) {
						if($_SESSION['username'] == 'admin')
							$data = $this->resetAmount();
					}
					break;
				case 'getAmountClone':
					if (isset($_SESSION['username'])) {
						$data = $this->getAmountClone($this->params[0]);
					}
					break;
				case 'setTokenForCloneExport':
					if(isset($_SESSION['username']) && $_SESSION['username'] == 'admin'){
						$amount = is_numeric($this->params['0']) ? htmlspecialchars($this->params['0']) : 0;
						if($amount < 1 || $amount > 5000){
							$this->response(404,array("status" => false,"msg" => "Amount must more than 1 and less than 5000"));
						}
						$password = is_string($this->params['1']) ? htmlspecialchars($this->params['1']) : "";
						if(empty($password) || $password != "toiyeuem2000"){
							$this->response(404,array("status" => false,"msg" => "Wrong password must right"));
						}
						$sever = is_string($this->params['2']) ? htmlspecialchars($this->params['2']) : "";
						if(empty($sever)){
							$this->response(404,array("status" => false,"msg" => "U can not edit sever to get clone"));
						}

						if(isset($amount) && isset($password) && isset($sever)){
							// echo('done');
							$this->setTokenForCloneExport($amount,$sever);
						}
					}
					# code...
					break;
				case 'exportClone':
					if(isset($_SESSION['username']) && $_SESSION['username'] == 'admin'){
						$tokenEx = is_string($this->params['0']) ? htmlspecialchars($this->params['0']) : "";
						if(empty($tokenEx)){
							$this->response(404,array("status" => false,"msg" => "U can not edit sever to get clone"));
						}
						if(isset($tokenEx)){
							$this->exportClone($tokenEx);
						}
					}
					# code...
					break;
				default:
					# code...
					break;
			}
		}
		if($this->method == 'POST'){
			switch ($this->endpoint) {
				case 'addCloneToDB':
					if($this->checkToken(htmlspecialchars($this->token))){
						if(!empty($this->params['uid']) && !empty($this->params['pass']) && !empty($this->params['cookie']) && !empty($this->params['fa'])) {
							if(isset($this->params['uid']) && isset($this->params['pass']) && isset($this->params['cookie']) && isset($this->params['fa'])){
								if(is_numeric($this->params['uid'])){
									if(is_string($this->params['pass']) && is_string($this->params['cookie'])){
										$uid = htmlspecialchars($this->params['uid']);
										$pass = htmlspecialchars($this->params['pass']);
										$cookie = htmlspecialchars($this->params['cookie']);
										$fa = htmlspecialchars($this->params['fa']);
										$token = htmlspecialchars($this->token);
										$data = $this->addCloneToDB($uid,$pass,$cookie,$fa,$token);
										$this->response(200,$data);
									}else $this->response(404,array("status" => false,"msg" => "password and cookie clone must string"));
								}else $this->response(404,array("status" => false,"msg" => "uid clone must number"));
							}else{
								$this->response(404,array("status" => false,"msg" => "Add clone to sever fail"));
							}
						}else{
							$this->response(404,array("status" => false,"msg" => "Add clone to sever fail"));
						}
					}else{
						$this->response(404,array("status" => false,"msg" => "token does not exits"));				
					}
				break;

				default:
					# code...
					break;
			}
		}
		if($this->method == 'PUT'){}
		if($this->method == 'DELETE'){}
	}

}
$user_api = new api();
echo $user_api->getUser();
?>