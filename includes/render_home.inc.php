<?php
function render_home() {
	?>
	<center>
		<h1>xiv-sim</h1>
	</center>
	
	<?php
	require_once 'featured_sims.inc.php';
	$featured = featured_sims();
	for ($i = 0; $i < 2; ++$i) {
		?>
		<div class="framed-box">
			<center><h3><?= ($i ? 'Sustained Simulations (6 - 11 minutes)' : 'Short Simulations (2.5 - 4 minutes)') ?></h3></center>
			<table class="info-table">
				<thead>
					<tr>
						<th>&nbsp;</th>
						<th class="numeric">Avg DPS</th>
						<th class="numeric">Min DPS</th>
						<th class="numeric">Max DPS</th>
						<th class="numeric">DET Value</th>
						<th class="numeric">CRT Value</th>
						<th class="numeric">SKS/SPS Value</th>
					</tr>
				</thead>
				<tbody>
					<?php
					foreach ($featured as $id => $sim) {
						if (($i == 0 && strstr($id, 'short')) || ($i == 1 && strstr($id, 'sustained'))) {
							$results = json_decode($sim['results'], true);
							$baselineDPS = $results['damage'] / ($results['time'] / 1000000);
							$primaryStatGain = 0;
							foreach (array('str', 'dex', 'int') as $stat) {
								if (isset($results['scaling'][$stat])) {
									$primaryStatGain = max($primaryStatGain, $results['scaling'][$stat]['damage'] / ($results['scaling'][$stat]['time'] / 1000000) - $baselineDPS);
								}
							}
							$detDPS = $results['scaling']['det']['damage'] / ($results['scaling']['det']['time'] / 1000000);
							$crtDPS = $results['scaling']['crt']['damage'] / ($results['scaling']['crt']['time'] / 1000000);
							$spdDPS = isset($results['scaling']['sks']) ? ($results['scaling']['sks']['damage'] / ($results['scaling']['sks']['time'] / 1000000)) : ($results['scaling']['sps']['damage'] / ($results['scaling']['sps']['time'] / 1000000));
							?>
							<tr>
								<td><?= htmlspecialchars($id) ?></td>
								<td class="numeric"><a href="?feature=<?= htmlspecialchars($id) ?>"><?= htmlspecialchars($baselineDPS) ?></a></td>
								<td class="numeric"><a href="?feature=<?= urlencode($id) ?>&seed=<?= urlencode($results['worst-seed']) ?>&len=<?= urlencode($results['worst-time'] / 1000000) ?>"><?= htmlspecialchars($results['worst-dps']) ?></a></td>
								<td class="numeric"><a href="?feature=<?= urlencode($id) ?>&seed=<?= urlencode($results['best-seed']) ?>&len=<?= urlencode($results['best-time'] / 1000000) ?>"><?= htmlspecialchars($results['best-dps']) ?></a></td>
								<td class="numeric"><?= ($detDPS - $baselineDPS) / $primaryStatGain ?></td>
								<td class="numeric"><?= ($crtDPS - $baselineDPS) / $primaryStatGain ?></td>
								<td class="numeric"><?= ($spdDPS - $baselineDPS) / $primaryStatGain ?></td>
							</tr>
							<?php
						}
					}
					?>
				</tbody>
			</table>
		</div>
		<?php
	}
	?>
	
	<br /><br />
	
	<b>What is this?</b>
	
	<p>This is a simulator for Final Fantasy 14. It aims to facilitate a better understanding of how stat and rotation changes might impact damage throughput.</p> 

	<b>How accurate is it?</b>
	
	<p>Some of the damage formulas being used might not be very accurate. Currently, the monk formula is believed to be very accurate, but the others (in particular, the bard and black mage formulas) seem to be off. If you can help with any of the formulas, please create an issue on <a href="https://github.com/ccbrown/xiv-sim" target="_blank">Github</a>.</p>
	
	<p>The abilities that are implemented are believed to be accurate, but not having all of the jobs at max-level, I can't confirm that the in-game effects are exactly what the tooltip texts have led me to believe. So I have to make a fair amount of assumptions. If any abilities don't seem to be functioning properly, create an issue on <a href="https://github.com/ccbrown/xiv-sim" target="_blank">Github</a>.
	
	<p>Some abilities haven't been implemented. If there are any additional abilities (or items) in particular that you'd like to see implemented, create an issue on <a href="https://github.com/ccbrown/xiv-sim" target="_blank">Github</a>. The vast majority of them can be added very quickly if you pressure me a little.</p>

	<p>Some rotations aren't ideal. I don't play all of these classes. In fact, the only one I play seriously is monk. If you can improve a rotation, put your improvements in an issue on <a href="https://github.com/ccbrown/xiv-sim" target="_blank">Github</a>. I'll run it through a crap-ton of simulations with varying stats and lengths, and if it puts out higher numbers, I'll make it the default.</p>
	
	<p>For the moment, only target dummy combat is simulated. Eventually I'll add parameters to give more control over the combat conditions.</p>
	
	<p>Accuracy cap is assumed. I would definitely like to simulate hits and misses, but this is of relatively low priority since it's generally assumed to be the highest priority until capped and the invisible avoidance number that varies from boss to boss isn't well defined or understood.</p>

	<p>Obviously, several jobs are missing. I will be adding those soon, but I'm prioritizing the pure dps jobs.</p>
	
	<p>There may be other issues open on <a href="https://github.com/ccbrown/xiv-sim/issues?state=open" target="_blank">Github</a> that I haven't bothered updating this page with.</p>

	<b>How do I use it?</b>
	
	<p>First, select a class at the top of the page. Then enter your stats and the simulation length, edit the rotation if you'd like, and click the button. You'll then see the results of a single simulated fight.</p>
	
	<b>How can I do larger scale simulations?</b>
	
	<p>You can't through the website, but the simulator is open source and cross-platform. If you're tech savvy, you can download it from <a href="https://github.com/ccbrown/xiv-sim" target="_blank">Github</a>, compile it, and use it directly from the command line of an OS X or Linux machine to do larger scale simulations (It shouldn't take too much to get it to compile on Windows, but I haven't done that yet.). Eventually, if there's enough support, I'll likely distribute a desktop application for Windows.</p>
	
	<b>What if I have other questions?</b>
	
	<p>If you just have any other questions or comments or anything of the sort, you can come to our <a href="http://ffxivguild.net/forums" target="_blank">forums</a> to ask them.</p>
	
	<?php
}
?>