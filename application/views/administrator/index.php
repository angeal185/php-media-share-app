<div class="small-header">
        <div class="hpanel">
            <div class="panel-body">
                <div id="hbreadcrumb" class="pull-right">
                    <ol class="hbreadcrumb breadcrumb">
                        <li class="active">
                            <span>Administration panel </span>
                        </li>
                    </ol>
                </div>
                <h2 class="font-light m-b-xs">
                    <i class="pe-7s-power"></i> Administration Panel </h2>
                <small>Edit & delete videos, manage site data and users</small>
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
		    		<h3><i class="pe-7s-drawer"></i> CATEGORY</h3>
		    		<select class="form-control m-b" name="video_category">
		    			<?=$categoriesSelect?>
                    </select>
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

<div class="modal fade" id="AddCategory" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
	    <form method="POST">
            <div class="modal-content">
                <div class="color-line"></div>
                <div class="modal-header text-center" style="padding-top:20px;padding-bottom:15px;">
                    <h4 class="modal-title">ADD NEW CATEGORY</h4>
                </div>
                <div class="modal-body">
                    <center>
					  <input type="hidden" name="security" value="<?=$this->Info->security?>"/>
					  <h3><i class="pe-7s-pen"></i> CATEGORY NAME</h3>
					  <input type="text" class="form-control m-b" name="add_category" />
				    </center>
                     </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">CANCEL</button>
                    <button type="submit" class="btn btn-primary">ADD & SAVE</button>
                </div>
            </div>
	    </form>
    </div>
</div>
		 
<div class="row">
    <div class="col-lg-12">
        <div class="hpanel">
            <ul class="nav nav-tabs">
                <li class="active"><a data-toggle="tab" href="#tab-1"> Manage Videos</a></li>
                <li class=""><a data-toggle="tab" href="#tab-2">Manage Users</a></li>
            </ul>
            <div class="tab-content">
                <div id="tab-1" class="tab-pane active">
                    <div class="panel-body">
					    <div class="col-md-12">
                        <h4 style="margin-bottom:16px;"><strong>Edit / Delete Site Videos</strong> | Just type the Video ID</h4>
                        <input type="text" placeholder="Video ID" class="form-control m-b" name="video_edit_id">
						</div>
						<div class="col-md-6">
					    <button type="button" data-target="#EditVideo" onclick="AdminEditVideo()" class="btn w-xs btn-success btn-block"><i class="fa fa-pencil"></i> EDIT VIDEO</button>
						</div>
						<div class="col-md-6">
						<button type="button" onclick="AdminDeleteVideo(this)" class="btn w-xs btn-danger2 btn-block"><i class="fa fa-trash"></i> DELETE VIDEO</button>
						</div>
                    </div>
					
                </div>
                <div id="tab-2" class="tab-pane">
                    <div class="panel-body">
					    <div class="col-md-12">
                            <h4 style="margin-bottom:16px;"><strong>Block / Delete Site Users</strong> | Just type the UserName</h4>
                            <input type="text" placeholder="Username" class="form-control m-b" name="delete_username">
						</div>
						<div class="col-md-12">
						    <button type="button" class="btn w-xs btn-danger2 btn-block" onclick="AdminDeleteUser(this)"><i class="fa fa-trash"></i> DELETE USER</button>
                        </div>
					</div>
                </div>
            </div>
			
			<div class="panel-body">
			<h3>Manage Categories</h3>
			<div class="table-responsive">
                <table cellpadding="1" cellspacing="1" class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Category</th>
						<th>Videos</th>
                        <th>Delete</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?=$categoriesTable?>
                    </tbody>
                </table>
				<button type="button" data-toggle="modal" data-target="#AddCategory" onclick="AddCategory();" class="btn w-xs btn-primary btn-block"><i class="fa fa-plus"></i> ADD NEW CATEGORY</button>
            </div>
			</div>
        </div>
     </div>
   </div>
</div>	

<script src="<?=URL?>asset/scripts/administrator.js" type="text/javascript"></script>