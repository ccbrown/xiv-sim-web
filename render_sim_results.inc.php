<?php
function render_sim_results($json_results) {
	$results = json_decode($json_results, true);
	?>
	<b>Simulation Length:</b> <?= htmlspecialchars($results['length']) ?><br />
	<b>Damage:</b> <?= htmlspecialchars($results['damage']) ?><br />
	<b>DPS:</b> <?= htmlspecialchars($results['dps']) ?><br />
	<table>
		<tr>
			<th>Effect</th>
			<th class="numeric">Damage</th>
			<th class="numeric">DPS</th>
			<th class="numeric">Count</th>
			<th class="numeric">Crits</th>
			<th class="numeric">Average Damage</th>
		</tr>
		<?php
		foreach ($results['effects'] as $effect) {
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