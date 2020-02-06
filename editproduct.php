<?php
include_once'connectdb.php';
session_start();
include_once'header.php';

$id=$_GET['id'];
$select = $pdo->prepare("select * from tbl_product where pid=$id");
$select -> execute();
$row=$select->fetch(PDO::FETCH_ASSOC);


// po otrzymaniu informacji z db mozna je zapisywac w innych zmiennych! !!!!!
// EDIT PRODUCT -> FORM
$id_db=$row['pid'];
//echo $id_db;
$productname_db=$row['pname'];
$category_db =$row['pcategory'];
$purchaseprice_db =$row['purchaseprice'];
$saleprice_db =$row['saleprice'];
$stock_db=$row['pstock'];
$description_db=$row['pdescription'];
$productimage_db =$row['pimage'];
// print_r($row);

// UPDATE PRODUCT
if(isset($_POST['btnupdate'])) {
    $productname_txt =$_POST['txtpname'];
    $category_txt =$_POST['txtselect_option'];
    $purchaseprice_txt =$_POST['txtpprice'];
    $saleprice_txt =$_POST['txtsaleprice'];
    $stock_txt =$_POST['txtstock'];
    $description_txt =$_POST['txtdescription'];
    $f_name = $_FILES['myfile']['name'];
    
    if(!empty($f_name)) {
        // ze zmiana zdjecia - kopiuj z add product, zamien insert na update
$f_tmp = $_FILES['myfile']['tmp_name'];  
$f_size = $_FILES['myfile']['size'];
      
$f_extension =explode('.', $f_name); 
$f_extension = strtolower(end($f_extension)); // wywolaj ostatni z explode, czyli extention, male litery
    
$f_newfile = uniqid().'.'.$f_extension; // create new file in case of dubbles
$store = "productimages/".$f_newfile;  
    
    
if($f_extension=='jpg' || $f_extension=='jpeg' || $f_extension == 'png' || $f_extension=='gif' || $f_extension == 'svg') {

    if($f_size >=1000000) { //spr rozmiar
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
$f_newfile;
           if(!isset($error)) {
  $update=$pdo->prepare("update tbl_product set pname=:pname,pcategory=:pcategory,purchaseprice=:purchaseprice,saleprice=:saleprice,pstock=:pstock,pdescription=:pdescription,pimage=:pimage where pid=$id");
        $update -> bindParam(':pname', $productname_txt);
        $update -> bindParam(':pcategory', $category_txt);
        $update -> bindParam(':purchaseprice', $purchaseprice_txt);
        $update -> bindParam(':saleprice', $saleprice_txt);
        $update -> bindParam(':pstock', $stock_txt);
        $update -> bindParam(':pdescription', $description_txt);
        $update -> bindParam(':pimage', $f_newfile);
        
        
        if($update -> execute()){
            echo '<script type="text/javascript">
        jQuery(
        function validation(){
        swal({
  title: "Success!",
  text: "Product Updated!",
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
    
    
    
    
    
    
    
    
    } else {
        // bez zmiany zdjecia
        $update=$pdo->prepare("update tbl_product set pname=:pname,pcategory=:pcategory,purchaseprice=:purchaseprice,saleprice=:saleprice,pstock=:pstock,pdescription=:pdescription,pimage=:pimage where pid=$id");
        $update -> bindParam(':pname', $productname_txt);
        $update -> bindParam(':pcategory', $category_txt);
        $update -> bindParam(':purchaseprice', $purchaseprice_txt);
        $update -> bindParam(':saleprice', $saleprice_txt);
        $update -> bindParam(':pstock', $stock_txt);
        $update -> bindParam(':pdescription', $description_txt);
        $update -> bindParam(':pimage', $productimage_db);
        
        if($update->execute()) {

          echo '<script type="text/javascript">
        jQuery(
        function validation(){
        swal({
  title: "Success!",
  text: "Product updated!",
  icon: "success",
  button: "Ok!",
});
        });     
        </script>';        
    } else {
 $error = '<script type="text/javascript">
        jQuery(
        function validation(){
        swal({
  title: "Error!",
  text: "Update failed!",
  icon: "error",
  button: "Ok!",
});
        });     
        </script>';
        echo $error;
        }   
    }
} 



$select = $pdo->prepare("select * from tbl_product where pid=$id");
$select -> execute();
$row=$select->fetch(PDO::FETCH_ASSOC);


// wyswietl w formularzu zaktualizowane dane
$id_db=$row['pid'];
$productname_db=$row['pname'];
$category_db =$row['pcategory'];
$purchaseprice_db =$row['purchaseprice'];
$saleprice_db =$row['saleprice'];
$stock_db=$row['pstock'];
$description_db=$row['pdescription'];
$productimage_db =$row['pimage'];

?>




<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Edit Product
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
                <h3 class="box-title"><a href="productlist.php" class="btn btn-primary" role="button">Back To Product List</a></h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form action="" method="post" name="formproduct" enctype="multipart/form-data">
                <div class="box-body">

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Product Name</label>
                            <input type="text" class="form-control" name="txtpname" value="<?php echo $productname_db?>">
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
                                <!-- TO JEST POKOPANE, ALE MA SENS. Jezeli kategoria tbl_category rowna jest tej z bazy danych produktu, to moze zostac oznaczona jako wybrana a php w tym miejscu jest zakonczony. -->
                                <option <?php
                                    if($row['category']==$category_db) {?> selected="selected" <?php } ?>>

                                    <?php echo $row['category'];?></option>
                                <?php
                                } 
                                ?>

                            </select>
                        </div>
                        <div class="form-group">
                            <label>Purchase price</label>
                            <input type="number" min="1" step="1" class="form-control" name="txtpprice" value="<?php echo $purchaseprice_db;?>">
                        </div>
                        <div class="form-group">
                            <label>Sale price</label>
                            <input type="number" min="1" step="1" class="form-control" name="txtsaleprice" value="<?php echo $saleprice_db;?>">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Stock</label>
                            <input type="number" min="1" step="1" class="form-control" name="txtstock" value="<?php echo $stock_db;?>">
                        </div>
                        <div class="form-group">
                            <!-- Tu jest inaczej - value jest wewnatrz textarea -->
                            <label>Description</label>
                            <textarea class="form-control" name="txtdescription" placeholder="Enter description" rows="4"><?php echo $description_db;?></textarea>
                        </div>

                        <div class="form-group">
                            <label>Product Image</label>
                            <!-- Tu jest inaczej - php jest w zrodle zdjecia -->
                            <img src="productimages/<?php echo $productimage_db; ?>" class="img-responsive" width="50px" height="50px">
                            <input type="file" class="input-group" name="myfile">
                            <p>Upload image.</p>
                        </div>
                    </div>


                </div>
                <div class="box-footer">
                    <button type="submit" class="btn btn-warning" name="btnupdate">Update Product</button>
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
