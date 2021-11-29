<?php

class Final_registration_menu_model extends CI_Model{

       function __construct()
	   {
		// Call the Model constructor
		parent::__construct();
	   }


	   function getMenu(){

            $menu=array();
            //$menu['adug']['Final Registration']=array();
            //$menu['hod']['SAH Booking']['Employee'] = site_url('sah_booking/booking_request/app_list/hod');
            //$menu['adug']['Final Registration']['Add/Remove Course']=site_url('');
            //$menu['adug']['Final Registration']['Pending Registration']=site_url('final_registration/final_registration');

            //$menu['adpg']['Final Registration']=array();
            //$menu['hod']['SAH Booking']['Employee'] = site_url('sah_booking/booking_request/app_list/hod');
            //$menu['adpg']['Final Registration']['Add/Remove Course']=site_url('');

            //$menu['adpg']['Final Registration']['Pending Registration']=site_url('final_registration/final_registration');

           return $menu;
	   }


}
?>