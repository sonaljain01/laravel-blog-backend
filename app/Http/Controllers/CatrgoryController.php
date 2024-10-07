<?php

namespace App\Http\Controllers;

use App\Http\Requests\ParentCatrgoryRequest;
use App\Http\Requests\ParentCatrgoryUpdateRequest;
use App\Models\ChildCategory;
use App\Models\ParentCategory;
use Storage;

class CatrgoryController extends Controller
{
    public function store(ParentCatrgoryRequest $request)
    {
        $data = [
            'name' => $request->name,
            'image' => $request->hasFile('image') ? $this->uploadImage($request->file('image')) : null,
            'created_by' => auth()->user()->id,
        ];
        $isSave = ParentCategory::create($data);
        if ($isSave) {
            return response()->json([
                'status' => true,
                'message' => 'Category created successfully',
            ], 200);
        }

        return response()->json([
            'status' => false,
            'message' => 'Unable to create category',
        ], 500);
    }

    public function destroy(int $id)
    {
        if (! auth()->user()->type === 'admin') {
            $this->err = 'You need to be admin to create category';

            return response()->json([
                'status' => false,
                'message' => 'You need to be admin to delete category',
            ], 500);
        }
        $isDelete = ParentCategory::find($id)->delete();
        if ($isDelete) {
            ChildCategory::where('category_id', $id)->delete();

            return response()->json([
                'status' => true,
                'message' => 'Category deleted successfully',
            ], 200);
        }

        return response()->json([
            'status' => false,
            'message' => 'Unable to delete category',
        ], 500);
    }

    public function update(ParentCatrgoryUpdateRequest $request, int $id)
    {
        $isUpdate = ParentCategory::find($id)->update($request->all());
        if ($isUpdate) {
            return response()->json([
                'status' => true,
                'message' => 'Category updated successfully',
            ], 200);
        }

        return response()->json([
            'status' => false,
            'message' => 'Unable to update category',
        ], 500);
    }

    public function display()
    {
        $category = ParentCategory::all();

        if ($category->count() == 0) {
            return response()->json([
                'status' => false,
                'message' => 'Category not found',
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => 'Category fetched successfully',
            'data' => $category,
        ]);
    }

    protected function uploadImage($file)
    {
        $uploadFolder = 'category-image';
        $image = $file;
        $image_uploaded_path = $image->store($uploadFolder, 'public');
        $uploadedImageUrl = Storage::disk('public')->url($image_uploaded_path);

        return $uploadedImageUrl;
    }
}
