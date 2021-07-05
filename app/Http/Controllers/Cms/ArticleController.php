<?php

namespace App\Http\Controllers\Cms;

use App\Article;
use App\Author;
use App\Category;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $articles = Article::withCount(['category', 'author'])->paginate(10);

        return view('admin.article.index', ['articles' => $articles]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $authors = Author::where('status', 'Active')->get();
        $categories = Category::where('status', 'Active')->get();
        return view('admin.article.create', ['authors' => $authors, 'categories' => $categories]);
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
            'title' => 'required|string|min:3|max:40',
            'description' => 'required|string|min:20|max:500',
            'category_id' => 'required|exists:categories,id|integer',
            'author_id' => 'required|exists:authors,id|integer',
            'status' => 'string|in:Active,InActive',
            'image' => 'required|image'
        ]);

        $article = new Article();

        $article->name = $request->get('title');
        $article->email = $request->get('description');
        $article->gender = $request->get('category_id');
        $article->birth_date = $request->get('author_id');
        $article->status = $request->has('status') ? 'Active' : 'Blocked';

        if ($request->hasFile('image')) {
            $articleImage = $request->file('image');
            $imageName = time() . '_' . Str::random(5) . '.' . $articleImage->getClientOriginalExtension();
            $articleImage->move('storage/images/article/', $imageName);
            $article->image = $imageName;
        }

        $isSaved = $article->save();
        if ($isSaved) {
            toast('Article Add Successfully', 'success');
            return redirect(route('articles.index'));
        } else {
            toast('Faild to create Article', 'error');
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
            'birth_date' => 'required|date',
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
