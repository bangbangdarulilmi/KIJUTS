<?php

namespace App\Services;

use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;

class QrService
{
    public static function generateSvg(string $data): string
    {
        $options = new QROptions([
            'outputType' => QRCode::OUTPUT_MARKUP_SVG,
            'eccLevel'   => QRCode::ECC_L,
            'addQuietzone' => true,
        ]);

        return (new QRCode($options))->render($data);
    }

    public static function generateDataUri(string $data): string
    {
        return (new QRCode())->render($data);
    }
}
