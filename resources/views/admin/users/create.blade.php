<x-app-layout>
    @php($title = 'Tambah User')

    <div class="max-w-lg">
        <div class="mb-5">
            <a href="{{ route('admin.users.index') }}" class="text-sm text-slate-500 hover:underline">&larr; Kembali ke Kelola User</a>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm transition-all duration-300 hover:shadow-lg hover:-translate-y-1">
            <h2 class="font-semibold text-slate-800 mb-5">Data Akun Baru</h2>

            <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-4">
                @csrf

                <div>
                    <x-input-label value="Nama Lengkap" />
                    <x-text-input name="name" class="w-full mt-1" value="{{ old('name') }}" required autofocus />
                    <x-input-error :messages="$errors->get('name')" class="mt-1" />
                </div>

                <div>
                    <x-input-label value="Email" />
                    <x-text-input type="email" name="email" class="w-full mt-1" value="{{ old('email') }}" required />
                    <x-input-error :messages="$errors->get('email')" class="mt-1" />
                </div>

                <div>
                    <x-input-label value="No. Telepon (opsional)" />
                    <x-text-input type="tel" name="phone" class="w-full mt-1" value="{{ old('phone') }}" placeholder="08xx-xxxx-xxxx" />
                    <x-input-error :messages="$errors->get('phone')" class="mt-1" />
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <x-input-label value="Password" />
                        <x-text-input type="password" name="password" class="w-full mt-1" required />
                        <x-input-error :messages="$errors->get('password')" class="mt-1" />
                    </div>
                    <div>
                        <x-input-label value="Konfirmasi Password" />
                        <x-text-input type="password" name="password_confirmation" class="w-full mt-1" required />
                    </div>
                </div>

                <div>
                    <x-input-label value="Role" />
                    <select name="role" class="w-full mt-1 rounded-xl border-slate-300 text-sm focus:ring-blue-500 focus:border-blue-500">
                        <option value="user" @selected(old('role','user') === 'user')>user — Pengguna biasa</option>
                        <option value="admin" @selected(old('role') === 'admin')>admin — Panel admin</option>
                    </select>
                    <x-input-error :messages="$errors->get('role')" class="mt-1" />
                </div>

                <div class="flex justify-end gap-3 pt-2">
                    <a href="{{ route('admin.users.index') }}" class="px-4 py-2 rounded-xl text-sm text-slate-600 hover:bg-slate-100">Batal</a>
                    <button type="submit" class="px-5 py-2 rounded-xl bg-blue-500 text-[#0f172a] font-semibold text-sm hover:bg-blue-400 transition-all duration-300 ease-in-out hover:scale-105 active:scale-95">
                        Buat Akun
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
