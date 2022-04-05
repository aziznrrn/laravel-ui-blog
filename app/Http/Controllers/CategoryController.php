<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Models\Category;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->ajax()) {
            $data = Category::where('user_id', auth()->user()->id)->with(['user'])->latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row) {
                    return '
                        <div class="btn-group" role="group" aria-label="action button">
                            <a data-id="'.$row->id.'" class="btn-show btn btn-sm btn-outline-primary"
                               title="show article">
                                <i class="fa-regular fa-eye"></i>
                            </a>
                            <a data-id="'.$row->id.'" class="btn-edit btn btn-sm btn-outline-warning"
                               title="edit article">
                                <i class="fa-regular fa-pen-to-square"></i>
                            </a>
                            <a data-id="'.$row->id.'" class="btn-delete btn btn-sm btn-outline-danger"
                               title="delete article">
                                <i class="fa-regular fa-trash-can"></i>
                            </a>
                        </div>
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
            'name' => 'required|unique:categories|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 401);
        }

        $validated = $validator->validated();

        try {
            Category::where('user_id', auth()->user()->id)->create(array_merge($validated, ['user_id' => auth()->user()->id]));
            return response()->json(['message' => 'Category has been created'], 200);
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
        $article = Category::where('user_id', auth()->user()->id)->where('id', $id)->first();

        if(!$article) {
            return response()->json(['message' => 'Category not found'], 404);
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
        $article = Category::where('user_id', auth()->user()->id)->where('id', $id)->first();

        if(!$article) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:categories|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 401);
        }

        $validated = $validator->validated();

        try {
            $article->update(array_merge($validated, ['user_id' => auth()->user()->id]));
            return response()->json(['message' => 'Category has been updated'], 200);
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
        $article = Category::where('user_id', auth()->user()->id)->where('id', $id)->first();

        if(!$article) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        try {
            $article->delete();
            return response()->json(['message' => 'Category has been deleted'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Get all categories
     *
     * @return \Illuminate\Http\Response
     */
    public function list() {
        $categories = Category::latest()->pluck('name', 'id');
        return response()->json($categories, 200);
    }
}
