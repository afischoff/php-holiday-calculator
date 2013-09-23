<?php
class Us_holidays {
	// in format: CONST => strtotime() format
	private $us_holidays = array(
		'US_NEW_YEARS' => 'january 1',
		'US_MLK' => 'third monday of january',
		'US_GROUNDHOG' => 'february 2',
		'US_VALENTINES' => 'february 14',
		'US_PRESIDENTS' => 'third monday of february',
		'US_EARTH' => 'april 22',
		'US_ARBOR' => 'last friday of april',
		'US_MOTHERS' => 'second sunday of may',
		'US_MEMORIAL' => 'last monday of may',
		'US_FLAG' => 'june 14',
		'US_FATHERS' => 'third sunday of june',
		'US_INDEPENDENCE' => 'july 4',
		'US_LABOR' => 'first monday of september',
		'US_PATRIOT' => 'september 11',
		'US_COLUMBUS' => 'second monday of october',
		'US_SWEETEST' => 'third saturday of october',
		'US_HALLOWEEN' => 'october 31',
		'US_VETERANS' => 'november 11',
		'US_THANKSGIVING' => 'fourth thursday of november',
		'US_PEARL_HARBOR' => 'december 7',
		'US_CHRISTMAS' => 'december 25'
	);

	public function loadHolidays() {
		return $this->us_holidays;
	}
}