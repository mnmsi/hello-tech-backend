<?php

namespace App\Nova;

use App\Nova\Actions\Order\OrderCancelledActions;
use App\Nova\Actions\Order\OrderCompletedActions;
use App\Nova\Actions\Order\OrderDeliveredActions;
use App\Nova\Actions\Order\OrderInvoice;
use App\Nova\Actions\Order\OrderInvoiceAction;
use App\Nova\Actions\Order\OrderPendingActions;
use App\Nova\Actions\Order\OrderProcessingActions;
use App\Nova\Filters\OrderByDateFilter;
use App\Nova\Filters\OrderStatusFilter;
use App\Nova\Metrics\OrderPerDay;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Exceptions\HelperNotSupported;
use Laravel\Nova\Fields\Badge;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Query\Search\SearchableRelation;

class Order extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\Order\Order>
     */
    public static $model = \App\Models\Order\Order::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'transaction_id';
    public static $group = 'Orders';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'transaction_id',
    ];

    public static function label()
    {
        return __('Order List');
    }

    /**
     * Get the fields displayed by the resource.
     *
     * @param NovaRequest $request
     * @return array
     * @throws HelperNotSupported
     */
    public function fields(NovaRequest $request)
    {
        return [
            ID::make()->sortable(),
            //            user
            BelongsTo::make('User', 'user', 'App\Nova\User')
                ->rules('required')
                ->noPeeking(),
            //            payment method
            BelongsTo::make('Payment Method', 'paymentMethod', 'App\Nova\PaymentMethods')
                ->rules('required')
                ->noPeeking(),
            //            delivery
            BelongsTo::make('Delivery Type', 'deliveryOption', 'App\Nova\DeliveryOption')
                ->rules('required')
                ->noPeeking(),
            //            user address
            BelongsTo::make('User address', 'userAddress', 'App\Nova\UserAddress')
                ->nullable()
                ->noPeeking(),
            //            Voucher
            BelongsTo::make('Voucher', 'voucher', 'App\Nova\Voucher')
                ->nullable()
                ->noPeeking(),
            //            transaction id
            Text::make('Transaction key', 'transaction_id')
                ->sortable()
                ->nullable()
                ->withMeta([
                    'extraAttributes' => [
                        'placeholder' => 'Enter key',
                    ],
                ]),
            //            order key
            Text::make('Order key', 'order_key')
                ->sortable()
                ->nullable()
                ->withMeta([
                    'extraAttributes' => [
                        'placeholder' => 'Enter key',
                    ],
                ]),
            //            discount
            Number::make('Discount', 'discount_rate')
                ->min(0)
                ->step('any')
                ->nullable(),
            //            shipping amount
            Number::make('Shipping amount', 'shipping_amount')
                ->min(0)
                ->step('any')
                ->nullable(),
            //            sub total
            Number::make('Sub total', 'subtotal_price')
                ->min(0)
                ->step('any')
                ->rules('required'),
            //            total
            Number::make('Total', 'total_price')
                ->min(0)
                ->step('any')
                ->rules('required'),
            //            order note
            Textarea::make('Order note', 'order_note')
                ->sortable()
                ->nullable()
                ->withMeta([
                    'extraAttributes' => [
                        'placeholder' => 'Enter note(optional)',
                    ],
                ]),
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

            //            date
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
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param NovaRequest $request
     * @return array
     */
    public function cards(NovaRequest $request)
    {
        return [
            new OrderPerDay,
        ];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param NovaRequest $request
     * @return array
     */
    public function filters(NovaRequest $request)
    {
        return [
            new OrderStatusFilter,
            new OrderByDateFilter,
        ];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param NovaRequest $request
     * @return array
     */
    public function lenses(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param NovaRequest $request
     * @return array
     */
    public function actions(NovaRequest $request)
    {
        return [
            new OrderInvoiceAction(),
            new OrderPendingActions(),
            new OrderProcessingActions(),
            new OrderCompletedActions(),
            new OrderDeliveredActions(),
            new OrderCancelledActions(),

            (new OrderInvoiceAction())->onlyOnTableRow(),
            (new OrderPendingActions())->onlyOnTableRow(),
            (new OrderProcessingActions())->onlyOnTableRow(),
            (new OrderCompletedActions())->onlyOnTableRow(),
            (new OrderDeliveredActions())->onlyOnTableRow(),
            (new OrderCancelledActions())->onlyOnTableRow(),
        ];
    }

    public static function searchableColumns()
    {
        return [
            'id',
            new SearchableRelation('user', 'email'),
            new SearchableRelation('paymentMethod', 'name'),
            new SearchableRelation('deliveryOption', 'name'),
        ];
    }

    public static function authorizedToCreate(Request $request): bool
    {
        return false;
    }
}
