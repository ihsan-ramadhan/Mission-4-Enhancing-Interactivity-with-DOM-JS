<?php

namespace App\Controllers;

use App\Models\CourseModel;
use App\Models\TakesModel;

class Course extends BaseController
{
    protected $courseModel;
    protected $takesModel;
    
    public function __construct()
    {
        $this->courseModel = new CourseModel();
        $this->takesModel = new TakesModel();
    }

    public function index()
    {
        // Check authentication
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        $data = [
            'title' => 'Manage Courses',
            'courses' => $this->courseModel->findAll()
        ];

        return view('course/index', $data);
    }

    public function create()
    {
        // Only admin can create courses
        if (!session()->get('logged_in') || session()->get('role') !== 'admin') {
            return redirect()->to('/dashboard')->with('error', 'Akses ditolak!');
        }

        if ($this->request->getMethod() === 'POST') {
            $data = [
                'course_name' => $this->request->getPost('course_name'),
                'credits' => $this->request->getPost('credits')
            ];

            if ($this->courseModel->insert($data)) {
                return redirect()->to('/course')->with('success', 'Course berhasil ditambahkan!');
            } else {
                return redirect()->back()->with('error', 'Gagal menambahkan course!');
            }
        }

        return view('course/create');
    }

    public function edit($id)
    {
        // Only admin can edit courses
        if (!session()->get('logged_in') || session()->get('role') !== 'admin') {
            return redirect()->to('/dashboard')->with('error', 'Akses ditolak!');
        }

        $course = $this->courseModel->find($id);
        
        if (!$course) {
            return redirect()->to('/course')->with('error', 'Course tidak ditemukan!');
        }

        if ($this->request->getMethod() === 'POST') {
            $data = [
                'course_name' => $this->request->getPost('course_name'),
                'credits' => $this->request->getPost('credits')
            ];

            if ($this->courseModel->update($id, $data)) {
                return redirect()->to('/course')->with('success', 'Course berhasil diupdate!');
            } else {
                return redirect()->back()->with('error', 'Gagal mengupdate course!');
            }
        }

        $data = [
            'title' => 'Edit Course',
            'course' => $course
        ];

        return view('course/edit', $data);
    }

    public function delete($id)
    {
        // Only admin can delete courses
        if (!session()->get('logged_in') || session()->get('role') !== 'admin') {
            return redirect()->to('/dashboard')->with('error', 'Akses ditolak!');
        }

        if ($this->courseModel->delete($id)) {
            return redirect()->to('/course')->with('success', 'Course berhasil dihapus!');
        } else {
            return redirect()->to('/course')->with('error', 'Gagal menghapus course!');
        }
    }

    public function enroll($course_id)
    {
        // Only student can enroll
        if (!session()->get('logged_in') || session()->get('role') !== 'student') {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Akses ditolak!'
                ]);
            }
            return redirect()->to('/dashboard')->with('error', 'Akses ditolak!');
        }

        $student_id = session()->get('student_id');
        
        // Check if course exists
        $course = $this->courseModel->find($course_id);
        if (!$course) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Course tidak ditemukan!'
                ]);
            }
            return redirect()->to('/dashboard')->with('error', 'Course tidak ditemukan!');
        }
        
        try {
            if ($this->takesModel->enrollCourse($student_id, $course_id)) {
                if ($this->request->isAJAX()) {
                    return $this->response->setJSON([
                        'success' => true,
                        'message' => 'Berhasil mendaftar mata kuliah: ' . $course['course_name'] . '!',
                        'course' => $course
                    ]);
                }
                return redirect()->to('/dashboard')->with('success', 'Berhasil mendaftar mata kuliah: ' . $course['course_name'] . '!');
            } else {
                if ($this->request->isAJAX()) {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Gagal mendaftar mata kuliah atau sudah terdaftar sebelumnya!'
                    ]);
                }
                return redirect()->to('/dashboard')->with('error', 'Gagal mendaftar mata kuliah atau sudah terdaftar sebelumnya!');
            }
        } catch (\Exception $e) {
            log_message('error', 'Enrollment error: ' . $e->getMessage());
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat mendaftar mata kuliah!'
                ]);
            }
            return redirect()->to('/dashboard')->with('error', 'Terjadi kesalahan saat mendaftar mata kuliah!');
        }
    }

    public function unenroll($course_id)
    {
        // Only student can unenroll
        if (!session()->get('logged_in') || session()->get('role') !== 'student') {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Akses ditolak!'
                ]);
            }
            return redirect()->to('/dashboard')->with('error', 'Akses ditolak!');
        }

        $student_id = session()->get('student_id');
        
        // Check if course exists
        $course = $this->courseModel->find($course_id);
        if (!$course) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Course tidak ditemukan!'
                ]);
            }
            return redirect()->to('/dashboard')->with('error', 'Course tidak ditemukan!');
        }
        
        try {
            if ($this->takesModel->unenrollCourse($student_id, $course_id)) {
                if ($this->request->isAJAX()) {
                    return $this->response->setJSON([
                        'success' => true,
                        'message' => 'Berhasil membatalkan pendaftaran mata kuliah: ' . $course['course_name'] . '!',
                        'course' => $course
                    ]);
                }
                return redirect()->to('/dashboard')->with('success', 'Berhasil membatalkan pendaftaran mata kuliah: ' . $course['course_name'] . '!');
            } else {
                if ($this->request->isAJAX()) {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Gagal membatalkan pendaftaran mata kuliah!'
                    ]);
                }
                return redirect()->to('/dashboard')->with('error', 'Gagal membatalkan pendaftaran mata kuliah!');
            }
        } catch (\Exception $e) {
            log_message('error', 'Unenrollment error: ' . $e->getMessage());
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat membatalkan pendaftaran mata kuliah!'
                ]);
            }
            return redirect()->to('/dashboard')->with('error', 'Terjadi kesalahan saat membatalkan pendaftaran mata kuliah!');
        }
    }
}