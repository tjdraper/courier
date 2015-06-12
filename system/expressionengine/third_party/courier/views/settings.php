<h2>Cron</h2>
<p>Use a server cron to call the following URL at 1 minute intervals.</p>
<p><a href="<?php echo $cron; ?>"><?php echo $cron; ?></a></p>
<p>Cron examples:</p>
<ul>
	<li><code>/usr/bin/wget "<?php echo $cron; ?>" -O /dev/null</code></li>
	<li><code>/usr/bin/php -source "<?php echo $cron; ?>"</code></li>
	<li><code>curl --silent --compressed "<?php echo $cron; ?>"</code></li>
</ul>