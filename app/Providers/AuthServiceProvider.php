<?php

namespace App\Providers;

use App\Models\Course;
use App\Models\Session;
use App\Models\Subject;
use App\Models\Certificate;
use App\Policies\CoursePolicy;
use App\Policies\SessionPolicy;
use App\Policies\SubjectPolicy;
use App\Policies\CertificatePolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Course::class      => CoursePolicy::class,
        Subject::class     => SubjectPolicy::class,
        Session::class     => SessionPolicy::class,
        Certificate::class => CertificatePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
