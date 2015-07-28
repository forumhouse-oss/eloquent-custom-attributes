<?php namespace FHTeam\EloquentCustomAttrs\PostgresTypes\Range;

use Carbon\Carbon;

interface DateRangeInterface
{
    /**
     * @return Carbon
     */
    public function getFrom();

    /**
     * @param Carbon $from
     */
    public function setFrom($from);

    /**
     * @return boolean
     */
    public function isFromInclusive();

    /**
     * @param boolean $fromInclusive
     */
    public function setFromInclusive($fromInclusive);

    /**
     * @return Carbon
     */
    public function getTo();

    /**
     * @param Carbon $to
     */
    public function setTo($to);

    /**
     * @return boolean
     */
    public function isToInclusive();

    /**
     * @param boolean $toInclusive
     */
    public function setToInclusive($toInclusive);
}
