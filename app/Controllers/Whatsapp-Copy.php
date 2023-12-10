<?php

namespace App\Controllers;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

use App\Controllers\BaseController;

class Whatsapp extends BaseController
{
    public function upload(){
        // Get JWT token from header
        $token = explode(' ', $this->request->getHeaderLine('Authorization'))[1];
        $key   = getenv('TOKEN_SECRET');

        // Validate JWT token
        try {
            $decoded = JWT::decode($token, new Key($key, 'HS256'));
        } catch (\Exception $e) {
            return $this->respond([
                'status'  => 'error',
                'message' => 'Invalid token: ' . $e->getMessage(),
            ], 401); // Unauthorized
        }

        // Validate uploaded files
        $validation = service('validation');

        $validationRules = [
            'file'     => 'uploaded[file]|max_size[file,1024]|ext_in[file,png,jpg,jpeg,gif]|is_image[file]',
            // 'images'   => 'uploaded[images]|is_array[images]|max_size[images,1024]',
            // 'file.*'   => 'uploaded[images.*]|mime_in[images.*,image/jpg,image/jpeg,image/png]|max_size[images.*,1024]',
        ];

        // $errorMessages = [
        //     'images' => [
        //         'uploaded' => 'Please upload at least one image.',
        //         'is_array' => 'Only multiple images can be uploaded.',
        //         'max_size' => 'Total image size cannot exceed 1MB.',
        //     ],
        //     'images.*' => [
        //         'uploaded' => 'Please upload an image.',
        //         'mime_in'  => 'Only JPG, JPEG or PNG files are allowed.',
        //         'max_size' => 'Image size cannot exceed 1MB.',
        //     ],
        // ];

        if (!$this->validate($this->request->getFiles(), $validationRules)) {
            return $this->respond([
                'status'  => 'error',
                'message' => 'Validation failed.',
                'errors'  => $validation->getErrors(),
            ], 400); // Bad Request
        }

        // Upload files
        $uploadedImages = [];
        $images = $this->request->getFiles('images');

        foreach ($images as $image) {
            $fileName = $image->getRandomName();

            if (!$image->move(WRITEPATH . 'uploads', $fileName)) {
                return $this->respond([
                    'status' => 'error',
                    'message' => 'File upload failed.',
                ], 500); // Internal Server Error
            }

            $uploadedImages[] = $fileName;
        }

        // Respond with success message and uploaded image names
        return $this->respond([
            'status' => 'success',
            'message' => 'Images uploaded successfully.',
            'uploaded_images' => $uploadedImages,
        ], 201); // Created
    }
}
