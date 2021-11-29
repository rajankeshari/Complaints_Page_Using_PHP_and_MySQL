<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of commonClassModel
 *
 * @author Ritu Raj <rituraj00@rediffmail.com>
 */
class Common_class_model extends CI_Model {
    private static $db;
       function __construct() {
        // Call the Model constructor
        parent::__construct();
        self::$db = &get_instance()->db;
         
    }
     public static function get_current_exam_static() {
        $sql = " select  syear as session_year,  
                (case 
                  when (b.session='Monsoon'  and b.`exam_type`='R') then  '1' 
                  when (b.session='Monsoon'  and b.`exam_type`='S') then  '2'                   
                  when (b.session='Monsoon'  and b.`exam_type`='O') then  '1'
                  when b.session='Winter'  and b.`exam_type`='R' then  '4'
                  when b.session='Winter'  and b.`exam_type`='E' then  '5'
                  when b.session='Winter'  and b.`exam_type`='S' then  '6'
                  when b.session='Summer'  and b.`exam_type`='R' then  '7'
                  when b.session='Winter'  and b.`exam_type`='O' then  '4'                                                                                                                   
               end) as custom_exm_type 
               ,b.session,b.`exam_type` from exam_held_time b  order by b.session_year desc, custom_exm_type desc limit 1";
        $query = self::$db->query($sql);
        return ($query->num_rows() > 0 ? $query->result() : false);
    }
}
