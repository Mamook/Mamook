<?php /* templates/policy_nav.php */
$policy_nav='<ul>'.
	'<li'.Document::addHereClass(APPLICATION_URL.'policy/', TRUE).'>'.
		'<a href="'.APPLICATION_URL.'policy/" title="Policy Statment">Policy Statment</a>'.
	'</li>'.
	'<li<'.Document::addHereClass(APPLICATION_URL.'policy/dispute.php').'>'.
		'<a href="'.APPLICATION_URL.'policy/dispute.php" title="Policy Dispute">Policy Dispute</a>'.
	'</li>'.
	'<li'.Document::addHereClass(APPLICATION_URL.'policy/OptOut.php').'>'.
		'<a href="'.APPLICATION_URL.'policy/OptOut.php" title="Opt Out">Opt Out</a>'.
	'</li>'.
'</ul>';