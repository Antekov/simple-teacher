<?

function mix_key($KEY)
{
	$key = "";
	$KEY_SIZE = mb_strlen($KEY);
	print_r("Rk.x: ".$KEY_SIZE);
	
	for ($i=0; $i<$KEY_SIZE; $i+=2)
	{
		$index = rand(0, mb_strlen($KEY)-1);
		$key .= $KEY[$index];

		$KEY = str_replace($KEY[$index], "" , $KEY);

	}
	return $key;
}

function shift_key($KEY, $shift)
{
	$key = mb_strstr($KEY, mb_strlen($KEY) - $shift - 1, $shift).mb_strstr($KEY, 0, mb_strlen($KEY) - $shift - 2);
	return $key;
}

function lab_encrypt($plain_text, $key)
{
	global $ALPHABET;
	$chipher_text = "";
	for ($i=0; $i<mb_strlen($plain_text); $i++)
	{
		$chipher_text .= mb_strpos($ALPHABET, $plain_text[$i]) ? $key[mb_strpos($ALPHABET, $plain_text[$i])] : $plain_text[$i];
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

$filename = (!empty($_REQUEST['filename']) ? $_REQUEST['filename'] : '');
$plain_text = (!empty($_REQUEST['plain_text']) ? $_REQUEST['plain_text'] : '');
$shift = (!empty($_REQUEST['shift']) ? $_REQUEST['shift'] : '');
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	</head>
<body>
<?
if (empty($filename) && empty($plain_text))
{
	echo "<br><br><center><form method=post><input type=file name=filename>&nbsp";
	?>
	или
	<br>
	<textarea name="plain_text" placeholder="введите открытый текст" cols="80" rows="12"></textarea>
	<br>
	<br><br>
	<input type="text" name="shift" placeholder="сдвиг">
	<br><br><br>
	<input type=submit value=Ok>
	</form></center>
	<?
} else if (!empty($filename)) {
	echo "<small>$filename</small><br><hr><br><br>";

	$ALPHABET = "АБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЪЫЬЭЮЯ";

	$key	  = $ALPHABET;
	$plain_text = "";
	$freq = array();
	$count = 0;

	for ($i=0; $i<mb_strlen($ALPHABET); $i++)
	{
		$freq[$ALPHABET[$i]] = 0;
	}

	$f= fopen($filename, "r") or die("?????? ???????? ????? $filename");
	while (!feof($f))
	{
		$ch=strtoupper(fgetc($f));
		if (strlen($ch))
		{
			//if ($ch == "?") $ch = "?";
			//if ($ch == "?") $ch = "?";

			if (strchr($ALPHABET, $ch))
			{
				$plain_text .= $ch;
				$freq[$ch]++;
				$count++;
			} else {
			    $plain_text .= $ch;    
			}
		}
	}
	fclose($f);

	echo lab_explode($plain_text, 80);
	srand((double)microtime()*1000000);

?>
<table width=100% border=0 cellpadding=0 cellspacing=0>
<tr><td>#</td><td>Ключ</td><td>Шифртекст</td></tr>
<?
    $COL = 1;
	for($i=0; $i<$COL; $i++)
	{
		$key = mix_key($key);
		echo "<tr><td td valign=top>".($i+1)."</td><td valign=top style='font-family: courier new;'>$ALPHABET<br>$key</td>";
		$chipher_text = lab_encrypt(substr($plain_text, (strlen($plain_text)/$COL)*$i, (strlen($plain_text)/$COL)), $key);
		echo "<td style='font-family: courier new;'>";
		echo lab_explode($chipher_text,60);
		echo "</td></tr>";
	}

?>
</table>
<h1>Таблица частот</h1>
<h2>Количество символов: <? echo $count;?></h2>
<table width=30% border=0 cellpadding=0 cellspacing=0>
<tr><td>Символ</td><td>Количество</td><td>Процент</td></tr>
<?
	arsort($freq);
	foreach ($freq as $key => $value)
	{
		echo "<tr><td td valign=top>".$key."</td><td valign=top>".$value."</td><td><b>";
		printf("%01.3f", ((float)$value)/$count);
		echo "</b></td></tr>";
	}
?>
	</table>
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
	echo "er htfioerhfo";
	print_r($key);
	$source = $plain_text;
	$plain_text = '';
	$len = strlen($source);
	$i = 0;
	while ($i < $len)
	{
		$ch=strtoupper($source[$i]);
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

	echo nl2br($plain_text);
	srand((double)microtime()*1000000);

	?>
		<table width=100% border=0 cellpadding=0 cellspacing=0>
			<tr><td>#</td><td>Ключ</td><td>Шифртекст</td></tr>
			<?
			$COL = 1;
			for($i=0; $i<$COL; $i++)
			{

				echo "<tr><td td valign=top>".($i+1)."</td><td valign=top style='font-family: courier new;'>$ALPHABET<br>$key</td>";
				$chipher_text = lab_encrypt(substr($plain_text, (strlen($plain_text)/$COL)*$i, (strlen($plain_text)/$COL)), $key);
				echo "<td style='font-family: courier new;'>";
				echo lab_explode($chipher_text,60);
				echo "</td></tr>";
			}
			?>
	</table><?


}
?>
</body>
</html>
