<?php

class Exam_schedule_menu_model extends CI_Model
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
		$menu['exam_dr']["Exam Schedule"]=array();

		$menu['exam_dr']["Exam Schedule"]["Generate"]=array();
		$menu['exam_dr']["Exam Schedule"]["Generate"]["common"] = site_url('exam_schedule/generate_comm');
		$menu['exam_dr']["Exam Schedule"]["Generate"]["others"] = site_url('exam_schedule/generate');
		$menu['exam_dr']["Exam Schedule"]["Download"] = site_url('exam_schedule/download');
		
		
		//,'exam_da1'
		$menu['exam_da1']["Exam Schedule"]["Generate"]=array();
		$menu['exam_da1']["Exam Schedule"]["Generate"]["common"] = site_url('exam_schedule/generate_comm');
		$menu['exam_da1']["Exam Schedule"]["Generate"]["others"] = site_url('exam_schedule/generate');
		$menu['exam_da1']["Exam Schedule"]["Download"] = site_url('exam_schedule/download');
		return $menu;
	}
}
?>