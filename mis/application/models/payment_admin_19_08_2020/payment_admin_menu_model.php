<?php
/*
 * Author : Jay Doshi
 */
class Payment_admin_menu_model extends CI_Model{

    function __construct()
    {
        // Calling the Model parent constructor
        parent::__construct();
    }

    function getMenu(){

        $menu = array();

        $menu['payment_admin'] = array();
        //$menu['payment_admin']['No Dues'] = array();
        //$menu['admin']['No Dues']['No Dues for Dropouts']=array();
        //$menu['payment_admin']['Start/Stop Payment Portal']=array();
       // $menu['admin']['No Dues']['Normal No Dues']['Edit Departments'] = site_url('no_dues/no_dues_admin_edit/no_dues_dept_list');
        //$menu['payment_admin']['No Dues']['Normal No Dues']['Start No Dues']=array();
        //$menu['admin']['No Dues']['Normal No Dues']['Start No Dues']['Start for admin'] = site_url('no_dues/no_dues_admin_edit/start_dues_admin');
        //$menu['payment_admin']['Start/Stop Payment Portal']['Start for student'] = site_url('payment_admin/manage_payment_portal/start_payment_student');
        //$menu['payment_admin']['No Dues']['Normal No Dues']['Stop No Dues']=array();
        //$menu['admin']['No Dues']['Normal No Dues']['Stop No Dues']['Stop for admin'] = site_url("no_dues/no_dues_admin_edit/stop_dues_admin");
        //$menu['payment_admin']['Start/Stop Payment Portal']['Stop for student'] = site_url("payment_admin/manage_payment_portal/stop_payment_student");
        //$menu['admin']['No Dues']['Normal No Dues']['Edit No Dues Time']=array();
        //$menu['admin']['No Dues']['Normal No Dues']['Edit No Dues Time']['Edit for admin'] = site_url("no_dues/no_dues_admin_edit/edit_no_dues_start_admin");
        //$menu['payment_admin']['Start/Stop Payment Portal']['Edit for student'] = site_url("payment_admin/manage_payment_portal/edit_payment_start_student");

        $menu['payment_admin']['Manage Merchant Panel']['Upload Settlement XML Report'] = site_url("payment_admin/manage_payment_portal/manage_merchant_panel");

        $menu['payment_admin']['Manage Merchant Panel']['View Settlement Report'] = site_url("payment_admin/manage_payment_portal/view_sbi_merchant_settlement_details");

        $menu['payment_admin']['Manage Merchant Panel']['View Settlement (Report Not Generated) Details'] = site_url("payment_admin/manage_payment_portal/view_sbi_merchant_settlement_pending_success_details");

        $menu['payment_admin']['Manage Merchant Panel']['View MIS Success Payment Details'] = site_url("payment_admin/manage_payment_portal/view_sbi_merchant_success_settlement_details");
        
        $menu['payment_admin']['Manage Merchant Panel']['View Success Report (Not Yet Settled)'] = site_url("payment_admin/manage_payment_portal/view_sbi_success_pending_mis");

        $menu['payment_admin']['Manage Merchant Panel']['Upload Refund XML Report'] = site_url("payment_admin/manage_payment_portal/upload_refund_xml_report");

        $menu['payment_admin']['Manage Merchant Panel']['View Refund XML Report'] = site_url("payment_admin/manage_payment_portal/view_sbi_merchant_refund_details");

        $menu['payment_admin']['Manage Merchant Panel']['View SBI Merchant Refund details'] = site_url("payment_admin/manage_payment_portal/view_sbi_merchant_refund_success_details");
        
        $menu['payment_admin']['Manage Merchant Panel']['Upload Full Transaction XML Report'] = site_url("payment_admin/manage_payment_portal/upload_complete_transaction");

        $menu['payment_admin']['Manage Merchant Panel']['View Full Transaction Report'] = site_url("payment_admin/manage_payment_portal/view_sbi_merchant_full_transaction");

        $menu['payment_admin']['Manage Merchant Panel']['View Full Transaction Report'] = site_url("payment_admin/manage_payment_portal/view_sbi_merchant_full_transaction");

        $menu['payment_admin']['Manage Payment Complaints']['Enquire with Order Number'] = site_url("payment_admin/manage_payment_complaints/enquire_with_order_number");

        //$menu['payment_admin']['Manage Payment Complaints']['View Total Enquires'] = site_url("payment_admin/manage_payment_complaints/view_total_enquires");
       
        return $menu;
    }
}

