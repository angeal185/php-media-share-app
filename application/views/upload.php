	<script>
function uploadFile(){
	var video = $("#video").prop('files')[0];
	var background = $("#video_background").prop('files')[0];
	var title = $("#video_title").val();
	var description = $("#video_description").val();
	var category = $("#video_category").val();
	var enable_comments = $("#enable_comments").val();
	
	var formdata = new FormData();
	formdata.append("video", video);
	formdata.append("background", background);
	formdata.append("title", title);
	formdata.append("description", description);
	formdata.append("enable_comments", enable_comments);
	formdata.append("category", category);
    formdata.append("security", security);

	var ajax = new XMLHttpRequest();
	ajax.upload.addEventListener("progress", progressHandler, false);
	ajax.addEventListener("load", completeHandler, false);
	ajax.addEventListener("error", errorHandler, false);
	ajax.addEventListener("abort", abortHandler, false);
	ajax.open("POST", "<?=URL?>post/upload");
	ajax.send(formdata);
}
function progressHandler(event){
	$("#loaded_n_total").text("Uploaded "+event.loaded+" bytes of "+event.total);
	var percent = (event.loaded / event.total) * 100;
	$("#progressBar").width(Math.round(percent) + "%");
	$("#status").text(Math.round(percent)+"% UPLOADED / PLEASE WAIT...");
}
function completeHandler(event){
	$("#status").html(event.target.responseText);
	$("#progressBar").width("0px");
}
function errorHandler(event){
	$("#status").text("Upload Failed");
}
function abortHandler(event){
	$("#status").text("Upload Aborted");
}

 $(function() {
     $("#video").change(function (){
		var fileName = $(this).val();
		
		if(fileName != "")
		{
			$('#video_category').removeAttr('disabled');
		    $('#video_title').removeAttr('disabled');
		    $('#video_description').removeAttr('disabled');
		    $('#video_submit').removeAttr('disabled');
			$('#video_background').removeAttr('disabled');
			$('#enable_comments').removeAttr('disabled');
			$('#no_file').attr('hidden', 'hidden');
			Scroll();
		}else{
			$('#video_category').attr('disabled', 'disabled');
		    $('#video_title').attr('disabled', 'disabled');
		    $('#video_description').attr('disabled', 'disabled');
		    $('#video_submit').attr('disabled', 'disabled');
			$('#video_background').attr('disabled', 'disabled');
			$('#enable_comments').attr('disabled', 'disabled');
			$('#no_file').removeAttr('hidden');
		}
     });
  });
  
function Scroll() {
    window.scrollTo(0,document.body.scrollHeight);
}
</script>

<div>

   <div class="small-header">
        <div class="hpanel">
            <div class="panel-body">
                <div id="hbreadcrumb" class="pull-right">
                    <ol class="hbreadcrumb breadcrumb">
                        <li><a href="index.html">Dashboard</a></li>
                        <li class="active">
                            <span>Upload </span>
                        </li>
                    </ol>
                </div>
                <h2 class="font-light m-b-xs">
                    <i class="pe-7s-cloud-upload"></i> Upload video
                </h2>
                <small>Share your video in seconds. Just browse & select file the file from your computer!</small>
            </div>
        </div>
    </div>

   <div class="content animate-panel" data-child="hpanel" data-effect="fadeInDown">
   
        <div class="modal fade" id="upload" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="color-line"></div>
                    <div class="modal-header text-center">
                        <h4 class="modal-title">VIDEO UPLOAD</h4>
                        <small class="font-bold"><div class="progress m-t-xs full progress-striped active">
                        <div style="width: 0%" max="100" value="50" role="progressbar" class="progress-bar progress-bar-info" id="progressBar"></div>
					</div></small>
                    </div>
                    <div class="modal-body" style="text-align:center;">
                        <p><h3 id="status"></h3>
                        <p id="loaded_n_total"></p></p>
                    </div>
                    <div class="modal-footer" style="text-align:center;">
                        <button type="button" class="btn btn-primary" data-toggle="collapse" data-target="#collapsevideoupload" aria-expanded="false" aria-controls="collapsevideoupload" data-dismiss="modal">CLOSE</button>
                    </div>
                </div>
            </div>
        </div>
		
         <div class="row">
			<div class="col-lg-8">
                <div class="hpanel hgreen">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <h4>UPLOAD NEW VIDEO</h4>
								<center><i class="pe-7s-cloud-upload fa-3x"></i></center>
								<form id="upload_form" enctype="multipart/form-data" method="post">
								<input type="file" name="video" id="video" class="btn btn-block btn-outline btn-default" value="UPLOAD VIDEO" />
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer">
                        SELECT VIDEO FROM YOUR COMPUTER <span style="color:red;float:right">THE MAXIMUM FILE SIZE IS <b>100 MB</b></span>
                    </div>
                </div>
            </div>
			
			<div class="col-lg-4">
                <div class="hpanel hgreen">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-sm-12" style="margin-top:15.5px;margin-bottom:15.5px;">
                                <div class="input-group"><span class="input-group-addon">UPLOADER</span> <input type="text" class="form-control" value="<?=$this->Info->login['username']?>" disabled></div>
								<br />
								<div class="input-group"><span class="input-group-addon">TIME/DATE</span> <input type="text" placeholder="Username" class="form-control" value="<?=date('h:i / d.m.Y', time())?>" disabled></div>	
                            </div>
                        </div> 
                    </div>
					<div class="panel-footer">
                        VIDEO UPLOAD DETAILS
                    </div>
                </div>
            </div>
		</div>
			
		<section id="details">	
		<div class="row">
			<div class="col-lg-12">
                <div class="hpanel hgreen">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-sm-12">
                               <h2>VIDEO DETAILS <span id="no_file">/ <b>NO FILE SELECTED</b></span></h2>
							   <hr width="400px" style="float:left;margin-top:10px;" />
							   <br />							   <br />
							   <div class="input-group m-b"><input type="text" class="form-control" placeholder="Title" disabled id="video_title"><span class="input-group-addon">TYPE VIDEO TITLE (4-60 SYMBOLS) <span style="color:red;">*</span></span></div>
							   <div class="input-group m-b"><input type="file" name="video_background" id="video_background" class="btn btn-block btn-outline btn-default" disabled /><span class="input-group-addon">BACKGROUND IMAGE <span style="color:red;">*</span></span></div>
							   <div class="input-group m-b">
							   <select class="form-control m-b" id="video_category" disabled>
							   <?=$categories?>
                               </select><span class="input-group-addon">SELECT 1 CATEGORY <span style="color:red;">*</span></span></div>
							   <div class="input-group m-b typ"><textarea class="form-control" placeholder="Description" disabled id="video_description"></textarea><span class="input-group-addon">ENTER DESCRIPTION (10-400 SYMBOLS) <span style="color:red;">*</span></span></div>
							   <div class="input-group m-b">
							   <select class="form-control m-b" id="enable_comments" disabled>
							   <option value="1">ENABLED (RECOMMENDED)</option>
							   <option value="0">DISABLED</option>
                               </select><span class="input-group-addon">ALLOW WRITING COMMENTS <span style="color:red;">*</span></span></div>
							   <button type="button" class="btn btn-success btn-outline btn-block btn-lg" disabled id="video_submit" onclick="uploadFile()" data-toggle="modal" data-target="#upload">PUBLISH</button>
                            </div>
                        </div>
                    </div>
					<div class="panel-footer">
                        BY PUBLISHING VIDEO, YOU ARE AGREEING WITH SITE <a href="rulesandterms">RULES AND TERMS</a>
                    </div>
                </div>
            </div>
	    </div>
		</section>
	</div>
	</div>