<?php

if (!defined("BASEPATH")) {
    exit("No direct script access allowed");
}

class Change_photo_menu_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function getMenu() {
        $menu = array();

       

        
        
        
        
      //  $menu['stu'] = array();      
        //$menu['stu']['Edit Photo'] = site_url('change_photo/student_change_photo');
		
		$menu['id_card']= array();      
        $menu['id_card']['Download data'] = site_url('change_photo/student_change_photo_admin_side');
	  
        //$menu['da_elec_payroll']['Electric Meter Reading']=array();
       // $menu['da_elec_payroll']['Electric Meter Reading']['Late Entry']=site_url('emp_meter_reading/emp_meter_reading_report');
        //$menu['da_elec_payroll']['Electric Meter Reading']['Month Wise Report']=site_url('emp_meter_reading/emp_meter_reading_monthwise_report');
        //$menu['da_elec_payroll']['Electric Meter Reading']['Open/Close Meter Reading Date']=site_url('emp_meter_reading/meter_date');
        



        return $menu;
    }

}
