<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\Files\File;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class Whatsapp extends ResourceController
{
    use ResponseTrait;

    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */
    public function upload(){
        $validation = service('validation');

        // Get JWT token from header
        $token = explode(' ', $this->request->getHeaderLine('Authorization'))[1];
        $key   = getenv('TOKEN_SECRET');

        // Validate JWT token
        try {
            $decoded = JWT::decode($token, new Key($key, 'HS256'));
        } catch (\Exception $e) {
            return $this->respond([
                'status'  => false,
                'message' => 'Invalid token: ' . $e->getMessage(),
            ], 401); // Unauthorized
        }

        $validationRules = [
            'message' => 'required',
            'images'  => 'uploaded[images]|max_size[images,10240]|mime_in[images,image/jpeg,image/png,image/jpg]',
        ];

        // Set custom error messages
        $customMessages = [
            'images' => [
                'uploaded' => 'Please select an image to upload.',
                'max_size' => 'The uploaded image exceeds the maximum allowed size (10MB).',
                'mime_in'  => 'Invalid image type. Please upload only JPEG, PNG, or JPG images.',
            ],
        ];

        $validation->setRules($validationRules, $customMessages);

        if (!$validation->withRequest($this->request)->run()) {
            $response = [
                'status'  => false,
                'message' => 'Validation failed.',
                'errors'  => $validation->getErrors()
            ];

            return $this->respond($response, 400);
        }

        // Get the uploaded files
        $images = $this->request->getFileMultiple('images');

        // Upload directory
        $uploadDir = ROOTPATH . 'public/uploads/';

        // Check if the directory exists, if not, create it
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $uploadedFiles = [];

        foreach ($images as $image) {
            $fileName = $image->getRandomName();
    
            if (!$image->move(ROOTPATH . 'public/uploads/', $fileName)) {
                return $this->respond([
                    'status' => 'error',
                    'message' => 'File upload failed.',
                ], 500); // Internal Server Error
            }
    
            $uploadedImages[] = $fileName;
        }

        $db      = \Config\Database::connect();
        $builder = $db->table('whatsapp');

        $message = $this->request->getPost('message');

        $builder->insert([
            'date'    => date('Y-m-d'),
            'message' => $message,
            'user_id' => $decoded->id,
            'cr_date' => time()
        ]);

        $insert_id = $db->insertID();

        foreach($uploadedImages as $row){
            $builder = $db->table('whatsapp_images');

            $builder->insert([
                'whatsapp_id' => $insert_id,
                'file_name'   => $row,
                'cr_date'     => time()
            ]);
        }

        // Respond with success message and uploaded image names
        return $this->respond([
            'status'          => true,
            'message'         => 'Message added successfully.',
            // 'uploaded_images' => $uploadedImages,
        ], 201); // Created
    }

    function getWhatsapp(){
        $token = explode(' ', $this->request->getHeaderLine('Authorization'))[1];
        $key   = getenv('TOKEN_SECRET');

        // Validate JWT token
        try {
            $decoded = JWT::decode($token, new Key($key, 'HS256'));
        } catch (\Exception $e) {
            return $this->respond([
                'status'  => false,
                'message' => 'Invalid token: ' . $e->getMessage(),
            ], 401); // Unauthorized
        }
        // $query->getFirstRow();

        $db    = \Config\Database::connect();
        $query = $db->query('SELECT * FROM whatsapp WHERE user_id = "'.$decoded->id.'"');

        $message = $query->getResult();

        foreach($message as $row){
            $query = $db->query('SELECT * FROM whatsapp_images WHERE whatsapp_id = "'.$row->id.'"');

            $row->images = $query->getResult();
        }

        return $this->respond([
            'status'   => true,
            'messages' => $message
        ], 200);
    }

    /**
     * Return the properties of a resource object
     *
     * @return mixed
     */
    public function show($id = null)
    {
        //
    }

    /**
     * Return a new resource object, with default properties
     *
     * @return mixed
     */
    public function new()
    {
        //
    }

    /**
     * Create a new resource object, from "posted" parameters
     *
     * @return mixed
     */
    public function create()
    {
        //
    }

    /**
     * Return the editable properties of a resource object
     *
     * @return mixed
     */
    public function edit($id = null)
    {
        //
    }

    /**
     * Add or update a model resource, from "posted" properties
     *
     * @return mixed
     */
    public function update($id = null)
    {
        //
    }

    /**
     * Delete the designated resource object from the model
     *
     * @return mixed
     */
    public function delete($id = null)
    {
        //
    }
}
