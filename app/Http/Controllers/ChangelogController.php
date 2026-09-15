<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\ChangelogService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ChangelogController extends Controller
{
    public function __construct(
        protected ChangelogService $changelogService
    ) {}

    /**
     * Marca a versão mais recente do changelog como visualizada pelo usuário logado.
     */
    public function dismiss(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        $version = $request->has('version') ? (string) $request->input('version') : null;

        $this->changelogService->dismissForUser($user, $version);

        return response()->json([
            'success' => true,
            'message' => 'Notificação de versão marcada como visualizada.',
            'last_seen_version' => $user->fresh()->last_seen_version,
        ]);
    }

    /**
     * Retorna o histórico de versões aplicável ao perfil do usuário logado.
     */
    public function history(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        $releases = $this->changelogService->getAllReleasesForUser($user);

        return response()->json([
            'current_version' => $this->changelogService->getCurrentVersion(),
            'releases' => array_values($releases),
        ]);
    }
}
