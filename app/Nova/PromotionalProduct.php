<?php

namespace App\Nova;

use App\Models\Product\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Outl1ne\MultiselectField\Multiselect;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Whitecube\NovaFlexibleContent\Flexible;

class PromotionalProduct extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\Dynamic\PromotionalProduct>
     */
    public static $model = \App\Models\Dynamic\PromotionalProduct::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'title';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'title'
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param \Laravel\Nova\Http\Requests\NovaRequest $request
     * @return array
     * @throws \Exception
     */
    public function fields(NovaRequest $request)
    {
        return [
            ID::make()->sortable(),
            //            title
            Text::make('Title', 'title')
                ->sortable()
                ->rules('required', 'max:255')
                ->withMeta([
                    'extraAttributes' => [
                        'placeholder' => 'Enter title',
                    ],
                ]),
//            MultiSelect::make("Product List","product_list")
//                ->rules('required', 'max:255')
//                ->options(
//                    \App\Models\Product\Product::where('is_active', 1)->pluck('name', 'id')
//                )
//                ->placeholder('Select product for promotions')
//                ->optionsLimit(30),
            //            status
            Select::make('Status', 'status')->options([
                '1' => 'Yes',
                '0' => 'No',
            ])->rules('required')
                ->resolveUsing(function ($value) {
                    if ($value === 0) {
                        return 0;
                    }
                    return 1;
                })
                ->displayUsing(function ($v) {
                    return $v ? "Active" : "Inactive";
                }),
            //            section
            Flexible::make('Product List', 'new_product_list')
                ->button('Add Some Product')
                ->addLayout('Select Product', 'wysiwyg', [
                    Select::make('Product', 'product')->options(
                        Product::pluck('name', 'id')
                    )->rules('required')
                        ->searchable()
                        ->displayUsingLabels(),
                    Number::make('Product Position No.', 'order')
                        ->rules('required'),
                ])->hideFromIndex()
                ->withMeta([
                    'ignoreOnSaving',
                ]),
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param \Laravel\Nova\Http\Requests\NovaRequest $request
     * @return array
     */
    public function cards(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param \Laravel\Nova\Http\Requests\NovaRequest $request
     * @return array
     */
    public function filters(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param \Laravel\Nova\Http\Requests\NovaRequest $request
     * @return array
     */
    public function lenses(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param \Laravel\Nova\Http\Requests\NovaRequest $request
     * @return array
     */
    public function actions(NovaRequest $request)
    {
        return [];
    }

    protected static function fillFields(NovaRequest $request, $model, $fields)
    {
        if ($request->isCreateOrAttachRequest()) {
            $fields = $fields->reject(function ($field) {
                return $field->attribute === 'new_product_list';
            });
        }

        if ($request->isUpdateOrUpdateAttachedRequest()) {
            $fields = $fields->reject(function ($field) {
                return $field->attribute === 'new_product_list';
            });
        }

        return parent::fillFields($request, $model, $fields);
    }

    public static function afterCreate(NovaRequest $request, $model)
    {
        $formData = $request->only('new_product_list');
        if (isset($formData['new_product_list'])) {
            $result = [];
            foreach ($formData['new_product_list'] as $list) {
                $result[] = [
                    "product" => $list['attributes']['product'],
                    "order" => $list['attributes']['order'],
                ];
            }
            $model->product_list = json_encode($result);
            $model->save();
        }
    }
//    update
    public static function afterUpdate(NovaRequest $request, $model)
    {
        $product_list = $request->only('new_product_list');
        if (isset($product_list['new_product_list'])) {
            $result = [];
            foreach ($product_list['new_product_list'] as $list) {
                $result[] = [
                    "product" => $list['attributes']['product'],
                    "order" => $list['attributes']['order'],
                ];
            }
            $model->product_list = json_encode($result);
            $model->save();
        }
    }

    public static function afterDelete(NovaRequest $request, Model $model)
    {
        // Clear the cache for the deleted model
        Cache::forget($model->getTable());
    }
}
