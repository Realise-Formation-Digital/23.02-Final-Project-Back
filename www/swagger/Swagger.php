<?php
namespace App\swagger;

require_once './vendor/autoload.php';

use OpenApi\Attributes as OA;

#[OA\Info(
    version:"1",
    title:'API Kanban'
)]
#[OA\Server(
    url:"http://localhost:8660",
    description:"Kanban"
)]
class Swagger {}