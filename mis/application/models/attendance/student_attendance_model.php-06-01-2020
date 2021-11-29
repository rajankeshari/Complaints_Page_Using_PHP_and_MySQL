<?php

class Student_attendance_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get_session_year($stu_id = "") {
        if (empty($student)) {
            $stu_id = $this->session->userdata('id');
        }

        $this->load->database();
        //$query=$this->db->query("SELECT DISTINCT session_year FROM reg_regular_form WHERE admn_no = '$stu_id'");
        $query = $this->db->query("(SELECT DISTINCT session_year FROM reg_regular_form WHERE admn_no ='" . $stu_id . "')
                        union
                        (select DISTINCT session_year from reg_other_form where admn_no='" . $stu_id . "' and (reason='REPEATER' || reason='Repeater'||reason='Repeater'))");

        return $query->result();
    }

    // public function get_new_subjects($data)
    // {
    // 	$emp_id=$this->session->userdata('id');
    // 	$session_year=$data['session_year'];
    // 	$session=$data['session'];
    // 	$this->load->database();
    // 		$query= $this->db->query("SELECT subject_mapping.map_id as map_id,subject_mapping.semester as semester,subjects.name as sub_name ,subjects.id as sub_id, subject_mapping.branch_id as branch_id ,
    // 		 cs_branches.name as branch_name,subject_mapping.course_id as course_id,
    // 		 cs_courses.name as course_name, subject_mapping_des.coordinator as coordinator,subjects.type as sub_type
    // 		FROM subject_mapping
    // 		INNER JOIN subject_mapping_des ON subject_mapping_des.map_id = subject_mapping.map_id
    // 		INNER JOIN subjects ON subjects.id = subject_mapping_des.sub_id
    // 		INNER JOIN cs_branches ON subject_mapping.branch_id=cs_branches.id
    // 		INNER JOIN cs_courses ON subject_mapping.course_id=cs_courses.id
    // 		WHERE session_year='$session_year' and session='$session' and emp_no=$emp_id and subject_mapping.course_id <> 'jrf' ;");
    // 	$result= $query->result();
    // 	//print_r($result);
    // 	return $result;
    // }



    public function get_subjects($data = '') {
        $stu_id = $this->session->userdata('id');
        $this->load->database();
        if ($data !== '') {
            $session = $data['session'];
            $session_year = $data['session_year'];

            $temp = $this->db->query("SELECT DISTINCT form_id as sem_form_id
									  FROM reg_regular_form
									  WHERE session ='$session'
									  AND session_year ='$session_year'
									  AND admn_no = '$stu_id'");

            $sem_form_id = $temp->result();
            $sem_form_id = $sem_form_id[0]->sem_form_id;
            $sub_id = $this->db->query("SELECT DISTINCT subject_id, name
			                          FROM subjects
			                          WHERE id
			                          IN (SELECT DISTINCT sub_id
			                              FROM reg_regular_elective_opted
			                              WHERE stu_sem_reg_subject.form_id =$sem_form_id)");
            $result = $sub_id->result();
            return $result;
        }
    }

    public function get_semester($data = '') {
        $stu_id = $this->session->userdata('id');
        $session = $data['session'];
        $session_year = $data['session_year'];
        $this->load->database();

        $sem1 = $this->db->query("SELECT DISTINCT semester as semster
		                 FROM  " . ($session == 'Summer' ? 'reg_summer_form' : 'reg_regular_form') . "
		                 WHERE session = '$session'
		                 AND session_year = '$session_year'
		                 AND admn_no = '$stu_id'
						 AND hod_status ='1'
						 AND acad_status='1'
						 ");

        $sem1 = $sem1->result();
        // echo $this->db->last_query();
        $sem2 = $this->db->query("select DISTINCT semester as semster from reg_other_form where (reason='REPEATER' || reason='Repeater'||reason='Repeater')	and session ='$session'  AND session_year = '$session_year' AND admn_no = '$stu_id'  AND hod_status ='1'	 AND acad_status='1'");

        $sem2 = $sem2->result();


        $sem = array_merge($sem1, $sem2);

        //print_r($sem);
        return $sem;
    }

    public function show_attendance_model($data) {
        $this->load->database();
        $session_year = $data['session_year'];
        $session = $data['session'];
        $stu_id = $data['stu_id'];
        $semester = $data['semester'];




        if ($session <> 'Summer') {
            $query = $this->db->query("SELECT DISTINCT course_id, branch_id,form_id as sem_form_id,course_aggr_id
		                        FROM reg_regular_form
								WHERE admn_no = '$stu_id'
								AND session_year = '$session_year'
								AND session = '$session'
								AND hod_status ='1'
								AND acad_status='1'
								AND semester='$semester'
								");
            $que_result = $query->result();

            if (Exam_tabulation::functionallyEmpty_static($que_result)) {
                $query = $this->db->query("SELECT DISTINCT course_id, branch_id,form_id as sem_form_id,course_aggr_id
		                        FROM reg_other_form
								WHERE admn_no = '$stu_id'
								AND session_year = '$session_year'
								AND session = '$session'
								AND hod_status ='1'
								AND acad_status='1'
								AND semester='$semester'
								");
                $que_result = $query->result();

                $data['other_status'] = '1';
            }
            //echo $data['other_status'];
            //echo $this->db->last_query();die();
            //echo '<pre>'; print_r($que_result);echo '</pre>';
        } else {

            $this->load->model('exam_attendance/exam_attd_model');
            $p = $this->exam_attd_model->getexcusiveSubList($this->session->userdata('dept_id'), $this->session->userdata('course_id'), $this->session->userdata('branch_id'), ($data['semester'] - 1) . ',' . $data['semester'], true, $this->session->userdata('id'), $mode = 'multisem');
            //  echo $this->db->last_query();
            // echo '<pre>'; print_r($p);echo '</pre>';



            $H_sub_list = null;
            $sublist_H = null;
            $sublist_M = null;
            $que_result[] = (object) array();
            $h = 0;


            foreach ($p as $row) {

                if (!strstr($row->course_aggr_id, "honour") && !strstr($row->course_aggr_id, "minor")) {
                    $que_result[$h]->subject_id = $row->subject_id;
                    $que_result[$h]->name = $row->name;
                    $que_result[$h]->sem_form_id = $row->sem_form_id;
                    $que_result[$h]->sem_form_id = $row->sem_form_id;
                    $que_result[$h]->course_aggr_id = $row->course_aggr_id;
                    $que_result[$h]->id = $row->id;
                    $que_result[$h]->course_id = $this->session->userdata('course_id');
                    $que_result[$h]->branch_id = $this->session->userdata('branch_id');
                    $sublist[] = $row->id;
                    $h++;
                }
                if (strstr($row->course_aggr_id, "honour"))
                    $sublist_H[] = $row->id;
                if (strstr($row->course_aggr_id, "minor"))
                    $sublist_M[] = $row->id;
            }
            $summer_sub_str = "'" . implode("','", $sublist) . "'";
            //echo '<pre>'; print_r($que_result);echo '</pre>'; die();
            //print_r( $sublist_M); print_r( $sublist_H); die();
        }

        //   echo $this->db->last_query();
        $pos = strpos(strtolower($stu_id), 'je');
        // echo "pos ".$pos;

        $where_sem = ($session == 'Summer' ? " and  semester in (" . ($semester - 1) . ',' . $semester . ") " : " and  semester = '$semester' ");


        if (!Exam_tabulation::functionallyEmpty_static($que_result)) {

//==========================14-02-2019===========================
            if($data['other_status']=='1'){
                $tcs=explode('_', $que_result[0]->course_aggr_id);
                if($tcs[0]=="comm"){
                    $hsem1=explode(_, $semester);
                    if($hsem1[1]=='2'){
                        $section='E';

                    }else{
                        $section='A';
                    }
                    $semester=$hsem1[0];

                }

            }
 //==========================14-02-2019===========================

            if ($pos && ($semester == '1' || $semester == '2')) {
                $q = $this->db->get_where('stu_section_data', array('admn_no' => $stu_id, 'session_year' => $session_year));
                $sec = $q->row()->section;
                //==========================14-02-2019===========================
                if($data['other_status']=='1'){
                    $sec=$section;
                }
                //==========================14-02-2019===========================

                //print_r( $semester);
                $course_id = $que_result[0]->course_id;
                $branch_id = $que_result[0]->branch_id;
                $sem_form_id = $que_result[0]->sem_form_id;

                if ($session <> 'Summer') {

                    $map = $this->db->query("SELECT DISTINCT map_id
		                         FROM subject_mapping
		                         WHERE course_id = 'comm'
		                         AND branch_id = 'comm'
		                        and  semester = '$semester'
		                        AND session = '$session'
                                           AND section='$sec'
		                         AND session_year = '$session_year'
		                         ");
                } else {



                    $map = $this->db->query("  SELECT DISTINCT a.map_id,b.sub_id FROM subject_mapping a
                                              inner join subject_mapping_des b on a.map_id=b.map_id

		                         WHERE a.course_id = 'comm'
		                         AND a.branch_id = 'comm'
		                        and  a.semester in (" . ($semester - 1) . ',' . $semester . ")
		                        AND a.session = '$session'
                                         AND a.section='$sec'
		                         AND a.session_year = '$session_year'
                                              and b.sub_id in(" . $summer_sub_str . ")
		                         ");
                }
                // echo $this->db->last_query();die();
                $map_result = $map->result();
                $map_id = null;
                //print_r($map_result);
                if ((count($map_result) > 1) && $session == 'Summer')
                    foreach ($map->result() as $row) {
                        $map_id[] = $row->map_id . '_' . $row->sub_id;
                    } else
                    $map_id = $map_result[0]->map_id;


                // echo '<pre>'; print_r($map_id);echo '</pre>';
            }
            //=========================prep starts===================

            else if ($pos && ($semester == '-1' || $semester == '0')) {


                //print_r( $semester);
                $course_id = $que_result[0]->course_id;
                $branch_id = $que_result[0]->branch_id;
                $sem_form_id = $que_result[0]->sem_form_id;

                if ($session <> 'Summer') {

                    $map = $this->db->query("SELECT DISTINCT map_id
                                 FROM subject_mapping
                                 WHERE course_id = 'prep'
                                 AND branch_id = 'prep'
                                and  semester = '$semester'
                                AND session = '$session'

                                 AND session_year = '$session_year'
                                 ");
                } else {



                    $map = $this->db->query("  SELECT DISTINCT a.map_id,b.sub_id FROM subject_mapping a
                                              inner join subject_mapping_des b on a.map_id=b.map_id

                                 WHERE a.course_id = 'prep'
                                 AND a.branch_id = 'prep'
                                and  a.semester in (" . ($semester - 1) . ',' . $semester . ")
                                AND a.session = '$session'
                                         AND a.section='$sec'
                                 AND a.session_year = '$session_year'
                                              and b.sub_id in(" . $summer_sub_str . ")
                                 ");
                }
                // echo $this->db->last_query();die();
                $map_result = $map->result();
                $map_id = null;
                //print_r($map_result);
                if ((count($map_result) > 1) && $session == 'Summer')
                    foreach ($map->result() as $row) {
                        $map_id[] = $row->map_id . '_' . $row->sub_id;
                    } else
                    $map_id = $map_result[0]->map_id;


                // echo '<pre>'; print_r($map_id);echo '</pre>';
            }

            //=========================prep ends===========================
            else {

                $course_id = $que_result[0]->course_id;
                $branch_id = $que_result[0]->branch_id;
                $sem_form_id = $que_result[0]->sem_form_id;
                $aggr_id = $que_result[0]->course_aggr_id;


                if ($session <> 'Summer') {
                    $map = $this->db->query("SELECT DISTINCT map_id
		                         FROM subject_mapping
		                         WHERE course_id = '$course_id'
		                         AND branch_id = '$branch_id'
		                         $where_sem
		                         AND session = '$session'
		                         AND session_year = '$session_year'
								 AND aggr_id='$aggr_id'
		                        ");
                } else {



                    $map = $this->db->query("  SELECT DISTINCT a.map_id,b.sub_id FROM subject_mapping a
                                              inner join subject_mapping_des b on a.map_id=b.map_id

		                           WHERE a.course_id = '$course_id'
		                         AND a.branch_id = '$branch_id'
		                        and  a.semester in (" . ($semester - 1) . ',' . $semester . ")
		                        AND a.session = '$session'
		                         AND a.session_year = '$session_year'  AND a.aggr_id='$aggr_id'
                                              and b.sub_id in(" . $summer_sub_str . ")
		                         ");
                }
                /* if($this->session->userdata('id')=='15je001788'){
                  echo $this->db->last_query(); die();
                  } */
                $map_result = $map->result();
                //print_r($map_result);
                $map_id = null;
                if ((count($map_result) > 1) && $session == 'Summer')
                    foreach ($map_result as $row) {
                        $map_id[] = $row->map_id . '_' . $row->sub_id;
                    } else
                    $map_id = $map_result[0]->map_id;


                // echo '<pre>'; print_r($map_id);echo '</pre>';
            }
            if (!Exam_tabulation::functionallyEmpty_static($map_id)) {
                if ($session == 'Summer') {
                    $summer_map_str = "'" . (count($map_result) > 1 ? implode("','", $map_id) : $map_id ) . "'  ";
                    $qery_append = " and  subject_mapping_des.sub_id in (" . $summer_sub_str . ") ";
                    $qery_append2 = "   subject_mapping_des.map_id in (" . $summer_map_str . ") ";
                    $qery_append3 = "    and  (sm.aggr_id  not like 'minor_%' and  sm.aggr_id  not like 'honour_%')  ";
                    $qery_append3_append = " inner join subject_mapping sm on sm.map_id=subject_mapping_des.map_id ";
                } else {
                    $qery_append = "";
                    $qery_append2 = " subject_mapping_des.map_id = '$map_id' ";
                    $qery_append3 = '';
                    // $qery_append3= "    and  (sm.aggr_id  not like 'minor_%' and  sm.aggr_id  not like 'honour_%')  ";
                    $qery_append3_append = '';
                    //  $qery_append3_append=       " inner join subject_mapping sm on sm.map_id=subject_mapping_des.map_id ";
                }
                $subject = $this->db->query("SELECT DISTINCT subject_id, name ,id,type
			                        FROM subjects
			                        WHERE elective=0
			                        AND type<>'Non-Contact'
			                        AND id
			                       	IN (SELECT DISTINCT sub_id as subject_id
		                            FROM subject_mapping_des $qery_append3_append
		                            WHERE  " . $qery_append2 . "" . $qery_append3 . " " . $qery_append . " ) ");
                //  echo $this->db->last_query(); die();
                //=================@anuj_15-09-2018 filtering subject list for other students starts===============
                if ($data['other_status'] == '1') {
                    $subject = $this->db->query("select x.* from (SELECT DISTINCT subject_id, name ,id,type
			                        FROM subjects
			                        WHERE elective=0
			                        AND type<>'Non-Contact'
			                        AND id
			                       	IN (SELECT DISTINCT sub_id as subject_id
		                            FROM subject_mapping_des $qery_append3_append
		                            WHERE  " . $qery_append2 . "" . $qery_append3 . " " . $qery_append . "))x
inner join reg_other_subject y on y.sub_id=x.id
inner join reg_other_form z on z.form_id=y.form_id
where z.session_year='" . $data['session_year'] . "' and z.`session`='" . $data['session'] . "' and z.semester='" . $data['semester'] . "' and z.admn_no='" . $data['stu_id'] . "' group by x.id");

                    //print_r( $subject);die();
                    //echo $this->db->last_query(); die();
                }

//=================@anuj_15-09-2018 filtering subject list for other students ends===============
//  echo  '<pre>'; print_r($subject->result()); echo  '</pre>';
                if ($session == 'Summer') {
                    $get_sem = $this->db->query(" SELECT distinct sm.semester
		                            FROM subject_mapping_des $qery_append3_append
		                            WHERE  " . $qery_append2 . "" . $qery_append3 . " " . $qery_append . "  ");
                    //   echo  $get_sem->row()->semester; die();
                }

                if (!Exam_tabulation::functionallyEmpty_static($subject->result())) {

                    if ($session <> 'Summer') {
                        foreach ($subject->result() as $rr) {
                            $rr->course_id = $course_id;
                            $rr->branch_id = $branch_id;
                            $rr->sem_form_id = $sem_form_id;
                            $rr->course_aggr_id = $aggr_id;
                            $rr->map_id = $map_id;

                            $rr->subject_id = $rr->subject_id;
                            $rr->name = $rr->name;
                            $rr->id = $rr->id;
                            $rr->type = $rr->type;
                        }
                    }
                    //         echo  '<pre>'; print_r($subject->result()); echo  '</pre>';
                    //      print_r(  $H_sub_list); die();
                    if ($session == 'Summer') {
                        //   echo '<pre>'; print_r($subject->result() );echo '</pre>'; die();
                        $appended_row[] = (object) array();
                        $k = 0;
                        foreach ($subject->result() as $rr) {
                            //   if(!in_array($rr->id, $H_sub_list)){
                            $appended_row[$k]->course_id = $course_id;
                            $appended_row[$k]->branch_id = $branch_id;
                            $appended_row[$k]->sem_form_id = $sem_form_id;
                            $appended_row[$k]->course_aggr_id = $aggr_id;
                            //           $appended_row[$k]->map_id=( $session=='Summer'? ( $semester== $get_sem->row()->semester?$map_id[1]:$map_id[0]) :$map_id);
                            if (count($map_id) > 1) {
                                $l = 0;
                                while ($l < count($map_id)) {
                                    $tpp = null;
                                    $tpp = explode('_', $map_id[$l]);
                                    //echo '<pre>'; print_r($tpp);echo '</pre>';
                                    //  echo $tpp[1].'=='.$rr->id; echo '<br/>';
                                    if ($tpp[1] == $rr->id) {
                                        $appended_row[$k]->map_id = $tpp[0];
                                    }


                                    //echo $map_id[$l];echo '<br/>';
                                    $l++;
                                }
                            } else {
                                $appended_row[$k]->map_id = $map_id;
                            }


                            $appended_row[$k]->subject_id = $rr->subject_id;
                            $appended_row[$k]->name = $rr->name;
                            $appended_row[$k]->id = $rr->id;
                            $appended_row[$k]->type = $rr->type;
                            $k++;
                            // }
                        }
                    }

                    ($sub_name = ($session == 'Summer' ? $appended_row : $subject->result()));
                    //  echo'<pre>';   print_r($appended_row);   echo'</pre>';
                    if ($session <> 'Summer') {
                        /*   $subject= $this->db->query("SELECT subject_id, name ,id,type
                          FROM subjects
                          WHERE id
                          IN (SELECT DISTINCT sub_id as subject_id
                          FROM reg_regular_elective_opted
                          WHERE form_id = $sem_form_id) and type<>'Non-Contact'"); */
                        $subject = $this->db->query(
                                "SELECT subject_id, name ,subjects.id,type,
			            cs.aggr_id  FROM subjects
                                   join course_structure cs on cs.id=subjects.id
                                     WHERE subjects.id
			                        IN (SELECT DISTINCT sub_id as subject_id
		                            FROM reg_regular_elective_opted
		                            WHERE form_id = $sem_form_id) and type<>'Non-Contact'");

                        //  echo $this->db->last_query(); die();
                        $appended_row2[] = (object) array();
                        $t = 0;
                        foreach ($subject->result() as $rr) {
                            if (!strstr($rr->aggr_id, "honour") && !strstr($rr->aggr_id, "minor")) {   // checking consarint that elective subjects are not hons & minors and only be offered core elective subjects
                                $appended_row2[$t]->course_id = $rr->course_id = $course_id;
                                $appended_row2[$t]->branch_id = $rr->branch_id = $branch_id;
                                $appended_row2[$t]->sem_form_id = $rr->sem_form_id = $sem_form_id;
                                $appended_row2[$t]->course_aggr_id = $rr->course_aggr_id = $aggr_id;
                                $appended_row2[$t]->map_id = $rr->map_id = $map_id;


                                $appended_row2[$t]->subject_id = $rr->subject_id = $rr->subject_id;
                                $appended_row2[$t]->name = $rr->name = $rr->name;
                                $appended_row2[$t]->id = $rr->id = $rr->id;
                                $appended_row2[$t++]->type = $rr->type = $rr->type;
                            }// end  checking consarint
                        }
                        $sub_name1 = $appended_row2;
                        //   print_r($sub_name1);die();
                        $sub_name = array_merge($sub_name, $sub_name1);
                    }
                } else {
                    $sub_name['error_core'] = 'Faculty has not been yet mapped  for Core paper(s) for which you have to attend the classes.Once it will be accomplished you will be able to view the attendance details';
                }
            } else {
                $sub_name['error_core'] = 'Faculty has not been yet mapped  for Core paper(s) for which you have to attend the classes.Once it will be accomplished you will be able to view the attendance details';
            }
            //      echo'<pre>';   print_r($sub_name);   echo'</pre>'; die();
            // $temp_query = $this->db->query("SELECT sub_id
            // 								FROM reg_regular_elective_opted
            // 								WHERE form_id = $sem_form_id ");
            // ($temp_query = $temp_query->result());
            // //echo ' <br><br> ';
            // foreach ($temp_query as $row) {
            // $temp = $this->db->query("SELECT subject_id, name ,id
            // 	                      FROM subjects
            // 	                      WHERE id  = '$row->sub_id'
            // 	                      ");
            // 	($temp = $temp->result());
            // //echo ' <br><br> ';
            // 	array_push($sub_name,$temp[0]);
            // }
            //print_r($sub_name);
        }
        $this->load->model('student_sem_form/x', '', TRUE);
        $data = $this->x->get_hm_form($this->session->userdata('id'));
        //echo $this->db->last_query();die();
        foreach ($data as $dd) {
            //---------------------added for dual degree honours case or 5 year honour case starts------------------
            $course_duration = $this->get_course_duration($this->session->userdata('id'));
            $tmpaggid = explode('_', $dd->honours_agg_id);
            if ($course_duration->duration == '5' && $tmpaggid[0] == "honour") {
                $branch_id = $tmpaggid[1];
            }
            //---------------------added for dual degree honours case or 5 year honour case ends------------------
            //echo $dd->form_id;
            //      print_r($sublist_H);
            if (($dd->honours == 1 && $dd->honour_hod_status == 'Y') && ($session == 'Summer' ? (!Exam_tabulation::functionallyEmpty_static($sublist_H) ) : true )) {
                //echo $dd->honours_agg_id; die();
                $where_sem = ($session == 'Summer' ? " and  semester in (" . ($semester - 1) . ',' . $semester . ") " : " and  semester = '$semester' ");
                if ($session <> 'Summer') {
                    $map = $this->db->query("SELECT DISTINCT map_id
		                         FROM subject_mapping
		                         WHERE course_id = 'honour'
		                         AND branch_id = '$branch_id'
		                          $where_sem
		                         AND session = '$session'
		                         AND session_year = '$session_year'
								 AND aggr_id='" . $dd->honours_agg_id . "'
		                         ");
                } else {
                    $summer_h_sub_str = "'" . implode("','", $sublist_H) . "'";
                    $map = $this->db->query("  SELECT DISTINCT a.map_id,b.sub_id FROM subject_mapping a
                                              inner join subject_mapping_des b on a.map_id=b.map_id

		                           WHERE a.course_id = 'honour'
		                         AND a.branch_id = '$branch_id'
		                        and  a.semester in (" . ($semester - 1) . ',' . $semester . ")
		                        AND a.session = '$session'
		                         AND a.session_year = '$session_year'  AND a.aggr_id='" . $dd->honours_agg_id . "'
                                              and b.sub_id in(" . $summer_h_sub_str . ")
		                         ");
                }
                /* if($this->session->userdata('id')=='15je001788'){
                  echo $this->db->last_query();
                  echo '<pre>'; print_r( $map->result());echo '</pre>';					  die();
                  } */

                $map_result = $map->result();


                /*   if(empty($map_result)){
                  $map = $this->db->query("SELECT DISTINCT map_id
                  FROM subject_mapping
                  WHERE course_id = 'b.tech'
                  AND branch_id = '$branch_id'
                  $where_sem
                  AND session = '$session'
                  AND session_year = '$session_year'
                  AND aggr_id=REPLACE('".$dd->honours_agg_id."', 'honour', 'b.tech')
                  ");

                  $map_result = $map->result();
                  //  echo $this->db->last_query();
                  } */








                //print_r($map_result); echo'test' ; die();
                $map_id = null;
                if ((count($map_result) > 1) && $session == 'Summer')
                    foreach ($map_result as $row) {
                        $map_id[] = $row->map_id . '_' . $row->sub_id;
                    } else
                    $map_id = $map_result[0]->map_id;
                //print_r($map_id);die();
                if (!Exam_tabulation::functionallyEmpty_static($map_id)) {
                    $sub = $this->student_attendance_model->getSubjectviaAggrid(($session == 'Summer' ? ($semester - 1) . ',' . $semester : $semester), $dd->honours_agg_id, $this->session->userdata('dept_id'), null, 1);
                    if ($session <> 'Summer') {
                        // put constraint to check that elective subjects are hons offered  only
                        $subject_filter = $this->db->query(
                                "SELECT subject_id, name ,subjects.id,type,
			                          cs.aggr_id  FROM subjects
                                   join course_structure cs on cs.id=subjects.id
                                     WHERE subjects.id
			                        IN (SELECT DISTINCT sub_id as subject_id
		                            FROM reg_regular_elective_opted
		                            WHERE form_id =  $sem_form_id) and type<>'Non-Contact'");
                        //  echo $this->db->last_query();
                        $filltered_sub_array = null;

                        foreach ($subject_filter->result() as $qrow) {
                            if (strstr($qrow->aggr_id, "honour")) {
                                $filltered_sub_array[] = $qrow->id;
                            }
                        }
                        //end
                    }

                    /*   if($this->session->userdata('id')=='15je001788'){
                      echo $this->db->last_query();
                      echo '<pre>'; print_r(  $sub);echo '</pre>';					 // die();
                      } */
                    //echo $this->db->last_query();die();
                    //$this->load->view('student_sem_form/regular/hm');
                    $temp = array();
                    //	$temp=array_column($sub,'name , id , subject_id');
                    //	print_r($sub);
                    //  $key = array_search('7', array_column( $sub['subjects'], 'semester'));
                    foreach ($sub as $key => $tax) {
                        foreach ($tax as $value) {
                            if (array_search($semester, $value))
                                $taxonomy = key($tax);
                        }
                    }
                    /* if($this->session->userdata('id')=='15je001788'){
                      echo  'key'.($taxonomy);  die();
                      } */

                    for ($i = $taxonomy; $i < count($sub['subjects']) + $taxonomy; $i++) {
                        if (empty($filltered_sub_array)) {
                            $checkValidation = ($session == 'Summer' ? in_array($sub['subjects'][$i]['id'], $sublist_H) : TRUE);
                        } else {
                            $checkValidation = ($session == 'Summer' ? in_array($sub['subjects'][$i]['id'], $sublist_H) : (in_array($sub['subjects'][$i]['id'], $filltered_sub_array)));
                        }

                        if ($checkValidation) {
                            $temp[$i]['subject_id'] = $sub['subjects'][$i]['subject_id'];
                            $temp[$i]['name'] = $sub['subjects'][$i]['name'];
                            $temp[$i]['id'] = $sub['subjects'][$i]['id'];
                            $temp[$i]['type'] = "honour";
                            //$temp[$i]['map_id']=( $session=='Summer'? ( $semester==$sub['subjects'][$i]['semester']?$map_id[1]:$map_id[0]) :$map_id) ;  // added for summer
                            if (count($map_id) > 1) {
                                $l = 0;
                                while ($l < count($map_id)) {
                                    $tpp = null;
                                    $tpp = explode('_', $map_id[$l]);
                                    //echo '<pre>'; print_r($tpp);echo '</pre>';
                                    //  echo $tpp[1].'=='.$rr->id; echo '<br/>';
                                    if ($tpp[1] == $sub['subjects'][$i]['id']) {
                                        $temp[$i]['map_id'] = $tpp[0];
                                    }
                                    //echo $map_id[$l];echo '<br/>';
                                    $l++;
                                }
                            } else {
                                $temp[$i]['map_id'] = $map_id;
                            }
                            $temp[$i]['course_id'] = 'honour';
                            $temp[$i]['branch_id'] = $branch_id;
                            $temp[$i]['sem_form_id'] = $dd->form_id;
                            $temp[$i]['course_aggr_id'] = $dd->honours_agg_id;
                        }
                    }
                    // if($this->session->userdata('id')=='15je001788'){ echo'<pre>';   print_r($temp);   echo'</pre>'; die();}
                    $sub_name = array_merge($sub_name, $temp);
                } else {
                    $sub_name['error_H'] = 'Faculty has not been yet mapped  for Honour paper(s) for which you have to attend the classes.Once it will be accomplished you will be able to view the attendance details';
                }
            }
            if ($dd->minor == 1 && $dd->minor_hod_status == 'Y' && ($session == 'Summer' ? (!Exam_tabulation::functionallyEmpty_static($sublist_M) ) : true )) {
                $m_ag_id = $this->x->get_hm_minor_details($dd->form_id);
                //print_r($m_ag_id);die();
                $where_sem = ($session == 'Summer' ? " and  semester in (" . ($semester - 1) . ',' . $semester . ") " : " and  semester = '$semester' ");
                if ($session <> 'Summer') {
                    $map = $this->db->query("SELECT DISTINCT map_id
		                         FROM subject_mapping
                                         WHERE course_id = '" . $m_ag_id[0]->course_id . "'
		                         and  dept_id = '" . $m_ag_id[0]->dept_id . "'
		                         AND branch_id = '" . $m_ag_id[0]->branch_id . "'
		                     $where_sem
		                         AND session = '$session'
		                         AND session_year = '$session_year'
			                 AND aggr_id='" . $m_ag_id[0]->minor_agg_id . "'
		                         ");
                } else {

                    $summer_M_sub_str = "'" . implode("','", $sublist_M) . "'";
                    $map = $this->db->query("  SELECT DISTINCT a.map_id,b.sub_id FROM subject_mapping a
                                              inner join subject_mapping_des b on a.map_id=b.map_id

		                           WHERE a.course_id = '" . $m_ag_id[0]->course_id . "'
                                                  and  a.dept_id = '" . $m_ag_id[0]->dept_id . "'
		                         AND a.branch_id = '" . $m_ag_id[0]->branch_id . "'
		                        and  a.semester in (" . ($semester - 1) . ',' . $semester . ")
		                        AND a.session = '$session'
		                         AND a.session_year = '$session_year'  AND a.aggr_id='" . $m_ag_id[0]->minor_agg_id . "'
                                              and b.sub_id in(" . $summer_M_sub_str . ")
		                         ");
                }

                //  echo $this->db->last_query(); die();
                $map_result = $map->result();
                //print_r($map_result); die();
                $map_id = null;
                if ((count($map_result) > 1) && $session == 'Summer')
                    foreach ($map_result as $row) {
                        $map_id[] = $row->map_id . '_' . $row->sub_id;
                    } else
                    $map_id = $map_result[0]->map_id;
                if (!Exam_tabulation::functionallyEmpty_static($map_id)) {
                    //print_r($map_id); die();

                    foreach ($m_ag_id as $ag) {
                        //	echo $ag->minor_agg_id;
                        $sub = $this->student_attendance_model->getSubjectviaAggrid(($session == 'Summer' ? ($semester - 1) . ',' . $semester : $semester), $ag->minor_agg_id, $ag->dept_id, null, 1);

                        //echo "Minor";
                        //		$sub_name = array_merge($sub_name,$sub);
                        //	print_r($sub);

                        if ($session <> 'Summer') {

                            // put constraint to check that elective subjects are hons offered  only
                            $subject_filter = null;
                            $subject_filter = $this->db->query(
                                    "SELECT subject_id, name ,subjects.id,type,
			                          cs.aggr_id  FROM subjects
                                   join course_structure cs on cs.id=subjects.id
                                     WHERE subjects.id
			                        IN (SELECT DISTINCT sub_id as subject_id
		                            FROM reg_regular_elective_opted
		                            WHERE form_id =  $sem_form_id) and type<>'Non-Contact'");
                            //  echo $this->db->last_query();
                            $filltered_sub_array = null;

                            foreach ($subject_filter->result() as $qrow) {
                                if (strstr($qrow->aggr_id, "minor")) {
                                    $filltered_sub_array[] = $qrow->id;
                                }
                            }
                            // end
                        }
                        $temp = array();

                        foreach ($sub as $key => $tax) {
                            foreach ($tax as $value) {
                                if (array_search($semester, $value))
                                    $taxonomy = key($tax);
                            }
                        }
                        for ($i = $taxonomy; $i < count($sub['subjects']) + $taxonomy; $i++) {
                            if (empty($filltered_sub_array)) {
                                $checkValidation = ($session == 'Summer' ? in_array($sub['subjects'][$i]['id'], $sublist_M) : TRUE);
                            } else {
                                $checkValidation = ($session == 'Summer' ? in_array($sub['subjects'][$i]['id'], $sublist_M) : (in_array($sub['subjects'][$i]['id'], $filltered_sub_array)));
                            }

                            if ($checkValidation) {
                                $temp[$i]['subject_id'] = $sub['subjects'][$i]['subject_id'];
                                $temp[$i]['name'] = $sub['subjects'][$i]['name'];
                                $temp[$i]['id'] = $sub['subjects'][$i]['id'];
                                $temp[$i]['type'] = "minor";
                                //  $temp[$i]['map_id']=( $session=='Summer'? ( $semester==$sub['subjects'][$i]['semester']?$map_id[1]:$map_id[0]) :$map_id);
                                if (count($map_id) > 1) {
                                    $l = 0;
                                    while ($l < count($map_id)) {
                                        $tpp = null;
                                        $tpp = explode('_', $map_id[$l]);
                                        //echo '<pre>'; print_r($tpp);echo '</pre>';
                                        //  echo $tpp[1].'=='.$rr->id; echo '<br/>';
                                        if ($tpp[1] == $sub['subjects'][$i]['id']) {
                                            $temp[$i]['map_id'] = $tpp[0];
                                        }
                                        //echo $map_id[$l];echo '<br/>';
                                        $l++;
                                    }
                                } else {
                                    $temp[$i]['map_id'] = $map_id;
                                }
                                $temp[$i]['course_id'] = $ag->course_id;
                                $temp[$i]['branch_id'] = $ag->branch_id;
                                $temp[$i]['sem_form_id'] = $dd->form_id;
                                $temp[$i]['course_aggr_id'] = $ag->minor_agg_id;
                            }
                        }
                    }
                    $sub_name = array_merge($sub_name, $temp);
                } else {
                    $sub_name['error_M'] = 'Faculty has not been yet mapped  for Minor paper(s) for which you have to attend the classes.Once it will be accomplished you will be able to view the attendance details';
                }
            }
        }
        if (!empty($sub_name)) {
            $customKey = 0;
            $filterSubjectEmptyColumn = array();
            foreach ($sub_name as $key => $value) {
                $is_empty_array = (array) $value;
                if (!empty($is_empty_array)) {
                    $filterSubjectEmptyColumn[$customKey++] = $value;
                }
            }
            $sub_name = $filterSubjectEmptyColumn;
        }
        //$sub_name['map_id'] = $map_id;
        //echo'<pre>';   print_r($sub_name);   echo'</pre>'; die();
        // var_dump($sub_name);die();
        return $sub_name;
    }

    private function get_numeric($val) {
        if (is_numeric($val)) {
            return $val + 0;
        } return 0;
    }

    function getSubjectviaAggrid($semester, $aggr_id, $dept, $g = '', $ele = '') {

        if ($g) {
            $semester = $semester . "_" . $g;
            $dept = 'cse'; // hack code
        }
        //echo $semester.",".$aggr_id.",".$dept; die();
        $this->load->model('course_structure/basic_model');
        $data = $this->basic_model->get_subjects_by_sem_and_dept($semester, $aggr_id, $dept);
        // echo $this->db->last_query();die();
        //print_r($data);
        $i = 0;
        foreach ($data as $da) {
            if ($ele == 1) {
                $d['subjects'][$i]['sequence'] = $da->sequence;
                $d['subjects'][$i]['id'] = $da->id;
                $d['subjects'][$i]['subject_id'] = $this->basic_model->get_subject_details($da->id)->subject_id;
                $d['subjects'][$i]['name'] = $this->basic_model->get_subject_details($da->id)->name;
                $d['subjects'][$i]['semester'] = $da->semester;
            } else {
                if (!is_float($this->get_numeric($da->sequence))) {
                    $d['subjects'][$i]['sequence'] = $da->sequence;
                    $d['subjects'][$i]['id'] = $da->id;
                    $d['subjects'][$i]['subject_id'] = $this->basic_model->get_subject_details($da->id)->subject_id;
                    $d['subjects'][$i]['name'] = $this->basic_model->get_subject_details($da->id)->name;
                    $d['subjects'][$i]['semester'] = $da->semester;
                }
            }
            $i++;
        }
        //if($this->session->userdata('id')=='15je001788'){		  echo '<pre>';print_r($d);echo '</pre>';		  }
        return $d;
    }

    public function view_attendance_model($data, $result) {
        //echo '<pre>';var_dump($result);echo '</pre>';die();
        //  echo '<pre>';print_r($result['sub_name']);echo '</pre>';die();

        $stu_id = $data['stu_id'];
        $session_year = $data['session_year'];
        $semester = $data['semester'];
        $session = $data['session'];
        $subject = array();
        $i = 0;
        // $map_id= $result['sub_name']['map_id'];  // getting map id from show_attendance_model function
        foreach ($result['sub_name'] as $row) {

            if (is_object($row)) {
                $subject[$i]['map_id'] = $row->map_id;
                $temp[$i]['course_id'] = $row->course_id;
                $temp[$i]['branch_id'] = $row->branch_id;
                $temp[$i]['sem_form_id'] = $row->sem_form_id;
                $temp[$i]['course_aggr_id'] = $row->course_aggr_id;
                $subject[$i++]['id'] = $row->id;
            } else {
                $subject[$i]['map_id'] = $row['map_id'];
                $temp[$i]['course_id'] = $row['course_id'];
                $temp[$i]['branch_id'] = $row['branch_id'];
                $temp[$i]['sem_form_id'] = $row['sem_form_id'];
                $temp[$i]['course_aggr_id'] = $row['course_aggr_id'];
                $subject[$i++]['id'] = $row["id"];
            }
        }
        //anuj starts
        //echo'<pre>';  print_r($temp); echo'</pre>';die();
        $i = 0;
        foreach ($subject['id'] as $p) {

            $temp = $this->check_for_elective($p);
            if ($temp == '0') {
                $subject[$i++]['id'] = $p;
            } else {
                //echo 'Elec'.$p;
                $temp1 = $this->check_elective_absent_table($p);
                $subject[$i++]['id'] = $temp1;
            }
        }
        // $i--;
        //     echo'<pre>';    print_r($subject);echo'</pre>';die();
        //anuj ends

        /*
          $temp = $this->db->query("SELECT DISTINCT course_id , branch_id, form_id,course_aggr_id
          FROM reg_regular_form
          WHERE semester = $semester
          AND session_year = '$session_year'
          AND session = '$session'
          AND admn_no = '$stu_id' and hod_status='1' and acad_status='1' ");
          $temp = $temp->result();
          if(empty($temp)){
          $temp=$this->db->query("SELECT DISTINCT course_id, branch_id,form_id as sem_form_id,course_aggr_id
          FROM reg_other_form
          WHERE admn_no = '$stu_id'
          AND session_year = '$session_year'
          AND session = '$session'
          AND hod_status ='1'
          AND acad_status='1'
          AND semester='$semester'
          ");
          $temp = $temp->result();

          }
          //echo $this->db->last_query();
          $pos =strpos( strtolower($stu_id),'je');
          // echo "pos ".$pos;
          if($pos && ($semester =='1' || $semester =='2') ){
          $q= $this->db->get_where('stu_section_data', array('admn_no'=>$stu_id,'session_year'=>$session_year));
          $sec=$q->row()->section;

          //print_r( $semester);
          $course_id = $temp[0]->course_id;
          $branch_id = $temp[0]->branch_id;
          $sem_form_id = $temp[0]->form_id;




          $map = $this->db->query("SELECT DISTINCT map_id
          FROM subject_mapping
          WHERE course_id = 'comm'
          AND branch_id = 'comm'
          AND semester = '$semester'
          AND session = '$session'
          AND section='$sec'
          AND session_year = '$session_year'

          ");
          // echo $this->db->last_query();
          $map_result = $map->result();
          //print_r($map_result);
          $map_id = $map_result[0]->map_id;

          }else{

          //print_r( $semester);
          $course_id = $temp[0]->course_id;
          $branch_id = $temp[0]->branch_id;
          $sem_form_id = $temp[0]->form_id;
          $aggr_id = $temp[0]->course_aggr_id;



          $map = $this->db->query("SELECT DISTINCT map_id
          FROM subject_mapping
          WHERE course_id = '$course_id'
          AND branch_id = '$branch_id'
          AND semester = $semester
          AND session = '$session'
          AND session_year = '$session_year'
          AND aggr_id='$aggr_id'
          ");
          echo $this->db->last_query();
          $map_result = $map->result();
          //print_r($map_result);
          $map_id = $map_result[0]->map_id;
          }
          //print_r($result);
         */
        //   print_r($temp);anuj
        for ($i = 0; $i < count($subject); $i++) {
// modify on 22-10-2018 By SHYAM for "($i= 0;$i < count($subject)-1;$i++)"
// to corporate full attendance display. earlier attendant of Honors , minor is displaying wrong on list line
            //$group_no = $result['sub_name'][$i]->group_no;
            //---------------------added for dual degree honours case or 5 year honour case starts------------------
            $this->load->model('student_sem_form/x', '', TRUE);
            $data = $this->x->get_hm_form($this->session->userdata('id'));
            $tmpaggid = explode('_', $data[0]->honours_agg_id);
            $course_duration = $this->get_course_duration($this->session->userdata('id'));
            /* 	if($course_duration->duration=='5' && $tmpaggid[0]=="honour")
              {
              $table="absent_table_dd_hons";
              }
              else
              {
              $table="absent_table";
              }
             */
            $table = "absent_table";

            //---------------------added for dual degree honours case or 5 year honour case ends------------------


            $temp1 = $this->db->query("SELECT  date,class_no
					                 FROM " . $table . "
					                 WHERE sub_id  = '" . $subject[$i]['id'] . "'
					                 AND admn_no = '$stu_id'
					                      AND map_id = '" . $subject[$i]['map_id'] . "'
					                  ");

            $temp[$i] = $temp1->result();

            //print_r($temp[$i]);
            //echo $this->db->last_query(); die();
            //=====anuj starts==========
//                        if(empty($temp[$i])){
//
//                          $sub_another= $this->get_another_code($aggr_id,$session_year,$session,$subject[$i]);
//                           if(!empty($sub_another)){
//                   $sub_another1= $this->get_another_id($aggr_id,$session_year,$session,$sub_another[0]->subject_id,$sub_another[0]->id);
//
//                                $temp1= $this->db->query("SELECT  date
//					                 FROM absent_table
//					                 WHERE sub_id  = '".$sub_another1[0]->id."'
//					                 AND admn_no = '$stu_id'
//					                 AND map_id = '$map_id'
//					                  ");
//
//			$temp[$i] = $temp1->result();
//
//                            }
//
//                        }
//=====anuj ends========
            $temp[$i]['count'] = count($temp[$i]);
            //---------------------added for dual degree honours case or 5 year honour case starts------------------
            $this->load->model('student_sem_form/x', '', TRUE);
            $data = $this->x->get_hm_form($this->session->userdata('id'));
            $tmpaggid = explode('_', $data[0]->honours_agg_id);
            $course_duration = $this->get_course_duration($this->session->userdata('id'));
            /* if($course_duration->duration=='5' && $tmpaggid[0]=="honour")
              {
              $table="total_class_table_dd_hons";
              }
              else
              {
              $table="total_class_table";
              } */
            $table = "total_class_table";

            //---------------------added for dual degree honours case or 5 year honour case ends------------------





            $temp2 = $this->db->query("SELECT total_class
									FROM " . $table . "
									WHERE map_id =  '" . $subject[$i]['map_id'] . "'
									AND sub_id = '" . $subject[$i]['id'] . "'
									  ");
            $temp3 = $temp2->result();
            //  echo $this->db->last_query();
            //=========anuj starts===========
            //=========anuj ends===========
            //echo $temp3->total;
            //print_r($temp3);
            if (count($temp3)) {
                $temp[$i]['total'] = $temp3[0]->total_class;
            } else {
                $temp[$i]['total'] = 'class not started';
            }
        }

        return $temp;
    }

    function get_another_code($aggr_id, $syear, $session, $sub) {

        $sql = "select b.subject_id,a.id from optional_offered a
inner join subjects b on a.id=b.id where a.aggr_id=? and a.session_year=? and a.`session`=? and a.id=?";

        $query = $this->db->query($sql, array($aggr_id, $syear, $session, $sub));


        if ($this->db->affected_rows() >= 0) {
            return $query->result;
        } else {
            return false;
        }
    }

    function get_another_id($aggr_id, $session_year, $session, $scode, $sid) {
        $sql = "select a.id from optional_offered a
inner join subjects b on a.id=b.id
where a.aggr_id=? and
a.session_year=? and a.`session`=? and b.subject_id=?
and a.id<>?";

        $query = $this->db->query($sql, array($aggr_id, $session_year, $session, $scode, $sid));

        //echo $this->db->last_query();
        if ($this->db->affected_rows() >= 0) {
            return $query->result;
        } else {
            return false;
        }
    }

    public function get_total_group($session_id, $sub_id) {
        $this->load->database();
        $this->db->select('*');
        $this->db->from('prac_group_attendance');
        $this->db->where('session_id', $session_id);
        $this->db->where('sub_id', $sub_id);
        $this->db->order_by("group_no", "asc");
        $query = $this->db->get();
        return $query->result();
    }

    public function get_new_student($data) {

        $sub = $data['sub_id'];
        $session = $data['session'];
        $this->load->database();
        if ($session === 'Summer')
            $sub_table = 'reg_summer_subject';
        else
            $sub_table = 'reg_regular_elective_opted';
        $subs = $this->get_subject_id($sub, $data);
        $this->db->select('form_id');
        $this->db->from($sub_table);
        $w = "";
        $i = 0;
        foreach ($subs as $c) {
            if ($i == 0) {
                $w = "(`sub_id`='$c->id')";
            } else {
                $w.=" OR (`sub_id`='$c->id')";
            }
            $i++;
        }
        $this->db->where($w);
        //$this->db->where('sub_id',$sub);
        $query = $this->db->get();

        //echo $this->db->last_query(); die();
        //if($query->num_rows() >0){
        $data['form_no'] = $query->result();
        //}else{
        //	$data['form_no']=array();
        //}
        //if(count($data['form_no']) < 5) $data['form_no'] =array();
        //	print_r($this->get_admn($data));
        return $this->get_admn($data);
    }

    function get_subject_id($id, $data) {
        // echo $data['aggr_id'];

        $q = $this->db->select('subject_id')->where('id', $id)->get('subjects')->row();
        $r = "select subjects.id from subjects join course_structure on subjects.id=course_structure.id where subjects.subject_id=? and course_structure.aggr_id=?";
        $get = $this->db->query($r, array($q->subject_id, $data['aggr_id']))->result();
        //echo $this->db->last_query();
        //$get=$this->db->select('id')->where('subject_id',$q->subject_id)->get('subjects')->result();
        return $get;
    }

    public function get_admn($data) {

        $session_year = $data['session_year'];
        $session = $data['session'];
        $branch = $data['branch_id'];
        $semester = $data['semester'];
        $course = $data['course_id'];

        // print_r($data);
        $this->load->database();
        $tmp = array();
        for ($i = 0; $i < count($data['form_no']); $i++) {
            $tmp[$i] = $data['form_no'][$i]->form_id;
        }
        if ($i > 0) {
            $form_table = 'reg_regular_form';
            if ($session === 'Summer')
                $form_table = 'reg_summer_form';
            $this->db->select('admn_no');
            $this->db->from($form_table);
            $this->db->where('session_year', $session_year);
            $this->db->where('session', $session);
            $this->db->where('branch_id', $branch);
            $this->db->where('course_id', $course);
            $this->db->where('semester', $semester);
            $this->db->where('course_aggr_id', $data['aggr_id']);
            $this->db->where('hod_status', '1');
            $this->db->where('acad_status', '1');
            $this->db->where_in('form_id', $tmp);
            $query = $this->db->get();

            $data['stu_admn'] = $query->result();
            return $this->get_name($data);
        }
        else {
            $this->db->select('admn_no');
            $this->db->from('reg_regular_form');
            $this->db->where('session_year', $session_year);
            $this->db->where('session', $session);
            $this->db->where('branch_id', $branch);
            $this->db->where('course_id', $course);
            $this->db->where('semester', $semester);
            $this->db->where('course_aggr_id', $data['aggr_id']);
            $this->db->where('hod_status', '1');
            $this->db->where('acad_status', '1');
            $query = $this->db->get();
            //echo $this->db->last_query();
            $data['stu_admn'] = $query->result();
            return $this->get_name($data);
        }
    }

    public function get_session_id($session, $session_year, $semester, $branch_id, $course_id) {
        $this->load->database();
        $this->db->select('session_id');
        $this->db->from('session_track');
        $this->db->where('session', $session);
        $this->db->where('session_year', $session_year);
        $this->db->where('semester', $semester);
        $this->db->where('branch_id', $branch_id);
        $this->db->where('course_id', $course_id);
        $query = $this->db->get();
        return $query->result();
    }

    public function insert_into_session_track($session, $session_year, $semester, $branch_id, $course_id) {
        $this->load->database();
        $query = "INSERT INTO session_track (session,session_year,semester,branch_id,course_id) VALUES('$session','$session_year',$semester,'$branch_id','$course_id')";
        $this->db->query($query);
    }

    public function get_rep_student($data, $sub_id) {


        $this->load->database();
        $this->db->select('form_id');
        $this->db->from('reg_other_subject');
        $this->db->where('sub_id', $sub_id);
        $query = $this->db->get();
        $data['rep_form_no'] = $query->result();
        //if(count($data['rep_form_no'])!=0)
        return $this->get_rep_admn($data);
        //else
        //	return $data['rep_form_no'];
    }

    public function get_rep_admn($data) {
        $session_year = $data['session_year'];
        $session = $data['session'];
        $branch_id = $data['branch_id'];
        $semester = $data['semester'];
        $course_id = $data['course_id'];
        $this->load->database();
        $tmp = array();
        for ($i = 0; $i < count($data['rep_form_no']); $i++) {
            $tmp[$i] = $data['rep_form_no'][$i]->form_id;
        }
        if ($i > 0) {
            $this->db->select('admn_no');
            $this->db->from('reg_other_form');
            $this->db->where('session_year', $session_year);
            $this->db->where('session', $session);
            $this->db->where('branch_id', $branch_id);
            $this->db->where('course_id', $course_id);
            $this->db->where('semester', $semester);
            $this->db->like('reason', 'repeater');
//			$this->db->where('hod_status','1');
//			$this->db->where('acdmic_status','1');
            $this->db->where_in('form_id', $tmp);
            $this->db->where('course_aggr_id', $data['aggr_id']);
            $query = $this->db->get();
            $data['stu_rep_admn'] = $query->result();
            //if(count($data['stu_rep_admn'])!=0)
            return $this->get_rep_name($data);
            //else
            //return $data['stu_rep_admn'];
        }
    }

    public function get_rep_name($data) {
        $tmp = array();
        for ($i = 0; $i < count($data['stu_rep_admn']); $i++) {
            $tmp[$i] = $data['stu_rep_admn'][$i]->admn_no;
        }
        if ($i > 0) {
            $this->load->dbutil();
            $this->load->database();
            $this->db->select('id,first_name,middle_name,last_name');
            $this->db->from('user_details');
            //	$this->db->like('id','2012');
            $this->db->where_in('id', $tmp);
            $this->db->order_by("id", "asc");
            $query = $this->db->get();
            return $query->result();
        }
    }

    public function get_stu_info($data) {
        $this->load->database();

        $stu_id = $data['stu_id'];
        $semester = $data['semester'];
        $query = $this->db->query("SELECT DISTINCT session,session_year
		                        FROM reg_regular_form
								WHERE admn_no = '$stu_id'
								AND hod_status ='1'
								AND acad_status='1'
								AND semester='$semester'
								");
        $que_result = $query->result();
        return $que_result;
    }

    public function get_name($data) {
        $tmp = array();
        for ($i = 0; $i < count($data['stu_admn']); $i++) {
            //	echo $data['form_no'][$i]->sem_form_id;
            $tmp[$i] = $data['stu_admn'][$i]->admn_no;
        }
        if ($i > 0) {
            $this->load->dbutil();
            $this->load->database();
            $this->db->select('id,first_name,middle_name,last_name');
            $this->db->from('user_details');
            //	$this->db->like('id','2012');
            $this->db->where_in('id', $tmp);
            $this->db->order_by("id", "asc");
            $query = $this->db->get();


            return $query->result();
        }
    }

    public function get_total_class($subject_id, $map_id) {

        $r = $this->db->query('select * from prac_group_attendance a where a.sub_id=?', array($subject_id));
        //echo $this->db->last_query();
        if ($r->num_rows() > 0) {
            $group = $this->db->query('select a.group_no from absent_table a where a.sub_id=? and a.admn_no=? limit 1', array($subject_id, $this->session->userdata('id')));

            if ($group->num_rows() > 0) {
                $query = $this->db->query("SELECT date
		                FROM class_engaged
		                WHERE sub_id =? and map_id=? and group_no=? ORDER BY STR_TO_DATE(date, '%d-%m-%Y')", array($subject_id, $map_id, $group->row()->group_no));
                //echo $this->db->last_query();
                $result = $query->result();
            } else {
                $query = $this->db->query("SELECT date
		                FROM class_engaged
		                WHERE sub_id =? and map_id=? ORDER BY STR_TO_DATE(date, '%d-%m-%Y')", array($subject_id, $map_id));
                $result = $query->result();
            }
        } else {
            $query = $this->db->query("SELECT date
		                FROM class_engaged
		                WHERE sub_id =? and map_id=? ORDER BY STR_TO_DATE(date, '%d-%m-%Y')", array($subject_id, $map_id));
            $result = $query->result();
        }
        return $result;
        ($result);
    }

    public function get_total_class_dd_hons($subject_id, $map_id) {

        $r = $this->db->query('select * from prac_group_attendance a where a.sub_id=?', array($subject_id));
        //echo $this->db->last_query();
        if ($r->num_rows() > 0) {
            $group = $this->db->query('select a.group_no from absent_table_dd_hons a where a.sub_id=? and a.admn_no=? limit 1', array($subject_id, $this->session->userdata('id')));

            if ($group->num_rows() > 0) {
                $query = $this->db->query("SELECT date
		                FROM class_engaged_dd_hons
		                WHERE sub_id =? and map_id=? and group_no=? ORDER BY STR_TO_DATE(date, '%d-%m-%Y')", array($subject_id, $map_id, $group->row()->group_no));
                echo $this->db->last_query();
                $result = $query->result();
            } else {
                $query = $this->db->query("SELECT date
		                FROM class_engaged_dd_hons
		                WHERE sub_id =? and map_id=? ORDER BY STR_TO_DATE(date, '%d-%m-%Y')", array($subject_id, $map_id));
                $result = $query->result();
            }
        } else {
            $query = $this->db->query("SELECT date
		                FROM class_engaged_dd_hons
		                WHERE sub_id =? and map_id=? ORDER BY STR_TO_DATE(date, '%d-%m-%Y')", array($subject_id, $map_id));
            $result = $query->result();
        }
        return $result;
        ($result);
    }

    public function getExtraAttendance($id, $admn_no) {
        //	echo "SELECT count from Attendance_remark_table where sub_id='$id' and admn_no='$admn_no' order_by desc";
        $query = $this->db->query("SELECT count from attendance_remark_table where sub_id='$id' and admn_no='$admn_no' order by 'session_id' desc");
        if ($query->num_rows() > 0)
            return $query->num_rows();
        return 0;
    }

    //===============@anuj starts======
    function check_for_elective($p) {

        $sql = "select elective from subjects where id=?";
        $query = $this->db->query($sql, array($p));

        if ($this->db->affected_rows() >= 0) {
            return $query->row()->elective;
        } else {
            return false;
        }
    }

    function check_elective_absent_table($p) {

        $reg_details = $this->get_last_registration();
        $dcb_details = $this->get_dept_course_branch_details();
        $sy = $reg_details->session_year;
        $sess = $reg_details->session;
        $aggid = $reg_details->course_aggr_id;
        $sem = $reg_details->semester;
        $did = $dcb_details->dept_id;
        $cid = $dcb_details->course_id;
        $bid = $dcb_details->branch_id;
        $map_details = $this->get_mapping_id($sy, $sess, $aggid, $sem, $did, $cid, $bid);
        $map_id = $map_details->map_id;
        $admn_no = $this->session->userdata('id');
        $abs_status = $this->check_absent_table_for_elective_subject($admn_no, $map_id, $p);
        if (empty($abs_status)) {
            $ano_ele_sub_id = $this->get_another_elective_id($aggid, $sy, $sess, $p);
            return $ano_ele_sub_id->id;
        } else {
            return $p;
        }
    }

    function get_last_registration() {

        $id = $this->session->userdata('id');
        $sql = "select a.* from reg_regular_form a where a.admn_no=? order by a.semester desc limit 1;";
        $query = $this->db->query($sql, array($id));

        if ($this->db->affected_rows() >= 0) {
            return $query->row();
        } else {
            return false;
        }
    }

    function get_dept_course_branch_details() {

        $id = $this->session->userdata('id');
        $sql = "select a.dept_id,b.course_id,b.branch_id from user_details a inner join stu_academic b on a.id=b.admn_no where a.id=?;";
        $query = $this->db->query($sql, array($id));

        if ($this->db->affected_rows() >= 0) {
            return $query->row();
        } else {
            return false;
        }
    }

    function get_mapping_id($sy, $sess, $aggid, $sem, $did, $cid, $bid) {

        $sql = "select a.map_id from subject_mapping a
where a.session_year=? and a.`session`=?
and a.aggr_id=? and a.semester=?
and a.dept_id=? and a.course_id=? and a.branch_id=?;";
        $query = $this->db->query($sql, array($sy, $sess, $aggid, $sem, $did, $cid, $bid));

        if ($this->db->affected_rows() >= 0) {
            return $query->row();
        } else {
            return false;
        }
    }

    function check_absent_table_for_elective_subject($admn_no, $map_id, $p) {
        $sql = "select a.* from absent_table a where a.admn_no=? and a.map_id=? and sub_id=?";
        $query = $this->db->query($sql, array($admn_no, $map_id, $p));

        if ($this->db->affected_rows() >= 0) {
            return $query->row();
        } else {
            return false;
        }
    }

    function get_another_elective_id($aggid, $sy, $sess, $p) {

        $sql = "select a.id from optional_offered a
inner join subjects b on a.id=b.id
where a.aggr_id=? and a.session_year=? and a.session=?
and a.id<>? and b.name=(
select b.name from optional_offered a
inner join subjects b on a.id=b.id
where a.aggr_id=? and a.session_year=? and a.session=?
and a.id=?
)";
        $query = $this->db->query($sql, array($aggid, $sy, $sess, $p, $aggid, $sy, $sess, $p));

        if ($this->db->affected_rows() >= 0) {
            return $query->row();
        } else {
            return false;
        }
    }

    //===============@anuj ends======

    function get_course_duration($id) {
        $this->load->database();
        $query = $this->db->query("select b.duration from stu_academic a inner join cs_courses b on b.id=a.course_id
where a.admn_no='" . $id . "';");
        return $query->row();
    }

    function get_sub_map_id($id) {
        $this->load->database();
        $query = $this->db->query("select * from subject_mapping where map_id=" . $id);
        return $query->row();
    }

    function get_current_status($id) {
		
		
		

        $query = "select a.session_year,a.`session`,a.semester,a.course_aggr_id,a.admn_no,a.form_id,a.section,a.branch_id,a.course_id from reg_regular_form a join stu_academic b on a.admn_no=b.admn_no
 where a.admn_no=? and a.hod_status='1' and a.acad_status='1' and b.semester=a.semester order by form_id desc limit 1";
 
 if(in_array("jrf",$this->session->userdata('auth'))){

                    $query = "select a.session_year,a.`session`,a.semester,a.course_aggr_id,a.admn_no,a.form_id,a.section,a.branch_id,a.course_id from reg_regular_form a join stu_academic b on a.admn_no=b.admn_no
 where a.admn_no=? and a.hod_status='1' and a.acad_status='1'  order by form_id desc limit 1";

         }
		 
        $query = $this->db->query($query, array($id));
      //  echo $this->db->last_query();
        return $query->row();
    }
	
	function get_current_status_cbcs($id,$syear,$sess) {
		
		
		

        $query = "select a.session_year,a.`session`,a.semester,a.course_aggr_id,a.admn_no,a.form_id,a.section,a.branch_id,a.course_id from reg_regular_form a 
 where a.admn_no=? and a.hod_status='1' and a.acad_status='1' and a.session_year=? and a.session=? order by form_id desc limit 1";
 

		 
        $query = $this->db->query($query, array($id,$syear,$sess));
      //  echo $this->db->last_query();
        return $query->row();
    }
	
	function get_registration_details($id,$syear,$sess) {

        $query = "select a.* from reg_regular_form a where a.admn_no='$id' and a.session_year='$syear' and a.`session`='$sess' and a.hod_status='1' and a.acad_status='1'";
        $query = $this->db->query($query, array($id));
        //echo $this->db->last_query();die();
        return $query->row();
    }
	function get_sub_check($form_id,$admn_no){
		$query = "select * from cbcs_stu_course where form_id=? and admn_no=?
		union
		select * from old_stu_course where form_id=? and admn_no=?";
        $query = $this->db->query($query, array($form_id,$admn_no,$form_id,$admn_no));
        //echo $this->db->last_query();die();
        return $query->result();
		
	}

    public function custom_view_attendance_model($data, $result) {
        $stu_id = $data['stu_id'];
        $session_year = $data['session_year'];
        $semester = $data['semester'];
        $session = $data['session'];
        $subject = array();
        $i = 0;
        // $map_id= $result['sub_name']['map_id'];  // getting map id from show_attendance_model function
        foreach ($result['sub_name'] as $row) {

            if (is_object($row)) {
                $subject[$i]['map_id'] = $row->map_id;
                $temp[$i]['course_id'] = $row->course_id;
                $temp[$i]['branch_id'] = $row->branch_id;
                $temp[$i]['sem_form_id'] = $row->sem_form_id;
                $temp[$i]['course_aggr_id'] = $row->course_aggr_id;
                $subject[$i++]['id'] = $row->id;
            } else {
                $subject[$i]['map_id'] = $row['map_id'];
                $temp[$i]['course_id'] = $row['course_id'];
                $temp[$i]['branch_id'] = $row['branch_id'];
                $temp[$i]['sem_form_id'] = $row['sem_form_id'];
                $temp[$i]['course_aggr_id'] = $row['course_aggr_id'];
                $subject[$i++]['id'] = $row["id"];
            }
        }
        $i = 0;
        foreach ($subject['id'] as $p) {

            $temp = $this->check_for_elective($p);
            if ($temp == '0') {
                $subject[$i++]['id'] = $p;
            } else {
                $temp1 = $this->check_elective_absent_table($p);
                $subject[$i++]['id'] = $temp1;
            }
        }

        for ($i = 0; $i < count($subject); $i++) {
            //---------------------added for dual degree honours case or 5 year honour case starts------------------
            $this->load->model('student_sem_form/x', '', TRUE);
            $data = $this->x->get_hm_form($stu_id);
            $tmpaggid = explode('_', $data[0]->honours_agg_id);
            $course_duration = $this->get_course_duration($stu_id);
            $table = "absent_table";
            //---------------------added for dual degree honours case or 5 year honour case ends------------------
            $temp1 = $this->db->query("SELECT  date
					                 FROM " . $table . "
					                 WHERE sub_id  = '" . $subject[$i]['id'] . "'
					                 AND admn_no = '$stu_id'
					                      AND map_id = '" . $subject[$i]['map_id'] . "'
					                  ");

            $temp[$i] = $temp1->result();
            $temp[$i]['count'] = count($temp[$i]);
            //---------------------added for dual degree honours case or 5 year honour case starts------------------
            $this->load->model('student_sem_form/x', '', TRUE);
            $data = $this->x->get_hm_form($stu_id);
            $tmpaggid = explode('_', $data[0]->honours_agg_id);
            $course_duration = $this->get_course_duration($stu_id);

            $table = "total_class_table";
            //---------------------added for dual degree honours case or 5 year honour case ends------------------
            $temp2 = $this->db->query("SELECT total_class
									FROM " . $table . "
									WHERE map_id =  '" . $subject[$i]['map_id'] . "'
									AND sub_id = '" . $subject[$i]['id'] . "'
									  ");
            $temp3 = $temp2->result();
            if (count($temp3)) {
                $temp[$i]['total'] = $temp3[0]->total_class;
            } else {
                $temp[$i]['total'] = 'class not started';
            }
        }
        return $temp;
    }

    public function get_record_from_filter($table_name, $request_fields = "", $filters_id_values) {
        $str = '';
        if ($request_fields) {
            foreach ($request_fields as $column_name) {
                $str.= $column_name . ', ';
            }
        } else {
            $str = '*';
        }

        $this->db->select($str);
        if ($filters_id_values) {
            foreach ($filters_id_values as $filter) {
                $this->db->where($filter['id'], $filter['value']);
            }
        }
        $query = $this->db->get($table_name);
        return $query->row_array();
    }

    public function custom_show_attendance_model($data) {
        $this->load->database();
        $session_year = $data['session_year'];
        $session = $data['session'];
        $stu_id = $data['stu_id'];
        $semester = $data['semester'];
        $c_dept_id = $data["dept_id"];
        $c_branch_id = $data["branch_id"];
        $c_course_id = $data["course_id"];

        if ($session <> 'Summer') {
            $query = $this->db->query("SELECT DISTINCT course_id, branch_id,form_id as sem_form_id,course_aggr_id
		                        FROM reg_regular_form
								WHERE admn_no = '$stu_id'
								AND session_year = '$session_year'
								AND session = '$session'
								AND hod_status ='1'
								AND acad_status='1'
								AND semester='$semester'
								");
            $que_result = $query->result();

            if (Exam_tabulation::functionallyEmpty_static($que_result)) {
                $query = $this->db->query("SELECT DISTINCT course_id, branch_id,form_id as sem_form_id,course_aggr_id
		                        FROM reg_other_form
								WHERE admn_no = '$stu_id'
								AND session_year = '$session_year'
								AND session = '$session'
								AND hod_status ='1'
								AND acad_status='1'
								AND semester='$semester'
								");
                $que_result = $query->result();

                $data['other_status'] = '1';
            }
            //echo $data['other_status'];
            //echo $this->db->last_query();die();
            //echo '<pre>'; print_r($que_result);echo '</pre>';
        } else {

            $this->load->model('exam_attendance/exam_attd_model');
            $p = $this->exam_attd_model->getexcusiveSubList($c_dept_id, $c_course_id, $c_branch_id, ($data['semester'] - 1) . ',' . $data['semester'], true, $stu_id, $mode = 'multisem');
            //  echo $this->db->last_query();
            // echo '<pre>'; print_r($p);echo '</pre>';



            $H_sub_list = null;
            $sublist_H = null;
            $sublist_M = null;
            $que_result[] = (object) array();
            $h = 0;


            foreach ($p as $row) {

                if (!strstr($row->course_aggr_id, "honour") && !strstr($row->course_aggr_id, "minor")) {
                    $que_result[$h]->subject_id = $row->subject_id;
                    $que_result[$h]->name = $row->name;
                    $que_result[$h]->sem_form_id = $row->sem_form_id;
                    $que_result[$h]->sem_form_id = $row->sem_form_id;
                    $que_result[$h]->course_aggr_id = $row->course_aggr_id;
                    $que_result[$h]->id = $row->id;
                    $que_result[$h]->course_id = $c_course_id;
                    $que_result[$h]->branch_id = $c_branch_id;
                    $sublist[] = $row->id;
                    $h++;
                }
                if (strstr($row->course_aggr_id, "honour"))
                    $sublist_H[] = $row->id;
                if (strstr($row->course_aggr_id, "minor"))
                    $sublist_M[] = $row->id;
            }
            $summer_sub_str = "'" . implode("','", $sublist) . "'";
            //echo '<pre>'; print_r($que_result);echo '</pre>'; die();
            //print_r( $sublist_M); print_r( $sublist_H); die();
        }

        //   echo $this->db->last_query();
        $pos = strpos(strtolower($stu_id), 'je');
        // echo "pos ".$pos;

        $where_sem = ($session == 'Summer' ? " and  semester in (" . ($semester - 1) . ',' . $semester . ") " : " and  semester = '$semester' ");


        if (!Exam_tabulation::functionallyEmpty_static($que_result)) {
            if ($pos && ($semester == '1' || $semester == '2')) {
                $q = $this->db->get_where('stu_section_data', array('admn_no' => $stu_id, 'session_year' => $session_year));
                $sec = $q->row()->section;

                //print_r( $semester);
                $course_id = $que_result[0]->course_id;
                $branch_id = $que_result[0]->branch_id;
                $sem_form_id = $que_result[0]->sem_form_id;

                if ($session <> 'Summer') {

                    $map = $this->db->query("SELECT DISTINCT map_id
		                         FROM subject_mapping
		                         WHERE course_id = 'comm'
		                         AND branch_id = 'comm'
		                        and  semester = '$semester'
		                        AND session = '$session'
                                           AND section='$sec'
		                         AND session_year = '$session_year'
		                         ");
                } else {



                    $map = $this->db->query("  SELECT DISTINCT a.map_id,b.sub_id FROM subject_mapping a
                                              inner join subject_mapping_des b on a.map_id=b.map_id

		                         WHERE a.course_id = 'comm'
		                         AND a.branch_id = 'comm'
		                        and  a.semester in (" . ($semester - 1) . ',' . $semester . ")
		                        AND a.session = '$session'
                                         AND a.section='$sec'
		                         AND a.session_year = '$session_year'
                                              and b.sub_id in(" . $summer_sub_str . ")
		                         ");
                }
                // echo $this->db->last_query();die();
                $map_result = $map->result();
                $map_id = null;
                //print_r($map_result);
                if ((count($map_result) > 1) && $session == 'Summer')
                    foreach ($map->result() as $row) {
                        $map_id[] = $row->map_id . '_' . $row->sub_id;
                    } else
                    $map_id = $map_result[0]->map_id;


                // echo '<pre>'; print_r($map_id);echo '</pre>';
            } else {

                $course_id = $que_result[0]->course_id;
                $branch_id = $que_result[0]->branch_id;
                $sem_form_id = $que_result[0]->sem_form_id;
                $aggr_id = $que_result[0]->course_aggr_id;


                if ($session <> 'Summer') {
                    $map = $this->db->query("SELECT DISTINCT map_id
		                         FROM subject_mapping
		                         WHERE course_id = '$course_id'
		                         AND branch_id = '$branch_id'
		                         $where_sem
		                         AND session = '$session'
		                         AND session_year = '$session_year'
								 AND aggr_id='$aggr_id'
		                        ");
                } else {



                    $map = $this->db->query("  SELECT DISTINCT a.map_id,b.sub_id FROM subject_mapping a
                                              inner join subject_mapping_des b on a.map_id=b.map_id

		                           WHERE a.course_id = '$course_id'
		                         AND a.branch_id = '$branch_id'
		                        and  a.semester in (" . ($semester - 1) . ',' . $semester . ")
		                        AND a.session = '$session'
		                         AND a.session_year = '$session_year'  AND a.aggr_id='$aggr_id'
                                              and b.sub_id in(" . $summer_sub_str . ")
		                         ");
                }
                $map_result = $map->result();
                //print_r($map_result);
                $map_id = null;
                if ((count($map_result) > 1) && $session == 'Summer')
                    foreach ($map_result as $row) {
                        $map_id[] = $row->map_id . '_' . $row->sub_id;
                    } else
                    $map_id = $map_result[0]->map_id;


                // echo '<pre>'; print_r($map_id);echo '</pre>';
            }
            if (!Exam_tabulation::functionallyEmpty_static($map_id)) {
                if ($session == 'Summer') {
                    $summer_map_str = "'" . (count($map_result) > 1 ? implode("','", $map_id) : $map_id ) . "'  ";
                    $qery_append = " and  subject_mapping_des.sub_id in (" . $summer_sub_str . ") ";
                    $qery_append2 = "   subject_mapping_des.map_id in (" . $summer_map_str . ") ";
                    $qery_append3 = "    and  (sm.aggr_id  not like 'minor_%' and  sm.aggr_id  not like 'honour_%')  ";
                    $qery_append3_append = " inner join subject_mapping sm on sm.map_id=subject_mapping_des.map_id ";
                } else {
                    $qery_append = "";
                    $qery_append2 = " subject_mapping_des.map_id = '$map_id' ";
                    $qery_append3 = '';
                    // $qery_append3= "    and  (sm.aggr_id  not like 'minor_%' and  sm.aggr_id  not like 'honour_%')  ";
                    $qery_append3_append = '';
                    //  $qery_append3_append=       " inner join subject_mapping sm on sm.map_id=subject_mapping_des.map_id ";
                }
                $subject = $this->db->query("SELECT DISTINCT subject_id, name ,id,type
			                        FROM subjects
			                        WHERE elective=0
			                        AND type<>'Non-Contact'
			                        AND id
			                       	IN (SELECT DISTINCT sub_id as subject_id
		                            FROM subject_mapping_des $qery_append3_append
		                            WHERE  " . $qery_append2 . "" . $qery_append3 . " " . $qery_append . " ) ");
                //  echo $this->db->last_query(); die();
                //=================@anuj_15-09-2018 filtering subject list for other students starts===============
                if ($data['other_status'] == '1') {
                    $subject = $this->db->query("select x.* from (SELECT DISTINCT subject_id, name ,id,type
			                        FROM subjects
			                        WHERE elective=0
			                        AND type<>'Non-Contact'
			                        AND id
			                       	IN (SELECT DISTINCT sub_id as subject_id
		                            FROM subject_mapping_des $qery_append3_append
		                            WHERE  " . $qery_append2 . "" . $qery_append3 . " " . $qery_append . "))x
inner join reg_other_subject y on y.sub_id=x.id
inner join reg_other_form z on z.form_id=y.form_id
where z.session_year='" . $data['session_year'] . "' and z.`session`='" . $data['session'] . "' and z.semester=" . $data['semester'] . " and z.admn_no='" . $data['stu_id'] . "'");

                    //echo $this->db->last_query(); die();
                }

//=================@anuj_15-09-2018 filtering subject list for other students ends===============
//  echo  '<pre>'; print_r($subject->result()); echo  '</pre>';
                if ($session == 'Summer') {
                    $get_sem = $this->db->query(" SELECT distinct sm.semester
		                            FROM subject_mapping_des $qery_append3_append
		                            WHERE  " . $qery_append2 . "" . $qery_append3 . " " . $qery_append . "  ");
                    //   echo  $get_sem->row()->semester; die();
                }

                if (!Exam_tabulation::functionallyEmpty_static($subject->result())) {

                    if ($session <> 'Summer') {
                        foreach ($subject->result() as $rr) {
                            $rr->course_id = $course_id;
                            $rr->branch_id = $branch_id;
                            $rr->sem_form_id = $sem_form_id;
                            $rr->course_aggr_id = $aggr_id;
                            $rr->map_id = $map_id;

                            $rr->subject_id = $rr->subject_id;
                            $rr->name = $rr->name;
                            $rr->id = $rr->id;
                            $rr->type = $rr->type;
                        }
                    }
                    //         echo  '<pre>'; print_r($subject->result()); echo  '</pre>';
                    //      print_r(  $H_sub_list); die();
                    if ($session == 'Summer') {
                        //   echo '<pre>'; print_r($subject->result() );echo '</pre>'; die();
                        $appended_row[] = (object) array();
                        $k = 0;
                        foreach ($subject->result() as $rr) {
                            //   if(!in_array($rr->id, $H_sub_list)){
                            $appended_row[$k]->course_id = $course_id;
                            $appended_row[$k]->branch_id = $branch_id;
                            $appended_row[$k]->sem_form_id = $sem_form_id;
                            $appended_row[$k]->course_aggr_id = $aggr_id;
                            //           $appended_row[$k]->map_id=( $session=='Summer'? ( $semester== $get_sem->row()->semester?$map_id[1]:$map_id[0]) :$map_id);
                            if (count($map_id) > 1) {
                                $l = 0;
                                while ($l < count($map_id)) {
                                    $tpp = null;
                                    $tpp = explode('_', $map_id[$l]);
                                    //echo '<pre>'; print_r($tpp);echo '</pre>';
                                    //  echo $tpp[1].'=='.$rr->id; echo '<br/>';
                                    if ($tpp[1] == $rr->id) {
                                        $appended_row[$k]->map_id = $tpp[0];
                                    }


                                    //echo $map_id[$l];echo '<br/>';
                                    $l++;
                                }
                            } else {
                                $appended_row[$k]->map_id = $map_id;
                            }


                            $appended_row[$k]->subject_id = $rr->subject_id;
                            $appended_row[$k]->name = $rr->name;
                            $appended_row[$k]->id = $rr->id;
                            $appended_row[$k]->type = $rr->type;
                            $k++;
                            // }
                        }
                    }

                    ($sub_name = ($session == 'Summer' ? $appended_row : $subject->result()));
                    //  echo'<pre>';   print_r($appended_row);   echo'</pre>';
                    if ($session <> 'Summer') {
                        /*   $subject= $this->db->query("SELECT subject_id, name ,id,type
                          FROM subjects
                          WHERE id
                          IN (SELECT DISTINCT sub_id as subject_id
                          FROM reg_regular_elective_opted
                          WHERE form_id = $sem_form_id) and type<>'Non-Contact'"); */
                        $subject = $this->db->query(
                                "SELECT subject_id, name ,subjects.id,type,
			            cs.aggr_id  FROM subjects
                                   join course_structure cs on cs.id=subjects.id
                                     WHERE subjects.id
			                        IN (SELECT DISTINCT sub_id as subject_id
		                            FROM reg_regular_elective_opted
		                            WHERE form_id = $sem_form_id) and type<>'Non-Contact'");

                        //  echo $this->db->last_query(); die();
                        $appended_row2[] = (object) array();
                        $t = 0;
                        foreach ($subject->result() as $rr) {
                            if (!strstr($rr->aggr_id, "honour") && !strstr($rr->aggr_id, "minor")) {   // checking consarint that elective subjects are not hons & minors and only be offered core elective subjects
                                $appended_row2[$t]->course_id = $rr->course_id = $course_id;
                                $appended_row2[$t]->branch_id = $rr->branch_id = $branch_id;
                                $appended_row2[$t]->sem_form_id = $rr->sem_form_id = $sem_form_id;
                                $appended_row2[$t]->course_aggr_id = $rr->course_aggr_id = $aggr_id;
                                $appended_row2[$t]->map_id = $rr->map_id = $map_id;


                                $appended_row2[$t]->subject_id = $rr->subject_id = $rr->subject_id;
                                $appended_row2[$t]->name = $rr->name = $rr->name;
                                $appended_row2[$t]->id = $rr->id = $rr->id;
                                $appended_row2[$t++]->type = $rr->type = $rr->type;
                            }// end  checking consarint
                        }
                        $sub_name1 = $appended_row2;
                        //   print_r($sub_name1);die();
                        $sub_name = array_merge($sub_name, $sub_name1);
                    }
                } else {
                    $sub_name['error_core'] = 'Faculty has not been yet mapped  for Core paper(s) for which you have to attend the classes.Once it will be accomplished you will be able to view the attendance details';
                }
            } else {
                $sub_name['error_core'] = 'Faculty has not been yet mapped  for Core paper(s) for which you have to attend the classes.Once it will be accomplished you will be able to view the attendance details';
            }
        }
        $this->load->model('student_sem_form/x', '', TRUE);
        $data = $this->x->get_hm_form($stu_id);
        //echo $this->db->last_query();die();
        foreach ($data as $dd) {
            //---------------------added for dual degree honours case or 5 year honour case starts------------------
            $course_duration = $this->get_course_duration($stu_id);
            $tmpaggid = explode('_', $dd->honours_agg_id);
            if ($course_duration->duration == '5' && $tmpaggid[0] == "honour") {
                $branch_id = $tmpaggid[1];
            }
            //---------------------added for dual degree honours case or 5 year honour case ends------------------
            //echo $dd->form_id;
            //      print_r($sublist_H);
            if (($dd->honours == 1 && $dd->honour_hod_status == 'Y') && ($session == 'Summer' ? (!Exam_tabulation::functionallyEmpty_static($sublist_H) ) : true )) {
                //echo $dd->honours_agg_id; die();
                $where_sem = ($session == 'Summer' ? " and  semester in (" . ($semester - 1) . ',' . $semester . ") " : " and  semester = '$semester' ");
                if ($session <> 'Summer') {
                    $map = $this->db->query("SELECT DISTINCT map_id
		                         FROM subject_mapping
		                         WHERE course_id = 'honour'
		                         AND branch_id = '$branch_id'
		                          $where_sem
		                         AND session = '$session'
		                         AND session_year = '$session_year'
								 AND aggr_id='" . $dd->honours_agg_id . "'
		                         ");
                } else {
                    $summer_h_sub_str = "'" . implode("','", $sublist_H) . "'";
                    $map = $this->db->query("  SELECT DISTINCT a.map_id,b.sub_id FROM subject_mapping a
                                              inner join subject_mapping_des b on a.map_id=b.map_id

		                           WHERE a.course_id = 'honour'
		                         AND a.branch_id = '$branch_id'
		                        and  a.semester in (" . ($semester - 1) . ',' . $semester . ")
		                        AND a.session = '$session'
		                         AND a.session_year = '$session_year'  AND a.aggr_id='" . $dd->honours_agg_id . "'
                                              and b.sub_id in(" . $summer_h_sub_str . ")
		                         ");
                }


                $map_result = $map->result();
                $map_id = null;
                if ((count($map_result) > 1) && $session == 'Summer')
                    foreach ($map_result as $row) {
                        $map_id[] = $row->map_id . '_' . $row->sub_id;
                    } else
                    $map_id = $map_result[0]->map_id;
                //print_r($map_id);die();
                if (!Exam_tabulation::functionallyEmpty_static($map_id)) {
                    $sub = $this->student_attendance_model->getSubjectviaAggrid(($session == 'Summer' ? ($semester - 1) . ',' . $semester : $semester), $dd->honours_agg_id, $c_dept_id, null, 1);
                    if ($session <> 'Summer') {
                        // put constraint to check that elective subjects are hons offered  only
                        $subject_filter = $this->db->query(
                                "SELECT subject_id, name ,subjects.id,type,
			                          cs.aggr_id  FROM subjects
                                   join course_structure cs on cs.id=subjects.id
                                     WHERE subjects.id
			                        IN (SELECT DISTINCT sub_id as subject_id
		                            FROM reg_regular_elective_opted
		                            WHERE form_id =  $sem_form_id) and type<>'Non-Contact'");
                        //  echo $this->db->last_query();
                        $filltered_sub_array = null;

                        foreach ($subject_filter->result() as $qrow) {
                            if (strstr($qrow->aggr_id, "honour")) {
                                $filltered_sub_array[] = $qrow->id;
                            }
                        }
                        //end
                    }


                    $temp = array();

                    foreach ($sub as $key => $tax) {
                        foreach ($tax as $value) {
                            if (array_search($semester, $value))
                                $taxonomy = key($tax);
                        }
                    }
                    for ($i = $taxonomy; $i < count($sub['subjects']) + $taxonomy; $i++) {
                        if (empty($filltered_sub_array)) {
                            $checkValidation = ($session == 'Summer' ? in_array($sub['subjects'][$i]['id'], $sublist_H) : TRUE);
                        } else {
                            $checkValidation = ($session == 'Summer' ? in_array($sub['subjects'][$i]['id'], $sublist_H) : (in_array($sub['subjects'][$i]['id'], $filltered_sub_array)));
                        }

                        if ($checkValidation) {
                            $temp[$i]['subject_id'] = $sub['subjects'][$i]['subject_id'];
                            $temp[$i]['name'] = $sub['subjects'][$i]['name'];
                            $temp[$i]['id'] = $sub['subjects'][$i]['id'];
                            $temp[$i]['type'] = "honour";
                            //$temp[$i]['map_id']=( $session=='Summer'? ( $semester==$sub['subjects'][$i]['semester']?$map_id[1]:$map_id[0]) :$map_id) ;  // added for summer
                            if (count($map_id) > 1) {
                                $l = 0;
                                while ($l < count($map_id)) {
                                    $tpp = null;
                                    $tpp = explode('_', $map_id[$l]);
                                    //echo '<pre>'; print_r($tpp);echo '</pre>';
                                    //  echo $tpp[1].'=='.$rr->id; echo '<br/>';
                                    if ($tpp[1] == $sub['subjects'][$i]['id']) {
                                        $temp[$i]['map_id'] = $tpp[0];
                                    }
                                    //echo $map_id[$l];echo '<br/>';
                                    $l++;
                                }
                            } else {
                                $temp[$i]['map_id'] = $map_id;
                            }
                            $temp[$i]['course_id'] = 'honour';
                            $temp[$i]['branch_id'] = $branch_id;
                            $temp[$i]['sem_form_id'] = $dd->form_id;
                            $temp[$i]['course_aggr_id'] = $dd->honours_agg_id;
                        }
                    }

                    $sub_name = array_merge($sub_name, $temp);
                } else {
                    $sub_name['error_H'] = 'Faculty has not been yet mapped  for Honour paper(s) for which you have to attend the classes.Once it will be accomplished you will be able to view the attendance details';
                }
            }
            if ($dd->minor == 1 && $dd->minor_hod_status == 'Y' && ($session == 'Summer' ? (!Exam_tabulation::functionallyEmpty_static($sublist_M) ) : true )) {
                $m_ag_id = $this->x->get_hm_minor_details($dd->form_id);
                //print_r($m_ag_id);die();
                $where_sem = ($session == 'Summer' ? " and  semester in (" . ($semester - 1) . ',' . $semester . ") " : " and  semester = '$semester' ");
                if ($session <> 'Summer') {
                    $map = $this->db->query("SELECT DISTINCT map_id
		                         FROM subject_mapping
                                         WHERE course_id = '" . $m_ag_id[0]->course_id . "'
		                         and  dept_id = '" . $m_ag_id[0]->dept_id . "'
		                         AND branch_id = '" . $m_ag_id[0]->branch_id . "'
		                     $where_sem
		                         AND session = '$session'
		                         AND session_year = '$session_year'
			                 AND aggr_id='" . $m_ag_id[0]->minor_agg_id . "'
		                         ");
                } else {

                    $summer_M_sub_str = "'" . implode("','", $sublist_M) . "'";
                    $map = $this->db->query("  SELECT DISTINCT a.map_id,b.sub_id FROM subject_mapping a
                                              inner join subject_mapping_des b on a.map_id=b.map_id

		                           WHERE a.course_id = '" . $m_ag_id[0]->course_id . "'
                                                  and  a.dept_id = '" . $m_ag_id[0]->dept_id . "'
		                         AND a.branch_id = '" . $m_ag_id[0]->branch_id . "'
		                        and  a.semester in (" . ($semester - 1) . ',' . $semester . ")
		                        AND a.session = '$session'
		                         AND a.session_year = '$session_year'  AND a.aggr_id='" . $m_ag_id[0]->minor_agg_id . "'
                                              and b.sub_id in(" . $summer_M_sub_str . ")
		                         ");
                }

                //  echo $this->db->last_query(); die();
                $map_result = $map->result();
                //print_r($map_result); die();
                $map_id = null;
                if ((count($map_result) > 1) && $session == 'Summer')
                    foreach ($map_result as $row) {
                        $map_id[] = $row->map_id . '_' . $row->sub_id;
                    } else
                    $map_id = $map_result[0]->map_id;
                if (!Exam_tabulation::functionallyEmpty_static($map_id)) {
                    //print_r($map_id); die();

                    foreach ($m_ag_id as $ag) {
                        //	echo $ag->minor_agg_id;
                        $sub = $this->student_attendance_model->getSubjectviaAggrid(($session == 'Summer' ? ($semester - 1) . ',' . $semester : $semester), $ag->minor_agg_id, $ag->dept_id, null, 1);

                        if ($session <> 'Summer') {

                            // put constraint to check that elective subjects are hons offered  only
                            $subject_filter = null;
                            $subject_filter = $this->db->query(
                                    "SELECT subject_id, name ,subjects.id,type,
			                          cs.aggr_id  FROM subjects
                                   join course_structure cs on cs.id=subjects.id
                                     WHERE subjects.id
			                        IN (SELECT DISTINCT sub_id as subject_id
		                            FROM reg_regular_elective_opted
		                            WHERE form_id =  $sem_form_id) and type<>'Non-Contact'");
                            //  echo $this->db->last_query();
                            $filltered_sub_array = null;

                            foreach ($subject_filter->result() as $qrow) {
                                if (strstr($qrow->aggr_id, "minor")) {
                                    $filltered_sub_array[] = $qrow->id;
                                }
                            }
                            // end
                        }
                        $temp = array();

                        foreach ($sub as $key => $tax) {
                            foreach ($tax as $value) {
                                if (array_search($semester, $value))
                                    $taxonomy = key($tax);
                            }
                        }
                        for ($i = $taxonomy; $i < count($sub['subjects']) + $taxonomy; $i++) {
                            if (empty($filltered_sub_array)) {
                                $checkValidation = ($session == 'Summer' ? in_array($sub['subjects'][$i]['id'], $sublist_M) : TRUE);
                            } else {
                                $checkValidation = ($session == 'Summer' ? in_array($sub['subjects'][$i]['id'], $sublist_M) : (in_array($sub['subjects'][$i]['id'], $filltered_sub_array)));
                            }

                            if ($checkValidation) {
                                $temp[$i]['subject_id'] = $sub['subjects'][$i]['subject_id'];
                                $temp[$i]['name'] = $sub['subjects'][$i]['name'];
                                $temp[$i]['id'] = $sub['subjects'][$i]['id'];
                                $temp[$i]['type'] = "minor";

                                if (count($map_id) > 1) {
                                    $l = 0;
                                    while ($l < count($map_id)) {
                                        $tpp = null;
                                        $tpp = explode('_', $map_id[$l]);
                                        //echo '<pre>'; print_r($tpp);echo '</pre>';
                                        //  echo $tpp[1].'=='.$rr->id; echo '<br/>';
                                        if ($tpp[1] == $sub['subjects'][$i]['id']) {
                                            $temp[$i]['map_id'] = $tpp[0];
                                        }
                                        //echo $map_id[$l];echo '<br/>';
                                        $l++;
                                    }
                                } else {
                                    $temp[$i]['map_id'] = $map_id;
                                }
                                $temp[$i]['course_id'] = $ag->course_id;
                                $temp[$i]['branch_id'] = $ag->branch_id;
                                $temp[$i]['sem_form_id'] = $dd->form_id;
                                $temp[$i]['course_aggr_id'] = $ag->minor_agg_id;
                            }
                        }
                    }
                    $sub_name = array_merge($sub_name, $temp);
                } else {
                    $sub_name['error_M'] = 'Faculty has not been yet mapped  for Minor paper(s) for which you have to attend the classes.Once it will be accomplished you will be able to view the attendance details';
                }
            }
        }
        if (!empty($sub_name)) {
            $customKey = 0;
            $filterSubjectEmptyColumn = array();
            foreach ($sub_name as $key => $value) {
                $is_empty_array = (array) $value;
                if (!empty($is_empty_array)) {
                    $filterSubjectEmptyColumn[$customKey++] = $value;
                }
            }
            $sub_name = $filterSubjectEmptyColumn;
        }
        return $sub_name;
    }

    //============================JRF Attendance=================================================

    function get_jrf_subjects($admn_no,$syear,$sess){

        $sql="select a.*,b.subject_id,b.name,c.semester,c.aggr_id from reg_exam_rc_subject a
inner join subjects b on b.id=a.sub_id
inner join course_structure c on c.id=a.sub_id
where a.form_id=(select a.form_id from reg_exam_rc_form a where a.admn_no=?
and a.session_year=? and a.`session`=?
and a.hod_status='1' and a.acad_status='1')";

        //echo $sql;die();
        $query = $this->db->query($sql,array($admn_no,$syear,$sess));

       //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() >= 0) {
            return $query->result();
        } else {
            return false;
        }
    }
	function get_subject_mapping_all($sub_id,$rstatus,$cid,$sec){
        if($cid=='comm'){
          $sql="select * from cbcs_class_engaged a where a.subject_offered_id=? and a.section=? order by id desc";
         $query = $this->db->query($sql,array($rstatus.$sub_id,$sec));

        }else{
          $sql="select * from cbcs_class_engaged a where a.subject_offered_id=? order by id desc";
         $query = $this->db->query($sql,array($rstatus.$sub_id));

        }
        if ($this->db->affected_rows() >= 0) {
            return $query->result();
        } else {
            return false;
        }

    }
    function get_subject_mapping($sub_id,$rstatus,$cid,$sec){


      if($cid=='comm'){
        $sql="select * from cbcs_class_engaged a where a.subject_offered_id=? and a.section=? order by id desc limit 1";
       $query = $this->db->query($sql,array($rstatus.$sub_id,$sec));
      }else{
        $sql="select * from cbcs_class_engaged a where a.subject_offered_id=? order by id desc limit 1";
       $query = $this->db->query($sql,array($rstatus.$sub_id));

      }
	  if ($this->db->affected_rows() >= 0) {
            return $query->row();
        } else {
            return false;
        }
	}
 


public function get_attendance_data($sub_offered_id,$cid,$sec){

      if($cid=='comm'){
        $sql="select a.*,concat_ws(' ',b.salutation,b.first_name,b.middle_name,b.last_name) as name from cbcs_class_engaged a
  inner join user_details b on a.engaged_by=b.id where a.subject_offered_id=? and a.section=?";
        $query = $this->db->query($sql,array($sub_offered_id,$sec));


      }else{
        $sql="select a.*,concat_ws(' ',b.salutation,b.first_name,b.middle_name,b.last_name) as name from cbcs_class_engaged a
  inner join user_details b on a.engaged_by=b.id where a.subject_offered_id=?";
        $query = $this->db->query($sql,array($sub_offered_id));


      }

   //echo $this->db->last_query();
      if ($this->db->affected_rows() > 0) {
          return $query->result();
      } else {
          return false;
      }
    }
	
    public function get_attendance_AP($map_id,$stu_id){

      $cntrow = $this->db->query("select * from cbcs_absent_table where class_engaged_id='$map_id' and admn_no='$stu_id' group by class_engaged_id");
            //  $cntrows= $cntrow->num_rows();
            //  echo $this->db->last_query();
if ($this->db->affected_rows() > 0) {
    return $cntrow->result();
} else {
    return false;
}
    }

    function print_details($sub_id){
      $sql="(select a.*,concat_ws(' ',b.salutation,b.first_name,b.middle_name,b.last_name) as stu_name from cbcs_stu_course a
      inner join user_details b on a.admn_no=b.id where a.admn_no=? and a.subject_code=? )
      union all
      (select a.*,concat_ws(' ',b.salutation,b.first_name,b.middle_name,b.last_name) as stu_name from old_stu_course a
      inner join user_details b on a.admn_no=b.id where a.admn_no=? and a.subject_code=?) ";

      //echo $sql;die();
      $query = $this->db->query($sql,array($this->session->userdata('id'),$sub_id,$this->session->userdata('id'),$sub_id));

    // echo $this->db->last_query(); die();
      if ($this->db->affected_rows() > 0) {
          return $query->result();
      } else {
          return false;
      }
    }

    function get_total_ab_class_details($mapid,$stu_id,$rstatus){
        $sql="select * from cbcs_absent_table where class_engaged_id in(select id from cbcs_class_engaged a where subject_offered_id=?) and admn_no=?";

        //echo $sql;die();
        $query = $this->db->query($sql,array($rstatus.$mapid,$stu_id));

    //   echo $this->db->last_query(); die();
        if ($this->db->affected_rows() >= 0) {
            return $query->num_rows();
        } else {
            return false;
        }
    }
    function get_absent_jrf_count($map_id,$admn_no,$sub_id){
        $sql="select count(*)as aclass from absent_table where map_id=? and admn_no=? and sub_id=?";

        //echo $sql;die();
        $query = $this->db->query($sql,array($map_id,$admn_no,$sub_id));

       //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() > 0) {
            return $query->row();
        } else {
            return false;
        }


    }

    //=========================Institute percentage=============================
        function check_institute_percentage_table($syear,$sess){
            $sql="select a.* from defaulter_new_institute_tbl a where a.session_year=? and a.`session`=?";

        //echo $sql;die();
        $query = $this->db->query($sql,array($syear,$sess));

       //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() > 0) {
            return $query->row()->percentage;
        } else {
            return false;
        }


        }

        function check_allpaper_percentage_table($temp_sy,$temp_sess,$temp_admn_no){
            $sql="select a.* from defaulter_new_allpaper_student a
where a.session_year=? and a.`session`=? and a.admn_no=?";

        //echo $sql;die();
        $query = $this->db->query($sql,array($temp_sy,$temp_sess,$temp_admn_no));

       //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() > 0) {
            return $query->row()->per;
        } else {
            return false;
        }


        }
        function get_cbsem($id){
            $sql="select b.semester,b.course_id,b.branch_id,b.course_aggr_id,SUBSTRING_INDEX(b.course_aggr_id,'_',1)as ctype from stu_academic a
inner join reg_regular_form b on b.admn_no=a.admn_no where a.admn_no=? order by b.semester desc limit 1";

        //echo $sql;die();
        $query = $this->db->query($sql,array($id));

       //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() > 0) {
            return $query->row();
        } else {
            return false;
        }


        }

        function check_individual_percentage_table($sy,$sess,$admno,$c,$b,$sem,$subid=''){
                $sql="select a.* from defaulter_new_individual_student a
where a.session_year=? and a.`session`=? and a.admn_no=? and a.course=? and a.branch=? and a.sem=? and a.subid=?;";

                //echo $sql;die();
                $query = $this->db->query($sql,array($sy,$sess,$admno,$c,$b,$sem,$subid));

               //echo $this->db->last_query(); die();
                if ($this->db->affected_rows() > 0) {
                    return $query->row()->per;
                } else {
                    return false;
                }

        }
             function check_individual_percentage_table_paper($sy,$sess,$admno,$c,$b,$sem,$subid,$mapid){
                $sql="select a.* from defaulter_new_individual_student a
where a.session_year=? and a.`session`=? and a.admn_no=? and a.course=? and a.branch=? and a.sem=? and a.subid=? and a.map_id=?;";

                //echo $sql;die();
                $query = $this->db->query($sql,array($sy,$sess,$admno,$c,$b,$sem,$subid,$mapid));

               //echo $this->db->last_query(); die();
                if ($this->db->affected_rows() > 0) {
                    return $query->row()->per;
                } else {
                    return false;
                }

        }
        function check_coursewise_percentage_table($sy,$sess,$c,$b,$sem){
            $sql="select a.* from defaulter_new_coursewise a where a.session_year=? and a.`session`=?
and a.course=? and a.branch=? and a.sem=?";

                //echo $sql;die();
                $query = $this->db->query($sql,array($sy,$sess,$c,$b,$sem));

               //echo $this->db->last_query(); die();
                if ($this->db->affected_rows() > 0) {
                    return $query->row()->per;
                } else {
                    return false;
                }

        }

        function check_hostel_percentage_table($sy,$sess,$hname){

            $sql="select a.* from defaulter_new_hostal_student a
where a.session_year=? and a.`session`=? and a.hostelname=? and a.course_id='na' and a.branch_id='na' and a.semester='na'";

                //echo $sql;die();
                $query = $this->db->query($sql,array($sy,$sess,$hname));

               //echo $this->db->last_query(); die();
                if ($this->db->affected_rows() > 0) {
                    return $query->row()->percentage;
                } else {
                    return false;
                }


        }

        function check_hostel_coursewise_percentage_table($sy,$sess,$hname,$c,$b,$sem){

            $sql="select a.* from defaulter_new_hostal_student a
where a.session_year=? and a.`session`=? and a.hostelname=? and a.course_id=? and a.branch_id=? and a.semester=?";

                //echo $sql;die();
                $query = $this->db->query($sql,array($sy,$sess,$hname,$c,$b,$sem));

               //echo $this->db->last_query(); die();
                if ($this->db->affected_rows() > 0) {
                    return $query->row()->percentage;
                } else {
                    return false;
                }


        }
        function check_hostel_coursewise_percentage_table_without_semester($sy,$sess,$hname,$c,$b){

            $sql="select a.* from defaulter_new_hostal_student a
where a.session_year=? and a.`session`=? and a.hostelname=? and a.course_id=? and a.branch_id=?";

                //echo $sql;die();
                $query = $this->db->query($sql,array($sy,$sess,$hname,$c,$b));

               //echo $this->db->last_query(); die();
                if ($this->db->affected_rows() > 0) {
                    return $query->row()->percentage;
                } else {
                    return false;
                }


        }
        function check_hostel_coursewise_percentage_table_without_semester_branch($sy,$sess,$hname,$c){

            $sql="select a.* from defaulter_new_hostal_student a
where a.session_year=? and a.`session`=? and a.hostelname=? and a.course_id=?";

                //echo $sql;die();
                $query = $this->db->query($sql,array($sy,$sess,$hname,$c));

               //echo $this->db->last_query(); die();
                if ($this->db->affected_rows() > 0) {
                    return $query->row()->percentage;
                } else {
                    return false;
                }


        }
        function get_hostelname($id){

            $sql="select a.* from defaulter_new_hostel_all a where a.admn_no=?";

                //echo $sql;die();
                $query = $this->db->query($sql,array($id));

               //echo $this->db->last_query(); die();
                if ($this->db->affected_rows() > 0) {
                    return $query->row()->hostel_name;
                } else {
                    return false;
                }


        }

       function count_class_datewiase($subid,$mapid,$stu_id,$date){
        $sql="select count(class_no)as max1 from absent_table where sub_id =? and map_id=? and admn_no=? and date=?";

        //echo $sql;die();
        $query = $this->db->query($sql,array($subid,$mapid,$stu_id,$date));

       //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() > 0) {
            return $query->row();
        } else {
            return false;
        }

       }
public function get_total_class_new($subject_id, $map_id) {

 $r = $this->db->query("select h.* ,g.user_id,concat_ws(' ',ud.salutation,ud.first_name,ud.middle_name,ud.last_name) as name  from
(select x.date,x.class_no,y.date as y_date,y.class_no as y_class ,  if(y.date is null ,'P','A') as abp_status,x.map_id, x.sub_id, x.session_id
 ,y.admn_no from
(select * from class_engaged where map_id=? and sub_id=?)x
left join
(select * from absent_table where map_id=? and sub_id=? and admn_no=?)y
on x.map_id=y.map_id and  x.sub_id=y.sub_id and x.session_id=y.session_id   and  x.date=y.date  and x.class_no=y.class_no)h

left  join
  absent_table_ta g on
    g.map_id=h.map_id and  g.sub_id=h.sub_id and g.session_id=h.session_id   and  g.date=h.date  and g.class_no=h.class_no and g.admn_no=h.admn_no
left  join user_details ud  on ud.id=g.user_id order by STR_TO_DATE(h.date,'%d-%m-%Y')", array($map_id,$subject_id,$map_id,$subject_id,$this->session->userdata('id')));
       // echo $this->db->last_query();
         return ($r->num_rows() > 0 ? $r->result():false) ;

    }

	function get_cbcs_subjects($form_id,$stu_id,$sessionYear,$session,$section,$course_aggr_id,$course_id,$branch_id){


    if(strpos($course_aggr_id, 'comm') !== false || $section!=""){
      $sectionjoin="inner join stu_section_data s on a.admn_no=s.admn_no";
      $condition="and b.sub_group='$section' and c.section=s.section";
      $branch_id="comm";
      $course_id="comm";
    }else{
    //	echo "not commm";
    }

    $sql="(select a.*,concat('c',CAST(b.id AS CHAR)) as offered_id,c.emp_no,concat(u.salutation,' ',u.first_name,' ',u.middle_name,' ',u.last_name) as name,b.semester,'c' as rstatus from cbcs_stu_course a INNER JOIN  cbcs_subject_offered b on a.subject_code=b.sub_code
  inner join cbcs_subject_offered_desc c on b.id=c.sub_offered_id
  inner join user_details u on c.emp_no=u.id
  $sectionjoin
  where a.admn_no='$stu_id' and a.form_id='$form_id' and a.session_year='$sessionYear' and a.session='$session' $condition  and b.branch_id='$branch_id' and b.course_id='$course_id'
  order by SUBSTR(a.sub_category,3)+0 asc )

  union all
  (
  SELECT a.*,concat('o',CAST(b.id AS CHAR)) AS offered_id,c.emp_no, CONCAT(u.salutation,' ',u.first_name,' ',u.middle_name,' ',u.last_name) AS name,b.semester,'o' as rstatus
  FROM old_stu_course a
  INNER JOIN old_subject_offered b ON a.subject_code=b.sub_code
  INNER JOIN old_subject_offered_desc c ON b.id=c.sub_offered_id
  $sectionjoin
  INNER JOIN user_details u ON c.emp_no=u.id
  WHERE a.admn_no='$stu_id' AND a.form_id='$form_id' AND a.session_year='$sessionYear' AND a.session='$session' $condition  AND b.branch_id='$branch_id' AND b.course_id='$course_id'
  ORDER BY SUBSTR(a.sub_category,3)+0 ASC)

  ";
    $query = $this->db->query($sql);
  //echo" common ".  $this->db->last_query(); exit;

      if ($this->db->affected_rows() > 0) {
           return $query->result();
       } else {
           return false;
       }
    }

    /*    $sql="SELECT a.*,SUBSTR(a.sub_category,3)AS sub_seq,b.contact_hours from cbcs_stu_course a
INNER JOIN cbcs_subject_offered b ON b.sub_code=a.subject_code
WHERE a.admn_no =? AND a.form_id=? AND b.contact_hours<>'0' GROUP BY a.subject_code
ORDER BY sub_seq+0";

        //echo $sql;die();
        $query = $this->db->query($sql,array($admn_no,$form_id));

       //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() >= 0) {
            return $query->result();
        } else {
            return false;
        }
    }
*/

public function student_basic_details($id,$syear) {
	
	$sql="SELECT a.id,
CONCAT_WS(' ',a.first_name,a.middle_name,a.last_name) AS stu_name,
c.name AS dname,
d.name AS cname,
e.name AS bname,
f.section
 FROM user_details a 
INNER JOIN stu_academic b ON b.admn_no=a.id
INNER JOIN cbcs_departments c ON c.id=a.dept_id
left JOIN cbcs_courses d ON d.id=b.course_id
left JOIN cbcs_branches e ON e.id=b.branch_id
LEFT JOIN stu_section_data f ON f.admn_no=a.id AND f.session_year=?

WHERE a.id=? ";

       
        $query = $this->db->query($sql,array($syear,$id));

       //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() >= 0) {
            return $query->result();
        } else {
            return false;
        }

 }
 public function get_cbcs_subjects_new($fid,$admn_no) {
	
	$sql="(SELECT a.*,CONCAT('c',a.sub_offered_id) AS sub_offered_id1 FROM cbcs_stu_course a WHERE form_id=? AND admn_no=?)
UNION
(SELECT a.*,CONCAT('o',a.sub_offered_id) AS sub_offered_id1 FROM old_stu_course a WHERE form_id=? AND admn_no=?)";

       
        $query = $this->db->query($sql,array($fid,$admn_no,$fid,$admn_no));

    //   echo $this->db->last_query(); die();
        if ($this->db->affected_rows() >= 0) {
            return $query->result();
        } else {
            return false;
        }

 }
 
 function get_defaulter_status($session_year,$session,$sub_offered_id,$admn_no,$param=null){
	 if($param)
		$sql="SELECT a.def_status FROM cbcs_absent_table_defaulter a WHERE a.session_year=? AND a.session=? AND a.sub_code=? AND a.admn_no=?"; 
	else	 
	 $sql="SELECT a.def_status FROM cbcs_absent_table_defaulter a WHERE a.session_year=? AND a.session=? AND a.sub_offered_id=? AND a.admn_no=?";

       
        $query = $this->db->query($sql,array($session_year,$session,$sub_offered_id,$admn_no));

    //   echo $this->db->last_query(); die();
        if ($this->db->affected_rows() >= 0) {
            return $query->row();
        } else {
            return false;
        }
	 
	 
 }
 


}

?>
