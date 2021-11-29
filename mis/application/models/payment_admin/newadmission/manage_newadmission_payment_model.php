<?php

class Manage_newadmission_payment_model extends CI_Model{

    private $tabnewadmission='newadmission';
    private $tabpaylive='paylive';
    function __construct()
    {
        // Calling the Model parent constructor
        parent::__construct();
        $CI =& get_instance();
        $this->db2 = $CI->load->database($this->tabnewadmission, TRUE);
        $this->db3 = $CI->load->database($this->tabpaylive, TRUE);
    }

    // function test_m()
    // {
    //     $query = $this->db2->get_where('online_payment_stud_final_fee',['amount' => '1.00']);

    //     if($query->num_rows() > 0)
    //     {
    //         echo "<pre>";
    //         print_r($query->result_array());
    //         exit;
    //     }
    // }

    function insert_online_payment_newadmission_full_transaction($data)
   {
    $sql = "select * from online_payment_newadmission_full_transaction where merchant_order_number = ?";
    $query = $this->db->query($sql,array($data['merchant_order_number'])); // mis db

    // echo $this->db->last_query(); exit;
    if($query->num_rows() > 0)
    {
      $this->db->where('merchant_order_number', $data['merchant_order_number']); // mis db
      $this->db->update('online_payment_newadmission_full_transaction', $data); // mis db

      $this->db2->where('merchant_order_number', $data['merchant_order_number']); // newadmission db
      $this->db2->update('online_payment_newadmission_full_transaction', $data); // newadmission db

      return 'update_success';
    }
    else
    {

    $this->db->insert('online_payment_newadmission_full_transaction', $data); // mis db

    $this->db2->insert('online_payment_newadmission_full_transaction', $data); // newadmission db
    //echo $this->db->last_query(); exit;
    return 'insert_success';
    }
  }

  public function insert_online_payment_newadmission_settled_transaction($data)
  {

    $sql = "select * from online_payment_newadmission_settled_transaction where merchant_order_number = ?";
    $query = $this->db->query($sql,array($data['merchant_order_number']));

     if($query->num_rows() > 0) 
     {
    //   $this->db->where('merchant_order_number', $data['merchant_order_number']);
    //   $this->db->update('sbi_final_settlement_data', $data);
      return 'update_success';
     }
     else
     {

     $this->db->insert('online_payment_newadmission_settled_transaction',$data); // mistest db

     $this->db2->insert('online_payment_newadmission_settled_transaction',$data); // newadmission db
    // echo $this->db->last_query(); exit;
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

  public function insert_online_payment_newadmission_check_settlement_data_merchant($datanew)
  {
    $sql = "select * from online_payment_newadmission_check_settlement_data_merchant where order_no = ?";
    $query = $this->db->query($sql,array($datanew['order_no']));
    if($query->num_rows() > 0) 
    {
    //   $this->db->where('order_no', $datanew['order_no']);
    //   $this->db->update('online_payment_newadmission_check_settlement_data_merchant', $datanew);
      return 'update_success';
    }
    else
    {

    $this->db->insert('online_payment_newadmission_check_settlement_data_merchant',$datanew); // mistest db

     $this->db2->insert('online_payment_newadmission_check_settlement_data_merchant',$datanew); // newadmission db
    // echo $this->db->last_query(); exit;

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

    // start payment admin data
    public function check_order_no_mis_success($order_number)
    {
        $sql = "select * from online_payment_newadmission_sbi_success_details where order_id = ?";
        $query = $this->db->query($sql, array($order_number));
        return $query->num_rows();
    }
    public function check_order_no_sbi_settlement($order_number)
    {
        $sql = "select * from online_payment_newadmission_settled_transaction where merchant_order_number = ?";
        $query = $this->db->query($sql, array($order_number));
        return $query->num_rows();
    }
    public function store_eonline_payment_newadmission_receipt_status($data_enquiry)
    {
        $this->db->insert('online_payment_newadmission_receipt_status',$data_enquiry);
        return TRUE;
    }
    public function check_order_no_mis_failure($order_number)
    {
        $sql = "select * from online_payment_newadmission_sbi_failure_details where order_id = ?";
        $query = $this->db->query($sql, array($order_number));
        return $query->num_rows();
    }
    public function check_order_no_full_transaction($order_number)
    {
        $sql = "select * from online_payment_newadmission_full_transaction where merchant_order_number = ?";
        $query = $this->db->query($sql, array($order_number));
        return $query->num_rows();
    }
    public function get_order_no_full_transaction_details($order_number)
    {
            $sql = "select * from online_payment_newadmission_full_transaction where merchant_order_number = ?";
            $query = $this->db->query($sql, array($order_number));
           // echo $this->db->last_query(); exit;
            if ($query->num_rows() > 0) 
            {
                return $query->row_array();
            }
            else 
            {
                return false;
            }
    }
    public function get_order_no_settled_sbi_details($order_number)
    {
            $sql = "select * from online_payment_newadmission_settled_transaction where merchant_order_number = ?";
            $query = $this->db->query($sql, array($order_number));
            if ($query->num_rows() > 0) 
            {
                return $query->row_array();
            }
            else 
            {
                return false;
            }
    }
    public function get_online_payment_stud_final_fee($reg_id,$email,$contact_no)
    {
            $sql = "SELECT * from online_payment_stud_final_fee where reg_id = ? and email = ? and contact_no = ?";
            $query = $this->db->query($sql, array($reg_id , $email , $contact_no));
            if ($query->num_rows() > 0) 
            {
                return $query->row_array();
            }
            else 
            {
                return false;
            }
    }
    function update_online_payment_stud_final_fee($where_data,$update_data)
    {
        //$this->db2->select('sl_no,admn_no,reg_id,contact_no,email,admn_type,fee_status,fee_status_msg,fee_order_no');
        $this->db->where($where_data); // mistest db
        $query = $this->db->update('online_payment_stud_final_fee',$update_data); // mis db online_payment_stud_final_fee

        $this->db2->where($where_data); // newadmission db
        $query2 = $this->db2->update('online_payment_stud_final_fee',$update_data); // newadmission db online_payment_stud_final_fee

        $this->db3->where($where_data); // pay db
        $query3 = $this->db3->update('online_payment_stud_final_fee',$update_data); // pay db online_payment_stud_final_fee
        //echo $this->db->last_query(); exit;
        if($this->db->affected_rows() > 0)
        {
          return TRUE;
        }
        else
        {
          return FALSE;
        }
    }
    public function get_branch_name($branch_id)
    {
            $sql = "SELECT name from cbcs_branches where id = ?";
            $query = $this->db->query($sql, array($branch_id));
            if ($query->num_rows() > 0) 
            {
                return $query->row_array();
            }
            else 
            {
                return false;
            }
    }
    public function get_course_name($course_id)
    {
            $sql = "SELECT name from cbcs_courses where id = ?";
            $query = $this->db->query($sql, array($course_id));
            if ($query->num_rows() > 0) 
            {
                return $query->row_array();
            }
            else 
            {
                return false;
            }
    }
  public function get_last_updated_settlement_date()
  {
    $sql = "SELECT * FROM online_payment_newadmission_settled_transaction ORDER BY id DESC LIMIT 1";
    $query = $this->db->query($sql);
    //echo $this->db->last_query(); die();
    $settle_date = $query->row_array();
    return $settle_date['settlement_date'];
  }
  public function get_last_updated_order_date()
  {
    $sql = "SELECT * FROM online_payment_newadmission_full_transaction ORDER BY id DESC LIMIT 1";
    $query = $this->db->query($sql);
    $settle_date = $query->row_array();
    return $settle_date['order_booking_date_time'];
  }


  // check double record from newadmission
  function check_mba_mbaba_branch($reg_id,$admn_type)
    {
        // $query = $this->db->query("SELECT COUNT(*) AS total_record FROM online_payment_stud_final_fee WHERE reg_id = '$reg_id' AND admn_type = '$admn_type' ");
        $query = $this->db2->query("SELECT reg_id, COUNT(reg_id) AS total_record FROM online_payment_stud_final_fee GROUP BY reg_id
    HAVING COUNT(reg_id) > 1 AND reg_id = '$reg_id'"); // newadmission db

        if($query->num_rows() > 0)
        {
            $total_record = $query->row_array();
            return $total_record['total_record'];
        }
        else
        {
            return false;
        }
    }
    function get_mba_select_branch_by_stu($where_data)
    {
        $query = $this->db2->get_where("mba_select_branch_by_stu",$where_data); // newadmission db
        // echo $this->db->last_query(); exit;
        
        if($query->num_rows() > 0)
        {
            return $query->row_array();
        }
        else
        {
            return false;
        }
    }
    function get_final_data_online_payment_stud_final_fee($where_data)
    {
        $query = $this->db2->get_where("online_payment_stud_final_fee",$where_data); // newadmission db
        // echo $this->db->last_query(); exit;
        
        if($query->num_rows() > 0)
        {
            return $query->row_array();
        }
        else
        {
            return false;
        }
    }




}