<?php /* framework/application/templates/footer.php */

$street=$main_content->getAddress1();
$street=((empty($street)) ? '' : '<span class="street">'.$street.'</span> ');

$city=$main_content->getCity();
$city=((empty($city)) ? '' : '<span class="city">'.$city.'</span>');

$state=$main_content->getState();
$state=((empty($state)) ? '' : ' <span class="state">'.$state.'</span>');

$zip=$main_content->getZipcode();
$zip=((empty($zip)) ? '' : ' <span class="zip">'.$zip.'</span>');

$phone=$main_content->getPhone();
$phone=((empty($phone)) ? '' : '<span class="phone">'.$phone.'</span>');

$full_address=$street.$city.((!empty($state)) ? ',' : '').$state.$zip.$phone;

echo '<footer id="info" class="footer" role="contentinfo">',
				'<ul class="info">',
					'<li class="list-info-copyright">',
						((!isset($copyright) OR empty($copyright)) ? '© '.date('Y').' '.$main_content->getSiteName() : $copyright),
					'</li>',
					'<li class="list-info-statement">',
					'</li>',
					'<li class="list-info-address">',
						$full_address,
					'</li>',
					'<li class="list-info-nav nav">',
						'<ul class="nav-1">',
							'<li class="list-nav-1 policy',Document::addHereClass(APPLICATION_URL.'policy/', FALSE, FALSE),'">',
								'<a href="',APPLICATION_URL,'policy/" title="Privacy Policy">Policy</a>',
							'</li>',
							'<li class="list-nav-1 websupport',Document::addHereClass(APPLICATION_URL.'webSupport/', FALSE, FALSE),'">',
								'<a href="',APPLICATION_URL,'webSupport/" title="Web Support">Web Support</a>',
							'</li>',
							'<li class="list-nav-1 sitemap',Document::addHereClass(APPLICATION_URL.'SiteMap/', FALSE, FALSE),'">',
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