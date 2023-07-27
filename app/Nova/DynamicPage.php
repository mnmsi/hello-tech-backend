<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\FormData;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\Hidden;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Whitecube\NovaFlexibleContent\Flexible;
use App\Models\Dynamic\DynamicPageBrand;

class DynamicPage extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\Dynamic\DynamicPage>
     */
    public static $model = \App\Models\Dynamic\DynamicPage::class;

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
        'id',"title"
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

            Text::make('Slug', 'slug')
                ->sortable()
                ->rules('required', 'max:255')
                ->hideWhenCreating()
                ->hideWhenUpdating()
                ->withMeta([
                    'extraAttributes' => [
                        'placeholder' => 'Enter slug',
                    ],
                ]),

            Select::make('Select Brand', 'all_brand')->options([
                '1' => 'All brand',
                '0' => 'Selected brand only',
            ])->rules('required')
            ->hideFromDetail()
            ->hideFromIndex(),

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

            HasMany::make('Pages Brand', 'pageBrand',"App\Nova\DynamicPageBrand"),

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
//            brand pages
            Flexible::make('Add Brands', 'brand_list')
                ->dependsOn(['all_brand'], function (Flexible $field, NovaRequest $request, FormData $formData) {
                    if ($formData->all_brand == 0) {
                        $field
                            ->rules('required');
                    } else {
                        $field
                            ->hide()
                            ->nullable();
                    }
                })
                ->button('Add more brand')
                ->addLayout('Select brand', 'brand', [
                    Hidden::make('brand Id', 'brand_id')
                        ->hideFromDetail()
                        ->hideFromIndex()
                        ->hideWhenCreating()
                        ->readonly(),
                    //                    brand
                    Select::make('Brand', 'brand_select')->options(
                        \App\Models\Product\Brand::where('is_active', 1)->pluck('name', 'id')
                    )->nullable()
                        ->searchable()
                        ->displayUsingLabels(),
                    // no of product
                    Number::make('No of product from brand', 'brand_product_count')
                        ->min(0)
                        ->nullable(),
                ])->hideFromIndex()
                ->hideFromDetail(),
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
        return [];
    }

    protected static function fillFields(NovaRequest $request, $model, $fields)
    {
        if ($request->isCreateOrAttachRequest()) {
            $fields = $fields->reject(function ($field) {
                return $field->attribute == 'brand_list';
            });
        }

        if ($request->isUpdateOrUpdateAttachedRequest()) {
            $fields = $fields->reject(function ($field) {
                return $field->attribute == 'brand_list';
            });
        }

        return parent::fillFields($request, $model, $fields);
    }

    //    create
    public static function afterCreate(NovaRequest $request, $model)
    {
        $formData = $request->only('brand_list');

        if (isset($formData['brand_list'])) {
            foreach ($formData['brand_list'] as $list) {
                $brand = new DynamicPageBrand();
                $brand->dynamic_page_id = $model->id;
                $brand->brand_id = $list['attributes']['brand_select'];
                $brand->product_count = $list['attributes']['brand_product_count'] ?? null;
                $brand->save();
            }
        }
    }

    //    update
    public static function afterUpdate(NovaRequest $request, $model)
    {
        $brand_list = $request->only('brand_list');
        $page_brand = new DynamicPageBrand();
        //        data
        $new_brand = collect($brand_list['brand_list'])->pluck('attributes.brand_id')->toArray();
        $prev_brand = $page_brand->where('dynamic_page_id', $model->id)->pluck('id')->toArray();
        $delete_brand = array_diff($prev_brand, $new_brand);
        //       delete color
        $dddd = DynamicPageBrand::whereIn('id', $delete_brand)->each(function ($item) {
            $item->delete();
        });

        if (isset($brand_list['brand_list'])) {
            foreach ($brand_list['brand_list'] as $list) {
                if ($list['attributes']['brand_id']) {
                    $check = DynamicPageBrand::find($list['attributes']['brand_id']);
                    $check->update([
                        'brand_id' => $list['attributes']['brand_select'],
                        'product_count' => $list['attributes']['brand_product_count'] ?? null,
                    ]);
                } else {
                    $page_brand->create([
                        'dynamic_page_id' => $model->id,
                        'brand_id' => $list['attributes']['brand_select'],
                        'product_count' => $list['attributes']['brand_product_count'] ?? null,
                    ]);
                }
            }
        }
    }
}
