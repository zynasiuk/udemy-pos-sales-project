<?php
include_once'connectdb.php';
session_start();

// krok1: pobierz wszystkie z tbl_product i tworz opcje do wybory tak dlugo jak sa wyniki zwrotu z db
function fill_product($pdo) {
    $output = '';
    $select = $pdo->prepare("select * from tbl_product order by pname asc");
    $select -> execute();
    $result = $select -> fetchAll();
    
    foreach($result as $row) {
        $output.= '<option value="'.$row["pid"].'">'.$row["pname"].'</option>';
    }
    return $output;
}
// krok2: uzyj select2 plugin aby moc rowniez wyszukiwac produkty

// krok3: pobierz dane i wpisz je do innych pol w dziale z jQuery


include_once'header.php';
?>




<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>

            Create Order
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
                    <h3 class="box-title">New Order</h3>
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
                                <input type="text" class="form-control" name="txtcustomer" placeholder="Enter Customer Name" required>
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
                                <input type="text" class="form-control pull-right" id="datepicker">
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
                                            <center><button type="button" name="add" class="btn btn-success btn-sm btnadd"><span class="glyphicon glyphicon-plus"></span></button></center>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
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
                                    <i class="fa fa-usd"></i>
                                </div>
                                <input type="text" class="form-control" name="txtsubtotal" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>tax (5%)</label>
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-usd"></i>
                                </div>
                                <input type="text" class="form-control" name="txttax" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Discount</label>
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-usd"></i>
                                </div>
                                <input type="text" class="form-control" name="txtdiscount" required>
                            </div>
                        </div>

                    </div>






                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Total</label>
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-usd"></i>
                                </div>
                                <input type="text" class="form-control" name="txttotal" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Paid</label>
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-usd"></i>
                                </div>
                                <input type="text" class="form-control" name="txtpaid" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Due</label>
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-usd"></i>
                                </div>
                                <input type="text" class="form-control" name="txtdue" required>
                            </div>
                        </div>

                        <!-- radio -->
                        <!-- radio -->
                        <label>Payment Method</label>
                        <div class="form-group">

                            <label>
                                <input type="radio" name="r2" class="minimal-red" checked> CASH
                            </label>
                            <label>
                                <input type="radio" name="r2" class="minimal-red"> CARD
                            </label>
                            <label>
                                <input type="radio" name="r2" class="minimal-red">
                                CHECK
                            </label>
                        </div>
                    </div>


                </div>
                <hr>
                <div align="center">
                    <input type="submit" name="btnsaveorder" value="Save Order" class="btn btn-info">
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
        $(document).on('click', '.btnadd', function() {
            var html = '';
            html += '<tr>';
            html += '<td><input type="hidden" class="form-control pname" name="productname[]" readonly></td>';

            html += '<td><select class="form-control productid" name="productid[]" style="width: 200px;"><option value="">Select Option</option><?php echo fill_product($pdo);?></select></td>';
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

                        tr.find(".stock").val(data["pstock"]);
                        tr.find(".price").val(data["saleprice"]);
                        tr.find(".qty").val(1);

                        tr.find(".total").val(tr.find(".qty").val() * tr.find(".price").val());


                    }
                })
            })
        });

        // remove products from your order
        $(document).on('click', '.btnremove', function() {
            $(this).closest('tr').remove();
        })

        // oblicz i wyswietl cene za ilosc produktow

        $('#producttable').delegate(".qty", "keyup change", function() {
            var quantity = $(this);
            var tr = $(this).parent().parent();
            if ((quantity.val() - 0) > (tr.find(".stock").val() - 0)) {
                swal("WARNING", "Not availiby in this number of quantity", "warning");

                quantity.val(1);
                tr.find(".total").val(quantity.val() * tr.find(".price").val());
            } else {
                tr.find(".total").val(quantity.val() * tr.find(".price").val());
            }
        })

    });

</script>

<?php
include_once'footer.php';
?>
