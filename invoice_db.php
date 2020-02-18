<?php
require('tfpdf/tfpdf.php');
include_once'connectdb.php';

$id=$_GET['id'];

$select=$pdo->prepare("select * from tbl_invoice where invoice_id=$id");
$select->execute();

$row=$select->fetch(PDO::FETCH_OBJ);






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
$pdf->Cell(40, 5, 'Invoice number: '.$row->invoice_id,0,1,'');

$pdf->Cell(150, 5, 'Address: postcode city',0,0,'');
$pdf->Cell(40, 5, 'Date: '.$row->order_date,0,1,'');
$pdf->Cell(110, 5, 'Phone number: ___-__-__',0,1,'');
$pdf->Cell(110, 5, 'Address: country',0,0,'');




//line(x1,y1,x2,y2)

$pdf->Line(5, 5,205,5);
$pdf->Line(5, 50,205,50);


$pdf->Ln(20); // line break
$pdf->SetFont('Arial','',14);
$pdf->Cell(20, 10, 'Bill To: ',0,0,'');
$pdf->SetFont('Arial','BI',14);
$pdf->Cell(20, 10, $row->customer_name ,0,1,'');
$pdf->Cell(20, 5, '',0,1,'');


$pdf->SetFont('Arial','B',12);
$pdf->SetFillColor(230,230,230);
$pdf->Cell(100, 8, 'Product',1,0,'C',true);
$pdf->Cell(20, 8, 'Quantity',1,0,'C',true);
$pdf->Cell(30, 8, 'Price',1,0,'C',true);
$pdf->Cell(40, 8, 'Total',1,1,'C',true);




// P R O D U C T S


$select=$pdo->prepare("select * from tbl_invoice_details where invoice_id=$id");
$select->execute();

while($item=$select->fetch(PDO::FETCH_OBJ)) {
$pdf->SetFont('Arial','B',10);
$pdf->Cell(100, 8, $item->product_name,1,0,'L');
$pdf->Cell(20, 8, $item->qty,1,0,'C');
$pdf->Cell(30, 8, $item->price,1,0,'C');
$pdf->Cell(40, 8, $item->price*$item->qty,1,1,'C');  
}





$pdf->SetFont('Arial','B',10);
$pdf->Cell(100, 8, '',0,0,'L');
$pdf->Cell(20, 8, '',0,0,'C');
$pdf->Cell(30, 8, 'Subtotal',1,0,'C', true);
$pdf->Cell(40, 8, $row->subtotal ,1,1,'C');


$pdf->SetFont('Arial','B',10);
$pdf->Cell(100, 8, '',0,0,'L');
$pdf->Cell(20, 8, '',0,0,'C');
$pdf->Cell(30, 8, 'Tax',1,0,'C', true);
$pdf->Cell(40, 8, $row->tax,1,1,'C');

$pdf->SetFont('Arial','B',10);
$pdf->Cell(100, 8, '',0,0,'L');
$pdf->Cell(20, 8, '',0,0,'C');
$pdf->Cell(30, 8, 'Discount',1,0,'C', true);
$pdf->Cell(40, 8, $row->discount,1,1,'C');

$pdf->SetFont('Arial','B',12);
$pdf->Cell(100, 8, '',0,0,'L');
$pdf->Cell(20, 8, '',0,0,'C');
$pdf->Cell(30, 8, 'Grand Total',1,0,'C', true);
$pdf->Cell(40, 8, $row->total,1,1,'C');

$pdf->SetFont('Arial','B',10);
$pdf->Cell(100, 8, '',0,0,'L');
$pdf->Cell(20, 8, '',0,0,'C');
$pdf->Cell(30, 8, 'Paid',1,0,'C', true);
$pdf->Cell(40, 8, $row->paid,1,1,'C');

$pdf->SetFont('Arial','B',10);
$pdf->Cell(100, 8, '',0,0,'L');
$pdf->Cell(20, 8, '',0,0,'C');
$pdf->Cell(30, 8, 'Due',1,0,'C', true);
$pdf->Cell(40, 8, $row->due,1,1,'C');

$pdf->SetFont('Arial','B',10);
$pdf->Cell(100, 8, '',0,0,'L');
$pdf->Cell(20, 8, '',0,0,'C');
$pdf->Cell(30, 8, 'Payment Type',1,0,'C', true);
$pdf->Cell(40, 8, $row->payment_type,1,1,'C');


$pdf->Cell(20, 8, '',0,1,'');
$pdf->SetFont('Arial','B',10);
$pdf->Cell(32, 8, 'Important Notice: ',0,0,'',true);
$pdf->SetFont('Arial','',8);
$pdf->Cell(148, 8, 'No iteme will be replace or reufunded if you don\'t have invoice with you. ',0,0,'');


$pdf->Output();


?>