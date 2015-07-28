<?php namespace FHTeam\EloquentCustomAttrs\PostgresTypes\Range;

use Carbon\Carbon;

class DateRange implements DateRangeInterface
{
    const DATE_MIN = '0001-01-01 00:00:00';

    const DATE_MAX = '9999-12-31 23:59:59';

    /**
     * @var Carbon
     */
    protected $from;

    /**
     * @var bool
     */
    protected $fromInclusive;

    /**
     * @var Carbon
     */
    protected $to;

    /**
     * @var bool
     */
    protected $toInclusive;

    /**
     * DateRange constructor.
     *
     * @param Carbon $from
     * @param bool   $fromInclusive
     * @param Carbon $to
     * @param bool   $toInclusive
     */
    public function __construct(Carbon $from, $fromInclusive, Carbon $to, $toInclusive)
    {
        $this->from = $from;
        $this->fromInclusive = $fromInclusive;
        $this->to = $to;
        $this->toInclusive = $toInclusive;
    }

    /**
     * @return Carbon
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * @param Carbon $from
     */
    public function setFrom($from)
    {
        $this->from = $from;
    }

    /**
     * @return boolean
     */
    public function isFromInclusive()
    {
        return $this->fromInclusive;
    }

    /**
     * @param boolean $fromInclusive
     */
    public function setFromInclusive($fromInclusive)
    {
        $this->fromInclusive = $fromInclusive;
    }

    /**
     * @return Carbon
     */
    public function getTo()
    {
        return $this->to;
    }

    /**
     * @param Carbon $to
     */
    public function setTo($to)
    {
        $this->to = $to;
    }

    /**
     * @return boolean
     */
    public function isToInclusive()
    {
        return $this->toInclusive;
    }

    /**
     * @param boolean $toInclusive
     */
    public function setToInclusive($toInclusive)
    {
        $this->toInclusive = $toInclusive;
    }
}
