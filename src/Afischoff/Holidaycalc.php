<?php namespace Afischoff;

class Holidaycalc
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
            if (is_callable($timestr)) {
                $hol_date = $timestr(date('Y'));
            } else {
                $hol_date = $this->isTimestamp($timestr) ? substr($timestr,1) : strtotime($timestr);
            }

			if ($upcoming && !$this->isTimestamp($timestr) && $hol_date < time()) {
				$nextYear = date('Y') + 1;
                if (is_callable($timestr)) {
                    $hol_date = $timestr($nextYear);
                } else {
                    $hol_date = strtotime($timestr.' '.$nextYear);
                }
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

    private function isFuture($holiday)
    {
        
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
            if (is_callable($timestr)) {
                $hol_date = $timestr(date('Y'));
            } else {
                $hol_date = $this->isTimestamp($timestr) ? substr($timestr,1) : strtotime($timestr);
            }

			if ($upcoming && !$this->isTimestamp($timestr) && $hol_date < time()) {
				$nextYear = date('Y') + 1;
                if (is_callable($timestr)) {
                    $hol_date = $timestr($nextYear);
                } else {
                    $hol_date = strtotime($timestr.' '.$nextYear);
                }
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

    /**
     * Checks whether the calendar string is a timestamp (in which case it will begin with an @ sign).
     * @param string $timestr The calendar string.
     * @return bool
     */
    private function isTimestamp($timestr)
    {
        return is_string($timestr) && preg_match('/^@/', $timestr);
    }
}
