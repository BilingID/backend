<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Counseling;
use App\Models\Psychotest;

class IndexController extends Controller
{
    public function statistic()
    {
        $users = User::all();
        $psychotests = Psychotest::all();
        $counselings = Counseling::all();

        return $this->success([
            'total_psychologist' => $users->where('role', 'psychologist')->count(),
            'total_client' => $users->where('role', 'client')->count(),
            'total_psychotest' => $psychotests->count(),
            'total_counseling' => $counselings->count(),
        ]);
    }
    }
}
