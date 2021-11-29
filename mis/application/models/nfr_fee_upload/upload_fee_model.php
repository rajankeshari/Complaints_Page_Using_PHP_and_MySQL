<?php

class Upload_fee_model extends CI_Model{

        private $nfrlive='nfrlive';
        //private $nfrbeta='nfrbeta';
       

	function __construct(){
		parent::__construct();
    }


    public function get_last_updated_order_date(){


    $sql = "SELECT created_TS
    FROM  nfr_admin_rec_fee_details
    group BY created_TS ORDER BY STR_TO_DATE(created_TS,'%d-%m-%y') DESC LIMIT 1";

// $sql = "SELECT order_booking_date_time
// FROM  sbi_full_transaction
// group BY order_booking_date_time ORDER BY STR_TO_DATE(order_booking_date_time,'%d-%m-%y') DESC LIMIT 1";

    $query = $this->db->query($sql);
    if($query != false)
    {
    $settle_date = $query->result_array();
    return $settle_date['0']['created_TS'];
    }
    else
    {
        return false;
    }



    }


    public function nfr_fee_upload()
    {
        $query = $this->db->select('category_name,payment_mode,transaction_id,transaction_date,amount_paid,status,adv_no,post_name,name,father_name,dob,mobile,fee_to_be_paid,remarks')
                           ->from('nfr_admin_rec_fee_details')
                           ->get(); 

                           return $query->result_array();
    }

    public function nfr_fee_upload_with_sink()
    {
        $query = $this->db->select('category_name,payment_mode,transaction_id,transaction_date,amount_paid,status,adv_no,post_name,name,father_name,dob,mobile,fee_to_be_paid,remarks,sink_details')
                           ->from('nfr_admin_rec_fee_details')
                           ->where('sink_details','sink_done')
                           ->get(); 

                           return $query->result_array();
    }

    public function nfr_fee_upload_without_sink()
    {
        $query = $this->db->select('category_name,payment_mode,transaction_id,transaction_date,amount_paid,status,adv_no,post_name,name,father_name,dob,mobile,fee_to_be_paid,remarks,sink_details')
                           ->from('nfr_admin_rec_fee_details')
                           ->where('sink_details',NULL)
                           ->get(); 


        // if($query->result_array()=='') {
           
        //     $query = $this->db->select('category_name,payment_mode,transaction_id,transaction_date,amount_paid,status,adv_no,post_name,name,father_name,dob,mobile,fee_to_be_paid,remarks,sink_details')
        //     ->from('nfr_admin_rec_fee_details')
        //     ->where('sink_details',NULL)
        //     ->get(); 
        // }

        //                    echo $this->db->last_query(); die();

                           return $query->result_array();
    }

    public function nfr_fee_sink_with_application($transaction_id)
    {

        $CI = &get_instance();
        $this->db2 = $CI->load->database($this->nfrlive, TRUE);

        $query = $this->db2->select('app_no')
                          ->from('nfr_emp_application')
                          ->where('transaction_id',$transaction_id)
                          ->get();

         if($query->num_rows() > 0) {

              $application_no = $query->result_array();
              $data = array(

                'status' => 'Completely Successful'

              );

              $this->db2->where('app_no',$application_no[0]['app_no'])
                        ->update('nfr_emp_application',$data);

                        $data = array(

                            'sink_details' => 'sink_done'
                        );

                        $this->db->where('transaction_id',$transaction_id)
                        ->update('nfr_admin_rec_fee_details',$data);

                        $this->db2->where('transaction_id',$transaction_id)
                        ->update('nfr_admin_rec_fee_details',$data);

                        return 1;
         }

         else {

            return 0;
         }


    }

    public function dynamic_entry_upload_fee($data)
    {

       $transaction_id = $data['transaction_id']; //exit;

       $check_trans_already_exists = $this->check_trans_already_exists($transaction_id);

       $check_trans_already_exists_nfr = $this->check_trans_already_exists_nfr($transaction_id);

       if ($check_trans_already_exists >= 1 || check_trans_already_exists_nfr >= 1) {
          
           return 0;
              
       }

       else {
           
           $this->db->insert('nfr_admin_rec_fee_details',$data);

           $CI = &get_instance();
           $this->db2 = $CI->load->database($this->nfrlive, TRUE);


           $this->db2->insert('nfr_admin_rec_fee_details',$data);



           return 1;
       }

    }

    public function check_trans_already_exists($transaction_id)
    {
        
             $query = $this->db->select('*')
                            ->from('nfr_admin_rec_fee_details')
                            ->where('transaction_id',$transaction_id)
                            ->get();

                            if($query != false)

                            {

                            return $query->num_rows();

                            }

                            else
                            {
                                return false;
                            }
        
    }


    public function check_trans_already_exists_nfr($transaction_id)
    {

        $CI = &get_instance();
        $this->db2 = $CI->load->database($this->nfrlive, TRUE);
        
             $query = $this->db2->select('*')
                            ->from('nfr_admin_rec_fee_details')
                            ->where('transaction_id',$transaction_id)
                            ->get();

                            return $query->num_rows();
        
    }


    public function get_nfr_fee_details(){

        $CI = &get_instance();
        $this->db2 = $CI->load->database($this->nfrlive, TRUE);

        $query = $this->db2->select('*')
                          ->from('nfr_admin_rec_fee_details')
                          //->where('status','Pending')
                          ->get();
        //echo $this->db->last_query();
        return $query->result_array();

    }

    // public function get_all_sbi_transaction_details()
    // {

    //     $query = $this->db->select('*')
    //                       ->from('sbi_all_transactions')
    //                       ->where('status','sync_done')
    //                       ->get();
    //     //echo $this->db->last_query();
    //     return $query->result_array();

    // }

}
    

    ?>