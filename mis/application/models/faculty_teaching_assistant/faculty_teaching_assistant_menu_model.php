<?php

if (!defined("BASEPATH")) {
    exit("No direct script access allowed");
}

class faculty_teaching_assistant_menu_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function getMenu() {
        $menu = array();

        //ft
        //$menu['dpgc']['Teaching Assistant Mapping']= array();
        //$menu['dpgc']['Teaching Assistant Mapping'] = site_url('faculty_teaching_assistant/ft_ta_mapping');


        //$menu['dugc']['Teaching Assistant Mapping']= array();
        //$menu['dugc']['Teaching Assistant Mapping'] = site_url('faculty_teaching_assistant/ft_ta_mapping');


        
        

        

        
        
        
        
        



        return $menu;

            //          insert into auth_types(id,type)values('mis_db','MIS Dashboard');
            //          insert into user_auth_types(id,auth_id)values('mis-anuj','mis_db'); 
    }

}
