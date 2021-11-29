<?PHP
if ( ! defined('BASEPATH'))  exit('No direct script access allowed'); 

class Disease_model extends CI_Model
{
    public function __construct() 
    { 
        parent::__construct(); 
    } 
    
                function get_allergy_list()
		{
			$this->db->from('hc_allergy');
			$this->db->order_by('all_name');
			$query = $this->db->get();
                        $query_result = $query->result();
			return $query_result;

		}
                function get_kdisease_list()
		{
			$this->db->from('hc_known_disease');
			$this->db->order_by('dis_name');
			$query = $this->db->get();
                        $query_result = $query->result();
			return $query_result;

		}
                function get_dependent_list($id)
		{
			$query = $this->db->query("select sno,name from  emp_family_details where emp_no='".$id."'");
                        $query_result = $query->result();
                            if($query->num_rows() > 0)  
                            {
                                    return $query_result;
                            }
                            else
                                {
                                    return false;   
                            }	
                        

		}
                function update_emp($id,$all,$kdis)
                {
                    $myquery="update user_other_details set emp_allergy='".$all."',emp_disease='".$kdis."' WHERE id = '".$id."'" ;
                    $r = $this->db->query($myquery);
                    return $r;
                    
                }
                
                 function update_emp_dependent($id,$mem,$all,$kdis)
                {
                    $myquery="update emp_family_details set emp_dep_allergy='".$all."',emp_dep_disease='".$kdis."' WHERE emp_no = ".$id." AND sno = ".$mem;
                    $r = $this->db->query($myquery);
                    return $r;
                    
                }
                //--------------used to view allergy of employee when view button is clicked---------------
                function get_allergy_emp($pid)
                {
                    $this->db->select('emp_allergy'); 
                    $this->db->from('user_other_details');
                    $this->db->where('id',$pid);
              
                    $query = $this->db->get();
                        if($query->num_rows()>0)
                        {
                            return	$query->result_array();
                        }
                        else
                        {
                            return	FALSE;
                        }
                }
                //-----------------used to view 2 allergy  name  when choose patient
                function get_allergy_emp_second($pid)
                {
                    $this->db->select('emp_allergy'); 
                    $this->db->from('user_other_details');
                    $this->db->where('id',$pid);
                    $query = $this->db->get();
                        if($query->num_rows()>0)
                        {
                            $pp="";
                            $rr=$query->result_array();
                            $rc=count($rr);
                           
                            if($rc<2)
                            {
                                $m=$rc;
                            }
                            else {
                                $m=2;
                            }
                          
                            $rr1=explode(",", $rr[0]['emp_allergy']);
                            for($i=0;$i<$m;$i++)
                            {
                                $tt=$this->disease_model->find_allergy($rr1[$i]);
                                 if($tt!=null){
                                    $pp.= $tt->all_name.",";
                                 }
                            }
                            return $pp;
                           // return count($rr);
                        }
                        else
                        {
                            return	FALSE;
                        }
                }
                //--------------used to view disease of employee when view button is clicked---------------
                function get_disease_emp($pid)
                {
                    $this->db->select('emp_disease'); 
                $this->db->from('user_other_details');
                $this->db->where('id',$pid);
              
                $query = $this->db->get();
                        if($query->num_rows()>0)
                        {
                            return	$query->result_array();
                        }
                        else
                        {
                            return	FALSE;
                        }
                }
               //-----------------used to view 2 disease  name  when choose patient
                
                function get_disease_emp_second($pid)
                {
                    $this->db->select('emp_disease'); 
                $this->db->from('user_other_details');
                $this->db->where('id',$pid);
              
                $query = $this->db->get();
                        if($query->num_rows()>0)
                        {
                            $pp="";
                            $rr=$query->result_array();
                            $rc=count($rr);
                            if($rc<2)
                            {
                                $m=$rc;
                            }
                            else {
                                $m=2;
                            }
                            $rr1=explode(",", $rr[0]['emp_disease']);
                            for($i=0;$i<$m;$i++)
                            {
                                $tt=$this->disease_model->find_disease($rr1[$i]);
                                  if($tt!=null){
                                        $pp.= $tt->dis_name.",";
                                  }
                            }
                            return $pp;
                        }
                        else
                        {
                            return	FALSE;
                        }
                }
                
                //---------------------------
                function find_allergy($id)
                {
                    $this->db->select('all_name'); 
                    $this->db->from('hc_allergy');
                    $this->db->where('all_id',$id);

                    $query = $this->db->get();
                            if($query->num_rows()>0)
                            {
                                return	$query->row();
                            }
                            else
                            {
                                return	FALSE;
                            }    
                
                }
                function find_disease($id)
                {
                    $this->db->select('dis_name'); 
                    $this->db->from('hc_known_disease');
                    $this->db->where('dis_id',$id);

                    $query = $this->db->get();
                            if($query->num_rows()>0)
                            {
                                return	$query->row();
                            }
                            else
                            {
                                return	FALSE;
                            }    
                
                }
                //--------------used to view allergy of dependant when view button is clicked---------
                function get_allergy_emp_dep($pid,$prel)
                {
                    $this->db->select('emp_dep_allergy'); 
                    $this->db->from('emp_family_details');
                    $this->db->where('emp_no',$pid);
                    $this->db->where('sno',$prel);
              
                    $query = $this->db->get();
                        if($query->num_rows()>0)
                        {
                            return	$query->result_array();
                        }
                        else
                        {
                            return	FALSE;
                        }
                }
                //--------------used to view disease of dependant when view button is clicked---------
                function get_disease_emp_dep($pid,$prel)
                {
                    $this->db->select('emp_dep_disease'); 
                    $this->db->from('emp_family_details');
                    $this->db->where('emp_no',$pid);
                    $this->db->where('sno',$prel);
              
                    $query = $this->db->get();
                        if($query->num_rows()>0)
                        {
                            return	$query->result_array();
                        }
                        else
                        {
                            return	FALSE;
                        }
                }
                //-----------------used to view 2 allergy  name  when choose patient (dependant)
                function get_allergy_emp_dep_second($pid)
                {
                    $this->db->select('emp_dep_allergy'); 
                    $this->db->from('emp_family_details');
                    $this->db->where('emp_no',$pid);
                    $query = $this->db->get();
                    $y=$query->num_rows();
                        if($y>0)
                        {
                            $pp="";
                            $pq=array();
                            $rr=$query->result_array();
                            $rc=count($rr);
                            if($rc<2)
                            {
                                $m=$rc;
                            }
                            else {
                                $m=2;
                            }
                            for($i=0;$i<$rc;$i++)
                            {
                              $rr1=explode(",", $rr[$i]['emp_dep_allergy']);
                              if(count($rr1) > 0){
                              for($j=0;$j<count($rr1);$j++)
                                {
                                   if(isset($rr1[$j]))
                                        {                                            
                                        $tt=$this->disease_model->find_allergy($rr1[$j]);
                                        if(isset($tt->all_name))
                                        $pp.= $tt->all_name.",";
                                        }
                                }
                              }
                             
                            array_push($pq,$pp);
                            $pp="";
                            }
                            return $pq;
                            
                        }
                        else
                        {
                            return	FALSE;
                        }
                }
                //-----------------used to view 2 Disease  name  when choose patient (dependant)
                function get_disease_emp_dep_second($pid)
                {
                    $this->db->select('emp_dep_disease'); 
                    $this->db->from('emp_family_details');
                    $this->db->where('emp_no',$pid);
                    $query = $this->db->get();
                        if($query->num_rows()>0)
                        {
                             $pp="";
                            $pq=array();
                            $rr=$query->result_array();
                            $rc=count($rr);
                            
                            if($rc<2)
                            {
                                $m=$rc;
                            }
                            else {
                                $m=2;
                            }
                            for($i=0;$i<$rc;$i++)
                            {
                            for($j=0;$j<$m;$j++)
                            {
                               
                                $rr1=explode(",", $rr[$i]['emp_dep_disease']);
                                if(is_array($rr1)){
                                if(isset($rr1[$j])){
                                $tt=$this->disease_model->find_disease($rr1[$j]);
                                 if(isset($tt->dis_name) )
                                  $pp.= $tt->dis_name.",";
                                }
                                }
                            }
                            array_push($pq,$pp);
                             $pp="";
                            }
                            return $pq;
                        }
                        else
                        {
                            return	FALSE;
                        }
                }
                
}

?>