<?php 
namespace App\Controllers;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;

use App\Models\EmployeeModel;

class Authentication extends ResourceController
{
    use ResponseTrait;
    // create
    public function register(){
        $validation = service('validation');

        $rules = [
            'name'      => 'required|min_length[3]|max_length[50]',
            'email'     => 'required|valid_email|is_unique[users.email]',
            'password'  => 'required|min_length[8]|max_length[255]',
            'phone_no'  => 'required|numeric|min_length[10]|max_length[15]',
            'interest'  => 'required|string',
            'brokerage' => 'required',
            'address'   => 'required',
            'addr_lat'  => 'required',
            'addr_lon'  => 'required',
            // 'password_confirm' => 'required|matches[password]',
        ];

        $errorMessages = [
            'name' => [
                'required'   => 'Please enter your name.',
                'min_length' => 'Your name must be at least 3 characters long.',
                'max_length' => 'Your name cannot exceed 50 characters.',
            ],
            'email' => [
                'required'    => 'Please enter your email address.',
                'valid_email' => 'Please enter a valid email address.',
                'is_unique'   => 'This email address is already in use.',
            ],
            'password' => [
                'required'   => 'Please enter a password.',
                'min_length' => 'Your password must be at least 8 characters long.',
                'max_length' => 'Your password cannot exceed 255 characters.',
            ],
            'phone_no' => [
                'required'   => 'Please enter your phone number.',
                'numeric'    => 'Please enter a valid phone number.',
                'min_length' => 'Phone number must be at least 10 digits long.',
                'max_length' => 'Phone number cannot exceed 15 digits.',
            ],
            'interest' => [
                'required' => 'Please enter your interests.',
                'string'   => 'Interests must be a valid string.',
            ],
            'password_confirm' => [
                'required' => 'Please confirm your password.',
                'matches' => 'Password confirmation does not match.',
            ],
        ];

        if (!$this->validate($rules, $errorMessages)) {
            // return $this->failValidationErrors($this->validator->getErrors());
            $response = [
                'status'  => false,
                'message' => 'Validation failed.',
                'errors'  => $this->validator->getErrors()
            ];

            return $this->respond($response, 400);
        }

        // Hash the password
        $passwordHash = password_hash($this->request->getPost('password'), PASSWORD_DEFAULT);
        
        $name         = $this->request->getPost('name');
        $email        = $this->request->getPost('email');
        $phoneNo      = $this->request->getPost('phone_no');
        $interest     = $this->request->getPost('interest');

        // Insert user data into database
        $db      = \Config\Database::connect();
        $builder = $db->table('users');

        $builder->insert([
            'name'      => $name,
            'email'     => $email,
            'phone_no'  => $phoneNo,
            'interest'  => $interest,
            'password'  => $passwordHash,
        ]);

        $response = [
            'status'  => true,
            'message' => 'Registered successfully'
        ];

        return $this->respond($response, 200);
    }

    public function login(){

    }
}