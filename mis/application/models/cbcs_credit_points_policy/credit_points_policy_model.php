<?php

class Credit_points_policy_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

        
    function get_course_list()
    {
          
      $sql = "select * from cbcs_courses  where status='1' order by name";
        
        
        $query = $this->db->query($sql);

       
        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    function insert($data)
    {
        if($this->db->insert('cbcs_credit_points_policy',$data))
            return TRUE;
        else
            return FALSE;
    }

    function insert_backup($id,$action){
        
        $sql = "insert into cbcs_credit_points_policy_backup select * from cbcs_credit_points_policy where id=?";
        $query = $this->db->query($sql,array($id));

        $sql = "update cbcs_credit_points_policy_backup set action='".$action."' where id=".$id;
        $query = $this->db->query($sql);

        if($action=='modify'){
            $sql = "update cbcs_credit_points_policy set action='".$action."' where id=".$id;
            $query = $this->db->query($sql);
        }

        if ($this->db->affected_rows() > 0) {
            return TRUE;
        } else {
            return false;
        }


    }

    function get_credit_point_policy_details(){
    $sql = "select a.*,b.name as cname,b.`status` as cstatus from cbcs_credit_points_policy a
inner join cbcs_courses b on b.id=a.course_id  order by a.wef,cname";
        
        
        $query = $this->db->query($sql);

       
        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    function delete_rowid($id) {
        $this->db->where('id', $id);
        $this->db->delete('cbcs_credit_points_policy');
    }

    function get_row_details($rowid)
    {
       
        
        $sql="select * from cbcs_credit_points_policy where id=?";
            $query = $this->db->query($sql,array($rowid));
         
            if ($this->db->affected_rows() > 0) {
                return $query->row();
            } else {
                return false;
            }
    }

     function update($data,$con)
    {
        $con1['id'] = $con;
         if($this->db->update('cbcs_credit_points_policy',$data,$con1))
         {
                    return true;
         } 
            return false;
            
    }



    

}

?>