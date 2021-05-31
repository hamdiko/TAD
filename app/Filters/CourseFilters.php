<?php


namespace App\Filters;


use App\Enums\CourseStatus;
use Carbon\Carbon;

class CourseFilters extends QueryFilter
{
    public function status($status = CourseStatus::IN_PROGRESS)
    {
        return $this->builder->where('status', $status);
    }

    public function submissionDateFrom($date_from = '')
    {
	    return $this->builder->whereDate('created_at', '>=' , $date_from ? $date_from : Carbon::now());
    }
	
	public function submissionDateTo($date_to = '')
	{
		return $this->builder->whereDate('created_at','<=', $date_to ? $date_to : Carbon::now());
	}
	
	public function booked()
	{
		return $this->builder->has('subscriptions');
	}
}
