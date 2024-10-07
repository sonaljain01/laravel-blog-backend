<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProfileRequest;
use App\Models\User;
use Storage;

class UpdateProfileController extends Controller
{
    public function updateProfile(UpdateProfileRequest $request)
    {
        // dd($request->file('profile_image'));
        $user = User::find(auth()->user()->id);

        $data = $request->only([
            'name',
            'email',
        ]);

        if ($request->hasFile('profile_image')) {

            // $data['profile_image'] = $this->uploadImage($request->file('profile_image'));
            $user->clearMediaCollection('profile_images');
            $user->addMediaFromRequest('profile_image')->toMediaCollection('profile_images');
            $mediaItems = $user->getMedia('*');
            $mediaItems[0]->getUrl();
            $data['profile_image'] = $mediaItems[0]->original_url;

        }

        $isUpdate = $user->update(attributes: $data);
        // $isUpdate['profile_image_url'] = $user->getFirstMediaUrl('profile_images');
        $user->fresh();
        $user['profile_image'] = $user->getFirstMediaUrl('profile_images');

        $user->makeHidden('media');

        if ($isUpdate) {
            return response()->json([
                'status' => true,
                'message' => 'Profile updated successfully',
                'data' => [
                    'user' => $user,

                ],
            ], 200);
        }

        return response()->json([
            'status' => false,
            'message' => 'Unable to update profile',
        ], 500);

    }

    protected function uploadImage($file)
    {
        $uploadFolder = 'profile-image';
        $image = $file;
        $image_uploaded_path = $image->store($uploadFolder, 'public');
        $uploadedImageUrl = Storage::disk('public')->url($image_uploaded_path);

        return $uploadedImageUrl;
    }
}
