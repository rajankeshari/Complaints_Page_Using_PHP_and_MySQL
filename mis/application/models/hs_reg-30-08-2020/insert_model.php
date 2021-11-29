
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
        // $this->db->from('edit_hs_item_inventory');

        // $query = $this->db->get();

        $sql = "select * from edit_hs_item_inventory where inventory_id=".$id;

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
          $this->db->insert('hs_add_inventory_list',$data);
          return $this->db->insert_id();
      }


      public function get_inventory_list()
      {
          $sql = 'select * from hs_add_inventory_list';
          $query = $this->db->query($sql);
          //echo $this->db->last_query(); die();
          return $query->result_array();
      }

      public function delete_inventory_list($inventory_list)
      {

        $sql = "delete from `hs_add_inventory_list` where id='".$inventory_list."'";

        $query = $this->db->query($sql);

      }

      public function get_inventory_list_data($id)
      {
          $sql = "select * from `hs_add_inventory_list` where id='".$id."'";
          $query = $this->db->query($sql);

          return $query->result_array();

      }

      public function edit_inventory_list_data($edit_item)
      
      {

        //print_r($edit_item);   exit;

        $this->db->insert('edit_hs_item_inventory',$edit_item);
          return $this->db->insert_id();


      }

      public function change_inventory_status($inventory_list)
      {

        $sql = "select * from `hs_add_inventory_list` where id='".$inventory_list."'";

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
        $this->db->update('hs_add_inventory_list', $data);

        //echo $this->db->last_query(); die();

        $sql = "select * from `hs_add_inventory_list` where id='".$inventory_list."'";
        $query = $this->db->query($sql);
        //echo $this->db->last_query(); die();
        $data = $query->result();
        //print_r($data);

        $status = $data['0']->status;

        return $status;

       

      }

      public function get_inventory_list_item()
      {
          $sql = "select * from `hs_add_inventory_list`";
          $query = $this->db->query($sql);
          return $query->result_array();
      }


      public function edit_inventory_list_item($id, $edit_item)

      {

        $this->db->where('id', $id);
        $this->db->update('hs_add_inventory_list', $edit_item);

      }

      public function get_student_details($admn_no)
      {

        $sql = "SELECT * FROM `user_details` a INNER JOIN `hs_assigned_student_room` b ON a.id = b.admn_no INNER JOIN `hs_room_details` c ON c.id = b.room_detail_id INNER JOIN `hs_hostel_details` d ON d.id = c.hostel_detail_id WHERE b.admn_no = '$admn_no' ORDER BY b.modify_date DESC LIMIT 1";
        $query = $this->db->query($sql);

        //print_r($query->result_array()); exit;

        return json_encode($query->result_array());

      }

      public function get_inventory_item_price($item)
      {

        $sql = "select `rate_unit_price` from `hs_add_inventory_list` where `description` = '$item'";
        $query = $this->db->query($sql);
        $amount = $query->result_array();
        return $amount['0']['rate_unit_price'];

      }

 
}

 ?>
