@extends('layouts.app')

@section('title', 'Dashboard Admin')
@section('breadcrumb', 'Admin / Dashboard')

@section('content')

<div class="bg-white dark:bg-[#1a2230] rounded-xl border border-[#e7ebf3] dark:border-gray-800 p-6 shadow-sm mb-6">
    <h1 class="text-3xl font-bold text-[#0d121b] dark:text-white">Dashboard Administrateur</h1>
    <p class="text-[#4c669a] dark:text-gray-400 mt-2">
        Bienvenue <span class="font-semibold text-[#0d121b] dark:text-white">{{ auth()->user()->name }}</span>
        — Rôle: <span class="font-semibold">{{ auth()->user()->getRoleNames()->implode(', ') }}</span>
    </p>
</div>

<div class="bg-white dark:bg-[#1a2230] rounded-xl border border-[#e7ebf3] dark:border-gray-800 p-6 shadow-sm">

    <div class="mb-6">
        <h2 class="text-2xl font-bold text-[#0d121b] dark:text-white">Gestion des utilisateurs & rôles</h2>
        <p class="text-[#4c669a] dark:text-gray-400 mt-1">Modifier le rôle d’un utilisateur</p>
    </div>

    {{-- Messages --}}
    @if(session('success'))
        <div class="mb-4 rounded-md bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 px-4 py-2">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-4 rounded-md bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200 px-4 py-2">
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mb-4 rounded-md bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200 px-4 py-2">
            <ul class="list-disc pl-5">
                @foreach($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Tableau --}}
    <div class="overflow-x-auto">
        <table class="min-w-full border border-[#e7ebf3] dark:border-gray-700 rounded-lg overflow-hidden">
            <thead class="bg-[#f6f8fc] dark:bg-[#111827] text-[#0d121b] dark:text-gray-200">
                <tr>
                    <th class="px-4 py-3 text-left text-sm font-semibold">ID</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold">Nom</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold">Email</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold">Rôle actuel</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold">Changer le rôle</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-[#e7ebf3] dark:divide-gray-700">
            @forelse($users as $user)
                @php
                    $currentRole = $user->getRoleNames()->first() ?? '—';
                @endphp
                <tr class="hover:bg-[#f9fbff] dark:hover:bg-[#1f2937]">
                    <td class="px-4 py-3 text-sm">{{ $user->id }}</td>
                    <td class="px-4 py-3 text-sm font-medium">{{ $user->name }}</td>
                    <td class="px-4 py-3 text-sm">{{ $user->email }}</td>

                    <td class="px-4 py-3 text-sm">
                        <span class="inline-flex items-center rounded-md bg-blue-100 dark:bg-blue-900 px-2 py-1 text-xs font-semibold text-blue-700 dark:text-blue-200">
                            {{ $currentRole }}
                        </span>
                    </td>

                    <td class="px-4 py-3">
                        <form method="POST"
                              action="{{ route('admin.users.role.update', $user) }}"
                              class="flex items-center gap-2">
                            @csrf
                            @method('PUT')

                            <select name="role"
                                    class="rounded-md border border-[#dbe1ef] dark:border-gray-700 bg-white dark:bg-[#111827] px-3 py-1.5 text-sm text-[#0d121b] dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    required>
                                @foreach($roles as $role)
                                    <option value="{{ $role }}" @selected($role === $currentRole)>
                                        {{ $role }}
                                    </option>
                                @endforeach
                            </select>

                            <button type="submit"
                                    class="rounded-md bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-3 py-1.5 transition">
                                Enregistrer
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-4 py-6 text-center text-sm text-gray-500">
                        Aucun utilisateur trouvé.
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $users->links() }}
    </div>

</div>

@endsection
