<?php
class Jewish_holidays {
	// holidays stored as CONST => array(month, day)
	private $holidays = array(
		'JEWISH_ROSH_HASHANA' => array(1,1),
		'JEWISH_YOM_KIPPUR' => array(1,10),
		'JEWISH_SUKKOT' => array(1,15),
		'JEWISH_SIMCHAT_TORAH' => array(1,23),
		'JEWISH_CHANUKKAH' => array(3,24),
		'JEWISH_TU_BISHVAT' => array(5,15),
		'JEWISH_PURIM' => array(7,14),
		'JEWISH_PESACH' => array(8,15),
		'JEWISH_LAG_BA_OMER' => array(9,18),
		'JEWISH_SHAVUOT' => array(10,6),
	);

	public function loadHolidays() {
		$jdDate = cal_to_jd(CAL_GREGORIAN, date('m'), date('d'), date('Y'));
		$hebrewDate = jdtojewish($jdDate);
		list($hebrewMonth, $hebrewDay, $hebrewYear) = explode('/',$hebrewDate);

		$holidays = array();
		$currYear = $hebrewYear - 1; // subtract a year for some reason
		$nextYear = $currYear + 1;

		foreach ($this->holidays as $key => $val) {
			$julianDate = jewishtojd($val[0], $val[1], $currYear);
			if (jdtounix($julianDate) < time()) {
				$julianDate = jewishtojd($val[0], $val[1], $nextYear);
				if (jdtounix($julianDate) < time()) {
					$julianDate = jewishtojd($val[0], $val[1], $nextYear+1);
				}
			}
			$unixTime = jdtounix($julianDate);
			$unixTime += 86400; // add 1 day for some reason
			$holidays[$key] = '@'.$unixTime;
		}

		return $holidays;
	}
}
