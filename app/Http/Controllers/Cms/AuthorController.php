<?php

namespace App\Http\Controllers\Cms;

use App\Author;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;

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
        $authors = Author::paginate(5);
        return view('admin.author.index', ['authors' => $authors]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('admin.author.create');
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
            'birth_date' => 'required|date',
            'status' => 'in:on',
            'image' => 'required|image',
        ]);

        $author = new Author();

        $author->name = $request->get('name');
        $author->email = $request->get('email');
        $author->password = Hash::make($request->get('password'));
        $author->gender = $request->get('gender');
        $author->birth_date = $request->get('birth_date');
        $author->status = $request->has('status') ? 'Active' : 'Blocked';

        if ($request->hasFile('image')) {
            $authorImage = $request->file('image');
            $imageName = time() . '_' . $request->get('email') . '.' . $authorImage->getClientOriginalExtension();
            $authorImage->move('storage/images/author/', $imageName);
            $author->image = $imageName;
        }

        $isSaved = $author->save();
        if ($isSaved) {
            toast('Author Add Successfully', 'success');
            return redirect(route('authors.index'));
        } else {
            toast('Faild to create Author', 'error');
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
        $author = Author::findOrFail($id);
        return view('admin.author.edit', ['author' => $author]);
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
            'birth_date' => 'required|date',
            'status' => 'in:on',
            'image' => 'image',
        ]);

        $author = Author::find($id);

        $author->name = $request->get('name');
        $author->email = $request->get('email');
        $author->gender = $request->get('gender');
        $author->birth_date = $request->get('birth_date');
        $author->status = $request->has('status') ? 'Active' : 'Blocked';

        if ($request->hasFile('image')) {
            if (File::exists('storage/images/author/' . $author->image)) {
                unlink('storage/images/author/' . $author->image);
            }
            $authorImage = $request->file('image');
            $imageName = time() . '_' . $request->get('email') . '.' . $authorImage->getClientOriginalExtension();
            $authorImage->move('storage/images/author/', $imageName);
            $author->image = $imageName;
        }

        $isSaved = $author->save();
        if ($isSaved) {
            toast('Author Updated Successfully', 'success');
            return redirect(route('authors.index'));
        } else {
            toast('Faild to update Author', 'error');
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
        $author = Author::whereId($id)->first();
        if ($author->image != 'default.png') {
            if (File::exists('storage/images/author/' . $author->image)) {
                unlink('storage/images/author/' . $author->image);
            }
        }

        $isDeleted = Author::destroy($id);
        if ($isDeleted) {
            return response()->json([
                'title' => 'Success',
                'text' => 'Author Deleted Successfully',
                'icon' => 'success'
            ], 200);
        } else {
            return response()->json([
                'title' => 'Failed',
                'text' => 'Failed to delete Author',
                'icon' => 'error'
            ], 400);
        }
    }
}
