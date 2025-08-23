<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Proposal;

class ApiTestController extends Controller
{
    public function testConnection()
    {
        return response()->json([
            'success' => true,
            'message' => 'API connection successful',
            'timestamp' => now(),
            'user' => auth()->check() ? auth()->user()->name : 'Not authenticated'
        ]);
    }

    public function testUsers()
    {
        try {
            $users = User::select('userid', 'name', 'email', 'isactive')->limit(5)->get();
            return response()->json([
                'success' => true,
                'data' => $users,
                'count' => $users->count()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching users: ' . $e->getMessage()
            ], 500);
        }
    }

    public function testProposals()
    {
        try {
            $proposals = Proposal::with(['applicant:userid,name', 'themeitem:themeid,themename'])
                ->select('proposalid', 'researchtitle', 'approvalstatus', 'useridfk', 'themefk', 'created_at')
                ->limit(5)
                ->get();
            
            return response()->json([
                'success' => true,
                'data' => $proposals,
                'count' => $proposals->count()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching proposals: ' . $e->getMessage()
            ], 500);
        }
    }
}