<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class IndexController extends Controller
{
    public function index(Request $request)
    {
        if ($request->get('error') === 'true') {
            return $this->error(null, 'coba', 417);
        }

        return $this->success([
            'api' => config('app.name', 'Laravel'),
            'version' => config('app.version', '1.0.0'),
            'query' => $request->get('query'),
            'adfadfasfdas' => $request->get('asdf'),
        ]);
    }

    public function show(Request $request, int $id)
    {
        $data = $request->json()->all();
        $strReturn = $id . '. ' . $data['name'] . ' adalah kategori ';

        if ($id === 10) {
            return $this->success('Hello, Admin');
        }

        return $this->success($strReturn . match (true) {
            $data['age'] < 15 => 'anak',
            $data['age'] < 25 => 'remaja',
            $data['age'] < 40 => 'dewasa',
            $data['age'] < 60 => 'tua',
            default => 'lansia',
        });
    }
}
