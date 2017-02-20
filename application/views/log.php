<div class="small-header">
        <div class="hpanel">
            <div class="panel-body">
                <div id="hbreadcrumb" class="pull-right">
                    <ol class="hbreadcrumb breadcrumb">
                        <li class="active">
                            <span>Actions LOG </span>
                        </li>
                    </ol>
                </div>
                <h2 class="font-light m-b-xs">
                    <i class="pe-7s-pin"></i> Actions LOG
                </h2>
                <small>Take care of your profile, list your actions, make sure your profile is secured.</small>
            </div>
        </div>
    </div>
	
<div class="content animate-panel">

<div class="row">
    <div class="col-md-12">
        <div class="hpanel">
            <div class="panel-body">
                <div class="table-responsive">
				    <?php
					if($log === null)
					{
						echo 'Do not have information in the system.';
					}
					else
					{
					?>
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>Action</th>
                            <th>Date</th>
							<th>IP Address</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?=$log?>
                        </tbody>
                    </table>
					<?php
					}
					?>
                </div>
            </div>
        </div>
    </div>
	
	<?=$pagination?>
	
</div>

</div>