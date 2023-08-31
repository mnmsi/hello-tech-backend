<?php

namespace App\Nova;

use App\Models\Product\Product;
use App\Models\ProductMetaKey;
use App\Models\ProductMetaValue;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\Hidden;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Whitecube\NovaFlexibleContent\Flexible;

class MetaKey extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<ProductMetaKey>
     */
    public static $model = ProductMetaKey::class;

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
            Text::make('Product Meta Key', 'key')
                ->sortable()
                ->rules('required', 'max:255')
                ->withMeta([
                    'extraAttributes' => [
                        'placeholder' => 'Enter key',
                    ],
                ]),
//            product
            BelongsTo::make('Category', 'category')
                ->searchable()
                ->rules('required')
                ->noPeeking(),
//            key value
            HasMany::make('Product Meta Value', 'productMetaValues', 'App\Nova\MetaValue'),
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
            //            meta value add
            Flexible::make('Add Some Meta Value*', 'meta_list')
                ->button('Add More Meta')
                ->addLayout('Fill up all data', 'meta', [
                    // id
                    Hidden::make('Meta Id', 'meta_id')
                        ->hideFromDetail()
                        ->hideFromIndex()
                        ->hideWhenCreating()
                        ->readonly(),
                    //                  meta name
                    Text::make('Meta Value', 'meta_name')
                        ->sortable()
                        ->rules('required', 'max:255')
                        ->withMeta([
                            'extraAttributes' => [
                                'placeholder' => 'Enter value',
                            ],
                        ]),
                    //                    product
                    Select::make('Product', 'meta_product')->options(
                        Product::where('is_active', 1)->pluck('name', 'id')
                    )->nullable()
                        ->searchable()
                        ->displayUsingLabels(),
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
                return $field->attribute == 'meta_list';
            });
        }

        if ($request->isUpdateOrUpdateAttachedRequest()) {
            $fields = $fields->reject(function ($field) {
                return $field->attribute == 'meta_list';
            });
        }

        return parent::fillFields($request, $model, $fields);
    }

    public static function afterCreate(NovaRequest $request, $model)
    {
        $formData = $request->only('meta_list');

        if (isset($formData['meta_list'])) {
            foreach ($formData['meta_list'] as $list) {
                $value_list = new ProductMetaValue();
                $value_list->product_meta_key_id = $model->id;
                $value_list->value = $list['attributes']['meta_name'];
                $value_list->product_id = $list['attributes']['meta_product'];
                $value_list->save();
            }
        }
    }

    public static function afterUpdate(NovaRequest $request, $model)
    {
        $meta_list = $request->only('meta_list');
        $metaList = new ProductMetaValue();
        //        data
        $new_list = collect($meta_list['meta_list'])->pluck('attributes.meta_id')->toArray();
        $prev_lists = $metaList->where('product_meta_key_id', $model->id)->pluck('id')->toArray();
        $deleteLists = array_diff($prev_lists, $new_list);
        //       delete color
        ProductMetaValue::whereIn('id', $deleteLists)->each(function ($item) {
            $item->delete();
        });

        if (isset($meta_list['meta_list'])) {
            foreach ($meta_list['meta_list'] as $list) {
                if ($list['attributes']['meta_id']) {
                    $check = ProductMetaValue::find($list['attributes']['meta_id']);
                    $check->update([
                        'value' => $list['attributes']['meta_name'],
                        'product_id' => $list['attributes']['meta_product'],
                    ]);
                } else {
                    $metaList->create([
                        'product_meta_key_id' => $model->id,
                        'value' => $list['attributes']['meta_name'],
                        'product_id' => $list['attributes']['meta_product'],
                    ]);
                }
            }
        }
    }
}
