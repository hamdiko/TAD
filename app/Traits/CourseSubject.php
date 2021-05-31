<?php

namespace App\Traits;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Session;
use App\Enums\CourseStatus;
use App\Models\Certificate;
use App\Models\Subscription;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait CourseSubject
{


    /**
     * Return the certificates added to this entity by all users
     *
     * @return void
     */
    public function certificates()
    {
        return $this->morphMany(Certificate::class, 'certificatable');
    }


    /**
     * get submission date formatted
     * @return string
     */
    public function getSubmissionDateAttribute()
    {
        return Carbon::parse($this->created_at)->format('Y-m-d');
    }

    /**
     * get submission time
     * @return string
     */
    public function getSubmissionTimeAttribute()
    {
        return Carbon::parse($this->created_at)->format('H:i');
    }

    /**
     * Get start date
     * @return string
     */
    public function getStartDateAttribute()
    {
        $earliestSession = $this->sessions()->orderBy('starts_at', 'ASC')->first();

        return $earliestSession ? $earliestSession->date : null;
    }

    /**
     * Get start time
     * @return string
     */
    public function getStartTimeAttribute()
    {
        $earliestSession = $this->sessions()->orderBy('starts_at', 'ASC')->first();

        return $earliestSession ? $earliestSession->time : null;
    }

    /**
     * Determine if the entity is successfully submitted by tutor and waiting admin approval
     *
     * @return boolean
     */
    public function isInProgress()
    {
        return $this->status === CourseStatus::IN_PROGRESS;
    }

    /**
     * Determine if the entity is approved by admin
     *
     * @return boolean
     */
    public function isApproved()
    {
        return $this->status === CourseStatus::APPROVED;
    }

    /**
     * Determine if the entity is rejected by admin
     *
     * @return boolean
     */
    public function isRejected()
    {
        return $this->status === CourseStatus::REJECTED;
    }

    /**
     * Determine if the subject can be deleted.
     *
     * @return boolean
     */
    public function isDeletable(): bool
    {
        return $this->isInProgress();
    }


    /**
     * Return entity's sessions.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function sessions(): MorphMany
    {
        return $this->morphMany(Session::class, 'sessionable')
            ->notRequested()
            ->where('user_id', $this->user_id);
    }

    /**
     * Return entity's subscriptions.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function subscriptions(): MorphMany
    {
        return $this->morphMany(Subscription::class, 'subscribable');
    }

    /**
     * Get the user that owns the entity.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
