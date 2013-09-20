<?php namespace afischoff;

class HolidayCalc
{
	private $holiday_names = array();
	private $selected_holidays = array();

	/**
	 * @param string|array $holidays
	 * @param string $lang
	 */
	public function __construct($holidays = 'us_holidays', $lang = 'en_us') {

		if (!empty($holidays) && !is_array($holidays)) {
			$holidays = array($holidays);
		}

		require 'lang/'.$lang.'.php';
		$this->holiday_names = $holiday_names;

		foreach ($holidays as $pack) {
			require_once('packs/'.$pack.'.php');
			$currPack = new $pack;
			$this->selected_holidays = array_merge($this->selected_holidays, $currPack->loadHolidays());
		}
	}

	/**
	 * @param bool $upcoming
	 * @param bool $timeStampKeys
	 * @param bool $showDate
	 * @param string $dateFormat
	 * @return array
	 */
	public function getAllHolidaysByAlphabetic($upcoming = false, $timeStampKeys = false, $showDate = false, $dateFormat = 'm/d/y') {
		$holidays = array();
		foreach ($this->selected_holidays as $hol => $timestr) {
			$hol_date = strpos($timestr, '@') === false ? strtotime($timestr) : substr($timestr,1);

			if ($upcoming && strpos($timestr, '@') === false && $hol_date < time()) {
				$nextYear = date('Y') + 1;
				$hol_date = strtotime($timestr.' '.$nextYear);
			}
			$key = (!$timeStampKeys ? $hol : $hol_date);
			$holidays[$key] = $this->holiday_names[$hol];

			if (!$timeStampKeys && $showDate) {
				$holidays[$hol] .= ' ('.date($dateFormat, $hol_date).')';
			}
		}
		asort($holidays);
		return $holidays;
	}

	/**
	 * @param bool $upcoming
	 * @param bool $timeStampKeys
	 * @param bool $showDate
	 * @param string $dateFormat
	 * @return array
	 */
	public function getAllHolidaysByCalendar($upcoming = false, $timeStampKeys = false, $showDate = false, $dateFormat = 'm/d/y') {
		$holidays = array();
		foreach ($this->selected_holidays as $hol => $timestr) {
			$hol_date = strpos($timestr, '@') === false ? strtotime($timestr) : substr($timestr,1);

			if ($upcoming && strpos($timestr, '@') === false && $hol_date < time()) {
				$nextYear = date('Y') + 1;
				$hol_date = strtotime($timestr.' '.$nextYear);
			}

			$holidays[ $hol_date ] = $hol;
		}
		ksort($holidays);

		if ($timeStampKeys) {
			return $holidays;
		}

		// rebuild array with constants and names
		$holidays = array_flip($holidays);
		foreach ($holidays as $key => $val) {
			$holidays[$key] = $this->holiday_names[$key];

			if ($showDate) {
				$holidays[$key] .= ' ('.date($dateFormat, $val).')';
			}

			// if we're only showing upcoming holidays, remove holidays from the past
			if ($upcoming && $val < time()) {
				unset($holidays[$key]);
			}
		}

		return $holidays;
	}
}
