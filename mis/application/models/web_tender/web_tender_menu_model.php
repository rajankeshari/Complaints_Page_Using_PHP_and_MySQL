<?php

class Web_Tender_Menu_Model extends CI_Model
{
	function __construct()
	{
		// Calling the Model parent constructor
		parent::__construct();
	}

	function getMenu()
	{
		$menu=array();

		$menu['web_tender_admin']=array();
		$menu['web_tender_admin']['Web Tender']=array();
		$menu['web_tender_admin']['Web Tender']['New Tender'] = site_url('web_tender/web_tender');

		$menu['web_tender_purchase']=array();
		$menu['web_tender_purchase']['Web Tender']=array();
		$menu['web_tender_purchase']['Web Tender'] = site_url('web_tender/web_tender');

		$menu['web_tender_civil']=array();
		$menu['web_tender_civil']['Web Tender']=array();
		$menu['web_tender_civil']['Web Tender'] = site_url('web_tender/web_tender');


	/*	$menu['tender']=array();
		$menu['tender']['Hostel Management']=array();
		$menu['tender']['Hostel Management']['Hello'] = site_url('course_structure/elective_offered_home');*/

		return $menu;
	}
}
/* End of file menu_model.php */
/* Location: mis/application/models/course_structure/menu_model.php */
