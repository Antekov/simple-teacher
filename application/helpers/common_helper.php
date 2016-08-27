<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('array_to_assoc'))
{
	function array_to_assoc($array, $field){
		$result = array();
		if( is_array($field) ){
			foreach($array as $v){
				// Формируем ключ
				$key = '';
				foreach($field as $f){
					if( isset( $v[$f] ) ){
						$key[] = $v[$f];
					}
				}
				if( is_array($key) ) $key = implode('.', $key);
				$result[$key] = $v;
			}
		}else{
			foreach($array as $v){
				if( isset( $v[$field] ) ){
					$result[$v[$field]] = $v;
				}
			}
		}
		return $result;
	}

	function compare_assoc_arrays( $array1, $array2 ){
		$res1 = array_diff_assoc($array1, $array2);
		$res2 = array_diff_assoc($array2, $array1);
		if( count($res1) > 0 or count($res2) > 0){
			return false;
		}
		return true;
	}

	function arrayRecursiveDiff($aArray1, $aArray2) {
		$aReturn = array();

		foreach ($aArray1 as $mKey => $mValue) {
			if (array_key_exists($mKey, $aArray2)) {
				if (is_array($mValue)) {
					$aRecursiveDiff = arrayRecursiveDiff($mValue, $aArray2[$mKey]);
					if (count($aRecursiveDiff)) { $aReturn[$mKey] = $aRecursiveDiff; }
				} else {
					if ($mValue != $aArray2[$mKey]) {
						$aReturn[$mKey] = $mValue;
					}
				}
			} else {
				$aReturn[$mKey] = $mValue;
			}
		}

		return $aReturn;
	}
	function arrayRecursiveOccurrence($aArray1, $aArray2) {
		foreach ($aArray1 as $mKey => $mValue) {
			if (array_key_exists($mKey, $aArray2)) {
				if (is_array($mValue)) {
					$aRecursiveDiff = arrayRecursiveOccurrence($mValue, $aArray2[$mKey]);
					if (!$aRecursiveDiff) {
						return false;
					}
				} else {
					if ($mValue != $aArray2[$mKey]) {
						return false;
					}
				}
			} else {
				return false;
			}
		}

		return true;
	}

	function random_key($salt = ''){
		$sessid = '';
		while (strlen($sessid) < 32){
			$sessid .= mt_rand(0, mt_getrandmax());
		}
		return md5(dechex(time()).$sessid.$salt);
	}
	function is_assoc($array) {
		return (is_array($array) && (count($array)==0 || 0 !== count(array_diff_key($array, array_keys(array_keys($array))) )));
	}

	function get_status( $key, $type='order' ){
		$ci =& get_instance();

		if( !isset($_system_statuses)){
			$_statuses = $ci->db->from('system_statuses')->get()->result_array();
			foreach( $_statuses as $status){
				$_system_statuses[$status['type']][$status['key']] = $status['value'];
			}
		}
		return ( isset($_system_statuses[$type][$key]) ) ? $_system_statuses[$type][$key] : '';
	}
	function get_system_user( $user_id ){
		$ci =& get_instance();

		$user = $ci->db->from( T_SYSTEM_USERS )->where('id', $user_id)->get()->row_array();
		if( isset($user['data'])){
			$user['data'] = json_decode( $user['data'], true );
		}
		return $user;
	}
}
if ( ! function_exists('get_system'))
{
	function get_system($key=null) {
		$ci =& get_instance();

		if( !is_null($key)){
			$ci->db->where('key', $key);
		}

		$q = $ci->db->from(T_SYSTEM)->get()->result_array();

		if( !empty($q) ){
			if( !is_null($key)){
				$value = $q[0];
				return $value['value'];
			}
			return $q;
		}
		return null;
	}
}
if ( ! function_exists('set_system'))
{
	function set_system($key, $value) {
		$ci =& get_instance();

		$isset = get_system($key);

		if( is_null($isset)){
			$data = array(
				'key' => $key,
				'value' => $value
			);
			$ci->db->insert(T_SYSTEM, $data);
		}else{
			$ci->db->where('key', $key);
			$ci->db->update(T_SYSTEM, array('value'=>$value));
		}

		return get_system($key);
	}
}


if ( ! function_exists('json_encode_fixed'))
{
	function json_encode_fixed($mixed) {
		if (is_null($mixed)) {
			return "";
		}
		if ( ! function_exists('_fix_utf')){
			function _fix_utf(&$item,$key){
				$regex = '/((?:[\x00-\x7F]|[\xC0-\xDF][\x80-\xBF]|[\xE0-\xEF][\x80-\xBF]{2}|[\xF0-\xF7][\x80-\xBF]{3}))|./x';
				$item = preg_replace($regex,'$1',$item);
			}
		}
		array_walk_recursive($mixed,'_fix_utf');
		return json_encode($mixed);
	}
}

if (!function_exists('word_ending')) {
	function word_ending($number, $word){
		$letters = array('р'=>2, 'ь'=>3, 'я'=>1, 'з'=>4, 'л'=>5, 'н'=>'6', 'е'=>'7', 'у'=>'8', 'о'=>'9');
		$group	= array(
			1 => array('я', 'и', 'й'),
			2 => array('р', 'ра', 'ров'),
			3 => array('ь', 'и', 'ей'),
			4 => array('з', 'за', 'зов'),
			5 => array('л', 'ла', 'лов'),
			6 => array('н', 'но', 'но'),
			7 => array('е', 'я', 'й'),
			8 => array('у', 'и', ''),
			9 => array('о', 'а', ''),
		);

		$ending = mb_substr($word, -1, 1);

		$letter_group = ( isset($letters[$ending]) ) ? $letters[$ending] : 0;
		$group_id = ( isset($group[$letter_group]) ) ? $group[$letter_group] : 0;

		if ($group_id != 0) {

			$word = mb_substr($word, 0, (mb_strlen($word) -1) );

			$two_last_digits = substr($number, -2, 2);

			if ($two_last_digits > 10 and $two_last_digits < 20) {
				$ending = $group_id[2];
			} else {

				$one_last_digit = substr($number, -1, 1);

				if( $one_last_digit == 1) {
					$ending = $group_id[0];
				} else if ($one_last_digit > 1 and $one_last_digit < 5) {
					$ending = $group_id[1];
				} else {
					$ending = $group_id[2];
				}
			}

			$word .= $ending;
		}
		return $word;
	}
}
if (!function_exists('url_exists')) {
	function url_exists($url) {
		$handle = curl_init($url);
		if (false === $handle) {
			return false;
		}

		curl_setopt($handle, CURLOPT_HEADER, false);
		curl_setopt($handle, CURLOPT_FAILONERROR, true);  // this works
		curl_setopt($handle, CURLOPT_NOBODY, true);
		curl_setopt($handle, CURLOPT_RETURNTRANSFER, false);
		$connectable = curl_exec($handle);
		curl_close($handle);
		return $connectable;
	}
}

/**
 * транслитерация
 */
if ( ! function_exists('translit')){

	function translit($str)
	{
		$converter = array(
			'а' => 'a',   'б' => 'b',   'в' => 'v',
			'г' => 'g',   'д' => 'd',   'е' => 'e',
			'ё' => 'e',   'ж' => 'zh',  'з' => 'z',
			'и' => 'i',   'й' => 'y',   'к' => 'k',
			'л' => 'l',   'м' => 'm',   'н' => 'n',
			'о' => 'o',   'п' => 'p',   'р' => 'r',
			'с' => 's',   'т' => 't',   'у' => 'u',
			'ф' => 'f',   'х' => 'h',   'ц' => 'c',
			'ч' => 'ch',  'ш' => 'sh',  'щ' => 'sch',
			'ь' => '',  'ы' => 'y',   'ъ' => '',
			'э' => 'e',   'ю' => 'yu',  'я' => 'ya',

			'А' => 'A',   'Б' => 'B',   'В' => 'V',
			'Г' => 'G',   'Д' => 'D',   'Е' => 'E',
			'Ё' => 'E',   'Ж' => 'Zh',  'З' => 'Z',
			'И' => 'I',   'Й' => 'Y',   'К' => 'K',
			'Л' => 'L',   'М' => 'M',   'Н' => 'N',
			'О' => 'O',   'П' => 'P',   'Р' => 'R',
			'С' => 'S',   'Т' => 'T',   'У' => 'U',
			'Ф' => 'F',   'Х' => 'H',   'Ц' => 'C',
			'Ч' => 'Ch',  'Ш' => 'Sh',  'Щ' => 'Sch',
			'Ь' => '',  'Ы' => 'Y',   'Ъ' => '',
			'Э' => 'E',   'Ю' => 'Yu',  'Я' => 'Ya',
		);
		$str = strtr($str, $converter);
		$str = strtolower($str);
		$str = preg_replace('~[^-a-z0-9_]+~u', '-', $str);
		$str = trim($str, "-");
		return $str;
	}
}

/**
 * db->result_array() as tree array
 *
 * for example:
 * - execute this code
 * ----------------------------------------------------
 * 		echo '-------- RAW ARRAY -----------<br>';
 * 		echo printR($this->db->get(T_HELP)->result_array());
 * 		echo '------------------------------<br>';
 * 		echo '-------- TREE ARRAY -----------<br>';
 * 		echo printR(db_array_by_tree($this->db->get(T_HELP)->result_array(),'document_id','parent_id'));
 * 		die('------------------------------');
 * ----------------------------------------------------
 */
if ( ! function_exists('db_array_by_tree'))
{
	function db_array_by_tree($array, $current_key, $parent_key)
	{

		$result = array();
		$handle = array();

		foreach($array as $key => $el)
		{
			$c_key = (int)$el[$current_key];
			$p_key = (int)$el[$parent_key];

			if ($p_key == 0)
			{
				$result[] = array_merge($el, array('level' => 0, 'child' => array()));
				$handle[$c_key] = &$result[count($result)-1];
			}
			else
			{
				if( !isset($handle[$p_key])) $handle[$p_key] = array('child'=>array(), 'level'=>0);
				$handle[$p_key]['child'][] = array_merge($el, array('level' => $handle[$p_key]['level']+1, 'child' => array()));
				$handle[$c_key] = &$handle[$p_key]['child'][count($handle[$p_key]['child'])-1];
			}
		}

		return $result;
	}
}

if( ! function_exists('make_tree_from_array')){
	function make_tree_from_array( $array, $parent_id=0, $selected_key=0, $current_key='id', $parent_key='parent_id', $level=0 ){
		$level++;
		$tree = array();
		if( is_array($array)){
			foreach( $array as $key=>$el ){
				if( (int)$el[$parent_key] == $parent_id ){
					$tree[$el[$current_key]] = $el;
					if( $selected_key == $el[$current_key] ){
						$tree['isset_selected'] = 1;
						$tree[$el[$current_key]]['selected'] = 1;
					}
					$childs = make_tree_from_array( $array, $el[$current_key], $selected_key, $current_key, $parent_key, $level );
					if( isset( $childs['isset_selected']) ){
						$tree['isset_selected'] = 1;
						$tree[$el[$current_key]]['selected'] = 1;
						unset( $childs['isset_selected'] );
					}
					if( count($childs) > 0 ){
						$tree[$el[$current_key]]['childs'] = $childs;
					}
				}
			}
		}
		if( $level == 1 ){
			unset( $tree['isset_selected'] );
		}
		return $tree;
	}
}

// Склонение существительных после числительных
if ( ! function_exists('number_of'))
{
	function number_of($number_of, $value = "", $suffix = array()){
		// не будем склонять отрицательные числа
		$number_of = abs($number_of);
		$keys = array(2, 0, 1, 1, 1, 2);
		$mod = $number_of % 100;
		$suffix_key = $mod > 4 && $mod < 20 ? 2 : $keys[min($mod%10, 5)];
		return $value . $suffix[$suffix_key];
	}
}
