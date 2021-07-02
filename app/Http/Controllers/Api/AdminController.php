<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ControllerHelper;
use App\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use function PHPUnit\Framework\isNull;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Add permsion
        $admins = Admin::paginate(20);
        return ControllerHelper::generateResponsedata(true, 'Success', $admins);
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
            $admin = new Admin();
            $admin->name = $request->get('name');
            $admin->email = $request->get('email');
            $admin->password = Hash::make($request->get('password'));
            $admin->gender = $request->get('gender');
            $admin->birth_date = $request->get('birth_date');
            $admin->status = $request->get('status');

            if ($request->hasFile('image')) {
                $adminImage = $request->file('image');
                $imageName = time() . '_' . $request->get('email') . '.' . $adminImage->getClientOriginalExtension();
                $adminImage->move('storage/images/admin/', $imageName);
                $admin->image = $imageName;
            }

            $isSaved = $admin->save();
            if ($isSaved) {
                return ControllerHelper::generateResponse(true, 'Saved Susseccfully', 201);
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
        $admin = Admin::find($id);
        if ($admin) {
            return ControllerHelper::generateResponsedata(true, 'Success', $admin);
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
            'name' => 'string|min:3|max:10',
            'email' => 'email|unique:admins,email,' . $id,
            'gender' => 'string|in:Male,Female',
            'birth_date' => 'date',
            'status' => 'in:Active,InActive',
            'image' => 'image',
        ];
        $validator = Validator::make($request->all(), $roles);
        if (!$validator->fails()) {
            $admin = Admin::find($id);
            if ($admin) {
                $request_data = $request->except(['password', 'password_confirmation', 'image']);

                if ($request->hasFile('image')) {
                    if (File::exists('storage/images/admin/' . $admin->image)) {
                        unlink('storage/images/admin/' . $admin->image);
                    }
                    $adminImage = $request->file('image');
                    $imageName = time() . '_' . $request->get('email') . '.' . $adminImage->getClientOriginalExtension();
                    $adminImage->move('storage/images/admin/', $imageName);
                    $request_data['image'] = $imageName;
                }

                $isUpdated = $admin->update($request->all());
                if ($isUpdated) {
                    return ControllerHelper::generateResponse(true, 'Updated Successfully', 200);
                } else {
                    return ControllerHelper::generateResponse(false, 'Updated Failed', 400);
                }
            } else {
                return ControllerHelper::generateResponse(false, 'Not Found', 404);
            }
        } else {
            return ControllerHelper::generateResponse(false, $validator->getMessageBag()->first(), 200);
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
        $admin = Admin::find($id);
        if ($admin) {
            if ($admin->image != 'default.png') {
                if (File::exists('storage/images/admin/' . $admin->image)) {
                    unlink('storage/images/admin/' . $admin->image);
                }
            }

            $isDeleted = $admin->delete();
            if ($isDeleted) {
                return ControllerHelper::generateResponse(true, 'Deleted Successfully', 200);
            } else {
                return ControllerHelper::generateResponse(false, 'Deleted Failed', 400);
            }
        } else {
            return ControllerHelper::generateResponse(false, 'Not Found', 404);
        }
    }
}
