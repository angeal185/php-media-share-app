<div class="small-header">
        <div class="hpanel">
            <div class="panel-body">
                <div id="hbreadcrumb" class="pull-right">
                    <ol class="hbreadcrumb breadcrumb">
                        <li class="active">
                            <span>Private messages </span>
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
            
            <div class="col-md-12">
			
		       <h1><span class="text-success"><i class="pe-7s-mail-open-file"></i> PM</span> / Inbox</h1>
			
                <div class="hpanel">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-6 m-b-md">
                                <div class="btn-group">
								    <button class="btn btn-default btn-sm" onclick="location.href='PM_send';"><i class="fa fa-plus"></i> New</button>
                                    <button class="btn btn-default btn-sm" onclick="location.reload();"><i class="fa fa-refresh"></i> Refresh</button>
                                    <button class="btn btn-default btn-sm" onclick="SeenMessages()"><i class="fa fa-eye"></i></button>
                                    <button class="btn btn-default btn-sm" onclick="DeleteMessages()"><i class="fa fa-trash-o"></i></button>
                                    <button class="btn btn-default btn-sm" onclick="MarkAll()"><i class="fa fa-check"></i></button>
                                </div>
                            </div>
                            <div class="col-md-6 mailbox-pagination m-b-md">
                                <div class="btn-group">
								    <?php
									if($StartPM > 0)
									{
										echo '<a href="'.URL.'home/PM/'.($StartPM - $PerPage).'"><button class="btn btn-default btn-sm"><i class="fa fa-arrow-left"></i></button></a>';
									}
									else
									{
										echo '<a><button class="btn btn-default btn-sm" disabled><i class="fa fa-arrow-left"></i></button></a>';
									}
								    
									$count = ($StartPM + 1)*$PerPage;
									
									if($count < $CountPM)
									{
										echo '<a href="'.URL.'home/PM/'.($StartPM + $PerPage).'"><button class="btn btn-default btn-sm"><i class="fa fa-arrow-right"></i></button></a>';
									}
									else
									{
										echo '<a><button class="btn btn-default btn-sm" disabled><i class="fa fa-arrow-right"></i></button></a>';
									}
									?>
                                    
                                    
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
								<?php
								$unreaded = 0;
								
								if($CountPM > 0)
								{
									echo '<table id="pm" class="table table-hover table-mailbox"><tbody>';
									
									while($info = mysql_fetch_array($list))
								    {
								    	if($info['view'] == 0)
								    	{
								    		echo '<tr class="unread">';
											
											$unreaded++;
								    	}
								    	else
								    	{
								    		echo '<tr>';
								    	}
								    	
								    	echo '<td class=""><div class="checkbox checkbox-single"><input type="checkbox" id="select_msg" value="'.$info['id'].'"><label></label></div></td><td><a href="'.URL.'home/Profile/'.$info['username'].'">'.$info['username'].'</a>';
								    	
								    	if($info['type'] == 1)
								    	{
								    		echo '<span class="label label-success">Administrator</span>';
								    	}
								    	
                                        echo '</td><td><a href="'.URL.'home/PM_message/'.$info['id'].'">'.$info['title'].'</a></td><td class="text-right mail-date">'.date('Y.m.d', $info['date']).'</td></tr>';
								    }
									
									echo '</tbody></table>';
								}
								else
								{
									echo '<div class="alert alert-info"><i class="fa fa-info"></i> &nbsp;YOUR PROFILE PM INBOX IS EMPTY</div>';
								}
								?>
                        </div>
                    </div>
                    <div class="panel-footer">
                        <i class="fa fa-eye"> </i> 0 unread in this list
                    </div>
                </div>
            </div>
        </div>

    </div>