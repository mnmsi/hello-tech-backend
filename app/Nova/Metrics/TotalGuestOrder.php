<?php

namespace App\Nova\Metrics;

use App\Models\GuestOrder;
use Laravel\Nova\Metrics\Value;

class  TotalGuestOrder extends Value
{
    public function name()
    {
        return "Total Guest Order";
    }

    public function calculate()
    {
        return $this->result(GuestOrder::count());
    }

    public function ranges()
    {
        return [
            30 => '30 Days',
            60 => '60 Days',
            365 => '365 Days',
        ];
    }
}
