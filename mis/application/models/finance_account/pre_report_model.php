<?php
class pre_report_model extends CI_Model{
	function __construct()
	{
		parent::__construct();
		$this->load->model($this->models,'',TRUE);
	}

	function get_salary_bill_payment_data($pay_field,$ded_field,$arr=''){
        $pf="";
        $pf_sum="";
        $df="";
        $df_sum="";
        $i=0;
        $cond="";
        if(count($arr)>0){
                    foreach ($arr as $key => $value) {
                        $cond.=" and A.".$key."=".$value;
                    }
        }
        foreach ($pay_field as $r) {
            if($i==0){
                $pf.=' apd.'.$r['field'];
                $pf_sum.="(case when apd.".$r['field']." is null then 0 else apd.".$r['field']." end)";
            }
            else{
                $pf.=',apd.'.$r['field'];  
                $pf_sum.="+(case when apd.".$r['field']." is null then 0 else apd.".$r['field']." end)";
            }
            $i++;
        }
        $i=0;
        foreach ($ded_field as $r) {
            if($i==0){
                $df.=' apd.'.$r['field'];
                $df_sum.="(case when apd.".$r['field']." is null then 0 else apd.".$r['field']." end)";
            }
            else{
                $df.=',apd.'.$r['field'];  
                $df_sum.="+(case when apd.".$r['field']." is null then 0 else apd.".$r['field']." end)";
            }
            $i++;
        }
       
        $q="SELECT apd.EMPNO, apd.NAME,apd.DEPT,apd.DESIG,apd.MON,apd.YR, $pf ,$pf_sum as GROSS, $df_sum as DEDUCTION,($pf_sum)-($df_sum) as NETPAY
        FROM  acc_pay_details_temp apd 
        where apd.BASIC>=0 order by cast(apd.EMPNO as DECIMAL)";
        /*if(count($arr)>0){
                    foreach ($arr as $key => $value) {
                        $q.=" and ".$key."=".$value;
                    }
                }*/
       //echo $q;die();
       if($query=$this->db->query($q)){
            if($query->num_rows()>0){
                return $query->result_array();
            }
            else{
                return false;
            }
        }
        else{
            return false;
        }
    }
	function get_salary_bill_sumup($pay_field,$ded_field,$arr=''){
		$pf="";
        $pf_sum="";
		$df_sum="";
        $i=0;
        $cond="";
        if(count($arr)>0){
                    foreach ($arr as $key => $value) {
                        $cond.=" and A.".$key."=".$value;
                    }
                }
        foreach ($pay_field as $r) {
            if($i==0){
                $pf.=' sum(apd.'.$r['field'].') as '. $r['field'];
				$pf_sum.='sum(apd.'.$r['field'].')';
            }
            else{
				$pf.=',sum(apd.'.$r['field'].') as '. $r['field'];
                $pf_sum.='+sum(apd.'.$r['field'].')';  
            }
            $i++;
        }
		$i=0;
		foreach ($ded_field as $r) {
            if($i==0){
				$df_sum.='sum(apd.'.$r['field'].')';
            }
            else{
                $df_sum.='+sum(apd.'.$r['field'].')';  
            }
            $i++;
        }
       
        $q="SELECT $pf,$pf_sum as GROSS, $df_sum as DEDUCTION,($pf_sum)-($df_sum) as NETPAY FROM acc_pay_details_temp apd";
       //echo $q;die();
       if($query=$this->db->query($q)){
            if($query->num_rows()>0){
                $result=$query->result_array();
                return $result[0];
            }
            else{
                return false;
            }
        }
        else{
            return false;
        }
	}
   
	/*-----------------------------------DEDUCTION REPORT----------------------------*/
	function get_salary_bill_deduction_data($pay_field,$ded_field,$arr=''){
        $pf="";
        $pf_sum="";
        $df="";
        $df_sum="";
        $i=0;
        $cond="";
        if(count($arr)>0){
                    foreach ($arr as $key => $value) {
                        $cond.=" and A.".$key."=".$value;
                    }
                }
        foreach ($pay_field as $r) {
            if($i==0){
                $pf.=' apd.'.$r['field'];
                $pf_sum.="(case when apd.".$r['field']." is null then 0 else apd.".$r['field']." end)";
            }
            else{
                $pf.=',apd.'.$r['field'];  
                $pf_sum.="+(case when apd.".$r['field']." is null then 0 else apd.".$r['field']." end)";
            }
            $i++;
        }
        $i=0;
        foreach ($ded_field as $r) {
            if($i==0){
                $df.=' apd.'.$r['field'];
                $df_sum.="(case when apd.".$r['field']." is null then 0 else apd.".$r['field']." end)";
            }
            else{
                $df.=',apd.'.$r['field'];  
                $df_sum.="+(case when apd.".$r['field']." is null then 0 else apd.".$r['field']." end)";
            }
            $i++;
        }
       
        $q="SELECT apd.EMPNO,apd.NAME,apd.DEPT,apd.DESIG, $df ,$pf_sum as GROSS, $df_sum as DEDUCTION,($pf_sum)-($df_sum) as NETPAY FROM acc_pay_details_temp apd where apd.BASIC>=0 order by cast(apd.EMPNO as DECIMAL)";
		//echo $q;die();
		if($query=$this->db->query($q)){
            if($query->num_rows()>0){
                return $query->result_array();
            }
            else{
                return false;
            }
        }
        else{
            return false;
        }
    }
	
	function get_salary_bill_deduction_sumup($ded_field,$arr=''){
		$df="";
        $pf_sum="";
        $i=0;
        $cond="";
        if(count($arr)>0){
                    foreach ($arr as $key => $value) {
                        $cond.=" and A.".$key."=".$value;
                    }
                }
         foreach ($ded_field as $r) {
            if($i==0){
                $df.=' sum(apd.'.$r['field'].') as '. $r['field'];
                $df_sum.='sum(apd.'.$r['field'].')';
            }
            else{
                $df.=',sum(apd.'.$r['field'].') as '. $r['field'];  
                $df_sum.='+sum(apd.'.$r['field'].')';  
            }
            $i++;
        }
       
        $q="SELECT $df,$df_sum as DEDUCTION FROM  acc_pay_details_temp apd";
       //echo $q;die();
       if($query=$this->db->query($q)){
            if($query->num_rows()>0){
                $result=$query->result_array();
                return $result[0];
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
?>