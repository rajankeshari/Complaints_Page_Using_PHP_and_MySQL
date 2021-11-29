

<?php

class  Healthcenter_menu_model extends CI_Model
{
	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}

	function getMenu()
	{
		$menu=array();

		// Dealing Assistance 1 for Main Store
		$menu['hc_da1']=array();
		$menu['hc_da1']['Health Center']=array();
		$menu['hc_da1']["Health Center"]["Main File"] = site_url('healthcenter/mainfile');
		$menu['hc_da1']["Health Center"]["View Rejected Medicine"] = site_url('healthcenter/medicine_rejection');
                
                // Dealing Assistance 2 for Patient Observation
                $menu['hc_da2']["Health Center"]["Patient Observation"] = site_url('healthcenter/patient_observation');
                
                 // Dealing Assistance 3 for Counter work
                $menu['hc_da3']["Health Center"]["Available Medicine Issue"] = site_url('healthcenter/counter_issue');
				$menu['hc_da3']["Health Center"]["Dues Medicine Issue"] = site_url('healthcenter/counter_issue_dues');
                $menu['hc_da3']["Health Center"]["Medicine Receive"] = site_url('healthcenter/counter_receive');
				$menu['hc_da3']["Health Center"]["Medicine Return by Patient"] = site_url('healthcenter/medicine_return_patient');
             
		// Dealing Assistance 4 for entry of finding of test
                $menu['hc_da4']["Health Center"]["Test Finding"] = site_url('healthcenter/patient_test_finding');
                
		//auth ==> HC_LMO
		$menu['hc_lmo']=array();
		$menu['hc_lmo']['Health Center']=array();
		//$menu['hc_lmo']["Health Center"]["Appointment"] = site_url('healthcenter/appointment');
                
//                $menu['hc_lmo']["Health Center"]["Report"]["Individual Report"] = site_url('healthcenter/view_individual_report');
//                $menu['hc_lmo']["Health Center"]["Report"]["Specific Date"] = site_url('healthcenter/view_date_wise');
//                $menu['hc_lmo']["Health Center"]["Report"]["Range of Date"] = site_url('healthcenter/view_date_range');
//                $menu['hc_lmo']["Health Center"]["Report"]["Medicine"] = site_url('healthcenter/view_medicine_wise');
//                $menu['hc_lmo']["Health Center"]["Report"]["Group of Medicine"] = site_url('healthcenter/view_group_wise');
               
                $menu['hc_lmo']["Health Center"]["Expenditure"]["View"] = site_url('healthcenter/view_expend_all');
               // $menu['hc_lmo']["Health Center"]["Expenditure"]["Employee"] = site_url('healthcenter/view_expend_emp');
               // $menu['hc_lmo']["Health Center"]["Expenditure"]["Student"] = site_url('healthcenter/view_expend_stu');
                $menu['hc_lmo']["Health Center"]["Expenditure"]["Medicine"] = site_url('healthcenter/view_expend_medi');
                $menu['hc_lmo']["Health Center"]["Expenditure"]["Group"] = site_url('healthcenter/view_expend_group');
                
                
                //auth ==> HC_SMO
		$menu['hc_smo']=array();
		$menu['hc_smo']['Health Center']=array();
		//$menu['hc_smo']["Health Center"]["Appointment"] = site_url('healthcenter/appointment');
                
//                $menu['hc_smo']["Health Center"]["Report"]["Individual Report"] = site_url('healthcenter/view_individual_report');
//                $menu['hc_smo']["Health Center"]["Report"]["Specific Date"] = site_url('healthcenter/view_date_wise');
//                $menu['hc_smo']["Health Center"]["Report"]["Range of Date"] = site_url('healthcenter/view_date_range');
//                $menu['hc_smo']["Health Center"]["Report"]["Medicine"] = site_url('healthcenter/view_medicine_wise');
//                $menu['hc_smo']["Health Center"]["Report"]["Group of Medicine"] = site_url('healthcenter/view_group_wise');
                
                $menu['hc_smo']["Health Center"]["Report and Expenditure"]["View"] = site_url('healthcenter/view_expend_all');
               // $menu['hc_lmo']["Health Center"]["Expenditure"]["Employee"] = site_url('healthcenter/view_expend_emp');
               // $menu['hc_lmo']["Health Center"]["Expenditure"]["Student"] = site_url('healthcenter/view_expend_stu');
                $menu['hc_smo']["Health Center"]["Report and Expenditure"]["Medicine"] = site_url('healthcenter/view_expend_medi');
                $menu['hc_smo']["Health Center"]["Report and Expenditure"]["Group"] = site_url('healthcenter/view_expend_group');
               
                //auth ==> HC_MO
		$menu['hc_mo']=array();
		$menu['hc_mo']['Health Center']=array();
		//$menu['hc_mo']["Health Center"]["Appointment"] = site_url('healthcenter/appointment');
                
//                $menu['hc_mo']["Health Center"]["Report"]["Individual Report"] = site_url('healthcenter/view_individual_report');
//                $menu['hc_mo']["Health Center"]["Report"]["Specific Date"] = site_url('healthcenter/view_date_wise');
//                $menu['hc_mo']["Health Center"]["Report"]["Range of Date"] = site_url('healthcenter/view_date_range');
//                $menu['hc_mo']["Health Center"]["Report"]["Medicine"] = site_url('healthcenter/view_medicine_wise');
//                $menu['hc_mo']["Health Center"]["Report"]["Group of Medicine"] = site_url('healthcenter/view_group_wise');
                
                $menu['hc_mo']["Health Center"]["Report and Expenditure"]["View"] = site_url('healthcenter/view_expend_all');
               // $menu['hc_lmo']["Health Center"]["Expenditure"]["Employee"] = site_url('healthcenter/view_expend_emp');
               // $menu['hc_lmo']["Health Center"]["Expenditure"]["Student"] = site_url('healthcenter/view_expend_stu');
                $menu['hc_mo']["Health Center"]["Report and Expenditure"]["Medicine"] = site_url('healthcenter/view_expend_medi');
                $menu['hc_mo']["Health Center"]["Report and Expenditure"]["Group"] = site_url('healthcenter/view_expend_group');
		
				$menu['emp']["Medical History"]["View"] = site_url('healthcenter/emphistory');
            //    $menu['emp']["Medical History"]["Disclose"] = site_url('healthcenter/empdisclose');
            //    $menu['stu']["Medical History"]["View"] = site_url('healthcenter/emphistory');
            //    $menu['stu']["Medical History"]["Disclose"] = site_url('healthcenter/empdisclose');
                
                // Manual Appointment
                $menu['hc_man']=array();
		$menu['hc_man']['Health Center']=array();
		$menu['hc_man']["Health Center"]["Appointment-Manual"] = site_url('healthcenter/appointment_manual');
		return $menu;
	}
}


