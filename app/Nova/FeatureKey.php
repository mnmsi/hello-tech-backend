<?php

namespace App\Nova;

use App\Models\ProductFeatureKey;
use App\Models\ProductFeatureValue;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\Hidden;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Query\Search\SearchableRelation;
use Whitecube\NovaFlexibleContent\Flexible;

class FeatureKey extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<ProductFeatureKey>
     */
    public static $model = ProductFeatureKey::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'key';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'key'
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param NovaRequest $request
     * @return array
     * @throws \Exception
     */
    public function fields(NovaRequest $request)
    {
        return [
            ID::make()->sortable(),
//            key
            Text::make('Product Key', 'key')
                ->sortable()
                ->rules('required', 'max:255')
                ->withMeta([
                    'extraAttributes' => [
                        'placeholder' => 'Enter key',
                    ],
                ]),
//            product
            BelongsTo::make('Product', 'product')
                ->searchable()
                ->rules('required')
                ->noPeeking(),
//            key value
            HasMany::make('Product Feature Value','featureValues','App\Nova\FeatureValue'),
            //            date
            DateTime::make('Created At', 'created_at')
                ->hideFromIndex()
                ->default(now())
                ->hideWhenCreating()
                ->hideWhenUpdating(),

            DateTime::make('Updated At', 'updated_at')
                ->hideFromIndex()
                ->hideWhenCreating()
                ->hideWhenUpdating()
                ->default(now()),
//            feature value add
            Flexible::make('Add Some Feature Value*', 'value_list')
                ->button('Add More Value')
                ->addLayout('Fill up all data', 'value', [
                    // id
                    Hidden::make('Value Id', 'value_id')
                        ->hideFromDetail()
                        ->hideFromIndex()
                        ->hideWhenCreating()
                        ->readonly(),
                    //                  color name
                    Text::make('Value Name', 'value_name')
                        ->sortable()
                        ->rules('required', 'max:255')
                        ->withMeta([
                            'extraAttributes' => [
                                'placeholder' => 'Enter name',
                            ],
                        ]),
                    //                  color price
                    Number::make('Value Price', 'value_price')
                        ->min(0)
                        ->rules('required'),
//                    stock
                    Number::make('Value Stock', 'value_stock')
                        ->min(0)
                        ->rules('required'),

                ])->hideFromIndex()
                ->hideFromDetail(),
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
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param NovaRequest $request
     * @return array
     */
    public function filters(NovaRequest $request)
    {
        return [];
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
        return [];
    }

    protected static function fillFields(NovaRequest $request, $model, $fields)
    {
        if ($request->isCreateOrAttachRequest()) {
            $fields = $fields->reject(function ($field) {
                return $field->attribute == 'value_list';
            });
        }

        if ($request->isUpdateOrUpdateAttachedRequest()) {
            $fields = $fields->reject(function ($field) {
                return $field->attribute == 'value_list';
            });
        }

        return parent::fillFields($request, $model, $fields);
    }

    public static function afterCreate(NovaRequest $request, $model)
    {
        $formData = $request->only('value_list');

        if (isset($formData['value_list'])) {
            foreach ($formData['value_list'] as $list) {
                $value_list = new ProductFeatureValue();
                $value_list->product_feature_key_id  = $model->id;
                $value_list->value = $list['attributes']['value_name'];
                $value_list->price = $list['attributes']['value_price'];
                $value_list->stock = $list['attributes']['value_stock'];
                $value_list->save();
            }
        }
    }

    public static function afterUpdate(NovaRequest $request, $model)
    {
        $value_list = $request->only('value_list');
        $valueList = new ProductFeatureValue();
        //        data
        $new_list = collect($value_list['value_list'])->pluck('attributes.value_id')->toArray();
        $prev_lists = $valueList->where('product_feature_key_id', $model->id)->pluck('id')->toArray();
        $deleteLists = array_diff($prev_lists, $new_list);
        //       delete color
        ProductFeatureValue::whereIn('id', $deleteLists)->each(function ($item) {
            $item->delete();
        });

        if (isset($value_list['value_list'])) {
            foreach ($value_list['value_list'] as $list) {
                if ($list['attributes']['value_id']) {
                    $check = ProductFeatureValue::find($list['attributes']['value_id']);
                    $check->update([
                        'value' => $list['attributes']['value_name'],
                        'price' => $list['attributes']['value_price'],
                        'stock' => $list['attributes']['value_stock'],
                    ]);
                } else {
                    $valueList->create([
                        'product_feature_key_id' => $model->id,
                        'value' => $list['attributes']['value_name'],
                        'price' => $list['attributes']['value_price'],
                        'stock' => $list['attributes']['value_stock'],
                    ]);
                }
            }
        }
    }

    public static function searchableColumns()
    {
        return [
            'id',
            'value',
            new SearchableRelation('product', 'name'),
        ];
    }
}
