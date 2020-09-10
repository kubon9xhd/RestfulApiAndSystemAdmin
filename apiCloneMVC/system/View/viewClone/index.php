<?php
session_start();
include_once '../header.php';
if(!isset($_SESSION['username']))
{
    echo '<script type="text/javascript">
             window.location="View/login/login.php";
            </script>';
}
?>
        <!--**********************************
            Content body start
        ***********************************-->
        <div class="content-body">

            <div class="row page-titles mx-0">
                <div class="col p-md-0">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Dashboard</a></li>
                        <li class="breadcrumb-item active"><a href="javascript:void(0)">Home</a></li>
                    </ol>
                </div>
            </div>
            <!-- row -->

            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Data Table - Tổng Clone : <span id="total">0</span>
                                    <?php
                                        if($_SESSION['username'] == 'kubon'){
                                            echo('- Amount Get <span class="amountGet">0</span>');
                                        }
                                    ?></h4>
                                    <!-- <button type="submit" class="btn btn-primary" id="click" onclick="">Export</button> -->
                                   <!--  <?php
                                        // if($_SESSION['username'] == 'kubon'){
                                        //     echo('<button type="submit" class="btn btn-primary" id="click" onclick="Reset()">Reset Amount</button>');
                                       // }
                                    ?> -->


                                <div class="table-responsive">
                                    <table id="example" class="table table-striped table-bordered" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>TIME</th>
                                                <th>ID</th>
                                                <th>CLONE</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th>TIME</th>
                                                <th>ID</th>
                                                <th>CLONE</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- #/ container -->
        </div>
        <!--**********************************
            Content body end
        ***********************************-->
        <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
        <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
        <script src="https://cdn.datatables.net/colreorder/1.5.2/js/dataTables.colReorder.min.js"></script>
        <script type="text/javascript">
            var url = '<?php echo $_SESSION["token"]?>';
            $(document).ready(function() {
                var interval = 500;
                if ( ! $.fn.DataTable.isDataTable( '#example' ) ) {
                    table = $('#example').DataTable( {
                        "ajax": 'http://jupiter-ns.club/api.php/getClone',
                        "paging": false,
                        "scrollX": true,
                        "order": [[ 1, "desc" ]]
                    } );
                }else{
                    table = $('#example').dataTable();
                }
                setInterval( function () {
                    table.ajax.reload(null,false);
                    $.ajax({
                        url: "http://jupiter-ns.club/api.php/getAmountClone/"+url,
                        type: "GET",
                        // contentType: "application/json",
                        dataType: "json",
                        // cache: true,
                        success: function(data) {
                            $(data).each(function (index,value) {
                                $("span#total").text(value);
                            });
                        },
                        error: function(data){
                            $(data).each(function (index,value) {
                                console.log(value);
                            });  
                       }
                    });

                }, 500);
            });
        </script>
<?php
include_once '../footer.php';
?>