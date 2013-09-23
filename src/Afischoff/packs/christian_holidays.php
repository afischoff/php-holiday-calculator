<?php
class Christian_holidays {
	public function loadholidays() {
		$year = date('Y');
		if (easter_date($year) < time()) {
			$year++;
		}
		return array('CHRISTIAN_EASTER' => '@'.easter_date($year));
	}
}
