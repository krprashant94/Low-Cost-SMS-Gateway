<?php

    Class Login{
        public function __construct(){}
        
        public function login($user, $pass, $conn){
		$query1 = $conn->prepare("SELECT `user_id`, `auth`, `pin`, `location` FROM `"._RMDB_."`.`api_users` WHERE `user` = ? AND  `password` = ?");
		$query2 = $conn->prepare("SELECT `access_log` FROM  `"._RMDB_."`.`dtdc_user_log` WHERE  `user_id` =  ?");
		$query3 = $conn->prepare("UPDATE `"._RMDB_."`.`dtdc_user_log` SET `access_log` = ? WHERE `dtdc_user_log`.`user_id` = ?");
		$query4 = $conn->prepare("UPDATE `"._RMDB_."`.`dtdc_user_log` SET `last_login` = ?  WHERE `dtdc_user_log`.`user_id` = ?");
		$query5 = $conn->prepare("DELETE FROM `"._RMDB_."`.`dtdc_fail_login` WHERE  `user_id` = ? ");
		$log = array('time' => time(), 'success'=>'Y');
		$log = json_encode($log);
		try{
			$conn->beginTransaction();
			$query1->execute(array($user, $pass));
			$num = $query1->rowCount();
			if($num == 1){
				$res = $query1->fetchAll(PDO::FETCH_ASSOC);
				$query2->execute(array($res[0]['user_id']));
				$fetch_log = $query2->fetchAll(PDO::FETCH_COLUMN);
				$fetch_log = json_decode($fetch_log[0]);
				array_push($fetch_log, $log);
				$new_log = json_encode($fetch_log);
				$query3->execute(array($new_log, $res[0]['user_id']));
				$query4->execute(array($log, $res[0]['user_id']));
				$query5->execute(array($user));
				$_SESSION['s_id'] = $res[0]['user_id'];
				$_SESSION['auth'] = $res[0]['auth'];
				$_SESSION['pin'] = $res[0]['pin'];
				$_SESSION['location'] = $res[0]['location'];
				$_SESSION['time'] = time();
				$conn->commit();
				return true;
			}else{
				throw new PDOException();
			}
		}catch(PDOException $e){
			$conn->rollBack();
			return false;
		}
	}
    }
	include("../core.inc.php");
	
	$feedback = array('status' => 'fail', 'html' => 'Failed &hellip;');
	
	header("Content-type: text/javascript");
	
	if(isset($_SESSION['s_id'])){
		$feedback['status'] = 'info';
		$feedback['html'] = 'You are already logged In.';
		echo (json_encode($feedback));
	}else{
		if(isset($_POST['user_id']) && isset($_POST['pass'])){
			$id = $_POST['user_id'];
			$pass = $_POST['pass'];
			if( !empty($id) && !empty($pass)){
				$pass = md5($pass);
				require "../db.inc.php";
				if(blocking($id, $conn)){
						$feedback['html'] = 'Due to security reason your login is blocked for some min.';
						echo (json_encode($feedback));
						die();
				}else{
                                    $login = new login();
					if($login->login($id, $pass, $conn)){
						$feedback['status'] = 'success';
						$feedback['html'] = 'Successfully login. Redirectring please wait ...';
						echo (json_encode($feedback));
						die();
					}else{
						include "../fail_login_attemp.php";
						log_login($id, $conn);
						$feedback['status'] = 'fail';
						$feedback['html'] = 'Your login id and password that you are entered is wrong.';
						echo (json_encode($feedback));
						die();
					}
				}
			}else{
				$feedback['status'] = 'info';
				$feedback['html'] = 'Important input fields are empty. Please fill this form.';
				echo (json_encode($feedback));
				die();
			}
		}
	}
	function blocking($user, $conn){
		$e_query = $conn->prepare("SELECT `time` FROM `"._RMDB_."`.`dtdc_fail_login` WHERE `user_id` = ? ORDER BY `dtdc_fail_login`.`time` ASC");
		$e_query->execute(array($user));
		$res = $e_query->rowCount();
		if($res >= 3){
			$time = $e_query->fetchAll(PDO::FETCH_ASSOC);
			if($time[$res-1]['time']+54000 >= time() && $time[$res-2]['time']+54000 >= time() && $time[$res-3]['time']+54000 >= time())
				return true;
			else
				return false;
		}
		return false;
	}
?>