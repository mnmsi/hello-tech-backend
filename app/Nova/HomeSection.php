<?php

namespace App\Nova;

use App\Models\System\HomePageSection;
use App\Models\System\HpsProduct;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Whitecube\NovaFlexibleContent\Flexible;
use App\Models\Product\Product;

class HomeSection extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<HomePageSection>
     */
    public static $model = HomePageSection::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'section_title';
    public static $group = 'Custom Home Section';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'section_title'
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
//            title
            Text::make('Section Title', 'section_title')
                ->sortable()
                ->rules('required', 'max:255')
                ->withMeta([
                    'extraAttributes' => [
                        'placeholder' => 'Enter title',
                    ],
                ]),
            Number::make('Section Position No.', 'order_no')
                ->rules('required'),
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

            //            product list
            Flexible::make('Product List')
                ->button('Add Some Product')
                ->addLayout('Select Product', 'wysiwyg', [
                    Select::make('Product', 'product')->options(
                        Product::pluck('name', 'id')
                    )->rules('required')
                        ->searchable()
                        ->displayUsingLabels(),
                    Number::make('Product Position No.', 'order')
                        ->required(),
                ])->hideFromIndex()
                ->withMeta([
                    'ignoreOnSaving',
                ]),
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
                return $field->attribute === 'product_list';
            });
        }

        if ($request->isUpdateOrUpdateAttachedRequest()) {
            $fields = $fields->reject(function ($field) {
                return $field->attribute === 'product_list';
            });
        }

        return parent::fillFields($request, $model, $fields);
    }


    public static function afterCreate(NovaRequest $request, $model)
    {
        $formData = $request->only('product_list');
        if (isset($formData['product_list'])) {

            foreach ($formData['product_list'] as $list) {
                $secModel = new HpsProduct();
                $secModel->hps_section_id = $model->id;
                $secModel->product_id = $list['attributes']['product'];
                $secModel->order = $list['attributes']['order'];
                $secModel->save();
            }
        }
    }

    public static function afterUpdate(NovaRequest $request, $model)
    {
        $old_product_id = HpsProduct::where('hps_section_id', $model->id)->pluck('product_id')->toArray();
        $product_list = $request->only('product_list');
        $new_product_id = collect($product_list['product_list'])->pluck('attributes.product')->toArray();
        $diff_product = array_diff($old_product_id, $new_product_id);

        $secModel = new HpsProduct();
        $secModel->where('hps_section_id', $model->id)
            ->whereIn('product_id', $diff_product)->each(function ($item) {
                $item->delete();
            }); // delete if item remove

        if (isset($product_list['product_list'])) {
            foreach ($product_list['product_list'] as $list) {
                $check = $secModel->where('hps_section_id', $model->id)
                    ->where('product_id', $list['attributes']['product'])
                    ->first();
                if (!$check) {
                    $secModel->create([
                        'hps_section_id' => $model->id,
                        'product_id' => $list['attributes']['product'],
                        'order' => $list['attributes']['order'],
                    ]);
                } else {
                    $check->order = $list['attributes']['order'];
                    $check->save();
                }
            }
        }
    }
}
