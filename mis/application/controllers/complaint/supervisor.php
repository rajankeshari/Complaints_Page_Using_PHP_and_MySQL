<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Supervisor extends MY_Controller
{
	public function __construct()
	{
//		parent::__construct(array('emp','stu','netsupport'));
//		$this->addJS ("file_tracking/file_tracking_script.js");
//		$this->addCSS("file_tracking/file_tracking_layout.css");

		parent::__construct(array('spvr_cc','spvr_pc','spvr_ups','spvr_civil','spvr_contingency', 'spvr_ee','spvr_mess','spvr_snt','emp','stu','dev_feed','dev_att','dev_grade','dev_hall','dev_info','dev_login','dev_other','dev_semreg','dev_salary','dev_examacad','mis_admin','dev','adis','adcm','adhm')); // below lines copied from report
		// Pl use all auth_id so that each auth can see the details of complaint otherwise they may be logged out from the code
		// The system treats as unauthorized person for taking any further action on the complaint.
				$this->load->model('complaint/complaints','',TRUE);
                $this->load->model('user/user_details_model','',TRUE);


	}

//	public function update_complaint_details ($complaint_id, $type)
        public function update_complaint_details ()
                
	{
		$this->load->model ('complaint/complaints', '', TRUE);
                $complaint_id=$this->input->post('comp');
		$status = $this->input->post('status');
		$fresh_action = $this->input->post('fresh_action');
        	$action_taken = $this->complaints->get_remarks($complaint_id);

		$date = date('j M Y g:i A');
		
		if ($action_taken == "New Complaint")
			$fresh_action = $date." : ".$fresh_action;
		else
			$fresh_action = $action_taken."<br/>".$date." : ".$fresh_action;

		//$this->complaints->update_complaint($complaint_id, $status, $fresh_action);
                $r=$this->complaints->update_complaint($complaint_id, $status, $fresh_action);
                
                echo json_encode($r);
		//$this->session->set_flashdata('flashSuccess','Complaint : '.$complaint_id.' successfully processed');
		//redirect('complaint/supervisor/view_complaints/'.$type);
	}
	
	public function view_complaints ($supervisor)
	{
		$this->load->model ('complaint/complaints', '', TRUE);

		$this->load->model('user_model', '', TRUE);

//------------------------------------------Closed Complaint Logic-----------------------------------------------------(A)               
		$res = $this->complaints->complaint_list("Closed", $supervisor);		//Closed
		$total_rows_closed = $res->num_rows();
		$data_array_closed = array();
		$sno = 1;
		foreach ($res->result() as $row)
		{
			$data_array_closed[$sno]=array();
			$j=1;
			$data_array_closed[$sno][$j++] = $row->complaint_id;
            $data_array_closed[$sno][$j++] = $row->status;
            $data_array_closed[$sno][$j++] = $row->type;
			$data_array_closed[$sno][$j++] = $this->user_model->getNameById($row->user_id);
			$data_array_closed[$sno][$j++] = date('j M Y g:i A', strtotime($row->date_n_time));
			$data_array_closed[$sno][$j++] = $row->location;
			$data_array_closed[$sno][$j++] = $row->location_details;
			$data_array_closed[$sno][$j++] = $row->problem_details;
			$data_array_closed[$sno][$j++] = $row->remarks;
            //  $data_array_under_processing[$sno][$j++] = $row->type;
			//$data_array_closed[$sno][$j++] = $row->pref_time;
			$sno++;
		}
                
//------------------------------------------Rejected Complaint Logic-----------------------------------------------------(A)              
	
		$res = $this->complaints->complaint_list("Rejected", $supervisor);		//Rejected
		$total_rows_rejected = $res->num_rows();
		$data_array_rejected = array();
		$sno = 1;
		foreach ($res->result() as $row)
		{
			$data_array_rejected[$sno]=array();
			$j=1;
			$data_array_rejected[$sno][$j++] = $row->complaint_id;
            $data_array_rejected[$sno][$j++] = $row->status;
            $data_array_rejected[$sno][$j++] = $row->type;
			$data_array_rejected[$sno][$j++] = $this->user_model->getNameById($row->user_id);
			$data_array_rejected[$sno][$j++] = date('j M Y g:i A', strtotime($row->date_n_time));
			$data_array_rejected[$sno][$j++] = $row->location;
			$data_array_rejected[$sno][$j++] = $row->location_details;
			$data_array_rejected[$sno][$j++] = $row->problem_details;
			$data_array_rejected[$sno][$j++] = $row->remarks;
            //  $data_array_under_processing[$sno][$j++] = $row->type;
			//$data_array_rejected[$sno][$j++] = $row->pref_time;
			$sno++;
		}

//------------------------------------------All Complaint Logic-------------------------------------------------------(A)
		
		$res = $this->complaints->all_complaint_list($supervisor);		             // All
		$total_rows_all = $res->num_rows();
		$data_array_all = array();
		$sno = 1;
		foreach ($res->result() as $row)
		{
			$data_array_all[$sno]=array();
			$j=1;
			$data_array_all[$sno][$j++] = $row->complaint_id;
                          $data_array_all[$sno][$j++] = $row->status;
                        $data_array_all[$sno][$j++] = $row->type;
			//$data_array_all[$sno][$j++] = $row->status;
			$data_array_all[$sno][$j++] = $this->user_model->getNameById($row->user_id);
			$data_array_all[$sno][$j++] = date('j M Y g:i A', strtotime($row->date_n_time));
			$data_array_all[$sno][$j++] = $row->location;
			$data_array_all[$sno][$j++] = $row->location_details;
			$data_array_all[$sno][$j++] = $row->problem_details;
			$data_array_all[$sno][$j++] = $row->remarks;
                      //  $data_array_under_processing[$sno][$j++] = $row->type;
			//$data_array_all[$sno][$j++] = $row->pref_time;
			$sno++;
		}
                
//------------------------------------------Under Processing Complaint Logic-----------------------------------------------------(A)
	
		$res = $this->complaints->complaint_list("Under Processing", $supervisor);    //Under Process
		$total_rows_under_processing = $res->num_rows();
		$data_array_under_processing = array();
		$sno = 1;
                
		foreach ($res->result() as $row)
		{
			$data_array_under_processing[$sno]=array();
			$j=1;
			$data_array_under_processing[$sno][$j++] = $row->complaint_id;
            $data_array_under_processing[$sno][$j++] = $row->status;
            $data_array_under_processing[$sno][$j++] = $row->type;
			$data_array_under_processing[$sno][$j++] = $this->user_model->getNameById($row->user_id);
			$data_array_under_processing[$sno][$j++] = date('j M Y g:i A', strtotime($row->date_n_time));
			$data_array_under_processing[$sno][$j++] = $row->location;
			$data_array_under_processing[$sno][$j++] = $row->location_details;
			$data_array_under_processing[$sno][$j++] = $row->problem_details;
			$data_array_under_processing[$sno][$j++] = $row->remarks;
                      //  $data_array_under_processing[$sno][$j++] = $row->type;
             $data_array_under_processing[$sno][$j++] = $row->com_id;
			//$data_array_under_processing[$sno][$j++] = $row->pref_time;
			$sno++;
		}
//-------------------------------------------------New Complaint Logic-------------------------------------------(A)

                $res = $this->complaints->complaint_list("New", $supervisor);    //Under Process
		$total_rows_new = $res->num_rows();
		$data_array_new = array();
		$sno = 1;
                
		foreach ($res->result() as $row)
		{
			$data_array_new[$sno]=array();
			$j=1;
			$data_array_new[$sno][$j++] = $row->complaint_id;
                        $data_array_new[$sno][$j++] = $row->status;
                        $data_array_new[$sno][$j++] = $row->type;
			$data_array_new[$sno][$j++] = $this->user_model->getNameById($row->user_id);
			$data_array_new[$sno][$j++] = date('j M Y g:i A', strtotime($row->date_n_time));
			$data_array_new[$sno][$j++] = $row->location;
			$data_array_new[$sno][$j++] = $row->location_details;
			$data_array_new[$sno][$j++] = $row->problem_details;
			$data_array_new[$sno][$j++] = $row->remarks;
                      //  $data_array_under_processing[$sno][$j++] = $row->type;
                        $data_array_new[$sno][$j++] = $row->com_id;
			//$data_array_under_processing[$sno][$j++] = $row->pref_time;
			$sno++;
		}
                            
//------------------------------------------------Sending data to view for all tab to display-------------------(A)	
		$data['data_array_closed'] = $data_array_closed;
		$data['total_rows_closed'] = $total_rows_closed;
                
		$data['data_array_rejected'] = $data_array_rejected;
		$data['total_rows_rejected'] = $total_rows_rejected;
                
		$data['data_array_all'] = $data_array_all;
		$data['total_rows_all'] = $total_rows_all;
                
		$data['data_array_under_processing'] = $data_array_under_processing;
		$data['total_rows_under_processing'] = $total_rows_under_processing;
                
                $data['data_array_new'] = $data_array_new;
		$data['total_rows_new'] = $total_rows_new;
	
                //print_r($data);
              //  die();
                
		$this->drawHeader ("Complaint List");
		$this->load->view ('complaint/view_complaints_supervisor', $data);
		$this->drawFooter();

	}
	
	 public function update_mis_complaint_details ()
                
	{
		$this->load->model ('complaint/complaints', '', TRUE);
                $complaint_id=$this->input->post('comp');
		$status = $this->input->post('status');
		$fresh_action = $this->input->post('fresh_action');
        	$action_taken = $this->complaints->get_mis_remarks($complaint_id);

		$date = date('j M Y g:i A');
		
		if ($action_taken == "New Complaint")
			$fresh_action = $date." : ".$fresh_action;
		else
			$fresh_action = $action_taken."<br/>".$date." : ".$fresh_action;

		//$this->complaints->update_complaint($complaint_id, $status, $fresh_action);
                $r=$this->complaints->update_mis_complaint($complaint_id, $status, $fresh_action);
                
                echo json_encode($r);
		//$this->session->set_flashdata('flashSuccess','Complaint : '.$complaint_id.' successfully processed');
		//redirect('complaint/supervisor/view_complaints/'.$type);
	}
	
	
	public function view_mis_complaints ($supervisor)
	{
		$this->load->model ('complaint/complaints', '', TRUE);

		$this->load->model('user_model', '', TRUE);
     
//------------------------------------------Closed Complaint Logic-----------------------------------------------------(A)               
		$res = $this->complaints->mis_complaint_list("Closed",$supervisor);		//Closed
		$total_rows_closed = $res->num_rows();
		$data_array_closed = array();
		$sno = 1;
		foreach ($res->result() as $row)
		{
			$data_array_closed[$sno]=array();
			$j=1;
			$data_array_closed[$sno][$j++] = $row->complaint_id;
                        $data_array_closed[$sno][$j++] = $row->status;
                        $data_array_closed[$sno][$j++] = $row->category;
			$data_array_closed[$sno][$j++] = $this->user_model->getNameById($row->user_id);
			$data_array_closed[$sno][$j++] = date('j M Y g:i A', strtotime($row->date_n_time));
			$data_array_closed[$sno][$j++] = $row->problem_details;
			$data_array_closed[$sno][$j++] = $row->remarks;
                      //  $data_array_under_processing[$sno][$j++] = $row->type;
			//$data_array_closed[$sno][$j++] = $row->pref_time;
			$sno++;
		}
                
//------------------------------------------Rejected Complaint Logic-----------------------------------------------------(A)              
	
		$res = $this->complaints->mis_complaint_list("Rejected",$supervisor);		//Rejected
		$total_rows_rejected = $res->num_rows();
		$data_array_rejected = array();
		$sno = 1;
		foreach ($res->result() as $row)
		{
			$data_array_rejected[$sno]=array();
			$j=1;
			$data_array_rejected[$sno][$j++] = $row->complaint_id;
                          $data_array_rejected[$sno][$j++] = $row->status;
                        $data_array_rejected[$sno][$j++] = $row->category;
			$data_array_rejected[$sno][$j++] = $this->user_model->getNameById($row->user_id);
			$data_array_rejected[$sno][$j++] = date('j M Y g:i A', strtotime($row->date_n_time));
			
			$data_array_rejected[$sno][$j++] = $row->problem_details;
			$data_array_rejected[$sno][$j++] = $row->remarks;
                      //  $data_array_under_processing[$sno][$j++] = $row->type;
			//$data_array_rejected[$sno][$j++] = $row->pref_time;
			$sno++;
		}

//------------------------------------------All Complaint Logic-------------------------------------------------------(A)
		
		$res = $this->complaints->all_mis_complaint_list($supervisor);		             // All
		$total_rows_all = $res->num_rows();
		$data_array_all = array();
		$sno = 1;
		foreach ($res->result() as $row)
		{
			$data_array_all[$sno]=array();
			$j=1;
			$data_array_all[$sno][$j++] = $row->complaint_id;
                          $data_array_all[$sno][$j++] = $row->status;
                        $data_array_all[$sno][$j++] = $row->category;
			//$data_array_all[$sno][$j++] = $row->status;
			$data_array_all[$sno][$j++] = $this->user_model->getNameById($row->user_id);
			$data_array_all[$sno][$j++] = date('j M Y g:i A', strtotime($row->date_n_time));
			
			$data_array_all[$sno][$j++] = $row->problem_details;
			$data_array_all[$sno][$j++] = $row->remarks;
                      //  $data_array_under_processing[$sno][$j++] = $row->type;
			//$data_array_all[$sno][$j++] = $row->pref_time;
			$sno++;
		}
                
//------------------------------------------Under Processing Complaint Logic-----------------------------------------------------(A)
	
		$res = $this->complaints->mis_complaint_list("Under Processing",$supervisor);    //Under Process
		$total_rows_under_processing = $res->num_rows();
		$data_array_under_processing = array();
		$sno = 1;
                
		foreach ($res->result() as $row)
		{
			$data_array_under_processing[$sno]=array();
			$j=1;
			$data_array_under_processing[$sno][$j++] = $row->complaint_id;
                        $data_array_under_processing[$sno][$j++] = $row->status;
                        $data_array_under_processing[$sno][$j++] = $row->category;
			$data_array_under_processing[$sno][$j++] = $this->user_model->getNameById($row->user_id);
			$data_array_under_processing[$sno][$j++] = date('j M Y g:i A', strtotime($row->date_n_time));
			
			$data_array_under_processing[$sno][$j++] = $row->problem_details;
			$data_array_under_processing[$sno][$j++] = $row->remarks;
                      //  $data_array_under_processing[$sno][$j++] = $row->type;
                        $data_array_under_processing[$sno][$j++] = $row->token;
			//$data_array_under_processing[$sno][$j++] = $row->pref_time;
			$sno++;
		}
//-------------------------------------------------New Complaint Logic-------------------------------------------(A)

                $res = $this->complaints->mis_complaint_list("New",$supervisor);    //Under Process
		$total_rows_new = $res->num_rows();
		$data_array_new = array();
		$sno = 1;
                
		foreach ($res->result() as $row)
		{
			$data_array_new[$sno]=array();
			$j=1;
			$data_array_new[$sno][$j++] = $row->complaint_id;
                        $data_array_new[$sno][$j++] = $row->status;
                        $data_array_new[$sno][$j++] = $row->category;
			$data_array_new[$sno][$j++] = $this->user_model->getNameById($row->user_id);
			$data_array_new[$sno][$j++] = date('j M Y g:i A', strtotime($row->date_n_time));
			$data_array_new[$sno][$j++] = $row->problem_details;
			$data_array_new[$sno][$j++] = $row->remarks;
                      //  $data_array_under_processing[$sno][$j++] = $row->type;
                        $data_array_new[$sno][$j++] = $row->token;
			//$data_array_under_processing[$sno][$j++] = $row->pref_time;
			$sno++;
		}
                            
//------------------------------------------------Sending data to view for all tab to display-------------------(A)	
		$data['data_array_closed'] = $data_array_closed;
		$data['total_rows_closed'] = $total_rows_closed;
                
		$data['data_array_rejected'] = $data_array_rejected;
		$data['total_rows_rejected'] = $total_rows_rejected;
                
		$data['data_array_all'] = $data_array_all;
		$data['total_rows_all'] = $total_rows_all;
                
		$data['data_array_under_processing'] = $data_array_under_processing;
		$data['total_rows_under_processing'] = $total_rows_under_processing;
                
                $data['data_array_new'] = $data_array_new;
		$data['total_rows_new'] = $total_rows_new;
		$data['supervisor']=$supervisor;
	
                //print_r($data);
              //  die();
                
		$this->drawHeader ("Complaint List");
		$this->load->view ('complaint/view_mis_complaints_supervisor', $data);
		$this->drawFooter();

	}
	
}