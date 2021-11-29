<?php


class Student_model extends CI_Model
{

    function __construct() {
        parent::__construct();
    }


    public function get_student_no_dues($user_id,$payment_status,$status,$status_new) {

       //echo $user_id;
       //echo $payment_status; 
        $sql = "select * from `no_dues_hs_payment` a inner join `no_dues_hs_details` b on b.id = a.hs_no_dues_id inner join `no_dues_hs_individual` c on c.id = b.assign_hs_no_dues_id where c.admn_no='".$user_id."' and a.payment_status='".$payment_status."' and a.status='".$status."' or a.status = '".$status_new."' and b.`is_deleted` = 0";//exit;
        $query = $this->db->query($sql);
       //echo $this->db->last_query(); die();
        return $query->result_array();



    }


    public function get_rejected_list_details($id)
    {

        $sql = "select * from `no_dues_hs_payment` a inner join `no_dues_hs_details` b on b.id = a.hs_no_dues_id inner join `no_dues_hs_individual` c on c.id = b.assign_hs_no_dues_id where a.hs_no_dues_id='".$id."' and b.`is_deleted` = 0";//exit;
        $query = $this->db->query($sql);
       //echo $this->db->last_query(); die();
        return $query->result_array();
    }

    public function get_student_no_dues_pending($user_id,$payment_status,$status)
    {

        $sql = "select * from `no_dues_hs_payment` a inner join `no_dues_hs_details` b on b.id = a.hs_no_dues_id inner join `no_dues_hs_individual` c on c.id = b.assign_hs_no_dues_id where c.admn_no='".$user_id."' and a.payment_status='".$payment_status."' and a.status='".$status."' and b.`is_deleted` = 0";//exit;
        $query = $this->db->query($sql);
       //echo $this->db->last_query(); die();
        return $query->result_array();

    }

    public function get_student_no_dues_rejected($user_id,$payment_status,$status)
    {

        $sql = "select * from `no_dues_hs_payment` a inner join `no_dues_hs_details` b on b.id = a.hs_no_dues_id inner join `no_dues_hs_individual` c on c.id = b.assign_hs_no_dues_id where c.admn_no='".$user_id."' and a.payment_status='".$payment_status."' and a.status='".$status."' and b.`is_deleted` = 0";//exit;
        $query = $this->db->query($sql);
       //echo $this->db->last_query(); die();
        return $query->result_array();

    }

    public function getAdmissionNumber($hs_details_id)
    {

        $sql = "select b.admn_no from `no_dues_hs_details` a inner join `no_dues_hs_individual` b on a.id = b.hostel_no_dues_id where b.hostel_no_dues_id = ?";
        $query = $this->db->query($sql,array($hs_details_id));
        echo $this->db->last_query($query);
        $array_admn = $query->result_array();
        return $array_admn[0]['admn_no'];


    }

    public function get_student_no_dues_approved($user_id,$payment_status,$status)
    {

        $sql = "select * from `no_dues_hs_payment` a inner join `no_dues_hs_details` b on b.id = a.hs_no_dues_id inner join `no_dues_hs_individual` c on c.id = b.assign_hs_no_dues_id where c.admn_no='".$user_id."' and a.payment_status='".$payment_status."' and a.status='".$status."' and b.`is_deleted` = 0";//exit;
        $query = $this->db->query($sql);
       //echo $this->db->last_query(); die();
        return $query->result_array();

    }

    public function get_hostel_dues_details($id)
    {

        $sql = "select * from `no_dues_hs_payment` a inner join `no_dues_hs_details` b on b.id = a.hs_no_dues_id inner join `no_dues_hs_individual` c on c.id = b.assign_hs_no_dues_id where b.id = ?";//exit;
        $query = $this->db->query($sql,array($id));
        //echo $this->db->last_query(); die();
        return $query->result_array();

    }

    public function get_item_details($receipt_id) {

        $sql = "SELECT * FROM `no_dues_hs_details` where `id`=".$receipt_id." and `is_deleted` = 0";
        $query = $this->db->query($sql);
       
        return $query->result_array();

    }

    public function upload_receipt($data,$id){

        // print_r($data);

        // echo $id; exit;

        $this->db->where('hs_no_dues_id', $id);
        $this->db->update('no_dues_hs_payment', $data);
        //echo $this->db->last_query(); die();

    }

    public function update_payment_details($data,$payment_id)
    {


        // echo '<pre>';
        
        // print_r($data);

        // print_r($payment_id);
        
        // echo '</pre>';

        // exit;



        $this->db->where('id', $payment_id['0']['payment_id']);
        $this->db->update('no_dues_hs_payment', $data);
        //echo $this->db->last_query(); die();

    }

    public function get_payment_id($hostel_id_details)
    {

        $sql = "select a.id as payment_id from `no_dues_hs_payment` a inner join `no_dues_hs_details` b on b.id = a.hs_no_dues_id inner join `no_dues_hs_individual` c on c.id = b.assign_hs_no_dues_id where b.id = ?";//exit;
        $query = $this->db->query($sql,array($hostel_id_details));
        //echo $this->db->last_query(); die();
        return $query->result_array();



    }

    public function upload_receipt_details($data)
    {

        $this->db->insert('no_dues_hs_payment_details', $data);

       
        $insert_id = $this->db->insert_id();

        return  $insert_id;

    }

    public function get_previous_dues_status($hs_id)
      {

            $sql = "select status , reject_reason from no_dues_hs_payment where hs_no_dues_id = ?";
			$query = $this->db->query($sql,array($hs_id));
            return $query->result_array();

      }


      public function update_appr_reject_log($datanew)
      {

        $this->db->insert('no_dues_hs_approve_reject_changed_status', $datanew);

      }



}





?>