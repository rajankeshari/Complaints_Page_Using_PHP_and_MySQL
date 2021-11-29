<?php

class Eso_mapping_menu_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function getMenu() {
        $menu = array();

      //  $menu['adug'] = array();
   //     $menu['adug']['CBCS']['ESO']['Guided ESO'] = site_url('cbcs_guided_eso/eso_mapping');
		//$menu['adug']['CBCS']['ESO']['Offered ESO'] = site_url('cbcs_guided_eso/eso_mapping/offered_eso');
       
       

        return $menu;
    }

}