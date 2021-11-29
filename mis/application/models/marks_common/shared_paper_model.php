<?php

class Shared_paper_model extends CI_Model {

    function sectionGroup($session_year) {
//echo "inmodel";
        $q = $this->db->get_where('section_group_rel', array('session_year' => $session_year, 'section !=' => "PURE")); // 'pure ' condition was added @3-5-18 @rituraj
        if ($q->num_rows() > 0) {
            return $q->result_array();
        }
        return false;
    }

    function getSectionMarks($sub_map_id, $subject_id) {
        $q = $this->db->get_where('marks_master', array('sub_map_id' => $sub_map_id, 'subject_id' => $subject_id, 'type' => 'R'));
        if ($q->num_rows() > 0) {
            return $q->result_array();
        }
        return false;
    }

    function update_highest_and_grade($marks_master_id_arr) {
        // $this->db->trans_start();
        $this->db->trans_begin();
        $affected = $affected_ctr = array();
        for ($i = 0; $i < count($marks_master_id_arr); $i++) {
            $arr = $marks_master_id = $highest = null;
            $arr = explode('#', $marks_master_id_arr[$i]);
            $marks_master_id = $arr[0];
            $highest = $arr[1];
            /* echo $marks_master_id . '#' . $highest;
              die(); */
            //getting  old record
            $this->db->select('highest_marks');
            $this->db->from('marks_master');
            $this->db->where(array('id' => $marks_master_id));
            $select = $this->db->get();
            //echo $this->db->last_query(); die();
            $affected[] = $select->num_rows();
            if ($select->num_rows()) {
                $old_highest = null;
                //print_r($select->result_array() );die();
                $row = $select->result_array();
                $old_highest = $row[0]['highest_marks'];
            }


            $this->db->update('marks_master', array('highest_marks' => $highest), array('id' => $marks_master_id, 'status' => 'Y'));
            $affected[] = $this->db->affected_rows();
            $lastlog_id = null;
            $this->db->insert('marks_master_grade_change_log', array('marks_master_id' => $marks_master_id, 'old_highest' => $old_highest, 'created_by' => $this->session->userdata('id')));
            $lastlog_id = $this->db->insert_id();
            //echo $this->db->last_query(); die();
            $affected[] = $this->db->affected_rows();
            //echo $this->db->last_query();
            //$this->get_grade_change_details($marks_master_id, $highest);
            //echo $this->db->last_query();
            //die();
            $mm_subject_details = null;
            $mm_subject_details = $this->get_grade_change_details($marks_master_id, $highest);
            if (sizeof($mm_subject_details) > 0) {
                $affected[] = $affected_ctr[] = $this->update_grade($highest, $marks_master_id);
                $row1 = null;
                foreach ($mm_subject_details as $row1) {
                    $this->db->insert('marks_subject_description_grade_change_log', array('id' => $lastlog_id, 'marks_master_id' => $marks_master_id, 'admn_no' => $row1['admn_no'], 'old_grade' => $row1['grade']));
                    //echo $this->db->last_query();
                }
            }

            //  echo $this->db->last_query();
        }

        $this->db->trans_complete();
        if (in_array(0, $affected) || ($this->db->trans_status() === FALSE)) {
            $returntmsg .= "Error while updating: " . $this->db->_error_message() . ",";
            $this->db->trans_rollback();
        } else
            $returntmsg = $affected_ctr;



        //echo $returntmsg; die();
        return $returntmsg;
    }

    function update_grade($highest, $marks_master_id) {

        $q = " update marks_subject_description b join marks_master a  on a.id=b.marks_master_id set b.grade= "
                . "(select (CASE  WHEN b.sub_type='T' AND b.theory < 21 THEN 'F'   WHEN   b.total  <35  and  ( a.type='R' or a.type='O') THEN 'F' WHEN  b.total  <41  AND a.type='J'   THEN 'F' ELSE c.grade END) as grade"
                . " from relative_grading_table c where b.total between c.min and c.max and c.highest_marks=? ) where b.marks_master_id=? ";

        if ($this->db->query($q, array($highest, $marks_master_id)))
            return $this->db->affected_rows();
        return false;
    }

    function get_grade_change_details($marks_master_id, $propsed_highest, $param = 'only_matched', $session = null, $session_year = null, $subject_id = null, $exam_type = null) {

        if ($param == 'only_matched') {
            $q_str_pre = " where (case when z.grade is null  then 'X' else z.grade end )<>z.to_be_grade ";
        } else
            $q_str_pre = "";



        if ($session <> null && $session_year <> null && $exam_type <> null && $subject_id <> null) {
            $str1 = " AND a.session_year=? AND a.session=? AND a.type=? ";
            $str2 = "  and s.subject_id=? ";
            $secure_array = array($marks_master_id, $session_year, $session, $exam_type, $subject_id, $propsed_highest, $propsed_highest, $propsed_highest);
        } else {
            $str1 = '';
            $str2 = '';
            $secure_array = array($marks_master_id, $propsed_highest, $propsed_highest, $propsed_highest);
        }

        $this->load->database();
        $sql = "	select z.* from(
		 select  a.`session`,a.session_year,a.subject_id, s.subject_id as sub_code  ,a.`status`,a.highest_marks , c.highest_marks as rel_highest,
   b.*  ,
  (
   CASE
          WHEN b.sub_type='T' AND b.theory < 21 THEN 'F'
	  WHEN   b.total  <35  and   (a.type='R' or  a.type='J') THEN 'F'
          WHEN  b.total  <41  AND a.type='J' THEN 'F'
	  ELSE c.grade
   END
  ) AS to_be_grade,a.sub_map_id
    from   marks_subject_description b
         inner join marks_master a
			on a.id=b.marks_master_id    and   a.id=?
		          $str1  AND a.status='Y' /*and b.grade is  null*/ and b.total is not null

          inner join  subjects s on  a.subject_id=s.id    $str2


    		left join relative_grading_table c
			on  ? = c.highest_marks
			and  (case
               when (  b.total < ?) then  b.total BETWEEN c.min AND c.max
               else ?  BETWEEN c.min AND c.max
               end
                ) order by b.admn_no	)z

                 $q_str_pre

								";

        $query = $this->db->query($sql, $secure_array);

        $result = $query->result_array();




        /* echo '<pre>';
          print_r($result);
          echo '</pre>';
          die(); */
        return $result;
    }

    function get_reverse_shared_details($session, $session_year) {
        // find  hiehest  discrpancy among regular shared subjects (across regular courses including jrf ) enlisting papers whose marks even not submitted but mapped and
        // considering  fact that highest   marks can  never be shared across  resular courses & jrf howvere shared paper highest can  be shared across JRF unit  only

        $this->load->database();
        $sql = "

  SELECT z.*, MAX(z.highest_marks) AS maxheighest, GROUP_CONCAT(z.subject_id) AS subject_ids_list,
 GROUP_CONCAT(CONCAT((IF(`z`.`id` IS NOT NULL, `z`.`highest_marks`, 'Marks not uploaded')), '#', '[', if(`z`.`id` is not null , (case when `z`.`type` = 'J' then 'JRF' when `z`.`type` = 'R' then 'REGULAR' when `z`.`type` = 'O' then 'CARRY'  end ),
 (IF(lower(`z`.course_id) = 'jrf', 'JRF', 'REGULAR')) ) , ']','#','dept:', `z`.`dept_id`, '#', 'course:', `z`.`course_id`, '#', 'branch:', `z`.`branch_id`, '#', 'sem:', `z`.`semester` ,'#',(case when z.id is not null then z.id  else '[marks_not_uploaded]' end ) )
  ORDER BY 0+`z`.type ) AS 'shared_details', group_concat( (case when z.id is not null then z.id else '[marks_not_uploaded]' end ) order by 0+`z`.type ) as marks_master_id_list ,
    count((case when z.id is not null then z.subject_id  end )) as ctr,
  group_concat( (case when z.id is not null then z.highest_marks else '[marks_not_uploaded]' end ) order by 0+`z`.type)as highest_marks_list , sum((case when z.id is not
	 null then z.highest_marks  end )) as sum_tot, group_concat((case when z.id is not null then z.type else '[marks_not_uploaded]' end ) order by 0+`z`.type) as type_list,
             sum( (case when z.id is not null  and  z.type='J' then 1 else 0 end ) ) as shared_jrf_count,
     max( (case when z.id is not null  and  z.type='J' then z.propsed_heighest end ) ) as shared_jrf_highest,
	 group_concat( (select concat_ws('',first_name,middle_name,last_name) from user_details where id= z.emp_id)) as Name_of_faculty_uploaded_marks,
	  group_concat( (case when z.id is not null then z.propsed_heighest else '[marks_not_uploaded]' end ) order by 0+`z`.type)as propsed_heighest_list,

	   GROUP_CONCAT(CONCAT(  CAST( (IF(`z`.`id` IS NOT NULL,   `z`.`propsed_heighest`, 'Marks not uploaded')) AS CHAR CHARACTER SET utf8)   , '#', '[', if(`z`.`id` is not null , (case when `z`.`type` = 'J' then 'JRF' when `z`.`type` = 'R' then 'REGULAR' when `z`.`type` = 'O' then 'CARRY'  end ),
 (IF(lower(`z`.course_id) = 'jrf', 'JRF', 'REGULAR')) ) , ']','#','dept:', `z`.`dept_id`, '#', 'course:', `z`.`course_id`, '#', 'branch:', `z`.`branch_id`, '#', 'sem:', `z`.`semester` ,'#',(case when z.id is not null then z.id  else '[marks_not_uploaded]' end ) )
  ORDER BY 0+`z`.type )  AS 'shared_propsed_details'


	  from

	 (select x.*,y.* from
	    (select s.name,a.map_id,a.dept_id,a.course_id,a.branch_id,a.semester,a.section , a.aggr_id, b.sub_id ,a.creater_id as mapper,
	 b.emp_no as mapped_to ,s.subject_id as mapped_sub_code from subject_mapping a join subject_mapping_des b on a.map_id=b.map_id JOIN subjects s ON b.sub_id=s.id and b.coordinator='1'
	  and dept_id<>'comm' and session_year=? and session=? and b.emp_no<>727 group by a.map_id,b.sub_id
	  )x

	  left join
	  (SELECT marks_master.id,marks_master.type,marks_master.status,sub_map_id,marks_master.subject_id, s.subject_id AS sub_code,marks_master.highest_marks,marks_master.emp_id
	  ,(case when max(mmd.total) is null then '0' else max(mmd.total) end) as propsed_heighest
	  FROM marks_master
	  join  marks_subject_description mmd  on marks_master.id=mmd.marks_master_id
	  JOIN subjects s ON marks_master.subject_id=s.id AND
	   session_year=? AND SESSION=? AND s.credit_hours<>'0' AND marks_master.type <>'O' AND marks_master.status='Y' AND emp_id<>727
		group by mmd.marks_master_id
		ORDER BY sub_code,highest_marks)y
		  on y.sub_map_id=x.map_id and y.subject_id=x.sub_id order by x.dept_id,x.course_id,x.branch_id,x.semester
		)z
		  group by z.mapped_sub_code having ctr>1
                   /* and maxheighest*ctr=sum_tot*/
                   /*and LOCATE('J',type_list) */

		  order by z.mapped_sub_code /*limit 5*/ ";


        $query = $this->db->query($sql, array($session_year, $session, $session_year, $session));

        $result = $query->result_array();


        /* echo '<pre>';
          print_r($result);
          echo '</pre>';
          die(); */
        return $result;
    }

    function get_shared_paper_details($session, $session_year) {
// find  hiehest  discrpancy among regular shared subjects (across regular courses including jrf ) enlisting papers whose marks even not submitted but mapped

        $this->load->database();
        $sql = "
  select z.* ,
   max( z.highest_marks) as maxheighest ,
   group_concat(z.subject_id) as  subject_ids_list,
    GROUP_CONCAT(CONCAT(
                         (IF(`z`.`id` IS NOT NULL, `z`.`highest_marks`, 'Marks not uploaded')), '#', '[',     if(`z`.`id` is not null ,  (IF(`z`.`type` = 'J', 'JRF', 'REGULAR')),    (IF(lower(`z`.course_id) = 'jrf', 'JRF', 'REGULAR'))  ) , ']','#','dept:', `z`.`dept_id`, '#', 'course:', `z`.`course_id`, '#', 'branch:', `z`.`branch_id`, '#', 'sem:', `z`.`semester`,'#',(case when z.id is not null then z.id  else '[marks_not_uploaded]' end )
                         )
                        ORDER BY 0+`z`.type
                ) AS 'shared_details',



  group_concat( (case when z.id is not null then z.id  else '[marks_not_uploaded]' end ))  as marks_master_id_list , count(z.subject_id) as ctr ,
  group_concat( (case when z.id is not null then z.highest_marks else '[marks_not_uploaded]' end ) ORDER BY 0+`z`.type)as highest_marks_list ,
  sum((case when z.id is not null then z.highest_marks else 1 end )) as sum_tot,
  group_concat((case when z.id is not null then z.type else '[marks_not_uploaded]' end )  ORDER BY 0+`z`.type)  as  type_list,
  group_concat( (select concat_ws('',first_name,middle_name,last_name) from user_details where id= z.emp_id)) as Name_of_faculty_uploaded_marks

from

  (select x.*,y.* from


(select   s.name,a.map_id,a.dept_id,a.course_id,a.branch_id,a.semester,a.section , a.aggr_id, b.sub_id ,a.creater_id as mapper,   b.emp_no as mapped_to ,s.subject_id as mapped_sub_code
  from subject_mapping a  join subject_mapping_des b on a.map_id=b.map_id
  JOIN subjects s  ON b.sub_id=s.id
  and b.coordinator='1' and session_year=? and session=?
  and b.emp_no<>727 group by a.map_id,b.sub_id )x
  left join

(SELECT marks_master.id,marks_master.type,marks_master.status,sub_map_id, marks_master.subject_id, s.subject_id AS sub_code,marks_master.highest_marks,marks_master.emp_id
FROM marks_master
JOIN subjects s  ON marks_master.subject_id=s.id AND session_year=? AND SESSION=? AND
 s.credit_hours<>'0' AND marks_master.type <>'O' AND marks_master.status='Y' AND emp_id<>727
ORDER BY sub_code, highest_marks)y
on y.sub_map_id=x.map_id  and y.subject_id=x.sub_id


order by x.dept_id,x.course_id,x.branch_id,x.semester)z




	    	group by z.mapped_sub_code


      having ctr>1   /*and   maxheighest*ctr<>sum_tot and LOCATE('J',type_list)*/
		 order by z.dept_id,z.course_id,z.branch_id,z.semester,z.mapped_sub_code";


        $query = $this->db->query($sql, array($session_year, $session, $session_year, $session));

        $result = $query->result_array();


        /* echo '<pre>';
          print_r($result);
          echo '</pre>';
          die(); */
        return $result;
    }

//mid is in Array
    /* function get_comm_grading_all($max,$mid){

      if( is_array($mid) ){
      $d=implode(',',$mid);
      }else{
      $d=$mid;
      }

      $q="update marks_master a join marks_subject_description b  on a.id=b.marks_master_id set b.grade= (select (if(b.sub_type = 'T',if(b.theory < 20,'F',c.grade),c.grade)) as grade from relative_grading_table c where b.total between c.min and c.max and c.highest_marks=? ) where a.id in (?); ";
      // $q="update marks_master a join marks_subject_description b  on a.id=b.marks_master_id set b.grade= (select c.grade from relative_grading_table c where b.total between c.min and c.max and c.highest_marks=? ) where a.id in (?); ";
      if($this->db->query($q,[$max,$d]))
      return true;
      return fasle;

      } */

    function get_comm_grading_all($max, $mid) {

        if (is_array($mid)) {
            $d = implode(',', $mid);
        } else {
            $d = $mid;
        }

        $q = "update marks_master a join marks_subject_description b  on a.id=b.marks_master_id set b.grade= (select (if(b.sub_type = 'T',if(b.theory < 21,'F',c.grade),c.grade)) as grade from relative_grading_table c where b.total between c.min and c.max and c.highest_marks=? ) where a.id in (" . $d . "); ";
// $q="update marks_master a join marks_subject_description b  on a.id=b.marks_master_id set b.grade= (select c.grade from relative_grading_table c where b.total between c.min and c.max and c.highest_marks=? ) where a.id in (?); ";
        if ($this->db->query($q, $max))
            return true;
        return false;
    }

    function get_section_marks($session, $session_year, $subject_id) {
        $this->db->from('marks_master');
        $this->db->where('subject_id', $subject_id);
        $this->db->where('session', $session);
        $this->db->where('session_year', $session_year);
// $this->db->where('status','Y');
        $this->db->where('type', 'R');
        $query = $this->db->get();
        $result = $query->result_array();


        return $result;
    }

    function get_relative_gradetable($highest_marks) {
        $this->db->from('relative_grading_table');

        $this->db->where('highest_marks', $highest_marks);
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }

    function get_marks_des($marks_master_id) {
        $this->db->from('marks_subject_description');
        $this->db->where('marks_master_id', $marks_master_id);
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }

    function get_subject_name($subject_id) {
        $this->db->select('name');
        $this->db->from('subjects');
        $this->db->where('id', $subject_id);
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }

}

?>