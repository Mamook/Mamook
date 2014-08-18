<?php /* application/js/jCarouselLite-call.php */

# Set the Slideshow instance to a variable.
$slideshow=Slideshow::getInstance();
$js='$(function(){'.
		'$(\''.$slideshow->getSelector().'\').jCarouselLite({'.
			'afterEnd:'.$slideshow->getAfterEnd().','."\n".
			'beforeStart:'.$slideshow->getBeforeStart().','."\n".
			'btnNext:\''.$slideshow->getButtonNext().'\','."\n".
			'btnPrev:\''.$slideshow->getButtonPrevious().'\','."\n".
			'visible:'.$slideshow->getVisible().','."\n".
			'scroll:'.$slideshow->getScroll().','."\n".
			'vertical:'.$slideshow->getVertical().','."\n".
			'auto:'.$slideshow->getAuto().','."\n".
			'speed:'.$slideshow->getSpeed().','."\n".
			'circular:'.$slideshow->getCircular().','."\n".
			'start:'.$slideshow->getStart().
		'})'.
	'});';