<?php /* templates/footer.php */

$street=$main_content->getAddress1();
$street=((empty($street)) ? '' : '<span class="street">'.$street.'</span> ');

$city=$main_content->getCity();
$city=((empty($city)) ? '' : '<span class="city">'.$city.'</span>,');

$state=$main_content->getState();
$state=((empty($state)) ? '' : ' <span class="state">'.$state.'</span>');

$zip=$main_content->getZipcode();
$zip=((empty($zip)) ? '' : ' <span class="zip">'.$zip.'</span>');

$phone=$main_content->getPhone();
$phone=((empty($phone)) ? '' : '<span class="phone">'.$phone.'</span>');

echo '<footer id="info" class="footer" role="contentinfo">',
				$street,$city,$state,$zip,$phone,
			'</footer>',
		'</div>', # End wrapper div
		'<script type="text/javascript">/* <![CDATA[ */',
			$doc->addFooterJS(),
		'/* ]]> */</script>',
	'</body>',
'</html>';