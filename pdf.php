<?php
//============================================================+
// File name   : example_038.php
// Begin       : 2008-09-15
// Last Update : 2013-05-14
//
// Description : Example 038 for TCPDF class
//               CID-0 CJK unembedded font
//
// Author: Nicola Asuni
//
// (c) Copyright:
//               Nicola Asuni
//               Tecnick.com LTD
//               www.tecnick.com
//               info@tecnick.com
//============================================================+

/**
 * Creates an example PDF TEST document using TCPDF
 * @package com.tecnick.tcpdf
 * @abstract TCPDF - Example: CID-0 CJK unembedded font
 * @author Nicola Asuni
 * @since 2008-09-15
 */

// Include the main TCPDF library (search for installation path).
//require_once('tcpdf/examples/tcpdf_include.php');
require_once('tcpdf/tcpdf.php');
define('IN_DOUCO', true);
require (dirname(__FILE__) . '/include/init.php');
$id = $check->is_number($_REQUEST['id']) ? $_REQUEST['id'] : '';
$sql="SELECT * FROM".$dou->table('course')." where id='$id'";
$query=$dou->query($sql);
$course=$dou->fetch_array($query);
$course['open_data']=date('Y年m月',$course['open_data']);
$sql="SELECT name FROM".$dou->table('school')." where id='$course[shid]'";
$query=$dou->query($sql);
$school=$dou->fetch_array($query);
//print_r($course);
// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Nicola Asuni');
$pdf->SetTitle('TCPDF Example 038');
$pdf->SetSubject('TCPDF Tutorial');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

// set default header data
// $pdf->SetHeaderData('PDF_HEADER_LOGO', PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 038', PDF_HEADER_STRING);

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
	require_once(dirname(__FILE__).'/lang/eng.php');
	$pdf->setLanguageArray($l);
}

// ---------------------------------------------------------

// set font
$pdf->SetFont('stsongstdlight', '', 15);
// add a page
$pdf->AddPage();



// set font
//$pdf->SetFont('stsongstdlight', '', 10);
;

$txt = "<div><p>$course[name]</p> <p>{$course[eng_name]}</p><div>$course[open_data]入学</div><div>课程代码：$course[code]</div><div>";
$pdf->WriteHTML($txt);

$txt = "<div><h2>入学要求</h2><div>$course[srequire]</div><div>";
$pdf->WriteHTML($txt);

// ---------------------------------------------------------

//Close and output PDF document
$pdf->Output('example_038.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+
