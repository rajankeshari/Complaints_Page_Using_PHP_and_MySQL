<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Accounts_project_menu_model extends CI_Model
{
	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}

	function getMenu()
	{
		$menu = array();
		
		// auth - dean_rnd
		$menu['dean_rnd']['Project Management']=array();
		
		$menu['dean_rnd']['Project Management']['Submitted Project']['Add Project Details'] = site_url('accounts_project/project_management');
        $menu['dean_rnd']['Project Management']['Submitted Project']['Edit Project Details'] = site_url('accounts_project/project_management/edit_project_management');
	
		
		$menu['dean_rnd']['Project Management']['Project']=array();
		$menu['dean_rnd']['Project Management']['Project']['Add Project'] = site_url('accounts_project/project_details/project_add_form');
		$menu['dean_rnd']['Project Management']['Project']['View/Adjust'] = site_url('accounts_project/view_project/get_all_projects');
		$menu['dean_rnd']['Project Management']['Project']['UC'] = site_url('accounts_project/uc_project');

		$menu['dean_rnd']['Project Management']['Funding Agency'] = array();
		$menu['dean_rnd']['Project Management']['Funding Agency']['View Agency'] = site_url('accounts_project/funding_agency/view_funding_agency');

		$menu['dean_rnd']['Project Management']['Funds Management']['Review Bills'] = site_url('accounts_project/billing/get_bills_dean');
		$menu['dean_rnd']['Project Management']['Funds Management']['Bill History'] = site_url('accounts_project/billing/view_history_so_admin');
		
		$menu['dean_rnd']['Project Management']['Report']['Abstract View'] = site_url('accounts_project/project_report');
		//$menu['dean_rnd']['Project Management']['Report']['Full View'] = site_url('accounts_project/project_report_new');
		
	

		// auth - project_admin
		$menu['project_admin']['Project']=array();
		$menu['project_admin']['Project']['Add Project'] = site_url('accounts_project/project_details/project_add_form');
		$menu['project_admin']['Project']['View/Adjust'] = site_url('accounts_project/view_project/get_all_projects');
		$menu['project_admin']['Project']['Project Report'] = site_url('accounts_project/project_report');

		$menu['project_admin']['Funding Agency'] = array();
		$menu['project_admin']['Funding Agency']['Add Agency'] = site_url('accounts_project/funding_agency/add_funding_agency_form');
		$menu['project_admin']['Funding Agency']['View/Edit Agency'] = site_url('accounts_project/funding_agency/edit_funding_agency');

		$menu['project_admin']['Funds Management']['Review Bills'] = site_url('accounts_project/billing/get_bills_admin');
		$menu['project_admin']['Funds Management']['Add Installments'] = site_url('accounts_project/installment');
		$menu['project_admin']['Funds Management']['Bill History'] = site_url('accounts_project/billing/view_history_so_admin');
		$menu['project_admin']['Funds Management']['Add Old Bill'] = site_url('accounts_project/old_bills');
		$menu['project_admin']['Funds Management']['Edit Old Bill'] = site_url('accounts_project/old_bills/get_all_edit_project');
		$menu['project_admin']['Funds Management']['View Bill'] = site_url('accounts_project/old_bills/get_new_bill');
		$menu['project_admin']['Funds Management']['Add Interest'] = site_url('accounts_project/interest');

		// auth - ar_project
		$menu['ar_project']['Project Management']['Project']=array();
		$menu['ar_project']['Project Management']['Project']['View/Adjust'] = site_url('accounts_project/view_project/get_all_projects');
		$menu['ar_project']['Project Management']['Project']['UC'] = site_url('accounts_project/uc_project');

		$menu['ar_project']['Project']=array();
		$menu['ar_project']['Project']['Add Project'] = site_url('accounts_project/project_details/project_add_form');
		$menu['ar_project']['Project']['View/Adjust'] = site_url('accounts_project/view_project/get_all_projects');
		$menu['ar_project']['Project']['Project Report'] = site_url('accounts_project/project_report');

		$menu['ar_project']['Funding Agency'] = array();
		$menu['ar_project']['Funding Agency']['Add Agency'] = site_url('accounts_project/funding_agency/add_funding_agency_form');
		$menu['ar_project']['Funding Agency']['View/Edit Agency'] = site_url('accounts_project/funding_agency/edit_funding_agency');

		$menu['ar_project']['Funds Management']['Review Bills'] = site_url('accounts_project/billing/get_bills_admin');
		$menu['ar_project']['Funds Management']['Add Installments'] = site_url('accounts_project/installment');
		$menu['ar_project']['Funds Management']['Bill History'] = site_url('accounts_project/billing/view_history_so_admin');
		$menu['ar_project']['Funds Management']['Add Old Bill'] = site_url('accounts_project/old_bills');
		$menu['ar_project']['Funds Management']['Edit Old Bill'] = site_url('accounts_project/old_bills/get_all_edit_project');
		$menu['ar_project']['Funds Management']['View Bill'] = site_url('accounts_project/old_bills/get_new_bill');
		$menu['ar_project']['Funds Management']['Add Interest'] = site_url('accounts_project/interest');


		// auth - project_so
		$menu['project_so']['Project']=array();
		$menu['project_so']['Project']['Add Project'] = site_url('accounts_project/project_details/project_add_form');
		$menu['project_so']['Project']['View/Adjust'] = site_url('accounts_project/view_project/get_all_projects');
		
		$menu['project_so']['Funding Agency'] = array();
		$menu['project_so']['Funding Agency']['Add Agency'] = site_url('accounts_project/funding_agency/add_funding_agency_form');
		$menu['project_so']['Funding Agency']['View/Edit Agency'] = site_url('accounts_project/funding_agency/edit_funding_agency');

		// $menu['project_so']['Funds Management']['Review Bills'] = site_url('accounts_project/billing/get_bills_SO');
		$menu['project_so']['Funds Management']['Add Installments'] = site_url('accounts_project/installment');
		$menu['project_so']['Funds Management']['Bill History'] = site_url('accounts_project/billing/view_history_so_admin');
		$menu['project_so']['Funds Management']['Add Old Bill'] = site_url('accounts_project/old_bills');
		$menu['project_so']['Funds Management']['Edit Old Bill'] = site_url('accounts_project/old_bills/get_all_edit_project');
		$menu['project_so']['Funds Management']['View Bill'] = site_url('accounts_project/old_bills/get_new_bill');
		$menu['project_so']['Funds Management']['Add Interest'] = site_url('accounts_project/interest');




		//auth ==> faculty
		$menu['ft']['Project Management']=array();
		$menu['ft']['Project Management']['Project']=array();
		$menu['ft']['Project Management']['Project']['View Project'] = site_url('accounts_project/view_project');

		$menu['ft']['Project Management']['Funding Agency'] = array();
		$menu['ft']['Project Management']['Funding Agency']['View Agency'] = site_url('accounts_project/funding_agency/view_funding_agency');
		
		$menu['ft']['Project Management']['Funds Management']['Add New Bill'] = site_url('accounts_project/billing');
		$menu['ft']['Project Management']['Funds Management']['Bill History'] = site_url('accounts_project/billing/view_history');



		return $menu;
	}
}


	/* End of file accounts_project_menu_model.php */
	/* Location: ./application/models/accounts_project/accounts_project_menu_model.php */
	