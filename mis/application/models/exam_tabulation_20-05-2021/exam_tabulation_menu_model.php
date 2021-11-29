<?php

/* tabulation process
 * Copyright (c) ISM dhanbad * 
 * @category   phpExcel
 * @package    exam_tabulation
 * @copyright  Copyright (c) 2014 - 2015 Ism dhanbad
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt    LGPL
 * @version    ##0.1##, #26/11/15#
 * @Author     Ritu raj<rituraj00@rediffmail.com>
 */

if (!defined("BASEPATH")) {
    exit("No direct script access allowed");
}

class Exam_tabulation_menu_model extends CI_Model {

    function __construct() {
        // Call the Model constructor
        parent::__construct();
    }

    function getMenu() {
        $menu = array();
		 $menu['acad_ar'] = array();
        $menu['exam_dr'] = array();
		$menu['dt'] = array();
        $menu['hod'] = array();
		//$menu['adug'] = array();
		//$menu['adpg'] = array();
        $menu['ft'] = array();
        $menu['exam_admin'] = array();
        $menu['exam_da1'] = array();
		$menu['exam_da2'] = array();
        
	//	$menu['exam_dr']["Tabulation Sheet"]["2019 onwards"] = site_url('exam_tabulation/exam_tabulation/index/cbcs');
	//	$menu['exam_dr']["Tabulation Sheet"]["2015 to 2018"] = site_url('exam_tabulation/exam_tabulation');
		
        
		
		//$menu['exam_dr']["Moderation"]= site_url('exam_moderation/moderation_admnno_wise');
        //$menu['exam_dr']["Moderation"]['Subject'] = site_url('exam_moderation/moderation_admnno_wise'); by Anuj on 29-01-2020
        //$menu['exam_dr']["Moderation"]['Aggregate'] = site_url('exam_moderation/moderation_admnno_wise_new');
	//     $menu['exam_dr']["Result"]["2019 onwards"] = site_url('result_declaration/result_declaration_drside/index/cbcs');
    //     $menu['exam_dr']["Result"]["2017 to 2018"] = site_url('result_declaration/result_declaration_drside');
	//	 $menu['exam_dr']["Result"]["2015 to 2016"] = site_url('result_declaration-old-ver/result_declaration_drside');
        //$menu['exam_dr']["Result"]["Backlog Result Freeeze"] = site_url('result_declaration-old-ver/backlogResultFreeze');
		 //$menu['exam_dr']["Result"]["Backlog Result Freeeze"] = site_url('result_declaration/backlogResultFreeze');
		 
		  $menu['admin_exam']["Exam Control"]["Result"]["2019 onwards"] = site_url('result_declaration/result_declaration_drside/index/cbcs');
         $menu['admin_exam']["Exam Control"]["Result"]["2017 to 2018"] = site_url('result_declaration/result_declaration_drside');
		 $menu['admin_exam']["Exam Control"]["Result"]["2015 to 2016"] = site_url('result_declaration-old-ver/result_declaration_drside');
		 $menu['admin_exam']["Exam Control"]["Backlog Result Freeeze(old)"] = site_url('result_declaration/backlogResultFreeze');
		 
		
		
        //$menu['exam_dr']["Grade Sheet"]["View"] = site_url('student_grade_sheet/dr_grade_sheet');    
		//$menu['exam_dr']["Grade Sheet"]["Bunch Print"] = site_url('student_grade_sheet/bunch_print_gradesheet');
        //$menu['exam_dr']["Grade Sheet"]["Remove Restriction"] = site_url('student_grade_sheet/dr_gs_remove_rest');
        //$menu['exam_dr']["Grade Sheet"]["Feedback Fine"] = site_url('student_grade_sheet/dr_gs_remove_feedback');
        /*$menu['exam_dr']["Exam Control"]['marks Finalization Control'] = site_url('exam_control/examControl');
        $menu['exam_dr']["Exam Control"]['Defaulter Control'] = site_url('exam_control/examControl/defaulterControl');
        
        $menu['exam_da1']["Exam Control"]['marks Finalization Control'] = site_url('exam_control/examControl');
        $menu['admin_exam']["Exam Control"]['marks Finalization Control'] = site_url('exam_control/examControl');
        */
        
    //   	$menu['acad_ar']["Result"]["Result Declaration"] = site_url('result_declaration/result_declaration_drside');
	//$menu['acad_ar']["Moderation"]['Subject'] = site_url('exam_moderation/moderation_admnno_wise');
        //$menu['hod']["View Result"] = site_url('result_declaration/result_declaration_drside');
		//$menu['hod']["View Result"]["Result Declaration[old]"] = site_url('result_declaration-old-ver/result_declaration_drside');
	  //   $menu['hod']["Result"]["2019 onwards"] = site_url('result_declaration/result_declaration_drside/index/cbcs');
	//	 $menu['hod']["Result"]["2017 to 2018"] = site_url('result_declaration/result_declaration_drside');
		// $menu['hod']["Result"]["2015 to 2016"] = site_url('result_declaration-old-ver/result_declaration_drside');
          //$menu['ft']["View Result"] = site_url('result_declaration/result_declaration_drside');
		  
		//  $menu['ft']["Result"]["2019 onwards"] = site_url('result_declaration/result_declaration_drside/index/cbcs');
		//  $menu['ft']["Result"]["2017 to 2018"] = site_url('result_declaration/result_declaration_drside');
		// $menu['ft']["Result"]["2015 to 2016"] = site_url('result_declaration-old-ver/result_declaration_drside');
		 
		$menu['dt']["Result"]["2019 onwards"] = site_url('result_declaration/result_declaration_drside/index/cbcs');
		$menu['dt']["Result"]["2017 to 2018"] = site_url('result_declaration/result_declaration_drside');
		$menu['dt']["Result"]["2015 to 2016"] = site_url('result_declaration-old-ver/result_declaration_drside');

      
		
	 //   $menu['exam_da1']["Tabulation Sheet"]["2019 onwards"] = site_url('exam_tabulation/exam_tabulation/index/cbcs');
	//	$menu['exam_da1']["Tabulation Sheet"]["2015 to 2018"] = site_url('exam_tabulation/exam_tabulation');
		
		
		//$menu['exam_da1']["Exam tabulation[old]"] = site_url('exam_tabulation/exam_tabulation');
		//$menu['exam_da1']["Exam tabulation[cbcs]"] = site_url('exam_tabulation/exam_tabulation/index/cbcs');
        
		
		
		
		//$menu['exam_da1']["Grade Sheet"]["Bunch Print"] = site_url('student_grade_sheet/bunch_print_gradesheet');
        //$menu['exam_da1']["Grade Sheet"]["View"] = site_url('student_grade_sheet/dr_grade_sheet');
       // $menu['exam_da1']["Restrictions"] = site_url('exam_absent_record/exam_absent');
        //$menu['exam_da1']["Grade Sheet"]["Feedback Fine"] = site_url('student_grade_sheet/dr_gs_remove_feedback');
		
		
		//$menu['exam_da2']["Grade Sheet"]["View"] = site_url('student_grade_sheet/dr_grade_sheet');
		
		
	//	  $menu['exam_da2']["Tabulation Sheet"]["2019 onwards"] = site_url('exam_tabulation/exam_tabulation/index/cbcs');
	//	$menu['exam_da2']["Tabulation Sheet"]["2015 to 2018"] = site_url('exam_tabulation/exam_tabulation');
	//	$menu['exam_da2']["Exam tabulation[old]"] = site_url('exam_tabulation/exam_tabulation');
		//$menu['exam_da2']["Exam tabulation[cbcs]"] = site_url('exam_tabulation/exam_tabulation/index/cbcs');
		
		
		
		//$menu['exam_da2']["Restrictions"] = site_url('exam_absent_record/exam_absent');
		
       // $menu['admin_exam'] = array();
        //$menu['admin_exam']["Grade Sheet"]["View"] = site_url('student_grade_sheet/dr_grade_sheet');

       // $menu['admin_exam']["Restrictions"] = site_url('exam_absent_record/exam_absent');

        //$menu['tpo'] = array();
        //$menu['tpo']["Grade Sheet"]["View"] = site_url('student_grade_sheet/dr_grade_sheet');
		
		//$menu['adug'] = array();
        //$menu['adug']["Grade Sheet"]["View"] = site_url('student_grade_sheet/dr_grade_sheet');
		
		//$menu['adpg'] = array();
       // $menu['adpg']["Grade Sheet"]["View"] = site_url('student_grade_sheet/dr_grade_sheet');
		
		//$menu['adsw'] = array();
        //$menu['adsw']["Grade Sheet"]["View"] = site_url('student_grade_sheet/dr_grade_sheet');
		
		//$menu['adsa'] = array();
        //$menu['adsa']["Grade Sheet"]["View"] = site_url('student_grade_sheet/dr_grade_sheet');
		
		//$menu['adhm'] = array();
        //$menu['adhm']["Grade Sheet"]["View"] = site_url('student_grade_sheet/dr_grade_sheet');
		 //For Dean Associate
		
		//$menu['adug']["Result"]["2019 onwards"] = site_url('result_declaration/result_declaration_drside/index/cbcs');
	//	$menu['adug']["Result"]["2017 to 2018"]= site_url('result_declaration/result_declaration_drside');
	//	$menu['adug']["Result"]["2015 to 2016"] = site_url('result_declaration-old-ver/result_declaration_drside');
		
	//	$menu['adpg']["Result"]["2019 onwards"] = site_url('result_declaration/result_declaration_drside/index/cbcs');
	//	$menu['adpg']["Result"]["2017 to 2018"]= site_url('result_declaration/result_declaration_drside');
	//	$menu['adpg']["Result"]["Result Declaration[old]"] = site_url('result_declaration-old-ver/result_declaration_drside');
		
	//	$menu['adac']["Result"]["2019 onwards"] = site_url('result_declaration/result_declaration_drside/index/cbcs');
	//	$menu['adac']["Result"]["2017 to 2018"]= site_url('result_declaration/result_declaration_drside');
	//	$menu['adac']["Result"]["2015 to 2016"] = site_url('result_declaration-old-ver/result_declaration_drside');
		
	//	$menu['dean_acad']["Result"]["2019 onwards"] = site_url('result_declaration/result_declaration_drside/index/cbcs');
	//	$menu['dean_acad']["Result"]["2017 to 2018"]= site_url('result_declaration/result_declaration_drside');
	//	$menu['dean_acad']["Result"]["2015 to 2016"] = site_url('result_declaration-old-ver/result_declaration_drside');
		
		
		
		//$menu['adug'] = array();
		//$menu['adug']["Grade Sheet"]["View"] = site_url('student_grade_sheet/dr_grade_sheet');
		
		//$menu['adpg'] = array();
		//$menu['adpg']["Grade Sheet"]["View"] = site_url('student_grade_sheet/dr_grade_sheet');
		
		//$menu['dean_acad'] = array();
		//$menu['dean_acad']["Grade Sheet"]["View"] = site_url('student_grade_sheet/dr_grade_sheet');

        return $menu;
    }

}
