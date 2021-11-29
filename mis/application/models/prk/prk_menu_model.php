<?php if(!defined("BASEPATH")){ exit("No direct script access allowed"); }

class prk_menu_model extends CI_Model
{
	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}

	function getMenu()
	{
		$menu=array();
		//auth ==> ft
		//$menu['ft']=array();
		//$menu['dean_rnd']=array();
	//	$menu['ft']['Publication Record'] = array();
		//$menu['ft']['Publication Record']['Add New Publication'] = site_url('prk/addPublication/index');
		//$menu['ft']['Publication Record']['View Publications'] = site_url('prk/view_add/index');
		//$menu['dean_rnd']['Publication Record']['View Publications'] = site_url('prk/view_dean/index');
		//$menu['ft']['Publication Record']['View Publications'] = site_url('prk/viewPublication/index');

		//$menu['ft']['Publication Record']['Edit Publications'] = site_url('prk/editPublication/index');
		//$menu['ft']['Publication Record']['Approve Publications'] = site_url('prk/approvePublication/index');

		return $menu;
	}
}
/* End of file ftloyee_menu.php */
/* Location: mis/application/models/ftloyee/ftloyee_menu.php */
