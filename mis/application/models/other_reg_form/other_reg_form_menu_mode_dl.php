<?php

class Other_reg_form_menu_model extends CI_Model
{
    function __construct(){
		// Call the Model constructor
		parent::__construct();
	}
       
    function getMenu(){
        
        //auth ==> acad_ar,
		$menu['acad_ar']=array();
		$menu['acad_ar']["Other Semester date"]["Summer Reg. Date"] = site_url('other_reg_form/date');
		$menu['acad_ar']["Other Registration"]["Forms"] = site_url('other_reg_form/acdamic_check');
                
                $menu['hod']=array();
		$menu['hod']["Other Semester date"]["Summer Reg. Date"] = site_url('other_reg_form/date');
		$menu['hod']["Other Registration"]["Forms"] = site_url('other_reg_form/department_check');
                
                $menu['stu']=array();
		$menu['stu']["Other Registration"]["Summer Reg. form / Status"] = site_url('other_reg_form/reg_form');
		
                return $menu;
    }


}

?>
