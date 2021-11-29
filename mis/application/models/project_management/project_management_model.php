<?php

/**
 * Author: Anuj
 */
class Project_management_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    public function get_departments($table_name = '', $filters_id_values = "", $request_fields = "", $order_by = '') {
        $str = '';
        if ($request_fields) {
            foreach ($request_fields as $column_name) {
                $str.= $column_name . ', ';
            }
        } else {
            $str = '*';
        }
        $this->db->select($str);

        if ($filters_id_values) {
            foreach ($filters_id_values as $filter) {
                $this->db->where($filter['id'], $filter['value']);
            }
        }

        if ($order_by) {
            $this->db->order_by($order_by);
        }

        $this->db->from($table_name);
        return $this->db->get()->result_array();
    }

    public function get_records_from_two_join_for_members_of_dept($tableOne, $tableOneColumn, $tableTwo, $tableTwoColumn, $filters_id_values, $orderBy = "", $req_data = "") {

        if ($req_data) {
            $this->db->select($req_data);
        } else {
            $this->db->select('*');
        }


        $this->db->from("$tableOne a");
        $this->db->join("$tableTwo b", "b.$tableTwoColumn=a.$tableOneColumn", "inner");
        if ($filters_id_values) {
            foreach ($filters_id_values as $filter) {//filter should be Table combination LIKE   $this->db->where("b.enrollment_year", $id);
                $this->db->where($filter['id'], $filter['value']);
            }
        }

        if ($orderBy) {//filter should be Table combination LIKE    $this->db->order_by('c.track_title', 'asc');
            $this->db->order_by($orderBy);
        }

        return $this->db->get()->result_array();
    }

    function get_funding_agency() {
        $myquery = "select distinct name from funding_agencies order by name asc";
        $query = $this->db->query($myquery);

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    function insert_project_main_details($data) {
        if ($this->db->insert('accounts_project_sub_details', $data))
            return $this->db->insert_id();
        else
            return FALSE;
    }

    function get_max_id() {

        $myquery = "select id from accounts_project_sub_details  order by id DESC limit 1";
        $query = $this->db->query($myquery);
        if ($query->num_rows() > 0) {
            //return TRUE;
            return $query->row();
        } else {
            return FALSE;
        }
    }

    public function add_update_record($table, $data) {
        $query = $this->db->insert($table, $data);
      #echo $this->db->last_query();die();
    }

    function get_project_details() {
        //$this->db->order_by("name","asc");
        $this->db->select('*');
        $this->db->where('status','submitted');
        $this->db->from('accounts_project_sub_details');
        $query = $this->db->get();
        return $query->result_array();
    }

    function get_record($ar) {

        $myquery = "select * from accounts_project_sub_details where id=?";

        $query = $this->db->query($myquery, $ar['id']);

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    function get_project_id_record() {

        $myquery = "select project_no  from accounts_project_details apd where apd.project_no not in(select project_no from accounts_project_status)";

        $query = $this->db->query($myquery);

        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return false;
        }
    }
    function get_fy() {

        $myquery = "select * from mis_session_year";

        $query = $this->db->query($myquery);

        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return false;
        }
    }
    function edit_project_management($new) {

    //  print_r($new);
      $p_id=$new['id'];
      $pro_no=$new['pro_no'];
      $query = $this->db->query("SELECT * FROM accounts_project_status WHERE p_id ='$p_id' and project_no='$pro_no'");
      $cnt=$query->num_rows();
#echo $this->db->last_query();die();
      $data = array(
              'p_id' => $new['id'],
              'project_no' => $new['pro_no'],
              'status' => $new['status'],
              'fy' => $new['fy']
      );
      if($cnt==0){
      $this->db->insert('accounts_project_status', $data);

      $data = array(
        'financial_year' => $new['fy'],
        'status' => $new['status'],
        'id' => $new['id']
);

    $this->db->where('project_no', $pro_no);
    $this->db->update('accounts_project_details', $data);

  /*  $querypi = $this->db->get('accounts_project_sub_ext_mem');

    foreach ($querypi->result() as $row)
    {
          //  echo $row->title;
    }*/


    }

    //  exit;
        $this->db->where('id', $new['id']);
        if ($this->db->update('accounts_project_sub_details', $new)) {
#echo $this->db->last_query();die();
            return true;
        } else {
            return false;
        }
    }

}
