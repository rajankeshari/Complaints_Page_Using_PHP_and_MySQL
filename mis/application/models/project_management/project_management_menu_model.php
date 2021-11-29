<?php

if (!defined("BASEPATH")) {
    exit("No direct script access allowed");
}

class Project_management_menu_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function getMenu() {
        $menu = array();
     //   $menu['dean_rnd'] = array();
       // $menu['dean_rnd']['Project Management'] = '';
      //  $menu['dean_rnd']['Project Management']['Submitted Project']['Add Project Details'] = site_url('accounts_project/project_management');
      //  $menu['dean_rnd']['Project Management']['Submitted Project']['Edit Project Details'] = site_url('accounts_project/project_management/edit_project_management');
        return $menu;
    }

}
