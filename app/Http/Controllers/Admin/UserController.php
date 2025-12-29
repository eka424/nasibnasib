<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UserRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role:admin']);
    }

    public function index(): View
    {
        $totalUsers = User::count();
        $activeUsers = User::count(); // Assuming all users are active for now based on the provided view example.
        // If there's a 'status' column, it would be User::where('status', 'active')->count();

        $users = User::query()
            ->latest()
            ->paginate(10);

        return view('kelola-user', compact('users', 'totalUsers', 'activeUsers'));
    }

    public function create(): View
    {
        $roles = ['admin', 'pengurus', 'ustadz', 'jamaah'];

        return view('admin.users.create', compact('roles'));
    }

    public function store(UserRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);

        User::create($data);

        return redirect()->route('admin.users.index')->with('success', 'User berhasil dibuat.');
    }

    public function show(User $user): View
    {
        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user): View
    {
        $roles = ['admin', 'pengurus', 'ustadz', 'jamaah'];

        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(UserRequest $request, User $user): RedirectResponse
    {
        $data = $request->validated();
        $this->guardLastAdmin($user, $data['role']);

        if (blank($data['password'])) {
            unset($data['password']);
        } else {
            $data['password'] = Hash::make($data['password']);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')->with('success', 'User berhasil diperbarui.');
    }

    public function destroy(User $user): RedirectResponse
    {
        $this->guardLastAdmin($user);

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User berhasil dihapus.');
    }

    protected function guardLastAdmin(User $user, ?string $nextRole = null): void
    {
        if ($user->role !== 'admin') {
            return;
        }

        $adminCount = User::where('role', 'admin')->count();

        $isRemovingAdminRole = $nextRole === null || $nextRole !== 'admin';

        if ($adminCount <= 1 && $isRemovingAdminRole) {
            throw ValidationException::withMessages([
                'role' => 'Admin terakhir tidak boleh dihapus atau diubah.',
            ]);
        }
    }
}
