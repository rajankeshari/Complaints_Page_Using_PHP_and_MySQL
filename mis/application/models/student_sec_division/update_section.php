<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Update_section extends MY_Controller
{
	function __construct()
	{
		parent::__construct(array('acad_ar'));
		$this->load->model('student_sec_division/update_model');
		$this->load->model('student_sec_division/stu_sec_model');
        $this->addCSS("student_sec_division/section_view.css");
		
	}

	public function index()
	{
		$session_year = $this->update_model->getSessionYear();
		foreach($session_year as $row)
		{
			$curr_session = $row['sess'];
		}
		$data['section'] = $this->update_model->getSection($curr_session);
		$this->drawHeader("Update Student Section");
		$this->load->view('student_sec_division/update', $data);
		$this->drawFooter();			
	}	
	function updateSection()
	{
		$data['admn_no'] =  trim($this->input->post('admn_no')); 
		$data['section'] = 	trim($this->input->post('section')); 

		$session_year = $this->update_model->getSessionYear();
		foreach($session_year as $row)
		{
			$curr_session = $row['sess'];
		}

		$data['session_year'] = $curr_session;

		if($this->update_model->studentExist($data) === false)
		{
			$this->session->set_flashdata('flashError','Admission No. '.$data['admn_no'].' not exist.');
				redirect('student_sec_division/update_section');
		}
		
		if($this->update_model->sectionExist($data) === false)
		{
			$this->session->set_flashdata('flashError','Section doesn\'t exist Try again With existing Section.');
				redirect('student_sec_division/update_section');
		} 
		else
		{
			$this->db->trans_start();	
			$this->update_model->updateSec($data);
			$this->db->trans_complete();

			$data['name'] = $this->stu_sec_model->getName($data['admn_no']);
			$grp = $this->update_model->getGroup($data);
			$data['group'] = $grp[0]->group;
			$name = ucfirst($data['name'][0]->first_name);
			if($data['name'][0]->middle_name)
				$name = $name.' '.ucfirst($data['name'][0]->middle_name);
			if($data['name'][0]->last_name)
				$name = $name.' '.ucfirst($data['name'][0]->last_name);
			$description = "Dear ".$name." admission no ".$data['admn_no'].", Your section is updated and your new section is ".$data['section']." under Group ".$data['group'];
			$title = "Section Allotment Noticce";
			$link = "student_sec_division/greetings/showUpdateGreetings/".$data['admn_no'].'/'.$data['section'].'/'.$data['group'];
			$this->notification->notify($data['admn_no'],"stu",$title,$description,$link,"");
			
			$this->db->trans_complete();
			
			$this->session->set_flashdata('flashSuccess','Admission No '.$data['admn_no'].' successfully updated.');
				redirect('student_sec_division/update_section');
		}	
	}
}
