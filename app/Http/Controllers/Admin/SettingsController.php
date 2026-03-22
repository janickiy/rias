<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\DTO\Admin\SettingData;
use App\Http\Requests\Admin\Settings\EditRequest;
use App\Http\Requests\Admin\Settings\StoreRequest;
use App\Http\Requests\Admin\Settings\DeleteRequest;
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
        parent::__construct();
    }

    public function index(): View
    {
        return view('cp.settings.index', [
            'title' => 'Настройки',
        ]);
    }

    /**
     * @param string $type
     * @return View
     */
    public function create(string $type): View
    {
        return view('cp.settings.create_edit', [
            'type' => $type,
            'title' => 'Добавление настроек',
        ]);
    }

    /**
     * @param StoreRequest $request
     * @return RedirectResponse
     */
    public function store(StoreRequest $request): RedirectResponse
    {
        $data = $this->prepareDataForStore($request);

        $this->settingRepository->create(
            SettingData::fromArray($data)
        );

        return redirect()
            ->route('cp.settings.index')
            ->with('success', 'Информация успешно добавлена');
    }

    /**
     * @param int $id
     * @return View
     */
    public function edit(int $id): View
    {
        $row = $this->settingRepository->findOrFail($id);

        return view('cp.settings.create_edit', [
            'row' => $row,
            'type' => $row->type,
            'title' => 'Редактирование настроек',
        ]);
    }

    /**
     * @param EditRequest $request
     * @return RedirectResponse
     */
    public function update(EditRequest $request): RedirectResponse
    {
        $setting = $this->settingRepository->findOrFail($request->integer('id'));
        $data = $this->prepareDataForUpdate($request, $setting);

        $this->settingRepository->update(
            $setting,
            SettingData::fromArray($data)
        );

        return redirect()
            ->route('cp.settings.index')
            ->with('success', 'Данные обновлены');
    }

    /**
     * @param \DeleteRequest $request
     * @return RedirectResponse
     */
    public function destroy(DeleteRequest $request): RedirectResponse
    {
        $row = $this->settingRepository->find($request->integer('id'));

        if ($row !== null) {
            if ($this->isFileType($row->type)) {
                $this->documentStorageService->deleteIfExists('settings', $row->filePath());
            }

            $this->settingRepository->delete($row);
        }

        return redirect()
            ->route('cp.settings.index')
            ->with('success', 'Информация успешно удалена');
    }

    /**
     * @param StoreRequest $request
     * @return array
     */
    private function prepareDataForStore(StoreRequest $request): array
    {
        $data = $request->validated();
        $type = strtoupper((string) $data['type']);

        $data['type'] = $type;
        $data['value'] = $this->resolveStoreValue($request, $type);

        return $data;
    }

    /**
     * @param EditRequest $request
     * @param object $setting
     * @return array
     */
    private function prepareDataForUpdate(EditRequest $request, object $setting): array
    {
        $data = $request->validated();
        $type = (string) $setting->getRawOriginal('type');

        $data['type'] = $type;
        $data['value'] = $this->resolveUpdateValue($request, $setting, $type);

        return $data;
    }

    /**
     * @param StoreRequest $request
     * @param string $type
     * @return string
     */
    private function resolveStoreValue(StoreRequest $request, string $type): string
    {
        if ($this->isFileType($type)) {
            return $this->documentStorageService->store(
                $request->file('value'),
                'settings'
            );
        }

        return (string) $request->input('value', '');
    }

    /**
     * @param EditRequest $request
     * @param object $setting
     * @param string $type
     * @return string
     */
    private function resolveUpdateValue(EditRequest $request, object $setting, string $type): string
    {
        if ($this->isFileType($type)) {
            if ($request->hasFile('value')) {
                $this->documentStorageService->deleteIfExists(
                    'settings',
                    $setting->filePath()
                );

                return $this->documentStorageService->store(
                    $request->file('value'),
                    'settings'
                );
            }

            return (string) $setting->filePath();
        }

        return (string) $request->input('value', '');
    }

    private function isFileType(string $type): bool
    {
        return strtoupper($type) === 'FILE';
    }
}
