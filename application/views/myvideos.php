    <div class="small-header">
        <div class="hpanel">
            <div class="panel-body">
                <div id="hbreadcrumb" class="pull-right">
                    <ol class="hbreadcrumb breadcrumb">
                        <li class="active">
                            <span>My Videos</span>
                        </li>
                    </ol>
                </div>
                <h2 class="font-light m-b-xs">
                    <i class="pe-7s-box2"></i> MY VIDEOS
                </h2>
                <small>Browse all your uploaded videos. You can easily delete or edit them.</small>
            </div>
        </div>
    </div>

<div class="content animate-panel">

        <?php
	       if($msg['error'] !== false)
           {
                echo '
			        <div class="alert alert-danger">
                        <i class="fa fa-remove"></i> &nbsp;'.$msg['error'].'
                    </div>';
           }
           else if($msg['success'] !== false)
           {
                echo '
				    <div class="alert alert-success">
                        <i class="fa fa-check"></i> &nbsp;'.$msg['success'].'
                    </div>';
           }
       ?>
	   
        <div class="row feed">
		
		<?php
	    $i = 0;
		
	    while($info = mysql_fetch_array($videos_list))
		{
			echo '<div class="col-lg-4">
             <div class="hpanel">
		      <div class="panel-heading hbuilt" id="auto-text">
			  <div class="panel-tools">
                    <a data-toggle="modal" data-target="#EditVideo" onclick="EditVideo(\''.$info['id'].'\', \''.mysql_real_escape_string($info['title']).'\', \''.mysql_real_escape_string($info['description']).'\', '.$info['enable_comments'].')"><i class="fa fa-pencil"></i></a>
                    <a onclick="DeleteVideo('.$info['id'].', this)"><i class="fa fa-times"></i></a>
                </div>
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
                <span><i class="pe-7s-look"></i> '.$info['views'].' | <i class="pe-7s-date"></i> '.date('Y.m.d', $info['date']).' | <i class="pe-7s-user"></i> <a href="'.URL.'home/Profile/'.$info['username'].'">'.$info['username'].'</a></span>
              </div>
             </div>
            </div>';
			$i++;
		}
	    
		if($i == 0)
		{
			echo '<h1>Ohh ! You don\' not have videos. Upload your first video from <a href="upload">here</a></h1><br /><div class="alert alert-info"><i class="fa fa-info"></i> &nbsp;YOU DO NOT HAVE ANY UPLOADED VIDEOS YET</div>';
		}
	    ?>
		
        </div>
		
		<div class="modal fade" id="EditVideo" tabindex="-1" role="dialog" aria-hidden="true">
             <div class="modal-dialog">
			  <form method="POST">
                 <div class="modal-content">
                     <div class="color-line"></div>
                     <div class="modal-header text-center" style="padding-top:20px;padding-bottom:15px;">
                         <h4 class="modal-title">VIDEO EDIT</h4>
                     </div>
                     <div class="modal-body">
                         <center>
						 <input type="hidden" name="security" value="<?=$this->Info->security?>"/>
						 <input type="hidden" name="edit_video_id" />
						<h3><i class="pe-7s-pen"></i> TITLE</h3>
						<input type="text" class="form-control m-b" name="edit_title" />
						<h3><i class="pe-7s-note2"></i> DESCRIPTION</h3>
						<textarea class="form-control m-b" rows="3" name="edit_description"></textarea>
						<h3><i class="pe-7s-comment"></i> COMMENTS</h3>
					    <select class="form-control m-b" name="enable_comments">
						<option value="1">ENABLED (RECOMMENDED)</option>
						<option value="0">DISABLED</option>
                        </select>
						</center>
                     </div>
                     <div class="modal-footer">
                         <button type="button" class="btn btn-default" data-dismiss="modal">CANCEL</button>
                         <button type="submit" class="btn btn-primary">SAVE CHANGES</button>
                     </div>
                 </div>
			  </form>
             </div>
         </div>
		
		<?=$pagination?>
		
    </div>	