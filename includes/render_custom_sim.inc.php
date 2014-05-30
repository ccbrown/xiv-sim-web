<?php
function render_custom_sim() {
	$error = NULL;
	$sim_results = NULL;
	
	if (isset($_POST['single'])) {
		$len   = isset($_POST['len']) ? intval($_POST['len']) : 0;
		$seed  = (isset($_POST['seed']) && is_numeric($_POST['seed'])) ? $_POST['seed'] : 0;
	
		$config = $_POST['config'];
		$rotation = $_POST['rotation'];

		if ($len < 1 || $len > 30 * 60) {
			$error = "Simulation length out of bounds.";
		} else if (strlen($config) > 500) {
			$error = "Configuration too long.";
		} else if (strlen($rotation) > 4000) {
			$error = "Rotation too long.";
		}
	
		if (!$error) {
			$configFile = tempnam('/tmp', 'xiv-sim-config-');
			if (!($f = fopen($configFile, 'w+'))) {
				die("Unable to create temporary file for configuration.");
			}
			fwrite($f, $config);
			fclose($f);

			$rotationFile = tempnam('/tmp', 'xiv-sim-rotation-');
			if (!($f = fopen($rotationFile, 'w+'))) {
				die("Unable to create temporary file for rotation.");
			}
			fwrite($f, $rotation);
			fclose($f);
	
			$command = "./simulator single-json '$configFile' '$rotationFile' $len".((isset($_POST['seed']) && $_POST['seed']) ? " $seed" : '');
			exec($command, $output, $return_code);
			unlink($configFile);
			unlink($rotationFile);
			
			if ($return_code) {
				$error = nl2br(str_replace(" ", "&nbsp;", htmlspecialchars(implode("\n", $output))));
			} else {
				$sim_results = implode("\n", $output);
			}
		}
	}
	
	if ($sim_results) {
		require_once 'includes/render_sim_results.inc.php';
		render_sim_results($sim_results);
	} else {
		$presets = array('monk', 'dragoon', 'bard', 'summoner', 'black-mage');
		if (in_array($_GET['sim'], $presets)) {
			$preset = $_GET['sim'];
		} else {
			$preset = NULL;
		}
		
		$defaultConfig = '';
		if (!isset($_POST['config']) && $preset) {
			$defaultConfig = file_get_contents("subjects/{$preset}-bis.conf");
		}

		$defaultRotation = '';
		if (!isset($_POST['rotation']) && $preset) {
			$defaultRotation = file_get_contents("rotations/{$preset}-bis.sl");
		}
		
		function field_value($name, $default) {
			return htmlspecialchars(isset($_POST[$name]) ? $_POST[$name] : $default);
		}
		?>
		
		<center>
		
		<h1>Simulate</h1>
		
		<?php
		if ($error) {
			?>
			<div class="fixed-width error"><?= $error ?></div>
			<?php
		}
		?>
		
		<br />

		<form action="" method="post">
			<div class="framed-box">
				<h3>Stats</h3>
				<p>Type in your stats here.</p>
				<textarea name="config" rows="12" cols="100"><?= field_value('config', $defaultConfig) ?></textarea>
			</div>
			<div class="framed-box">
				<h3>Rotation</h3>
				<p>If you want to be adventurous, you can program your rotation here.</p>
				<textarea name="rotation" rows="20" cols="100"><?= field_value('rotation', $defaultRotation) ?></textarea>
			</div>
			<div class="framed-box">
				<table>
					<tr>
						<td>Simulation Length (Seconds): <input type="text" name="len" value="<?= field_value('len', 660) ?>" /></td>
						<td>Simulation Seed (Leave Blank For Random): <input type="text" name="seed" value="<?= field_value('seed', '') ?>" /></td>
						<td>	<input type="submit" name="single" value="Simulate" /></td>
					</tr>
				</table>
			</div>
		</form>

		</center>
		<?php
	}
}
?>