<?php

namespace App\Nova\Metrics;

use App\Models\GuestOrder;
use App\Models\GuestUser;
use App\Nova\Order;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Menu\MenuItem;
use Laravel\Nova\Metrics\MetricTableRow;
use Laravel\Nova\Metrics\Table;

class RecentGuestOrder extends Table
{

    public $name = "Recent Guest Orders";

    /**
     * Calculate the value of the metric.
     *
     * @param \Laravel\Nova\Http\Requests\NovaRequest $request
     * @return mixed
     */
    public function calculate(NovaRequest $request)
    {
        $result = [];
        $order = GuestOrder::orderBy("id", "Desc")->take(10)->get();
        foreach ($order as $o) {
            $result[] = MetricTableRow::make()
                ->title($o['order_key'])
                ->subtitle("  Amount: " . $o['total_price'])
                ->actions(function () {
                    return [
                        MenuItem::resource(\App\Nova\GuestOrder::class),
                    ];
                });
        }
        return $result;
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
