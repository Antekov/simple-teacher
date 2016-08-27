<div class="container easy">
<div class="toolbar left-side">
	<div class="button square" onclick="$('.header-menu').toggle(); $(this).toggleClass('button_default');"><br><b>≡</b><?=lang('Меню')?></div>
	<div class="button square" onclick="g.go({url: '/user/'})"><b>&larr;</b>К списку людей</div>
	<div class="button square" onclick="g.go({url: '/order/'})"><b>&laquo;</b>К списку заказов</div>
	<div class="button square" onclick="g.go({url: '/order/edit/0/?user_id=<?=$client['id']?>'})"><b>+</b><?=lang('Добавить заказ')?></div>
	
</div>
<div class="content">
<div class="widget">
<form id="user_form" method="post" enctype="multipart/form-data">
<input type="hidden" name="id" value="<?=$client['id']?>">

<table width="100%" class="board user">
	<tr>
		<td class="block header">
			<h1>
				<span class="blue"><span id="user_name"><?=($client['id'] == 0 ? 'Новый' : $client['name'].'</span> <span class="c-gray small">/ '.$client['address'].'</span>')?></span>
			</h1>
		</td>
		<td class="header" align="right">
						<div class="toolbar" style="text-align: right;">
							<div class="button button_default" onclick="users.save();"><?=(empty($client['id']) ? 'Создать' : 'Сохранить')?></div>
						</div>
		</td>
	</tr>
	<tr>
		<td class="block" colspan="2">
			<div class="field">
				<div>�?мя</div>
				<input name="name" value="<?=$client['name']?>" style="width: 250px;">
			</div>
			<div class="field">
				<div>Логин</div>
				<input name="login" value="<?=$client['login']?>" style="width: 150px;">
			</div>
			<div class="field">
				<div>E-mail</div>
				<input name="email" value="<?=$client['email']?>" style="width: 200px;">
			</div>
			<div class="field">
				<div>Комментарий</div>
				<input name="description" value="<?=$client['description']?>" style="width: 300px;">
			</div>
			<br>
			<div class="field">
				<div>Адрес</div>
				<input name="address" value="<?=$client['address']?>" style="width: 415px;">
			</div>
			<div class="field">
				<div>Доставка</div>
				<select name="data[delivery_id]" style="width: 200px;">
				<? foreach ($deliveries as $delivery_id => $d) { ?>
					<option value="<?=$delivery_id?>" <?=($client['data']['delivery_id'] == $delivery_id ? 'selected' : '')?>><?=$d['name']?> &mdash; <?=$d['price']?> руб.</option>
				<? } ?>
				</select>
			</div>
			<div class="field">
				<div>Телефоны</div>
				<span id="phones">
				<? if (empty($client['phones'])) {
					$client['phones'][0] = '';
				}
					foreach ($client['phones'] as $d => $phone) { ?>
					<div>
						<input type="text" name="phones[<?=(intval($d).'' == $d ? '' : $d)?>]" style="width: 200px;" placeholder="8 9XX XXX-XX-XX" value="<?=$this->user_model->format_phone($this->user_model->parse_phone($phone))?>">
						<div class="button button_delete" onclick="$(this).parent().remove()">X</div>
					</div>
				<? } ?>
				</span>
				<div id="new_phone" style="display: none">
					<input type="text" name="phones[]" style="width: 200px;" placeholder="+7 9XX XXX-XX-XX">
					<div class="button button_delete" onclick="$(this).parent().remove()">X</div>
				</div>
				<div class="button" onclick="if ($('#phones div:last-child select').val() != '') { $('#new_phone').clone().appendTo($('#phones')).show(); }">+</div>
			</div>
		</td>
	</tr>
	<tr>
		<td class="block">
			<h1><?=lang('Заказы')?> <span class="small c-gray">(<?=count($orders)?>)</span></h1>
			<div class="widget">
			<? $tt->load_template('order/list')?>
			</div>
		</td>
	</tr>
</table>
</form>
</div>
</div>
</div>
<script type="text/javascript">
users.id = <?=$client['id']?>;
var default_title = '<?=($client['is_group'] ? 'Новый проект' : 'Новая задача')?>';

$('input[name=title]').bind('change keyup', function() {
	if ($(this).val() != '') {
		$('#user_title').html($(this).val());
	} else {
		$('#user_title').html(default_title);
	}
});


</script>