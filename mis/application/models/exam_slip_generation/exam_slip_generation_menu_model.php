<?php if(!defined("BASEPATH")){ exit("No direct script access allowed"); }

class Exam_slip_generation_menu_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}

	function getMenu()
	{
		$menu=array();
		
		//$menu['exam_dr']=array();
		//$menu['exam_dr']['Generate Exam Hall Sticker'] = site_url('exam_slip_generation/exam_slip');
                
                // Dipankar sir
                $menu['admin_exam']=array();
		$menu['admin_exam']['Generate Exam Hall Sticker'] = site_url('exam_slip_generation/exam_slip');
                
                // Choudhary ji
                $menu['exam_da1']=array();
		$menu['exam_da1']['Generate Exam Hall Sticker'] = site_url('exam_slip_generation/exam_slip');
		

		
		return $menu;
	}
}

