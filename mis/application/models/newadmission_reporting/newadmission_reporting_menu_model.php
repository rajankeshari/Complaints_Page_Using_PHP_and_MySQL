<?php

if (!defined("BASEPATH")) {
    exit("No direct script access allowed");
}

class Newadmission_reporting_menu_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function getMenu() 
    {
        $menu = array();

        $menu['new_adm_rpt_academic'] = array();
        $menu['new_adm_rpt_academic']['Newadmission Report'] = array();
        $menu['new_adm_rpt_academic']['Newadmission Report']['From Academic'] = site_url('newadmission_reporting/academic_report');

        $menu['new_adm_rpt_hostel'] = array();
        $menu['new_adm_rpt_hostel']['Newadmission Report'] = array();
        $menu['new_adm_rpt_hostel']['Newadmission Report']['From Hostel'] = site_url('newadmission_reporting/hostel_report');
        
        $menu['new_adm_rpt_admin'] = array();
        $menu['new_adm_rpt_admin']['Newadmission Report'] = array();
        $menu['new_adm_rpt_admin']['Newadmission Report']['From Academic'] = site_url('newadmission_reporting/academic_report');
        $menu['new_adm_rpt_admin']['Newadmission Report']['From Hostel'] = site_url('newadmission_reporting/hostel_report');
        $menu['new_adm_rpt_admin']['Newadmission Report']['Student Not Reported'] = site_url('newadmission_reporting/not_reported');

        return $menu;
    }

}
