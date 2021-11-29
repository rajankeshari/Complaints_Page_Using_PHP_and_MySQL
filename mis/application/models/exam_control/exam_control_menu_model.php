<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of exam_control_menu_model
 *
 * @author Ritu Raj <rituraj00@rediffmail.com>
 */
if (!defined("BASEPATH")) {
    exit("No direct script access allowed");
}

class exam_control_menu_model extends CI_Model {

    function __construct() {
        // Call the Model constructor
        parent::__construct();
    }

    function getMenu() {
        $menu = array();
        
        $menu['hod'] = array();
        $menu['ft'] = array();
        $menu['exam_admin'] = array();
		$menu['acad_ar'] = array();
			$menu['dsw'] = array();
				$menu['adsw'] = array();
				$menu['dsw_da1'] = array();
		
       // $menu['sw_ar'] = array();
		
	//	$menu['exam_dr'] = array();
     //   $menu['exam_dr']["Exam Control_New"]['marks Finalization Control'] = site_url('exam_control/examControl');  
	//	$menu['exam_dr']["Exam Control_New"]['ogpa match'] = site_url('exam_control/examControl/showing_ogpa_matching_form');
	//	$menu['exam_dr']["Exam Control_New"]['foil & description match'] = site_url('exam_control/examControl/showing_foil_foil_desc_matching_form');
	//	$menu['exam_dr']["Exam Control_New"]['List Of Registered Students'] = site_url('exam_control/examControl/showing_List_of_Registered_Students_form');
	//	$menu['exam_dr']["Exam Control_New"]['Defaulter Control'] = site_url('exam_control/examControl/defaulterControl');
		
		
	//	$menu['dsw']["Exam Control_New"]['List Of Registered Students'] = site_url('exam_control/examControl/showing_List_of_Registered_Students_form');
	//	$menu['adsw']["Exam Control_New"]['List Of Registered Students'] = site_url('exam_control/examControl/showing_List_of_Registered_Students_form');
	//	$menu['dsw_da1']["Exam Control_New"]['List Of Registered Students'] = site_url('exam_control/examControl/showing_List_of_Registered_Students_form');
		
	//	$menu['sw_ar']["Exam Control_New"]['List Of Registered Students'] = site_url('exam_control/examControl/showing_List_of_Registered_Students_form');
		

	//	$menu['exam_da1'] = array();
    //    $menu['exam_da1']["Exam Control"]['marks Finalization Control'] = site_url('exam_control/examControl');		
	//	$menu['exam_da1']["Exam Control"]['ogpa match'] = site_url('exam_control/examControl/showing_ogpa_matching_form');
	//	$menu['exam_da1']["Exam Control"]['foil & description match'] = site_url('exam_control/examControl/showing_foil_foil_desc_matching_form');
	//	$menu['exam_da1']["Exam Control_New"]['List Of Registered Students'] = site_url('exam_control/examControl/showing_List_of_Registered_Students_form');
		

		
		
		
		
		$menu['exam_da2']["Exam Control"]['ogpa match'] = site_url('exam_control/examControl/showing_ogpa_matching_form');
		$menu['exam_da2']["Exam Control_New"]['List Of Registered Students'] = site_url('exam_control/examControl/showing_List_of_Registered_Students_form');
		$menu['adad_da2']["Exam Control_New"]['List Of Registered Students'] = site_url('exam_control/examControl/showing_List_of_Registered_Students_form');
		$menu['adad_dr']["Exam Control_New"]['List Of Registered Students'] = site_url('exam_control/examControl/showing_List_of_Registered_Students_form');
		$menu['exam_ar']["Exam Control_New"]['List Of Registered Students'] = site_url('exam_control/examControl/showing_List_of_Registered_Students_form');
		
        $menu['admin_exam']["Exam Control"]['marks Finalization Control'] = site_url('exam_control/examControl');                
		$menu['acad_ar']["Exam Control"]['marks Finalization Control'] = site_url('exam_control/examControl');                
       	$menu['acad_ar']["Exam Control"]['Defaulter Control'] = site_url('exam_control/examControl/defaulterControl');
		
		$menu['admin_exam']["Exam Control"]['ogpa match'] = site_url('exam_control/examControl/showing_ogpa_matching_form');
		$menu['admin_exam']["Exam Control"]['foil & description match'] = site_url('exam_control/examControl/showing_foil_foil_desc_matching_form');
        $menu['admin_exam']["Exam Control"]['backend foil tarnsfer'] = site_url('course_coordinator/course_coordinator/backend_foil_transfer_form');
		$menu['admin_exam']["Exam Control"]["Freeeze_transfer(After result)"] = site_url('result_declaration/backlogResultFreeze/index/cbcs');
		$menu['admin_exam']["Exam Control"]['Release Grades'] = site_url('course_coordinator/course_coordinator/backend_foil_transfer_form/rg');
		$menu['admin_exam']["Exam Control"]['Marks Monitor'] = site_url('course_coordinator/course_coordinator/backend_foil_transfer_form/mm');
        
        return $menu;
    }

}


