<?php

namespace App\doc;

require("./vendor/autoload.php");

use OpenApi\Attributes as OA;

#[OA\Info(
    version: "0.1",
    title: 'API Kanban',
)]
#[OA\Contact(
    name: "DreamTeam Backend",
)]
#[OA\Server(
    url: "http://localhost:8660",
    description: "Api Backend Kanban"
)]
class Swagger {}