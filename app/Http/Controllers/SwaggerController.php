<?php

namespace App\Http\Controllers;

use OpenApi\Attributes as OA;

#[OA\Info(version: '1.0.0', title: 'Fire Extinguisher API')]
#[OA\PathItem(path: '/api')]
#[OA\Server(url: 'http://127.0.0.1:8000', description: 'Local')]
#[OA\SecurityScheme(
    securityScheme: 'bearerAuth',
    type: 'http',
    scheme: 'bearer',
    bearerFormat: 'JWT'
)]
class SwaggerController extends Controller
{
}