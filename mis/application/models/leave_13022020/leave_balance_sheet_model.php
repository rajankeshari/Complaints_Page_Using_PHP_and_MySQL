<?php

class Leave_balance_sheet_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function check_status($empno) {

        $myquery = "select * from  leave_balance_sheet where emp_no=?";
        $query = $this->db->query($myquery, array($empno));
        if ($query->num_rows() > 0) {
            return $query->row();
        } else {
            return FALSE;
        }
    }

    function insert_leave_balance($data) {
		
        if ($this->db->insert('leave_balance_sheet', $data))
            return $this->db->insert_id() + 1;
        else
			//echo $this->db->last_query();die();
            return false;
    }
	function insert_leave_balance_sheet_reason($data) {
		
        if ($this->db->insert('leave_balance_sheet_reason', $data))
            return TRUE;
        else
			//echo $this->db->last_query();die();
            return false;
    }

    function get_all_details() {

        $myquery = "select * from leave_balance_sheet";
        $query = $this->db->query($myquery);
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    function get_all_details_individual($data) {

        $myquery = "select * from leave_balance_sheet where emp_no=?";

        $query = $this->db->query($myquery, $data['emp_no']);

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    function get_joining_retirement_dates($data) {

        $myquery = "select * from emp_basic_details where emp_no=?";

        $query = $this->db->query($myquery, $data['emp_no']);

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    function get_leave_balance_sheet($ar) {

        $myquery = "select * from leave_balance_sheet where emp_no=?";

        $query = $this->db->query($myquery, $ar['emp_no']);

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    function delete_leave_balance_sheet($emp_no) {
        /* $this->db->where('emp_no', $emp_no);
          $query = $this->db->get('leave_balance_sheet');
          $result = $query->result()[0];
          $result->operation = 'D';
          $result->operated_on = date('Y-m-d H:i:s');
          $result->operated_by = $this->session->userdata('id'); */
        /*         * ***********--Deleting leave_balance_sheet--*************** */
        $this->db->where('emp_no', $emp_no);
        if ($this->db->delete('leave_balance_sheet')) {
            /*             * *********--Inserting data in leave_balance_sheet_backup--******** */
            //$this->db->insert('leave_balance_sheet_backup', $result);
            $this->db->where('emp_no', $emp_no);
            $this->db->delete(leave_dynamic_credit_sheet);
            $this->db->where('emp_no', $emp_no);
            $this->db->delete(leave_availed_sheet);

            return true;
        } else {
            return false;
        }
    }

    function edit_leave_balance_sheet($new) {

        //$this->db->where('emp_no', $new['emp_no']);
        //$query = $this->db->get('leave_balance_sheet');
        //$result = $query->result()[0];

        $this->db->where('emp_no', $new['emp_no']);
        if ($this->db->update('leave_balance_sheet', $new)) {
            // $result->operation = 'E';
            //$result->operated_on = date('Y-m-d H:i:s');
            //$result->operated_by = $this->session->userdata('id');
            // $this->db->insert('leave_balance_sheet_backup', $result);
            return true;
        } else {
            return false;
        }
    }

}
