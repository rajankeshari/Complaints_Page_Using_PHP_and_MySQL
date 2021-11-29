<?php

class No_dues_due_model extends CI_Model{
	function __construct(){
		parent::__construct();
    }



    public function add_no_dues_due_type($data){

     $this->db->insert('no_dues_due_type', $data);

    }


      public function get_no_dues_due_type_list(){

        $sql = "select * from `no_dues_due_type` where `is_deleted` = 0";
        $query = $this->db->query($sql);
        return $query->result_array();


      }

      public function get_no_dues_due_type(){

        $sql = "select * from `no_dues_due_type` where `is_deleted` = 0 and `status` = 1";
        $query = $this->db->query($sql);
        return $query->result_array();


      }


      public function update_no_dues_due_type($data,$id)
      {

        $this->db->where('id', $id);
        $this->db->update('no_dues_due_type', $data);

      }


      public function delete_assigned_no_dues($id)
      {


        $sql = "select * from `no_dues_lists` where `id` = ?";
        $query = $this->db->query($sql,array($id));
        $previous_value = $query->result_array();


        $date = date('Y-m-d H:i:s');

        $data = array(

          'no_dues_id' => $previous_value[0]['id'],
          'previous_due_amount' => $previous_value[0]['due_amt'],
          'previous_due_list' => $previous_value[0]['due_list'],
          'previous_dues_remark' => $previous_value[0]['remarks'],
          //'previous_dues_remark' => $previous_value[0]['inventory_remark'],
          'is_deleted' => 1,
          'last_modified_by' => $previous_value[0]['last_modified_by'],
          'last_modified_date' => $date

            );


            $this->db->insert('no_dues_changed_logs',$data);


            //$date = date('Y-m-d H:i:s');

            $data = array(

              'no_dues_id' => $previous_value[0]['id'],
              'previous_due_amount' => $previous_value[0]['due_amt'],
              'previous_due_list' => $previous_value[0]['due_list'],
              'previous_dues_remark' => $previous_value[0]['remarks'],
              //'previous_dues_remark' => $previous_value[0]['inventory_remark'],
              'is_deleted' => 1,
              'last_modified_by' => $this->session->userdata('id'),
              'last_modified_date' => $date

                );


                $this->db->insert('no_dues_changed_logs',$data);

                $data = array(

                  'is_deleted' => 1,
                  'last_modified_by' => $this->session->userdata('id'),
                  'last_modified_date' => $date

              );

              $this->db->where('id', $id);
              $this->db->update('no_dues_lists', $data);



      }

      public function edit_no_dues($due_amount,$due_list,$remarks,$no_dues_id)
      {

        
        $date = date('d-m-Y H:i:s');
        $data = array(

            'due_amt' => $due_amount,
            'due_list' => $due_list,
            'remarks' => $remarks,
            //'inventory_remark' => $remarks,
            'last_modified_by' => $this->session->userdata('id'),
            'last_modified_date' => $date

    );

    $this->db->where('id', $no_dues_id);
    $this->db->update('no_dues_lists', $data);

      }

      public function insert_values_before_edit($pre_due_list,$pre_due_amount,$pre_remarks,$no_dues_id,$pre_added_by)
      {


        $date = date('Y-m-d H:i:s');


        $data = array(

        'no_dues_id' => $no_dues_id,
        'previous_due_amount' => $pre_due_amount,
        'previous_due_list' => $pre_due_list,
        
        'previous_dues_remark' => $pre_remarks,
        'is_deleted' => 0,
        'last_modified_by' => $pre_added_by,
        'last_modified_date' => $date

          );


          $this->db->insert('no_dues_changed_logs',$data);


      }

      public function get_due_type_name($due_type_id)
      {

         $sql = "select due_name from no_dues_due_type where due_id = ?";
         $query = $this->db->query($sql,array($due_type_id));
         $due_name = $query->result_array();
         return $due_name[0]['due_name'];

      }

      public function delete_no_dues_due_type($id)
      {

          $data = array(

            'is_deleted' => 1

          );

        $this->db->where('id', $id);
        $this->db->update('no_dues_due_type', $data);

      }

      public function get_all_no_dues_by_dept_pending($dept_id)
      {

       $sql = "select * from `no_dues_lists` where `imposed_dept_id` = ? and `pay_status` = 2 and `approv_reject_status_change` = 0";   // imposed_dept_id is to changed to student_dept_id
       $query = $this->db->query($sql,array($dept_id));

       return $query->result_array();

      }

      public function get_all_no_dues_by_dept_approved($dept_id)
      {

       $sql = "select * from `no_dues_lists` where `imposed_dept_id` = ? and `pay_status` = 2 and `approv_reject_status_change` = 1";   // imposed_dept_id is to changed to student_dept_id
       $query = $this->db->query($sql,array($dept_id));

       return $query->result_array();

      }

      public function get_all_no_dues_by_dept_rejected($dept_id)
      {

       $sql = "select * from `no_dues_lists` where `imposed_dept_id` = ? and `pay_status` = 2 and `approv_reject_status_change` = 2";   // imposed_dept_id is to changed to student_dept_id
       $query = $this->db->query($sql,array($dept_id));

       return $query->result_array();

      }

      public function get_all_no_dues_by_dept($dept_id)
      {


       $sql = "select * from `no_dues_lists` where `imposed_dept_id` = ? and `is_deleted` = 0 and `pay_status` = 0";   // imposed_dept_id is to changed to student_dept_id
       $query = $this->db->query($sql,array($dept_id));

       return $query->result_array();
       
      }

      public function get_all_no_dues()
      
      {


      }


      public function check_admn_no($admn_no,$course,$semester,$department)
      {

         

           $q = "SELECT user_details.id, first_name, middle_name, last_name, user_details.dept_id, semester, course_id ".
			          "FROM user_details ".
                "INNER JOIN stu_academic ".
                "ON user_details.id = stu_academic.admn_no ".
                // "WHERE user_details.id NOT IN (SELECT admn_no FROM no_dues_list) ".
                "WHERE  user_details.dept_id LIKE '$department' ".
                "AND stu_academic.semester LIKE '$semester' ".
                "AND stu_academic.course_id LIKE '$course'".
                "AND user_details.id = '$admn_no'"
                ;
	
          $res = $this->db->query($q);
          
         return $res->num_rows();

      }

      public function curr_time_stamp(){
        $curr_time = date_create();
        $ts = date_format($curr_time, 'Y-m-d H:i:s');
        return $ts;
      }

      public function get_session($ts){
        $year = explode('-', $ts)[0];
           $month=explode('-',$ts)[1];
           $m=strval((int)$month);
           if($m>=7&&$m<=12){  
               $p_year = strval((int)$year);  // 2019
               $year = $p_year +1;  // if month is greater than 6 then year will be present year + 1 
   
           }
           else
           $p_year = strval((int)$year - 1);  // if month is less than 6 then year will be present year will be  year - 1
           return $p_year.'-'.$year;
     }




      public function dynamic_entry($admn_no,$session_year,$imposed_from,$due_list,$due_amount,$remarks,$due_type,$department) {


        $session_error_count = 0;
        $due_type_error = 0;
        $no_dues_already_exits = array();


        $ts = $this->curr_time_stamp();
        $sess = $this->get_session($ts);

        if($session_year != $sess && $imposed_from != $due_type)

        {

          $session_error_count++;
          $due_type_error++;
           

        }


        elseif($imposed_from != $due_type)

        {


          $due_type_error++;


        }

        elseif($session_year != $sess)
        {

          $session_error_count++;

        }


        else

        {

          //$this->ndqm->


          $data = array(


            'admn_no' => $admn_no,
            'due_amount' => $due_amount,
            'due_list' => $due_list,
            'remarks' => $remarks,
            'session_year' => $session_year,
            'imposed_from' => $imposed_from,
            'department' => $department,
            'pay_status' => 0,
            

         );


         $duplicate_entry = $this->check_duplicate_data($data);


         $duplicate_array = $duplicate_entry['duplicate_data_array'];

         $duplicate_count = $duplicate_entry['duplicate_data_count'];


         if($duplicate_count > 1)
         {

             //print_r($duplicate_array);

             $duplicate_data_new = array();
             foreach($duplicate_array as $duplicate_data)
             {

                array_push($duplicate_data_new,$duplicate_data['id']);
             }

             $duplicate_data_store = "";
             $duplicate_data_store = implode('/',$duplicate_data_new);
             //echo "ssfsfsf".$duplicate_data_store;

             //exit;



             $data = array(


              'no_dues_list_id' => $duplicate_data_store,
              'admn_no' => $admn_no,
              'session_year' => $session_year,
              'due_amt' => $due_amount,
              'due_list' => $due_list,
              'remarks' => $remarks,
              'imposed_dept_id' => $department,
              'imposed_from' => $imposed_from

        );


         }

         else
         {
             $duplicate_entry_new  = $duplicate_array[0]['id'];
             $data = array(


              'no_dues_list_id' => $duplicate_entry_new,
              'admn_no' => $admn_no,
              'session_year' => $session_year,
              'due_amt' => $due_amount,
              'due_list' => $due_list,
              'remarks' => $remarks,
              'imposed_dept_id' => $department,
              'imposed_from' => $imposed_from

        );

         }


         $this->insert_duplicate_entry($data);

         array_push($no_dues_already_exits,$admn_no);

        }


        return array(

             'session_error_no_dues' => $session_error_count,
             'due_type_error_no_dues' => $due_type_error,
             'no_dues_already_exists' => $no_dues_already_exists


        );


      }


      public function check_duplicate_data($data)
{

    //print_r($data);

    $admn_no = $data['admn_no'];
    $due_amount = $data['due_amount'];
    $due_list = $data['due_list'];
    $remarks = $data['remarks'];
    $session_year = $data['session_year'];
    $imposed_from = $data['imposed_from'];
    $department = $data['department'];
    $pay_status = $data['pay_status'];
    


    if(count($this->check_bulk_duplicate($admn_no,$due_amount,$due_list,$remarks,$session_year,$imposed_from,$department,$pay_status)) > 0)
    {

           $duplicate_date_id = $this->check_bulk_duplicate($admn_no,$due_amount,$due_list,$remarks,$session_year,$imposed_from,$department,$pay_status);
           $count_duplicate_data = count($duplicate_date_id);
          

           return array(
             
            "duplicate_data_array" => $duplicate_date_id,

              "duplicate_data_count" => $count_duplicate_data

        );
    }

    else
    {


      $this->ndqm->update_dues_status_bulk($admn_no,$due_amount,$due_list,$remarks,$session_year,$imposed_from,$department);



    
      return array(
        
      "duplicate_data_array" => "success",

      "duplicate_data_count" => 0
    
    );

    }


  }


  public function check_bulk_duplicate($admn_no,$due_amount,$due_list,$remarks,$session_year,$department,$imposed_from,$pay_status)
  {

      $sql = "select * from no_dues_lists where admn_no = ? and due_amt = ? and due_list = ? and remarks = ? and session_year = ? and imposed_dept_id = ? and imposed_from = ? and pay_status = ?";
      $query = $this->db->query($sql,array($admn_no,$due_amount,$due_list,$remarks,$session_year,$department,$imposed_from,$pay_status));
      return $query->result_array();

  }


      public function change_due_type_status($due_type_id)
      {

        $sql = "select * from `no_dues_due_type` where id='".$due_type_id."' and `is_deleted` = 0";

        $query = $this->db->query($sql);
        $data = $query->result();
        //print_r($data);

        $status = $data['0']->status;

        //echo $status; exit;
        if($status == 0)
        {
            $status = 1;
        }
        else
        {

            $status = 0;

        }




        $date = date('d-m-Y h:i:s');

        $data = array(
            'status' => $status,
            'modified_on' => $date,
            'modified_by' => $this->session->userdata('id')
    );

        $this->db->where('id', $due_type_id);
        $this->db->update('no_dues_due_type', $data);

        //echo $this->db->last_query(); die();

        $sql = "select * from `no_dues_due_type` where id='".$due_type_id."' and `is_deleted` = 0";
        $query = $this->db->query($sql);
        //echo $this->db->last_query(); die();
        $data = $query->result();
        //print_r($data);

        $status = $data['0']->status;

        return $status;



      }

      public function fetch_no_dues_due_type_by_id($id)
      {

        $sql = "select * from `no_dues_due_type` where `id` = ? and `is_deleted` = 0";
        $query = $this->db->query($sql,array($id));
        return $query->result_array();


      }


}
    


    ?>