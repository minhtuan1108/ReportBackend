<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Models\Assignment;
use App\Models\Feedback;
use App\Models\Report;
use App\Models\User;
use App\Policies\AssignmentPolicy;
use App\Policies\FeedbackPolicy;
use App\Policies\ReportPolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        User::class => UserPolicy::class,
        Report::class => ReportPolicy::class,
        Assignment::class => AssignmentPolicy::class,
        Feedback::class => FeedbackPolicy::class
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
        Gate::define('view-any-report', function(User $user){
            return $user->tokenCan('manager');
        });
    }
}
