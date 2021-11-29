<?php

class Change_basic_model extends CI_Model
{
	function __construct(){
		parent::__construct();
	}

	function get_previous_basic(){
		//echo "Hello";die();
		//$myquery="select ad.EMPNO,ad.NAME,(select dp.name from departments dp where dp.id=ad.DEPT) as dept,ad.BASIC,ad.GRPAY from acc_pay_details_temp ad";
		$myquery="SELECT ad.EMPNO,ad.NAME,ad.BASIC,ad.GRPAY,ucase(dpt.name) as dept,UCASE(dsg.name) as desig
			FROM acc_pay_details_temp ad
			left join user_details ud on ud.id=ad.EMPNO
			left join emp_basic_details ebd on ebd.emp_no=ad.EMPNO
			left join departments dpt on dpt.id=ud.dept_id
			left join designations dsg on dsg.id=ebd.designation";
		$query=$this->db->query($myquery);
		if($query->num_rows()>0){
			return $query->result();
		}
		else{
			return false;
		}
		//echo $this->db->last_query();die();
	}

	function get_old_basic_grade(){
		//$myquery="select ad.EMPNO,ad.NAME,(select dp.name from departments dp where dp.id=ad.DEPT) as dept,ad.BASIC,ad.GRPAY from acc_pay_details_temp ad";
		$myquery="SELECT ad.EMPNO,ad.NAME,ad.BASIC,ad.GRPAY,ucase(dpt.name) as dept,UCASE(dsg.name) as desig
FROM acc_pay_details_temp ad
left join user_details ud on ud.id=ad.EMPNO
left join emp_basic_details ebd on ebd.emp_no=ad.EMPNO
left join departments dpt on dpt.id=ud.dept_id
left join designations dsg on dsg.id=ebd.designation";
		$query=$this->db->query($myquery);
		if($query->num_rows()>0){
			return $query->result();
		}
		else{
			return false;
		}
	}

	function insert_new_basic_temp($data){
		$myquery="delete from acc_new_bpay where 1=1";
		$this->db->query($myquery);
		//$myquery="insert into acc_new_bpay (EMPNO,) "
		foreach ($data as $r) {
			$this->db->insert('acc_new_bpay',$r);
			//echo $this->db->last_query();
		}
		

	}

	function get_new_basic(){
		$query=$this->db->get('acc_new_bpay');
		if($query->num_rows()>0){
			return $query->result();
		}
		else{
			return false;
		}
	}

	function update_new_basic($data){
		$myquery="select max(CN)+1 as cn from acc_new_bpay_bkp";
		$query=$this->db->query($myquery);
		//echo $this->db->last_query();die();
		$cn;
		if($query){
			if($query->num_rows()>0){
				$result=$query->result();
				//var_dump($result);die();
				$cn=$result[0]->cn;
			}
			else{
				$cn=1;
			}
		}
		else{
			$cn=1;
		}
		//echo $cn;die();
		foreach ($data as $r) {
			$this->db->set('BASIC',$r->NEW_BPAY);
			$this->db->where('EMPNO',$r->EMPNO);
			$this->db->update('acc_pay_details_temp');
			//echo $this->db->last_query()."<br>";
			$r->CN=$cn;
		}
		foreach ($data as $r) {
			$this->db->insert('acc_new_bpay_bkp',$r);
			//echo $this->db->last_query();
		}
				
	}
}

?>