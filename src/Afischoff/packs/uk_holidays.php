<?php
class Uk_holidays {
    const TWENTYFOURHOURS = 86400;
    const SUNDAY = 0;
    const SATURDAY = 6;
    const MONDAY = 1;

    public function loadHolidays()
    {
        return [
            'UK_NEW_YEARS' => function($year) {
                return $this->weekendFix("january 1 {$year}");
            },
            'UK_GOOD_FRIDAY' => function($year) {
                return easter_date($year) - (self::TWENTYFOURHOURS*2);
            },
            'UK_EASTER_MONDAY' => function($year) {
                return easter_date($year) + self::TWENTYFOURHOURS;
            },
            'UK_EARLY_MAY' => 'first monday of may',
            'UK_SPRING' => 'last monday of may',
            'UK_SUMMER' => 'last monday of august',
            'UK_CHRISTMAS' => function($year) {
                return $this->weekendFix("december 25 {$year}");
            },
            'UK_BOXING_DAY' => function($year) {
                return $this->weekendFix("december 26 {$year}", true);
            },
        ];
    }

    /**
     * @param string $holiday The full date string for the holiday
     * @param bool $isAdjacent set true if the holiday is immediately after another (ie Boxing day follows Christmas day)
     * @return int the unix timestamp for the given holiday.
     */
    private function weekendFix($holiday, $isAdjacent = false)
    {
        $timestamp =  strtotime($holiday);
        $dayofweek = (int)date('w', $timestamp); //mon=1, sat=6, sun=7
        if ($dayofweek === self::SUNDAY) {
            $timestamp += self::TWENTYFOURHOURS;
            if ($isAdjacent) {
                $timestamp += self::TWENTYFOURHOURS;
            }
        }
        if ($dayofweek === self::SATURDAY) {
            $timestamp += (self::TWENTYFOURHOURS*2) ;
        }
        if ($dayofweek === self::MONDAY && $isAdjacent) {
            $timestamp += self::TWENTYFOURHOURS;
        }
        return $timestamp;
    }
}