<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ResetController extends AbstractController
{
    #[Route('/status/reset', name: 'status_reset', methods: ['POST','GET'])]
    public function reset(Request $req): Response
    {
        $token = getenv('RESET_TOKEN') ?: ($_ENV['RESET_TOKEN'] ?? null);
        $provided = $req->query->get('token') ?: $req->request->get('token');

        if (!$token || $provided !== $token) {
            return new Response('Forbidden', 403);
        }

        $projectDir = dirname(__DIR__, 2);
        $varDb = $projectDir . '/var/data_prod.db';
        $appDb = $projectDir . '/app/data/database.db';

        // Delete files if exist
        if (file_exists($varDb)) {
            @unlink($varDb);
        }
        if (file_exists($appDb)) {
            @unlink($appDb);
        }

        // Recreate schema and default users
        $output = [];
        $cmds = [
            "php $projectDir/bin/console doctrine:database:create --if-not-exists --env=prod",
            "php $projectDir/bin/console doctrine:schema:create --env=prod",
            "php $projectDir/bin/console app:create-user diablesse@whiteboard.app diablesse123 --env=prod",
            "php $projectDir/bin/console app:create-user mat@whiteboard.app mat123 --env=prod",
        ];

        foreach ($cmds as $c) {
            exec($c . ' 2>&1', $out, $rc);
            $output[] = [
                'cmd' => $c,
                'rc' => $rc,
                'out' => $out,
            ];
        }

        return $this->json(['status' => 'ok', 'result' => $output]);
    }
}
