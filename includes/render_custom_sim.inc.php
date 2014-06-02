<?php
function render_custom_sim() {
	$error = NULL;

	$presets = array(
		'monk-bis' => array(
			'config' => 'monk-bis',
			'rotation' => 'monk-bis',
		),
		'dragoon-bis' => array(
			'config' => 'dragoon-bis',
			'rotation' => 'dragoon-bis',
		),
		'black-mage-bis' => array(
			'config' => 'black-mage-bis',
			'rotation' => 'black-mage-bis',
		),
		'summoner-bis' => array(
			'config' => 'summoner-bis',
			'rotation' => 'summoner-bis',
		),
		'bard-bis' => array(
			'config' => 'bard-bis',
			'rotation' => 'bard-bis',
		),
		'bard-tp-singer' => array(
			'config' => 'bard-bis',
			'rotation' => 'bard-tp-singer',
		),
	);
	
	$actors = array();
	
	if (isset($_POST['actor-id'])) {
		foreach ($_POST['actor-id'] as $oldId) {
			if (count($actors) < 8 && !isset($_POST["actor-{$oldId}-remove"]) && isset($_POST["actor-{$oldId}-id"], $_POST["actor-{$oldId}-config"], $_POST["actor-{$oldId}-rotation"])) {
				$newId = preg_replace('/[^A-Za-z0-9_-]/', '', $_POST["actor-{$oldId}-id"]);
				if (isset($actors[$newId])) {
					$newId = $oldId;
				}
				$actors[$newId] = array(
					'config' => $_POST["actor-{$oldId}-config"],
					'rotation' => $_POST["actor-{$oldId}-rotation"],
				);
			}
		}
	}

	$newPreset = NULL;

	if (isset($_POST['add-actor'], $_POST['new-actor-preset'])) {
		$newPreset = $_POST['new-actor-preset'];
	} else if (isset($_GET['preset']) && !isset($_POST['actor-id'])) {
		$newPreset = $_GET['preset'];
	}
	
	if (!isset($presets[$newPreset])) {
		$newPreset = NULL;
	}

	if ($newPreset && count($actors) < 8) {
		$newId = "player";
		if (isset($actors[$newId])) {
			for ($number = 2; isset($actors["player-{$number}"]); ++$number);
			$newId = "player-{$number}";
		}
		$actors[$newId] = array(
			'config' => file_get_contents("subjects/{$presets[$newPreset]['config']}.conf"),
			'rotation' => file_get_contents("rotations/{$presets[$newPreset]['rotation']}.sl"),
		);
	}

	$sim_results = NULL;
	
	if (isset($_POST['single']) && count($actors) && count($actors) <= 8) {
		$len   = (isset($_POST['len']) && is_numeric($_POST['len'])) ? $_POST['len'] : 0;
		$seed  = (isset($_POST['seed']) && is_numeric($_POST['seed'])) ? $_POST['seed'] : 0;
		
		$load = sys_getloadavg();

		if ($len < 1 || $len > 30 * 60) {
			$error = "Simulation length out of bounds.";
		} else if ($load[0] > 16) {
			$error = "Server overloaded. Please try again in a moment.";
		}

		if (!$error) {
			foreach ($actors as $id => $actor) {
				if (strlen($actor['config']) > 500) {
					$error = "Configuration too long.";
				} else if (strlen($actor['rotation']) > 4000) {
					$error = "Rotation too long.";
				}
				if ($error) { break; }
			}
		}

		if (!$error) {
			$files = array();
			$command = "./simulator single-json --length $len".((isset($_POST['seed']) && $_POST['seed'] != '') ? " --seed $seed" : '');

			foreach ($actors as $id => $actor) {
				$configFile = tempnam('/tmp', 'xiv-sim-config-');
				if (!($f = fopen($configFile, 'w+'))) {
					$error = "Unable to create temporary file for configuration.";
					break;
				}
				fwrite($f, $actor['config']);
				fclose($f);
				$files[] = $configFile;
	
				$rotationFile = tempnam('/tmp', 'xiv-sim-rotation-');
				if (!($f = fopen($rotationFile, 'w+'))) {
					$error = "Unable to create temporary file for rotation.";
					break;
				}
				fwrite($f, $actor['rotation']);
				fclose($f);
				$files[] = $rotationFile;
				
				$command .= ' '.escapeshellarg($id).' '.escapeshellarg($configFile).' '.escapeshellarg($rotationFile);
			}

			if (!$error) {
				exec($command, $output, $return_code);
				if ($return_code) {
					$error = nl2br(str_replace(" ", "&nbsp;", htmlspecialchars(implode("\n", $output))));
				} else {
					$sim_results = implode("\n", $output);
				}
			}
			
			foreach ($files as $file) {
				unlink($file);
			}			
		}
	}
	
	if ($sim_results) {
		require_once 'includes/render_sim_results.inc.php';
		render_sim_results($sim_results);
	} else {
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
			<?php
			foreach ($actors as $id => $actor) {
				?>
				<div class="framed-box">
					<input type="hidden" name="actor-id[]" value="<?= htmlspecialchars($id) ?>" />
					<table>
						<tr>
							<td><b>Identifier</b></td>
							<td><input type="text" name="actor-<?= htmlspecialchars($id) ?>-id" value="<?= htmlspecialchars($id) ?>" /></td>
						</tr>
						<tr>
							<td>
								<b>Stats</b><br />
								<small>Type in your stats here.</small>
							</td>
							<td><textarea name="actor-<?= htmlspecialchars($id) ?>-config" rows="12" cols="100"><?= htmlspecialchars($actor['config']) ?></textarea></td>
						</tr>
						<tr>
							<td>
								<b>Rotation</b><br />
								<small>If you want to be adventurous, you can program your rotation here.</small>
							</td>
							<td><textarea name="actor-<?= htmlspecialchars($id) ?>-rotation" rows="20" cols="100"><?= htmlspecialchars($actor['rotation']) ?></textarea></td>
						</tr>
					</table>
					<div class="right"><input type="submit" name="actor-<?= htmlspecialchars($id) ?>-remove" value="Remove Party Member" /></div>
				</div>
				<?php
			}
			?>
			<?php
			if (count($actors) < 8) {
				?>
				<div class="right">
					Add Party Member: 
					<select name="new-actor-preset">
					<?php
					foreach ($presets as $id => $preset) {
						?>
						<option value="<?= htmlspecialchars($id) ?>" /><?= htmlspecialchars($id) ?></option>
						<?php
					}
					?>
					</select>
					<input type="submit" name="add-actor" value="Add" />
				</div>
				<?php
			}
			?>
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