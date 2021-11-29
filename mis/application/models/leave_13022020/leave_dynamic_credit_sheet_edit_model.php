<?php

class Leave_dynamic_credit_sheet_edit_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function check_status($empno, $yr, $mon) {

        $myquery = "select * from  leave_dynamic_credit_sheet where emp_no=? and year=? and month=?";

        $query = $this->db->query($myquery, array($empno, $yr, $mon));

        if ($query->num_rows() > 0) {

            return $query->row();
        } else {
            return FALSE;
        }
    }

    function check_el_availability($yr, $mon) {

        $myquery = "select el_credit from  leave_dynamic_credit_sheet where year=? and month=? and el_credit <> ''";

        $query = $this->db->query($myquery, array($yr, $mon));
        //echo $this->db->last_query();
        //die();

        if ($query->num_rows() > 0) {

            return $query->row();
        } else {
            return FALSE;
        }
    }

    function check_vl_availability($yr, $mon) {

        $myquery = "select vl_credit from  leave_dynamic_credit_sheet where year=? and month=? and vl_credit <> ''";

        $query = $this->db->query($myquery, array($yr, $mon));
        //echo $this->db->last_query();
        //die();

        if ($query->num_rows() > 0) {

            return $query->row();
        } else {
            return FALSE;
        }
    }

    function check_hpl_availability($yr, $mon) {

        $myquery = "select hpl_credit from  leave_dynamic_credit_sheet where year=? and month=? and hpl_credit <> ''";

        $query = $this->db->query($myquery, array($yr, $mon));
        //echo $this->db->last_query();
        //die();

        if ($query->num_rows() > 0) {

            return $query->row();
        } else {
            return FALSE;
        }
    }

    function check_cl_availability($yr, $mon) {

        $myquery = "select cl_credit from  leave_dynamic_credit_sheet where year=? and month=? and cl_credit <> ''";

        $query = $this->db->query($myquery, array($yr, $mon));
        //echo $this->db->last_query();
        //die();

        if ($query->num_rows() > 0) {

            return $query->row();
        } else {
            return FALSE;
        }
    }

    function check_rh_availability($yr, $mon) {

        $myquery = "select rh_credit from  leave_dynamic_credit_sheet where year=? and month=? and rh_credit <> ''";

        $query = $this->db->query($myquery, array($yr, $mon));
        //echo $this->db->last_query();
        //die();

        if ($query->num_rows() > 0) {

            return $query->row();
        } else {
            return FALSE;
        }
    }

    function check_pl_availability($yr, $mon) {

        $myquery = "select pl_credit from  leave_dynamic_credit_sheet where year=? and month=? and pl_credit <> ''";

        $query = $this->db->query($myquery, array($yr, $mon));
        //echo $this->db->last_query();
        //die();

        if ($query->num_rows() > 0) {

            return $query->row();
        } else {
            return FALSE;
        }
    }

    function check_dl_availability($yr, $mon) {

        $myquery = "select dl_credit from  leave_dynamic_credit_sheet where year=? and month=? and dl_credit <> ''";

        $query = $this->db->query($myquery, array($yr, $mon));
        //echo $this->db->last_query();
        //die();

        if ($query->num_rows() > 0) {

            return $query->row();
        } else {
            return FALSE;
        }
    }

    function check_balance_table_status() {

        $myquery = "select * from  leave_balance_sheet";

        $query = $this->db->query($myquery, array($empno, $yr));

        if ($query->num_rows() > 0) {

            return $query->row();
        } else {
            return FALSE;
        }
    }

    function insert_dynamic_leave_credit($data, $leave_type, $emp_type) {

        $this->db->where('emp_no', $data['emp_no']);
        $query = $this->db->get('leave_balance_sheet');
        $result = $query->result()[0];


        switch ($leave_type) {
            case 'EL':
                $result->el_bal = $result->el_bal + $data['el_credit'];
                break;

            case 'VL':
                $result->vl_bal = $data['vl_credit'];
                break;

            case 'HPL':
                $result->hpl_bal = $result->hpl_bal + $data['hpl_credit'];
                break;

            case 'CL':
                $result->cl_bal = $data['cl_credit'];
                break;

            case 'RH':
                $result->rh_bal = $data['rh_credit'];
                break;

            case 'PL':
                $result->pl_bal = $data['pl_credit'];
                break;

            default :
                $result->dl_bal = $data['dl_credit'];
        }
        $result->updated_by = $this->session->userdata('id');
        $result->updated_on = date('Y-m-d H:i:s');

        if ($this->db->insert('leave_dynamic_credit_sheet', $data)) {
            //$this->db->insert('leave_dynamic_credit_count',$cond)
            $this->db->where('emp_no', $data['emp_no']);
            $this->db->update('leave_balance_sheet', $result);
            return true;
        } else {
            return false;
        }
    }

    function update_dynamic_leave_credit($data, $leave_type, $emp_type) {


        $this->db->where('emp_no', $data['emp_no']);
        $query = $this->db->get('leave_balance_sheet');
        $result = $query->result()[0];

        $this->db->where('emp_no', $data['emp_no'], 'year', $data['year']);
        $query = $this->db->get('leave_dynamic_credit_sheet');
        $credit = $query->result()[0];

        switch ($leave_type) {
            case 'EL': $cond = array('el_credit' => $data['el_credit']);
                $result->el_bal = $result->el_bal + $data['el_credit'];
                break;

            case 'VL': $cond = array('vl_credit' => $data['vl_credit']);
                $result->vl_bal = $data['vl_credit'];
                break;

            case 'HPL': $cond = array('hpl_credit' => $data['hpl_credit']);
                $result->hpl_bal = $result->hpl_bal + $data['hpl_credit'];
                break;

            case 'CL': $cond = array('cl_credit' => $data['cl_credit']);
                $result->cl_bal = $data['cl_credit'];
                break;

            case 'RH': $cond = array('rh_credit' => $data['rh_credit']);
                $result->rh_bal = $data['rh_credit'];
                break;

            case 'PL': $cond = array('pl_credit' => $data['pl_credit']);
                $result->pl_bal = $data['pl_credit'];
                break;

            default: $cond = array('dl_credit' => $data['dl_credit']);
                $result->dl_bal = $data['dl_credit'];
        }
        $result->updated_by = $this->session->userdata('id');
        $result->updated_on = date('Y-m-d H:i:s');


        $this->db->where('emp_no', $data['emp_no']);
        $this->db->where('year', $data['year']);
        $this->db->where('month', $data['month']);

        if ($this->db->update('leave_dynamic_credit_sheet', $cond)) {

            $this->db->where('emp_no', $data['emp_no']);
            $this->db->update('leave_balance_sheet', $result);
            return true;
        } else {
            return false;
        }
    }

    function update_el_for_faculty($data) {


        $this->db->where('emp_no', $data['emp_no']);
        $query = $this->db->get('leave_balance_sheet');
        $result = $query->result()[0];


        $this->db->where('emp_no', $data['emp_no'], 'year', $data['year']);
        $query = $this->db->get('leave_dynamic_credit_sheet');
        $credit = $query->result()[0];

        $cond = array('el_credit' => $data['el_credit']);
        $result->el_bal = $result->el_bal + $data['el_credit'];


        $this->db->where('emp_no', $data['emp_no']);
        $this->db->where('year', $data['year']);
        $this->db->where('month', $data['month']);

        if ($this->db->update('leave_dynamic_credit_sheet', $cond)) {
            //echo $this->db->last_query();
            //die();
            $this->db->where('emp_no', $empno);
            $this->db->update('leave_balance_sheet', $result);

            return true;
        } else {
            return false;
        }
    }

    function insert_el_for_faculty($data) {


        $this->db->where('emp_no', $data['emp_no']);
        $query = $this->db->get('leave_balance_sheet');
        $result = $query->result()[0];

        $result->el_bal = $result->el_bal + $data[el_credit];

        if ($this->db->insert('leave_dynamic_credit_sheet', $data)) {
            //echo $this->db->last_query();
            //die();
            $this->db->where('emp_no', $data['emp_no']);
            $this->db->update('leave_balance_sheet', $result);
            //echo $this->db->last_query();
            //die();
            return true;
        } else {
            return false;
        }
    }

    function edit_dynamic_leave_credit_sheet($new) {
        // this is to ready data to update the Leave Balance Table [Start]
        $this->db->where('emp_no', $new['emp_no']);
        $query = $this->db->get('leave_balance_sheet');
        $update_bal = $query->result()[0];

        $this->db->where('id', $new['id']);
        $query = $this->db->get('leave_dynamic_credit_sheet');
        $get_previous = $query->result()[0];

        $update_bal->el_bal = $update_bal->el_bal - $get_previous->el_credit + $new['el_credit'];
        $update_bal->vl_bal = $update_bal->vl_bal - $get_previous->vl_credit + $new['vl_credit'];
        $update_bal->hpl_bal = $update_bal->hpl_bal - $get_previous->hpl_credit + $new['hpl_credit'];
        $update_bal->cl_bal = $update_bal->cl_bal - $get_previous->cl_credit + $new['cl_credit'];
        $update_bal->rh_bal = $update_bal->rh_bal - $get_previous->rh_credit + $new['rh_credit'];
        $update_bal->pl_bal = $update_bal->pl_bal - $get_previous->pl_credit + $new['pl_credit'];
        $update_bal->dl_bal = $update_bal->dl_bal - $get_previous->dl_credit + $new['dl_credit'];
        $update_bal->updated_by = $this->session->userdata('id');
        $update_bal->updated_on = date('Y-m-d H:i:s');
        // this is to ready data to update the Leave Balance Table [End]


        $this->db->where('id', $new['id']);
        if ($this->db->update('leave_dynamic_credit_sheet', $new)) {

            // this is to ready data to update the Leave Balance Table [Start]
            $this->db->where('emp_no', $new['emp_no']);
            $this->db->update('leave_balance_sheet', $update_bal);
            // this is to ready data to update the Leave Balance Table [End]
            return true;
        } else {
            return false;
        }
    }

    function delete_leave_credit_sheet($id) {
        // this is to ready data to update the Leave Balance Table [Start]

        $this->db->where('id', $id);
        $query = $this->db->get('leave_credit_sheet');
        $get_previous = $query->result()[0];


        $this->db->where('emp_no', $get_previous->emp_no);
        $query = $this->db->get('leave_balance_sheet');

        $update_bal = $query->result()[0];



        $update_bal->el_bal = $update_bal->el_bal - $get_previous->el_credit;
        $update_bal->vl_bal = $update_bal->vl_bal - $get_previous->vl_credit;
        $update_bal->hpl_bal = $update_bal->hpl_bal - $get_previous->hpl_credit;
        $update_bal->cl_bal = $update_bal->cl_bal - $get_previous->cl_credit;
        $update_bal->rh_bal = $update_bal->rh_bal - $get_previous->rh_credit;
        $update_bal->pl_bal = $update_bal->pl_bal - $get_previous->pl_credit;
        $update_bal->dl_bal = $update_bal->dl_bal - $get_previous->dl_credit;
        $update_bal->updated_by = $this->session->userdata('id');
        $update_bal->updated_on = date('Y-m-d H:i:s');
        //var_dump($update_bal);die();
        // this is to ready data to update the Leave Balance Table [End]		

        $this->db->where('id', $id);
        $query = $this->db->get('leave_credit_sheet');
        $result = $query->result()[0];
        $result->operation = 'D';
        $result->operated_on = date('Y-m-d H:i:s');
        $result->operated_by = $this->session->userdata('id');
        /*         * ***********--Deleting leave_balance_sheet--*************** */
        $this->db->where('id', $id);


        if ($this->db->delete('leave_credit_sheet')) {
            /*             * *********--Inserting data in leave_balance_sheet_backup--******** */
            $this->db->insert('leave_credit_sheet_backup', $result);
            // this is to ready data to update the Leave Balance Table [Start]
            $this->db->where('emp_no', $get_previous->emp_no);
            $this->db->update('leave_balance_sheet', $update_bal);
            // this is to ready data to update the Leave Balance Table [End]
            return true;
        } else {
            return false;
        }
    }

    function get_all_details() {

        $myquery = "select * from leave_dynamic_credit_sheet";
        //$myquery="select * from leave_balance_sheet where (emp_no, year) in (select emp_no,max(year) from leave_balance_sheet group by emp_no)";

        $query = $this->db->query($myquery);

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    function get_all_emp_nos($emp_type) {

        if ($emp_type == 'Faculty') {
            $myquery = "select emp_no from leave_balance_sheet where emp_no in (select emp_no from emp_basic_details where auth_id='ft')";
        } else if ($emp_type == 'Non Faculty') {
            $myquery = "select emp_no from leave_balance_sheet where emp_no in (select emp_no from emp_basic_details where auth_id='nfta' or auth_id='nftn')";
        } else {
            $myquery = "select emp_no from leave_balance_sheet where emp_no in (select emp_no from emp_basic_details)";
        }


        $query = $this->db->query($myquery);

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    function get_dynamic_leave_credit_sheet($ar) {

        $myquery = "select * from leave_dynamic_credit_sheet where id=?";

        $query = $this->db->query($myquery, $ar['id']);

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    function get_leave_bal($empid) {

        $myquery = "select * from leave_balance_sheet where emp_no=?";

        $query = $this->db->query($myquery, $empid);

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

}
