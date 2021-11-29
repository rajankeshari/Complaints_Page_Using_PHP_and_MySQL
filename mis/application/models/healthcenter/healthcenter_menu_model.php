<?php

class  Healthcenter_menu_model extends CI_Model
{
	function __construct()
	{
		// Call the Model constructor
		parent                                                                   ::__construct();
	}

	function getMenu()
	{
		$menu                                                                    =array();

		// Dealing Assistance 1 for Main Store
		$menu['hc_da1']                                                          =array();
		//$menu['hc_da1']["Entry"]                               = site_url('healthcenter/entry');
		$menu['hc_da1']["Main File"]                            = site_url('healthcenter/mainfile');
		$menu['hc_da1']["View Rejected Medicine"]               = site_url('healthcenter/medicine_rejection');
		$menu['hc_da1']["Medicine Return"]["Return to Supplier"]= site_url('healthcenter/medicine_return_to_supplier');
		$menu['hc_da1']["Medicine Return"]["Delete from Stock"] = site_url('healthcenter/medicine_delete_both_stock');

		//Batch Number Updation

		//$menu['hc_da1']["Health Center"]["Update Batch No"] = site_url('healthcenter/batchno_updation');

		
        // Dealing Assistance 2 for Patient Observation
        // 19-02-2020
		//$menu['hc_da2']["Health Center"]["Patient Observation"]                  = site_url('healthcenter/patient_observation');
		
        // Dealing Assistance 3 for Counter work
		
		//$menu['hc_da3']["Available Medicine Issue"]             = site_url('healthcenter/counter_issue');
		//$menu['hc_da3']["Dues Medicine Issue"]                  = site_url('healthcenter/counter_issue_dues');
		$menu['hc_da3']["View Appointment Date Wise"]             = site_url('healthcenter/counter_issue');
		$menu['hc_da3']["Medicine Receive"]                     = site_url('healthcenter/counter_receive');
		$menu['hc_da3']["Medicine Return by Patient"]           = site_url('healthcenter/medicine_return_patient');
		$menu['hc_da3']["Update Batch No"]                               = site_url('healthcenter/entry');
		// Dealing Assistance 4 for entry of finding of test
		// 19-02-2020
		//$menu['hc_da4']["Health Center"]["Test Finding"]                         = site_url('healthcenter/patient_test_finding');
		
		//auth                                                                   ==> HC_LMO
		// 19-02-2020
		/*$menu['hc_lmo']                                                          =array();
		$menu['hc_lmo']['Health Center']                                         =array();
		$menu['hc_lmo']["Health Center"]["Appointment"]                          = site_url('healthcenter/appointment');*/

		
//                $menu['hc_lmo']["Health Center"]["Report"]["Individual Report"]= site_url('healthcenter/view_individual_report');
//                $menu['hc_lmo']["Health Center"]["Report"]["Specific Date"]    = site_url('healthcenter/view_date_wise');
//                $menu['hc_lmo']["Health Center"]["Report"]["Range of Date"]    = site_url('healthcenter/view_date_range');
//                $menu['hc_lmo']["Health Center"]["Report"]["Medicine"]         = site_url('healthcenter/view_medicine_wise');
//                $menu['hc_lmo']["Health Center"]["Report"]["Group of Medicine"]= site_url('healthcenter/view_group_wise');
		
		// 19-02-2020
		//$menu['hc_admin']["Expenditure"]["View"]        = site_url('healthcenter/view_expend_all');
      //  $menu['hc_admin']["Expenditure"]["Employee"]    = site_url('healthcenter/view_expend_emp');
      //  $menu['hc_admin']["Expenditure"]["Student"]     = site_url('healthcenter/view_expend_stu');
		
		
		
		
		//$menu['hc_da1']["Expenditure"]["View"]        = site_url('healthcenter/view_expend_all');
      //  $menu['hc_da1']["Expenditure"]["Employee"]    = site_url('healthcenter/view_expend_emp');
      //  $menu['hc_da1']["Expenditure"]["Student"]     = site_url('healthcenter/view_expend_stu');
		// 19-02-2020
		//$menu['hc_lmo']["Health Center"]["Expenditure"]["Medicine"]              = site_url('healthcenter/view_expend_medi');
		//$menu['hc_lmo']["Health Center"]["Expenditure"]["Group"]                 = site_url('healthcenter/view_expend_group');
		
		
                //auth                                                           ==> HC_SMO
        // 19-02-2020
		/*$menu['hc_smo']                                                          =array();
		$menu['hc_smo']['Health Center']                                         =array();
		$menu['hc_smo']["Health Center"]["Appointment"]                          = site_url('healthcenter/appointment');*/
		
//                $menu['hc_smo']["Health Center"]["Report"]["Individual Report"]= site_url('healthcenter/view_individual_report');
//                $menu['hc_smo']["Health Center"]["Report"]["Specific Date"]    = site_url('healthcenter/view_date_wise');
//                $menu['hc_smo']["Health Center"]["Report"]["Range of Date"]    = site_url('healthcenter/view_date_range');
//                $menu['hc_smo']["Health Center"]["Report"]["Medicine"]         = site_url('healthcenter/view_medicine_wise');
//                $menu['hc_smo']["Health Center"]["Report"]["Group of Medicine"]= site_url('healthcenter/view_group_wise');
		
	// 19-02-2020
		//$menu['hc_smo']["Health Center"]["Report and Expenditure"]["View"]       = site_url('healthcenter/view_expend_all');
     // $menu['hc_lmo']["Health Center"]["Expenditure"]["Employee"]    = site_url('healthcenter/view_expend_emp');
     // $menu['hc_lmo']["Health Center"]["Expenditure"]["Student"]     = site_url('healthcenter/view_expend_stu');
		// 19-02-2020
		//$menu['hc_smo']["Health Center"]["Report and Expenditure"]["Medicine"]   = site_url('healthcenter/view_expend_medi');
		//$menu['hc_smo']["Health Center"]["Report and Expenditure"]["Group"]      = site_url('healthcenter/view_expend_group');
		
        //auth                                                           ==> HC_MO
        // 19-02-2020
		/*$menu['hc_mo']                                                           =array();
		$menu['hc_mo']['Health Center']                                          =array();
		$menu['hc_mo']["Health Center"]["Appointment"]                           = site_url('healthcenter/appointment');
        $menu['hc_mo']["Health Center"]["Report"]["Individual Report"]    = site_url('healthcenter/view_individual_report');
        $menu['hc_mo']["Health Center"]["Report"]["Specific Date"]        = site_url('healthcenter/view_date_wise');
        $menu['hc_mo']["Health Center"]["Report"]["Range of Date"]        = site_url('healthcenter/view_date_range');
        $menu['hc_mo']["Health Center"]["Report"]["Medicine"]             = site_url('healthcenter/view_medicine_wise');
        $menu['hc_mo']["Health Center"]["Report"]["Group of Medicine"]    = site_url('healthcenter/view_group_wise');
		
		$menu['hc_mo']["Health Center"]["Report and Expenditure"]["View"]        = site_url('healthcenter/view_expend_all');*/
        // $menu['hc_lmo']["Health Center"]["Expenditure"]["Employee"]    = site_url('healthcenter/view_expend_emp');
        // $menu['hc_lmo']["Health Center"]["Expenditure"]["Student"]     = site_url('healthcenter/view_expend_stu');
		// 19-02-2020
		/*$menu['hc_mo']["Health Center"]["Report and Expenditure"]["Medicine"]    = site_url('healthcenter/view_expend_medi');
		$menu['hc_mo']["Health Center"]["Report and Expenditure"]["Group"]       = site_url('healthcenter/view_expend_group');*/
		
		// 19-02-2020
		$menu['emp']["Medical History"]["View"]                                  = site_url('healthcenter/emphistory');
            //    $menu['emp']["Medical History"]["Disclose"]                    = site_url('healthcenter/empdisclose');
            //    $menu['stu']["Medical History"]["View"]                        = site_url('healthcenter/emphistory');
            //    $menu['stu']["Medical History"]["Disclose"]                    = site_url('healthcenter/empdisclose');
		
        // Manual Appointment
		$menu['hc_man']                                                          =array();
		$menu['hc_man']["Appointment"]                   = site_url('healthcenter/appointment_manual');

		// Audut Report
		// $menu['audit_hc'] = array();
		// $menu['audit_hc']["Opening Stock Form"] = site_url('healthcenter/med_opening_stock');
		// $menu['audit_hc']["Audit Report"] = site_url('healthcenter/financial_year_audit_report');

		$menu['phy_ver_hc'] = array();
		$menu['phy_ver_hc']["Physical Verification"]["Update (Stock)"]["Main-stock"] = site_url('healthcenter/mainstock_updation');
		$menu['phy_ver_hc']["Physical Verification"]["Update (Stock)"]["Main-stock Batch-no"]= site_url('healthcenter/mainstock_batchno_updation');
		$menu['phy_ver_hc']["Physical Verification"]["Update (Stock)"]["Counter-stock"] = site_url('healthcenter/counterstock_updation');
		$menu['phy_ver_hc']["Physical Verification"]["Update (Stock)"]["Counter-stock Batch-no"] = site_url('healthcenter/counterstock_batchno_updation');
		
		$menu['phy_ver_hc']["Physical Verification"]["Report"]["All Stock Details"]= site_url('healthcenter/medi_all_details');
		$menu['phy_ver_hc']["Physical Verification"]["Report"]["Mainstock Report"]= site_url('healthcenter/mainstock_physical_verification_report');
		$menu['phy_ver_hc']["Physical Verification"]["Report"]["Mainstock Batchno Report"]= site_url('healthcenter/mainstock_batchno_physical_verification_report');
		$menu['phy_ver_hc']["Physical Verification"]["Report"]["Counter-stock Report"] = site_url('healthcenter/counterstock_physical_verification_report');
		$menu['phy_ver_hc']["Physical Verification"]["Report"]["Counter-stock Batchno Report"] = site_url('healthcenter/counterstock_batchno_physical_verification_report');
		
		$menu['phy_ver_hc']["Physical Verification"]["Upload Approval"]["Upload Signed Copy"]= site_url('healthcenter/physical_verification_upload');
		$menu['phy_ver_hc']["Physical Verification"]["Upload Approval"]["View Signed Copy"]= site_url('healthcenter/physical_verification_upload_report');

		$menu['ao_hc'] = array();
		$menu['ao_hc']["Physical Verification"]["Requested Stock"]["Main-stock"] = site_url('healthcenter/mainstock_update_request');
		$menu['ao_hc']["Physical Verification"]["Requested Stock"]["Main-stock Batch-no"]= site_url('healthcenter/mainstock_batchno_update_request');
		$menu['ao_hc']["Physical Verification"]["Requested Stock"]["Counter-stock"]= site_url('healthcenter/counterstock_update_request');
		$menu['ao_hc']["Physical Verification"]["Requested Stock"]["Counter-stock Batch-no"]= site_url('healthcenter/counterstock_batchno_update_request');
		
		$menu['ao_hc']["Physical Verification"]["Report"]["All Stock Details"]= site_url('healthcenter/medi_all_details');
		$menu['ao_hc']["Physical Verification"]["Report"]["Mainstock Report"]= site_url('healthcenter/mainstock_physical_verification_report');
		$menu['ao_hc']["Physical Verification"]["Report"]["Mainstock Batchno Report"]= site_url('healthcenter/mainstock_batchno_physical_verification_report');
		$menu['ao_hc']["Physical Verification"]["Report"]["Counter-stock Report"] = site_url('healthcenter/counterstock_physical_verification_report');
		$menu['ao_hc']["Physical Verification"]["Report"]["Counter-stock Batchno Report"] = site_url('healthcenter/counterstock_batchno_physical_verification_report');
		
		$menu['ao_hc']["Physical Verification"]["Upload Approval"]["Upload Signed Copy"]= site_url('healthcenter/physical_verification_upload');
		$menu['ao_hc']["Physical Verification"]["Upload Approval"]["View Signed Copy"]= site_url('healthcenter/physical_verification_upload_report');

		return $menu;
	}
}


 