<?php

class Offer_eso_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function offered_course_compoment_list($dept,$syear,$sess,$course,$branch,$sem){
    $sql="SELECT a.course_component,d.name,a.course_id,e.branch_id,a.sem,a.sequence,f.sub_code,f.sub_name,f.sub_type,a.status,f.id,
    (select count(*) from cbcs_coursestructure_policy x
    LEFT join cbcs_credit_points_policy y on x.cbcs_curriculam_policy_id=y.id
    where x.course_id=a.course_id and x.sem=a.sem and x.course_component=d.id
    ) as count_comp,
(SELECT count(DISTINCT(aa.sub_category))
FROM cbcs_subject_offered aa
JOIN cbcs_coursestructure_policy bb ON bb.course_id=aa.course_id AND bb.sem=aa.semester AND (CONCAT(bb.course_component,bb.sequence)=aa.sub_category OR CONCAT(bb.course_component,bb.sequence)=aa.unique_sub_pool_id)
JOIN cbcs_curriculam_policy cc ON cc.id=bb.cbcs_curriculam_policy_id
JOIN cbcs_credit_points_policy dd ON dd.id=cc.cbcs_credit_points_policy_id
JOIN cbcs_course_component ee ON ee.id=bb.course_component AND ee.course_id=aa.course_id
LEFT JOIN cbcs_departments ff ON ff.id=aa.dept_id
LEFT JOIN cbcs_courses gg ON gg.id=aa.course_id
LEFT JOIN cbcs_branches hh ON hh.id=aa.branch_id
WHERE aa.dept_id='$dept' AND aa.course_id=a.course_id AND aa.branch_id=e.branch_id AND aa.semester=a.sem AND aa.`session`='$sess' AND aa.session_year='$syear' AND bb.course_component=a.course_component
ORDER BY aa.id) as created_component

     #, GROUP_CONCAT(DISTINCT CONCAT_WS(' ',h.salutation,h.first_name,h.middle_name,h.last_name),' / ', CASE WHEN g.coordinator='1' THEN 'Yes' WHEN g.coordinator='0' THEN 'No' END SEPARATOR '<br>') AS fname,f.sub_category
FROM cbcs_coursestructure_policy a
INNER JOIN cbcs_curriculam_policy b ON b.cbcs_credit_points_policy_id=a.cbcs_curriculam_policy_id
INNER JOIN cbcs_credit_points_policy c ON c.id=b.cbcs_credit_points_policy_id
INNER JOIN cbcs_course_component d ON d.id=a.course_component
INNER JOIN cbcs_credit_points_master e ON e.course_id=c.course_id
LEFT JOIN cbcs_subject_offered f ON f.session_year='$syear' AND f.`session`='$sess' AND f.course_id=a.course_id AND f.branch_id=e.branch_id AND f.semester=a.sem 
#AND IF(INSTR (f.unique_sub_pool_id,'DC/DE'),f.unique_sub_pool_id= CONCAT(a.course_component,a.sequence),f.sub_category= CONCAT(a.course_component,a.sequence))
/*f.sub_category=concat(a.course_component,a.sequence)*/
LEFT JOIN cbcs_subject_offered_desc g ON g.sub_offered_id=f.id
LEFT JOIN user_details h ON h.id=g.emp_no
WHERE a.course_id in (SELECT DISTINCT course_branch.course_id #,id,name,duration
FROM
cbcs_courses
INNER JOIN course_branch ON course_branch.course_id = cbcs_courses.id
INNER JOIN dept_course ON
dept_course.course_branch_id = course_branch.course_branch_id
WHERE dept_course.dept_id = '$dept' AND cbcs_courses.`status`=1) and a.course_id='$course' and e.branch_id='$branch' and a.sem='$sem' AND a.course_component ='ESO'
GROUP BY a.course_component ,a.course_id,e.branch_id#a.sequence,f.sub_code
ORDER BY a.course_component,a.sequence,a.course_id,e.branch_id";
    $query=$this->db->query($sql);
      return $query->result();
  }

}