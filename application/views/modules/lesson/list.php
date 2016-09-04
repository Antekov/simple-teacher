<form class="j-lessons-dates-form" method="post" onsubmit="lessons.init(); return false;">
	<div class="" style="text-align:center">
		<!-- <span class=""><i class="fa fa-angle-double-left"></i></span>
		<span class=""><i class="fa fa-angle-left"></i></span> -->
		<input class="ld-week-input" style="text-align: center" type="week" name="week" value="<?=date('Y-\WW', strtotime((!empty($filters['date_from']) ? $filters['date_from'] : date('Y-m-d'))))?>">
		<!-- <span class=""><i class="fa fa-angle-right"></i></span>
		<span class=""><i class="fa fa-angle-double-right"></i></span> -->
	</div>
</form>
<div id="lessons" class="">
</div>
<script>
	lessons.init_form();
	lessons.init();
</script>