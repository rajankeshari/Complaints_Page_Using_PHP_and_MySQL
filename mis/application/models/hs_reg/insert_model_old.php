
<?php

class Insert_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }


    public function insert_data($data)
    {
        
        //print_r($data);        

//exit();
        
        $hostel_name = $data[1].",".$data[2];            //exit();

        if($data['7'] == '2')
            
        {
            
            
            $sql = "INSERT INTO `hs_export_hostel_data` (`hostel_name`, `room_no` , `room_id` , `no_of_bed`) VALUES ( '$hostel_name' , '$data[5]' , '$data[6]' , '$data[7]' ) ";
            
            $this->db->query($sql);
            
            //echo $this->db->last_query(); die();
            
            $sql = "INSERT INTO `hs_export_hostel_data` (`hostel_name`, `room_no` , `room_id` , `no_of_bed`) VALUES ( '$hostel_name' , '$data[5]' , '$data[6]' , '$data[7]' ) ";
            
            $this->db->query($sql);
            
            
            
        }
        
        elseif($data['7'] == '3')
        {
            $sql = "INSERT INTO `hs_export_hostel_data` (`hostel_name`, `room_no` , `room_id` , `no_of_bed`) VALUES ( '$hostel_name' , '$data[5]' , '$data[6]' , '$data[7]' ) ";
            
            $this->db->query($sql);
            
            //echo $this->db->last_query(); die();
            
            $sql = "INSERT INTO `hs_export_hostel_data` (`hostel_name`, `room_no` , `room_id` , `no_of_bed`) VALUES ( '$hostel_name' , '$data[5]' , '$data[6]' , '$data[7]' ) ";
            
            $this->db->query($sql);
            
            $sql = "INSERT INTO `hs_export_hostel_data` (`hostel_name`, `room_no` , `room_id` , `no_of_bed`) VALUES ( '$hostel_name' , '$data[5]' , '$data[6]' , '$data[7]' ) ";
            
            $this->db->query($sql);
            
        }
        
        else 
        {
            
            
        }
                
           
                
        
    }

    public function get_edit_history_data($id)
    {
        //echo $id ; exit;

        // $this->db->select('*');
        // $this->db->from('no_dues_hs_inventory_logs');

        // $query = $this->db->get();

        $sql = "select * from no_dues_hs_inventory_logs where inventory_id=".$id;

        $query = $this->db->query($sql);
        
        return $query->result_array();
    }
    
    
    public function insert_data_new($data)
    {
        
        //print_r($data);        exit();

        if($data['6'] == '2')
            
        {
            
            
            $sql = "INSERT INTO `hs_export_hostel_data` (`hostel_name`, `room_no` , `room_id` , `no_of_bed`) VALUES ( '$data[1]' , '$data[4]' , '$data[5]' , '$data[6]' ) ";
            
            $this->db->query($sql);
            
            //echo $this->db->last_query(); die();
            
            $sql = "INSERT INTO `hs_export_hostel_data` (`hostel_name`, `room_no` , `room_id` , `no_of_bed`) VALUES ( '$data[1]' , '$data[4]' , '$data[5]' , '$data[6]' ) ";
            
            $this->db->query($sql);
            
            
            
        }
        
        elseif($data['6'] == '3')
        {
            $sql = "INSERT INTO `hs_export_hostel_data` (`hostel_name`, `room_no` , `room_id` , `no_of_bed`) VALUES ( '$data[1]' , '$data[4]' , '$data[5]' , '$data[6]' ) ";
            
            $this->db->query($sql);
            
            //echo $this->db->last_query(); die();
            
            $sql = "INSERT INTO `hs_export_hostel_data` (`hostel_name`, `room_no` , `room_id` , `no_of_bed`) VALUES ( '$data[1]' , '$data[4]' , '$data[5]' , '$data[6]' ) ";
            
            $this->db->query($sql);
            
            $sql = "INSERT INTO `hs_export_hostel_data` (`hostel_name`, `room_no` , `room_id` , `no_of_bed`) VALUES ( '$data[1]' , '$data[4]' , '$data[5]' , '$data[6]' ) ";
            
            $this->db->query($sql);
            
        }
        
        else 
        {
            
            
        }
                
           
                
        
    }
    
    
    public function insert_data_2($data)
    {
        
        //print_r($data);    
        
       $hostel_name = $data[2].",".$data[3];
        
        //exit();

        if($data['8'] == '2')
            
        {
            
            
            $sql = "INSERT INTO `hs_export_hostel_data` (`hostel_name`, `room_no` , `room_id` , `no_of_bed`) VALUES ( '$hostel_name' , '$data[6]' , '$data[7]' , '$data[8]' ) ";
            
            $this->db->query($sql);
            
            //echo $this->db->last_query(); die();
            
            $sql = "INSERT INTO `hs_export_hostel_data` (`hostel_name`, `room_no` , `room_id` , `no_of_bed`) VALUES ( '$hostel_name' , '$data[6]' , '$data[7]' , '$data[8]' ) ";
            
            $this->db->query($sql);
            
            
            
        }
        
        elseif($data['8'] == '3')
        {
            $sql = "INSERT INTO `hs_export_hostel_data` (`hostel_name`, `room_no` , `room_id` , `no_of_bed`) VALUES ( '$hostel_name' , '$data[6]' , '$data[7]' , '$data[8]' ) ";
            
            $this->db->query($sql);
            
            //echo $this->db->last_query(); die();
            
            $sql = "INSERT INTO `hs_export_hostel_data` (`hostel_name`, `room_no` , `room_id` , `no_of_bed`) VALUES ( '$hostel_name' , '$data[6]' , '$data[7]' , '$data[8]' ) ";
            
            $this->db->query($sql);
            
            $sql = "INSERT INTO `hs_export_hostel_data` (`hostel_name`, `room_no` , `room_id` , `no_of_bed`) VALUES ( '$hostel_name' , '$data[6]' , '$data[7]' , '$data[8]' ) ";
            
            $this->db->query($sql);
            
        }
        
        else 
        {
            
            
        }
                
           
                
        
    }
    
    public function insert_data_3($data)
    {
        
        //print_r($data);        exit();

        if($data['7'] == '2')
            
        {
            
            
            $sql = "INSERT INTO `hs_export_hostel_data` (`hostel_name`, `room_no` , `room_id` , `no_of_bed`) VALUES ( '$data[2]' , '$data[5]' , '$data[6]' , '$data[7]' ) ";
            
            $this->db->query($sql);
            
            //echo $this->db->last_query(); die();
            
            $sql = "INSERT INTO `hs_export_hostel_data` (`hostel_name`, `room_no` , `room_id` , `no_of_bed`) VALUES ( '$data[2]' , '$data[5]' , '$data[6]' , '$data[7]' ) ";
            
            $this->db->query($sql);
            
            
            
        }
        
        elseif($data['7'] == '3')
        {
            $sql = "INSERT INTO `hs_export_hostel_data` (`hostel_name`, `room_no` , `room_id` , `no_of_bed`) VALUES ( '$data[2]' , '$data[5]' , '$data[6]' , '$data[7]' ) ";
            
            $this->db->query($sql);
            
            //echo $this->db->last_query(); die();
            
            $sql = "INSERT INTO `hs_export_hostel_data` (`hostel_name`, `room_no` , `room_id` , `no_of_bed`) VALUES ( '$data[2]' , '$data[5]' , '$data[6]' , '$data[7]' ) ";
            
            $this->db->query($sql);
            
            $sql = "INSERT INTO `hs_export_hostel_data` (`hostel_name`, `room_no` , `room_id` , `no_of_bed`) VALUES ( '$data[2]' , '$data[5]' , '$data[6]' , '$data[7]' ) ";
            
            $this->db->query($sql);
            
        }
        
        else 
        {
            
            
        }
                
           
                
        
    }
    
    public function select() {
        
        
        $this->db->select('*');
        $this->db->from('hs_export_hostel_data');

        $query = $this->db->get();
        
        return $query->result_array();
        
        
        //echo $this->db->last_query(); die();

        
    }
    
    /*
    
    
    public function get_room_detail($value) {
        
        //print_r($value); exit();
        
        //$floor = $this->get_floor_value_from_name($value['4']);
        
        
    }
    
     public function return_floor_value($floor) {
        switch ($floor) {
            case "Ground Floor":
                $str = "floor0";
                break;
            case "First Floor":
                $str = "floor1";
                break;
            case "Second Floor":
                $str = "floor2";
                break;
            case "Third Floor":
                $str = "floor3";
                break;
            case "Fourth Floor":
                $str = "floor4";
                break;
            case "Fifth Floor":
                $str = "floor5";
                break;
            case "Six Floor":
                $str = "floor6";
                break;
            case "Seven Floor":
                $str = "floor7";
                break;
            case "Eight Floor":
                $str = "floor8";
                break;
            case "Nine Floor":
                $str = "floor9";
                break;
            case "Ten Floor":
                $str = "floor10";
                break;
            case "Eleven Floor":
                $str = "floor11";
                break;
            case "Twelve Floor":
                $str = "floor12";
                break;
            case "Thirteen Floor":
                $str = "floor13";
                break;
            case "Fourteen Floor":
                $str = "floor14";
                break;
            case "Fifteen Floor":
                $str = "floor15";
                break;

            default:
                $str = "";
                break;
        }
        return $str;
    }
    
    
   /* switch ($floor) {
            case "floor0":
                $str = "Ground Floor";
                break;
            case "floor1":
                $str = "First Floor";
                break;
            case "floor2":
                $str = "Second Floor";
                break;
            case "floor3":
                $str = "Third Floor";
                break;
            case "floor4":
                $str = "Fourth Floor";
                break;
            case "floor5":
                $str = "Fifth Floor";
                break;
            case "floor6":
                $str = "Six Floor";
                break;
            case "floor7":
                $str = "Seven Floor";
                break;
            case "floor8":
                $str = "Eight Floor";
                break;
            case "floor9":
                $str = "Nine Floor";
                break;
            case "floor10":
                $str = "Ten Floor";
                break;
            case "floor11":
                $str = "Eleven Floor";
                break;
            case "floor12":
                $str = "Twelve Floor";
                break;
            case "floor13":
                $str = "Thirteen Floor";
                break;
            case "floor14":
                $str = "Fourteen Floor";
                break;
            case "floor15":
                $str = "Fifteen Floor";
                break;

            default:
                $str = "";
                break;
        }
        return $str;
    * 
    * */


      public function add_new_inventory_list($data)
      {
          $this->db->insert('no_dues_hs_inventory',$data);
          return $this->db->insert_id();
      }


      public function get_inventory_list()
      {
          $sql = 'select * from no_dues_hs_inventory';
          $query = $this->db->query($sql);
          //echo $this->db->last_query(); die();
          return $query->result_array();
      }

      public function delete_inventory_list($inventory_list)
      {

        $sql = "delete from `no_dues_hs_inventory` where id='".$inventory_list."'";

        $query = $this->db->query($sql);

      }

      public function get_inventory_list_data($id)
      {
          $sql = "select * from `no_dues_hs_inventory` where id='".$id."'";
          $query = $this->db->query($sql);

          return $query->result_array();

      }


      public function get_inventory_list_name()
      {
          $sql = "select * from `no_dues_hs_inventory` where `status` = '0'";
          $query = $this->db->query($sql);
          return $query->result_array();
      }

      public function edit_inventory_list_data($edit_item)
      
      {

        //print_r($edit_item);   exit;

        $this->db->insert('no_dues_hs_inventory_logs',$edit_item);
          return $this->db->insert_id();


      }

      public function change_inventory_status($inventory_list)
      {

        $sql = "select * from `no_dues_hs_inventory` where id='".$inventory_list."'";

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
    
        $this->db->where('id', $inventory_list);
        $this->db->update('no_dues_hs_inventory', $data);

        //echo $this->db->last_query(); die();

        $sql = "select * from `no_dues_hs_inventory` where id='".$inventory_list."'";
        $query = $this->db->query($sql);
        //echo $this->db->last_query(); die();
        $data = $query->result();
        //print_r($data);

        $status = $data['0']->status;

        return $status;

       

      }

      public function get_inventory_list_item()
      {
          $sql = "select * from `no_dues_hs_inventory` where `status` = 0";
          $query = $this->db->query($sql);
          return $query->result_array();
      }


      public function edit_inventory_list_item($id, $edit_item)

      {

        $this->db->where('id', $id);
        $this->db->update('no_dues_hs_inventory', $edit_item);

      }

      public function bulk_assign_hostel_no_dues($assign_student_data)
      
      {

        
        $student_details = $this->get_bulk_student_details($assign_student_data['admn_no']);

        $student_name = $student_details[0]['salutation']." ".$student_details[0]['first_name']." ".$student_details[0]['middle_name']." ".$student_details[0]['last_name'];

        $data = array(

            'admn_no' => $student_details[0]['admn_no'],
            'student_name' => $student_name,
            'hostel_name' => $student_details[0]['hostel_name'],
            'room_no' => $student_details[0]['room_name'],
            'date_of_entry' => $student_details[0]['entry_datetime'],
            'date_of_exit' => '----'
        );


        $this->db->insert('no_dues_hs_individual',$data);



      }

      public function update_descrip_status($description,$bulk_id_new)
      {

        //   echo $description;
        //   echo $bulk_id_new;
          //exit;

          $data = array(
            'description' => $description,
           // 'status' => $name,
           // 'date' => $date
    );
    
    $this->db->where('id', $bulk_id_new);
    $this->db->update('bulk_assign_hostel_no_dues_dynamic', $data);

      }

      public function check_bulk_duplicate($admn_no,$inventory_item,$inventory_quantity,$hostel_name,$block,$session_name,$session_year,$payment_status)
      {

        //echo 'entered';

         $sql = 'select b.id from no_dues_hs_individual a inner join no_dues_hs_details b on a.hostel_no_dues_id = b.id inner join no_dues_hs_payment c on b.id = c.hs_no_dues_id where a.admn_no = ? and b.inventory_item = ? and  b.inventory_quantity = ? and a.hostel_name = ? and a.block = ? and a.session_name = ? and a.session_year = ? and a.is_deleted = 0 and c.payment_status = ?';
         $query = $this->db->query($sql,array($admn_no,$inventory_item,$inventory_quantity,$hostel_name,$block,$session_name,$session_year,$payment_status));
         //echo $this->db->last_query(); die();
         return $query->result_array();
         

         //echo $this->db->last_query(); die();
      }

      public function get_item_price($item_name,$item_quantity)
      {

            $sql = "select `rate_unit_price` from `no_dues_hs_inventory` where `description` = ?";
            $query = $this->db->query($sql,array(trim($item_name)));
            $price = $query->result_array();
            return $item_quantity*$price['0']['rate_unit_price']; //exit;
            
      }

      public function select_assign_bulk_hs_no_dues()
      {
          $sql = "select * from `bulk_assign_hostel_no_dues_dynamic` where `status` = 0";
          $query = $this->db->query($sql);
          
          return $query->result_array();
      }

      public function bulk_entry_hostel_no_dues($no_dues_hs_details,$insert_id)

      {

        $array_new = array();

        $array_inventory_name = array();

        $array_inventory_value = array();
        //explode('-->',$no_dues_hs_details);
        $count = count($no_dues_hs_details);

       for($i=0;$i<$count;$i++)
       {
          
           array_push($array_new,explode('-->',$no_dues_hs_details[$i]));

       }

       $count2 = count($array_new);

       for($i=1;$i<=$count2-1;$i++)
       {

        array_push($array_inventory_name,explode('(',$array_new[$i]['0'])['0']);

        array_push($array_inventory_value,$array_new[$i]['1']);

       }

       print_r($array_inventory_name);

       print_r($array_inventory_value);


       exit;

       

      }

      public function get_bulk_student_details($admn_no)
      {

        $sql = "SELECT * FROM `user_details` a INNER JOIN `hs_assigned_student_room` b ON a.id = b.admn_no INNER JOIN `hs_room_details` c ON c.id = b.room_detail_id INNER JOIN `hs_hostel_details` d ON d.id = c.hostel_detail_id WHERE b.admn_no = '$admn_no' ORDER BY b.modify_date DESC LIMIT 1";
        $query = $this->db->query($sql);
        //echo $this->db->last_query(); die();
        return $query->result_array();

      }



      public function get_student_details($admn_no)
      {

        $sql = "SELECT * FROM `user_details` a INNER JOIN `hs_assigned_student_room` b ON a.id = b.admn_no INNER JOIN `hs_room_details` c ON c.id = b.room_detail_id INNER JOIN `hs_hostel_details` d ON d.id = c.hostel_detail_id WHERE b.admn_no = '$admn_no' ORDER BY b.modify_date DESC LIMIT 1";
        $query = $this->db->query($sql);

        //echo $this->db->last_query(); die();

        //print_r($query->result_array()); exit;

        return json_encode($query->result_array());

      }

      public function get_inventory_item_price($item)
      {

        $sql = "select `rate_unit_price` from `no_dues_hs_inventory` where `description` = '$item'";
        $query = $this->db->query($sql);
        $amount = $query->result_array();
        return $amount['0']['rate_unit_price'];

      }

      public function get_inventory_item_price_id($item)
      {

        $sql = "select `rate_unit_price` from `no_dues_hs_inventory` where `id` = '$item'";
        $query = $this->db->query($sql);
        $amount = $query->result_array();
        return $amount['0']['rate_unit_price'];

      }


      public function insert_values_before_edit($pre_inventory_item,$pre_item_quantity,$pre_due_amount,$pre_remarks,$hostel_no_dues_id) 
      
      {


        $date = date('Y-m-d H:i:s');


        $data = array(
        
        'hs_no_dues_id' => $hostel_no_dues_id, 
        'previous_due_amount' => $pre_due_amount,
        'previous_inventory_item' => $pre_inventory_item,
        'previous_inventory_quantity' => $pre_item_quantity,
        'previous_dues_remark' => $pre_remarks,
        'is_deleted' => 0,
        'last_modified_by' => $this->session->userdata('id'),
        'last_modified_date' => $date

          );


          $this->db->insert('no_dues_hs_changed_logs',$data);


          
       }


      public function edit_hostel_no_dues($inventory_item,$item_quantity, $due_amount,$remarks,$hostel_no_dues_id)
      {
        $data = array(

            'inventory_item' => $inventory_item,
            'inventory_quantity' => $item_quantity,
            'inventory_amount' => $due_amount,
            'inventory_remark' => $remarks
    );
    
    $this->db->where('id', $hostel_no_dues_id);
    $this->db->update('no_dues_hs_details', $data);

      }

      public function delete_assigned_hs_no_dues($id,$assign_id)
      {

          $sql = 'select `hostel_no_dues_id` from `no_dues_hs_individual` where `id`= ?';
          $query = $this->db->query($sql , array($assign_id));

          //echo $this->db->last_query(); die();
          $hostel_no_dues_id = $query->result_array();

          //print_r($hostel_no_dues_id);

          $hostel_no_dues_id = $hostel_no_dues_id['0']['hostel_no_dues_id'];

          //echo $hostel_no_dues_id; exit;

          //$pos = strpos($hostel_no_dues_id, ",");


              //if ($pos == false) {


                // $this->db->where('hostel_no_dues_id', $hostel_no_dues_id);
                // $this->db->delete('no_dues_hs_individual');


                $data = array(

                          'is_deleted' => 1
                      );
            
            
                      $this->db->where('hostel_no_dues_id', $hostel_no_dues_id);
                      $this->db->update('no_dues_hs_individual', $data);

                      $this->db->where('id', $hostel_no_dues_id);
                      $this->db->update('no_dues_hs_details', $data);

                      $this->db->where('hs_no_dues_id', $hostel_no_dues_id);
                      $this->db->update('no_dues_hs_payment', $data);

                    //   $this->db->where('id', $id);
                    //   $this->db->delete('no_dues_hs_details');


                    //     $this->db->where('hs_no_dues_id', $id);
                    //     $this->db->delete('no_dues_hs_payment');


              //} 
              
              
        //       else {
                 
        //   //echo $hostel_no_dues_id;

        //   $arrayreq = explode(',', $hostel_no_dues_id);

        //   //print_r($arrayreq);

        //   $count = count($arrayreq);

        //   for($i = 0; $i < $count; $i++)
        //   {

        //         if($arrayreq[$i] == $id)
        //         {
        //             unset($arrayreq[$i]);
        //         }

                
          
        //   }


        //    sort($arrayreq);

        

        //   $count = count($arrayreq);

        //   if($count > 1)
        //   {

        //         $hostel_no_dues_id_new = implode(',', $arrayreq);
        //   }


        //   else
        //   {

        //     $hostel_no_dues_id_new =  $arrayreq['0'];

        //   }


        //   $data = array(

        //       'hostel_no_dues_id' => $hostel_no_dues_id_new
        //   );


        //   $this->db->where('id', $assign_id);
        //   $this->db->update('no_dues_hs_individual', $data);


        // }


          



    }

    public function get_admission_number($assign_id)

    {

         $sql = "select `admn_no` from `no_dues_hs_individual` where `id` = ?";
         $query = $this->db->query($sql, array($assign_id));

         return $query->result_array();
    }



      public function add_individual_no_dues($data)
      {

        // echo '<pre>';
        
        // print_r($data);
        // echo '</pre>';

        // exit;
        $this->db->insert('no_dues_hs_individual', $data);

        //echo $this->db->last_query(); die();
        $insert_id = $this->db->insert_id();

        return  $insert_id;
        
      }

      public function insert_duplicate_entry($data)
      {

        $this->db->insert('no_dues_hs_temporary_details', $data);

        //echo $this->db->last_query(); //die();
        //$insert_id = $this->db->insert_id();

        //return  $insert_id;

      }

      public function get_duplicate_date()
      {
          $sql = "select * from `no_dues_hs_temporary_details` where `is_deleted` = 0";
          $query = $this->db->query($sql);
          return $query->result_array();

      }

      public function update_hs_details($id)
      {

           
        $data = array(

            'is_deleted' => 1
        );


        // $this->db->where('hostel_no_dues_id', $hostel_no_dues_id);
        // $this->db->update('no_dues_hs_individual', $data);

        $this->db->where('id', $id);
        $this->db->update('no_dues_hs_details', $data);

        // $this->db->where('hs_no_dues_id', $hostel_no_dues_id);
        // $this->db->update('no_dues_hs_payment', $data);

      }

      public function update_individual_details($id)
      {

          
        $data = array(

            'is_deleted' => 1
        );


        $this->db->where('hostel_no_dues_id', $id);
        $this->db->update('no_dues_hs_individual', $data);

        

      }

      public function update_pay_details($id)
      {


           
        $data = array(

            'is_deleted' => 1
        );


        

        $this->db->where('hs_no_dues_id', $id);
        $this->db->update('no_dues_hs_payment', $data);

      }

      public function check_duplicate_temp($hostel_name,$session_name, $session_year,$block)
      {
          $sql = "select * from `no_dues_hs_temporary_details` a where a.hostel_name = ? and a.session_name = ? and a.session_year = ? and a.block = ? and is_deleted = 0";
          $query = $this->db->query($sql, array($hostel_name,$session_name, $session_year,$block));
          return $query->result_array();
      }

      public function dynamic_entry($no_dues_hs_detailss,$description,$hostel_name,$block,$session_name,$session_year)
      {

       
        $array_insert = 0;
        $array_error = 0;
        $invalid_quantity = array();
        $inventory_already_exits = array();
        //$duplicate_data_new = array();
        

        $count = count($no_dues_hs_detailss);

        //echo $count; exit; //5

        if (strpos($description, '/') !== false) {

            $description_new = explode('/',$description); //3

            array_unshift($description_new,"testing");
    
            $count_description = count($description_new);
        }

        else
        {
            $description_new = $description;
        }

        $admn_no = explode('-->',$no_dues_hs_detailss[0])[1];

        for($i=1; $i<$count-1;$i++) 
        {

            $inventory = explode('-->',$no_dues_hs_detailss[$i])[0];
            $inventory_name = explode('(',$inventory)[0];
            $inventory_quantity = explode('-->',$no_dues_hs_detailss[$i])[1];

            $inventory_price = $this->get_item_price($inventory_name,$inventory_quantity);
            $date = date('d-m-Y H:i:s');

            // if($inventory_price == 0) {

            //     //$array_error++;
            //     //array_push($invalid_quantity,trim($inventory_name));
            //     array_unshift($description_new,"testing");


            // }
            if (strpos($description, '/') !== false) {
            
            if($count-2 == $count_description)
            {
            $cumulative_description = trim($description_new[$i]);
            }

            else
            {
                $cumulative_description = trim($inventory_name)." Not Working";
            }
            }
            else
            {

                if($count-2 == $count_description)
                {
                //$cumulative_description = trim($description_new[$i]);
                $cumulative_description = trim($description_new);
                }
    
                else
                {
                    $cumulative_description = trim($inventory_name)." Not Working";
                }
                
            }
            
           

            if($inventory_price == 0 || is_numeric($inventory_price) == false) {

                $array_error++;
                array_push($invalid_quantity,trim($inventory_name));
                //array_unshift($description_new,"testing");


            }

            else
            {

            $data = array(

                'admn_no' => $admn_no,
                'inventory_item' => trim($inventory_name),
                'inventory_quantity' => $inventory_quantity,
                'inventory_price' => $inventory_price,
                'description' => $cumulative_description,
                'hostel_name' => $hostel_name,
                'block' => $block,
                'session_name' => $session_name,
                'session_year' => $session_year,
                'payment_status' => 'no_action'
                //'status' => '0'
            );

            //$this->db->insert('bulk_assign_hostel_no_dues_dynamic', $data);

            $duplicate_entry = $this->check_duplicate_data($data);

            // echo $i."distict";
            
            $duplicate_array = $duplicate_entry['duplicate_data_array']; 

            $duplicate_count = $duplicate_entry['duplicate_data_count'];

            //exit;


            // echo $duplicate_array;

            // echo $duplicate_count;

            // exit;

            // print_r($duplicate_array);
            // echo $duplicate_count;

            if($cumulative_description == '')
            {
                $cumulative_description = 'Not Working';
            }



            if($duplicate_count > 0)

            {

            
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

                    'admn_no' => $admn_no,
                    'inventory_name' => trim($inventory_name),
                    'inventory_quantity' => $inventory_quantity,
                    'inventory_amount' => $inventory_price,
                    'inventory_remark' => $cumulative_description,
                    'hostel_name' => $hostel_name,
                    'block' => $block,
                    'session_name' => $session_name,
                    'session_year' => $session_year,
                    'no_dues_hs_details_id' => $duplicate_data_store,
                    'is_deleted' => 0,
                    'last_modified_date' => $date,
                    'last_modified_by' => $this->session->userdata('id')
  
              );
  



            }

            else
            {
                $duplicate_entry_new  = $duplicate_array[0]['id'];
                $data = array(

                    'admn_no' => $admn_no,
                    'inventory_name' => trim($inventory_name),
                    'inventory_quantity' => $inventory_quantity,
                    'inventory_amount' => $inventory_price,
                    'inventory_remark' => $cumulative_description,
                    'hostel_name' => $hostel_name,
                    'block' => $block,
                    'session_name' => $session_name,
                    'session_year' => $session_year,
                    'no_dues_hs_details_id' => $duplicate_entry_new,
                    'is_deleted' => 0,
                    'last_modified_date' => $date,
                    'last_modified_by' => $this->session->userdata('id')
  
              );

            }


            // echo $duplicate_entry_new;

            // exit;
            
            
            

            $this->insert_duplicate_entry($data);

            array_push($inventory_already_exits,$admn_no);

            }

            //$array_insert++;

        }
           
        }

        //exit;

        return array(
            'invalid_quantity' => $invalid_quantity,
            'inventory_already_exits' => $inventory_already_exits,
        );
        
        
        //$inventory_already_exits;



     
      }


      public function check_duplicate_data($data)
{

    //print_r($data);

    $admn_no = $data['admn_no'];
    $inventory_item = $data['inventory_item'];
    $inventory_quantity = $data['inventory_quantity'];
    $inventory_price = $data['inventory_price'];
    //$bulk_id = $data['5'];

    $description = $data['description'];
    if($description == "")
    {
        $description = 'Not Working';
    }

    $hostel_name = $data['hostel_name'];
    $block = $data['block'];
    $session_name = $data['session_name'];
    $session_year = $data['session_year'];
    $already_exists = 0;
    $payment_status = $data['payment_status'];

    //exit;

    // insert description in each column and change the status.
    //$bulk_id_new = explode('.',$bulk_id)['1'];

    //$update_description_status = $this->insert_model->update_descrip_status($description,trim($bulk_id_new));


    if(count($this->insert_model->check_bulk_duplicate($admn_no,$inventory_item,$inventory_quantity,$hostel_name,$block,$session_name,$session_year,$payment_status)) > 0)
    {

           $duplicate_date_id = $this->insert_model->check_bulk_duplicate($admn_no,$inventory_item,$inventory_quantity,$hostel_name,$block,$session_name,$session_year,$payment_status);
           $count_duplicate_data = count($duplicate_date_id);
           //$already_exists++;

        //    print_r($duplicate_date_id);

        //    echo $count_duplicate_data;

        //    exit;

           return array("duplicate_data_array" => $duplicate_date_id,
        
              "duplicate_data_count" => $count_duplicate_data
        
        );
    }

    else
    {


    

         $bulk_students_details = $this->insert_model->get_bulk_student_details($admn_no);

         $student_name = $bulk_students_details[0]['first_name']." ".$bulk_students_details[0]['middle_name']." ".$bulk_students_details[0]['last_name'];

         $date = date('d-m-Y H:i:s');

         $data = array(

            'admn_no' => $bulk_students_details['0']['admn_no'],
            'student_name' => $student_name,
            'hostel_name' => $bulk_students_details['0']['hostel_name'],
            'room_no' => $bulk_students_details[0]['room_name'],
            'date_of_entry' => $bulk_students_details[0]['entry_datetime'],
            'date_of_exit' => '----',
            'is_deleted' => 0,
            'session_name' => $session_name,
            'session_year' => $session_year,
            'block' => $block,
            'last_modified_date' => $date,
            'last_modified_by' => $this->session->userdata('id')

         );

         $no_dues_hs_individual = $this->insert_model->add_individual_no_dues($data); //exit;


         $data = array(

            'assign_hs_no_dues_id' => $no_dues_hs_individual,
            // inventory quality
            'inventory_item' => $inventory_item,
            'inventory_quantity' => $inventory_quantity,
            'inventory_amount' => $inventory_price,
            'inventory_remark' => $description,
            'is_deleted' => 0,
            'last_modified_date' => $date,
            'last_modified_by' => $this->session->userdata('id')


        );


        $this->insert_model->add_hs_no_dues_list($data);

        $get_no_dues_list = $this->insert_model->get_no_dues_list($no_dues_hs_individual);

            // echo '<pre>';
            // print_r($get_no_dues_list);
            // echo '</pre>';

            $array_hs_no_dues = array();


            if (count($get_no_dues_list) > 1) {
              // $arraynew = implode(',', $get_no_dues_list);
              foreach ($get_no_dues_list as $hs_no_dues_list)
              {

                array_push($array_hs_no_dues, $hs_no_dues_list['id']);

              }

              $hs_no_dues_list = implode(',', $array_hs_no_dues);


            } 

            else 
            {
                $hs_no_dues_list = $get_no_dues_list[0]['id'];
            }

           

            $this->insert_model->update_hs_no_dues($hs_no_dues_list, $no_dues_hs_individual);

           // exit;

            if (count($get_no_dues_list) > 1) {

                foreach ($get_no_dues_list as $hs_no_dues_list)
                {

                    $data = array(

                        'hs_no_dues_id' => $hs_no_dues_list['id'],
                        'payment_status' => 'no_action',
                        'payment_receipt' => '',
                        'status' => 0,
                        'description' => 'not_yet_started',
                        'is_deleted' => 0,
                        'last_modified_date' => $date,
                        'last_modified_by' => $this->session->userdata('id')
                    );

                    $this->insert_model->insert_pay_status($data);
                    
                    
                }

                }

            else
            {

                $data = array(

                    'hs_no_dues_id' => $get_no_dues_list[0]['id'],
                    'payment_status' => 'no_action',
                    'payment_receipt' => '',
                    'status' => 0,
                    'description' => 'not_yet_started',
                    'is_deleted' => 0,
                    'last_modified_date' => $date,
                    'last_modified_by' => $this->session->userdata('id')
                );

                $this->insert_model->insert_pay_status($data);

            }



            $this->insert_model->update_bulk_status($bulk_id_new);

            //exit;

            $user_from = $this->session->userdata('id');

            $date = date('d-m-Y H:i:s');

            $auth_id = "stu";

            $check = $this->insert_model->check_notification($admn_no,$user_from,$date,$auth_id);

            if($check == 0)
            {

            $title = "Hostel No Dues";
			$description = "Hostel No Dues Imposed By ".$this->session->userdata('id')."!";
			$link = 'hs_reg/assign_student_hs_no_dues/view_student_hs_no_dues';
            $this->notification->notify($admn_no,"stu",$title,$description,$link,"");

            }
            

            //return 'success';
            return array("duplicate_data_array" => "success",
        
              "duplicate_data_count" => 0 

        );


        }


    //}


}

   public function check_temp_duplicate($admn_no,$hostel_name,$session_name,$session_year,$block,$inventory_item,$inventory_quantity,$inventory_price,$description,$payment_status)
   {

    $data_new = array();
    $data_new_store = "";


    $sql = 'select id from no_dues_hs_temporary_details a where a.admn_no = ? and a.inventory_name = ? and  a.inventory_quantity = ? and a.hostel_name = ? and a.block = ? and a.session_name = ? and a.session_year = ? and a.inventory_amount = ? and a.inventory_remark = ? and a.is_deleted = 0';
    $query = $this->db->query($sql,array($admn_no,trim($inventory_item),$inventory_quantity,$hostel_name,$block,$session_name,$session_year,$inventory_price,trim($description)));
    //echo $this->db->last_query(); die();
    $tem_details_id = $query->result_array();

    $data = $this->check_bulk_duplicate($admn_no,$inventory_item,$inventory_quantity,$hostel_name,$block,$session_name,$session_year,$payment_status);

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

          'no_dues_hs_details_id' => $data_new_store

         );


    
    
    $this->db->where('id', $tem_details['id']);
    $this->db->update('no_dues_hs_temporary_details',$array_multiple);
    //echo $this->db->last_query(); die();

    }

    }

    else
    {

        $array_single = array(

            'no_dues_hs_details_id' => $data_new_store
  
           );

    $this->db->where('id', $tem_details_id['0']['id']);
    $this->db->update('no_dues_hs_temporary_details',$array_single);
    //echo $this->db->last_query(); die();

    }


    //exit;

    
      }

      public function check_duplicate_before_insert($inventory_name,$inventory_quantity,$admn_no)
      {

          $sql = "select * from `bulk_assign_hostel_no_dues_dynamic` where  inventory_item = ? and inventory_quantity = ? and admn_no = ?";
          $query = $this->db->query($sql, array($inventory_name,$inventory_quantity,$admn_no));
          return $query->num_rows();

      }



      public function check_admn_no($admn_no,$hostel_name,$block,$session_name,$session_year)
      {

        // $sql = "SELECT * FROM `user_details` a INNER JOIN `hs_assigned_student_room` b ON a.id = b.admn_no INNER JOIN `hs_room_details` c ON c.id = b.room_detail_id INNER JOIN `hs_hostel_details` d ON d.id = c.hostel_detail_id WHERE b.admn_no = '$admn_no' ORDER BY b.modify_date DESC LIMIT 1";
        // $query = $this->db->query($sql);
        //echo $this->db->last_query();
        // $query->num_rows(); //exit;

        // $sql = "SELECT b.admn_no
        // FROM reg_regular_form b
        // INNER JOIN reg_regular_fee c ON c.form_id = b.form_id AND b.session='$session_name' AND b.session_year='$session_year' AND b.admn_no IN (
        // SELECT distinct a.admn_no
        // FROM `hs_student_allotment_list` a
        // WHERE a.hostel_name='$hostel_name' and a.admn_no='$admn_no')";

        $sql = "SELECT b.admn_no
        FROM reg_regular_form b
        INNER JOIN reg_regular_fee c ON c.form_id = b.form_id AND b.session='$session_name' AND b.session_year='$session_year' AND b.admn_no IN (
        SELECT DISTINCT s.admn_no
        FROM `hs_assigned_student_room` s INNER JOIN `hs_room_details` p ON s.room_detail_id = p.id INNER JOIN `hs_hostel_details` t ON p.hostel_detail_id = t.id
        WHERE t.hostel_name='$hostel_name' and t.id='$block' and s.admn_no='$admn_no')";


        

        //exit;
        $query = $this->db->query($sql);

        //echo $this->db->last_query(); die();
        return $query->num_rows();

      }

      public function add_hs_no_dues_list($data)
      {

        $this->db->insert('no_dues_hs_details', $data);

      }

      public function get_no_dues_list($no_dues_hs_individual)
      {
          $sql= "select `id` from `no_dues_hs_details` where `assign_hs_no_dues_id`='$no_dues_hs_individual' and `is_deleted` = 0";
          $query = $this->db->query($sql);
          return $hs_list = $query->result_array();
         // print_r($hs_list);  

      }

      public function update_hs_no_dues($hs_no_dues_list,$no_dues_hs_individual)
      {
            $data = array(

                'hostel_no_dues_id' => $hs_no_dues_list,
                
        );
        
        $this->db->where('id', $no_dues_hs_individual);
        $this->db->update('no_dues_hs_individual', $data);
        //echo $this->db->last_query(); 

      }

      public function update_bulk_status($temp_hs_details_id)
      {

                $data = array(

                    'is_deleted' => 1,
                    
            );
            
            $this->db->where('id', $temp_hs_details_id);
            $this->db->update('no_dues_hs_temporary_details', $data);
            //echo $this->db->last_query();die();

      }

      public function insert_pay_status($data)
      {

        $this->db->insert('no_dues_hs_payment', $data);

      }

      public function fetch_fine_row()
      {

            $sql = "select * from no_dues_hs_individual";
            $query = $this->db->query($sql);
            return $query->result_array();


      }

      public function get_payment_details_paid($datamsg)
      {
          $sql = "select * from `no_dues_hs_payment` a inner join `no_dues_hs_payment_details` b on a.id = b.payment_id where b.cumulative_id_details = ?";
          $query = $this->db->query($sql,array($datamsg));
          //echo $this->db->last_query(); die();
          return $query->result_array();

      }

      public function get_hostel_dues_details($id)
    {

        $sql = "select * from `no_dues_hs_payment` a inner join `no_dues_hs_details` b on b.id = a.hs_no_dues_id inner join `no_dues_hs_individual` c on c.id = b.assign_hs_no_dues_id inner join no_dues_hs_payment_details d on d.id = a.payment_details_id where b.id = ?";//exit;
        $query = $this->db->query($sql,array($id));
        //echo $this->db->last_query(); die();
        return $query->result_array();

    }

      public function get_pending_hostel_no_dues()
      {

        $sql = "select * from `no_dues_hs_payment` a inner join `no_dues_hs_details` b on b.id = a.hs_no_dues_id inner join `no_dues_hs_individual` c on c.id = b.assign_hs_no_dues_id inner join no_dues_hs_payment_details d on d.id = a.payment_details_id where a.status='pending' and a.payment_status='success' and b.`is_deleted` = 0";//exit;
        $query = $this->db->query($sql);
        //echo $this->db->last_query(); die();
        return $query->result_array();

      }

      public function get_rejected_hostel_no_dues()
      {

        $sql = "select * from `no_dues_hs_payment` a inner join `no_dues_hs_details` b on b.id = a.hs_no_dues_id inner join `no_dues_hs_individual` c on c.id = b.assign_hs_no_dues_id where a.status='Rejected' and a.payment_status='success' and b.`is_deleted` = 0";//exit;
        $query = $this->db->query($sql);
        //echo $this->db->last_query(); die();
        return $query->result_array();

      }


      public function get_admission_number_list($hostel_name,$block,$session_name,$session_year)
      {
        
        // $sql = "SELECT b.admn_no
        // FROM reg_regular_form b
        // INNER JOIN reg_regular_fee c ON c.form_id = b.form_id AND b.session='$session_name' AND b.session_year='$session_year' AND b.admn_no IN (
        // SELECT distinct a.admn_no
        // FROM `hs_student_allotment_list` a
        // WHERE a.hostel_name='$hostel_name')";
        //exit;

        $sql = "SELECT b.admn_no
        FROM reg_regular_form b
        INNER JOIN reg_regular_fee c ON c.form_id = b.form_id AND b.session='$session_name' AND b.session_year='$session_year' AND b.admn_no IN (
        SELECT DISTINCT s.admn_no
        FROM `hs_assigned_student_room` s INNER JOIN `hs_room_details` p ON s.room_detail_id = p.id INNER JOIN `hs_hostel_details` t ON p.hostel_detail_id = t.id
        WHERE t.hostel_name='$hostel_name' and t.id='$block')";
        $query = $this->db->query($sql);
        //echo $this->db->last_query(); die();
        return $query->result_array();
        
      }


      public function check_notification($admn_no,$user_from,$date,$auth_id)
      {

         $sql = "select * from user_notifications where user_to = ? and user_from = ? and send_date = ? and auth_id = ?";
         $query = $this->db->query($sql,array($admn_no,$user_from,$date,$auth_id));
         return $query->num_rows();


      }

      public function select_hostel_no_dues($admn_no)
      {

        $sql = 'select * from no_dues_hs_individual a inner join no_dues_hs_details b on a.hostel_no_dues_id = b.id where a.admn_no = ? and and b.`is_deleted` = 0';
        $query = $this->db->query($sql,array($admn_no));
        return $query->result_array();

      }

      public function select_no_dues($admn_no)
      {

          $sql = "select * from `no_dues_lists` where admn_no = ?";
          $query = $this->db->query($sql,array($admn_no));
          return $query->result_array();

      }

      public function get_approved_hostel_no_dues()
      {

        $sql = "select * from `no_dues_hs_payment` a inner join `no_dues_hs_details` b on b.id = a.hs_no_dues_id inner join `no_dues_hs_individual` c on c.id = b.assign_hs_no_dues_id where a.status='Approved' and a.payment_status='success' and b.`is_deleted` = 0";//exit;
        $query = $this->db->query($sql);
        //echo $this->db->last_query(); die();
        return $query->result_array();

      }

      public function approve_reject_status($data,$id) {

            $this->db->where('hs_no_dues_id', $id);
            $this->db->update('no_dues_hs_payment',$data);


      }

      public function get_student_hostel_no_dues_details($id)
      {

        $sql = "select * from `no_dues_hs_payment` a inner join `no_dues_hs_details` b on b.id = a.hs_no_dues_id inner join `no_dues_hs_individual` c on c.id = b.assign_hs_no_dues_id where a.hs_no_dues_id='".$id."' and b.`is_deleted` = 0";//exit;
        $query = $this->db->query($sql);
        //echo $this->db->last_query(); die();
        return $query->result_array();

      }


      public function fetch_fine_hostel_no_dues()
      {

        $sql = "select * from `no_dues_hs_payment` a inner join `no_dues_hs_details` b on b.id = a.hs_no_dues_id inner join `no_dues_hs_individual` c on c.id = b.assign_hs_no_dues_id where a.status='0' and a.payment_status='no_action' and b.`is_deleted` = 0";//exit;
        $query = $this->db->query($sql);
        //echo $this->db->last_query(); die();
        return $query->result_array();



      }

      public function fetch_fine_hostel_no_dues_id($hostel_no_dues_id)
      {

          $sql="SELECT a.*,b.*,a.id as hostel_dues_entry_id from no_dues_hs_details a inner join no_dues_hs_individual b on a.id = b.hostel_no_dues_id where a.id = ? and a.`is_deleted` = 0";
          $query = $this->db->query($sql,array($hostel_no_dues_id));
        //echo $this->db->last_query(); die();
          return $query->result_array();

      }



      public function fetch_fine_list() {

            $sql = "select * from no_dues_hs_details where `is_deleted` = 0";
            $query = $this->db->query($sql);
            $query->result_array();
      }

      public function get_details_values($hostel_details_id)
      {
         
          $get_hostel_details_id =  explode(',', $hostel_details_id);

          //echoreturn print_r( $get_hostel_details_id);

          return $get_hostel_details_id;



        
      }


      public function get_item_des($hostel_details)
      {

        $sql = "select `inventory_item` from no_dues_hs_details where `id`='".$hostel_details."' and `is_deleted` = 0";
        $query = $this->db->query($sql);
        $inv_item = $query->result_array();
        return $inv_item['0']['inventory_item'];


      }

      public function get_item_amount($hostel_details)
      {

        $sql = "select `inventory_amount` from no_dues_hs_details where `id`='".$hostel_details."' and `is_deleted` = 0";
        $query = $this->db->query($sql);
        $inv_item = $query->result_array();
        return $inv_item['0']['inventory_amount'];


      }


      public function get_item_remarks($hostel_details) {

        $sql = "select `inventory_remark` from no_dues_hs_details where `id`='".$hostel_details."' and `is_deleted` = 0";
        $query = $this->db->query($sql);
        $inv_item = $query->result_array();
        return $inv_item['0']['inventory_remark']; 

      }


 
}

 ?>
