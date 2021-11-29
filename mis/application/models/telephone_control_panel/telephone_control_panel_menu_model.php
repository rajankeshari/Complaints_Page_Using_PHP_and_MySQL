<?php
class Telephone_control_panel_menu_model extends CI_model

{

   function __construct()
   {

	parent::__construct();
	
   }

  function getMenu()
  {

	$menu = array();
  	

    $menu['admin']['Telephone Panel']=array();
    $menu['admin']['Telephone Panel']['Telephone Structure']=site_url('telephone_control_panel/telephone_control_panel');
                           
    return $menu;
 
  }


}




?>