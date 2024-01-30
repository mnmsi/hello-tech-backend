<?php

namespace App\Nova\Metrics;

use App\Models\GuestOrder;
use App\Models\Order\Order;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Trend;

class  TotalGuestOrder extends Trend
{
    public function name()
    {
        return "Total Guest Order";
    }

    public function calculate(NovaRequest $request)
    {
        return $this->countByDays($request, GuestOrder::class)
            ->showSumValue();
    }

    public function ranges()
    {
        return [
            15 => '15 Days',
            30 => '30 Days',
            60 => '60 Days',
            90 => '90 Days',
            365 => '365 Days',
        ];
    }
}
