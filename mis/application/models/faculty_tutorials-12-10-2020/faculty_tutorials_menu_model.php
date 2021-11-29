<?php

if (!defined("BASEPATH")) {
    exit("No direct script access allowed");
}

class faculty_tutorials_menu_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function getMenu() {
        $menu = array();

        //ft
     //   $menu['ft']['Class Materials']= array();
      //  $menu['ft']['Class Materials'] = site_url('faculty_tutorials/choose_syear_sess');


        //stu
      //  $menu['stu']['Class Materials']= array();
     //   $menu['stu']['Class Materials'] = site_url('faculty_tutorials/student_choose_syear_sess');

        //hod

        //$menu['hod']['Class Materials']= array();
        //$menu['hod']['Class Materials'] = site_url('faculty_tutorials/assignment_admin_side');

        //admr

        $menu['admr']['Class Materials']= array();
        $menu['admr']['Assignment'] = site_url('faculty_tutorials/assignment_admin_side');
		$menu['admr']["Class Engaged"] = site_url('class_engaged/class_engaged_details');

        //dt
        $menu['dt']['Class Materials']= array();
        $menu['dt']['Class Materials'] = site_url('faculty_tutorials/assignment_admin_side');

        

        
        
        
        
        



        return $menu;

            //          insert into auth_types(id,type)values('mis_db','MIS Dashboard');
            //          insert into user_auth_types(id,auth_id)values('mis-anuj','mis_db'); 
    }

}
