<?php
	class Complaint_model extends CI_model
	{

        private $tabulation='payserver';
        private $misdev = 'misdev';
        private $parentserver = 'parentserver';

        function __construct()
		{
            parent::__construct();
           
        }

        public function check_order_no_mis_success($order_number)
        {
            // $CI = &get_instance();
            // $this->db2 = $CI->load->database($this->misdev, TRUE);

            $sql = "select * from sbi_success_details_semester_fees where txnid = ?";
            $query = $this->db->query($sql, array($order_number));
            return $query->num_rows();

        }

        public function check_order_no_sbi_settlement($order_number)
        {

            // $CI = &get_instance();
            // $this->db2 = $CI->load->database($this->misdev, TRUE);

            $sql = "select * from sbi_final_settlement_data where merchant_order_number = ?";
            $query = $this->db->query($sql, array($order_number));
            return $query->num_rows();

        }

        public function check_order_no_mis_failure($order_number)
        {

            // $CI = &get_instance();
            // $this->db2 = $CI->load->database($this->misdev, TRUE);

            $sql = "select * from sbi_failure_details_semester_fees where txnid = ?";
            $query = $this->db->query($sql, array($order_number));
            return $query->num_rows();

        }

        public function check_order_no_full_transaction($order_number)
        {

            // $CI = &get_instance();
            // $this->db2 = $CI->load->database($this->misdev, TRUE);

            $sql = "select * from sbi_full_transaction where merchant_order_number = ?";
            $query = $this->db->query($sql, array($order_number));
            return $query->num_rows();

        }

        public function get_order_no_full_transaction_details($order_number)
        {

            // $CI = &get_instance();
            // $this->db2 = $CI->load->database($this->misdev, TRUE);

            $sql = "select * from sbi_full_transaction where merchant_order_number = ?";
            $query = $this->db->query($sql, array($order_number));
            if ($query->num_rows() > 0) {

                return $query->result_array();
            }
            else {
               
                return false;
            }
           

        }

        public function store_user_enquiry_order_number($data_enquiry)
        {

            // $CI = &get_instance();
            // $this->db2 = $CI->load->database($this->parentserver, TRUE);

            $this->db->insert('store_details_enquiry',$data_enquiry);

        }

        //public function 

    }

    ?>