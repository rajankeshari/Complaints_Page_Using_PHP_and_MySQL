<?php

class No_dues_due_model extends CI_Model{
	function __construct(){
		parent::__construct();
    }



    public function add_no_dues_due_type($data){

     $this->db->insert('no_dues_due_type', $data);

    }


    public function get_auth_types()
    {

        $sql = "select * from `auth_types`";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

   


      public function get_no_dues_due_type_list(){

        $sql = "select * from `no_dues_due_type` where `is_deleted` = 0";
        $query = $this->db->query($sql);
        return $query->result_array();


      }

      // public function update_bulk_status($temp_hs_details_id)
      // {

      //   $this->db->where('id', $temp_hs_details_id);
      //   $this->db->delete('no_dues_lists_temporary');

      //       // $this->db->where('id', $temp_hs_details_id);
      //       // $this->db->update('no_dues_hs_temporary_details', $data);
      //       //echo $this->db->last_query();die();

      // }

      public function update_hs_details($id)
      {


        $data = array(

            'is_deleted' => 1,
            'last_modified_by' => $this->session->userdata('id'),
            'last_modified_date' => date('d-m-Y H:i:s')
        );


        // $this->db->where('hostel_no_dues_id', $hostel_no_dues_id);
        // $this->db->update('no_dues_hs_individual', $data);

        $this->db->where('id', $id);
        $this->db->update('no_dues_lists', $data);

        // $this->db->where('hs_no_dues_id', $hostel_no_dues_id);
        // $this->db->update('no_dues_hs_payment', $data);

      }

      public function get_no_dues_due_type(){

        $sql = "select * from `no_dues_due_type` where `is_deleted` = 0 and `status` = 1 order by due_name asc";
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

       $sql = "select * from `no_dues_lists` where `imposed_dept_id` = ? and `pay_status` = 2 and `approv_reject_status_change` = 1";   // imposed_dept_id is to changed to student_dept_id
       $query = $this->db->query($sql,array($dept_id));
       if($query != false) 
       {
       return $query->result_array();
       }
       else
       {
         return false;
       }

      }


      public function get_all_no_dues_by_assign_dept_pending($array_dues_list)
      {
  
        $comma_separated = implode("','",$array_dues_list);
        

          //echo $comma_separated; exit;

          $sql = "select * from `no_dues_lists` where `pay_status` = 2 and `approv_reject_status_change` = 1 and `imposed_from` IN ('" . $comma_separated . "')";  // imposed_dept_id is to changed to student_dept_id
          $query = $this->db->query($sql);
          //echo $this->db->last_query();die();

          if($query != false) 
          {
          return $query->result_array();
          }
          else
          {
            return false;
          }


  
         
      }

      public function get_all_no_dues_by_dept_approved($dept_id)
      {

       $sql = "select * from `no_dues_lists` where `imposed_dept_id` = ? and `pay_status` = 2 and `approv_reject_status_change` = 2";   // imposed_dept_id is to changed to student_dept_id
       $query = $this->db->query($sql,array($dept_id));
       if($query != false)
       {
       return $query->result_array();
       }
       else
       {
          return false;
       }

      }


      public function get_all_no_dues_by_assign_dept_approved($array_dues_list)
      {
  
        $comma_separated = implode("','",$array_dues_list);
        

          //echo $comma_separated; exit;

          $sql = "select * from `no_dues_lists` where `pay_status` = 2 and `approv_reject_status_change` = 2 and `imposed_from` IN ('" . $comma_separated . "')";  // imposed_dept_id is to changed to student_dept_id
          $query = $this->db->query($sql);
          //echo $this->db->last_query();die();

          if($query != false) 
          {
          return $query->result_array();
          }
          else
          {
            return false;
          }


  
         
      }

      public function get_all_no_dues_by_dept_rejected($dept_id)
      {

       $sql = "select * from `no_dues_lists` where `imposed_dept_id` = ? and `pay_status` = 2 and `approv_reject_status_change` = 3";   // imposed_dept_id is to changed to student_dept_id
       $query = $this->db->query($sql,array($dept_id));
       if($query != false)
       {
       return $query->result_array();
       }
       else
       {
         return false;
       }

      }

      public function get_all_no_dues_by_assign_dept_rejected($array_dues_list)
      {
  
        $comma_separated = implode("','",$array_dues_list);
        

          //echo $comma_separated; exit;

          $sql = "select * from `no_dues_lists` where `pay_status` = 2 and `approv_reject_status_change` = 3 and `imposed_from` IN ('" . $comma_separated . "')";  // imposed_dept_id is to changed to student_dept_id
          $query = $this->db->query($sql);
          //echo $this->db->last_query();die();

          if($query != false) 
          {
          return $query->result_array();
          }
          else
          {
            return false;
          }


  
         
      }


      public function get_all_no_dues_by_dept($dept_id)
      {


       $sql = "select * from `no_dues_lists` where `imposed_dept_id` = ? and `is_deleted` = 0 and `pay_status` = 0";   // imposed_dept_id is to changed to student_dept_id
       $query = $this->db->query($sql,array($dept_id));
       if($query != false)
       {
       return $query->result_array();
       }

       else
       {
         return false;
       }
       
      }

      public function get_all_no_dues()
      
      {


       $sql = "select * from `no_dues_lists` where `is_deleted` = 0 and `pay_status` = 0";   // imposed_dept_id is to changed to student_dept_id
       $query = $this->db->query($sql);
       if($query != false)
       {
       return $query->result_array();
       }
       else
       {
         return false;
       }


      }

      public function get_all_no_dues_rejected()
      
      {


       $sql = "select * from `no_dues_lists` where `is_deleted` = 0 and `pay_status` IN (0,2) AND `approv_reject_status_change` IN (0,3)";   // imposed_dept_id is to changed to student_dept_id
       $query = $this->db->query($sql);
       //echo $this->db->last_query(); die();
       if($query != false)
       {
       return $query->result_array();
       }
       else
       {
         return false;
       }


      }


      public function check_admn_no($admn_no,$course,$semester,$department,$session_year)
      {

         

           $q = "SELECT user_details.id, first_name, middle_name, last_name, user_details.dept_id, stu_academic.semester, stu_academic.course_id ".
			          "FROM user_details ".
                "INNER JOIN stu_academic ".
                "ON user_details.id = stu_academic.admn_no ".
                "INNER JOIN reg_regular_form ".
			          "ON reg_regular_form.admn_no = stu_academic.admn_no ".
                // "WHERE user_details.id NOT IN (SELECT admn_no FROM no_dues_list) ".
                "WHERE  user_details.dept_id LIKE '$department' ".
                "AND stu_academic.semester LIKE '$semester' ".
                "AND stu_academic.course_id LIKE '$course'".
                "AND reg_regular_form.session_year LIKE '$session_year' ".
                "AND user_details.id = '$admn_no' ".
                "GROUP BY reg_regular_form.admn_no";
                
	
          $res = $this->db->query($q);

          //echo $this->db->last_query(); die();
          
          if($res != false)
          {
          return $res->result_array();
          }
          else
          {
            return false;
          }
   

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




      public function dynamic_entry($admn_no,$session_year,$imposed_from,$due_list,$due_amount,$remarks,$due_type,$department,$session_year_match) {


        $session_error_count = 0;
        $due_type_error = 0;
        $amount_error = 0;
        $admn_no_already_exists = 0;


        $ts = $this->curr_time_stamp();
        $sess = $this->get_session($ts);

        // if($session_year != $sess && $imposed_from != $due_type)

        // {

        //   $session_error_count++;
        //   $due_type_error++;
           

        // }


        if($imposed_from != $due_type)

        {


          $due_type_error++;


        }

        if($session_year != $session_year_match)
        {

          $session_error_count++;

        }

        if($due_amount == 0 || is_numeric($due_amount) == false) {

          $amount_error++;
          //array_push($invalid_quantity,trim($inventory_name));
          //array_unshift($description_new,"testing");


      }


        if($due_type_error == 0 && $session_error_count == 0 && $amount_error == 0) 

        {

          //$this->ndqm->

          // detect duplicate entries in no_dues_lists : callback function duplicate_entry_no_dues_lists()
          $get_the_count_for_duplicate_rows = $this->check_duplicate_no_dues_list_entries($admn_no,$due_amount,$due_list,$remarks,$session_year,$imposed_from,$department);
          $get_no_dues_lists_id_for_duplicate_data = $this->get_id_duplicate_no_dues_list_entries($admn_no,$due_amount,$due_list,$remarks,$session_year,$imposed_from,$department);
          //print_r($get_no_dues_lists_id_for_duplicate_data); exit;
         
         
          if ($get_the_count_for_duplicate_rows > 1) {
            
               $id_duplicate_data = array();
               foreach ($get_no_dues_lists_id_for_duplicate_data as $value) {
                
                     array_push($id_duplicate_data, $value['id']);
                    
               }

               $no_dues_list_id = implode('/', $id_duplicate_data);
          }

          else {
            # code...
            $no_dues_list_id = $get_no_dues_lists_id_for_duplicate_data['0']['id']; 
          }
          
          
          if($get_the_count_for_duplicate_rows > 0)
          {

            $datatemp = array(

              'no_dues_list_id' => $no_dues_list_id,
              'admn_no' => $admn_no,
              'session_year' => $session_year,
              'due_amt' => $due_amount,
              'due_list' => $due_list,
              'remarks' => $remarks,
              'imposed_dept_id' => $department,
              'imposed_from' => $imposed_from,
              'created_date' => date('d-m-Y H:i:s'),
              'imposed_by' => $this->session->userdata('id')
              //'is_deleted'

            );

            $this->ndqm->insert_due_lists_temporary($datatemp);

            $admn_no_already_exists++;

          }

          else
          {

          $this->ndqm->update_dues_status_bulk($admn_no,$due_amount,$due_list,$remarks,$session_year,$imposed_from,$department);

          }
        


        }

        


        return array(

             'session_error_no_dues' => $session_error_count,
             'due_type_error_no_dues' => $due_type_error,
             'amount_error' => $amount_error,
             'admission_number_error' => $admn_no_already_exists


        );


      }


      public function check_duplicate_no_dues_list_entries($admn_no,$due_amount,$due_list,$remarks,$session_year,$imposed_from,$department)
      {
 
          $sql = "select * from `no_dues_lists` where admn_no = ? and session_year = ? and due_amt = ? and due_list = ? and remarks = ? and imposed_dept_id = ? and imposed_from = ? and pay_status = 0";
          $query = $this->db->query($sql,array($admn_no,$session_year,$due_amount,$due_list,$remarks,$department,$imposed_from));
          return $query->num_rows();
          
        
      }

      public function get_id_duplicate_no_dues_list_entries($admn_no,$due_amount,$due_list,$remarks,$session_year,$imposed_from,$department)
      {

          $sql = "select `id` from `no_dues_lists` where admn_no = ? and session_year = ? and due_amt = ? and due_list = ? and remarks = ? and imposed_dept_id = ? and imposed_from = ? and pay_status = 0";
          $query = $this->db->query($sql,array($admn_no,$session_year,$due_amount,$due_list,$remarks,$department,$imposed_from));
          return $query->result_array();

      }

      public function get_duplicate_date()
      {
          
          $sql = "select * from `no_dues_lists_temp` where `imposed_by` = '".$this->session->userdata('id')."'";
          $query = $this->db->query($sql);
          return $query->result_array();
      }

      public function update_bulk_status($temp_id)
      {

        $this->db->where('id', $temp_id);
        $this->db->delete('no_dues_lists_temp');

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


      public function check_temp_duplicate($admn_no,$due_amount,$due_list,$remarks,$session_year,$imposed_from,$department,$imposed_by)
   { // to update the temporary table after append if there are any more items left

    $data_new = array();
    $data_new_store = "";


    $sql = 'select id from no_dues_lists_temp a where a.admn_no = ? and a.session_year = ? and  a.due_amt = ? and a.due_list = ? and a.remarks = ? and a.imposed_dept_id = ? and a.imposed_from = ? and a.imposed_by = ?';
    $query = $this->db->query($sql,array($admn_no,$session_year,$due_amount,$due_list,$remarks,$department,$imposed_from,$imposed_by));
    //echo $this->db->last_query(); die();
    $tem_details_id = $query->result_array(); // search temporary table

    $data = $this->get_id_duplicate_no_dues_list_entries($admn_no,$due_amount,$due_list,$remarks,$session_year,$imposed_from,$department);

    // print_r($tem_details_id);

    // print_r($data);

    if(count($data) > 1)
    {
        foreach($data as $value)
        {

             array_push($data_new,$value['id']);
        }

        $data_new_store = implode('/',$data_new);
    }

    else

    {
        $data_new_store = $data['0']['id'];
    }

    //print_r($data);        exit;


    if (count($tem_details_id) > 1)
    {

      foreach($tem_details_id as $tem_details)
    {

         $array_multiple = array(

          'no_dues_list_id' => $data_new_store

         );




    $this->db->where('id', $tem_details['id']);
    $this->db->update('no_dues_lists_temp',$array_multiple);
    //echo $this->db->last_query(); die();

    }

    }

    else
    {

        $array_single = array(

            'no_dues_list_id' => $data_new_store

           );

    $this->db->where('id', $tem_details_id['0']['id']);
    $this->db->update('no_dues_lists_temp',$array_single);
    //echo $this->db->last_query(); die();

    }


    //exit;


      }


}
    


    ?>