<?php

/**
 * Author: Anuj
*/
class Finance_electric_model extends CI_Model {
    
    

    function __construct() {
        parent::__construct();
    }

    function get_ac_status($ar=[]){
    	//echo $ar['empno']; die();
    	$myquery="select ac.* ,CONCAT(ud.salutation,' ',ud.first_name,' ',ud.middle_name,' ',ud.last_name) AS name, dp.name as department from acc_emp_ac_status ac inner join user_details ud on ac.empno=ud.id inner join departments dp on ud.dept_id=dp.id where 1=1";
    	if(isset($ar['id'])){
    		$myquery.=" and ac.id=".$ar['id'];
    	}
    	if(isset($ar['empno'])){
    		$myquery.=" and ac.empno=".$ar['empno'];
    	}
    	$query=$this->db->query($myquery);
    	//echo $this->db->last_query();die();
    	if($query->num_rows()>0){
    		return $query->result();
    	}
    	else{
    		return false;
    	}
    	
    }

    function get_ac_details($ar=[]){
        //echo $ar['empno']; die();
         $emp=$this->session->userdata('id');


          $myquery="select ac.* ,CONCAT(ud.salutation,' ',ud.first_name,' ',ud.middle_name,' ',ud.last_name) AS name, dp.name as department from acc_emp_ac_status ac inner join user_details ud on ac.empno=ud.id inner join departments dp on ud.dept_id=dp.id where ac.empno=".$emp;

    
        if(isset($ar['id'])){
            $myquery.=" and ac.id=".$ar['id'];
        }
        if(isset($ar['empno'])){
            $myquery.=" and ac.empno=".$ar['empno'];
        }
        $query=$this->db->query($myquery);
        //echo $this->db->last_query();die();
        if($query->num_rows()>0){
            return $query->result();
        }
        else{
            return false;
        }                   
     
        
    }

    
    
    public function count_all()
    {
        $this->db->from('acc_emp_ac_status');
        return $this->db->count_all_results();
    }

    function edit_acc_emp_ac_master($new){
    	/*****************Geting Date for backup********************/
    	$this->db->where('id',$new['id']);
    	$query=$this->db->get('acc_emp_ac_status');
    	$result=$query->result()[0];
    	$result->operation='E';
    	$result->operation_on=$new['created_on'];
    	/*************--Updating acc_emp_ac_status--****************/
    	$this->db->where('id',$new['id']);
    	$this->db->update('acc_emp_ac_status',$new);
    	//echo $this->db->last_query();die();
    	/***********--Inserting data in acc_emp_ac_status_backup--*********/
    	$this->db->insert('acc_emp_ac_status_backup',$result);
    }

  	function delete_acc_emp_ac_master($id){
  		$this->db->where('id',$id);
    	$query=$this->db->get('acc_emp_ac_status');
    	$result=$query->result()[0];
    	$result->operation='D';
    	$result->operation_on=$new['created_on'];
    	$result->operated_by=$this->session->userdata('id');
    	/*************--Updating acc_emp_ac_status--****************/
    	$this->db->where('id',$id);
    	$this->db->delete('acc_emp_ac_status');
    	//echo $this->db->last_query();die();
    	/***********--Inserting data in acc_emp_ac_status_backup--*********/
    	$this->db->insert('acc_emp_ac_status_backup',$result);
  	}
    
    function get_acc_ac_master_data($ar=[]){
    	if(isset($ar['id'])){
    		$this->db->where('id',$ar['id']);
    	}
    	$query=$this->db->get('acc_ac_master');
    	//echo $this->db->last_query();die();
    	if($query->num_rows()>0){
    		return $query->result();
    	}
    	else{
    		return false;
    	}
    }
    function update_ac_master($data){
    	//var_dump($data);die();
    	$this->db->where('id',$data['id']);
    	$query=$this->db->get('acc_ac_master');
    	$result=$query->result()[0];

    	$this->db->where('id',$data['id']);
    	if($this->db->update('acc_ac_master',$data)){
    		$data['operation']="E";
    		$data['operation_on']=$data['created_on'];
    		$data['operated_by']=$this->session->userdata('id');
    		//echo $this->db->last_query();
    		$this->db->insert('acc_ac_master_backup',$data);
    		//var_dump($data);die();
    		return true;
    	}
    	else{
    		return false;
    	}
    }

    function delete_acc_ac_master($id){
    	$this->db->where('id',$id);
    	$query=$this->db->get('acc_ac_master');
    	$result=$query->result()[0];
    	$result->operation='D';
    	$result->operation_on=$new['created_on'];+
    	$result->operated_by=$this->session->userdata('id');
    	/*************--Updating acc_emp_ac_status--****************/
    	$this->db->where('id',$id);
    	$this->db->delete('acc_ac_master');
    	//echo $this->db->last_query();die();
    	/***********--Inserting data in acc_emp_ac_status_backup--*********/
    	$this->db->insert('acc_ac_master_backup',$result);
    }

    function get_electric_bill(){
    	$myquery="select acu.*, concat(ud.salutation,' ',ud.first_name,' ',ud.middle_name,' ',ud.last_name) as name,(select dp.name from departments dp where dp.id=ud.dept_id) as department from acc_elect_unit acu inner join user_details ud on ud.id=acu.empno";
		$query=$this->db->query($myquery);
		if($query->num_rows()>0){
			return $query->result();
		}
		else{
			return false;
		}
    }

    function get_electic_bill_temp(){
    	$myquery="select acu.*, concat(ud.salutation,' ',ud.first_name,' ',ud.middle_name,' ',ud.last_name) as name,(select dp.name from departments dp where dp.id=ud.dept_id) as department from acc_elect_unit_temp acu inner join user_details ud on ud.id=acu.empno";
		$query=$this->db->query($myquery);
		if($query->num_rows()>0){
			return $query->result();
		}
		else{
			return false;
		}
    }

    function get_not_electric_bill(){
    	$myquery="select ud.id, concat(ud.salutation,' ',ud.first_name,' ',ud.middle_name,' ',ud.last_name) as name,(select dpt.name from departments dpt where dpt.id=ud.dept_id) as department from emp_basic_details ebd left join user_details ud on ud.id=ebd.emp_no where ebd.emp_no not in (select empno from acc_elect_unit_temp)";
		$query=$this->db->query($myquery);
		if($query->num_rows()>0){
			return $query->result();
		}
		else{
			return false;
		}
    }

    function get_elect_to_merge(){
    	$ar=array('empno','tot_amt');
    	$this->db->select($ar);
    	$query=$this->db->get('acc_elect_unit_temp');
    	if($query->num_rows()>0){
    		return $query->result();
    	}
    	else{
    		return false;
    	}
    }

    function megre_electric_bill($data){
    	if($this->reset_echarge()){
    		foreach($data as $r){
    			$this->db->set('echarge',$r->tot_amt);
    			$this->db->where('empno',$r->empno);
    			$this->db->update("acc_pay_details_temp");
    			//echo $this->db->last_query()."<br>";
    		}
    	}

    }

    function reset_echarge(){
    	$this->db->set('echarge',0);
    	if($this->db->update('acc_pay_details_temp')){
    		return true;
    	}
    	else{
    		return false;
    	}
    }

    function flush_electric_charge(){
    	$this->copy_electric_bill();
    	$myquery="delete from acc_elect_unit_temp";
    	if($this->db->query($myquery)){
    		return true;
    	}
    	else{
    		return false;
    	}
    }

    function copy_electric_bill(){
    	$myquery="select act.year,act.month,act.empno,act.unit,act.amount,act.ac_charge,act.tot_amt,act.unit_date,act.userid,act.created_on from acc_elect_unit_temp act";
    	$query=$this->db->query($myquery);
    	$result=$query->result();
    	/*-----Inserting data into acc_elect_unit------*/
    	foreach($result as $r){
    		$this->db->insert('acc_elect_unit',$r);
    		//echo $this->db->last_query()."<br>";
    	}
    	//echo $this->db->last_query();
    	//var_dump($result);die();
    }

    function delete_electric_units($id){
        $this->db->where('id',$id);
        $this->db->delete("acc_elect_unit_temp");
    }
}