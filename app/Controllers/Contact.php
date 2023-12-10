<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class Contact extends ResourceController
{
    function updateContacts(){
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

        $db      = \Config\Database::connect();
        $builder = $db->query('SELECT * FROM contacts WHERE user_id = "'.$decoded->id.'"');

        $contact = $builder->getRow();

        // echo $db->getLastQuery();

        $contacts = $this->request->getPost('contacts');

        if($contacts == ''){
            $contacts = '[]';
        }

        $db      = \Config\Database::connect();
        $builder = $db->table('contacts');

        if(!$contact){
            // Insert user data into database
            $builder->insert([
                'user_id'     => $decoded->id,
                'contacts'    => $contacts,
                'cr_date'     => time(),
                'last_synced' => time()
            ]);
        }else{
            $dataToUpdate = [
                'contacts'    => $contacts,
                'last_synced' => time()
            ];
    
            $db->table('contacts')
                ->where('id', $contact->id)
                ->update($dataToUpdate);
        }

        return $this->respond([
            'status'          => true,
            'message'         => 'Contacts updated successfully.',
        ], 201); // Created
    }

    function getContacts(){
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

        $db      = \Config\Database::connect();
        $builder = $db->query('SELECT * FROM contacts WHERE user_id = "'.$decoded->id.'"');

        $contact = $builder->getRow();

        if(!$contact){
            $builder = $db->table('contacts');

            $builder->insert([
                'user_id'     => $decoded->id,
                'contacts'    => '[]',
                'cr_date'     => time(),
                'last_synced' => time()
            ]);

            $builder = $db->query('SELECT * FROM contacts WHERE user_id = "'.$decoded->id.'"');

            $contact = $builder->getRow();
        }

        return $this->respond([
            'status'   => true,
            'contacts' => $contact->contacts,
        ], 201);
    }
}
