<?php

if (!defined("BASEPATH")) {
    exit("No direct script access allowed");
}

class Consultancy_menu_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function getMenu() {
        $menu = array();

               
        $menu['emp'] = array();
        $menu['emp']['Consultancy'] = array();
	    $menu['emp']['Consultancy']['Manage Consultancy'] = site_url('consultancy/consultancy/manage_member_data');
        
        $menu['dean_rnd'] = array();
        $menu['dean_rnd']['Consultancy'] = array();
		$menu['dean_rnd']['Consultancy']['Add Consultancy'] = site_url('consultancy/consultancy/add');
	    $menu['dean_rnd']['Consultancy']['Manage Consultancy'] = site_url('consultancy/consultancy/manageOldRecords');
        // Added by CK on 22 June 2019
		$menu['dean_rnd']['Consultancy']['Manage Consultancy Employee Wise'] = site_url('consultancy/consultancy/manageRecordFilter');



        return $menu;
    }

}
