<?php
require('tfpdf/tfpdf.php');
include_once'connectdb.php';



$id=$_GET['id'];
$select=$pdo->prepare("select * from tbl_invoice where invoice_id=$id");
$select->execute();
$row=$select->fetch(PDO::FETCH_OBJ);

$pdf = new tFPDF('P', 'mm', array(80,200)); 
$pdf->AddPage();


$pdf->SetFont('Arial','B',16);
$pdf->Cell(60, 8, 'Company name',0,1,'C');
$pdf->SetFont('Arial','',8);
$pdf->Cell(60, 5, 'Address: street name',0,1,'C');
$pdf->Cell(60, 5, 'Address: postcode city',0,1,'C');
$pdf->Cell(60, 5, 'Phone number: ___-__-__',0,1,'C');
$pdf->Cell(60, 5, 'Address: country',0,1,'C');

$pdf->Line(7,40,72,40);
$pdf->Ln(5);


$pdf->SetFont('Courier','',8);
$pdf->Cell(20, 4, 'Bill To: ',0,0,'');
$pdf->SetFont('Arial','BI',8);
$pdf->Cell(60, 4, $row->customer_name ,0,1,'');


$pdf->SetFont('Courier','',8);
$pdf->Cell(40, 4,'Invoice number: ',0,0,'');
$pdf->SetFont('Arial','',8);
$pdf->Cell(40, 4, $row->invoice_id ,0,1,'');

$pdf->SetFont('Courier','',8);
$pdf->Cell(40, 4, 'Date: ',0,0,'');
$pdf->SetFont('Arial','',8);
$pdf->Cell(40, 4, $row->order_date,0,1,'');

$pdf->Ln(5);



$pdf->setX(7);
$pdf->SetFont('courier','B',8);
$pdf->Cell(34, 5, 'Product',1,0,'C');
$pdf->Cell(7, 5, 'QTY',1,0,'C');
$pdf->Cell(12, 5, 'PRC',1,0,'C');
$pdf->Cell(12, 5, 'TOTAL',1,1,'C');


$select=$pdo->prepare("select * from tbl_invoice_details where invoice_id=$id");
$select->execute();

while($item=$select->fetch(PDO::FETCH_OBJ)) {
$pdf->setX(7);
$pdf->SetFont('Helvetica','B',8);
$pdf->Cell(34, 5, $item->product_name,1,0,'L');
$pdf->Cell(7, 5, $item->qty,1,0,'C');
$pdf->Cell(12, 5, $item->price,1,0,'C');
$pdf->Cell(12, 5, $item->price*$item->qty,1,1,'C');  
}




$pdf->setX(7);
$pdf->SetFont('courier','',8);
$pdf->Cell(20, 5, '',0,0,'L');
$pdf->Cell(25, 5, 'SUBTOTAL',1,0,'C');
$pdf->SetFont('Arial','B',8);
$pdf->Cell(20, 5, $row->subtotal,1,1,'C');

$pdf->setX(7);
$pdf->SetFont('courier','',8);
$pdf->Cell(20, 5, '',0,0,'L');

$pdf->Cell(25, 5, 'TAX(5%)',1,0,'C');
$pdf->SetFont('Arial','B',8);
$pdf->Cell(20, 5, $row->tax,1,1,'C');

$pdf->setX(7);
$pdf->SetFont('courier','',8);
$pdf->Cell(20, 5, '',0,0,'L');
$pdf->Cell(25, 5, 'DISCOUNT',1,0,'C');
$pdf->SetFont('Arial','B',8);
$pdf->Cell(20, 5, $row->discount,1,1,'C');

$pdf->setX(7);
$pdf->SetFont('courier','B',8);
$pdf->Cell(20, 5, '',0,0,'L');
$pdf->Cell(25, 8, 'TOTAL',1,0,'C');
$pdf->SetFont('Arial','B',8);
$pdf->Cell(20, 8, $row->total ,1,1,'C');

$pdf->setX(7);
$pdf->SetFont('courier','',8);
$pdf->Cell(20, 5, '',0,0,'L');
$pdf->Cell(25, 5, 'PAID',1,0,'C');
$pdf->SetFont('Arial','B',8);
$pdf->Cell(20, 5, $row->paid ,1,1,'C');

$pdf->setX(7);
$pdf->SetFont('courier','',8);
$pdf->Cell(20, 5, '',0,0,'L');;
$pdf->Cell(25, 5, 'DUE',1,0,'C');
$pdf->SetFont('Arial','B',8);
$pdf->Cell(20, 5, $row->due ,1,1,'C');

$pdf->setX(7);
$pdf->SetFont('courier','',8);
$pdf->Cell(20, 5, '',0,0,'L');
$pdf->Cell(25, 5, 'PAYMENT TYPE',1,0,'C');
$pdf->SetFont('Arial','B',8);
$pdf->Cell(20, 5, $row->payment_type ,1,1,'C');


$pdf->Ln(5);
$pdf->SetFont('courier','',8);
$pdf->Cell(25, 5, 'Important Notice: ',0,1,'');
$pdf->SetFont('Arial','',8);
$pdf->setX(7);
$pdf->Cell(70, 5, 'No iteme will be replace or reufunded if you don\'t ',0,1,'');
$pdf->setX(7);
$pdf->Cell(70, 5, 'have invoice with you. ',0,0,'');



$pdf->Output();
?>