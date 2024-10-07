<?php

namespace App\Http\Controllers;

use App\Http\Requests\RatingRequest;
use App\Models\Rating;

class RatingController extends Controller
{
    public function display(string $id)
    {
        $rating = Rating::where('blog_id', $id)->with('users:id,name')->get();

        if (count($rating) == 0) {
            return response()->json([
                'status' => false,
                'message' => 'Rating not found',
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Rating found successfully',
            'data' => $rating,
        ], 200);
    }

    public function store(RatingRequest $request)
    {
        $fillData = [
            'blog_id' => $request->blog_id,
            'rating' => $request->rating,
            'user_id' => auth()->user()->id,
        ];
        $rating = Rating::create($fillData);
        if (! $rating) {
            return response()->json([
                'status' => false,
                'message' => 'Unable to create rating',
            ], 500);
        }

        return response()->json([
            'status' => true,
            'message' => 'Rating created successfully',
            'data' => $rating,
        ], 200);
    }

    public function displayavgRating(string $id)
    {
        $avg = 0;
        $rating = Rating::where('blog_id', $id)->with('users:id,name')->get();
        if (! count($rating) == 0) {
            foreach ($rating as $key => $value) {
                $avg += $value->rating;
            }
            $avg = $avg / count($rating);

            return response()->json([
                'status' => true,
                'message' => 'Rating found successfully',
                'data' => $avg,
            ], 200);
        }

        return response()->json([
            'status' => false,
            'message' => 'Rating not found',
            'data' => $avg,
        ], 404);
    }
}
