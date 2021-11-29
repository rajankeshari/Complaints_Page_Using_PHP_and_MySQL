<?php

if (!defined("BASEPATH")) {
    exit("No direct script access allowed");
}

class attendance_distribution_menu_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function getMenu() {
        $menu = array();






// HOD LEVEL

	//$menu['hod']['Attendance']['Attendance Distribution'] = site_url('attendance_distribution/distribution');
      





        return $menu;
    }

}
