<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>mediashare | Register</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/metisMenu/2.6.2/metisMenu.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" />
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.3.0/css/mdb.min.css" />
    <link rel="stylesheet" href="<?=URL?>asset/fonts/pe-icon-7-stroke/css/pe-icon-7-stroke.css" />
    <link rel="stylesheet" href="<?=URL?>asset/fonts/pe-icon-7-stroke/css/helper.css" />
    <link rel="stylesheet" href="<?=URL?>asset/styles/style.css">

</head>

<body class="blank" background="<?=URL?>asset/images/bgregister.jpg" style="background-repeat:no-repeat;background-size:cover;">

<div class="splash"> <div class="color-line"></div><div class="splash-title"><h1>mediashare - Video Sharing Platorm</h1><p>Share your videos easily... Freely!</p><img src="<?=URL?>asset/images/loading-bars.svg" width="64" height="64" /> </div> </div>

<div class="color-line"></div>

<div class="pull-left m">
    <a href="<?=URL?>home/" class="btn btn-primary">BACK TO HOME PAGE</a>
</div>
<div class="pull-right m">
    <a href="<?=URL?>home/login" class="btn btn-primary">LOGIN TO YOUR ACCOUNT</a>
</div>

<div class="register-container" style="color:black">
    <div class="row">
        <div class="col-md-12">
            <div class="text-center m-b-md">
                <h3><b>mediashare</b> | SIGN UP</h3>
                <?php
				if($msg['error'] !== false)
                {
                 echo '<div class="alert alert-danger">
                         <i class="fa fa-remove"></i> &nbsp;'.$msg['error'].'
                     </div>';
                }
                else if($msg['success'] !== false)
                {
                 echo '<div class="alert alert-success">
                         <i class="fa fa-check"></i> &nbsp;'.$msg['success'].'
                     </div>';
                }
				?>
            </div>
            <div class="hpanel">
                <div class="panel-body">
                        <form method="POST" id="loginForm">
                            <div class="row">
							<div class="form-group col-lg-6">
                                <label>First Name</label>
                                <input type="text" value="<?=$this->Info->Auto($_POST, 'FirstName', null)?>" class="form-control" name="FirstName">
                            </div>
                            <div class="form-group col-lg-6">
                                <label>Last Name</label>
                                <input type="text" value="<?=$this->Info->Auto($_POST, 'LastName', null)?>" class="form-control" name="LastName">
                            </div>
                            <div class="form-group col-lg-12">
                                <label>Username</label>
                                <input type="" value="<?=$this->Info->Auto($_POST, 'username', null)?>" class="form-control" name="username">
                            </div>
                            <div class="form-group col-lg-6">
                                <label>Password</label>
                                <input type="password" value="" id="" class="form-control" name="password">
                            </div>
                            <div class="form-group col-lg-6">
                                <label>Repeat Password</label>
                                <input type="password" value="" class="form-control" name="password_re">
                            </div>
                            <div class="form-group col-lg-6">
                                <label>E-Mail Address</label>
                                <input type="" value="<?=$this->Info->Auto($_POST, 'email', null)?>" class="form-control" name="email">
                            </div>
                            <div class="form-group col-lg-6">
                                <label>Repeat E-Mail Address</label>
                                <input type="" value="<?=$this->Info->Auto($_POST, 'email_re', null)?>" class="form-control" name="email_re">
                            </div>
                            <div class="checkbox col-lg-12">
                                <input type="checkbox" class="i-checks" checked>
                                Subscribe for our newsletter
                            </div>
                            </div>
                            <div class="text-center">
                                <button class="btn btn-success">Register</button>
                                <a class="btn btn-default" href="index">Cancel</a>
                            </div>
                        </form>
                </div>
            </div>
        </div>
    </div>
    <div class="row" style="color:white">
        <div class="col-md-12 text-center">
            <strong>mediashare</strong> - Video Sharing Platform <br /> All Rights Reserved © 2016
        </div>
    </div>
</div>


<script src="<?=URL?>asset/vendor/jquery/dist/jquery.min.js"></script>
<script src="<?=URL?>asset/vendor/jquery-ui/jquery-ui.min.js"></script>
<script src="<?=URL?>asset/vendor/slimScroll/jquery.slimscroll.min.js"></script>
<script src="<?=URL?>asset/vendor/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="<?=URL?>asset/vendor/metisMenu/dist/metisMenu.min.js"></script>
<script src="<?=URL?>asset/vendor/iCheck/icheck.min.js"></script>
<script src="<?=URL?>asset/vendor/sparkline/index.js"></script>
<script src="<?=URL?>asset/scripts/homer.js"></script>

</body>
</html>