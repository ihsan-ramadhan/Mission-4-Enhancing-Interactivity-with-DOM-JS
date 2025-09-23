<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<div class="space-y-8">
    <div>
        <h1 class="text-3xl font-bold text-gray-900">Student Dashboard</h1>
        <p class="mt-2 text-gray-600">Manage your course enrollments and view available courses</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:items-start">
        <div class="bg-white shadow rounded-lg p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-6">Available Courses</h2>
            
            <div id="course-selection-form">
                <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 rounded-lg">
                    <table class="min-w-full divide-y divide-gray-300">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="w-10 px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Choose</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Course Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Credits</th>
                            </tr>
                        </thead>
                        <tbody id="available-courses-body" class="bg-white divide-y divide-gray-200">
                            <?php if (!empty($available_courses)): ?>
                                <?php foreach ($available_courses as $course): ?>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-4 text-center">
                                        <input type="checkbox" 
                                               class="course-checkbox h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded"
                                               value="<?= $course['course_id'] ?>"
                                               <?= (isset($course['is_enrolled']) && $course['is_enrolled']) ? 'checked' : '' ?>>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        <?= $course['course_name'] ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            <?= $course['credits'] ?> Credits
                                        </span>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="3" class="px-6 py-8 text-center text-sm text-gray-500">
                                        No enrolled courses yet
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <div class="mt-6 flex justify-end">
                    <button onclick="submitEnrollmentChanges()" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-primary hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                        Save Changes
                    </button>
                </div>
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
                        </tr>
                    </thead>
                    <tbody id="enrolled-courses-body" class="bg-white divide-y divide-gray-200">
                        </tbody>
                </table>
            </div>
            
            <div id="total-credits-info" class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4 hidden">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" /></svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-blue-800">Total Credits</h3>
                        <div class="mt-2 text-sm text-blue-700">
                            <p>You are currently enrolled in <strong class="font-medium"><span id="total-credits">0</span> credits</strong></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const allCoursesData = <?= json_encode($available_courses) ?>;
    let initialEnrolledIds = new Set();

    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.course-checkbox:checked').forEach(cb => {
            initialEnrolledIds.add(cb.value);
        });

        renderEnrolledCoursesTable();
    });

    function renderEnrolledCoursesTable() {
        const enrolledBody = document.getElementById('enrolled-courses-body');
        enrolledBody.innerHTML = '';
        let totalCredits = 0;

        const enrolledCourses = allCoursesData.filter(course => initialEnrolledIds.has(String(course.course_id)));

        if (enrolledCourses.length > 0) {
            enrolledCourses.forEach(course => {
                const enrollDate = course.enroll_date ? new Date(course.enroll_date).toLocaleDateString('id-ID') : '-';
                const newRow = `
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${course.course_name}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">${course.credits} Credits</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${enrollDate}</td>
                    </tr>`;
                enrolledBody.innerHTML += newRow;
                totalCredits += parseInt(course.credits);
            });
        } else {
            enrolledBody.innerHTML = `<tr><td colspan="3" class="px-6 py-8 text-center text-sm text-gray-500">Belum ada mata kuliah terdaftar.</td></tr>`;
        }

        updateTotalCreditsDisplay(totalCredits);
    }

    function updateTotalCreditsDisplay(total) {
        const totalCreditsElement = document.getElementById('total-credits');
        const totalCreditsInfo = document.getElementById('total-credits-info');
        
        totalCreditsElement.textContent = total;
        if (total > 0) {
            totalCreditsInfo.classList.remove('hidden');
        } else {
            totalCreditsInfo.classList.add('hidden');
        }
    }

    function submitEnrollmentChanges() {
        const currentCheckedIds = new Set();
        document.querySelectorAll('.course-checkbox:checked').forEach(cb => {
            currentCheckedIds.add(cb.value);
        });

        const coursesToEnroll = [...currentCheckedIds].filter(id => !initialEnrolledIds.has(id));
        const coursesToUnenroll = [...initialEnrolledIds].filter(id => !currentCheckedIds.has(id));

        if (coursesToEnroll.length === 0 && coursesToUnenroll.length === 0) {
            showFlashMessage('Tidak ada perubahan yang dilakukan.', 'info');
            return;
        }

        const title = 'Konfirmasi Perubahan';
        const message = 'Apakah Anda yakin ingin menyimpan perubahan pada mata kuliah yang Anda pilih?';
        const confirmAction = () => {
            sendUpdateRequest(coursesToEnroll, coursesToUnenroll);
        };

        showConfirmationModal(title, message, '#', 'blue', confirmAction);
    }

    function sendUpdateRequest(enrollIds, unenrollIds) {
        fetch('/course/batch-update', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                enroll_ids: enrollIds,
                unenroll_ids: unenrollIds
            })
        })
        .then(response => response.json())
        .then(data => {
            showFlashMessage(data.message, data.success ? 'success' : 'error');

            if (data.success) {
                const newDataMap = new Map(data.enrolled_courses.map(c => [String(c.course_id), c]));

                allCoursesData.forEach(course => {
                    const updatedCourseData = newDataMap.get(String(course.course_id));
                    if (updatedCourseData) {
                        course.enroll_date = updatedCourseData.enroll_date;
                    } else {
                        delete course.enroll_date;
                    }
                });
                const updatedEnrolledIds = new Set(data.enrolled_courses.map(c => String(c.course_id)));
                initialEnrolledIds = updatedEnrolledIds;
                
                renderEnrolledCoursesTable();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showFlashMessage('Terjadi kesalahan sistem saat menyimpan perubahan.', 'error');
        });
    }

    function showFlashMessage(message, type) {
        const existingMessages = document.querySelectorAll('.flash-message');
        existingMessages.forEach(msg => msg.remove());
        
        let mainClasses, iconSvg, buttonClasses, buttonIconSvg;

        switch (type) {
            case 'success':
                mainClasses = 'bg-green-50 border border-green-200 text-green-800';
                iconSvg = `<svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>`;
                buttonClasses = 'ml-4 -mr-1 p-1 rounded-md hover:bg-green-100';
                buttonIconSvg = `<svg class="h-5 w-5 text-green-700" viewBox="0 0 20 20" fill="currentColor"><path d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z" /></svg>`;
                break;
            case 'error':
                mainClasses = 'bg-red-50 border border-red-200 text-red-800';
                iconSvg = `<svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" /></svg>`;
                buttonClasses = 'ml-4 -mr-1 p-1 rounded-md hover:bg-red-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-red-50 focus:ring-red-600 transition';
                buttonIconSvg = `<svg class="h-5 w-5 text-red-700" viewBox="0 0 20 20" fill="currentColor"><path d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z" /></svg>`;
                break;
            default:
                mainClasses = 'bg-blue-50 border border-blue-200 text-blue-800';
                iconSvg = `<svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" /></svg>`;
                buttonClasses = 'ml-4 -mr-1 p-1 rounded-md hover:bg-blue-100';
                buttonIconSvg = `<svg class="h-5 w-5 text-blue-700" viewBox="0 0 20 20" fill="currentColor"><path d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z" /></svg>`;
                break;
        }

        const flashMessage = document.createElement('div');
        flashMessage.className = `flash-message mb-6 px-4 py-3 rounded-md ${mainClasses}`;
        flashMessage.setAttribute('role', 'alert');
        
        flashMessage.innerHTML = `
            <div class="flex justify-between items-center">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        ${iconSvg}
                    </div>
                    <div class="ml-3">
                        ${message}
                    </div>
                </div>
                <button type="button" onclick="closeFlashMessage(this)" class="${buttonClasses}">
                    <span class="sr-only">Dismiss</span>
                    ${buttonIconSvg}
                </button>
            </div>
        `;
        
        const mainContent = document.querySelector('main .max-w-7xl');
        mainContent.insertBefore(flashMessage, mainContent.firstChild);
    }
</script>

<?= $this->endSection() ?>