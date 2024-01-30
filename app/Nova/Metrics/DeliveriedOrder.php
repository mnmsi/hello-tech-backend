<?php

namespace App\Nova\Metrics;

use App\Models\Order\Order;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Trend;
use Laravel\Nova\Metrics\Value;
use Laravel\Nova\Nova;

class DeliveriedOrder extends Trend
{
    public $name = "Total Order Delivered";

    /**
     * Calculate the value of the metric.
     *
     * @param \Laravel\Nova\Http\Requests\NovaRequest $request
     * @return mixed
     */
    public function calculate(NovaRequest $request)
    {
        return $this->countByDays($request, Order::where("status", "delivered"))->showSumValue();
    }

    /**
     * Get the ranges available for the metric.
     *
     * @return array
     */
    public function ranges()
    {
        return [
            15 => Nova::__('15 Days'),
            30 => Nova::__('30 Days'),
            60 => Nova::__('60 Days'),
            365 => Nova::__('365 Days'),
        ];
    }

    /**
     * Determine the amount of time the results of the metric should be cached.
     *
     * @return \DateTimeInterface|\DateInterval|float|int|null
     */
    public function cacheFor()
    {
        // return now()->addMinutes(5);
    }
}
