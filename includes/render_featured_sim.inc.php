<?php
function render_featured_sim() {
	$identifier = $_GET['feature'];
	
	require_once 'featured_sims.inc.php';
	$sims = featured_sims();
		
	if (!isset($sims[$identifier])) {
		die('Invalid identifier.');
	}
	
	$sim = $sims[$identifier];

	$len  = (isset($_GET['len']) && is_numeric($_GET['len'])) ? $_GET['len'] : 0;
	$seed = (isset($_GET['seed']) && is_numeric($_GET['seed'])) ? $_GET['seed'] : 0;

	$sim_results = NULL;
	
	if ($len && $seed) {
		if ($len < 1 || $len > 30 * 60) {
			die('Invalid length.');
		}
		
		$load = sys_getloadavg();
		if ($load[0] > 16) {
			die('Server overloaded. Please try again in a moment.');
		}

		$command = "./simulator single-json --length ".escapeshellarg($len)." --seed ".escapeshellarg($seed)." ".escapeshellarg($sim['subject-file'])." ".escapeshellarg($sim['rotation-file']);
		exec($command, $output, $return_code);
		
		if ($return_code) {
			die(nl2br(str_replace(" ", "&nbsp;", htmlspecialchars(implode("\n", $output)))));
		} else {
			$sim_results = implode("\n", $output);
		}
	} else {
		$sim_results = $sim['results'];
	}
	
	require_once 'includes/render_sim_results.inc.php';
	render_sim_results($sim_results, $identifier);
}
?>