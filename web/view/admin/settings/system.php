<div class="row-fluid">
<div class="span12" id="user-list">
							<h3 class="heading">System Informacje</h3>
<div class="tab-content">
<?php

/*$uptime = shell_exec("cut -d. -f1 /proc/uptime");
$days = floor($uptime/60/60/24);
$hours = $uptime/60/60%24;
$mins = $uptime/60%60;
$secs = $uptime%60;
echo "This server is up $days days $hours hours $mins minutes and $secs seconds";*/
?>

<pre>
<b>Uptime:</b>
<?php @system("uptime"); ?>

<b>System Information:</b>
<?php @system("uname -a"); ?>

<b>Memory Usage (MB):</b>
<?php @system("free -m"); ?>

<b>Disk Usage:</b>
<?php @system("df -h"); ?>

<b>CPU Information:</b>
<?php @system("cat /proc/cpuinfo | grep \"model name\|processor\""); ?>
</pre>

</div>
</div>
</div>