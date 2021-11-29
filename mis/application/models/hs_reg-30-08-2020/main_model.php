<?php

class Main_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    //--------------------------------------------------------------------------
    public function add_update_record($table, $data = array(), $id_name = '') {

        $this->db->set($data);


        if ($id_name) {

        if($table == "hs_student_allotment_list" || $table == "hs_assigned_student_room_temp")
        {

            $this->db->where("admn_no", $data[$id_name]);
            $query = $this->db->update($table);

            //return 'uodated';
            //echo $this->db->last_query(); die();
        }

        else

        {
            $this->db->where("id", $data[$id_name]);
            $query = $this->db->update($table);

        }


        }

        else {//adding record in table
            $query = $this->db->insert($table);
            //echo $this->db->last_query(); die();
        }


       //echo $this->db->last_query(); //die();


    }


    public function add_bulk_update_record($table, $data = array(), $id_name = '') {


        //echo 'entered'; exit;

        $this->db->set($data);
        if ($id_name) {
            $this->db->where("id", $id_name);
            $query = $this->db->update($table);
            //echo $this->db->last_query(); die();
        } else {//adding record in table
            $query = $this->db->insert($table);
            //echo $this->db->last_query(); die();
        }
    }

    public function check_in_allotment_list($table_name = '', $filters_id_values = "") {

        if ($filters_id_values) {
            foreach ($filters_id_values as $filter) {
                $this->db->where($filter['id'], $filter['value']);
            }
        }

        $this->db->from($table_name);

        $query = $this->db->get();

        //echo $count =  $query->num_rows(); exit;

        return $count =  $query->num_rows();



    }

    public function get_many_records_groupby($table_name = '', $filters_id_values = "", $request_fields = "", $order_by = '', $groupby = "") {

	// print_r($data);         die;

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
        $this->db->where("is_deleted", 0);
        if ($groupby) {
            $this->db->group_by($groupby);
        }


        if ($order_by) {
            $this->db->order_by($order_by);
        }

        $this->db->from($table_name);
        //print_r(data);         die;
        //$this->db->last_query(); die();
        return $this->db->get()->result_array();
    }

    public function get_many_records_assigned_out($table_name = '', $filters_id_values = "", $request_fields = "", $order_by = '') {
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


                //print_r($filter);
                $count = count($filter['value']);

                if($count==1){
                $this->db->where($filter['id'], $filter['value']);
                }

                else {

                    $this->db->where_in($filter['id'], $filter['value']);
                }
               // $this->db->where($filter['id'], $filter['value']);
            }
        }
        $this->db->where("is_deleted", 1);

        if ($order_by) {
            $this->db->order_by($order_by);
        }

        $this->db->from($table_name);
        

        //$this->db->get();

        //echo $this->db->last_query(); //die();

       return $this->db->get()->result_array();
    }




     public function get_many_records_by_parameter($table_name = '', $filters_id_values = "", $request_fields = "", $order_by = '', $parameter = '') {
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


                //print_r($filter);
                $count = count($filter['value']);

                if($count==1){
                $this->db->where($filter['id'], $filter['value']);
                }

                else {

                    $this->db->where_in($filter['id'], $filter['value']);
                }
               // $this->db->where($filter['id'], $filter['value']);
            }
        }
        $this->db->where("is_deleted", 0);

        if ($order_by && $parameter) {
            $this->db->order_by($order_by,$parameter);
        }

        

        $this->db->from($table_name);

        //$this->db->get();

        //echo $this->db->last_query(); //die();

        return $this->db->get()->result_array();
    }


    public function get_sessions()
    {
        $sql = "select * from `mis_session`";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function get_sessions_year()
    {
        $sql = "select * from `mis_session_year`";
        $query = $this->db->query($sql);
        return $query->result_array();
    }



    public function get_many_records($table_name = '', $filters_id_values = "", $request_fields = "", $order_by = '') {


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


                //print_r($filter);
                $count = count($filter['value']);

                if($count==1){
                $this->db->where($filter['id'], $filter['value']);
                }

                else {

                    $this->db->where_in($filter['id'], $filter['value']);
                }
               // $this->db->where($filter['id'], $filter['value']);
            }
        }
        $this->db->where("is_deleted", 0);

        if ($order_by) {
            $this->db->order_by($order_by);
        }

       
        $this->db->from($table_name);

        //$this->db->get();

        //echo $this->db->last_query(); //die();

        return $this->db->get()->result_array();
    }

    public function get_many_records_new($table_name = '', $filters_id_values = "", $request_fields = "", $order_by = '') {


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


                //print_r($filter);
                $count = count($filter['value']);

                if($count==1){
                $this->db->where($filter['id'], $filter['value']);
                }

                else {

                    $this->db->where_in($filter['id'], $filter['value']);
                }
               // $this->db->where($filter['id'], $filter['value']);
            }
        }
        $this->db->where("is_deleted", 0);

        if ($order_by) {
            $this->db->order_by($order_by);
        }

       
        $this->db->from($table_name);

        //$this->db->get();

        //echo $this->db->last_query(); //die();

        return $this->db->get()->result_array();
    }

    public function get_many_records_temp_hostel_assigned($table_name = '', $filters_id_values = "", $request_fields = "", $order_by = '') {
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


                //print_r($filter);
                $count = count($filter['value']);

                if($count==1){
                $this->db->where($filter['id'], $filter['value']);
                }

                else {

                    $this->db->where_in($filter['id'], $filter['value']);
                }
               // $this->db->where($filter['id'], $filter['value']);
            }
        }
        $this->db->where("is_deleted", 1);

        if ($order_by) {
            $this->db->order_by($order_by);
        }

        $this->db->from($table_name);

        //$this->db->get();

        //echo $this->db->last_query(); //die();

        return $this->db->get()->result_array();
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

    public function get_many_records_or($table_name = '', $filters_id_values = "", $request_fields = "", $order_by = '') {
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
                $this->db->or_where($filter['id'], $filter['value']);
            }
        }
        $this->db->where("is_deleted", 0);

        if ($order_by) {
            $this->db->order_by($order_by);
        }

        $this->db->from($table_name);
        return $this->db->get()->result_array();
    }


    public function get_record_from_filter_assigned_out($table_name, $request_fields = "", $filters_id_values) {

        //echo 'entered';
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
        $this->db->where("is_deleted", 1);
        $query = $this->db->get($table_name);

        //echo $this->db->last_query();

        if($query !== false)
       {
       return $query->row_array();
       }
       else
       {
        return array();
       }
        //echo $this->db->last_query(); //die();

       }

    public function get_record_from_filter($table_name, $request_fields = "", $filters_id_values) {

        //echo 'entered';
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
        $this->db->where("is_deleted", 0);
        $query = $this->db->get($table_name);

          //echo $this->db->last_query();

          if($query !== false)
         {
         return $query->row_array();
         }
         else
        {
         return array();
        }
          //echo $this->db->last_query(); //die();

        }

    public function lock($table){

       $sql = "LOCK TABLE $table WRITE";
       $this->db->query($sql);

       //echo $this->db->last_query();


    }

    public function unlock(){


       $sql = "UNLOCK TABLE";
       $this->db->query($sql);

       //echo $this->db->last_query();
              //exit();

    }

    public function get_room_detail_id($admn_no)
    {
      $sql = "select room_detail_id from hs_assigned_student_room where admn_no='$admn_no'";
      $query = $this->db->query($sql);
      $room_detail_id = $query->result_array();
      $room_detail_id['0']['room_detail_id'];

      return $room_detail_id['0']['room_detail_id'] ;
    }

    public function get_record_from_filter_ism_tbl_stu($table_name, $request_fields = "", $filters_id_values) {
        $str = '';
        if ($request_fields) {
            foreach ($request_fields as $column_name) {
                $str .= $column_name . ', ';
            }
        } else {
            $str = '*';
        }

        //print_r($filters_id_values);


        $this->db->select($str);
        if ($filters_id_values) {
            foreach ($filters_id_values as $filter) {

                $count = count($filter['value']);

                //echo $count; exit;

                if($count==1){
                $this->db->where($filter['id'], $filter['value']);
                }

                else {

                    $this->db->where_in($filter['id'], $filter['value']);
                }
                //$this->db->where($filter['id'], $filter['value']);
            }
        }

        $query = $this->db->get($table_name);

        //echo $this->db->last_query(); die();

        $count = $query->num_rows();

        if($count != '0'){
        return $query->result_array();

        }

        else {

            return '';
        }
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

                $count = count($filter['value']);

                //echo $count; exit;

                if($count==1){
                $this->db->where($filter['id'], $filter['value']);
                }

                else {

                    $this->db->where_in($filter['id'], $filter['value']);
                }
                //$this->db->where($filter['id'], $filter['value']);
            }
        }

        $query = $this->db->get($table_name);

        //echo $this->db->last_query(); die();

        $count = $query->num_rows();

        if($count != '0'){
        return $query->result_array();

        }

        else {

            return '';
        }
    }

    public function get_record_from_filter_ism_allotment_tbl($table_name, $request_fields = "", $filters_id_values) {
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

        //$query = $this->db->get($table_name);

        //echo $this->db->last_query(); exit;

        $this->db->from($table_name);

        $query = $this->db->get();

        //echo $this->db->last_query(); exit;

        /*
        $count = $query->num_rows();

        if($count != '0'){ */
        return $query->result_array();

      /*  }

        else {

            return '';
        }*/
    }


    public function get_many_records_from_two_or_more_joins($tableOne, $tableOneColumn, $tableTwo, $tableTwoColumn, $tableThree, $tableThreeColumn,$filters_id_values) {

        if ($req_data) {
            $this->db->select($req_data);
        } else {
            $this->db->select('*');
        }


        $this->db->from("$tableOne a");
        $this->db->join("$tableTwo b", "b.$tableTwoColumn=a.$tableOneColumn", "inner");
        $this->db->join("$tableThree c", "c.$tableTwoColumn=a.$tableOneColumn", "inner");
        if ($filters_id_values) {
            foreach ($filters_id_values as $filter) {

                //filter should be Table combination LIKE   $this->db->where("b.enrollment_year", $id);

                //print_r($filter);
                $count = count($filter['value']);

                if($count == 1)

                {

                $this->db->where($filter['id'], $filter['value']);

                }

                else {

                    $this->db->where_in($filter['id'], $filter['value']);
                }
            }
        }

       /* if ($orderBy) {//filter should be Table combination LIKE    $this->db->order_by('c.track_title', 'asc');
            $this->db->order_by($orderBy);
        }*/

        $this->db->get();

        echo $this->db->last_query(); //die();

        //return $this->db->get()->result_array();


    }

    public function get_records_from_inner_join_from_admn_no($filter4)
    {
         $admn_no = $filter4[0]['value'];
         $session = $filter4[1]['value'];
         $session_year = $filter4[2]['value'];
         $hostel_name = $filter4[3]['value'];

        // //print_r($filter); 
        // exit;
        /*$sql = "SELECT * FROM `hs_student_allotment_list` a
        WHERE a.admn_no IN ( SELECT b.admn_no
        FROM reg_regular_form b
        INNER JOIN reg_regular_fee c
        on c.admn_no = b.admn_no AND b.session='$session' AND b.session_year='$session_year') and hostel_name='$hostel_name' and a.admn_no='$admn_no'"; */

        $sql = "SELECT b.admn_no , c.receipt_path
        FROM reg_regular_form b
        INNER JOIN reg_regular_fee c ON c.form_id = b.form_id AND b.session='$session' AND b.session_year='$session_year' AND b.admn_no IN (
        SELECT distinct a.admn_no
        FROM `hs_student_allotment_list` a
        WHERE a.hostel_name='$hostel_name' and a.admn_no='$admn_no')";

        //exit;
        $query = $this->db->query($sql);
        //echo $this->db->last_query(); die();


        return $query->result_array();

    }

    public function get_records_from_inner_join_admn_no($session,$session_year,$admn_no,$hostel_name)
    {
        //  $hostel_name = $filter[0]['value'];
        //  $session = $filter[1]['value'];
        //  $session_year = $filter[2]['value'];

        // print_r($filter); 
        // exit;
        echo $sql = "SELECT b.admn_no
        FROM reg_regular_form b
        INNER JOIN reg_regular_fee c
        on c.admn_no = b.admn_no AND b.session='$session' AND b.session_year='$session_year' and b.admn_no='$admn_no'";

        exit;
        $query = $this->db->query($sql);
        return $query->result_array();

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
            foreach ($filters_id_values as $filter) {

                //filter should be Table combination LIKE   $this->db->where("b.enrollment_year", $id);

                //print_r($filter);
                $count = count($filter['value']);

                if($count == 1)

                {

                $this->db->where($filter['id'], $filter['value']);

                }

                else {

                    $this->db->where_in($filter['id'], $filter['value']);
                }
            }
        }

        if ($orderBy) {//filter should be Table combination LIKE    $this->db->order_by('c.track_title', 'asc');
            $this->db->order_by($orderBy);
        }

        //$this->db->get();

        //echo $this->db->last_query(); die();

        return $this->db->get()->result_array();


    }

    public function get_empty_room_old($hostel_detail_id) {//old approch
        $query = "select a.* from hs_room_details as a left join hs_assigned_student_room  as b on b.room_detail_id = a.id where b.id is null and a.is_deleted = 0 and a.`hostel_detail_id` =?";
        $result_query = $this->db->query($query, array($hostel_detail_id));
        return $result_query->result_array();
    }

    public function available_seat_old($hostel_detail_id, $seat) {//old approch
        $query = "select count(a.id) as avaiable_seat  from hs_room_details as a left join hs_assigned_student_room  as b on b.room_detail_id = a.id where b.id is null and a.is_deleted = 0 and a.no_of_bed=? and  a.`hostel_detail_id`=?";
        $result_query = $this->db->query($query, array($seat, $hostel_detail_id));
        return $result_query->result_array();
    }

    public function get_empty_room($hostel_name = "") {
        if (!empty($hostel_name)) {

            //print_r($hostel_name);
            $count = count($hostel_name);



            //print_r(implode(",",'".$hostel_name."'));

            if($count > 1) {

            $this->db->select("a.*");
            $this->db->from("hs_room_details a");
            $this->db->join("hs_assigned_student_room b", "b.room_detail_id=a.id", "left");
            $this->db->join("hs_hostel_details c", "c.id = a.hostel_detail_id", "left");
            $this->db->where("b.id",null);
            $this->db->where("a.is_deleted","0");
            $this->db->where_in("c.`hostel_name`", $hostel_name);
            //$this->db->where_in($hostel_name);
            $result_query = $this->db->get();


            //b.id is null and a.is_deleted = 0 and c.`hostel_name` IN".$hostel_name;


            //$query = "select a.* from hs_room_details as a left join hs_assigned_student_room  as b on b.room_detail_id = a.id left join hs_hostel_details  as c on c.id = a.hostel_detail_id where b.id is null and a.is_deleted = 0 and c.`hostel_name` IN".$hostel_name;

            //$result_query = $this->db->query($query);

        }

            else {

             $query = "select a.* from hs_room_details as a left join hs_assigned_student_room  as b on b.room_detail_id = a.id left join hs_hostel_details  as c on c.id = a.hostel_detail_id where b.id is null and a.is_deleted = 0 and c.`hostel_name` = ?";
             $result_query = $this->db->query($query, array($hostel_name));

            }


            //
            //echo $this->db->last_query(); die();

            //exit;
        } else {

            $query = "select a.* from hs_room_details as a left join hs_assigned_student_room  as b on b.room_detail_id = a.id left join hs_hostel_details  as c on c.id = a.hostel_detail_id where b.id is null and a.is_deleted = 0";
            $result_query = $this->db->query($query);
        }

       // echo $this->db->last_query(); die();

        //print_r($result_query->result_array()); exit;

        return $result_query->result_array();
    }



    public function get_empty_room_temp($hostel_name = "") {
        if (!empty($hostel_name)) {

            //print_r($hostel_name);
            $count = count($hostel_name);



            //print_r(implode(",",'".$hostel_name."'));

            if($count > 1) {

            $this->db->select("a.*");
            $this->db->from("hs_room_details a");
            $this->db->join("hs_assigned_student_room_temp b", "b.room_detail_id=a.id", "left");
            $this->db->join("hs_hostel_details c", "c.id = a.hostel_detail_id", "left");
            $this->db->where("b.id",null);
            $this->db->where("a.is_deleted","0");
            $this->db->where_in("c.`hostel_name`", $hostel_name);
            //$this->db->where_in($hostel_name);
            $result_query = $this->db->get();


            //b.id is null and a.is_deleted = 0 and c.`hostel_name` IN".$hostel_name;


            //$query = "select a.* from hs_room_details as a left join hs_assigned_student_room  as b on b.room_detail_id = a.id left join hs_hostel_details  as c on c.id = a.hostel_detail_id where b.id is null and a.is_deleted = 0 and c.`hostel_name` IN".$hostel_name;

            //$result_query = $this->db->query($query);

        }

            else {

             $query = "select a.* from hs_room_details as a left join hs_assigned_student_room_temp  as b on b.room_detail_id = a.id left join hs_hostel_details  as c on c.id = a.hostel_detail_id where b.id is null and a.is_deleted = 0 and c.`hostel_name` = ?";
             $result_query = $this->db->query($query, array($hostel_name));

            }


            //
            //echo $this->db->last_query(); die();

            //exit;
        } else {

            $query = "select a.* from hs_room_details as a left join hs_assigned_student_room_temp  as b on b.room_detail_id = a.id left join hs_hostel_details  as c on c.id = a.hostel_detail_id where b.id is null and a.is_deleted = 0";
            $result_query = $this->db->query($query);
        }

        //echo $this->db->last_query(); die();

        //print_r($result_query->result_array()); exit;

        return $result_query->result_array();
    }

    public function available_seat($hostel_name, $seat) {
        $query = "select count(a.id) as avaiable_seat  from hs_room_details as a left join hs_assigned_student_room_temp  as b on b.room_detail_id = a.id left join hs_hostel_details  as c on c.id = a.hostel_detail_id where b.id is null and a.is_deleted = 0 and a.no_of_bed=? and  c.`hostel_name`=?";
        $result_query = $this->db->query($query, array($seat, $hostel_name));
        //echo $this->db->last_query();//die();
        return $result_query->result_array();
    }

    public function get_allocated_room_groupBy() {
        $query = "select a.room_detail_id from hs_assigned_student_room  as a where a.`status` = 'in' group by a.room_detail_id";
        $result_query = $this->db->query($query);
        return $result_query->result_array();
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

    public function get_student_full_details($hostel_name = "") {
        if (!empty($hostel_name)) {



            $count = count($hostel_name);
            //echo $count; die();
            if($count > 1){

            $this->db->select("c.hostel_name,c.block_name,b.floor,b.room_name,b.no_of_bed,a.admn_no,a.entry_datetime,
                               d.dept_id,f.name as dept_name,e.auth_id,e.course_id,g.name as course_name,e.branch_id,h.name as branch_name,
                               e.semester, d.salutation,d.first_name,d.middle_name,d.last_name,d.photopath,k.line1,k.line2,k.city,k.state,k.pincode,k.country,l.father_name");
            $this->db->from("hs_assigned_student_room a");
            $this->db->join("hs_room_details b", "b.id = a.room_detail_id", "inner");
            $this->db->join("hs_hostel_details c", "c.id = b.hostel_detail_id", "inner");
            $this->db->join("user_details d", "d.id = a.admn_no", "left");
            $this->db->join("stu_details i", "i.admn_no = a.admn_no", "left"); // stu_details
            $this->db->join("stu_other_details j", "j.admn_no = a.admn_no", "left");
            $this->db->join("user_address k", "k.id = a.admn_no", "left");
            $this->db->join("user_other_details l", "l.id = a.admn_no", "left");
            $this->db->join("stu_academic e", "e.admn_no = a.admn_no", "left");
            $this->db->join("departments f", "f.id = d.dept_id", "left");
            $this->db->join("courses g", "g.id = e.course_id", "left");
            $this->db->join("branches h", "h.id = e.branch_id", "left");
            //$this->db->where("b.id",null);
            $this->db->where("a.`type`","Student");
            $this->db->where_in("c.`hostel_name`", $hostel_name);
            $this->db->group_by("a.`admn_no`");
            //$this->db->where_in($hostel_name);
            $result_query = $this->db->get();

            //echo $this->db->last_query();die();

            //$result_query = $this->db->query($query, array($hostel_name));
            }

            else {

                $query = "select c.hostel_name,c.block_name,b.floor,b.room_name,b.no_of_bed,a.admn_no,a.entry_datetime,
d.dept_id,f.name as dept_name, e.course_id,g.name as course_name,e.branch_id,h.name as branch_name,
e.semester, d.salutation,d.first_name,d.middle_name,d.last_name,d.photopath,k.line1,k.line2,k.city,k.state,k.pincode,k.country,
l.father_name
from hs_assigned_student_room as a
inner join hs_room_details as b on b.id = a.room_detail_id
inner join hs_hostel_details as c on c.id = b.hostel_detail_id
left join user_details as d on d.id = a.admn_no
left join stu_details as i on i.admn_no = a.admn_no
left join stu_other_details as j on j.admn_no = a.admn_no
left join user_address as k on k.id = a.admn_no
left join user_other_details as l on l.id = a.admn_no
left join stu_academic as e on e.admn_no = a.admn_no
left join departments as f on f.id = d.dept_id
left join courses as g on g.id = e.course_id
left join branches as h on h.id = e.branch_id
where a.`type` = 'Student' and c.hostel_name=? GROUP BY a.admn_no";

                $result_query = $this->db->query($query, array($hostel_name));


            }




            } else {


           $query = "select c.hostel_name,c.block_name,b.floor,b.room_name,b.no_of_bed,a.admn_no,a.entry_datetime,
d.dept_id,f.name as dept_name, e.course_id,g.name as course_name,e.branch_id,h.name as branch_name,
e.semester, d.salutation,d.first_name,d.middle_name,d.last_name,d.photopath,k.line1,k.line2,k.city,k.state,k.pincode,k.country,
l.father_name
from hs_assigned_student_room as a
inner join hs_room_details as b on b.id = a.room_detail_id
inner join hs_hostel_details as c on c.id = b.hostel_detail_id
left join user_details as d on d.id = a.admn_no
left join stu_details as i on i.admn_no = a.admn_no
left join stu_other_details as j on j.admn_no = a.admn_no
left join user_address as k on k.id = a.admn_no
left join user_other_details as l on l.id = a.admn_no
left join stu_academic as e on e.admn_no = a.admn_no
left join departments as f on f.id = d.dept_id
left join courses as g on g.id = e.course_id
left join branches as h on h.id = e.branch_id
where a.`type` = 'Student' GROUP BY a.admn_no";




           $result_query = $this->db->query($query);
        }

       //echo $this->db->last_query(); die();

        return $result_query->result_array();
    }

    public function get_guest_full_details($hostel_name = "") {
        if (!empty($hostel_name)) {
            $query = "select c.hostel_name,c.block_name,b.floor,b.room_name,a.entry_datetime,d.name,d.mobile,d.email
from hs_assigned_student_room as a
inner join hs_room_details as b on b.id = a.room_detail_id
inner join hs_hostel_details as c on c.id = b.hostel_detail_id
inner join hs_guest_contact as d on d.id = a.admn_no
where a.type = 'Guest' and c.hostel_name=?";
            $result_query = $this->db->query($query, array($hostel_name));
        } else {
            $query = "select c.hostel_name,c.block_name,b.floor,b.room_name,a.entry_datetime,d.name,d.mobile,d.email
from hs_assigned_student_room as a
inner join hs_room_details as b on b.id = a.room_detail_id
inner join hs_hostel_details as c on c.id = b.hostel_detail_id
inner join hs_guest_contact as d on d.id = a.admn_no
where a.type = 'Guest'";
            $result_query = $this->db->query($query);
        }

        return $result_query->result_array();
    }

    public function delete_record($tbl, $id, $value) {
        $this->db->set('is_deleted ', 1);
        $this->db->where($id, $value);
        $this->db->update($tbl);
    }


    public function select_hostel_ajax($table, $order_by = "name", $value = "") {

        $this->db->select("name");
        $this->db->from($table);
        $this->db->where("is_deleted", 0);
        if ($order_by) {
            $this->db->order_by($order_by);
        }

        //$this->db->get();

        //echo $this->db->last_query(); exit;

        $return_data = $this->db->get()->result_array();

        return $return_data;

        //print_r($return_data); exit;

    }


    public function get_assigned_room_status($room_detail_id)
    {


            $sql  = "select * from `hs_assigned_student_room` where `room_detail_id` = '".$room_detail_id."'";

            $query = $this->db->query($sql);

             if ($query->num_rows() == 0)
             {
                 return 'Vacant';
             }

             else
             {

                return 'Occupied';
             }

          // echo $this->db->last_query(); die();
    }




    public function select_hostel_data($table, $order_by = "name", $value = "") {

        $this->db->select("name");
        $this->db->from($table);
        $this->db->where("is_deleted", 0);
        if ($order_by) {
            $this->db->order_by($order_by);
        }
        $return_data = $this->db->get()->result_array();
        $opt_str = "";
        if (!$value) {
            $opt_str .= '<option selected="selected" value=""> Select  Value </option>';
        } else {
            $opt_str .= '<option value=""> Select  Value </option>';
        }
        if (!empty($return_data)) {
            foreach ($return_data as $return_data_value) {

                if (!empty($return_data_value["name"])) {
                    $opt_str .= '<option ';
                    if (($value) && ($value == $return_data_value["name"])) {
                        $opt_str .= 'selected="selected" ';
                    }
                    $opt_str .= 'value="' . $return_data_value["name"] . '">' . $return_data_value["name"] . '</option>';
                }
            }
        }
        return $opt_str;
    }

    public function select_block_data($table, $order_by = "", $value = "") {

        $this->db->select(array("id", "block_name"));
        $this->db->from($table);
        $this->db->where("is_deleted", 0);
        if ($order_by) {
            $this->db->order_by($order_by);
        }
        $this->db->group_by("block_name");
        $return_data = $this->db->get()->result_array();
        $opt_str = "";
        if (!$value) {
            $opt_str .= '<option selected="selected" value=""> Select  Value </option>';
        } else {
            $opt_str .= '<option value=""> Select  Value </option>';
        }
        if (!empty($return_data)) {
            foreach ($return_data as $return_data_value) {

                if (!empty($return_data_value["block_name"])) {
                    $opt_str .= '<option ';
                    if (($value) && ($value == $return_data_value["block_name"])) {
                        $opt_str .= 'selected="selected" ';
                    }
                    $opt_str .= 'value="' . $return_data_value["id"] . '">' . $return_data_value["block_name"] . '</option>';
                }
            }
        }
        return $opt_str;
    }

    public function fill_selected_block($value = "") {

        $opt_str = "";
        $str[1] = 'A';
        $str[2] = 'B';
        $str[3] = 'C';
        $str[4] = 'D';
        $str[5] = 'E';
        $str[6] = 'Annexe';
		$str[7] = 'N/A';


        if (!$value) {
            $opt_str .= '<option selected="selected" value=""> Select Block No.  </option>';
        } else {
            $opt_str .= '<option value=""> Select Block No.  </option>';
        }

        foreach ($str as $block) {
            $opt_str .= '<option ';
            if (($value) && ($value == $block)) {
                $opt_str .= 'selected="selected" ';
            }
            $opt_str .= 'value="' . $block . '">' . $block . '</option>';
        }
        return $opt_str;
    }

    public function fill_selected_semester($value = "") {

        $opt_str = "";
        $str[1] = '1';
        $str[2] = '2';
        $str[3] = '3';
        $str[4] = '4';
        $str[5] = '5';
        $str[6] = '6';
        $str[7] = '7';
        $str[8] = '8';
        $str[9] = '9';
        $str[10] = '10';

        if (!$value) {
            $opt_str .= '<option selected="selected" value=""> Select value  </option>';
        } else {
            $opt_str .= '<option value=""> Select value  </option>';
        }

        foreach ($str as $block) {
            $opt_str .= '<option ';
            if (($value) && ($value == $block)) {
                $opt_str .= 'selected="selected" ';
            }
            $opt_str .= 'value="' . $block . '">' . $block . '</option>';
        }
        return $opt_str;
    }

    public function delete($table, $data) {
        $this->db->delete($table, $data);

        //echo $this->db->last_query(); //die();
    }

     public function delete_now($table, $data) {
        $this->db->delete($table, $data);

        //echo $this->db->last_query(); //die();
    }

     public function delete_next($table, $data) {
        $this->db->delete($table, $data);

        //echo $this->db->last_query(); die();
    }

    public function fill_data($table, $order_by = "", $value = "") {

        $this->db->select("name");
        $this->db->from($table);
        if ($order_by) {
            $this->db->order_by($order_by);
        }
        $return_data = $this->db->get()->result_array();
        $opt_str = "";
        if (!$value) {
            $opt_str .= '<option selected="selected" value=""> Select  Value </option>';
        } else {
            $opt_str .= '<option value=""> Select  Value </option>';
        }
        if (!empty($return_data)) {
            foreach ($return_data as $return_data_value) {

                if (!empty($return_data_value["name"])) {
                    $opt_str .= '<option ';
                    if (($value) && ($value == $return_data_value["name"])) {
                        $opt_str .= 'selected="selected" ';
                    }
                    $opt_str .= 'value="' . $return_data_value["name"] . '">' . $return_data_value["name"] . '</option>';
                }
            }
        }
        return $opt_str;
    }

    //--------------------------------------------------------------------------

    public function delete_many_records($table_name, $filters_id_values = "") {
        if ($filters_id_values) {
            foreach ($filters_id_values as $filter) {
                $this->db->where($filter['id'], $filter['value']);
            }
        }
        $this->db->delete($table_name);
    }

    public function get_records_groupby_from_two_join_two_column($tableOne, $tableOneColumn, $tableOneColumnTwo, $tableTwo, $tableTwoColumn, $tabletwoColumnTwo, $filters_id_values, $orderBy = "", $req_data = "", $groupWiseColumn = "") {

        if ($req_data) {
            $this->db->select($req_data);
        } else {
            $this->db->select('*');
        }


        $this->db->from("$tableOne a");
        $this->db->join("$tableTwo b", "b.$tableTwoColumn=a.$tableOneColumn and b.$tabletwoColumnTwo=a.$tableOneColumnTwo", "inner");
        if ($filters_id_values) {
            foreach ($filters_id_values as $filter) {//filter should be Table combination LIKE   $this->db->where("b.enrollment_year", $id);
                $this->db->where($filter['id'], $filter['value']);
            }
        }

        if ($orderBy) {//filter should be Table combination LIKE  $this->db->order_by('c.track_title', 'asc');
            $this->db->order_by($orderBy);
        }
        if ($groupWiseColumn) {
            $this->db->group_by($groupWiseColumn);
        }

        return $this->db->get()->result_array();
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

}

?>
