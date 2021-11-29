<?php

if (!defined("BASEPATH")) {
    exit("No direct script access allowed");
}

class Student_hall_ticket_menu_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function getMenu() {
        $menu = array();

       

     //   $menu['stu'] = array();
       // $menu['stu']['Hall Ticket'] = array();
        //$menu['stu']['Hall Ticket'] = site_url('student_hall_ticket/hall_ticket');
         
        $menu['exam_dr'] = array();
        $menu['exam_dr']['Hall Ticket'] = array();
        $menu['exam_dr']['Hall Ticket'] = site_url('student_hall_ticket/hall_ticket');
         
        $menu['acad_ar'] = array();
        $menu['acad_ar']['Hall Ticket'] = array();
        $menu['acad_ar']['Hall Ticket'] = site_url('student_hall_ticket/hall_ticket');
        
       $menu['admin_exam'] = array();
        $menu['admin_exam']['Hall Ticket'] = array();
        $menu['admin_exam']['Hall Ticket'] = site_url('student_hall_ticket/hall_ticket');
        
       $menu['exam_da1'] = array();
        $menu['exam_da1']['Hall Ticket'] = array();
       $menu['exam_da1']['Hall Ticket'] = site_url('student_hall_ticket/hall_ticket');
	
		$menu['exam_da2'] = array();
        $menu['exam_da2']['Hall Ticket'] = array();
       $menu['exam_da2']['Hall Ticket'] = site_url('student_hall_ticket/hall_ticket');
      



        return $menu;
    }

}
