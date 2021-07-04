<?php

namespace App\Http\Controllers\Cms;

use App\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $admins = Admin::paginate(5);
        return view('admin.admins.index', ['admins' => $admins]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('admin.admins.create');
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
            'name' => 'required|string|min:3|max:10',
            'email' => 'required|email|unique:admins',
            'password' => 'required',
            'gender' => 'required|string|in:Male,Female',
            'birth_date'=>'required|date',
            'status' => 'in:on',
            'image' => 'required|image',
        ]);

        $admin = new Admin();

        $admin->name = $request->get('name');
        $admin->email = $request->get('email');
        $admin->password = Hash::make($request->get('password'));
        $admin->gender = $request->get('gender');
        $admin->birth_date = $request->get('birth_date');
        $admin->status = $request->has('status') ? 'Active' : 'Blocked';

        if ($request->hasFile('image')) {
            $adminImage = $request->file('image');
            $imageName = time() . '_' . $request->get('email') . '.' . $adminImage->getClientOriginalExtension();
            $adminImage->move('storage/images/admin/', $imageName);
            $admin->image = $imageName;
        }

        $isSaved = $admin->save();
        if ($isSaved) {
            toast('Admin Add Successfully', 'success');
            return redirect(route('admins.index'));
        } else {
            toast('Faild to create Admin', 'error');
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
        $admin = Admin::findOrFail($id);
        return view('admin.admins.edit', ['admin' => $admin]);
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
            'id' => 'required|integer|exists:admins',
            'name' => 'required|string|min:3|max:10',
            'email' => 'required|email|unique:admins,email,' . $id,
            'gender' => 'required|string|in:Male,Female',
            'birth_date'=>'required|date',
            'status' => 'in:on',
            'image' => 'image',
        ]);

        $admin = Admin::find($id);

        $admin->name = $request->get('name');
        $admin->email = $request->get('email');
        $admin->gender = $request->get('gender');
        $admin->birth_date = $request->get('birth_date');
        $admin->status = $request->has('status') ? 'Active' : 'Blocked';

        if ($request->hasFile('image')) {
            if (File::exists('storage/images/admin/' . $admin->image)) {
                unlink('storage/images/admin/' . $admin->image);
            }
            $adminImage = $request->file('image');
            $imageName = time() . '_' . $request->get('email') . '.' . $adminImage->getClientOriginalExtension();
            $adminImage->move('storage/images/admin/', $imageName);
            $admin->image = $imageName;
        }

        $isSaved = $admin->save();
        if ($isSaved) {
            toast('Admin Updated Successfully', 'success');
            return redirect(route('admins.index'));
        } else {
            toast('Faild to update Admin', 'error');
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
        $admin = Admin::whereId($id)->first();
        if ($admin->image != 'default.png') {
            if (File::exists('storage/images/admin/' . $admin->image)) {
                unlink('storage/images/admin/' . $admin->image);
            }
        }

        $isDeleted = Admin::destroy($id);
        if ($isDeleted) {
            return response()->json([
                'title' => 'Success',
                'text' => 'Admin Deleted Successfully',
                'icon' => 'success'
            ], 200);
        } else {
            return response()->json([
                'title' => 'Failed',
                'text' => 'Failed to delete admin',
                'icon' => 'error'
            ], 400);
        }
    }
}
