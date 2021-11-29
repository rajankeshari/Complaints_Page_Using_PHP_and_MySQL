<?php

class Payment_admin_query_model extends CI_Model{

        private $tabulation='parentlive';
        //private $misdev = 'misdev';

	function __construct(){
		parent::__construct();
    }


    public function get_no_dues_status($auth){
		$ts=$this->ndqm->curr_time_stamp();
		$session_year_curr=$this->ndqm->get_session($ts);
		//echo $sql = "SELECT * FROM no_dues_start where session_year='$session_year_curr' and access_to='$auth' and status!=2"; exit;
        $res=$this->db->query("SELECT * FROM payment_manage_portal where session_year='$session_year_curr' and access_to='$auth' and status!=2")->result_array();
        if(!$res) return 10;
        else return $res;
    }

    public function curr_time_stamp(){
		$curr_time = date_create();
		$ts = date_format($curr_time, 'Y-m-d H:i:s');
		return $ts;
	}


	public function get_session($ts){
		 $year = explode('-', $ts)[0];
        $month=explode('-',$ts)[1];
        $m=strval((int)$month);
        if($m>=7&&$m<=12){  
            $p_year = strval((int)$year);  // 2019
            $year = $p_year +1;  // if month is greater than 6 then year will be present year + 1 

        }
        else
        $p_year = strval((int)$year - 1);  // if month is less than 6 then year will be present year will be  year - 1
        return $p_year.'-'.$year;
  }
  
  public function insert_into_sbi_merchant_panel_details($data)
  {

    $this->db->insert('sbi_merchant_panel_details', $data);

  }

  public function getMerchantDetails()
  {
    $sql = "select * from sbi_merchant_panel_details";
    $query = $this->db->query($sql);
    return $query->result_array();

  }

  public function get_last_updated_settlement_date()
  {
    $sql = "SELECT settlement_date
    FROM  sbi_final_settlement_data
    group BY settlement_date ORDER BY STR_TO_DATE(settlement_date,'%d-%m-%y') DESC LIMIT 1";

    $query = $this->db->query($sql);
    $settle_date = $query->result_array();
    return $settle_date['0']['settlement_date'];
  }


  public function get_last_updated_refund_date()
  {
    $sql = "SELECT refund_booking_date_time
    FROM  sbi_final_refund_data
    group BY refund_booking_date_time ORDER BY refund_booking_date_time DESC LIMIT 1";

    $query = $this->db->query($sql);
    $settle_date = $query->result_array();
    return $settle_date['0']['refund_booking_date_time'];
  }


  public function get_last_updated_order_date()
  {
    $sql = "SELECT order_booking_date_time
    FROM  sbi_full_transaction
    group BY order_booking_date_time ORDER BY STR_TO_DATE(order_booking_date_time,'%d-%m-%y') DESC LIMIT 1";

    $query = $this->db->query($sql);
    $settle_date = $query->result_array();
    return $settle_date['0']['order_booking_date_time'];
  }


  public function search_duplicate_entry_full_trans($order_number)
  {

    $sql = "select * from sbi_full_transaction where merchant_order_number = ?";
    $query = $this->db->query($sql,array($order_number));
    if($query != false)
    {
      return $query->num_rows();
    }
    else
    {
    return false;
    }

  }

  public function getMerchant_settlement_Details()
  {

    
    $sql = "select * from sbi_final_settlement_data";
    $query = $this->db->query($sql);
    if($query != false)
    {

      return $query->result_array();
    }
    else
   {

      return false;
   }

  }

  public function getMerchant_full_trans_Details()
  {

    $sql = "select * from sbi_full_transaction";
    $query = $this->db->query($sql);
    return $query->result_array();

      
  }

  public function insert_merchant_full_transactions($data)
  {

    $sql = "select * from sbi_full_transaction where merchant_order_number = ?";
    $query = $this->db->query($sql,array($data['merchant_order_number']));
    //echo $this->db->last_query();
    if($query->num_rows() > 0)
    {
      $this->db->where('merchant_order_number', $data['merchant_order_number']);
      $this->db->update('sbi_full_transaction', $data);
      return 'update_success';
    }
    else
    {
    $this->db->insert('sbi_full_transaction', $data);
    return 'insert_success';
    }
  }

  public function get_payment_status()
  {

     $sql = "select order_status from sbi_full_transaction";
     $query = $this->db->query($sql);
     return $query->result_array();

  }

  public function get_bank_fee_details()
  {

      //$CI = &get_instance();
      //$this->db2 = $CI->load->database($this->tabulation, TRUE);
      $sql = "select * from bank_fee_details";
      $query = $this->db->query($sql);
      return $query->result_array();
  }

  public function get_filter_pay_details($to_date,$from_date,$pay_status)
  {

    //$to_date;
    //$from_date;
    //echo $pay_status;

    $sql = "select * from sbi_full_transaction where order_status = '".$pay_status."' and STR_TO_DATE(order_booking_date_time,'%d-%m-%Y') BETWEEN '".$to_date."' AND '".$from_date."' GROUP BY order_booking_date_time order by STR_TO_DATE(order_booking_date_time,'%d-%m-%Y') asc";
    $query = $this->db->query($sql);
    //echo $this->db->last_query(); die();

    return $query->result_array();

  }

  public function search_duplicate_entry($atrn)
  {

      $sql = "select * from sbi_final_settlement_data where sbi_reference_no_atrn = ?";
      $query = $this->db->query($sql,array($atrn));
      return $query->num_rows();

  }
  

  public function search_duplicate_entry_refund($atrn) 
  
  {

      $sql = "select * from sbi_final_refund_data where atrn_reference_number = ?";
      $query = $this->db->query($sql,array($atrn));
      return $query->num_rows();


  }

  public function insert_into_sbi_settlement_data($data)
  {

    $sql = "select * from sbi_final_settlement_data where merchant_order_number = ?";
    $query = $this->db->query($sql,array($data['merchant_order_number']));

    //echo "hii".$query->num_rows();

     if($query->num_rows() > 0) {
       
      $this->db->where('merchant_order_number', $data['merchant_order_number']);
      $this->db->update('sbi_final_settlement_data', $data);
      return 'update_success';
      
     }

     else

     {

     $this->db->insert('sbi_final_settlement_data',$data);
     $insert_id = $this->db->insert_id();
     if($insert_id != '')
     {
        return $insert_id;
     }

     else
     {
       return false;
     }

    }

  }

  public function insert_into_sbi_refund_data($data)
  {

    $sql = "select * from sbi_final_refund_data where merchant_order_number = ?"; 
    $query = $this->db->query($sql,array($data['merchant_order_number']));

    if($query->num_rows() > 0)
    {

      $this->db->where('merchant_order_number', $data['merchant_order_number']);
      $this->db->update('sbi_final_refund_data', $data);
      return 'update_success';
    }

    else
    {
     $this->db->insert('sbi_final_refund_data',$data);
     $insert_id = $this->db->insert_id();
     if($insert_id != '')
     {
        return $insert_id;
     }

     else
     {
       return false;
     }

    }

  }


  public function insert_into_sbi_refund_data_final($datanew)
  {

    $sql = "select * from sbi_final_check_refund_data where order_number = ?";
    $query = $this->db->query($sql,array($datanew['order_number']));

    if($query->num_rows() > 0)
    {
      $this->db->where('order_number', $datanew['order_number']);
      $this->db->update('sbi_final_check_refund_data', $datanew);
      return 'update_success';
    }

    else
    {
     $this->db->insert('sbi_final_check_refund_data',$datanew);
     $insert_id = $this->db->insert_id();
     if($insert_id != '')
     {
        return $insert_id;
     }

     else
     {
       return false;
     }
    }

  }

  public function insert_into_sbi_refund_settlement_data($datanew)
  {

    $sql = "select * from sbi_final_check_refund_settlement_data where order_number = ?";
    $query = $this->db->query($sql,array($datanew['order_number']));

    if($query->num_rows() > 0)
    {

      $this->db->where('order_number', $datanew['order_number']);
      $this->db->update('sbi_final_check_refund_settlement_data', $datanew);
      return 'update_success';

    }

    else
    {
     $this->db->insert('sbi_final_check_refund_settlement_data',$datanew);
     $insert_id = $this->db->insert_id();
     if($insert_id != '')
     {
        return $insert_id;
     }

     else
     {
       return false;
     }
    }

  }

  public function getMerchant_refund_Details()
  {

      $sql = "select * from sbi_final_refund_data";
      $query = $this->db->query($sql);
      return $query->result_array();

  }

  public function get_order_number($settlement_id)
  {

    $sql = "select `merchant_order_number` from sbi_final_settlement_data where id = ?";
    $query = $this->db->query($sql,array($settlement_id));
    $id_array = $query->result_array();
    return $id_array['0']['merchant_order_number'];
  }

  public function get_order_number_refund($refund_table_id)
  {

    $sql = "select `merchant_order_number` from sbi_final_refund_data where id = ?";
    $query = $this->db->query($sql,array($refund_table_id));
    $id_array = $query->result_array();
    return $id_array['0']['merchant_order_number'];
  }

  public function get_order_number_success($success_table_id)
  {

    //$CI = &get_instance();
    //$this->db2 = $CI->load->database($this->tabulation, TRUE);
    $sql = "select `txnid` from sbi_success_details_semester_fees where id = ?";
    $query = $this->db->query($sql,array($success_table_id));
    $id_array = $query->result_array();
    return $id_array['0']['txnid'];
  }

  public function insert_into_sbi_final_settlement_data($datanew)
  {

    if ($datanew['sbi_reference_no'] == '' || $datanew['order_no'] == '') {
      
      return false;
    }

    else {
     

    $this->db->insert('sbi_final_check_settlement_data',$datanew);
     $insert_id = $this->db->insert_id();
     if($insert_id != '')
     {
        return $insert_id;
     }

     else
     {
       return false;
     }

    }

  }


  public function check_sbi_success_details_parent($order_number)
  {

    //$CI = &get_instance();
    //$this->db2 = $CI->load->database($this->tabulation, TRUE);
    $sql = "select * from sbi_success_details_semester_fees where txnid = ?";
    $query = $this->db->query($sql,array($order_number));
    return $query->num_rows();

  }


  public function check_sbi_failure_details_parent($order_number)
  {

    //$CI = &get_instance();
    //$this->db2 = $CI->load->database($this->tabulation, TRUE);
    $sql = "select * from sbi_failure_details_semester_fees where txnid = ?";
    $query = $this->db->query($sql,array($order_number));
    return $query->num_rows();

  }



  public function insert_into_sbi_final_settlement_data_merchant($datanew)
  {

    $sql = "select * from sbi_final_check_settlement_data_merchant where order_no = ?";
    $query = $this->db->query($sql,array($datanew['order_no']));
    if($query->num_rows() > 0) {
     
      $this->db->where('order_no', $datanew['order_no']);
      $this->db->update('sbi_final_check_settlement_data_merchant', $datanew);
      return 'update_success';

    }

    else
    {
    $this->db->insert('sbi_final_check_settlement_data_merchant',$datanew);
    $insert_id = $this->db->insert_id();
    if($insert_id != '')
    {
       return $insert_id;
    }

    else
    {
      return false;
    }
  }

  }

  public function check_sbi_settlement($order_number)
  {

    // $CI = &get_instance();
    // $this->db2 = $CI->load->database($this->tabulation, TRUE);
    $sql = "select * from sbi_final_settlement_data where merchant_order_number = ?";
    $query = $this->db->query($sql,array($order_number));
    return $query->num_rows();

  }

  public function get_settlement_amount($order_number)
  {

     $sql = "select `payoutamount` from sbi_final_settlement_data where merchant_order_number = ?";
     $query = $this->db->query($sql,array($order_number));
     $pay_array = $query->result_array();
     return $pay_array['0']['payoutamount'];


  }

  public function get_settlement_gst($order_number)
  {

     $sql = "select `gst` from sbi_final_settlement_data where merchant_order_number = ?";
     $query = $this->db->query($sql,array($order_number));
     $pay_array = $query->result_array();
     return $pay_array['0']['gst'];


  }

  public function get_settlement_commission($order_number)
  {

     $sql = "select `commission_payable` from sbi_final_settlement_data where merchant_order_number = ?";
     $query = $this->db->query($sql,array($order_number));
     $pay_array = $query->result_array();
     return $pay_array['0']['commission_payable'];


  }

  public function check_mis_final_settlement_data($order_number)
  {
    $sql = "select * from sbi_final_check_settlement_data where order_no = ?";
    $query = $this->db->query($sql,array($order_number));
    //echo $this->db->last_query(); die();
    return $query->num_rows();
      
  }

 

  public function get_sbi_success_data()
  {

    //$CI = &get_instance();
    //$this->db2 = $CI->load->database($this->tabulation, TRUE);
    $sql = "select * from sbi_success_details_semester_fees";
    $query = $this->db->query($sql);

    return $query->result_array();

  }

  public function update_final_settlement_data($order_number)
  {

    $data = array(

        'status' => 'success',
    );

    $this->db->where('order_no', $order_number);
    $this->db->update('sbi_final_check_settlement_data', $data);
      
      
  }

  public function update_final_settlement_data_merchant($order_number)
  {

    $data = array(

        'status' => 'success',
    );

    $this->db->where('order_no', $order_number);
    $this->db->update('sbi_final_check_settlement_data_merchant', $data);
      
      
  }

  public function update_final_settlement_data_merchant_failure($order_number)
  {

    $data = array(

        'status' => 'failure',
    );

    $this->db->where('order_no', $order_number);
    $this->db->update('sbi_final_check_settlement_data_merchant', $data);
      
      
  }

  public function update_final_refund_data_merchant($order_number)
  {

    $data = array(

        'status' => 'success',
    );

    $this->db->where('order_no', $order_number);
    $this->db->update('sbi_final_check_refund_data', $data);
      
      
  }

  public function update_final_refund_data_settlement_merchant($order_number)
  {

      $data = array(

        'status' => 'success',
    );

    $this->db->where('order_no', $order_number);
    $this->db->update('sbi_final_check_refund_settlement_data', $data);

  }

  public function get_date_of_settlement($order_number)
  {

     $sql = "select `settlement_date` from sbi_final_settlement_data where merchant_order_number = ?";
     $query = $this->db->query($sql,array($order_number));
     $settledatearray = $query->result_array();
     return $settledatearray['0']['settlement_date'];

  }

  public function get_final_settlement_success_details()
  {
      
      $sql = "select * from sbi_final_check_settlement_data where status = 'success'";
      $query = $this->db->query($sql);
      return $query->result_array();
  }

  public function get_final_settlement_pending_details()
  {
      
      $sql = "select * from sbi_final_check_settlement_data where status = 'pending'";
      $query = $this->db->query($sql);
      return $query->result_array();
  }

  public function get_final_settlement_success_details_merchant()
  {
      
      $sql = "select * from sbi_final_check_settlement_data_merchant where status = 'success'";
      $query = $this->db->query($sql);
      return $query->result_array();
  }

  public function get_final_refund_details_merchant()
  {

    $sql = "select * from sbi_final_check_refund_data where status = 'not_any'";
    $query = $this->db->query($sql);
    //echo $this->db->last_query(); die();
    return $query->result_array();
      
  }

  public function get_final_success_refund_details_merchant()
  {

    $sql = "select * from sbi_final_check_refund_data where status = 'success'";
    $query = $this->db->query($sql);
    //echo $this->db->last_query(); die();
    return $query->result_array();

  }

  public function get_final_settlement_refund_details_merchant()
  {

    $sql = "select * from sbi_final_check_refund_settlement_data where status = 'success'";
    $query = $this->db->query($sql);
    //echo $this->db->last_query(); die();
    return $query->result_array();

  }


  public function get_final_settlement_pending_details_merchant()
  {
      
      $sql = "select * from sbi_final_check_settlement_data_merchant where status = 'pending'";
      $query = $this->db->query($sql);
      return $query->result_array();
  }

  public function get_final_settlement_failure_details_merchant()
  {
      
      $sql = "select * from sbi_final_check_settlement_data_merchant where status = 'failure'";
      $query = $this->db->query($sql);
      return $query->result_array();
  }

  public function get_success_details_mis($order_number){

    //$CI = &get_instance();
    //$this->db2 = $CI->load->database($this->tabulation, TRUE);
    $sql = "select * from sbi_success_details_semester_fees where txnid = ?";
    $query = $this->db->query($sql,array($order_number));
    return $query->result_array();

  }

  public function get_settlement_details_success($order_number){

    // $CI = &get_instance();
    // $this->db2 = $CI->load->database($this->tabulation, TRUE);
    $sql = "select * from sbi_final_settlement_data where merchant_order_number = ?";
    $query = $this->db->query($sql,array($order_number));
    return $query->result_array();

  }

  public function get_settlement_details_pending($order_number){

    // $CI = &get_instance();
    // $this->db2 = $CI->load->database($this->tabulation, TRUE);
    $sql = "select * from sbi_final_settlement_data where merchant_order_number = ?";
    $query = $this->db->query($sql,array($order_number));
    return $query->result_array();

  }

  public function get_refund_details($order_number)
  {

    $sql = "select * from sbi_final_refund_data where merchant_order_number = ?";
    $query = $this->db->query($sql,array($order_number));
    return $query->result_array();
     
  }

  public function get_refund_success_details($order_number)
  {

    $sql = "select * from sbi_final_refund_data where merchant_order_number = ?";
    $query = $this->db->query($sql,array($order_number));
    return $query->result_array();
     
  }

  public function get_refund_settlement_details($order_number)
  {

    $sql = "select * from sbi_final_refund_data where merchant_order_number = ?";
    $query = $this->db->query($sql,array($order_number));
    return $query->result_array();
     
  }

}
    

    ?>