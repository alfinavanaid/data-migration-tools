<?php
// src/Controller/LuckyController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Lib\HandsomeAlfin\DataMigrationTools\DataMigrationTools;

class DefaultController
{

    public function index() : Response
    {
        return new Response('helo', 200);
    }
}
