

<article class="span12 data-block nested">
						<div class="data-container">
							<header>
								<h2>Logi Systemowe</h2>
								<ul class="data-header-actions">
									<li>
										<form class="form-search">
											<div class="control-group">
												<div class="controls">
													<input class="search-query" type="text">
													<button class="btn" type="submit">Search</button>
												</div>
											</div>
										</form>
									</li>
								</ul>
							</header>
							<section>
							
								<!-- Tickets container -->
								<ul id="ticketsDemo" class="tickets">
								
									<!-- Tickets header -->
									<li class="ticket-header">
										<ul>
											<li class="ticket-header-ticket">Ticket</li>
											<li class="ticket-header-activity">Activity</li>
											<li class="ticket-header-priority">Priority</li>
											<li class="ticket-header-age">Age</li>
										</ul>
									</li>
									<!-- /Tickets header -->
									
									<!-- Tickets data -->
									
									<?php

$handle = fopen(SYSPATH."/logs/log.csv", "r");
$i=1;
while (($data = fgetcsv($handle, 5000, ",")) !== FALSE) {
$podmiana = explode(";",$data[0]);
if($podmiana[1] == 'INFO') { $class= 'success';}
elseif($podmiana[1] == 'ERROR' OR $podmiana[1] == 'WARNING' OR $podmiana[1] == 'ERRORLEVEL') { $class= 'important';} 
elseif($podmiana[1] == 'DEBUG') { $class= 'warning';} else { $class=''; }
echo '<li class="ticket-data">
										<div class="ticket-overview">
											<ul>
												<li class="ticket-data-label">#'.$i.'</li>
												<li class="ticket-data-activity">
													<a href="#" data-toggle="collapse" data-parent="#ticketsDemo" data-target="#ticket'.$i.'">'.$podmiana[3].'</a>
													<p>Nowy Błąd</p>
												</li>
												<li class="ticket-data-priority"><span class="label label-'.$class.'">'.$podmiana[1].'</span></li>
												<li class="ticket-data-age">'.$podmiana[0].'</li>
											</ul>
										</div>
										<div class="ticket-details collapse fade" id="ticket'.$i.'">
											<dl>
												<dt>Wstawiony:</dt>
												<dd><strong>'.$podmiana[0].'</strong></dd>
												<dt>Typ Błędu:</dt>
												<dd><strong>'.$podmiana[1].'</strong></dd>
												<dt>Linia Błędu:</dt>
												<dd><strong>'.@$podmiana[4].'</strong></dd>
												<dt class="clear">Scieżka:</dt>
												<dd style="width:70%;"><strong>'.@$podmiana[5].'</strong></dd>
												
											</dl>
											<h5>Opis</h5>
											<p>'.$podmiana[3].'</p>
											<a href="#" class="btn btn-alt btn-primary">Usuń Błąd</a>
										</div>
									</li>';
$i++;
}




?>
								</ul>
							</section>
						</div>
					</article>
                    
