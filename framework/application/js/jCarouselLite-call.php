<?php /* application/js/jCarouselLite-call.php */

# Set the Slideshow instance to a variable.
$slideshow=Slideshow::getInstance();
$js='$(function(){'.
		'$("'.$slideshow->getSelector().'").jCarouselLite({'.
			'afterEnd:'.$slideshow->getAfterEnd().','.
			'beforeStart:'.$slideshow->getBeforeStart().','.
			'btnNext:".'.$slideshow->getButtonNext().'",'.
			'btnPrev:".'.$slideshow->getButtonPrevious().'",'.
			'visible:'.$slideshow->getVisible().','.
			'scroll:'.$slideshow->getScroll().','.
			'vertical:'.$slideshow->getVertical().','.
			'auto:'.$slideshow->getAuto().','.
			'speed:'.$slideshow->getSpeed().','.
			'circular:'.$slideshow->getCircular().','.
			'start:'.$slideshow->getStart().
		'})'.
	'});';