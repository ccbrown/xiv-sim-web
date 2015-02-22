<html>
<head>
	<title>FFXIV Simulation</title>

	<meta name="description" content="Open source FFXIV combat simulator for theorycrafters and min-maxers with programmable rotations and detailed graphs.">

	<link href="./style/style.css" rel="stylesheet" type="text/css" />
	<link href="./style/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />

	<script type="text/javascript" src="./js/jquery.min.js"></script>
	<script type="text/javascript" src="./js/jquery.dataTables.min.js"></script>
</head>
<body>

<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-51493793-1', 'ffxivguild.net');
  ga('send', 'pageview');

</script>

<center>
<div class="wrapper">

<center><span style="color:#ff0000;">Warning: This is very much a work in progress. Take anything you see here with a grain of salt.</style></center>

<br /><br />

<a href="./">Home</a> ; Simulate: <a href="?sim&preset=monk-bis">Monk</a> | <a href="?sim&preset=dragoon-bis">Dragoon</a> | <a href="?sim&preset=ninja-bis">Ninja</a> | <a href="?sim&preset=bard-bis">Bard</a> | <a href="?sim&preset=summoner-bis">Summoner</a> | <a href="?sim&preset=black-mage-bis">Black Mage</a> | <a href="?sim&preset=warrior-bis">Warrior</a> | <a href="?sim">Party</a>

<br /><br />

<?php

if (isset($_GET['feature'])) {
	require_once 'includes/render_featured_sim.inc.php';
	render_featured_sim();
} else if (isset($_GET['sim'])) {
	require_once 'includes/render_custom_sim.inc.php';
	render_custom_sim();
} else {
	require_once 'includes/render_home.inc.php';
	render_home();
}
?>

</div>

<div class="footer">
	<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
		<input type="hidden" name="cmd" value="_s-xclick">
		<input type="hidden" name="hosted_button_id" value="QKZ7KG2CX7GQC">
		<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_SM.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
		<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
	</form>

	<small><a href="https://github.com/ccbrown/xiv-sim" target="_blank">https://github.com/ccbrown/xiv-sim</a></small><br />
	<small><a href="https://github.com/ccbrown/xiv-sim-web" target="_blank">https://github.com/ccbrown/xiv-sim-web</a></small>
</div>

</center>

<script>
$(function(){
	$('.info-table').dataTable({
		'bPaginate': false,
		'bFilter': false,
		'bInfo': false,
	});
});
</script>

</body>
</html>