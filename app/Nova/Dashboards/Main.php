<?php

namespace App\Nova\Dashboards;

use App\Nova\Metrics\BikeSellRequestPerDay;
use App\Nova\Metrics\OrderPerDay;
use App\Nova\Metrics\OrderTotalPayment;
use App\Nova\Metrics\RegisteredUsers;
use App\Nova\Metrics\ShowroomPerCity;
use App\Nova\Metrics\TotalProduct;
use Laravel\Nova\Cards\Help;
use Laravel\Nova\Dashboards\Main as Dashboard;

class Main extends Dashboard
{
    /**
     * Get the cards for the dashboard.
     *
     * @return array
     */
    public function cards()
    {
        return [
            new RegisteredUsers(),
            new OrderTotalPayment(),
            new TotalProduct(),
            new OrderPerDay(),
//            new BikeSellRequestPerDay(),
//            new ShowroomPerCity(),
        ];
    }

    public function name()
    {
        return "Dashboard"; // TODO: Change the autogenerated stub
    }
}
