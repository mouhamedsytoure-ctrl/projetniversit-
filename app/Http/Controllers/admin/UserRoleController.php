<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class UserRoleController extends Controller
{
    public function index()
    {
        $users = User::latest()->paginate(10);
        $roles = Role::orderBy('name')->pluck('name'); // ['admin','etudiant',...]

        return view('admin.dashboard', compact('users', 'roles'));
    }

    public function updateRole(Request $request, User $user)
    {
        $request->validate([
            'role' => ['required', 'string', 'exists:roles,name'],
        ]);

        // Empêche de se retirer admin à soi-même (sécurité basique)
        if (auth()->id() === $user->id && $user->hasRole('admin') && $request->role !== 'admin') {
            return back()->with('error', "Tu ne peux pas retirer ton propre rôle admin.");
        }

        $user->syncRoles([$request->role]);

        return back()->with('success', "Rôle mis à jour pour {$user->name} : {$request->role}");
    }
}
