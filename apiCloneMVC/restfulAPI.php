<?php
date_default_timezone_set("Asia/Ho_Chi_Minh");
/**
 * Code by @Kubon
 */
class resful_api_clone{
	// cac thuoc tinh
	protected $method = '';
	protected $endpoint = '';
	protected $token = '';
	protected $params   = array();
	protected $file     = null;

	function __construct()
	{
		$this->_input();
		$this->_process_api();
	}
	protected function selectCloneFromDB($limit = 0,$token = ""){
		$db = new Connect();
		$users = array();
		$data = $db->prepare('SELECT time,id,uid,pass,cookie,fa,action FROM clone WHERE action = 0 AND export = 0 AND token = :token LIMIT 1');
		$data->bindParam(":token",$token);
		$data->execute();
		$ouputData = $data->fetch(PDO::FETCH_ASSOC);
		$user = array(
				"data" =>array(
					'time' => date("Y-m-d h:i:sa",$ouputData['time']),
					'id' => $ouputData['id'],
					'uid' => $ouputData['uid'],
					'pass' => $ouputData['pass'],
					'cookie' => $ouputData['cookie'],
					'2fa' => $ouputData['fa'],
					'action' => $ouputData['action']
				)
		);
		
		if(!empty($user)){
			// for ($i=0; $i < count($user) ; $i++) { 
				if(isset($user['data']['uid'])){
					$sql = 'UPDATE clone SET action = 1 WHERE id='.$user['data']['id'];
					$data = $db->prepare($sql);
					$data->execute();
					// update amount
					$sql = 'UPDATE thongke SET amount = '.$this->updateAmount();
					$data = $db->prepare($sql);
					$data->execute();
				}
			// }

			$this->response(200,$user);
		}
		else
			$this->response(500,array("status" => false,"msg" => "Can not get data from database1"));
	}
	protected function resetAmount(){
		$db = new Connect();
		$sql = 'UPDATE thongke SET amount = 0';
		$data = $db->prepare($sql);
		try{
			$data->execute();
			$this->response(200,array(array("done")));
		}catch(PDOException $e){
			$this->response(500,$e);
		}

	}
	protected function getAmount(){
		$db = new Connect();
		$sql = 'SELECT * FROM `thongke`';
		$number = array();
		$data = $db->prepare($sql);
		$data->execute();
		while ($amount = $data->fetch(PDO::FETCH_ASSOC)) {
			$number = array($amount['amount']);
		}
		$this->response(200,$number);
	}
	private function updateAmount(){
		$db = new Connect();
		$sql = 'SELECT amount FROM `thongke`';
		$data = $db->prepare($sql);
		$data->execute();
		while ($amount = $data->fetch(PDO::FETCH_ASSOC)) {
			$number = $amount['amount']+1;
		}
		return $number;
	}
	protected function addCloneToDB($uid = null, $pass = "", $cookie = "", $fa = "",$token = ""){
		$db = new Connect();
		$t=time();
		if($uid != 0 && $pass !="" && $cookie != "" && $token != ""){
			// if($this->checkUid($uid)) 
			// $this->response(500,array(0 =>array("satus"=>false,"msg"=>"Clone is exits")));
			$data = $db->prepare("INSERT INTO clone (uid, pass, cookie, fa,time,token) VALUES (:uid, :pass, :cookie, :fa,:time,:token)");
			$data->bindParam(":uid", $uid);
			$data->bindParam(":pass", $pass);
			$data->bindParam(":cookie",$cookie);
			$data->bindParam(":fa", $fa);
			$data->bindParam(":time", $t);
			$data->bindParam(":token", $token);
			try{
				$data->execute();
				$this->response(200,array(0 =>array("uid"=>$uid,"pass"=>$pass,"cookie"=>$cookie,"2fa"=>$fa,"satus"=>true,"time"=>$t,"token"=>$token)));
			}catch(PDOException $e){
				$this->response(500,$e);
			}
			
		}else $this->response(500,"The data must right format");
		$db->close();
	}
	protected function getClone($token = ""){
		if(!$this->checkAmountCloneInSever($token)){
			$user["data"][] = [ "Now",
								"Demo",
								"Demo",
								"Demo",
								"Demo",
								"Demo"];
			$this->response(200,$user);
		}
		$db = new Connect();
		$users = array();
		$data = $db->prepare('SELECT * FROM clone WHERE token = :token AND export = 0 ORDER BY id DESC LIMIT 100');
		$data->bindParam(":token",$token);
		$data->execute();
		$i = 0;
		while ($ouputData = $data->fetch(PDO::FETCH_ASSOC)) {
			$user["data"][] = [ date("Y-m-d h:i:sa",$ouputData['time']),
								$i++,
								$ouputData['uid']."|".$ouputData['pass']."|".$ouputData['cookie']."|".$ouputData['fa']];
		}
		if(isset($user)){
			$this->response(200,$user);
		}else{
			$user["data"][] = [ "Now",
								"Demo",
								"Demo",
								"Demo",
								"Demo",
								"Demo"];
			$this->response(200,$user);
		}
		$this->response(200,$user);
		$db->close();
	}
	protected function checkAmountCloneInSever($token = ""){
		$db = new Connect();
		if(!empty($token) && isset($token)){
			$data = $db->prepare('SELECT COUNT(*) FROM clone WHERE token = :token AND export = 0');
			$data->bindParam(":token", $token);			
			$data->execute();
			$row = $data->fetchColumn();
			if($row > 0){
				return true;
			}
		}
		return false;
	}
	protected function getCloneFollowAdmin($token = ""){
		if(!$this->checkAmountCloneInSever($token)){
			$user["data"][] = [ "Now",
								"Demo",
								"Demo",
								"Demo",
								"Demo",
								"Demo"];
			$this->response(200,$user);
		}
		$db = new Connect();
		$users = array();
		$data = $db->prepare('SELECT * FROM clone WHERE token = :token AND export = 0  ORDER BY id DESC LIMIT 100');
		$data->bindParam(":token",$token);
		$data->execute();
		while ($ouputData = $data->fetch(PDO::FETCH_ASSOC)) {
			$user["data"][] = [ date("Y-m-d h:i:sa",$ouputData['time']),
								$ouputData['id'],
								$ouputData['uid'],
								$ouputData['pass'],
								$ouputData['cookie'],
								$ouputData['fa']];
		}
		if(isset($user)){
			$this->response(200,$user);
		}else{
			$user["data"][] = [ "Now",
								"Demo",
								"Demo",
								"Demo",
								"Demo",
								"Demo"];
			$this->response(200,$user);
		}
		$this->response(200,$user);
		$db->close();
	}
	// protected function deleteClone($token = ""){
	// 	$db = new Connect();
	// 	try{
	// 		$data = $db->prepare('DELETE FROM `clone` WHERE token = :token');
	// 		$data->bindParam(':token',$token);
	// 		$response = $data->execute();
	// 		$this->response(200,array("staus"=>true,"msg"=>"done"));
	// 	}catch(PDOException $e){
	// 		$this->response(500,$e);
	// 	}

	// }
	protected function getAmountClone($token = ""){
		$db = new Connect();
		$number = array();
		$data = $db->prepare('SELECT COUNT(*) FROM clone WHERE token = :token AND export = 0 AND action = 0');
		$data->bindParam(":token",$token);
		$data->execute();
		$row = $data->fetchColumn();
		$number = array($row);
		$this->response(200,$number);
	}
	private function checkUid($uid = null){
		$db = new Connect();
		if(!empty($uid) && isset($uid)){
			$data = $db->prepare('SELECT COUNT(*) FROM clone WHERE action = 0 AND uid ='.$uid);
			$data->execute();
			$row = $data->fetchColumn();
			if($row > 0){
				return true;
			}
		}
		return false;
	}
	protected function checkToken($token = null){
		$db = new Connect();
		if(!empty($token) && isset($token)){
			$data = $db->prepare('SELECT COUNT(*) FROM admin WHERE token = :token');
			$data->bindParam(":token", $token);			
			$data->execute();
			$row = $data->fetchColumn();
			if($row > 0){
				return true;
			}
		}
		return false;
	}
	protected function nameSever($token = ""){
		$db = new Connect();
		$name = "";
		if(!empty($token) && isset($token)){
			$data = $db->prepare('SELECT * FROM admin WHERE token = :token');
			$data->bindParam(":token", $token);			
			$data->execute();
			while ($nameSever = $data->fetch(PDO::FETCH_ASSOC)) {
				$name = $nameSever['username'];
			}
		}
		return $name;
	}
	protected function setTokenForCloneExport($amount = 0, $sever = ""){
		$db = new Connect();
		//genderate new token 
		$bytes = random_bytes(20);
		$tokenEx = bin2hex($bytes).time().md5($bytes);
		// token sever
		$array = ['5fe69c95ed70a9869d9f9af7d8400a6673bb9ce9','a975ce2e22441f565f3a367acd4cbaf94436440915987918524d59bf3e3c4d03cc49ee8826ae8f3c01','955b0f487f7136c432be993cfe6e6398ffa5a1071599191087926a4a28cf494d6e5becd0e36fda7fc3','682c1e8e7104bb1d5ee044538eaebed67510ab1b159915436419854bdae73a22e7b16e6b88a2d97d76','1611151109afb95eed41b8ffbe7fa1ea699885041599226887ac6aa32a8fa326f99573be4fb0d68d71'];
		//
		if(in_array($sever, $array)){
			$data = $db->prepare('SELECT * FROM clone WHERE token = :token AND export = 0 ORDER BY ID DESC LIMIT '.$amount);
			$data->bindParam(":token",$sever);
			try{
				$data->execute();
			}catch(PDOException $e){
				$this->response(500,$e);
			}
			//echo('done');
			//set token for clone
			$satus = false;
			while ($ouputData = $data->fetch(PDO::FETCH_ASSOC)) {
				// update token for clone to get
				$dt = $db->prepare('UPDATE clone SET tokenExport = :tokenExport, export = 1 WHERE id = '.$ouputData['id']);
				$dt->bindParam(':tokenExport',$tokenEx);
				try{
					$dt->execute();
					$status = true;
				}catch(PDOException $e){
					$this->response(500,$e);
				}
			}
			if($status){
				$dt = $db->prepare('INSERT INTO history (time, tokenExport, username,amount) VALUES (:timeNow, :tokenExport, :username,:amount)');
				$dt->bindParam(':timeNow',time());
				$dt->bindParam(':tokenExport',$tokenEx);
				$dt->bindParam(':username',$this->nameSever($sever));
				$dt->bindParam(':amount',$amount);
				$time = time();
				try{
					$dt->execute();
					$url = ["http://jupiter-ns.club/api.php/exportClone/".$tokenEx."",
							date("Y-m-d h:i:sa",$time),$tokenEx,$this->nameSever($sever),$amount];
				}catch(PDOException $e){
					$this->response(500,$e);
				}
				$this->response(200,array($url));
			}

		}

	}
	protected function nameSeverData($token = ""){
		$db = new Connect();
		$name = "";
		if(!empty($token) && isset($token)){
			$data = $db->prepare('SELECT * FROM history WHERE tokenExport = :token');
			$data->bindParam(":token", $token);			
			$data->execute();
			while ($nameSever = $data->fetch(PDO::FETCH_ASSOC)) {
				$name = $nameSever['username'];
			}
		}
		return $name;
	}
	protected function checkTokenEx($token = ""){
		$db = new Connect();
		if(!empty($token) && isset($token)){
			$data = $db->prepare('SELECT COUNT(*) FROM history WHERE tokenExport = :token');
			$data->bindParam(":token", $token);			
			$data->execute();
			$row = $data->fetchColumn();
			if($row > 0){
				return true;
			}
		}
		return false;
	}
	protected function exportClone($token = ""){
		$db = new Connect();
		$users = array();

		$filename = "clone-sever-".$this->nameSeverData($token)."-" . date("Y-m-d h:i:sa",time()) . ".xls";
		header("Content-Disposition: attachment; filename=\"$filename\"");
  		header("Content-Type: application/vnd.ms-excel");
		
		if($this->checkTokenEx($token)){
			$data = $db->prepare('SELECT * FROM clone WHERE tokenExport = :token AND export = 1');
			$data->bindParam(":token",$token);
			try{
				$data->execute();
			}catch(PDOException $e){
				$this->response(500,$e);
			}

      		$flag = false;
			while ($ouputData = $data->fetch(PDO::FETCH_ASSOC)) {
				$user = [ date("Y-m-d h:i:sa",$ouputData['time']),
									$ouputData['uid'],
									$ouputData['pass'],
									$ouputData['cookie'],
									$ouputData['fa']
							];
				if (!$flag) {
					$firstRow = ['time','uid','pass','cookie','2fa'];
			        // display field/column names as first row
			        echo implode("\t", $firstRow) . "\r\n";
			        $flag = true;
			    }
			    array_walk($user, __NAMESPACE__ . '\cleanData');
    			echo implode("\t", array_values($user)) . "\r\n";
			}

		}else{
			$this->response(404, array("status" => false,"msg" => "The sever does not exist"));
		}
	}
	private function cleanData(&$str)
	{
	    $str = preg_replace("/\t/", "\\t", $str);
	    $str = preg_replace("/\r?\n/", "\\n", $str);
	    if(strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"';
	}
	private function _input(){
        $this->params = explode('/', trim($_SERVER['PATH_INFO'],'/'));
        $this->endpoint = array_shift($this->params);
        $this->token = $this->params[0];

        // L?y method c?a request
        $method         = $_SERVER['REQUEST_METHOD'];
        $allow_method   = array('GET', 'POST', 'PUT', 'DELETE');

        if (in_array($method, $allow_method)){
            $this->method = $method;
        }

        // Nh?n thêm d? li?u tuong ?ng theo t?ng lo?i method
        switch ($this->method) {
            case 'POST':
                $this->params = $_POST;
            break;

            case 'GET':
                // Không c?n nh?n, b?i params dã du?c l?y t? url
            break;

            case 'PUT':
                $this->file    = file_get_contents("php://input");
            break;

            case 'DELETE':
                // Không c?n nh?n, b?i params dã du?c l?y t? url
            break;

            default:
                $this->response(500, array("status" => false,"msg" => "Invalid Method"));
            break;
        }
    }
    private function _process_api(){
        // code c?a hàm _process_api
        if (method_exists($this, $this->endpoint)){
            // $this->{$this->endpoint}();
        }
        else {
            $this->response(500, array("status" => false,"msg" => "Unknown Error"));
        }
    }

    protected function response($status_code, $data = NULL){
        header($this->_build_http_header_string($status_code));
        header("Content-Type: application/json");
        echo json_encode($data,JSON_PRETTY_PRINT);
        die();
    }
    private function _build_http_header_string($status_code){
        $status = array(
            200 => 'OK',
            404 => 'Not Found',
            405 => 'Method Not Allowed',
            500 => 'Internal Server Error'
        );
        return "HTTP/1.1 " . $status_code . " " . $status[$status_code];
    }
}
?>