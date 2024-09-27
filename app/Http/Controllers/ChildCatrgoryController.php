<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChildCatrgoryRequest;
use App\Http\Requests\ChildCatrgoryUpdateRequest;
use App\Models\ChildCategory;

class ChildCatrgoryController extends Controller
{
    public function store(ChildCatrgoryRequest $request)
    {
        $data = [
            "name" => $request->name,
            "category_id" => $request->category_id,
            "created_by" => auth()->user()->id,
        ];

        $isSave = ChildCategory::create($data);
        if ($isSave) {
            return response()->json([
                "status" => true,
                "message" => "Child category created successfully",
            ], 200);
        }
        return response()->json([
            "status" => false,
            "message" => "Child category creation failed",
        ], 500);
    }

    public function update(ChildCatrgoryUpdateRequest $request, $id)
    {
        $data = [
            "name" => $request->name,
            "category_id" => $request->category_id,
            "updated_by" => auth()->user()->id,
        ];
        $isUpdate = ChildCategory::where("id", $id)->update($data);
        if ($isUpdate) {
            return response()->json([
                "status" => true,
                "message" => "Child category updated successfully",
            ], 200);
        }
        return response()->json([
            "status" => false,
            "message" => "Child category update failed",
        ], 500);
    }

    public function destroy($id)
    {
        $isDelete = ChildCategory::where("id", $id)->delete();
        if ($isDelete) {
            return response()->json([
                "status" => true,
                "message" => "Child category deleted successfully",
            ], 200);
        }
        return response()->json([
            "status" => false,
            "message" => "Child category delete failed",
        ], 500);
    }

    public function display(string $id)
    {
        $category = ChildCategory::where("category_id", $id)->get();
        if ($category->count() == 0) {
            return response()->json([
                "status" => false,
                "message" => "Child category not found",
            ]);
        }
        return response()->json([
            "status" => true,
            "message" => "Child category fetched successfully",
            "data" => $category
        ]);
    }
}
