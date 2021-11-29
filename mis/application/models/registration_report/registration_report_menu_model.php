<?php
class Registration_report_menu_model extends CI_model

{

   function __construct()
   {

	parent::__construct();
	
   }

  function getMenu()
  {

	$menu = array();
	//$menu['adac']['Registration Report']=array();
   // $menu['adac']['Registration Report']['Generate Registration Report']=site_url('registration_report/Registration_report');
	//$menu['adac']['Registration Report']['Backlog Course Report']=site_url('registration_report/registration_report/index/backpaper');
                           
    return $menu;
 
  }


}




?>