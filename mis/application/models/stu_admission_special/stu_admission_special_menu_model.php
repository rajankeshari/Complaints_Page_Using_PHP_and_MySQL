<?php


if(!defined("BASEPATH")){ exit("No direct script access allowed"); }

class Stu_admission_special_menu_model extends CI_Model
{
	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}

	
        function getMenu()
	{
		$menu=array();	
// 28-10-2019

/*		
                $menu['admin_exam']=array();
$menu['admin_exam']["Permitted Student Registration"]['Registration'] = site_url('stu_admission_special/admission_special');		
$menu['admin_exam']["Permitted Student Registration"]['Print'] = site_url('stu_admission_special/admission_special_print');	
$menu['admin_exam']["Permitted Student Registration"]['Upload Marks'] = site_url('stu_admission_special/upload_marks');

$menu['acad_ar']=array();
$menu['acad_ar']["Permitted Student Registration"]['Registration'] = site_url('stu_admission_special/admission_special');		
$menu['acad_ar']["Permitted Student Registration"]['Print'] = site_url('stu_admission_special/admission_special_print');	

$menu['hod']=array();
$menu['hod']["Permitted Student Registration"]['Registration'] = site_url('stu_admission_special/admission_special');		
$menu['hod']["Permitted Student Registration"]['Print'] = site_url('stu_admission_special/admission_special_print');

$menu['fa']=array();
$menu['fa']["Permitted Student Registration"]['Registration'] = site_url('stu_admission_special/admission_special');		
$menu['fa']["Permitted Student Registration"]['Print'] = site_url('stu_admission_special/admission_special_print');	

$menu['exam_dr']=array();
$menu['exam_dr']["Permitted Student Registration"]['Registration'] = site_url('stu_admission_special/admission_special');		
$menu['exam_dr']["Permitted Student Registration"]['Print'] = site_url('stu_admission_special/admission_special_print');

$menu['exam_da1']=array();
$menu['exam_da1']["Permitted Student Registration"]['Registration'] = site_url('stu_admission_special/admission_special');		
$menu['exam_da1']["Permitted Student Registration"]['Print'] = site_url('stu_admission_special/admission_special_print');
		
		//$menu['stu']=array();
		//$menu['Semester Registration']=array();
		
//$menu['stu']["Print Summer Registration"] = site_url('stu_admission_special/admission_special_print');	
	*/
	
               
		return $menu;                                                
	}
}