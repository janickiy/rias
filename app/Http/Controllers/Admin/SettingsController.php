<?php

namespace App\Http\Controllers\Admin;

use App\DTO\Admin\SettingData;
use App\Http\Requests\Admin\Settings\EditRequest;
use App\Http\Requests\Admin\Settings\StoreRequest;
use App\Models\Settings;
use App\Repositories\SettingRepository;
use App\Services\DocumentStorageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingsController extends Controller
{
    public function __construct(
        private readonly SettingRepository $settingRepository,
        private readonly DocumentStorageService $documentStorageService,
    ) {
    }

    public function index(): View
    {
        return view('cp.settings.index')->with('title', 'Настройки');
    }

    public function create(string $type): View
    {
        return view('cp.settings.create_edit', compact('type'))->with('title', 'Добавление настроек');
    }

    public function store(StoreRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $type = strtoupper((string) $validated['type']);

        if ($type === 'FILE') {
            $validated['value'] = $this->documentStorageService->store($request->file('value'), 'settings');
        } else {
            $validated['value'] = (string) $request->input('value');
        }

        $this->settingRepository->create(SettingData::fromArray($validated));

        return redirect()->route('cp.settings.index')->with('success', 'Информация успешно добавлена');
    }

    public function edit(int $id): View
    {
        $row = Settings::findOrFail($id);
        $type = $row->type;

        return view('cp.settings.create_edit', compact('row', 'type'))->with('title', 'Редактирование настроек');
    }

    public function update(EditRequest $request): RedirectResponse
    {
        $settings = Settings::findOrFail($request->integer('id'));
        $validated = $request->validated();
        $validated['type'] = $settings->getRawOriginal('type');

        if ($request->hasFile('value')) {
            $this->documentStorageService->deleteIfExists('settings', $settings->filePath());
            $validated['value'] = $this->documentStorageService->store($request->file('value'), 'settings');
        } else {
            $validated['value'] = (string) ($request->input('value') ?? $settings->filePath());
        }

        $this->settingRepository->update($settings, SettingData::fromArray($validated));

        return redirect()->route('cp.settings.index')->with('success', 'Данные обновлены');
    }

    public function destroy(Request $request): void
    {
        $row = Settings::find($request->id);

        if (!$row) {
            return;
        }

        if ($row->type === 'FILE') {
            $this->documentStorageService->deleteIfExists('settings', $row->filePath());
        }

        $this->settingRepository->delete($row);
    }
}
