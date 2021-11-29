<?php

class Reg_exam_rc_form_menu_model extends CI_Model
{
    function __construct(){
		// Call the Model constructor
		parent::__construct();
	}
       
    function getMenu(){
        
        //auth ==> acad_ar,
		$menu['acad_ar']=array();
		//$menu['acad_ar']["Regular & carryover Exam Form"]["Exam Reg. Date"] = site_url('reg_exam_rc_form/date');
	//28-10-2019	$menu['acad_ar']["Regular & carryover Registration/Exam Form"]["Forms approval"] = site_url('reg_exam_rc_form/acdamic_check');
      //28-10-2019  $menu['acad_ar']['Regular & carryover Registration/Exam Form']['Direct Submission'] = site_url('reg_exam_rc_form/reg_form_dept');
     //28-10-2019   $menu['acad_ar']["Regular & carryover Registration/Exam Form"]["JRF Semester Registration Forms"] = site_url('reg_exam_rc_form/acdamic_check_jrf_sem_reg_frm');

        $menu['exam_dr']=array();
     // $menu['exam_dr']["Regular & carryover Exam Form"]["Exam Reg. Date"] = site_url('reg_exam_rc_form/date');
	//28-10-2019	$menu['exam_dr']["Regular & carryover Registration/Exam Form"]["Forms approval"] = site_url('reg_exam_rc_form/acdamic_check');
      //28-10-2019  $menu['exam_dr']['Regular & carryover Registration/Exam Form']['Direct Submission'] = site_url('reg_exam_rc_form/reg_form_dept');
      //28-10-2019  $menu['exam_dr']["Regular & carryover Registration/Exam Form"]["JRF Semester Registration Forms"] = site_url('reg_exam_rc_form/acdamic_check_jrf_sem_reg_frm');

        $menu['acad_dr']=array();
      //28-10-2019  $menu['acad_dr']["Regular & carryover Registration/Exam Form"]["Forms approval"] = site_url('reg_exam_rc_form/acdamic_check');
	//28-10-2019	$menu['acad_dr']['Regular & carryover Registration/Exam Form']['Direct Submission'] = site_url('reg_exam_rc_form/reg_form_dept');
		//28-10-2019$menu['acad_dr']["Regular & carryover Registration/Exam Form"]["JRF Semester Registration Forms"] = site_url('reg_exam_rc_form/acdamic_check_jrf_sem_reg_frm');


        $menu['acad_da']=array();
       //28-10-2019 $menu['acad_da']['Regular & carryover Registration/Exam Form']['Direct Submission'] = site_url('reg_exam_rc_form/reg_form_dept');

        $menu['hod']=array();
	  //$menu['hod']["Regular & carryover Exam Form date"]["Summer Reg. Date"] = site_url('reg_exam_rc_form/date');
	//28-10-2019	$menu['hod']["Regular & carryover Registration/Exam Form"]["Forms"] = site_url('reg_exam_rc_form/department_check');
	//28-10-2019	$menu['hod']["Regular & carryover Registration/Exam Form"]["JRF Semester Registration Forms"] = site_url('jrf_coordinator/form_check_jrf_sem_reg');
               
     //   $menu['stu']=array();
	//	$menu['stu']["Exam Form"]["Exam Form / Status"] = site_url('reg_exam_rc_form/reg_form');
	 //   $menu['stu']["JRF Semester Registration"]["Semester Registration / Status(With Paper)"] = site_url('reg_exam_rc_form/reg_form');
	//	$menu['stu']["JRF Semester Registration"]["Semester Registration / Status(Without Paper)"] = site_url('reg_exam_rc_form/reg_form_wpaper');
		
		$menu['adug']=array();
		//28-10-2019
	//$menu['adug']["Regular & carryover Registration/Exam Form"]["Forms approval"] = site_url('reg_exam_rc_form/acdamic_check');
	//$menu['adug']["Regular & carryover Registration/Exam Form"]["JRF Semester Registration Forms"] = site_url('reg_exam_rc_form/acdamic_check_jrf_sem_reg_frm');
		
		$menu['adpg']=array();
	//28-10-2019
	//$menu['adpg']["Regular & carryover Registration/Exam Form"]["Forms approval"] = site_url('reg_exam_rc_form/acdamic_check');
	//$menu['adpg']["Regular & carryover Registration/Exam Form"]["JRF Semester Registration Forms"] = site_url('reg_exam_rc_form/acdamic_check_jrf_sem_reg_frm');
		
	/*	28-10-2019
	$menu['acad_da2']=array();
		$menu['acad_da2']["Regular & carryover Registration/Exam Form"]["Forms approval"] = site_url('reg_exam_rc_form/acdamic_check');
		$menu['acad_da2']["Regular & carryover Registration/Exam Form"]["JRF Semester Registration Forms"] = site_url('reg_exam_rc_form/acdamic_check_jrf_sem_reg_frm');*/
		
                return $menu;
    }


}

?>
