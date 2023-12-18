<?php


class SalesStatisticDTO
{
    public $total;
    public $count;
    public $time;

    public function __construct($total, $count, $time)
    {
        $this->total = $total;
        $this->count = $count;
        $this->time = $time;
    }
}
