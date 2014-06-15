<form role="form" method="post" class="form-horizontal form-groups-bordered validate" action="" novalidate="novalidate">
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
                    
                    <div class="form-group">
						<label class="col-sm-3 control-label"><?php echo Jezyk::get("#select_language"); ?></label>
						
						<div class="col-sm-5">
							<select class="form-control">
								<?php
									$db = DB::query(Database::SELECT, 'SELECT * FROM tlumaczenia WHERE active=1')->as_object(TRUE)->execute();
									foreach ($db as $lang) {
										echo '<option value="'.$lang->name.'">'.$lang->name.'</option>';	
									}
								?>
							</select>
						</div>
					</div>
					
				</div>
                
                
                
			
            
			</div>
		
        
        <div class="row">
		<div class="col-md-6">
			
			<div class="panel panel-primary" data-collapsed="0">
			
				<div class="panel-heading">
					<div class="panel-title">
						<?php echo Jezyk::get("#Gzip"); ?>
					</div>
					
					<div class="panel-options">
						<a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
						<a href="#" data-rel="reload"><i class="entypo-arrows-ccw"></i></a>
					</div>
				</div>
				
				<div class="panel-body">
	
					<div class="form-group">
						<label for="field-5" class="col-sm-5 control-label">Gzip</label>
						
						<div class="col-sm-5">
                        
                        <div class="radio">
						<input type="radio" name="gzip" id="optionsRadios1" value="true"> Gzip On
                        </div>
                        <div class="radio">
						<input type="radio" name="gzip" id="optionsRadios1" value="false" checked=""> Gzip Off
                        </div>
							
							
						</div>
					</div>
				
				</div>
				
			</div>
		
		</div>
		
		
		<div class="col-md-6">
			
			<div class="panel panel-primary" data-collapsed="0">
			
				<div class="panel-heading">
					<div class="panel-title">
						Date and Time
					</div>
					
					<div class="panel-options">
						<a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
						<a href="#" data-rel="reload"><i class="entypo-arrows-ccw"></i></a>
					</div>
				</div>
				
				<div class="panel-body">
	
					<div class="form-group">
						<label for="field-3" class="col-sm-5 control-label">Date format</label>
						
						<div class="col-sm-5">
						
							<div class="radio radio-replace neon-cb-replacement checked">
								<label class="cb-wrapper"><input type="radio" id="rd-1" name="radio1" checked=""><div class="checked"></div></label>
								<label>March 27, 2014</label>
							</div>
							
							<div class="radio radio-replace neon-cb-replacement">
								<label class="cb-wrapper"><input type="radio" id="rd-2" name="radio1"><div class="checked"></div></label>
								<label>03/27/2014</label>
							</div>
							
							<div class="radio radio-replace neon-cb-replacement">
								<label class="cb-wrapper"><input type="radio" id="rd-3" name="radio1"><div class="checked"></div></label>
								<label>2014/03/27</label>
							</div>
							
							<div class="radio radio-replace neon-cb-replacement">
								<label class="cb-wrapper"><input type="radio" id="rd-4" name="radio1"><div class="checked"></div></label>
								<label>
									Custom format: 
									<input type="text" class="form-control input-sm form-inline" value="d-m-Y" style="width: 70px; display: inline-block;">
									<p class="description">Read more about <a href="http://php.net/date" target="_blank">date format</a></p>
								</label>
							</div>
							
						</div>
					</div>
					
				</div>
			
			</div>
		</div>
	</div>
        </form>
        
		</div>
	</div>