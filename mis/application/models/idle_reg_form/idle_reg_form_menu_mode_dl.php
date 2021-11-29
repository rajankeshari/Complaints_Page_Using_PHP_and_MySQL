<?php

class idle_reg_form_menu_model extends CI_Model
{
    function __construct(){
		// Call the Model constructor
		parent::__construct();
	}

    function getMenu(){

        //auth ==> acad_ar,
		$menu['acad_ar']=array();
		//$menu['acad_ar']["Idle Semester date"]["Summer Reg. Date"] = site_url('idle_reg_form/date');
		$menu['acad_ar']["Idle Registration"]["Forms"] = site_url('idle_reg_form/acdamic_check');

                $menu['hod']=array();
		//$menu['hod']["Idle Semester date"]["Summer Reg. Date"] = site_url('idle_reg_form/date');
		$menu['hod']["Idle Registration"]["Forms"] = site_url('idle_reg_form/department_check');

               $menu['stu']=array();
		$menu['stu']["Idle Registration"]["Summer Reg. form / Status"] = site_url('idle_reg_form/reg_form');

                return $menu;
    }


}

?>
