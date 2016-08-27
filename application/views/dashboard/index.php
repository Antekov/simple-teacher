<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('header', $this->stash);
$this->load->view('left_menu', $this->stash);
?>
<div class="container">
	<div class="content j-content">
		<? $this->load->view('top_header', $this->stash); ?>

		<!-- Main component for a primary marketing message or call to action -->
		<div class="jumbotron">
			<h1>DASHBOARD Navbar example</h1>

			<p>This example is a quick exercise to illustrate how the default, static and fixed to top navbar work. It
				includes the responsive CSS and HTML, so it also adapts to your viewport and device.</p>

			<p>To see the difference between static and fixed top navbars, just scroll.</p>

			<p>
				<a class="btn btn-lg btn-primary" href="#" role="button">View navbar docs »</a>
			</p>
		</div>

	</div>
</div>
<?
global $ALPHABET;

function mix_key($KEY)
{
	$key = "";
	$KEY_SIZE = mb_strlen($KEY);

	for ($i=0; $i<$KEY_SIZE; $i++)
	{
		$index = rand(0, mb_strlen($KEY)-1);
		$s = mb_substr($KEY, $index, 1);

		$key .= $s;
		$KEY = preg_replace("/$s/", "" , $KEY);

	}
	return $key;
}

function shift_key($KEY, $shift)
{
	$key = mb_substr($KEY, mb_strlen($KEY) - $shift, $shift).mb_substr($KEY, 0, mb_strlen($KEY) - $shift);
	return $key;
}

function lab_encrypt($plain_text, $key)
{
	global $ALPHABET;
	$chipher_text = "";
	for ($i=0; $i<mb_strlen($plain_text); $i++)
	{
		$s = mb_substr($plain_text, $i, 1);
		$chipher_text .= (mb_strpos($ALPHABET, $s) !== false) ? mb_substr($key, mb_strpos($ALPHABET, $s), 1) : $s;
	}
	return $chipher_text;
}

function lab_decrypt($chipher_text, $key)
{
	global $ALPHABET;
	$plain_text = "";
	for ($i=0; $i<mb_strlen($chipher_text); $i++)
	{
		$plain_text .= mb_strpos($key, $chipher_text[$i]) ? $ALPHABET[mb_strpos($key, $chipher_text[$i])] : $chipher_text[$i];
	}
	return $plain_text;
}

function lab_explode($text, $len=40)
{
	$out_text = "";
	for ($n=0; $n<mb_strlen($text); $n++)
	{
		$out_text .= $text[$n];
		if (($n+1) % $len == 0 ) $out_text .= "\n";
	}
	return $out_text;
}


$plain_text = (!empty($_REQUEST['plain_text']) ? $_REQUEST['plain_text'] : '');
$shift = (!empty($_REQUEST['shift']) ? $_REQUEST['shift'] : '');
?>

<?
if (empty($plain_text))
{

	?>
	<center><form method=post>
	<br>
	<textarea name="plain_text" placeholder="введите открытый текст" cols="80" rows="12"></textarea>
	<br>
	<br><br>
	<input type="text" name="shift" placeholder="сдвиг">
	<br><br><br>
	<input type=submit value=Ok>
	</form></center>
	<?
} else {
	$ALPHABET = "АБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЪЫЬЭЮЯ";

	$key	  = $ALPHABET;
	$chipher_text = '';

	if (!empty($shift)) {
		$key = shift_key($key, $shift);
	} else {
		$key = mix_key($key);
	}

	$source = $plain_text;
	$plain_text = '';
	$len = mb_strlen($source);
	$i = 0;
	while ($i < $len)
	{
		$ch = mb_strtoupper(mb_substr($source, $i, 1));
		if (mb_strlen($ch))
		{
			//if ($ch == "?") $ch = "?";
			//if ($ch == "?") $ch = "?";

			if (mb_strpos($ALPHABET, $ch) !== false)
			{
				$plain_text .= $ch;
			} else {
				$plain_text .= $ch;
			}
		}
		$i++;
	}

	srand((double)microtime()*1000000);

	?>
		<table width=100% border=0 cellpadding=0 cellspacing=0>
			<tr><td>#</td><td>Ключ</td><td>Открытый текст</td><td>Шифртекст</td></tr>
			<?
			$COL = 1;
			for($i=0; $i<$COL; $i++)
			{

				echo "<tr><td td valign=top>".($i+1)."</td><td valign=top style='font-family: courier new;'>$ALPHABET<br>$key</td>";
				$chipher_text = lab_encrypt($plain_text, $key);
				echo "<td style='font-family: courier new;'>";
				echo nl2br($plain_text);
				echo "</td>";
				echo "<td style='font-family: courier new;'>";
				echo nl2br($chipher_text);
				echo "</td></tr>";
			}
			?>
	</table><?


}
?>


<?
$this->load->view('footer');
?>
