<h2><?php echo Jezyk::get("#logs"); ?></h2>

<div class="row">
	
		<div class="col-md-12">
		
			<div class="panel panel-primary">
				
				<div class="panel-heading">
					<div class="panel-title">
						<h4>
							<?php echo Jezyk::get("#logs_system"); ?> 
							<span class="badge badge-danger">73</span>
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
									<a href="#"><?php echo $logi->DATETIME; ?></a> <div class="label label-<?php echo $logi->ERRORLEVEL; ?>"><?php echo $logi->ERRORLEVEL; ?></div>
								</div>
								
								<p class="comment-text">
									Servants contempt as although addition dashwood is procured. Interest in yourself an do of numerous feelings cheerful confined. 
								</p>
								
								<div class="comment-footer">
									
									<div class="comment-time">
										Today - 21:05
									</div>
									
									<div class="action-links">
										
										<a href="#" class="approve">
											<i class="entypo-check"></i>
											Approve
										</a>
										
										<a href="#" class="delete">
											<i class="entypo-cancel"></i>
											Delete
										</a>
										
										
										<a href="javascript:;" onclick="jQuery('#modal-edit-comment').modal('show');" class="edit">
											<i class="entypo-pencil"></i>
											Edit
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