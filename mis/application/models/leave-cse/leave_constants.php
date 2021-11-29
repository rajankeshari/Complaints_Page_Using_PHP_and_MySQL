<?php

/**
 * Author: Majeed Siddiqui (samsidx)
*/

class Leave_constants {
    
    // types of leaves
    static $TYPE_CASUAL_LEAVE = 2;
    static $TYPE_RESTRICTED_LEAVE = 3;
    static $TYPE_STATION_LEAVE = 1;
    
    // status of leave
    static $APPROVED = 0;
    static $REJECTED = 1;
    static $PENDING = 2;
    static $CANCELED = 3;
    static $WAITING_CANCELLATION = 4;
    static $FORWARDED = 5;
    static $NOT_SENT = 6;
    static $DEPRECATED = 7;

    // tables of leaves
    static $TABLE_LEAVE_BASIC_INFO = 'leave_basic_info';
    static $TABLE_CASUAL_LEAVE = 'leave_casual';
    static $TABLE_RESTRICTED_LEAVE = 'leave_restricted';
    static $TABLE_LEAVE_STATUS = 'leave_status';
    static $TABLE_USER_DETAILS = 'user_details';
    static $TABLE_RESTRICTED_HOLIDAYS = 'restricted_holidays';
    static $TABLE_VACATION_DATES = 'vacation_dates';
    static $TABLE_STATION_LEAVE = 'leave_station_details';
    static $TABLE_STATION_LEAVE_STATUS = 'leave_station_status';
    static $TABLE_STATION_LEAVE_REJECTING_REASON = 'leave_st_cancel_reason';
    static $TABLE_LEAVE_FRAGMENT = 'leave_fragment';
    // max, min vals of leaves
    static $MAX_CASUAL_LEAVES = 8;
    static $MAX_RESTRICTED_LEAVE = 2;
    //nature of leave
    static $OFFICIAL = 1;
    static $PERSONNEL = 2;

    //auth_id of employee,Dealing assistant 3 for leave
    static $ADMIN_ARRAY = array(1=>'hod' , 2=>'DEAN_FnP' , 3=>'admin' , 4=>'DT' , 5=>'DEAN_RnD' , 6=>'DEAN_ACAD' ,
        7=>'DEAN_F&P_DA' , 8=>'DEAN_R&D_DA', 9=>'HOS',10=>'HOC',11=>'ACAD_DA3',12=>'EXAM_DA3',13=>'EST_DA3',
        14=>'PnS_DA3',15=>'ACC_DA3',16=>'SW_DA3',17=>'CA_DA3',18=>'CMU_DA3',19=>'SP_DA3',20=>'HC_DA3',
        21=>'CC_DA3',22=>'TC_DA3' , 23=>'CW_DA3',24=>'TP_DA3',25=>'PCE_DA3',26=>'CRF_DA3',27=>'DEPT _DA3',
        28=>'DT_DA3' , 29=>'RG',30=>'RG_DA3',31=>'LIB');

    static  $DEPARTMENTAL_ADMIN =array(1=>'ACAD_DA3',2=>'EXAM_DA3',3=>'EST_DA3',
        4=>'PnS_DA3',5=>'ACC_DA3',6=>'SW_DA3',7=>'CA_DA3',8=>'CMU_DA3',9=>'SP_DA3',10=>'HC_DA3',
        11=>'CC_DA3',12=>'TC_DA3' , 13=>'CW_DA3',14=>'TP_DA3',15=>'PCE_DA3',16=>'CRF_DA3',17=>'DEPT _DA3',
        18=>'hod' , 19=>'HOS' , 20=>'HOC' , 21=>'hos' , 22=>'hoc',23=>'LIB');

    static $SUPER_ADMIN = array(1=>'DEAN_F&P_DA' , 2=>'DEAN_R&D_DA' , 3=>'ACAD_DA3' , 4=>'DT' , 5=>'admin' ,
        6=>'DT_DA3',7=>'RG_DA3',8=>'RG',9=>'DEAN_ACAD',10=>'DEAN_FnP',11=>'DEAN_ACAD',12=>'DEAN_RnD');
    static $HOD = 'hod';
    static $HOS = 'HOS';
    static $HOC = 'HOC';

    // integer value assigned to particular name
    static $DEPARTMENT_TYPE = 1;
    static $DEPARTMENT = 2;
    static $DESIGNATION = 3;
    static $EMPLOYEE = 4;

    static $ACADEMIC = 'academic';
    static $NONACADEMIC = 'nonacademic';

    static $NON_TEACHING_ACADEMIC = array(1=>'ft',2=>'nfta');
    static $NON_ACADEMIC_ID = 'nftn';
}

