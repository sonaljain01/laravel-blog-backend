<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProfileRequest;
use Auth;
use Illuminate\Http\Request;
use Storage;

class UpdateProfileController extends Controller
{
    public function updateProfile(UpdateProfileRequest $request)
    {
        $user = Auth::user();
        $data = [
            "name" => $request->name,
            "profile_image" => $request->hasFile('profile_image') ? $this->uploadProfileImage($request->file('profile_image')) : $user->profile_image
        ];
        
        $isUpdate = $user->update(attributes: $data);
        if ($isUpdate) {
            return response()->json([
                "status" => true,
                "message" => "Profile updated successfully",
            ], 200);
        }
        return response()->json([
            "status" => false,
            "message" => "Unable to update profile",
        ], 500);

    }

    protected function uploadProfileImage($file)
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
