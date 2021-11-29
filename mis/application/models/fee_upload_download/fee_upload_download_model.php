<?
class Fee_upload_download_model extends CI_Model
{
	var $table = 'acad_fee_regular_tbl'; // table name in the database in which data of newly admitted students are stored.

	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}
	
	function insert($data)
	{
            
            
		$this->db->insert_batch($this->table,$data['values']);	
	}
	function insert_fee_structure_all($data,$type)
	{
            //echo '<br>',$type,'<br>'; 
            //print_r($data);
            switch ($type)
            {
                case 'fee':
                    $table = 'acad_fee_regular_tbl';
                    break;
                case 'sextra':
                    $table = 'acad_fee_extra_tbl';
                    break;
                case 'waiver':
                    $table = 'acad_fee_waiver_tbl';
                    break;
            }
            echo '<br>',$table,'<br>';    
           //print_r($data);
           
		$this->db->insert_batch($table,$data['values']);
                //$this->db->last_query();
                //die("insert data");
	}
	function delete_record($syear,$sess,$type){

        switch ($type)
        {
            case 'regular':
                    $sql="delete from fee_student_details where session_year=? and session=? and feegroup=? and account='open'";
                    break;
            case 'extra':
                    $sql="delete from fee_student_details where session_year=? and session=? and feegroup=? and account='open'";
                    break;
            case 'ALL':            
                    $sql="delete from fee_student_details where session_year=? and session=? and account='open'";
                    break;
            default :
                    $sql="delete from fee_student_details where session_year=? and session=? and srlno=? and account='open'";
                    break;
        } //of switch  
        //echo $syear,' ',$sess,' ',$type,'<br>';
        //echo($sql);
        //die('<br>Delete all');
        $query = $this->db->query($sql,array($syear,$sess,$type));

           // echo $this->db->last_query(); die();
            if ($this->db->affected_rows() > 0) {
                return true;
            } else {
                return false;
            }
        }
        
        function get_student_list($syear,$sess,$type){
        
        echo $syear,$sess,$type, '<br>';
                    
        switch ($type) {
            
            case 'regular':
            $tbl='reg_regular_form';
                break;
            case 'other':
            $tbl='reg_other_form';
                break;
            case 'idle':
            $tbl='reg_idle_form';
                break;
            case 'exam':
            $tbl='reg_exam_rc_form';
                break;
            default:
                $tbl='reg_regular_form';
                break;
        }
        $sql='';       
        
        
        if($sess=='Summer'){
            $tbl='reg_summer_form';
        }
        switch ($type) {
            case 'fee':
                $sql="select a.* from acad_fee_regular_tbl a where a.session_year=? and a.session=?";
                //$sql="select a.* from marks_master a where a.session_year=? and a.session=?";
                break;
            case  'waiver':
                $sql="select a.* from acad_fee_waiver_tbl a where a.session_year=? and a.session=?";
                break;
            case 'sextra':
                $sql="select a.* from acad_fee_extra_tbl a where a.session_year=? and a.session=?";
                break;
            default:
                $sql="select a.admn_no, concat_ws(' ',b.first_name,b.middle_name,b.last_name)as stu_name,
                    b.dept_id,a.course_id,a.branch_id,a.semester,b.category,b.physically_challenged  
                    from ".$tbl." a 
                    inner join user_details b on a.admn_no=b.id 
                    where a.session_year=? and a.session=?
                    and a.hod_status='1' and a.acad_status='1'";
                $sql="select a.admn_no, concat_ws(' ',b.first_name,b.middle_name,b.last_name)as stu_name,
                    a.session_year,a.session,b.dept_id,a.course_id,a.branch_id,a.semester,b.category,b.physically_challenged as pd 
                    from ".$tbl." a 
                    inner join user_details b on a.admn_no=b.id 
                    where a.session_year=? and a.session=?
                    and a.hod_status='1' and a.acad_status='1'
                    and a.course_id='b.tech'
                    union
                    select a.admn_no, concat_ws(' ',b.first_name,b.middle_name,b.last_name)as stu_name,
                    a.session_year,a.session,b.dept_id,a.course_id,a.branch_id,a.semester,b.category,b.physically_challenged as pd 
                    from reg_idle_form a 
                    inner join user_details b on a.admn_no=b.id 
                    where a.session_year=@sy and a.session=@ss
                    and a.hod_status='1' and a.acad_status='1'
                    and a.course_id='b.tech'

                     ";
            break;
        }
       // echo $syear,$sess,$type,'<br>';
       // echo $tbl, "  ",$sql,'<br>';
       // die('close');
        
        $query = $this->db->query($sql,array($syear,$sess));
        echo $this->db->last_query();
        //echo $this->db->affected_rows();
        //echo '<br>';
        //print_r($query);
        //die('close');
        if ($this->db->affected_rows() > 0) {
            //echo "<BR>",$syear,$sess,$type, '<br>';
            //echo "<br>length:",$query->num_rows();
            //echo "<br>length:",count($query->result());
            //print_r($query->result());
            return $query->result();
        } else {
            return false;
        }
            
        }
        //END of get_student_list
        function get_student_list_2($syear,$sess,$type){
        
        echo $syear,$sess,$type, '<br>';
                    
        switch ($type) {
            
            case 'regular':
            $tbl='reg_regular_form';
                break;
            case 'other':
            $tbl='reg_other_form';
                break;
            case 'idle':
            $tbl='reg_idle_form';
                break;
            case 'exam':
            $tbl='reg_exam_rc_form';
                break;
            default:
                $tbl='reg_regular_form';
                break;
        }
        $sql='';       
        
        
        if($sess=='Summer'){
            $tbl='reg_summer_form';
        }
        switch ($type) {
            case 'fee':
                $sql="select a.* from acad_fee_regular_tbl a where a.session_year=? and a.session=?";
                //$sql="select a.* from marks_master a where a.session_year=? and a.session=?";
                break;
            case  'waiver':
                $sql="select a.* from acad_fee_waiver_tbl a where a.session_year=? and a.session=?";
                break;
            case 'sextra':
                $sql="select a.* from acad_fee_extra_tbl a where a.session_year=? and a.session=?";
                break;
            default:
                $sql="select a.admn_no, concat_ws(' ',b.first_name,b.middle_name,b.last_name)as stu_name,
                    b.dept_id,a.course_id,a.branch_id,a.semester,b.category,b.physically_challenged  
                    from ".$tbl." a 
                    inner join user_details b on a.admn_no=b.id 
                    where a.session_year=? and a.session=?
                    and a.hod_status='1' and a.acad_status='1'";
                $sql="select a.admn_no, concat_ws(' ',b.first_name,b.middle_name,b.last_name)as stu_name,
                    a.session_year,a.session,b.dept_id,a.course_id,a.branch_id,a.semester,b.category,b.physically_challenged as pd 
                    from ".$tbl." a 
                    inner join user_details b on a.admn_no=b.id 
                    where a.session_year=? and a.session=?
                    
                    union
                    select a.admn_no, concat_ws(' ',b.first_name,b.middle_name,b.last_name)as stu_name,
                    a.session_year,a.session,b.dept_id,a.course_id,a.branch_id,a.semester,b.category,b.physically_challenged as pd 
                    from reg_idle_form a 
                    inner join user_details b on a.admn_no=b.id 
                    where a.session_year=@sy and a.session=@ss
                    

                     ";
            break;        }
       // echo $syear,$sess,$type,'<br>';
       // echo $tbl, "  ",$sql,'<br>';
       // die('close');
        
        $query = $this->db->query($sql,array($syear,$sess));
        //echo $this->db->last_query();
        //echo $this->db->affected_rows();
        //echo '<br>';
        //print_r($query);
        //die('close');
        if ($this->db->affected_rows() > 0) {
            //echo "<BR>",$syear,$sess,$type, '<br>';
            //echo "<br>length:",$query->num_rows();
            //echo "<br>length:",count($query->result());
            //print_r($query->result());
            return $query->result();
        } else {
            return false;
        }
            
        }
        //END of get_student_list
        
        function get_processed_student_list($syear,$sess,$type){
        
       //echo "<br> 1 get process student list",$syear,$sess,$type,"<br>";
        switch ($type) {
            
            case 'regular':
            $tbl='fee_student_details';
                break;
            case 'other':
            $tbl='fee_student_details';
                break;
            case 'idle':
            $tbl='fee_student_details';
                break;
            case 'exam':
            $tbl='fee_student_details';
                break;
            case sextra:
                $tbl='fee_student_details';
                break;
            default:
                $tbl='fee_student_details';
                break;
        }
       // echo "<br> 2 get process student list: ",$syear," ",$sess," ",$type," ",$tbl,"<br>";
        if($sess=='Summer'){
            $tbl='reg_summer_form';
        }
        switch ($type) {
            case 'fee':
                $sql="select a.* from acad_fee_regular_tbl a where a.session_year=? and a.`session`=?";
                break;
            case  'waiver':
                $sql="select a.* from acad_fee_waiver_tbl a where a.session_year=? and a.`session`=?";
                break;
            case 'sextra':
                $sql="select a.sbi,a.admn_no, a.stu_name as stu_name,
                a.dept_id,a.course,a.branch,a.semester,a.category,a.phd,a.total 
                from ".$tbl." a where a.session_year=? and a.`session`=? and a.feegroup=?";
                break;
            case 'regular':
                $sql="select a.sbi,a.admn_no, a.stu_name as stu_name,
                a.dept_id,a.course,a.branch,a.semester,a.category,a.phd,a.total 
                from ".$tbl." a where a.session_year=? and a.`session`=? and a.feegroup=?";
                break;
            default:
                $sql="select a.sbi,a.admn_no, a.stu_name as stu_name,
                a.dept_id,a.course,a.branch,a.semester,a.category,a.phd,a.total 
                from ".$tbl." a where a.session_year=? and a.`session`=? and a.srlno=?";
                break;
        }
        //echo $sql,"<br> 1 <br>";        
        //die("5 a b c");
        
        switch ($type) {
            case 'regular':
            $query = $this->db->query($sql,array($syear,$sess,'regular')); 
                break;
            case 'sextra':
            $query = $this->db->query($sql,array($syear,$sess,'sextra')); 
                break;
            default:
            $query = $this->db->query($sql,array($syear,$sess,$type));
                break;
        }
        echo " 2 abc<br>";
        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
            
        }
        //END of get_student_list
        
        function search_student_record ($admno,$syear,$sess,$sem,$type){
        //echo "<br>search_student_record<br>"    ;
        //echo $admno,"   ",$syear,"  ",$sess,"  ",$sem,"   ",$type,"<br>";
        switch ($type) {
            
            case 'regular':
            $tbl='reg_regular_form';
                break;
            case 'other':
            $tbl='reg_other_form';
                break;
            case 'idle':
            $tbl='reg_idle_form';
                break;
            case 'exam':
            $tbl='reg_exam_rc_form';
                break;
            case 'summer' :
            $tbl='reg_summer_form';
                break;
            default:
                $tbl='reg_regular_form';
                break;
        }
                
        $sql="select a.admn_no, concat_ws(' ',b.first_name,b.middle_name,b.last_name)as stu_name,
                    b.dept_id,a.course_id,a.branch_id,a.semester,b.category,b.physically_challenged  
                    from ".$tbl." a 
                    inner join user_details b on a.admn_no=b.id 
                    where a.admn_no =? and a.session_year=? and a.`session`=? and a.`semester`=?
                    and a.hod_status='1' and a.acad_status='1'";
        
        
        $query = $this->db->query($sql,array($admno, $syear,$sess,$sem));
               
        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
            
        }
        //++++++++++++++++++++++++++++ Read all the tables
        //
        function get_all_fee_tables($syear,$sess,$type,$query_regfee,$query_waiver,$query_extra){
        
            $sql_regular="select a.* from acad_fee_regular_tbl a where a.session_year=? and a.`session`=?";
               
            
            $sql_waiver="select a.* from acad_fee_waiver_tbl a where a.session_year=? and a.`session`=?";
                
            $sql_extra="select a.* from acad_fee_extra_tbl a where a.session_year=? and a.`session`=?";
       
            
        $query_regfee = $this->db->query($sql_regular,array($syear,$sess));
        $query_waiver = $this->db->query($sql_waiver,array($syear,$sess));
        $query_extra = $this->db->query($sql_extra,array($syear,$sess));
        
        $query__regular_fee = $query_regfee->result();
        $query_waiver_fee = $query_waiver->result();
        $query_extra_fee = $query_extra->result();
        
       
       //echo $this->db->last_query(); die();
       if ($this->db->affected_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
       }
            
        }
        //+++++++++++++++++++++++++++++++++++++++++++++++++
        //=====================Fee Waiver------------------------------------------------------------
        function delete_record_fee_waiver($syear,$sess){
            
            $sql="delete from acad_fee_regular_tbl where session_year=? and session=?";

            $query = $this->db->query($sql,array($syear,$sess));

            // echo $this->db->last_query(); die();
            if ($this->db->affected_rows() > 0) {
                return true;
            } else {
                return false;
            }
        }
        function insert_fee_waiver($data)
	{
		$this->db->insert_batch('acad_fee_waiver_tbl',$data['values']);	
	}
        function delete_record_extra_list($syear,$sess){
            
            $sql="delete from acad_fee_extra_tbl where session_year=? and session=?";

            $query = $this->db->query($sql,array($syear,$sess));

            // echo $this->db->last_query(); die();
            if ($this->db->affected_rows() > 0) {
                return true;
            } else {
                return false;
            }
        }
        function delete_record_fee_structure_all($syear,$sess,$type){
            
            switch ($type)
            {
                case 'fee':
                    $tbl = 'acad_fee_regular_tbl';
                    break;
                case 'sextra':
                    $tbl = 'acad_fee_extra_tbl';
                    break;
                case 'waiver':
                    $tbl = 'acad_fee_waiver_tbl';
                    break;
            }
            $sql="delete from $tbl";
                
            $query = $this->db->query($sql,array($syear,$sess));

            if ($this->db->affected_rows() > 0) {
                return true;
            } else {
                return false;
            }
        } //delete_record_fee_structure_all
        function insert_extra_list($data)
	{
		$this->db->insert_batch('acad_fee_extra_tbl',$data['values']);	
	}
        function insert_student_fee_table($data)
	{
        
        //echo "Within insert function<br>";
        //echo "Flag: ",!is_null($data['admn_no']),"<br>";
        //echo "Length: ",strlen($data['admn_no']),"<br>";
        //print_r($data);
        
         if ((!is_null($data['admn_no'])) || (strlen($data['admn_no']) > 0))
        {
         //echo "xxxxx<br>";
         $sql = "insert into fee_student_details(sbi,admn_no, stu_name,dept_id,course,branch,category,phd,semester,session_year,session,fee1,fee2,fee3,total,srlno,feegroup,account) values (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
         //echo $sql;
         //die("insert");
         $query = $this->db->query($sql,array($data['sbi'],$data['admn_no'],$data['stu_name'],$data['dept_id'],$data['course'],$data['branch'],$data['category'],$data['phd'],$data['semester'],$data['session_year'],$data['session'],$data['fee1'],$data['fee2'],$data['fee3'],$data['total'],$data['srlno'],$data['feegroup'],$data['account']));
         //echo "I m here<br>";
         //echo $this->db->last_query();
        }
        else 
            { 
            echo "<br> Null Record: <br>";
            }    
	}
        function read_stuname($admno)
        {
            //echo "i m here <br>";
            $sql = "select concat_ws(' ',first_name,middle_name,last_name)as stuname";
            $sql = $sql . "  from user_details";
            $sql = $sql . "  where id=?";
            $query = $this->db->query($sql,array($admno));
            //echo $this->db->last_query();
            //echo "<pre>";
            //print_r($query->result());
            $row = $query->result();
            $stuname = $row[0]->stuname;
            //die("xxxx4");
            if ($this->db->affected_rows() > 0) {
                return $stuname;
            } else {
                return "Name Not Found ";
            }
        }
        function read_deptid($admno)
        {
            $sql = "select dept_id as deptid";
            $sql = $sql . "  from user_details";
            $sql = $sql . "  where id=?";
            $query = $this->db->query($sql,array($admno));
            $row = $query->result();
            $did = $row[0]->deptid;
            if ($this->db->affected_rows() > 0) {
                return $did;
            } else {
                return "Dept Not Found";
            }
        }
        function read_courseid($admno)
        {
            $sql = "select course_id as courseid";
            $sql = $sql . "  from stu_academic";
            $sql = $sql . "  where admn_no=?";
            $query = $this->db->query($sql,array($admno));
            $row = $query->result();
            $cid = $row[0]->courseid;
            if ($this->db->affected_rows() > 0) {
                return $cid;
            } else {
                return "Course Not Found";
            }
        }
        function read_branchid($admno)
        {
            $sql = "select branch_id as branchid";
            $sql = $sql . "  from stu_academic";
            $sql = $sql . "  where admn_no=?";
            $query = $this->db->query($sql,array($admno));
            $row = $query->result();
            $bid = $row[0]->branchid;
            if ($this->db->affected_rows() > 0) {
                return $bid;
            } else {
                return "Branch Not Found";
            }
        }
        function read_category($admno)
        {
            $sql = "select category as cat";
            $sql = $sql . "  from user_details";
            $sql = $sql . "  where admn_no=?";
            $query = $this->db->query($sql,array($admno));
            $row = $query->result();
            $cat = $row[0]->cat;
            if ($this->db->affected_rows() > 0) {
                return $cat;
            } else {
                return "Course Not Found";
            }
        }
        function update_student_fee_table($admno,$fee,$sey,$ses,$sem)
	{
                $sql = "update fee_student_details";
                $sql = $sql . " set total= ?";
                $sql = $sql . " where admn_no=?";
                $sql = $sql . " and   session_year=?";
                $sql = $sql . " and   session=?";
                $sql = $sql . " and   semester=?";
                
            //echo "Update model module: ",$admno," ",$sey," ",$ses," ",$sem,"  ",$fee,"<br>";
            $query = $this->db->query($sql,array($fee,$admno,$sey,$ses,$sem));
        
            
	}
        function update_student_fee_table_acstatus($acstatus,$sey,$ses)
	{
                $sql = "update fee_student_details";
                $sql = $sql . " set account = ?";
                $sql = $sql . " where session_year=?";
                $sql = $sql . " and   session=?";
               
                
            //echo "Update model module: ",$admnno," ",$sey," ",$ses," ",$sem,"  ",$fee,"<br>";
            $query = $this->db->query($sql,array($acstatus,$sey,$ses));
        
            
	}
        //------------------------------------------------------------------------------------------
	function search_fee_tables_model($admnno,$sesyr,$ses,$course,$branch,$cat,$pd,$sem,$syear,$sess,$admission_year,$query_regular_fee,$query_waiver_fee,$query_extra_fee)
        {
            //echo "I am here <br>";
            //echo $sesyr,"  ",$ses,"  ",$admnno,"<br>";
            //print_r($query_regular_fee);
            //echo "<br><br>";
            //$x = in_array($admnno,array_column($query_extra_fee,'admission_no'));
            //echo "Length waiver:",count($query_regular_fee);
            //echo "<br>";
            //die("OK");
            //echo "<br> Length regular:",count($query_regular_fee);
            //echo "<br> Length waiver:",count($query_waiver_fee);
	    //echo "<br> Length extra:",count($query_extra_fee);
            //echo "<br>";
            //die("OK");
            //$syear,$sess - session year and session of admission/registration  year
            if (count($query_extra_fee) > 0) {
                
            foreach($query_extra_fee as $extrarow) 
            {
                //echo "<br>extra<br>";
                $a=  $extrarow->session_year;
                $b=  $extrarow->session;
                $c=  $extrarow->admn_no;
                //echo "<br>Foreach: ",$a," ",$b," ",$c,"<br>";
                //print_r($extrarow);
                //if (($a == $sesyr) && ($b == $ses) && ($c == $admnno))
                if ($c == $admnno)
                {
                    
                    $extrafee = $extrarow->fee_amt;
                    $query_extra_fee = $extrafee;
                    //echo $extrafee;
                    return $extrafee;
                    break;
                }
                
                
            }
            }//of if
            //die("a s d");
            //die("search_fee_tables_model");
            //$x = in_array($admnno,array_column($query_waiver_fee,'admission_no'));
            if (count($query_waiver_fee) > 0) {
            foreach($query_waiver_fee as $waiverrow)
            {
                //echo "<br>waiver<br>";
                $a=  $waiverrow->session_year;
                $b=  $waiverrow->session;
                $c=  $waiverrow->admn_no;
                $g = $waiverrow->semester;
                //echo "<br>Foreach: ",$a," ",$b," ",$c,"<br>";
                //if (($a == $sesyr) && ($b == $ses) && ($c == $admnno))
                if ($c == $admnno && $g == $sem)
                {
                    $wavefee = $waiverrow->fee_amt;
                    $query_waiver_fee = $wavefee;
                    
                    return $wavefee;
                    break;
                }
                
                
            }
            }//of if
            //$x = in_array($sesyr,array_column($query_regular_fee,'session_year'));
            
            if (count($query_regular_fee) > 0) {
            foreach($query_regular_fee as $regularrrow)
            {
                //echo "<br>regular<br>";
                //print_r($regularrow);echo "<br>";
                $i=  $regularrrow->id;
                $a=  $regularrrow->session_year;
                $b=  $regularrrow->session;
                $c=  strtoupper($regularrrow->course);
                $ay = substr($regularrrow->admission,2,2);
                $e = strtoupper($regularrrow->category);
                $f = strtoupper($regularrrow->pd);
                $g = $regularrrow->semester;
                $h = $regularrrow->fee_amt;
                //print_r($query_regular_fee);
                //echo "<br>",$admnno,"   ",$$ay,"  ",$i," ",$g,"  ",$sem,"  ",$a,"   ",$c," ",$e," ",$h,"  ",$admission_year,"  ",strtoupper($course),"  ",strtoupper($cat);
                //echo $a,"  ",$b,"  ",$c,"<br>";
                //die("<br>within fee regular");
                //if (($a == $sesyr) && ($b == $ses) && ($c == strtoupper($course)) && ($e == strtoupper($cat))) // && ($f == strtoupper($pd)))
                //if (($ay == $admission_year) && ((strpos($c,strtoupper($course)) !== FALSE) || (strpos(strtoupper($course),$c) !== FALSE))
                //&& ((strpos($e,strtoupper($cat)) !== FALSE) || (strpos(strtoupper($cat),$e) !== FALSE)) ) // && ($f == strtoupper($pd)))
                if (($ay == $admission_year) && $g == $sem && ((strpos($c,strtoupper($course)) !== FALSE) || (strpos(strtoupper($course),$c) !== FALSE))
                && ((strpos($e,strtoupper($cat)) !== FALSE) || (strpos(strtoupper($cat),$e) !== FALSE) || (strpos(strtoupper($e),"PD") !== FALSE))   ) // && ($f == strtoupper($pd)))
                        
                    {
                    //die("<br>within fee regular");
                    $regfee = $h;
                    $query_regular_fee = $regfee;
                    return $regfee;
                    break;
                }
                
                
            }
            }//of if
            
            //return $regfee;
            //return $wavefee;
        }
    function search_admno_infile($admno,$syear,$sess,$sem,$type)
    {
      
        $sql = "select * from fee_student_details";
        $sql = $sql . " where admn_no=?";
        $sql = $sql . " and   session_year=?";
        $sql = $sql . " and   session=?";
        $sql = $sql . " and   semester=?";
        $query = $this->db->query($sql,array($admno,$syear,$sess,$sem));
        
        if ($this->db->affected_rows() > 0) {
            return TRUE; // $query->result();
        } else {
            return FALSE;
        }
       
      return $stu_list;
    }
    function search_srlno_infile($syear,$sess,$type)
    {
      
        $sql = "select max(srlno) as lastsrlno  from fee_student_details";
        $sql = $sql . " where session_year=?";
        $sql = $sql . " and   session=?";
        $sql = $sql . "group by session_year, session";
        
        $query = $this->db->query($sql,array($syear,$sess ));
        //    print_r($query->result());
            
        
        if ($this->db->affected_rows() > 0) {
                return $query->result(); // $query->result();
            
        } else {
            return 0;
        }
       
      return $stu_list;
    }
    
    function search_in_array_1($srchvalue, $array, $indexflds, $srchfield)
    {
    foreach($array as $row)
    {
        
    }
    if (is_array($array) && count($array) > 0)
    {
        $foundkey = array_search($srchvalue, $array);
        if ($foundkey === FALSE)
        {
            foreach ($array as $key => $value)
            {
                if (is_array($value) && count($value) > 0)
                {
                    $foundkey = search_in_array($srchvalue, $value);
                    if ($foundkey != FALSE)
                        return $foundkey;
                }
            }
        }
        else
            return $foundkey;
    }
}
    function search_in_array($srchvalue, $array)
    {
    if (is_array($array) && count($array) > 0)
    {
        $foundkey = array_search($srchvalue, $array);
        if ($foundkey === FALSE)
        {
            foreach ($array as $key => $value)
            {
                if (is_array($value) && count($value) > 0)
                {
                    $foundkey = search_in_array($srchvalue, $value);
                    if ($foundkey != FALSE)
                        return $foundkey;
                }
            }
        }
        else
            return $foundkey;
    }
}
function read_fee_student_details_srlno($sesy,$ses)       
{
                
        $sql = "select  distinct  srlno  from fee_student_details";
        $sql = $sql . " where session_year= ? and session= ?";
        
        $query = $this->db->query($sql,array($sesy,$ses ));
                 
        
        if ($this->db->affected_rows() > 0) {
                return $query->result(); // $query->result();
            
        } else {
            return 0;
        }
       
      
}   
function get_student_fee_list($syear,$sess){
        
        //echo "<br>within get student fee list<br>"        ;
        
                $sql = "select a.admn_no,concat_ws('  ',c.first_name,c.middle_name,c.last_name) as name
                        ,c.dept_id,b.course_id,b.branch_id,b.semester,c.category,c.physically_challenged,a.fee_amt,a.late_fee_amt,(a.fee_amt+a.late_fee_amt) as total,
                        DATE_FORMAT(a.fee_date, '%d %b %Y') as payment_date,a.transaction_id,
                        if (DATE_FORMAT(a.late_fee_date,'%Y') = '1970', '---', DATE_FORMAT(a.late_fee_date, '%d %b %Y')) as late_paymet_date ,a.late_transaction_id
                        from reg_regular_fee a
                        inner join reg_regular_form  as b on a.form_id = b.form_id
                        inner join user_details as c on c.id = a.admn_no
                        where b.session_year = ? and b.session= ? and b.hod_status='1' and b.acad_status='1'
                        group by a.admn_no
                        order by b.course_id,b.branch_id,b.semester,a.admn_no;";
            
        
        //echo "<br>"      , $sql,"<br>",$syear,"<bt>",$sess;
        $query = $this->db->query($sql,array($syear,$sess));
        //echo $this->db->last_query(); die();
        //echo $syear,$sess,$sql;
        //die();
        if ($this->db->affected_rows() > 0) {
            
            return $query->result();
        } else {
            return false;
        }
            
        }
        //END of get_student_fee_list
        
}// of class Fee_upload_download_model 
?>