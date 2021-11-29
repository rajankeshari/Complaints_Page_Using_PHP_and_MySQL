<?php

if (!defined("BASEPATH")) {
    exit("No direct script access allowed");
}

class Cgpa_correction_menu_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function getMenu() {
        $menu = array();
		    $menu['dean_acad'] = array();
        $menu['dean_acad']['Examination']['CGPA Correction Control'] = site_url('cgpa_correction/cgpa_correction');
        $menu['admin_exam']['CGPA Correction Control'] = site_url('cgpa_correction/cgpa_correction');
        return $menu;
    }

}
