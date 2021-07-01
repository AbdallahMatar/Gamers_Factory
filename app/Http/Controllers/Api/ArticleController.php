<?php

namespace App\Http\Controllers\Api;

use App\Article;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ControllerHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
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
        $articles = Article::with(['category', 'author'])->paginate(10);
        return ControllerHelper::generateResponsedata(true, 'Success', $articles);
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
            'title' => 'required|string|min:3|max:40',
            'description' => 'required|string|min:20|max:500',
            'category_id' => 'required|exists:categories,id|integer',
            'author_id' => 'required|exists:authors,id|integer',
            'status' => 'string|in:Active,InActive',
            'image' => 'required|image'
        ];
        $validator = Validator::make($request->all(), $roles);
        if (!$validator->fails()) {
            $article = new Article();
            $article->title = $request->title;
            $article->description = $request->description;
            $article->category_id = $request->category_id;
            $article->author_id = $request->author_id;
            $article->status = $request->status;

            if ($request->hasFile('image')) {
                $articlePath = $request->file('image');
                $articleName = time() . '_' . Str::random(5) . '.' . $articlePath->getClientOriginalExtension();

                $path = $request->file('image')->storeAs('images/article/', $articleName, 'public');
                $article->image = '/storage/' . $path;
                // $article->image = $request->image->store('images', 'public');
            }

            $isSaved = $article->save();
            if ($isSaved) {
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
        $article = Article::with(['category', 'author'])->find($id);
        if ($article) {
            return ControllerHelper::generateResponsedata(true, 'Success', $article);
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
            'title' => 'string|min:3|max:40',
            'description' => 'string|min:20|max:500',
            'category_id' => 'exists:categories,id|integer',
            'author_id' => 'exists:authors,id|integer',
            'status' => 'in:Active,InActive',
            'image' => 'image'
        ];
        $validator = Validator::make($request->all(), $roles);
        if (!$validator->fails()) {
            $article = Article::find($id);
            if ($article) {
                $request_data = $request->except('image');

                if ($request->hasFile('image')) {
                    if (File::exists('images/article/' . $article->image)) {
                        unlink('images/article/' . $article->image);
                    }
                    $articleImage = $request->file('image');
                    $imageName = time() . '_' . Str::random(5) . '.' . $articleImage->getClientOriginalExtension();
                    $articleImage->move('images/article/', $imageName);
                    $request_data['image'] = $imageName;
                }

                $isUpdated = $article->update($request->all());
                if ($isUpdated) {
                    return ControllerHelper::generateResponse(true, 'Updated Successfully', 200);
                } else {
                    return ControllerHelper::generateResponse(false, 'Updated Failed', 400);
                }
            } else {
                return ControllerHelper::generateResponse(false, 'Not Found', 404);
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
        $article = Article::find($id);
        if ($article) {
            // if ($article->image != 'default.png') {
            //     // dd($article->image);
            //     if (File::exists('images/article/' . $article->image)) {
            //         unlink('images/article/' . $article->image);
            //     }
            // }
            // Storage::disk('public')->delete($article->image);
            Storage::delete( public_path('/images/article' . $article->image));

            $isDelted = $article->delete();
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
