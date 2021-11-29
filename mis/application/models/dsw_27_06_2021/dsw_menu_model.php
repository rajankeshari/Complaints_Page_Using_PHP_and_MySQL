<?php

if (!defined("BASEPATH")) {
    exit("No direct script access allowed");
}

class Dsw_menu_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function getMenu() {
        $menu = array();      
	
    	// $menu['acad_ar'] = array();
     //    $menu['acad_ar']['Semester Fee Interface'] = array();
    	// $menu['acad_ar']['Semester Fee Interface'] = site_url('semester_fee_interface/semester_fee_interface');

        $menu['adsw'] = array();
        $menu['adsw']['Fee Waiver'] = array();
        $menu['adsw']['Fee Waiver']['Get Student List'] = site_url('dsw/dsw_fee_waiver');
        $menu['adsw']['Fee Waiver']['Upload Waiver List'] = site_url('dsw/dsw_fee_waiver/fee_waiver_list');
        $menu['adsw']['Fee Waiver']['Waiving Student List'] = site_url('dsw/dsw_fee_waiver/waive_student');

        // $menu['exam_dr'] = array();
        // $menu['exam_dr']['Semester Fee Interface'] = array();
        // $menu['exam_dr']['Semester Fee Interface'] = site_url('semester_fee_interface/semester_fee_interface');

    return $menu;
    }

}
