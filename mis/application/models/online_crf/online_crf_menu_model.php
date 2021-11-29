<?php

if (!defined("BASEPATH")) {
    exit("No direct script access allowed");
}

class Online_crf_menu_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function getMenu() {
        // $menu = array();
        
        // $menu['adri'] = array();
        // $menu['adri']['CRF'] = array();        
        // $menu['adri']['CRF'][' Admin Panel'] = site_url('online_crf/online_crf');
        // $menu['adri']['CRF'][' Booking Panel'] = site_url('online_crf/online_crf_booking');

        
        // $menu['ft'] = array();
        // $menu['ft']['CRF'] = array();
        // $menu['ft']['CRF'][' Instrument Detail'] = site_url('online_crf/ft_online_crf');
        // $menu['ft']['CRF'][' View Booking'] = site_url('online_crf/online_crf_booking');


        $menu = array();
        $menu['adri'] = array();
        $menu['adri']['CRF'] = array();        
        $menu['adri']['CRF'][' Admin Panel'] = site_url('online_crf/online_crf');
        $menu['adri']['CRF'][' Booking Panel'] = site_url('online_crf/online_crf_booking');
        $menu['adri']['CRF'][' Booking Detail'] = site_url('online_crf/ft_online_crf');

        // $menu['ft'] = array();
        // $menu['ft']['Research']['CRF'] = array();
        // $menu['ft']['Research']['CRF'][' Instrument Detail'] = site_url('online_crf/ft_online_crf');
        // $menu['ft']['Research']['CRF'][' Instrument Booking'] = site_url('online_crf/online_crf_booking');
        // $menu['ft']['Research']['CRF'][' Booking Detail'] = site_url('online_crf/online_crf_booking/booking_detail');

        
        // $menu['adri'] = array();
        // $menu['adri']['Fee Status'] = array();        
        // $menu['adri']['Fee Status'] = site_url('fee_status/fee_status');


        return $menu;
    }
    
}
