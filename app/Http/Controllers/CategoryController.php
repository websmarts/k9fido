<?php

namespace App\Http\Controllers;

use App\Legacy\Category\Category;
use App\Legacy\Category\CategoryType;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::topLevel()->with('children')->orderBy('display_order', 'desc')->get();

        return view('admin.category.index')->with('categories', $categories);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $category = new Category;

        $category->parent_id = (int) $request->input('parent_id', 0);

        return view('admin.category.create', ['category' => $category, 'parentOptions' => $this->_parentOptions()]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validate
        $this->validate($request, ['name' => 'required', 'description' => 'required']);

        Category::create($request->all());

        return back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $category = Category::with('children')->find($id);
        return view('admin.category.edit', ['category' => $category, 'parentOptions' => $this->_parentOptions()]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $category = Category::find($id);
        if (!$category) {
            abort(404);
        }
        $category->update($request->all());
        flash('Category updated', 'success');
        return redirect()->route('category.index');
    }

    /**
     * Delete the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        // any types assigned to category?

        // if ($this->productTypes($id)->count() > 0) {
        //     flash('Cannot delete category as it contains products', 'error');
        //     return redirect()->route('category.index');
        // }

        // any sub-categories?
        if ($this->subCategories($id)->count() > 0) {
            flash('Cannot delete category as it contains sub-categories', 'error');
            return redirect()->route('category.index');
        }

        // Okay to delete the category
        Category::find($id)->delete();
        CategoryType::where('catid', $id)->delete();

        flash('Category deleted', 'success');

        return redirect()->route('category.index');

    }

    protected function subCategories($id)
    {
        return Category::find($id)->children;

    }

    protected function productTypes($id)
    {
        return Category::with('productTypes')->find($id)->productTypes;

    }

    protected function _parentOptions()
    {
        return Category::where('parent_id', '=', 0)
            ->orderBy('name', 'asc')
            ->lists('name', 'id')
            ->toArray();
    }
}
