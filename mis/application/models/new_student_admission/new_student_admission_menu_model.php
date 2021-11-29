<?php

class New_student_admission_menu_model extends CI_Model
{
	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}

	function getMenu()
	{
		$menu=array();
		$menu['new_adm']=array();
		
		//jee
             /* $menu['new_adm']['Student Admission JEE']["Upload Student Database"] = site_url('new_student_admission/db_upload_jee');
		      $menu['new_adm']['Student Admission JEE']["Student MIS Registration"] = site_url('new_student_admission/student_register_deo_jee');
		      $menu['new_adm']['Student Admission JEE']["Course Details"] = site_url('new_student_admission/course_edit_jee');
		  */
		  
		  //mtech
		     /* $menu['new_adm']['Student Admission M.Tech']["Upload Student Database"] = site_url('new_student_admission/db_upload_mtech');
		      $menu['new_adm']['Student Admission M.Tech']["Student MIS Registration M.Tech 2 Years"] = site_url('new_student_admission/student_register_deo_mtech');
              $menu['new_adm']['Student Admission M.Tech']["Student MIS Registration M.Tech 3 Years"] = site_url('new_student_admission/student_register_deo_mtech_3years');	
		   */
		   
		   //mba/ex-mba
		    /* $menu['new_adm']['Student Admission MBA/EX-MBA']["Upload Student Database"] = site_url('new_student_admission/db_upload_mba');
		     $menu['new_adm']['Student Admission MBA/EX-MBA']["Student MIS Registration MBA"] = site_url('new_student_admission/student_register_deo_mba');
             $menu['new_adm']['Student Admission MBA/EX-MBA']["Student MIS Registration EX-MBA"] = site_url('new_student_admission/student_register_deo_execmba');
		     */
		   
		   
		   //msc
		  /*   $menu['new_adm']['Student Admission MSc/MSc-Tech']["Upload Student Database"] = site_url('new_student_admission/db_upload_msc');
		     $menu['new_adm']['Student Admission MSc/MSc-Tech']["Student MIS Registration"] = site_url('new_student_admission/student_register_deo_msc');
			 */
			/*  $menu['new_adm']['Student Admission MSc/MSc-Tech']['Student Admission MSc-Tech']["Upload Student Database"] = site_url('new_student_admission/db_upload_msc_tech');
		     $menu['new_adm']['Student Admission MSc/MSc-Tech']['Student Admission MSc-Tech']["Student MIS Registration"] = site_url('new_student_admission/student_register_deo_msc_tech');*/
		   
		   
		   // others
		     
		     $menu['new_adm']['Student Admission Others']["Student MIS Registration"] = site_url('new_student_admission/student_register_deo_others');
		 
		
		//jrf
		//$menu['new_adm']['Student Admission JRF']["Upload Student Database"] = site_url('new_student_admission/db_upload_jrf');
		    /* $menu['new_adm']['Student Admission JRF']["Student MIS Registration"] = site_url('new_student_admission/student_register_deo_jrf');*/
		   
		
		
		return $menu;
	}
}
/* End of file menu_model.php */
