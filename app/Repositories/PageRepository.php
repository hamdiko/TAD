<?php

namespace App\Repositories;

use App\Models\StaticPage;
use App\Repositories\BaseRepository;

class PageRepository extends BaseRepository
{
    public function getPages(array $filters = [])
    {
        $pages = StaticPage::query();

        $pages = $this->applyFilters($pages, $filters);

        return $this->applyPagination($pages);
    }
}
