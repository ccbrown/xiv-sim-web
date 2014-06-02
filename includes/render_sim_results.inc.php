<?php
function render_sim_scaling($scaling, $baselineDPS) {
	if (!count($scaling)) {
		return;
	}
	?>
	<br /><br />
	
	<table class="info-table">
		<thead>
			<tr>
				<th>Stat</th>
				<th class="numeric">Amount</th>
				<th class="numeric">Iterations</th>
				<th class="numeric">Average DPS Gain</th>
				<th class="numeric">Average Relative Value</th>
			</tr>
		</thead>
		<tbody>
			<?php
			$primaryStatGain = 0;
			foreach (array('str', 'dex', 'int') as $stat) {
				if (isset($scaling[$stat])) {
					$primaryStatGain = max($primaryStatGain, $scaling[$stat]['damage'] / ($scaling[$stat]['time'] / 1000000) - $baselineDPS);
				}
			}
			foreach ($scaling as $stat => $results) {
				$gain = $results['damage'] / ($results['time'] / 1000000) - $baselineDPS;
				?>
				<tr>
					<td><?= htmlspecialchars($stat) ?></td>
					<td class="numeric"><?= ($results['amount'] >= 0 ? '+' : '').htmlspecialchars($results['amount']) ?></td>
					<td class="numeric"><?= htmlspecialchars($results['iterations']) ?></td>
					<td class="numeric"><?= htmlspecialchars($gain) ?></td>
					<td class="numeric"><?= $gain / $primaryStatGain ?></td>
				</tr>
				<?php
			}
			?>
		</tbody>
	</table>
	<?php
}

function render_sim_effects($effects, $time) {
	if (!count($effects)) {
		return;
	}
	?>
	<br /><br />
	
	<table class="info-table">
		<thead>
			<tr>
				<th>Effect</th>
				<th class="numeric">Damage</th>
				<th class="numeric">DPS</th>
				<th class="numeric">Count</th>
				<th class="numeric">Crits</th>
				<th class="numeric">Average Damage</th>
			</tr>
		</thead>
		<tbody>
			<?php
			foreach ($effects as $id => $effect) {
				?>
				<tr>
					<td><?= htmlspecialchars($id) ?></td>
					<td class="numeric"><?= htmlspecialchars($effect['damage']) ?></td>
					<td class="numeric"><?= htmlspecialchars($effect['damage'] / ($time / 1000000)) ?></td>
					<td class="numeric"><?= htmlspecialchars($effect['count']) ?></td>
					<td class="numeric"><?= htmlspecialchars($effect['crits']) ?></td>
					<td class="numeric"><?= htmlspecialchars($effect['damage'] / $effect['count']) ?></td>
				</tr>
				<?php
			}
			?>
		</tbody>
	</table>
	<?php
}

function render_sim_results($json_results, $feature = NULL) {
	$results = json_decode($json_results, true);
	?>
	<script src="./js/highcharts/highcharts.js"></script>
	<script src="./js/highcharts/modules/exporting.js"></script>

	<center><h1>Simulation Results</h1></center>
	
	<?php
	if (isset($results['seed'])) {
		?>
		<b>Seed:</b> <?= htmlspecialchars($results['seed']) ?><br />
		<?php
	}
	?>
	<?php
	if (isset($results['iterations'])) {
		?>
		<b>Iterations:</b> <?= htmlspecialchars($results['iterations']) ?><br />
		<?php
	}
	?>
	<?php
	if (isset($results['min-time'], $results['max-time'])) {
		?>
		<b>Simulation Times:</b> <?= $results['min-time'] / 1000000 / 60 ?> - <?= $results['max-time'] / 1000000 / 60 ?> minutes<br />
		<?php
	}
	?>
	<b>Simulated Time:</b> <?= htmlspecialchars($results['time'] / 1000000 / 60) ?> minutes<br />

	<br />

	<b>Damage:</b> <?= htmlspecialchars($results['damage']) ?><br />
	<b><?= (isset($results['iterations']) && $results['iterations'] > 1) ? 'Average ' : '' ?>DPS:</b> <?= htmlspecialchars($results['damage'] / ($results['time'] / 1000000)) ?><br />

	<?php
	if ($feature && (isset($results['worst-dps']) || isset($results['best-dps']))) {
		?>
		<br />
		<?php
		if (isset($results['worst-dps'])) {
			?>
			<b>Worst DPS:</b> <a href="?feature=<?= urlencode($feature) ?>&seed=<?= urlencode($results['worst-seed']) ?>&len=<?= urlencode($results['worst-time'] / 1000000) ?>"><?= htmlspecialchars($results['worst-dps']) ?></a><br />
			<?php
		}
		if (isset($results['best-dps'])) {
			?>
			<b>Best DPS:</b> <a href="?feature=<?= urlencode($feature) ?>&seed=<?= urlencode($results['best-seed']) ?>&len=<?= urlencode($results['best-time'] / 1000000) ?>"><?= htmlspecialchars($results['best-dps']) ?></a><br />
			<?php
		}
	}
	?>

	<br />

	<?php
	if (isset($results['subjects'])) {
		foreach ($results['subjects'] as $id => $subject) {
			?>
			<div class="framed-box">
	
			<center><h2><?= htmlspecialchars($id) ?></h2></center>
	
			<table width="100%">
				<tr>
					<td>
						<br /><br />
	
						<b>Damage:</b> <?= htmlspecialchars($subject['damage']) ?><br />
						<b>DPS:</b> <?= htmlspecialchars($subject['damage'] / ($results['time'] / 1000000)) ?><br />
	
						<br />
	
						<b>Model:</b> <?= htmlspecialchars($subject['model']) ?><br />
						<?php
							if ($subject['owner']) {
								?><b>Owner:</b> <?= htmlspecialchars($subject['owner']) ?><br /><?php
							}
						?>
	
						<br />
	
						<?php
							if ($subject['stats']['wpdmg']) {
								?><b>Weapon Physical Damage:</b> <?= htmlspecialchars($subject['stats']['wpdmg']) ?><br /><?php
							}
							if ($subject['stats']['wmdmg']) {
								?><b>Weapon Magic Damage:</b> <?= htmlspecialchars($subject['stats']['wmdmg']) ?><br /><?php
							}
							if ($subject['stats']['wdel']) {
								?><b>Weapon Delay:</b> <?= htmlspecialchars($subject['stats']['wdel']) ?><br /><?php
							}
							if ($subject['stats']['str']) {
								?><b>Strength:</b> <?= htmlspecialchars($subject['stats']['str']) ?><br /><?php
							}
							if ($subject['stats']['dex']) {
								?><b>Dexterity:</b> <?= htmlspecialchars($subject['stats']['dex']) ?><br /><?php
							}
							if ($subject['stats']['int']) {
								?><b>Intelligence:</b> <?= htmlspecialchars($subject['stats']['int']) ?><br /><?php
							}
							if ($subject['stats']['crt']) {
								?><b>Critical Hit Rate:</b> <?= htmlspecialchars($subject['stats']['crt']) ?><br /><?php
							}
							if ($subject['stats']['det']) {
								?><b>Determination:</b> <?= htmlspecialchars($subject['stats']['det']) ?><br /><?php
							}
							if ($subject['stats']['sks']) {
								?><b>Skill Speed:</b> <?= htmlspecialchars($subject['stats']['sks']) ?><br /><?php
							}
							if ($subject['stats']['sps']) {
								?><b>Spell Speed:</b> <?= htmlspecialchars($subject['stats']['sps']) ?><br /><?php
							}
						?>
					</td>
					<td style="padding: 0px;">
						<?php
						if (count($subject['mp-samples']) > 1) {
							$maxMana = 0;
							foreach ($subject['mp-samples'] as $sample) {
								if ($sample[1] > $maxMana) {
									$maxMana = $sample[1];
								}
							}
							?>
							<script type="text/javascript">
								$(function () {
									$('#<?= htmlspecialchars($id) ?>-mp-chart').highcharts({
										chart: {
											zoomType: 'x',
											marginLeft: 0,
											marginRight: 0,
											spacingLeft: 0,
											spacingRight: 0,
											width: 900
										},
										title: { text: 'MP Over Time' },
										xAxis: {
											type: 'datetime',
											minPadding: 0,
											maxPadding: 0
										},
										yAxis: {
											title: { enabled: false },
											min: 0, max: <?= $maxMana ?>,
											labels: { enabled: false }
										},
										legend: { enabled: false },
										plotOptions: {
											area: {
												fillColor: {
													linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1},
													stops: [
														[0, Highcharts.getOptions().colors[0]],
														[1, Highcharts.Color(Highcharts.getOptions().colors[0]).setOpacity(0).get('rgba')]
													]
												},
												marker: { radius: 2 },
												lineWidth: 1,
												states: {
													hover: { lineWidth: 1 }
												},
												threshold: null
											}
										},
										series: [{
											type: 'area',
											name: 'MP',
											data: [
												<?php
												$first = true;
												foreach ($subject['mp-samples'] as $sample) {
													if (!$first) { echo ","; }
													$x = $sample[0] / 1000;
													echo "[{$x},{$sample[1]}]";
													$first = false;
												}
												?>
											]
										}],
										credits: { enabled: false },
									});
								});
							</script>
							<div id="<?= htmlspecialchars($id) ?>-mp-chart" class="mp-chart"></div>
							<?php
						}
						if (count($subject['tp-samples']) > 1) {
							?>
							<script type="text/javascript">
								$(function () {
									$('#<?= htmlspecialchars($id) ?>-tp-chart').highcharts({
										chart: {
											zoomType: 'x',
											marginLeft: 0,
											marginRight: 0,
											spacingLeft: 0,
											spacingRight: 0,
											width: 900
										},
										title: { text: 'TP Over Time' },
										xAxis: {
											type: 'datetime',
											minPadding: 0,
											maxPadding: 0
										},
										yAxis: {
											title: { enabled: false },
											min: 0, max: 1000,
											labels: { enabled: false }
										},
										legend: { enabled: false },
										plotOptions: {
											area: {
												fillColor: {
													linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1},
													stops: [
														[0, Highcharts.getOptions().colors[0]],
														[1, Highcharts.Color(Highcharts.getOptions().colors[0]).setOpacity(0).get('rgba')]
													]
												},
												marker: { radius: 2 },
												lineWidth: 1,
												states: {
													hover: { lineWidth: 1 }
												},
												threshold: null
											}
										},
										series: [{
											type: 'area',
											name: 'TP',
											data: [
												<?php
												$first = true;
												foreach ($subject['tp-samples'] as $sample) {
													if (!$first) { echo ","; }
													$x = $sample[0] / 1000;
													echo "[{$x},{$sample[1]}]";
													$first = false;
												}
												?>
											]
										}],
										credits: { enabled: false },
									});
								});
							</script>
							<div id="<?= htmlspecialchars($id) ?>-tp-chart" class="tp-chart"></div>
							<?php
						}
						?>
					</td>
				</tr>
		
				<tr>
					<th>Aura</th>
					<th>Stacks</th>
				</tr>
				<?php
				foreach ($subject['auras'] as $id => $samples) {
					?>
					<tr>
						<td><?= htmlspecialchars($id) ?></td>
						<td class="chart-area">
							<div class="aura-timeline">
								<?php
								$max = 0;
								foreach ($samples as $sample) {
									if ($sample[1] > $max) {
										$max = $sample[1];
									}
								}
								for ($i = -1; $i < count($samples); ++$i) {
									$t = $i < 0 ? 0 : $samples[$i][0];
									$count = $i < 0 ? 0 : $samples[$i][1];
		
									$end = ($i + 1) < count($samples) ? $samples[$i + 1][0] : $results['time'];
									$width = ($end - $t) / $results['time'] * 100;
									if ($count > 0) {
										$green = dechex($count / $max * 255);
										while (strlen($green) < 2) { $green = '0'.$green; }
										echo "<span style=\"background-color:#00{$green}00;width:$width%;\">&nbsp;</span>";
									} else {
										echo "<span style=\"width:$width%;\">&nbsp;</span>";
									}
								}
								?>
							</div>
						</td>
					</tr>
					<?php
				}
				?>
			</table>
			
			<?php
			render_sim_effects($subject['effects'], $results['time']);
			?>
	
			<?php
			if (count($subject['actions'])) {
				?>
				<div class="action-list">
					<?php
					foreach ($subject['actions'] as $action) {
						echo htmlspecialchars($action).' ';
					}
					?>
				</div>
				<?php
			}
			?>
	
			</div>
			<?php
		}
	}
	
	if (isset($results['scaling'])) {
		render_sim_scaling($results['scaling'], $results['damage'] / ($results['time'] / 1000000));
	}

	if (isset($results['effects'])) {
		render_sim_effects($results['effects'], $results['time']);
	}
}
?>