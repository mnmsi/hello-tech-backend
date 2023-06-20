<?php

namespace App\Nova;

use App\Nova\Filters\ProductStatusFilter;
use App\Nova\Metrics\TotalProduct;
use Illuminate\Support\Facades\Storage;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\FormData;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\Hidden;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\Trix;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Query\Search\SearchableRelation;
use Whitecube\NovaFlexibleContent\Flexible;
use App\Models\Product\ProductColor;
use App\Models\Product\ProductSpecification;

class Product extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\Product\Product>
     */
    public static $model = \App\Models\Product\Product::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'name';
    public static $group = 'Product';
    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'name',
    ];

    public static function label()
    {
        return __('Product List');
    }

    /**
     * Get the fields displayed by the resource.
     *
     * @param NovaRequest $request
     * @return array
     * @throws \Exception
     */
    public function fields(NovaRequest $request): array
    {
        return [
            ID::make()->sortable(),
            //            brand
            BelongsTo::make('Brand', 'brand')
                ->rules('required')
                ->noPeeking(),
            //            image
            Image::make('Image', 'image_url')
                ->path('product_image')
                ->disk('public')
                ->creationRules('required')
                ->updateRules('nullable')
                ->help("*For better view please use image height=200,width=282")
                ->disableDownload(),
            //            badge
            Image::make('Badge Image', 'badge_url')
                ->path('product_badge')
                ->disk('public')
                ->nullable()
                ->help("*For better view please use image height=52,width=52")
                ->disableDownload(),
            //              type
            Select::make('Type', 'type')->options([
                'bike' => 'Bike',
                'accessory' => 'Accessory',
            ])->rules('required'),

            //            bike body type
            BelongsTo::make('Body Type', 'bodyType')
                ->dependsOn(['type'], function (BelongsTo $field, NovaRequest $request, FormData $formData) {
                    if ($formData->type == "bike") {
                        $field
                            ->rules('required');
                    } else {
                        $field
                            ->hideWhenCreating()
                            ->hideWhenUpdating()
                            ->hide()
                            ->nullable();
                    }
                })
                ->noPeeking(),

            //            category
            BelongsTo::make('Category', 'category')
                ->dependsOn(['type'], function (BelongsTo $field, NovaRequest $request, FormData $formData) {
                    if ($formData->type == "accessory") {
                        $field
                            ->rules('required');
                    } else {
                        $field
                            ->hide()
                            ->nullable();
                    }
                })
                ->noPeeking(),
            //            name
            Text::make('Name', 'name')
                ->sortable()
                ->rules('required', 'max:255')
                ->withMeta([
                    'extraAttributes' => [
                        'placeholder' => 'Enter name',
                    ],
                ]),
            //            code
            Text::make('Product code', 'product_code')
                ->sortable()
                ->rules('required', 'max:255')
                ->withMeta([
                    'extraAttributes' => [
                        'placeholder' => 'Enter code',
                    ],
                ])
                ->creationRules('unique:App\Models\Product\Product,product_code')
                ->updateRules('unique:App\Models\Product\Product,product_code,{{resourceId}}'),
            //            price
            Number::make('price')
                ->min(1)
                ->step('any')
                ->rules('required'),
            //            discount
            Number::make('Discount', 'discount_rate')
                ->default(0)
                ->min(0)
                ->step('any')
                ->nullable(),
            //            used or not
            Select::make('Is Used', 'is_used')->options([
                '1' => 'Yes',
                '0' => 'No',
            ])->rules('required')
                ->resolveUsing(function ($value) {
                    if (!$value) {
                        return 0;
                    }
                    return 1;
                })
                ->displayUsing(function ($v) {
                    return $v ? "Used" : "New";
                }),
            //            feature
            Select::make('Featured', 'is_featured')->options([
                '1' => 'Yes',
                '0' => 'No',
            ])->rules('required')
                ->resolveUsing(function ($value) {
                    if (!$value) {
                        return 0;
                    }
                    return 1;
                })
                ->displayUsing(function ($v) {
                    return $v ? "Yes" : "No";
                }),
            //              status
            Select::make('Status', 'is_active')->options([
                '1' => 'Yes',
                '0' => 'No',
            ])->rules('required')
                ->resolveUsing(function ($value) {
                    if (!$value) {
                        return 0;
                    }
                    return 1;
                })
                ->displayUsing(function ($v) {
                    return $v ? "Active" : "Inactive";
                }),
            //              short description
            Textarea::make('Short description', 'short_description')
                ->sortable()
                ->nullable()
                ->rows(2)
                ->alwaysShow(),
            //            description
            Trix::make('Description', 'description')
                ->sortable()
                ->rules('required')
                ->alwaysShow(),
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

            HasMany::make('Product Color', 'colors'),
            HasMany::make('Product Specifications', 'specifications'),
            HasMany::make('Product Media', 'media', 'App\Nova\ProductMedia'),

            Flexible::make('Add Color List *', 'color_list')
                ->button('Add Some Product Color')
                ->addLayout('Select Color', 'video', [
                    // id
                    Hidden::make('Color Id', 'color_id')
                        ->hideFromDetail()
                        ->hideFromIndex()
                        ->hideWhenCreating()
                        ->readonly(),
                    //                  color name
                    Text::make('Color Name', 'color_name')
                        ->sortable()
                        ->rules('required', 'max:255')
                        ->withMeta([
                            'extraAttributes' => [
                                'placeholder' => 'Enter name',
                            ],
                        ]),
                    //                  color image
                    Image::make('Color Image', 'color_image')
                        ->path('product_color')
                        ->disk('public')
                        ->creationRules('required')
                        ->updateRules('nullable')
                        ->help("*For better view please use image height=53,width=68")
                        ->preview(function ($value, $disk) {
                            return $value ? Storage::disk($disk)->url($value) : null;
                        })->prunable(),
                    //                  color stock
                    Number::make('Color Stock', 'color_stock')
                        ->min(0)
                        ->rules('required'),

                ])->hideFromIndex()
                ->hideFromDetail(),
            //            product specification
            Flexible::make('Add Product specification *', 'specification_list')
                ->button('Add more specification')
                ->addLayout('Select specification', 'video', [
                    Hidden::make('Specification Id', 'specification_id')
                        ->hideFromDetail()
                        ->hideFromIndex()
                        ->hideWhenCreating()
                        ->readonly(),
                    //                    title
                    Text::make('Specification Title', 'specification_title')
                        ->sortable()
                        ->rules('required', 'max:255')
                        ->withMeta([
                            'extraAttributes' => [
                                'placeholder' => 'Enter specification title',
                            ],
                        ]),
                    //                    details
                    Text::make('Specification Value', 'specification_value')
                        ->sortable()
                        ->rules('required', 'max:255')
                        ->withMeta([
                            'extraAttributes' => [
                                'placeholder' => 'Enter specification value',
                            ],
                        ]),
                    //                    feature
                    Select::make('Specification Feature', 'is_key_feature')->options([
                        '1' => 'Yes',
                        '0' => 'No',
                    ])->rules('required')
                        ->displayUsing(function ($v) {
                            return $v ? "Yes" : "No";
                        }),
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
    public function cards(NovaRequest $request): array
    {
        return [
            new TotalProduct,
        ];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param NovaRequest $request
     * @return array
     */
    public function filters(NovaRequest $request): array
    {
        return [
            new ProductStatusFilter,
        ];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param NovaRequest $request
     * @return array
     */
    public function lenses(NovaRequest $request): array
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param NovaRequest $request
     * @return array
     */
    public function actions(NovaRequest $request): array
    {
        return [];
    }

    public static function searchableColumns(): array
    {
        return [
            'id',
            new SearchableRelation('brand', 'name'),
            new SearchableRelation('bodyType', 'name'),
            new SearchableRelation('category', 'name'),
        ];
    }

    protected static function fillFields(NovaRequest $request, $model, $fields)
    {
        if ($request->isCreateOrAttachRequest()) {
            $fields = $fields->reject(function ($field) {
                return in_array($field->attribute, ['color_list', 'specification_list']);
            });
        }

        if ($request->isUpdateOrUpdateAttachedRequest()) {
            $fields = $fields->reject(function ($field) {
                return in_array($field->attribute, ['color_list', 'specification_list']);
            });
        }

        return parent::fillFields($request, $model, $fields);
    }


    public static function afterCreate(NovaRequest $request, $model)
    {
        $formData = $request->only('color_list');
        $specification_data = $request->only('specification_list');

        if (isset($formData['color_list'])) {
            foreach ($formData['color_list'] as $list) {
                $product_color = new ProductColor();
                $product_color->product_id = $model->id;
                $product_color->name = $list['attributes']['color_name'];
                $product_color->image_url = $request->{$list['attributes']['color_image']}->store('product_color', 'public');
                $product_color->stock = $list['attributes']['color_stock'];
                $product_color->save();
            }
        }
        //        specification
        if (isset($specification_data['specification_list'])) {
            foreach ($specification_data['specification_list'] as $s) {
                $specification = new ProductSpecification();
                $specification->product_id = $model->id;
                $specification->title = $s['attributes']['specification_title'];
                $specification->value = $s['attributes']['specification_value'];
                $specification->is_key_feature = $s['attributes']['is_key_feature'];
                $specification->save();
            }
        }
    }

    public static function afterUpdate(NovaRequest $request, $model)
    {
        $color_list = $request->only('color_list');
        $productColors = new ProductColor();
        //        data
        $new_color = collect($color_list['color_list'])->pluck('attributes.color_id')->toArray();
        $prev_colors = $productColors->where('product_id', $model->id)->pluck('id')->toArray();
        $deleteColors = array_diff($prev_colors, $new_color);
        //       delete color
        $dddd = ProductColor::whereIn('id', $deleteColors)->each(function ($item) {
            $item->delete();
        });

        if (isset($color_list['color_list'])) {
            foreach ($color_list['color_list'] as $list) {
                if ($list['attributes']['color_id']) {
                    $check = ProductColor::find($list['attributes']['color_id']);
                    if (isset($list['attributes']['color_image'])) {
                        $check->update([
                            'name' => $list['attributes']['color_name'],
                            'stock' => $list['attributes']['color_stock'],
                            'image_url' => $request->{$list['attributes']['color_image']}->store('product_color', 'public'),
                        ]);
                    } else {
                        $check->update([
                            'name' => $list['attributes']['color_name'],
                            'stock' => $list['attributes']['color_stock'],
                        ]);
                    }
                } else {
                    $productColors->create([
                        'product_id' => $model->id,
                        'name' => $list['attributes']['color_name'],
                        'stock' => $list['attributes']['color_stock'],
                        'image_url' => $request->{$list['attributes']['color_image']}->store('product_color', 'public'),
                    ]);
                }
            }
        }

        //        product specification update
        $specification_list = $request->only('specification_list');
        $specification_model = new ProductSpecification();

        $specification_new_color = collect($specification_list['specification_list'])->pluck('attributes.specification_id')->toArray();
        $specification_prev_colors = $specification_model->where('product_id', $model->id)->pluck('id')->toArray();
        $specification_delete_colors = array_diff($specification_prev_colors, $specification_new_color);
        //        delete item
        $ppp = ProductSpecification::whereIn('id', $specification_delete_colors)->each(function ($item) {
            $item->delete();
        });

        if (isset($specification_list['specification_list'])) {
            foreach ($specification_list['specification_list'] as $list) {
                if ($list['attributes']['specification_id']) {
                    $check_spe = ProductSpecification::find($list['attributes']['specification_id']);

                    $check_spe->update([
                        'title' => $list['attributes']['specification_title'],
                        'value' => $list['attributes']['specification_value'],
                        'is_key_feature' => $list['attributes']['is_key_feature']
                    ]);
                } else {
                    $specification_model->create([
                        'product_id' => $model->id,
                        'title' => $list['attributes']['specification_title'],
                        'value' => $list['attributes']['specification_value'],
                        'is_key_feature' => $list['attributes']['is_key_feature']
                    ]);
                }
            }
        }
    }
}
