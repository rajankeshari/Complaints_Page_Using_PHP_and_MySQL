<?php

Class Main_model extends CI_Model {

    function __construct() {
        parent::__construct();
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
        //return $query->row_array();
    }
	public function get_records_from_id_emp($table_name, $request_fields = "", $id_name, $id_value) {
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

}

?>
