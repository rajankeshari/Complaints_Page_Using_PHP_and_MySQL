<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Description of global_variable_model
 * @created on : Saturday, 11-Mar-2017 06:50:33
 * @author Rohit Rana <rohitkkrana@gmail.com>
 * Copyright 2017    
 */
 
 
class global_variable_model extends CI_Model 
{

    public function __construct() 
    {
        parent::__construct();
    }


    /**
     *  Get All data acc_global_variables
     *
     *  @param limit  : Integer
     *  @param offset : Integer
     *
     *  @return array
     *
     */
    public function get_all($limit, $offset) 
    {

        $result = $this->db->get('acc_global_variables', $limit, $offset);

        if ($result->num_rows() > 0) 
        {
            return $result->result_array();
        } 
        else 
        {
            return array();
        }
    }

    

    /**
     *  Count All acc_global_variables
     *    
     *  @return Integer
     *
     */
    public function count_all()
    {
        $this->db->from('acc_global_variables');
        return $this->db->count_all_results();
    }
    

    /**
    * Search All acc_global_variables
    *
    *  @param limit   : Integer
    *  @param offset  : Integer
    *  @param keyword : mixed
    *
    *  @return array
    *
    */
    public function get_search($limit, $offset) 
    {
        $keyword = $this->session->userdata('keyword');
                
        $this->db->like('variable_name', $keyword);  
                
        $this->db->like('variable_alis', $keyword);  
                
        $this->db->like('rate_type', $keyword);  
        
        $this->db->limit($limit, $offset);
        $result = $this->db->get('acc_global_variables');

        if ($result->num_rows() > 0) 
        {
            return $result->result_array();
        } 
        else 
        {
            return array();
        }
    }

    
    
    
    
    
    /**
    * Search All acc_global_variables
    * @param keyword : mixed
    *
    * @return Integer
    *
    */
    public function count_all_search()
    {
        $keyword = $this->session->userdata('keyword');
        $this->db->from('acc_global_variables');        
                
        $this->db->like('variable_name', $keyword);  
                
        $this->db->like('variable_alis', $keyword);  
                
        $this->db->like('rate_type', $keyword);  
        
        return $this->db->count_all_results();
    }


    
    
    
    /**
    *  Get One acc_global_variables
    *
    *  @param id : Integer
    *
    *  @return array
    *
    */
    public function get_one($id) 
    {
        $this->db->where('id', $id);
        $result = $this->db->get('acc_global_variables');

        if ($result->num_rows() == 1) 
        {
            return $result->row_array();
        } 
        else 
        {
            return array();
        }
    }

    
    
    
    /**
    *  Default form data acc_global_variables
    *  @return array
    *
    */
    public function add()
    {
        $data = array(
            
                'variable_name' => '',
            
                'variable_alis' => '',
            
                'rate' => '',
            
                'rate_type' => '',
            
        );

        return $data;
    }

    
    
    
    
    /**
    *  Save data Post
    *
    *  @return void
    *
    */
    public function save() 
    {
        $data = array(
        
            'variable_name' => strip_tags($this->input->post('variable_name', TRUE)),
        
            'variable_alis' => strip_tags($this->input->post('variable_alis', TRUE)),
        
            'rate' => strip_tags($this->input->post('rate', TRUE)),
        
            'rate_type' => strip_tags($this->input->post('rate_type', TRUE)),
        
        );
        
        
        $this->db->insert('acc_global_variables', $data);
    }
    
    
    

    
    /**
    *  Update modify data
    *
    *  @param id : Integer
    *
    *  @return void
    *
    */
    public function update($id)
    {
        $data = array(
        
                'variable_name' => strip_tags($this->input->post('variable_name', TRUE)),
        
                'variable_alis' => strip_tags($this->input->post('variable_alis', TRUE)),
        
                'rate' => strip_tags($this->input->post('rate', TRUE)),
        
                'rate_type' => strip_tags($this->input->post('rate_type', TRUE)),
        
        );
        
        
        $this->db->where('id', $id);
        $this->db->update('acc_global_variables', $data);
    }


    
    
    
    /**
    *  Delete data by id
    *
    *  @param id : Integer
    *
    *  @return void
    *
    */
    public function destroy($id)
    {       
        $this->db->where('id', $id);
        $this->db->delete('acc_global_variables');
        
    }







    



}
