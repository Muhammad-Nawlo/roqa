<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;

class MenuController extends Controller
{

    public function index()
    {
        $menu = Menu::OrderBy('menus.id', 'DESC')->get();
        return response()->json([
            'menu' => $menu
        ], 200);
    }

    public function getMenuById($id)
    {
        $menu = Menu::where('category_id', '=', $id)
        ->OrderBy('menus.id', 'DESC')->get();
        return response()->json([
            'menu' => $menu
        ], 200);
    }

    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'price' => ['required', 'integer'],
            'category_id' => ['required', 'integer'],
            'menu_image' => ['required', 'image', 'mimes:jpg,png,jpeg,gif,svg'],
            'titleAr' => ['required', 'string'],
            'titleEn' => ['required', 'string'],
            'descAr' => ['required', 'string'],
            'descEn' => ['required', 'string'],
        ]);
        if ($validator->fails()) {
            return new JsonResponse(['success' => false, 'message' => $validator->errors()], 422);
        }

        $data['price'] =  $request['price'];
        $data['ar']['title'] = $request['titleAr'];
        $data['en']['title'] = $request['titleEn'];
        $data['ar']['desc'] = $request['descAr'];
        $data['en']['desc'] = $request['descEn'];
        $data['category_id'] =  $request['category_id'];

        if ($request->file('menu_image')) {
            $filename = date('YmdHi') . $request->file('menu_image')->getClientOriginalName();
            $request->file('menu_image')->move(public_path('menu'), $filename);
            $data['menu_image'] =  strip_tags('menu/' . $filename);
        }

        $menu = Menu::create($data);


        return response()->json([
            'message' => 'Successful created menu.'
        ], 200);
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
            'price' => ['nullable', 'integer'],
            'category_id' => ['nullable', 'integer'],
            'menu_image' => ['nullable', 'image', 'mimes:jpg,png,jpeg,gif,svg'],
            'titleAr' => ['nullable', 'string'],
            'titleEn' => ['nullable', 'string'],
            'descAr' => ['nullable', 'string'],
            'descEn' => ['nullable', 'string'],
        ]);
        if ($validator->fails()) {
            return new JsonResponse(['success' => false, 'message' => $validator->errors()], 422);
        }
        $menu = Menu::find($id);
        $data['price'] =  $request['price'];
        $data['ar']['title'] = $request['titleAr'];
        $data['en']['title'] = $request['titleEn'];
        $data['ar']['desc'] = $request['descAr'];
        $data['en']['desc'] = $request['descEn'];
        $data['category_id'] =  $request['category_id'];

        if ($request->file('menu_image')) {
            if ($menu['menu_image'] != null) {
                $image_path = public_path($menu['menu_image']);
                unlink($image_path);
            }

            $filename = date('YmdHi') . $request->file('menu_image')->getClientOriginalName();
            $request->file('menu_image')->move(public_path('menu'), $filename);
            $data['menu_image'] =  strip_tags('menu/' . $filename);
        }


        $menu->update($data);


        return response()->json([
            'message' => 'Successful updated menu.'
        ], 200);
    }

    public function destroy(string $id)
    {
        $menu = Menu::find($id);
        if (!$menu) {
            return response()->json([
                'message' => "menu not found"
            ], 404);
        }
        if ($menu['menu_image'] != null) {
            $image_path = public_path($menu['menu_image']);
            unlink($image_path);
        }
        $menu->delete();
        return response()->json([
            'message' => "menu successfully deleted."
        ], 200);
    }
}
