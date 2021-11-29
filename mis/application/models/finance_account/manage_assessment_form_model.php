<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Description of deduction_fields_model
 * @created on : Friday, 24-Feb-2017 11:27:40
 * @author Rohit Rana <rohitkkrana@gmail.com>
 * Copyright 2017    
 */
 
 
class manage_assessment_form_model extends CI_Model 
{

    public function __construct() 
    {
        parent::__construct();
    }

    public function getTabeColumn(){
    	if($fields = $this->db->field_data('acc_assessment_form_details')){
    		return $fields;
    	}
    	else{
    		return false;
    	}
    }

    function getFieldsDetails(){
    	if($query=$this->db->get('acc_assessment_from_fields')){
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

    function saveField($tbl,$record){
    	if($this->db->insert($tbl,$record)){
    		return true;
    	}
    	else{
    		return false;
    	}
    }

    function remove($cond){
    	if($this->db->delete('acc_assessment_from_fields',$cond)){
    		return true;
    	}
    	else{
    		return false;
    	}
    }

    function getSheet($FY){
        $q="select distinct(a.SHEET) from acc_assessment_from_fields a where a.FY=?";
        if($query=$this->db->query($q,$FY)){
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

    function getField($cond){
        $this->db->where($cond);
        if($query=$this->db->get('acc_assessment_from_fields')){
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

    function getEmpRecord($fields,$cond){
        $this->db->select($fields);
        $this->db->where($cond);
        if($query=$this->db->get('acc_assessment_form_details')){
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

    function addupdateRecord($record){
        //var_dump($record);die();
        $cond=array(
            'FY'=>$record['FY'],
            'EMPNO'=>$record['EMPNO']
        );
        if($query=$this->db->get_where('acc_assessment_form_details',$cond)){
            if($query->num_rows()>0){
                $this->db->where($cond);
                if($this->db->update('acc_assessment_form_details',$record)){
                    return true;
                }
                return false;
            }
            else{
                if($this->db->insert('acc_assessment_form_details',$record)){
                    return true;
                }
                else{
                    return false;
                }
            }
        }
        else{
            return false;
        }
    }
}

?>