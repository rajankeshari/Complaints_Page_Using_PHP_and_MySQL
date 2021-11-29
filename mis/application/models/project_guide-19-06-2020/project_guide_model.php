<?php

Class Project_guide_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function get_depts() {
        $query = $this->db->get_where($this->departments, array('type' => 'academic'));
        return $query->result();
    }

    public function get_records_from_id($table_name, $request_fields = "", $id_name, $id_value) {
        $str = '';
        if ($request_fields) {
            foreach ($request_fields as $column_name) {
                $str.= $column_name . ', ';
            }
        } else {
            $str = '*';
        }

        $this->db->select($str);
        $this->db->where($id_name, $id_value);
        $query = $this->db->get($table_name);
        return $query->row_array();
    }

    public function add_update_record($table, $data = array(), $id_name = '') {
        $this->db->set($data);
        if ($id_name) {
            $this->db->where($id_name, $data[$id_name]);
            $query = $this->db->update($table);
        } else {//adding record in table
            $query = $this->db->insert($table);
        }
    }

    public function get_many_records($table_name = '', $filters_id_values = "", $request_fields = "", $order_by = '') {
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

    public function get_many_records_groupby($table_name = '', $filters_id_values = "", $request_fields = "", $order_by = '', $groupby = "") {
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
        if ($groupby) {
            $this->db->group_by($groupby);
        }

        $this->db->from($table_name);
        return $this->db->get()->result_array();
    }

    function get_course_bydept($dept_id) {

        $query = $this->db->query("SELECT DISTINCT course_branch.course_id,id,name,duration FROM
		courses INNER JOIN course_branch ON course_branch.course_id = courses.id INNER JOIN dept_course ON
		dept_course.course_branch_id = course_branch.course_branch_id WHERE dept_course.dept_id = '$dept_id'");
        if ($query->num_rows() > 0)
            return $query->result();
        else
            return false;
    }

    function get_branch_bycourse($course, $dept) {
        $query = $this->db->query("SELECT DISTINCT id,name,dept_course.course_branch_id FROM branches INNER JOIN course_branch ON course_branch.branch_id = branches.id INNER JOIN dept_course ON dept_course.course_branch_id = course_branch.course_branch_id WHERE course_branch.course_id = '" . $course . "' AND dept_course.dept_id = '" . $dept . "'");
        if ($query->num_rows() > 0)
            return $query->result();
        else
            return false;
    }

    public function get_records_from_two_join($tableOne, $tableOneColumn, $tableTwo, $tableTwoColumn, $filters_id_values, $orderBy = "", $req_data = "") {

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

    public function get_name_from_id($table_name = '', $requested_column = 'name', $id_name = "id", $value = 0) {

        $this->db->where($id_name, $value);
        $this->db->select("$requested_column as name");
        $this->db->from($table_name);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $row = $query->row();
            return $row->name;
        }
    }

    public function update_records($table, $data = array(), $filters_id_values) {
        $this->db->set($data);

        if ($filters_id_values) {
            foreach ($filters_id_values as $filter) {
                $this->db->where($filter['id'], $filter['value']);
            }
        }
        $query = $this->db->update($table);
    }

    public function get_select_guide($value = "") {
        $guide_filter[0]["id"] = "a.auth_id";
        $guide_filter[0]["value"] = "ft"; //facaulty
        $guide_filter[1]["id"] = "b.dept_id";
        $guide_filter[1]["value"] = $this->session->userdata('dept_id');

        $req_data = "b.id, b.salutation ,b.first_name,b.middle_name,b.last_name";
        $return_data = $this->get_records_from_two_join($this->emp_basic_details, "emp_no", $this->user_details, "id", $guide_filter, "b.first_name", $req_data);

        $opt_str = "";
        if (!$value) {
            $opt_str.='<option selected="selected" value=""> Choose an Option </option>';
        } else {
            $opt_str.='<option value=""> Choose an Option </option>';
        }
        if (!empty($return_data)) {
            foreach ($return_data as $return_data_value) {
                $opt_str.='<option ';
                if (($value) && ($value == $return_data_value["id"])) {
                    $opt_str.='selected="selected" ';
                }
                $opt_str.='value="' . $return_data_value["id"] . '">' . $return_data_value['salutation'] . " " . $return_data_value['first_name'] . " " . $return_data_value['middle_name'] . " " . $return_data_value['last_name'] . '</option>';
            }
        }
        return $opt_str;
    }
	function get_fellowship_student_details($admn_no){
				
		$q = "SELECT a.* FROM fellow_master a WHERE a.stud_reg_no=?";

        $qu = $this->db->query($q,array($admn_no));
        
         echo $this->db->last_query();   
        if ($qu->num_rows() > 0){
            return $qu->row();
        }else{
            return false;    
        }
	
	}
	function update_project_guide($year,$guide,$coguide,$admn_no){
		
		$query = $this->db->query("update fellow_master set processing_yr='".$year."',guide='".$guide."',co_guide='".$coguide."' where stud_reg_no='".$admn_no."'");
         echo $this->db->last_query();   
		if($this->db->affected_rows()){
          return true;
        }
        else{
          return false;
        }
		
	}
	

}

?>
