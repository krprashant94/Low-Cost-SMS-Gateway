<?php
	define('RED_MORUS', "http://".$_SERVER['HTTP_HOST']."/dtdc");
	include "../../Core/core.inc.php";
	if(!isset( $_SESSION['s_id'])){ header("Location: ".RED_MORUS."/login/"); }
	if(!isset($_SERVER['HTTP_REFERER'])){ die();}
	class __booking{
		public
		function assign(){
			$this->cn = trim($_POST['cn']);
			$this->mode = $_POST['mode'];
			$this->destnation = trim($_POST['destnation']);
			$this->weaght = (float)$_POST['weaght'];
			$this->type = $_POST['type'];
			$this->pay = $_POST['pay'];

			$this->from = $_POST['from'];
			$this->from_ph = $_POST['from_ph'];
			$this->from_pin = $_POST['from_pin'];
			$this->to = $_POST['to'];
			$this->to_ph = $_POST['to_ph'];
			$this->to_pin = $_POST['to_pin'];

			$this->cot_id = $_POST['cot_id'];

			$this->amount = (float)$_POST['amount'];
			$this->risk = (float)$_POST['risk'];
			$this->tax = (float)$_POST['tax'];
			$this->other = (float)$_POST['other'];
			$this->total = $this->amount+$this->risk+$this->tax+$this->other;
			
			$this->booked_by = $_SESSION['s_id'];
			$this->time = time()+3*60*60+30*60; /* time Zone +3:30 change also status/booking.php*/
			$this->bill_month = date('n/Y', $this->time);
			$this->referer = $_SERVER['HTTP_REFERER'];
		}
		function correct(){
			$this->cn = strtoupper($this->cn);
			$this->mode = strtolower($this->mode);
			$this->type = strtolower($this->type);
			$this->destnation = strtolower($this->destnation);
			$this->destnation[0] = strtoupper($this->destnation[0]);
			$this->pay = strtolower($this->pay);
			if ($this->pay == 'credit') {
				$this->amount = 0;
				$this->risk = 0;
				$this->tax = 0;
				$this->other = 0;
				$this->total = 0;
			}
		}
	}
	$ref = 'empty_field';
	if(isset($_POST['cn']) && isset($_POST['destnation']) && isset($_POST['weaght']) && isset($_POST['type']) && isset($_POST['pay']) && isset($_POST['from_pin']) && isset($_POST['to_pin'])){
		$book = new __booking();
		$book->assign();
		if(!empty($book->cn) && !empty($book->destnation) && !empty($book->weaght) && !empty($book->type) && !empty($book->pay) && !empty($book->from_pin) && !empty($book->to_pin)){
			$book->correct();
			if(!in_array($book->mode, array('not', 'surface', 'air'))){
				$ref = 'mode_error';
			}elseif(!in_array($book->type, array('dox', 'non_dox'))){
				$ref = 'type_error';
			}elseif($book->pay == 'credit' && empty($book->cot_id)){
				$ref = 'cot_id_empty';
			}elseif($book->pay == 'cash' && empty($book->amount)){
				$ref = 'amount_error';
			}elseif($book->pay == 'cash' && $book->amount < 35){
				$ref = 'amount_error';
			}else{
				include "../../Class/db.php";
				$query = $conn->prepare("
					INSERT INTO `"._DTDC_BOOKING_."`.`dtdc_booking` ( `id`, `cot_id`, `months`, `cn_no`, `time`, `dest`, `from`, `from_pin`, `from_ph`, `to`, `to_pin`, `to_ph`, `weaght`, `type`, `method`, `pay`, `amount`, `risk`, `service_tex`, `other`, `total`, `book_by`, `referer` )
					VALUES ( NULL , ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ? );
				");
				try{
					$conn->beginTransaction();
					$query->execute(array($book->cot_id, $book->bill_month, $book->cn, $book->time, $book->destnation, $book->from, $book->from_pin, $book->from_ph, $book->to, $book->to_pin, $book->to_ph, $book->weaght, $book->type, $book->mode, $book->pay, $book->amount, $book->risk, $book->tax, $book->other, $book->total, $book->booked_by, $book->referer ));
					$conn->commit();
					$ref = 'booking_success';
				}catch(PDOException $e){
					$conn->rollBack();
					$ref = $e.getMessage();
				}
			}
		}else{
			$ref = 'empty_field';
		}
	}
	echo json_encode($ref);
?>