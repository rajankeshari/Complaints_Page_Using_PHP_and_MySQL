<?php

if (!defined("BASEPATH")) {
    exit("No direct script access allowed");
}

class Emp_meter_reading_menu_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function getMenu() {
        $menu = array();

       

        
        
        
        
        $menu['emp'] = array();
        //$menu['emp']["Monthly General/AC Meter Reading Details"]=array();
       // $menu['emp']["Monthly General/AC Meter Reading Details"]['AC Status'] = site_url('emp_meter_reading/emp_ac_status');
        $menu['emp']['Meter Reading/Updation'] = site_url('emp_meter_reading/emp_meter');

		//$menu['da_elec_payroll']=array();
		//$menu['da_elec_payroll']['Electricity Report']=site_url('finance_account/electricity_report');
       // $menu['da_elec_payroll']['Employee Meter Reading Report']=site_url('emp_meter_reading/emp_meter_reading_report');
		
        //$menu['da_elec_payroll']['Reports']=array();
       // $menu['da_elec_payroll']['Electrical Reports']['Electricity Report']=site_url('finance_account/electricity_report');
          //$menu['da_elec_payroll']['Employee Meter Reading']=site_url('emp_meter_reading/emp_meter_reading_report');
		  
		  //$menu['da_elec_payroll']['Open/Close Meter Reading Date']=site_url('emp_meter_reading/meter_date');
     
	  
        $menu['da_elec_payroll']['Electric Meter Reading']=array();
        $menu['da_elec_payroll']['Electric Meter Reading']['Late Entry']=site_url('emp_meter_reading/emp_meter_reading_report');
        $menu['da_elec_payroll']['Electric Meter Reading']['Month Wise Report']=site_url('emp_meter_reading/emp_meter_reading_monthwise_report');
        $menu['da_elec_payroll']['Electric Meter Reading']['Open/Close Meter Reading Date']=site_url('emp_meter_reading/meter_date');
        



        return $menu;
    }

}
