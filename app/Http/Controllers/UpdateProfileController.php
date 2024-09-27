<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProfileRequest;
use Storage;
use App\Models\User;

class UpdateProfileController extends Controller
{
    public function updateProfile(UpdateProfileRequest $request, string $id)
    {
        $user = User::find($id);

        if (auth()->user()->id != $id) {
            return response()->json([
                "status" => false,
                "message" => "Unauthorized",
            ], 401);
        }
        $data = $request->only([
            "name",
            "email",
        ]);

        if ($request->hasFile('profile_image')) {
            $data['profile_image'] = $this->uploadImage($request->file('profile_image'));
        }

        $isUpdate = $user->update(attributes: $data);
        if ($isUpdate) {
            return response()->json([
                "status" => true,
                "message" => "Profile updated successfully",
                "data" => $user->fresh()
            ], 200);
        }
        return response()->json([
            "status" => false,
            "message" => "Unable to update profile",
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

    // public function getProfile()
    // {
    //     $user = Auth::user();

    //     $user->profile_image_url = $user->profile_image ? asset("storage/" . $user->profile_image) : null;
    //     return response()->json([
    //         "status" => true,
    //         "message" => "Profile data",
    //         "data" => [
    //             'name' => $user->name,
    //             'profile_image' => $user->profile_image_url,
    //         ],
    //     ]);
    // }
}
