var lessons = new function() {
	this.id = 0;
		
	this.init = function() {
		$('#users').html('<div class="loading" style="z-index: 0;"><div class="spinner">Загружаем данные</div></div>');
		$.get('/user/get/', function(data){
			$('#users').html(data.result);
		});
	};
	
	this.open = function(client_id) {
		g.go({url: '/lesson/edit/'+client_id});
	};
	
	this.close = function(user_id) {
		g.go({url: '/lesson/?id='+user_id});
	}
	
	this.save = function(d) {
		var d = d || {}
		

		var url = '/services/lesson/save/';
		
		g.overlay(true);
		
		$.ajax({
			type: "POST",
			url: url,
			data: $('#lesson_form').serializeArray(),
			success: function(data) {
				if (data.status == 1) {
					
					//projects.init();
					lessons.close(data.id);
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
