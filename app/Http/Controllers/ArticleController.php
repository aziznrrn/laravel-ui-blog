<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Models\Article;
use Illuminate\Support\Facades\Validator;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->ajax()) {
            $data = Article::where('user_id', auth()->user()->id)->with(['category', 'user'])->latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row) {
                    return '
                        <a href="'.route('posts.show', $row->id).'" class="btn-show btn btn-sm border-2 btn-outline-primary"
                            title="show article">
                            <i class="fa-regular fa-eye"></i>
                        </a>
                        <a data-id="'.$row->id.'" class="btn-edit btn btn-sm border-2 btn-outline-warning"
                            title="edit article">
                            <i class="fa-regular fa-pen-to-square"></i>
                        </a>
                        <a data-id="'.$row->id.'" class="btn-delete btn btn-sm border-2 btn-outline-danger"
                            title="delete article">
                            <i class="fa-regular fa-trash-can"></i>
                        </a>
                    ';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 401);
        }

        $validated = $validator->validated();

        try {
            Article::where('user_id', auth()->user()->id)->create(array_merge($validated, ['user_id' => auth()->user()->id]));
            return response()->json(['message' => 'Article has been created'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
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
        $article = Article::where('user_id', auth()->user()->id)->where('id', $id)->with(['category'])->first();

        if(!$article) {
            return response()->json(['message' => 'Article not found'], 404);
        }

        return response()->json($article, 200);
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
        $article = Article::where('user_id', auth()->user()->id)->where('id', $id)->first();

        if(!$article) {
            return response()->json(['message' => 'Article not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 401);
        }

        $validated = $validator->validated();

        try {
            $article->update(array_merge($validated, ['user_id' => auth()->user()->id]));
            return response()->json(['message' => 'Article has been updated'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
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
        $article = Article::where('user_id', auth()->user()->id)->where('id', $id)->first();

        if(!$article) {
            return response()->json(['message' => 'Article not found'], 404);
        }

        try {
            $article->delete();
            return response()->json(['message' => 'Article has been deleted'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
