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
		/*$myquery="select a.emp_no,concat (b.salutation,' ',b.first_name,' ',b.middle_name,' ',b.last_name) as Name, f.name as Department,e.name as Designation,b.dob,c.joining_date,c.retirement_date,d.basic_pay,a.el_bal,a.el_bal_actual,a.hpl_bal,c.auth_id from leave_balance_sheet a 
inner join user_details b on a.emp_no=b.id
inner join emp_basic_details c on a.emp_no=c.emp_no
inner join emp_pay_details d on a.emp_no=d.emp_no
inner join designations e on e.id=c.designation
inner join cbcs_departments f on b.dept_id=f.id
order by c.auth_id,e.name";*/
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
