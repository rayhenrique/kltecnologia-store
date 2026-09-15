<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\ChangelogService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ChangelogController extends Controller
{
    public function __construct(
        protected ChangelogService $changelogService
    ) {}

    /**
     * Exibe a página administrativa com o histórico completo de lançamentos e versões.
     */
    public function index(Request $request): View
    {
        /** @var User $user */
        $user = $request->user();

        $releases = $this->changelogService->getAllReleasesForUser($user);
        $currentVersion = $this->changelogService->getCurrentVersion();
        $totalReleases = count($releases);

        $totalChanges = (int) array_sum(array_map(
            static fn (array $release): int => count((array) ($release['changes'] ?? [])),
            $releases
        ));

        $latestRelease = ! empty($releases) ? reset($releases) : null;
        $latestReleaseDate = $latestRelease['date'] ?? null;

        return view('admin.changelog.index', compact(
            'releases',
            'currentVersion',
            'totalReleases',
            'totalChanges',
            'latestReleaseDate'
        ));
    }
}
