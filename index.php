<html>
<head>
	<title>Simulation</title>
	<link href="./style/style.css" rel="stylesheet" type="text/css" />
</head>
<body>

<?php
$error = NULL;
$sim_results = NULL;

if (isset($_POST['submit'])) {
	$wdmg = intval($_POST['wdmg']);
	$wdel = floatval($_POST['wdel']);
	$str  = intval($_POST['str']);
	$crit = intval($_POST['crit']);
	$ss   = intval($_POST['ss']);
	$det  = intval($_POST['det']);
	$len  = intval($_POST['len']);

	$rotation = $_POST['rotation'];

	if ($wdmg < 1 || $wdm > 10000) {
		$error = "Weapon damage out of bounds.";
	} else if ($wdel < 0.001 || $wdel > 100) {
		$error = "Weapon delay out of bounds.";
	} else if ($str < 1 || $str > 10000) {
		$error = "Strength out of bounds.";
	} else if ($crit < 1 || $crit > 10000) {
		$error = "Critical strike rate out of bounds.";
	} else if ($det < 1 || $det > 10000) {
		$error = "Determination out of bounds.";
	} else if ($len < 1 || $len > 30 * 60) {
		$error = "Simulation length out of bounds.";
	} else if (strlen($rotation) > 4000) {
		$error = "Rotation too long.";
	}

	if (!$error) {
		$file = tempnam('/tmp', 'xiv-sim-rotation-');
		if (!($f = fopen($file, 'w+'))) {
			die("Unable to create temporary file for source.");
		}
		fwrite($f, $rotation);
		fclose($f);

		$command = "./simulator single-json '$file' 'WDMG=$wdmg WDEL=$wdel STR=$str CRIT=$crit SS=$ss DET=$det LEN=$len'";
		exec($command, $output, $return_code);
		unlink($file);
		
		if ($return_code) {
			$error = nl2br(str_replace(" ", "&nbsp;", htmlspecialchars(implode("\n", $output))));
		} else {
			$sim_results = implode("\n", $output);
		}
	}
}

if ($sim_results) {
	require_once 'render_sim_results.inc.php';
	render_sim_results($sim_results);
} else {
	$rotation = file_get_contents("rotations/monk.sl");
	
	function field_value($name, $default) {
		return htmlspecialchars(isset($_POST[$name]) ? $_POST[$name] : $default);
	}
	?>
	
	<center>
	
	<br /><br /><br />
	
	<?php
	if ($error) {
		?>
		<div class="fixed-width error"><?= $error ?></div>
		<?php
	}
	?>
	
	<br /><br />
	
	<form action="" method="post">
		<table>
		<tr><td>Weapon Damage</td><td><input type="text" name="wdmg" value="<?= field_value('wdmg', 47) ?>" /></td></tr>
		<tr><td>Weapon Delay</td><td><input type="text" name="wdel" value="<?= field_value('wdel', 2.72) ?>" /></td></tr>
		<tr><td>Strength</td><td><input type="text" name="str" value="<?= field_value('str', 512) ?>" /></td></tr>
		<tr><td>Critical Hit Rate</td><td><input type="text" name="crit" value="<?= field_value('crit', 486) ?>" /></td></tr>
		<tr><td>Skill Speed</td><td><input type="text" name="ss" value="<?= field_value('ss', 402) ?>" /></td></tr>
		<tr><td>Determination</td><td><input type="text" name="det" value="<?= field_value('det', 346) ?>" /></td></tr>
		<tr><td>Simulation Length (Seconds)</td><td><input type="text" name="len" value="<?= field_value('len', 660) ?>" /></td></tr>
		</table>
		<br /><br />
		<textarea name="rotation" rows="20" cols="100"><?= field_value('rotation', $rotation) ?></textarea>
		<br /><br />
		<input type="submit" name="submit" value="Submit" />
	</form>
	
	<br /><br />
	
	<small><a href="https://github.com/ccbrown/xiv-sim" target="_blank">https://github.com/ccbrown/xiv-sim</a></small>
	
	</center>
	<?php
}
?>
</body>
</html>