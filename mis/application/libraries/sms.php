<?php if(!defined("BASEPATH")){ exit("No direct script access allowed"); }
	/**
	 * SMS Library
	 *
	 *
	 */

	class SMS 
	{
		private $msg ='';
		private $mobiles = [];
		private $senderId = '';
		private $userName = 'IITISM';
		private $pass = 'Abcd@123';
		private $errorNum = [];
		private $response = [];
		private $url ='http://193.105.74.58/api/v3/sendsms/xml';


		function __construct() {
		    $this->CI =& get_instance();
		    $this->CI->load->database();
		}
			
				// $number send in to array SenderId Must Be 6 charcter //
		function sendSMS($number,$msg,$senderId=''){
				$this->mobiles = $number; //array
				$this->msg = trim($msg);
				$this->senderId = ($senderId !='') ? $senderId : 'MISMIN'; 

				$this->ProcessMsg();
					$r['response'] = $this->xmlConvert2Array($this->response);
					$r['error'] = $this->errorNum;
				return $r;
		}
		
		private function ProcessMsg(){
					
					$xml = $this->CreateSMSBody();

			        //setting the curl parameters.
			        $ch = curl_init();
			        curl_setopt($ch, CURLOPT_URL, $this->url);

					// Following line is compulsary to add as it is:
			        curl_setopt($ch, CURLOPT_POSTFIELDS,"xmlRequest=" . $xml);

			        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 300);
			        $data = curl_exec($ch);
			        curl_close($ch);

			        //convert the XML result into array
			        $this->response = $data;
		}

		private function CreateSMSBody(){
			 $xml = '<SMS>  
					<authentication>   
					<username>'.$this->userName.'</username>   
					<password>'.$this->pass.'</password>  
					</authentication>  
					<message>   
					<sender>'.$this->senderId.'</sender>   
					<text>'.$this->msg.'</text>   
					<recipients>   
					'.$this->mobileNum().'
					</recipients>  </message> </SMS>';
					
					return $xml;
		}

		private function mobileNum(){

			return	$this->refineMobNum();

		}

		private function refineMobNum(){
			$num='';
			foreach($this->mobiles as $mob){
				if(strlen((string)$mob) >= 10){
						$num .= ((strlen((string)$mob) == 10) ? $this->addBeforNum($mob) : $this->bigNumberManage($mob));
				}else{
					$this->errorNum[]=$mob; 
				}
			}
				return $num ;
		}

		private function addBeforNum($mob){

			return "<gsm>".('91'.$mob)."</gsm>";
		} 

		private function bigNumberManage($mob){
			$this->errorNum[] = $mo;
			return '';
		}

		private function xmlConvert2Array($xmldata){
			$xmlparser = xml_parser_create();
			xml_parse_into_struct($xmlparser,$xmldata,$values);
			xml_parser_free($xmlparser);
			return $values;
		}

		/********************* OTP Handling*******************/
		//$user_id,$contact,$otp,$msg,$senderId=''
		function sendOTP($arr=''){
				$this->expireOTP();
				if(isset($arr['otp'])){
					$otp=$arr['otp'];
				}
				else{
					$otp=rand(1111,9999);
					$msg="Your OTP is $otp. Your OTP will expire in next 15 minuets.";
					//echo $msg;
				}
				if($this->insertOTP(array('user_id'=>$arr['user_id'],'otp'=>$otp))){
						$this->mobiles = [$arr['contact']]; //array
						$this->msg = trim($arr['msg']);
						$this->senderId = ($senderId !='') ? $senderId : 'IITISM'; 

						$this->ProcessMsg();
							$r['response'] = $this->xmlConvert2Array($this->response);
							$r['error'] = $this->errorNum;
						return $r;
				}
				
				
		}

		function insertOTP($arr=[]){
			$q="insert into otp_master (user_id,otp,create_time,expire_on) values(?,?,current_time(),timestampadd(MINUTE,15,current_time()))";
			if($this->CI->db->query($q,array($arr['user_id'],$arr['otp']))){
				return true;
			}
			else{
				return false;
			}
		}

		function matchOTP($arr=[]){
			//var_dump($arr);
			$this-> expireOTP();
			$q="select * from otp_master where user_id=? and otp=?";
			$query=$this->CI->db->query($q,array($arr['user_id'],$arr['otp']));
			//echo $this->CI->db->last_query();die();
			if($query->num_rows()>0){
				return true;
			}
			else{
				return false;
			}
		}

		function expireOTP(){
			$q="delete from otp_master where NOW()>expire_on";
			if($this->CI->db->query($q)){
				return true;
			}
			else{
				return false;
			}
		}

		function deleteOTP(){

		}
	}
?>