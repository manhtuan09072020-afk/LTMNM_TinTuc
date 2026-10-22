<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UserRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $keyword = trim((string) $request->query('q'));
        $role = $request->query('role');

        $users = User::withCount(['posts', 'comments'])
            ->when($keyword, fn ($q) => $q->where(fn ($w) => $w->where('name', 'like', "%{$keyword}%")->orWhere('email', 'like', "%{$keyword}%")))
            ->when(in_array($role, [User::ROLE_ADMIN, User::ROLE_USER], true), fn ($q) => $q->where('role', $role))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.users.index', compact('users', 'keyword', 'role'));
    }

    public function create(): View
    {
        return view('admin.users.form', ['user' => new User(['role' => User::ROLE_USER])]);
    }

    public function store(UserRequest $request): RedirectResponse
    {
        User::create($request->validated());

        return redirect()->route('admin.users.index')->with('success', 'Đã thêm tài khoản mới.');
    }

    public function edit(User $user): View
    {
        return view('admin.users.form', compact('user'));
    }

    public function update(UserRequest $request, User $user): RedirectResponse
    {
        $data = $request->validated();

        if (empty($data['password'])) {
            unset($data['password']);
        }

        // Không cho admin tự hạ quyền của chính mình
        if ($user->is($request->user())) {
            $data['role'] = User::ROLE_ADMIN;
        }

        $user->update($data);

        return redirect()->route('admin.users.index')->with('success', 'Đã cập nhật tài khoản.');
    }

    public function destroy(Request $request, User $user): RedirectResponse
    {
        if ($user->is($request->user())) {
            return back()->with('error', 'Bạn không thể tự xóa tài khoản của mình.');
        }

        if ($user->posts()->withTrashed()->exists()) {
            return back()->with('error', 'Không thể xóa: tài khoản này là tác giả của bài viết.');
        }

        $user->delete();

        return back()->with('success', 'Đã xóa tài khoản.');
    }
}
