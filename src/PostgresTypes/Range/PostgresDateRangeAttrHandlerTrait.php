<?php namespace FHTeam\EloquentCustomAttrs\PostgresTypes\Range;

use Carbon\Carbon;
use Exception;
use PostgresTypes\Range\PostgresDateRangeAttributeWrapper;

/**
 * Class PostgresDateRangeAttrHandlerTrait
 *
 * @package PostgresTypes\Range
 */
trait PostgresDateRangeAttrHandlerTrait
{
    protected $dateRangeAttrWrappers = [];

    /**
     * @param string $key
     *
     * @return DateRange|null
     */
    public function handleGetAttributePostgresDateRange($key)
    {
        if (isset($this->dateRangeAttrWrappers[$key])) {
            return $this->dateRangeAttrWrappers[$key];
        }
        $wrapper = new PostgresDateRangeAttributeWrapper(
            $this,
            $key,
            $this->postgresDateRangeToObject(parent::getAttribute($key))
        );

        $this->dateRangeAttrWrappers[$key] = $wrapper;

        return $wrapper;
    }

    /**
     * @param string         $key
     * @param DateRange|null $value
     *
     * @return void
     */
    public function handleSetAttributePostgresDateRange($key, $value)
    {
        parent::setAttribute($key, $this->objectToPostgresDateRange($value));
    }

    /**
     * @param string $dateRange
     *
     * @return DateRange
     * @throws Exception
     */
    public function postgresDateRangeToObject($dateRange)
    {
        if (null == $dateRange) {
            return null;
        }

        $dateRange = trim($dateRange);
        if ($dateRange[0] !== '[' && $dateRange[0] !== '(') {
            throw new Exception("Cannot parse postgres date range. No '[' or '(' as a range opener in: '$dateRange'");
        }

        $lastChar = strlen($dateRange) - 1;

        if ($dateRange[$lastChar] !== ']' && $dateRange[$lastChar] !== ')') {
            throw new Exception("Cannot parse postgres date range. No ']' or ')' as a range closer in: '$dateRange'");
        }

        $dates = explode(',', substr($dateRange, 0, $lastChar));
        $dateFrom = $dates[0] == '-infinity' ? new Carbon(DateRange::DATE_MIN) : new Carbon($dates[0]);
        $dateFromInclusive = $dateRange[0] === '[';
        $dateTo = $dates[1] == 'infinity' ? new Carbon(DateRange::DATE_MAX) : new Carbon($dates[1]);
        $dateToInclusive = $dateRange[$lastChar] === ']';

        return new DateRange($dateFrom, $dateFromInclusive, $dateTo, $dateToInclusive);
    }

    /**
     * @param DateRange $dateRange
     *
     * @return string
     */
    public function objectToPostgresDateRange(DateRange $dateRange)
    {
        $range = '';
        $range .= $dateRange->isFromInclusive() ? '[' : '(';

        $dateFrom = $dateRange->getFrom();
        $dateTo = $dateRange->getTo();

        $range .= $dateFrom->lte(new Carbon(DateRange::DATE_MIN)) ? '-infinity' : $dateFrom->toIso8601String();
        $range .= ', ';

        $range .= $dateTo->gte(new Carbon(DateRange::DATE_MAX)) ? 'infinity' : $dateTo->toIso8601String();

        $range .= $dateRange->isToInclusive() ? ']' : ')';

        return $range;
    }
}
