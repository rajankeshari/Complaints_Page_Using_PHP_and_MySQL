<?php

class Appointment_menu_model extends CI_Model
{
	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}

	function getMenu()
	{
		    // Salary Edit Protion: 'acc_hos','acc_da2','acc_sal'
		    // For Emp
        $menu['appoint_clerk']=array();
		//$menu['appoint_clerk']["appointment"]=array();
		$menu['appoint_clerk']['New Appointment'] = site_url('appointment/appoint');


		$menu['emp']=array();
		//$menu['appoint_clerk']["appointment"]=array();
		$menu['emp']['Appointment']['View Appointment'] = site_url('appointment/view_appointment');
        return $menu;
	}
}
?>