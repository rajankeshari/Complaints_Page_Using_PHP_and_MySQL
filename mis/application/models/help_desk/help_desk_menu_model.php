<?php

class Help_desk_menu_model extends CI_Model {

    function __construct() {
        // Call the Model constructor
        parent::__construct();
    }

    function getMenu() {
        $menu = array();

        // Dealing Assistance 1 for Main Store
        $menu['stu_report'] = array();
        $menu['stu_report']['Quick Help Desk'] = site_url('help_desk/help_desk');


        return $menu;
    }

}
?>

