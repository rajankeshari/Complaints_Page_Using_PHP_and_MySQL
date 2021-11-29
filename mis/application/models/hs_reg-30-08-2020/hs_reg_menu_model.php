<?php

if (!defined("BASEPATH")) {
    exit("No direct script access allowed");
}

class Hs_reg_menu_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function getMenu() {
        $menu = array();
        
        
        $menu['admin'] = array();
        $menu['admin']['Hostel Booking'] = array();
       // $menu['hos_stu_admin']['Hostel Booking']['Manage Hostel Name'] = site_url('hs_reg/hostel/manage_hostel_name');
        $menu['admin']['Hostel Booking']['Manage Hostel'] = site_url('hs_reg/hostel/manage_hostel');
        $menu['admin']['Hostel Booking']['Block/Unblock Room'] = site_url('hs_reg/hostel/block_unblock_room');
        $menu['admin']['Hostel Booking']['Manage student contact'] = site_url('hs_reg/hostel/manage_contact_student');
        $menu['admin']['Hostel Booking']['Bulk upload student contact'] = site_url('hs_reg/hostel/bulk_upload_contact_student');
        $menu['admin']['Hostel Booking']['Search Assigned Student'] = site_url('hs_reg/hostel/search_student_allot');
        $menu['admin']['Hostel Booking']['Manage Warden'] = site_url('hs_reg/hostel/manage_hostel_warden');
        $menu['admin']['Hostel Booking']['Show Room Status'] = site_url('hs_reg/hostel/show_hostel_room');
        $menu['admin']['Report']['Show Students'] = site_url('hs_reg/hostel/filter_assigned_student');
        $menu['admin']['Report']['Show Rooms'] = site_url('hs_reg/hostel/show_rooms_hostel');
      //$menu['admin']['Report']['History of Student'] = site_url('hs_reg/hostel/history_student');

        //$menu['admin'] = array();
        $menu['admin']['Hostel Booking']['Manage guest contact'] = site_url('hs_reg/hostel/manage_guest_contact');
        $menu['admin']['Hostel Booking']['Show Guest Room Status'] = site_url('hs_reg/hostel/show_guest_hostel_room');
        $menu['admin']['Hostel Booking']['Search Assigned Guest'] = site_url('hs_reg/hostel/search_guest');
        //$menu['hos_guest_admin']['Report']['Show Assigned Guest'] = site_url('hs_reg/hostel/filter_assigned_guest');
	    $menu['admin']['Report']['Show Assigned Guest'] = site_url('hs_reg/hostel/search_guest');
	    $menu['admin']['Report']['Download Student Contact'] = site_url('hs_reg/hostel/download_student_contact');
        
        

        $menu['hos_stu_admin'] = array();
        $menu['hos_stu_admin']['Hostel Booking'] = array();
       // $menu['hos_stu_admin']['Hostel Booking']['Manage Hostel Name'] = site_url('hs_reg/hostel/manage_hostel_name');
        $menu['hos_stu_admin']['Hostel Booking']['Manage Hostel'] = site_url('hs_reg/hostel/manage_hostel');
        $menu['hos_stu_admin']['Hostel Booking']['Block/Unblock Room'] = site_url('hs_reg/hostel/block_unblock_room');
        $menu['hos_stu_admin']['Hostel Booking']['Manage student contact'] = site_url('hs_reg/hostel/manage_contact_student');
        $menu['hos_stu_admin']['Hostel Booking']['Bulk upload student contact'] = site_url('hs_reg/hostel/bulk_upload_contact_student');
        $menu['hos_stu_admin']['Hostel Booking']['Search Assigned Student'] = site_url('hs_reg/hostel/search_student_allot');
        $menu['hos_stu_admin']['Hostel Booking']['Manage Warden'] = site_url('hs_reg/hostel/manage_hostel_warden');
        $menu['hos_stu_admin']['Hostel Booking']['Show Room Status'] = site_url('hs_reg/hostel/show_hostel_room');
        $menu['hos_stu_admin']['Hostel Booking']['Vacant Hostel'] = site_url('hs_reg/hostel/vacant_hostel');
        $menu['hos_stu_admin']['Hostel Booking']['Vacant Temp Hostel'] = site_url('hs_reg/hostel/vacant_temp_hostel');
        $menu['hos_stu_admin']['Hostel Booking']['Manual Room Allotment'] = site_url('hs_reg/hostel/manual_room_allotment');
        $menu['hos_stu_admin']['Report']['Show Students'] = site_url('hs_reg/hostel/filter_assigned_student');
        $menu['hos_stu_admin']['Report']['Show Rooms'] = site_url('hs_reg/hostel/show_rooms_hostel');
        $menu['hos_stu_admin']['Report']['History of Student'] = site_url('hs_reg/hostel/history_student');
        $menu['hos_stu_admin']['Report']['Download Student Contact'] = site_url('hs_reg/hostel/download_student_contact');
        //$menu['hos_stu_admin']['Hostel Booking']['Hostel No Dues Inventory List'] = site_url('hs_reg/hostel_no_dues_inventory_list');
        $menu['hos_stu_admin']['Hostel No Dues']['Hostel No Dues Inventory List'] = site_url('hs_reg/hostel_no_dues_inventory_list');
        $menu['hos_stu_admin']['Hostel No Dues']['Assign Individual No Dues'] = site_url('hs_reg/hostel_no_dues_inventory_list');
        $menu['hos_stu_admin']['Hostel No Dues']['Bulk Upload No Dues'] = site_url('hs_reg/hostel_no_dues_inventory_list');
        //$menu['hos_stu_admin']['Report']['History of Student'] = site_url('hs_reg/hostel/history_student');
        //$menu['hos_stu_admin']['Report']['Download Student Contact'] = site_url('hs_reg/hostel/download_student_contact');

        //$menu['hos_stu_admin']['Report']['Vacant Hostel'] = site_url('hs_reg/hostel/vacant_hostel');
        //$menu['hos_stu_admin']['Report']['Vacant Temp Hostel'] = site_url('hs_reg/hostel/vacant_temp_hostel');
        //$menu['hos_stu_admin']['Report']['Manual Room Allotment'] = site_url('hs_reg/hostel/manual_room_allotment');
   
        $menu['hos_guest_admin'] = array();
        $menu['hos_guest_admin']['Hostel Booking']['Manage guest contact'] = site_url('hs_reg/hostel/manage_guest_contact');
        $menu['hos_guest_admin']['Hostel Booking']['Show Guest Room Status'] = site_url('hs_reg/hostel/show_guest_hostel_room');
        $menu['hos_guest_admin']['Hostel Booking']['Search Assigned Guest'] = site_url('hs_reg/hostel/search_guest');
        //$menu['hos_guest_admin']['Report']['Show Assigned Guest'] = site_url('hs_reg/hostel/filter_assigned_guest');
		$menu['hos_guest_admin']['Report']['Show Assigned Guest'] = site_url('hs_reg/hostel/search_guest');

        $menu['hostel_cwd'] = array();
        $menu['hostel_cwd']['Hostel Booking'] = array();
        $menu['hostel_cwd']['Hostel Booking']['Manage Hostel'] = site_url('hs_reg/hostel/manage_hostel');
        $menu['hostel_cwd']['Hostel Booking']['Block/Unblock Room'] = site_url('hs_reg/hostel/block_unblock_room');
        $menu['hostel_cwd']['Hostel Booking']['Manage student contact'] = site_url('hs_reg/hostel/manage_contact_student');
        $menu['hostel_cwd']['Hostel Booking']['Bulk upload student contact'] = site_url('hs_reg/hostel/bulk_upload_contact_student');
        $menu['hostel_cwd']['Hostel Booking']['Search Assigned Student'] = site_url('hs_reg/hostel/search_student_allot');
        $menu['hostel_cwd']['Hostel Booking']['Show Room Status'] = site_url('hs_reg/hostel/show_hostel_room');
        $menu['hostel_cwd']['Hostel Booking']['Vacant Hostel'] = site_url('hs_reg/hostel/vacant_hostel');
        $menu['hostel_cwd']['Hostel Booking']['Manual Room Allotment'] = site_url('hs_reg/hostel/manual_room_allotment');
        $menu['hostel_cwd']['Report']['Show Students'] = site_url('hs_reg/hostel/filter_assigned_student');
        $menu['hostel_cwd']['Report']['Show Rooms'] = site_url('hs_reg/hostel/show_rooms_hostel');
		$menu['hostel_cwd']['Report']['History of Student'] = site_url('hs_reg/hostel/history_student');
		$menu['hostel_cwd']['Report']['Download Student Contact'] = site_url('hs_reg/hostel/download_student_contact');
        //$menu['hostel_cwd']['Report']['Vacant Hostel'] = site_url('hs_reg/hostel/vacant_hostel');

        $menu['hostel_wd'] = array();
        $menu['hostel_wd']['Hostel Booking'] = array();
        $menu['hostel_wd']['Hostel Booking']['Manage Hostel'] = site_url('hs_reg/hostel/manage_hostel');
        $menu['hostel_wd']['Hostel Booking']['Block/Unblock Room'] = site_url('hs_reg/hostel/block_unblock_room');
        $menu['hostel_wd']['Hostel Booking']['Manage student contact'] = site_url('hs_reg/hostel/manage_contact_student');
        $menu['hostel_wd']['Hostel Booking']['Bulk upload student contact'] = site_url('hs_reg/hostel/bulk_upload_contact_student');
        $menu['hostel_wd']['Hostel Booking']['Search Assigned Student'] = site_url('hs_reg/hostel/search_student_allot');
        $menu['hostel_wd']['Hostel Booking']['Show Room Status'] = site_url('hs_reg/hostel/show_hostel_room');
        $menu['hostel_wd']['Hostel Booking']['Vacant Hostel'] = site_url('hs_reg/hostel/vacant_hostel');
        $menu['hostel_wd']['Hostel Booking']['Manual Room Allotment'] = site_url('hs_reg/hostel/manual_room_allotment');
        $menu['hostel_wd']['Report']['Show Students'] = site_url('hs_reg/hostel/filter_assigned_student');
		$menu['hostel_wd']['Report']['Show Rooms'] = site_url('hs_reg/hostel/show_rooms_hostel');
		$menu['hostel_wd']['Report']['History of Student'] = site_url('hs_reg/hostel/history_student');
		$menu['hostel_wd']['Report']['Download Student Contact'] = site_url('hs_reg/hostel/download_student_contact');
        //$menu['hostel_wd']['Report']['Vacant Hostel'] = site_url('hs_reg/hostel/vacant_hostel');
        //$menu['hostel_wd']['Report']['Manual Room Allotment'] = site_url('hs_reg/hostel/manual_room_allotment');

        $menu['hostel_wd_asst'] = array();
        $menu['hostel_wd_asst']['Hostel Booking'] = array();
        $menu['hostel_wd_asst']['Hostel Booking']['Manage Hostel'] = site_url('hs_reg/hostel/manage_hostel');
        $menu['hostel_wd_asst']['Hostel Booking']['Manage student contact'] = site_url('hs_reg/hostel/manage_contact_student');
        $menu['hostel_wd_asst']['Hostel Booking']['Bulk upload student contact'] = site_url('hs_reg/hostel/bulk_upload_contact_student');
        $menu['hostel_wd_asst']['Hostel Booking']['Search Assigned Student'] = site_url('hs_reg/hostel/search_student_allot');
        $menu['hostel_wd_asst']['Hostel Booking']['Show Room Status'] = site_url('hs_reg/hostel/show_hostel_room');
        $menu['hostel_wd_asst']['Report']['Show Students'] = site_url('hs_reg/hostel/filter_assigned_student');
		$menu['hostel_wd_asst']['Report']['Show Rooms'] = site_url('hs_reg/hostel/show_rooms_hostel');
		$menu['hostel_wd_asst']['Report']['History of Student'] = site_url('hs_reg/hostel/history_student');


       // $menu['stu'] = array();
        //$menu['stu']['Hostel Booking'] = array();
        //$menu['stu']['Hostel Booking']['Received OTP'] = site_url('hs_reg/hostel_booking/received_otp');
        //$menu['stu']['Hostel Booking']['Choose Room Partner'] = site_url('hs_reg/hostel_booking/choose_room_partner');
        //$menu['stu']['Hostel Booking']['Enter OTP'] = site_url('hs_reg/hostel_booking/enter_otp');
        //$menu['stu']['Hostel Booking']['Room booking'] = site_url('hs_reg/hostel_booking/book_room');
        //$menu['stu']['Hostel Booking']['View Booked Room'] = site_url('hs_reg/hostel_booking/completed_room_booking');
        //$menu['stu']['Hostel Booking']['Sent Request'] = site_url('hs_reg/hostel_booking/success_otp');


        return $menu;
    }

}
