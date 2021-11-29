<?php

class Credit_point_master_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

        
    

   

    

    function insert($data)
    {
        if($this->db->insert('cbcs_credit_points_master',$data))
            return TRUE;
        else
            return FALSE;
    }

    function insert_backup($id,$action){
        
        $sql = "insert into cbcs_credit_points_master_backup select * from cbcs_credit_points_master where id=?";
        $query = $this->db->query($sql,array($id));

        $sql = "update cbcs_credit_points_master_backup set action='".$action."' where id=".$id;
        $query = $this->db->query($sql);

        if($action=='modify'){
            $sql = "update cbcs_credit_points_master set action='".$action."' where id=".$id;
            $query = $this->db->query($sql);
        }

        if ($this->db->affected_rows() > 0) {
            return TRUE;
        } else {
            return false;
        }


    }

    function get_credit_point_master_details(){
//     $sql = "select a.*,b.name as cname,c.name as bname,d.name as dname from cbcs_credit_points_master a
// inner join cbcs_courses b on b.id=a.course_id 
// inner join cbcs_branches c on c.id=a.branch_id
// inner join cbcs_departments d on d.id=a.dept_id
// order by a.wef,cname";

        $sql="select a.*,b.name as cname,c.name as bname,d.name as dname,d.`status` as dstatus,e.mincp as pmincp,e.maxcp as pmaxcp from cbcs_credit_points_master a
inner join cbcs_courses b on b.id=a.course_id 
inner join cbcs_branches c on c.id=a.branch_id
inner join cbcs_departments d on d.id=a.dept_id
inner join cbcs_credit_points_policy e on e.course_id=a.course_id
order by a.wef,cname";
        
        
        $query = $this->db->query($sql);

       
        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }
	
	
	function get_credit_point_master_details_dept_wise($id){
//     $sql = "select a.*,b.name as cname,c.name as bname,d.name as dname from cbcs_credit_points_master a
// inner join cbcs_courses b on b.id=a.course_id 
// inner join cbcs_branches c on c.id=a.branch_id
// inner join cbcs_departments d on d.id=a.dept_id
// order by a.wef,cname";

        $sql="select a.*,b.name as cname,c.name as bname,d.name as dname,d.`status` as dstatus,e.mincp as pmincp,e.maxcp as pmaxcp from cbcs_credit_points_master a
inner join cbcs_courses b on b.id=a.course_id 
inner join cbcs_branches c on c.id=a.branch_id
inner join cbcs_departments d on d.id=a.dept_id
inner join cbcs_credit_points_policy e on e.course_id=a.course_id
where a.dept_id='$id' order by a.wef,cname";
        
        
        $query = $this->db->query($sql);

       
        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    function delete_rowid($id) {
        $this->db->where('id', $id);
        $this->db->delete('cbcs_credit_points_master');
    }

    function get_row_details($rowid)
    {
       
        
        $sql="select * from cbcs_credit_points_master where id=?";
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
         if($this->db->update('cbcs_credit_points_master',$data,$con1))
         {
                    return true;
         } 
            return false;
            
    }

    function get_cbcs_credit_points_policy_data($sy, $cid){

        $sql="select mincp,maxcp from cbcs_credit_points_policy where wef=? and course_id=?";
            $query = $this->db->query($sql,array($sy,$cid));
         
            if ($this->db->affected_rows() > 0) {
                return $query->row();
            } else {
                return false;
            }



    }



    

}

?>