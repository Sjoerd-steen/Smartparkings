<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserManagementController extends Controller {

    public function index(Request $request) {
        $query = User::where('role', 'user');

        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                    ->orWhere('email', 'like', "%{$request->search}%");
            });
        }

        $users = $query->withCount('reservations')->latest()->paginate(15);
        return view('admin.users.index', compact('users'));
    }

    public function edit(User $user) {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user) {
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role'  => 'required|in:user,admin',
        ]);

        $user->update($request->only('name', 'email', 'role'));
        return redirect()->route('admin.users.index')
            ->with('success', "Gebruiker {$user->name} bijgewerkt.");
    }

    public function ban(User $user) {
        $user->update(['is_banned' => !$user->is_banned]);
        $status = $user->is_banned ? 'geblokkeerd' : 'gedeblokkeerd';
        return redirect()->route('admin.users.index')
            ->with('success', "Gebruiker {$user->name} is {$status}.");
    }

    public function destroy(User $user) {
        $user->delete();
        return redirect()->route('admin.users.index')
            ->with('success', 'Gebruiker verwijderd.');
    }
}
