@extends('user_page.app')

@section('content')
<div class="max-w-md mx-auto mt-10 p-6 bg-white rounded-lg shadow dark:bg-gray-800 text-center">
    <h2 class="text-xl font-bold mb-4 text-gray-900 dark:text-white">Email Updated</h2>
    <p class="text-gray-600 dark:text-gray-300">
        Your email has been verified successfully. Please log in again 
    </p>
    <button id="go-profile" class="mt-5 px-5 py-2.5 rounded-lg border text-sm font-medium bg-white hover:bg-gray-100 dark:bg-gray-700 dark:text-gray-300">
        Go to Profile
    </button>
</div>

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", async () => {
    const { data: { user }, error } = await supabase.auth.getUser();

    if (error || !user) {
        console.error(error);
        return;
    }

    // Step 1: ambil email baru dari Supabase Auth
    const newEmail = user.email;

    // Step 2: update tabel users di backend
    try {
        const response = await fetch("/member/update-email", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                supabase_id: user.id,
                email: newEmail
            })
        });

        if (!response.ok) throw new Error("Failed to sync email");

        console.log("✅ Email synced with database:", newEmail);
    } catch (err) {
        console.error("DB sync failed:", err);
    }
});

document.getElementById("go-profile").addEventListener("click", () => {
    window.location.href = "{{ route('member-profile') }}";
});
</script>
@endpush
@endsection
