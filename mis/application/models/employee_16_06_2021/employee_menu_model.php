<?php

class Employee_menu_model extends CI_Model
{
	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}

	function getMenu()
	{
		$menu=array();
		
		$menu['emp']=array();
		
		$menu['emp']['Bookings']=array();
		$menu['emp']['Bookings']['SAH Booking']['Booking Form']= site_url('sah_booking/booking/form');
		$menu['emp']['Bookings']['SAH Booking']['Track Booking']= site_url('sah_booking/booking/track_status');
		$menu['emp']['Bookings']['SAH Booking']['Booked History']= site_url('sah_booking/booking/history');
		$menu['emp']['Bookings']['Sports']['Issue Item'] = site_url('spo_section/employee');
		$menu['emp']['Bookings']['Sports']['View History'] = site_url('spo_section/employee/view_history');
		$menu['emp']['Bookings']['Swimming Booking']['Registration'] = site_url('swimming/emp_registration');
		$menu['emp']['Bookings']['Swimming Booking']['Status'] = site_url('swimming/emp_registration/view_status');
		$menu['emp']['Bookings']['Vehicle Booking']['Booking Form']['Official Use']= site_url('car_booking/booking/official');
		$menu['emp']['Bookings']['Vehicle Booking']['Booking Form']['Other Use']= site_url('car_booking/booking/other');
		$menu['emp']['Bookings']['Vehicle Booking']['Booking Form']['Personal Use']= site_url('car_booking/booking/personal');
		$menu['emp']['Bookings']['Vehicle Booking']['Track Booking'] = site_url('car_booking/booking/viewBookingStatus');
		$menu['emp']['Bookings']['Vehicle Booking']['Booked History'] = site_url('car_booking/booking/empReport');
		
		$menu['emp']['Complaint General']=array();
		$menu['emp']['Complaint General']['Register Complaint'] = site_url('complaint/register_complaint');
		$menu['emp']['Complaint General']['Check Status'] = site_url('complaint/view_own_complaint');
		
		$menu['emp']['Consultancy']['Manage Consultancy'] = site_url('consultancy/consultancy/manage_member_data');
		
		$menu['emp']['Delegate Power']=array();
		$menu['emp']['Delegate Power']['Delegation of Power'] = site_url('delegation_of_power/delegate_power/assign_auths');
		$menu['emp']['Delegate Power']['Relinquish Power'] = site_url('delegation_of_power/delegate_power/deny_auths');
		
		$menu['emp']['Employee Details']=array();
		$menu['emp']["Employee Details"]["View Details"] = site_url('employee/view/menu/emp');
		$menu['emp']["Employee Details"]["Edit Details"] = site_url('employee/edit/menu/emp');
		
		$menu['emp']['Finance Account']=array();
		$menu['emp']["Finance Account"]["Salary"] = site_url('finance_account/finance_account_empwise');
		$menu['emp']["Finance Account"]["Self IT Assessment"] = site_url('finance_account/self_assessment');
		
		$menu['emp']['Leave Management']=array();
		$menu['emp']["Leave Management"]["Application Form"] = site_url('leave/leave_all_kind_application');
		$menu['emp']["Leave Management"]["Current Leave Balance"] = site_url('leave/leave_balance_individual');
		$menu['emp']["Leave Management"]["Leave Archive"] = site_url('leave/leave_all_kind_application/archive');
		$menu['emp']["Leave Management"]["Returned Applications"] = site_url('leave/leave_all_kind_application/my_returned_applications');
        

		$menu['emp']["Manage Students"]["View Student"]["Details"] = site_url('student_view_report/view');
		$menu['daily_emp']["Manage Students"]["View Student"]["Details"] = site_url('student_view_report/view');
	 		
		$menu['emp']["Medical History"] = site_url('healthcenter/emphistory');
		
		$menu['emp']["Meter Reading/Updation"] = site_url('emp_meter_reading/emp_meter');
	 	
		$menu['emp']['Miscellaneous']=array();		
		$menu['emp']['Miscellaneous']['Register Email ID']= site_url('register_mail_id/register_mail_emp');
		$menu['emp']['Miscellaneous']['Set/Reset Security Questions']= site_url('sec_inside/sec_inside_controller/change_security_questions');
		
		$menu['emp']['Notices & Circulars']=array();		
		$menu['emp']['Notices & Circulars']['Notices']= site_url('information/view_notice');
		$menu['emp']['Notices & Circulars']['Circulars']= site_url('information/view_circular');
		
		
		
		//------------------------------------------------ TO BE SHIFTED TO RESPECTIVE MENU MODAL FILE ----------------------------------
		
		
		$menu['est_ar']=array();
		$menu['est_ar']['Employee Details']=array();
		$menu['est_ar']["Employee Details"]["Validation"]=array();
		$menu['est_ar']["Employee Details"]["Validation"]["Validate Permanent"]=site_url('employee/validation');
		
		
		$menu['ar_fa']=array();
		$menu['ar_fa']['Employee Details']=array();
		$menu['ar_fa']["Employee Details"]["Validation"]=array();
		$menu['ar_fa']["Employee Details"]["Validation"]["Validate Permanent"]=site_url('employee/validation');
		
		$menu['est_da1']=array();
		$menu['est_da1']['Manage Employees']=array();
		$menu['est_da1']["Manage Employees"]["Add Employee"]=array();
		$menu['est_da1']["Manage Employees"]["Add Employee"]["Add Permanent Employee"] = site_url('employee/add');
		
		$menu['est_da1']["Manage Employees"]["View Employee Details"] = site_url('employee/view/menu/est_da1');
		$menu['est_da1']["Manage Employees"]["Edit Employee Details"]=array();
		$menu['est_da1']["Manage Employees"]["Edit Employee Details"]["Edit Permanent Employee"] = site_url('employee/edit/menu/est_da1');
		
		$menu['est_dr']=array();
		$menu['est_dr']['Employee Details']=array();
		
		//$menu['ar_fa']=array();
	//	$menu['ar_fa']['Employee Details']=array();
                
                $menu['est_da2']=array();
		$menu['est_da2']['Employee Details']=array();
                
                $menu['admin']=array();
		$menu['admin']['Employee Details']=array();
                
                $menu['hos']=array();
		$menu['hos']['Employee Details']=array();
                
                $menu['ar_fa']["Employee Details"]["Report"]=site_url('employee/emp_report');
				$menu['est_ar']["Employee Details"]["Report"]=site_url('employee/emp_report');
                $menu['est_dr']["Employee Details"]["Report"]=site_url('employee/emp_report');
                $menu['est_da2']["Employee Details"]["Report"]=site_url('employee/emp_report');      
                $menu['admin']["Employee Details"]["Report"]=site_url('employee/emp_report'); 
                $menu['hos']["Employee Details"]["Report"]=site_url('employee/emp_report'); 
				
				//===============Retired Employee================
                $menu['est_da1']["Manage Employees"]["Add Employee"]["Add Retired Employee"] = site_url('employee/add_retired_emp');
                
		
		return $menu;
		
	}
}
