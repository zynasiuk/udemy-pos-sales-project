<?php
include_once'connectdb.php';
session_start();
include_once'header.php';

if(isset($_POST['btnaddproduct'])) {
    $productname=$_POST['txtpname'];
    $category=$_POST['txtselect_option'];
    $purchaseprice=$_POST['txtpprice'];
    $saleprice=$_POST['txtsaleprice'];
    $stock=$_POST['txtstock'];
    $description=$_POST['txtdescription'];
    
    // file image
    $f_name = $_FILES['myfile']['name'];
$f_tmp = $_FILES['myfile']['tmp_name'];  
$f_size = $_FILES['myfile']['size'];
      
$f_extension =explode('.', $f_name); //explode name and extention     
$f_extension = strtolower(end($f_extension)); // wywolaj ostatni z explode, czyli extention, male litery
    
$f_newfile = uniqid().'.'.$f_extension; // create new file in case of dubbles
$store = "productimages/".$f_newfile;  
    
    
if($f_extension=='jpg' || $f_extension=='jpeg' || $f_extension == 'png' || $f_extension=='gif' || $f_extension == 'svg') {
    echo '<br>extention ok <br>';
    if($f_size >=1000000) { //spr rowmiar
           $error = '<script type="text/javascript">
        jQuery(
        function validation(){
        swal({
  title: "Your file is to big!",
  text: "Max size of image is 1MB",
  icon: "warning",
  button: "Ok!",
});
        });     
        </script>';
        echo $error;
        
    } else {
        if(move_uploaded_file($f_tmp, $store)){
$productimage=$f_newfile;
        } 
    }
} else {
      $error = '<script type="text/javascript">
        jQuery(
        function validation(){
        swal({
  title: "Wrong file type!",
  text: "Only jpg, jepg, png, gif or svg",
  icon: "error",
  button: "Ok!",
});
        });     
        </script>';
        echo $error;
}
      
    if(!isset($error)) {
        $insert=$pdo->prepare("insert into tbl_product(pname,pcategory,purchaseprice,saleprice,pstock,pdescription,pimage) values(:pname,:pcategory,:purchaseprice,:saleprice,:pstock,:pdescription,:pimage)");
        
        $insert -> bindParam(':pname', $productname );
        $insert -> bindParam(':pcategory', $category );
        $insert -> bindParam(':purchaseprice', $purchaseprice);
        $insert -> bindParam(':saleprice', $saleprice);
        $insert -> bindParam(':pstock', $stock);
        $insert -> bindParam(':pdescription', $description);
        $insert -> bindParam(':pimage', $productimage);
        
        
        if($insert -> execute()){
            echo '<script type="text/javascript">
        jQuery(
        function validation(){
        swal({
  title: "Success!",
  text: "Product Added",
  icon: "success",
  button: "Ok!",
});
        });     
        </script>';
        }else{
           echo '<script type="text/javascript">
        jQuery(
        function validation(){
        swal({
  title: "Error",
  text: "Something went wrong...",
  icon: "error",
  button: "Ok!",
});
        });     
        </script>';
        }
    }
}
?>




<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Add Product
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
        <!-- general form elements -->
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">
                    <a href="productlist.php" class="btn btn-primary" role="button">Back To Product List</a>

                </h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form action="" method="post" name="formproduct" enctype="multipart/form-data">
                <div class="box-body">

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Product Name</label>
                            <input type="text" class="form-control" name="txtpname" placeholder="Enter Product Name">
                        </div>
                        <div class="form-group">
                            <label>Category</label>
                            <select class="form-control" name="txtselect_option" required>
                                <option value="" disabled selected>Select Category</option>

                                <?php
                                $select = $pdo ->prepare("select*from tbl_category order by catid desc");
                                $select->execute();
                                while($row=$select->fetch(PDO::FETCH_ASSOC)) {
                                    extract($row);                                
                                ?>
                                <option><?php echo $row['category'];?></option>
                                <?php
                                } 
                                ?>

                            </select>
                        </div>
                        <div class="form-group">
                            <label>Purchase price</label>
                            <input type="number" min="1" step="1" class="form-control" name="txtpprice" placeholder="Enter price">
                        </div>
                        <div class="form-group">
                            <label>Sale price</label>
                            <input type="number" min="1" step="1" class="form-control" name="txtsaleprice" placeholder="Enter price">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Stock</label>
                            <input type="number" min="1" step="1" class="form-control" name="txtstock" placeholder="Enter quantity">
                        </div>
                        <div class="form-group">
                            <label>Description</label>
                            <textarea class="form-control" name="txtdescription" placeholder="Enter description" rows="4"></textarea>
                        </div>

                        <div class="form-group">
                            <label>Product Image</label>
                            <input type="file" class="input-group" name="myfile" required>
                            <p>Upload image.</p>
                        </div>
                    </div>


                </div>
                <div class="box-footer">
                    <button type="submit" class="btn btn-info" name="btnaddproduct">Add Product</button>
                </div>
            </form>
        </div>
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->




<?php
include_once'footer.php';
?>
