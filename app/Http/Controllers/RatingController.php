<?php

namespace App\Http\Controllers;

use App\Http\Requests\RatingRequest;
use App\Models\Rating;


class RatingController extends Controller
{
    public function display(string $id)
    {
        // $rating = Rating::with(['blog:id,name', 'users:id,name'])->find($id);
        $rating = Rating::where('blog_id', $id)->with('users:id,name')->get();

        if (count($rating) == 0) {
            return response()->json([
                "status" => false,
                "message" => "Rating not found",
            ]);
        }

        return response()->json([
            "status" => true,
            "message" => "Rating found successfully",
            "data" => $rating
        ]);
    }
    public function store(RatingRequest $request)
    {
        $rating = Rating::create($request->all());
        if (!$rating) {
            return response()->json([
                "status" => false,
                "message" => "Unable to create rating"
            ]);
        }

        return response()->json([
            "status" => true,
            "message" => "Rating created successfully",
            "data" => $rating
        ]);
    }

    public function displayavgRating(string $id)
    {
    }
}
