<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Online_crf_booking_model extends CI_Model {
	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}

	public function get_instrument()
	{
		$sql = "SELECT ci.* FROM crf_inst ci INNER JOIN crf_inst_facility cif ON ci.inst_id=cif.inst_id INNER JOIN crf_inst_cost_and_slot cics ON ci.inst_id=cics.inst_id left JOIN crf_inst_costing cic ON cic.detail_id=cics.detail_id left JOIN crf_inst_slotting cis ON cis.detail_id=cics.detail_id WHERE ci.`status`='Active' GROUP BY ci.inst_id ORDER BY ci.instrument ASC";
		$query = $this->db->query($sql);		
		if($query->num_rows() > 0)
			return $query->result();
		else
			return false;
		// return $query->result();
	}

	public function get_fac_by_inst($inst_id){
		// $sql="SELECT * FROM crf_inst ci INNER JOIN crf_inst_facility cif ON ci.inst_id=cif.inst_id INNER JOIN crf_inst_cost_and_slot cics ON cics.inst_fac_id=cif.inst_fac_id WHERE ci.inst_id='$inst_id' AND  cif.`status`='Active' ORDER BY cif.facility ASC";
		$sql="SELECT * FROM crf_inst ci INNER JOIN crf_inst_facility cif ON ci.inst_id=cif.inst_id INNER JOIN crf_inst_cost_and_slot cics ON cics.inst_fac_id=cif.inst_fac_id INNER JOIN crf_inst_costing cic ON cic.detail_id=cics.detail_id LEFT JOIN crf_inst_slotting cis ON cis.detail_id=cics.detail_id WHERE ci.inst_id='$inst_id' AND cif.`status`='Active' GROUP BY cif.inst_fac_id ORDER BY cif.facility ASC";
		$query = $this->db->query($sql);

		// echo $this->last_query();
		
		if($query->num_rows() > 0)
			return $query->result();
		else
			return false;
	}

	public function get_booking_detail($id){ 
		$data_result=array();
		foreach ($id as $key => $value) {
			$sql="SELECT cics.detail_id,ci.inst_id,cif.inst_fac_id,ci.email,uod.mobile_no,ci.fic_emp_no,ci.instrument,cif.facility,cics.analysis_charge,cics.sample_per_day,cics.sample_per_night,cics.slot_allotment,cics.slot_per_day,cics.slot_per_night FROM crf_inst_facility cif INNER JOIN crf_inst ci ON cif.inst_id=ci.inst_id INNER JOIN crf_inst_cost_and_slot cics ON ci.inst_id=cics.inst_id AND cif.inst_fac_id=cics.inst_fac_id INNER JOIN user_other_details uod ON uod.id=ci.fic_emp_no WHERE cif.inst_fac_id='$id[$key]'";

			$query = $this->db->query($sql);
			// echo $this->db->last_query();
			if($query->num_rows()>0){
				$data_result=array_merge($data_result,$query->result());
				// $data_detail_id=$query->row()->detail_id;
				// $analysis_charge=$query->row()->analysis_charge;				
			}
		}		
		if($data_result)
			return $data_result;
		else
			return false;		
	}


	public function get_costing_detail($user_cat,$user,$purpose,$user_subcat,$ext_user_org){
		return $user_cat.' & '.$user.' & '.$purpose.' & '.$user_subcat.' & '.$ext_user_org;
	}


	public function get_slot_tab($detail_id,$user_cat,$user,$purpose,$user_subcat,$ext_user_org){
		if($user_cat='Internal'){
			$sql="SELECT * FROM crf_inst_costing cic WHERE cic.detail_id='$detail_id' AND cic.user_type='$user_cat' AND cic.user='$user' AND cic.purpose='$purpose'";
		}
		else{
			$sql="SELECT * FROM crf_inst_costing cic WHERE cic.detail_id='$detail_id' AND cic.user_type='$user_cat' AND cic.user='$ext_user_org' AND cic.purpose='$user_subcat'";
		}

		$query = $this->db->query($sql);
		// echo $this->db->last_query();
		if($query->num_rows() > 0)
			return $query->result();
		else
			return false;

	}

	public function get_hour_slot_tab($detail_id){
		$sql="SELECT * FROM crf_inst_slotting cis WHERE cis.detail_id='$detail_id'";
		$query = $this->db->query($sql);

		if($query->num_rows() > 0)
			return $query->result();
		else
			return false;

	}

	

	public function get_basic_detail($id){
		
		$cquery=$this->db->query("SELECT * FROM users WHERE id='$id'");
		
		if($cquery->num_rows() >0){
			$auth_id=$cquery->row()->auth_id;

			if($auth_id=="emp"){
				$sql="SELECT ud.id,ud.salutation,ud.first_name,if(ud.middle_name=' ','NA',ud.middle_name) AS mname,ud.last_name,cd.name AS dept,dg.name AS idesg,ud.email,uod.mobile_no AS mobile,auth.type AS user FROM users u INNER JOIN user_details ud ON u.id=ud.id INNER JOIN cbcs_departments cd ON ud.dept_id=cd.id LEFT JOIN emp_basic_details emp ON emp.emp_no=u.id LEFT JOIN designations dg ON dg.id=emp.designation INNER JOIN auth_types auth ON auth.id=emp.auth_id INNER JOIN user_other_details uod ON uod.id=u.id WHERE u.id='$id' AND u.`status`='A' AND auth.id='ft'";
				
				$query = $this->db->query($sql);
				// echo $this->db->last_query();
				if($query->num_rows() > 0)
					return $query->result();
				else
					return "false";

			}
			if($auth_id=="stu"){
				$sql="SELECT ud.id,ud.salutation,ud.first_name,if(ud.middle_name=' ','NA',ud.middle_name) AS mname,ud.last_name,cd.name AS dept,ud.email,if(ed.phn_no IS NULL,'NA',ed.phn_no) AS mobile,auth.type AS user FROM users u INNER JOIN user_details ud ON u.id=ud.id INNER JOIN cbcs_departments cd ON ud.dept_id=cd.id INNER JOIN stu_details sd ON sd.admn_no=u.id LEFT JOIN emaildata ed ON ed.admission_no=u.id INNER JOIN  auth_types auth ON auth.id=u.auth_id WHERE u.id='$id' AND u.`status`='A'";

				$query = $this->db->query($sql);
				// echo $this->db->last_query();
				if($query->num_rows() > 0)
					return $query->result();
				else
					return "false";
			}	
			
		}
		else{
			return "invalid";
		}

		// $sql="SELECT *,if(ud.middle_name=' ','NA',ud.middle_name) AS mname,auth.`type` AS user FROM users u INNER JOIN user_details ud ON u.id=ud.id INNER JOIN cbcs_departments cd ON ud.dept_id=cd.id INNER JOIN auth_types auth ON auth.id=u.auth_id WHERE u.id='$id' AND u.`status`='A'";
		// // $sql="SELECT *,if(ud.middle_name=' ','NA',ud.middle_name) AS mname FROM users u INNER JOIN user_details ud ON u.id=ud.id WHERE u.id='$id'";
		// $query = $this->db->query($sql);
		
		// if($query->num_rows() > 0)
		// 	return $query->result();
		// else
		// 	return false;
	}


	public function get_booking_table_detail($data){
		$tdate=date("d-m-Y");
		$booking_detail=$data[booking_detail];
		$costing_detail=$data[costing_detail];		
		$booking_table=array();

		foreach ($booking_detail as $key => $value) {
			if($value->analysis_charge=='Hour Based'){
				if($costing_detail['user_cat']=='Internal'){
					$csql = "SELECT cis.slot_id,cis.detail_id,cis.d_start_time,cis.d_end_time,cis.n_start_time,cis.n_end_time,cics.analysis_charge,cics.slot_per_day,cics.slot_per_night,cic.day_cost,cic.night_cost,cbsn.d_start_time AS bd_start,cbsn.d_end_time AS bd_end,cbsn.n_start_time AS bn_start,cbsn.n_end_time AS bn_end FROM crf_inst_slotting cis INNER JOIN crf_inst_cost_and_slot cics ON cics.detail_id=cis.detail_id LEFT JOIN crf_inst_costing cic ON cic.detail_id=cis.detail_id LEFT JOIN crf_booking_s_n_c cbsn ON cbsn.detail_id=cis.detail_id AND cbsn.slot_id=cis.slot_id AND cbsn.dates='$tdate' WHERE cis.detail_id='$value->detail_id' AND cic.user_type='$costing_detail[user_cat]' AND cic.user='$costing_detail[user]' AND cic.purpose='$costing_detail[purpose]' ORDER BY cis.slot_id ASC";
				}
				else{
					$csql = "SELECT cis.slot_id,cis.detail_id,cis.d_start_time,cis.d_end_time,cis.n_start_time,cis.n_end_time,cics.analysis_charge,cics.slot_per_day,cics.slot_per_night,cic.day_cost,cic.night_cost,cbsn.d_start_time AS bd_start,cbsn.d_end_time AS bd_end,cbsn.n_start_time AS bn_start,cbsn.n_end_time AS bn_end FROM crf_inst_slotting cis INNER JOIN crf_inst_cost_and_slot cics ON cics.detail_id=cis.detail_id LEFT JOIN crf_inst_costing cic ON cic.detail_id=cis.detail_id LEFT JOIN crf_booking_s_n_c cbsn ON cbsn.detail_id=cis.detail_id AND cbsn.slot_id=cis.slot_id AND cbsn.dates='$tdate' WHERE cis.detail_id='$value->detail_id' AND cic.user_type='$costing_detail[user_cat]' AND cic.user='$costing_detail[user_subcat]' AND cic.purpose='$costing_detail[ext_user_org]' ORDER BY cis.slot_id ASC";
				}
				
			}
			else{
				if($costing_detail['user_cat']=='Internal'){
					$csql = "SELECT cic.cost_id,cic.detail_id,cic.day_cost,cic.night_cost,cics.analysis_charge,cics.sample_per_day,cics.sample_per_night,SUM(cbsn.day_sample) AS day_sample,SUM(cbsn.night_sample) AS night_sample FROM crf_inst_costing cic INNER JOIN crf_inst_cost_and_slot cics ON cics.detail_id=cic.detail_id LEFT JOIN crf_booking_s_n_c cbsn ON cic.detail_id=cbsn.detail_id AND cbsn.dates='$tdate' WHERE cic.detail_id='$value->detail_id' AND cic.user_type='$costing_detail[user_cat]' AND cic.user='$costing_detail[user]' AND cic.purpose='$costing_detail[purpose]'";
				}
				else{
					$csql="SELECT cic.cost_id,cic.detail_id,cic.day_cost,cic.night_cost,cics.analysis_charge,cics.sample_per_day,cics.sample_per_night,SUM(cbsn.day_sample) AS day_sample,SUM(cbsn.night_sample) AS night_sample FROM crf_inst_costing cic INNER JOIN crf_inst_cost_and_slot cics ON cics.detail_id=cic.detail_id LEFT JOIN crf_booking_s_n_c cbsn ON cic.detail_id=cbsn.detail_id AND cbsn.dates='$tdate' WHERE cic.detail_id='$value->detail_id' AND cic.user_type='$costing_detail[user_cat]' AND cic.user='$costing_detail[user_subcat]' AND cic.purpose='$costing_detail[ext_user_org]'";
				}

			}
			$cquery = $this->db->query($csql);
			// echo $this->db->last_query();
			if($cquery->num_rows()>0){
				$booking_table[$key]=$cquery->result();					
			}
			
		}
		if($booking_table){
			return $booking_table;
		}
		else{
			return false;
		}
		
	}


	public function get_final_data($data){
		// echo "<pre>";
		// print_r($data);

		if($data['analysis_charge']=='Hour Based'){
			if($data['user_cat']=='Internal'){
				$csql = "SELECT cis.slot_id,cis.detail_id,cis.d_start_time,cis.d_end_time,cis.n_start_time,cis.n_end_time,cics.analysis_charge,cics.slot_per_day,cics.slot_per_night,cic.day_cost,cic.night_cost,cbsn.d_start_time AS bd_start,cbsn.d_end_time AS bd_end,cbsn.n_start_time AS bn_start,cbsn.n_end_time AS bn_end FROM crf_inst_slotting cis INNER JOIN crf_inst_cost_and_slot cics ON cics.detail_id=cis.detail_id LEFT JOIN crf_inst_costing cic ON cic.detail_id=cis.detail_id LEFT JOIN crf_booking_s_n_c cbsn ON cbsn.detail_id=cis.detail_id AND cbsn.slot_id=cis.slot_id AND cbsn.dates='$data[sdate]' WHERE cis.detail_id='$data[detail_id]' AND cic.user_type='$data[user_cat]' AND cic.user='$data[user]' AND cic.purpose='$data[purpose]'";
			}
			else{
				$csql = "SELECT cis.slot_id,cis.detail_id,cis.d_start_time,cis.d_end_time,cis.n_start_time,cis.n_end_time,cics.analysis_charge,cics.slot_per_day,cics.slot_per_night,cic.day_cost,cic.night_cost,cbsn.d_start_time AS bd_start,cbsn.d_end_time AS bd_end,cbsn.n_start_time AS bn_start,cbsn.n_end_time AS bn_end FROM crf_inst_slotting cis INNER JOIN crf_inst_cost_and_slot cics ON cics.detail_id=cis.detail_id LEFT JOIN crf_inst_costing cic ON cic.detail_id=cis.detail_id LEFT JOIN crf_booking_s_n_c cbsn ON cbsn.detail_id=cis.detail_id AND cbsn.slot_id=cis.slot_id AND cbsn.dates='$data[sdate]' WHERE cis.detail_id='$data[detail_id]' AND cic.user_type='$data[user_cat]' AND cic.user='$data[user_subcat]' AND cic.purpose='$data[ext_user_org]'";
			}
			
		}
		else{
			if($data['user_cat']=='Internal'){
				$csql = "SELECT cic.cost_id,cic.detail_id,cic.day_cost,cic.night_cost,cics.analysis_charge,cics.sample_per_day,cics.sample_per_night,SUM(cbsn.day_sample) AS day_sample,SUM(cbsn.night_sample) AS night_sample FROM crf_inst_costing cic INNER JOIN crf_inst_cost_and_slot cics ON cics.detail_id=cic.detail_id LEFT JOIN crf_booking_s_n_c cbsn ON cic.detail_id=cbsn.detail_id AND cbsn.dates='$data[sdate]' WHERE cic.detail_id='$data[detail_id]' AND cic.user_type='$data[user_cat]' AND cic.user='$data[user]' AND cic.purpose='$data[purpose]'";
			}
			else{
				$csql="SELECT cic.cost_id,cic.detail_id,cic.day_cost,cic.night_cost,cics.analysis_charge,cics.sample_per_day,cics.sample_per_night,SUM(cbsn.day_sample) AS day_sample,SUM(cbsn.night_sample) AS night_sample FROM crf_inst_costing cic INNER JOIN crf_inst_cost_and_slot cics ON cics.detail_id=cic.detail_id LEFT JOIN crf_booking_s_n_c cbsn ON cic.detail_id=cbsn.detail_id AND cbsn.dates='$data[sdate]' WHERE cic.detail_id='$data[detail_id]' AND cic.user_type='$data[user_cat]' AND cic.user='$data[user_subcat]' AND cic.purpose='$data[ext_user_org]'";
			}

		}
		$cquery = $this->db->query($csql);
		// echo $this->db->last_query();
		if($cquery->num_rows()>0){
			return $cquery->result();					
		}
		else{
			return false;
		}
	}

	public function calendar_detail($detail_id,$analysis_charge){

		if($analysis_charge=='Sample Based'){
			// $sql="SELECT cics.*,cbsn.* FROM crf_inst_cost_and_slot cics INNER JOIN  crf_booking_s_n_c cbsn ON cics.detail_id=cbsn.detail_id WHERE cics.detail_id='$detail_id' GROUP BY cbsn.dates";
			$sql="SELECT cics.*,cbsn.dates,SUM(cbsn.day_sample) AS day_sample,SUM(cbsn.night_sample) AS night_sample FROM  crf_inst_cost_and_slot cics LEFT JOIN crf_booking_s_n_c cbsn ON cics.detail_id=cbsn.detail_id WHERE cics.detail_id='$detail_id' GROUP BY cbsn.dates";
		}
		else{
			$sql="SELECT cics.analysis_charge,cics.slot_per_day,cics.slot_per_night,COUNT(cbsn.d_start_time) AS d_start,COUNT(cbsn.n_start_time) AS n_start,cbsn.dates FROM crf_inst_cost_and_slot cics INNER JOIN crf_inst_slotting cis ON cics.detail_id=cis.detail_id LEFT JOIN crf_booking_s_n_c cbsn ON cics.detail_id=cbsn.detail_id AND cis.slot_id=cbsn.slot_id WHERE cics.detail_id='$detail_id' GROUP BY cbsn.dates";
		}

		
		$query = $this->db->query($sql);
		// echo $this->db->last_query();
		if($query->num_rows()>0){
			return $query->result();					
		}
		else{
			return false;
		}

	}

	public function add_pay_data($name){
		// echo "<pre>";
  //       print_r($name);
  //       exit;
		$check=$name['user_cat'];
		if($check=='Internal'){
			$insert_data=array(
				'user_category'=>$check,
				'int_id'=>$name['admn_no'],
				'user_sub_category'=>$name['h_user'],
				'user_purpose'=>$name['purpose'],
				'salutation'=>$name['salutation'],
				'first_name'=>$name['fname'],
				'middle_name'=>$name['mname'],
				'family_name'=>$name['lname'],
				'desg'=>ucwords($name['h_idesg']),
				'dept'=>$name['dept'],
				'city'=>$name['city'],
				'state'=>$name['state'],
				'pincode'=>$name['pin'],
				'email'=>$name['email'],
				'cell_no'=>$name['cellno'],
				'landline_no'=>$name['landline']
			);

		}
		if($check=='External'){
			$purp='Research';
			$insert_data=array(
				'user_category'=>$check,
				'user_sub_category'=>$name['user_sub_cat'],
				'ext_org_type'=>$name['ext_user_org'],
				'ext_org_name'=>$name['org_name'],
				'user_purpose'=>$purp,
				'salutation'=>$name['salutation'],
				'first_name'=>$name['fname'],
				'middle_name'=>$name['mname'],
				'family_name'=>$name['lname'],
				'desg'=>ucwords($name['desg']),
				'dept'=>$name['dept'],
				'city'=>$name['city'],
				'state'=>$name['state'],
				'pincode'=>$name['pin'],
				'email'=>$name['email'],
				'cell_no'=>$name['cellno'],
				'landline_no'=>$name['landline']
			);

		}
		if($this->db->insert('crf_booking_user',$insert_data)){
			$slot_key=0; //very complicated Problem.

			// echo "<pre>";
			// print_r($name);
			// exit;
			
			$last_id = $this->db->insert_id();
			foreach ($name['ph_slot_mode'] as $key => $value) {
				if($value=='Day Mode'){
					if((!empty($name['ph_stime'][$key])) && (!empty($name['ph_etime'][$key]))){

						$second_insert=array(
							'user_id'=>$last_id,
							'detail_id'=>$name['pdata'][$key],
							'inst_id'=>$name['ph_inst_id'][$key],
							'inst_fac_id'=>$name['ph_inst_fac_id'][$key],
							'slot_id'=>$name['ph_slot_id'][$slot_key],
							'd_start_time'=>$name['ph_stime'][$key],
							'd_end_time'=>$name['ph_etime'][$key],
							'dates'=>$name['ph_dates'][$key],
							'charge'=>$name['ph_charge'][$key]
						);
						$this->db->insert('crf_booking_s_n_c',$second_insert);
						$slot_key++;
					}
					else{
						$second_insert=array(
							'user_id'=>$last_id,
							'detail_id'=>$name['pdata'][$key],
							'inst_id'=>$name['ph_inst_id'][$key],
							'inst_fac_id'=>$name['ph_inst_fac_id'][$key],
							'day_sample'=>$name['ph_nsample'][$key],
							'dates'=>$name['ph_dates'][$key],
							'charge'=>$name['ph_charge'][$key]
						);
						$this->db->insert('crf_booking_s_n_c',$second_insert);
					}					
				}
				if($value=='Night Mode'){
					if((!empty($name['ph_stime'][$key])) && (!empty($name['ph_etime'][$key]))){
						$second_insert=array(
							'user_id'=>$last_id,
							'detail_id'=>$name['pdata'][$key],
							'inst_id'=>$name['ph_inst_id'][$key],
							'inst_fac_id'=>$name['ph_inst_fac_id'][$key],
							'slot_id'=>$name['ph_slot_id'][$slot_key],
							'n_start_time'=>$name['ph_stime'][$key],
							'n_end_time'=>$name['ph_etime'][$key],
							'dates'=>$name['ph_dates'][$key],
							'charge'=>$name['ph_charge'][$key]
						);
						$this->db->insert('crf_booking_s_n_c',$second_insert);
						$slot_key++;
					}
					else{
						$second_insert=array(
							'user_id'=>$last_id,
							'detail_id'=>$name['pdata'][$key],
							'inst_id'=>$name['ph_inst_id'][$key],
							'inst_fac_id'=>$name['ph_inst_fac_id'][$key],
							'night_sample'=>$name['ph_nsample'][$key],
							'dates'=>$name['ph_dates'][$key],
							'charge'=>$name['ph_charge'][$key]
						);
						$this->db->insert('crf_booking_s_n_c',$second_insert);
					}					
				}				
				// $this->db->insert('crf_booking_s_n_c',$second_insert);
				
			}

			/* Complicated Case */
			$gst=0;
			if($check=='External'){
				$gst=0;
			}
			if(empty($name['pay_date'])){
				date_default_timezone_set('Asia/Kolkata');
				$name['pay_date'] = date('d-m-Y', time()); 
				$insert_payment=array(
					'user_id'=>$last_id,
					'total_charge'=>$name['pay_total'],
					'gst'=>$gst,
					'grand_total'=>$name['pay_grand_tot'],
					'payment_mode'=>$name['pay_mode'],
					'payment_date'=>$name['pay_date'],
					'pay_through'=>$name['project_head'],
					'project_no'=>$name['pay_project']
				);
				if($this->db->insert('crf_booking_payment',$insert_payment)){
					// echo $this->db->last_query();
					return true;				
				}
				else{
					return false;
				}
			}
			else{
				$insert_payment=array(
					'user_id'=>$last_id,
					'total_charge'=>$name['pay_total'],
					'gst'=>$gst,
					'grand_total'=>$name['pay_grand_tot'],
					'payment_mode'=>$name['pay_mode'],
					'transaction_id'=>$name['trans_id'],
					'pay_amount'=>$name['p_amnt'],
					'payment_date'=>$name['pay_date'],
					'receipt_path'=>$name['file_path']
				);
				if($this->db->insert('crf_booking_payment',$insert_payment)){
					// echo $this->db->last_query();
					return true;				
				}
				else{
					return false;
				}
			}
			// // exit;
			return true;
		}
		else{

			return false;
		}
	}
	

	function get_project($emp_no){
		$sql = "SELECT p.* FROM accounts_project_details p WHERE p.pi_id='$emp_no'";
		$query = $this->db->query($sql);
		// echo $this->db->last_query();
		if($query->num_rows()>0){
			return $query->result();					
		}
		else{
			return false;
		}

	}


	function get_project_head($project_id){
		$sql = "SELECT * FROM accounts_project_funds apf WHERE apf.project_no='$project_id'";
		$query = $this->db->query($sql);
		// echo $this->db->last_query();
		if($query->num_rows()>0){
			return $query->result();					
		}
		else{
			return false;
		}

	}


	
}
?>
