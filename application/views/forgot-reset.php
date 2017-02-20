<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>mediashare | Recover Password</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/metisMenu/2.6.2/metisMenu.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" />
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.3.0/css/mdb.min.css" />
    <link rel="stylesheet" href="<?=URL?>asset/fonts/pe-icon-7-stroke/css/pe-icon-7-stroke.css" />
    <link rel="stylesheet" href="<?=URL?>asset/fonts/pe-icon-7-stroke/css/helper.css" />
    <link rel="stylesheet" href="<?=URL?>asset/styles/style.css">

</head>

<body class="blank" background="<?=URL?>asset/images/bgrecover.jpg" style="background-repeat:no-repeat;background-size:cover;">

<div class="splash"> <div class="color-line"></div><div class="splash-title"><h1>mediashare - Video Sharing Platorm</h1><p>Share your videos easily... Freely!</p><img src="<?=URL?>asset/images/loading-bars.svg" width="64" height="64" /> </div> </div>

<div class="color-line"></div>

<div class="pull-left m">
    <a href="<?=URL?>home/" class="btn btn-primary">BACK TO HOME PAGE</a>
</div>
<div class="pull-right m">
    <a href="<?=URL?>home/login" class="btn btn-primary">LOGIN TO YOUR ACCOUNT</a>
</div>

<div class="lock-container" style="color:white">
    <div class="row">
        <div class="col-md-12">
            <div class="text-center m-b-md">
                <h3><b>mediashare</b> | RECOVER PASSWORD</h3>
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
            <div class="panel-body text-center">
			<i class="pe-7s-lock big-icon text-success"></i>
                <br/>
                <form method="POST" role="form" class="m-t">
                    <div class="form-group">
                        <label class="control-label" style="color:black">New Password</label>
                        <input type="password" placeholder="New Password" title="Please enter you password" name="new_pass" class="form-control" required>
						<br />
						<label class="control-label" style="color:black">Repeat New Password</label>
                        <input type="password" placeholder="New Password Again" title="Please enter you Password again" name="new_pass_re" class="form-control" required >
                    </div>
                    <button class="btn btn-primary block full-width" type="submit">Change Password</button>
                </form>
            </div>
        </div>
        </div>
    </div>
    <div class="row">
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