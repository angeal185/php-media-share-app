<script>
            var uploader_user_id = "<?=$profile['id']?>";
</script>
			
<div class="small-header">
        <div class="hpanel">
            <div class="panel-body">
                <div id="hbreadcrumb" class="pull-right">
                    <ol class="hbreadcrumb breadcrumb">
                        <li class="active">
                            <span>View Profile </span>
                        </li>
                    </ol>
                </div>
                <h2 class="font-light m-b-xs">
                    <i class="pe-7s-user"></i> <?=$username?>'s Profile
                </h2>
                <small>View user's profile. Surf him/her videos and view written comments.</small>
            </div>
        </div>
    </div>
	
    <div class="content animate-panel">

        <div class="row" style="max-width:1200px;margin:auto;">
    <div class="col-lg-4">
        <div class="hpanel hgreen contact-panel no-pad">
            <div class="panel-body">
                <center><img id="profileavatar" alt="avatar" class="img-circle m-b m-t-md" src="<?=URL?>profiles/avatar/<?=$profile["profile"]?>" width="78px">
                <h3><a href=""><?=$profile["FirstName"]?> <?=$profile["LastName"]?></a></h3>
                <div class="text-muted font-bold m-b-xs"><?=$username?></div>
				<?php
				if($profile['id'] == $this->Info->login['id'])
				{
					echo '<button id="profilefollow" type="button" class="btn btn-outline btn-primary color-white" disabled><i class="pe-7s-paper-plane"></i> <span>CANNOT FOLLOW</span></button></center>';
				}
				else
				{
				    if($profile['following'] == 1)
				    {
				    	echo '<button id="profilefollow" type="button" class="btn btn-outline btn-primary following" onclick="UnFollow(this)"><i class="pe-7s-paper-plane"></i> <span>FOLLOWING</span></button></center>';
				    }
				    else
				    {
				    	echo '<button id="profilefollow" type="button" class="btn btn-outline btn-primary" onclick="Follow(this)"><i class="pe-7s-paper-plane"></i> <span>FOLLOW</span></button></center>';
				    }
				}
				?>
				
            </div>
            <div class="panel-footer contact-footer">
                <div class="row">
                    <div class="col-md-6 border-right">
                        <div class="contact-stat"><span>UPLOADED VIDEOS </span> <strong><?=$profile["videos_count"]?></strong></div>
                    </div>
                    <div class="col-md-6 border-right">
                        <div class="contact-stat"><span>FOLLOWERS </span> <strong><?=$profile["follows_count"]?></strong></div>
                    </div>
                </div>
            </div>

        </div>
    </div>
	<div class="col-lg-8">
        <div class="hpanel">
            <div id="profilecoverpanel" class="panel-body" style="background-image: url('<?=URL?>profiles/cover/<?=$profile["cover"]?>');background-size:cover;">
			<div id="profilecover">
            </div>
			</div>
            <div class="panel-footer">
				PROFILE COVER IMAGE
            </div>

        </div>
    </div>
    <div class="col-lg-12">
        <div class="hpanel">
            <div class="hpanel">
            <ul class="nav nav-tabs">
                <li class="active"><a data-toggle="tab" href="#tab-1">ALL VIDEOS</a></li>
                <li class=""><a data-toggle="tab" href="#tab-2">LAST COMMENTS</a></li>
            </ul>
            <div class="tab-content">
                <div id="tab-1" class="tab-pane active">
                    <div class="panel-body feed" style="padding-bottom:0px;">
					    <?php
						$i = 0;
		
		                echo '<div style="display:block;width:100%;">';
	                    while($info = mysql_fetch_array($profile['playlist']))
		                {
		                	echo '<div class="col-lg-4">
                             <div class="hpanel">
		                      <div class="panel-heading hbuilt" id="auto-text">
                               <a href="'.URL.'home/video/'.$info['id'].'" title="'.$info['title'].'">'.$info['title'].'</a>
                              </div>
                              <div class="panel-body text-center video_background">
				<div class="thumb thumb-play">
                 <a href="'.URL.'home/video/'.$info['id'].'">
                  <span class="play">&#9658;</span>
                 <div class="overlay"></div>
                 </a>
                 <img src="'.URL."videos/".$info['image_file'].'" />
                </div>
              </div>
                              <div class="panel-footer">
                                <span><i class="pe-7s-look"></i> '.$info['views'].' | <i class="pe-7s-date"></i> '.date('Y.m.d', $info['date']).' | <i class="pe-7s-user"></i> <a href="">'.$username.'</a></span>
                              </div>
                             </div>
                            </div>';
							
		                	$i++;
		                }
	                    echo '</div>';
						
		                if($i == 0)
		                {
		                	echo '<div class="alert alert-info"><i class="fa fa-info"></i> &nbsp;THIS USER HAVE NOT UPLOADED ANY VIDEOS YET</div>';
		                }
						
                        if(isset($count_videos))
		                {
		                	$list = $this->Info->Pagination($page, ceil($count_videos/$perPage), $link);
		                	
		                	echo '<center><ul class="pagination">';
		                	
		                	if($list !== false)
		                	{
		                		echo ''.$list.'';
		                	}
		                	
		                	echo '</ul></center>';
		                }
						?>
					</div>
                </div>
                <div id="tab-2" class="tab-pane">
                    <div class="panel-body" style="padding-bottom:44px;">


                        <div class="chat-discussion">
							    <?php
								$i = 0;
		
	                            while($info = mysql_fetch_array($profile['comment_list']))
		                        {
		                        	echo '<div class="message">
                                    <a class="message-author" href="'.URL.'home/video/'.$info['id'].'"> '.$info['title'].' </a>
                                    <span class="message-date"> '.date('h:i / d.m.Y', $info['date']).' </span>
                                            <span class="message-content">
											'.$info['comment'].'
                                            </span>
                                    </div><br />';
									
									$i++;
									
		                        }
	                            
		                        if($i == 0)
		                        {
		                        	echo '<div class="alert alert-info"><i class="fa fa-info"></i> &nbsp;THIS USER HAVE NOT WRITTEN ANY COMMENTS YET</div>';
		                        }
						        ?>
                        </div>

                    </div>
                </div>
            </div>


            </div>
        </div>
    </div>
</div>

</div>