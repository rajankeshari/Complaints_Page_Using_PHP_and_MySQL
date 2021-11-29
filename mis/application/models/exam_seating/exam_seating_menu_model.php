<?php

class Exam_seating_menu_model extends CI_Model
{
	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}

	function getMenu()
	{
		$menu=array();
		//auth ==> exam_dr
		$menu['exam_dr']=array();
		$menu['exam_dr']["Exam seating"] = site_url('exam_seating/exam_seating');
		
		//auth ==> exam_da1
		$menu['exam_da1']=array();
		$menu['exam_da1']["Exam seating"] = site_url('exam_seating/exam_seating');

		return $menu;
	}
}
?>