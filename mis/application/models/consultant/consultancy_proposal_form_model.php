<?php

class Consultancy_proposal_form_model extends CI_Model
{

	var $table1 = 'consultancy_client_details';
	//var $table2 = 'consultancy_schedule';
	//var $table3 = 'consultancy_details';
	var $table = 'consultancy_proposal_details';

	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}

	
	function insert1($data1,$data4,$payment_no)
	{
		
		if($payment_no==1)
		{
			$this->db->insert($this->table1,$data1);
			$this->db->where('sr_no',$data4['sr_no']);
			$this->db->insert($this->table,$data4);
		}
		else
		{
			$this->db->where('sr_no',$data1['sr_no']);
			$this->db->update($this->table1,$data1);
			$this->db->insert($this->table,$data4);
		}
		
		
	}
	function update_by_pce($data,$payment_no)
	{
		 
                          	$sr_no  =   $data['sr_no'];
                            $file = $data['file_path'];
                            $payment_mode = $data['payment_mode'];
                            $currency =  $data['currency'];
                            $currency_type = $data['currency_type'];
                            $payment_enclosed = $data['payment_enclosed'];
                            $value_fig  = $data['value_fig'];
                            $value_word =  $data['value_word'];
                            $bank_name =   $data['bank_name'];
                            $dd_cheque_no =   $data['dd_cheque_no'];
                            $dd_cheque_amt =  $data['dd_cheque_amt'];
                            $dd_cheque_date =  $data['dd_cheque_date'];
                            print_r($data);
		$this->db->query("UPDATE consultancy_proposal_details set  file_path = '$file', payment_mode='$payment_mode',currency='$currency',									currency_type='$currency_type',payment_enclosed='$payment_enclosed',value_fig='$value_fig',
							value_word='$value_word',bank_name='$bank_name',dd_cheque_no='$dd_cheque_no',dd_cheque_amt='$dd_cheque_amt',
							dd_cheque_date = '$dd_cheque_date' where sr_no = '$sr_no'");
	}
	function insertType($data5,$data6,$payment_no)
	{
		if($payment_no==1)
		{
			$this->db->insert('consultancy_testing_type',$data5);
			$this->db->insert('consultancy_client_type',$data6);
		}
		else
		{
			$this->db->where('sr_no',$data5['sr_no']);
			$this->db->update('consultancy_testing_type',$data5);
			$this->db->where('sr_no',$data6['sr_no']);
			$this->db->update('consultancy_client_type',$data6);
		}
	}
	function update($data1,$data4,$data5,$data6,$payment_no)
	{
		$this->db->where('sr_no',$data1['sr_no']);
		$this->db->update($this->table1,$data1);

		$this->db->where('sr_no',$data5['sr_no']);
		$this->db->update('consultancy_testing_type',$data5);

		$this->db->where('sr_no',$data6['sr_no']);
		$this->db->update('consultancy_client_type',$data6);

		$this->db->where('sr_no',$data4['sr_no']);
		$this->db->where('payment_no',$data4['payment_no']);
		$this->db->update($this->table,$data4);
	}
	function insertM($sr_no,$payment_no)
	{
		/*insert into modification table
		/*table= client*/
		$table = 'consultancy_client_details_modification';
		$query = $this->db->where(array('sr_no'=>$sr_no))->get($this->table1);
		if($query->num_rows() == 0 ) return FALSE;
		else $ans = $query->row_array();
		$query1=$this->db->query("SELECT max(modification_value) as max
						FROM consultancy_client_details_modification 
						where sr_no='$sr_no';") ;
		$query1=$query1->result();
		if(!is_null($query1) && $ans['modification_value'] == $query1[0]->max )
		{
		  $this->db->where('sr_no',$sr_no);
		  $this->db->where('modification_value',$query1[0]->max);
		  $this->db->update($table,$ans);
		}
		else
		$this->db->insert($table, $ans);
		
		/*table= testing type*/
		$table = 'consultancy_testing_type_modification';
		$query = $this->db->where(array('sr_no'=>$sr_no))->get('consultancy_testing_type');
		if($query->num_rows() == 0 ) return FALSE;
		else $ans = $query->row_array();
		$query1=$this->db->query("SELECT max(modification_value) as max
						FROM consultancy_testing_type_modification
						where sr_no='$sr_no';") ;
		$query1=$query1->result();
		if(!is_null($query1) && $ans['modification_value'] == $query1[0]->max )
		{
		  $this->db->where('sr_no',$sr_no);
		  $this->db->where('modification_value',$query1[0]->max);
		  $this->db->update($table,$ans);
		}
		else
		$this->db->insert($table, $ans);

		/*table=client_type*/

		$table = 'consultancy_client_type_modification';
		$query = $this->db->where(array('sr_no'=>$sr_no))->get('consultancy_client_type');
		if($query->num_rows() == 0 ) return FALSE;
		else $ans = $query->row_array();
		$query1=$this->db->query("SELECT max(modification_value) as max
						FROM consultancy_client_type_modification 
						where sr_no='$sr_no';") ;
		$query1=$query1->result();
		if(!is_null($query1) && $ans['modification_value'] == $query1[0]->max )
		{
		  $this->db->where('sr_no',$sr_no);
		  $this->db->where('modification_value',$query1[0]->max);
		  $this->db->update($table,$ans);
		}
		else
		$this->db->insert($table, $ans);

		/*table=proposal_detail*/

		$table = 'consultancy_proposal_detail_modification';
		$query = $this->db->where(array('sr_no'=>$sr_no,'payment_no'=>$payment_no))->get($this->table);
		
		if($query->num_rows() == 0 ) return FALSE;
		else $ans = $query->row_array();
		
		$query1=$this->db->query("SELECT max(modification_value) as max
						FROM consultancy_proposal_detail_modification 
						where sr_no='$sr_no'
						AND payment_no='$payment_no';") ;
		$query1=$query1->result();
		
		if(!is_null($query1) && $ans['modification_value'] == $query1[0]->max )
		{
			$this->db->where('sr_no',$sr_no);
			$this->db->where('payment_no',$payment_no);
			$this->db->where('modification_value',$query1[0]->max);
			$this->db->update($table,$ans);
		}
		else
		$this->db->insert($table, $ans);
}
	

	function get_client($consultancy_no)
	{
		$this->db->select();
		$this->db->where('consultancy_no',$consultancy_no);
		$query1=$this->db->get($this->table1);
		return $query1->row();
	}
	function get_default($id_user)
	{

		$query=("SELECT user_details.*,
				user_address.contact_no AS contact_no, departments.name AS 
				dept_name, emp_basic_details.emp_no AS emp_no, emp_basic_details.designation AS 
				designation_id, designations.id AS desig_id, designations.name AS designation
				FROM user_details
				INNER JOIN user_address ON user_details.id = user_address.id
				INNER JOIN departments ON user_details.dept_id = departments.id
				INNER JOIN emp_basic_details ON user_details.id = emp_basic_details.emp_no
				INNER JOIN designations ON emp_basic_details.designation = designations.id
				WHERE user_details.id = '$id_user'");
		$query1=$this->db->query($query);

		return $query1->result();
	}
	function get_max_cl_no()
	{
		$this->db->select_max('consultancy_no');
		$query = $this->db->get($this->table);
		return $query->row();
	}
	function checkdone($sr_no)
	{
		$query2=$this->db->query("SELECT * FROM consultancy_proposal_details WHERE consultancy_proposal_details.sr_no='$sr_no';")->result();
		$sum=0;
		
		$total_val=-5;
		foreach ($query2 as $key => $val) {
		$sum+=$val->dd_cheque_amt;
		$total_val=$val->value_fig;	
		
		}
		
		if($sum==$total_val) return true;
		else return false;

	}
	function is_payment($sr_no)
	{
		$this->db->select('consultancy_no');
		$this->db->where('sr_no',$sr_no);
		$query = $this->db->get($this->table);
		return $query->row();
	}
	function get_max_payment_no($sr_no)
	{
		$this->db->select_max('payment_no');
		$this->db->where('sr_no',$sr_no);
		$query = $this->db->get($this->table);
		return $query->row();
	}
	function insert_mode_payment($sr_no,$payment_no,$choice)
	{
		$this->db->query("INSERT INTO consultancy_payment_mod values('$sr_no','$payment_no','$choice')");

	}
	function get_mode_payment($sr_no,$payment_no)
	{
		$query1=$this->db->query("SELECT * from consultancy_payment_mod where sr_no = '$sr_no' and payment_no = '$payment_no'");
		return $query1->row();
	}


}

/* End of file post_notice_model.php */
/* Location: mis/application/models/post_notice_model.php */