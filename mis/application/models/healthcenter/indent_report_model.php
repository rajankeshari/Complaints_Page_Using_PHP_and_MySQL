<?PHP

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Indent_report_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    function getAll_Indent(){
           
        $sql = "select a.* from hc_indent a order by a.indent_id desc";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return FALSE;
        }
        
    }
    
    function get_supplier_details($id){
           
        $sql = "select b.s_name,concat_ws(' ',b.s_address1,b.s_address2,b.s_address3,b.s_city)as saddress,b.s_phone_no from hc_indent a 
inner join hc_supplier b on a.s_id=b.s_id
where a.indent_id=?";
        $query = $this->db->query($sql,array($id));
        if ($query->num_rows() > 0) {
            return $query->row();
        } else {
            return FALSE;
        }
        
    }
    function get_purchase_details($id){
        $sql = "select a.* from hc_pur_order a where a.indent_id=?";
        $query = $this->db->query($sql,array($id));
        if ($query->num_rows() > 0) {
            return $query->row();
        } else {
            return FALSE;
        }
        
        
    }
    
    function get_indented_quantity_details($id){
        $sql = "select b.m_id,b.m_name,a.std_pkt,a.app_rate,a.ind_qty,a.tot_qty,a.app_cost,c.po_id from hc_indent_description a 
inner join hc_medicine b on b.m_id=a.m_id
inner join hc_pur_order c on c.indent_id=a.indent_id
where a.indent_id=? order by b.m_name";
        $query = $this->db->query($sql,array($id));
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return FALSE;
        }
        
    }
    
        function get_received_medi_qty($po,$mid){
        $sql = "select sum(a.mrec_qty)as rec_qty,group_concat(a.mrec_qty)as rqty_details,group_concat(a.invoice_no)as invoice_no,
a.mrp,a.rate_of_pur,a.amount from hc_medi_receive a where a.po_id=? and a.m_id=?";
        $query = $this->db->query($sql,array($po,$mid));
        if ($query->num_rows() > 0) {
            return $query->row();
        } else {
            return FALSE;
        }
        
    }
    
    function get_all_patient_datewise($dt){
        $sql = "select a.pid,a.prel, 
group_concat(concat(b.m_name,' [',a.mqty,'] ') SEPARATOR '</br> ') as medicine_name,
a.visit_no as visit_no,
a.pres_no as pres_no,
a.doc_id
from hc_patient a
inner join hc_medicine b on b.m_id=a.mid
where  DATE_FORMAT(a.visit_date ,'%Y-%m-%d')=?
group by a.pid,a.prel,a.visit_no,a.pres_no;";
        $query = $this->db->query($sql,array($dt));
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return FALSE;
        }
        
    }
        function get_all_patient_datewise_dues($dt){
        $sql = "select a.pid,a.prel, 
group_concat(concat(b.m_name,' [',a.mqty,'] ') SEPARATOR '</br> ') as medicine_name,
a.visit_no as visit_no,
a.pres_no as pres_no,
a.doc_id
from hc_patient_med_dues a
inner join hc_medicine b on b.m_id=a.mid
where  DATE_FORMAT(a.visit_date ,'%Y-%m-%d')=?
group by a.pid,a.prel,a.visit_no,a.pres_no;";
        $query = $this->db->query($sql,array($dt));
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return FALSE;
        }
        
    }
    
    function get_all_patient_name($pid){
        $sql = "select concat_ws(' ',a.first_name,a.middle_name,a.last_name)as pname from user_details a where a.id=?";
        $query = $this->db->query($sql,array($pid));
        if ($query->num_rows() > 0) {
            return $query->row()->pname;
        } else {
            return FALSE;
        }
        
    }
    function get_patient_relation($pid,$slno){
        $sql = "select a.relationship from emp_family_details a where a.emp_no=? and a.sno=?";
        $query = $this->db->query($sql,array($pid,$slno));
        if ($query->num_rows() > 0) {
            return $query->row()->relationship;
        } else {
            return FALSE;
        }
        
    }
    
}

?>