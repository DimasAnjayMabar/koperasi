@extends('app')

@section('content')
    <style>
        #sidebar-overlay {
            -webkit-backdrop-filter: blur(4px); /* For Safari */
            backdrop-filter: blur(4px);
        }
    </style>
    <header class="antialiased">
        <nav class="bg-white border-gray-200 px-4 py-2.5 dark:bg-gray-800">
            <div class="flex justify-between items-center">
                <div class="flex items-center">
                    <!-- Hamburger Button (Always Visible) -->
                    <button id="hamburger" type="button" class="inline-flex items-center p-2 text-sm text-gray-500 rounded-lg hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700">
                        <i class="fas fa-bars text-xl"></i> <!-- Font Awesome hamburger icon -->
                    </button>

                    <a href="{{ route('simpan') }}" class="flex mr-4">
                        <img src="https://flowbite.s3.amazonaws.com/logo.svg" class="mr-3 h-8" alt="FlowBite Logo" />
                        <span class="self-center text-2xl font-semibold whitespace-nowrap dark:text-white">Koperasi</span>
                    </a>
                </div>

                <div class="flex items-center">
                    <button type="button" class="flex mx-3 text-sm bg-gray-800 rounded-full focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-600" id="user-menu-button" aria-expanded="false" data-dropdown-toggle="dropdown">
                        <span class="sr-only">Open user menu</span>
                        <img class="w-8 h-8 rounded-full" src="" id="profile" alt="user photo">
                    </button>
                    <div class="hidden z-50 my-4 w-56 text-base list-none bg-white rounded divide-y divide-gray-100 shadow dark:bg-gray-700 dark:divide-gray-600" id="dropdown">
                        <div class="py-3 px-4">
                            <span id="email" class="block text-sm text-gray-500 truncate dark:text-gray-400">loading...</span>
                        </div>
                        <div class="py-3 px-4">
                            <span id="name" class="block text-sm text-gray-500 truncate dark:text-gray-400">loading...</span>
                        </div>
                        <div class="py-3 px-4">
                            <span id="phone" class="block text-sm text-gray-500 truncate dark:text-gray-400">loading...</span>
                        </div>
                        <ul class="py-1 text-gray-500 dark:text-gray-400" aria-labelledby="dropdown">
                            <li>
                                <a href="#" id="sign-out" class="block py-2 px-4 text-sm hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Sign out</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>
    </header>

    <!-- Sidebar Overlay -->
    <div id="sidebar-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden"></div>

    <!-- Sidebar -->
    <aside id="sidebar" class="fixed top-0 left-0 z-50 w-64 h-full bg-white border-r border-gray-200 transform -translate-x-full transition-transform dark:bg-gray-800 dark:border-gray-700">
        <div class="p-4 space-y-2">
            <a href="{{ route('simpan') }}" data-table="simpan" class="block px-4 py-2 rounded text-white hover:bg-gray-100 dark:hover:bg-gray-700">Simpan</a>
            <a href="{{ route('history') }}" data-table="history" class="block px-4 py-2 rounded  text-white hover:bg-gray-100 dark:hover:bg-gray-700">History</a>
            <a href="{{ route('profile') }}" data-table="profile" class="block px-4 py-2 rounded  text-white hover:bg-gray-100 dark:hover:bg-gray-700">Profile</a>
    </aside>

    <!-- Dynamic Content Container -->
    <div id="table-container" class="p-3 sm:p-5 antialiased">
        <!-- Profile -->
        <section class="py-8 bg-white md:py-16 dark:bg-gray-900 antialiased">
          <div class="max-w-screen-xl px-4 mx-auto 2xl:px-0">
            <div class="lg:grid lg:grid-cols-2 lg:gap-8 xl:gap-16 items-center">
              <!-- Image Section -->
              <div class="shrink-0 max-w-md lg:max-w-lg mx-auto">
                <img class="w-full dark:hidden" src="https://flowbite.s3.amazonaws.com/blocks/e-commerce/imac-front.svg" alt="Profile" />
                <img class="w-full hidden dark:block" src="https://flowbite.s3.amazonaws.com/blocks/e-commerce/imac-front-dark.svg" alt="Profile Dark" />
              </div>
      
              <!-- Info Section -->
              <div class="mt-6 sm:mt-8 lg:mt-0">
                <h1 class="text-2xl font-extrabold text-gray-900 dark:text-white mb-4">
                  John Doe
                </h1>
      
                <div class="space-y-4">
                  <div class="flex flex-col sm:flex-row sm:items-center sm:space-x-4">
                    <span class="text-lg font-medium text-gray-500 dark:text-gray-400">Phone:</span>
                    <span class="text-lg font-semibold text-gray-900 dark:text-white">+1 (123) 456-7890</span>
                  </div>
                  <div class="flex flex-col sm:flex-row sm:items-center sm:space-x-4">
                    <span class="text-lg font-medium text-gray-500 dark:text-gray-400">Email:</span>
                    <span class="text-lg font-semibold text-gray-900 dark:text-white">john.doe@example.com</span>
                  </div>
                </div>

                <button type="button" data-drawer-target="edit-staff" data-drawer-show="edit-staff" class="w-full justify-center sm:w-auto text-gray-500 inline-flex items-center bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-primary-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">
                    Back
                </button>
              </div>
            </div>
          </div>
        </section>
    </div>      

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', async () => {
                const { data: { user }, error } = await window.supabase.auth.getUser();
        
                if (!user || error) {
                    // Not authenticated — redirect to welcome
                    window.location.href = '/';
                    return;
                }
        
                // Save auth session state
                sessionStorage.setItem('loggedIn', 'true');
        
                // Prevent back button
                history.pushState(null, null, window.location.href);
                window.addEventListener('popstate', function (event) {
                    if (sessionStorage.getItem('loggedIn') === 'true') {
                        history.pushState(null, null, window.location.href);
                    }
                });

                const response = await fetch('/get-user', {
                    method : 'POST',
                    headers : {
                        'Content-Type' : 'application/json',
                        'X-CSRF-TOKEN' : document.querySelector('meta[name="csrf-token"]').content
                    },
                    body : JSON.stringify({email : user.email})
                });

                const userData = await response.json();

                document.getElementById('email').textContent = userData.email;
                document.getElementById('profile').src = userData.profile || 'https://flowbite.com/docs/images/people/profile-picture-5.jpg';
                document.getElementById('name').textContent = userData.username;
                document.getElementById('phone').textContent = userData.phone;
        
                // Sign-out handling
                const signOut = document.getElementById('sign-out');
                if (signOut) {
                    signOut.addEventListener('click', async (event) => {
                        event.preventDefault();
                        await window.supabase.auth.signOut();
                        sessionStorage.removeItem('loggedIn'); // Clear session flag
                        window.location.href = '/';
                    });
                }
            });
        </script>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const links = document.querySelectorAll('[data-table]');
                const container = document.getElementById('table-container');
                const sidebar = document.getElementById('sidebar');
                const overlay = document.getElementById('sidebar-overlay');
                const toggleButton = document.getElementById('hamburger');

                // Toggle sidebar
                toggleButton.addEventListener('click', function () {
                    sidebar.classList.toggle('-translate-x-full');
                    overlay.classList.toggle('hidden');
                });

                // Close sidebar on overlay click
                overlay.addEventListener('click', function () {
                    sidebar.classList.add('-translate-x-full');
                    overlay.classList.add('hidden');
                });

                // Close sidebar on Escape key press
                document.addEventListener('keydown', function (event) {
                    if (event.key === 'Escape') {
                        sidebar.classList.add('-translate-x-full');
                        overlay.classList.add('hidden');
                    }
                });
            });
        </script>
    @endpush
@endsection