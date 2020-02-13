<?php
include_once'connectdb.php';

$id=$_POST['pidd'];
$sql="delete tbl_invoice, tbl_invoice_details from tbl_invoice inner join tbl_invoice_details on tbl_invoice.invoice_id = tbl_invoice_details.invoice_id where tbl_invoice.invoice_id=$id";

// $sql="delete from tbl_product where pid=$id";
$delete=$pdo->prepare($sql);

if($delete -> execute()) {
    
} else {
    echo "Error in Deleting";
}


?>