<div class="lesson-items">
	<?php foreach ($lessons as $lesson) { ?>
		<div class="li-item">			
			<div class="lii-data">
			<div>
			<span class="ld-status ld-status-<?=$lesson['status']?>"></span>
			</div>
				<div class="liid-date">
					
					<span class="ld-date"><?=timestamp_to_human($lesson['start_date'], 'd mnth %Y')?></span>
					<div class="liidd-time">
						<span class="ld-time-hour"><?=timestamp_to_human($lesson['start_date'], 'H')?></span><span class="ld-time-minute"><?=timestamp_to_human($lesson['start_date'], 'i')?></span>
					</div>
				</div>
				<div class="liid-duration-cost">
					<span class="ld-cost">
						<span class="ldc-cost"><?=($lesson['cost'])?> <span class="ld-currency">руб.</span></span>
						<span class="ldc-duration"><?=($lesson['duration'])?> <span class="ld-currency">мин.</span></span>
					</span>
				</div>
				
				<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#lessonEditModal" onclick="$('#lessonEditModal .modal-body').html(''); $.get('/services/lesson/edit/<?=$lesson['id']?>/<?=$lesson['client_id']?>', function(html) { $('#lessonEditModal .modal-body').html(html); })">
						...
					</button>
			</div>
			
		</div>
	<?php } ?>
</div>

