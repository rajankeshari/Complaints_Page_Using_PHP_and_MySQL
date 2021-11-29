<?php

if (!defined("BASEPATH")) {
    exit("No direct script access allowed");
}

class transcript_menu_model extends CI_Model {

    function __construct() {
        // Call the Model constructor
        parent::__construct();
    }

    function getMenu() {
        $menu = array();
        //auth ==> ft
//		$menu['ft']=array();
//		$menu['ft']['Generate Transcript']['Admission Number Wise'] = site_url('transcript/main');
//		$menu['ft']['Generate Transcript']['Bunch Print'] = site_url('transcript/transcript_bunch');
        $menu['exam_dr'] = array();
        $menu['exam_dr']['Bonafide Student'] = site_url('transcript/bonafide_student');
        $menu['exam_dr']['Provisional Certificate']['Admission Number Wise'] = site_url('transcript/pro_certificate');
        $menu['exam_dr']['Generate Transcript']['Bunch Print'] = site_url('transcript/transcript_bunch');




        $menu['exam_da1'] = array();
        $menu['exam_da1']['Bonafide Student'] = site_url('transcript/bonafide_student');
        //$menu['exam_da1']['Provisional Certificate'] = site_url('transcript/pro_certificate');
        $menu['exam_da1']['Generate Transcript']['Bunch Print'] = site_url('transcript/transcript_bunch');
        $menu['exam_da1']['Generate Transcript']['Transcript'] = site_url('transcript/transcript_single');

		
		
		    $menu['exam_da2'] = array();
        $menu['exam_da2']['Bonafide Student'] = site_url('transcript/bonafide_student');
        //$menu['exam_da1']['Provisional Certificate'] = site_url('transcript/pro_certificate');
        $menu['exam_da2']['Generate Transcript']['Bunch Print'] = site_url('transcript/transcript_bunch');
        $menu['exam_da2']['Generate Transcript']['Transcript'] = site_url('transcript/transcript_single');
		

        $menu['acad_ar'] = array();
        $menu['acad_ar']['Bonafide Student'] = site_url('transcript/bonafide_student');
        
        $menu['acad_da1'] = array();
        $menu['acad_da1']['Bonafide Student'] = site_url('transcript/bonafide_student');
        
        
        //$menu['acad_ar']['Provisional Certificate'] = site_url('transcript/pro_certificate');
//                $menu['admin_exam']=array();
//              //  $menu['admin_exam']['Generate Transcript']['Admission Number Wise'] = site_url('transcript/main');
//		$menu['admin_exam']['Generate Transcript']['Bunch Print'] = site_url('transcript/transcript_bunch');
//                $menu['admin_exam']['Generate Transcript']['TPS'] = site_url('transcript/main_tps');
//                $menu['admin_exam']['Provisional Certificate']['Admission Number Wise'] = site_url('transcript/pro_certificate');
//                $menu['admin_exam']['Provisional Certificate']['Bunch Print'] = site_url('transcript/pro_certificate_bunch');


        return $menu;
    }

}
