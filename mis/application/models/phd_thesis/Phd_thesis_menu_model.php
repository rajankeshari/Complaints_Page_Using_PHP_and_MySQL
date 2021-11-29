<?php
if (!defined("BASEPATH")) {
  exit("No direct script access allowed");
}

class Phd_thesis_menu_model extends CI_Model
{

  function __construct()
  {
    parent::__construct();
  }

  function getMenu()
  {
    $menu = array();

    $menu['asst_lib'] = array();
    $menu['asst_lib']['Assistant Librarian'] = array();
    $menu['asst_lib']['Assistant Librarian']['Phd Scholars'] = site_url('phd_thesis/phd_thesis');
    // $menu['asst_lib']['Assistant Librarian']['JRF Student Report'] = site_url('jrf_reg/jrf_registration/showreport');

    // $menu['stu'] = array();
    // $menu['stu']['PHD Progress'] = array();
    // $menu['stu']['PHD Progress'] = site_url('jrf_reg/jrf_registration');
    // // $menu['adpg']['JRF Registration']['JRF Student Report'] = site_url('jrf_reg/jrf_registration/showreport');

    return $menu;
  }
}
