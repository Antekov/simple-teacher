var clients = new function() {
	this.id = 0;

	this.init_form = function() {
		var c = $('.client-form');
		var place = 0;

		$('.cf-field-place', c).change(function() {
			place = $('.cf-field-place', c).val();
			$('.cf-only-place', c).hide().filter('.cf-only-place-'+place).show();

			var placeClasses = ['fa-skype', 'fa-user', 'fa-home']
			$('.j-client-place .fa').removeClass(placeClasses.join(' ')).addClass(placeClasses[place]);
		});

		$('.cf-field-place', c).change();

		$('[name*="_date"]', c).datepicker({
			dateFormat: 'dd.mm.yy',
			monthNames: [ "Январь", "Февраль", "Март", "Апрель", "Май", "Июнь", "Июль", "Август", "Сентябрь", "Октябрь", "Ноябрь", "Декабрь" ],
			dayNamesMin: [ "Вс", "Пн", "Вт", "Ср", "Чт", "Пт", "Сб" ],
			firstDay: 1
		});

		$('input[name=name]', c).bind('change keyup', function() {
			if ($(this).val() != '') {
				$('.j-client-name').html($(this).val());
			} else {
				$('.j-client-name').html('Новый');
			}
		});

		this.addComment = function(el) {
			$(el).parent().parent().find('.phone-comment').show();
		}
	};
		
	this.init = function() {
		$('#users').html('<div class="loading" style="z-index: 0;"><div class="spinner">Загружаем данные</div></div>');
		$.get('/user/get/', function(data){
			$('#users').html(data.result);
		});
	};
	
	this.open = function(client_id) {
		g.go({url: '/client/edit/'+client_id});
	};
	
	this.close = function(user_id) {
		g.go({url: '/client/?id='+user_id});
	}
	
	this.save = function(d) {
		var d = d || {}
		
		if ($('input[name="title"]').val() == '') {
			return g.alert('Введите название задачи');
		}
		
		var url = '/services/client/save/';
		
		g.overlay(true);
		
		$.ajax({
			type: "POST",
			url: url,
			data: $('#client_form').serializeArray(),
			success: function(data) {
				if (data.status == 1) {
					
					//projects.init();
					clients.close(data.id);
				}
				
			},
			dataType: 'json'
		});
	}
	
	this.status = function(status) {
		var url = '/user/status/'+this.id+'/'+status+'/';
		
		g.overlay(true);
		
		$.ajax({
			type: "GET",
			url: url,
			success: function(data) {
				if (data.status == 1) {
					users.replace(users.id);
				}
				
			},
			dataType: 'json'
		});
	}
	
	this.delete = function(d) {
		var d = d || {}
		if (d.confirm == undefined) {
			if (d.project_id != undefined) { projects.id = d.project_id; }
			return g.confirm('Вы уверены, что хотите удалить бренд?', projects.delete, {project_id:projects.id, count:0, confirm:1}, {label_ok:'    Да    ', label_cancel:'Отмена'});
		}
		
		if (d.project_id != undefined) {
			
			g.overlay(true);
			$.ajax({
				type: "GET",
				url: '/projects/delete/'+d.project_id,
				success: function(data) {
					g.overlay(false);
					if (data.status == 1) {
						projects.close();
						projects.init();
					} else if (data.status == 2) {
						g.alert('У бренда есть артикулы!<br><br>Для удаления этого бренда:<br>- выберите в поле "<i>Сохранить как</i>" правильный бренд для этих артикулов<br>- нажмите кнопку "<i>Сохранить</i>"')
					}
				},
				dataType: 'json'
			});
		}
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
$(function () {
	clients.form = new clients.init_form();
});