<?php

class Auth_menu_details_menu_model  extends CI_Model
{
	function __construct()
	{
		// Calling the Model parent constructor
		parent::__construct();
	}

	function getMenu()
	{
		$menu=array();
		
                
		$menu['deo']=array();
		$menu['deo']["Menu detail"]=site_url('auth_menu_details/auth_menu_details/insert_detail');
		// $menu['deo']["Menu detail"]["Enter menu detail"] =site_url('auth_menu_details/auth_menu_details/insert_detail');


		return $menu;
	}

}
?>