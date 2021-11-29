<?php

class Subject_master_dept_menu_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function getMenu() {
        $menu = array();

      //  $menu['hod'] = array();
    //   $menu['hod']['CBCS']['Course Master']['Course Wise'] = site_url('cbcs_subject_master/subject_master_dept');
       

        return $menu;
    }

}
