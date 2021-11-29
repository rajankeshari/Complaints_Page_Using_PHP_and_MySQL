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
        $menu['adpg']['JRF Registration'] = array();
    	$menu['adpg']['JRF Registration']['JRF Student View'] = site_url('jrf_reg/jrf_registration');
        $menu['adpg']['JRF Registration']['JRF Student Report'] = site_url('jrf_reg/jrf_registration/showreport');

        $menu['stu'] = array();
        $menu['stu']['PHD Progress'] = array();
    	$menu['stu']['PHD Progress'] = site_url('jrf_reg/jrf_registration/student_view');
        // $menu['adpg']['JRF Registration']['JRF Student Report'] = site_url('jrf_reg/jrf_registration/showreport');

    return $menu;
    }

}
