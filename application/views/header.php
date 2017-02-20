<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>mediashare | Video Sharing Platform</title>
	
	<link rel="icon" href="<?=URL?>asset/images/favicon.ico" type="image/x-icon"/>
	
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/metisMenu/2.6.2/metisMenu.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" />
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.3.0/css/mdb.min.css" />
    <link rel="stylesheet" href="<?=URL?>asset/fonts/pe-icon-7-stroke/css/pe-icon-7-stroke.css" />
	
    <link rel="stylesheet" href="<?=URL?>asset/fonts/pe-icon-7-stroke/css/helper.css" />
    <script src="<?=URL?>asset/vendor/jquery/dist/jquery.min.js"></script>
	
    <link rel="stylesheet" href="<?=URL?>asset/styles/style.css">
	
	<link rel="stylesheet" href="<?=URL?>asset/vendor/awesome-bootstrap-checkbox/awesome-bootstrap-checkbox.css" />
	<link rel="stylesheet" href="<?=URL?>asset/vendor/sweetalert/lib/sweet-alert.css" />
	<?php echo '<script>var security = "'.$this->Info->security.'";var url = "'.URL.'" ;</script>'; ?>
	
</head>

<body>

<div class="splash"> <div class="color-line"></div><div class="splash-title"><h1>mediashare - Video Sharing Platorm</h1><p>Share your videos easily... For free!</p><img src="<?=URL?>asset/images/loading-bars.svg" width="64" height="64" /> </div> </div>

<div id="header">
    <div class="color-line">
    </div>
    <div id="logo" class="light-version">
        <span>
            <center>mediashare</center>
        </span>
    </div>
    <nav role="navigation">
        <div class="header-link hide-menu"><i class="fa fa-bars"></i></div>
        <div class="small-logo">
            <span class="text-primary">mediashare</span>
        </div>
		
        <form action="javascript:Search()" class="navbar-form-custom">
            <div class="form-group">
			
			 <div class="input-group m-b">
                            <div class="input-group-btn">
							<div class="btn-group">
  <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
	<?php
	if(isset($method))
	{
	  if($method == 2)
	  {
		  echo '<span class="selection" id="search_method"><i class="pe-7s-users"></i> USERS</span>';
	  }
	  else
	  {
		  echo '<span class="selection" id="search_method"><i class="pe-7s-play"></i> VIDEOS</span>';
	  }
	}
	else
	{
		echo '<span class="selection" id="search_method"><i class="pe-7s-play"></i> VIDEOS</span>';
	}
	?>					  
  </button>
  <ul class="dropdown-menu" role="menu">
    <li><a><i class="pe-7s-play"></i> VIDEOS</a></li>
    <li><a><i class="pe-7s-users"></i> USERS</a></li>
  </ul>
</div>
                                
                            </div>
                            <input style="margin-left:10px;" type="text" placeholder="Search for videos..." class="form-control" id="search" value="<?php if(isset($search)) { echo $search; } ?>" /></div>
            </div>
        </form>
		
<?php if($this->Info->login['status']){ ?>
		<div class="navbar-right">
            <ul class="nav navbar-nav no-borders">
                <li class="dropdown">
                    <a href="<?=URL?>home/LogOut">
                        <i class="pe-7s-upload pe-rotate-90"></i>
                    </a>
                </li>
            </ul>
        </div>
<?php } ?>
    </nav>
</div>