<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;


use App\Http\Requests\Admin\Robots\UpdateRequest;
use App\Services\RobotsService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class RobotsController extends Controller
{
    public function __construct(
        private readonly RobotsService $robotsService,
    ) {
        parent::__construct();
    }

    public function edit(): View
    {
        return view('cp.robots.edit', [
            'file' => $this->robotsService->getContent(),
            'title' => 'Редактирование Robots.txt',
        ]);
    }

    public function update(UpdateRequest $request): RedirectResponse
    {
        $this->robotsService->update($request->string('content')->toString());

        return redirect()
            ->route('cp.robots.edit')
            ->with('success', 'Данные успешно обновлены');
    }
}
