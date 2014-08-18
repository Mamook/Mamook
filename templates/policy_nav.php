<?php /* templates/policy_nav.php */ ?>
<ul>
	<li<?php echo Document::addHereClass(APPLICATION_URL.'policy/', TRUE); ?>>
		<a href="<?php echo APPLICATION_URL; ?>policy/" title="Policy Statment">Policy Statment</a>
	</li>
	<li<?php echo Document::addHereClass(APPLICATION_URL.'policy/dispute.php'); ?>>
		<a href="<?php echo APPLICATION_URL; ?>policy/dispute.php" title="Policy Dispute">Policy Dispute</a>
	</li>
	<li<?php echo Document::addHereClass(APPLICATION_URL.'policy/OptOut.php'); ?>>
		<a href="<?php echo APPLICATION_URL; ?>policy/OptOut.php" title="Opt Out">Opt Out</a>
	</li>
</ul>