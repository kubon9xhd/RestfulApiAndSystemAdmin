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
                <div class="row">
                    <div class="col-12">
                    <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Số Account</h4>
                                <div class="table-responsive"> 
                                    <table class="table table-bordered table-striped verticle-middle">
                                        <thead>
                                            <tr>
                                                <th scope="col">Username</th>
                                                <th scope="col">Token</th>
                                                <th scope="col">Label</th>
                                                <th scope="col">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                while ($ouputData = $data->fetch(PDO::FETCH_ASSOC)) {
                                                    echo('
                                                    <tr>
                                                        <td>'.$ouputData['username'].'</td>
                                                        <td>'.$ouputData['token'].'</td>
                                                        <td><span class="label gradient-1 btn-rounded">70%</span>
                                                        </td>
                                                        <td><span><a href="#" data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit"><i class="fa fa-pencil color-muted m-r-5"></i> </a><a href="#" data-toggle="tooltip" data-placement="top" title="" data-original-title="Close"><i class="fa fa-close color-danger"></i></a></span>
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
                        </div>
<?php
include_once '../footer.php';
?>