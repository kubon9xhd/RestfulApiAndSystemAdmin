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
                        <li class="breadcrumb-item active"><a href="javascript:void(0)">AddClone</a></li>
                    </ol>
                </div>
            </div>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">AddClone - Total Clone: <span id="checking">0</span> </h4>
                                
                                <div class="basic-form">
                                        <div class="form-group">
                                            <label>Clone:</label>
                                            <textarea class="form-control h-150px" rows="6" placeholder="uid|pass|cookie|2fa" id="listClone"></textarea>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-lg-8 ml-auto">
                                                <button type="submit" class="btn btn-primary" id="submit">Submit</button>
                                            </div>
                                        </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12" id = "log" style="display: none;">
                        <div class="card">
                            <div class="card-body">
                                <div class="card-title">
                                    <h4>Done</h4>
                                </div>
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>UID</th>
                                                <th>PASS</th>
                                                <th>COOKIE</th>
                                                <th>2FA</th>
                                                <th>STATUS</th>
                                            </tr>
                                        </thead>
                                        <tbody id="result">
                                        </tbody>
                                    </table>
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
<script src="https://code.jquery.com/jquery.js"></script>
<script type="text/javascript">
    var checking = 0,total = 0;
    $(document).ready(function() {
        $("#submit").click(function() {
            var listClone = $("#listClone").val().trim();
            var arrAccount = listClone.split(/\n/);
            total = arrAccount.length;
            $("#checking").text(total);
            var stt = 0;
            jQuery.each(arrAccount,function(i, clone){
                var tbody = "";
                var status = 1;
                var res = clone.split("|");
                if(res[0] == "" || res[1] == "" || res[3] == ""){
                    status = 0;
                }else if(res[0] != "" && res[1] != "" && res[3] != ""){
                    $.ajax({
                        url: "http://jupiter-ns.club/api.php/addCloneToDB/<?php echo $_SESSION['token'];?>",
                        type: "POST",
                        dataType: "json",
                        data : {
                            uid : res[0],
                            pass : res[1],
                            cookie : res[2]+"|"+res[3],
                            fa : res[4],
                        },
                        success: function(data) {
                            $(data).each(function (index,value) {
                                // tbody += "<td style='font-weight: bold; color: green'>Done</td>";
                                $("#result").append("<tr>"+
                                "<td>" + res[0] + " </td>"+
                                "<td>"+ res[1] +"</td>"+
                                "<td>"+res[2].substring(0,10)+".....</td>"+
                                "<td>"+res[3]+".....</td>"+
                                "<td style='font-weight: bold; color: green'>Done</td>"+
                                "</tr>"
                                );
                            });
                        },
                        error: function(data){
                            $(data).each(function (index,data) {
                                $("#result").append("<tr>"+
                                "<td>" + res[0] + " </td>"+
                                "<td>"+ res[1] +"</td>"+
                                "<td>"+res[2].substring(0,10)+".....</td>"+
                                "<td>"+res[3]+".....</td>"+
                                "<td style='font-weight: bold; color: red'>Fail</td>"+
                                "</tr>"
                                );  
                            });  
                       }
                    });
                   
                }
                $("#log").show();
            });

        })
    })
</script>
<?php
include_once '../footer.php';
?>