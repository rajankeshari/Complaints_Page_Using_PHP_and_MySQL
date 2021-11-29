<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Insert_new extends MY_Controller
{
	function __construct()
	{
		parent::__construct(array('acad_ar'));
		$this->load->model('student_sec_division/insert_model');
		$this->load->model('student_sec_division/stu_sec_model');
        $this->addCSS("student_sec_division/section_view.css");
		
	}

	public function index()
	{
		$session_year = $this->insert_model->getSessionYear();
		foreach($session_year as $row)
		{
			$curr_session = $row['sess'];
		}
		$data['section'] = $this->insert_model->getSection($curr_session);
		$this->drawHeader("Insert New Student Section");
		$this->load->view('student_sec_division/insertstudent', $data);
		$this->drawFooter();			
	}	
	function insertStu()
	{
		$data['admn_no'] =  trim($this->input->post('admn_no')); 
		$data['section'] = 	trim($this->input->post('section')); 
		$data['group'] = 	trim($this->input->post('group'));   
		
		$session = $this->insert_model->getSessionYear();
		foreach($session as $row)
		{
			$data['session_year'] = $row['sess'];
		}
		$this->db->trans_start();

		if($this->insert_model->studentExist($data) === true)
		{
			$this->session->set_flashdata('flashError','Admission No. '.$data['admn_no'].' already exist.');
				redirect('student_sec_division/insert_new');
		}
		
		if($this->insert_model->sectionExist($data) === true)
		{
			if($this->insert_model->secGrpRelExist($data)===false)
			{
				$this->session->set_flashdata('flashError','Section Group Relation Violeted.');
				redirect('student_sec_division/insert_new');
			}
			else
			{
				$this->insert_model->insertStuData($data);
				
			}		
		} 
		else
		{
			$this->insert_model->insertStuData($data);
			$this->insert_model->insertSecGrpRel($data);
		}

		$this->db->trans_complete();
		$data['name'] = $this->stu_sec_model->getName($data['admn_no']);
			$name = ucfirst($data['name'][0]->first_name);
		if($data['name'][0]->middle_name)
			$name = $name.' '.ucfirst($data['name'][0]->middle_name);
		if($data['name'][0]->last_name)
			$name = $name.' '.ucfirst($data['name'][0]->last_name);
			$description = "Dear ".$name." admission no ".$data['admn_no'].", You are in section ".$data['section']." under Group ".$data['group'];
			$title = "Section Allotment Noticce";
			$link = "student_sec_division/greetings/showGreetings/".$data['admn_no'].'/'.$data['group'].'/'.$data['section'];
			$this->notification->notify($data['admn_no'],"stu",$title,$description,$link,"");
		$this->session->set_flashdata('flashSuccess','Admission No'.$data['admn_no'].' successfully inserted.');
			redirect('student_sec_division/insert_new');
	}
}
