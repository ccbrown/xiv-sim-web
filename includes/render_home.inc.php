<?php
function render_home() {
	?>
	<center>
		<h1>xiv-sim</h1>
	</center>
	
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
			require_once 'featured_sims.inc.php';
			$featured = featured_sims();
			foreach ($featured as $id => $sim) {
				$results = json_decode($sim['results'], true);
				$baselineDPS = $results['damage'] / ($results['time'] / 1000000);
				$primaryStat = isset($results['scaling']['str']) ? 'str' : (isset($results['scaling']['dex']) ? 'dex' : 'int');
				$primaryStatDPS = $results['scaling'][$primaryStat]['damage'] / ($results['scaling'][$primaryStat]['time'] / 1000000);
				$primaryStatGain = $primaryStatDPS - $baselineDPS;
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
			?>
		</tbody>
	</table>
	
	<br /><br />
	
	<b>What is this?</b>
	
	<p>This is a simulator for Final Fantasy 14. It aims to facilitate a better understanding of how stat and rotation changes might impact damage throughput.</p> 

	<b>How accurate is it?</b>
	
	<p>Skill and spell speed seem to provide no benefit or even reduce damage per second for summoner and bard. Figuring out what's going on here is currently my top priority. The problem might simply be that the rotations make poor decisions when low on mp or tp. Or it might be a more critical bug.</p>
	
	<p>Some of the damage formulas being used might not be very accurate. Prioritizing the coding over the statistical analysis, I haven't spent much time refining them yet. Instead, I've used what I could find via a few Google searches. The only formula I believe to be accurate enough to trust the output for is the monk formula.</p>

	<p>I believe all of the core combat mechanics are nearly entirely accurate. If there's something off, create an issue on <a href="https://github.com/ccbrown/xiv-sim" target="_blank">Github</a> and I'll fix it as soon as possible.</p>
	
	<p>The abilities that are implemented are also believed to be accurate, but not having all of the jobs at max-level, I can't confirm that the in-game effects are exactly what the tooltip texts have led me to believe. So I have to make a fair amount of assumptions. If any abilities don't seem to be functioning properly, create an issue on <a href="https://github.com/ccbrown/xiv-sim" target="_blank">Github</a>.
	
	<p>Some abilities haven't been implemented. If there are any additional abilities (or items) in particular that you'd like to see implemented, create an issue on <a href="https://github.com/ccbrown/xiv-sim" target="_blank">Github</a>. The vast majority of them can be added very quickly.</p>

	<p>Some rotations aren't ideal. I don't play all of these classes. In fact, the only one I play seriously is monk. If you can improve a rotation, put your improvements in an issue on <a href="https://github.com/ccbrown/xiv-sim" target="_blank">Github</a>. I'll run it through a crap-ton of simulations with varying stats and lengths, and if it puts out higher numbers, I'll make it the default. One rotation in particular that definitely needs more attention is the black mage rotation, because it can currently get stuck in states where it does hardly any dps.</p>
	
	<p>For the moment, only solo target dummy combat is simulated. Eventually I'll add parameters to give more control over the combat conditions.</p>
	
	<p>Accuracy cap is assumed. I would definitely like to simulate hits and misses, but this is of relatively low priority since it's generally assumed to be the highest priority until capped and the invisible avoidance number that varies from boss to boss isn't well defined or understood.</p>
	
	<p>There is no pre-combat phase. Buffs like Aetherflow and Fists of Fire are put up at the beginning of combat. Adding pre-combat conditions (or at least making summoners start with Aetherflow up) is a pretty high priority.</p>

	<b>How do I use it?</b>
	
	<p>First, select a class at the top of the page. Then enter your stats and the simulation length, edit the rotation if you'd like, and click the button. You'll then see the results of a single simulated fight.</p>
	
	<p>Soon I'll add ways to do other cool things like determine stat weights and simulate entire parties.</p>
	
	<b>How can I do larger scale simulations?</b>
	
	<p>You can't through the website, but the simulator is open source and cross-platform. If you're tech savvy, you can download it from <a href="https://github.com/ccbrown/xiv-sim" target="_blank">Github</a>, compile it, and use it directly from the command line of an OS X or Linux machine to do larger scale simulations. Eventually, if there's enough support, I'll likely distribute a desktop application for Windows. It'll take quite a bit of work though and I personally detest doing any sort of software development on Windows.</p>
	
	<b>What if I have other questions?</b>
	
	<p>If you just have any other questions or comments or anything of the sort, you can come to our <a href="http://ffxivguild.net/forums" target="_blank">forums</a> to ask them.</p>
	
	<?php
}
?>