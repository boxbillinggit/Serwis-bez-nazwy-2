<div class="row-fluid">
<div class="span12" id="user-list">
							<h3 class="heading">Pod strony</h3>
								<ul class="nav nav-tabs">
									<li class="active"><a data-toggle="tab" href="#podstrony">Pod Strony</a></li>
									<li class=""><a data-toggle="tab" href="#dodaj">Dodaj Pod Strone</a></li>
								</ul>
<div class="tab-content">
<div class="tab-pane active" id="podstrony">

	<script>
    $(function() {
        $( "#sortable" ).sortable({
        update: function(event, ui) {
            var data = $('#sortable').sortable('serialize');
            $.post("/admin/podstrony_ajax", data);
        }
    });
        $( "#sortable" ).disableSelection();
    });
    </script>

<table class="table">
										<thead>
											<tr>
                                            <th style="width:40px;"></th>
                                            <th>Kolejność</th>
												<th>Id</th>
												<th>Tytuł</th>
												<th>Aktywne</th>
												<th></th>
											</tr>
										</thead>
										<tbody id="sortable">
											<?php 
											if(empty($podstrona)) {
											echo '<tr>
												<td colspan="4">Brak Wpisów</td> 
											</tr>';	
											} else {
												$i=1;
												$o=1;
											foreach($podstrona as $podtrs) { 

											echo '<tr id="list_'.$podtrs['id_cms'].'">
												<td style="width:40px; text-align:center;"><span class="icon-arrow-down"></span><span class="icon-arrow-up"></span></td>
												<td style="text-align:center;">'.$o.'</td>
												<td style="text-align:center;">'.$podtrs['id_cms'].'</td>
												<td style="text-align:center;">'.$podtrs['title'].'</td>
												<td style="text-align:center;">'.$podtrs['active'].'</td>
												<td class="toolbar">
											
						<a title="View" class="sepV_a" href="'.URL::site('show/'.$podtrs['seo']).'" target="_blank"><i class="icon-eye-open"></i></a>
						<a title="Edit" class="sepV_a" href="'.URL::site('/admin/podstrony_edit/'.$podtrs['id_cms']).'"><i class="icon-pencil"></i></a>
						<a title="Delete" href="#demoModal'.$i.'" data-backdrop="static" data-toggle="modal" oldtitle="New messages" aria-describedby="ui-tooltip-17"><i class="icon-trash"></i></a>
						
												</td>
											</tr>';
											echo '<!-- USUN modal -->
								<div class="modal fade hide modal-info" id="demoModal'.$i.'">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal">×</button>
										<h3>Informacja!!</h3>
									</div>
									<div class="modal-body">
										<p>Potwierdzenie Usunięcia: <b>'.$podtrs['title'].'</b></p>
										<p>Czy jesteś pewien że chcesz usunąc ten wpis ??</p>
									</div>
									<div class="modal-footer">
										<a href="#" class="btn btn-alt" data-dismiss="modal">Anuluj Usuwanie</a>
										<a href="/admin/podstrony_delete/'.$podtrs['id_cms'].'" class="btn btn-alt">Potwierdzam Usunięcie</a>
									</div>
								</div>
								<!-- /USUN modal -->';
								
								
								$o++;
								$i++;
											}
											}
											 ?>
										</tbody>
									</table>

</div>


<div class="tab-pane" id="dodaj">
<script>
/*	$(document).ready(function() {
      $('#titles').live('change',function() {   
      $.post("/admin/ajax_seo",{url: $('#titles').val(), suffix: ''}, function(data) {
		 $("#seos").val(data);
	  });    
});

    });*/
</script>
                 											 
                                                                                    <form action="/admin/podstrony" class="form-horizontal" method="POST">
                                                                                        <fieldset>
                                                                                            <div class="control-group">
                                                                                                <label class="control-label" for="input">Tytuł</label>
                                                                                                <div class="controls">
                                                                                                    <input class="input-xxlarge" id="titles" name="title" type="text">
                                                                                                </div>
                                                                                            </div>
                                                                                            
                                                                                            <div class="control-group">
                                                                                                <label class="control-label" for="input">Seo</label>
                                                                                                <div class="controls">
                                                                                                    <input class="input-xxlarge" name="seo" id="seos" type="text" value="/show/">
                                                                                                    <p class="help-block">np. nasze-wozy</p>
                                                                                                </div>
                                                                                            </div>
                                                                                            
<div class="control-group">
<label class="control-label" for="input">Kategoria</label>
<div class="controls">
<label class="uni-radio">
				<div class="uni-radio" id="uniform-uni_r1a">
                <span class="uni-checked">
                <select name="sub_id">
                <option value="0">Jako Głowna Kategoria</option>
                <?php /*foreach($menn as $me) {
					echo '<option value="'.$me['id'].'">'.$me['name'].'</option>';
				} */?>
                </select>
                </span></div>
				Włączone
			</label>
</div>
</div>

                                                                                            
                                                                                            <div class="control-group">
                                                                                                <div class="controls">
                                                                                                    <textarea  id="wysiwg_full" class="wysiwyg" name="text" placeholder="Wprowadź Treść" rows="8"></textarea>
                                                                                                </div>
                                                                                            </div>
                                                                                            
                                                                                            
                                                                                            <div class="control-group">
<label class="control-label" for="input">Komentarze</label>
<div class="controls">
<label class="uni-radio">
				<div class="uni-radio" id="uniform-uni_r1a">
                <span class="uni-checked">
                <input type="radio" value="0" id="uni_r1a" name="comments_on_arts" class="uni_style" style="opacity: 0;"></span></div>
				Włączone
			</label>
            <label class="uni-radio">
				<div class="uni-radio" id="uniform-uni_r1a"><span class="uni-checked">
                <input type="radio" value="1" id="uni_r1a" name="comments_on_arts" class="uni_style" style="opacity: 0;"></span></div>
				Wyłączone
			</label>
</div>
</div>
            
            <div class="control-group">
<label class="control-label" for="input">Ocena artykulu</label>
<div class="controls">
<label class="uni-radio">
				<div class="uni-radio" id="uniform-uni_r1a">
                <span class="uni-checked">
                <input type="radio" value="0" id="uni_r1a" name="rate_on_arts" class="uni_style" style="opacity: 0;"></span></div>
				Włączone
			</label>
            <label class="uni-radio">
				<div class="uni-radio" id="uniform-uni_r1a"><span class="uni-checked">
                <input type="radio" value="1" id="uni_r1a" name="rate_on_arts" class="uni_style" style="opacity: 0;"></span></div>
				Wyłączone
			</label>
</div>
</div>

                                                                                            
                                                                                            <div class="form-actions">
                                                                                                <button class="btn btn-alt btn-large btn-primary" type="submit">Dodaj Podstrone</button>
                                                                                            </div>
                                                                                        </fieldset>
                                                                                    </form>
                                                                            


</div>
</div>
                  </div>  </div>