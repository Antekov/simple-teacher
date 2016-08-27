<div class="header">
	<div class="container">
		<div onclick="g.go({url:'/'})" style="cursor: pointer; position: absolute; font-family: 'PT Sans','Open Sans','Helvetica', sans-serif; margin-top: 0%; font-size: 2em; text-align: center; display: inline-block;">
			<span style="color: #D5D5D5;">ЛПХ</span>
			<span style="color:#0db2ff;">Просто</span> <span style="color: #A0A0A0">&mdash;</span> полезно
			<?
function func($a) {
    if ($a > 0) return;
    echo $a;
}?>
		</div>
	</div>
</div>
<div class="container">
	<div class="content">
<div class="widget">
	<div class="yellow-frame ta-center">
		<h1>Авторизация</h1>
	</div>
	<div class="content ta-center">
<form id="login_form" method="post" action="/login?return=<?=$return ?>&random=<?=time()?>">
				<? if (isset($error) && $error) { ?> 
				<div class="error">Wrong login-password combination. Try again.</div>
				<? } ?>
						<input class="ta-center" type="text" id="login" size="16" name="login" value="<? echo isset($login) ? $login : '' ?>" placeholder="Логин / тел. / e-mail"/>
					<br>
					<input class="ta-center" type="password" id="pass" size="16" name="pass" value="" placeholder="Пароль"/>
					<div class="toolbar">
					<div class="button button_default" onclick="$('#login_form').submit();">Войти</div><br><br>
					 <a href="#" onclick="$('#login_form').hide(); $('#password_form').show(); $('#password_form [name=login]').val($('#login_form [name=login]').val()); return false;">Какой у меня пароль?</a>
					</div>
			</form>
			<form id="password_form" action="/ajax/customer/customer/password" method="post" onsubmit="return false;" style="display: none;">
				<h3>Прислать новый пароль на</h3>
				<input class="ta-center" type="text" id="login" size="16" name="login" value="<? echo isset($login) ? $login : '' ?>" placeholder="телефон / e-mail"/><br>
				<div class="button button_default" onclick="customer.password(this.form);">Отправить</div><br><br>
				<a href="#" onclick="$('#password_form').hide(); $('#login_form').show(); return false;">Отмена</a>
			</td>
		</tr>
	</table>
</form>

		</div>

<div class="clear"></div>
	<div class="footer">
		<div class="column colspan-8">
			<div class="copyright">
				ЛПХ Просто &mdash; полезно, <br>
				&copy; <b>2013 - <?=date('Y')?></b>
				<!-- Yandex.Metrika counter --><script type="text/javascript">(function (d, w, c) { (w[c] = w[c] || []).push(function() { try { w.yaCounter25914239 = new Ya.Metrika({id:25914239, webvisor:true, clickmap:true, trackLinks:true, accurateTrackBounce:true}); } catch(e) { } }); var n = d.getElementsByTagName("script")[0], s = d.createElement("script"), f = function () { n.parentNode.insertBefore(s, n); }; s.type = "text/javascript"; s.async = true; s.src = (d.location.protocol == "https:" ? "https:" : "http:") + "//mc.yandex.ru/metrika/watch.js"; if (w.opera == "[object Opera]") { d.addEventListener("DOMContentLoaded", f, false); } else { f(); } })(document, window, "yandex_metrika_callbacks");</script><noscript><div><img src="//mc.yandex.ru/watch/25914239" style="position:absolute; left:-9999px;" alt="" /></div></noscript><!-- /Yandex.Metrika counter -->
			</div>
		</div>
		<div class="column colspan-8">
			<ul class="menu">
				<li><a href="/about/">О нас</a></li>
				<li><a href="/sale/">Каталог</a></li>
				<li><a href="/blog/">Блог</a></li>
				<li><a href="/contacts/">Контакты</a></li>
			</ul>
		</div>
	</div>
</div>
</div>
</div>
</div>
</div>



<script type="text/javascript">
$('input.big').bind('focus', function() { $(this).prev().hide(); }).bind('blur', function() { $(this).prev().show(); })
</script>