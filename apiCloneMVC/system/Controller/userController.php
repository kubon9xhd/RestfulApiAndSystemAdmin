<?php
session_start();
/**
 * Code by @KuBon
 */
require_once '../../Model/database.php';
class userController
{
	public $status = "";
	function __construct()
	{
	}
	public function login(){
		if(!isset($_SESSION['username'])){
			$username = isset($_POST['username'])? $_POST['username'] : "";
			$password = isset($_POST['password'])? $_POST['password'] : "";
			if(!empty($password) && !empty($username)){
				// check
				$username = htmlspecialchars($username);
				$password = htmlspecialchars($password);
				// $password = md5($password);
				// Connect to DB
				$db =  new Connect();
				$data = $db->prepare('SELECT COUNT(*) FROM admin WHERE username = :username AND password = :password');
				$data->bindParam(":username",$username);
				$data->bindParam(":password",$password);
				$data->execute();
				$row = $data->fetchColumn();
				if($row > 0){
					$_SESSION['username'] = $username;
					$token = $this->getToken($_SESSION['username']);
					$_SESSION['token'] = !empty($token)? $token : "";
					 echo '<script type="text/javascript">alert("Login Done !!");
                			window.location="../../index.php";
            			   </script>';
				}else
				{	
					$error = "Erorr! Account does not exits in db";
					$this->status = $error;
					require_once '../../View/login/login.php';
				}

			}else{
				$error = "Error! username and password must right enter";
				$this->status = $error;
				require_once '../../View/login/login.php';
			}
		}
	}
	private function getToken($username = ''){
		$db = new Connect();
		// $sql = 
		$data = $db->prepare('SELECT * FROM `admin` WHERE username = :username');
		$data->bindParam(':username',$username);
		$data->execute();
		while ($account = $data->fetch(PDO::FETCH_ASSOC)) {
			$token = $account['token'];
		}
		return $token;
	}
	public function register(){
		if(isset($_SESSION['username'])){
			$name = isset($_POST['name']) ? $_POST['name'] : '';
			$username = isset($_POST['username']) ? $_POST['username'] : '';
			$password = isset($_POST['password']) ? $_POST['password'] : '';
			if(!empty($name) && !empty($username) && !empty($password)){
				$name = htmlspecialchars($name);
				$username = htmlspecialchars($username);
				$password = htmlspecialchars($password);
				$bytes = random_bytes(20);
				$token = bin2hex($bytes).time().md5($bytes);
				// connect to db
				if($this->checkAccount($username)){
					$this->status = "Register account fail !!, The username does exits";
					require_once '../../View/login/register.php';			
				}else{
					try{
						$db = new Connect();
					}catch(PDOException $e){
						$error = "Cannot connect to DB";
						$this->status = $error;
						require_once '../../View/login/register.php';
					}
					$data = $db->prepare('INSERT INTO admin(username,password,token) VALUES (:username,:password,:token)');
					$data->bindParam(':username',$username);
					$data->bindParam(':password',$password);
					$data->bindParam(':token',$token);
					try{
						$data->execute();
					}catch(PDOException $e){
						$error = "Cannot connect to DB msg";
						$this->status = $error;
						require_once '../../View/login/register.php';
					}
					if(empty($this->status)){
						$this->status = 'Register Account Done !! Back to <a class="text-center" href="login.php">Login Account</a>';
						require_once '../../View/login/register.php';
					}

				}		
			}else{
				$error = "The elements does not empty";
				$_SESSION['error'] = $error;
				require_once '../../View/login/register.php';
			}
		}

	}
	private function checkAccount($username="")
	{
		try{
			$db =  new Connect();
			$data = $db->prepare('SELECT COUNT(*) FROM admin WHERE username = :username');
			$data->bindParam(":username",$username);
			$data->execute();
			$row = $data->fetchColumn();
		}catch(PDOException $e){
			return false;
		}
		if($row > 0){
			return true;
		}
			return false;
	}
	public function deleteAccount(){

	}
}
?>
