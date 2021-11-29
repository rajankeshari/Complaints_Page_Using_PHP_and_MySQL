<?php

class Form_insert_model extends CI_Model
{
    private $form = 'reg_idle_form';
    private $fee ='reg_idle_fee';
   
    function __construct() {
        parent::__construct();
    }
    
    function insertForm($data){
        if($this->db->insert($this->form, $data)){
           return $this->db->insert_id();
        }
    }
    
  
    function insertFeeDetails($fee){
         if($this->db->insert($this->fee, $fee)){
           return true;
        }
    }
}
?>

