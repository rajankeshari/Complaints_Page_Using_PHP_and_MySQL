<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Greetings extends MY_Controller
{
	function __construct()
	{
		parent::__construct(array('stu'));
		$this->load->model('student_sec_division/update_model');
		$this->load->model('student_sec_division/stu_sec_model');
        $this->addCSS("student_sec_division/section_view.css");
		
	}

	function showUpdateGreetings($admn_no, $section, $group)
	{
		$data['admn_no'] = $admn_no;
		$data['section'] = $section;
		$data['group'] = $group;

		$name = $this->stu_sec_model->getName($admn_no);
		$full_name = $name[0]->first_name;
		if($name[0]->middle_name)
			$full_name = $full_name.' '.$name[0]->middle_name;
		if($name[0]->last_name)
			$full_name = $full_name.' '.$name[0]->last_name;
		$data['name'] = $full_name;
		$this->drawHeader('Updated Section Notification');
		$this->load->view('student_sec_division/showupdatenotice', $data);
		$this->drawFooter();
	}

	function showGreetings($admn_no, $grp, $sec)
	{
		$data['admn_no'] = $admn_no;
		$data['sec'] = $sec;
		$data['grp'] = $grp;
		$data['name'] = $this->stu_sec_model->getName($admn_no);
 		$this->drawheader('Welcome Student');
		$this->load->view('student_sec_division/showgreetings', $data);
		$this->drawfooter();
	}
}
