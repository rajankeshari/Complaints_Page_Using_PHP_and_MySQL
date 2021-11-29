<?php
class Admin_structure_menu_model extends CI_model

{

   function __construct()
   {

	parent::__construct();
	
   }

  function getMenu()
  {

	$menu = array();
  	

    $menu['admin']['Admin Structure']=array();
    $menu['admin']['Admin Structure']['Admin Structure panel']=site_url('admin_structure/admin_structure');
                           
    return $menu;
 
  }


}




?>