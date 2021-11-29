<?php

class Main_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    //--------------------------------------------------------------------------
    public function add_update_record($table, $data = array(), $id_name = '') {

        $this->db->set($data);
        if ($id_name) {
            $this->db->where($id_name, $data[$id_name]);
            $query = $this->db->update($table);
        } else {//adding record in table
            $query = $this->db->insert($table);
        }
    }

    public function get_student_full_details_from_admn_no($admn_no) {
        $query = "select a.admn_no,b.dept_id,c.name as dept_name, a.course_id,d.name as course_name,a.branch_id,e.name as branch_name,a.semester, b.salutation,b.first_name,b.middle_name,b.last_name,b.photopath from stu_academic as a
join  user_details as b on b.id = a.admn_no
left join departments as c on c.id = b.dept_id
left join courses as d on d.id = a.course_id
left join branches as e on e.id = a.branch_id
where a.admn_no = ?";
        $result_query = $this->db->query($query, array($admn_no));
        return $result_query->row_array();
    }

    public function update_record_from_filter($table, $data = array(), $filters_id_values = array()) {
        if ($filters_id_values) {
            $this->db->set($data);
            foreach ($filters_id_values as $filter) {
                $this->db->where($filter['id'], $filter['value']);
            }
            $query = $this->db->update($table);
        }
    }

    public function get_many_records_ism_tbl($table_name = '', $filters_id_values = "", $request_fields = "", $order_by = '') {
        $str = '';
        if ($request_fields) {
            foreach ($request_fields as $column_name) {
                $str .= $column_name . ', ';
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

    public function get_record_from_filter_ism_tbl($table_name, $request_fields = "", $filters_id_values) {
        $str = '';
        if ($request_fields) {
            foreach ($request_fields as $column_name) {
                $str .= $column_name . ', ';
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

        $query = $this->db->get($table_name);
        return $query->row_array();
    }

}

?>
