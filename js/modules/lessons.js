var lessons = new function() {
	this.id = 0;
	var self = this;



	this.init = function() {
		$('#lessons').html('<div class="loading"><i class="fa fa-spin fa-spinner"></i>Загружаем данные</div></div>');

		$.post('/services/lesson/get',
			$('.j-lessons-dates-form').serialize(),
			function(data){
				$('#lessons').html(data.result);
			}
		);
	};

	this.fill_schedule = function() {
		$('#lessons').html('<div class="loading"><i class="fa fa-spin fa-spinner"></i>Загружаем данные</div></div>');

		$.post('/services/lesson/fill',
			$('.j-lessons-dates-form').serialize(),
			function(data) {
				if (data.success == '1') {
					$('#lessons').html(data.result);
				} else {
					self.init();
				}
			}
		);
	};

	this.init_form = function() {
		$('[type="week"]').change(function() {
			self.init();
		});


		var form = $('.lesson-form');
		if (form.length) {
			var place = 0;

			self.changeDate = function () {
				$('[name="start_date"]', form).val(
					$('.lf-date-input').val() + ' ' + $('.lf-time-input').val()
				)
			};

			// Собираем дату и время занятия в одно поле при изменении полей даты и времени
			$('.lf-date-input, .lf-time-input', form).on('change keyup', self.changeDate);
			
			// При смене клиента меняем продолжительность занятия на типовую для этого клиента
			$('[name="client_id"]', form).on('change keyup', function() {
				$('.data-duration').val($('option', this)[this.selectedIndex].dataset['duration']);
			});

			$('.lf-date-input')[0].focus();
		}
	};
	
	this.open = function(lesson_id, client_id) {
		var client_id = client_id || '';
		g.go({url: '/lesson/edit/'+lesson_id+'/'+client_id});
	};
	
	this.close = function(data) {
		if (data.return_url != '') {
			return_url = data.return_url
		} else {
			return_url = '/client/edit/'+data.client_id+'#lessons' 
		}
		
		g.go({url: return_url});
	}
	
	this.save = function(d) {
		var d = d || {}
		
		var form = $('#lesson_form');

		if (form.length) {

			if ($('select[name="client_id"]', form).val() == '') return;

			var url = '/services/lesson/save/';
			
			g.overlay(true);
			
			$.ajax({
				type: "POST",
				url: url,
				data: form.serializeArray(),
				success: function(data) {
					if (data.status == 1) {
						lessons.close(data);
					}
				},
				dataType: 'json'
			});
		}
	}
	
	this.status = function(status) {
		var url = '/services/lesson/status/'+this.id+'/'+status+'/';
		
		g.overlay(true);
		
		$.ajax({
			type: "GET",
			url: url,
			success: function(data) {
				if (data.status != -1) {

					lessons.init();
				}
				
			},
			dataType: 'json'
		});
	}
	
	this.delete = function(d) {
		var url = '/services/lesson/delete/'+this.id+'/';
		
		g.overlay(true);
		
		$.ajax({
			type: "GET",
			url: url,
			success: function(data) {
				if (data.status != -1) {
					lessons.init();
				}
				
			},
			dataType: 'json'
		});
	}
	
	this.add_comment = function(user_id, $comment) {
		g.overlay(true);
		
		$.ajax({
			type: "POST",
			url: '/user/add_comment',
			data: {user_id: user_id, comment: $comment.val()},
			success: function(data) {
				if (data.status == 1) {
					$('#user_comments').append(data.result);
					$comment.val('');
				}
				g.overlay(false);
			},
			dataType: 'json'
		});
	}
}
