<?php

namespace App\Http\Controllers;

use App\Http\Resources\GalleryResource;
use App\Models\Gallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;

class GalleryController extends Controller
{

    public function index()
    {
        $gallery =  Gallery::OrderBy('galleries.id', 'DESC')->get();
        // $gallery = Gallery::all();
        // print_r($gallery);
        // return '';
        // $gallery = GalleryResource::make($gallery);
        // Return Json Response
        return response()->json([
            'gallery' => $gallery
        ], 200);
    }


    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'gallery_image' => ['required', 'image', 'mimes:jpg,png,jpeg,gif,svg'],
        ]);
        if ($validator->fails()) {
            return new JsonResponse(['success' => false, 'message' => $validator->errors()], 422);
        }

        $gallery = new Gallery();
        if ($request->file('gallery_image')) {
            $filename = date('YmdHi') . $request->file('gallery_image')->getClientOriginalName();
            $request->file('gallery_image')->move(public_path('gallery'), $filename);
            $gallery['gallery_image'] = strip_tags('gallery/' . $filename);
        }
        $gallery->save();
        return response()->json([
            'message' => 'Successful created gallery.'
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
        //
    }


    public function destroy(string $id)
    {
        $gallery = Gallery::find($id);
        if (!$gallery) {
            return response()->json([
                'message' => "gallery not found"
            ], 404);
        }
        if ($gallery['gallery_image'] != null) {
            $image_path = public_path($gallery['gallery_image']);
            unlink($image_path);
        }
        $gallery->delete();
        return response()->json([
            'message' => "gallery successfully deleted."
        ], 200);
    }
}
