<?php 
require_once "ltc_constants.php";
class Ltc_menu_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}
	function getMenu()
	{
		$menu = array();
		$menu['emp'] = array();
		$menu['emp']['Leave Travel Concession'] = array();
		$menu['emp']['Leave Travel Concession']['Apply For LTC'] = site_url('ltc/employee/ltc_type');
		$menu['emp']['Leave Travel Concession']['LTC History'] = site_url('ltc/employee/ltc_history');
		$menu['emp']['Leave Travel Concession']['Cancel LTC'] = site_url('ltc/employee/cancel_ltc');
		
		$menu['est_da1'] = array();
		$menu['est_da1']['Leave Travel Concession'] = array();
		$menu['est_da1']['Leave Travel Concession']['LTC entry'] = site_url('ltc/manual_data_entry/ltc_type');

		$i = 1;
		foreach(Ltc_constants::$ADMIN_ARRAY as $result)
		{
			$menu[$result] = array();
			$menu[$result]['Leave Travel Concession'] = array();
			$menu[$result]['Leave Travel Concession']['Approval/Cancellation/Forward'] = site_url('ltc/employee/approval_forward_reject_all');
			$menu[$result]['Leave Travel Concession']['Approved/Cancelled/Forwarded'] = site_url('ltc/employee/approved_forwarded_rejected_all');
		}
		foreach(Ltc_constants::$DEPARTMENTAL_ADMIN as $result)
		{
			$menu[$result] = array();
			$menu[$result]['Leave Travel Concession'] = array();
			$menu[$result]['Leave Travel Concession']['Approval/Cancellation/Forward'] = site_url('ltc/employee/approval_forward_reject_all');
			$menu[$result]['Leave Travel Concession']['Approved/Cancelled/Forwarded'] = site_url('ltc/employee/approved_forwarded_rejected_all');
		}
		foreach(Ltc_constants::$SUPER_ADMIN as $result)
		{
			$menu[$result] = array();
			$menu[$result]['Leave Travel Concession'] = array();
			$menu[$result]['Leave Travel Concession']['Approval/Cancellation/Forward'] = site_url('ltc/employee/approval_forward_reject_all');
			$menu[$result]['Leave Travel Concession']['Approved/Cancelled/Forwarded'] = site_url('ltc/employee/approved_forwarded_rejected_all');
		}
		return $menu;
	}
}

?>