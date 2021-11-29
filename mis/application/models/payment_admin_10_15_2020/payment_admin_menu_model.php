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

        $menu['payment_admin']['Uploads (From SBI to MIS)']['Upload Settlement XML Report'] = site_url("payment_admin/manage_payment_portal/manage_merchant_panel");

        $menu['payment_admin']['Uploads (From SBI to MIS)']['Upload Refund XML Report'] = site_url("payment_admin/manage_payment_portal/upload_refund_xml_report");

        $menu['payment_admin']['Uploads (From SBI to MIS)']['Upload Full Transaction XML Report'] = site_url("payment_admin/manage_payment_portal/upload_complete_transaction");

        $menu['payment_admin']['Reports']['Settlement']['All Settlements'] = site_url("payment_admin/manage_payment_portal/view_sbi_merchant_settlement_details");

        $menu['payment_admin']['Reports']['Settlement']['View Settlement (Report Not Generated) Details'] = site_url("payment_admin/manage_payment_portal/view_sbi_merchant_settlement_pending_success_details");

        $menu['payment_admin']['Reports']['MIS Success']['View MIS Success Payment Details'] = site_url("payment_admin/manage_payment_portal/view_sbi_merchant_success_settlement_details");
        
        $menu['payment_admin']['Reports']['MIS Success']['View MIS Success Payment (Not Yet Settled)'] = site_url("payment_admin/manage_payment_portal/view_sbi_success_pending_mis");

        //$menu['payment_admin']['Manage Merchant Panel']['Upload Refund XML Report'] = site_url("payment_admin/manage_payment_portal/upload_refund_xml_report");

        $menu['payment_admin']['Reports']['Refund']['All Refund'] = site_url("payment_admin/manage_payment_portal/view_sbi_merchant_refund_details");

        $menu['payment_admin']['Reports']['Refund']['View SBI Merchant Refund details'] = site_url("payment_admin/manage_payment_portal/view_sbi_merchant_refund_success_details");
        
        //$menu['payment_admin']['Reports']['Upload Full Transaction XML Report'] = site_url("payment_admin/manage_payment_portal/upload_complete_transaction");

        $menu['payment_admin']['Reports']['All Transaction']['View Full Transaction Report'] = site_url("payment_admin/manage_payment_portal/view_sbi_merchant_full_transaction");

        //$menu['payment_admin']['Reports']['View Full Transaction Report'] = site_url("payment_admin/manage_payment_portal/view_sbi_merchant_full_transaction");

        $menu['payment_admin']['Generate Receipt']['Enquire with Order Number'] = site_url("payment_admin/manage_payment_complaints/enquire_with_order_number");
        $menu['payment_admin']['Generate Receipt']['Enquire with Admn No.']['View Refund Details'] = site_url("payment_admin/manage_payment_complaints/enquire_with_admn_no_refund_details");
        $menu['payment_admin']['Charge Back']['Download Payment Receipt'] = site_url("payment_admin/manage_payment_complaints/download_payment_receipt");



        $menu['payment_accounts_admin'] = array();
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

        //$menu['payment_admin']['Uploads (From SBI to MIS)']['Upload Settlement XML Report'] = site_url("payment_admin/manage_payment_portal/manage_merchant_panel");

        //$menu['payment_admin']['Uploads (From SBI to MIS)']['Upload Refund XML Report'] = site_url("payment_admin/manage_payment_portal/upload_refund_xml_report");

        //$menu['payment_admin']['Uploads (From SBI to MIS)']['Upload Full Transaction XML Report'] = site_url("payment_admin/manage_payment_portal/upload_complete_transaction");

        $menu['payment_accounts_admin']['Reports']['Settlement']['All Settlements'] = site_url("payment_admin/manage_payment_portal/view_sbi_merchant_settlement_details");

        $menu['payment_accounts_admin']['Reports']['Settlement']['View Settlement (Report Not Generated) Details'] = site_url("payment_admin/manage_payment_portal/view_sbi_merchant_settlement_pending_success_details");

        $menu['payment_accounts_admin']['Reports']['MIS Success']['View MIS Success Payment Details'] = site_url("payment_admin/manage_payment_portal/view_sbi_merchant_success_settlement_details");
        
        $menu['payment_accounts_admin']['Reports']['MIS Success']['View MIS Success Payment (Not Yet Settled)'] = site_url("payment_admin/manage_payment_portal/view_sbi_success_pending_mis");

        //$menu['payment_admin']['Manage Merchant Panel']['Upload Refund XML Report'] = site_url("payment_admin/manage_payment_portal/upload_refund_xml_report");

        $menu['payment_accounts_admin']['Reports']['Refund']['All Refund'] = site_url("payment_admin/manage_payment_portal/view_sbi_merchant_refund_details");

        $menu['payment_accounts_admin']['Reports']['Refund']['View SBI Merchant Refund details'] = site_url("payment_admin/manage_payment_portal/view_sbi_merchant_refund_success_details");
        
        //$menu['payment_admin']['Reports']['Upload Full Transaction XML Report'] = site_url("payment_admin/manage_payment_portal/upload_complete_transaction");

        $menu['payment_accounts_admin']['Reports']['All Transaction']['View Full Transaction Report'] = site_url("payment_admin/manage_payment_portal/view_sbi_merchant_full_transaction");

        //$menu['payment_admin']['Reports']['View Full Transaction Report'] = site_url("payment_admin/manage_payment_portal/view_sbi_merchant_full_transaction");

        $menu['payment_accounts_admin']['Generate Receipt']['Enquire with Order Number'] = site_url("payment_admin/manage_payment_complaints/enquire_with_order_number");
        $menu['payment_accounts_admin']['Generate Receipt']['Enquire with Admn No.']['View Refund Details'] = site_url("payment_admin/manage_payment_complaints/enquire_with_admn_no_refund_details");



        $menu['payment_academic_admin'] = array();
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

        //$menu['payment_admin']['Uploads (From SBI to MIS)']['Upload Settlement XML Report'] = site_url("payment_admin/manage_payment_portal/manage_merchant_panel");

        //$menu['payment_admin']['Uploads (From SBI to MIS)']['Upload Refund XML Report'] = site_url("payment_admin/manage_payment_portal/upload_refund_xml_report");

        //$menu['payment_admin']['Uploads (From SBI to MIS)']['Upload Full Transaction XML Report'] = site_url("payment_admin/manage_payment_portal/upload_complete_transaction");

        $menu['payment_academic_admin']['Reports']['Settlement']['All Settlements'] = site_url("payment_admin/manage_payment_portal/view_sbi_merchant_settlement_details");

        $menu['payment_academic_admin']['Reports']['Settlement']['View Settlement (Report Not Generated) Details'] = site_url("payment_admin/manage_payment_portal/view_sbi_merchant_settlement_pending_success_details");

        $menu['payment_academic_admin']['Reports']['MIS Success']['View MIS Success Payment Details'] = site_url("payment_admin/manage_payment_portal/view_sbi_merchant_success_settlement_details");
        
        $menu['payment_academic_admin']['Reports']['MIS Success']['View MIS Success Payment (Not Yet Settled)'] = site_url("payment_admin/manage_payment_portal/view_sbi_success_pending_mis");

        //$menu['payment_admin']['Manage Merchant Panel']['Upload Refund XML Report'] = site_url("payment_admin/manage_payment_portal/upload_refund_xml_report");

        $menu['payment_academic_admin']['Reports']['Refund']['All Refund'] = site_url("payment_admin/manage_payment_portal/view_sbi_merchant_refund_details");

        $menu['payment_academic_admin']['Reports']['Refund']['View SBI Merchant Refund details'] = site_url("payment_admin/manage_payment_portal/view_sbi_merchant_refund_success_details");
        
        //$menu['payment_admin']['Reports']['Upload Full Transaction XML Report'] = site_url("payment_admin/manage_payment_portal/upload_complete_transaction");

        $menu['payment_academic_admin']['Reports']['All Transaction']['View Full Transaction Report'] = site_url("payment_admin/manage_payment_portal/view_sbi_merchant_full_transaction");

        //$menu['payment_admin']['Reports']['View Full Transaction Report'] = site_url("payment_admin/manage_payment_portal/view_sbi_merchant_full_transaction");

        $menu['payment_academic_admin']['Generate Receipt']['Enquire with Order Number'] = site_url("payment_admin/manage_payment_complaints/enquire_with_order_number");
        $menu['payment_academic_admin']['Generate Receipt']['Enquire with Admn No.']['View Refund Details'] = site_url("payment_admin/manage_payment_complaints/enquire_with_admn_no_refund_details");

        $menu['payment_academic_admin']['Late Fine']['Download Bank Fee Details']['Bulk Download (Not Paid Admission Number)'] = site_url("payment_admin/manage_payment_portal/download_late_fine_bank_fee_table");

        $menu['payment_academic_admin']['Late Fine']['Upload Bank Fee Details']['Bulk Upload (Not Paid Admission Number)'] = site_url("payment_admin/manage_payment_portal/upload_late_fine_bank_fee_table");

        $menu['payment_academic_admin']['Late Fine']['View Lists']['(Not Paid Admission Number,Before Sink)'] = site_url("payment_admin/manage_payment_portal/late_fine_all_details");

        $menu['payment_academic_admin']['Late Fine']['View Lists']['(Not Paid Admission Number,After Sink)'] = site_url("payment_admin/manage_payment_portal/late_fine_all_details_after_sinking");

        $menu['payment_academic_admin']['verify offline receipts'] = site_url("payment_admin/manage_payment_portal/verify_offline_receipt");

        $menu['payment_academic_admin']['Sinking of Reg Regular Fee with Bank Fee Details'] = site_url("payment_admin/manage_payment_portal/sinking_regular_fee_with_bank_and_sbi_bank_fee_details");

        $menu['payment_academic_admin']['Online Offline Not Paid Payment Lists'] = site_url("payment_admin/manage_payment_portal/get_payment_registered_reports");

        $menu['payment_academic_admin']['View Lists After Data Sink'] = site_url("payment_admin/manage_payment_portal/get_bank_fee_sink_details");

        $menu['payment_academic_admin']['Change Offline Receipt'] = site_url("payment_admin/manage_payment_portal/change_offline_receipt");


        $menu['payment_reports'] = array();
        $menu['payment_reports']['Online Offline Not Paid Payment Lists'] = site_url("payment_admin/manage_payment_portal/get_payment_registered_reports");

        //$menu['payment_admin']['Manage Payment Complaints']['View Total Enquires'] = site_url("payment_admin/manage_payment_complaints/view_total_enquires");
       
        //return $menu;

        //$menu['payment_admin']['Manage Payment Complaints']['View Total Enquires'] = site_url("payment_admin/manage_payment_complaints/view_total_enquires");
       
        return $menu;



    }
}

