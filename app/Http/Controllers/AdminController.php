<?php

namespace App\Http\Controllers;

use App\Models\Blog;

class AdminController extends Controller
{
    public function display()
    {
        //admin can see blog of every user
        $blogs = Blog::with(['users:id,name', 'deletedBy:id,name', 'parentCategory:id,name', 'childCategory:id,name'])
            ->where('isdeleted', false)
            ->where('draft', false)
            ->where('publish', true)
            ->paginate(20);
        $returnData = [];

        foreach ($blogs as $blog) {
            $returnData[] = [
                'id' => $blog->id,
                'title' => $blog->title,
                'description' => $blog->description,
                'photo' => $blog->photo,
                'category' => $blog->parentCategory->name ?? '',
                'sub_category' => $blog->childCategory->name ?? '',
                'tag' => $blog->tag ?? '',
                'created_at' => $blog->created_at,
                'created_by' => $blog->users->name,
                'is_deleted' => $blog->isdeleted ? true : false,
                'seo' => [
                    'meta.name' => $blog->title,
                    'meta.desc' => $blog->description,
                    'meta.robots' => 'noindex, nofollow',
                ],

            ];
        }

        return response()->json([
            'status' => true,
            'message' => 'Blog fetched successfully',
            'data' => $returnData,
        ]);
    }

    public function destroy(string $id)
    {

        $isBlogExist = Blog::where('id', $id)->with('deletedBy:id,name')->get()[0];

        if (! $isBlogExist) {
            return response()->json([
                'status' => false,
                'message' => 'Blog not found',

            ]);
        }

        if ($isBlogExist->trashed()) {
            return response()->json([
                'status' => false,
                'message' => 'Blog already deleted',
            ]);
        }

        $deletedBy = $isBlogExist->deletedBy->name ?? 'Admin';
        // $isDeleted = $isBlogExist->delete();
        $isBlogExist->delete();

        // if ($isDeleted)
        return response()->json([
            'status' => true,
            'message' => 'Blog deleted successfully',
            'deletedBy' => "Blog is deleted by admin $deletedBy",
        ]);
    }

    public function restore(int $blog_id)
    {
        $blog = Blog::withTrashed()->find($blog_id);

        if (! $blog) {
            return response()->json([
                'status' => false,
                'message' => 'Blog not found',
            ]);
        }

        if (! $blog->trashed()) {
            return response()->json([
                'status' => false,
                'message' => 'Blog is not deleted',
            ]);
        }

        $blog->restore();

        return response()->json([
            'status' => true,
            'message' => 'Blog restored successfully',
        ]);
    }

    public function forceDelete($id)
    {
        // Find the blog including soft deleted ones
        $blog = Blog::withTrashed()->find($id);

        if (! $blog) {
            return response()->json([
                'status' => false,
                'message' => 'Blog not found',
            ], 404);
        }

        if (! $blog->trashed()) {
            return response()->json([
                'status' => false,
                'message' => 'Blog is not deleted',
            ], 404);
        }

        $deletedBy = $blog->deletedBy->name ?? 'Admin';

        $blog->forceDelete();

        return response()->json([
            'status' => true,
            'message' => 'Blog permanently deleted',
            'deletedBy' => "Blog was permanently deleted by $deletedBy",
        ], 200);
    }

    public function displaySpecificBlog(string $id)
    {
        $blog = Blog::find($id);
        if (! $blog) {
            return response()->json([
                'status' => false,
                'message' => 'Blog not found',
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => 'Blog fetched successfully',
            'data' => $blog,
            'seo' => [
                'title' => $blog->title,
                'description' => $blog->description,
                'meta.robots' => 'noindex, nofollow',
            ],
        ]);
    }
}
