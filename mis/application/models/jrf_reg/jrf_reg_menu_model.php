<?php

if (!defined("BASEPATH")) {
    exit("No direct script access allowed");
}

class Jrf_reg_menu_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function getMenu() {
        $menu = array();      
	
    	$menu['adpg'] = array();
        //$menu['adpg']['JRF Registration'] = array();
    	//$menu['adpg']['JRF Registration']['JRF Student View'] = site_url('jrf_reg/jrf_registration');
        //$menu['adpg']['JRF Registration']['JRF Student Report'] = site_url('jrf_reg/jrf_registration/showreport');
		
		$menu['adpg']['PHD Evaluation'] = array();
    	$menu['adpg']['PHD Evaluation']['Scholar View'] = site_url('jrf_reg/jrf_registration');
        $menu['adpg']['PHD Evaluation']['Scholar Report'] = site_url('jrf_reg/jrf_registration/showreport');

        $menu['acad_da'] = array();
        //$menu['acad_da']['JRF Registration'] = array();
    	//$menu['acad_da']['JRF Registration']['JRF Student View'] = site_url('jrf_reg/jrf_registration');
        //$menu['acad_da']['JRF Registration']['JRF Student Report'] = site_url('jrf_reg/jrf_registration/showreport');
		
		$menu['acad_da']['PHD Evaluation'] = array();
    	$menu['acad_da']['PHD Evaluation']['Scholar View'] = site_url('jrf_reg/jrf_registration');
        $menu['acad_da']['PHD Evaluation']['Scholar Report'] = site_url('jrf_reg/jrf_registration/showreport');
		$menu['acad_da']['PHD Evaluation']['Scholar Offline Thesis'] = site_url('jrf_reg/jrf_registration/offline_thesis');
		
		$menu['acad_da']['PHD Course Work'] = site_url('phdawarded/phd_course_details');
		$menu['acad_da']['DSC Details']['Add'] = site_url('phdawarded/dsc_entry');
		$menu['acad_da']['DSC Details']['Edit'] = site_url('phdawarded/edit_dsc_entry');
		
		$menu['acad_da']['PHD Ongoing'] = site_url('phdawarded/phdcurrent');
		$menu['acad_da']['PHD Awarded'] = site_url('phdawarded/phdawardedlist');
		$menu['acad_da']['Date of Admission Updation'] = site_url('phdawarded/student_admission_year');
		

        $menu['stu'] = array();
        $menu['stu']['PHD Progress'] = array();
    	$menu['stu']['PHD Progress'] = site_url('jrf_reg/jrf_registration');
        // $menu['adpg']['JRF Registration']['JRF Student Report'] = site_url('jrf_reg/jrf_registration/showreport');

    return $menu;
    }

}
