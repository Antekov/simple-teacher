<?php
if ( ! function_exists('timestamp_to_human'))
{
	/**
	 * Unix to "Human"
	 *
	 * Formats Unix timestamp to the following prototype: 2006-08-21 11:35 PM
	 *
	 * @param	int	Unix timestamp
	 * @param	bool	whether to show seconds
	 * @param	string	format: us or euro
	 * @return	string
	 */
	class date_singleton {

		protected static $instance;
		var $MONTH_RUS;
		var $WEEK_RUS;
		var $DATE_IDS;

		function __construct() {
			$this->MONTH_RUS['I'] = array(0 => '', 1 => 'январь', 2 => 'февраль', 3 => 'март', 4 => 'апрель', 5 => 'май', 6 => 'июнь', 7 => 'июль', 8 => 'август', 9 => 'сентябрь', 10 => 'октябрь', 11 => 'ноябрь', 12 => 'декабрь');
			$this->MONTH_RUS['V'] = array(0 => '', 1 => 'января', 2 => 'февраля', 3 => 'марта', 4 => 'апреля', 5 => 'мая', 6 => 'июня', 7 => 'июля', 8 => 'августа', 9 => 'сентября', 10 => 'октября', 11 => 'ноября', 12 => 'декабря');
			$this->MONTH_RUS['short'] = array(0 => '', 1 => 'янв', 2 => 'фев', 3 => 'мар', 4 => 'апр', 5 => 'май', 6 => 'июн', 7 => 'июл', 8 => 'авг', 9 => 'сен', 10 => 'окт', 11 => 'ноя', 12 => 'дек');
			$this->WEEK_RUS['long'] = array(1 => 'Понедельник', 2 => 'Вторник', 3 => 'Среда', 4 => 'Четверг', 5 => 'Пятница', 6 => 'Суббота', 7 => 'Воскресенье', 0 => 'Воскресенье');
			$this->WEEK_RUS['short'] = array(1 => 'Пн', 2 => 'Вт', 3 => 'Ср', 4 => 'Чт', 5 => 'Пт', 6 => 'Сб', 7 => 'Вс', 0 => 'Вс');
			$this->DATE_IDS = array(1 => array('позавчера', date("Y-m-d H:i:s", mktime(0, 0, 0, date('m'), date('d') - 2, date('Y')))),
				2 => array('вчера', date("Y-m-d", mktime(0, 0, 0, date('m'), date('d') - 1, date('Y')))),
				3 => array('сегодня', date("Y-m-d", mktime(0, 0, 0, date('m'), date('d'), date('Y')))),
				4 => array('завтра', date("Y-m-d", mktime(0, 0, 0, date('m'), date('d') + 1, date('Y')))),
				5 => array('послезавтра', date("Y-m-d", mktime(0, 0, 0, date('m'), date('d') + 2, date('Y')))),
				6 => array('', date("Y-m-d", mktime(0, 0, 0, date('m'), date('d') + 3, date('Y'))))
			);
		}

		function timestamp_to_human($datetime, $format = 'human') {
			if ($datetime == '') { return ''; }
			$stop_words = array(' ', '.', ',', '/', ':', '-', 'c', 'в');
			$word = [];
			$delimiter = [];
			$dt = explode(' ', $datetime);
			$date_fmt = '';
			if( strpos($dt[0], '-') != FALSE ){
				list($Y, $m, $d) = explode('-', $dt[0]);
			}else{
				list($d, $m, $Y) = explode('.', $dt[0]);
				
			}
			if (isset($dt[1])) {
				$_time = explode(':', $dt[1]);
				$H = ( isset($_time[0])) ? $_time[0] : 0;
				$i = ( isset($_time[1])) ? $_time[1] : 0;
				$s = ( isset($_time[2])) ? $_time[2] : 0;
			} else {
				$H = 0;
				$i = 0;
				$s = 0;
			}
			$week_day = date('w', mktime($H, $i, $s, $m, $d, $Y));
			// Готовим формат
			//preg_match_all("/([a-zA-Z0-9.,\/\s]{1,30})/", $format, $fmt, PREG_PATTERN_ORDER);
			$fmt = str_split($format);
			
			$c = 0;
			foreach ($fmt as $l) {
				if (in_array($l, $stop_words)) {
					$c++;
					$delimiter[$c] = $l;
				} else {
					if (isset($word[$c])) {
						$word[$c] .= $l;
					} else {
						$word[$c] = $l;
					}
				}
			}
			
			for ($n = 0; $n <= $c; $n++) {
				if (isset($delimiter[$n])) {
					$date_fmt .= $delimiter[$n];
				}
				
				if (isset($word[$n])) {
					switch($word[$n]) {
						case 'w' :
							$date_fmt .= ($this->WEEK_RUS['short'][$week_day]);
							break;
						case 'W' :
							$date_fmt .= mb_strtoupper(($this->WEEK_RUS['short'][$week_day]));
							break;
						case 'week' :
							$date_fmt .= ($this->WEEK_RUS['long'][$week_day]);
							break;
						case 'm' :
							$date_fmt .= $m;
							break;
						case 'd' :
							$date_fmt .= $d;
							break;
						case '%Y' :
							if ($Y != date('Y')) { $date_fmt .= $Y;
							} else { $date_fmt = substr($date_fmt, 0, (strlen($date_fmt) - 1));
							}
							break;
						case 'Y' :
							$date_fmt .= $Y;
							break;
						case 'y' :
							$date_fmt .= substr($Y, 2, 2);
							break;
						case 'month' :
							$date_fmt .= ($this->MONTH_RUS['I'][intval($m)]);
							break;
						case 'months' :
							$date_fmt .= ($this->MONTH_RUS['V'][intval($m)]);
							break;
						case 'mnth' :
							$date_fmt .= ($this->MONTH_RUS['short'][intval($m)]);
							break;
						case 'H' :
							$date_fmt .= $H;
							break;
						case 'i' :
							$date_fmt .= $i;
							break;
						case 's' :
							$date_fmt .= $s;
							break;
						case 'day':
							$date_id = 0;
							foreach ($this->DATE_IDS as $id => $date) {
								if ($dt[0] == $date[1]) {
									$date_fmt = $date[0];
									break;
								}
							}
							if( $date_fmt == '') $date_fmt = $this->unix_to_human($datetime, 'd months');
							break;
						case 'human' :
							// Подставляем человечные даты
							$date_id = 0;
							foreach ($this->DATE_IDS as $id => $date) {
								if ($datetime == $date[1]) {
									$date_fmt = $date[0];
								}
							}
							$format = ($Y == date('Y')) ? 'week, d months' : 'week, d months Y';
							$date_fmt .= ($date_fmt != '') ? ', ' : '';
							$date_fmt .= $this->timestamp_to_human($datetime, $format);
							break;
					}
				}

			}
			return $date_fmt;
		}

		function get_date_id($dt) {
			$date_id = 0;

			foreach ($this->DATE_IDS as $id => $date) {
				if ($dt > $date[1]) {
					$date_id = $id;
				}
			}
			return $date_id;
		}

		public static function getInstance() {
			if (is_null(self::$instance)) {
				self::$instance = new date_singleton();
			}
			return self::$instance;
		}

	}

	function timestamp_to_human($datetime, $format = 'human') {
		if ($datetime == '') {
			return '';
		};
		return date_singleton::getInstance()->timestamp_to_human($datetime, $format);
	}
	function datetime_2_arr($date_time) {
		if( is_array($date_time) ) return $date_time;
		$d = '';
		if (strlen($date_time) > 10)
			list($date, $time) = explode(' ', $date_time);
		else
			$date = $date_time;
		list($d['Y'], $d['m'], $d['d']) = explode('-', $date);
		if (isset($time))
			list($d['H'], $d['i'], $d['s']) = explode(':', $time);

		return $d;
	}
}