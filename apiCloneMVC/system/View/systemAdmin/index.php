<?php
session_start();
include_once '../header.php';
include_once '../../../config.php';
if(!isset($_SESSION['username']))
{
    echo '<script type="text/javascript">
             window.location="../../index.php";
            </script>';
}
if($_SESSION['username'] != 'admin'){
    echo '<script type="text/javascript">
             window.location="../../index.php";
            </script>';  
}
$db = new Connect();
$data = $db->prepare('SELECT * FROM admin WHERE username != "admin"');
$data->execute();
?>
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
                    <div class="col-lg-4 col-xl-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="media align-items-center mb-4">
                                    <img class="mr-3" src="<?php echo URL; ?>/public_html/images/avatar/11.png" width="80" height="80" alt="">
                                    <div class="media-body">
                                        <h3 class="mb-0"><?php echo $_SESSION['username']?></h3>
                                        <p class="text-muted mb-0">Canada</p>
                                    </div>
                                </div>
                                
                                <div class="row mb-5">
                                    <div class="col">
                                        <div class="card card-profile text-center">
                                            <span class="mb-1 text-primary"><i class="icon-people"></i></span>
                                            <h3 class="mb-0">263</h3>
                                            <p class="text-muted px-4">Following</p>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="card card-profile text-center">
                                            <span class="mb-1 text-warning"><i class="icon-user-follow"></i></span>
                                            <h3 class="mb-0">263</h3>
                                            <p class="text-muted">Followers</p>
                                        </div>
                                    </div>
                                    <div class="col-12 text-center">
                                        <a href="./accountSystem.php" class="btn btn-danger">Account System</a>
                                        <hr>
                                        <a href="./export.php" class="btn btn-success">Export Clone</a>
                                    </div>
                                </div>
                            </div>
                        </div>  
                    </div>
                    <div class="col-lg-8 col-xl-9">
                        <div class="card">
                            <div class="card-body">
                               <!-- Select -->
                               <label>Select Table To See :</label>
                              <select name="select" id="clone" class="form-control" required="required">
                                <?php
                                    while ($ouputData = $data->fetch(PDO::FETCH_ASSOC)) {
                                        echo('<option value="'.$ouputData['token'].'">'.$ouputData['username'].'</option>');
                                    }

                                ?>
<!--                               	<option value="5fe69c95ed70a9869d9f9af7d8400a6673bb9ce9">Noveri Sv 1</option>
                              	<option value="a975ce2e22441f565f3a367acd4cbaf94436440915987918524d59bf3e3c4d03cc49ee8826ae8f3c01">Noveri Sv 2</option> -->
                              </select>
                            </div>
                        </div>
                        <div class="card">
                        	<div class="card-body">
                        		<div class="card-title">
                        			<h4>Thống kê</h4>
                        		</div>
                        		<div class="thongke">
                        			<b>Tổng clone trong bảng :<i id="totalClone">0</i></b>
                        			<br/>
                        			<b>Clone got to veri:<i id="cloneVeri">0</i></b><br/>
                        			<button type="button" class="btn btn-success" onclick="Reset()" id="resetAmountClone">Reset Amount Clone Veri</button>
                        		</div>
                        	</div>
                        </div>
                        <div class="card">
                            <div class="card-body">
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
        <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
        <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
        <script src="https://cdn.datatables.net/colreorder/1.5.2/js/dataTables.colReorder.min.js"></script>
        <script type="text/javascript">
            var myVar;
            var getAmount1;
            var getSelectTable1;
        	function Reset(){
                $.ajax({
                    url: "http://jupiter-ns.club/api.php/resetAmount",
                    type: "GET",
                    // contentType: "application/json",
                    dataType: "json",
                    // cache: true,
                    success: function(data) {
                        $(data).each(function (index,value) {
                            console.log(value);
                        });
                    },
                    error: function(data){
                        $(data).each(function (index,value) {
                            console.log(value);
                        });  
                   }
                });
             }
        	function getSelectTable(selectedClone = ""){
				if ($.fn.DataTable.isDataTable( '#example' ) ) {
					// hủy bảng cũ tạo bảng mới
					table.destroy();
                    table = $('#example').DataTable( {
                    	"processing": true,
        				"serverSide": true,
                        "paging": false,
                        "order": [[ 0, "desc" ]],
                        "ajax": {
                            "url" :  'http://jupiter-ns.club/api.php/getCloneFollowAdmin/'+selectedClone+'',
                            "type" : "GET"

                        }
                    } );
                
		        }else{
		           table = $('#example').DataTable( {
                        "ajax": {
                            "url" :  'http://jupiter-ns.club/api.php/getCloneFollowAdmin/'+selectedClone+'',
                            "type" : "GET"
                        },
                        "processing": true,
        				"serverSide": true,
                        "paging": false,
                        "scrollX": true,
                        "order": [[ 0, "desc" ]]
                    } );
	            }
			}
			function getAmount(){
				$.ajax({
                    url: "http://jupiter-ns.club/api.php/getAmount",
                    type: "GET",
                    dataType: "json",
                    success: function(data) {
                        $(data).each(function (index,value) {
                            $("i#cloneVeri").text(value);
                        });
                    },
                    error: function(data){
                        $(data).each(function (index,value) {
                            console.log(value);
                        });  
                   }
                });
			}
			function getAmountClone(selectedClone = ""){				  		
				$.ajax({
                        url: "http://jupiter-ns.club/api.php/getAmountClone/"+selectedClone,
                        type: "GET",
                        dataType: "json",
                        success: function(data) {
                            $(data).each(function (index,value) {
                                $("i#totalClone").text(value);
                            });
                        },
                        error: function(data){
                            $(data).each(function (index,value) {
                                console.log(value);
                            });  
                       }
                    });
			}
            function Call(selectedClone = "5fe69c95ed70a9869d9f9af7d8400a6673bb9ce9"){
                getSelectTable(selectedClone);
                getAmountClone(selectedClone);
                getAmount();
            }
        	$(document).ready(function(){
        		// get turn the firts
                var load = setInterval( Call, 1000 );
				$("#clone").change(function(){
					var selectedClone = "";
				  	selectedClone = $(this).children("option:selected").val();
                    clearInterval(load);
                    load = setInterval(function(){
                        Call(selectedClone);
                    },1000);
				});
	            	
			});
        </script>
<?php
include_once '../footer.php';
?>