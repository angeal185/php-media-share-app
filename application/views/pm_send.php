<div class="small-header">
        <div class="hpanel">
            <div class="panel-body">
                <div id="hbreadcrumb" class="pull-right">
                    <ol class="hbreadcrumb breadcrumb">
                        <li><a href="index.html">Profile</a></li>
						<li><a href="index.html">Private Messages</a></li>
                        <li class="active">
                            <span>Send PM </span>
                        </li>
                    </ol>
                </div>
                <h2 class="font-light m-b-xs">
                    <i class="pe-7s-chat"></i> Private Messages
                </h2>
                <small>Send and receive private messages to communicate with other site users.</small>
            </div>
        </div>
    </div>
	
<div class="content animate-panel">
            
    <div class="row">
            <div class="col-lg-12">
            
            <div class="hpanel" style="margin-top:0px;margin-bottom:10px;">
                <div class="panel-heading hbuilt">
                    SEND PM TO ANOTHER SITE USER
                </div>
                <div class="panel-body">
                    <div class="row">
					    <div class="col-md-12">
					    <?php
						if($msg['error'] !== false)
						{
							echo "<span style='color:red;'>".$msg['error']."</span><br /><br />";
						}
						else if($msg['success'] !== false)
						{
							echo "<span style='color:green;'>".$msg['success']."</span><br /><br />";
						}
						?>
						</div>
                        <form method="POST">
                            <div class="col-md-6">
                	            <div class="input-group m-b"><span class="input-group-addon">TO</span> <input type="text" name="to" class="form-control" placeholder="Username of the reciver" value="<?=$this->Info->Auto($_POST,'to',$to)?>" /></div>
                	        </div>
                            <div class="col-md-6">
                	            <div class="input-group m-b"><span class="input-group-addon">FROM</span> <input type="text" class="form-control" value="<?=$this->Info->login['username']?>" disabled></div>
                	        </div>
                        
                            <div class="col-md-12">
                                <input type="text" name="title" placeholder="Title" class="form-control input-lg m-b" value="<?=$this->Info->Auto($_POST, 'title', null)?>" />
                                <textarea class="form-control input-lg m-b" placeholder="Message" rows="6" name="message"><?=$this->Info->Auto($_POST, 'message', null)?></textarea>
                                <button type="submit" class="btn btn-success btn-outline btn-block btn-lg">SEND</button>
					        </div>
                        </form>
					</div>
                </div>
				
                <div class="panel-footer">
                    BY SENDING PRIVATE MESSAGE, YOU ARE AGREEING WITH SITE <a href="rulesandterms">RULES AND TERMS</a>
                </div>
            </div>
            <a href="PM" type="button" class="btn btn-primary btn-block"><i class="pe-7s-back"></i> RETURN TO INBOX</a>
        </div>
    </div>
</div>