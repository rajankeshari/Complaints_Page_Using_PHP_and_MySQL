<?php

class Help_desk_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    public function get_many_records_with_like($table_name = '', $filters_id_values = "", $request_fields = "", $order_by = '', $likeCol = "", $likeVal = "") {
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
        if ($likeCol) {
            $this->db->like($likeCol, $likeVal);
        }

        if ($order_by) {
            $this->db->order_by($order_by);
        }

        $this->db->from($table_name);
        return $this->db->get()->result_array();
    }

}
