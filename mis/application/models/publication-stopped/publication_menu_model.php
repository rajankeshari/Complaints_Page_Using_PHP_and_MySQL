<?php if(!defined("BASEPATH")){ exit("No direct script access allowed"); }

class publication_menu_model extends CI_Model
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
		$menu['ft']=array();
		$menu['ft']['Publication Record'] = array();
		$menu['ft']['Publication Record']['Add New Publication'] = site_url('publication/publication/index');
		$menu['ft']['Publication Record']['View Publications'] = site_url('publication/publication/view');
		$menu['ft']['Publication Record']['Edit Publications'] = site_url('publication/publication/editpublication');
		$menu['ft']['Publication Record']['Approve Publications'] = site_url('publication/publication/approve');

		//auth ==> deo
		// $menu['deo']=array();
		// $menu['deo']['Publication Record']['Add New Publication'] = site_url('publication/publication/index');
		// $menu['deo']['Publication Record']['View Publications'] = site_url('publication/publication/view');
		// $menu['deo']['Publication Record']['Edit Publications'] = site_url('publication/publication/editpublication');
		// $menu['deo']['Publication Record']['Approve Publications'] = site_url('publication/publication/approve');

		return $menu;
	}
}
/* End of file ftloyee_menu.php */
/* Location: mis/application/models/ftloyee/ftloyee_menu.php */
