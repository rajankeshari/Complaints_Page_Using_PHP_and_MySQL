<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Complain_on_behalf_of_student extends MY_Controller
{
	public function __construct()
	{
		parent::__construct(array('emp','stu'));
		//$this->addJS ("complaint/get_residence_address.js");
	}

	public function index()
	{
//		$this->load->model("file_tracking/file_details");

//		$data['department'] = $this->file_details->get_department_by_id();

		$this->drawHeader ("Register your Complaint");// show header
		$this->load->view('complaint/ofline_complain_reg');//loading view named ofline_complai_reg
		$this->drawFooter ();// show footer
	}

	
	public function insert ()
	{
		$admission_no = $this->input->post('ak');// storing ID on behalf of which the complaint is to be done
		$type = $this->input->post('type');//storing type of complaint
        $location = $this->input->post('location');//storing location
		$location_details = $this->input->post('locationDetails');//storing location detail
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
		$this->complaints->insert($data);
		/*$lid=$this->complaints->insert($data);
		$cid=$this->complaints->get_complaint_and_type($lid);
		$this->details_own($cid[0]->complaint_id,$cid[0]->status,$cid[0]->type);*/

		$this->session->set_flashdata('flashSuccess','Complaint successfully Registered. Your Complaint ID : '.$complaint_id.' .');
		redirect('home');
          }
          else
          {
              $this->session->set_flashdata('flashError','Complaint Not Registered Successfully, Please Try Again');
		      redirect('home');
          }
	}



}