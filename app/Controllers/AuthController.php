<?php

namespace App\Controllers;
use App\Models\UserModel;

class AuthController extends BaseController
{

    public function login()
    {
        helper(['form']);
        $error = null;

        if ($this->request->getMethod() === 'POST') {
            $email = $this->request->getPost('email');
            $password = $this->request->getPost('password');

            echo $email;
            echo $password;

            $userModel = new UserModel();
            $user = $userModel->getUserByEmail($email);

            if ($user && $password === $user['password']) {
                // Set session
                session()->set([
                    'isLoggedIn' => true,
                    'user_id'    => $user['user_id'],
                    'name'       => $user['name'],
                    'role'       => $user['role'],
                ]);
                // Redirect dashboard sesuai role
                echo $user['role'];
                switch ($user['role']) {
                    case 'admin':
                        return redirect()->to('/dashboard/admin');
                    case 'outlet':
                        return redirect()->to('/dashboard/outlet');
                    case 'cust':
                        return redirect()->to('/dashboard/customer');
                    default:
                        return redirect()->to('/auth/login');
                }
            } else {
                $error = 'Email atau password salah!';
            }
        }


        return view('auth/login', ['error' => $error]);
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/auth/login');
    }
}
