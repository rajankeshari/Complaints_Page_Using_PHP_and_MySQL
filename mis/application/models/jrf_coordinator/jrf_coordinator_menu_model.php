<?php

class Jrf_coordinator_menu_model extends CI_Model
{
	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}

	function getMenu()
	{
		
		//auth ==> jcord,
		$menu = array();
		$menu['jcord']=array();
		$menu['jcord']["Form Approval"]=array();
		$menu['jcord']["Form Approval"]["Exam Forms"] = site_url('jrf_coordinator/form_check');
		
		$menu['jcord']["Form Approval"]["JRF Semester Registration Forms"] = site_url('jrf_coordinator/form_check_jrf_sem_reg');
		
		return $menu;
	}
}