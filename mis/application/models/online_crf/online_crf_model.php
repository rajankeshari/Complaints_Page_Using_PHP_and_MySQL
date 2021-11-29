<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Online_crf_model extends CI_Model {
	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}
	
	public function get_depts()
	{
		$query = $this->db->get_where('cbcs_departments', array('type'=>'academic','status'=>'1'));
		return $query->result();
	}

	public function get_faculty_by_depid($dept_id)
	{
		$query = $this->db->query("SELECT * FROM `user_details` AS ud INNER JOIN `emp_basic_details` AS ebd ON ud.id=ebd.emp_no INNER JOIN `users` AS us ON ud.id=us.id WHERE ebd.auth_id='ft' AND us.`status`='A' AND ud.dept_id='".$dept_id."' ORDER BY ud.first_name ASC");
		
		if($query->num_rows() > 0)
			return $query->result();
		else
			return false;
	}

	public function save_data($path){
		
		$inst_name=$this->input->post('inst_name');
        $fac_id=$this->input->post('facultylist');
        $inst_email=$this->input->post('inst_email');
        $inst_docs=$path;
        $inst_remarks=$this->input->post('inst_remarks');
		$session_user_id=$this->session->userdata('id');

		$this->db->set('instrument', $inst_name);
		$this->db->set('fic_emp_no', $fac_id);
		$this->db->set('email', $inst_email);
		$this->db->set('about_inst', $inst_docs);
		$this->db->set('added_by', $session_user_id);
		$this->db->set('remark1', $inst_remarks);
		if($this->db->insert('crf_inst')){
			return true;
		}
		else{
			return false;
		}

	}
	
	public function get_table_data()
	{
		
		$sql="SELECT c.*,if(c.remark1 IS NULL,'NA',c.remark1) AS remark, CONCAT_WS(' ',u.salutation,u.first_name,u.middle_name,u.last_name) as fname,u.dept_id FROM crf_inst c INNER JOIN user_details u ON c.fic_emp_no=u.id ORDER BY c.instrument ASC,u.first_name ASC,c.status ASC";
		$query=$this->db->query($sql);
		if($query->num_rows()>0){			
			return $query->result();
		}
		else{
			return false;
		}
	}
	public function getname($id)
	{
		
		$sql="SELECT CONCAT_WS(' ',u.salutation,u.first_name,u.middle_name,u.last_name) as fname,u.dept_id FROM user_details u WHERE u.id='$id'";
		$query=$this->db->query($sql);
		if($query->num_rows()>0){			
			return $query->result();
		}
		else{
			return false;
		}
	}

	public function get_instrument(){
		$query=$this->db->query("SELECT * FROM crf_inst WHERE status='Active' GROUP BY instrument ORDER BY instrument ASC");
		if($query->num_rows()>0){			
			return $query->result();
		}
		else{
			return false;
		}
	}

	
	public function edit_inst_detail($name){
		$session_user_id=$this->session->userdata('id');
		
		$sql="SELECT * FROM crf_inst WHERE inst_id=$name[inst_id]";
		$query=$this->db->query($sql);
		//echo $this->db->last_query()."<br>";
		if($query->num_rows()>0){	
			$prev_data=$query->result_array()[0];
			if($this->db->insert('crf_inst_log',$prev_data)){
				if($name[edit_inst_docs]==''){
        			$usql="UPDATE crf_inst SET `instrument`='$name[inst_name]',`fic_emp_no`='$name[edit_facultylist]',`status`='$name[status]',`email`='$name[edit_email]',`added_by`='$session_user_id',`remark1`='$name[edit_remark]' WHERE `inst_id`='$name[inst_id]'";
	        	}
	        	else{
	        		$usql="UPDATE crf_inst SET `instrument`='$name[inst_name]',`fic_emp_no`='$name[edit_facultylist]',`status`='$name[status]',`email`='$name[edit_email]',`about_inst`='$name[edit_inst_docs]',`added_by`='$session_user_id',`remark1`='$name[edit_remark]' WHERE `inst_id`='$name[inst_id]'";
	        	}				
					if($this->db->query($usql)){
						return true;
					}
					else{
						return false;
					}
			}
			else{
				return false;
			}
		}
		else{
			return false;
		}
		
	}


	public function add_faciltiy($inst_id,$facility){
		$session_user_id=$this->session->userdata('id');
		$this->db->set('inst_id', $inst_id);
		$this->db->set('facility', $facility);
		$this->db->set('added_by', $session_user_id);
		if($this->db->insert('crf_inst_facility')){
			return true;
		}
		else{
			return false;
		}
	}


	public function get_facility_tab(){
		$sql="SELECT cif.inst_fac_id,cif.inst_id,cif.facility,cif.status,ci.instrument FROM crf_inst_facility cif INNER JOIN crf_inst ci ON cif.inst_id=ci.inst_id WHERE ci.status='Active' ORDER BY ci.instrument ASC,cif.facility ASC,cif.status ASC";
		$query=$this->db->query($sql);
		if($query->num_rows() > 0)
			return $query->result();
		else
			return false;
	}

	public function edit_facility_detail($name){
		$sql="SELECT * FROM crf_inst_facility WHERE inst_fac_id=$name[inst_fac_id]";
		$query=$this->db->query($sql);
		//echo $this->db->last_query()."<br>";
		if($query->num_rows()>0){	
			$prev_data=$query->result_array()[0];
			if($this->db->insert('crf_inst_facility_log',$prev_data)){
				$usql="UPDATE crf_inst_facility SET `inst_id`='$name[edit_inst_id]',`facility`='$name[edit_facility]',`status`='$name[edit_status]' WHERE `inst_fac_id`='$name[inst_fac_id]'";
				if($this->db->query($usql)){
					return true;
				}
				else{
					return false;
				}
			}
			else{
				return false;
			}
		}
		else{
			return false;
		}	
	}

	public function get_slot_detail($inst_id){

		$sql="SELECT cics.detail_id,ci.*,cif.* FROM crf_inst_facility cif
			INNER JOIN crf_inst ci ON cif.inst_id=ci.inst_id LEFT JOIN crf_inst_cost_and_slot cics ON ci.inst_id=cics.inst_id AND cif.inst_fac_id=cics.inst_fac_id WHERE ci.inst_id='$inst_id' AND ci.status='Active' AND cif.status='Active' ORDER BY ci.instrument ASC,cif.facility ASC";
		$query=$this->db->query($sql);
		// echo $this->db->last_query();
		if($query->num_rows() > 0)
			return $query->result();
		else
			return false;

	}

	public function add_sandc_detail($data){
		$session_user_id=$this->session->userdata('id');
		if ($data['analysis']=='Sample Based') {

			$insert_data=array(
				'inst_id'=>$data['tab_inst_id'],
				'inst_fac_id'=>$data['tab_inst_fac_id'],
				'analysis_charge'=>$data['analysis'],
				'slot_allotment'=>$data['slot'],
				'sample_per_day'=>$data['max_day_slot'],
				'sample_per_night'=>$data['max_night_slot'],
				'added_by'=>$session_user_id
			);
		}
		else{

			$insert_data=array(
				'inst_id'=>$data['tab_inst_id'],
				'inst_fac_id'=>$data['tab_inst_fac_id'],
				'analysis_charge'=>$data['analysis'],
				'slot_allotment'=>$data['slot'],
				'slot_per_day'=>$data['max_day_slot'],
				'slot_per_night'=>$data['max_night_slot'],
				'added_by'=>$session_user_id
			);
		}
			
		if($this->db->insert('crf_inst_cost_and_slot',$insert_data)){
			// echo $this->db->last_query();
			return true;				
		}
		else{

			return false;
		}
	}

	public function edit_slot_and_cost(){
		$session_user_id=$this->session->userdata('id');
		$name=$this->input->post();
		if($name[slot_edit_analysis]=='Sample Based'){
			$data=array(
				'analysis_charge'=>$name[slot_edit_analysis],
				'slot_allotment'=>$name[slot_edit_allotment],
				'sample_per_day'=>$name[slot_edit_max_perday],
				'sample_per_night'=>$name[slot_edit_max_pernight],
				'added_by'=>$session_user_id,
				'status'=>$name[slot_edit_staus]
			);
		}
		else{
			$data=array(
				'analysis_charge'=>$name[slot_edit_analysis],
				'slot_allotment'=>$name[slot_edit_allotment],
				'slot_per_day'=>$name[slot_edit_max_perday],
				'slot_per_night'=>$name[slot_edit_max_pernight],
				'added_by'=>$session_user_id,
				'status'=>$name[slot_edit_staus]
			);

		}
		
		$this->db->where('detail_id',$name[detail_id]);		
		if($this->db->update('crf_inst_cost_and_slot',$data)){
			return true;
		}
		else{
			return false;
		}
	}

	public function get_sandc_tab(){
		// $sql="SELECT ci.instrument,cif.facility,cics.* FROM crf_inst_cost_and_slot cics INNER JOIN crf_inst ci ON cics.inst_id=ci.inst_id INNER JOIN crf_inst_facility cif ON cics.inst_fac_id=cif.inst_fac_id INNER JOIN crf_inst_costing cic ON cic.detail_id=cics.detail_id WHERE ci.status='Active' AND cif.status='Active' GROUP BY cics.detail_id ORDER BY detail_id DESC";
		$sql="SELECT ci.instrument,cif.facility,cics.* FROM crf_inst_cost_and_slot cics INNER JOIN crf_inst ci ON cics.inst_id=ci.inst_id INNER JOIN crf_inst_facility cif ON cics.inst_fac_id=cif.inst_fac_id WHERE ci.status='Active' AND cif.status='Active' GROUP BY cics.detail_id ORDER BY detail_id DESC";
		$query=$this->db->query($sql);
		// echo $this->db->last_query();
		if($query->num_rows() > 0)
			return $query->result();
		else
			return false;
	}

	public function get_sandc_report_tab(){
		$sql="SELECT ci.instrument,cif.facility,cics.* FROM crf_inst_cost_and_slot cics INNER JOIN crf_inst ci ON cics.inst_id=ci.inst_id INNER JOIN crf_inst_facility cif ON cics.inst_fac_id=cif.inst_fac_id INNER JOIN crf_inst_costing cic ON cic.detail_id=cics.detail_id WHERE ci.status='Active' AND cif.status='Active' GROUP BY cics.detail_id ORDER BY detail_id DESC";
		$query=$this->db->query($sql);
		// echo $this->db->last_query();
		if($query->num_rows() > 0)
			return $query->result();
		else
			return false;
	}

	public function show_detail($detail_id){
		$sql="SELECT ci.instrument,ci.email,ci.fic_emp_no,ci.remark1,cif.facility,cics.* FROM crf_inst_cost_and_slot cics INNER JOIN crf_inst ci ON cics.inst_id=ci.inst_id INNER JOIN crf_inst_facility cif ON cics.inst_fac_id=cif.inst_fac_id WHERE cics.detail_id='$detail_id' AND cics.status='Active'";

		$query=$this->db->query($sql);
		if($query->num_rows() > 0)
			return $query->result();
		else
			return false;
	}

	public function show_costing($detail_id){
		$sql="SELECT cic.* FROM crf_inst_costing cic WHERE cic.detail_id='$detail_id' AND cic.`status`='Active'";

		$query=$this->db->query($sql);
		if($query->num_rows() > 0)
			return $query->result();
		else
			return false;
	}

	public function show_slotting($detail_id){
		$sql="SELECT cis.* FROM crf_inst_slotting cis WHERE cis.detail_id='$detail_id' AND cis.`status`='Active'";

		$query=$this->db->query($sql);
		if($query->num_rows() > 0)
			return $query->result();
		else
			return false;
	}



	public function add_sandc_moredetail($name){
		// echo "<pre>";
        // print_r($name);
        // exit;        

  //       if((!empty($name[sample_per_day]))||(!empty($name[sample_per_night]))){        	
  //       	$data=array(			
		// 		'sample_per_day'=>$name[sample_per_day],
		// 		'sample_per_night'=>$name[sample_per_night]
		// 	);

		// 	$this->db->where('detail_id',$name[h_detail_id]);
		// 	$this->db->update('crf_inst_cost_and_slot',$data);		
			
		// }
	
		
		if (!empty($name[c_cost_id][0])) {
			if($name[h_analysis_charge]=='Sample Based'){
				if ($name[h_samplepernight]!=0) {
					foreach ($name[c_user_type] as $key => $value) {
						if(empty($name[c_cost_day][$key])){
							$name[c_cost_day][$key]=0;
						}
						if(empty($name[c_cost_night][$key])){
							$name[c_cost_night][$key]=0;
						}
						$insert_data=array(				
							'day_cost'=>$name[c_cost_day][$key],				
							'night_cost'=>$name[c_cost_night][$key]				
						);
						$this->db->where('cost_id', $name[c_cost_id][$key]);
						$this->db->update('crf_inst_costing',$insert_data);						
					}
				}
				else{
					foreach ($name[c_user_type] as $key => $value) {
						if(empty($name[c_cost_day][$key])){
							$name[c_cost_day][$key]=0;
						}
						$insert_data=array(				
							'day_cost'=> $name[c_cost_day][$key]			
						);
						$this->db->where('cost_id',$name[c_cost_id][$key]);
						$this->db->update('crf_inst_costing',$insert_data);						
					}
				}				
			}
			//  Updattion of Hour Based  //
			else{
				if($name[h_slotpernight]!=0)
				{
					foreach ($name[c_user_type] as $key => $value) {
						if(empty($name[c_cost_day][$key])){
							$name[c_cost_day][$key]=0;
						}
						if(empty($name[c_cost_night][$key])){
							$name[c_cost_night][$key]=0;
						}
						$insert_data=array(				
							'day_cost'=>$name[c_cost_day][$key],				
							'night_cost'=>$name[c_cost_night][$key]				
						);
						$this->db->where('cost_id', $name[c_cost_id][$key]);
						$this->db->update('crf_inst_costing',$insert_data);						
					}


					foreach ($name[d_start] as $key => $value) {
						$insert_data=array(
							'd_start_time'=>$name[d_start][$key],
							'd_end_time'=>$name[d_end][$key]
						);
						$this->db->where('slot_id', $name[d_slot_id][$key]);
						$this->db->update('crf_inst_slotting',$insert_data);	
					}

					/* check update of insertion changes on 19 jan 2021 */
					$usql = "SELECT * FROM crf_inst_slotting cis WHERE cis.detail_id='$name[h_detail_id]' AND (cis.n_start_time IS NOT NULL OR cis.n_end_time IS NOT NULL)";
					$uquery = $this->db->query($usql);					
					if($uquery->num_rows()>0){
						foreach ($name[n_start] as $key => $value) {
							$insert_data=array(
								'n_start_time'=>$name[n_start][$key],
								'n_end_time'=>$name[n_end][$key]
							);
							$this->db->where('slot_id', $name[n_slot_id][$key]);
							$this->db->update('crf_inst_slotting',$insert_data);
						}
					}
					else{
						foreach ($name[n_start] as $key => $value) {
							$insert_data=array(
								'detail_id'=>$name[h_detail_id],
								'n_start_time'=>$name[n_start][$key],
								'n_end_time'=>$name[n_end][$key]
							);
							$this->db->insert('crf_inst_slotting',$insert_data);
						}
					}
					/* check update of insertion */					
					
				}
				else
				{
					foreach ($name[c_user_type] as $key => $value) {
						if(empty($name[c_cost_day][$key])){
							$name[c_cost_day][$key]=0;
						}
						$insert_data=array(					
							'day_cost'=>$name[c_cost_day][$key]				
						);
						$this->db->where('cost_id', $name[c_cost_id][$key]);
						$this->db->update('crf_inst_costing',$insert_data);						
					}

					foreach ($name[d_start] as $key => $value) {
						$insert_data=array(
							'd_start_time'=>$name[d_start][$key],
							'd_end_time'=>$name[d_end][$key]
						);
						$this->db->where('slot_id', $name[d_slot_id][$key]);
						$this->db->update('crf_inst_slotting',$insert_data);	
					}

				}

			}
			return "Updated";
		}
		else {
			if($name[h_analysis_charge]=='Sample Based'){
				if ($name[h_samplepernight]!=0) {
					foreach ($name[c_user_type] as $key => $value) {
						if(empty($name[c_cost_day][$key])){
							$name[c_cost_day][$key]=0;
						}
						if(empty($name[c_cost_night][$key])){
							$name[c_cost_night][$key]=0;
						}
						$insert_data=array(
							'detail_id'=>$name[h_detail_id],
							'user_type'=>$name[c_user_type][$key],
							'user'=>$name[c_user][$key],
							'purpose'=>$name[c_purpose][$key],				
							'day_cost'=>$name[c_cost_day][$key],				
							'night_cost'=>$name[c_cost_night][$key]				
						);
						$this->db->insert('crf_inst_costing',$insert_data);						
					}
				}
				else{
					foreach ($name[c_user_type] as $key => $value) {
						if(empty($name[c_cost_day][$key])){
							$name[c_cost_day][$key]=0;
						}
						$insert_data=array(
							'detail_id'=>$name[h_detail_id],
							'user_type'=>$name[c_user_type][$key],
							'user'=>$name[c_user][$key],
							'purpose'=>$name[c_purpose][$key],				
							'day_cost'=>$name[c_cost_day][$key]			
						);
						$this->db->insert('crf_inst_costing',$insert_data);						
					}

				}
				
			}
			else{
				if($name[h_slotpernight]!=0)
				{
					foreach ($name[c_user_type] as $key => $value) {
						$insert_data=array(
							'detail_id'=>$name[h_detail_id],
							'user_type'=>$name[c_user_type][$key],
							'user'=>$name[c_user][$key],
							'purpose'=>$name[c_purpose][$key],				
							'day_cost'=>$name[c_cost_day][$key],				
							'night_cost'=>$name[c_cost_night][$key]				
						);
						$this->db->insert('crf_inst_costing',$insert_data);						
					}

					foreach ($name[d_start] as $key => $value) {
						$insert_data=array(
							'detail_id'=>$name[h_detail_id],
							'd_start_time'=>$name[d_start][$key],
							'd_end_time'=>$name[d_end][$key]
						);
						$this->db->insert('crf_inst_slotting',$insert_data);
					}
					foreach ($name[n_start] as $key => $value) {
						$insert_data=array(
							'detail_id'=>$name[h_detail_id],
							'n_start_time'=>$name[n_start][$key],
							'n_end_time'=>$name[n_end][$key]
						);
						$this->db->insert('crf_inst_slotting',$insert_data);
					}
				}
				else
				{
					foreach ($name[c_user_type] as $key => $value) {
						$insert_data=array(
							'detail_id'=>$name[h_detail_id],
							'user_type'=>$name[c_user_type][$key],
							'user'=>$name[c_user][$key],
							'purpose'=>$name[c_purpose][$key],				
							'day_cost'=>$name[c_cost_day][$key]				
						);
						$this->db->insert('crf_inst_costing',$insert_data);						
					}

					foreach ($name[d_start] as $key => $value) {
						$insert_data=array(
							'detail_id'=>$name[h_detail_id],
							'd_start_time'=>$name[d_start][$key],
							'd_end_time'=>$name[d_end][$key]
						);
						$this->db->insert('crf_inst_slotting',$insert_data);
					}

				}

			}
			return "Inserted";
		}
		
		
	}




}
