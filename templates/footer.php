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
				'<ul>',
					'<li id="copyright">',
						'&copy; ',date("Y"),' ',$main_content->getSiteName(),
					'</li>',
					'<li id="statement">',
					'</li>',
					'<li class="address">',
						$street,$city,$state,$zip,$phone,
					'</li>',
					'<li id="menu3">',
						'<ul>',
							'<li id="policy">',
								'<a href="',APPLICATION_URL,'policy/" title="Privacy Policy">Policy</a>',
							'</li>',
							'<li id="websupport">',
								'<a href="',APPLICATION_URL,'webSupport/" title="Web Support">Web Support</a>',
							'</li>',
							'<li id="sitemap">',
								'<a href="',APPLICATION_URL,'SiteMap/" title="Site Map">Site Map</a>',
							'</li>',
						'</ul>',
					'</li>',
				'</ul>',
			'</footer>',
		'</div>', # End wrapper div
		'<script type="text/javascript">/* <![CDATA[ */',
			$doc->addFooterJS(),
		'/* ]]> */</script>',
	'</body>',
'</html>';