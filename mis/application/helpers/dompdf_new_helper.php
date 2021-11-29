<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
 require_once("dompdf_new/autoload.inc.php");
 use Dompdf\Dompdf;
 use Dompdf\Options;
function pdf_create($html, $filename='',  $paper='', $stream=TRUE) 
{
  
	ini_set("max_execution_time","-1");
	ini_set('memory_limit', '-1');
	
	
	
    $dompdf = new Dompdf();
  
	if($paper == 'L'){
		$dompdf->set_paper("a4", "landscape" ); 
	}
    $dompdf->load_html($html,'UTF-8');
    $dompdf->render();
    if ($stream) {
        $dompdf->stream($filename);
    } else {
        return $dompdf->output();
    }
}
?>