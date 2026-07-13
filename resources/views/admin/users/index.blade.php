<x-app-layout>
    @php($title = 'Kelola User')

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-5">
        <form method="GET" class="flex-1 max-w-sm">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama / email..."
                   class="w-full rounded-xl border-slate-300 text-sm focus:ring-blue-500 focus:border-blue-500">
        </form>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.users.trashed') }}" class="text-sm text-slate-500 hover:underline whitespace-nowrap">Lihat Akun Nonaktif</a>
            <a href="{{ route('admin.users.create') }}" class="inline-flex items-center px-4 py-2 rounded-xl bg-blue-500 text-[#0f172a] font-semibold text-sm hover:bg-blue-400 transition-all duration-300 ease-in-out hover:scale-105 active:scale-95 whitespace-nowrap">
                + Tambah User
            </a>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm transition-all duration-300 hover:shadow-lg hover:-translate-y-1 overflow-hidden transition-all duration-300 hover:shadow-md">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wide">
                <tr>
                    <th class="text-left px-5 py-3">Nama</th>
                    <th class="text-left px-5 py-3">Email</th>
                    <th class="text-left px-5 py-3">No. Telp</th>
                    <th class="text-left px-5 py-3">Role</th>
                    <th class="text-left px-5 py-3">Status Driver</th>
                    <th class="text-right px-5 py-3">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach($users as $u)
                    <tr class="hover:bg-blue-50 transition-colors duration-200">
                        <td class="px-5 py-3 font-medium text-slate-800">{{ $u->name }}</td>
                        <td class="px-5 py-3 text-slate-600">{{ $u->email }}</td>
                        <td class="px-5 py-3 text-slate-400">{{ $u->phone ?? '-' }}</td>
                        <td class="px-5 py-3">
                            <form method="POST" action="{{ route('admin.users.role', $u) }}" class="inline">
                                @csrf @method('PATCH')
                                <select name="role" onchange="this.form.submit()" class="rounded-lg border-slate-300 text-xs focus:ring-blue-500 focus:border-blue-500">
                                    <option value="user" @selected($u->roles->pluck('name')->contains('user'))>user</option>
                                    <option value="admin" @selected($u->roles->pluck('name')->contains('admin'))>admin</option>
                                </select>
                            </form>
                        </td>
                        <td class="px-5 py-3 text-slate-600">{{ $u->is_driver_active ? 'Available' : '-' }}</td>
                        <td class="px-5 py-3 text-right space-x-3 whitespace-nowrap">
                            <a href="{{ route('admin.users.edit', $u) }}" class="text-sky-700 hover:underline text-sm">Edit</a>
                            @unless($u->isAdmin())
                            <form method="POST" action="{{ route('admin.users.destroy', $u) }}" class="inline"
                                  onsubmit="return confirm('Nonaktifkan akun {{ addslashes($u->name) }}? Akun bisa dipulihkan dari halaman Akun Nonaktif.')">
                                @csrf @method('DELETE')
                                <button class="text-rose-600 hover:underline text-sm">Nonaktifkan</button>
                            </form>
                            @endunless
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $users->links() }}</div>
</x-app-layout>
