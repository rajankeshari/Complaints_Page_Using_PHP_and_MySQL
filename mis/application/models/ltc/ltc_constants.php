<?php

class Ltc_constants
{
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

    static $ABSOLUTE_ADMIN = array('hod','HOS', 'HOC','LIB' , 'admin' , 'DT' , 'DEAN_RnD' , 'DEAN_ACAD' , 'DEAN_F&P_DA');
    static $HOD = 'hod';
    static $HOS = 'HOS';
    static $HOC = 'HOC';
    static $REGISTRAR = 'RG';
    static $PASSWORD = '246065bab5818fd330acc2f4793ed8cc';
}

?>