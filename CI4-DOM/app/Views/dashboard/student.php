<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<div class="space-y-8">
    <div>
        <h1 class="text-3xl font-bold text-gray-900">Student Dashboard</h1>
        <p class="mt-2 text-gray-600">Manage your course enrollments and view available courses</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <div class="bg-white shadow rounded-lg p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-6">Available Courses</h2>
            
            <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 rounded-lg">
                <table class="min-w-full divide-y divide-gray-300">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Course Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Credits</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                        </tr>
                    </thead>
                    <tbody id="available-courses-body" class="bg-white divide-y divide-gray-200">
                        <?php if (!empty($available_courses)): ?>
                            <?php foreach ($available_courses as $course): ?>
                            <tr class="hover:bg-gray-50" data-course-id="<?= $course['course_id'] ?>">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 course-name">
                                    <?= $course['course_name'] ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        <?= $course['credits'] ?> Credits
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 status-cell">
                                    <?php if (isset($course['is_enrolled']) && $course['is_enrolled']): ?>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Enrolled
                                        </span>
                                    <?php else: ?>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            Available
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium action-cell">
                                    <?php if (!isset($course['is_enrolled']) || !$course['is_enrolled']): ?>
                                        <button onclick="confirmEnroll(<?= $course['course_id'] ?>, this)" 
                                                class="inline-flex items-center px-3 py-1 rounded text-green-600 hover:text-green-800">
                                            Enroll
                                        </button>
                                    <?php else: ?>
                                        <button onclick="confirmUnenroll(<?= $course['course_id'] ?>, this)" 
                                                class="inline-flex items-center px-1 py-1 font-medium rounded text-red-600 hover:text-red-800">
                                            Unenroll
                                        </button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-sm text-gray-500">
                                    No courses available
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-white shadow rounded-lg p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-6">My Enrolled Courses</h2>
            
            <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 rounded-lg">
                <table class="min-w-full divide-y divide-gray-300">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Course Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Credits</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date Enrolled</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                        </tr>
                    </thead>
                    <tbody id="enrolled-courses-body" class="bg-white divide-y divide-gray-200">
                        <?php if (!empty($enrolled_courses)): ?>
                            <?php foreach ($enrolled_courses as $course): ?>
                            <tr class="hover:bg-gray-50" data-course-id="<?= $course['course_id'] ?>">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 course-name">
                                    <?= $course['course_name'] ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        <?= $course['credits'] ?> Credits
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <?= date('d/m/Y', strtotime($course['enroll_date'])) ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <button onclick="confirmUnenroll(<?= $course['course_id'] ?>, this)" 
                                            class="inline-flex items-center px-1 py-1 font-medium rounded text-red-600 hover:text-red-800">
                                        Unenroll
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr id="no-enrolled-courses">
                                <td colspan="4" class="px-6 py-8 text-center text-sm text-gray-500">
                                    No enrolled courses yet
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            
            <?php if (!empty($enrolled_courses)): ?>
            <div id="total-credits-info" class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-blue-800">Total Credits</h3>
                        <div class="mt-2 text-sm text-blue-700">
                            <p>You are currently enrolled in <strong class="font-medium"><span id="total-credits"><?= array_sum(array_column($enrolled_courses, 'credits')) ?></span> credits</strong></p>
                        </div>
                    </div>
                </div>
            </div>
            <?php else: ?>
            <div id="total-credits-info" class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4 hidden">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-blue-800">Total Credits</h3>
                        <div class="mt-2 text-sm text-blue-700">
                            <p>You are currently enrolled in <strong><span id="total-credits">0</span> credits</strong></p>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
function confirmEnroll(courseId, buttonElement) {
    const courseName = buttonElement.closest('tr').querySelector('.course-name').textContent.trim();
    const title = 'Konfirmasi Pendaftaran';
    const message = `Apakah Anda yakin ingin mengambil mata kuliah "${courseName}"?`;
    
    const enrollAction = () => {
        enrollCourse(courseId);
    };

    showConfirmationModal(title, message, '#', 'green', enrollAction);
}

function confirmUnenroll(courseId, buttonElement) {
    const courseName = buttonElement.closest('tr').querySelector('.course-name').textContent.trim();
    const title = 'Konfirmasi Pembatalan';
    const message = `Apakah Anda yakin ingin membatalkan pendaftaran mata kuliah "${courseName}"?`;
    
    const unenrollAction = () => {
        unenrollCourse(courseId);
    };

    showConfirmationModal(title, message, '#', 'red', unenrollAction);
}

function showFlashMessage(message, type) {
    const existingMessages = document.querySelectorAll('.flash-message');
    existingMessages.forEach(msg => msg.remove());
    
    const flashMessage = document.createElement('div');
    flashMessage.className = `flash-message mb-6 ${type === 'success' ? 'bg-green-50 border border-green-200 text-green-800' : 'bg-red-50 border border-red-200 text-red-800'} px-4 py-3 rounded-md`;
    flashMessage.setAttribute('role', 'alert');
    
    flashMessage.innerHTML = `
        <div class="flex justify-between items-center">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    ${type === 'success' ? 
                        '<svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>' :
                        '<svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" /></svg>'
                    }
                </div>
                <div class="ml-3">${message}</div>
            </div>
            <button type="button" onclick="closeFlashMessage(this)" class="ml-4 -mr-1 p-1 rounded-md ${type === 'success' ? 'hover:bg-green-100' : 'hover:bg-red-100'}">
                <svg class="h-5 w-5 ${type === 'success' ? 'text-green-700' : 'text-red-700'}" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z" />
                </svg>
            </button>
        </div>
    `;
    
    const mainContent = document.querySelector('main .max-w-7xl');
    mainContent.insertBefore(flashMessage, mainContent.firstChild);
}

function enrollCourse(courseId) {
    fetch(`/course/enroll/${courseId}`, {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        showFlashMessage(data.message, data.success ? 'success' : 'error');
        
        if (data.success) {
            updateCourseStatus(courseId, 'enrolled', data.course);
        }
    })
    .catch(error => {
        showFlashMessage('Terjadi kesalahan sistem', 'error');
        console.error('Error:', error);
    });
}

function unenrollCourse(courseId) {
    fetch(`/course/unenroll/${courseId}`, {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        showFlashMessage(data.message, data.success ? 'success' : 'error');
        
        if (data.success) {
            updateCourseStatus(courseId, 'available', data.course);
        }
    })
    .catch(error => {
        showFlashMessage('Terjadi kesalahan sistem', 'error');
        console.error('Error:', error);
    });
}

function updateCourseStatus(courseId, status, courseData) {
    const availableRow = document.querySelector(`#available-courses-body tr[data-course-id="${courseId}"]`);
    const enrolledRow = document.querySelector(`#enrolled-courses-body tr[data-course-id="${courseId}"]`);
    
    if (status === 'enrolled') {
        if (availableRow) {
            const statusCell = availableRow.querySelector('.status-cell span');
            const actionCell = availableRow.querySelector('.action-cell');
            
            statusCell.className = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800';
            statusCell.textContent = 'Enrolled';
            
            actionCell.innerHTML = `<button onclick="confirmUnenroll(${courseId}, this)" class="inline-flex items-center px-1 py-1 font-medium rounded text-red-600 hover:text-red-800">Unenroll</button>`;
        }
        
        const enrolledBody = document.getElementById('enrolled-courses-body');
        const noCoursesRow = document.getElementById('no-enrolled-courses');
        
        if (noCoursesRow) {
            noCoursesRow.remove();
        }
        
        const newRow = document.createElement('tr');
        newRow.className = 'hover:bg-gray-50';
        newRow.setAttribute('data-course-id', courseId);
        newRow.innerHTML = `
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 course-name">${courseData.course_name}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">${courseData.credits} Credits</span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${new Date().toLocaleDateString('id-ID')}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                <button onclick="confirmUnenroll(${courseId}, this)" class="inline-flex items-center px-1 py-1 font-medium rounded text-red-600 hover:text-red-800">Unenroll</button>
            </td>
        `;
        enrolledBody.appendChild(newRow);
        
        updateTotalCredits(courseData.credits, 'add');
        
    } else if (status === 'available') {
        if (availableRow) {
            const statusCell = availableRow.querySelector('.status-cell span');
            const actionCell = availableRow.querySelector('.action-cell');
            
            statusCell.className = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800';
            statusCell.textContent = 'Available';
            
            actionCell.innerHTML = `<button onclick="confirmEnroll(${courseId}, this)" class="inline-flex items-center px-3 py-1 rounded text-green-600 hover:text-green-800">Enroll</button>`;
        }
        
        if (enrolledRow) {
            enrolledRow.remove();
            
            const remainingRows = document.querySelectorAll('#enrolled-courses-body tr[data-course-id]');
            if (remainingRows.length === 0) {
                const enrolledBody = document.getElementById('enrolled-courses-body');
                const noCoursesRow = document.createElement('tr');
                noCoursesRow.id = 'no-enrolled-courses';
                noCoursesRow.innerHTML = '<td colspan="4" class="px-6 py-8 text-center text-sm text-gray-500">No enrolled courses yet</td>';
                enrolledBody.appendChild(noCoursesRow);
            }
        }
        
        updateTotalCredits(courseData.credits, 'subtract');
    }
}

function updateTotalCredits(credits, operation) {
    const totalCreditsElement = document.getElementById('total-credits');
    const totalCreditsInfo = document.getElementById('total-credits-info');
    
    if (totalCreditsElement) {
        let currentTotal = parseInt(totalCreditsElement.textContent) || 0;
        
        if (operation === 'add') {
            currentTotal += parseInt(credits);
        } else if (operation === 'subtract') {
            currentTotal -= parseInt(credits);
        }
        
        totalCreditsElement.textContent = currentTotal;
        
        if (currentTotal > 0) {
            totalCreditsInfo.classList.remove('hidden');
        } else {
            totalCreditsInfo.classList.add('hidden');
        }
    }
}
</script>

<?= $this->endSection() ?>