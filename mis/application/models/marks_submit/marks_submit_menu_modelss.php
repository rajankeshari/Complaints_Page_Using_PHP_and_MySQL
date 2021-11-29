<?php 
require_once "ltc_constants.php";
class marks_submit_menu_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}
	function getMenu()
	{
		$menu = array();
		$menu['ft'] = array();
		$menu['ft']['Upload Marks']= site_url('marks_submit/marks_main/');
		

		return $menu;
	}   
}

?>