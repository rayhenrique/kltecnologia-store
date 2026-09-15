<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\User;

class ChangelogService
{
    /**
     * Retorna a versão corrente cadastrada na aplicação.
     */
    public function getCurrentVersion(): string
    {
        return (string) config('changelog.current_version', '1.0.0');
    }

    /**
     * Retorna o papel do usuário no formato de audiência do changelog ('admin' ou 'customer').
     */
    public function getUserAudience(User $user): string
    {
        return $user->isAdmin() ? 'admin' : 'customer';
    }

    /**
     * Verifica se uma release ou item de alteração é aplicável ao papel do usuário.
     */
    public function isApplicableToAudience(?string $itemAudience, string $userAudience): bool
    {
        if ($itemAudience === null || $itemAudience === '' || $itemAudience === 'all') {
            return true;
        }

        return $itemAudience === $userAudience;
    }

    /**
     * Retorna todas as releases aplicáveis ao usuário, com as alterações devidamente filtradas.
     *
     * @return array<string, array<string, mixed>>
     */
    public function getAllReleasesForUser(User $user): array
    {
        $allReleases = (array) config('changelog.releases', []);
        $userAudience = $this->getUserAudience($user);
        $filteredReleases = [];

        foreach ($allReleases as $version => $release) {
            $releaseAudience = (string) ($release['audience'] ?? 'all');

            if (! $this->isApplicableToAudience($releaseAudience, $userAudience)) {
                continue;
            }

            $rawChanges = (array) ($release['changes'] ?? []);
            $filteredChanges = [];

            foreach ($rawChanges as $change) {
                $changeAudience = (string) ($change['audience'] ?? $releaseAudience);

                if ($this->isApplicableToAudience($changeAudience, $userAudience)) {
                    $filteredChanges[] = $change;
                }
            }

            // Se a release possuir itens relevantes para este usuário, inclui no histórico
            if (! empty($filteredChanges)) {
                $releaseCopy = $release;
                $releaseCopy['changes'] = $filteredChanges;
                $filteredReleases[$version] = $releaseCopy;
            }
        }

        return $filteredReleases;
    }

    /**
     * Retorna a versão mais recente aplicável ao perfil do usuário.
     */
    public function getLatestVersionForUser(User $user): ?string
    {
        $releases = $this->getAllReleasesForUser($user);

        if (empty($releases)) {
            return null;
        }

        $versions = array_keys($releases);

        usort($versions, static function (string $a, string $b) {
            return version_compare($b, $a); // Ordenação decrescente por SemVer
        });

        return $versions[0];
    }

    /**
     * Retorna a release mais recente não visualizada pelo usuário, caso exista.
     *
     * @return array<string, mixed>|null
     */
    public function getUnseenReleaseForUser(User $user): ?array
    {
        $latestVersion = $this->getLatestVersionForUser($user);

        if (! $latestVersion) {
            return null;
        }

        // Se o usuário já tiver visto esta versão ou superior, não exibe modal
        if ($user->last_seen_version && version_compare($user->last_seen_version, $latestVersion, '>=')) {
            return null;
        }

        $releases = $this->getAllReleasesForUser($user);

        return $releases[$latestVersion] ?? null;
    }

    /**
     * Registra que o usuário visualizou as novidades até determinada versão (ou versão atual).
     */
    public function dismissForUser(User $user, ?string $version = null): void
    {
        $versionToRecord = $version ?: ($this->getLatestVersionForUser($user) ?: $this->getCurrentVersion());

        $user->update([
            'last_seen_version' => $versionToRecord,
        ]);
    }
}
