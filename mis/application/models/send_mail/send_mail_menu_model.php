<?php

if (!defined("BASEPATH")) {
    exit("No direct script access allowed");
}

class Send_mail_menu_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function getMenu() {
        $menu = array();
        
        
        $menu['email_admin'] = array();
        //$menu['email_admin']['Hostel Booking'] = array();
       // $menu['hos_stu_admin']['Hostel Booking']['Manage Hostel Name'] = site_url('hs_reg/hostel/manage_hostel_name');
      // $menu['email_admin']['Mail Admin'] = array();
        $menu['email_admin']['Mail Admin'] = site_url('admin_panel_email/admin_panel_email');
        $menu['email_admin']['Send Multiple Mail'] = site_url('send_mail/send_indiviadual_mail');
        $menu['email_admin']['Send Multiple Mail(JEE)'] = site_url('send_mail/send_multiple_mail_jee');
        $menu['email_admin']['Get Mail Delivery Report'] = site_url('send_mail/mail_delivery_report');

        //$menu['email_admin']['Hostel Booking']['Manage student contact'] = site_url('hs_reg/hostel/manage_contact_student');


        return $menu;

    }


}

?>