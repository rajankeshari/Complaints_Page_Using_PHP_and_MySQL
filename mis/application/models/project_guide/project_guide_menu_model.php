<?php

class Project_guide_menu_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function getMenu() {
        $menu = array();

        $menu['hod'] = array();
        $menu['hod']["Project Guide"]['Assign Guide(For JRF)'] = site_url('fellowship/fellowshipProcess/get_fellow_list');
        $menu['hod']["Project Guide"]['Assign Guide(For PG/UG)'] = site_url('project_guide/project_guide/manage_list');
		$menu['hod']["Project Guide"]['Assignment & Fellowship'] = site_url('project_assignment/fellowship_report');

        $menu['jcord'] = array();
        $menu['jcord']["Project Guide"]['Assign Guide(For JRF)'] = site_url('fellowship/fellowshipProcess/get_fellow_list/dept_da');

        $menu['fa'] = array();
        $menu['fa']["Project Guide"]['Assign Guide(For PG/UG)'] = site_url('project_guide/project_guide/manage_list');

        $menu['ft'] = array();
        $menu['ft']["Project Guide"]['View Student'] = site_url('project_guide/project_guide/view_assigned_ft_student');
		$menu['ft']["Project Guide"]['Assignments'] = site_url('project_assignment/project_assign');


        return $menu;
    }

}
