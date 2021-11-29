<?php

if (!defined("BASEPATH")) {
    exit("No direct script access allowed");
}

class transcript_cbcs_menu_model extends CI_Model {

    function __construct() {
        // Call the Model constructor
        parent::__construct();
    }

    function getMenu() {
        $menu = array();
        
		
		$menu['exam_da1'] = array();
        $menu['exam_da1']['CBCS Transcript/Gradesheet'] = site_url('transcript_cbcs/transcript_single');
		
		$menu['exam_dr'] = array();
        $menu['exam_dr']['CBCS Transcript/Gradesheet'] = site_url('transcript_cbcs/transcript_single');

        return $menu;
    }

}
