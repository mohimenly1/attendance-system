<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\User; // Import the User model

class AdminController extends Controller
{
    public function dashboard()
    {
        return Inertia::render('admin/Dashboard');
    }
        // --- Add this new method ---
        public function usersIndex()
        {
            // Fetch all users except the admin (user with ID 1, for example)
            $users = User::where('id', '!=', 1)
                         ->orderBy('role')
                         ->orderBy('name')
                         ->get();
    
            return Inertia::render('admin/users/Index', [
                'users' => $users,
            ]);
        }
}