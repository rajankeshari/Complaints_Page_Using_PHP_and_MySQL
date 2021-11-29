<?php

class Get_from_final_foil extends CI_Model {

    function __construct() {
        // Call the Model constructor
        parent::__construct();
    }

    function stu_details($id) {
        $sql = "select a.id,concat_ws(' ',a.first_name,a.middle_name,a.last_name) as stu_name,b.name as dept_nm,d.name as course_nm,e.name as branch_nm,c.semester
,f.section,a.dept_id as did,c.course_id as cid,c.branch_id as bid from user_details a
inner join departments b on a.dept_id=b.id
inner join stu_academic c on c.admn_no=a.id
inner join cs_courses d on d.id=c.course_id
inner join cs_branches e on e.id=c.branch_id
left join stu_section_data f on f.admn_no=a.id
where a.id=? ";
        $query = $this->db->query($sql, array($id));
        // echo $this->db->last_query();die();
        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return FALSE;
        }
    }

    function stu_current_gpa($syear,$sess,$et,$admn_no,$sem) {
        $sql = "select a.* from final_semwise_marks_foil a
where a.session_yr=? and a.`session`=?
and a.`type`=? and a.admn_no=?
and a.semester=? and a.course<>'MINOR'";
        $query = $this->db->query($sql, array($syear,$sess,$et,$admn_no,$sem));
        // echo $this->db->last_query();die();
        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return FALSE;
        }
    }
    function get_sub_list($syear,$sess,$et,$admn_no,$sem){
        
        $sql = "
            select b.*,d.subject_id as sid,d.name,c.aggr_id,c.semester,b.mis_sub_id as subject_id,
(
CASE 
WHEN (c.aggr_id not like '%honour_%') THEN 'Core' 
WHEN (c.aggr_id like '%honour_%') THEN 'Honours' 
else  null 
END
)as 'paper_type'

from final_semwise_marks_foil a
inner join final_semwise_marks_foil_desc b on a.id=b.foil_id
inner join course_structure c on c.id=b.mis_sub_id
inner join subjects d on d.id=b.mis_sub_id
where a.session_yr=? and a.`session`=?
and a.`type`=? and a.admn_no=?
and a.semester=? and a.course<>'MINOR'
order by paper_type,b.sub_code ";
        $query = $this->db->query($sql, array($syear,$sess,$et,$admn_no,$sem));
         //echo $this->db->last_query();die();
        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return FALSE;
        }
        
    }
    function get_marks_row_from_foil_desc_table($id,$subid){
        
        $sql = "select * from final_semwise_marks_foil_desc where foil_id=? and mis_sub_id=?";
        $query = $this->db->query($sql, array($id,$subid));
        // echo $this->db->last_query();die();
        if ($this->db->affected_rows() > 0) {
            return $query->row();
        } else {
            return FALSE;
        }
        
    }
    
    function get_marks_description_row($sy,$sess,$subid,$admn_no){
        
        $sql = "select b.* from marks_master a
inner join marks_subject_description b on a.id=b.marks_master_id
where a.session_year=? and a.`session`=?
and a.subject_id=? and b.admn_no=?;";
        $query = $this->db->query($sql, array($sy,$sess,$subid,$admn_no));
        // echo $this->db->last_query();die();
        if ($this->db->affected_rows() > 0) {
            return $query->row();
        } else {
            return FALSE;
        }
        
    }
    
        function insert_old_marks_foil_bkp($data)
	{
		if($this->db->insert('final_semwise_marks_foil_desc_modration_bkp',$data))
			return TRUE;
		else
			return FALSE;
	}
        
        function update_final_foil_description_ufm($sessional1, $theory1, $tot1, $gd1, $status1, $id,$subid)
        {
         $sql = "update final_semwise_marks_foil_desc set sessional=?,theory=?,total=?,grade=?, remarks=?,cr_pts='0' where id=? and mis_sub_id=?";
        $query = $this->db->query($sql,array($sessional1, $theory1, $tot1, $gd1, $status1, $id,$subid));

        if ($this->db->affected_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }   
        }
        
        function update_final_foil_description($sessional,$theory,$total,$grade,$crpts,$id,$subid)
        {
       
        $sql = "update final_semwise_marks_foil_desc set sessional=?,theory=?,total=?,grade=?,cr_pts=? where foil_id=? and mis_sub_id=?";
        $query = $this->db->query($sql,array($sessional,$theory,$total,$grade,$crpts,$id,$subid));
        if ($this->db->affected_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
        }
        
        function get_aggrid_of_paper($fid,$sub_id){
            
            $sql = "select c.aggr_id from final_semwise_marks_foil_desc a 
                    inner join subjects b on b.id=a.mis_sub_id
                    inner join course_structure c on c.id=a.mis_sub_id
                    where a.foil_id=? and a.mis_sub_id=?";
            
            $query = $this->db->query($sql,array($fid,$sub_id));
            if ($this->db->affected_rows() > 0) {
                 return $query->row();
            } else {
                return FALSE;
            }
        
        }
        function get_credit_hours($fid,$sub_id){
            
            $sql = "select a.cr_hr from final_semwise_marks_foil_desc a
                    where a.foil_id=? and a.mis_sub_id=?";
            
            $query = $this->db->query($sql,array($fid,$sub_id));
            if ($this->db->affected_rows() > 0) {
                 return $query->row();
            } else {
                return FALSE;
            }
        }
        
        function get_gpa_after_update_foil_core_honour($admno,$foilid){
            
            $sql = "select sum(cr_hr)as chours,sum(cr_pts)as cpoints,sum(cr_pts)/sum(cr_hr) as gpa from final_semwise_marks_foil_desc a 
inner join subjects b on b.id=a.mis_sub_id
inner join course_structure c on c.id=a.mis_sub_id
where a.admn_no=? and a.foil_id=?";
            
            $query = $this->db->query($sql,array($admno,$foilid));
            if ($this->db->affected_rows() > 0) {
                 return $query->row();
            } else {
                return FALSE;
            }
        }
        
        function get_gpa_after_update_foil_core($admno,$foilid,$aggrid){
            
            $sql = "select sum(cr_hr)as chours,sum(cr_pts)as cpoints,sum(cr_pts)/sum(cr_hr) as gpa from final_semwise_marks_foil_desc a 
inner join subjects b on b.id=a.mis_sub_id
inner join course_structure c on c.id=a.mis_sub_id
where a.admn_no=? and a.foil_id=? and c.aggr_id=?";
            
            $query = $this->db->query($sql,array($admno,$foilid,$aggrid));
            if ($this->db->affected_rows() > 0) {
                 return $query->row();
            } else {
                return FALSE;
            }
        }
        
        function get_passfail_foil_core_honour($admno,$foilid){
            
            $sql = "select 
                    CASE COUNT(*)
                    WHEN '0' THEN 'PASS'  
                    ELSE 'FAIL'  
                    END as 'passfail' 
                    from final_semwise_marks_foil_desc a 
                    inner join subjects b on b.id=a.mis_sub_id
                    inner join course_structure c on c.id=a.mis_sub_id
                    where a.admn_no=? and a.foil_id=? and grade='F'";
            
            $query = $this->db->query($sql,array($admno,$foilid));
            if ($this->db->affected_rows() > 0) {
                 return $query->row();
            } else {
                return FALSE;
            }
            
        }
        
        function get_passfail_foil_core($admno,$foilid,$aggrid){
            
            $sql = "select 
                    CASE COUNT(*)
                    WHEN '0' THEN 'PASS'  
                    ELSE 'FAIL'  
                    END as 'passfail' 
                    from final_semwise_marks_foil_desc a 
                    inner join subjects b on b.id=a.mis_sub_id
                    inner join course_structure c on c.id=a.mis_sub_id
                    where a.admn_no=? and a.foil_id=?
                    and c.aggr_id=? and grade='F'";
            
            $query = $this->db->query($sql,array($admno,$foilid,$aggrid));
            if ($this->db->affected_rows() > 0) {
                 return $query->row();
            } else {
                return FALSE;
            }
            
        }
        
        function get_row_details_from_foil($admno,$foilid){
            
            $sql = "SELECT * FROM final_semwise_marks_foil  WHERE  admn_no=? and id=?";
            
            $query = $this->db->query($sql,array($admno,$foilid));
            if ($this->db->affected_rows() > 0) {
                 return $query->row();
            } else {
                return FALSE;
            }
            
        }


}
