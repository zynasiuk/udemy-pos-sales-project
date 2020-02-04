<?php
include_once'header.php';
include_once'connectdb.php';
session_start();

// jezeli kliknieto btn save
if(isset($_POST['btnsave'])) {
    $category=$_POST['txtcategory'];
    // wyslanie pustego formularza powoduje error
    if(empty($category)) {
        $error='<script type="text/javascript">
        jQuery(
        function validation(){
        swal({
  title: "Empty Feild!",
  text: "Fill Feild",
  icon: "error",
  button: "Ok!",
});
        });     
        </script>';
        echo $error;
    }    
    // jezeli btn save nie jest pusty i nie niesie z soba bledu
    if(!isset($error)) {
        $insert=$pdo->prepare("insert into tbl_category(category) values(:category)");
        $insert->bindParam(':category', $category);
        if($insert->execute()) {
            echo '<script type="text/javascript">
        jQuery(
        function validation(){
        swal({
  title: "Success!",
  text: "Category Added!",
  icon: "success",
  button: "Ok!",
});
        });     
        </script>';
        }
    }
}
    // jezeli edytujesz i musisz zapisac zmiany
if(isset($_POST['btnupdate'])) {
      $category=$_POST['txtcategory'];
    $id =$_POST['txtid'];
    if(empty($category)) {
        $errorupdate = '<script type="text/javascript">
        jQuery(
        function validation(){
        swal({
  title: "Error!",
  text: "Feild is empty! Enter category",
  icon: "error",
  button: "Ok!",
});
        });     
        </script>';
         echo $errorupdate;
    }   
    if(!isset($errorupdate)) {
        $update=$pdo->prepare("update tbl_category set category=:category where catid=".$id);
        $update->bindParam(':category', $category);
        if($update->execute()) {
              echo '<script type="text/javascript">
        jQuery(
        function validation(){
        swal({
  title: "Updated!",
  text: "Category Updated!",
  icon: "success",
  button: "Ok!",
});
        });     
        </script>';
        } else {
           echo '<script type="text/javascript">
        jQuery(
        function validation(){
        swal({
  title: "Error!",
  text: "Your category is not updated.",
  icon: "error",
  button: "Ok!",
});
        });     
        </script>';
        }
    }    
} // koniec btnupdate

// delete btn
if(isset($_POST['btndelete'])) {
    $delete = $pdo -> prepare("delete from tbl_category where catid=".$_POST['btndelete']);

    if($delete ->execute()){
        echo '<script type="text/javascript">
        jQuery(
        function validation(){
        swal({
  title: "Deleted!",
  text: "Category Deleted!",
  icon: "success",
  button: "Ok!",
});
        });     
        </script>';
    } else{
        echo '<script type="text/javascript">
        jQuery(
        function validation(){
        swal({
  title: "Error!",
  text: "Category is Not Deleted!",
  icon: "error",
  button: "Ok!",
});
        });     
        </script>';
    }
}

?>




<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Category
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
        <div class="box box-warning">
            <div class="box-header with-border">
                <h3 class="box-title">Category Form</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
         
                <div class="box-body">
   <form role="form" action="" method="post">
              <?php
       // BTN EDIT
       if(isset($_POST['btnedit'])) {
           $select=$pdo->prepare("select * from tbl_category where catid=".$_POST['btnedit'] );
           $select->execute();
           if($select) {
               $row=$select->fetch(PDO::FETCH_OBJ);
               echo '
                           <div class="col-md-4">
                        <div class="form-group">
                            <label>Category</label>
                            <input type="text" class="form-control" name="txtcategory" value="'.$row->category.'" placeholder="Enter Category">
                             <input type="hidden" class="form-control" name="txtid" value="'.$row->catid.'" placeholder="Enter Category">
                        </div>


                        <div class="box-footer">
                            <button type="submit" class="btn btn-info" name="btnupdate">Update</button>
                        </div>
                    </div>';
           }
           
       } else {
           // BTN SAVE 
           echo '
                           <div class="col-md-4">
                        <div class="form-group">
                            <label>Category</label>
                            <input type="text" class="form-control" name="txtcategory" placeholder="Enter Category">
                        </div>

                        <div class="box-footer">
                            <button type="submit" class="btn btn-warning" name="btnsave">Save</button>
                        </div>
                    </div>';
       }
       ?>
                    
    
                    <div class="col-md-8">
                        <table id="tablecategory" class="table table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Category</th>
                                    <th>Edit</th>
                                    <th>Delete</th>

                                </tr>
                            </thead>
                            <tbody>


                                <?php
                      $select=$pdo->prepare("select * from tbl_category order by catid desc");
                                    $select->execute();
                                    while($row=$select->fetch(PDO::FETCH_OBJ)) {
                                        echo '<tr>
                                    <td>'.$row->catid.'</td>
                                    <td>'.$row->category.'</td>
                                    <td><button type="submit" class="btn btn-success" name="btnedit" value="'.$row->catid.'">Edit</button></td>
                                    <td><button type="submit" class="btn btn-danger" name="btndelete" value="'.$row->catid.'">Delete</button></td>
                                </tr>
                                        ';
                                    }                    
                      ?>


                            </tbody>
                        </table>
                    </div>
                      </form>
                </div>
                
                <!-- /.box-body -->       
        </div>
        <!-- /.box -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<!-- data tables -->
<script>
$(document).ready( function () {
    $('#tablecategory').DataTable();
} );
</script>

<?php
include_once'footer.php';
?>
