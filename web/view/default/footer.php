<footer class="footer">
footer
</footer>

<?php
//GOOGLE ANALITICS
/*$ga_code = DB::query(Database::SELECT, 'SELECT * FROM configuration WHERE klucz="gacode"')->execute()->as_array();

if(empty($ga_code)) {} else {

echo '<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push([\'_setAccount\', \''.$ga_code[0]['wartosc'].'\']);
  _gaq.push([\'_trackPageview\']);

  (function() {
    var ga = document.createElement(\'script\'); ga.type = \'text/javascript\'; ga.async = true;
    ga.src = (\'https:\' == document.location.protocol ? \'https://\' : \'http://\') + \'stats.g.doubleclick.net/dc.js\';
    var s = document.getElementsByTagName(\'script\')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>';	
	
}
*/


?>
</body></html>