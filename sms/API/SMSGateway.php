<?php
	/**
	* SMS Gateway API
	* @author Prashant Kumar
	* @date  27 Apr 2018
	*
	**/
	namespace Redmorus;
	include 'GatewayAPIException.php';
	use Redmorus\Exceptions\GatewayAPIException;
	define("PORT", "COM3");
	/**
	*  SMS class
	*/
	class SMS
	{
		/**
		* @var OK|PROCESSING|TASK_DONE|FAIL|false Message sent or not.
		*/
		private $status = false;

		/**
		* @var String, Message Text to be send.
		*/
		private $text;

		/**
		* @var long int array, Contact nos.
		*/
		private $phone;

		/**
		* @var int process id for aurdino and id in database.
		*/
		private $process_id;
		function __construct($id, $msg, $ph)
		{
			$this->process_id = $id;
			$this->text = $msg;
			$this->phone = $ph;
		}
		public function getPhone(){
			return $this->phone;
		}
		public function getMessage(){
			return $this->text;
		}
		public function getProcessId(){
			return $this->process_id;
		}
		public function getStatus(){
			return $this->status;
		}
		public function setStatus(){
			$this->status = shell_exec('receive from '.PORT.'  '.$this->process_id);
		}
	}
	/**
	* Phone Call Class
	*/
	class Call
	{
		/**
		* @var OK|PROCESSING|TASK_DONE|FAIL|false Message sent or not.
		*/
		private $status = false;

		/**
		* @var long int, Contact no.
		*/
		private $phone;

		/**
		* @var int process id for aurdino and id in database.
		*/
		private $process_id;
		public function __construct($id, $ph)
		{
			$this->phone = $ph;
			$this->process_id = $id;
		}
		public function getPhone(){
			return $this->phone;
		}
		function getProcessId(){
			return $this->process_id;
		}
		public function getStatus(){
			return $this->status;
		}
		public function setStatus(){
			$this->status = shell_exec('receive from '.PORT.' '.$this->process_id);
		}
	}
	/**
	*  Class for Gateway 
	*/
	class Gateway 
	{
		/**
		* @const string Version number of the SMS Gateway.
		*/
		const VERSION = '2.0';
		
		/**
		* @const string Default SMS Module.
		*/
		const SIM_MODULE = 'SIM900A';
		
		/**
		* @var RedmorusClient ID.
		*/
		protected $client;
		
		
		/**
		* @var AccessToken|null The default access token to use with requests.
		*/
		protected $accessToken;
		
		/**
		* @var OK|FAIL|null Stores the last request made to Module.
		*/
		protected $lastResponse;
		
		
		
		
		/**
		* Instantiates a new Gateway super-class object.
		*
		* @param array $key, $port
		*
		* @throws GatewayAPIException
		*/
		private $validation = false;
		private $ph, $sms;

		function __construct($key)
		{
			$this->accessToken = $key;
		}
		/**
		 * Returns the default AccessToken entity.
		 *
		 * @return AccessToken|null
		 */
		public function getValidation()
		{
			return $this->validation;
		}
		/**
		 * Returns the default AccessToken entity.
		 *
		 * @return ClientID|null
		 */
		public function getClient()
		{
			return $this->client;
		}
		/**
		 * Returns the last response returned from API.
		 *
		 * @return OK|FAIL|null
		 */
		public function getLastResponse()
		{
			return $this->lastResponse;
		}

		/**
		 * Returns true for valid API key.
		 *
		 * @return true|GatewayAPIException
		 *
		 * @throws GatewayAPIException
		 */
		public function validate($conn){
			try{
				$conn->beginTransaction();
				$query = $conn->prepare("SELECT `user_id` FROM `api_users` WHERE `api_key` = ?");
				$query->execute(array($this->accessToken));
				$res = $query->fetchAll(\PDO::FETCH_COLUMN);
				if($query->rowCount() == 1){
					$this->validation = true;
					$this->client = $res[0];
				}
				else{
					throw new  GatewayAPIException("API key validation error", 21);
				}
				$conn->commit();
			}catch(PDOException $e){
				$conn->rollBack();
				$validation = false;
				throw new  GatewayAPIException("API key validation error", 21);
			}catch(Exception $e){
				$conn->rollBack();
				$validation = false;
				throw new  GatewayAPIException("API key validation error", 21);
			}
		}
		/**
		 * @return true|GatewayAPIException
		 *
		 * @throws GatewayAPIException
		 */
		function sms($sms){
			$cmd = 'GC+SID'.$sms->getProcessId().":GC+PHN";
			$cmd .=  $sms->getPhone().':';
			if ($this->validation) {
				$tmp = 'send to '.PORT.' '.$cmd;
				$responce = shell_exec($tmp);
				sleep(1);
				$tmp = explode(' ', $sms->getMessage());
				$i = 0;
				foreach ($tmp as $value) {
					$tmp = 'send to '.PORT.' "'.$value.' :"';
					shell_exec($tmp);
					$i += strlen($value);
					if($i >= 40){
						sleep(1);
					}
				}
				sleep(1);
				$cmd = ':GC+SND:';
				$tmp = 'send to '.PORT.' '.$cmd;
				$responce = shell_exec($tmp);
				$responce = explode(":", $responce);
				$sms->getStatus();
				if ($responce[0] != 1) {
					throw new GatewayAPIException($responce[1], (int)$responce[0]);
				}
				return $responce;
			}else{
				throw new  GatewayAPIException("SMS Gateway denied your access because you are using an invalid API key.", 38);
			}
		}
		/**
		 * @return true|GatewayAPIException
		 *
		 * @throws GatewayAPIException
		 */
		function call($call){
			$cmd = $call->getProcessId().":ATD".$call->getPhone().";:EX:HLT2:ATH:";
			if ($this->validation) {
				$responce = shell_exec('send to '.PORT.' '.$cmd);
				$responce = explode(":", $responce);
				$call->getStatus();
				if ($responce[0] != 1) {
					throw new GatewayAPIException($responce[1], (int)$responce[0]);
				}
				return $responce;
			}else{
				throw new  GatewayAPIException("Gateway denied your access because you are using a invalid API key.", 51);
			}
		}
		function OTP(){
			$string = 'abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
			$string_shuffled = str_shuffle($string);
			$password = substr($string_shuffled, 1, 7);
			return $password;
		}
	}
//END OF API class
	include "../db.inc.php";
	try {
		if(isset($_POST['key']) && isset($_POST['phone']) && isset($_POST['message']) && isset($_POST['sms'])){
			if(!empty($_POST['key']) && !empty($_POST['phone']) && !empty($_POST['message'])){
				$otp = '0';
				$a = new Gateway($_POST['key']);
				$a->validate($conn);
				$last_ins_id = "0";
				if(isset($_POST['otp'])){
					$otp = $a->OTP();
					$_POST['message'] .= ' '.$otp;
				}
				try{
					$conn->beginTransaction();
					$query = $conn->prepare("INSERT INTO `sms_getway` (`user_id`, `ph`, `sms`, `time`) VALUES ( ?, ?, ?, ?);");
					$query2 = $conn->prepare("UPDATE `sms_getway` SET `status` = ? WHERE `sms_getway`.`id` = ?;");
					$query->execute(array($a->getClient(), $_POST['phone'], $_POST['message'], time()));
					$last_ins_id = $conn->lastInsertId();
					$sms = new SMS($last_ins_id, $_POST['message'], $_POST['phone']);
					$res = $a->sms($sms);
					array_push($res, $last_ins_id);
					array_push($res, $otp);
					$query2->execute(array($sms->getStatus(), $last_ins_id));
					echo json_encode($res);
					$conn->commit();
				}catch(PDOException $e){
					$conn->rollBack();
					throw new GatewayAPIException($e->getMessage(), 200);
				}catch(GatewayAPIException $e){
					$conn->rollBack();
					throw new GatewayAPIException($e->getMessage(), $e->getCode());
				}catch(Exception $e){
					$conn->rollBack();
					throw new GatewayAPIException($e->getMessage(), 200);
				}
				die();
				}else{
					throw new GatewayAPIException("Invalid parameters list for SMS.", 94);
				}
		}else if(isset($_POST['key']) && isset($_POST['phone']) && isset($_POST['call'])){
			if(!empty($_POST['key']) && !empty($_POST['phone'])){
				$a = new Gateway($_POST['key'], PORT);
				$a->validate($conn);
				try{
					$conn->beginTransaction();
					$query = $conn->prepare("INSERT INTO `sms_getway` (`user_id`, `ph`, `sms`, `time`) VALUES ( ?, ?, ?, ?);");
					$query2 = $conn->prepare("UPDATE `sms_getway` SET `status` = ? WHERE `sms_getway`.`id` = ?;");
					$query->execute(array($a->getClient(), $_POST['phone'], '', time()));
					$last_ins_id = $conn->lastInsertId();
					$call = new Call($last_ins_id, $_POST['phone']);
					$res = $a->call($call);
					array_push($res, $last_ins_id);
					$query2->execute(array($call->getStatus(), $last_ins_id));
					echo json_encode($res);
					$conn->commit();
				}catch(PDOException $e){
					$conn->rollBack();
					throw new GatewayAPIException($e->getMessage(), 248);
				}catch(GatewayAPIException $e){
					$conn->rollBack();
					throw new GatewayAPIException($e->getMessage(), $e->getCode());
				}catch(Exception $e){
					$conn->rollBack();
					throw new GatewayAPIException($e->getMessage(), 248);
				}
				die();
			}else{
				throw new GatewayAPIException("Invalid parameters list for Call.", 94);
			}
		}else if (isset($_POST['key']) && isset($_POST['id']) && isset($_POST['get'])) {
			if(!empty($_POST['key']) && !empty($_POST['id'])){
				$a = new Gateway($_POST['key'], PORT);
				$a->validate($conn);
				try{
					$conn->beginTransaction();
					$tmp =  'receive from '.PORT.' '.$_POST['id'];
					$status = shell_exec($tmp);
					if($status != "NOT_FOUND"){
						$query2 = $conn->prepare("UPDATE `sms_getway` SET `status` = ? WHERE `sms_getway`.`id` = ?;");
						$query2->execute(array($status, $_POST['id']));
					}
					echo json_encode($status);
					$conn->commit();
				}catch(PDOException $e){
					$conn->rollBack();
					throw new GatewayAPIException($e.getMessage(), 278);
				}catch(GatewayAPIException $e){
					$conn->rollBack();
					throw new GatewayAPIException($e->getMessage(), $e->getCode());
				}catch(Exception $e){
					$conn->rollBack();
					throw new GatewayAPIException($e.getMessage(), 278);
				}
				die();
			}else{
				throw new GatewayAPIException("Invalid parameter for fatching data.", 107);
			}
		}else{
			throw new GatewayAPIException("Invalid parameters list for fetching data for Gateway.", 360);
		}
	} catch (GatewayAPIException $e) {
		echo json_encode(array($e->getMessage(), $e->getCode()));
		die();
	}
?>