<?php

class Register_mail_id_menu_model extends CI_Model{

	function __construct(){	// Call the Model constructor
		parent::__construct();
	}

	function getMenu(){
		
		// For all users who can opt for new email ids on ism.ac.in domain
		//$menu['stu']=array();
		//$menu['stu']['Register Mail ID']=array();
		//$menu['stu']['Register Mail ID'] = site_url('register_mail_id/register_mail_id_main');    
                
      //  $menu['emp']=array();
		//$menu['emp']['Register Mail ID']=array();
		//$menu['emp']['Register Mail ID'] = site_url('register_mail_id/register_mail_emp'); 
		
		$menu['prj_emp']=array();
		$menu['prj_emp']['Register Mail ID']=array();
		$menu['prj_emp']['Register Mail ID'] = site_url('register_mail_id/register_mail_project_emp'); 

		$menu['email_admin']=array();
		$menu['email_admin']['Mail Admin']=array();
		$menu['email_admin']['Mail Admin'] = site_url('admin_panel_email/admin_panel_email'); 
		
        return $menu; 
	}
}
?>