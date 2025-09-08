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
    // If RESET_TOKEN is set, require it. If not set, allow actions without token.
    $token = getenv('RESET_TOKEN') ?: ($_ENV['RESET_TOKEN'] ?? null);
        $provided = $req->query->get('token') ?: $req->request->get('token');

        if ($token && $provided !== $token) {
            return new Response('Forbidden - missing or invalid token', 403);
        }

        $action = $req->request->get('action') ?: $req->query->get('action') ?: 'reset_db';

        $projectDir = dirname(__DIR__, 2);
        $varDb = $projectDir . '/var/data_prod.db';
        $appDb = $projectDir . '/app/data/database.db';

        $result = [];

        // Simple file logging for debugging from within containers where stdout may be captured elsewhere
        $logFile = $projectDir . '/var/log/reset.log';
        $log = function($msg) use ($logFile) {
            $line = '['.date('Y-m-d H:i:s').'] ' . $msg . "\n";
            @file_put_contents($logFile, $line, FILE_APPEND | LOCK_EX);
        };
        $log("reset called; method=" . $req->getMethod() . "; action param=" . ($req->request->get('action') ?? $req->query->get('action') ?? 'none'));

        // Helper to run commands and capture output
        $run = function(string $cmd) {
            exec($cmd . ' 2>&1', $out, $rc);
            return ['cmd' => $cmd, 'rc' => $rc, 'out' => $out];
        };

        if ($action === 'reset_db') {
            // Delete existing DB files
            if (file_exists($varDb)) { @unlink($varDb); $result[] = ['info' => "deleted $varDb"]; }
            if (file_exists($appDb)) { @unlink($appDb); $result[] = ['info' => "deleted $appDb"]; }

            // Recreate schema
            $result[] = $run("php $projectDir/bin/console doctrine:database:create --if-not-exists --env=prod");
            $result[] = $run("php $projectDir/bin/console doctrine:schema:create --env=prod");

            // Ensure default users
            $result[] = $run("php $projectDir/bin/console app:create-user diablesse@whiteboard.app diablesse123 --env=prod");
            $result[] = $run("php $projectDir/bin/console app:create-user mat@whiteboard.app mat123 --env=prod");

        } elseif ($action === 'create_users') {
            $result[] = $run("php $projectDir/bin/console app:create-user diablesse@whiteboard.app diablesse123 --env=prod");
            $result[] = $run("php $projectDir/bin/console app:create-user mat@whiteboard.app mat123 --env=prod");

        } elseif ($action === 'import_users') {
            // Attempt to copy legacy DB if present and run migrate script
            $legacy = $projectDir . '/data/database.db';
            if (file_exists($legacy)) {
                $copied = @copy($legacy, $varDb);
                $result[] = ['info' => $copied ? "copied $legacy to $varDb" : "failed to copy $legacy"];
                // Optionally run migrate-data.php
                if (file_exists($projectDir . '/public/migrate-data.php')) {
                    $result[] = $run("php $projectDir/public/migrate-data.php");
                }
            } else {
                $result[] = ['error' => 'no legacy data/database.db found to import'];
            }

        } else {
            $result[] = ['error' => 'unknown action'];
        }

    $log('action completed; rc summary: ' . json_encode(array_map(function($r){ return $r['rc'] ?? null; }, array_filter($result, function($v){ return is_array($v) && isset($v['rc']); }))));
    $log('detailed result: ' . json_encode($result));

    return $this->json(['status' => 'ok', 'action' => $action, 'result' => $result]);
    }
}
