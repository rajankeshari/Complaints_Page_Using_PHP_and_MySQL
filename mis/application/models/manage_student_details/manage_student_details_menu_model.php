<?php

if (!defined("BASEPATH")) {
    exit("No direct script access allowed");
}

class Manage_student_details_menu_model extends CI_Model {
//class managestudentdetails_menu_model extends CI_Model {	

    function __construct() {
        parent::__construct();
    }

    function getMenu() {
        $menu = array();

        $menu['dsw'] = array();
        $menu['dsw']['Edit Student'] = array();
        $menu['dsw']['Edit Student']['Change student contact'] = site_url('manage_student_details/update_student_contact/updateData');
		
		$menu['dsw-office'] = array();
        $menu['dsw-office']['Edit Student'] = array();
        $menu['dsw-office']['Edit Student']['Change student contact'] = site_url('manage_student_details/update_student_contact/updateData');

		$menu['mis-office'] = array();
        $menu['mis-office']['Edit Student'] = array();
        $menu['mis-office']['Edit Student']['Change student contact'] = site_url('manage_student_details/update_student_contact/updateData');


       // $menu['acad_da1'] = array();
       // $menu['acad_da1']['Edit Student'] = array();
       // $menu['acad_da1']['Edit Student']['Change student basic info'] = site_url('manage_student_details/update_student_basic_data/updateData');
		
		//$menu['acad_ar'] = array();
        //$menu['acad_ar']['Edit Student'] = array();
        //$menu['acad_ar']['Edit Student']['Change student basic info'] = site_url('manage_student_details/update_student_basic_data/updateData');

        return $menu;
    }

}
