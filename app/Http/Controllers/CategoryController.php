<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    public function index()
    {
        $category = Category::OrderBy('categories.id', 'DESC')->get();
        return response()->json([
            'category' => $category
        ], 200);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        // foreach (config('app.languages') as $key => $value) {
        //     $data[$key . '*.category_name'] = 'required|string';
        // }
        // $validatedData = $request->validate($data);
        // $category = new Category();
        $validator = Validator::make($request->all(), [
            'titleAr' => ['required', 'string'],
            'titleEn' => ['required', 'string'],
        ]);
        if ($validator->fails()) {
            return new JsonResponse(['success' => false, 'message' => $validator->errors()], 422);
        }
        $data['ar']['category_name'] = $request['titleAr'];
        $data['en']['category_name'] = $request['titleEn'];
        $category = Category::create($data);


        return response()->json([
            'message' => 'Successful created category.'
        ], 201);
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        //
    }


    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'titleAr' => ['required', 'string'],
            'titleEn' => ['required', 'string'],
        ]);
        if ($validator->fails()) {
            return new JsonResponse(['success' => false, 'message' => $validator->errors()], 422);
        }
        $data['ar']['category_name'] = $request['titleAr'];
        $data['en']['category_name'] = $request['titleEn'];
        $category = Category::find($id);
        $category->update($data);
        // $category->save();
        return response()->json([
            'message' => 'Successful updated category.'
        ], 200);
    }

    public function destroy(string $id)
    {
        $category = Category::find($id);
        if (!$category) {
            return response()->json([
                'message' => "category not found"
            ], 404);
        }

        $category->delete();
        return response()->json([
            'message' => "category successfully deleted."
        ], 200);
    }
}
