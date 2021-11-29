<?php
if(!defined("BASEPATH")){ exit("No direct script access allowed"); } 

class Time_table_menu_model extends CI_Model{

	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}

	function getMenu(){

		$menu = array();

		$menu['hod']=array();
		$menu['hod']['Time Table']=array();
		// $menu['hod']['Time Table']['Master Structure'] = site_url('time_table/slotedit/master_structure_edit');
		// $menu['hod']['Time Table']['Slot Edit'] = site_url('time_table/slotedit');
		//$menu['hod']['Time Table']['Map'] = site_url('time_table/departmentwise');
		// $menu['hod']['Time Table']['Temp Derive Table'] = site_url('time_table/slotedit/tempderive');
		//$menu['hod']['Time Table']['Map CBCS'] = site_url('time_table/cbcs_departmentwise');
		//$menu['hod']['Time Table']['Map Non-CBCS'] = site_url('time_table/old_departmentwise');		
		$menu['hod']['Time Table']['View CBCS'] = site_url('time_table/view_time_table');
		$menu['hod']['Time Table']['View Non-CBCS'] = site_url('time_table/view_time_table_old');
		$menu['hod']['Time Table']["Venue Information"] = site_url('venue_edit/venue_edit');



		$menu['ttc']=array();
		$menu['ttc']['Time Table']=array();
		// $menu['hod']['Time Table']['Master Structure'] = site_url('time_table/slotedit/master_structure_edit');
		// $menu['hod']['Time Table']['Slot Edit'] = site_url('time_table/slotedit');
		//$menu['ttc']['Time Table']['Map'] = site_url('time_table/departmentwise');
		// $menu['hod']['Time Table']['Temp Derive Table'] = site_url('time_table/slotedit/tempderive');
		$menu['ttc']['Time Table']['Map CBCS'] = site_url('time_table/cbcs_departmentwise');
		$menu['ttc']['Time Table']['Map Non-CBCS'] = site_url('time_table/old_departmentwise');
		$menu['ttc']['Time Table']['View CBCS'] = site_url('time_table/view_time_table');
		$menu['ttc']['Time Table']['View Non-CBCS'] = site_url('time_table/view_time_table_old');
		$menu['ttc']['Time Table']["Venue Information"] = site_url('venue_edit/venue_edit');


		$menu['ttch']=array();
		$menu['ttch']['Time Table']=array();
		$menu['ttch']['Time Table']['Master Structure'] = site_url('time_table/slotedit/master_structure_edit');
		$menu['ttch']['Time Table']['Slot Edit'] = site_url('time_table/slotedit');
		//$menu['ttch']['Time Table']['Map CBCS'] = site_url('time_table/cbcs_departmentwise');
		//$menu['ttch']['Time Table']['Map Non-CBCS'] = site_url('time_table/old_departmentwise');
		$menu['ttch']['Time Table']['View CBCS'] = site_url('time_table/view_time_table');
		$menu['ttch']['Time Table']['View Non-CBCS'] = site_url('time_table/view_time_table_old');
		$menu['ttch']['Time Table']["Venue Information"] = site_url('venue_edit/venue_edit');
		
		
		//$menu['stu']=array();
		//$menu['stu']['Time Table']=array();		
		//$menu['stu']['Time Table']['View CBCS'] = site_url('time_table/view_time_table');
		///$menu['stu']['Time Table']['View Non-CBCS'] = site_url('time_table/view_time_table_old');
		
		
		//$menu['ft']=array();
		//$menu['ft']['Time Table']=array();		
		//$menu['ft']['Time Table']['View CBCS'] = site_url('time_table/view_time_table');
		//$menu['ft']['Time Table']['View Non-CBCS'] = site_url('time_table/view_time_table_old');
		

		
		$menu['exam_dr']=array();
		$menu['exam_dr']['Time Table']["Venue Information"] = site_url('venue_edit/venue_edit');
		
		//auth ==> exam_da1
		$menu['exam_da1']=array();
		$menu['exam_da1']['Time Table']["Venue Information"] = site_url('venue_edit/venue_edit');



		return $menu;

	}









}











?>