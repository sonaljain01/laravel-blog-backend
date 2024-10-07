<?php

namespace App\Http\Controllers;

use App\Http\Requests\TagRequest;
use App\Models\Tags;

class TagController extends Controller
{
    public function display()
    {
        $tags = Tags::all();

        return response()->json([
            'status' => true,
            'data' => $tags,
        ], 200);
    }

    public function store(TagRequest $request)
    {
        $data = [
            'name' => $request->name,
            'created_by' => auth()->user()->id,
        ];
        $isSave = Tags::create($data);
        if ($isSave) {
            return response()->json([
                'status' => true,
                'message' => 'Tag created successfully',
            ], 200);
        }

        return response()->json([
            'status' => false,
            'message' => 'Unable to create tag',
        ], 500);
    }

    public function destroy($id)
    {
        $tag = Tags::find($id);
        if ($tag) {
            $tag->delete();

            return response()->json([
                'status' => true,
                'message' => 'Tag deleted successfully',
            ], 200);
        }

        return response()->json([
            'status' => false,
            'message' => 'Tag not found',
        ], 500);
    }

    public function update($id, TagRequest $request)
    {
        $tag = Tags::find($id);
        if ($tag) {
            $data = [
                'name' => $request->name,
            ];
            $tag->update($data);

            return response()->json([
                'status' => true,
                'message' => 'Tag updated successfully',
            ], 200);
        }

        return response()->json([
            'status' => false,
            'message' => 'Tag not found',
        ], 500);
    }
}
