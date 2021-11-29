<?php

class Leave_availed_sheet_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function check_status($empno) {

        $myquery = "select * from  leave_balance_sheet where emp_no=?";

        $query = $this->db->query($myquery, array($empno));
//echo $$this->db->last_query();die();
        if ($query->num_rows() > 0) {

            return $query->row();
        } else {
            return FALSE;
        }
    }

    function check_vl_availed($prev_yr, $empno) {

        $myquery = "select sum(vacation_availed) as tot from leave_availed_sheet where  emp_no=? and year=?";

        $query = $this->db->query($myquery, array($empno, $prev_yr));

        //var_dump($query->result());
        //die();
        if ($query->num_rows() > 0) {

            return $query->result_array();
        } else {
            return FALSE;
        }
    }

    function insert_leave_availed($data, $leave_type,$yr) {
		//echo $yr;die();
		if ($yr == date('Y')-1 || date('m')==8 && $yr==date('Y') - 1 . '-' . substr(date('Y'), 2)){			
		$this->db->where('emp_no', $data['emp_no']);
		$this->db->where('year', $yr);
        $query = $this->db->get('leave_balance_sheet_yearwise');
        $result1 = $query->result()[0];
		$this->db->where('emp_no', $data['emp_no']);
        $query = $this->db->get('leave_balance_sheet');
        $result = $query->result()[0];		
		}else{
		$this->db->where('emp_no', $data['emp_no']);
        $query = $this->db->get('leave_balance_sheet');
        $result = $query->result()[0];
		}
		//echo $this->db->last_query();die();
        
        switch ($leave_type) {
            case 'EL':	$result->el_bal = $result->el_bal - $data['el_availed'];
						$result->el_bal_actual = $result->el_bal_actual - $data['el_availed'];
						break;
            case 'VL':
						if (date('m')==8 && $yr==date('Y') - 1 . '-' . substr(date('Y'), 2)){
						$result1->vl_bal = $result1->vl_bal - $data['vacation_availed'];
						$result->el_bal = $result->el_bal - floor($data['vacation_availed']/2);
						$result->el_bal_actual = $result->el_bal_actual - floor($data['vacation_availed']/2);
						}else{
						$result->vl_bal = $result->vl_bal - $data['vacation_availed'];
						}
						break;
						
            case 'HPL':	$result->hpl_bal = $result->hpl_bal - $data['hpl_availed'];
						break;
            case 'CoL':	$result->hpl_bal = $result->hpl_bal - ($data['commuted_availed'] * 2);
						break;
            case 'CL':
						if ($yr==date('Y')-1){
						$result1->cl_bal = $result1->cl_bal - $data['cl_availed'];
						}else{
						$result->cl_bal = $result->cl_bal - $data['cl_availed'];	
						}						
						break;
            case 'RH':
						if ($yr==date('Y')-1){
						$result1->rh_bal = $result1->rh_bal - $data['rh_availed'];
						}else{
						$result->rh_bal = $result->rh_bal - $data['rh_availed'];
						}
						
						break;
            case 'PL':	if($yr==date('Y')-1){
						$result1->pl_bal = $result1->pl_bal - $data['pl_availed'];
						}else{
							$result->pl_bal = $result->pl_bal - $data['pl_availed'];
						}						
						break;
            default :	if($yr==date('Y')-1){
				
						$result1->dl_bal = $result1->dl_bal - $data['dl_availed'];
						
						}
						
						$result->dl_bal = $result->dl_bal - $data['dl_availed'];
        }

    
		
		if ($yr==date('Y')-1 || date('m')==8 && $yr==date('Y') - 1 . '-' . substr(date('Y'), 2)){
		$result1->updated_by = $this->session->userdata('id');
        $result1->updated_on = date('Y-m-d H:i:s');
		}else{
			    $result->updated_by = $this->session->userdata('id');
				$result->updated_on = date('Y-m-d H:i:s');
		}

        if ($this->db->insert('leave_availed_sheet', $data)) {
            //if($leave_type != 'SL' && $leave_type != 'OD'){	
			if($leave_type != 'SL' && $leave_type != 'DL'){	
			
			if ($yr==date('Y')-1 || date('m')==8 && $yr==date('Y') - 1 . '-' . substr(date('Y'), 2)){
				$this->db->where('emp_no', $data['emp_no']);
				$this->db->where('year', $yr);
				$this->db->update('leave_balance_sheet_yearwise', $result1);
				$this->db->where('emp_no', $data['emp_no']);
				$this->db->update('leave_balance_sheet', $result);
			}else{
			$this->db->where('emp_no', $data['emp_no']);
            $this->db->update('leave_balance_sheet', $result);
			}
			
			}
            return true;
        } else {
            return false;
        }
    }

    function edit_leave_availed_sheet($new) {
        // this is to ready data to update the Leave Balance Table [Start]
        $this->db->where('emp_no', $new['emp_no']);
        $query = $this->db->get('leave_balance_sheet');
        $update_bal = $query->result()[0];

        $this->db->where('id', $new['id']);
        $query = $this->db->get('leave_availed_sheet');
        $get_previous = $query->result()[0];

        $update_bal->el_bal = $update_bal->el_bal + $get_previous->el_availed - $new['el_availed'];
        $update_bal->vl_bal = $update_bal->vl_bal + $get_previous->vacation_availed - $new['vacation_availed'];
        $update_bal->hpl_bal = $update_bal->hpl_bal + $get_previous->hpl_availed - $new['hpl_availed'];
        $update_bal->hpl_bal = $update_bal->hpl_bal + ($get_previous->commuted_availed * 2) - ($new['commuted_availed'] * 2);
        $update_bal->cl_bal = $update_bal->cl_bal + $get_previous->cl_availed - $new['cl_availed'];
        $update_bal->rh_bal = $update_bal->rh_bal + $get_previous->rh_availed - $new['rh_availed'];
        $update_bal->pl_bal = $update_bal->pl_bal + $get_previous->pl_availed - $new['pl_availed'];
        //var_dump($update_bal->pl_bal);die();
        $update_bal->dl_bal = $update_bal->dl_bal + $get_previous->dl_credit - $new['dl_credit'];
        $update_bal->updated_by = $this->session->userdata('id');
        $update_bal->updated_on = date('Y-m-d H:i:s');

        // this is to ready data to update the Leave Balance Table [End]

        $this->db->where('id', $new['id']);
        $query = $this->db->get('leave_availed_sheet');
        $result = $query->result()[0];

        $this->db->where('id', $new['id']);
        if ($this->db->update('leave_availed_sheet', $new)) {
            $result->operated_by = $this->session->userdata('id');
            $result->operated_on = date('Y-m-d H:i:s');
            $result->operation = 'E';


            $this->db->insert('leave_availed_sheet_backup', $result);
            //echo $this->db->last_query();die();
            // this is to ready data to update the Leave Balance Table [Start]
            $this->db->where('emp_no', $new['emp_no']);
            $this->db->update('leave_balance_sheet', $update_bal);
            // this is to ready data to update the Leave Balance Table [End]
            return true;
        } else {
            return false;
        }
    }

    function delete_leave_availed_sheet($id) {
        // this is to ready data to update the Leave Balance Table [Start]

        $this->db->where('id', $id);
        $query = $this->db->get('leave_availed_sheet');
        $get_previous = $query->result()[0];


        $this->db->where('emp_no', $get_previous->emp_no);
        $query = $this->db->get('leave_balance_sheet');
        $update_bal = $query->result()[0];



        $update_bal->el_bal = $update_bal->el_bal + $get_previous->el_availed;
        $update_bal->vl_bal = $update_bal->vl_bal + $get_previous->vacation_availed;
        $update_bal->hpl_bal = $update_bal->hpl_bal + $get_previous->hpl_availed;
        $update_bal->hpl_bal = $update_bal->hpl_bal + ($get_previous->commuted_availed * 2);
        $update_bal->cl_bal = $update_bal->cl_bal + $get_previous->cl_availed;
        $update_bal->rh_bal = $update_bal->rh_bal + $get_previous->rh_availed;
        $update_bal->pl_bal = $update_bal->pl_bal + $get_previous->pl_availed;
        $update_bal->dl_bal = $update_bal->dl_bal + $get_previous->dl_availed;
        $update_bal->updated_by = $this->session->userdata('id');
        $update_bal->updated_on = date('Y-m-d H:i:s');
        //var_dump($update_bal);die();
        // this is to ready data to update the Leave Balance Table [End]		

        $this->db->where('id', $id);
        $query = $this->db->get('leave_availed_sheet');
        $result = $query->result()[0];
        $result->operated_by = $this->session->userdata('id');
        $result->operated_on = date('Y-m-d H:i:s');
        $result->operation = 'D';


        /*         * ***********--Deleting leave_balance_sheet--*************** */
        $this->db->where('id', $id);


        if ($this->db->delete('leave_availed_sheet')) {
            /*             * *********--Inserting data in leave_balance_sheet_backup--******** */
            $this->db->insert('leave_availed_sheet_backup', $result);
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

        $myquery = "select * from leave_availed_sheet";
        //$myquery="select * from leave_balance_sheet where (emp_no, year) in (select emp_no,max(year) from leave_balance_sheet group by emp_no)";

        $query = $this->db->query($myquery);

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    function get_leave_availed_sheet($ar) {

        $myquery = "select * from leave_availed_sheet where id=?";

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
	function get_leave_bal_cal_yr($empid, $yr) {

        $myquery = "select * from leave_balance_sheet_yearwise where emp_no=? and year=?";

        $query = $this->db->query($myquery, array ($empid,$yr));

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

}
