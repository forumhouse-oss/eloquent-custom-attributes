<?php namespace PostgresTypes\Range;

use Carbon\Carbon;
use FHTeam\EloquentCustomAttrs\AbstractAttributeWrapper;
use FHTeam\EloquentCustomAttrs\PostgresTypes\Range\DateRangeInterface;

class PostgresDateRangeAttributeWrapper extends AbstractAttributeWrapper implements DateRangeInterface
{
    /**
     * @return Carbon
     */
    public function getFrom()
    {
        // TODO: Implement getFrom() method.
    }

    /**
     * @param Carbon $from
     */
    public function setFrom($from)
    {
        // TODO: Implement setFrom() method.
    }

    /**
     * @return boolean
     */
    public function isFromInclusive()
    {
        // TODO: Implement isFromInclusive() method.
    }

    /**
     * @param boolean $fromInclusive
     */
    public function setFromInclusive($fromInclusive)
    {
        // TODO: Implement setFromInclusive() method.
    }

    /**
     * @return Carbon
     */
    public function getTo()
    {
        // TODO: Implement getTo() method.
    }

    /**
     * @param Carbon $to
     */
    public function setTo($to)
    {
        // TODO: Implement setTo() method.
    }

    /**
     * @return boolean
     */
    public function isToInclusive()
    {
        // TODO: Implement isToInclusive() method.
    }

    /**
     * @param boolean $toInclusive
     */
    public function setToInclusive($toInclusive)
    {
        // TODO: Implement setToInclusive() method.
    }
}
