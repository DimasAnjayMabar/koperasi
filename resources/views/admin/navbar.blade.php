@extends('app')

@section('content')
    <style>
        #sidebar-overlay {
            -webkit-backdrop-filter: blur(4px); /* For Safari */
            backdrop-filter: blur(4px);
        }
    </style>
    <header class="antialiased">
        <nav class="bg-white border-gray-200 px-4 lg:px-6 py-2.5 dark:bg-gray-800">
            <div class="flex flex-wrap justify-between items-center">
                <div class="flex justify-start items-center">
                    <!-- Hamburger (visible on lg and up) -->
                    <button id="toggleSidebar" aria-controls="sidebar" class="p-2 mr-3 text-gray-600 rounded cursor-pointer hover:text-gray-900 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-700">
                        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 16 12"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1h14M1 6h14M1 11h7"/></svg>
                    </button>

                    <a href="/" class="flex mr-4">
                        <img src="https://flowbite.s3.amazonaws.com/logo.svg" class="mr-3 h-8" alt="FlowBite Logo" />
                        <span class="self-center text-2xl font-semibold whitespace-nowrap dark:text-white">Travel App</span>
                    </a>
                </div>

                <div class="flex items-center lg:order-2">
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

    <!-- Sidebar and Overlay OUTSIDE header/nav -->
    <div id="sidebar-overlay" class="fixed inset-0 z-40 bg-black bg-opacity-30 backdrop-blur-sm hidden"></div>

    <aside id="sidebar" class="fixed top-0 left-0 z-50 w-64 h-full bg-white shadow-md dark:bg-gray-800 transform -translate-x-full transition-transform duration-300">
        <div class="p-4 space-y-4">
            <a href="#" data-table="simpan" class="block px-4 py-2 text-gray-700 rounded hover:bg-gray-200 dark:text-white dark:hover:bg-gray-600">Simpan</a>
            <a href="#" data-table="pinjam" class="block px-4 py-2 text-gray-700 rounded hover:bg-gray-200 dark:text-white dark:hover:bg-gray-600">Pinjam</a>
        </div>
    </aside>

    <div id="table-container" class="p-3 sm:p-5 antialiased">
        @include('admin.simpan') <!-- Default table to show -->
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
                document.getElementById('profile').src = userData.profile_photo_url || 'https://flowbite.com/docs/images/people/profile-picture-5.jpg';
                document.getElementById('name').textContent = userData.name;
        
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
            document.addEventListener('DOMContentLoaded', () => {
                const sidebar = document.getElementById('sidebar');
                const overlay = document.getElementById('sidebar-overlay');
                const toggleButtons = document.querySelectorAll('#toggleSidebar');

                toggleButtons.forEach(btn => {
                    btn.addEventListener('click', () => {
                        sidebar.classList.toggle('-translate-x-full');
                        overlay.classList.toggle('hidden');
                    });
                });

                // Close sidebar when overlay is clicked
                overlay.addEventListener('click', () => {
                    sidebar.classList.add('-translate-x-full');
                    overlay.classList.add('hidden');
                });
            });
        </script>

            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    const links = document.querySelectorAll('[data-table]');
                    const container = document.getElementById('table-container');
                    const sidebar = document.getElementById('sidebar');
                    const overlay = document.getElementById('sidebar-overlay');

                    links.forEach(link => {
                        link.addEventListener('click', function (e) {
                            e.preventDefault();

                            const tableName = this.dataset.table;
                            const url = `/dashboard/${tableName}`;
                            console.log("🔁 Loading:", url);

                            container.innerHTML = '<div class="text-center py-10">Loading...</div>';

                            fetch(url)
                                .then(res => {
                                    if (!res.ok) throw new Error(`Failed to fetch: ${res.status}`);
                                    return res.text();
                                })
                                .then(html => {
                                    container.innerHTML = html;
                                    sidebar.classList.add('-translate-x-full');
                                    overlay.classList.add('hidden');
                                })
                                .catch(err => {
                                    console.error("❌ Error:", err);
                                    container.innerHTML = '<div class="text-center py-10 text-red-500">Error loading table</div>';
                                });
                        });
                    });
                });
            </script>
    @endpush
@endsection