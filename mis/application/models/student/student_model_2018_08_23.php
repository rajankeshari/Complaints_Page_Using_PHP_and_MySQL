<?php

class Student_model extends CI_Model {

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

    public function fill_selected_hostel($value = "") {

        $opt_str = "";
        $str[1] = 'Amber';
        $str[2] = 'Diamond';
		$str[3] = 'International';
        $str[4] = 'Emerald';
        $str[5] = 'Jasper';
        $str[6] = 'Opal';
        $str[7] = 'Rosaline';
        $str[8] = 'Ruby';
		$str[9] = 'Ruby Annexe';
        $str[10] = 'Sapphire';
        $str[11] = 'Shanti Bhavan';
        $str[12] = 'Topaz';
        $str[13] = 'N/A';

        if (!$value) {
            $opt_str.='<option selected="selected" value=""> Select Hostel Name</option>';
        } else {
            $opt_str.='<option value=""> Select Hostel Name</option>';
        }

        foreach ($str as $hostel) {
            $opt_str.='<option ';
            if (($value) && ($value == $hostel)) {
                $opt_str.='selected="selected" ';
            }
            $opt_str.='value="' . $hostel . '">' . $hostel . '</option>';
        }
        return $opt_str;
    }

    public function fill_selected_block($value = "") {

        $opt_str = "";
        $str[1] = 'A/I';
        $str[2] = 'B/II';
        $str[3] = 'C/III';
        $str[4] = 'D/IV';
        $str[5] = 'E/V';
        $str[6] = 'F/VI';
        $str[7] = 'G/VII';
        $str[8] = 'H/VIII';
        $str[9] = 'N/A';

        if (!$value) {
            $opt_str.='<option selected="selected" value=""> Select Block No.  </option>';
        } else {
            $opt_str.='<option value=""> Select Block No.  </option>';
        }

        foreach ($str as $block) {
            $opt_str.='<option ';
            if (($value) && ($value == $block)) {
                $opt_str.='selected="selected" ';
            }
            $opt_str.='value="' . $block . '">' . $block . '</option>';
        }
        return $opt_str;
    }

    public function fill_selected_floor($value = "") {

        $opt_str = "";
        $str[1] = '0';
        $str[2] = '1';
        $str[3] = '2';
        $str[4] = '3';
        $str[5] = '4';
        $str[6] = '5';
        $str[7] = '6';
        $str[8] = '7';
        $str[9] = '8';
        $str[10] = '9';
        $str[11] = '10';



        if (!$value) {
            $opt_str.='<option selected="selected" value=""> Select Floor No.  </option>';
        } else {
            $opt_str.='<option value=""> Select Floor No.  </option>';
        }

        foreach ($str as $floor) {
            $opt_str.='<option ';
            if (($value) && ($value == $floor)) {
                $opt_str.='selected="selected" ';
            }
            $opt_str.='value="' . $floor . '">' . $floor . '</option>';
        }
        return $opt_str;
    }

    public function get_many_records($table_name = '', $filters_id_values, $request_fields = "", $order_by = '') {
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
