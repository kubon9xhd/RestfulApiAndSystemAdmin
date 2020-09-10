<?php
require_once 'header.php';
require_once '../../Controller/userController.php';
$register =  new userController();
if($_SESSION['username'] != 'kubon'){
    echo '<script type="text/javascript">alert("U does not access");
                window.location="../../index.php";
            </script>';
}
if($_SERVER['REQUEST_METHOD'] == "POST"){
    if(isset($_SESSION['username'])){
        $register->register();
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
                                
                                <a class="text-center" href="index.html"> <h4>Register</h4></a>
                                <b><?php if(!empty($register->status)) echo $register->status; else echo '' ?></b>
                                <form class="mt-5 mb-5 login-input" action="register.php" method="post">
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="name"  placeholder="Name" required>
                                    </div>
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="username"  placeholder="username" required>
                                    </div>
                                    <div class="form-group">
                                        <input type="password" class="form-control" name="password" placeholder="Password" required>
                                    </div>
                                    <button class="btn login-form__btn submit w-100">Sign in</button>
                                </form>
                                    <p class="mt-5 login-form__footer">Have account <a href="page-login.html" class="text-primary">Sign Up </a> now</p>
                                    </p>
                                </div>
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