<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;

class MemberController extends Controller
{
    public function index()
    {
        $members = User::where('role', 'member')
            ->withCount('downlines')
            ->latest()
            ->paginate(15);

        return view('admin.members', compact('members'));
    }
}
