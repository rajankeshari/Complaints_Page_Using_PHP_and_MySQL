<?php
class Expected_pre_registration_menu_model extends CI_Model{

       function __construct()
	   {
		// Call the Model constructor
		parent::__construct();
	   }
	   function getMenu(){
            $menu=array();
            $menu['adug']['Expected Pre Registration List']=array();
            $menu['adug']['Expected Pre Registration List']['Expected Pre Registration']=site_url('expected_pre_registration/expected_pre_registration');

           return $menu;
	   }


}
?>