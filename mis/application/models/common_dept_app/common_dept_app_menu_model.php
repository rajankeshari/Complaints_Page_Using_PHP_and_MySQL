<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of common_dept_app
 *
 * @author Ritu Raj <rituraj00@rediffmail.com>
 */
class Common_dept_app_menu_model extends CI_Model {

    function getMenu() {

        // For DR Exam
       // $menu['ft'] = array();
       // $menu['ft']['Weightage & Marks Upload'] = site_url('common_dept_app/common_dept_app');
       // $menu['ft']['Course Coordinator'] = site_url('course_coordinator/course_coordinator');
		//$menu['hod']['CBCS']['Course Component Deletion'] = site_url('marks_distribution/marks_distribution/course_component');
		
		// added by @bhi
		
	//	$menu['hod']['CBCS']['Assign Course Coordinator'] = site_url('cbcs_offered_subjects/offer_subject/course_coordinator');
	//	$menu['hod']['CBCS']['Credit Point Master'] = site_url('cbcs_credit_points_master/credit_point_master');
     //   $menu['hod']['CBCS']['Curriculam Master'] = site_url('cbcs_curriculam_master/curriculammaster');
		
	//	$menu['hod']['CBCS']['Offer Course'] = site_url('cbcs_offered_subjects/offer_subject');
	//    $menu['hod']['CBCS']['Online Course']= array();
	//	$menu['hod']['CBCS']['Online Course']['Online Course Offer'] = site_url('cbcs_subject_master/subject_master_dept/offer_online_course');
     //   $menu['hod']['CBCS']['Online Course']['Course Offer Approve'] = site_url('cbcs_subject_master/subject_master_dept/approve_online_course');
		
		// added by @bhi end
        return $menu;
    }

}

?>
