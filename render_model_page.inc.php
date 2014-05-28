<?php
function render_model_page($model) {
	$error = NULL;
	$sim_results = NULL;
	
	if (isset($_POST['submit'])) {
		$wdmg = isset($_POST['wdmg']) ? intval($_POST['wdmg']) : 0;
		$wdel = isset($_POST['wdel']) ? floatval($_POST['wdel']) : 3.0;
		$str  = isset($_POST['str']) ? intval($_POST['str']) : 0;
		$int  = isset($_POST['int']) ? intval($_POST['int']) : 0;
		$pie  = isset($_POST['pie']) ? intval($_POST['pie']) : 0;
		$crit = isset($_POST['crit']) ? intval($_POST['crit']) : 0;
		$sks  = isset($_POST['sks']) ? intval($_POST['sks']) : 0;
		$sps  = isset($_POST['sps']) ? intval($_POST['sps']) : 0;
		$det  = isset($_POST['det']) ? intval($_POST['det']) : 0;
		$len  = isset($_POST['len']) ? intval($_POST['len']) : 0;
	
		$rotation = $_POST['rotation'];

		if ($wdmg < 1 || $wdm > 10000) {
			$error = "Weapon damage out of bounds.";
		} else if ($wdel < 0.001 || $wdel > 100) {
			$error = "Weapon delay out of bounds.";
		} else if ($str < 0 || $str > 10000) {
			$error = "Strength out of bounds.";
		} else if ($int < 0 || $int > 10000) {
			$error = "Intelligence out of bounds.";
		} else if ($pie < 0 || $pie > 10000) {
			$error = "Piety out of bounds.";
		} else if ($crit < 0 || $crit > 10000) {
			$error = "Critical strike rate out of bounds.";
		} else if ($sks < 0 || $sks > 10000) {
			$error = "Skill speed out of bounds.";
		} else if ($sps < 0 || $sps > 10000) {
			$error = "Spell speed out of bounds.";
		} else if ($det < 0 || $det > 10000) {
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
	
			$command = "./simulator single-json '$model' '$file' 'WDMG=$wdmg WDEL=$wdel STR=$str INT=$int PIE=$pie CRIT=$crit SKS=$sks SPS=$sps DET=$det LEN=$len'";
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
		$rotation = file_get_contents("rotations/{$model}.sl");
		
		function field_value($name, $default) {
			return htmlspecialchars(isset($_POST[$name]) ? $_POST[$name] : $default);
		}
		?>
		
		<center>
		
		<h1><?= htmlspecialchars($model) ?></h1>
		
		<?php
		if ($error) {
			?>
			<div class="fixed-width error"><?= $error ?></div>
			<?php
		}
		?>
		
		<br />
		
		<form action="" method="post">
			<table>
			<tr><td>Weapon Damage</td><td><input type="text" name="wdmg" value="<?= field_value('wdmg', 47) ?>" /></td></tr>
			<tr><td>Weapon Delay</td><td><input type="text" name="wdel" value="<?= field_value('wdel', 2.72) ?>" /></td></tr>
			<?php
				if ($model == 'monk' || $model == 'dragoon') {
					?>
					<tr><td>Strength</td><td><input type="text" name="str" value="<?= field_value('str', 512) ?>" /></td></tr>
					<tr><td>Critical Hit Rate</td><td><input type="text" name="crit" value="<?= field_value('crit', 486) ?>" /></td></tr>
					<tr><td>Skill Speed</td><td><input type="text" name="sks" value="<?= field_value('sks', 402) ?>" /></td></tr>
					<tr><td>Determination</td><td><input type="text" name="det" value="<?= field_value('det', 346) ?>" /></td></tr>
					<?php
				}
				if ($model == 'summoner') {
					?>
					<tr><td>Intelligence</td><td><input type="text" name="int" value="<?= field_value('int', 412) ?>" /></td></tr>
					<tr><td>Piety</td><td><input type="text" name="pie" value="<?= field_value('pie', 244) ?>" /></td></tr>
					<tr><td>Critical Hit Rate</td><td><input type="text" name="crit" value="<?= field_value('crit', 486) ?>" /></td></tr>
					<tr><td>Spell Speed</td><td><input type="text" name="sps" value="<?= field_value('sps', 486) ?>" /></td></tr>
					<tr><td>Determination</td><td><input type="text" name="det" value="<?= field_value('det', 346) ?>" /></td></tr>
					<?php
				}
			?>
			<tr><td>Simulation Length (Seconds)</td><td><input type="text" name="len" value="<?= field_value('len', 660) ?>" /></td></tr>
			</table>
			<br /><br />
			<textarea name="rotation" rows="20" cols="100"><?= field_value('rotation', $rotation) ?></textarea>
			<br /><br />
			<input type="submit" name="submit" value="Simulate" />
		</form>

		</center>
		<?php
	}
}
?>