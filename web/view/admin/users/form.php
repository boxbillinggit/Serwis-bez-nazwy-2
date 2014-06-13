<article class="span12 data-block">
						<div class="data-container">
							<header>
								<h2>Użytkownicy - Edycja Użytkownika</h2>
							</header>
                            
                            
							<section class="tab-content">

																					<form action="/admin/users_edit/<?php echo Request::instance()->param('id'); ?>" class="form-horizontal" method="POST">
                                                                                        <fieldset>
                                                                                            <legend>Edycja Użytkownika</legend>
                                                                                            <div class="control-group">
                                                                                                <label class="control-label" for="input">E-Mail</label>
                                                                                                <div class="controls">
                                                                                                    <input id="input" class="input-xxlarge" name="email" type="text" value="<?php echo $users[0]['email']; ?>">
                                                                                                </div>
                                                                                            </div>
                                                                                            
                                                                                            <div class="control-group">
                                                                                                <label class="control-label" for="input">Nazwa Użytkownika</label>
                                                                                                <div class="controls">
                                                                                                    <input id="input" class="input-xxlarge" name="username" type="text" value="<?php echo $users[0]['username']; ?>">
                                                                                                </div>
                                                                                            </div>
                                                                                            
                                                                                            <div class="control-group">
                                                                                                <label class="control-label" for="input">Hasło</label>
                                                                                                <div class="controls">
                                                                                                    <input id="input" class="input-xxlarge" name="password" type="password"  value="<?php echo $users[0]['password']; ?>">
                                                                                                </div>
                                                                                            </div>
                                                                                            
                                                                                            <div class="control-group">
                                                                                             	<label class="control-label" for="select">Rola</label>
                                                                                                <div class="controls">
                                                                                                        <?php 
																										$i=0;
																										foreach($role as $roles) {
																											if($role_user[$i]['role_id']==$roles['id']) { $rol = 'checked'; } else { $rol =''; }
																									echo '
													<label class="checkbox inline">
														<input type="checkbox" id="inlineCheckbox3" name="role[]" '.$rol.' value="'.$roles['id'].'"> '.$roles['name'].'
													</label>';
													$i++;
																										}
																										?>
                                                                                                </div>
                                                                                            </div>
                                                                                            
                                                                                            <div class="form-actions">
                                                                                                <button class="btn btn-alt btn-large btn-primary" type="submit">Edytuj Użytkownika</button>
                                                                                            </div>
                                                                                        </fieldset>
                                                                                    </form>


                                    </section>
                                    </div>
                                    </article>