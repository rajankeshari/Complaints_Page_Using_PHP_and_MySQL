<?php

if (!defined("BASEPATH")) {
    exit("No direct script access allowed");
}

class Admission_new_process_menu_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function getMenu() {
        $menu = array();

        $menu['adm_all'] = array();
        //$menu['adm_all']['All Admission'] = array();
	//$menu['adm_all']['All Admission']['JEE'] = site_url('admission_new_process/jee_student_transfer');
        $menu['adm_all']['All Admission'] = site_url('admission_new_process/jee_student_transfer');

        return $menu;
    }

}
