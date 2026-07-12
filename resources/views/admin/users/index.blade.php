<x-app-layout>
    @php($title = 'Kelola User')

    <div class="flex items-center justify-between mb-5 gap-3">
        <form method="GET" class="flex-1 max-w-sm">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama / email..."
                   class="w-full rounded-xl border-stone-300 text-sm focus:ring-amber-500 focus:border-amber-500">
        </form>
        <a href="{{ route('admin.users.trashed') }}" class="text-sm text-stone-500 hover:underline whitespace-nowrap">Lihat Akun Nonaktif</a>
    </div>

    <div class="bg-white rounded-2xl border border-stone-200 shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-stone-50 text-stone-500 text-xs uppercase tracking-wide">
                <tr>
                    <th class="text-left px-5 py-3">Nama</th>
                    <th class="text-left px-5 py-3">Email</th>
                    <th class="text-left px-5 py-3">Role</th>
                    <th class="text-left px-5 py-3">Status Driver</th>
                    <th class="text-right px-5 py-3">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-stone-100">
                @foreach($users as $u)
                    <tr class="hover:bg-stone-50">
                        <td class="px-5 py-3 font-medium text-stone-800">{{ $u->name }}</td>
                        <td class="px-5 py-3 text-stone-600">{{ $u->email }}</td>
                        <td class="px-5 py-3">
                            <form method="POST" action="{{ route('admin.users.role', $u) }}" class="inline">
                                @csrf @method('PATCH')
                                <select name="role" onchange="this.form.submit()" class="rounded-lg border-stone-300 text-xs focus:ring-amber-500 focus:border-amber-500">
                                    <option value="user" @selected($u->roles->pluck('name')->contains('user'))>user</option>
                                    <option value="admin" @selected($u->roles->pluck('name')->contains('admin'))>admin</option>
                                </select>
                            </form>
                        </td>
                        <td class="px-5 py-3 text-stone-600">{{ $u->is_driver_active ? 'Available' : '-' }}</td>
                        <td class="px-5 py-3 text-right">
                            @unless($u->isAdmin())
                            <form method="POST" action="{{ route('admin.users.destroy', $u) }}" onsubmit="return confirm('Nonaktifkan akun ini?')">
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
