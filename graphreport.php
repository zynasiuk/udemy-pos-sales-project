<?php
include_once'connectdb.php';
session_start();
error_reporting(0);
include_once'header.php';
?>




<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Graph Report
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
                <h3 class="box-title">From: <?php echo $_POST['date_1']?> To: <?php echo $_POST['date_2']?></h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->

            <div class="box-body">

                <div class="row">
                    <form action="" method="post" autocomplete="off">
                        <div class="col-md-5">





                            <div class="input-group date">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input type="text" class="form-control pull-right" id="datepicker1" name="date_1" data-date-format="yyyy-mm-dd">
                            </div>
                        </div>

                        <div class="col-md-5">
                            <div class="input-group date">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input type="text" class="form-control pull-right" id="datepicker2" name="date_2" data-date-format="yyyy-mm-dd">
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div align="left">
                                <input type="submit" name="btndatefilter" value="Filter By Date" class="btn btn-success">
                            </div>

                        </div>
                    </form>
                </div>


                <?php
    $select=$pdo->prepare("select order_date, sum(total) as price from tbl_invoice where order_date between :fromdate AND :todate group by order_date");
                    $select->bindParam(':fromdate',$_POST['date_1']);
                    $select->bindParam(':todate',$_POST['date_2']);
                    $select->execute();
                    
                    $total=[];
                    $date=[];
             
while($row = $select -> fetch(PDO::FETCH_ASSOC)) {
    extract($row);
    $total[] = $price;
    $date[] = $order_date;
}
      
    //  echo json_encode($total);
     ?>


                <div class="chart">
                    <canvas id="myChart" style="height: 250"></canvas>
                </div>

                <br>




                <?php
    $select=$pdo->prepare("select sum(qty) as q from tbl_invoice_details  where order_date between :fromdate AND :todate group by product_name");
                $select->bindParam(':fromdate',$_POST['date_1']);  
    $select->bindParam(':todate',$_POST['date_2']);  
            
    $select->execute();
              
    $qty=[];                       
    $select->execute();          
                
                
while($row=$select->fetch(PDO::FETCH_ASSOC)  ){
    
extract($row);
    $qty[]=$q;
 
}
  // echo json_encode($qty);

     ?>




                <?php
    $select=$pdo->prepare("select product_name from tbl_invoice_details  where order_date between :fromdate AND :todate group by product_name");       
                
      $select->bindParam(':fromdate',$_POST['date_1']);  
    $select->bindParam(':todate',$_POST['date_2']);  
            
    $select->execute();
                  
$pname=[];
                
                
while($row=$select->fetch(PDO::FETCH_ASSOC)  ){
    
extract($row);
    
$pname[]=$product_name;    
}
               //   echo json_encode($pname);
     ?>




                <div class="chart">
                    <canvas id="bestsellingproduct" style="height: 250"></canvas>
                </div>
            </div>

        </div>
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->


<script>
    $('#datepicker1').datepicker({
        autoclose: true
    });
    $('#datepicker2').datepicker({
        autoclose: true
    });



    var ctx = document.getElementById('myChart').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($date);?>,

            datasets: [{
                label: 'Total Earning',
                backgroundColor: 'rgba(255, 99, 132, .8)',
                data: <?php echo json_encode($total);?>
            }]
        },
        options: {}
    });





    var ctx2 = document.getElementById('bestsellingproduct').getContext('2d');
    var myChart2 = new Chart(ctx2, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($pname);?>,
            datasets: [{
                label: 'Best Selling Products',
                backgroundColor: 'rgba(205, 199, 132, .8)',
                data: <?php echo json_encode($qty);?>
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
        }
    });

</script>

<?php
include_once'footer.php';
?>
