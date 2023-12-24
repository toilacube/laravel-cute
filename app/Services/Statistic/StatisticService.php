<?php

namespace App\Services\Statistic;


use Carbon\Carbon;
use App\Models\OrderLine;
use App\Models\ShopOrder;
use Illuminate\Support\Facades\DB;
use App\DTOs\Responses\BestSellerDTO;
use App\DTOs\Responses\LifeTimeSalesDTO;
use App\Models\ProductItem;

class StatisticService
{
    public function lifetimeSales()
    {

        $totalOrders = ShopOrder::count();
        $totalSales = ShopOrder::sum('order_total');

        // $results = DB::table('shop_order')
        //     ->selectRaw('COUNT(*) as totalOrders, SUM(order_total) as totalSales')
        //     ->first();

        // $totalOrders = $results->totalOrders ?? 0; // Assign 0 if totalOrders is null
        // $totalSales = $results->totalSales ?? 0;


        $completedPercentage = ShopOrder::where('order_status', 1)->count() / $totalOrders * 100;
        $cancelledPercentage = $completedPercentage <= 1 ? 1 - $completedPercentage : 0;

        $lifetimeSales = new LifeTimeSalesDTO(
            $totalOrders,
            $totalSales,
            $completedPercentage,
            $cancelledPercentage
        );

        return $lifetimeSales->toArray();
    }
    public function bestSellers()
    {
        // select top 5 sum(qty) from OrderLine group by product_id order by sum(qty) desc
        $results = OrderLine::selectRaw('product_item_id, SUM(qty) as quantinty')
            ->groupBy('product_item_id')
            ->orderByDesc('quantinty')
            ->limit(20)
            ->get();

        $statistic = [];

        foreach ($results as $result) {
            $productItem = ProductItem::where('id', $result->product_item_id)->with('product')->with('product_item_images')->first();
            //return $productItem;
            $bestSellerDTO = new BestSellerDTO(
                $result->product_item_id,
                $result->quantinty,
                $productItem->product->name,
                $productItem->product_item_images[0]->url,
                $productItem->product->price_int
            );

            $statistic[] = $bestSellerDTO->toArray();
        }

        return response($statistic);
    }

    public function saleStatistic($period)
    {
        // $period: 
        // 1: daily: the last 7 days
        // 2: weekly: the last 7 weeks
        // 3: monthly: the last 7 months

        // do switch case for $period
        switch ($period) {
            case "daily":
                return $this->daily();
                break;
            case "weekly":
                return $this->weekly();
                break;
            case "monthly":
                return $this->monthly();
                break;
            default:
                return "Invalid period";
        }
    }

    public function daily()
    {
        $currentDate = date('Y-m-d');
        $last7Days = date('Y-m-d', strtotime('-7 days', strtotime($currentDate)));

        $results = DB::table('shop_order')
            ->selectRaw('DATE(order_date) as date, SUM(order_total) as totalSales, COUNT(*) as count')
            ->whereBetween('order_date', [$last7Days, $currentDate])
            ->groupBy('date')
            ->orderBy('date')
            ->get();
        $statistic = [];
        foreach ($results as $result) {
            $statistic[] = [
                'date' => $result->date,
                'totalSales' => $result->totalSales,
                'count' => $result->count
            ];
        }
        return response()->json(['data' => $statistic]);
    }

    public function weekly()
    {
        $currentDate = date('Y-m-d');
        $last7Weeks = date('Y-m-d', strtotime('-49 days', strtotime($currentDate)));

        $results = DB::table('shop_order')
            ->selectRaw('WEEK(order_date) as week,MIN(DATE(order_date)) as date, SUM(order_total) as totalSales, COUNT(*) as count')
            ->groupBy('week')
            ->whereBetween('order_date', [$last7Weeks, $currentDate])
            ->orderBy('week')
            ->get();

        $statistic = [];
        foreach ($results as $result) {
            $statistic[] = [
                'time' => $result->date,
                'totalSales' => $result->totalSales,
                'count' => $result->count
            ];
        }
        return response()->json(['data' => $statistic]);
    }

    public function monthly()
    {

        // $currentDate = Carbon::parse('2023-4-10');
        // $fromDate =  Carbon::parse('2023-4-10');

        $currentDate = Carbon::now();
        $fromDate = Carbon::now();

        $last7Months = $currentDate->subMonths(7);

        $firstDateOfMonth = $last7Months->firstOfMonth();

        $results = DB::table('shop_order')
            ->selectRaw('MONTH(order_date) as mo, MIN(DATE(order_date)) as date, SUM(order_total) as totalSales, COUNT(*) as count')
            ->whereBetween('order_date', [$firstDateOfMonth, $fromDate]) // Filter for the last 7 months
            ->groupBy('mo')
            ->orderBy('mo')
            ->get();

        $statistic = [];
        foreach ($results as $result) {
            $statistic[] = [
                'time' => $result->date,
                'totalSales' => $result->totalSales,
                'count' => $result->count
            ];
        }
        return response()->json(['data' => $statistic]);
    }


}
