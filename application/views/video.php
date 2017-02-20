<script type="text/javascript">
function CopyToClipboard()

{
CopiedTxt = document.selection.createRange();
CopiedTxt.execCommand("Copy");
}

</script>
	 
	 	<div class="modal fade" id="ShareEmbed" tabindex="-1" role="dialog" aria-hidden="true">
             <div class="modal-dialog">
                 <div class="modal-content">
                     <div class="color-line"></div>
                     <div class="modal-header text-center" style="padding-top:20px;padding-bottom:15px;">
                         <h4 class="modal-title">SHARE EMBED</h4>
						 <img src="<?=URL?>asset/images/icons/embed.png" width="40" height="40">
                     </div>
                     <div class="modal-body">
						<textarea id="txtArea" class="form-control m-b" rows="3">
<video class="video-js vjs-default-skin" controls
 preload="auto" poster="<?=URL?>videos/<?=$image_file?>"
 data-setup="{}" width="100%" height="380px" autoplay>
<source src="<?=URL?>videos/<?=$video_file?>" type="video/mp4" />
</video></textarea>
						</center>
                     </div>
                     <div class="modal-footer" style="text-align:center;">
                         <button type="button" class="btn btn-default" onClick="CopyToClipboard()">COPY</button>
                         <button type="button" class="btn btn-primary" data-dismiss="modal">CLOSE</button>
                     </div>
                 </div>
             </div>
        </div>
		 
	 <div class="content">

        <div class="row" style="max-width:1200px;margin:auto;">
			<script>
			var hash = "<?=$id?>";
            var _category = "<?=$category_id?>";
            var uploader_user_id = "<?=$uploader_user_id?>";
            var login_username = "<?=$this->Info->login['username']?>";
			var avatar = "<?=$this->Info->login['profile']?>";
			</script>
            <div class="col-md-8 box-video">

                <div class="hpanel">
				
                    <div class="panel-body" style="padding:10px;">
                        <h3>
                            <?=$title?>
                        </h3>
					</div>
					
					<div class="panel-body">
                            <video class="video-js vjs-default-skin" controls autoplay
                                preload="auto" poster="<?=URL?>videos/<?=$image_file?>"
                                data-setup="{}" width="100%" height="380px">
                               <source src="<?=URL?>videos/<?=$video_file?>" type="video/mp4"/>
                            </video>
                    </div>
					
					<div class="panel-footer">
                <div id="video" style="margin-left:10px;margin-right:10px;"><span><h5><?php if($this->Info->login['id'] !== false){ ?> <a onclick="LikeOrUnlike(this)"><i class="pe-7s-like2"></i> <b id="like_text"><?php if($liked == 0){ echo 'LIKE'; }else{ echo 'UNLIKE'; } ?></b></a> / <?php }else{ echo '<i class="pe-7s-like2"></i> '; } ?><b id="likes"><?=$likes?></b> Likes
				<span style="float:right;"><i class="pe-7s-look"></i> <?=$views?></span></h5></span></div>
              </div>
            </div>
			
			<h2><i class="pe-7s-note2"></i> DESCRIPTION</h2>
			<div class="hpanel">
			<div class="panel-body">
				<b class="publish_text">Published on <i class="pe-7s-date"></i> <b><span id="date"><?=date('d.m.Y', $date)?></span> by <i class="pe-7s-user"></i> <a href="<?=URL?>home/Profile/<?=$username?>"><b><?=$username?></b></a> <span style="float:right">
				<?php
				if($this->Info->login['status'] && $this->Info->login['id'] != $uploader_user_id)
				{
					if($following == null)
					{
				    	echo '<button type="button" class="btn btn-outline btn-primary btn-xs" onclick="Follow(this)"><i class="pe-7s-paper-plane"></i> <span>FOLLOW</span></button>';
				    }
					else
					{
				    	echo '<button type="button" class="btn btn-outline btn-primary btn-xs following" onclick="UnFollow(this)"><i class="pe-7s-paper-plane"></i> <span>FOLLOWING</span></button>';
				    }
				}
				?>
				<img src="<?=URL?>asset/images/icons/facebook.png">&nbsp;&nbsp;<img src="<?=URL?>asset/images/icons/twitter.png">&nbsp;&nbsp;<img src="<?=URL?>asset/images/icons/googleplus.png">&nbsp;&nbsp;<img src="<?=URL?>asset/images/icons/linkedin.png">&nbsp;&nbsp;<a data-toggle="modal" data-target="#ShareEmbed"><img src="<?=URL?>asset/images/icons/embed.png" width="24" height="24"></a></span>
				</b></b>
				
				<div class="collapse out" id="collapseExample">
				<br/><?=$this->Info->AutoLinkText(nl2br($description))?><br/><br/>
                        <div id="tags" style="color:black"><i class="pe-7s-pin"></i> Category: <?=$category?></div>
                    
                </div>
                    <center style="margin-top:8px;"><button class="btn btn-default btn-sm" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                        SHOW MORE
                    </button></center></div>
				</div>
			
			  <h2><i class="pe-7s-comment"></i> COMMENTS</h2>
			
                <div class="hpanel">
<?php if($this->Info->login['status']){ ?>
                    <div class="panel-body">
					
					<div class="input-group"><input type="text" placeholder="Type your comment" class="form-control" id="comment_text"> <span class="input-group-btn"> <button type="button" class="btn btn-primary" onclick="COMMENT(this)">PUBLISH</button> </span></div>

					</div>
<?php } ?>
					 <div class="panel-body">
						
                        <?=$videoComments?>	
						
					  </div>
					
                 </div>
            </div>
            <div class="col-md-4 video-list">

                <div class="hpanel">
				
				<div class="panel-body" style="padding:10px;">
                        <div id="morevideosbox" style="margin-top:14px;margin-bottom:13px;"><h4>
                            MORE VIDEOS</div>
                        </h4>
					</div>
				     
					 <div class="more-videos">
					    <div class="panel-body">
							<?=$rightVideos?>
                        </div>

                     </div>
				
                   </div>
				</div>
            </div>
        </div>
		
	 <link href="http://vjs.zencdn.net/4.12/video-js.css" rel="stylesheet">
	<script src="http://vjs.zencdn.net/4.12/video.js"></script>