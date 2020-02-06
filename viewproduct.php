<?php
include_once'connectdb.php';
session_start();
if ($_SESSION['useremail']==""  OR $_SESSION['role']=='User' ) {
    header('location:index.php');
}
include_once'header.php';
?>


<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            View Product
            <small></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
            <li class="active">Here</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content container-fluid">

        <!--------------------------
        | Your Page Content Here |
        -------------------------->
        <div class="box box-warning">
            <div class="box-header with-border">
                <a href="productlist.php" class="btn btn-primary" role="button">Back To Product List</a>
            </div>
            <!-- /.box-header -->
            <!-- form start -->

            <div class="box-body">


<?php
                
$id=$_GET['id'];
$select = $pdo -> prepare("select * from tbl_product where pid=$id");
$select->execute();
                    
while($row=$select->fetch(PDO::FETCH_OBJ)) {
                        echo '
      <div class="col-md-6">
          <ul class="list-group">
          <center><p class="list-group-item list-group-item-success"><b>Product Detail</b></p></center>
          
          
  <li class="list-group-item">
    ID
    <span class="badge">'.$row->pid.'</span>
  </li>
    <li class="list-group-item d-flex justify-content-between align-items-center">
    Product Name
    <span class="label label-info pull-right">'.$row->pname.'</span>
  </li>
    <li class="list-group-item d-flex justify-content-between align-items-center">
    Product Category
    <span class="label label-primary pull-right">'.$row->pcategory.'</span>
  </li>
      <li class="list-group-item d-flex justify-content-between align-items-center">
    Purchase Price
    <span class="label label-warning pull-right">'.$row->purchaseprice.'</span>
  </li>
      <li class="list-group-item d-flex justify-content-between align-items-center">
    Sale Price
    <span class="label label-warning pull-right">'.$row->saleprice.'</span>
  </li>
  
      <li class="list-group-item d-flex justify-content-between align-items-center">
    Product Profit
    <span class="label label-success pull-right">'.($row->saleprice - $row->purchaseprice).'</span>
  </li>
  
      <li class="list-group-item d-flex justify-content-between align-items-center">
    Stock
    <span class="label label-danger pull-right">'.$row->pstock.'</span>
  </li>
      <li class="list-group-item d-flex justify-content-between align-items-center">
    <b>Description: </b>
    <span>'.$row->pdescription.'</span>
  </li>


</ul>
      </div>  
      
      
         
      
            <div class="col-md-6">
          <ul class="list-group">
          <center><p class="list-group-item list-group-item-success"><b>Product Image</b></p></center>
            </li>
      <li class="list-group-item d-flex justify-content-between align-items-center">
    <span><img src="productimages/'.$row->pimage.'" class="img-responsive"></span>
  </li>
</ul>
      </div>                          
                        ';
                    }
                    
                    ?>


            </div>
        </div>
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->




<?php
include_once'footer.php';
?>
