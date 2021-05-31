<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Relations\Relation;

class VisitRepository extends BaseRepository
{
    public function getVisits($visitableType,  $visitableId, array $filters = [])
    {
        $visitable = $this->getVisitable($visitableType, $visitableId);

        $visits = $visitable->visits()
            ->select(DB::raw('count(id) as `visits`'), DB::raw('YEAR(created_at) year, MONTH(created_at) month'))
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month');

        $visits = $this->applyFilters($visits, $filters);

        return $this->applyPagination($visits);
    }


    public function getVisitors($visitableType,  $visitableId, array $filters = [])
    {
        $visitable = $this->getVisitable($visitableType, $visitableId);

        $visits = $visitable->visits()->with('visitor');

        $visits = $this->applyFilters($visits, $filters);

        return $this->applyPagination($visits);
    }

    public function addVisit(User $visitor,  $visitableType,  $visitableId): array
    {
        $visitable = $this->getVisitable($visitableType, $visitableId);

        $isVisitAdded = $visitable->addVisit($visitor);

        return [
            'visits'  => $visitable->visits_count,
            'message' => $isVisitAdded ? __('tutor_profile.new_visit_success') : __('tutor_profile.new_visit_error'),
        ];
    }

    protected function getVisitable($visitableType,  $visitableId)
    {
        if ((!$visitableType || !$visitableId) && auth()->check()) {
            return auth()->user();
        }

        $visitableClass = Relation::getMorphedModel($visitableType);

        return App::make($visitableClass)->findOrFail($visitableId);
    }

    public function applyFilters($query, array $filters = [])
    {
        $from = $filters['date_from'] ?? null;

        $to = $filters['date_to'] ?? null;

        if ($from && $to && $from <= $to) {
            return $query->whereBetween('created_at', [$from, $to]);
        }

        return $query;
    }
}
