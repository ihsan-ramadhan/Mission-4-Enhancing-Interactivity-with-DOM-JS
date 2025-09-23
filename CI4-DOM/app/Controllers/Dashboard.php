<?php

namespace App\Controllers;

use App\Models\CourseModel;
use App\Models\TakesModel;
use App\Models\StudentModel;

class Dashboard extends BaseController
{
    protected $courseModel;
    protected $takesModel;
    protected $studentModel;
    
    public function __construct()
    {
        $this->courseModel = new CourseModel();
        $this->takesModel = new TakesModel();
        $this->studentModel = new StudentModel();
    }

    public function index()
    {
        // Check if logged in
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        $role = session()->get('role');
        
        if ($role === 'admin') {
            return $this->adminDashboard();
        } else {
            return $this->studentDashboard();
        }
    }

    private function adminDashboard()
    {
        $data = [
            'title' => 'Admin Dashboard',
            'courses' => $this->courseModel->findAll(),
            'students' => $this->studentModel->getStudentWithUser()
        ];
        
        return view('dashboard/admin', $data);
    }

    private function studentDashboard()
    {
        $student_id = session()->get('student_id');
        
        $allCourses = $this->courseModel
            ->select('courses.*, takes.enroll_date, takes.student_id AS is_enrolled_flag')
            ->join('takes', "takes.course_id = courses.course_id AND takes.student_id = {$student_id}", 'left')
            ->orderBy('courses.course_name', 'ASC')
            ->findAll();

        $processedCourses = array_map(function ($course) {
            $course['is_enrolled'] = !is_null($course['is_enrolled_flag']);
            unset($course['is_enrolled_flag']);
            return $course;
        }, $allCourses);

        $data = [
            'title' => 'Student Dashboard',
            'available_courses' => $processedCourses,
        ];
        
        return view('dashboard/student', $data);
    }
}