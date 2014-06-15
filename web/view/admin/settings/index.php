<form role="form" method="post" class="form-horizontal form-groups-bordered validate" action="" novalidate>
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
						<label for="field-1" class="col-sm-3 control-label"><?php echo Jezyk::get("#site_name"); ?></label>
						
						<div class="col-sm-5">
							<input type="text" class="form-control" id="field-1" name="site_name" value="<?php echo Conf::get("site_name"); ?>">
						</div>
					</div>
	
					<div class="form-group">
						<label for="field-2" class="col-sm-3 control-label"><?php echo Jezyk::get("#description"); ?></label>
						
						<div class="col-sm-5">
							<input type="text" class="form-control" id="field-2" name="description" value="<?php echo Conf::get("description"); ?>">
						</div>
					</div>
	
					<div class="form-group">
						<label for="field-3" class="col-sm-3 control-label"><?php echo Jezyk::get("#url"); ?></label>
						
						<div class="col-sm-5">
							<input type="text" class="form-control" name="url" id="field-3" data-validate="required,url" value="<?php echo Conf::get("url"); ?>">
						</div>
					</div>
	
					<div class="form-group">
						<label for="field-4" class="col-sm-3 control-label"><?php echo Jezyk::get("#email"); ?></label>
						
						<div class="col-sm-5">
							<input type="text" class="form-control" name="email" id="field-4" data-validate="required,email" value="<?php echo Conf::get("email"); ?>">
						</div>
					</div>
                    
                    <div class="form-group">
						<label class="col-sm-3 control-label"><?php echo Jezyk::get("#templates"); ?></label>
						
						<div class="col-sm-5">
							<select class="form-control" name="templates">
								<?php
								if ($handle = opendir(DOCROOT.'/web/view/')) {
									while (false !== ($entry = readdir($handle))) {
										if ($entry != "." && $entry != ".." && $entry != "admin") {
											echo "<option value='$entry'>$entry</option>";
										}
									}
									closedir($handle);
								}
								?>
							</select>
						</div>
					</div>
                    
                    <div class="form-group">
						<label class="col-sm-3 control-label"><?php echo Jezyk::get("#select_language"); ?></label>
						
						<div class="col-sm-5">
							<select class="form-control" name="language">
								<?php
									$db = DB::query(Database::SELECT, 'SELECT * FROM tlumaczenia WHERE active=1')->as_object(TRUE)->execute();
									foreach ($db as $lang) {
										echo '<option value="'.$lang->icon.'">'.$lang->name.'</option>';	
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
						<input type="radio" name="gzip" id="optionsRadios1" value="true" <?php if(Conf::get("gzip") == 'true') { echo ' checked=""'; } ?>> Gzip On
                        </div>
                        <div class="radio">
						<input type="radio" name="gzip" id="optionsRadios1" value="false" <?php if(Conf::get("gzip") == 'false') { echo ' checked=""'; } ?>> Gzip Off
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
						
							
							
						</div>
					</div>
					
				</div>
			
			</div>
		</div>
	</div>
    
    <div class="form-group default-padding">
		<button type="submit" class="btn btn-success"><?php echo Jezyk::get("#save_change"); ?></button>
	</div>
        </form>
        
		</div>
	</div>