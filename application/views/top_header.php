<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if (empty($header_title)) { $header_title = array(); }
?>
<div class="top-header">
	<div class="th-left">
		<div class="th-button j-left-menu-button"><i class="fa fa-bars"></i></div>
		<div class="th-button j-top-back-button"><i class="fa fa-angle-left"></i></div>
		<? if (!empty($header_buttons)) { foreach ($header_buttons as $hb) { ?>
			<div class="th-button" onclick="<?=$hb['click']?>"><?=$hb['name']?></div>
		<? }} ?>
	</div>
	<div>
		<? foreach ($header_title as $ht) { ?>
		<div class="th-title"><?=$ht['name']?></div>
		<? } ?>
	</div>
	<div class="th-right">
		<div class="th-button j-top-search-button"><i class="fa fa-search"></i></div>
	</div>
</div>

<script type="text/javascript">
	$('.j-top-back-button').click(function () {
		history.back();
	});

	$('.j-top-search-button').click(function () {
		if (!$('.j-top-menu').is(':visible')) {
			$('.j-top-menu-button').click();
		}
		$('.j-search-query').focus();
	});

	$('.j-left-menu-button').click(function () {
		var that = this;
		var $menu = $('.j-left-menu');
		var $c = $('.j-content');
		var $f = $('.fa', that);
		if ($menu.is(':visible')) {
			$menu.hide();
			$('body')[0].scrollTop = 0;
			$c.css({position: 'absolute'}).animate({'left': '0'}, 10, function () {
				$c.css({position: ''}).removeClass('left-menu__active');
				$f.removeClass('fa-arrow-left').addClass('fa-bars');
			}).unbind('click');
		} else {
			$menu.show()[0].scrollTop = 0;
			$c.css({position: 'absolute'}).addClass('left-menu__active ').animate({'left': '17em'}, 10, function () {
				$c.css({position: ''}).bind('click', function (e) {
					if (e.eventPhase) {
						e.stopPropagation();
						e.preventDefault();
						that.click();
					}
				});
				$f.removeClass('fa-bars').addClass('fa-arrow-left');
			});
		}
	})
</script>