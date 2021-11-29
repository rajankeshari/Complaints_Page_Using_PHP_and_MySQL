<?php

class Web_Admin_Menu_Model extends CI_Model
{
	function __construct()
	{
		// Calling the Model parent constructor
		parent::__construct();
	}

	function getMenu()
	{
		$menu=array();

		$menu['web_admin']=array();
		$menu['web_admin']['Web Admin']=array();
    $menu['web_admin']['Web Admin']['Info For Department'] = site_url('web_admin/web_admin/');
    $menu['web_admin']['Web Admin']['News And Events'] = site_url('web_admin/web_admin/add_article');
		$menu['web_admin']['Web Admin']['New Faculty'] = site_url('web_admin/web_admin/add_user');
		$menu['web_admin']['Web Admin']['Edit Faculty'] = site_url('web_admin/web_admin/edit_user');

		$menu['web_user']=array();
		$menu['web_user']['Web User']=array();
		$menu['web_user']['Edit Faculty'] = site_url('web_admin/web_admin/edit_web_profile');
		$menu['web_user']['Upload Notices'] = site_url('web_admin/web_admin/upload_notices');
		$menu['web_user']['Project Opening'] = site_url('web_admin/web_admin/project_opening');

		$menu['ft']=array();
		$menu['ft']['Web User']=array();
		$menu['ft']['Web User']['Edit Faculty'] = site_url('web_admin/web_admin/edit_web_profile');
		$menu['ft']['Web User']['Upload Notices'] = site_url('web_admin/web_admin/upload_notices');
		$menu['ft']['Web User']['Project Opening'] = site_url('web_admin/web_admin/project_opening');

		/*	$menu['tender']=array();
		$menu['tender']['Hostel Management']=array();
		$menu['tender']['Hostel Management']['Hello'] = site_url('course_structure/elective_offered_home');*/

		return $menu;
	}
}
/* End of file menu_model.php */
/* Location: mis/application/models/course_structure/menu_model.php */
