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
    protected $allow_str = ['1'=>'round','2'=>'ceil','3'=>'floor'];

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
        //echo $this->db->last_query();die();
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
		$result = $this->db->count_all_results();
	//	echo $this->db->last_query();die();
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
        $this->db->where('id', $id);
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

                'valid_from' => '',
            
                'valid_to' => '',
            
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
        
           // 'id' => strip_tags($this->input->post('id', TRUE)),
        
            'field' => strip_tags($this->input->post('field', TRUE)),
        
            'alise' => strip_tags($this->input->post('alise', TRUE)),
        
            'formula' => $this->input->post('formula'),
        
            'generate_by' => $this->session->userdata('id'),

            'valid_from' =>  $this->session->userdata('valid_from'),
            
            'valid_to' =>  $this->session->userdata('valid_to'),
        
            'datetime' => date('Y-m-d H:i:s')
        
           // 'status' => strip_tags($this->input->post('status', TRUE))
        
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
                            $this->db->last_query();
                            if($res->num_rows() > 0 )
                                $this->formula = str_replace(" ","",'('.$res->row()->formula.')');
                            else
                                $this->formula = false;

                       }

    function getformulaResult($id,$field){

              $this->getformulaByField($field);
                //echo $this->formula;die();
              $d= $this->getStringArray();
              
               
               foreach($d as $key =>$val){
                   // echo $val."<br>";
                    $d[$key] = $this->GetFieldValueById($id,$val);
                    //echo $this->db->last_query()."<br>";
                    if($d[$key] == 'error'){
                        $flag =1;  
                        $ef = $val;
                        break;
                    }
               }
                if($flag ==1){
                   //     echo $this->formula=implode($d);die();
                        return "Formula having mistake, Please check your Formula And try Again, Please check the field <b>".strtoupper($ef)."</b>";
                }else{

                $this->formula=implode($d);
                return $this->calc_string($this->formula);
            }
        
    }


  private  function calc_string( $mathString ){
    //echo $mathString;die();
        
        //echo $mathString."<br>";
        //$cf_DoCalc = create_function("", "return (" . $mathString . ");" );
        //echo 0 +$cf_DoCalc;die();
        $q="select ".$mathString." as value";
        if($query=$this->db->query($q)){
            if($query->num_rows()>0){
                $result=$query->result_array();
                //echo $result[0]['value']."<br>";
                return $result[0]['value'];
            }
            else{
                return 0;
            }
        }
        else{
           //echo $this->db->last_query();die();
            return 0;
        }
        //return $cf_DoCalc();
    
        
    }
            

    protected function GetFieldValueById($id,$field) {
               
                 if(preg_match("/^[A-z]+$/", $field)){  

                   if(array_search($field, $this->allow_str)){  return $field;    }
                        
                    $res=$this->db->get_where('acc_pay_details_temp',['EMPNO'=>$id] ) ;
                           if($res->num_rows() > 0){
                                $r=$res->row();                   
                                    return ($r->$field == '')?0:$r->$field;
                           }

                 }
                 else{
                        return $field;
                 } 
    }    

    protected function array_find($needle, array $haystack){

            foreach ($haystack as $key => $value) {
                if (false !== stripos($value, $needle)) {
                    return $key;
                }
            }
            return false;
    }


    protected function getStringArray(){
          
            $r = str_split($this->formula);
         
            $i=0 ; $e=false;
            foreach ($r as  $value) {
              
               if(preg_match("/^[A-z]+$/", $value)){   
                    if($e==true){
                            $i++; $e=false;
                    }    
                        $arr[$i] .=$value;
                    
                }else{
                    $e=true;
                  $i++; $arr[$i] = $value;  
                }
                
            }
        
            return $arr;
    }

}


