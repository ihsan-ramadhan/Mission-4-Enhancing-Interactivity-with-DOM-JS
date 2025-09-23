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

    public function batchUpdate()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403, 'Akses Ditolak');
        }
        $student_id = session()->get('student_id');
        if (!$student_id) {
            return $this->response->setJSON(['success' => false, 'message' => 'Sesi tidak valid. Silakan login kembali.'])->setStatusCode(401);
        }

        $json = $this->request->getJSON();
        $coursesToEnroll = $json->enroll_ids ?? [];
        $coursesToUnenroll = $json->unenroll_ids ?? [];

        $successCount = 0;
        $failureCount = 0;

        try {
            foreach ($coursesToUnenroll as $courseId) {
                if ($this->takesModel->unenrollCourse($student_id, $courseId)) {
                    $successCount++;
                } else {
                    $failureCount++;
                }
            }
            foreach ($coursesToEnroll as $courseId) {
                if ($this->takesModel->enrollCourse($student_id, $courseId)) {
                    $successCount++;
                } else {
                    $failureCount++;
                }
            }
            $updatedEnrolledCourses = $this->takesModel->getStudentCourses($student_id);

            if ($failureCount > 0) {
                 return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Beberapa perubahan gagal diterapkan.',
                    'enrolled_courses' => $updatedEnrolledCourses
                ]);
            }

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Perubahan mata kuliah berhasil disimpan!',
                'enrolled_courses' => $updatedEnrolledCourses
            ]);

        } catch (\Exception $e) {
            log_message('error', '[BATCH_UPDATE] ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'Terjadi kesalahan pada server.'])->setStatusCode(500);
        }
    }
}