<?php

namespace App\Http\Controllers;

use App\Models\StaticPage;
use App\Repositories\PageRepository;
use App\Http\Requests\StaticPage\StaticPageListRequest;
use App\Http\Requests\StaticPage\StoreStaticPageRequest;
use App\Http\Requests\StaticPage\UpdateStaticPageRequest;

class StaticPageController extends Controller
{
    protected $pageRepository;

    public function __construct(PageRepository $pageRepository) {
        $this->pageRepository = $pageRepository;
        $this->pageRepository->setPaginatorLength(request('per_page'));

    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(StaticPageListRequest $request)
    {
        $filters = $request->validated();

        $pages = $this->pageRepository->getPages($filters);

        return response()->json([
            'status' => true,
            'pages' => $pages,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  App\Http\Requests\StaticPage\StoreStaticPageRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreStaticPageRequest $request)
    {
        $page = StaticPage::create($request->validated());

        return response()->json([
            'status' => true,
            'page' => $page,
            'message' =>'Page has been created successfully.'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\StaticPage  $page
     * @return \Illuminate\Http\Response
     */
    public function show(StaticPage $page)
    {
        return response()->json([
            'status' => true,
            'page' => $page,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  App\Http\Requests\StaticPage\UpdateStaticPageRequest  $request
     * @param  \App\Models\StaticPage  $page
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateStaticPageRequest $request, StaticPage $page)
    {
        $page->update($request->validated());

        return response()->json([
            'status' => true,
            'page' => $page,
            'message' => 'Page has been updated successfully.'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\StaticPage  $page
     * @return \Illuminate\Http\Response
     */
    public function destroy(StaticPage $page)
    {
        $page->delete();

        return response()->json([
            'status'      => true,
            'message'    => 'Page has been deleted successfully.',
        ]);
    }
}
