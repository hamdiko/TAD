<?php

namespace App\Providers;

use App\Models\Email;
use App\Models\Course;
use App\Models\Session;
use App\Models\Subject;
use App\Models\UserBank;
use App\Models\Certificate;
use App\Models\PhoneNumber;
use App\Models\CertificateFile;
use App\Observers\EmailObserver;
use App\Models\Testimonial;
use App\Observers\CourseObserver;
use App\Observers\SessionObserver;
use App\Observers\SubjectObserver;
use App\Observers\UserBankObserver;
use App\Observers\CertificateObserver;
use App\Observers\PhoneNumberObserver;
use App\Observers\TestimonialObserver;
use Illuminate\Support\ServiceProvider;
use App\Observers\CertificateFileObserver;

class ObserverServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Course::observe(CourseObserver::class);
        Subject::observe(SubjectObserver::class);
        Session::observe(SessionObserver::class);
        UserBank::observe(UserBankObserver::class);
        Certificate::observe(CertificateObserver::class);
        UserBank::observe(UserBankObserver::class);
        CertificateFile::observe(CertificateFileObserver::class);
        PhoneNumber::observe(PhoneNumberObserver::class);
        Email::observe(EmailObserver::class);
        Testimonial::observe(TestimonialObserver::class);
    }
}
