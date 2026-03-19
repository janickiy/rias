<?php

namespace App\Http\Controllers\Admin;

use App\DTO\Admin\SeoData;
use App\Http\Requests\Admin\Seo\UpdateRequest;
use App\Models\Seo;
use App\Repositories\SeoRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SeoController extends Controller
{
    public function __construct(private readonly SeoRepository $seoRepository)
    {
    }

    public function index(): View
    {
        return view('cp.seo.index')->with('title', 'Seo');
    }

    public function edit(int $id): View
    {
        $row = Seo::findOrFail($id);

        return view('cp.seo.edit', compact('row'))->with('title', 'Редактирование seo');
    }

    public function update(UpdateRequest $request): RedirectResponse
    {
        $row = Seo::findOrFail($request->integer('id'));
        $this->seoRepository->update($row, SeoData::fromArray($request->validated()));

        return redirect()->route('cp.seo.index')->with('success', 'Данные успешно обновлены');
    }
}
