@extends('app')

@section('content')
    <section class="h-screen flex items-center justify-center bg-gray-50 dark:bg-gray-900">
        <div class="w-full max-w-md p-6 space-y-4 bg-white rounded-lg shadow dark:border dark:bg-gray-800 dark:border-gray-700">
            <div class="text-center">
                <a href="#" class="flex justify-center items-center mb-6 text-2xl font-semibold text-gray-900 dark:text-white">
                    <img class="w-8 h-8 mr-2" src="https://flowbite.s3.amazonaws.com/blocks/marketing-ui/logo.svg" alt="logo">
                    Travel App
                </a>
                <h1 class="text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl dark:text-white">
                    Verify your email address
                </h1>
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                    We’ve sent a verification link to your email. Please check your inbox and click the link to activate your account.
                </p>

                <!-- Supabase status message (updated via JavaScript) -->
                <div id="verification-status" class="hidden mt-4 text-sm text-green-600 dark:text-green-400"></div>
            </div>

            <!-- Replace Laravel form with Supabase JavaScript -->
            <button 
                id="resend-button" 
                onclick="resendVerificationEmail()" 
                class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
            >
                Resend Verification Email
            </button>
        </div>
    </section>

    @push('scripts')
        <!-- Add Supabase JavaScript -->
        <script>
            async function resendVerificationEmail() {
                // const user = supabase.auth.user(); // For Supabase v1
                const { data: { user } } = await supabase.auth.getUser();

                const statusEl = document.getElementById('verification-status');

                if (!user) {
                    statusEl.textContent = 'You must be signed in to resend the verification email.';
                    statusEl.classList.remove('text-green-600', 'dark:text-green-400');
                    statusEl.classList.add('text-red-600', 'dark:text-red-400');
                    statusEl.classList.remove('hidden');
                    return;
                }

                const { error } = await supabase.auth.updateUser({ email: user.email });

                if (error) {
                    statusEl.textContent = 'Failed to resend. Please try again.';
                    statusEl.classList.remove('text-green-600', 'dark:text-green-400');
                    statusEl.classList.add('text-red-600', 'dark:text-red-400');
                } else {
                    statusEl.textContent = 'A new verification link has been sent to your email address.';
                    statusEl.classList.remove('text-red-600', 'dark:text-red-400');
                    statusEl.classList.add('text-green-600', 'dark:text-green-400');
                }
                statusEl.classList.remove('hidden');
            }
        </script>
    @endpush
@endsection