<?php

class Form_insert_model extends CI_Model
{
    private $form = 'reg_summer_form';
    private $subject ='reg_summer_subject';
    private $fee ='reg_summer_fee';
   
    function __construct() {
        parent::__construct();
    }
    
    function insertForm($data){
        if($this->db->insert($this->form, $data)){
           return $this->db->insert_id();
        }
    }
    
    function insertSubject($data){
         if($this->db->insert($this->subject, $data)){
           return true;
        }
    }
    function insertFeeDetails($fee){
         if($this->db->insert($this->fee, $fee)){
           return true;
        }
    }
}
?>

