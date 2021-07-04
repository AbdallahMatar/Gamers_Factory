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
        $cities = Category::withcount('states')->paginate(10);
        return view('admin.cities.index', ['cities' => $cities]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('admin.cities.create');
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
            'name' => 'required|string|min:3|max:15|unique:cities',
            'status' => 'in:on'
        ], [
            'name.required' => 'Please enter city name',
            'name.min' => 'Name must be at least 3 characters'

        ]);

        $city = new Category();
        $city->name = $request->get('name');
        $city->status = $request->has('status') ? 'Active' : 'InActive';
        $isSaved = $city->save();
        if ($isSaved) {
            toast('City Add Successfully', 'success');
            return redirect(route('cities.index'));
        } else {
            toast('Faild to create City', 'error');
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
        $city = City::findOrFail($id);
        return view('admin.cities.edit', ['city' => $city]);
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
            'id' => 'required|integer|exists:cities',
            'name' => 'required|string|min:3|max:15|unique:cities,name,' . $id,
            'status' => 'in:on'
        ]);

        $city = City::find($id);
        $city->name = $request->get('name');
        $city->status = $request->has('status') ? 'Active' : 'InActive';

        $isSaved = $city->save();
        if ($isSaved) {
            toast('City Updated Successfully', 'success');
            return redirect(route('cities.index'));
        } else {
            toast('Faild to Updated City', 'error');
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
        $isDeleted = City::destroy($id);
        if ($isDeleted) {
            return response()->json([
                'title' => 'Success',
                'text' => 'City Deleted Successfully',
                'icon' => 'success'
            ], 200);
        } else {
            return response()->json([
                'title' => 'Failed',
                'text' => 'Faild to delete city',
                'icon' => 'error'
            ], 400);
        }
    }

    public function showStates($id)
    {
        $states = City::find($id)->states()->paginate(5);
        return view('admin.states.index', ['states' => $states]);
    }
}
