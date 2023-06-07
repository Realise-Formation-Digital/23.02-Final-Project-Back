<?php
namespace App\swagger;

require_once './vendor/autoload.php';

use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *      title="API Kanban",
 *      version="1"
 *      )
 * @OA\Server(
 *      url="http://localhost:8660",
 *      description="Notre Kanban"
 *      )
 */
class Swagger {}