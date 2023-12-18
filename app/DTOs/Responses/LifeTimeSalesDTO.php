<?php
namespace App\DTOs\Responses;

class LifeTimeSalesDTO
{
    public int $totalOrders;
    public int $totalSales;
    public int $completedPercentage;
    public int $cancelledPercentage;

    public function __construct(
        int $totalOrders,
        int $totalSales,
        int $completedPercentage,
        int $cancelledPercentage
    ) {
        $this->totalOrders = $totalOrders;
        $this->totalSales = $totalSales;
        $this->completedPercentage = $completedPercentage;
        $this->cancelledPercentage = $cancelledPercentage;
    }

    public function toArray()
    {
        return [
            'totalOrders' => $this->totalOrders,
            'totalSales' => $this->totalSales,
            'completedPercentage' => $this->completedPercentage,
            'cancelledPercentage' => $this->cancelledPercentage
        ];
    }
}
