<?php
date_default_timezone_set("Asia/Ho_Chi_Minh");
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
$data = $db->prepare('SELECT * FROM admin');
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
                <div class="row justify-content-center">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                            	<div class="error">
                            	</div>
                                <div class="form-validation">
                                    <form class="form-valide" novalidate="novalidate">
                                        <div class="form-group row">
                                            <label class="col-lg-4 col-form-label" for="val-username">Amount <span class="text-danger">*</span>
                                            </label>
                                            <div class="col-lg-6">
                                                <input type="number" min="1" max="5000" class="form-control" id="val-amount" name="val-amount" placeholder="Enter a number of clone..">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-lg-4 col-form-label" for="val-password">Password <span class="text-danger">*</span>
                                            </label>
                                            <div class="col-lg-6">
                                                <input type="password" class="form-control" id="val-password" name="val-password" placeholder="Choose a safe one..">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-lg-4 col-form-label" for="val-skill">Sever Clone :<span class="text-danger">*</span>
                                            </label>
                                            <div class="col-lg-6">
                                                <select class="form-control" id="val-skill" name="val-skill">
                                                    <?php
                                                        while ($ouputData = $data->fetch(PDO::FETCH_ASSOC)) {
                                                            echo('<option value="'.$ouputData['token'].'">'.$ouputData['username'].'</option>');
                                                        }

                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-lg-4 col-form-label"><a href="#">Terms &amp; Conditions</a>  <span class="text-danger">*</span>
                                            </label>
                                            <div class="col-lg-8">
                                                <label class="css-control css-control-primary css-checkbox" for="val-terms">
                                                    <input type="checkbox" class="css-control-input" id="val-terms" name="val-terms" value="1"> <span class="css-control-indicator"></span> I agree to the terms</label>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-lg-8 ml-auto">
                                                <button type="button" id="Submit" class="btn btn-primary">Submit</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                    <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Lịch Sử</h4>
                                <div class="table-responsive"> 
                                    <table class="table table-bordered table-striped verticle-middle">
                                        <thead>
                                            <tr>
                                                <th scope="col">ID</th>
                                                <th scope="col">Time</th>
                                                <th scope="col">TokenExport</th>
                                                <th scope="col">Sever</th>
                                                <th scope="col">Amount</th>
                                                <th scope="col">GetFile</th>
                                            </tr>
                                        </thead>
                                        <tbody id = "result">
                                            <?php
                                            $dt = $db->prepare('SELECT * FROM history ORDER BY time DESC');
                                            $dt->execute();
                                            $i = 1;
                                                while ($ouputData = $dt->fetch(PDO::FETCH_ASSOC)) {
                                                    echo('
                                                    <tr>
                                                        <td>'.$i++.'</td>
                                                        <td>'.date("Y-m-d h:i:sa",$ouputData['time']).'</td>
                                                        <td>'.substr($ouputData['tokenExport'], 0,10).'...</td>
                                                        <td>'.$ouputData['username'].'</td>
                                                        <td>'.$ouputData['amount'].'</td>
                                                        <td><span><a href="http://jupiter-ns.club/api.php/exportClone/'.$ouputData['tokenExport'].'" data-toggle="tooltip" data-placement="top" title="" data-original-title="GetFile"><i class="fa fa-file-excel-o color-muted m-r-5"></i> </a></span>
                                                        </td>
                                                    </tr>
                                                        ');
                                                }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>                
                </div>
            </div>
        </div>
        <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
        <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
        <script src="https://cdn.datatables.net/colreorder/1.5.2/js/dataTables.colReorder.min.js"></script>
        <script type="text/javascript">
        	$(document).ready(function(){
        		$('#Submit').click(function(){
        			$(".error").text("");
        			var amount = $("#val-amount").val();
        			var password = $("#val-password").val();
        			var select = $("#val-skill").val();
        			// console.log(check);
        			if(amount < 1 || amount > 5000){
        				$(".error").append("<p style = 'color : red;'>Lỗi số lượng không được nhỏ hơn 1 và lớn hơn 5000<p>");
        				return false;
        			}
        			if(password === ""){
        				$(".error").append("<p style = 'color : red;'>Lỗi bạn phải bắt buộc nhập password<p>");
        				return false;
        			}
        			if(select === ""){
        				$(".error").append("<p style = 'color : red;'>Lỗi vui lòng lựa chọn 1 trong những sv trên<p>");
        				return false;
        			}
        			if(amount && password && select){
        				// console.log(amount,password,select);
        				$.ajax({
		                    url: "http://jupiter-ns.club/api.php/setTokenForCloneExport/"+amount+"/"+password+"/"+select,
		                    type: "GET",
		                    dataType: "json",
		                  
		                    success: function(data) {
		                        $(data).each(function (index,value) {
                                    $("#result").prepend('<tr><td>0</td><td>'+value[1]+'</td><td>'+value[2]+'</td><td>'+value[3].substring(0,10)+'...</td><td>'+value[4]+'</td><td><span><a href="'+value[0]+'" data-toggle="tooltip" data-placement="top" title="" data-original-title="GetFile"><i class="fa fa-file-excel-o color-muted m-r-5"></i> </a></span></td></tr>');
		                        });
		                    },
		                    error: function(data){
		                        $(data).each(function (index,value) {
		                            console.log(value);
		                        });  
		                   }
		                });
        			}else{
        				$(".error").append("<p style = 'color : red;'>Lỗi !!<p>");
        				return false;
        			}
        		});
        	});
        </script>
<?php
include_once '../footer.php';
?>