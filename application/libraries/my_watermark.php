<?php  
if ( ! defined('BASEPATH')) exit('No direct script access allowed');  
  
class My_watermark {  
    public function My_watermark() {  

        require_once('fpdf/fpdf.php');
        require_once('fpdi/fpdi.php');
        require_once("pdfwatermarker/pdfwatermark.php");
        require_once("pdfwatermarker/pdfwatermarker.php");
        		
    }  
}  