<?php

/**
 * Author: Majeed Siddiqui (samsidx)
*/

class Leave_constants {

    //Encryption key

    // types of leaves
    static $TYPE_STATION_LEAVE = 1;
    static $TYPE_CASUAL_LEAVE = 2;
    static $TYPE_RESTRICTED_LEAVE = 3;
    static $TYPE_EARNED_LEAVE = 4;
    static $TYPE_VACATION_LEAVE = 5;
    static $TYPE_HPL = 6;
    
    // status of leave
    static $APPROVED = 0;
    static $REJECTED = 1;
    static $PENDING = 2;
    static $CANCELED = 3;
    static $WAITING_CANCELLATION = 4;
    static $WAITING_CANCELLATION_FWD = 14;    // Added new    
    static $WAITING_CANCELLATION_REJECTED = 15;    // Added new    
    static $FORWARDED = 5;
    static $NOT_SENT = 6;
    static $DEPRECATED = 7;
    static $CONSUMED = 8;
    static $WAITING_JOINING_APPROVAL = 9;
    static $JOINING_REJECTED = 10;
    static $JOINING_REQUEST_FORWARDED = 11;
    static $JOINING_REQUEST_SENT_BACK = 12;
    static $PRE_CANCELED = 13;

    // tables of leaves
    static $TABLE_LEAVE_BASIC_DETAILS = 'leave_basic_details';
    static $TABLE_CASUAL_LEAVE = 'leave_casual_details';
    static $TABLE_RESTRICTED_LEAVE = 'leave_restricted_details';
    static $TABLE_LEAVE_STATUS = 'leave_status';
    static $TABLE_USER_DETAILS = 'user_details';
    static $TABLE_RESTRICTED_HOLIDAYS = 'leave_restricted_dates';
    static $TABLE_VACATION_DATES = 'leave_vacation_date';
    static $TABLE_STATION_LEAVE = 'leave_station_details';
    static $TABLE_STATION_LEAVE_STATUS = 'leave_station_status';
    static $TABLE_STATION_LEAVE_REJECTING_REASON = 'leave_st_cancel_reason';
    static $TABLE_CANCEL_REASON = 'leave_cancel_reason';
    static $TABLE_LEAVE_FRAGMENT = 'leave_fragment';
    static $TABLE_LEAVE_BALANCE = 'leave_balance';
    static $TABLE_REJECTING_REASON = 'leave_cancel_reason';
    static $TABLE_EARNED_LEAVE_BALANCE = 'earned_leave_balance';
    static $TABLE_VACATION_LEAVE_BALANCE = 'vacation_leave_balance';
    static $TABLE_EARNED_LEAVE = 'leave_earned_details';
    static $TABLE_VACATION_LEAVE = 'leave_vacation_details';
    static $TABLE_HP_LEAVE_BALANCE = 'HP_leave_balance';
    static $TABLE_HPL_DETAILS = 'leave_hpl_details';
    static $TABLE_LEAVE_BALANCE_UPDATE_DETAILS = 'leave_balance_update_details';
    static $TABLE_LEAVE_JOINING_DETAILS = 'leave_joining_details';
    static $TABLE_HPL_FITNESS_CERTIFICATE = 'leave_hpl_fitness_certificate_path';
    static $TABLE_PREFIX_SUFFIX_DETAILS = 'leave_prefix_suffix_details';
    static $TABLE_GENERAL_STATION_RELATION = 'leave_general_station_relation';
    // max, min vals of leaves
    static $MAX_CASUAL_LEAVES = 8;
    static $MAX_RESTRICTED_LEAVE = 2;
    //nature of leave
    static $OFFICIAL = 1;
    static $PERSONNEL = 2;

    //auth_id of employee,Dealing assistant 3 for leave
    static $ADMIN_ARRAY = array(1=>'hod' , 2=>'dean_fnp',5=>'dean_rnd' , 6=>'dean_acad' ,
        7=>'dean_fnp' ,9=>'hos',10=>'hoc', 29=>'rg',33=>'dsw',34=>'ddt',35=>'dean_infra',36=>'dean_iraa',37=>'dean_is',38=>'jee_c',39=>'piclib',40=>'picexam',41=>'dean_admin');
		
		/*static $ADMIN_ARRAY = array(1=>'hod' , 2=>'dean_fnp', 4=>'dt' , 5=>'dean_rnd' , 6=>'dean_acad' ,
        7=>'dean_f&p_da' , 8=>'dean_r&d_da', 9=>'hos',10=>'hoc',11=>'acad_da3',12=>'exam_da3',13=>'est_da3',
        14=>'pns_da3',15=>'acc_da3',16=>'sw_da3',17=>'ca_da3',18=>'cmu_da3',19=>'sp_da3',20=>'hc_da3',
        21=>'cc_da3',22=>'tc_da3' , 23=>'cw_da3',24=>'tp_da3',25=>'pce_da3',26=>'crf_da3',27=>'dept_da3',
        28=>'dt_da3' , 29=>'rg',30=>'rg_da3',31=>'lib',32=>'ism_admin',33=>'dsw');*/

    static  $DEPARTMENTAL_ADMIN =array(1=>'acad_da3',2=>'exam_da3',3=>'est_da3',
        4=>'pns_da3',5=>'acc_da3',6=>'sw_da3',7=>'ca_da3',8=>'cmu_da3',9=>'sp_da3',10=>'hc_da3',
        11=>'cc_da3',12=>'tc_da3' , 13=>'cw_da3',14=>'tp_da3',15=>'pce_da3',16=>'crf_da3',17=>'dept_da3',
        18=>'hod' , 19=>'hos' , 22=>'hoc',23=>'lib');

    static $SUPER_ADMIN = array(1=>'dean_f&p_da' , 2=>'dean_r&d_da' , 3=>'acad_da3' , 4=>'dt' , 5=>'admin' ,
        6=>'dt_da3',7=>'rg_da3',8=>'RG',9=>'dean_acad',10=>'dean_fnp',11=>'dean_acad',12=>'dean_rnd');

     //Added  as per rq. to show other Admin option  in type choosing  like academic,nonacademic,other on 28/9/15
/*     static $OTHERS_ADMIN= array(1=>'dt',2=>'dsw3',3=>'dean_acad',4=>'dean_rnd',5=>'dean_sw',6=>'dean_fnp',7=>'pce',
                                 8=>'jee_c',9=>'adean_pid',10=>'adean_anr',11=>'adean_iraa',
                                 12=>'chwb',13=>'chwg',14=>'rg',15=>'est_ar',16=>'est_dr',17=>'est_leave');
								 
	*/
	
								 
     
       static $OTHERS_UNIQUE_ADMIN= array(1=>'est_dr');
	/*static $OTHERS_UNIQUE_ADMIN= array(1=>'est_leave',2=>'dsw3',3=>'chwg',4=>'est_ar',5=>'dean_sw',6=>'dean_fnp',7=>'pce',
                                 8=>'jee_c',9=>'adean_pid',10=>'adean_anr',11=>'adean_iraa',12=>'chwb',13=>'est_dr',14=>'chw');*/
   
    // end
    static $ABSOLUTE_ADMIN = array('hod','HOS', 'HOC','LIB' , 'admin' , 'DT' , 'dean_rnd' , 'dean_acad' , 'dean_f&p_da');
    static $HOD = 'hod';
    static $HOS = 'HOS';
    static $HOC = 'HOC';
    static $REGISTRAR = 'RG';

    // integer value assigned to particular name
    static $DEPARTMENT_TYPE = 1;
    static $DEPARTMENT = 2;
    static $DESIGNATION = 3;
    static $EMPLOYEE = 4;

    static $ACADEMIC = 'academic';
    static $NONACADEMIC = 'nonacademic';
	static $OTHERS_ADMIN = 'ism_admin';

    static $FACULTY = 'ft';
    static $NON_TEACHING_ACADEMIC = array(1=>'ft',2=>'nfta');
    static $NON_ACADEMIC_ID = 'nftn';

    static $CASUAL_FULL_NAME = "full";
    static $CASUAL_FOR_NOON_NAME = "before_half";
    static $CASUAL_AFTER_NOON_NAME = "after_half";

    static $CASUAL_FULL_VALUE = 1;
    static $CASUAL_FOR_NOON_VALUE = 2;
    static $CASUAL_AFTER_NOON_VALUE = 3;
    /** ID GENERATORS:- */
    static  $CASUAL_FULL_HALF = "full_half_";

    // ADD of Subtract
    static $ADD = 1;
    static $SUBTRACT = 2;

    // Encryption methods
    static $IV = "ABCDEFGHIJKLMNOP";
    static $PASSWORD = "NIshANtRaJLeAvE";
    static $METHOD = "aes128";

    public function strToHex($string){
        $hex = '';
        for ($i=0; $i<strlen($string); $i++){
            $ord = ord($string[$i]);
            $hexCode = dechex($ord);
            $hex .= substr('0'.$hexCode, -2);
        }
        return strToUpper($hex);
    }
    public function hexToStr($hex){
        $string='';
        for ($i=0; $i < strlen($hex)-1; $i+=2){
            $string .= chr(hexdec($hex[$i].$hex[$i+1]));
        }
        return $string;
    }
}

