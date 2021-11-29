<?php

if (!defined("BASEPATH")) {
    exit("No direct script access allowed");
}

class Department_grouping_menu_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function getMenu() {
        $menu = array();

        $menu['dean_acad'] = array();
        $menu['dean_acad']['Department Wise Grouping'] = array();
        $menu['dean_acad']['Department Wise Grouping']['Department Grouping'] = site_url('department_grouping/department_grouping');
        $menu['dean_acad']['Department Wise Grouping']['Grouping Course Type'] = site_url('department_grouping/group_course_type');

        $menu['cm'] = array();
        $menu['cm']['Department Wise Grouping'] = array();
        $menu['cm']['Department Wise Grouping']['Department Grouping'] = site_url('department_grouping/department_grouping');
        $menu['cm']['Department Wise Grouping']['Grouping Course Type'] = site_url('department_grouping/group_course_type');

        $menu['acad_arug'] = array();
        $menu['acad_arug']['Department Wise Grouping'] = array();
        $menu['acad_arug']['Department Wise Grouping']['Department Grouping'] = site_url('department_grouping/department_grouping');
        $menu['acad_arug']['Department Wise Grouping']['Grouping Course Type'] = site_url('department_grouping/group_course_type');

        $menu['adug'] = array();
        $menu['adug']['Department Wise Grouping'] = array();
        $menu['adug']['Department Wise Grouping']['Department Grouping'] = site_url('department_grouping/department_grouping');
        $menu['adug']['Department Wise Grouping']['Grouping Course Type'] = site_url('department_grouping/group_course_type');


        return $menu;
    }

}