<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Tampilkan halaman profil user
     */
    public function show()
    {
        $user = auth('admin')->user();
        return view('profile.show', compact('user'));
    }
    
    /**
     * Update data profil user
     */
    public function update(Request $request)
    {
        $user = auth('admin')->user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('admins')->ignore($user->id)
            ]
        ]);
        
        $user->update([
            'name' => $request->name,
            'email' => $request->email
        ]);
        
        return back()->with('success', 'Profil berhasil diperbarui');
    }
    
    /**
     * Update password user
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed'
        ]);
        
        $user = auth('admin')->user();
        
        // Cek password lama
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->with('error', 'Password saat ini salah');
        }
        
        // Update password baru
        $user->update([
            'password' => Hash::make($request->new_password)
        ]);
        
        return back()->with('success', 'Password berhasil diubah');
    }
}