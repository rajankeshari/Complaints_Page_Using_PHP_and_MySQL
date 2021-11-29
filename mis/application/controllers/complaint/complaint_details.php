<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Complaint_details extends MY_Controller
{
	public function __construct()
	{


		//parent::__construct(array('spvr_cc','spvr_civil','spvr_ee','spvr_mess','spvr_snt','emp','stu','dev_feed','dev')); // below lines copied from report
		
		parent::__construct(array('spvr_cc','spvr_ups','spvr_pc','spvr_contingency','spvr_civil','spvr_ee','spvr_mess','spvr_snt','emp','daily_emp','stu','dev_feed','dev_att','dev_grade','dev_hall','dev_info','dev_login','dev_other','dev_semreg','mis_salary','mis_admin','dev','adis','adcm','adhm','prj_emp')); // below lines copied from report
		// Pl use all auth_id so that each auth can see the details of complaint otherwise they may be logged out from the code
		// The system treats as unauthorized person for taking any further action on the complaint.
		$this->load->model('complaint/complaints','',TRUE);
        $this->load->model('user/user_details_model','',TRUE);


	}

	public function details ($complaint_id, $status='',$type='')
	{
		$data = array();
                $this->load->model ('complaint/complaints', '', TRUE);
		
                $res = $this->complaints->get_complaint_details_next($complaint_id, rawurldecode($status),$type);
               
                $this->load->model('user_model', '', TRUE);
		$this->load->model('user_other_details_model', '', TRUE);
		                
		$sno = 1;
		foreach ($res->result() as $row)
		{
			//$data_array[$sno]=array();
			$j=1;
            $data['next'] = $this->complaints->get_next_complaint($row->com_id,rawurldecode($row->status),$row->type);
            $data['prev'] = $this->complaints->get_prev_complaint($row->com_id,rawurldecode($row->status),$row->type);
			$data['complaint_id'] = $row->complaint_id;
			$data['complaint_by'] = $this->user_model->getNameById($row->user_id);
			$data['date_n_time'] = date('j M Y g:i A', strtotime($row->date_n_time));
			$data['location'] = $row->location;
			$data['location_details'] = urldecode($row->location_details);
			$data['problem_details'] = urldecode($row->problem_details);
			$data['pref_time'] = $row->pref_time;
			$data['status'] = $row->status;
			$data['remarks'] = $row->remarks;
			
			$query = $this->user_other_details_model->getUserById($row->user_id);
			$data['mobile'] = $query->mobile_no;
			if (!$data['mobile'])
				$data['mobile'] = "NA";
			$data['email'] = $this->user_model->getEmailById($row->user_id);
			if (!$data['email'])
				$data['email'] = "NA";
			$data['type'] = $row->type;
			//$data_array[$sno][$j++] = $row->pref_time;
			//$sno++;
		}
		
               
		$this->drawHeader ("Complaint Details");
                
		if (rawurldecode($status) == "Under Processing" || $status=="New")
			$this->load->view('complaint/complaint_details_editable',$data);
		else
			$this->load->view('complaint/complaint_details_view',$data);
		$this->drawFooter();		
	}
	public function mis_details ($supervisor, $complaint_id, $status='',$type='')
	{
		$data = array();
                $this->load->model ('complaint/complaints', '', TRUE);
		
                $res = $this->complaints->get_mis_complaint_details_next($complaint_id, rawurldecode($status),$type);
                 
                $this->load->model('user_model', '', TRUE);
		$this->load->model('user_other_details_model', '', TRUE);
		                
		$sno = 1;
		foreach ($res->result() as $row)
		{
			//$data_array[$sno]=array();
			$j=1;
						if(strcmp($supervisor,"All")==0)
						{
							$data['next'] = $this->complaints->get_next_all_supervisor_mis_complaint($row->token,rawurldecode($row->status),$row->category);
                        $data['prev'] = $this->complaints->get_prev_all_supervisor_mis_complaint($row->token,rawurldecode($row->status),$row->category);

						}
						else
						{
							$data['next'] = $this->complaints->get_next_mis_complaint($row->token,rawurldecode($row->status),$row->category);
                        $data['prev'] = $this->complaints->get_prev_mis_complaint($row->token,rawurldecode($row->status),$row->category);
						}

                        
			$data['complaint_id'] = $row->complaint_id;
			$data['complaint_by'] = $this->user_model->getNameById($row->user_id);
			$data['date_n_time'] = date('j M Y g:i A', strtotime($row->date_n_time));
			
			$data['problem_details'] = urldecode($row->problem_details);
			
			$data['status'] = $row->status;
			$data['remarks'] = $row->remarks;
			
			$query = $this->user_other_details_model->getUserById($row->user_id);
			$data['mobile'] = $query->mobile_no;
			if (!$data['mobile'])
				$data['mobile'] = "NA";
			$data['email'] = $this->user_model->getEmailById($row->user_id);
			if (!$data['email'])
				$data['email'] = "NA";
			$data['category'] = $row->category;
			$data['supervisor']=$supervisor;
			//$data_array[$sno][$j++] = $row->pref_time;
			//$sno++;
		}
		
               
		$this->drawHeader ("Complaint Details");
                
		if (rawurldecode($status) == "Under Processing" || $status=="New")
			$this->load->view('complaint/mis_complaint_details_editable',$data);
		else
			$this->load->view('complaint/mis_complaint_details_view',$data);
		$this->drawFooter();		
	}
        
        public function details_own ($complaint_id, $status='',$type='')
	{
		$data = array();
                $this->load->model ('complaint/complaints', '', TRUE);
		
                $res = $this->complaints->get_complaint_details_next($complaint_id, rawurldecode($status),$type);
               
                $this->load->model('user_model', '', TRUE);
		$this->load->model('user_other_details_model', '', TRUE);
		                
		$sno = 1;
		foreach ($res->result() as $row)
		{
			//$data_array[$sno]=array();
			$j=1;
                        $data['next'] = $this->complaints->get_next_complaint($row->com_id,rawurldecode($row->status),$row->type);
                        $data['prev'] = $this->complaints->get_prev_complaint($row->com_id,rawurldecode($row->status),$row->type);
			$data['complaint_id'] = $row->complaint_id;
			$data['complaint_by'] = $this->user_model->getNameById($row->user_id);
			$data['date_n_time'] = date('j M Y g:i A', strtotime($row->date_n_time));
			$data['location'] = $row->location;
			$data['location_details'] = urldecode($row->location_details);
			$data['problem_details'] = urldecode($row->problem_details);
			$data['pref_time'] = $row->pref_time;
			$data['status'] = $row->status;
			$data['remarks'] = $row->remarks;
			
			$query = $this->user_other_details_model->getUserById($row->user_id);
			$data['mobile'] = $query->mobile_no;
			if (!$data['mobile'])
				$data['mobile'] = "NA";
			$data['email'] = $this->user_model->getEmailById($row->user_id);
			if (!$data['email'])
				$data['email'] = "NA";
			$data['type'] = $row->type;
			//$data_array[$sno][$j++] = $row->pref_time;
			//$sno++;
		}
		
               
		$this->drawHeader ("Complaint Details");
                
//		if (rawurldecode($status) == "Under Processing" || $status=="New")
//			$this->load->view('complaint/complaint_details_editable',$data);
//		else
		$this->load->view('complaint/complaint_details_view_own',$data);
		$this->drawFooter();		
	}
     public function mis_details_own ($complaint_id, $status='',$type='')
	{
		$data = array();
                $this->load->model ('complaint/complaints', '', TRUE);
		
                $res = $this->complaints->get_mis_complaint_details_next($complaint_id, rawurldecode($status),$type);
               
                $this->load->model('user_model', '', TRUE);
		$this->load->model('user_other_details_model', '', TRUE);
		                
		$sno = 1;
		foreach ($res->result() as $row)
		{
			//$data_array[$sno]=array();
			$j=1;
                        $data['next'] = $this->complaints->get_next_mis_complaint($row->com_id,rawurldecode($row->status),$row->type);
                        $data['prev'] = $this->complaints->get_prev_mis_complaint($row->com_id,rawurldecode($row->status),$row->type);
			$data['complaint_id'] = $row->complaint_id;
			$data['complaint_by'] = $this->user_model->getNameById($row->user_id);
			$data['date_n_time'] = date('j M Y g:i A', strtotime($row->date_n_time));
			
			$data['problem_details'] = urldecode($row->problem_details);
			
			$data['status'] = $row->status;
			$data['remarks'] = $row->remarks;
			
			$query = $this->user_other_details_model->getUserById($row->user_id);
			$data['mobile'] = $query->mobile_no;
			if (!$data['mobile'])
				$data['mobile'] = "NA";
			$data['email'] = $this->user_model->getEmailById($row->user_id);
			if (!$data['email'])
				$data['email'] = "NA";
			$data['type'] = $row->type;
			//$data_array[$sno][$j++] = $row->pref_time;
			//$sno++;
		}
		
               
		$this->drawHeader ("Complaint Details");
                
//		if (rawurldecode($status) == "Under Processing" || $status=="New")
//			$this->load->view('complaint/complaint_details_editable',$data);
//		else
		$this->load->view('complaint/mis_complaint_details_view_own',$data);
		$this->drawFooter();		
	}   
        
       
        
        
}