<?php
include_once'connectdb.php';
session_start();
if ($_SESSION['useremail']=="") {
    header('location:index.php');
}

if ($_SESSION['role']=="Admin") {
    include_once'header.php';
} else {
    include_once'headeruser.php';
}


// btnupdate clicked = get values from user's form into variables
if (isset($_POST['btnupdate'])) {
    
$oldpassword_txt=$_POST['txtoldpass']; 
$newpassword_txt=$_POST['txtnewpass'];
$confpassword_txt=$_POST['txtconfpass'];  
    //echo $_old_pssw." - ". $_new_pssw ." - ". $_conf_pssw;
    
    // select query by useremail
    $email=$_SESSION['useremail'];
    $select = $pdo->prepare("select * from tbl_user where useremail='$email'");
    $select->execute();
    
    $row = $select->fetch(PDO::FETCH_ASSOC);
    $useremail_db = $row['useremail'];
    $password_db = $row['password'];
    
    // compare input with db
    
/*  without sweetalaerts

if($oldpassword_txt == $password_db) {
       // echo 'pssw ok';
        if($newpassword_txt == $confpassword_txt){
           // echo 'new pssw confirmed';
              $update = $pdo -> prepare("update tbl_user set password=:pass where useremail=:email");
            
            $update -> bindParam(':pass',$confpassword_txt);
            $update -> bindParam(':email', $email);
            
            if($update ->execute()) {
                echo "pssw updated";
            } else {
                echo "pssw not updated";
            }
        }else{
            echo 'new and confirm do not match';
        } 
        
    } else {
        echo  'pssw not ok';
    }*/
    
    
    
    //adding sweetalerts
        if($oldpassword_txt == $password_db) {
        if($newpassword_txt == $confpassword_txt){
              $update = $pdo -> prepare("update tbl_user set password=:pass where useremail=:email");
            
            $update -> bindParam(':pass',$confpassword_txt);
            $update -> bindParam(':email', $email);
            
            if($update ->execute()) {
                
         echo '<script type="text/javascript">
        jQuery(
        function validation(){
        swal({
  title: "Great!",
  text: "Your password is uptaded!",
  icon: "success",
  button: "OK!",
});
        });     
        </script>';
        echo  'pssw not ok';
            } else {
                    echo '<script type="text/javascript">
        jQuery(
        function validation(){
        swal({
  title: "Error!",
  text: "Password not updated!",
  icon: "error",
  button: "Try again!",
});
        });     
        </script>';
            }
        }else{
                    echo '<script type="text/javascript">
        jQuery(
        function validation(){
        swal({
  title: "Oops!",
  text: "new and confirm do not match",
  icon: "warning",
  button: "Ok!",
});
        });     
        </script>';
        
        } 
        
    } else {
        echo '<script type="text/javascript">
        jQuery(
        function validation(){
        swal({
  title: "Warning!",
  text: "Your password is wrong!",
  icon: "warning",
  button: "Try again!",
});
        });     
        </script>';
        echo  'pssw not ok';
    }   
}

?>




<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Admin Dashboard
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

        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Update Your Password</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form role="form" action="" method="post">
                <div class="box-body">

                    <div class="form-group">
                        <label for="exampleInputPassword1">Old Password</label>
                        <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Password" name="txtoldpass" required>
                    </div>

                    <div class="form-group">
                        <label for="exampleInputPassword1">New Password</label>
                        <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Password" name="txtnewpass" required>
                    </div>

                    <div class="form-group">
                        <label for="exampleInputPassword1">Confirm Password</label>
                        <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Password" name="txtconfpass" required>
                    </div>

                </div>
                <!-- /.box-body -->

                <div class="box-footer">
                    <button type="submit" class="btn btn-primary" name="btnupdate">Update</button>
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
