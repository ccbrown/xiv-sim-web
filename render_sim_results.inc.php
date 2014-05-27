<?php
function render_sim_results($json_results) {
	$results = json_decode($json_results, true);
	?>
	<script src="./js/highcharts/highcharts.js"></script>
	<script src="./js/highcharts/modules/exporting.js"></script>

	<center>

	<div class="wrapper">

	<br />

	<h1>Simulation Results</h1>
	
	<b>Time:</b> <?= htmlspecialchars($results['length'] / 1000000) ?><br />
	<b>Damage:</b> <?= htmlspecialchars($results['damage']) ?><br />
	<b>DPS:</b> <?= htmlspecialchars($results['dps']) ?><br />

	<?php
	foreach ($results['subjects'] as $id => $subject) {
		?>
		<div class="actor-frame">

		<h2><?= htmlspecialchars($id) ?></h2>
		<table width="100%">
			<tr>
				<td>
					<br /><br />
					<b>Damage:</b> <?= htmlspecialchars($subject['damage']) ?><br />
					<b>DPS:</b> <?= htmlspecialchars($subject['dps']) ?><br />
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
	
								$end = ($i + 1) < count($samples) ? $samples[$i + 1][0] : $results['length'];
								$width = ($end - $t) / $results['length'] * 100;
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
		if (count($subject['effects'])) {
			?>
			<br /><br />
			
			<table class="info-table">
				<tr>
					<th>Effect</th>
					<th class="numeric">Damage</th>
					<th class="numeric">DPS</th>
					<th class="numeric">Count</th>
					<th class="numeric">Crits</th>
					<th class="numeric">Average Damage</th>
				</tr>
				<?php
				foreach ($subject['effects'] as $effect) {
					?>
					<tr>
						<td><?= htmlspecialchars($effect['id']) ?></td>
						<td class="numeric"><?= htmlspecialchars($effect['damage']) ?></td>
						<td class="numeric"><?= htmlspecialchars($effect['dps']) ?></td>
						<td class="numeric"><?= htmlspecialchars($effect['count']) ?></td>
						<td class="numeric"><?= htmlspecialchars($effect['crits']) ?></td>
						<td class="numeric"><?= htmlspecialchars($effect['avg-damage']) ?></td>
					</tr>
					<?php
				}
				?>
			</table>
			<?php
		}
		?>

		</div>
		<?php
	}
	?>
	
	</div>
	
	</center>
	
	<?php
}
?>