<?php

class Venue_edit_menu_model extends CI_Model
{
	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}

	function getMenu()
	{
		$menu=array();
		
		//$menu['hod']=array();
		//$menu['hod']["Venue Edit"] = site_url('venue_edit/venue_edit');

		//$menu['exam_dr']=array();
		//$menu['exam_dr']["Venue Edit"] = site_url('venue_edit/venue_edit');
		
		//auth ==> exam_da1
		//$menu['exam_da1']=array();
		//$menu['exam_da1']["Venue Edit"] = site_url('venue_edit/venue_edit');
		
		$menu['ttch']=array();
		$menu['ttch']["Venue Edit"] = site_url('venue_edit/venue_edit');
		
		//$menu['ttc']=array();
	//	$menu['ttc']["Venue Edit"] = site_url('venue_edit/venue_edit');

		return $menu;
	}
}
?>