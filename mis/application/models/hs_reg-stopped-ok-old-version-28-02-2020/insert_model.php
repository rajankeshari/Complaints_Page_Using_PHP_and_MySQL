
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
    



}

 ?>
