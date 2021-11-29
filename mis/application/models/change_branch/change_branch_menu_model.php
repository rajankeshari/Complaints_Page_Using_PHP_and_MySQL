<?php

if (!defined("BASEPATH")) {
    exit("No direct script access allowed");
}

class Change_branch_menu_model extends CI_Model {

    function __construct() {
        // Call the Model constructor
        parent::__construct();
    }

    function getMenu() {
        $menu = array();
        $this->load->model('change_branch/Cb_model', 'CB', true);

        // var_dump($this->CB->getEligiblCourse($this->session->userdata('course_id')));

//        if ($this->CB->checkDate()) {
//            if ($this->CB->getEligibleStudent($this->session->userdata('id'), $this->session->userdata('category'), $this->session->userdata('physically_challenged')) && $this->CB->getEligiblCourse($this->session->userdata('course_id'))) {
//                $menu['stu'] = array();
//                $menu['stu']['Change Branch'] = array();
//                $menu['stu']['Change Branch']['Change Branch'] = site_url('change_branch/changebranch');
//            }
//        }
        
        $menu['stu'] = array();
     //   $menu['stu']['Change Branch'] = site_url('change_branch/changebranch');
      
        //Anuj 05-08-2020
		$menu['jee_c'] = array();
        $menu['jee_c']['Change Branch']['Approval'] = site_url('change_branch/cbacademic');
       // $menu['jee_c']['Change Branch']['Add New'] = site_url('change_branch/cbacademic/newCBFormRequest');
       // $menu['jee_c']['Change Branch']['Report'] = site_url('change_branch/cbreport');
        
        $menu['jee_vc'] = array();
      //  $menu['jee_vc']['Change Branch']['Approval'] = site_url('change_branch/cbacademic');
       // $menu['jee_vc']['Change Branch']['Add New'] = site_url('change_branch/cbacademic/newCBFormRequest');
       // $menu['jee_c']['Change Branch']['Report'] = site_url('change_branch/cbreport');
      /*    
        $menu['jee_da'] = array();
        $menu['jee_da']['Change Branch']['Approval'] = site_url('change_branch/cbacademic');
        $menu['jee_da']['Change Branch']['Add New'] = site_url('change_branch/cbacademic/newCBFormRequest');
      */
        $menu['exam_da1'] = array();
       //28-10-2019 $menu['exam_da1']['Change Branch']['Add New'] = site_url('change_branch/cbacademic/newCBFormRequest');
        
        /*$menu['acad_exam'] = array();
        $menu['acad_exam']['Change Branch']['Add New'] = site_url('change_branch/cbacademic/newCBFormRequest');
*/
        //28-10-2019$menu['exam_dr']['Change Branch']['Report'] = site_url('mis_report/change_branch_report');
      //  $menu['exam_dr']['Change Branch']['Print'] = site_url('mis_report/change_branch_print');
    
        
        //28-10-2019$menu['jee_vc']['Change Branch']['Report Excel'] = site_url('mis_report/change_branch_report');
       // $menu['jee_vc']['Change Branch']['Print'] = site_url('mis_report/change_branch_print');
        
        $menu['jee_c']['Change Branch']['Report Excel'] = site_url('mis_report/change_branch_report');
        //$menu['jee_c']['Change Branch']['Print'] = site_url('mis_report/change_branch_print');
      
		$menu['adug'] = array();
       //28-10-2019 $menu['adug']['Change Branch']['Report Excel'] = site_url('mis_report/change_branch_report');
		
		$menu['adpg'] = array();
        //28-10-2019$menu['adpg']['Change Branch']['Report Excel'] = site_url('mis_report/change_branch_report');

        return $menu;
    }

}
