<?php

class Dipankarsir_menu_model extends CI_Model
{
	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}

	function getMenu()
	{
		$menu=array();
		//auth ==> dev
		$menu['dev']['Dipankar-Sir SEMCODE']=site_url('dipankarsir/semcode');
		
		return $menu;
	}
}