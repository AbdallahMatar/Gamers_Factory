<?php

namespace App\Http\Controllers\Api;

use App\Author;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ControllerHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        //
        // $authers = Auther::paginate(20)->makeHidden(['password'])->latest();
        $authors = Author::paginate(20);
        return ControllerHelper::generateResponsedata(true, 'Success', $authors);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $roles = [
            'name' => 'required|string|min:3|max:10',
            'email' => 'required|email|unique:admins',
            'password' => 'required|min:3',
            'gender' => 'required|string|in:Male,Female',
            'birth_date' => 'required|date',
            'status' => 'required|in:Active,InActive',
            'image' => 'nullable|image',
        ];
        $validator = Validator::make($request->all(), $roles);
        if (!$validator->fails()) {
            $request_data = $request->except(['password', 'password_confirmation', 'image']);
            $request_data['password'] = Hash::make($request->password);

            if ($request->hasFile('image')) {
                $authorImage = $request->file('image');
                $imageName = time() . '_' . $request->get('email') . '.' . $authorImage->getClientOriginalExtension();
                $authorImage->move('images/author/', $imageName);
                $request_data['image'] = $imageName;
            }

            $author = Author::create($request_data);
            if ($author) {
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
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $author = Author::find($id);
        if ($author) {
            return ControllerHelper::generateResponsedata(true, 'Success', $author);
        } else {
            return ControllerHelper::generateResponse(false, 'Not Found', 404);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $roles = [
            'name' => 'string|min:3|max:10',
            'email' => 'email|unique:admins,email,' . $id,
            'gender' => 'string|in:Male,Female',
            'birth_date' => 'date',
            'status' => 'in:Active,InActive',
            'image' => 'image',
        ];
        $validator = Validator::make($request->all(), $roles);
        if (!$validator->fails()) {
            $author = Author::find($id);
            if ($author) {
                $request_data = $request->except(['password', 'password_confirmation', 'image']);

                if ($request->hasFile('image')) {
                    if (File::exists('images/auther/' . $author->image)) {
                        unlink('images/auther/' . $author->image);
                    }
                    $autherImage = $request->file('image');
                    $imageName = time() . '_' . $author->name . '.' . $autherImage->getClientOriginalExtension();
                    $autherImage->move('images/auther/', $imageName);
                    $request_data['image'] = $imageName;
                }

                $isUpdated = $author->update($request->all());
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
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $author = Author::find($id);
        if ($author) {
            if ($author->image != 'default.png') {
                if (File::exists('images/auther/' . $author->image)) {
                    unlink('images/auther/' . $author->image);
                }
            }

            $isDelted = $author->delete();
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
