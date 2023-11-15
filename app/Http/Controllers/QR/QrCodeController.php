<?php

namespace App\Http\Controllers\QR;

use App\Http\Controllers\Controller;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Str;

class QrCodeController extends Controller
{
    public static function generate(string $data)
    {
        $qrCode = QrCode::size(512)
                    ->format('png')
                    ->errorCorrection('H')
                    ->generate($data);

        // save to file
        $filename = 'qr-code-' . Str::uuid() . '.png';

        $storage = 'app/public/img/qrcodes/';
        $path = storage_path($storage . $filename);
        
        file_put_contents($path, $qrCode);

        return '/storage/'. $storage . $filename;
    }
}
