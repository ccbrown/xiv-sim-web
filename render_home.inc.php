<?php
function render_home() {
	?>
	<center>
		<h1>Welcome!</h1>
	
		<a href="?monk">Monk</a> | <a href="?dragoon">Dragoon</a> | <a href="?bard">Bard</a> | <a href="?summoner">Summoner</a> | <a href="?black-mage">Black Mage</a>
	</center>

	<br /><br />
	
	<b>What is this?</b>
	
	<p>This is a simulator for Final Fantasy 14. It aims to facilitate a better understanding of how stat and rotation changes might impact damage throughput.</p> 

	<b>How accurate is it?</b>
	
	<p>Currently, I believe all of the core combat mechanics are nearly entirely accurate. If there's something off, create an issue on <a href="https://github.com/ccbrown/xiv-sim" target="_blank">Github</a> and I'll fix it as soon as possible.</p>
	
	<p>The abilities that are implemented are also believed to be accurate, but not having all of the jobs at max-level, I can't confirm that the in-game effects are exactly what the tooltip texts have led me to believe. So I have to make a fair amount of assumptions. If any abilities don't seem to be functioning properly, create an issue on <a href="https://github.com/ccbrown/xiv-sim" target="_blank">Github</a>.
	
	<p>Some abilities haven't been implemented. If there are any additional abilities (or items) in particular that you'd like to see implemented, create an issue on <a href="https://github.com/ccbrown/xiv-sim" target="_blank">Github</a>. The vast majority of them can be added very quickly.</p>

	<p>Some jobs haven't been implemented. My highest priority right now is getting the fundamentals for each of the 5 pure damage jobs up.</p>

	<p>Some rotations aren't ideal. I don't play all of these classes. In fact, the only one I play seriously is Monk. If you can improve a rotation, put your improvements in an issue on <a href="https://github.com/ccbrown/xiv-sim" target="_blank">Github</a>. I'll run it through a crap-ton of simulations with varying stats and lengths, and if it puts out higher numbers, I'll make it the default.</p>
	
	<p>For the moment, only solo target dummy combat is simulated. Eventually I'll add parameters to give more control over the combat conditions.</p>
	
	<p>Accuracy cap is assumed. I would definitely like to simulate hits and misses, but this is of relatively low priority since it's generally assumed to be the highest priority until capped and the invisible avoidance number that varies from boss to boss isn't well defined or understood.</p>
	
	<p>There is no pre-combat phase. Buffs like Aetherflow and Fists of Fire are put up at the beginning of combat. Adding pre-combat conditions is a high priority.</p>
	
	<p>Finally, and most importantly, some of the damage formulae being used aren't very accurate. Prioritizing the coding over the statistical analysis, I haven't spent much time refining them yet. Instead, I've used what I could find via a few Google searches. The only formula I believe to be accurate enough to trust the output for is the Monk formula.</p>

	<b>How do I use it?</b>
	
	<p>First, select a class. Then enter your stats and the simulation length, edit the rotation if you'd like, and click the button. You'll then see the results of a single simulated fight.</p>
	
	<p>Soon I'll add ways to do other cool things like determine stat weights and simulate entire parties.</p>

	<?php
}
?>