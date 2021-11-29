<?php

class Consultant_form extends CI_Model
{

	var $table = 'consultancy_charges';

	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}
	function update($data)
	{
		$this->db->where('sr_no',$data['sr_no']);
		$this->db->update($this->table,$data);
	}
	
	function insert($data)
	{
		$this->db->insert($this->table,$data);
	}
	function insertM($data)
	{
		$this->db->insert('consultancy_member',$data);
	}
	function insertMTemp($sr_no,$modv)
	{
		$query=$this->db->query("UPDATE consultancy_member set modification_value='$modv'  Where sr_no='$sr_no';");
		$query=$this->db->query("DELETE FROM consultancy_member_temp  Where sr_no='$sr_no';");
		$query = $this->db->where('sr_no',$sr_no)->get('consultancy_member');
		$query1=$query->result();
		
			
		foreach($query1 as $data)
		{
			$this->db->insert('consultancy_member_temp',$data);
		}
	}
	function insertRMTemp($sr_no)
	{
		$query=$this->db->query("DELETE FROM consultancy_member  Where sr_no='$sr_no';");
		
		$query = $this->db->where('sr_no',$sr_no)->get('consultancy_member_temp');
		$query1=$query->result();
		
		foreach($query1 as $data)
		{
			$this->db->insert('consultancy_member',$data);
		}
	}
	function insertEMTemp($sr_no)
	{
		$query = $this->db->where('sr_no',$sr_no)->get('consultancy_member_temp');
		$query1=$query->result();
		foreach($query1 as $data)
		{
			$this->db->insert('consultancy_member_modification',$data);
		}
	}
	function editC($share1,$sr_no)
	{
		$this->db->query("UPDATE consultancy_member set share='$share1' Where 
							sr_no='$sr_no' and position='ci';");
	}

	function insertedM($sr_no)
	{
		$table = 'consultancy_member_modification';
		$query = $this->db->where('sr_no',$sr_no)->get('consultancy_member');
		
		if($query->num_rows() == 0 ) return FALSE;
		else $ans1 = $query->result_array();
		
		$query1=$this->db->query("SELECT max(modification_value) as max
						FROM consultancy_member_modification 
						where sr_no='$sr_no'") ;
		$query1=$query1->result();
		foreach($ans1 as $ans)
		{
			if(!is_null($query1) && $ans['modification_value'] == $query1[0]->max )
			{	
				$this->db->where('sr_no',$sr_no);
				$this->db->where('modification_value',$query1[0]->max);
				$this->db->update($table,$ans);
			}
			else
			$this->db->insert($table, $ans);
		}
		

	}
	function insertEdited($sr_no)
	{
		$table = 'consultancy_charges_modification';
		$query = $this->db->where('sr_no',$sr_no)->get($this->table);
		
		if($query->num_rows() == 0 ) return FALSE;
		else $ans = $query->row_array();
		
		$query1=$this->db->query("SELECT max(modification_value) as max
						FROM consultancy_charges_modification 
						where sr_no='$sr_no'") ;
		$query1=$query1->result();
		
		if(!is_null($query1) && $ans['modification_value'] == $query1[0]->max )
		{
			$this->db->where('sr_no',$sr_no);
			$this->db->where('modification_value',$query1[0]->max);
			$this->db->update($table,$ans);
		}
		else
		$this->db->insert($table, $ans);

	}	
	function get_max_sr_no()
	{
		$this->db->select_max('sr_no');
		$query = $this->db->get($this->table);
		return $query->row();
	}
	function getdetail($sr_no)
	{
		$this->db->select();
		$this->db->where('sr_no',$sr_no);
		$query = $this->db->get($this->table);
		return $query->row();

	}
	function getdetail_all()
	{
		$this->db->select();
		$query = $this->db->get($this->table);
		return $query->result();
	}
	function getdetailprev($sr_no)
	{
		$this->db->select();
		$this->db->where('sr_no',$sr_no);
		$query = $this->db->get('consultancy_charges_modification');
		return $query->result();
	}
	function getdetailprevone($sr_no,$modv)
	{
		$this->db->select();
		$this->db->where('sr_no',$sr_no);
		$this->db->where('modification_value',$modv);
		$query = $this->db->get('consultancy_charges_modification');
		return $query->row();
	}
	function getAllConsult($id='')
	{
		
		if($id!='')
			{
				$query=$this->db->query("SELECT consultancy_charges.*
								 FROM consultancy_charges     
                                INNER JOIN consultancy_member
                                ON consultancy_charges.sr_no=consultancy_member.sr_no
                                 WHERE consultancy_member.emp_no='$id' AND consultancy_member.position='ci'
                                ORDER BY  consultancy_charges.sr_no asc;");
        			return $query->result();
			}
		else
			{

				$query=$this->db->query("SELECT consultancy_charges.*
								 FROM consultancy_charges     
                                INNER JOIN consultancy_member
                                ON consultancy_charges.sr_no=consultancy_member.sr_no
                                 WHERE consultancy_member.position='ci'
                                ORDER BY  consultancy_charges.sr_no asc;");
        			return $query->result();
			}
	}
	function getAllConsult1($id='')
	{
		
		if($id!='')
			{
				$query=$this->db->query("SELECT consultancy_charges.*,cons.*
								 FROM consultancy_charges     
                                INNER JOIN consultancy_member
                                ON consultancy_charges.sr_no=consultancy_member.sr_no
                                LEFT JOIN 
                                (SELECT DISTINCT consultancy_no  , sr_no
                                	FROM consultancy_proposal_details
                                	) as  cons
                                ON consultancy_charges.sr_no=cons.sr_no
                                 WHERE consultancy_member.emp_no='$id' AND consultancy_member.position='ci'
                                ORDER BY  consultancy_charges.sr_no asc;");
        			return $query->result();
			}
		else
			{

				$query=$this->db->query("SELECT consultancy_charges.*,cons.*
								 FROM consultancy_charges     
                                INNER JOIN consultancy_member
                                ON consultancy_charges.sr_no=consultancy_member.sr_no
                               	LEFT JOIN 
                                (SELECT DISTINCT consultancy_no  , sr_no
                                	FROM consultancy_proposal_details
                                	) as  cons
                                ON consultancy_charges.sr_no=cons.sr_no
                                 WHERE consultancy_member.position='ci'
                                ORDER BY  consultancy_charges.sr_no asc;");
        			return $query->result();
			}
	}

	function getAllConsultHod($dept_id)
	{
		
		$query=$this->db->query("SELECT consultancy_charges.*
								 FROM consultancy_charges     
                                INNER JOIN consultancy_member
                                ON consultancy_charges.sr_no=consultancy_member.sr_no
                                INNER JOIN user_details
                                 ON consultancy_member.emp_no=user_details.id
                                 WHERE consultancy_member.position='ci'
                                 AND user_details.dept_id='$dept_id' 
                                ORDER BY  consultancy_charges.sr_no asc;");
        			return $query->result();
	}
	function getAllConsultHod1($dept_id)
	{
		
		$query=$this->db->query("SELECT consultancy_charges.*,cons.*
								 FROM consultancy_charges     
                                INNER JOIN consultancy_member
                                ON consultancy_charges.sr_no=consultancy_member.sr_no
                                INNER JOIN 
                                (SELECT DISTINCT consultancy_no  , sr_no
                                	FROM consultancy_proposal_details
                                	) as  cons
                                ON consultancy_charges.sr_no=cons.sr_no
                                INNER JOIN user_details
                                 ON consultancy_member.emp_no=user_details.id
                                 WHERE consultancy_member.position='ci'
                                 AND user_details.dept_id='$dept_id' 
                                ORDER BY  consultancy_charges.sr_no asc;");
        			return $query->result();
	}
	function getmember($sr_no)
	{
		
    $query=$this->db->query("SELECT consultancy_member.*,
								 user_details.* ,departments.name as dept,designations.name as designation
                                FROM consultancy_member 
                                INNER JOIN  user_details
                                ON user_details.id=consultancy_member.emp_no
                                INNER JOIN emp_basic_details
                                ON emp_basic_details.emp_no=user_details.id
                                INNER JOIN departments 
                                ON departments.id=user_details.dept_id
                                INNER JOIN designations
                                ON designations.id=emp_basic_details.designation
                                WHERE consultancy_member.sr_no='$sr_no'
                                ORDER BY consultancy_member.position asc;");
     
        return $query->result();
	}
	function getprevmember($sr_no,$modv)
	{
		$query=$this->db->query("SELECT consultancy_member_modification.*,
								 user_details.* ,departments.name as dept,designations.name as designation
                                FROM consultancy_member_modification 
                                INNER JOIN  user_details
                                ON user_details.id=consultancy_member_modification.emp_no
                                INNER JOIN emp_basic_details
                                ON emp_basic_details.emp_no=user_details.id
                                INNER JOIN departments 
                                ON departments.id=user_details.dept_id
                                INNER JOIN designations
                                ON designations.id=emp_basic_details.designation
                                WHERE consultancy_member_modification.sr_no='$sr_no'
                                AND consultancy_member_modification.modification_value='$modv'
                                ORDER BY consultancy_member_modification.position asc;");
        return $query->result();
	}
	function getc_i($sr_no)
	{
		$this->db->select();
		$this->db->where('sr_no',$sr_no);
		$this->db->where('position','ci');
		$query = $this->db->get('consultancy_member');
		return $query->row();
	}
	function approve($sr_no,$status)
	{
		
		$this->db->query("UPDATE consultancy_charges set status='$status' where sr_no='$sr_no';");


	}
	function getdetail_array($sr_no)
	{
		
		$this->db->select();
		$this->db->where('sr_no',$sr_no);
		$query = $this->db->get($this->table);
		$data=$query->row_array();
		$this->db->select();
		$this->db->where('sr_no',$sr_no);
		$this->db->where('position','ci');
		$query = $this->db->get('consultancy_member');
	
		$data['c_i']=$query->row()->emp_no;
		return $data;

	}
	function delete_member($sr_no,$emp_no='')
	{ 
		
		if($emp_no=='')
		$query=$this->db->query("DELETE FROM consultancy_member WHERE sr_no='$sr_no'");
		else
		{
			$query=$this->db->query("DELETE FROM consultancy_member WHERE sr_no='$sr_no' AND emp_no='$emp_no'");
		
		}
		
	}
	function edit_member($share1,$sr_no,$emp_no='')
	{ 
		
		
		$query=$this->db->query("UPDATE consultancy_member set share='$share1' WHERE sr_no='$sr_no' AND emp_no='$emp_no'");
		
		
		
	}
	function get_data_action($sr_no)
	{

		$query=$this->db->query("SELECT consultancy_charges.*,consultancy_form_track.*
								 FROM consultancy_charges     
                                INNER JOIN consultancy_form_track
                                ON consultancy_charges.sr_no=consultancy_form_track.sr_no
                               WHERE  consultancy_charges.sr_no='$sr_no'
                                ORDER BY  consultancy_form_track.timestamp desc;");
        			return $query->result();
	}
	function add_action($sr_no,$remark,$status,$auth,$date)
	{
		 $remark=addslashes($remark);
		$query=$this->db->query("INSERT INTO consultancy_form_track(sr_no,remark,status,auth,timestamp) values('$sr_no','$remark','$status','$auth','$date')");
                            
	}
	function get_last_action($sr_no,$payment_no=0)
	{
		$query=$this->db->query("SELECT consultancy_form_track.timestamp,consultancy_form_track.remark,								consultancy_form_track.status,consultancy_form_track.auth
								 FROM consultancy_form_track
								 WHERE timestamp = (SELECT max(consultancy_form_track.timestamp)
								 FROM consultancy_form_track
                              	 WHERE  consultancy_form_track.sr_no='$sr_no' 
                              	 AND consultancy_form_track.payment_no = '$payment_no') 
                                ");
        			return $query->row();
	}
	function get_no_payment()
	{
		$query=$this->db->query("SELECT count(*) as no,sr_no
								 FROM consultancy_proposal_details     
                                GROUP BY sr_no
                                ORDER BY  sr_no desc;");
        return $query->result();
	}
	function get_old_status($sr_no,$modv)
	{
		$query=$this->db->query("SELECT *
								 FROM consultancy_charges_modification
								 WHERE consultancy_charges_modification.sr_no='$sr_no'
								 AND consultancy_charges_modification.modification_value='$modv';");
        return $query->row()->status;
	}
	function allot_cons($sr_no)
	{
		print_r($sr_no);
		$query=$this->db->query("SELECT max(consultancy_no) as no,sr_no
								 FROM consultancy_proposal_details     
                                ");
		$cons=$query->row()->no+1;
		$query=$this->db->query("INSERT into consultancy_proposal_details (sr_no,consultancy_no) 
									values('$sr_no',$cons)   
                                ");


	}
	function insert_link_up_no($sr_no,$link_up,$page_no)
	{
		$date = date("Y-m-d H:i:s");
		$query = $this->db->query("INSERT into consultancy_link_up_no values('$sr_no','$link_up','$page_no','$date')");
	}
	function edit_link_up_no($sr_no,$link_up,$page_no)
	{
		$date = date("Y-m-d H:i:s");
		$query = $this->db->query("UPDATE  consultancy_link_up_no set link_up_no='$link_up' , page_no = '$page_no',timestamp='$date' where  sr_no ='$sr_no'");
	}
	function get_link_up_no($sr_no)
	{
		$date = date("Y-m-d H:i:s");
		$query = $this->db->query("SELECT * from consultancy_link_up_no where sr_no='$sr_no'");
			return $query->row();
	}
	function get_link_up_no_all()
	{
		$date = date("Y-m-d H:i:s");
		$query = $this->db->query("SELECT consultancy_charges.*,consultancy_link_up_no.* from consultancy_link_up_no 
									INNER JOIN consultancy_charges
									ON consultancy_charges.sr_no = consultancy_link_up_no.sr_no
									where consultancy_charges.status = 6");
			return $query->result();
	}
} 