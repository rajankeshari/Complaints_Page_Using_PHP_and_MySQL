<?php
	class manage_field_calculation_model extends CI_Model{

		function __construct() {
        	parent::__construct();
   		}
   		function index()
   		{

   		}
   		function getEmpId($emp_no)
		{
			$myquery="select apd.EMPNO,apd.NAME from acc_pay_details_temp apd  where apd.EMPNO like '".$emp_no."%' limit 15";
			$query=$this->db->query($myquery);
			if($query->num_rows()>0)
				return $query->result();
		}
		function showStatus($data){
			$q="select aee.".$data['FIELD']." from acc_emp_eligiblity aee where aee.EMPNO=".$data['EMPNO'];
			if($query=$this->db->query($q)){
				if($query->num_rows()>0){
					//echo "Hello";die();
					return $query->result();
				}
				else{
					//echo"Not Available";die();
					return false;
				}
			}
			else{
				return false;
			}
		}
		function showAvaibality($data){
			$q="select aee.".$data['FIELD']." from acc_emp_eligiblity aee where aee.EMPNO=".$data['EMPNO']." and aee.".$data['FIELD']."=1";
			if($query=$this->db->query($q)){
				if($query->num_rows()>0){
					//echo "Hello";die();
					return $query->result();
				}
				else{
					//echo"Not Available";die();
					return false;
				}
			}
			else{
				return false;
			}
		}
		function updateStatus($data){
			$q="update acc_emp_eligiblity aee set aee.".$data['FIELD']."=? where aee.EMPNO=?";
			if($this->db->query($q,array($data['STAUTS'],$data['EMPNO']))){
					return true;
			}
			else{
				return false;
			}
		}

		function saveStatus($data){
			if($this->db->insert('acc_emp_eligiblity',$data)){
				return true;
			}
			else{
				return false;
			}
		}
		
		/*------------------------------This Function Returns list of employee number whose status against given field is set to YES-------------------*/
		function get_emp_no($field){
			$q="select aee.EMPNO from acc_emp_eligiblity aee where aee.".$field."=1";
			if($query=$this->db->query($q)){
				if($query->num_rows()>0){
					return $query->result_array();
				}
				else{
					return false;
				}
			}
			else{
				return false;
			}
		}

		function view_status($field){
			//echo "Hello";die();
			$q="select aee.EMPNO,concat(ud.salutation,' ',ud.first_name,' ',ud.middle_name,' ',ud.last_name) as name, case aee.$field when 1 then 'YES' else 'NO' end as $field from acc_emp_eligiblity aee left join emp_basic_details ebd on ebd.emp_no=aee.EMPNO left join user_details ud on ud.id=aee.EMPNO";

			//$q="select case  aee.$field when 1 then 'YES' else 'NO' end as $field from acc_emp_eligiblity aee left join emp_other_details eod ";
			if($query=$this->db->query($q)){
				if($query->num_rows()>0){
					return $query->result();
				}
				else{
					return false;
				}
			}
			else{
				return false;
			}
		}

   	}