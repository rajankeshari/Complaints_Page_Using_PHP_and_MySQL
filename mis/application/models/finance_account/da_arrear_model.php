<?php
/*
select apd.EMPNO,apd.BASIC,apd.GRPAY,apd.NPA,apd.TALLOW,ebd.joining_date,ebd.retirement_date from acc_pay_details  apd LEFT join emp_basic_details ebd on ebd.emp_no=apd.EMPNO  where apd.MON=8 and apd.YR=17  order by apd.EMPNO

select apd.EMPNO,apd.BASIC,apd.GRPAY,apd.NPA,apd.TALLOW,ebd.joining_date,ebd.retirement_date from acc_pay_details  apd LEFT join emp_basic_details ebd on ebd.emp_no=apd.EMPNO  where apd.MON=8 and apd.YR=17 and ebd.joining_date not between '2017-07-01' and '2017-09-31' order by apd.EMPNO

select apd.EMPNO,apd.BASIC,apd.GRPAY,apd.NPA,apd.TALLOW,ebd.joining_date,ebd.retirement_date from acc_pay_details  apd LEFT join emp_basic_details ebd on ebd.emp_no=apd.EMPNO  where apd.MON=8 and apd.YR=17 and ebd.joining_date  between '2017-07-01' and '2017-09-31' order by apd.EMPNO

select apd.EMPNO,apd.BASIC,apd.GRPAY,apd.NPA,apd.TALLOW,ebd.joining_date,ebd.retirement_date from acc_pay_details  apd LEFT join emp_basic_details ebd on ebd.emp_no=apd.EMPNO  where apd.MON=8 and apd.YR=17 and ebd.retirement_date  between '2017-07-01' and '2017-09-31' order by apd.EMPNO
*/
class Da_arrear_model extends CI_Model {
    function __construct() {
        parent::__construct();
    }

   function getEmployee($data,$type){
   	$this->db->query("SET SESSION group_concat_max_len = 1000000");
   	if(strcmp($type,'cat1')==0){
    # written by sourav
   	$q="select  group_concat(apd.EMPNO) as EMPNO from acc_pay_details  apd LEFT join emp_basic_details ebd on ebd.emp_no=apd.EMPNO  where apd.MON=? and apd.YR=? and ebd.joining_date not between ? and ? order by apd.EMPNO";

    /* commented by sourav
    $q="select  group_concat(apd.EMPNO) as EMPNO from acc_pay_details  apd LEFT join emp_basic_details ebd on ebd.emp_no=apd.EMPNO  where apd.MON=? and apd.YR=? and ebd.joining_date not between ? and ? order by apd.EMPNO";
    */

      if($query=$this->db->query($q,array($data['mon'],$data['yr'],$data['dfrom'],$data['dto']))){
         //echo $this->db->last_query();die();
         if($query->num_rows()>0){
            return $query->row();
         }
         else{
            return false;
         }
      }
      else{
         return false;
      }
   	}
   	if(strcmp($type,'cat2')==0){
       $q="select group_concat(ebd.emp_no) as EMPNO from emp_basic_details ebd  where ebd.joining_date between ? and ? order by ebd.emp_no";
   	 //$q="select group_concat(apd.EMPNO) as EMPNO from acc_pay_details  apd LEFT join emp_basic_details ebd on ebd.emp_no=apd.EMPNO  where apd.MON=? and apd.YR=? and ebd.joining_date between ? and ? order by apd.EMPNO";
       if($query=$this->db->query($q,array($data['dfrom'],$data['dto']))){
         //echo $this->db->last_query();die();
         if($query->num_rows()>0){
            return $query->row();
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

   function getArrearCat1($empno,$all){
   	//var_dump($all); CURRENT DA RATE FOR BOTH 2% of basic
   	/* STOPPED BY SOURAV 25-09-2018
    $q="select apd.EMPNO,apd.MON,apd.YR,round((apd.BASIC+ apd.GRPAY)*?/100) as DAARR,round((apd.TALLOW*?/100)) as TRDAARR from (select A.EMPNO,A.MON,A.YR,A.BASIC,A.GRPAY,A.NAME,A.TALLOW from acc_pay_details A where A.MON=? and A.YR=? and A.EMPNO in ($empno)) as apd";
    */
	
	// Added NPA also to calculate the DA 
    $q="select apd.EMPNO,
     apd.MON,
     apd.YR,
     round((apd.BASIC+ apd.GRPAY+apd.NPA)*?/100) as DA,
     round((apd.BASIC+ apd.GRPAY+apd.NPA)*?/100) as DAARR,
     round((apd.TALLOW*?/100)) as TRDAARR,
     round(apd.TALLOW*?/100) as TRANSDA
     from (select A.EMPNO,A.MON,A.YR,A.BASIC,A.GRPAY,A.NAME,A.TALLOW,A.NPA from acc_pay_details A where A.MON=? and A.YR=? and A.EMPNO in
     ($empno)) as apd";
   	if($query=$this->db->query($q,array($all['ndarate'],$all['darate'],$all['darate'],$all['transda'],$all['mon'],$all['yr']))){
   		#echo 'getArrearCat1:- '.$this->db->last_query();die();
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

   function insertArrearTemp($arr){ /* iNSERTING da ARREAR ONE SINGLE ROW WISE*/
   		foreach ($arr as $r) {
   			#echo '<pre>'.var_dump($r).'</br>'; #die();
   			$this->db->insert('acc_arrear_temp',$r);#echo '</br>';
        #$this->db->query('update acc_pay_details_temp set TRANSDA =0'); #moved to below function
   			#echo $this->db->last_query().'</br>';
   		} #die();
   }



   function getJoiningDate($id){
         $this->db->select('joining_date');
         $this->db->where('emp_no',$id);
         if($query=$this->db->get('emp_basic_details')){
            if($query->num_rows()>0){
               $result=$query->result();
               return $result[0]->joining_date;
            }
            else{
               return false;
            }
         }
         else{
            return false;
         }
   }


   function calSplArrear1($arr,$all){
      //var_dump($arr);
      //var_dump($all);
      $q="select apd.EMPNO,round((apd.BASIC+ apd.GRPAY+apd.NPA)*(?*?)/(?*100)) as DAARR,round((apd.TALLOW)*(?*?)/(?*100)) as TRDAARR from acc_pay_details apd, (select A.YR, (select max(B.MON) from acc_pay_details B where B.YR=A.YR) as MON from (select max(YR) as YR from acc_pay_details ) as A) C where apd.MON=C.MON and apd.YR=C.YR and apd.EMPNO=?";
      if($query=$this->db->query($q,array($all['darate'],$arr['work_day'],$arr['tot_day'],$all['darate'],$arr['work_day'],$arr['tot_day'],$arr['empno']))){
         if($query->num_rows()>0){
            #echo 'calSplArrear1:- '.$this->db->last_query()."<br>"; die();
            return $query->result_array();
         }
         else{
            return false;
         }
      }
      else{
         //echo $this->db->last_query();
         return false;
      }
   }

    function calSplArrear2($arr,$all){
      $q="select apd.EMPNO,round((apd.BASIC+ apd.GRPAY)*(?*?)/(?*100)) as DAARR,round((apd.TALLOW)*(?*?)/(?*100)) as TRDAARR from acc_pay_details apd, (select A.YR, (select max(B.MON) from acc_pay_details B where B.YR=A.YR) as MON from (select max(YR) as YR from acc_pay_details ) as A) C where apd.MON=C.MON and apd.YR=C.YR and apd.EMPNO=?";
       if($query=$this->db->query($q,array($all['darate'],$arr['tot_day'],$arr['tot_day'],$all['darate'],$arr['tot_day'],$arr['tot_day'],$arr['empno']))){
         if($query->num_rows()>0){
            #echo 'calSplArrear2:- '.$this->db->last_query()."<br>"; die();
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

   function getFormTemp(){
      /* For fixing blank records in table below query commented by sourav
      $q="select aat.*,concat(ud.salutation,' ',ud.first_name,' ',ud.middle_name,' ',ud.last_name) as name,(select A.name from departments A where A.id=ud.dept_id) as dept from acc_arrear_temp  aat  left join user_details ud on ud.id=aat.EMPNO order by cast(aat.EMPNO as DECIMAL) ";
      */
      $q="select aat.*,concat(ud.salutation,' ',ud.first_name,' ',ud.middle_name,' ',ud.last_name) as name,(select A.name from departments A where A.id=ud.dept_id) as dept from acc_arrear_temp aat join user_details ud on ud.id=aat.EMPNO order by cast(aat.EMPNO as DECIMAL) ";
      if($query=$this->db->query($q)){
         if($query->num_rows()>0){
            return $query->result();
         }
         else{
            return false;
         }
      }
      else{
         return false;
      }
   }

   function getDataToMerge(){
      # cOMMENTED AS ON 25-09-2018 NEW FIELDS ADDED IN TEM TABLE $q="select apd.*,apdt.DA,apdt.TRANSDA,concat(ud.salutation,' ',ud.first_name,' ',ud.middle_name,' ',ud.last_name) as name,(select X.name from departments X where X.id=ud.dept_id) as dept from (select distinct(aat.EMPNO), (select sum(A.DAARR) from acc_arrear_temp A where A.EMPNO=aat.EMPNO) as DAARR ,(select sum(B.TRDAARR) from acc_arrear_temp B where B.EMPNO=aat.EMPNO) as TRDAARR from acc_arrear_temp aat) as apd left join user_details ud on ud.id=apd.EMPNO left join acc_pay_details_temp apdt on apdt.EMPNO=apd.EMPNO";
      //$q="select apd.*,concat(ud.salutation,' ',ud.first_name,' ',ud.middle_name,' ',ud.last_name) as name,(select X.name from departments X where X.id=ud.dept_id) as dept from (select distinct(aat.EMPNO), (select sum(A.DAARR) from acc_arrear_temp A where A.EMPNO=aat.EMPNO) as DAARR ,(select sum(B.TRDAARR) from acc_arrear_temp B where B.EMPNO=aat.EMPNO) as TRDAARR from acc_arrear_temp aat) as apd left join user_details ud on ud.id=apd.EMPNO";

      /*************************
              @author: Sourav-25-09-2018 with name and empid
              ****************************************************/
      $q="select apd.*,
          concat(ud.salutation,' ',ud.first_name,' ',ud.middle_name,' ',ud.last_name) as name,
          (select X.name from departments X where X.id=ud.dept_id) as dept from (select distinct(aat.EMPNO),
          (select C.DA from acc_arrear_temp C where C.EMPNO=aat.EMPNO LIMIT 1) as DA,
          (select D.TRANSDA from acc_arrear_temp D where D.EMPNO=aat.EMPNO LIMIT 1) as TRANSDA,
          (select sum(A.DAARR) from acc_arrear_temp A where A.EMPNO=aat.EMPNO) as DAARR ,
          (select sum(B.TRDAARR) from acc_arrear_temp B where B.EMPNO=aat.EMPNO) as TRDAARR
          from acc_arrear_temp aat) as apd left join user_details ud on ud.id=apd.EMPNO join acc_pay_details_temp apdt on apdt.EMPNO=apd.EMPNO";
       if($query=$this->db->query($q)){
         if($query->num_rows()>0){
            return $query->result();
         }
         else{
            return false;
         }
      }
      else{
         return false;
      }
   }

   function getDataToMergeFinal(){ /*Return empno & daarr*/
      /*With TRDAARR
      $q="select apd.EMPNO,(apd.DAARR+apd.TRDAARR) as DAARR from (select distinct(aat.EMPNO), (select sum(A.DAARR) from acc_arrear_temp A where A.EMPNO=aat.EMPNO) as DAARR ,(select sum(B.TRDAARR) from acc_arrear_temp B where B.EMPNO=aat.EMPNO) as TRDAARR from acc_arrear_temp aat) as apd left join user_details ud on ud.id=apd.EMPNO";
      */
      /* Without TRDAARR
      $q="select apd.EMPNO, apd.DAARR as DAARR from (select distinct(aat.EMPNO), (select sum(A.DAARR) from acc_arrear_temp A where A.EMPNO=aat.EMPNO) as DAARR ,(select sum(B.TRDAARR) from acc_arrear_temp B where B.EMPNO=aat.EMPNO) as TRDAARR from acc_arrear_temp aat) as apd left join user_details ud on ud.id=apd.EMPNO";
      */
      /**************************************
          @author: Sourav #ONLY FIRST MONTH DA & TRANSDA IS PAYABLE- without name & empid
                                            ******************************/
      $q="select apd.* from (
              select distinct(aat.EMPNO),
             (select C.DA from acc_arrear_temp C where C.EMPNO=aat.EMPNO limit 1) as DA,
             (select sum(A.DAARR) from acc_arrear_temp A where A.EMPNO=aat.EMPNO) as DAARR,
             (select sum(B.TRDAARR) from acc_arrear_temp B where B.EMPNO=aat.EMPNO) as TRDAARR,
             (select D.TRANSDA from acc_arrear_temp D where D.EMPNO=aat.EMPNO LIMIT 1) as TRANSDA
              from acc_arrear_temp aat
              ) as apd LEFT join user_details ud on ud.id=apd.EMPNO";
       if($query=$this->db->query($q)){
      //echo $this->db->last_query();die();
           if($query->num_rows()>0){
              return $query->result();
           }
           else{
              return false;
           }
        }
        else{
           return false;
        }
   }

   function updateArrear($data){
      //$arr="";
      # SET 0
      $this->db->set('DAARR',0);
      $this->db->update('acc_pay_details_temp');

      /*Set TRANSDA=0 WHILE MERGING - ADDED BY SOURAV*/
      #$this->db->set('TRANSDA',0);
      #$this->db->update('acc_pay_details_temp');

      #echo $this->db->last_query(); echo '<br/><br/>';
      foreach ($data as $r) {
         $cond=array('EMPNO'=>$r->EMPNO);
         $setDA=array('DA'=>$r->DA);
         $setDAARR=array('DAARR'=>$r->DAARR);
         $setTRDAARR=array('TRDAARR'=>$r->TRDAARR);
        // $setTRANSDA=array('TRANSDA'=>$r->TRANSDA);
         $this->db->where($cond);
         $this->db->set($setDA); //set darr from above
         $this->db->set($setDAARR);
         $this->db->set($setTRDAARR);
      //   $this->db->set($setTRANSDA);
         $this->db->update('acc_pay_details_temp');
         //echo $this->db->last_query(); echo '<br/><br/>';//die();
      }

      $q='insert into acc_arrear(EMPNO,MON,YR,DA,DAARR,TRDAARR) ( select aat.EMPNO,aat.MON,aat.YR,aat.DA,aat.DAARR,aat.TRDAARR from acc_arrear_temp aat)';
      $this->db->query($q);

      #echo '<br/><br/>'; die();
   }

    function calculateDATDA($all){
      //echo $rate;die();
         /* fOR UPDATING DA ONLY IN acc_pay_details_temp*/
        #$q="update acc_pay_details_temp apdt set apdt.DA=round((apdt.BASIC+apdt.GRPAY)*?/100))";
        /* fOR UPDATING DA and TRANSDA IN acc_pay_details_temp*/
        $q="update acc_pay_details_temp apd set apd.DA=round((apd.BASIC+apd.GRPAY)*?/100),apd.TRANSDA=round(apd.TALLOW*?/100)";
        #$q="update acc_pay_details_temp apdt set apdt.DA=round((apdt.BASIC+apdt.GRPAY)*?/100))";
        if($query=$this->db->query($q,array($all['ndarate'],$all['transda']))){
        /// echo $this->db->last_query();die();
          return true;
        }
        else{
         return false;
        }

    }

    function updateArrearInTemp($cond,$set){
      $this->db->set($set);
      $this->db->where($cond);
      if($this->db->update('acc_arrear_temp')){
         return true;
      }
      else{
       return false;
      }
    }

    function updateDaTransDA($cond,$set){ #EMPNO., DA +TRANSDA
      $this->db->set($set);
      $this->db->where($cond);
      if($this->db->update('acc_pay_details_temp')){
         return true;
      }
      else{
       return false;
      }
    }

}
?>
