<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Description of deduction_fields_model
 * @created on : Friday, 24-Feb-2017 11:27:40
 * @author Rohit Rana <rohitkkrana@gmail.com>
 * Copyright 2017    
 */
 
 
class cess_management_model extends CI_Model 
{

    public function __construct() 
    {
        parent::__construct();
    }

    public function getResult(){
    	if($query = $this->db->get('acc_assessment_cess_details')){
    		if($query->num_rows()>0){
                return $query->result();
            }
            else{
                return false;
            }
    	}
    	else{
    		return false;
    	}
    }

    function saveCess($data){
        if($this->db->insert('acc_assessment_cess_details',$data)){
            return true;
        }
        else{
            return false;
        }

    }

    function remove($fy){
        $this->db->where('FY',$fy);
        if($this->db->delete('acc_assessment_cess_details')){
            return true;
        }
        else{
            return false;
        }
    }
}