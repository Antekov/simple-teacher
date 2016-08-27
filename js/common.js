var g = new function(){
	
};

var skip_key_codes = {
	9: 'tab',
	16:'shift',
	17:'ctrl',
	18:'alt',
	20:'caps',
	91:'lcmd',
	93:'rcmd',
	112: 'f1',113: 'f2',114: 'f3',115: 'f4',116: 'f5',117: 'f6',118: 'f7',119: 'f8',120: 'f9',121: 'f10',
};
$(document).ready(function(){
	$(document).keydown(function(e){
		if(e.keyCode == 27){
			search.reset();
			g.dialog.close();
		}else{
			if( search.settings != undefined && !search.intercepted && !search.disabled){
				if(skip_key_codes[e.keyCode] == undefined ){
					//console.log(e.keyCode);
					search.init();
				}
			}
		}
 } );
});

g.init_layout = function(){
	g.clock();
	if( $('.layout_menu').length > 0 ){
		$('.layout_menu').height( $(window).height() - $('.layout_menu').offset().top );
		$('.splitter').height( $(window).height() - $('.splitter').offset().top );
        $('.layout_header').width( $(window).width() - $('.layout_menu').width() - $('.splitter').width());
        $('.layout_body .layout_content').css('top' , $('.layout_body .layout_header').height()+'px');
        $('.layout_content').height( $(window).height() - $('.layout_content').offset().top );
		$('.logo').css('top', $(window).height() - 50 );
	}
};

g.clock = function(){
	g.tick_tock();
	setInterval( g.tick_tock, 1000 );
};

g.tick_tock = function(){
	var today = new Date();
	//console.log( today.getHours() +':'+today.getMinutes()  );
	var h = today.getHours();
	if( h < 10 ) h = '0'+h;

	var m = today.getMinutes();				
	if( m < 10 ) m = '0'+m;
	
	$('.clock-time').html( h +':'+m );
};

g.alert = function(message, func, args, params){
	params = params || {};
	var window_id = g.dialog($.extend({id: (params.id || undefined)}, {
		header: params.header || 'Внимание',
		content: '<div class="alert">'+message+'</div>',
		toolbar: [{value: params.label_ok || '   OK   ', type: 'close', id: 'bt_ok'}]
	}));
	
	if($.isFunction(func)) {
		$('#dialog_'+window_id+' #bt_ok').attr({onclick: ''}).click(function() {
			func(args || {});
			g.dialog.close(window_id);
		});
	}
	return window_id;
};

g.confirm = function(message, func, args, params) {
    params = params || {};
    var toolbar = [
        {value: params.label_ok || '   OK   ', id: 'bt_ok'},
        {value: params.label_cancel || 'Отмена', type: 'close', id: 'bt_cancel'}
    ];
    if(typeof params.toolbar !== 'undefined'){
        for(var i = 0; i < params.toolbar.length; i++){
            toolbar.push(params.toolbar[i]);
        }
    }
	var window_id = g.dialog({
		header: params.header || 'Требуется подтверждение',
		content: '<div class="confirm">'+message+'</div>',
		toolbar: toolbar
	});
	
	if($.isFunction(func)) {
		$('#dialog_'+window_id+' #bt_ok').click(function() {
			func(args || {});
			g.dialog.close(window_id); 
		});
	}
	
	if($.isFunction(params.cancel)) {
		$('#dialog_'+window_id+' #bt_cancel').attr({onclick: ''}).click(function() {
			params.cancel(params.cancel_args || {});
			g.dialog.close(window_id);
		});
	}
};

g.prompt = function(label, func, args, params) {
    params = params || {};

    var content = '<div class="prompt"><label>'+label+'<br>';
    if(params.textarea){
        content += '<textarea name="prompt_value" style="height: 80%">'+(params.default_value || '')+'</textarea>';
    }else{
        content += '<input name="prompt_value" type="text" value="'+(params.default_value || '')+'">';
    }
    content += '</label></div>';

    var window_id = g.dialog({
        header: 'Внимание',
        content: content,
        toolbar: [
            {value: params.label_ok || '   OK   ', id: 'bt_ok'},
            {value: params.label_cancel || 'Отмена', type: 'close', id: 'bt_cancel'}
        ]
    });

    if($.isFunction(func)) {
        $('#dialog_'+window_id+' #bt_ok').click(function() {
            args = args || {};
            args.prompt_value = $('#dialog_'+window_id).find('[name=prompt_value]').val();
            g.dialog.close(window_id);
            func(args);
        });
    }

    if($.isFunction(params.cancel)) {
        $('#dialog_'+window_id+' #bt_cancel').attr({onclick: ''}).click(function() {
            g.dialog.close(window_id);
            params.cancel(params.cancel_args || {});
        });
    }
};

g.dialog = function( args ){
	args.window_id = (args.id != undefined) ? 'id_'+args.id : $('#dialogs .dialog').length+1;

	g.dialog.create_container(args.window_id);
	$('#dialogs #overlay').show();
	
	// Заполняем
	if( args.url ){
		$.get( args.url, function( data ){
			if( data.header ){ args.header = data.header; }
			if( data.result ){ args.content = data.result; }
			if( data.toolbar ){ args.toolbar = data.toolbar; }
			if( data.width ){ args.width = data.width; }
			if( data.height ){ args.height = data.height; }
			
			g.dialog.set_content( args );
		});
	}else{
		g.dialog.set_content( args );
	}
	return args.window_id;
};

g.dialog.create_container = function(window_id){
	if (!$('#dialogs').length) {
		$('body').append('<div id="dialogs"><b id="overlay"/><div>');
	}
	if (!$('#dialogs #dialog_'+window_id).length) {
		$('#dialogs').append('<div class="dialog" id="dialog_'+window_id+'"/>');
	} else {
		$('#dialogs #dialog_'+window_id).html('');
	}
};

g.dialog.set_content = function( args ){
	args.window = $('#dialogs #dialog_'+args.window_id);
	var default_close = true;
	var close_callback = '';
	var buttons = '';
	
	if( args.toolbar == false ){
	
	}else{
		//console.log( args.toolbar );
		if( args.toolbar ){
			for( i in args.toolbar ){
				var button_class = 'button';
				if( args.toolbar[i].type != undefined ){
					button_class += ' button_'+args.toolbar[i].type;
					if( args.toolbar[i].type == 'cancel' || args.toolbar[i].type == 'close' ){
						default_close = false;
						close_callback = (args.toolbar[i].onclick || '');
						args.toolbar[i].onclick = (args.toolbar[i].onclick || '')+'; g.dialog.close(\''+args.window_id+'\')';
					}
				}

				buttons += '<input '+((args.toolbar[i].id != undefined)?'id="'+args.toolbar[i].id+'"':'')+ 'class="'+button_class+'" type="button" onclick="'+(args.toolbar[i].onclick || '')+'" value="'+args.toolbar[i].value+'"/>';
			}
		}
		if( !args.header && default_close ){
			buttons += '<input class="button" type="button" onclick="g.dialog.close(\''+args.window_id+'\')" value="Close"/>';
		}
		args.window.append('<div class="dialog_toolbar">'+buttons+'</div>');
	}

	if( args.header ){
		args.window.append('<div class="dialog_header">'+args.header+'<div class="close" onclick="'+close_callback+'; g.dialog.close(\''+args.window_id+'\')" title="Close"></div></div>');
	}
	// Рисуем контейнер
	if( args.result ) args.content = args.result;
	if( args.content ){
		args.window.append('<div class="dialog_container">'+args.content+'</div>');
	}

	g.dialog.set_position( args );
};

g.dialog.close = function( window_id ){
	//console.log(window_id);
	if( window_id == undefined ){
		$('#dialogs .dialog').remove();
	}else{
		$('#dialog_'+window_id).remove();
	}
	if (!$('#dialogs .dialog').length) { $('#dialogs #overlay').hide(); }
};

g.dialog.set_position = function( args ){
	//console.log( args );
	if(args.width == undefined) args.width = 600;
	args.width = (args.width == undefined) ? 600 : ( args.width > $(window).width() || args.width == 'auto' ) ? $(window).width()-100 : args.width;
	args.height = (args.height == undefined) ? 300 : ( args.height > $(window).height() || args.height == 'auto' ) ? $(window).height()-100 : args.height;
	args.window.width( args.width );
	args.window.height(args.height);
	args.window.css('left', ($(window).width()-args.window.width())/2);
	args.window.css('top', ($(window).height()-args.window.height())/2-40);
	args.window.fadeIn(100);
	args.window.draggable({handle: 'h1, h2, .header, .dialog_header', cursor: 'move', containment: '.layout_body'});
	var toolbar = args.window.find('.dialog_toolbar');
	toolbar.css('top', args.height - toolbar.height() - 20 );
	toolbar.width( args.width);
	if( args.overlay == false ){
		$('#dialogs #overlay').hide();
	}
	var header = ( args.window.find('.dialog_header').length > 0 ) ? args.window.find('.dialog_header') : false;
	var header_height = 0;
	if( header ){
		header_height = header.height();
	}
	var container = args.window.find('.dialog_container');
	//console.log( 'toolbar height: '+ toolbar.height() );
	//console.log( 'header height: '+ header_height );
	container.height( args.window.height() - toolbar.height() - header_height - 61 /*с учетом паддингов*/ );
};

g.submit = function(form_id, func ){
	g.overlay(true);
	if( $(form_id).prop('method') == 'get' ){
		var form_action = ( $(form_id).prop('action').indexOf('?') == -1 )
				? $(form_id).prop('action')+'?'+$(form_id).serialize()
				: $(form_id).prop('action')+'&'+$(form_id).serialize();
		
		$.get(form_action, function(data){
			g.overlay(false);
			func( data );
		});
	}else{
		$.post($(form_id).prop('action'), $(form_id).serialize(), function(data){
			g.overlay(false);
			func( data );
		});
	}
};
g.go = function( args ){
	if( args == undefined ){
		window.location.reload();
	}else{
		if( args._blank ){
			var _prop = '';
			
			if( args.width != undefined && args.height != undefined ){
				args.left = ( args.left == undefined ) ? ($(window).width()-args.width)/2 : args.left;
				args.top = ( args.top == undefined ) ? ($(window).height()-args.height)/2 -100: args.top;
				_prop = "top="+args.top+",left="+args.left+",width="+args.width+",height="+ args.height;
			}
			return window.open( args.url, (args.name || '_blank'), _prop );
		}else{
			window.location.href = args.url;
		}
	}
};
g.number_format = function(number, decimals, dec_point, thousands_sep) {
	// Strip all characters but numerical ones.
	number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
	var n = !isFinite(+number) ? 0 : +number,
	prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
	sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
	dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
	s = '',
	toFixedFix = function (n, prec) {
		var k = Math.pow(10, prec);
		return '' + Math.round(n * k) / k;
	};
	// Fix for IE parseFloat(0.55).toFixed(0) = 0;
	s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
	if (s[0].length > 3) {
		s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
	}
	
	if ((s[1] || '').length < prec) {
		s[1] = s[1] || '';
		s[1] += new Array(prec - s[1].length + 1).join('0');
	}
	return s.join(dec);
};
g.set_user_data = function(name, value){
	$.get( '/orders/set_user_data/'+name+'/'+value, function(data){
		g.go();
	});
};

g.overlay = function(show) {
	var $overlay = $('body .overlay');
	
	if (show) {
		if (!$overlay.length) { $('body').append('<div class="overlay"><div><div class="loading"><div class="spinner">Загружаем данные</div></div></div></div>'); }
	} else {
		if ($overlay.length) { $overlay.remove(); }
	}
};

g.toggleMenu = function(){
    $('.layout_menu').toggle(0, function(){
        if($('.layout_menu').css('display') == 'none'){
            $('.splitter').css('left', '0');
            $('.layout_content').css('margin-left',$('.splitter').width()+'px');
            $('.layout_header').width( $(window).width() - $('.splitter').width());
        }else{
            $('.splitter').css('left', $('.layout_menu').width()+'px');
            $('.layout_content').css('margin-left',($('.layout_menu').width()+$('.splitter').width())+'px');
            $('.layout_header').width( $(window).width() - $('.layout_menu').width() - $('.splitter').width());
        }
        $.get('/users/set_menu_status/'+(($('.layout_menu').css('display') == 'none') ? 0 : 1));
    });

};

g.news_read = function(news_id) {
	$.get('/news/read/'+news_id, function(data) {
		if (data.status == 1) {
			g.dialog(data);
		}
	});
};


jQuery.fn.numericOnly = function(){
    return this.each(function()
    {
        $(this).keydown(function(e)
        {
            var key = e.charCode || e.keyCode || 0;
            // Разрешаем backspace, tab, delete, стрелки, обычные цифры и цифры на дополнительной клавиатуре
            return (
                key == 8 ||
                key == 9 ||
                key == 46 ||
                (key >= 37 && key <= 40) ||
                (key >= 48 && key <= 57) ||
                (key >= 96 && key <= 105));
        });
    });
};

var _on_ohstname_callbacks = [];
function onHostname(func) {
	_on_ohstname_callbacks.push(func);
}
$(document).ready(function() {
	$('head').bind('DOMSubtreeModified', function() {
		var $h = $('hostname');
		if ($h && $h.html() != '' && $h.html() != null ) {
			var hostname = $h.html();
			for ( i in _on_ohstname_callbacks ) {
				if (jQuery.isFunction(_on_ohstname_callbacks[i])) {
					_on_ohstname_callbacks[i](hostname);
				}
			}
		} 
	});
});