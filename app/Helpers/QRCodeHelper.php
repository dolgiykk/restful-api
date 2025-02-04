<?php

namespace App\Helpers;

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

class QRCodeHelper
{
    /**
     * @param string $qrCodeUrl
     * @return string
     */
    public static function toBase64(string $qrCodeUrl): string
    {
        $qrCode = new QrCode($qrCodeUrl);
        $writer = new PngWriter();

        $result = $writer->write($qrCode);
        $base64 = base64_encode($result->getString());

        return "data:image/png;base64,{$base64}";
    }
}
