<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Description of salary_edit_model
 * @created on : Saturday, 18-Feb-2017 08:19:58
 * @author Rohit Rana <rohitkkrana@gmail.com>
 * Copyright 2017    
 */
 
 
class salary_edit_model extends CI_Model 
{

    public function __construct() 
    {
        parent::__construct();
    }


    /**
     *  Get All data acc_pay_details_temp
     *
     *  @param limit  : Integer
     *  @param offset : Integer
     *
     *  @return array
     *
     */
    public function get_all($limit, $offset) 
    {

        $result = $this->db->get('acc_pay_details_temp', $limit, $offset);

        if ($result->num_rows() > 0) 
        {
            return $result->result_array();
        } 
        else 
        {
            return array();
        }
    }

     public function get_all_to_show($limit, $offset,$arr) 
    {

        $this->db->where($arr);
        $result = $this->db->get('acc_pay_details', $limit, $offset);
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
     *  Count All acc_pay_details_temp
     *    
     *  @return Integer
     *
     */
    public function count_all()
    {
        $this->db->from('acc_pay_details_temp');
        return $this->db->count_all_results();
    }
 // Added these Models on 27-06-2019   
 public function count_teaching_Emp()
    {
        $this->db->from('acc_pay_details_temp');
        $this->db->where('GROUP','A');
        return $this->db->count_all_results();
    }
    public function count_non_teaching_Emp()
    {
        $this->db->from('acc_pay_details_temp');
        $this->db->where('GROUP !=','A');
        return $this->db->count_all_results();
    }
	// Added upto these Models on 27-06-2019 
    /**
    * Search All acc_pay_details_temp
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
                
        $this->db->like('PMODE', $keyword);  
                
        $this->db->like('HAG', $keyword);  
                
        $this->db->like('DC', $keyword);  
                
        $this->db->like('PFACNO', $keyword);  
                
        $this->db->like('DCPSNO', $keyword);  
                
        $this->db->like('PRAN', $keyword);  
                
        $this->db->like('BLDGNO', $keyword);  
                
        $this->db->like('PP', $keyword);  
                
        $this->db->like('TRTYPE', $keyword);  
                
        $this->db->like('HR', $keyword);  
                
        $this->db->like('CONV', $keyword);  
                
        $this->db->like('WASH', $keyword);  
                
        $this->db->like('NLIC', $keyword);  
                
        $this->db->like('AC', $keyword);  
                
        $this->db->like('NGROSS', $keyword);  
                
        $this->db->like('YGROSS', $keyword);  
                
        $this->db->like('YPROFTAX', $keyword);  
                
        $this->db->like('RATE', $keyword);  
                
        $this->db->like('PAYSCALE', $keyword);  
                
        $this->db->like('LACNO', $keyword);  
                
        $this->db->like('UNIT', $keyword);  
                
        $this->db->like('WF', $keyword);  
                
        $this->db->like('BUS', $keyword);  
                
        $this->db->like('PUJAADV', $keyword);  
                
        $this->db->like('PNRNO', $keyword);  
                
        $this->db->like('MEDDEP', $keyword);  
                
        $this->db->like('GRP', $keyword);  
                
        $this->db->like('G', $keyword);  
                
        $this->db->like('PUJA', $keyword);  
                
        $this->db->like('CANBANK', $keyword);  
        
        $this->db->limit($limit, $offset);
        $result = $this->db->get('acc_pay_details_temp');

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
    * Search All acc_pay_details_temp
    * @param keyword : mixed
    *
    * @return Integer
    *
    */
    public function count_all_search()
    {
        $keyword = $this->session->userdata('keyword');
        $this->db->from('acc_pay_details_temp');        
                
        $this->db->like('PMODE', $keyword);  
                
        $this->db->like('HAG', $keyword);  
                
        $this->db->like('DC', $keyword);  
                
        $this->db->like('PFACNO', $keyword);  
                
        $this->db->like('DCPSNO', $keyword);  
                
        $this->db->like('PRAN', $keyword);  
                
        $this->db->like('BLDGNO', $keyword);  
                
        $this->db->like('PP', $keyword);  
                
        $this->db->like('TRTYPE', $keyword);  
                
        $this->db->like('HR', $keyword);  
                
        $this->db->like('CONV', $keyword);  
                
        $this->db->like('WASH', $keyword);  
                
        $this->db->like('NLIC', $keyword);  
                
        $this->db->like('AC', $keyword);  
                
        $this->db->like('NGROSS', $keyword);  
                
        $this->db->like('YGROSS', $keyword);  
                
        $this->db->like('YPROFTAX', $keyword);  
                
        $this->db->like('RATE', $keyword);  
                
        $this->db->like('PAYSCALE', $keyword);  
                
        $this->db->like('LACNO', $keyword);  
                
        $this->db->like('UNIT', $keyword);  
                
        $this->db->like('WF', $keyword);  
                
        $this->db->like('BUS', $keyword);  
                
        $this->db->like('PUJAADV', $keyword);  
                
        $this->db->like('PNRNO', $keyword);  
                
        $this->db->like('MEDDEP', $keyword);  
                
        $this->db->like('GRP', $keyword);  
                
        $this->db->like('G', $keyword);  
                
        $this->db->like('PUJA', $keyword);  
                
        $this->db->like('CANBANK', $keyword);  
        
        return $this->db->count_all_results();
    }


    /**
    *  Get One acc_pay_details_temp
    *
    *  @param id : Integer
    *
    *  @return array
    *
    */
    public function get_one($id) 
    {
        $this->db->where('id', $id);
        $result = $this->db->get('acc_pay_details_temp');

        if ($result->num_rows() == 1) 
        {
            return $result->row_array();
        } 
        else 
        {
            return $result->num_rows();
        }
    }

     public function get_one_by_empNo($id) 
    {
        $this->db->where('EMPNO', $id);
        $result = $this->db->get('acc_pay_details_temp');

        if ($result->num_rows() == 1) 
        {
            return $result->row_array();
        } 
        else 
        {
            return $result->num_rows();
        }
    }

    
    
    
    /**
    *  Default form data acc_pay_details_temp
    *  @return array
    *
    */
    public function add()
    {
         $this->load->model('finance_account/payble_fields_model','PFM');
         $this->load->model('finance_account/deduction_fields_model','DFM');

           $deduction_fields= $this->DFM->get_all(1000, '');
                    foreach($deduction_fields as $val ){
                            $data[$val['field']] = '';
                    }

                    $payble_fields = $this->PFM->get_all(1000, '');
                    foreach($payble_fields as $val ){
                             $data[$val['field']] = '';
                    }
        $data = array(
            
                'YR' => '',
            
                'MON' => '',
            
                'EMPNO' => '',
            
                'GROUP' => '',  
        );

        return $data;
    }

    
    
    
    
    /**
    *  Save data Post
    *
    *  @return void
    *
    */
    public function save($f='') 
    {
        //echo "reaching";die();
        $this->load->model('finance_account/payble_fields_model','PFM');
         $this->load->model('finance_account/deduction_fields_model','DFM');

           $deduction_fields= $this->DFM->get_all(1000, '');
                    foreach($deduction_fields as $val ){
                            $data[$val['field']] = strip_tags($this->input->post($val['field'], TRUE));
                    }

                    $payble_fields = $this->PFM->get_all(1000, '');
                    foreach($payble_fields as $val ){
                             $data[$val['field']] = strip_tags($this->input->post($val['field'], TRUE));
                    }
            if($f==1){
                    $data['EMPNO'] = $this->input->post('EMPNO');
                    $data['YR'] = $this->input->post('YR');
                    $data['MON'] = $this->input->post('MON');
            }
            $data['GROUP'] = $this->input->post('GROUP');
            //print_r($data); die();
         // If any data such as designation or department is nor added by esst the data can not be added in salary module
            $emp_id=$data['EMPNO'];
$query=$this->db->query("SELECT UPPER (CONCAT_WS(' ', ud.first_name, ud.middle_name, ud.last_name)) as name,ud.dob as dob,ebd.*,UPPER(desi.name) AS desi ,UPPER(dept.name) AS dept FROM user_details ud JOIN emp_basic_details ebd on ud.id=ebd.emp_no
JOIN designations desi on ebd.designation=desi.id JOIN departments dept on ud.dept_id=dept.id
where ud.id='$emp_id'");
foreach($query->result() as $query){
 $data['NAME'] =   $query->name;
$data['DESIG'] =   $query->desi;
$data['DEPT'] =   $query->dept;
$data['DOB'] =   $query->dob;
$data['DOJ'] =   $query->joining_date;
$data['DOR'] =   $query->retirement_date;

}
#print_r($data);exit;

      //  $this->db->insert('acc_pay_details_temp', $data);
	  
	   if($this->db->insert('acc_pay_details_temp', $data)){        
            return true;
        }else{
          return false;
        }
	  
	  
    }
    
    
    function grossPayableAmt($id){
        $sum=0;
        $str="";
        $this->load->model('finance_account/payble_fields_model');
        $fields = $this->payble_fields_model->getAllActive();
        //echo $this->db->last_query();die();
        foreach($fields as $val){
            $f= $val->field;
            $this->db->select($f);
            (array)$d=$this->db->get_where(acc_pay_details_temp,['EMPNO' =>$id])->row(); 
            //echo $this->db->last_query()."<br>"; exit;
           $sum += $d->$f;
           //$str=$str.$d->$f.",";
        }
        //echo "<br>".$str;
        //die();
        return $sum;
    }


     function grossDeductionAmt($id){
        $sum=0;
        $this->load->model('finance_account/deduction_fields_model');
        $fields = $this->deduction_fields_model->getAllActive();
        foreach($fields as $val){
            $f= $val->field;
            $this->db->select($f);
            (array)$d=$this->db->get_where(acc_pay_details_temp,['EMPNO' =>$id])->row(); 
          
           $sum += $d->$f;
        }
        return $sum;
    }
        // condition type true mean equal to / false mean not equal to//
    function singleFieldSum($field,$group='',$bool=true){
        $con='';
        if($group !='' && $bool){
                $con = "where a.GROUP='".$group."'";
        }else if($group !='' && !$bool){
                $con = "where a.GROUP<>'".$group."'";
        }
        $sum=$this->db->query("select sum(a.$field) as $field from acc_pay_details_temp a $con");
        
        return $sum->row()->$field;
    }

       // starting to variable date range condition type true mean equal to / false mean not equal to//
    function declardSingleFieldSum($from, $to, $field,$group='',$bool=true){
            $frm = explode('/', $from);
            $t = explode('/',$to);
        $con=' where (a.MON between ? and ? ) and (a.YR between ? and ? )';
        if($group !='' && $bool){
                $con .= " and a.GROUP='".$group."'";
        }else if($group !='' && !$bool){
                $con .= " and a.GROUP<>'".$group."'";
        }
        $sum=$this->db->query("select sum(a.$field) as $field from acc_pay_details a $con",[$frm[0],$t[0],substr($frm[1], 2),substr($t[1], 2)]);
        if($sum)
            return $sum->row()->$field;
        return (int)0;
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
        //echo"Reaching";die();
         $this->load->model('finance_account/payble_fields_model','PFM');
         $this->load->model('finance_account/deduction_fields_model','DFM');

           $deduction_fields= $this->DFM->get_all(1000, '');
                    foreach($deduction_fields as $val ){
                            $data[$val['field']] = strip_tags($this->input->post($val['field'], TRUE));
                    }

                    $payble_fields = $this->PFM->get_all(1000, '');
                    foreach($payble_fields as $val ){
                             $data[$val['field']] = strip_tags($this->input->post($val['field'], TRUE));
                    }
        
                $data['GROUP'] = $this->input->post('GROUP');
				$data['BACNO'] = $this->input->post('BACNO');
				$data['DEPT'] = strtoupper($this->input->post('DEPT'));
				$data['DESIG'] = strtoupper($this->input->post('DESIG')); // Added this line to allow edit of Designation
				$data['PNRNO'] = $this->input->post('PANNO');
				//added to update GROSS,DEDUCTION,NETPAY ON 06-08-19
				$data['GROSS'] = $this->input->post('GROSS');
				$data['TOTD'] = $this->input->post('TOTD');
				$data['NETPAY'] = $this->input->post('NETPAY');
				if(strcmp($this->input->post('CGPRAN'),"")==0){
                    $data['PRAN']=0;
                }
                else{
                    if(is_numeric($this->input->post('CGPRAN'))){
                        $data['PRAN']=$this->input->post('CGPRAN');
                        $data['PFACNO']="";
                    }
                    else{
                        $data['PFACNO']=$this->input->post('CGPRAN');
                        $data['PRAN']=0;
                    }
                }
        echo"<pre>";
        //var_dump($data);die();
        echo"</pre>";
        $this->db->where('id', $id);
        $this->db->update('acc_pay_details_temp', $data);
    }

    public function updateCon($data,$con){
            if($this->db->update('acc_pay_details_temp',$data,$con))
                return true;
            return false;
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
        $this->db->delete('acc_pay_details_temp');
        
    }


    function getEmpNum(){
        $q=$this->db->query("select a.EMPNO,b.mobile_no,a.MON from acc_pay_details_temp a left join user_other_details b on CAST(a.EMPNO  as char character set utf8)=b.id");
        //echo $this->db->last_query();die();
        if($q->num_rows() > 0){
           return $q->result();
        }
        return false;
    }
    // get emp email for send salary slip from emaildata_emp 13.05.2021
    function getEmpNumEmail(){
        $q=$this->db->query("SELECT a.EMPNO,b.mobile_no,a.MON,a.YR,c.domain_name from acc_pay_details_temp a 
        left join (user_other_details b , emaildata_emp c) on CAST(a.EMPNO  as char character set utf8)=b.id and (a.EMPNO = c.emp_id)");
        // where a.EMPNO in('769','806','768')
       // echo $this->db->last_query();die();
        if($q->num_rows() > 0){
           return $q->result();
        }
        return false;
    }
    // end get emp email for send salary slip from emaildata_emp 13.05.2021

	function getContact($user_id){
		$q=$this->db->query("select b.mobile_no from  user_other_details b where b.id=?",$user_id);
        //echo $this->db->last_query();die();
        if($q->num_rows() > 0){
           return $q->result();
        }
        return false;
	}

}
