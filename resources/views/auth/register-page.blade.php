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
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="confirm-password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Confirm password</label>
                    <input type="password" id="confirm-password" class="w-full p-2.5 rounded-lg border bg-gray-50 text-sm text-gray-900 border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:placeholder-gray-400" placeholder="••••••••" required>
                </div>

                <!-- Terms -->
                <div class="flex items-start">
                    <div class="flex items-center h-5">
                        <input id="terms" type="checkbox" class="w-4 h-4 border border-gray-300 rounded bg-gray-50 dark:bg-gray-700 dark:border-gray-600" required>
                    </div>
                    <label for="terms" class="ml-2 text-sm font-light text-gray-500 dark:text-gray-300">
                        I accept the <a href="#" class="font-medium text-blue-600 hover:underline dark:text-blue-500">Terms and Conditions</a>
                    </label>
                </div>

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
                    
                    try {
                        // Get form values
                        const email = document.getElementById('email').value;
                        const password = document.getElementById('password').value;
                        const name = document.getElementById('name').value;

                        // Debug: Check if supabase exists
                        console.log(window.supabase); // Should show client object

                        // Call Supabase
                        const { data, error } = await supabase.auth.signUp({
                            email,
                            password,
                            options: {
                                data: {
                                    name: name  // Store name in user_metadata
                                },
                                emailRedirectTo: 'http://localhost:8000/verify-success'
                            }
                        });

                        if (error) throw error;
                        window.location.href = '/verify-email';
                    } catch (error) {
                        alert('Registration failed: ' + error.message);
                        console.error(error);
                    }
                });
            });
        </script>
    @endpush
@endsection