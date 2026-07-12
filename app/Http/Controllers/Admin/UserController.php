<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::withoutTrashed()->with('roles');

        if ($request->filled('q')) {
            $term = $request->get('q');
            $query->where(function ($q) use ($term) {
                $q->where('name', 'like', "%{$term}%")
                  ->orWhere('email', 'like', "%{$term}%");
            });
        }

        $users = $query->latest()->paginate(10)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    public function updateRole(Request $request, User $user)
    {
        $request->validate([
            'role' => ['required', Rule::in(['admin', 'user'])],
        ]);

        $user->syncRoles([$request->role]);

        return back()->with('success', "Role {$user->name} diubah jadi {$request->role}.");
    }

    /** Nonaktifkan akun (soft delete), bukan hapus permanen. */
    public function destroy(User $user)
    {
        abort_if($user->isAdmin(), 422, 'Akun admin tidak bisa dinonaktifkan lewat sini.');
        $user->delete();

        return back()->with('success', "Akun {$user->name} dinonaktifkan.");
    }

    public function restore($id)
    {
        $user = User::onlyTrashed()->findOrFail($id);
        $user->restore();

        return back()->with('success', "Akun {$user->name} diaktifkan kembali.");
    }

    public function trashed()
    {
        $users = User::onlyTrashed()->latest()->paginate(10);

        return view('admin.users.trashed', compact('users'));
    }
}
