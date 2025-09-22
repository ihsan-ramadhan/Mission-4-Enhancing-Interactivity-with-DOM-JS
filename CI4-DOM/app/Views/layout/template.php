<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? $title : 'Campus App' ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#2563eb',
                        secondary: '#64748b'
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">
    <!-- Navbar -->
    <?php if (session()->get('logged_in')): ?>
    <?php
        $current_path = uri_string();

        $activeClass = 'text-primary border-b-2 border-primary';
        $inactiveClass = 'text-gray-700 hover:text-primary border-b-2 border-transparent';
    ?>
    <nav class="bg-white shadow-lg border-b">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between h-16">
                <div class="flex items-center space-x-8">
                    <div class="flex-shrink-0">
                        <span class="text-xl font-bold text-primary">Campus App</span>
                    </div>
                    <div class="hidden md:flex items-center space-x-4">
                        
                        <a href="/dashboard" 
                           class="<?= ($current_path == 'dashboard') ? $activeClass : $inactiveClass ?> px-3 py-2 text-sm font-medium transition-colors">
                           Dashboard
                        </a>

                        <?php if (session()->get('role') === 'admin'): ?>
                        <a href="/course" 
                           class="<?= (str_starts_with($current_path, 'course')) ? $activeClass : $inactiveClass ?> px-3 py-2 text-sm font-medium transition-colors">
                           Manage Courses
                        </a>
                        
                        <a href="/student" 
                           class="<?= (str_starts_with($current_path, 'student')) ? $activeClass : $inactiveClass ?> px-3 py-2 text-sm font-medium transition-colors">
                           Manage Students
                        </a>
                        <?php endif; ?>

                    </div>
                </div>
                <div class="hidden md:flex items-center space-x-4">
                    <span class="text-sm text-gray-600">
                        Welcome, <strong class="font-medium"><?= session()->get('full_name') ?></strong> 
                        (<span class="text-primary">
                            <?= ucfirst(session()->get('role')) ?>
                        </span>)
                    </span>

                    <button onclick="showConfirmationModal('Konfirmasi Logout', 'Apakah Anda yakin ingin keluar?', '/logout', 'red')"
                            class="bg-red-600 text-white hover:bg-red-700 px-3 py-2 rounded-md text-sm font-medium transition-colors">
                        Logout
                    </button>
                </div>
            </div>
        </div>
    </nav>
    <?php endif; ?>

    <!-- Main Content -->
    <main class="flex-1 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Flash Messages -->
            <?php if (session()->getFlashdata('success')): ?>
                <div class="flash-message mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-md" role="alert">
                    <div class="flex justify-between items-center">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <?= session()->getFlashdata('success') ?>
                            </div>
                        </div>
                        <button type="button" onclick="closeFlashMessage(this)" class="ml-4 -mr-1 p-1 rounded-md hover:bg-green-100">
                            <span class="sr-only">Dismiss</span>
                            <svg class="h-5 w-5 text-green-700" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z" />
                            </svg>
                        </button>
                    </div>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="flash-message mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-md" role="alert">
                    <div class="flex justify-between items-center">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <?= session()->getFlashdata('error') ?>
                            </div>
                        </div>
                        <button type="button" onclick="closeFlashMessage(this)" class="ml-4 -mr-1 p-1 rounded-md hover:bg-red-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-red-50 focus:ring-red-600 transition">
                            <span class="sr-only">Dismiss</span>
                            <svg class="h-5 w-5 text-red-700" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z" />
                            </svg>
                        </button>
                    </div>
                </div>
            <?php endif; ?>

            <?= $this->renderSection('content') ?>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t mt-auto">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <div class="text-center text-gray-500 text-sm">
                <p>&copy; 2025 Campus App. All rights reserved.</p>
            </div>
        </div>
        <div id="confirmationModal" class="fixed inset-0 bg-gray-900 bg-opacity-60 flex items-center justify-center hidden z-50 transition-opacity duration-300 ease-in-out opacity-0">
        <div class="bg-white rounded-lg shadow-xl p-6 m-4 max-w-sm w-full transform transition-all duration-300 ease-in-out scale-95">
            <div class="flex items-center">
                <div class="flex-shrink-0 h-10 w-10 rounded-full bg-red-100 flex items-center justify-center">
                    <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 id="modalTitle" class="text-lg font-medium text-gray-900">Konfirmasi Tindakan</h3>
                </div>
            </div>
            <p id="modalMessage" class="text-sm text-gray-600 mt-4">Apakah Anda yakin ingin melanjutkan tindakan ini? Aksi ini tidak dapat diurungkan.</p>
            <div class="mt-6 flex justify-end space-x-3">
                <button id="modalCancel" type="button" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 transition">
                    Batal
                </button>
                <a id="modalConfirm" href="#" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition">
                    Yakin
                </a>
            </div>
        </div>
    </div>
    </footer>

    <script>
        const modal = document.getElementById('confirmationModal');
        const modalTitle = document.getElementById('modalTitle');
        const modalMessage = document.getElementById('modalMessage');
        const modalConfirm = document.getElementById('modalConfirm');
        const modalCancel = document.getElementById('modalCancel');

        function showConfirmationModal(title, message, confirmUrl, buttonColor = 'red', confirmAction = null) {
            modalTitle.textContent = title;
            modalMessage.textContent = message;

            modalConfirm.className = `px-4 py-2 text-white rounded-md`;
            if (buttonColor === 'red') {
                modalConfirm.classList.add('bg-red-600', 'hover:bg-red-700');
            } else if (buttonColor === 'green') {
                modalConfirm.classList.add('bg-green-600', 'hover:bg-green-700');
            } else {
                modalConfirm.classList.add('bg-blue-600', 'hover:bg-blue-700');
            }

            if (confirmAction && typeof confirmAction === 'function') {
                modalConfirm.href = '#'
                modalConfirm.onclick = (event) => {
                    event.preventDefault();
                    confirmAction();
                    hideModal();
                };
            } else {
                modalConfirm.href = confirmUrl;
                modalConfirm.onclick = null;
            }

            modal.classList.remove('hidden');
            setTimeout(() => {
                modal.classList.remove('opacity-0');
                modal.querySelector('div').classList.remove('scale-95');
            }, 10);
        }

        function hideModal() {
            modal.classList.add('opacity-0');
            modal.querySelector('div').classList.add('scale-95');
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        }

        function closeFlashMessage(buttonElement) {
            const flashMessageDiv = buttonElement.closest('.flash-message');
            if (flashMessageDiv) {
                flashMessageDiv.style.transition = 'opacity 0.3s ease-out';
                flashMessageDiv.style.opacity = '0';
                setTimeout(() => {
                    flashMessageDiv.remove();
                }, 300);
            }
        }
        
        modalCancel.addEventListener('click', hideModal);

        const logoutButton = document.querySelector('button[onclick="logout()"]');
        if (logoutButton) {
            logoutButton.setAttribute('onclick', "showConfirmationModal('Konfirmasi Logout', 'Apakah Anda yakin ingin keluar?', '/logout', 'red')");
        }
    </script>
</body>
</html>