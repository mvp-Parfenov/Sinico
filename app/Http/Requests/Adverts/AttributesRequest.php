<?php

namespace App\Http\Requests\Adverts;

use App\Entity\Adverts\Advert\Advert;
use App\Entity\Adverts\Attribute;
use App\Entity\Adverts\Category;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AttributesRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        $items = [];

        /** @var Advert $advert*/
        $advert = $this->advert;
        /** @var Category $category*/
        $category = $advert->category;

        $attributes = $category->allAttributes();

        foreach ($attributes as $attribute) {
        /** @var Attribute $attribute*/
            $rules = [
                $attribute->required ? 'required' : 'nullable',
            ];

            if ($attribute->isInteger()) {
                $rules[] = 'integer';
            } elseif ($attribute->isFloat()){
                $rules[] = 'numeric';
            } else {
                $rules[] = 'string';
                $rules[] = 'max:255';
            }

            if($attribute->isSelect()){
                $rules[] = Rule::in($attribute->variants);
            }

            $items['attribute.'.$attribute->id] = $rules;
        }
        return $items;
    }
}
