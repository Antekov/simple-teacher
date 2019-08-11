<?php if( isset($users)){ ?>
	<ul class="login-users">
	<?php foreach( $users as $user ){
		$fio = explode(' ', $user['fio']);
		if( isset($fio[2])) unset( $fio[2] );
		$fio = implode(' ', $fio);
		$avatar = ( $user['uniqueid'] != '' )
			? 'http://back95.ru/f/p48x48/avatars/system_users/'.$user['uniqueid'].'.jpg'
			: '/i/d-t.gif';
	?>
		<li class="user-groups" onclick="auth.enter_pin(this, <?=$user['id']?>)">
			<img style="background-image:url(<?=$avatar?>);" src="/i/d-t.gif" class="avatar" />
			<?=$fio?>
		</li>
	<?php } ?>
	</ul>
<?php } ?>