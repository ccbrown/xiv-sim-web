<html>
<head>
	<title>Simulation</title>
	<link href="./style/style.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="./js/jquery.min.js"></script>
</head>
<body>

<center>
<div class="wrapper">

<?php

$model = NULL;

if (isset($_GET['monk'])) {
	$model = 'monk';
} else if (isset($_GET['dragoon'])) {
	$model = 'dragoon';
} else if (isset($_GET['bard'])) {
	die("Sorry, bard isn't available yet.");
} else if (isset($_GET['summoner'])) {
	$model = 'summoner';
} else if (isset($_GET['black-mage'])) {
	$model = 'black-mage';
}

if ($model) {
	require_once 'render_model_page.inc.php';
	render_model_page($model);
} else {
	require_once 'render_home.inc.php';
	render_home();
}
?>

</div>
		
<small><a href="https://github.com/ccbrown/xiv-sim" target="_blank">https://github.com/ccbrown/xiv-sim</a></small><br />
<small><a href="https://github.com/ccbrown/xiv-sim-web" target="_blank">https://github.com/ccbrown/xiv-sim-web</a></small>

</center>

</body>
</html>