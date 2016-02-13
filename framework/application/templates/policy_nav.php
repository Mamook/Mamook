<?php /* framework/application/templates/policy_nav.php */
$policy_nav='<ul class="nav-1 policy">'.
	'<li class="list-nav-1'.Document::addHereClass(APPLICATION_URL.'policy/', TRUE, FALSE).'">'.
		'<a href="'.APPLICATION_URL.'policy/" title="Policy Statment">Policy Statment</a>'.
	'</li>'.
	'<li class="list-nav-1'.Document::addHereClass(APPLICATION_URL.'policy/dispute.php', FALSE, FALSE).'">'.
		'<a href="'.APPLICATION_URL.'policy/dispute.php" title="Policy Dispute">Policy Dispute</a>'.
	'</li>'.
	'<li class="list-nav-1'.Document::addHereClass(APPLICATION_URL.'policy/OptOut.php', FALSE, FALSE).'">'.
		'<a href="'.APPLICATION_URL.'policy/OptOut.php" title="Opt Out">Opt Out</a>'.
	'</li>'.
'</ul>';