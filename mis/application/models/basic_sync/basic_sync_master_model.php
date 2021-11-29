<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Basic_sync_master_model
 *
 * @author Your Name <Ritu Raj @ ISM DHANBAD>
 *//* Category  Data Import  
 * Copyright (c) ISM dhanbad * 
 * @category    phpExcel
 * @package    Basic_sync
 * @copyright  Copyright (c) 2014 - 2015 Ism dhanbad
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt    LGPL
 * @version    ##0.1##, #28/9/16#
 * @Author     Ritu raj<rituraj00@rediffmail.com>
 */

class Basic_sync_master_model extends CI_Model {
    private  $ptable;
    function __construct() {
        // Call the Model constructor
        parent::__construct();
        $this->ptable='user_details';
    }

    function update($col, $where,$prr) {
        //    echo '<pre>'; print_r($col);echo '</pre>';
        //   echo 'where:</br>'; echo '<pre>'; print_r($where);echo '</pre>'; 
        // $col_flat = array_reduce($col, 'array_merge', array());
        //    echo 'cond:</br>'; echo '<pre>'; print_r($col_flat);echo '</pre>';   die();
        //$where_flat = array_reduce($where, 'array_merge', array());
        //  echo 'where:</br>'; echo '<pre>'; print_r($where_flat);echo '</pre>';   die();
         //echo echo '<pre>'; print_r($prr);echo '</pre>';   die();
        $this->db->where($where);
        //$this->db->where("($field = $value || $field2 = $value)");
        $custom_match=array('','0');
         
        foreach ($prr as $field) {
        // $field=$this->db->escape($field);          
         $OR_conditions = $this->make_OR_conditions($field, $custom_match);
         
        $this->db->where($OR_conditions);
        }

        if (!$this->db->update($this->ptable, $col))
            throw new Exception(implode(',', $col) . ':' . ($this->db->_error_message() == null ? "Internal Error Occured" : $this->db->_error_message()));
        else{
            if($this->db->affected_rows())         
               return $this->db->affected_rows();         
            else
               return 0;    
            }
                //$this->db->update($this->ptable, $col); echo $this->db->last_query(); die();
        }
    
    
    function make_OR_conditions ( $field, array $custom_match) {
    $or = array();
    
        foreach ($custom_match as $cust) {
               $cust=$this->db->escape($cust);          
               $or[] = "$field = $cust";
           }
      
        return '('.implode(' or ', $or).')';
   }

}
