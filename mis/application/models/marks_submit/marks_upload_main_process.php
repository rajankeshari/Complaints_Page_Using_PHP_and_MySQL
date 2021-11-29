<?php

class Marks_upload_main_process extends CI_Model {

    private $marksMaster = 'marks_master';
    private $marksDes = 'marks_subject_description';
    private $dipankarSir = 'marksentry';
    private $semcode = 'dip_m_semcode';
    private $subjects = 'subjects';
    private $courseSt = 'course_structure';
    private $stu_a = 'stu_academic';
    private $section = 'stu_section_data';
    //database Report//
    private $tabulation = 'tabulation';

    function insertMarksMaster($data) {
        if ($this->db->insert($this->marksMaster, $data)) {
            return true;
        }
        return false;
    }

    // @solving double  entry issue of masrks_master @dated-30-4-18
    function getMarksMasterInserted_or_not($data) {
        $q = $this->db->select('id')->where($data)->get($this->marksMaster);
        //  echo  $this->db->last_query();
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
    }

    // end
    function updateMarksMaster($data, $con) {
        if ($this->db->update($this->marksMaster, $data, $con)) {
            return true;
        }
        return false;
    }

    function getMarksMaster($con = '') {

        if (is_array($con)) {
            $q = $this->db->get_where($this->marksMaster, $con);
        } else {
            $q = $this->db->get($this->marksMaster);
        }
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
    }

    function insertMarksDesInsert($data) {
        if ($this->db->insert_batch($this->marksDes, $data)) {
            return true;
        }
        return false;
    }

    function updateMarksDes($data, $con) {
        if ($this->db->update($this->marksDes, $data, $con)) {
            return true;
        }
        return false;
    }

    /* function updateMarksDes2($data,$con,$id){
      if($this->db->update($this->marksDes,$data,$con,$id)){
      return true;
      }
      return false;
      } */

    function getMarksDes($con = '') {
        if (is_array($con)) {
            $q = $this->db->order_by('admn_no', 'ASC')->get_where($this->marksDes, $con);
        } else {
            $q = $this->db->order_by('admn_no', 'ASC')->get($this->marksDes);
        }
        if ($q->num_rows() > 0) {
            return $q->result();
        }
        return false;
    }

    function userDetailsById($id) {
        $q = $this->db->get_where('user_details', array('id' => $id));
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
    }

    function sumfields($id, $type, $markId, $maindata) {
        //   print_r($_POST);

        if ($type != 'U') {
            $total = $this->db->query("select a.admn_no,a.sub_type,(case when a.sessional is null then '0' else a.sessional end) as sessional,(case  when a.theory is null then '0' else a.theory end) as theory,(case  when a.practical is null then '0' else a.practical end) as practical,(sum((case  when a.sessional is null then '0' else a.sessional end))+sum((case  when a.theory is null then '0' else a.theory end))+sum((case when a.practical is null then '0' else a.practical end))) as total from marks_subject_description as a where id=?", array($id))->row();
            //echo $total->total."tt";
            //echo $this->db->last_query();
            $Max = $this->getMaxNumber($markId);
            if ($Max < $maindata['maxnum']) {
                $Max = (int) $maindata['maxnum'];
            }
            if ($total->total > $Max) {
                $Max = $total->total;
            }
            $grade = $this->getGrade($Max, $total);
            //  echo $this->db->last_query();
            $this->updateMarksDes(array('total' => $total->total, 'grade' => $grade), array('id' => $id));
            $data['total'] = $total->total;
            $data['Max'] = $Max;
            if ($total->total > $Max) {

            } else {
                ($this->input->post('course') != 'comm') ? $data['grade'] = $grade : $data['grade'] = '';
            }
            //dipankarSir Processing Start//
            $maindata['admn_no'] = $total->admn_no;
            $maindata['total'] = $total->total;
            $maindata['type'] = $type;

            //  $this->DipankarSirProcess($this->input->post('course'), $this->input->post('branch'), $this->input->post('semester'), $this->input->post('sub_id'), $maindata, $id);
            //End DipankarSir Process//
            return $data;
        } else {
            $total = $this->db->get_where($this->marksDes, array('id' => $id))->row();
            $Max = $this->getMaxNumber($markId);
            $grade = $this->getGrade($Max, $total);
            $this->updateMarksDes(array('total' => $total->total, 'grade' => $grade), array('id' => $id));
            $data['total'] = $total->total;
            $data['Max'] = $Max;
            ($this->input->post('course') != 'comm') ? $data['grade'] = $grade : $data['grade'] = '';
            //dipankarSir Processing Start//
            $maindata['admn_no'] = $total->admn_no;
            $maindata['total'] = $total->total;
            $maindata['type'] = $type;

            //   $this->DipankarSirProcess($this->input->post('course'), $this->input->post('branch'), $this->input->post('semester'), $this->input->post('sub_id'), $maindata, $id);
            //End DipankarSir Process//
            return $data;
        }
    }

    function sumfields2($id, $type, $markId, $maindata) {
        //   print_r($_POST);

        if ($type != 'U') {
            $total = $this->db->query("select a.admn_no,a.sub_type,(case when a.sessional is null then '0' else a.sessional end) as sessional,(case  when a.theory is null then '0' else a.theory end) as theory,(case  when a.practical is null then '0' else a.practical end) as practical,(sum((case  when a.sessional is null then '0' else a.sessional end))+sum((case  when a.theory is null then '0' else a.theory end))+sum((case when a.practical is null then '0' else a.practical end))) as total from marks_subject_description as a where id=?", array($id))->row();
            //echo $total->total."tt";
            //echo $this->db->last_query();
            $Max = $this->getMaxNumber($markId);
            if ($Max < $maindata['maxnum']) {
                $Max = (int) $maindata['maxnum'];
            }
            if ($total->total > $Max) {
                $Max = $total->total;
            }
            $grade = $this->getGrade($Max, $total);
            //  echo $this->db->last_query();
            $this->updateMarksDes(array('total' => $total->total), array('id' => $id));
            $data['total'] = $total->total;
            $data['Max'] = $Max;
            if ($total->total > $Max) {

            } else {
                ($this->input->post('course') != 'comm') ? $data['grade'] = $grade : $data['grade'] = '';
            }
            //dipankarSir Processing Start//
            $maindata['admn_no'] = $total->admn_no;
            $maindata['total'] = $total->total;
            $maindata['type'] = $type;

            //  $this->DipankarSirProcess($this->input->post('course'), $this->input->post('branch'), $this->input->post('semester'), $this->input->post('sub_id'), $maindata, $id);
            //End DipankarSir Process//
            return $data;
        } else {
            $total = $this->db->get_where($this->marksDes, array('id' => $id))->row();
            $Max = $this->getMaxNumber($markId);
            $grade = $this->getGrade($Max, $total);
            $this->updateMarksDes(array('total' => $total->total), array('id' => $id));
            $data['total'] = $total->total;
            $data['Max'] = $Max;
            ($this->input->post('course') != 'comm') ? $data['grade'] = $grade : $data['grade'] = '';
            //dipankarSir Processing Start//
            $maindata['admn_no'] = $total->admn_no;
            $maindata['total'] = $total->total;
            $maindata['type'] = $type;

            //   $this->DipankarSirProcess($this->input->post('course'), $this->input->post('branch'), $this->input->post('semester'), $this->input->post('sub_id'), $maindata, $id);
            //End DipankarSir Process//
            return $data;
        }
    }

    /* function maxMarksSummer($subject_id,$sy)
      {
      $this->db->select('highest_marks');
      $this->db->select('session');
      $this->db->from('marks_master');
      //$this->db->where('subject_id' , $subject_id);
      $this->db->where(array('subject_id' => $subject_id,'session_year'=>$sy));
      $query = $this->db->get();
      $result=$query->result_array();
      return $result;
      } */

    function maxMarksSummer($subject_id, $marksId, $sy) {

        $sql = "select highest_marks as Max from marks_master where session=(
	 select   case  when MOD( d.semester, 2 )=0 then 'Winter' else 'Monsoon' end as sess  from  course_structure d  where  d.id=
	 ( select  subject_id  from  marks_master where id=?))
	  and session_year=?   and  subject_id= ( select  subject_id  from  marks_master where id=?) and type=? and  status=? ";

        $q = $this->db->query($sql, array($marksId, $sy, $marksId, 'R', 'Y'));
        //  echo $this->db->last_query(); die();
        if ($q->num_rows() > 0)
            return $q->row()->Max;
        else
            return '0';
    }

    function getMaxNumber($marksId) {
        // echo $marksId;
        //$q = $this->db->select_max('total', 'Max')->get_where($this->marksDes, array('marks_master_id' => $marksId))->row();
        $q = $this->db->select_max('highest_marks', 'Max')->get_where($this->marksMaster, array('id' => $marksId))->row();
        return $q->Max;
    }

    function getcommMaxNumber($sub, $session_year, $session) {
        $q = "select Z.*,(case when max(d.total) is null then '0' else max(d.total) end) as total  from (select X.map_id,c.id from (select a.map_id,a.sub_id from subject_mapping_des as a join subject_mapping as b on a.map_id=b.map_id
where  b.session_year=?  and b.`session`=? and  a.sub_id=?
and a.coordinator='1' group by b.section) X
inner join marks_master as c on c.sub_map_id=X.map_id and  c.subject_id=X.sub_id and c.session_year=?  and c.`session`=? /*and  c.`status`='Y'*/) Z
inner join marks_subject_description as d on d.marks_master_id=Z.id";
        $d = $this->db->query($q, array($session_year, $session, $sub, $session_year, $session));

        //echo $this->db->last_query(); die();
        if ($d->num_rows() > 0) {
            return $d->row()->total;
        }
        return '0';
    }

    function getGrade($max, $res, $session = '2013_2014') {
        //  print_r($res);
        $get = trim($res->total);
        $admnNo = $res->admn_no;
        $r = $this->getStuacademic(array('admn_no' => $admnNo));
        //check Grading Type//
        if ($r->auth_id == 'jrf') {
            //relative

            if ($res->sub_type == 'T') {
                if ($res->theory < 21) {
                    $grade = 'F';
                } else if ($res->total < 41) {
                    $grade = 'F';
                } else {
                    if ($get > $max) {
                        $get = $max;
                    }
                    $q = $this->db->query("select * from relative_grading_table as a where a.highest_marks=? and ? BETWEEN `a`.`min` and `a`.`max` and a.effected_form=?", array($max, $get, $session))->row();
                    $grade = $q->grade;
                }
            } else {
                if ($get > $max) {
                    $get = $max;
                }
                $q = $this->db->query("select * from relative_grading_table as a where a.highest_marks=? and ? BETWEEN `a`.`min` and `a`.`max` and a.effected_form=?", array($max, $get, $session))->row();
                $grade = $q->grade;
            }

            if ($grade == 'D')
                $grade = 'F';
            $this->setGradingType('R', $admnNo);

            //end of relative
            //return $grade;
        } else {

            if ($r->grading_type == 'N' || ($r->auth_id == 'jrf')) {
                if ((substr($admnNo, 0, 4) <= '2012') || ($r->auth_id == 'jrf')) {
                    if ($r->auth_id != 'prep' && $r->auth_id <> 'jrf' && ($r->semester == '7' || $r->semester == '9') || ($r->auth_id == 'jrf')) {
                        // echo $res->sub_type;
                        if ($res->sub_type == "T") {
                            //echo $get;
                            if (((int) $res->sessional < 14 && $r->auth_id <> 'jrf')) {
                                $grade = 'F';
                            } else if (((int) $res->theory) < 21) {
                                $grade = 'F';
                            } else if (((int) $res->total) < ($r->auth_id == 'jrf' ? 41 : 35)) {
                                $grade = 'F';
                            } else if (((int) $get) > 90 && ((int) $get) <= 100) {
                                $grade = 'A+';
                            } else if (((int) $get) > 80 && ((int) $get) <= 90) {
                                $grade = 'A';
                            } else if (((int) $get) > 70 && ((int) $get) <= 80) {
                                $grade = 'B+';
                            } else if (((int) $get) > 60 && ((int) $get) <= 70) {
                                $grade = 'B';
                            } else if (((int) $get) > 50 && ((int) $get) <= 60) {
                                $grade = 'C+';
                            } else if (((int) $get) > 40 && ((int) $get) <= 50) {
                                $grade = 'C';
                            } else if (((int) $get) > 34 && ((int) $get) <= 40) {
                                $grade = ($r->auth_id == 'jrf' ? 'F' : 'D');
                            } else {
                                $grade = 'F';
                            }
                        } else if ($res->sub_type == "jrf") {
                            //echo $get;
                            if (((int) $res->sessional < 14 && $r->auth_id <> 'jrf')) {
                                $grade = 'F';
                            } else if (((int) $res->theory) < 21) {
                                $grade = 'F';
                            } else if (((int) $res->total) < ($r->auth_id == 'jrf' ? 41 : 35)) {
                                $grade = 'F';
                            } else if (((int) $get) > 90 && ((int) $get) <= 100) {
                                $grade = 'A+';
                            } else if (((int) $get) > 80 && ((int) $get) <= 90) {
                                $grade = 'A';
                            } else if (((int) $get) > 70 && ((int) $get) <= 80) {
                                $grade = 'B+';
                            } else if (((int) $get) > 60 && ((int) $get) <= 70) {
                                $grade = 'B';
                            } else if (((int) $get) > 50 && ((int) $get) <= 60) {
                                $grade = 'C+';
                            } else if (((int) $get) > 40 && ((int) $get) <= 50) {
                                $grade = 'C';
                            } else if (((int) $get) > 34 && ((int) $get) <= 40) {
                                $grade = ($r->auth_id == 'jrf' ? 'F' : 'D');
                            } else {
                                $grade = 'F';
                            }
                        } else {
                            if (((int) $res->total) < ($r->auth_id == 'jrf' ? 41 : 35)) {
                                $grade = 'F';
                            } else if (((int) $get) > 90 && ((int) $get) <= 100) {
                                $grade = 'A+';
                            } else if (((int) $get) > 80 && ((int) $get) <= 90) {
                                $grade = 'A';
                            } else if (((int) $get) > 70 && ((int) $get) <= 80) {
                                $grade = 'B+';
                            } else if (((int) $get) > 60 && ((int) $get) <= 70) {
                                $grade = 'B';
                            } else if (((int) $get) > 50 && ((int) $get) <= 60) {
                                $grade = 'C+';
                            } else if (((int) $get) > 40 && ((int) $get) <= 50) {
                                $grade = 'C';
                            } else if (((int) $get) > 34 && ((int) $get) <= 40) {
                                $grade = ($r->auth_id == 'jrf' ? 'F' : 'D');
                            } else {
                                $grade = 'F';
                            }
                        }
                        $this->setGradingType('A', $admnNo);
                    } else {
                        //relative

                        if ($res->sub_type == 'T') {
                            if ($res->theory < 21) {
                                $grade = 'F';
                            } else if ($res->total < 35) {
                                $grade = 'F';
                            } else {
                                if ($get > $max) {
                                    $get = $max;
                                }
                                $q = $this->db->query("select * from relative_grading_table as a where a.highest_marks=? and ? BETWEEN `a`.`min` and `a`.`max` and a.effected_form=?", array($max, $get, $session))->row();
                                $grade = $q->grade;
                            }
                        } else {
                            if ($get > $max) {
                                $get = $max;
                            }
                            $q = $this->db->query("select * from relative_grading_table as a where a.highest_marks=? and ? BETWEEN `a`.`min` and `a`.`max` and a.effected_form=?", array($max, $get, $session))->row();
                            $grade = $q->grade;
                        }
                        $this->setGradingType('R', $admnNo);

                        //end of relative
                    }
                } else {
                    // echo $max."-".$get;
                    if ($res->sub_type == 'T') {
                        if ($res->theory < 21) {
                            $grade = 'F';
                        } else if ($res->total < 35) {
                            $grade = 'F';
                        } else {
                            if ($get > $max) {
                                $get = $max;
                            }
                            $q = $this->db->query("select * from relative_grading_table as a where a.highest_marks=? and ? BETWEEN `a`.`min` and `a`.`max` and a.effected_form=?", array($max, $get, $session))->row();
                            $grade = $q->grade;
                        }
                    } else {
                        if ($get > $max) {
                            $get = $max;
                        }
                        $q = $this->db->query("select * from relative_grading_table as a where a.highest_marks=? and ? BETWEEN `a`.`min` and `a`.`max` and a.effected_form=?", array($max, $get, $session))->row();
                        $grade = $q->grade;
                    }
                    $this->setGradingType('R', $admnNo);
                }
            }// realtive
            else if ($r->grading_type == 'R' && ($r->auth_id <> 'jrf')) {

                if ($res->sub_type == 'T') {

                    if ($res->theory < 21) {
                        $grade = 'F';
                    } else if ($res->total < 35) {
                        $grade = 'F';
                    } else {
                        if ($get > $max) {
                            $get = $max;
                        }
                        $q = $this->db->query("select * from relative_grading_table as a where a.highest_marks=? and ? BETWEEN `a`.`min` and `a`.`max` and a.effected_form=?", array($max, $get, $session))->row();
                        $grade = $q->grade;
                    }
                } else {
                    if ($get > $max) {
                        $get = $max;
                    }
                    $q = $this->db->query("select * from relative_grading_table as a where a.highest_marks=? and ? BETWEEN `a`.`min` and `a`.`max` and a.effected_form=?", array($max, $get, $session))->row();
                    $grade = $q->grade;
                }
            } else {  // absoulte
                if ($res->sub_type == "T") {
                    if (((int) $res->sessional < 14 && $r->auth_id <> 'jrf')) {
                        $grade = 'F';
                    } else if (((int) $res->theory) < 21) {
                        $grade = 'F';
                    } else if (((int) $res->total) < ($r->auth_id == 'jrf' ? 41 : 35)) {
                        $grade = 'F';
                    } else if (((int) $get) > 90 && ((int) $get) <= 100) {
                        $grade = 'A+';
                    } else if (((int) $get) > 80 && ((int) $get) <= 90) {
                        $grade = 'A';
                    } else if (((int) $get) > 70 && ((int) $get) <= 80) {
                        $grade = 'B+';
                    } else if (((int) $get) > 60 && ((int) $get) <= 70) {
                        $grade = 'B';
                    } else if (((int) $get) > 50 && ((int) $get) <= 60) {
                        $grade = 'C+';
                    } else if (((int) $get) > 40 && ((int) $get) <= 50) {
                        $grade = 'C';
                    } else if (((int) $get) > 34 && ((int) $get) <= 40) {
                        $grade = ($r->auth_id == 'jrf' ? 'F' : 'D');
                    } else {
                        $grade = 'F';
                    }
                } else {
                    if (((int) $res->total) < ($r->auth_id == 'jrf' ? 41 : 35)) {
                        $grade = 'F';
                    } else if (((int) $get) > 90 && ((int) $get) <= 100) {
                        $grade = 'A+';
                    } else if (((int) $get) > 80 && ((int) $get) <= 90) {
                        $grade = 'A';
                    } else if (((int) $get) > 70 && ((int) $get) <= 80) {
                        $grade = 'B+';
                    } else if (((int) $get) > 60 && ((int) $get) <= 70) {
                        $grade = 'B';
                    } else if (((int) $get) > 50 && ((int) $get) <= 60) {
                        $grade = 'C+';
                    } else if (((int) $get) > 40 && ((int) $get) <= 50) {
                        $grade = 'C';
                    } else if (((int) $get) > 34 && ((int) $get) <= 40) {
                        $grade = ($r->auth_id == 'jrf' ? 'F' : 'D');
                    } else {
                        $grade = 'F';
                    }
                }
            }
        }        //echo $garde; die();
        return $grade;
    }

    /* function getGrade($max,$res,$session='2013_2014'){
      //  print_r($res);
      $get = $res->total;
      $admnNo = $res->admn_no;
      $r=$this->getStuacademic(array('admn_no' => $admnNo));
      //check Grading Type//
      if($r->grading_type == 'N'){
      if(substr($admnNo,0,4) <= '2012'){
      if($r->auth_id!='prep' && ($r->semester == '7' || $r->semester == '9')){
      // echo $res->sub_type;
      if($res->sub_type=="T"){
      if(((int)$res->sessional) < 14){
      $grade = 'F';
      }else if(((int)$res->theory) < 21){
      $grade = 'F';
      }else if(((int)$res->total) < 35){
      $grade = 'F';
      }else if(((int)$get) > 90 && ((int)$get) <= 100){
      $grade = 'A+';
      }else if(((int)$get) > 80 && ((int)$get) <= 90){
      $grade = 'A';
      }else if(((int)$get) > 70 && ((int)$get) <= 80){
      $grade = 'B+';
      }else if(((int)$get) > 60 && ((int)$get) <= 70){
      $grade = 'B';
      }else if(((int)$get) > 50 && ((int)$get) <= 60){
      $grade = 'C+';
      }else if(((int)$get) > 40 && ((int)$get) <= 50){
      $grade = 'C';
      }else if(((int)$get) > 34 && ((int)$get) <= 40){
      $grade = 'D';
      }else{
      $grade ='F';
      }
      }else{
      if(((int)$res->total) < 35){
      $grade = 'F';
      }else if(((int)$get) > 90 && ((int)$get) <= 100){
      $grade = 'A+';
      }else if(((int)$get) > 80 && ((int)$get) <= 90){
      $grade = 'A';
      }else if(((int)$get) > 70 && ((int)$get) <= 80){
      $grade = 'B+';
      }else if(((int)$get) > 60 && ((int)$get) <= 70){
      $grade = 'B';
      }else if(((int)$get) > 50 && ((int)$get) <= 60){
      $grade = 'C+';
      }else if(((int)$get) > 40 && ((int)$get) <= 50){
      $grade = 'C';
      }else if(((int)$get) > 34 && ((int)$get) <= 40){
      $grade = 'D';
      }else{
      $grade ='F';
      }
      }
      $this->setGradingType('A',$admnNo);

      }else{
      if($res->sub_type == 'T'){
      if ($res->theory < 21) {
      $grade = 'F';
      } else if ($res->total < 35) {
      $grade = 'F';
      } else {
      $q = $this->db->query("select * from relative_grading_table as a where a.highest_marks=? and ? BETWEEN `a`.`min` and `a`.`max` and a.effected_form=?", array($max, $get, $session))->row();
      $grade = $q->grade;
      }
      }else{
      $q = $this->db->query("select * from relative_grading_table as a where a.highest_marks=? and ? BETWEEN `a`.`min` and `a`.`max` and a.effected_form=?", array($max, $get, $session))->row();
      $grade = $q->grade;
      }
      $this->setGradingType('R',$admnNo);
      }
      }else{
      // echo $max."-".$get;
      if($res->sub_type == 'T'){
      if ($res->theory < 21) {
      $grade = 'F';
      } else if ($res->total < 35) {
      $grade = 'F';
      } else {
      $q = $this->db->query("select * from relative_grading_table as a where a.highest_marks=? and ? BETWEEN `a`.`min` and `a`.`max` and a.effected_form=?", array($max, $get, $session))->row();
      $grade = $q->grade;
      }
      }else{
      $q = $this->db->query("select * from relative_grading_table as a where a.highest_marks=? and ? BETWEEN `a`.`min` and `a`.`max` and a.effected_form=?", array($max, $get, $session))->row();
      $grade = $q->grade;
      }
      $this->setGradingType('R',$admnNo);
      }
      }
      else if($r->grading_type == 'R'){

      if($res->sub_type == 'T'){

      if($res->theory < 21){
      $grade = 'F';
      }else if($res->total < 35){
      $grade = 'F';
      }else{
      $q=$this->db->query("select * from relative_grading_table as a where a.highest_marks=? and ? BETWEEN `a`.`min` and `a`.`max` and a.effected_form=?",array($max,$get,$session))->row();
      $grade = $q->grade;
      }
      }else{
      $q=$this->db->query("select * from relative_grading_table as a where a.highest_marks=? and ? BETWEEN `a`.`min` and `a`.`max` and a.effected_form=?",array($max,$get,$session))->row();
      $grade = $q->grade;
      }

      }
      else{
      if($res->sub_type=="T"){
      if(((int)$res->sessional) < 14){
      $grade = 'F';
      }else if(((int)$res->theory) < 21){
      $grade = 'F';
      }else if(((int)$res->total) < 35){
      $grade = 'F';
      }else if(((int)$get) > 90 && ((int)$get) <= 100){
      $grade = 'A+';
      }else if(((int)$get) > 80 && ((int)$get) <= 90){
      $grade = 'A';
      }else if(((int)$get) > 70 && ((int)$get) <= 80){
      $grade = 'B+';
      }else if(((int)$get) > 60 && ((int)$get) <= 70){
      $grade = 'B';
      }else if(((int)$get) > 50 && ((int)$get) <= 60){
      $grade = 'C+';
      }else if(((int)$get) > 40 && ((int)$get) <= 50){
      $grade = 'C';
      }else if(((int)$get) > 34 && ((int)$get) <= 40){
      $grade = 'D';
      }else{
      $grade ='F';
      }
      }else{
      if(((int)$res->total) < 35){
      $grade = 'F';
      }else if(((int)$get) > 90 && ((int)$get) <= 100){
      $grade = 'A+';
      }else if(((int)$get) > 80 && ((int)$get) <= 90){
      $grade = 'A';
      }else if(((int)$get) > 70 && ((int)$get) <= 80){
      $grade = 'B+';
      }else if(((int)$get) > 60 && ((int)$get) <= 70){
      $grade = 'B';
      }else if(((int)$get) > 50 && ((int)$get) <= 60){
      $grade = 'C+';
      }else if(((int)$get) > 40 && ((int)$get) <= 50){
      $grade = 'C';
      }else if(((int)$get) > 34 && ((int)$get) <= 40){
      $grade = 'D';
      }else{
      $grade ='F';
      }
      }
      }


      return $grade;
      } */

    function getStuacademic($con = '') {
        if (is_array($con)) {
            $q = $this->db->get_where($this->stu_a, $con);
        } else {
            $q = $this->db->get($this->stu_a);
        }
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
    }

    private function insertDipkankarSirTable($data) {
        $CI = &get_instance();
        $this->db2 = $CI->load->database($this->tabulation, TRUE);
        if ($this->db2->insert($this->dipankarSir, $data))
            return true;
        return false;
    }

    private function getdipankarSir($id) {
        $q = $this->db->get_where($this->dipankarSir, array('marksid' => $id));
        if ($q->num_rows() > 0)
            return true;
        return false;
    }

    private function updateDipkankarSir($data, $con) {
        $CI = &get_instance();
        $this->db2 = $CI->load->database($this->tabulation, TRUE);
        if ($this->db2->update($this->dipankarSir, $data, $con)) {
            return true;
        }
        return false;
    }

    private function DipankarSirProcess($c, $b, $s, $subId, $maindata, $markId) {

        $semcode = $this->getSemCode($c, $b, $s);
        $subject = $this->getsubject($subId);

        $data['YSESSION'] = '1516';
        $data['sem_code'] = $semcode->semcode;
        if ($c == 'comm') {
            $data['dept'] = $this->getdept($this->getStudept($maindata['admn_no']))->dept;
        } else {
            $data['dept'] = $semcode->dept;
        }
        $data['adm_no'] = strtoupper($maindata['admn_no']);
        $data['stu_name'] = $this->gatName($maindata['admn_no']);
        $data['wsms'] = 'ms';
        $data['examtype'] = 'R';
        $data['subje_code'] = $subject->subject_id;
        $data['subje_orde'] = $this->getSeq($subId);
        $data['subje_name'] = $subject->name;
        $data['subje_type'] = $maindata['type'];
        if ($c == 'comm') {
            $data['section'] = $this->getSection($maindata['admn_no']);
        }
        $data['ltp'] = $subject->lecture . "-" . $subject->tutorial . "-" . $subject->practical;
        if (isset($maindata['sessional'])) {
            $data['sessional'] = $maindata['sessional'];
        } else if (isset($maindata['theory'])) {
            $data['theory'] = $maindata['theory'];
        } else if (isset($maindata['practical'])) {
            $data['practical'] = $maindata['practical'];
            $data['total'] = $maindata['practical'];
        } else if (isset($maindata['total'])) {
            $data['total'] = $maindata['total'];
        }
        $data['efectfrom'] = $this->getEffectivefrom($subId);
        $data['crhrs'] = $subject->credit_hours;
        //$data['crpts'];
        //  $data['g_e'];


        if ($this->getdipankarSir($markId)) {

            $this->updateDipkankarSir($data, array('marksid' => $markId));
        } else {
            $data['marksid'] = $markId;
            $this->insertDipkankarSirTable($data);
        }
    }

    private function getSemCode($course, $branch, $sem) {
        $q = $this->db->get_where($this->semcode, array('course' => $course, 'branch' => $branch, 'sem' => $sem));
        //echo $this->db->last_query();
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
    }

    private function getdept($dept) {
        $q = $this->db->get_where($this->semcode, array('deptmis' => $dept));
        //echo $this->db->last_query();
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
    }

    private function getsubject($id) {
        $q = $this->db->get_where($this->subjects, array('id' => $id));
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
    }

    private function getSeq($id) {
        $q = $this->db->get_where($this->courseSt, array('id' => $id));
        if ($q->num_rows() > 0) {
            if (is_float($this->get_numeric($q->row()->sequence))) {
                $r = explode('.', (string) $q->row()->sequence);
                return $r[0];
            } else {
                return $q->row()->sequence;
            }
        }
        return false;
    }

    private function getEffectivefrom($id) {
        $q = $this->db->get_where($this->courseSt, array('id' => $id));
        if ($q->num_rows() > 0) {
            $r = explode('_', $q->row()->aggr_id);
            if ($r[2] == '2009') {
                return '0910';
            } else if ($r[2] == '2010') {
                return '1011';
            } else if ($r[2] == '2011') {
                return '1112';
            } else if ($r[2] == '2012') {
                return '1213';
            } else if ($r[2] == '2013') {
                return '1314';
            } else if ($r[2] == '2014') {
                return '1415';
            } else if ($r[2] == '2015') {
                return '1516';
            } else if ($r[2] == '2016') {
                return '1617';
            } else if ($r[2] == '2017') {
                return '1718';
            }
        }
    }

    protected function get_numeric($val) {
        if (is_numeric($val)) {
            return $val + 0;
        }
        return 0;
    }

    private function gatName($id) {
        $q = $this->db->get_where('user_details', array('id' => $id));
        if ($q->num_rows() > 0) {
            return strtoupper($q->row()->first_name . " " . $q->row()->middle_name . " " . $q->row()->last_name);
        }
        return false;
    }

    function checkAllMarks($marksId, $t) {

        if ($t == 'T') {
            $q = $this->db->query("SELECT * FROM (`marks_subject_description`) WHERE `marks_master_id` = ? AND `sessional` is null AND `theory` is null ", array($marksId));
        } else if ($t == 'P') {
            $q = $this->db->query("SELECT * FROM (`marks_subject_description`) WHERE `marks_master_id` = ? AND `practical` is null", array($marksId));
        } else {
            $q = $this->db->query("SELECT * FROM (`marks_subject_description`) WHERE `marks_master_id` = ? AND `total` is null", array($marksId));
        }
        //echo $this->db->last_query();
        $q->num_rows();
        if ($q->num_rows() > 0) {
            $tt = false;
        } else {
            $tt = true;
        }
        return $tt;
    }

    function getAllgradeP($max, $mapId) {
        $q = $this->db->query("update marks_subject_description as a set grade=(select grade from relative_grading_table as b where b.highest_marks=? and a.total between b.min and b.max) where a.marks_master_id=?", array($max, $mapId));
        if ($q) {
            return true;
        }
    }

    function getAllgradeT($max, $mapId) {
        $q = $this->db->query("update marks_subject_description as a set grade=(select (case when a.theory < 21 then 'F' else grade end) as grade from relative_grading_table as b where b.highest_marks=? and a.total between b.min and b.max) where a.marks_master_id=?", array($max, $mapId));
        if ($q) {
            return true;
        }
    }

    function setGradingType($type, $id) {
        if ($this->db->update($this->stu_a, array('grading_type' => $type), array('admn_no' => $id)))
            return TRUE;
        return false;
    }

    function getStuName($id) {
        $q = $this->db->get_where('user_details', array('id' => $id))->row();
        return $q->first_name . " " . $q->middle_name . " " . $q->last_name;
    }

    function getSubjectId($id) {
        $q = $this->db->get_where('subjects', array('id' => $id))->row();
        return $q->subject_id;
    }

    function getSection($id) {
        $q = $this->db->get_where($this->section, array('admn_no' => $id));
        if ($q->num_rows() > 0)
            return $q->row()->section;
    }

    function getStudept($id) {
        $q = $this->db->get_where('user_details', array('id' => $id))->row();
        return $q->dept_id;
    }

    function getregnew($sy, $s, $c, $b, $se, $agr_id, $mid) {
        $q = "SELECT `admn_no` FROM (`reg_regular_form`) WHERE `session_year` =? AND `session` = ? AND `branch_id` = ? AND `course_id` = ? AND `semester` = ? AND `course_aggr_id` = ? AND `hod_status` = '1' AND `acad_status` = '1'
and admn_no not in( select admn_no from marks_subject_description as a where a.marks_master_id=?)";

        $d = $this->db->query($q, array($sy, $s, $b, $c, $se, $agr_id, $mid));
        // echo $this->db->last_query();
        if ($d->num_rows() > 0) {
            return $d->result();
        }
        return false;
    }

    function getsummernew($sy, $s, $c, $b, $se, $agr_id, $mid, $sub_id) {
        $q = "SELECT x.`admn_no` FROM `reg_summer_form` x join reg_summer_subject sub on x.form_id=sub.form_id and sub.sub_id=?

	  and  x.`session_year` =? AND x.`session` = ? AND x.`branch_id` = ? AND x.`course_id` = ?  AND x.`course_aggr_id` = ? AND x.`hod_status` = '1' AND x.`acad_status` = '1'
      and x.admn_no not in( select admn_no from marks_subject_description as a where a.marks_master_id=?)  group by x.`admn_no`";
        // if($this->session->userdata('id')=='1036') { echo $q;}
        $d = $this->db->query($q, array($sub_id, $sy, $s, $b, $c, $agr_id, $mid));
        if ($d->num_rows() > 0) {
            return $d->result();
        }
        return false;
    }

    function getCommonNew($sy, $s, $se, $sec, $agr_id, $mid) {
        $q = "SELECT `reg_regular_form`.`admn_no` FROM (`reg_regular_form`) JOIN `stu_section_data` ON `stu_section_data`.`admn_no`=`reg_regular_form`.`admn_no` WHERE `reg_regular_form`.`hod_status` = '1' AND `reg_regular_form`.`acad_status` = '1' AND `reg_regular_form`.`session_year` = ? AND `reg_regular_form`.`session` = ? AND `reg_regular_form`.`semester` = ? AND `stu_section_data`.`section` = ? AND `course_aggr_id` = ? and reg_regular_form.admn_no not in( select admn_no from marks_subject_description as a where a.marks_master_id=? ) ";

        $d = $this->db->query($q, array($sy, $s, $se, $sec, $agr_id, $mid));
        //echo $this->db->last_query();
        //die();
        if ($d->num_rows() > 0) {
            return $d->result();
        }
        return false;
    }

    function getCommon_summer_New($sy, $s, $se, $sec, $agr_id, $mid, $sub_id) {
        $q = "SELECT `reg_summer_form`.`admn_no` FROM (`reg_summer_form`) JOIN `stu_section_data` ON `stu_section_data`.`admn_no`=`reg_summer_form`.`admn_no`
	  join  reg_summer_subject sub on reg_summer_form.form_id=sub.form_id and sub.sub_id=? and
	   `reg_summer_form`.`hod_status` = '1'
	  AND `reg_summer_form`.`acad_status` = '1' AND `reg_summer_form`.`session_year` = ? AND `reg_summer_form`.`session` = ?  AND `stu_section_data`.`section` = ?
	  AND `course_aggr_id` = ? and reg_summer_form.admn_no not in( select admn_no from marks_subject_description as a where a.marks_master_id=?) group by  `reg_summer_form`.`admn_no`";

        $d = $this->db->query($q, array($sub_id, $sy, $s, $sec, $agr_id, $mid));
        if ($d->num_rows() > 0) {
            return $d->result();
        }
        return false;
    }

    /* function gethonourNew($sy,$dept,$agr_id,$mid){
      $q="SELECT `hm_form`.`admn_no` FROM (`hm_form`) WHERE `honours` = '1' AND `session_year` =? AND `honour_hod_status` = 'Y' AND `dept_id` =? AND `honours_agg_id` = ? and `hm_form`.`admn_no` not in(select admn_no from marks_subject_description as a where a.marks_master_id=?)";
      $d=$this->db->query($q,array($sy,$dept,$agr_id,$mid));
      if($d->num_rows() >0){
      return $d->result();
      }
      return false;
      } */

    private function isElective($id) {
        $qu = $this->db->get_where('subjects', array('id' => $id));
        $r = $qu->row();
        if ($r->elective == 0)
            return false;

        return true;
    }

    function gethonourNew($sy, $dept, $agr_id, $mid, $data) {
        // print_r($data); die();
        $branch = $data['branch'];
        $aggr_id = $data['aggr_id'];
        $session = $data['session'];
        $session_year = $data['session_year'];
        if ($this->isElective($data['sub_id'])) {
            $q = "SELECT hm_form.`admn_no` FROM (`hm_form`)
join reg_regular_form a on a.admn_no=hm_form.admn_no
join reg_regular_elective_opted b on a.form_id=b.form_id
 WHERE `honours_agg_id` =? AND `honour_hod_status` = 'Y' AND `honours` = '1' and a.session_year=? and a.`session`=? and a.semester=? and b.sub_id=? and `hm_form`.`admn_no` not in(select admn_no from marks_subject_description as a where a.marks_master_id=?)";
            $d = $this->db->query($q, array($aggr_id, $session_year, $session, $data['semester'], $data['sub_id'], $mid));
        } else {

            $q = "SELECT hm_form.`admn_no` FROM (`hm_form`) join reg_regular_form a on a.admn_no=hm_form.admn_no WHERE `honours_agg_id` =? AND `honour_hod_status` = 'Y' AND `honours` = '1' and a.session_year=? and a.`session`=? and a.semester=? and `hm_form`.`admn_no` not in(select admn_no from marks_subject_description as a where a.marks_master_id=?)";


            $d = $this->db->query($q, array($aggr_id, $session_year, $session, $data['semester'], $mid));
        }

        //echo $this->db->last_query(); die();
        if ($d->num_rows() > 0) {
            return $d->result();
        }
        return false;
    }

    function getminorNew($sy, $dept, $b, $agr_id, $mid, $sem, $sess) {
        /* $q="SELECT `hm_form`.`admn_no` FROM (`hm_form`) JOIN `hm_minor_details` ON `hm_minor_details`.`form_id`=`hm_form`.`form_id` WHERE `hm_form`.`minor` = '1' AND `hm_form`.`session_year` = ? AND `hm_form`.`minor_hod_status` = 'Y' AND `hm_minor_details`.`dept_id` = ? AND `hm_minor_details`.`branch_id` = ? AND `hm_minor_details`.`offered` = '1' AND `hm_minor_details`.`minor_agg_id` = ? and `hm_form`.`admn_no` not in (select admn_no from marks_subject_description as a where a.marks_master_id=?)"; */


        $q = "SELECT `hm_form`.`admn_no` FROM (`hm_form`) JOIN `hm_minor_details` ON `hm_minor_details`.`form_id`=`hm_form`.`form_id`
	  INNER JOIN reg_regular_form rgf ON rgf.admn_no=hm_form.admn_no AND rgf.session_year=? AND rgf.semester=? AND rgf.`session`=? AND rgf.hod_status<>'2' AND rgf.acad_status<>'2'
	  WHERE `hm_form`.`minor` = '1' AND `hm_form`.`minor_hod_status` = 'Y' AND `hm_minor_details`.`dept_id` = ? AND `hm_minor_details`.`branch_id` = ? AND `hm_minor_details`.`offered` = '1' AND `hm_minor_details`.`minor_agg_id` = ? and `hm_form`.`admn_no` not in (select admn_no from marks_subject_description as a where a.marks_master_id=?)";


        $d = $this->db->query($q, array($sy, $sem, $sess, $dept, $b, $agr_id, $mid));
        //$d=$this->db->query($q,array($sy,$dept,$b,$agr_id,$mid));
        //echo $this->db->last_query();
        if ($d->num_rows() > 0) {
            return $d->result();
        }
        return false;
    }

    // added by @ rituraj @dated:12-7-18
    function getminor_summer_New($sy, $dept, $b, $agr_id, $mid, $sem, $sess) {

        $q = "
select z.* from
(SELECT hm_form.admn_no
FROM hm_form
JOIN hm_minor_details ON hm_form.form_id=hm_minor_details.form_id WHERE hm_form.minor_hod_status =? AND hm_minor_details.dept_id=? AND hm_minor_details.branch_id=? AND hm_minor_details.offered=?
AND `hm_minor_details`.`minor_agg_id` = ? )z
join
(select x.admn_no from  reg_summer_form x join  reg_summer_subject y on x.form_id=y.form_id
   and  x.session_year=? AND x.`session`=?
   AND x.hod_status<>? AND x.acad_status<>?
	 join  course_structure cs on cs.id=y.sub_id and cs.aggr_id like 'minor%' AND (cs.semester=? )
	 group by x.admn_no
	) w
	 ON w.admn_no=z.admn_no
	  AND w.`admn_no` NOT IN (
SELECT admn_no
FROM marks_subject_description AS a
WHERE a.marks_master_id=?)  ORDER BY z.admn_no
";


        $d = $this->db->query($q, array('Y', $dept, $b, '1', $agr_id, $sy, $sess, '2', '2', $sem, $mid));
        //$d=$this->db->query($q,array($sy,$dept,$b,$agr_id,$mid));
        //echo $this->db->last_query();
        if ($d->num_rows() > 0) {
            return $d->result();
        }
        return false;
    }

    function isele($id) {
        $q = $this->db->get_where('subjects', array('id' => $id))->row();
        return $q->elective;
    }

    function getPrepNew($sem, $sess, $sy, $mid) {
        $q = "SELECT `admn_no` FROM (`reg_regular_form`) WHERE `semester` =? AND `hod_status` = '1' AND `acad_status` = '1' AND `session` =? AND `session_year` =? and `admn_no` not in (select admn_no from marks_subject_description as a where a.marks_master_id=?)";

        $d = $this->db->query($q, array($sem, $sess, $sy, $mid));
        if ($d->num_rows() > 0) {
            return $d->result();
        }
        return false;
    }

    function getjrfnew($sub, $s, $sy, $dept, $mid) {
        $q = "SELECT user_details.id as admn_no
                        FROM user_details
                        INNER JOIN (SELECT admn_no, jrf_sub.form_id
                                    FROM reg_exam_rc_form
                                    INNER JOIN (SELECT *
                                                FROM reg_exam_rc_subject
                                                WHERE sub_id = ?) as jrf_sub
                                    ON jrf_sub.form_id = reg_exam_rc_form.form_id
                                    WHERE course_id = 'jrf' AND branch_id = 'jrf' AND hod_status = '1'
                                    AND reg_exam_rc_form.session = ?
                                    AND reg_exam_rc_form.session_year=?
                                    AND acad_status = '1') as jrf_reg
                                ON user_details.id = jrf_reg.admn_no
                    WHERE user_details.id not in (select admn_no from marks_subject_description as a where a.marks_master_id=?)";


        $d = $this->db->query($q, array($sub, $s, $sy, $mid, $dept));
        if ($d->num_rows() > 0) {
            return $d->result();
        }
        return false;
    }

    function getjrfnewcore($sub, $s, $sy, $dept, $mid) {
        $q = "SELECT user_details.id as admn_no
                        FROM user_details
                        INNER JOIN (SELECT admn_no, jrf_sub.form_id
                                    FROM reg_exam_rc_form
                                    INNER JOIN (SELECT *
                                                FROM reg_exam_rc_subject
                                                WHERE sub_id = ?) as jrf_sub
                                    ON jrf_sub.form_id = reg_exam_rc_form.form_id
                                    WHERE course_id = 'jrf' AND branch_id = 'jrf' AND hod_status = '1'
                                    AND reg_exam_rc_form.session = ?
                                    AND reg_exam_rc_form.session_year=?
                                    AND acad_status = '1' and type='R') as jrf_reg
                                ON user_details.id = jrf_reg.admn_no
                    WHERE user_details.id not in (select admn_no from marks_subject_description as a where a.marks_master_id=?) and user_details.dept_id=?";


        $d = $this->db->query($q, array($sub, $s, $sy, $mid, $dept));
        if ($d->num_rows() > 0) {
            return $d->result();
        }
        return false;
    }

    function getjrf_spl_newcore($sub, $s, $sy, $dept, $mid) {
        $q = "SELECT user_details.id as admn_no
                        FROM user_details
                        INNER JOIN (SELECT admn_no, jrf_sub.form_id
                                    FROM reg_exam_rc_form
                                    INNER JOIN (SELECT *
                                                FROM reg_exam_rc_subject
                                                WHERE sub_id = ?) as jrf_sub
                                    ON jrf_sub.form_id = reg_exam_rc_form.form_id
                                    WHERE course_id = 'jrf' AND branch_id = 'jrf' AND hod_status = '1'
                                    AND reg_exam_rc_form.session = ?
                                    AND reg_exam_rc_form.session_year=?
                                    AND acad_status = '1' and type='S') as jrf_reg
                                ON user_details.id = jrf_reg.admn_no
                    WHERE user_details.id not in (select admn_no from marks_subject_description as a where a.marks_master_id=?) and user_details.dept_id=?";


        $d = $this->db->query($q, array($sub, $s, $sy, $mid, $dept));
        if ($d->num_rows() > 0) {
            return $d->result();
        }
        return false;
    }

    function otherForm($emp, $sy, $s) {

        $q = "(select  *,f.name as branch_name,g.name as course_name,e.name as sub_name,c.emp_no as emp_id from reg_other_subject as a
join reg_other_form as b on b.form_id=a.form_id
join subject_mapping_des as c on c.sub_id=a.sub_id
join subject_mapping as d on d.map_id=c.map_id and d.session_year=b.session_year and d.`session`=b.`session`
join subjects as e on e.id=a.sub_id
join cs_branches as f on d.branch_id=f.id
join courses as g on g.id=d.course_id
where c.emp_no=?
and b.session_year=?
and b.session=?
and b.course_id <> 'jrf'
and b.type='R'
and c.coordinator='1' group by a.sub_id)
union
(select  *,f.name as branch_name,g.name as course_name,e.name as sub_name,c.emp_no as emp_id from reg_exam_rc_subject as a
join reg_exam_rc_form as b on b.form_id=a.form_id
join subject_mapping_des as c on c.sub_id=a.sub_id
join subject_mapping as d on d.map_id=c.map_id
join subjects as e on e.id=a.sub_id
join cs_branches as f on d.branch_id=f.id
join courses as g on g.id=d.course_id
where c.emp_no=?
and b.session_year=?
and b.session=?
and b.course_id <> 'jrf'
and b.type='R'
and c.coordinator='1' group by a.sub_id)";

        $d = $this->db->query($q, array($emp, $sy, $s, $emp, $sy, $s));
        //echo $this->db->last_query();die();
        if ($d->num_rows() > 0) {
            return $d->result();
        }
        return false;
    }

    function specialForm($emp, $sy, $s) {

        $q = "(select  *,f.name as branch_name,g.name as course_name,e.name as sub_name,c.emp_no as emp_id from reg_other_subject as a
join reg_other_form as b on b.form_id=a.form_id
join subject_mapping_des as c on c.sub_id=a.sub_id
join subject_mapping as d on (d.map_id=c.map_id and d.session_year=b.session_year)
join subjects as e on e.id=a.sub_id
join cs_branches as f on d.branch_id=f.id
join courses as g on g.id=d.course_id
where c.emp_no=?
and d.session_year=?
and d.session=?
and b.course_id <> 'jrf'
and b.type='S'
and c.coordinator='1' group by a.sub_id)
union
(select  *,f.name as branch_name,g.name as course_name,e.name as sub_name,c.emp_no as emp_id from reg_exam_rc_subject as a
join reg_exam_rc_form as b on b.form_id=a.form_id
join subject_mapping_des as c on c.sub_id=a.sub_id
join subject_mapping as d on (d.map_id=c.map_id and d.session_year=b.session_year)
join subjects as e on e.id=a.sub_id
join cs_branches as f on d.branch_id=f.id
join courses as g on g.id=d.course_id
where c.emp_no=?
and d.session_year=?
and d.session=?
and b.course_id <> 'jrf'
and b.type='S'
and c.coordinator='1' group by a.sub_id)";

        $d = $this->db->query($q, array($emp, $sy, $s, $emp, $sy, $s));
        if ($d->num_rows() > 0) {
            return $d->result();
        }
        return false;
    }

    function jrfForm($emp, $sy, $s) {

        $q = "select  *,f.name as branch_name,g.name as course_name,d.dept_id as dept_id, dept.id as jrf_dept_id,
      dept.name as jrf_dept_name, e.name as sub_name,c.emp_no as emp_id, a.sub_id as sub_id,e.type as type
      from reg_exam_rc_subject as a
join reg_exam_rc_form as b on b.form_id=a.form_id
join subject_mapping_des as c on c.sub_id=a.sub_id
join subject_mapping as d on d.map_id=c.map_id and d.session_year=b.session_year and d.session=b.session
join subjects as e on e.id=a.sub_id
join cs_branches as f on d.branch_id=f.id
join cs_courses as g on g.id=d.course_id
join departments as dept on d.dept_id = dept.id
join user_details ud on ud.id=b.admn_no /*and ud.dept_id=d.dept_id*/
where c.emp_no=?
and d.session_year=?
and d.session=?
and b.course_id='jrf'
and b.branch_id='jrf'
and c.coordinator='1' group by /*b.admn_no,*/ d.dept_id, a.sub_id";

        $d = $this->db->query($q, array($emp, $sy, $s));

        if ($d->num_rows() > 0) {
            return $d->result();
        }
        return false;
    }

    //************************JRF Special Starts ***************************************

    function jrfForm_spl($emp, $sy, $s, $grp_by = null) {
        if ($grp_by == null)
            $grp_by = " b.admn_no /*d.dept_id, a.sub_id*/ ";

        $q = "select  *,f.name as branch_name,g.name as course_name,d.dept_id as dept_id, dept.id as jrf_dept_id,
      dept.name as jrf_dept_name, e.name as sub_name,c.emp_no as emp_id, a.sub_id as sub_id,e.type as type
      from reg_exam_rc_subject as a
join reg_exam_rc_form as b on b.form_id=a.form_id
join subject_mapping_des as c on c.sub_id=a.sub_id
join subject_mapping as d on d.map_id=c.map_id and d.session_year=b.session_year and d.session=b.session
join subjects as e on e.id=a.sub_id
join cs_branches as f on d.branch_id=f.id
join cs_courses as g on g.id=d.course_id
join departments as dept
on d.dept_id = dept.id
where c.emp_no=?
and d.session_year=?
and d.session=?
and b.course_id='jrf'
and b.branch_id='jrf'
and c.coordinator='1'
and b.`type`='S'


 group by $grp_by
";
        //group by b.admn_no /*d.dept_id, a.sub_id*/      coomented by ritu raj -18-7-18


        $d = $this->db->query($q, array($emp, $sy, $s));
        // echo $this->db->last_query();die();
        if ($d->num_rows() > 0) {
            return $d->result();
        }
        return false;
    }

    //************************JRF Special Ends ******************************************


    function getDeptById($id) {
        $d = $this->db->get_where('departments', array('id' => $id));
        if ($d->num_rows() > 0) {
            return $d->row();
        }
        return false;
    }

    /* function updateMarksDesTotal($mid){
      //echo $mid;
      $this->db->from('marks_subject_description');
      $this->db->where('marks_master_id' , $mid);
      $query = $this->db->get();
      $result=$query->result_array();
      foreach ($result as $row) {
      $tm = $row['sessional']+ ceil($row['theory']*6/10);
      //echo $row['sessional'];
      $this->updateMarksDes(array('total'=>$tm), array( 'marks_master_id'=>$mid , 'admn_no' => $row['admn_no']));
      }

      } */

    function updateMarksDesTotal($mid) {
        //echo $mid;
        $this->db->from('marks_subject_description');
        $this->db->where('marks_master_id', $mid);
        $query = $this->db->get();
        $result = $query->result_array();
        foreach ($result as $row) {
            $tm = $row['sessional'] + ceil($row['theory'] * 6 / 10);
            $newtheory = ceil($row['theory'] * 6 / 10);
            //echo $row['sessional'];
            $this->updateMarksDes(array('total' => $tm), array('marks_master_id' => $mid, 'admn_no' => $row['admn_no']));
            $this->updateMarksDes(array('theory' => $newtheory), array('marks_master_id' => $mid, 'admn_no' => $row['admn_no']));
        }
    }

}

?>
