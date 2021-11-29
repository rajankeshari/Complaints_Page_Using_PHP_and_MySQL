<?php

if (!defined("BASEPATH")) {
    exit("No direct script access allowed");
}

class Leave_types_menu_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function getMenu() {
        $menu = array();      
        $menu['emp'] = array();
        $menu['emp']['Enter Leave Types'] = site_url('leave/leave_types');
        return $menu;
    }

}
