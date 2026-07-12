<x-app-layout>
    @php($title = 'Akun Nonaktif')

    <div class="bg-white rounded-2xl border border-stone-200 shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-stone-50 text-stone-500 text-xs uppercase tracking-wide">
                <tr>
                    <th class="text-left px-5 py-3">Nama</th>
                    <th class="text-left px-5 py-3">Email</th>
                    <th class="text-left px-5 py-3">Dinonaktifkan</th>
                    <th class="text-right px-5 py-3">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-stone-100">
                @forelse($users as $u)
                    <tr class="hover:bg-stone-50">
                        <td class="px-5 py-3 font-medium text-stone-800">{{ $u->name }}</td>
                        <td class="px-5 py-3 text-stone-600">{{ $u->email }}</td>
                        <td class="px-5 py-3 text-stone-400">{{ $u->deleted_at?->diffForHumans() }}</td>
                        <td class="px-5 py-3 text-right">
                            <form method="POST" action="{{ route('admin.users.restore', $u->id) }}">
                                @csrf @method('PATCH')
                                <button class="text-emerald-700 hover:underline">Aktifkan Kembali</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="px-5 py-8 text-center text-stone-400">Tidak ada akun nonaktif.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $users->links() }}</div>
</x-app-layout>
