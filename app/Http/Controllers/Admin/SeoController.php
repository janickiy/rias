<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\DTO\Admin\SeoData;
use App\Http\Requests\Admin\Seo\UpdateRequest;
use App\Repositories\SeoRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SeoController extends Controller
{
    public function __construct(
        private readonly SeoRepository $seoRepository,
    ) {
    }

    public function index(): View
    {
        return view('cp.seo.index', [
            'title' => 'Seo',
        ]);
    }

    public function edit(int $id): View
    {
        $row = $this->seoRepository->findOrFail($id);

        return view('cp.seo.edit', [
            'row' => $row,
            'title' => 'Редактирование seo',
        ]);
    }

    public function update(UpdateRequest $request): RedirectResponse
    {
        $row = $this->seoRepository->findOrFail($request->integer('id'));

        $this->seoRepository->update(
            $row,
            SeoData::fromArray($request->validated())
        );

        return redirect()
            ->route('cp.seo.index')
            ->with('success', 'Данные успешно обновлены');
    }
}
