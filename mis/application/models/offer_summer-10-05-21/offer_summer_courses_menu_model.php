<?php

if (!defined("BASEPATH")) {
    exit("No direct script access allowed");
}

class Offer_summer_courses_menu_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function getMenu() {
        $menu = array();



		
		
		
		$menu['cm'] = array();
    $menu['cm']['Summer'] = array();
		$menu['cm']['Summer']['Offer Course'] = site_url('offer_summer/offer_summer_courses');


        return $menu;
    }

}
