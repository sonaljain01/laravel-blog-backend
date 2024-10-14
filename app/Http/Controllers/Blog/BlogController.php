<?php

namespace App\Http\Controllers\Blog;

use App\Http\Controllers\Controller;
use App\Http\Requests\BlogStoreRequest;
use App\Http\Requests\BlogUpdateRequest;
use App\Http\Requests\SeoMetaRequest;
use App\Models\Blog;
use Storage;
use Str;

class BlogController extends Controller
{
    public function display()
    {
        $blogs = Blog::where('isdeleted', false)
            ->where('type', 'publish')
            ->with(['users:id,name', 'deletedBy:id,name', 'parentCategory:id,name', 'childCategory:id,name', 'seoMeta'])
            ->paginate(10);

        // $returnData = [];
        $returnData = $blogs->map(function ($blog) {
            $seoMeta = $blog->seoMeta;

            return [
                'id' => $blog->id,
                'slug' => $blog->slug,
                'title' => $blog->title,
                'description' => $blog->description,
                'photo' => $blog->photo,
                'category' => $blog->parentCategory->name ?? '',
                'sub_category' => $blog->childCategory->name ?? '',
                'tag' => $blog->tag ?? '',
                'created_at' => $blog->created_at,
                'created_by' => $blog->users->name,
                'is_deleted' => $blog->isdeleted,
                'seo' => [
                    'meta_title' => $seoMeta->meta_name ?? $blog->title,
                    'meta_description' => $seoMeta->meta_description ?? $blog->description,
                ],

            ];
        });
        $pagination = [
            'next_page_url' => $blogs->nextPageUrl(),
            'previous_page_url' => $blogs->previousPageUrl(),
            'total' => $blogs->total(),
        ];

        return response()->json([
            'status' => true,
            'message' => 'Blog fetched successfully',
            'data' => $returnData,
            'pagination' => $pagination,
        ], 200);

    }

    public function store(BlogStoreRequest $request, SeoMetaRequest $seoRequest)
    {
        $slug = null;
        if ($request->slug) {
            $isBlogExist = Blog::where('slug', $request->slug)->first();
            if ($isBlogExist) {
                return response()->json([
                    'status' => false,
                    'message' => 'Blog with this slug already exist',
                ]);
            } else {
                $slug = $request->slug;
            }
        } else {
            $slug = $this->slug($request->title);
        }
        $filldata = [
            'user_id' => auth()->user()->id,
            'title' => $request->title,
            'description' => $request->description,
            'photo' => $request->file('image') ? $this->uploadImage($request->file('image')) : null,
            'parent_category' => $request->category,
            'tag' => $request->tag,
            'child_category' => $request->sub_category,
            'slug' => $slug,
            'type' => $request->type,
        ];

        $sendData = [
            'subject' => 'New blog created',
            'title' => $request->title,
            'description' => $request->description,
        ];

        $blog = Blog::create($filldata);

        $blog->seoMeta()->create([
            'meta_title' => $seoRequest->meta_title ?? $blog->title,
            'meta_description' => $seoRequest->meta_description ?? $blog->description,
        ]);
        // Http::post("https://connect.pabbly.com/workflow/sendwebhookdata/IjU3NjYwNTZkMDYzNTA0MzI1MjZlNTUzMDUxMzQi_pc", $sendData);

        if ($request->type === 'draft') {
            return response()->json([
                'status' => true,
                'message' => 'Draft created successfully',
            ], 200);
        }

        return response()->json([
            'status' => true,
            'message' => 'Blog and SEO created successfully',
            'data' => $blog,
        ], 200);
    }

    public function update(BlogUpdateRequest $request, string $slug, SeoMetaRequest $seoRequest)
    {
        $blog = Blog::where('slug', $slug)->where('user_id', auth()->user()->id)->first();
        if (! $blog) {
            $this->error = 'You are not allowed to update other person blog';

            return false;
        }

        $filldata = $request->only([
            'title',
            'description',
            'parent_category',
            'tag',
            'child_category',
            'type',
        ]);

        $blog->seoMeta()->updateOrCreate(
            ['blog_id' => $blog->id],
            [
                'meta_title' => $seoRequest->meta_title ?? $blog->title,
                'meta_description' => $seoRequest->meta_description ?? $blog->description,

            ]
        );

        if ($request->hasFile('image')) {
            $filldata['photo'] = $this->uploadImage($request->file('image'));
        }
        $sendData = [
            'subject' => 'Blog with id.'.$slug.' updated',
            'title' => $request->title,
            'description' => $request->description,
        ];

        $isUpdate = $blog->update($filldata);
        if (! $isUpdate) {
            return response()->json([
                'status' => false,
                'message' => 'Unable to update blog',
            ]);
        }

        // Http::post("https://connect.pabbly.com/workflow/sendwebhookdata/IjU3NjYwNTZkMDYzNTA0MzI1MjZlNTUzMDUxMzQi_pc", $sendData);
        return response()->json([
            'status' => true,
            'message' => 'Blog updated successfully',
            'data' => $blog->fresh(),
        ]);
    }

    public function destroy(int $blog_id)
    {
        if (! auth()->check()) {
            return response()->json([
                'status' => false,
                'message' => 'Please login to delete blog',
            ]);
        }
        $isBlogExist = Blog::find($blog_id);

        if (! $isBlogExist) {
            return response()->json([
                'status' => false,
                'message' => 'Blog not found',

            ]);
        }

        if ($isBlogExist->isDeleted) {
            return response()->json([
                'status' => false,
                'message' => 'Blog already deleted',

            ]);
        }

        // $isUpdate = $isBlogExist->update([
        //     "isDeleted" => true,
        //     "deleted_by" => auth()->user()->id
        // ]);
        $isBlogExist->deleted_by = auth()->user()->id;
        $isBlogExist->save();

        // Soft delete the blog
        $isBlogExist->delete();

        // if ($isUpdate)
        return response()->json([
            'status' => true,
            'message' => 'Blog deleted successfully',
            'deletedBy' => 'Blog is deleted by You.',
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

        // Permanently delete the blog
        $blog->forceDelete();

        return response()->json([
            'status' => true,
            'message' => 'Blog permanently deleted',
        ], 200);
    }

    public function displayuserBlog()
    {
        //only authenticate user can see their blog
        if (! auth()->check()) {
            return response()->json([
                'status' => false,
                'message' => 'Please login first',
            ], 401);
        }
        $id = auth()->user()->id;
        $blogs = Blog::where('user_id', $id)
            ->where('isdeleted', false)
            ->with(['users:id,name', 'deletedBy:id,name', 'parentCategory:id,name', 'childCategory:id,name', 'seoMeta'])
            ->paginate(20);

        // $returnData = [];

        $returnData = $blogs->map(function ($blog) {
            return [
                'id' => $blog->id,
                'slug' => $blog->slug,
                'title' => $blog->title,
                'description' => $blog->description,
                'photo' => $blog->photo,
                'category' => $blog->parentCategory->name ?? '',
                'sub_category' => $blog->childCategory->name ?? '',
                'tag' => $blog->tag ?? '',
                'created_at' => $blog->created_at,
                'created_by' => $blog->users->name,
                'is_deleted' => $blog->isdeleted,
                'type' => $blog->type,
                'seo' => [
                    'title' => $seoMeta->meta_title ?? $blog->title,
                    'description' => $seoMeta->meta_description ?? $blog->description,
                ],
            ];
        });
        $pagination = [
            'next_page_url' => $blogs->nextPageUrl(),
            'previous_page_url' => $blogs->previousPageUrl(),
            'total' => $blogs->total(),
        ];

        return response()->json([
            'status' => true,
            'message' => 'Blog fetched successfully',
            'data' => $returnData,
            'pagination' => $pagination,
        ], 200);

    }

    protected function uploadImage($file)
    {
        $uploadFolder = 'blog-image';
        $image = $file;
        $image_uploaded_path = $image->store($uploadFolder, 'public');
        $uploadedImageUrl = Storage::disk('public')->url($image_uploaded_path);

        return $uploadedImageUrl;
    }

    public function displaySpecificBlog(string $slug)
    {
        $blog = Blog::where('slug', $slug)
            ->with(['users:id,name,email,type', 'deletedBy:id,name', 'parentCategory:id,name', 'childCategory:id,name'])->first();

        if (! $blog) {
            return response()->json([
                'status' => false,
                'message' => 'Blog not found',
            ]);
        }

        if ($blog->draft) {
            if (! auth()->check() || auth()->user()->id !== $blog->user_id) {
                return response()->json([
                    'status' => false,
                    'message' => 'Unauthorized to view this blog',
                ]);
            }
        }

        return response()->json([
            'status' => true,
            'message' => 'Blog fetched successfully',
            'data' => $blog,
            'seo' => [
                'title' => $seoMeta->meta_title ?? $blog->title,
                'description' => $seoMeta->meta_description ?? $blog->description,
            ],
        ]);
    }

    protected function slug($title)
    {
        $slug = Str::slug($title);
        $isBlogExist = Blog::where('slug', $slug)->first();
        if ($isBlogExist) {
            $slug = $slug.'-'.rand(1000, 9999);
        }

        return $slug;
    }

    public function search()
    {
        $query = request('query');

        $blogs = Blog::search($query)->paginate(10); // Algolia search
        $returnData = $blogs->map(fn ($blog) => $this->formatBlogData($blog));
        $pagination = $this->getPaginationData($blogs);

        return response()->json([
            'status' => true,
            'message' => 'Search results fetched successfully',
            'data' => $returnData,
            'pagination' => $pagination,
        ]);
    }

    protected function getPaginationData($blogs)
    {
        return [
            'next_page_url' => $blogs->nextPageUrl(),
            'previous_page_url' => $blogs->previousPageUrl(),
            'total' => $blogs->total(),
        ];
    }

    protected function formatBlogData($blog)
    {
        return [
            'id' => $blog->id,
            'slug' => $blog->slug,
            'title' => $blog->title,
            'description' => $blog->description,
            'photo' => $blog->photo,
            'category' => $blog->parentCategory->name ?? '',
            'sub_category' => $blog->childCategory->name ?? '',
            'tag' => $blog->tag ?? '',
            'created_at' => $blog->created_at,
            // 'created_by' => $blog->users->name,
        ];
    }
}
