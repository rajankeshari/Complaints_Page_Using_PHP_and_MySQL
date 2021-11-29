<?php

if (!defined("BASEPATH")) {
    exit("No direct script access allowed");
}

class Delegation_of_power_menu_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function getMenu() {
        $menu = array();

       $menu['emp']['Delegate Power']=array();
		$menu['emp']["Delegate Power"]["Delegation of Power"] = site_url('delegation_of_power/delegate_power/assign_auths');
		$menu['emp']["Delegate Power"]["Relinquish Power"] = site_url('delegation_of_power/delegate_power/deny_auths');

	$menu['deo']=array();
		$menu['deo']["Menu detail"]=site_url('auth_menu_details/auth_menu_details/insert_detail');
        
        
        
        
       
        



        return $menu;
    }

}
