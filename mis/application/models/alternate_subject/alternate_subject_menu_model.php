<?php

if (!defined("BASEPATH")) {
    exit("No direct script access allowed");
}

class Alternate_subject_menu_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function getMenu() {
        $menu = array();
        $menu['dean_acad'] = array();
        $menu['dean_acad']['Alternate Course']=array();
	      $menu['dean_acad']['Alternate Course']['Specific'] = site_url('alternate_subject/alternate_subject');
        $menu['dean_acad']['Alternate Course']['All'] = site_url('alternate_subject/alternate_subject_all');
        return $menu;
    }

}
