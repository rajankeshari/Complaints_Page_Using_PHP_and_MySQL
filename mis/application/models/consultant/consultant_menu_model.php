<?php

class Consultant_menu_model extends CI_Model
{
	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}

	function getMenu($auth='')
	{
		$menu=array();
		//auth ==> emp.
		
		$menu['emp']=array();
		$menu['emp']["Consultancy And Short Courses"] = array();
		$menu['emp']["Consultancy And Short Courses"]['Apply For consultancy'] = site_url('consultant/consultant/index');
		$menu['emp']["Consultancy And Short Courses"]['Submit Proposal Form'] = site_url('consultant/consultancy_proposal_form/consultancy_proposal_form_all/ft');
		$menu['emp']["Consultancy And Short Courses"]['Submit Disbursement Form'] = site_url('consultant/consultancy_proposal_approve/disbursement_apply_all');
		$menu['emp']["Consultancy And Short Courses"]['Edit Consultancy Form'] = site_url('consultant/edit_consultancy_form/edit_consultancy');
		$menu['emp']["Consultancy And Short Courses"]['View Consultancy Form'] = site_url('consultant/consultant/view_consultancy_form/ft');
		$menu['emp']["Consultancy And Short Courses"]['View Disbursement Form'] = site_url('consultant/consultant_disbursement_sheet/view_disbursement_form/ft');
		
		

		//auth ==> dt
		$menu['dt']=array();
		$menu['dt']["Consultancy And Short Courses"] = array();
		$menu['dt']["Consultancy And Short Courses"]['View Consultancy Form'] = site_url('consultant/consultant/view_consultancy_form/dt');
		
		


		//auth ==> hod
		$menu['hod']=array();
		$menu['hod']["Consultancy And Short Courses"] = array();
		$menu['hod']["Consultancy And Short Courses"]['View Consultancy Form'] = site_url('consultant/consultant/view_consultancy_form/hod');
		
		$menu['hos']=array();
		$menu['hos']["Consultancy And Short Courses"] = array();
		$menu['hos']["Consultancy And Short Courses"]['View Consultancy Form'] = site_url('consultant/consultant/view_consultancy_form/hod');
		
		


		//auth ==> pce
		$menu['pce']=array();
		$menu['pce']["Consultancy And Short Courses"] = array();
		

		$menu['pce']["Consultancy And Short Courses"]['Send Receipt'] = site_url('consultant/consultancy_proposal_approve/apply_form3');
		$menu['pce']["Consultancy And Short Courses"]['Edit Receipt'] = site_url('consultant/consultancy_proposal_approve/edit_form3_all');
		$menu['pce']["Consultancy And Short Courses"]['Edit Service Tax'] = site_url('consultant/service_tax/insert');
		$menu['pce']["Consultancy And Short Courses"]['Edit Link Up No.'] = site_url('consultant/consultant/edit_linkup_all');
		$menu['pce']["Consultancy And Short Courses"]['View Consultancy Form'] = site_url('consultant/consultant/view_consultancy_form/pce');
		$menu['pce']["Consultancy And Short Courses"]['View Project Account Form'] = site_url('consultant/project_account/view_all/pce');

		

		//auth ==> pce_da
		$menu['pce_da']=array();
		//$menu['pce_da']["Consultancy And Short Courses"] = array();
		//$menu['pce_da']["Consultancy And Short Courses"]['View Consultancy Form'] = site_url('consultant/consultant/view_consultancy_form/pce');

		//$menu['pce_da']["Consultancy And Short Courses"]['Send Receipt'] = site_url('consultant/consultancy_proposal_approve/apply_form3');
		$menu['pce_da']["Consultancy And Short Courses"]['Edit Receipt'] = site_url('consultant/consultancy_proposal_approve/edit_form3_all');
		$menu['pce_da']["Consultancy And Short Courses"]['Edit service tax'] = site_url('consultant/service_tax/insert');




		//auth ==> acc_ar_prj
		$menu['acc_ar_prj']=array();
		$menu['acc_ar_prj']["Consultancy And Short Courses"] = array();
		$menu['acc_ar_prj']["Consultancy And Short Courses"]['View Consultancy Form'] = site_url('consultant/consultant/view_consultancy_form/acc_ar_prj');
		$menu['acc_ar_prj']["Consultancy And Short Courses"]['View Disbursement Form'] = site_url('consultant/consultant_disbursement_sheet/view_disbursement_form/acc_ar_prj');
		
		$menu['acc_ar_prj']["Consultancy And Short Courses"]['Fill Project Account Form'] = site_url('consultant/project_account/fill');
		$menu['acc_ar_prj']["Consultancy And Short Courses"]['Edit Project Account Form'] = site_url('consultant/project_account/edit_all/acc_ar_prj');
		$menu['acc_ar_prj']["Consultancy And Short Courses"]['View Project Account Form'] = site_url('consultant/project_account/view_all/acc_ar_prj');
		

		return $menu;
		
	}
}
