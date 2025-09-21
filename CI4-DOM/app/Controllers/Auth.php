<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\StudentModel;

class Auth extends BaseController
{
    protected $userModel;
    protected $studentModel;
    
    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->studentModel = new StudentModel();
    }

    public function login()
    {
        // Jika sudah login, redirect ke dashboard
        if (session()->get('logged_in')) {
            return redirect()->to('/dashboard');
        }

        if ($this->request->getMethod() === 'POST') {
            $username = $this->request->getPost('username');
            $password = $this->request->getPost('password');

            $user = $this->userModel->validateUser($username, $password);

            if ($user) {
                $sessionData = [
                    'user_id' => $user['user_id'],
                    'username' => $user['username'],
                    'full_name' => $user['full_name'],
                    'role' => $user['role'],
                    'logged_in' => true
                ];

                // Jika role student, ambil student_id
                if ($user['role'] === 'student') {
                    $student = $this->studentModel->getStudentByUserId($user['user_id']);
                    if ($student) {
                        $sessionData['student_id'] = $student['student_id'];
                    }
                }

                session()->set($sessionData);
                return redirect()->to('/dashboard')->with('success', 'Login berhasil!');
            } else {
                return redirect()->back()->with('error', 'Username atau password salah!');
            }
        }

        return view('auth/login');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login')->with('success', 'Logout berhasil!');
    }
}