<?php

class Stu_edit_sem_ey_menu_model extends CI_Model
{
	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}

	function getMenu()
	{
		$menu=array();

		//auth ==> deo
		$menu['admin_exam']=array();
		$menu['admin_exam']=array();
		$menu['admin_exam']["Edit Semester Enrollment Year"] = site_url('stu_edit_sem_ey/student_edit_sem_eyear');
                $menu['admin_exam']["Change Mobile Number"] = site_url('stu_edit_sem_ey/change_mobile_number');
		
		$menu['exam_da1']=array();
		$menu['exam_da1']=array();
		$menu['exam_da1']["Edit Semester Enrollment Year"] = site_url('stu_edit_sem_ey/student_edit_sem_eyear');
                $menu['exam_da1']["Change Mobile Number"] = site_url('stu_edit_sem_ey/change_mobile_number');
              
		
                
		
                
		return $menu;
	}
}
/* End of file menu_model.php */
/* Location: mis/application/models/employee/menu_model.php */