<?php 
namespace App\Controllers;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use Firebase\JWT\JWT;

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
        ];

        if (!$this->validate($rules)) {
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
        $brokerage    = $this->request->getPost('brokerage');
        $address      = $this->request->getPost('address');
        $addr_lat     = $this->request->getPost('addr_lat');
        $addr_lon     = $this->request->getPost('addr_lon');

        // Insert user data into database
        $db      = \Config\Database::connect();
        $builder = $db->table('users');

        $builder->insert([
            'name'      => $name,
            'email'     => $email,
            'phone_no'  => $phoneNo,
            'interest'  => $interest,
            'password'  => $passwordHash,
            'brokerage' => $brokerage,
            'address'   => $address,
            'addr_lat'  => $addr_lat,
            'addr_lon'  => $addr_lon,
            'cr_date'   => time(),
        ]);

        $response = [
            'status'  => true,
            'message' => 'Registered successfully'
        ];

        return $this->respond($response, 200);
    }

    public function login(){
        $rules = [
            'email'    => 'required|valid_email',
            'password' => 'required|min_length[6]'
        ];

        if (!$this->validate($rules)) {
            $response = [
                'status'  => false,
                'message' => 'Validation failed.',
                'errors'  => $this->validator->getErrors()
            ];

            return $this->respond($response, 400);
        }

        $db      = \Config\Database::connect();
        $builder = $db->query('SELECT * FROM users WHERE email = "'.$this->request->getPost('email').'"');

        $user = $builder->getRow();

        // echo $db->getLastQuery();

        if(!$user){
            return $this->respond([
                'status'  => 'error',
                'message' => 'Invalid email',
            ], 401); // Unauthorized
        }else{
            if($user->is_active == 'Y'){
                $pwd_verify = password_verify($this->request->getPost('password'), $user->password);

                if($pwd_verify){
                    $key = getenv('TOKEN_SECRET');
                    $iat = time();
                    $exp = $iat + 3600;
            
                    $payload = array(
                        "iat"   => $iat,
                        "exp"   => $exp,
                        "email" => $user->email,
                    );
                    
                    $token = JWT::encode($payload, $key, 'HS256');

                    return $this->respond([
                        'success' => true,
                        'message' => 'Login Succesful',
                        'token'   => $token
                    ], 200);
                }else{
                    return $this->respond([
                        'status'  => 'error',
                        'message' => 'Invalid login credentials',
                    ], 401);
                }
            }else{
                return $this->respond([
                    'status'  => 'error',
                    'message' => 'Inactive account',
                ], 401);
            }
        }
    }
}