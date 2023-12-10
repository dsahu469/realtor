<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class User extends ResourceController
{
    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */

    function updateLocation(){
        $validation = service('validation');

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
            'address' => 'required',
            'lat'     => 'required',
            'lon'     => 'required'
        ];

        $customMessages = [
            'lat' => [
                'required' => 'Please provide the Latitude'
            ],
            'lon' => [
                'required' => 'Please provide the Longitude'
            ]
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

        $db      = \Config\Database::connect();
        $builder = $db->table('whatsapp');

        $dataToUpdate = [
            'cur_address' => $this->request->getPost('address'),
            'cur_lat'     => $this->request->getPost('lat'),
            'cur_lon'     => $this->request->getPost('lon'),
        ];

        $db->table('users') // Replace 'your_table_name' with your actual table name
            ->where('id', $decoded->id) // Replace 'id' with your actual column name
            ->update($dataToUpdate);

        return $this->respond([
            'status'          => true,
            'message'         => 'Location updated successfully.',
            // 'uploaded_images' => $uploadedImages,
        ], 201); // Created
    }

    function getRealtor(){
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

        if($decoded->type == 'REALTOR'){
            return $this->respond([
                'status'  => false,
                'message' => 'This API is for users only',
            ], 401);
        }

        $rules = [
            'lat' => 'required',
            'lon' => 'required'
        ];

        if (!$this->validate($rules)) {
            $response = [
                'status'  => false,
                'message' => 'Validation failed.',
                'errors'  => $this->validator->getErrors()
            ];

            return $this->respond($response, 400);
        }

        $lat = $this->request->getPost('lat');
        $lon = $this->request->getPost('lon');

        $sql = "SELECT id, address, name, phone_no, addr_lat, addr_lon, cur_address, cur_lat, cur_lon,
        6371 * ACOS( COS(RADIANS($lat)) * COS(RADIANS(addr_lat)) * COS(RADIANS(addr_lon) - RADIANS($lon)) + SIN(RADIANS($lat)) * SIN(RADIANS(addr_lat)) ) AS distance FROM users WHERE 6371 * ACOS( COS(RADIANS($lat)) * COS(RADIANS(addr_lat)) * COS(RADIANS(addr_lon) - RADIANS($lon)) + SIN(RADIANS($lat)) * SIN(RADIANS(addr_lat)) ) <= 20 AND id != ".$decoded->id." AND user_type = 'REALTOR' ORDER BY distance";

        $db    = \Config\Database::connect();
        $query = $db->query($sql);

        $realtor = $query->getResult();

        return $this->respond([
            'status'  => true,
            'realtor' => $realtor
        ], 200);
    }
    
    public function index()
    {
        //
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
