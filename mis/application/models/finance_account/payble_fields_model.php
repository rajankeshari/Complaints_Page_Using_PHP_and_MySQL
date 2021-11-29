<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Description of payble_fields_model
 * @created on : Friday, 24-Feb-2017 11:27:40
 * @author Rohit Rana <rohitkkrana@gmail.com>
 * Copyright 2017    
 */
 
 
class payble_fields_model extends CI_Model 
{

    public function __construct() 
    {
        parent::__construct();
    }


    /**
     *  Get All data acc_pay_field_tbl
     *
     *  @param limit  : Integer
     *  @param offset : Integer
     *
     *  @return array
     *
     */
    public function get_all($limit, $offset,$con='') 
    {
           // $this->db->where('status','Y');
            if(!$con){
                  $this->db->where('status','Y');  
            }
        $this->db->order_by('sn');
        $result = $this->db->get('acc_pay_field_tbl', $limit, $offset);

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
     *  Count All acc_pay_field_tbl
     *    
     *  @return Integer
     *
     */
    public function count_all()
    {
        $this->db->from('acc_pay_field_tbl');
        return $this->db->count_all_results();
    }
    

    /**
    * Search All acc_pay_field_tbl
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
        
        $this->db->limit($limit, $offset);
        $result = $this->db->get('acc_pay_field_tbl');

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
    * Search All acc_pay_field_tbl
    * @param keyword : mixed
    *
    * @return Integer
    *
    */
    public function count_all_search()
    {
        $keyword = $this->session->userdata('keyword');
        $this->db->from('acc_pay_field_tbl');        
        
        return $this->db->count_all_results();
    }


    
    
    
    /**
    *  Get One acc_pay_field_tbl
    *
    *  @param id : Integer
    *
    *  @return array
    *
    */
    public function get_one($id) 
    {
        $this->db->where('id', $id);
        $result = $this->db->get('acc_pay_field_tbl');

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
    *  Default form data acc_pay_field_tbl
    *  @return array
    *
    */
    public function add()
    {
        $data = array(
            
                'field' => '',
            
                'alis' => '',
            
                'status' => '',

                'apply_formula' => '',
            
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
        
            'field' => strip_tags($this->input->post('field', TRUE)),
        
            'alis' => strip_tags($this->input->post('alis', TRUE)),
        
            'status' => strip_tags($this->input->post('status', TRUE)),

            'apply_formula' => strip_tags($this->input->post('apply_formula', TRUE)),
        
        );
        
        
        $this->db->insert('acc_pay_field_tbl', $data);
    }

     public function saveSingle($d) 
    {
        $data = array(
        
            'field' => strip_tags($d['field']),
        
            'alis' => strip_tags($d['alis']),
        
            'status' => strip_tags($d['status']),

            'apply_formula' => strip_tags($d['apply_formula']),
        
        );
        
        
        $this->db->insert('acc_pay_field_tbl', $data);
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
        
                'field' => strip_tags($this->input->post('field', TRUE)),
        
                'alis' => strip_tags($this->input->post('alis', TRUE)),
        
                'status' => strip_tags($this->input->post('status', TRUE)),

                'apply_formula' => strip_tags($this->input->post('apply_formula', TRUE)),
        
        );
        
        
        $this->db->where('id', $id);
        $this->db->update('acc_pay_field_tbl', $data);
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
        $this->db->delete('acc_pay_field_tbl');
        
    }



    function getAllActive(){
        $a=$this->db->get_where('acc_pay_field_tbl',['status'=>'Y']);
        //echo $this->db->last_query();
        if($a->num_rows > 0 )
            return $a->result();
        return false;
    }

    function getAllForSS(){
        $myquery="select * from acc_pay_field_tbl order by sn";
        $query=$this->db->query($myquery);
        return $query->result();
    }
    
    public function get_all_payable_fields() 
    {
        $q="select apf.field,apf.alis from acc_pay_field_tbl apf where apf.`status`='Y' order by apf.sn";
        $query=$this->db->query($q);
        if ($query->num_rows() > 0) 
        {
            return $query->result_array();
        } 
        else 
        {
            return array();
        }
    }
    
    function submit_edit_payable_order($data){
        foreach ($data as $key => $value) {
            $this->db->set('sn',$value);
            $this->db->where('field',$key);
            $this->db->update('acc_pay_field_tbl');
            //echo $this->db->last_query()."<br>";
        }
        $this->manage_payable_field_order_order();
    }

    function manage_payable_field_order_order(){
        $myquery="select max(sn) as sn from acc_pay_field_tbl";
        $query=$this->db->query($myquery);
        //echo $this->db->last_query();
        $result=$query->result();
        //var_dump($result);die();
        $ar=$result[0];
        //var_dump($ar);die();
        $sn=$ar->sn;
        //echo $sn;die();
        $myquery="select * from acc_pay_field_tbl where sn=0";
        $query=$this->db->query($myquery);
        $result=$query->result();
        //var_dump($result);die();  
        if(count($result)>0){
            foreach ($result as $r) {
                $sn++;
                //echo $sn;
                $this->db->set('sn',$sn);
                $this->db->where('field',$r->field);
                $this->db->update('acc_pay_field_tbl'); 
                //echo $this->db->last_query()."<br>";
            }
        }  
        
        //die();
    }
    function reset_orderNo(){
        $this->db->set('sn',null);
        $this->db->update('acc_pay_field_tbl');
    }
}
