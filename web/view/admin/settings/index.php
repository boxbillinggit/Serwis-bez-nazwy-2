<div class="row">
		<div class="col-md-12">
			
			<div class="panel panel-primary" data-collapsed="0">
			
				<div class="panel-heading">
					<div class="panel-title">
						<?php echo Jezyk::get("#settings"); ?>
					</div>
					
					<div class="panel-options">
						<a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
						<a href="#" data-rel="reload"><i class="entypo-arrows-ccw"></i></a>
					</div>
				</div>
				<form role="form" method="post" class="form-horizontal form-groups-bordered validate" action="" novalidate="novalidate">
				<div class="panel-body">
		
					<div class="form-group">
						<label for="field-1" class="col-sm-3 control-label">Site title</label>
						
						<div class="col-sm-5">
							<input type="text" class="form-control" id="field-1" value="Neon">
						</div>
					</div>
	
					<div class="form-group">
						<label for="field-2" class="col-sm-3 control-label">Tagline</label>
						
						<div class="col-sm-5">
							<input type="text" class="form-control" id="field-2" value="Bootstrap Admin Theme">
							<span class="description">Few words that will describe your site.</span>
						</div>
					</div>
	
					<div class="form-group">
						<label for="field-3" class="col-sm-3 control-label">Site URL</label>
						
						<div class="col-sm-5">
							<input type="text" class="form-control" name="site-url" id="field-3" data-validate="required,url" value="http://exampledomain.com/neon">
						</div>
					</div>
	
					<div class="form-group">
						<label for="field-4" class="col-sm-3 control-label">Email address</label>
						
						<div class="col-sm-5">
							<input type="text" class="form-control" name="email" id="field-4" data-validate="required,email" value="john@doe.com">
							<span class="description">Here you will receive site notifications.</span>
						</div>
					</div>
					
				</div>
			</form>
			</div>
		
		</div>
	</div>