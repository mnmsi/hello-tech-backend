<?php

namespace App\Nova;

use App\Nova\Actions\GuestOrderInvoiceAction;
use App\Nova\Actions\Order\OrderCancelledActions;
use App\Nova\Actions\Order\OrderCompletedActions;
use App\Nova\Actions\Order\OrderDeliveredActions;
use App\Nova\Actions\Order\OrderPendingActions;
use App\Nova\Actions\Order\OrderProcessingActions;
use App\Nova\Metrics\TotalGuestOrder;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Badge;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class GuestOrder extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\GuestOrder>
     */
    public static $model = \App\Models\GuestOrder::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'order_key';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id','order_key','name','transaction_id','phone_number','email'
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [

            Text::make('Name of the customer','name')->required(),
            Text::make('Email','email'),
            Text::make('Phone','phone_number')->required(),
            Text::make('City','city')->required(),
            Text::make('Division','division')->required(),
            Text::make('Area','area')->required(),
            Text::make('Address Line','address_line')->required(),
            Text::make('Delivery Option','delivery_option')->required(),
            Text::make('Payment Method','payment_method')->required(),
            Text::make('Order Note','order_note')->required(),
            Text::make('Voucher Code','voucher_code')->required(),
            Text::make('Transaction ID','transaction_id')->required(),
            Text::make('Order Key','order_key')->required(),
            Text::make('Discount Rate','discount_rate')->required(),
            Text::make('Shipping Amount','shipping_amount')->required(),
            Text::make('Subtotal Price','subtotal_price')->required(),
            Text::make('Total Price','total_price')->required(),
            //            status
            Select::make('Status', 'status')->options([
                'pending' => 'Pending',
                'processing' => 'Processing',
                'completed' => 'Completed',
                'delivered' => 'Delivered',
                'cancelled' => 'Cancelled',
            ])->rules('required')->hideFromIndex()
                ->hideFromDetail(),

            Badge::make('Status','status')->map([
                'pending' => 'warning',
                'processing' => 'info',
                'completed' => 'success',
                'delivered' => 'success',
                'cancelled' => 'danger',
            ]),
            DateTime::make('Order date', 'created_at')
                ->default(now())
                ->hideWhenCreating()
                ->hideWhenUpdating()
                ->displayUsing(function ($v) {
                    return Carbon::parse($v)->format('Y-m-d H:i:s');
                }),
            DateTime::make('Updated At', 'updated_at')
                ->hideFromIndex()
                ->hideWhenCreating()
                ->hideWhenUpdating()
                ->default(now()),
          HasMany::make('Order Details','orderItems',GuestOrderDetails::class)->show(),
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function cards(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function filters(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function lenses(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function actions(NovaRequest $request)
    {
        return [
            new GuestOrderInvoiceAction(),
            new OrderPendingActions(),
            new OrderProcessingActions(),
            new OrderCompletedActions(),
            new OrderDeliveredActions(),
            new OrderCancelledActions(),

            (new GuestOrderInvoiceAction())->onlyOnTableRow(),
            (new OrderPendingActions())->onlyOnTableRow(),
            (new OrderProcessingActions())->onlyOnTableRow(),
            (new OrderCompletedActions())->onlyOnTableRow(),
            (new OrderDeliveredActions())->onlyOnTableRow(),
            (new OrderCancelledActions())->onlyOnTableRow(),
        ];
    }

    public static function authorizedToCreate(Request $request)
    {
        return false;
    }

    public function metrics(Request $request)
    {
        return [
            new TotalGuestOrder(),
        ];
    }



}
