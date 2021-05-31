<?php

namespace App\Models;

use App\Traits\HasVisitors;
use App\Traits\HasTrends;
use App\Traits\HasRatings;
use App\Filters\QueryFilter;
use App\Interfaces\HasImages;
use App\Traits\CourseSubject;
use App\Traits\HasTestimonials;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasImages as HasImagesTrait;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Course extends Model implements HasImages
{
    use HasFactory, HasImagesTrait, CourseSubject, HasTrends, HasTestimonials, HasRatings, HasVisitors;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    protected $appends = ['start_date', 'start_time'];

    protected $imageFields = [
        'image' => 'image'
    ];


    /********************************
     *            Scopes            *
     ********************************/

    /**
     * @param $query
     * @param QueryFilter $filter
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFilter($query, QueryFilter $filter)
    {
        return $filter->apply($query);
    }
}
