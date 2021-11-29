<?php

class jrf_mapping_model extends CI_Model 
{

    function __construct() 
    {
        
        parent::__construct();
    }

    public function get_jrf_details($dept, $session, $session_year) 
    {
        $sql = "select a.sub_id,c.name,d.aggr_id,c.subject_id,e.dept_id,f.emp_no 
                from  reg_exam_rc_subject as a 
                join reg_exam_rc_form as b 
                on a.form_id=b.form_id 
                join subjects as c 
                on a.sub_id=c.id
                join course_structure as d 
                on a.sub_id=d.id
                left join dept_course as e 
                on d.aggr_id=e.aggr_id
                left join subject_mapping_des as f 
                on a.sub_id=f.sub_id
                where b.course_id='jrf'
                and f.emp_no is null
                and b.session_year='".$session_year."'
                and b.`session`='".$session."'
                and e.dept_id='".$dept."'
                group by a.sub_id
                order by e.dept_id,c.name asc  ";

        $query = $this->db->query($sql);
         print_r($query->result());
        if ($this->db->affected_rows() >= 0) 
        {
            return $query->result();
        } 
        else 
        {
            return false;
        }
    }
    function checkJRFmapping($con,$u='')
    {
        $q = $this->db->get_where('subject_mapping',$con);
        if($q->num_rows()  > 0){
        if($u){
            return true;
        }
        return $q->row();        
       }
       return false;
    }
    
    function checkJRFmappingSpecific($sy, $session, $sub_id)
    {
        $query = "SELECT *
                    FROM (SELECT *
                            FROM subject_mapping 
                            WHERE session_year = '$sy' AND session = '$session')AS map_jrf
                    INNER JOIN (SELECT *
                                FROM subject_mapping_des
                                WHERE sub_id='$sub_id') as map_sub
                    ON map_jrf.map_id = map_sub.map_id";
        if($this->db->query($query)->result())
            return true;
        return false;
    }

    public function getJRFDept($data)
    {
        $sub = $data['sub_id'];
        $session = $data['session'];
        $session_year = $data['session_year'];
        $query =  ("SELECT dept.id as id, dept.name as `name` 
                    FROM departments AS dept
                    INNER JOIN (SELECT DISTINCT dept_id 
                                FROM user_details 
                                INNER JOIN (SELECT admn_no, jrf_sub.form_id
                                            FROM reg_exam_rc_form
                                            INNER JOIN (SELECT *
                                                        FROM reg_exam_rc_subject 
                                                        WHERE sub_id = '$sub') as jrf_sub
                                            ON jrf_sub.form_id = reg_exam_rc_form.form_id
                                            WHERE course_id = 'jrf' AND branch_id = 'jrf' AND hod_status = '1'
                                            AND acad_status = '1' 
                                            AND reg_exam_rc_form.session = '$session' 
                                            AND reg_exam_rc_form.session_year='$session_year') as jrf_reg
                                ON user_details.id = jrf_reg.admn_no) as jrf_dept
                    ON jrf_dept.dept_id = dept.id");
        return $this->db->query($query)->result_array();
    }

    function get_jrf_dept($data)
    {
        $this->load->database();
                $sub = $data['sub_id'];
        $session = $data['session'];
        $session_year = $data['session_year'];

        // print_r($data);
       $query=(" SELECT DISTINCT departments.id,departments.name FROM `departments` JOIN `user_details` ON departments.id=user_details.dept_id JOIN `reg_exam_rc_form` ON user_details.id=reg_exam_rc_form.admn_no JOIN `reg_exam_rc_subject` ON reg_exam_rc_form.form_id=reg_exam_rc_subject.form_id WHERE course_id='jrf' AND session='$session' AND session_year='$session_year' AND branch_id='jrf' AND hod_status='1' AND acad_status='1' AND sub_id='$sub' ");
   
        return $this->db->query($query)->result_array();

    }

    function get_all_dept()
    {
        $this->load->database();

        $query=("SELECT * FROM departments WHERE type='academic' ");
                return $this->db->query($query)->result_array();

    }

    function getsubject($id)
    {
        return $this->db->get_where('subjects',array('id'=>$id))->row();
    }
    
    function GetCoreSubjectDept($sub,$session,$sessionYear)
    {
        $q="select d.dept_id from  reg_exam_rc_subject as a 
        join reg_exam_rc_form as b on a.form_id=b.form_id 
        join subjects as c on a.sub_id=c.id
        join user_details as d on b.admn_no=d.id
        where a.sub_id='MS_1'
        and b.session_year='2015-2016'
        and b.`session`='Monsoon'
        and b.hod_status='1'
        and b.acad_status='1'
        group by d.dept_id
        order by d.dept_id";
        $qu = $this->db->query($q,array($sub,$session,$sessionYear));
        if($qu->num_rows() > 0 )
        {
            return $qu->result();
        }
        return false;
    }
    
    function addJrfdept_id($data)
    {
       if( $this->db->insert('subject_mapping_jrf_dept',$data))
       {
           return true;
       }
       return false;
            
    }
    function getJrfDeptById($id)
    {
        $q='select * from subject_mapping_des as a 
            join subject_mapping_jrf_dept as b on 
            a.id=b.map_des_id
            where a.map_id=?';
        
       $qu= $this->db->query($q,array($id));
       if($qu->num_rows() > 0 ){
            return $qu->result();
        }
        return false;
    }
    
    function deletemapDesByid($id){
       // echo $id; die();
        $this->db->trans_start();
        $this->db->delete('subject_mapping_des', array('map_id'=>$id));
        $this->db->delete('subject_mapping_jrf_dept', array('map_id'=>$id));
        $this->db->trans_complete();
    }
}
	