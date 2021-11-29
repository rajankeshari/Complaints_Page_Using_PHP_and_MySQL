<?php

class Delete_attendance_model extends CI_Model {

    function __construct() {
        // Call the Model constructor
        parent::__construct();
    }

    
    
        function get_faculty_byDept($id) {
        $myquery = "select a.id,concat_ws(' ',a.first_name,a.middle_name,a.last_name)as fname from user_details a 
inner join emp_basic_details b on b.emp_no=a.id
inner join users c on c.id=a.id
where a.dept_id=? and b.auth_id='ft' and c.`status`='A'
order by a.first_name,a.middle_name,a.last_name" ;
      
        $query = $this->db->query($myquery,array($id));

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return FALSE;
        }
    }
    function get_faculty_name($fid){
        $myquery = "select concat_ws(' ',first_name,middle_name,last_name)as fname from user_details where id=?" ;
      
        $query = $this->db->query($myquery,array($fid));

        if ($query->num_rows() > 0) {
            return $query->row()->fname;
        } else {
            return FALSE;
        }


    }
    
    function get_faculty_subject($sy,$sess,$fid){
        $myquery = "select a.sub_id,c.subject_id,c.name,d.semester,d.aggr_id,e.session_id,f.dept_id,g.course_id,g.branch_id,a.map_id,h.name as dname,i.name as cname,j.name as bname,b.section from subject_mapping_des a
inner join subject_mapping b on b.map_id=a.map_id
inner join subjects c on c.id=a.sub_id
inner join course_structure d on d.id=a.sub_id
inner join dept_course f on f.aggr_id=d.aggr_id
inner join course_branch g on g.course_branch_id=f.course_branch_id
left join total_class_table e on e.map_id=a.map_id and e.sub_id=a.sub_id
inner join departments h on h.id=f.dept_id
inner JOIN cs_courses i on i.id=g.course_id
inner join cs_branches j on j.id=g.branch_id
where b.session_year=? and b.`session`=? and a.emp_no=? order by c.name,b.section" ;
      
        $query = $this->db->query($myquery,array($sy,$sess,$fid));

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return FALSE;
        }
        
    }
    function get_total_class($map_id,$subid,$session_id){
        $myquery = "select a.* from total_class_table a
where a.map_id=? and a.sub_id=? and a.session_id=?" ;
      
        $query = $this->db->query($myquery,array($map_id,$subid,$session_id));

        if ($query->num_rows() > 0) {
            return $query->row();
        } else {
            return FALSE;
        }
    }

    function get_class_engage($map_id,$subid,$session_id){
        $myquery = " SELECT a.*,STR_TO_DATE( a.date, '%d-%m-%Y' )as date1
FROM class_engaged a
WHERE a.map_id=? AND a.sub_id=? AND a.session_id=?
GROUP BY a.date
ORDER BY STR_TO_DATE( a.date, '%d-%m-%Y' ) DESC" ;
      
        $query = $this->db->query($myquery,array($map_id,$subid,$session_id));
//echo $this->db->last_query();die();
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return FALSE;
        }
    }
    
    function get_attendance_defaulter_status($map_id,$subid,$session_id){
        $myquery = "select a.* from absent_table a where a.map_id=? and a.sub_id=? and a.session_id=? and a.`status`='2'" ;
      
        $query = $this->db->query($myquery,array($map_id,$subid,$session_id));

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return FALSE;
        }
    }
    
    
    
    function insert_absent_table_bkp($mapid,$subid,$sessionid,$dtype){
            if($dtype=='all'){
                $where="";
            }else{
                $where=" and a.date='".$dtype."'";
            }

        $query = $this->db->query("INSERT INTO absent_table_bkp 
                                    select a.* from absent_table a 
                                    where a.map_id='".$mapid."' and a.sub_id='".$subid."' and a.session_id='".$sessionid."'".$where);

        //echo $this->db->last_query();die();
        if($this->db->affected_rows()){
          return true;
        }
        else{
          return false;
        }
    }
    function insert_absent_table_bkp_auth($data){
        if($this->db->insert('absent_table_bkp_auth',$data))
			return $this->db->insert_id();
		else
			return FALSE;
    }
    
    function delete_attendance_table($mapid,$subid,$sessionid,$dtype){
        if($dtype=='all'){
                $where="";
            }else{
                $where=" and date='".$dtype."'";
            }
//echo "delete from absent_table where map_id='".$mapid."' and sub_id='".$subid."' and session_id='".$sessionid."'".$where;
//die();
        $query = $this->db->query("delete from absent_table where map_id='".$mapid."' and sub_id='".$subid."' and session_id='".$sessionid."'".$where);
        //echo $this->db->last_query();
        if($this->db->affected_rows()){
          return true;
        }
        else{
          return false;
        }
    }
    //for ta
    function delete_attendance_table_ta($mapid,$subid,$sessionid,$dtype){
        if($dtype=='all'){
                $where="";
            }else{
                $where=" and date='".$dtype."'";
            }

        $query = $this->db->query("delete from absent_table_ta where map_id='".$mapid."' and sub_id='".$subid."' and session_id='".$sessionid."'".$where);
        //echo $this->db->last_query();
        if($this->db->affected_rows()){
          return true;
        }
        else{
          return false;
        }
    }
    
    function delete_class_engaged($mapid,$subid,$sessionid,$dtype){
        if($dtype=='all'){
                $where="";
            }else{
                $where=" and date='".$dtype."'";
            }
        $query = $this->db->query("delete from class_engaged where map_id='".$mapid."' and sub_id='".$subid."' and session_id='".$sessionid."'".$where);
        //echo $this->db->last_query();
        if($this->db->affected_rows()){
          return true;
        }
        else{
          return false;
        }
    }
    //ta
    function delete_class_engaged_jrf($mapid,$subid,$sessionid,$dtype){
        if($dtype=='all'){
                $where="";
            }else{
                $where=" and date='".$dtype."'";
            }
        $query = $this->db->query("delete from class_engaged_jrf where map_id='".$mapid."' and sub_id='".$subid."' and session_id='".$sessionid."'".$where);
        //echo $this->db->last_query();
        if($this->db->affected_rows()){
          return true;
        }
        else{
          return false;
        }
    }
    
    function delete_total_class_table($mapid,$subid,$sessionid,$dtype){
        if($dtype=='all'){
            $query = $this->db->query("delete from total_class_table where map_id='".$mapid."' and sub_id='".$subid."' and session_id='".$sessionid."'");
                
            }else{

                //$cc=$this->count_class_particular_date($subid,$mapid,$dtype);
				$cc=1;
                $query = $this->db->query("update total_class_table set total_class=total_class-".$cc." where map_id='".$mapid."' and sub_id='".$subid."' and session_id='".$sessionid."'");
                
            }
       // echo $this->db->last_query();die();
        if($this->db->affected_rows()){
          return true;
        }
        else{
          return false;
        }
    }
    function count_class_particular_date($subid,$mapid,$date){
        $sql="SELECT COUNT(class_no) AS max1 FROM class_engaged WHERE sub_id =? AND map_id=?  AND DATE=?";

        //echo $sql;die();
        $query = $this->db->query($sql,array($subid,$mapid,$date));

       //echo $this->db->last_query();
        if ($this->db->affected_rows() > 0) {
            $p= $query->row()->max1;
            return $p;
        } else {
            return false;
        }
    }
    function delete_total_class_table_jrf($mapid,$subid,$sessionid,$dtype){
        if($dtype=='all'){
            $query = $this->db->query("delete from total_class_table_jrf where map_id='".$mapid."' and sub_id='".$subid."' and session_id='".$sessionid."'");
                
            }else{

                $query = $this->db->query("update total_class_table_jrf set total_class=total_class-1 where map_id='".$mapid."' and sub_id='".$subid."' and session_id='".$sessionid."'");
                
            }
      //  echo $this->db->last_query();
        if($this->db->affected_rows()){
          return true;
        }
        else{
          return false;
        }
    }
    
    function delete_Attendance_remark_table($subid,$sessionid){
        $query = $this->db->query("delete from Attendance_remark_table where sub_id='".$subid."' and session_id='".$sessionid."'");
        if($this->db->affected_rows()){
          return true;
        }
        else{
          return false;
        }
    }
    
    function get_deleted_attendance($sy,$sess,$fid){
        $myquery = "select a.*,d.name as cname,e.name as bname,c.semester,f.subject_id,f.name,UNIX_TIMESTAMP(a.timestamp) as date1 from absent_table_bkp_auth a
inner join subject_mapping_des b on b.map_id=a.map_id  and b.sub_id=a.sub_id
inner join subject_mapping c on c.map_id=b.map_id
inner join cs_courses d on d.id=c.course_id
inner join cs_branches e on e.id=c.branch_id
inner join subjects f on f.id=b.sub_id
where c.session_year=? and c.`session`=?
and b.emp_no=?
order by date1 desc" ;
      
        $query = $this->db->query($myquery,array($sy,$sess,$fid));

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return FALSE;
        }
        
    }
            

}
