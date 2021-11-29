<?php

if (!defined("BASEPATH")) {
    exit("No direct script access allowed");
}

class cbcs_credit_points_policy_menu_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function getMenu() {
        $menu = array();


// DEO LEVEL

       $menu['deo'] = array();
       $menu['deo']['CBCS'] = array();
	     $menu['deo']['CBCS']['Credit Point Policy'] = site_url('cbcs_credit_points_policy/credit_point_policy');
       $menu['deo']['CBCS']['Curriculam Policy'] = site_url('cbcs_curriculam_policy/curriculam');
       $menu['deo']['CBCS']['Course Structure Policy'] = site_url('cbcs_coursestructure_policy/coursestructure');

       $menu['deo']['CBCS']['Credit Point Master'] = site_url('cbcs_credit_points_master/credit_point_master');
       $menu['deo']['CBCS']['Curriculam Master'] = site_url('cbcs_curriculam_master/curriculammaster');

	     $menu['deo']['CBCS']['Course Master']= array();
       $menu['deo']['CBCS']['Course Master']['Course Wise'] = site_url('cbcs_subject_master/subject_master_dept');
       $menu['deo']['CBCS']['Course Master']['Upload'] = site_url('cbcs_subject_master/subject_master_upload');
		$menu['deo']['CBCS']['Student Course Mapping'] = site_url('cbcs_offered_subjects/offer_subject/opted_subject');

       
     
       $menu['deo']['CBCS']['Common Course Policy'] = site_url('cbcs_common_policy/common_policy');
	   //$menu['deo']['CBCS']['Offer Course'] = site_url('cbcs_offered_subjects/offer_subject');
	   // $menu['hod']['CBCS']['CBCS Course Code'] = site_url('cbcs_offered_subjects/offer_subject/upload_course_code');
	//	$menu['hod']['CBCS']['Assign Course Coordinator'] = site_url('cbcs_offered_subjects/offer_subject/course_coordinator');


//Dean Academic Level

      // $menu['dean_acad'] = array();
       //$menu['dean_acad']['CBCS'] = array();
	   //$menu['dean_acad']['CBCS']['Credit Point Policy'] = site_url('cbcs_credit_points_policy/credit_point_policy');
	 //  $menu['dean_acad']['CBCS']['Curriculam Policy'] = site_url('cbcs_curriculam_policy/curriculam');
     //  $menu['dean_acad']['CBCS']['Common (Institute Core) Policy'] = site_url('cbcs_policy/institute_core_policy');
      // $menu['dean_acad']['CBCS']['Common (Institute Core) Master'] = site_url('cbcs_master/institute_core_master');
     //  $menu['dean_acad']['CBCS']['Common Course Structure Policy'] = site_url('cbcs_coursestructure_policy/comm_coursestructure');
     //  $menu['dean_acad']['CBCS']['Offer Common Course'] = site_url('cbcs_offered_subjects/offer_subject_common');
       //$menu['dean_acad']['CBCS']['Curriculam Policy'] = site_url('cbcs_curriculam_policy/curriculam');
	 //  $menu['dean_acad']['CBCS']['Course Master']= array();
	   //$menu['dean_acad']['CBCS']['Course Master']['Course Wise'] = site_url('cbcs_subject_master/subject_master_dept');
       //$menu['dean_acad']['CBCS']['Course Master']['Upload'] = site_url('cbcs_subject_master/subject_master_upload');
	   //$menu['hod']['CBCS']['Opted Subject'] = site_url('cbcs_offered_subjects/offer_subject_common/opted_subject');
       



// HOD LEVEL

	   //$menu['hod']['CBCS']['Offer Course'] = site_url('cbcs_offered_subjects/offer_subject');
       //$menu['hod']['CBCS']['Curriculam Policy'] = site_url('cbcs_curriculam_policy/curriculam');
	 //  $menu['hod']['CBCS']['Credit Point Master'] = site_url('cbcs_credit_points_master/credit_point_master');
     //  $menu['hod']['CBCS']['Curriculam Master'] = site_url('cbcs_curriculam_master/curriculammaster');
	 //  $menu['hod']['CBCS']['Course Master']= array();
	//   $menu['hod']['CBCS']['Course Master']['Course Wise'] = site_url('cbcs_subject_master/subject_master_dept');
     //  $menu['hod']['CBCS']['Course Master']['Upload'] = site_url('cbcs_subject_master/subject_master_upload');
	 //  //$menu['hod']['CBCS']['Opted Subject'] = site_url('cbcs_offered_subjects/offer_subject_common/opted_subject');
	/*   $menu['hod']['CBCS']['Offer Course'] = site_url('cbcs_offered_subjects/offer_subject');
	     $menu['hod']['CBCS']['Online Course']= array();
		  $menu['hod']['CBCS']['Online Course']['Online Course Offer'] = site_url('cbcs_subject_master/subject_master_dept/offer_online_course');
          $menu['hod']['CBCS']['Online Course']['Course Offer Approve'] = site_url('cbcs_subject_master/subject_master_dept/approve_online_course'); */

       
// Associate Dean Level

	    //    $menu['adug'] = array();
        //  $menu['adug']['CBCS'] = array();
		  
        //  $menu['adug']['CBCS']['Course Structure Policy'] = site_url('cbcs_coursestructure_policy/coursestructure');
		//  $menu['adug']['CBCS']['Offer Common Course'] = site_url('cbcs_offered_subjects/offer_subject_common');
          


		  //$menu['adug']['CBCS']['Subject Transfer'] = site_url('cbcs_subject_transfer/cbcs_regular_subject_transfer');
          //$menu['adug']['CBCS']['Subject Transfer Course Wise'] = site_url('cbcs_subject_transfer/cbcs_regular_subject_transfer_new');
          //$menu['adug']['CBCS']['Subject Transfer Admission Number Wise'] = site_url('cbcs_subject_transfer/cbcs_regular_subject_transfer_admn_wise');



		//    $menu['adpg'] = array();
     //   $menu['adpg']['CBCS'] = array();
     //   $menu['adpg']['CBCS']['Course Structure Policy'] = site_url('cbcs_coursestructure_policy/coursestructure');

        //$menu['adpg']['CBCS']['Subject Master']= array();
        //$menu['adpg']['CBCS']['Subject Master']['Course Wise'] = site_url('cbcs_subject_master/subject_master_dept');
        //$menu['adpg']['CBCS']['Subject Master']['Upload'] = site_url('cbcs_subject_master/subject_master_upload');

        //$menu['adpg']['CBCS']['Offer Course'] = site_url('cbcs_offered_subjects/offer_subject');
        
    		//$menu['adpg']['CBCS']['Subject Transfer'] = site_url('cbcs_subject_transfer/cbcs_regular_subject_transfer');
        //$menu['adpg']['CBCS']['Subject Transfer Course Wise'] = site_url('cbcs_subject_transfer/cbcs_regular_subject_transfer_new');
        //$menu['adpg']['CBCS']['Subject Transfer Admission Number Wise'] = site_url('cbcs_subject_transfer/cbcs_regular_subject_transfer_admn_wise');

        
		
		$menu['dpgc'] = array();
        $menu['dpgc']['CBCS'] = array();
		//$menu['dpgc']['CBCS']['Offer Course'] = site_url('cbcs_offered_subjects/offer_subject');
		
		$menu['dugc'] = array();
        $menu['dugc']['CBCS'] = array();
		//$menu['dugc']['CBCS']['Offer Course'] = site_url('cbcs_offered_subjects/offer_subject');
		
		$menu['acad_dr'] = array();
        $menu['acad_dr']['CBCS'] = array();
		$menu['acad_dr']['CBCS']['Offer Course'] = site_url('cbcs_offered_subjects/offer_subject');


        return $menu;
    }

}
