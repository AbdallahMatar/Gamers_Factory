<?php

namespace App\Http\Controllers\Api;

use App\Category;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ControllerHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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
        $categories = Category::withCount('articles')->paginate(5);
        return ControllerHelper::generateResponsedata(true, 'Success', $categories);
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
        $roles = [
            'name' => 'required|string|min:3|max:20',
            'status' => 'required|in:Active,InActive'
        ];
        $validator = Validator::make($request->all(), $roles);
        if (!$validator->fails()) {
            $category = Category::create($request->all());
            if ($category) {
                return ControllerHelper::generateResponse(true, 'Saved Successfully', 201);
            } else {
                return ControllerHelper::generateResponse(false, 'Error Credentials', 400);
            }
        } else {
            return ControllerHelper::generateResponse(false, $validator->getMessageBag()->first(), 200);
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
        $category = Category::with('articles')->find($id);
        if ($category) {
            return ControllerHelper::generateResponsedata(true, 'Success', $category);
        } else {
            return ControllerHelper::generateResponse(false, 'Not Found', 404);
        }
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
        $roles = [
            'name' => 'string|min:3|max:20',
            'status' => 'in:Active,InActive'
        ];
        $validator = Validator::make($request->all(), $roles);
        if (!$validator->fails()) {
            $category = Category::find($id);
            if ($category) {
                $isUpdated = $category->update($request->all());
                if ($isUpdated) {
                    return ControllerHelper::generateResponse(true, 'Updated Successfully', 200);
                } else {
                    return ControllerHelper::generateResponse(false, 'Updated Failed', 400);
                }
            } else {
                return ControllerHelper::generateResponse(false, 'Not Found', 400);
            }
        } else {
            return ControllerHelper::generateResponse(false, $validator->getMessageBag()->first(), 400);
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
        $category = Category::find($id);
        if ($category) {
            $isDelted = $category->delete();
            if ($isDelted) {
                return ControllerHelper::generateResponse(true, 'Deleted Successfully', 200);
            } else {
                return ControllerHelper::generateResponse(false, 'Deleted Failed', 400);
            }
        } else {
            return ControllerHelper::generateResponse(false, 'Not Found', 404);
        }
    }
}
