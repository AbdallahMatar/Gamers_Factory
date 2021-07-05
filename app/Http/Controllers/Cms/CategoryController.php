<?php

namespace App\Http\Controllers\Cms;

use App\Category;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $categories = Category::paginate(10);
        return view('admin.category.index', ['categories' => $categories]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('admin.category.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'name' => 'required|string|min:2|max:15|unique:categories',
            'status' => 'in:on'
        ], [
            'name.required' => 'Please enter category name',
            'name.min' => 'Name must be at least 3 characters'

        ]);

        $Category = new Category();
        $Category->name = $request->get('name');
        $Category->status = $request->has('status') ? 'Active' : 'InActive';
        $isSaved = $Category->save();
        if ($isSaved) {
            toast('Category Add Successfully', 'success');
            return redirect(route('categories.index'));
        } else {
            toast('Faild to create Category', 'error');
            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $category = Category::findOrFail($id);
        return view('admin.category.edit', ['category' => $category]);
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
        //
        $request->request->add(['id' => $id]);
        $request->validate([
            'id' => 'required|integer|exists:categories',
            'name' => 'required|string|min:3|max:15|unique:categories,name,' . $id,
            'status' => 'in:on'
        ]);

        $Category = Category::find($id);
        $Category->name = $request->get('name');
        $Category->status = $request->has('status') ? 'Active' : 'InActive';

        $isSaved = $Category->save();
        if ($isSaved) {
            toast('Category Updated Successfully', 'success');
            return redirect(route('categories.index'));
        } else {
            toast('Faild to Updated Category', 'error');
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $isDeleted = Category::destroy($id);
        if ($isDeleted) {
            return response()->json([
                'title' => 'Success',
                'text' => 'Category Deleted Successfully',
                'icon' => 'success'
            ], 200);
        } else {
            return response()->json([
                'title' => 'Failed',
                'text' => 'Faild to delete Category',
                'icon' => 'error'
            ], 400);
        }
    }

    public function showStates($id)
    {
        $states = Category::find($id)->states()->paginate(5);
        return view('admin.states.index', ['states' => $states]);
    }
}
