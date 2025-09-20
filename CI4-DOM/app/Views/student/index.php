<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Manage Students</h1>
            <p class="mt-2 text-gray-600">View and manage all student accounts</p>
        </div>
        <a href="/student/create" 
           class="inline-flex items-center px-4 py-2 bg-primary text-white text-sm font-medium rounded-md hover:bg-blue-700 transition-colors">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Add New Student
        </a>
    </div>

    <div class="bg-white shadow overflow-hidden sm:rounded-md">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-300">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Full Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Username</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Entry Year</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php if (!empty($students)): ?>
                        <?php foreach ($students as $student): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= $student['student_id'] ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= $student['full_name'] ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= $student['username'] ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= $student['entry_year'] ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-4">
                                <a href="/student/view/<?= $student['student_id'] ?>" class="text-blue-600 hover:text-blue-900">View</a>
                                <a href="/student/edit/<?= $student['student_id'] ?>" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                <button onclick="showConfirmationModal('Hapus Mahasiswa', 'PERHATIAN: Menghapus mahasiswa juga akan menghapus semua data KRS-nya. Lanjutkan?', '/student/delete/<?= $student['student_id'] ?>', 'red')" 
                                        class="text-red-600 hover:text-red-900">
                                    Delete
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-sm text-gray-500">
                                <div class="flex flex-col items-center">
                                    <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                    <p class="font-semibold">No Students Found</p>
                                    <p class="text-xs text-gray-400 mt-1">Get started by adding a new student.</p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>