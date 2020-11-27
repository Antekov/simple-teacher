<ul class="login-groups">
<?php foreach( $groups as $group ){ ?>
	<li class="user-groups" onclick="auth.show_users(this, <?=$group['id']?>)"><?=$group['title']?></li>
<?php } ?>
</ul>
