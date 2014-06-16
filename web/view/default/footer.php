<footer class="site-footer">

	<div class="container">
	
		<div class="row">
			
			<div class="col-sm-6">
				Copyright &copy; Frogss - All Rights Reserved. 
			</div>
			
			<div class="col-sm-6">
				
				<ul class="social-networks text-right">
					<li>
						<a href="#">
							<i class="entypo-instagram"></i>
						</a>
					</li>
					<li>
						<a href="#">
							<i class="entypo-twitter"></i>
						</a>
					</li>
					<li>
						<a href="#">
							<i class="entypo-facebook"></i>
						</a>
					</li>
				</ul>
				
			</div>
			
		</div>
		
	</div>
	
</footer>	
</div>


	<!-- Bottom Scripts -->
	<script src="<?php echo THEMEROOT; ?>assets/js/gsap/main-gsap.js"></script>
	<script src="<?php echo THEMEROOT; ?>assets/js/bootstrap.js"></script>
	<script src="<?php echo THEMEROOT; ?>assets/js/joinable.js"></script>
	<script src="<?php echo THEMEROOT; ?>assets/js/resizeable.js"></script>
	<script src="<?php echo THEMEROOT; ?>assets/js/neon-slider.js"></script>
	<script src="<?php echo THEMEROOT; ?>assets/js/neon-custom.js"></script>
    
    <?php
//GOOGLE ANALITICS
$ga_code = Conf::get("gacode");

if(empty($ga_code)) {} else {

echo '<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push([\'_setAccount\', \''.$ga_code.'\']);
  _gaq.push([\'_trackPageview\']);

  (function() {
    var ga = document.createElement(\'script\'); ga.type = \'text/javascript\'; ga.async = true;
    ga.src = (\'https:\' == document.location.protocol ? \'https://\' : \'http://\') + \'stats.g.doubleclick.net/dc.js\';
    var s = document.getElementsByTagName(\'script\')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>';	
	
}



?>

</body>
</html>