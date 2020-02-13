<?php
require('tfpdf/tfpdf.php');

$pdf = new tFPDF('P', 'mm', 'A4'); // Portret czy Landscape, milimetry, format
$pdf->AddPage();




/*

// Add a Unicode font (uses UTF-8)
$pdf->AddFont('DejaVu','','DejaVuSansCondensed.ttf',true);
$pdf->SetFont('DejaVu','',14);

// Load a UTF-8 string from a file and print it
$txt = file_get_contents('./HelloWorld.txt');
$pdf->Write(8,$txt);

// Select a standard font (uses windows-1252)
$pdf->SetFont('Arial','',14);
$pdf->Ln(10);
$pdf->Write(10,'The file size of this PDF is only 13 KB.');

*/

// $pdf->SetFillColor(123,255,234); //r,g,b
$pdf->SetFont('Arial','',16);

//Cell(w,h,'txt',border,ln,align,fill,link)
$pdf->Cell(150, 10, 'Company',0,0,'');



$pdf->SetFont('Arial','B',14);
$pdf->Cell(40, 14, 'Invoice',0,1,'');

$pdf->SetFont('Arial','',8);
$pdf->Cell(150, 5, 'Address: street name',0,0,'');
$pdf->Cell(40, 5, 'Invoice number: 1234',0,1,'');

$pdf->Cell(150, 5, 'Address: postcode city',0,0,'');
$pdf->Cell(40, 5, 'Date: __/__/____',0,1,'');
$pdf->Cell(110, 5, 'Phone number: ___-__-__',0,1,'');
$pdf->Cell(110, 5, 'Address: country',0,0,'');




//line(x1,y1,x2,y2)

$pdf->Line(5, 5,205,5);
$pdf->Line(5, 50,205,50);


$pdf->Ln(20); // line break
$pdf->SetFont('Arial','',14);
$pdf->Cell(20, 10, 'Bill To: ',0,0,'');
$pdf->SetFont('Arial','BI',14);
$pdf->Cell(20, 10, '_Customer name_',0,1,'');
$pdf->Cell(20, 5, '',0,1,'');


$pdf->SetFont('Arial','B',12);
$pdf->SetFillColor(230,230,230);
$pdf->Cell(100, 8, 'Product',1,0,'C',true);
$pdf->Cell(20, 8, 'Quantity',1,0,'C',true);
$pdf->Cell(30, 8, 'Price',1,0,'C',true);
$pdf->Cell(40, 8, 'Total',1,1,'C',true);

$pdf->SetFont('Arial','B',10);
$pdf->Cell(100, 8, 'iPhone',1,0,'L');
$pdf->Cell(20, 8, '1',1,0,'C');
$pdf->Cell(30, 8, '800',1,0,'C');
$pdf->Cell(40, 8, '800',1,1,'C');

$pdf->SetFont('Arial','B',10);
$pdf->Cell(100, 8, 'iPhone',1,0,'L');
$pdf->Cell(20, 8, '1',1,0,'C');
$pdf->Cell(30, 8, '800',1,0,'C');
$pdf->Cell(40, 8, '800',1,1,'C');

$pdf->SetFont('Arial','B',10);
$pdf->Cell(100, 8, 'iPhone',1,0,'L');
$pdf->Cell(20, 8, '1',1,0,'C');
$pdf->Cell(30, 8, '800',1,0,'C');
$pdf->Cell(40, 8, '800',1,1,'C');



$pdf->Output();


?>