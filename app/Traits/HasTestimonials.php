<?php

namespace App\Traits;

use App\Models\User;
use App\Models\Testimonial;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasTestimonials
{
    /**
     * Return the testimonials added to this entity by all users.
     *
     * @return Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function testimonials() : MorphMany
    {
        return $this->morphMany(Testimonial::class, 'testimonialable');
    }
}
