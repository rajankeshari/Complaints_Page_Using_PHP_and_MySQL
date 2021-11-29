<?php

class Class_engaged_menu_model extends CI_Model
{
	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}

	function getMenu()
	{
		
		// For DR Acad
        /*$menu['dean_acad']=array();
		$menu['dean_acad']['Class Engaged']=array();
		$menu['dean_acad']["Class Engaged"] = site_url('class_engaged/class_engaged_details');    
         */       
        /*$menu['hod']=array();
		$menu['hod']['Class Engaged']=array();
		$menu['hod']["Class Engaged"] = site_url('class_engaged/class_engaged_details'); 
		*/
		
		$menu['dt']=array();
		$menu['dt']['Class Engaged']=array();
		$menu['dt']["Class Engaged"] = site_url('class_engaged/class_engaged_details'); 
		return $menu;
	}
}
?>