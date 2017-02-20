<div class="small-header">
        <div class="hpanel">
            <div class="panel-body">
                <div id="hbreadcrumb" class="pull-right">
                    <ol class="hbreadcrumb breadcrumb">
                        <li><a href="index.html">Profile</a></li>
						<li><a href="index.html">Private Messages</a></li>
                        <li class="active">
                            <span>Read PM </span>
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

<div class="hpanel forum-box">

<div class="panel-heading">
                <span class="pull-right">
                    <i class="fa fa-clock-o"> </i> Recived on <?=date('Y.m.d', $info['date']) ?>, 10:22 AM
                </span>
    <span class="f"> Private Messages > Read PM > <span class="text-success"><?=$info['username']?></span> </span>
</div>
<div class="panel-body">
    <div class="media">
        <div class="media-image pull-left">
            <img src="<?=URL?>profiles/avatar/<?=$info['profile']?>" alt="profile-picture">
            <div class="author-info">
                <strong><?=$info['username']?></strong><br>
				<i><?=$info['FirstName']?> <?=$info['LastName']?></i><br>
				<a href="<?=URL?>home/Profile/<?=$info['username']?>">View Profile</a><br>
				<hr style="margin:5px;" />
				<i class="pe-7s-clock"></i> 10:22
				<br />
                <i class="pe-7s-date"></i> <?=date('Y.m.d', $info['date']) ?>
            </div>
        </div>
        <div class="media-body">
            <h5><?=$info['title']?></h5>
			<hr />
            <?=$info['message']?>
            <br/>
        </div>
		<hr />
    </div>
</div>


        </div>
    </div>

</div>
</div>