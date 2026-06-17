<?php

namespace App\Documentation;

use OpenApi\Attributes as OA;

#[OA\Info(
    title: "Pasangin Backend API",
    version: "1.0.0",
    description: "Dokumentasi API untuk Aplikasi Pasangin. Silakan gunakan Bearer Token JWT untuk mengakses endpoint yang dilindungi (API Private)."
)]
#[OA\Server(
    url: "http://localhost:8080",
    description: "Local Development Server"
)]
#[OA\Server(
    url: "https://backend.pasangin.co.id",
    description: "Production Server"
)]
#[OA\SecurityScheme(
    securityScheme: "bearerAuth",
    type: "http",
    name: "Authorization",
    in: "header",
    bearerFormat: "JWT",
    scheme: "bearer",
    description: "Masukkan Token JWT Anda untuk mengakses API Private."
)]
class BaseDocs
{
}
