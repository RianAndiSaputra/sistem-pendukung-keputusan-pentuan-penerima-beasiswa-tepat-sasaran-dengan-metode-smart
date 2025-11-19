<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    // HAPUS CONSTRUCTOR INI DULU
    // public function __construct()
    // {
    //     $this->middleware('super_admin');
    // }

    public function index()
    {
        // Tambahkan manual check untuk super admin
        if (auth('admin')->user()->role !== 'super_admin') {
            return redirect()->route('dashboard')->with('error', 'Akses ditolak. Hanya Super Admin yang dapat mengakses halaman ini.');
        }

        $admins = Admin::where('id', '!=', auth('admin')->id())->get();
        return view('admin.index', compact('admins'));
    }

    public function create()
    {
        // Manual check
        if (auth('admin')->user()->role !== 'super_admin') {
            return redirect()->route('dashboard')->with('error', 'Akses ditolak. Hanya Super Admin yang dapat mengakses halaman ini.');
        }
        
        return view('admin.create');
    }

    public function store(Request $request)
    {
        // Manual check
        if (auth('admin')->user()->role !== 'super_admin') {
            return redirect()->route('dashboard')->with('error', 'Akses ditolak. Hanya Super Admin yang dapat mengakses halaman ini.');
        }

        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:admins',
            'password' => 'required|min:6',
            'role' => 'required|in:super_admin,operator,viewer'
        ]);

        Admin::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role
        ]);

        return redirect()->route('admin.index')->with('success', 'Admin berhasil ditambahkan.');
    }

    public function edit(Admin $admin)
    {
        // Manual check
        if (auth('admin')->user()->role !== 'super_admin') {
            return redirect()->route('dashboard')->with('error', 'Akses ditolak. Hanya Super Admin yang dapat mengakses halaman ini.');
        }
        
        return view('admin.edit', compact('admin'));
    }

    public function update(Request $request, Admin $admin)
    {
        // Manual check
        if (auth('admin')->user()->role !== 'super_admin') {
            return redirect()->route('dashboard')->with('error', 'Akses ditolak. Hanya Super Admin yang dapat mengakses halaman ini.');
        }

        $request->validate([
            'name' => 'required',
            'email' => [
                'required',
                'email',
                Rule::unique('admins')->ignore($admin->id)
            ],
            'password' => 'nullable|min:6',
            'role' => 'required|in:super_admin,operator,viewer'
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role
        ];

        if ($request->password) {
            $data['password'] = Hash::make($request->password);
        }

        $admin->update($data);

        return redirect()->route('admin.index')->with('success', 'Admin berhasil diperbarui.');
    }

    public function destroy(Admin $admin)
    {
        // Manual check
        if (auth('admin')->user()->role !== 'super_admin') {
            return redirect()->route('dashboard')->with('error', 'Akses ditolak. Hanya Super Admin yang dapat mengakses halaman ini.');
        }

        if ($admin->id === auth('admin')->id()) {
            return redirect()->route('admin.index')->with('error', 'Tidak dapat menghapus akun sendiri.');
        }

        $admin->delete();

        return redirect()->route('admin.index')->with('success', 'Admin berhasil dihapus.');
    }
}