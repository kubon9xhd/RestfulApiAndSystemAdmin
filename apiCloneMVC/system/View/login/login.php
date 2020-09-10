<?php
require_once 'header.php';
require_once '../../Controller/userController.php';
if(isset($_SESSION['username'])) 
{
     echo '<script type="text/javascript">alert("U were login !!");
                window.location="../../index.php";
            </script>';

}
$login =  new userController();
if($_SERVER['REQUEST_METHOD'] == "POST"){
	if(!isset($_SESSION['username'])){
		$login->login();
	}
}
?>
    <!--*******************
        Preloader start
    ********************-->
    <div id="preloader">
        <div class="loader">
            <svg class="circular" viewBox="25 25 50 50">
                <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="3" stroke-miterlimit="10" />
            </svg>
        </div>
    </div>
    <!--*******************
        Preloader end
    ********************-->

    



    <div class="login-form-bg h-100">
        <div class="container h-100">
            <div class="row justify-content-center h-100">
                <div class="col-xl-6">
                    <div class="form-input-content">
                        <div class="card login-form mb-0">
                            <div class="card-body pt-5">
                                <a class="text-center" href="login.php"> <h4>Login Account</h4></a>
                                <b><?php if(!empty($login->status)) echo $login->status; else echo '' ?></b>
                                <form class="mt-5 mb-5 login-input" action="login.php" method="post">
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="username" placeholder="Username">
                                    </div>
                                    <div class="form-group">
                                        <input type="password" class="form-control" name="password" placeholder="Password">
                                    </div>
                                    <button class="btn login-form__btn submit w-100">Sign In</button>
                                </form>
                                <p class="mt-5 login-form__footer">Dont have account? <a href="register.php" class="text-primary">Sign Up</a> now</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php
require_once 'footer.php';
?>