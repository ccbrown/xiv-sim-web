<html>
<head>
	<title>Simulation</title>
	<link href="./style/style.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="./js/jquery.min.js"></script>
</head>
<body>

<center>
<div class="wrapper">

<a href="./">Home</a> ; Simulations: <a href="?sim&monk">Monk</a> | <a href="?sim&dragoon">Dragoon</a> | <a href="?sim&bard">Bard</a> | <a href="?sim&summoner">Summoner</a> | <a href="?sim&black-mage">Black Mage</a>

<br /><br />

<?php

if (isset($_GET['sim'])) {
	require_once 'includes/render_sim_input.inc.php';
	render_sim_input();
} else {
	require_once 'includes/render_home.inc.php';
	render_home();
}
?>

</div>
		
<small><a href="https://github.com/ccbrown/xiv-sim" target="_blank">https://github.com/ccbrown/xiv-sim</a></small><br />
<small><a href="https://github.com/ccbrown/xiv-sim-web" target="_blank">https://github.com/ccbrown/xiv-sim-web</a></small>

</center>

</body>
</html>