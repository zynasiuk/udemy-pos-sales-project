<?php
include_once'connectdb.php';
session_start();
if ($_SESSION['useremail']==""  OR $_SESSION['role']=='User' ) {
    header('location:index.php');
}
include_once'header.php';
// krok 1: stworz formularz z general.html z template, umiesc go w pierwszej czesci grid bootstrap
// krok 2: utworz w drugiej czesci grid tabele z danymi z db
// krok 3: dodaj action, method i nazwy w formularzu
// krok 4: edytuj dane, utworz nowy profil dla Admina lub User
// krok 5: nie pozwol by tworzono wiele kont na jeden e-mail + sweetalerts
// krok 6: usun uwytkownika
$id=$_GET['id'];
$delete= $pdo->prepare("delete from tbl_user where userid=".$id);

error_reporting(0);
if ($delete->execute()) {
              echo '<script type="text/javascript">
        jQuery(
        function validation(){
        swal({
  title: "Success!",
  text: "User account removed!",
  icon: "success",
  button: "Ok!",
});
        });     
        </script>';
}

// formularz dziala!
if(isset($_POST['btnsave'])) { 
$username=$_POST['txtname'];
$useremail=$_POST['txtemail'];
$password=$_POST['txtpassword'];
$role=$_POST['txtselect_option'];
// echo $username.' - '.$useremail .' - '. $password .' - '. $role;

    
// tu sprawdzasz czy email juz istnieje w db
    if(isset($_POST['txtemail'])) {
        $select=$pdo->prepare("select useremail from tbl_user where useremail='$useremail'");
        $select->execute();
        if($select->rowCount() > 0) {
        echo '<script type="text/javascript">
        jQuery(
        function validation(){
        swal({
  title: "Warning!",
  text: "E-mail already exist!",
  icon: "warning",
  button: "Try again!",
});
        });     
        </script>';
        } else {
  // insert query, czyli wpisz do db    
    $insert=$pdo->prepare("insert into tbl_user(username,useremail,password,role) values(:name,:email,:password,:role)");
    
    $insert->bindParam(':name',$username);
    $insert->bindParam(':email',$useremail);
    $insert->bindParam(':password',$password);
    $insert->bindParam(':role',$role);
    
    if($insert->execute()) {
          echo '<script type="text/javascript">
        jQuery(
        function validation(){
        swal({
  title: "Success!",
  text: "Registration successful!",
  icon: "success",
  button: "Ok!",
});
        });     
        </script>';
    } else {
      //  echo 'registration failed', jezeli ktores z pol jest puste
        echo '<script type="text/javascript">
        jQuery(
        function validation(){
        swal({
  title: "Error!",
  text: "Registration failed!",
  icon: "error",
  button: "Try again!",
});
        });     
        </script>';
    }         
        }
    }
}
?>


<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Registration
            <small></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
            <li class="active">Here</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content container-fluid">

        <!-- general form elements -->
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">Registration Form</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form role="form" action="" method="post">
                <div class="box-body">


                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" class="form-control" name="txtname" placeholder="Enter name" required>
                        </div>
                        <div class="form-group">
                            <label>E-mail</label>
                            <input type="email" class="form-control" name="txtemail" placeholder="Enter e-mail" required>
                        </div>
                        <div class="form-group">
                            <label>Password</label>
                            <input type="password" class="form-control" name="txtpassword" placeholder="Password" required>
                        </div>

                        <div class="form-group">
                            <label>Role</label>
                            <select class="form-control" name="txtselect_option" required>
                                <option value="" disabled selected>Select role</option>
                                <option>User</option>
                                <option>Admin</option>
                            </select>
                        </div>
                        <div class="box-footer">
                        <button type="submit" class="btn btn-info" name="btnsave">Save</button>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Password</th>
                                    <th>Role</th>
                                    <th>DELETE</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
        $select=$pdo->prepare("select * from tbl_user order by userid desc");
        $select->execute();
        
        while($row=$select->fetch(PDO::FETCH_OBJ)) {
            echo'
                  <tr>
          <td>'.$row->userid.'</td>
          <td>'.$row->username.'</td>
          <td>'.$row->useremail.'</td>
          <td>'.$row->password.'</td>
          <td>'.$row->role.'</td>    
          <td>
          <a href="registration.php?id='.$row->userid.'" class="btn btn-danger" role="button"><span class="glyphicon glyphicon-trash"></span></a>
          </td>   
      </tr>          
            ';
        }        
        
        ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- /.box-body -->
            </form>
        </div>
        <!-- /.box -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->



<?php
include_once'footer.php';
?>
