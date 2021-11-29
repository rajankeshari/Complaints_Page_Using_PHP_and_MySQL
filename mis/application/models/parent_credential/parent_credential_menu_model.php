<?php
if (!defined("BASEPATH")) {
    exit("No direct script access allowed");
}

class Parent_credential_menu_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function getMenu() 
    {
        $menu = array();

        $menu['parent_admin'] = array();
        $menu['parent_admin']['Parent Credentials'] = array();
        $menu['parent_admin']['Parent Credentials'] = site_url('parent_credential/parent_credential');

        $menu['parent_incharge'] = array();
        $menu['parent_incharge']['Parent Credentials'] = array();
        $menu['parent_incharge']['Parent Credentials'] = site_url('parent_credential/parent_credential');

        return $menu;
    }

}
