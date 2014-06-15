<h2><?php echo Jezyk::get("#logs"); ?></h2>

<div class="row">
	
		<div class="col-md-12">
		
			<div class="panel panel-primary">
				
				<div class="panel-heading">
					<div class="panel-title">
						<h4>
							<?php echo Jezyk::get("#logs_system"); ?> 
							<span class="badge badge-danger"><?php echo count($logs); ?></span>
						</h4>
					</div>
				</div>
				
				<div class="panel-body no-padding">
					
					<ul class="comments-list">
						<?php 
						foreach($logs as $logi) {
						?>
						<li>
							<div class="comment-checkbox">
								<div class="checkbox checkbox-replace neon-cb-replacement">
									<label class="cb-wrapper"><input type="checkbox"><div class="checked"></div></label>
								</div>
							</div>
							
							<div class="comment-details">
								
								<div class="comment-head">
									<a href="#"><?php echo $logi->DATETIME; ?></a> <div class="label label-<?php echo strtolower($logi->ERRORLEVEL); ?>"><?php echo $logi->ERRORLEVEL; ?> : <?php echo Jezyk::get("#Line"); ?> <?php echo $logi->LINE; ?></div>
								</div>
								
								<p class="comment-text">
                                    <?php echo $logi->VALUE; ?>
								</p>
								
								<div class="comment-footer">
									
									<div class="comment-time">
										<?php echo $logi->FILE; ?>
									</div>
									
									<div class="action-links">
                                    
										<a href="#" class="delete">
											<i class="entypo-cancel"></i>
											Delete
										</a>
                                        
									</div>
									
								</div>
								
							</div>
						</li>
                        <?php } ?>
						
					
						
						
						
						
					</ul>
					
				</div>
			
			</div>
		
		</div>
	
	</div>