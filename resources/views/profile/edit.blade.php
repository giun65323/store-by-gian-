@extends('layouts.app')
@php use Illuminate\Support\Facades\Storage; @endphp

@section('title', 'Edit Profile — gian games')

@section('head')
<style>
    .profile-input {
        width: 100%;
        border: none;
        border-bottom: 1.5px solid #d1d5db;
        padding: 8px 0 10px 0;
        font-size: 15px;
        font-weight: 600;
        color: #111827;
        background: transparent;
        outline: none;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }
    .profile-input:focus {
        border-bottom-color: #7c3aed;
    }
    .profile-label {
        display: block;
        font-size: 13px;
        font-weight: 700;
        color: #4b5563;
        margin-bottom: 2px;
    }
    .dark .profile-input {
        color: #f3f4f6;
        border-bottom-color: #4b5563;
    }
    .dark .profile-input:focus {
        border-bottom-color: #38bdf8;
    }
    .dark .profile-label {
        color: #9ca3af;
    }
</style>
@endsection

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 py-8">
    
    <!-- Card Container -->
    <div class="bg-white dark:bg-[#111827] rounded-3xl shadow-sm border border-gray-100 dark:border-gray-800 overflow-hidden">
        
        <!-- Header with Back Arrow -->
        <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-800 flex items-center gap-4">
            <a href="{{ route('dashboard') }}" class="p-2 -ml-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-800 text-gray-700 dark:text-gray-300 transition-colors" title="Kembali ke Dashboard">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <h1 class="text-lg font-black text-gray-900 dark:text-white">
                Edit Profile
            </h1>
        </div>

        <!-- Form Edit Profile -->
        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="p-6 sm:p-8 space-y-7">
            @csrf
            @method('PUT')

            <!-- Success Alert -->
            @if(session('success'))
            <div class="rounded-2xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 px-4 py-3 text-emerald-800 dark:text-emerald-300 text-xs font-bold flex items-center gap-2">
                <span>✅</span>
                <span>{{ session('success') }}</span>
            </div>
            @endif

            <!-- Errors Alert -->
            @if($errors->any())
            <div class="rounded-2xl bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800 px-4 py-3 text-rose-800 dark:text-rose-300 text-xs space-y-1">
                @foreach($errors->all() as $error)
                    <div class="flex items-center gap-1.5 font-bold">
                        <span>⚠️</span>
                        <span>{{ $error }}</span>
                    </div>
                @endforeach
            </div>
            @endif

            <!-- Avatar Section (Matching Screenshot 1) -->
            <div class="space-y-2">
                <div class="relative w-24 h-24 mx-auto sm:mx-0">
                    <!-- Avatar Image / Placeholder -->
                    <div id="avatar-container" class="w-24 h-24 rounded-full overflow-hidden border-2 border-gray-200 dark:border-gray-700 bg-gradient-to-tr from-sky-400 via-indigo-500 to-purple-600 shadow-sm flex items-center justify-center relative">
                        <img id="avatar-preview-img"
                             src="{{ $user->avatar_url ?: '' }}"
                             alt="{{ $user->name }}"
                             class="w-full h-full object-cover {{ empty($user->avatar_url) ? 'hidden' : '' }}"
                             onerror="this.classList.add('hidden'); var fb = document.getElementById('avatar-initial-fallback'); if(fb) fb.classList.remove('hidden');">
                        <div id="avatar-initial-fallback"
                             class="w-full h-full bg-[#08326e] flex items-center justify-center text-white font-black text-3xl {{ !empty($user->avatar_url) ? 'hidden' : '' }}">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                    </div>

                    <!-- Floating Pencil Edit Button -->
                    <label for="avatar" class="absolute bottom-0 right-0 w-8 h-8 rounded-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-md flex items-center justify-center text-gray-700 dark:text-gray-200 hover:scale-110 active:scale-95 transition-transform cursor-pointer" title="Ganti Foto Profil">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                        </svg>
                    </label>

                    <input type="file" id="avatar" name="avatar" accept=".jpg,.jpeg,.png,.gif" class="hidden" onchange="previewAvatar(this)">
                </div>

                <p class="text-[11px] text-gray-400 text-center sm:text-left">
                    Make sure the image is in .JPG / .PNG / .GIF format (max 5MB)
                </p>
            </div>

            <!-- Field 1: Name -->
            <div class="pt-2">
                <label for="name" class="profile-label">Name</label>
                <input type="text"
                       id="name"
                       name="name"
                       value="{{ old('name', $user->name) }}"
                       required
                       placeholder="Masukkan nama lengkap Anda"
                       class="profile-input">
            </div>

            <!-- Field 2: Email (Readonly with Verified Badge) -->
            <div>
                <label class="profile-label">Email</label>
                <div class="relative flex items-center">
                    <input type="email"
                           value="{{ $user->email }}"
                           readonly
                           class="profile-input pr-10 text-gray-600 dark:text-gray-400 cursor-not-allowed bg-transparent select-none">
                    <!-- Blue Verified Checkmark Badge -->
                    <div class="absolute right-0 top-2.5 flex items-center justify-center text-sky-500" title="Email Terverifikasi">
                        <svg class="w-5 h-5 fill-sky-500" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Field 3: Mobile Number -->
            <div>
                <label for="phone_number" class="profile-label">Mobile Number</label>
                <input type="tel"
                       id="phone_number"
                       name="phone_number"
                       value="{{ old('phone_number', $user->phone_number ?? '+62') }}"
                       placeholder="+628123456789"
                       class="profile-input">
            </div>

            <!-- Field 4: Gender -->
            <div>
                <label for="gender" class="profile-label">Gender</label>
                <div class="relative">
                    <select id="gender"
                            name="gender"
                            class="profile-input appearance-none cursor-pointer pr-8">
                        <option value="" {{ empty($user->gender) ? 'selected' : '' }}>- Pilih Jenis Kelamin -</option>
                        <option value="Laki-laki" {{ old('gender', $user->gender) === 'Laki-laki' ? 'selected' : '' }}>Laki-laki (Male)</option>
                        <option value="Perempuan" {{ old('gender', $user->gender) === 'Perempuan' ? 'selected' : '' }}>Perempuan (Female)</option>
                        <option value="Lainnya" {{ old('gender', $user->gender) === 'Lainnya' ? 'selected' : '' }}>Lainnya (Other)</option>
                    </select>
                    <div class="absolute right-0 top-3 pointer-events-none text-gray-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Field 5: Date of Birth -->
            <div>
                <label for="date_of_birth" class="profile-label">Date of birth</label>
                <div class="relative">
                    <input type="date"
                           id="date_of_birth"
                           name="date_of_birth"
                           value="{{ old('date_of_birth', $user->date_of_birth ? $user->date_of_birth->format('Y-m-d') : '') }}"
                           class="profile-input pr-8">
                    <div class="absolute right-0 top-3 pointer-events-none text-gray-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                </div>
                <p class="text-[11px] text-gray-400 mt-2">
                    The data you enter will not be visible to the public.
                </p>
            </div>

            <!-- Submit Button -->
            <div class="pt-4">
                <button type="submit"
                        class="w-full py-3.5 bg-gradient-to-r from-[#7c3aed] to-indigo-700 hover:from-[#6d28d9] hover:to-indigo-800 text-white font-extrabold text-sm rounded-xl shadow-md hover:shadow-lg transition-all hover:scale-[1.01] active:scale-[0.99] flex items-center justify-center gap-2 cursor-pointer">
                    <span>💾</span>
                    <span>Simpan Perubahan Profil</span>
                </button>
            </div>

        </form>

    </div>

</div>

<!-- JavaScript for Live Avatar Preview -->
<script>
function previewAvatar(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            var img = document.getElementById('avatar-preview-img');
            var fallback = document.getElementById('avatar-initial-fallback');
            
            img.src = e.target.result;
            img.classList.remove('hidden');
            if (fallback) {
                fallback.classList.add('hidden');
            }
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection
