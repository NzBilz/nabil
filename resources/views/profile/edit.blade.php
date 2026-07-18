<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Profil') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <section>
                        <header>
                            <h2 class="text-lg font-medium text-gray-900">
                                {{ __('Informasi Profil') }}
                            </h2>

                            <p class="mt-1 text-sm text-gray-600">
                                {{ __("Perbarui nama, alamat email, password, dan foto profil Anda.") }}
                            </p>
                        </header>

                        <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="mt-6 space-y-6">
                            @csrf
                            @method('patch')

                            <!-- Foto Profil / Avatar -->
                            <div>
                                <x-input-label for="avatar" :value="__('Foto Profil')" />
                                
                                <div class="flex items-center gap-4 mt-2">
                                    @if ($user->avatar)
                                        <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}" class="w-20 h-20 rounded-full object-cover border-2 border-gray-200">
                                    @else
                                        <div class="w-20 h-20 rounded-full bg-orange-100 flex items-center justify-center text-orange-600 font-bold text-2xl border-2 border-gray-200">
                                            {{ strtoupper(substr($user->name, 0, 2)) }}
                                        </div>
                                    @endif
                                    
                                    <div>
                                        <input type="file" id="avatar" name="avatar" class="text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-orange-50 file:text-orange-700 hover:file:bg-orange-100" accept="image/*">
                                        <p class="text-xs text-gray-500 mt-1">PNG, JPG, JPEG maks. 2MB</p>
                                    </div>
                                </div>
                                <x-input-error class="mt-2" :messages="$errors->get('avatar')" />
                            </div>

                            <!-- Name -->
                            <div>
                                <x-input-label for="name" :value="__('Nama')" />
                                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
                                <x-input-error class="mt-2" :messages="$errors->get('name')" />
                            </div>

                            <!-- Email -->
                            <div>
                                <x-input-label for="email" :value="__('Email')" />
                                <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
                                <x-input-error class="mt-2" :messages="$errors->get('email')" />
                            </div>

                            <!-- Password Section -->
                            <div class="border-t border-gray-100 pt-6">
                                <h3 class="text-md font-medium text-gray-900 mb-2">
                                    {{ __('Ubah Password (Opsional)') }}
                                </h3>
                                <p class="text-xs text-gray-500 mb-4">
                                    {{ __('Kosongkan jika Anda tidak ingin mengubah password.') }}
                                </p>

                                <div class="space-y-4">
                                    <div>
                                        <x-input-label for="current_password" :value="__('Password Lama')" />
                                        <x-text-input id="current_password" name="current_password" type="password" class="mt-1 block w-full" autocomplete="current-password" />
                                        <x-input-error :messages="$errors->get('current_password')" class="mt-2" />
                                    </div>

                                    <div>
                                        <x-input-label for="password" :value="__('Password Baru')" />
                                        <x-text-input id="password" name="password" type="password" class="mt-1 block w-full" autocomplete="new-password" />
                                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                                    </div>

                                    <div>
                                        <x-input-label for="password_confirmation" :value="__('Konfirmasi Password Baru')" />
                                        <x-text-input id="password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full" autocomplete="new-password" />
                                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                                    </div>
                                </div>
                            </div>

                            <!-- Save Button and status -->
                            <div class="flex items-center gap-4 border-t border-gray-100 pt-6">
                                <x-primary-button class="bg-orange-500 hover:bg-orange-600 focus:bg-orange-600 active:bg-orange-700">
                                    {{ __('Simpan Perubahan') }}
                                </x-primary-button>

                                @if (session('status') === 'profile-updated')
                                    <p
                                        x-data="{ show: true }"
                                        x-show="show"
                                        x-transition
                                        x-init="setTimeout(() => show = false, 3000)"
                                        class="text-sm text-emerald-600 font-medium"
                                    >{{ __('Profil berhasil diperbarui.') }}</p>
                                @endif
                            </div>
                        </form>
                    </section>
                </div>
            </div>

            <!-- Delete Account Section -->
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
