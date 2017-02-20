<div class="small-header">
        <div class="hpanel">
            <div class="panel-body">
                <div id="hbreadcrumb" class="pull-right">
                    <ol class="hbreadcrumb breadcrumb">
                        <li><a href="<?=URL?>home/Profile/<?=$this->Info->login['username']?>">Profile</a></li>
                        <li class="active">
                            <span>Settings </span>
                        </li>
                    </ol>
                </div>
                <h2 class="font-light m-b-xs">
                    <i class="pe-7s-tools"></i> PROFILE AND ACCOUNT OPTIONS & SETTINGS
                </h2>
                <small>Update your account information or make your profile more attractive.</small>
            </div>
        </div>
    </div>
	
<div class="content animate-panel">

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
       
	   <form method="POST" id="UpdateDetailsForm" enctype="multipart/form-data">
	   <h2><span class="text-success"><i class="pe-7s-id"></i> PROFILE</span> / Options</h2>
        <div class="row">
            <div class="col-lg-4">
            <div class="hpanel hgreen">
            <div class="panel-body">
			    <center><h4><b>AVATAR</b> (MAX SIZE: 5MB)</h4>
                <img id="profileavatar" alt="avatar" class="img-circle m-b m-t-md" src="<?=URL?>profiles/avatar/<?=$this->Info->login['profile']?>" width="78px">
				<input type="file" name="avatar" id="SettingsName" class="form-control" />
				<hr />
				<h4><b>NAME</b></h4>
				<input type="text" name="FirstName" id="SettingsName" class="form-control" value="<?=$this->Info->login['FirstName']?>">
				<br />
				<input type="text" name="LastName" id="SettingsName" class="form-control" value="<?=$this->Info->login['LastName']?>"></center>
            </div>
        </div>
        </div>
		<div class="col-lg-8">
		<div class="hpanel">
            <div id="profilecoverpanel" class="panel-body" style="background-image: url('<?=URL?>profiles/cover/<?=$this->Info->login['cover']?>');background-size:cover;">
			<div id="SettingsCover">
            </div>
			<h4 style="color:white;"><b>CHANGE COVER PHOTO</b> (MAX SIZE: 10MB)</h4>
			<input type="file" id="SettingsName" class="form-control" name="cover" />
			</div>
            <div class="panel-footer">
				PROFILE COVER IMAGE
            </div>
        </div>
        </div>
        </div>
		<input type="hidden" name="security" value="<?=$this->Info->security?>">
		<button type="submit" class="btn btn-primary btn-outline btn-block"><i class="pe-7s-diskette"></i> UPDATE DETEILS</button>		
		</form>
		
		<br />

		<h2><span class="text-success"><i class="pe-7s-config"></i> ACCOUNT</span> / Settings</h2>
		
		<div class="row">
            <div class="col-lg-6">
		    	<form method="POST">
                    <input type="text" class="form-control" placeholder="Current E-Mail Address" name="old_email" /><br/>
		    	    <input type="text" class="form-control" placeholder="New E-Mail" name="new_email" /><br/>
		    	    <input type="text" class="form-control" placeholder="Repeat New E-Mail" name="new_email_re" /><br />
					<input type="hidden" name="security" value="<?=$this->Info->security?>">
		    	    <button type="submit" class="btn btn-primary btn-outline btn-block"><i class="pe-7s-mail-open"></i> UPDATE E-MAIL ADDRESS</button>
		    	</form>
		    </div>
		    		
		    
		    <div class="col-lg-6">
		        <form method="POST">
                    <input type="password" class="form-control" placeholder="Current Password" name="old_pass" /><br/>
		        	<input type="password" class="form-control" placeholder="New Password" name="new_pass" /><br/>
		        	<input type="password" class="form-control" placeholder="Repeat New Password" name="new_pass_re" /> <br />
					<input type="hidden" name="security" value="<?=$this->Info->security?>">
		        	<button type="submit" class="btn btn-primary btn-outline btn-block"><i class="pe-7s-key"></i> UPDATE PASSWORD</button>
		        </form>
            </div>
        </div>
		

		
		<br />
	
</div>