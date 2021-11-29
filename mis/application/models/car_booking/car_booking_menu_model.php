<?php

class Car_booking_menu_model extends CI_Model
{
	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}

	function getMenu()
	{
		    // Salary Edit Protion: 'acc_hos','acc_da2','acc_sal' 'daily_emp'
		    // For MIS Users
        $menu['daily_emp']=array();
		$menu['daily_emp']['Vehicle Booking']=array();
		$menu['daily_emp']['Vehicle Booking']["Book Vehicle"]["Official Use"] = site_url('car_booking/booking/official');
        $menu['daily_emp']['Vehicle Booking']["Book Vehicle"]["Other Use"] = site_url('car_booking/booking/other');
        $menu['daily_emp']['Vehicle Booking']["View Booking Status"] = site_url('car_booking/booking/viewBookingStatus');

       
		
		// For Emp
      //  $menu['emp']=array();
		//$menu['emp']['Vehicle Booking']=array();
		//$menu['emp']['Vehicle Booking']["Booking Form"]["Personal Use"] = site_url('car_booking/booking/personal');
      // $menu['emp']['Vehicle Booking']["Booking Form"]["Official Use"] = site_url('car_booking/booking/official');
      //  $menu['emp']['Vehicle Booking']["Booking Form"]["Other Use"] = site_url('car_booking/booking/other');

        //$menu['emp']['Vehicle Booking']["Other Use"] = site_url('car_booking/booking/other');
        //$menu['emp']['Vehicle Booking']["Official Use"] = site_url('car_booking/booking/official');
       // $menu['emp']['Vehicle Booking']["Track Booking"] = site_url('car_booking/booking/viewBookingStatus');

      //  $menu['emp']['Vehicle Booking']["Booked History"] = site_url('car_booking/booking/empReport');

        $menu['hod']=array();
        $menu['hod']['Vehicle Booking']['View All Booking']=site_url('car_booking/booking/view_all_hod');
        $menu['hos']=array();
        $menu['hos']['Vehicle Booking']['View All Booking']=site_url('car_booking/booking/view_all_hod');
       // $menu['hod']['View All Booking']=site_url('car_booking/booking/view_all_hod');
        $menu['dt']=array();
        $menu['dt']['Vehicle Booking']['View All Booking']=site_url('car_booking/booking/view_all_dir');

        $menu['ism_admin']=array();
        $menu['ism_admin']['Vehicle Booking']['View All Booking']=site_url('car_booking/booking/view_all_dir');

        $menu['car_sup']=array();
        $menu['car_sup']["View All Booking"] = site_url('car_booking/booking/view_all');
        $menu['car_sup']["Booking for Others"]["Personal Use"]= site_url('car_booking/booking/for_other_personal');
        $menu['car_sup']["Booking for Others"]["Official Use"]= site_url('car_booking/booking/for_other_official');
        $menu['car_sup']["Booking for Others"]["Other Use"]= site_url('car_booking/booking/for_other_other');
        $menu['car_sup']["Destination List"] = site_url('car_booking/booking/destination');
        $menu['car_sup']["Vehicle List"] = site_url('car_booking/booking/allvehicle');
        $menu['car_sup']["Bookings by Car Supervisor"] = site_url('car_booking/booking/viewBookingCarSup');


        $menu['car_sup']["Driver Reporting"] = site_url('car_booking/booking/driver_reporting');
        $menu['car_sup']["Report"] = site_url('car_booking/booking/report');
        $menu['car_sup']["Contractual Drivers"] = array();
        $menu['car_sup']["Contractual Drivers"]["Add New Driver"] = site_url('car_booking/booking/tempdriver');
        $menu['car_sup']["Contractual Drivers"]["View All Drivers"] = site_url('car_booking/booking/view_contractual_drivers');

        return $menu;
	}
}
?>