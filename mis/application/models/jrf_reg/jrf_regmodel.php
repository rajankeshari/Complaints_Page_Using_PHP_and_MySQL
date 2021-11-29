<?php
class Jrf_regmodel extends CI_Model 
{

    function __construct() 
    {        
        parent::__construct(array('adpg','jrf','ft','acad_da','acad_exam','acad_so'));
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation'); 
    }

    public function get_jrf_detail($adm_no){

        $sql = "SELECT sa.other_rank,ud.salutation,ud.first_name,ud.middle_name,
        ud.last_name,ud.sex,ud.category,ud.dob,ud.email,ed.domain_name,ud.photopath,
        ud.marital_status,ud.physically_challenged,dp.name AS department,br.name AS branch,
        ud.id as admn_no,rrf.semester,rrf.session,rrf.session_year,rrf.timestamp,rrfe.form_id,
        rrfe.fee_amt,rrfe.fee_date,rrfe.transaction_id,rrfe.receipt_path FROM user_details AS ud
        LEFT JOIN emaildata ed ON ed.admission_no=ud.id
        LEFT JOIN departments AS dp ON ud.dept_id=dp.id LEFT JOIN stu_academic AS sa ON ud.id=sa.admn_no
        LEFT JOIN cs_branches AS br ON sa.branch_id=br.id LEFT JOIN reg_regular_form AS rrf ON sa.admn_no=rrf.admn_no 
        LEFT JOIN reg_regular_fee AS rrfe ON rrfe.form_id=rrf.form_id
        WHERE ud.id='$adm_no' AND sa.auth_id='jrf' /*AND sa.enrollment_year>=2018*/ ORDER BY rrf.timestamp DESC LIMIT 1"; 

        //exit;    
     
        $query = $this->db->query($sql);
        // echo $this->db->last_query();
        if ($query->result_array()) 
        {
            return $query->result_array();
        } 
        else 
        {
            return "False";
        }       
        
    }

    public function get_jrf_evaluation($adm_no){
        
        $user_id=$this->session->userdata('id'); 
        $admn_no=$adm_no;
        if(in_array('jrf',$this->session->userdata('auth'))){
            $auth='jrf';
        }
        elseif (in_array('ft',$this->session->userdata('auth'))) {
            $auth='ft';
        }
        elseif (in_array('asst_lib',$this->session->userdata('auth'))) {
            $auth='asst_lib';
        }
		elseif (in_array('acad_da',$this->session->userdata('auth'))) {
            $auth='acad_da';
        }
		elseif (in_array('acad_exam',$this->session->userdata('auth'))) {
            $auth='acad_exam';
        }

        if($auth==='jrf' || $auth==='ft' || $auth==='asst_lib' || $auth==='acad_da' || $auth==='acad_exam'){
            $token='';
        }

        else{
            $token='jd.added_by_id='.$user_id.' AND';
        }
               

        $sql="SELECT * FROM `jrf_evaluation_main` AS jm INNER JOIN `jrf_evaluation_desc` AS jd ON 
        jm.admn_no=jd.admn_no WHERE $token jd.admn_no='$admn_no' ORDER BY jd.id DESC";
        $query = $this->db->query($sql);
        // echo $this->db->last_query();
        
        if ($query->num_rows()>0) 
        {
            return $query->result_array();
        } 
        else 
        {
            return False;
        }
    }

    public function get_thesis_detail($adm_no){


        $sql="SELECT * FROM `jrf_evaluation_main` AS jm WHERE jm.admn_no='$adm_no'";
        $query = $this->db->query($sql);
        // echo $this->db->last_query(); die();
        
        if ($query->num_rows()) 
        {
            return $query->result_array();
        } 
        else 
        {
            return False;
        }
    }

    public function get_viva_detail($adm_no){
        $user_id=$this->session->userdata('id');  
        $this->session->userdata('auth');
        if(in_array('jrf',$this->session->userdata('auth'))){
            $auth='jrf';
        }
        elseif (in_array('ft',$this->session->userdata('auth'))) {
            $auth='ft';
        }
        elseif (in_array('asst_lib',$this->session->userdata('auth'))) {
            $auth='asst_lib';
        }
		elseif (in_array('acad_da',$this->session->userdata('auth'))) {
            $auth='acad_da';
        }
		elseif (in_array('acad_exam',$this->session->userdata('auth'))) {
            $auth='acad_exam';
        }

        if($auth==='jrf' || $auth==='ft' || $auth==='asst_lib' || $auth==='acad_da' || $auth==='acad_exam'){
            $token='';
        }
        else{
            $token='AND jd.added_by_id='.$user_id;
        }
        $sql="SELECT * FROM jrf_evaluation_main AS jm INNER JOIN jrf_evaluation_desc jd ON jm.admn_no=jd.admn_no
        WHERE jm.admn_no='$adm_no' AND jd.exam_name='PHD_viva' $token";
        $query = $this->db->query($sql);
        if ($query->num_rows()) 
        {
            return $query->result_array();
        } 
        else 
        {
            return False;
        }
    }

    public function get_dsc_member($adm_no){
        // $sql = "SELECT x.*, CONCAT_WS(' ',ud.first_name,ud.middle_name,ud.last_name) AS dsc_memb FROM (SELECT p.admn_no,pge.ext_dept AS dept,pge.ext_ins AS emp,pge.ext_role AS role FROM project_guide p INNER JOIN project_guide_external pge ON pge.project_id=p.id
        // UNION SELECT p.admn_no,pgi.dept_id AS dept,pgi.emp_no AS emp,pgi.role AS role FROM project_guide p INNER JOIN project_guide_internal pgi ON pgi.project_id=p.id)x LEFT JOIN user_details ud ON ud.id=x.emp WHERE x.admn_no='$adm_no'";

        // $sql = "SELECT CONCAT(k.persons,'#',k.person) AS person,CONCAT(k.roles,'#',k.role) AS role,k.id AS project_id,k.admn_no FROM 
        // (SELECT x.*,GROUP_CONCAT(pgi.emp_no SEPARATOR '#') AS persons,GROUP_CONCAT(pgi.role SEPARATOR '#') AS roles  FROM 
        // (SELECT CONCAT_WS('#',p.guide,p.co_guide) AS person,(CASE WHEN p.co_guide IS NULL THEN CONCAT_WS('#','Supervisor',NULL) ELSE CONCAT_WS('#','Supervisor','Co-Supersvisor (Internal)') END) AS role, p.id,p.admn_no FROM project_guide p WHERE p.admn_no='$adm_no')x LEFT JOIN project_guide_internal pgi ON x.id=pgi.project_id)k";

        // $sql="SELECT x.*,GROUP_CONCAT(pgi.emp_no SEPARATOR '#') AS persons,GROUP_CONCAT(pgi.role ORDER BY pgi.role SEPARATOR '#') AS roles  FROM(SELECT CONCAT_WS('#',p.guide,p.co_guide) AS person,(CASE WHEN p.co_guide IS NULL THEN CONCAT_WS('#','Supervisor',NULL) ELSE CONCAT_WS('#','Supervisor','Co-Supersvisor (Internal)') END) AS role,p.id,p.admn_no FROM project_guide p WHERE p.admn_no='$adm_no')x LEFT JOIN project_guide_internal pgi ON x.id=pgi.project_id ORDER BY pgi.role";

        $sql = "SELECT k.*,GROUP_CONCAT(k.dept_id ORDER BY k.order_list SEPARATOR '#') AS dept ,GROUP_CONCAT(k.emp_no ORDER BY k.order_list SEPARATOR '#') AS persons,GROUP_CONCAT(k.roles ORDER BY k.order_list SEPARATOR '#') AS roles FROM (SELECT x.*,pgi.dept_id,pgi.emp_no,pgi.role AS roles,(CASE pgi.role WHEN 'chairman' THEN '1' WHEN 'mem_dept' THEN '2' WHEN 'mem_sis_dept' THEN '3' END) AS order_list FROM (SELECT CONCAT_WS('#',p.guide,p.co_guide) AS person,(CASE WHEN p.co_guide IS NULL THEN CONCAT_WS('#','Supervisor',NULL) ELSE CONCAT_WS('#','Supervisor','Co-Supervisor (Internal)') END) AS role,
        p.id,p.admn_no FROM project_guide p WHERE p.admn_no='$adm_no')x LEFT JOIN project_guide_internal pgi ON x.id=pgi.project_id)k";
        
        $query = $this->db->query($sql);
        // echo $this->db->last_query();
        if ($query->num_rows()){
            return $query->result_array();
        } 
        else {
            return False;
        }

    }

    public function get_name($emp){
        // return $emp;
        $sql="SELECT CONCAT_WS(' ',ud.salutation,ud.first_name,ud.middle_name,ud.last_name) AS emp_name FROM user_details ud WHERE ud.id='$emp'";
        $query = $this->db->query($sql);
        if ($query->num_rows()){
            return $query->row('emp_name');
        } 
        else {
            return False;
        }
    }

    public function get_external_dsc($project_id){
        $sql="SELECT pge.* FROM project_guide_external pge WHERE pge.project_id='$project_id'";
        $query = $this->db->query($sql);
        if ($query->num_rows()){
            return $query->result();
        } 
        else {
            return False;
        }
    }

    public function get_jrf_to_srf_detail($adm_no){
        
        $user_id=$this->session->userdata('id'); 
        $this->session->userdata('auth');
        if(in_array('jrf',$this->session->userdata('auth'))){
            $auth='jrf';
        }
        elseif (in_array('ft',$this->session->userdata('auth'))) {
            $auth='ft';
        }
        elseif (in_array('asst_lib',$this->session->userdata('auth'))) {
            $auth='asst_lib';
        }
		elseif (in_array('acad_da',$this->session->userdata('auth'))) {
            $auth='acad_da';
        }
		elseif (in_array('acad_exam',$this->session->userdata('auth'))) {
            $auth='acad_exam';
        }

        if($auth==='jrf' || $auth==='ft' || $auth==='asst_lib' || $auth==='acad_da' || $auth==='acad_exam'){
            $token='';
        }
        else{
            $token='jts.converted_by_id='.$user_id.' AND';
        }

        $sql="SELECT jts.* FROM `jrf_to_srf` AS jts WHERE $token jts.admn_no='$adm_no'";
        $query = $this->db->query($sql);
        
        if ($query->num_rows()) 
        {
            return $query->result_array();
        } 
        else 
        {
            return False;
        }
    }

    public function check_jrf_to_srf($adm_no){

        $admn_no=$adm_no;       

        $sql="SELECT jts.* FROM `jrf_to_srf` AS jts WHERE jts.admn_no='$admn_no'";
        $query = $this->db->query($sql);
        
        if ($query->num_rows()) 
        {
            return $query->result_array();
        } 
        else 
        {
            return False;
        }
    }

    public function all_jrf_to_srf(){
        $sql="SELECT CONCAT_WS(' ',ud.salutation,ud.first_name,ud.middle_name,ud.last_name) AS sname,jts.* FROM `jrf_to_srf` jts INNER JOIN user_details ud ON jts.admn_no=ud.id GROUP BY jts.admn_no";
        $query = $this->db->query($sql);
        
        if ($query->num_rows()) 
        {
            return $query->result_array();
        } 
        else 
        {
            return False;
        }
    }

    public function set_jrf_evalutation($name){        
       
        $admn_no=$name['admn_no'];
        $user_id=$this->session->userdata('id');        
        $user_name=$this->session->userdata('name');  

        $sql_check="SELECT * FROM `jrf_evaluation_main` WHERE `admn_no`='$admn_no'";
        $query_check = $this->db->query($sql_check);
        
        if ($query_check->num_rows()>0){

                $sql_second="INSERT INTO `jrf_evaluation_desc`(`admn_no`, `exam_name`, `exam_date`, `exam_desc`,`file_path`,`added_by_name`, `added_by_id`) VALUES ('$name[admn_no]','$name[exam_name]','$name[exam_date]','$name[exam_desc]','$name[file_path]','$user_name','$user_id')";
                
                if($this->db->query($sql_second)){
                    return True;
                }
                else{
                    return false;
                }
            
            
        }
        else{
           $sql="INSERT INTO `jrf_evaluation_main`(`admn_no`, `br_name`, `dep_name`, `session_year`, `session`, `semester`, `last_reg_date`, `added_by`) VALUES ('$name[admn_no]','$name[branch]','$name[department]','$name[session_year]','$name[session]','$name[semester]','$name[last_dor]','$user_id')";

            if($this->db->query($sql)){

                $sql_second="INSERT INTO `jrf_evaluation_desc`(`admn_no`, `exam_name`, `exam_date`, `exam_desc`,`file_path`,`added_by_name`, `added_by_id`) VALUES ('$name[admn_no]','$name[exam_name]','$name[exam_date]','$name[exam_desc]','$name[file_path]','$user_name','$user_id')";
                    $check=$this->db->query($sql_second);
                    if($check){
                        return True;
                    }
            }
            else{
                $check=False;
            }

        }  
    }


    public function jrf_tab_data(){

        // $sql="SELECT CONCAT_WS(' ',ud.salutation,ud.first_name,ud.middle_name,ud.last_name) AS sname,jem.* FROM `jrf_evaluation_main` jem INNER JOIN user_details ud ON jem.admn_no=ud.id";
        // $query = $this->db->query($sql); 
        // if ($query->num_rows()){
        //     $tab_data['jrf_main']=$query->result_array();            
        //     $sql_desc="SELECT CONCAT_WS(' ',ud.salutation,ud.first_name,ud.middle_name,ud.last_name) AS sname,jed.* FROM `jrf_evaluation_desc` jed INNER JOIN user_details ud ON jed.admn_no=ud.id";
        //     $query_desc = $this->db->query($sql_desc); 
        //     if ($query_desc->num_rows()){
        //         $tab_data['jrf_desc']=$query_desc->result_array();
        //     }         
        //     return $tab_data;
        // } 
        // else 
        // {
        //     return false;
        // }

        $sql = "SELECT CONCAT_WS(' ',ud.salutation,ud.first_name,ud.middle_name,ud.last_name) AS sname,jem.dep_name,k.* FROM (select jed.admn_no,GROUP_CONCAT(jed.exam_name SEPARATOR '#') AS ex_name,GROUP_CONCAT(jed.exam_desc SEPARATOR '#') AS ex_desc from jrf_evaluation_desc jed WHERE jed.id IN (select max(id) from jrf_evaluation_desc group by admn_no,exam_name) GROUP BY jed.admn_no)k INNER JOIN user_details ud ON k.admn_no=ud.id LEFT JOIN jrf_evaluation_main jem ON jem.admn_no=k.admn_no";
        $query = $this->db->query($sql); 
        if ($query->num_rows()){
            return $query->result_array();
        }
        else{
            return false;
        }
         
    }


    public function all_jrf_student(){
        // $sql = "SELECT CONCAT_WS(' ',ud.salutation,ud.first_name,ud.middle_name,ud.last_name) AS sname,ud.sex,ud.category,ud.dob,ud.email,ud.photopath,ud.marital_status,ud.physically_challenged,dp.name AS department,br.name AS branch,sa.admn_no,rrf.semester,rrf.session,rrf.session_year,rrf.timestamp,max(rrf.form_id) FROM user_details AS ud INNER JOIN departments AS dp ON ud.dept_id=dp.id INNER JOIN stu_academic AS sa ON ud.id=sa.admn_no INNER JOIN branches AS br ON sa.branch_id=br.id INNER JOIN reg_regular_form AS rrf ON sa.admn_no=rrf.admn_no WHERE sa.auth_id='jrf' AND sa.enrollment_year>=2018 GROUP BY admn_no"; 
        
        // $sql = "SELECT ud.id,CONCAT_WS(' ',ud.salutation,ud.first_name,ud.middle_name,ud.last_name) AS sname,
        // dp.name AS department,k.*  FROM user_details AS ud INNER JOIN users u ON u.id=ud.id
        // INNER JOIN departments AS dp ON ud.dept_id=dp.id
        // INNER JOIN stu_academic AS sa ON ud.id=sa.admn_no
        // INNER JOIN reg_regular_form AS rrf ON sa.admn_no=rrf.admn_no LEFT JOIN 
        // (SELECT y.admn_no,GROUP_CONCAT(y.exam_name SEPARATOR '#') AS ex_name,GROUP_CONCAT(y.exam_desc SEPARATOR '#') AS ex_desc 
        // FROM(SELECT x.* FROM(SELECT jem.admn_no,jed.exam_name,jed.exam_desc FROM jrf_evaluation_main jem INNER JOIN jrf_evaluation_desc jed ON jem.admn_no=jed.admn_no
        // ORDER BY jed.id ASC,jed.added_date DESC)x GROUP BY x.exam_name)y)k ON k.admn_no=ud.id WHERE sa.auth_id='jrf' AND u.`status`='A' GROUP BY ud.id  ORDER BY ud.id DESC";

        $sql = "SELECT ud.id, CONCAT_WS(' ',ud.salutation,ud.first_name,ud.middle_name,ud.last_name) AS sname,
        dp.name AS department,k.*
       FROM user_details AS ud
       INNER JOIN users u ON u.id=ud.id INNER JOIN departments AS dp ON ud.dept_id=dp.id INNER JOIN stu_academic AS sa ON ud.id=sa.admn_no
       INNER JOIN reg_regular_form AS rrf ON sa.admn_no=rrf.admn_no
       LEFT JOIN (select jed.admn_no,GROUP_CONCAT(jed.exam_name SEPARATOR '#') AS ex_name,GROUP_CONCAT(jed.exam_desc SEPARATOR '#') AS ex_desc from jrf_evaluation_desc jed WHERE
       jed.id IN (select max(id) from jrf_evaluation_desc group by admn_no,exam_name) GROUP BY jed.admn_no)k ON k.admn_no=ud.id
       WHERE sa.auth_id='jrf' AND u.`status`='A' GROUP BY ud.id 
       ORDER BY ud.id DESC";
     
        $query = $this->db->query($sql);
        if ($query->result_array()) 
        {
            return $query->result_array();
        } 
        else 
        {
            return "False";
        } 

    }


    public function getexceldata(){

        $all_jrf_student=$this->all_jrf_student();
        $jrf_student=array();

        $sql_desc="SELECT * FROM `jrf_evaluation_desc` order by id desc";
        $query_desc = $this->db->query($sql_desc); 
        if ($query_desc->num_rows()) 
        {
            $jrf_student=$query_desc->result_array();
        }

        $sql_desc1="SELECT * FROM `jrf_to_srf` order by id desc";
        $query_desc1 = $this->db->query($sql_desc1); 
        if ($query_desc1->num_rows()) 
        {
            $jts_student=$query_desc1->result_array();
        }

       
        
        
        $dummy=array(
            'ce_exam' => '',
            'ce_desc' => '', 
            'rp_exam' => '',
            'rp_desc' => '',
            'jts_exam' => '', 
            'jts_desc' => '', 
            'ps_exam' => '',
            'ps_desc' => ''
        );     

        $excel_data=array();

        
       
        foreach ($all_jrf_student as $key1 => $value1) {
            $dummy['ce_exam']='Comprehensive Examination';
            $dummy['ce_desc']='Pending';
            $dummy['rp_exam']='Research Proposal Seminar';
            $dummy['rp_desc']='Pending'; 
            $dummy['jts_exam']='JRF to SRF'; 
            $dummy['jts_desc']='JRF'; 
            $dummy['ps_exam']='Pre Submission Seminar';
            $dummy['ps_desc']='Pending';

            if(sizeof($jrf_student)>0){
                foreach ($jrf_student as $key2 => $value2) {

                    if($value2['admn_no']==$value1['admn_no'] && $value2['exam_name']=='Comprehensive Examination'){             
                        $dummy['ce_exam']="Comprehensive Examination";
                        $dummy['ce_desc']=$value2['exam_desc'];
                        $excel_data[$key1]=array_merge($all_jrf_student[$key1],$dummy);
                       
                    }
                    else if($value2['admn_no']==$value1['admn_no'] && $value2['exam_name']=='Research Proposal Seminar'){
                        $dummy['rp_exam']="Research Proposal Seminar";
                        $dummy['rp_desc']=$value2['exam_desc'];
                        $excel_data[$key1]=array_merge($all_jrf_student[$key1],$dummy);
                    }
                    else if($value2['admn_no']==$value1['admn_no'] && $value2['exam_name']=='Pre Submission Seminar'){
                        $dummy['ps_exam']="Pre Submission Seminar";
                        $dummy['ps_desc']=$value2['exam_desc'];
                        $excel_data[$key1]=array_merge($all_jrf_student[$key1],$dummy);
                    }
                    else{
                        $excel_data[$key1]=array_merge($all_jrf_student[$key1],$dummy);
                    }
                    
                }
            }
            if(sizeof($jts_student)>0){
                foreach ($jts_student as $key => $value) {
                   if($value['admn_no']==$value1['admn_no'] ){             
                        $dummy['jts_exam']="JRF to SRF";
                        $dummy['jts_desc']='SRF';
                        $excel_data[$key1]=array_merge($all_jrf_student[$key1],$dummy);
                       
                    }
                }
            }
            else{
                $excel_data[$key1]=array_merge($all_jrf_student[$key1],$dummy);
            } 
            
        }

        return $excel_data;

    }


    public function getexam_excel($exname){
        //echo $exname;
        $demo=array(
            'exam_name' => '',
            'exam_desc' => ''
        );   

        $jrf_student=array();

        $sql_main="SELECT CONCAT_WS(' ',ud.salutation,ud.first_name,ud.middle_name,ud.last_name) AS sname,jem.* FROM `jrf_evaluation_main` jem INNER JOIN user_details ud ON jem.admn_no=ud.id";
        $query_main = $this->db->query($sql_main); 
        if ($query_main->num_rows()){
            $jrf_main=$query_main->result_array();
        }    
      

        $sql_desc="SELECT CONCAT_WS(' ',ud.salutation,ud.first_name,ud.middle_name,ud.last_name) AS sname,jed.* FROM `jrf_evaluation_desc` jed INNER JOIN user_details ud ON jed.admn_no=ud.id";
        $query_desc = $this->db->query($sql_desc); 
        if ($query_desc->num_rows()){
            $jrf_desc=$query_desc->result_array();
        }   
        

        if(sizeof($jrf_main)>0){
            foreach ($jrf_main as $key1 => $value1) {
                $demo['exam_name']=$exname;
                $demo['exam_desc']='Pending';

                foreach ($jrf_desc as $key2 => $value2) {
                    if($value2['admn_no']==$value1['admn_no'] && $value2['exam_name']==$exname){
                        $demo['exam_name']=$value2['exam_name'];
                        $demo['exam_desc']=$value2['exam_desc'];
                        $jrf_student[$key1]=array_merge($jrf_main[$key1],$demo);
                    }
                    else{
                        $jrf_student[$key1]=array_merge($jrf_main[$key1],$demo);
                    }
                }          
                
            }
        }

        return $jrf_student;
    }


    public function set_jrf_to_srf($name){        
        $user_id=$this->session->userdata('id');        
        $user_name=$this->session->userdata('name'); 

        $sql="INSERT INTO `jrf_to_srf`(`admn_no`, `br_name`, `dept_name`, `session_year`, `session` , `upload_path`, `date_of_conversion`, `converted_by_id`, `converted_by_name`) VALUES ('$name[admn_no]','$name[branch]','$name[department]','$name[session_year]','$name[session]','$name[file_path]','$name[converse_date]','$user_id','$user_name')";

        if($this->db->query($sql)){
            return True;
        }
        else{
            return false;
        }
    }

    public function get_course_detail($admn_no,$cgpa){
        // echo $admn_no."<br>";
        // echo $cgpa;
        if(is_null($cgpa)){
            $token='GROUP BY d.course_code ORDER BY t.order_list';
        }
        else{           
            // $token=' order by t.admn_no,t.session_yr desc,t.session DESC LIMIT 1';
            $token=' HAVING t.cgpa IS NOT NULL order by t.admn_no,t.session_yr desc,t.order_list DESC LIMIT 1';
        }
        $sql="select d.course_code,d.course_name ,t.* from (select d.course_code,d.course_name,d.admn_no from jrf_dsc d  where  d.admn_no='$admn_no' AND d.`status`=1)d left join (select y.*,fd.sub_code,fd.grade,fd.cr_hr,fd.cr_pts,fd.total from (SELECT x.* FROM( SELECT a.session_yr,a.session,a.admn_no,a.dept,a.course,a.branch,a.semester,a.id,a.`status`,
            a.ctotcrpts,a.ctotcrhr, a.core_ctotcrpts,a.core_ctotcrhr, a.tot_cr_hr,a.tot_cr_pts,  
            a.core_tot_cr_hr,a.core_tot_cr_pts,a.cgpa,(CASE a.session WHEN 'Monsoon' THEN '1' WHEN 'Winter' THEN '2' WHEN 
            'Summer' THEN '3' END) AS order_list FROM final_semwise_marks_foil_freezed AS a WHERE a.admn_no='$admn_no' AND  
            UPPER(a.course)<>'MINOR' and lower(a.course)='jrf' ORDER BY a.admn_no,a.session_yr ,order_list 
            desc,a.actual_published_on DESC LIMIT 100000000)x  GROUP BY x.admn_no,x.session_yr,x.session) y JOIN 
            final_semwise_marks_foil_desc_freezed fd ON fd.foil_id=y.id AND fd.admn_no=y.admn_no)t on t.admn_no=d.admn_no 
            and d.course_code=t.sub_code $token";

            /*this query is written bu Rituraj Sir*/
        
        $query = $this->db->query($sql); 
        // echo $this->db->last_query();
        if ($query->num_rows()>0){
            return $query->result();
        }
        else{
            return false;
        }
    }

    public function get_foil_marks($admn_no,$cgpaa){
        
        if(is_null($cgpaa)){
            $token1='GROUP BY fd.sub_code ORDER BY y.order_list';
        }
        else{
            $token1='HAVING y.cgpa IS NOT NULL order by y.admn_no,y.session_yr desc,y.order_list DESC LIMIT 1';
        }

        // $sql = "SELECT y.*,fd.sub_code,fd.grade,fd.cr_hr,fd.cr_pts,fd.total
        // FROM (SELECT x.* FROM(SELECT a.session_yr,a.session,a.admn_no,a.dept,a.course,a.branch,a.semester,a.id,a.`status`,
        //  a.ctotcrpts,a.ctotcrhr, a.core_ctotcrpts,a.core_ctotcrhr, a.tot_cr_hr,a.tot_cr_pts, 
        //  a.core_tot_cr_hr,a.core_tot_cr_pts,a.cgpa,(CASE a.session WHEN 'Monsoon' THEN '1' WHEN 'Winter' THEN '2' WHEN 
        //  'Summer' THEN '3' END) AS order_list FROM final_semwise_marks_foil_freezed AS a
        // WHERE a.admn_no='$admn_no' AND UPPER(a.course)<>'MINOR' AND LOWER(a.course)='jrf'
        // ORDER BY a.admn_no,a.session_yr,order_list DESC,a.actual_published_on DESC
        // LIMIT 100000000)x GROUP BY x.admn_no,x.session_yr,x.session) Y
        // JOIN final_semwise_marks_foil_desc_freezed fd ON fd.foil_id=y.id AND fd.admn_no=y.admn_no $token1";

        $sql="SELECT cso.sub_name AS cbcs_sub,sb.name AS sub_name,oso.sub_name AS oso_sub,y.*,fd.sub_code,fd.grade,fd.cr_hr,fd.cr_pts,fd.total FROM (SELECT x.* FROM(SELECT a.session_yr,a.session,a.admn_no,a.dept,a.course,a.branch,a.semester,a.id,a.`status`, 
        a.ctotcrpts,a.ctotcrhr, a.core_ctotcrpts,a.core_ctotcrhr, a.tot_cr_hr,a.tot_cr_pts, a.core_tot_cr_hr,a.core_tot_cr_pts,a.cgpa,
        (CASE a.session WHEN 'Monsoon' THEN '1' WHEN 'Winter' THEN '2' WHEN 'Summer' THEN '3' END) AS order_list
        FROM final_semwise_marks_foil_freezed AS a
        WHERE a.admn_no='$admn_no' AND UPPER(a.course)<>'MINOR' AND LOWER(a.course)='jrf'
        ORDER BY a.admn_no,a.session_yr,order_list DESC,a.actual_published_on DESC
        LIMIT 100000000)x
        GROUP BY x.admn_no,x.session_yr,x.session) y
        JOIN final_semwise_marks_foil_desc_freezed fd ON fd.foil_id=y.id AND fd.admn_no=y.admn_no
        LEFT JOIN subjects sb ON sb.id= fd.mis_sub_id AND y.session_yr<'2019-2020'
        LEFT JOIN cbcs_subject_offered cso ON cso.sub_code=fd.sub_code
        LEFT JOIN old_subject_offered oso ON oso.sub_code=fd.sub_code $token1";
        
        $query = $this->db->query($sql); 
        // echo $this->db->last_query();
        if ($query->num_rows()>0){
            return $query->result();
        }
        else{
            return false;
        }

    }


    public function set_synopsis($name){
        
        date_default_timezone_set('Asia/Kolkata');
        $date = date('d-m-Y', time()); 

        $sql = "SELECT * FROM jrf_evaluation_main j WHERE j.admn_no='$name[admn_no]'";
        $query = $this->db->query($sql); 
        // echo $this->db->last_query();
        if ($query->num_rows()>0){
            // return $query->result();            
            $usql="UPDATE jrf_evaluation_main j SET j.synopsis='$name[synopsis_file]',j.synopsis_other_doc='$name[synopsis_other_file]',j.synopsis_status1='1',j.synopsis_submission_date='$date' WHERE j.admn_no='$name[admn_no]'";
            if($this->db->query($usql)){
                return true;
            }
            else{
                return false;
            }
        }
        else{
            $isql = "INSERT INTO `jrf_evaluation_main`(`admn_no`, `br_name`, `dep_name`, `session_year`, `session`, `semester`, `last_reg_date`,`synopsis`,`synopsis_other_doc`,`synopsis_status1`,`synopsis_submission_date`) VALUES ('$name[admn_no]','$name[branch]','$name[department]','$name[session_year]','$name[session]','$name[semester]','$name[last_dor]','$name[synopsis_file]','$name[synopsis_other_file]','1','$date')";
            // echo $this->db->last_query();
            if($this->db->query($isql)){
                return true;
            }
            else{
                return false;
            }
        }

    }

    public function set_draft_thesis($name){
        
        date_default_timezone_set('Asia/Kolkata');
        $date = date('d-m-Y', time()); 

        $sql = "SELECT * FROM jrf_evaluation_main j WHERE j.admn_no='$name[admn_no]'";
        $query = $this->db->query($sql); 
        // echo $this->db->last_query();
        if ($query->num_rows()>0){
            // return $query->result();            
            $usql="UPDATE jrf_evaluation_main j SET j.draft_thesis='$name[draft_thesis_file]',j.draft_other_doc='$name[draft_other_file]',j.draft_thesis_status1='1',j.draft_submission_date='$date' WHERE j.admn_no='$name[admn_no]'";
            if($this->db->query($usql)){
                return true;
            }
            else{
                return false;
            }
        }
        else{

            $isql = "INSERT INTO `jrf_evaluation_main`(`admn_no`, `br_name`, `dep_name`, `session_year`, `session`, `semester`, `last_reg_date`,`draft_thesis`,`draft_other_doc`,`draft_thesis_status1`,`draft_submission_date`) VALUES ('$name[admn_no]','$name[branch]','$name[department]','$name[session_year]','$name[session]','$name[semester]','$name[last_dor]','$name[draft_thesis_file]','$name[draft_other_file]','1','$date')";
            // echo $this->db->last_query();
            if($this->db->query($isql)){
                return true;
            }
            else{
                return false;
            }
        }

    }


    public function set_final_thesis($name){
        date_default_timezone_set('Asia/Kolkata');
        $date = date('d-m-Y', time()); 

        $sql = "SELECT * FROM jrf_evaluation_main j WHERE j.admn_no='$name[admn_no]'";
        $query = $this->db->query($sql); 
        // echo $this->db->last_query();
        if ($query->num_rows()>0){
            // return $query->result();            
            $usql="UPDATE jrf_evaluation_main j SET j.thesis_doc='$name[final_thesis_file]',j.thesis_other_doc='$name[final_thesis_other_file]',j.thesis_status='1',j.thesis_submission_date='$date' WHERE j.admn_no='$name[admn_no]'";
            if($this->db->query($usql)){
                return true;
            }
            else{
                return false;
            }
        }
        else{
            $isql = "INSERT INTO `jrf_evaluation_main`(`admn_no`, `br_name`, `dep_name`, `session_year`, `session`, `semester`, `last_reg_date`,`thesis_doc`,`thesis_other_doc`,`thesis_status`,`thesis_submission_date`) VALUES ('$name[admn_no]','$name[branch]','$name[department]','$name[session_year]','$name[session]','$name[semester]','$name[last_dor]','$name[final_thesis_file]','$name[final_thesis_other_file]','1','$date')";
            // echo $this->db->last_query();
            if($this->db->query($isql)){
               return true;
            }
            else{
               return false;
            }
        }

    }

    public function set_phd_viva($name){
        $admn_no=$name['admn_no'];
        $user_id=$this->session->userdata('id');        
        $user_name=$this->session->userdata('name');  

        $sql_check="SELECT * FROM `jrf_evaluation_main` WHERE `admn_no`='$admn_no'";
        $query_check = $this->db->query($sql_check);

        if ($query_check->num_rows()>0){

            $sql="INSERT INTO `jrf_evaluation_desc`(`admn_no`, `exam_name`, `exam_date`, `exam_desc`,`file_path`,`added_by_name`, `added_by_id`) VALUES ('$name[admn_no]','$name[exam_name]','$name[viva_date]','$name[viva_result]','$name[viva_file]','$user_name','$user_id')";
            
            if($this->db->query($sql)){
                return true;
            }
            else{
                return false;
            }
        }
        else
        {
            $sql="INSERT INTO `jrf_evaluation_main`(`admn_no`, `br_name`, `dep_name`, `session_year`, `session`, `semester`, `last_reg_date`,`added_by`) VALUES ('$name[admn_no]','$name[branch]','$name[department]','$name[session_year]','$name[session]','$name[semester]','$name[last_dor]','$user_id')";

            if($this->db->query($sql)){
                $sql = "INSERT INTO `jrf_evaluation_desc`(`admn_no`, `exam_name`, `exam_date`, `exam_desc`,`file_path`,`added_by_name`, `added_by_id`) VALUES ('$name[admn_no]','$name[exam_name]','$name[viva_date]','$name[viva_result]','$name[viva_file]','$user_name','$user_id')";
            
                if($this->db->query($sql)){
                    return true;
                }
                else{
                    return false;
                }
            }
            else{
                return false;
            }
        }
    }

    public function get_change_status($admn_no,$exam){
        
        if($exam=='Synopsis'){
            $usql="UPDATE jrf_evaluation_main j SET j.synopsis_status2='1' WHERE j.admn_no='$admn_no'";
        }
        if($exam=='Draft_thesis'){
            $usql="UPDATE jrf_evaluation_main j SET j.draft_thesis_status2='1' WHERE j.admn_no='$admn_no'";
        }
        if($exam=='Final_thesis'){
            $usql="UPDATE jrf_evaluation_main j SET j.thesis_status2='1' WHERE j.admn_no='$admn_no'";
        }
        
        if($this->db->query($usql)){
            return true;
        }
        else{
            return false;
        }


    }

    public function get_fellowship_data($admn_no){
        // $sql = "SELECT fa.admn_no,fa.paid_month AS mon,fa.year AS yr,fa.net_pay AS fa_netpay,fa.remark1 AS fa_remark,fd.paid_amt AS fd_netpay,fd.remark AS fd_remark FROM fellow_monthly_bill_acad fa JOIN fellow_monthly_bill_dept fd ON fa.admn_no=fd.admn_no AND fa.paid_month=fd.paid_month WHERE fa.admn_no='$admn_no' ORDER BY fa.paid_month ASC";

        $sql = "SELECT * FROM fellow_monthly_bill_acad fa WHERE fa.admn_no='$admn_no' ORDER BY fa.paid_month ASC";
        $query = $this->db->query($sql); 
        // echo $this->db->last_query();
        if ($query->num_rows()>0){
            return $query->result();
        }
        else{
            return false;
        }

    }
        
    public function set_pre_clearance_date($name){
        // date_default_timezone_set('Asia/Kolkata');
        // $date = date('d-m-Y', time()); 
        $user_id=$this->session->userdata('id');
        $sql = "SELECT * FROM jrf_evaluation_main j WHERE j.admn_no='$name[admn_no]'";
        $query = $this->db->query($sql); 
        
        if ($query->num_rows()>0){
            // return $query->result();            
            $usql="UPDATE jrf_evaluation_main j SET j.pre_clear_date='$name[pre_clear_date]' WHERE j.admn_no='$name[admn_no]' OR j.added_by='$user_id'";
            if($this->db->query($usql)){
                // echo $this->db->last_query();
                // exit;
                return true;
            }
            else{
                return false;
            }
        }
        else{
            $isql = "INSERT INTO `jrf_evaluation_main`(`admn_no`, `br_name`, `dep_name`, `session_year`, `session`, `semester`, `last_reg_date`,`added_by`,`pre_clear_date`) VALUES ('$name[admn_no]','$name[branch]','$name[department]','$name[session_year]','$name[session]','$name[semester]','$name[last_dor]','$user_id','$name[pre_clear_date]')";
            // echo $this->db->last_query();
            if($this->db->query($isql)){
               return true;
            }
            else{
               return false;
            }
        }
    }

    public function get_full_registration($admn_no){
        $sql = "select x.*,rrf.* from(
            (select 'na' as form_id,a.adm_no as admn_no,b.course_id,b.branch_id,right(a.sem_code,1) as semester,'na' as section,
            a.ysession as session_year,a.wsms as `session`,'na' as hod_status,'na' as acad_status,'na' as timestamp,'Tabulation1' as exam_type
             from tabulation1 a 
            inner join stu_academic b on b.admn_no=a.adm_no
            where a.adm_no=?
            group by a.sem_code)
            
            union
            (select a.form_id,a.admn_no,a.course_id,a.branch_id,a.semester,a.section,
            a.session_year,a.`session`,a.hod_status,a.acad_status,a.timestamp,'Regular' as exam_type from reg_regular_form a where a.admn_no=?)
            union
            (select a.form_id,a.admn_no,a.course_id,a.branch_id,a.semester,'na' as section,
            a.session_year,a.`session`,a.hod_status,a.acad_status,a.timestamp,'Other' as exam_type from reg_other_form a where a.admn_no=?)
            union
            (select a.form_id,a.admn_no,a.course_id,a.branch_id,a.semester,'na' as section,
            a.session_year,a.`session`,a.hod_status,a.acad_status,a.timestamp,'Exam' as exam_type from reg_exam_rc_form a where a.admn_no=?)
            union
            (select a.form_id,a.admn_no,a.course_id,a.branch_id,a.semester,'na' as section,
            a.session_year,a.`session`,a.hod_status,a.acad_status,a.timestamp,'Summer' as exam_type from reg_summer_form a where a.admn_no=?)
            )x LEFT JOIN reg_regular_fee rrf on rrf.form_id=x.form_id
            order by x.semester,x.session_year,x.session,x.session,x.timestamp";
            $query = $this->db->query($sql,array($admn_no,$admn_no,$admn_no,$admn_no,$admn_no));
    
            // echo $this->db->last_query(); die();
            if ($this->db->affected_rows() > 0) {
                return $query->result();
            } else {
                return false;
            }
    }

    public function update_file($name){
        // echo "<pre>";
        // print_r($name);
        // exit;
        if($name['col_name']=="Synopsis"){
            $sql="UPDATE jrf_evaluation_main j SET j.synopsis='$name[u_file]',j.synopsis_other_doc='$name[u_other_file]',j.synopsis_submission_date='$name[u_synopsis_date]' WHERE j.id='$name[h_id]'";
        }
        elseif ($name['col_name']=="Draft Thesis") {
            $sql="UPDATE jrf_evaluation_main j SET j.draft_thesis='$name[u_file]',j.draft_other_doc='$name[u_other_file]',j.draft_submission_date='$name[u_draft_date]' WHERE j.id='$name[h_id]'";
        }
        else{
            $sql="UPDATE jrf_evaluation_main j SET j.thesis_doc='$name[u_file]',j.thesis_other_doc='$name[u_other_file]',j.thesis_submission_date='$name[u_thesis_date]' WHERE j.id='$name[h_id]'";
        } 
        
        if($this->db->query($sql)){
            return true;
        }
        else{
            return false;
        }
    }

    public function get_offline_thesis_detail($admn_no){
        $sql = "SELECT * FROM jrf_evaluation_main jem WHERE jem.admn_no='$admn_no'";
        $query = $this->db->query($sql);
        if($this->db->affected_rows() > 0){
            return $query->result();
        }
        else{
            return false;
        }
    }

    public function add_offline_synopsis_detail($name){
        $admn_no = $name['admn_no'];
        $synopsis_date = $name['synopsis_date'];

        // echo "<pre>";
        // print_r($name);
        // exit;

        $sql = "SELECT * FROM jrf_evaluation_main jem WHERE jem.admn_no='$admn_no'";        
        $query = $this->db->query($sql);        
        // echo $this->db->last_query();
        if($this->db->affected_rows() > 0){
            $id=$query->row()->id;
            // echo $id;
            // exit;
            $update_detail =array(
                'synopsis_status1'=>'1',
                'synopsis_status2'=>'1',
                'synopsis_submission_date'=>$synopsis_date,
                'remark1'=>'Offline Synopsis Submission'
            );

            $this->db->trans_start();

            $this->db->where(array('id' => $id));
            $this->db->update('jrf_evaluation_main', $update_detail);

            $this->db->trans_complete();

            if ($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
                return false;
            }
            else{
                $this->db->trans_commit();
                return true;
            }
            
            // return $basic_detail;
        }
        else{
            $basic_detail =$this->get_jrf_detail($admn_no);
            if(is_null($basic_detail[0]['session_year']) && is_null($basic_detail[0]['session']) && is_null($basic_detail[0]['semester'])){
                $jrf_main = array(
                    'admn_no'=>$basic_detail[0]['admn_no'],
                    'br_name'=>$basic_detail[0]['branch'],
                    'dep_name'=>$basic_detail[0]['department'],
                    'session_year'=>$name['new_session_year'],
                    'session'=>$name['new_session'],
                    'semester'=>$name['new_semester'],
                    'last_reg_date'=>$synopsis_date,
                    'added_by'=>$this->session->userdata('id'),
                    'synopsis_status1'=>'1',
                    'synopsis_status2'=>'1',
                    'synopsis_submission_date'=>$synopsis_date,
                    'remark1'=>'Offline Synopsis Submission',
                    'remark3'=>'Offline Registration by Acad_da'
                );
            }
            else{
                $jrf_main = array(
                    'admn_no'=>$basic_detail[0]['admn_no'],
                    'br_name'=>$basic_detail[0]['branch'],
                    'dep_name'=>$basic_detail[0]['department'],
                    'session_year'=>$basic_detail[0]['session_year'],
                    'session'=>$basic_detail[0]['session'],
                    'semester'=>$basic_detail[0]['semester'],
                    'last_reg_date'=>$basic_detail[0]['timestamp'],
                    'added_by'=>$this->session->userdata('id'),
                    'synopsis_status1'=>'1',
                    'synopsis_status2'=>'1',
                    'synopsis_submission_date'=>$synopsis_date,
                    'remark1'=>'Offline Synopsis Submission'
                );
            }

            // echo "<pre>";

            // print_r($basic_detail);
            // print_r($jrf_main);
            // exit;

            $this->db->trans_start();

            $this->db->insert('jrf_evaluation_main', $jrf_main);

            $this->db->trans_complete();

            if ($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
                // $data['error'] = 'false';
                // return $data;
                return false;
            }
            else{
                $this->db->trans_commit();
                // $data['success'] ='true';
                // return $data;
                return true;
            }
            
        }
    }

    

    public function add_offline_draft_thesis_detail($name){

        $admn_no = $name['admn_no'];
        $draft_thesis_date = $name['draft_thesis_date'];

        // echo "<pre>";
        // print_r($name);
        // exit;

        $sql = "SELECT * FROM jrf_evaluation_main jem WHERE jem.admn_no='$admn_no'";        
        $query = $this->db->query($sql);        
        
        if($this->db->affected_rows() > 0){
            $id=$query->row()->id;
            // echo $id;
            
            $update_detail =array(
                'draft_thesis_status1'=>'1',
                'draft_thesis_status2'=>'1',
                'draft_submission_date'=>$draft_thesis_date,
                'remark2'=>'Offline Draft Thesis Submission'
            );
            // echo "<pre>";
            // print_r($update_detail);
           
            $this->db->trans_start();

            $this->db->where(array('id' => $id));
            $this->db->update('jrf_evaluation_main', $update_detail);

            // echo $this->db->last_query();
            //  exit;
            $this->db->trans_complete();

            if ($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
                return false;
            }
            else{
                $this->db->trans_commit();
                return true;
            }
        }
        else{
            $basic_detail =$this->get_jrf_detail($admn_no);
            if(is_null($basic_detail[0]['session_year']) && is_null($basic_detail[0]['session']) && is_null($basic_detail[0]['semester'])){
                $jrf_main = array(
                    'admn_no'=>$basic_detail[0]['admn_no'],
                    'br_name'=>$basic_detail[0]['branch'],
                    'dep_name'=>$basic_detail[0]['department'],
                    'session_year'=>$name['new_session_year'],
                    'session'=>$name['new_session'],
                    'semester'=>$name['new_semester'],
                    'last_reg_date'=>$draft_thesis_date,
                    'added_by'=>$this->session->userdata('id'),
                    'draft_thesis_status1'=>'1',
                    'draft_thesis_status2'=>'1',
                    'draft_submission_date'=>$draft_thesis_date,
                    'remark2'=>'Offline Draft Thesis Submission',
                    'remark3'=>'Offline Registration by Acad_da'
                );
            }
            else{
                $jrf_main = array(
                    'admn_no'=>$basic_detail[0]['admn_no'],
                    'br_name'=>$basic_detail[0]['branch'],
                    'dep_name'=>$basic_detail[0]['department'],
                    'session_year'=>$basic_detail[0]['session_year'],
                    'session'=>$basic_detail[0]['session'],
                    'semester'=>$basic_detail[0]['semester'],
                    'last_reg_date'=>$basic_detail[0]['timestamp'],
                    'added_by'=>$this->session->userdata('id'),
                    'draft_thesis_status1'=>'1',
                    'draft_thesis_status2'=>'1',
                    'draft_submission_date'=>$draft_thesis_date,
                    'remark2'=>'Offline Draft Thesis Submission'
                );
            }
            
            // print_r($basic_detail);
            // print_r($jrf_main);
            // exit;
            $this->db->trans_start();

            $this->db->insert('jrf_evaluation_main', $jrf_main);

            $this->db->trans_complete();

            if ($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
                // $data['error'] = 'false';
                // return $data;
                return false;
            }
            else{
                $this->db->trans_commit();
                // $data['success'] ='true';
                // return $data;
                return true;
            }
            
        }
    }
    
	
	// update of 25-01-2021
	
	public function update_jrf_to_srf($name){
        // echo "<pre>";
        // print_r($name);
        if(empty($name['hsession'])){
            $session = $name['session'];
        }
        else{
             $session = $name['hsession'];
        }

        if(empty($name['hsession_year'])){
            $session_year = $name['session_year'];
        }
        else{
            $session_year = $name['hsession_year'];
        }

        // echo "<pre>";
        // print_r($name);
        // exit;
        $id = $name['id'];
        $remark = 'Updated by '.$this->session->userdata('id');
        $update_array = array(
            'session_year'=>$session_year,
            'session'=>$session,
            'date_of_conversion' =>$name['exam_date'],
            'remark1'=>$remark
        );

        $this->db->trans_start();

        $this->db->where(array('id' => $id));
        $this->db->update('jrf_to_srf', $update_array);
        // echo $this->db->last_query();
        // exit;
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return false;
        }
        else{
            $this->db->trans_commit();
            return true;
        }

    }
    

    public function update_pre_clearance_date($name){
        // echo "<pre>";
        // print_r($name);
        $admn_no = $name['admn_no'];
        $remark = 'Updated by '.$this->session->userdata('id');
        $update_array = array(
            'pre_clear_date' =>$name['exam_date'],
            'pre_clear_remark'=>$remark
        );

        $this->db->trans_start();

        $this->db->where(array('admn_no' => $admn_no));
        $this->db->update('jrf_evaluation_main', $update_array);
        // echo $this->db->last_query();
        // exit;
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return false;
        }
        else{
            $this->db->trans_commit();
            return true;
        }

    }




    /*======================== ENd Of Class ====================*/

}

?>