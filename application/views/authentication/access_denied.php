<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<link rel="shortcut icon" href="/i/favicon.gif"/>
		<title>Z95 &gt; Authentication</title>
		<link rel="stylesheet" type="text/css" href="/<?=APPPATH?>views/css/common.css" />
		<link rel="stylesheet" type="text/css" href="/<?=APPPATH?>views/css/default.css" />
		<link href="http://fonts.googleapis.com/css?family=Open+Sans:300italic,400,300&subset=latin,cyrillic" rel="stylesheet" type="text/css"/>
		<script language="JavaScript" type="text/javascript" src="/<?=APPPATH?>views/js/jquery.js"></script>
		<script language="JavaScript" type="text/javascript" src="/<?=APPPATH?>views/js/global.js"></script>
		<script type="text/javascript">
			$(document).ready( function(){
				$('#auth_dialog').css('top', ($(window).height()-$('#auth_dialog').height())/2-60);
				$('#auth_dialog').css('left', ($(window).width()-$('#auth_dialog').width())/2-60);
				$('#auth_dialog').fadeIn(200);
			});
		</script>
		<style>
			body { background:black url('/i/login_bg.jpg') no-repeat center center; color:white; }
			#auth_dialog { display:none; }
			.login_form { position:relative; width:300px; padding:30px 20px 30px 100px; background:white url('/i/auth_lock.png') no-repeat 20px 30px; }
			.login_form .error { background:red; color:white; padding: 5px; }
			.login_form table td { font-size:1.2em; }
			.login_form table td input.big { font-size:1.8em; width:200px; }
		</style>
	</head>
	<body>
	<div style="padding:100px 0 0 20%">
		<h1>Access denied</h1>
		<a href="/login">Login</a>
	</div>
	</body>
</html>