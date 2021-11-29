<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Register_complaint extends MY_Controller
{
	public function __construct()
	{
		parent::__construct(array('emp','stu','prj_emp','daily_emp'));
		$this->addJS ("complaint/get_residence_address.js");
	}

	public function index()
	{
//		$this->load->model("file_tracking/file_details");

//		$data['department'] = $this->file_details->get_department_by_id();

		$this->drawHeader ("Register your Complaint");// show header
		$this->load->view('complaint/register_complaint');//loading view register_complaint
		$this->drawFooter ();// show footer
	}
	
	
	public function insert ()
	{
		$admission_no = $this->input->post('ak');// storing ID on behalf of which the complaint is to be done
		$type = $this->input->post('type');
        $location = $this->input->post('location');
		$location_details = $this->input->post('locationDetails');
		$pref_time = $this->input->post('time');
		$problem_details = $this->input->post('problemDetails');
		
          if(!empty($type) && !empty($location) && !empty($location_details) && !empty($pref_time) && !empty($problem_details))
          {
		//$user_id = $this->session->userdata('id');
          	if(!empty($admission_no))
          	{
          		$user_id=&$admission_no;
          	}
            else
          	{
          		$user_id = $this->session->userdata('id');// if ID is not provided, the ID will be extracted from database
          	}

		$complaint_id = time();
		$complaint_id = $type."_".$complaint_id.$user_id;//creating complaint id
		$data = array(
				'user_id' => $user_id,
				'type' => $type,
				'location'=> $location,
				'location_details' => $location_details, 
				'problem_details' => $problem_details,	  
				'pref_time' => $pref_time,	  
				'complaint_id' => $complaint_id	  
					  );
                
		
		$this->load->model ('complaint/complaints', '', TRUE);
		$lid=$this->complaints->insert($data);
		$cid=$this->complaints->get_complaint_and_type($lid);
		$this->details_own($cid[0]->complaint_id,$cid[0]->status,$cid[0]->type);

		/*$this->session->set_flashdata('flashSuccess','Complaint successfully Registered. Your Complaint ID : '.$complaint_id.' .');
		redirect('home');*/
          }
          else
          {
              $this->session->set_flashdata('flashError','Complaint Not Registered Successfully, Please Try Again');
		      redirect('home');
          }
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

}