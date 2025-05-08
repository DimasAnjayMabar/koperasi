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
                    Create an account
                </h1>
            </div>

            <!-- Add id="register-form" to your form -->
            <form id="register-form" class="space-y-4">
                <!-- Email -->
                <div>
                    <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Your email</label>
                    <input type="email" id="email" class="w-full p-2.5 rounded-lg border bg-gray-50 text-sm text-gray-900 border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:placeholder-gray-400" placeholder="name@company.com" required>
                </div>

                <!-- Name/Username -->
                <div>
                    <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Your username</label>
                    <input type="text" id="name" class="w-full p-2.5 rounded-lg border bg-gray-50 text-sm text-gray-900 border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:placeholder-gray-400" placeholder="username123" required>
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Password</label>
                    <input type="password" id="password" class="w-full p-2.5 rounded-lg border bg-gray-50 text-sm text-gray-900 border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:placeholder-gray-400" placeholder="••••••••" required>
                    <p id="password-error" class="mt-2 text-sm text-red-600 dark:text-red-500 hidden">
                        <span class="font-medium">Oops!</span> Passwords do not match.
                    </p>
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="confirm-password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Confirm password</label>
                    <input type="password" id="confirm-password" class="w-full p-2.5 rounded-lg border bg-gray-50 text-sm text-gray-900 border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:placeholder-gray-400" placeholder="••••••••" required>
                    <p id="confirm-password-error" class="mt-2 text-sm text-red-600 dark:text-red-500 hidden">
                        <span class="font-medium">Oops!</span> Passwords do not match.
                    </p>
                </div>

                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="file_input">Upload profile picture (optional)</label>
                <input id="profile" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400" id="file_input" type="file" accept="image/*">

                <!-- Submit -->
                <button type="submit" class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Create an account</button>

                <!-- Login -->
                <p class="text-sm font-light text-gray-500 dark:text-gray-400 text-center">
                    Already have an account? <a href="{{ route('login') }}" class="font-medium text-blue-600 hover:underline dark:text-blue-500">Login here</a>
                </p>
            </form>
        </div>
    </section>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const registerForm = document.getElementById('register-form');

                registerForm.addEventListener('submit', async (e) => {
                    e.preventDefault();

                    const name = document.getElementById('name').value;
                    const email = document.getElementById('email').value;
                    const password = document.getElementById('password').value;
                    const confirmPassword = document.getElementById('confirm-password').value;
                    const file = document.getElementById('profile').files[0];

                    const passwordInput = document.getElementById('password');
                    const confirmPasswordInput = document.getElementById('confirm-password');
                    const passwordError = document.getElementById('password-error');
                    const confirmPasswordError = document.getElementById('confirm-password-error');

                    // Reset styles and hide errors
                    [passwordInput, confirmPasswordInput].forEach(el => {
                        el.classList.remove('border-red-500', 'bg-red-50', 'text-red-900');
                    });
                    passwordError.classList.add('hidden');
                    confirmPasswordError.classList.add('hidden');

                    // ✅ Validate passwords match
                    if (password !== confirmPassword) {
                        [passwordInput, confirmPasswordInput].forEach(el => {
                            el.classList.add('border-red-500', 'bg-red-50', 'text-red-900');
                        });
                        passwordError.classList.remove('hidden');
                        confirmPasswordError.classList.remove('hidden');
                        return;
                    }

                    try {
                        const { data: signupData, error: signupError } = await supabase.auth.signUp({
                            email,
                            password
                        });

                        if (signupError) throw signupError;

                        const user = signupData?.user;
                        if (!user) throw new Error('Supabase registration failed.');

                        const formData = new FormData();
                        formData.append('id', user.id);
                        formData.append('email', email);
                        formData.append('name', name);
                        formData.append('profile_photo_url', file);

                        const response = await fetch('/register/new-user', {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            }
                        });

                        if (!response.ok) {
                            const data = await response.json();
                            throw new Error(data.message || 'Failed to register user in MySQL');
                        }

                        window.location.href = "{{ route('verify-email') }}";

                    } catch (error) {
                        console.error('Registration failed:', error);
                    }
                });
            });
        </script>
    @endpush
@endsection
