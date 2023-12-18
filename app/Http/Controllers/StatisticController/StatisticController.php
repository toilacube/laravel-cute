<?php
namespace App\Http\Controllers\StatisticController;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Statistic\StatisticService;

class StatisticController extends Controller
{

    public function __construct(private StatisticService $statisticService)
    {
    }

    public function lifetimeSales(){
        return $this->statisticService->lifetimeSales();
    }

    public function saleStatistic(Request $request){
        $period = $request->period;
        return $this->statisticService->saleStatistic($period);
    }

    public function bestSellers(){
        return $this->statisticService->bestSellers();
    }
}