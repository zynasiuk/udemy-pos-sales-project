<?php
include_once'connectdb.php';
session_start();

// krok1: pobierz wszystkie z tbl_product i tworz opcje do wybory tak dlugo jak sa wyniki zwrotu z db
function fill_product($pdo,$pid) {
    $output = '';
    $select = $pdo->prepare("select * from tbl_product order by pname asc");
    $select -> execute();
    $result = $select -> fetchAll();
    
    foreach($result as $row) {
        $output.= '<option value="'.$row["pid"].'"';
        if($pid==$row['pid']){
            $output.='selected';
        }   
        $output.='>'.$row["pname"].'</option>';
    }
    
    return $output;
}
// krok1: edytuj createorder

// krok2: pobierz dane i wpisz je do innych pol w formularzu przy php


$id=$_GET['id'];
$select = $pdo -> prepare("select * from tbl_invoice where invoice_id=$id");
$select -> execute();

$row=$select -> fetch(PDO::FETCH_ASSOC);
$customer_name = $row['customer_name'];
$order_date = date('Y-m-d', strtotime($row['order_date']));
$subtotal = $row['subtotal'];
$tax = $row['tax'];
$discount = $row['discount'];
$total = $row['total'];
$paid = $row['paid'];
$due = $row['due'];
$payment_type = $row['payment_type'];


$select = $pdo -> prepare("select * from tbl_invoice_details where invoice_id=$id");
$select -> execute();

$row_invoice_details =$select -> fetchAll();









if (isset($_POST['btnupdateorder'])) {
    $customer_name = $_POST['txtcustomer'];
    $order_date = date('Y-m-d', strtotime($_POST['orderdate']));
    $subtotal = $_POST['txtsubtotal'];
    $tax = $_POST['txttax'];
    $discount = $_POST['txtdiscount'];
    $total = $_POST['txttotal'];
    $paid = $_POST['txtpaid'];
    $due = $_POST['txtdue'];
    $payment_type = $_POST['rb'];
    
    
    ///// arrays values
    $arr_productid = $_POST['productid'];
    $arr_productname = $_POST['productname'];
    $arr_stock = $_POST['stock'];
    $arr_qty = $_POST['qty'];
    $arr_price = $_POST['price'];
    $arr_total = $_POST['total'];
    
    $insert = $pdo -> prepare("insert into tbl_invoice(customer_name,order_date,subtotal,tax,discount,total,paid,due,payment_type) values(:cust,:ordat,:stot,:tax,:disc,:total,:paid,:due,:ptype)");
        
        
    $insert -> bindParam(':cust', $customer_name);
    $insert -> bindParam(':ordat', $order_date);
    $insert -> bindParam(':stot', $subtotal);
    $insert -> bindParam(':tax', $tax);
    $insert -> bindParam(':disc',$discount);
    $insert -> bindParam(':total',$total);
    $insert -> bindParam(':paid', $paid);
    $insert -> bindParam(':due', $due);
    $insert -> bindParam(':ptype',$payment_type );

    $insert -> execute();    
    
    // second query
    
    $invoice_id = $pdo->lastInsertId();
    if ($invoice_id!=null) {
        
        for($i=0;$i<count($arr_productid); $i++) {
            
            //reminding quantity in db on change in the order
            $rem_qty = $arr_stock[$i] - $arr_qty[$i];
            
            if($rem_qty<0){
                return"Order Is Not Complete";
            }else{
                $update = $pdo ->prepare("update tbl_product SET pstock='$rem_qty' where pid='".$arr_productid[$i]."'");
                $update -> execute();
            }
            
            
            $insert = $pdo ->prepare("insert into tbl_invoice_details(invoice_id,product_id,product_name,qty,price,order_date) values(:invid,:pid,:prodn,:qty,:price,:od)");
            
            $insert -> bindParam(':invid',$invoice_id);
            $insert -> bindParam(':pid',$arr_productid[$i]);
            $insert -> bindParam(':prodn',$arr_productname[$i]);
            $insert -> bindParam(':qty',$arr_qty[$i]);
            $insert -> bindParam(':price',$arr_price[$i]);
            $insert -> bindParam(':od',$order_date);
            
            $insert->execute();
           
        }
        // echo "success! yar -------";
        header('location:orderlist.php');
    }
        
}

include_once'header.php';
?>




<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Your Order
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
            <!-- form start -->
            <form action="" method="post" name="">
                <div class="box-header with-border">
                    <h3 class="box-title">Edit Order</h3>
                </div>
                <!-- /.box-header -->


                <div class="box-body">
                    <!-- customer and date-->
                    <div class="col-md-6">


                        <div class="form-group">
                            <label>Customer Name</label>
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-user"></i>
                                </div>
                                <input type="text" class="form-control" name="txtcustomer" value="<?php echo $customer_name; ?>" required>
                            </div>
                        </div>


                    </div>
                    <div class="col-md-6">
                        <!-- Date -->
                        <div class="form-group">
                            <label>Date:</label>

                            <div class="input-group date">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input type="text" class="form-control pull-right" id="datepicker" name="orderdate" value="<?php echo $order_date; ?>" required readonly>
                            </div>
                            <!-- /.input group -->
                        </div>
                        <!-- /.form group -->
                    </div>
                </div>

                <div class="box-body">
                    <!-- table -->

                    <div class="col-md-12">
                        <div style="overflow-x:auto;">
                            <table id="producttable" class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Search Product</th>
                                        <th>Stock</th>
                                        <th>Price</th>
                                        <th>Enter Quantity</th>
                                        <th>Total</th>
                                        <th>
                                            <center><button type="button" name="add" class="btn btn-info btn-sm btnadd"><span class="glyphicon glyphicon-plus"></span></button></center>
                                        </th>
                                    </tr>
                                </thead>


                                <?php
                                foreach($row_invoice_details as $item_invoice_details) {
                                    $select = $pdo -> prepare("select * from tbl_product where pid='{$item_invoice_details['product_id']}'");
                                    $select -> execute();
                                    
                                    $row_product = $select -> fetch(PDO::FETCH_ASSOC);                                                           
                                
                                  ?>
<tr>
    <?php
    
     echo '<td><input type="hidden" class="form-control pname" name="productname[]" readonly></td>';

            echo '<td><select class="form-control productidedit" name="productid[]" style="width: 200px;"><option value="">Select Option</option>'.fill_product($pdo,$item_invoice_details['product_id']).'</select></td>';
            echo '<td><input type="text" class="form-control stock" name="stock[]" value="'.$row_product['pstock'].'" readonly></td>';
            echo '<td><input type="text" class="form-control price" name="price[]" value="'.$row_product['saleprice'].'" readonly></td>';
            echo '<td><input type="number" min="1" class="form-control qty" value="'.$item_invoice_details['qty'].'" name="qty[]"></td>';
          echo '<td><input type="text" class="form-control total" name="total[]" value="'.$row_product['saleprice']*$item_invoice_details['qty'].'" readonly></td>';

            echo '<td><center><button type="button" name="remove" class="btn btn-danger btn-sm btnremove"><span class="glyphicon glyphicon-remove"></span></button></center></td></tr>';
    
    ?>
    
    
</tr>
 <?php } ?>

                            </table>
                        </div>
                    </div>

                </div>





                <div class="box-body">
                    <!-- tax, ... -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>SubTotal</label>
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-eur"></i>
                                </div>
                                <input type="text" class="form-control" name="txtsubtotal" id="txtsubtotal" value="<?php echo $subtotal; ?>" required readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>tax (5%)</label>
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-eur"></i>
                                </div>
                                <input type="text" class="form-control" name="txttax" id="txttax" value="<?php echo $tax; ?>" required readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Discount</label>
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-eur"></i>
                                </div>
                                <input type="text" class="form-control" name="txtdiscount" id="txtdiscount" value="<?php echo $discount; ?>" required>
                            </div>
                        </div>

                    </div>






                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Total</label>
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-eur"></i>
                                </div>
                                <input type="text" class="form-control" name="txttotal" id="txttotal" value="<?php echo $total; ?>" required readonly>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Paid</label>
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-eur"></i>
                                </div>
                                <input type="text" class="form-control" name="txtpaid" id="txtpaid" value="<?php echo $paid; ?>" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Due</label>
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-eur"></i>
                                </div>
                                <input type="text" class="form-control" name="txtdue" id="txtdue" value="<?php echo $due; ?>" required readonly>
                            </div>
                        </div>

                        <!-- radio -->
                        <!-- radio -->
                        <label>Payment Method</label>
                        <div class="form-group">

                            <label>
                                <input type="radio" name="rb" class="minimal-red" value="Cash" <?php echo ($payment_type == 'Cash')?'checked':''; ?>> CASH
                            </label>
                            <label>
                                <input type="radio" name="rb" class="minimal-red" value="Card" <?php echo ($payment_type == 'Card')?'checked':''; ?>> CARD
                            </label>
                            <label>
                                <input type="radio" name="rb" class="minimal-red" value="Check" <?php echo ($payment_type == 'Check')?'checked':''; ?>>
                                CHECK
                            </label>
                        </div>
                    </div>


                </div>
                <hr>
                <div align="center">
                    <input type="submit" name="btnupdateorder" value="Update Order" class="btn btn-warning">
                </div>

                <hr>


            </form>
        </div>
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->


<script>
    //Date picker
    $('#datepicker').datepicker({
        autoclose: true
    });

    //Flat red color scheme for iCheck
    $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
        checkboxClass: 'icheckbox_flat-green',
        radioClass: 'iradio_flat-green'
    });


    // add products to your order; FETCH DATA function ABOVE! - here insert fill_product($pdo) php code in select
    $(document).ready(function() {
        
                    //Initialize Select2 Elements
            $('.productidedit').select2();

            $(".productidedit").on('change', function(e) {
                var productid = this.value;
                var tr = $(this).parent().parent();
                $.ajax({

                    //w getproduct.php polacz sie z db i pobierz dane z tabeli produktow na podstawie id
                    url: "getproduct.php",
                    method: "get",
                    data: {
                        id: productid
                    },
                    success: function(data) {

                        tr.find(".pname").val(data["pname"]);
                        tr.find(".stock").val(data["pstock"]);
                        tr.find(".price").val(data["saleprice"]);
                        tr.find(".qty").val(1);

                        tr.find(".total").val(tr.find(".qty").val() * tr.find(".price").val());
                        calculate(0, 0);

                    }
                })
            })
        
        
        
        
        $(document).on('click', '.btnadd', function() {
            var html = '';
            html += '<tr>';
            html += '<td><input type="hidden" class="form-control pname" name="productname[]" readonly></td>';

            html += '<td><select class="form-control productid" name="productid[]" style="width: 200px;"><option value="">Select Option</option><?php echo fill_product($pdo,'');?></select></td>';
            html += '<td><input type="text" class="form-control stock" name="stock[]" readonly></td>';
            html += '<td><input type="text" class="form-control price" name="price[]" readonly></td>';
            html += '<td><input type="number" min="1" class="form-control qty" name="qty[]"></td>';
            html += '<td><input type="text" class="form-control total" name="total[]" readonly></td>';

            html += '<td><center><button type="button" name="remove" class="btn btn-danger btn-sm btnremove"><span class="glyphicon glyphicon-remove"></span></button></center></td></tr>';

            $('#producttable').append(html);

            //Initialize Select2 Elements
            $('.productid').select2();

            $(".productid").on('change', function(e) {
                var productid = this.value;
                var tr = $(this).parent().parent();
                $.ajax({

                    //w getproduct.php polacz sie z db i pobierz dane z tabeli produktow na podstawie id
                    url: "getproduct.php",
                    method: "get",
                    data: {
                        id: productid
                    },
                    success: function(data) {

                        tr.find(".pname").val(data["pname"]);
                        tr.find(".stock").val(data["pstock"]);
                        tr.find(".price").val(data["saleprice"]);
                        tr.find(".qty").val(1);

                        tr.find(".total").val(tr.find(".qty").val() * tr.find(".price").val());
                        calculate(0, 0);

                    }
                })
            })
        });

        // remove products from your order
        $(document).on('click', '.btnremove', function() {
            $(this).closest('tr').remove();
            $('#txtpaid').val(0);
            calculate(0, 0);
        })

        // oblicz i wyswietl cene za ilosc produktow

        $('#producttable').delegate(".qty", "keyup change", function() {
            var quantity = $(this);
            var tr = $(this).parent().parent();
            if ((quantity.val() - 0) > (tr.find(".stock").val() - 0)) {
                swal("WARNING", "Not availiby in this number of quantity", "warning");
                quantity.val(1);
                tr.find(".total").val(quantity.val() * tr.find(".price").val());
                calculate(0, 0);
            } else {
                tr.find(".total").val(quantity.val() * tr.find(".price").val());
                calculate(0, 0);
            }
        })



        function calculate(dis, paid) {
            var subtotal = 0;
            var tax = 0;
            var discount = dis;
            var total = 0;
            var paid_amd = paid;
            var due = 0;

            $(".total").each(function() {
                subtotal = subtotal + ($(this).val() * 1);
            })

            tax = 0.05 * subtotal;
            net_total = tax + subtotal;
            net_total = net_total - discount;
            due = net_total - paid_amd;

            $("#txtsubtotal").val(subtotal.toFixed(2));
            $("#txttax").val(tax.toFixed(2));
            $('#txttotal').val(net_total.toFixed(2));
            $('#txtdiscount').val(discount);
            $('#txtdue').val(due.toFixed(2));

            $('#txtdiscount').keyup(function() {
                var paid = $("#txtpaid").val();
                var discount = $(this).val();
                calculate(discount, paid);
            })

            $('#txtpaid').keyup(function() {
                var paid = $(this).val();
                var discount = $("#txtdiscount").val();
                calculate(discount, paid);
            })
        }

    });

</script>

<?php
include_once'footer.php';
?>
