<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Description of formula_master_model
 * @created on : Saturday, 25-Mar-2017 06:10:54
 * @author Rohit Rana <rohitkkrana@gmail.com>
 * Copyright 2017    
 */
 
 
class formula_master_model extends CI_Model 
{

    protected $formula = '';

    public function __construct() 
    {
        parent::__construct();
    }


    /**
     *  Get All data acc_field_formula
     *
     *  @param limit  : Integer
     *  @param offset : Integer
     *
     *  @return array
     *
     */
    public function get_all($limit, $offset) 
    {

        $result = $this->db->get('acc_field_formula', $limit, $offset);

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
     *  Count All acc_field_formula
     *    
     *  @return Integer
     *
     */
    public function count_all()
    {
        $this->db->from('acc_field_formula');
        return $this->db->count_all_results();
    }
    

    /**
    * Search All acc_field_formula
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
                
        $this->db->like('field', $keyword);  
                
        $this->db->like('alise', $keyword);  
                
        $this->db->like('formula', $keyword);  
        
        $this->db->limit($limit, $offset);
        $result = $this->db->get('acc_field_formula');

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
    * Search All acc_field_formula
    * @param keyword : mixed
    *
    * @return Integer
    *
    */
    public function count_all_search()
    {
        $keyword = $this->session->userdata('keyword');
        $this->db->from('acc_field_formula');        
                
        $this->db->like('field', $keyword);  
                
        $this->db->like('alise', $keyword);  
                
        $this->db->like('formula', $keyword);  
        
        return $this->db->count_all_results();
    }


    
    
    
    /**
    *  Get One acc_field_formula
    *
    *  @param id : Integer
    *
    *  @return array
    *
    */
    public function get_one($id) 
    {
        $this->db->where('', $id);
        $result = $this->db->get('acc_field_formula');

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
    *  Default form data acc_field_formula
    *  @return array
    *
    */
    public function add()
    {
        $data = array(
            
                'id' => '',
            
                'field' => '',
            
                'alise' => '',
            
                'formula' => '',
            
                'generate_by' => '',
            
                'datetime' => '',
            
                'status' => '',
            
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
        
            'id' => strip_tags($this->input->post('id', TRUE)),
        
            'field' => strip_tags($this->input->post('field', TRUE)),
        
            'alise' => strip_tags($this->input->post('alise', TRUE)),
        
            'formula' => strip_tags($this->input->post('formula', TRUE)),
        
            'generate_by' => $this->session->userdata('id'),
        
            'datetime' => date('Y-m-d H:i:s'),
        
            'status' => strip_tags($this->input->post('status', TRUE)),
        
        );
        
        
        $this->db->insert('acc_field_formula', $data);
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
        
                'id' => strip_tags($this->input->post('id', TRUE)),
        
                'field' => strip_tags($this->input->post('field', TRUE)),
        
                'alise' => strip_tags($this->input->post('alise', TRUE)),
        
                'formula' => strip_tags($this->input->post('formula', TRUE)),
        
                'generate_by' => strip_tags($this->input->post('generate_by', TRUE)),
        
                'datetime' => strip_tags($this->input->post('datetime', TRUE)),
        
                'status' => strip_tags($this->input->post('status', TRUE)),
        
        );
        
        
        $this->db->where('', $id);
       // $this->db->update('acc_field_formula', $data);
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
        $this->db->where('', $id);
        $this->db->delete('acc_field_formula');
        
    }




    ///////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////
    //                                  Formula calculation                                                  //
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////


    protected function getformulaByField($field) {
                            $this->db->limit('1');
                            $this->db->order_by('datetime','desc');
                            $res = $this->db->get_where('acc_field_formula',['field'=>$field]);
                            if($res->num_rows() > 0 )
                                $this->formula = $this->row()->formula;
                            else
                                $this->formula = false;

                       }

    function getformulaResult($id,$field){

            $this->getformulaByField($field);

       $this->load->model('finance_account/payble_fields_model','PFM');
         $this->load->model('finance_account/deduction_fields_model','DFM');

          $deduction_fields = $this->DFM->get_all(1000, '');
           $payble_fields = $this->PFM->get_all(1000, '');
           $f = array_merge($deduction_fields, $payble_fields)

           foreach($f as $val){
                if(strpos($this->formula,$val->field)){
                    $this->formula =str_replace($val->field, $this->GetFieldValueById($id,$val->field), $this->formula);
                }
           }

           return (int)$this->formula;
        
    }

            


    protected function GetFieldValueById($id,$field) 

                $res=$this->db->get_where('acc_pay_details_temp',['EMPNO'=>$id]);

                if($res->num_rows() > 0)
                    reutrn $res->row()->$fields;
    }

    



}
