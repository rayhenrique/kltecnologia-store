<?php

namespace App\Policies;

use App\Models\NewsletterSubscriber;
use App\Models\User;

class NewsletterSubscriberPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    public function view(User $user, NewsletterSubscriber $subscriber): bool
    {
        return $user->isAdmin();
    }

    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user, NewsletterSubscriber $subscriber): bool
    {
        return $user->isAdmin();
    }

    public function delete(User $user, NewsletterSubscriber $subscriber): bool
    {
        return $user->isAdmin();
    }

    public function export(User $user): bool
    {
        return $user->isAdmin();
    }
}
