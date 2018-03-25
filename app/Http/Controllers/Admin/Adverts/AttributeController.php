<?php

namespace App\Http\Controllers\Admin\Adverts;

use App\Entity\Adverts\Attribute;
use App\Entity\Adverts\Category;
use function array_keys;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;
use function preg_split;

class AttributeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Category $category)
    {
        $types = Attribute::typesList();

        return view('admin.adverts.categories.attributes.create', compact('category', 'types'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Category $category)
    {
        $this->validate($request,[
            'name' => 'required|string|max:255',
            'type' => ['required', 'string', 'max:255', Rule::in(
                array_keys(Attribute::typesList())
            )],
            'required' => 'nullable|string|max:255',
            'variants' => 'nullable|string',
            'sort' => 'required|integer',
        ]);

        $attribute = $category->attributes()->create([
            'name' => $request['name'],
            'type' => $request['type'],
            'required' => (bool)$request['required'],
            'variants' => array_map('trim', preg_split('#[\r\n]+#', $request['variants'])),
            'sort' => $request['sort']
        ]);

        return redirect()->route('admin.adverts.categories.attributes.show', [$category, $attribute]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Entity\Adverts\Attribute  $attribute
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category, Attribute $attribute)
    {
        return view('admin.adverts.categories.attributes.show', compact('category', 'attribute'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Entity\Adverts\Attribute  $attribute
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category, Attribute $attribute)
    {
        $types = Attribute::typesList();

        return view('admin.adverts.categories.attributes.edit', compact('category', 'attribute', 'types'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Entity\Adverts\Attribute  $attribute
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category, Attribute $attribute)
    {
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'type' => ['required', 'string', 'max:255', Rule::in(array_keys(Attribute::typesList()))],
            'required' => 'nullable|string|max:255',
            'variants' => 'nullable|string',
            'sort' => 'required|integer',
        ]);

        $category->attributes()->findOrFail($attribute->id)->update([
            'name' => $request['name'],
            'type' => $request['type'],
            'required' => (bool)$request['required'],
            'variants' => array_map('trim', preg_split('#[\r\n]+#', $request['variants'])),
            'sort' => $request['sort'],
        ]);
        return redirect()->route('admin.adverts.categories.show', $category);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Category $category
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->route('admin.adverts.categories.show', $category);
    }
}
