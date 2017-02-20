<aside id="menu">
    <div id="navigation">
        <div class="profile-picture">
            <a href="<?=URL?>home/Profile/<?=$this->Info->login['username']?>">
                <img src="<?=URL?>profiles/avatar/<?=$this->Info->login['profile']?>" width="78" height="78" class="img-circle m-b" alt="logo">
            </a>

            <div class="stats-label text-color">
                <span class="font-extra-bold font-uppercase"><?php if(!$this->Info->login['id']){ echo 'GUEST'; }else{ echo $this->Info->login['FirstName']." ".$this->Info->login['LastName']; } ?></span>
<?php if($this->Info->login['status']){ ?>
                <div class="dropdown">
                    <a class="dropdown-toggle" href="#" data-toggle="dropdown">
                        <small class="text-muted">PROFILE<b class="caret"></b></small>
                    </a>
                    <ul class="dropdown-menu animated fadeInRight m-t-xs">
                        <li><a href="<?=URL?>home/Profile/<?=$this->Info->login['username']?>">Profile</a></li>
						<li><a href="<?=URL?>home/LOG">LOG</a></li>
                        <li><a href="<?=URL?>home/Settings">Settings</a></li>
                        <li class="divider"></li>
                        <li><a href="<?=URL?>home/LogOut">Logout</a></li>
                    </ul>
                </div>
				
				<br />
				<a href="<?=URL?>home/upload"><button class="btn btn-success" type="button"><span class="bold">UPLOAD VIDEO</span></button></a>
<?php } ?>
		   </div>
        </div>


        <ul class="nav" id="side-menu">
		<?php
		$menu = array(
		    'VIDEOS' => 
		        array(
				    'SUBMENU' => array(
		                'RECOMMENDED' => array('href' => 'home/videos/recommended', 'OnLogin' => true), 
		                'MOST POPULAR' => array('href' => 'home/videos/most-popular'),
		    	        'BEST RATED' => array('href' => 'home/videos/best-rated'),
		    	        'RANDOM VIDEOS' => array('href' => 'home/videos/random')
				    ),
					'href' => 'home/videos'
				),
		    'MY FEED' => array('href' => 'home/MyFeed', 'OnLogin' => true),
		    'MY VIDEOS' => array('href' => 'home/MyVideos', 'OnLogin' => true),
		    'MAIL BOX' => array('href' => 'home/PM', 'OnLogin' => true),
			'ADMINISTRATOR' => array('href' => 'home/Administrator', 'OnAdmin' => true),
		    'LOGIN' => array('href' => 'home/login', 'OnLogOut' => true)
		);
		
		if(!isset($page_now))
		{
			$page_now = 'home';
		}
		
		$this->Info->GenerateMenu($menu, $page_now);
		?>
        </ul>
    </div>
</aside>

<div id="wrapper">