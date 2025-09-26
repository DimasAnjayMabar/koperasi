@extends('user_page.app')

@section('content')
<div class="max-w-md mx-auto mt-10 p-6 bg-white rounded-lg shadow dark:bg-gray-800">
    <h2 class="text-xl font-bold mb-4 text-gray-900 dark:text-white">Change Email</h2>

    <form id="change-email-form" class="space-y-4">
        <div>
            <label for="new-email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                New Email
            </label>
            <input type="email" id="new-email" name="new-email" required
                   class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg 
                          block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 
                          dark:placeholder-gray-400 dark:text-white 
                          focus:ring-primary-600 focus:border-primary-600">
        </div>
        <button type="submit"
                class="w-full text-gray-500 inline-flex items-center justify-center bg-white hover:bg-gray-100 
                       focus:ring-4 focus:outline-none focus:ring-primary-300 rounded-lg border border-gray-200 
                       text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 
                       dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 
                       dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">
            Update Email
        </button>
        <a href="{{ route('member-profile') }}" class="w-full text-gray-500 inline-flex items-center justify-center bg-white hover:bg-gray-100 
                       focus:ring-4 focus:outline-none focus:ring-primary-300 rounded-lg border border-gray-200 
                       text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 
                       dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 
                       dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">
            Back
        </a>
    </form>

    <p id="change-email-msg" class="mt-4 text-sm text-gray-500 dark:text-gray-400 hidden"></p>
</div>

@push('scripts')
<!-- Auth Script -->
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

                const response = await fetch('/get-member', {
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
                document.getElementById('profile-name').textContent = userData.name;
                document.getElementById('profile-phone').textContent = userData.phone;
                document.getElementById('profile-email').textContent = userData.email;
                document.getElementById('profile-photo').src = userData.profile || 'https://flowbite.com/docs/images/people/profile-picture-5.jpg';
        
                // Sign-out handling
                const signOutLinks = [
                    document.getElementById('sign-out'),
                    document.getElementById('signout-btn')
                ];

                signOutLinks.forEach(link => {
                    if (link) {
                        link.addEventListener('click', async (event) => {
                        event.preventDefault();
                        await window.supabase.auth.signOut();
                        sessionStorage.removeItem('loggedIn'); // Clear session flag
                        window.location.href = '/';
                    });
                }
            });
        });
        </script>

<script>
document.getElementById("change-email-form").addEventListener("submit", async (e) => {
    e.preventDefault();

    const newEmail = document.getElementById("new-email").value;
    const msgEl = document.getElementById("change-email-msg");

    if (!newEmail) {
        alert("Please enter your new email.");
        return;
    }

    // Panggil Supabase updateUser
    const { data, error } = await supabase.auth.updateUser({ email: newEmail }, { emailRedirectTo: window.location.origin + '/member/email-updated' });

    if (error) {
        console.error(error);
        msgEl.textContent = "Failed: " + error.message;
        msgEl.classList.remove("hidden");
        return;
    }

    // Kalau sukses → Supabase sudah kirim email verifikasi otomatis
    msgEl.textContent = "Please check your new email inbox to confirm the change. Email will only be updated after verification.";
    msgEl.classList.remove("hidden");

    // Optional: disable form setelah submit
    document.getElementById("new-email").disabled = true;
});
</script>
@endpush
@endsection
