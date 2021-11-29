<?php 

class marks_submit_menu_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}
	function getMenu()
	{
		$menu = array();
		$menu['ft'] = array();
	//	$menu['ft']['Upload Marks']= site_url('marks_submit/marks_main/');
	//	$menu['ft']['Upload/Print Marks']= site_url('marks_submit/marks_main/');  //18-07-2019
		
	//	$menu['exam_da1'] = array();
	//	$menu['exam_da1']['Upload Marks']= site_url('marks_submit/marks_admin/');
		
		
		//$menu['exam_dr'] = array();
		//$menu['exam_dr']['Upload Marks']= site_url('marks_submit/marks_admin/');
		
		
		//$menu['admin_exam'] = array();
	//	$menu['admin_exam']['Upload Marks']= site_url('marks_submit/marks_admin/');
		

		return $menu;
	}   
}

?>