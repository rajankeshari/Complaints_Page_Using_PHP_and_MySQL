<?php

if (!defined("BASEPATH")) {
    exit("No direct script access allowed");
}

class Online_crf_menu_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function getMenu() {
        $menu = array();

        $menu['adri'] = array();
        $menu['adri']['CRF'] = array();        
        $menu['adri']['CRF'][' Admin Panel'] = site_url('online_crf/online_crf');
        $menu['adri']['CRF'][' Booking Panel'] = site_url('online_crf/online_crf_booking');

        $menu['ft'] = array();
        $menu['ft']['CRF'] = array();
        $menu['ft']['CRF'][' Admin Panel'] = site_url('online_crf/ft_online_crf');

        return $menu;
    }

}
