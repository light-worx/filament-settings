<?php

namespace Lightworx\FilamentSettings\Filament\Clusters\Settings\Resources\Pages;

use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Pages\Page;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Support\Icons\Heroicon;
use Lightworx\FilamentSettings\Filament\Clusters\SettingsCluster;
use Lightworx\FilamentSettings\Models\FilamentSetting;

class FilamentSettings extends Page implements HasForms
{
    use Forms\Concerns\InteractsWithForms;

    protected string $view = 'filament-settings::filament.resources.filament-setting-resource.pages.filament-settings';

    protected static ?string $slug = 'general';

    protected static ?string $title = 'Settings';

    protected static ?string $cluster = SettingsCluster::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    public ?array $data = [];

    public function mount(): void
    {
        // Load all settings and fill form with their values
        $formData = FilamentSetting::all()
            ->mapWithKeys(fn ($setting) => [
                $setting->key => $setting->value, // accessor is used here
            ])
            ->toArray();

        $this->form->fill($formData);
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('manage')
                ->label('Edit settings')
                ->icon('heroicon-o-pencil-square')
                ->url('/admin/settings/filament-settings'),
            Action::make('manage')
                ->label('Add new setting')
                ->icon('heroicon-o-plus')
                ->url('/admin/settings/filament-settings/create'),
            Action::make('save')
                ->label('Save')
                ->icon('heroicon-o-check')
                ->action(function ($livewire) {
                    $livewire->save();
                    return redirect(request()->header('Referer'));
                })
        ];
    }

    public static function canAccess(): bool
    {
        return (bool) auth()->user()?->can(config('filament-settings.permission_name', 'access_settings'));
    }

    protected function getFormSchema(): array
    {
        $settings = FilamentSetting::all();
        $grouped = $settings->groupBy('category');

        $tabs = [];

        foreach ($grouped as $category => $categorySettings) {
            $fields = [];

            foreach ($categorySettings as $setting) {
                $field = $this->createField($setting);

                if ($setting->required) {
                    $field->required();
                }

                $fields[] = $field;
            }

            $tabs[] = Tab::make($category)
                ->schema($fields);
        }

        return [
            Tabs::make('Settings')
                ->tabs($tabs)
                ->columns(2)
                ->columnSpanFull(),
        ];
    }

    protected function getFormStatePath(): ?string
    {
        return 'data';
    }

    protected function createField(FilamentSetting $setting)
    {
        switch ($setting->setting_type) {
            case 'text':
                return TextInput::make($setting->key)
                    ->label($setting->label);

            case 'password':
                return TextInput::make($setting->key)
                    ->label($setting->label)
                    ->password();

            case 'textarea':
                return Textarea::make($setting->key)
                    ->label($setting->label);

            case 'boolean':
                return Toggle::make($setting->key)
                    ->label($setting->label);

            case 'list':
                return Select::make($setting->key)
                    ->label($setting->label)
                    ->options($this->parseOptions($setting->options));

            case 'keyvalue':
                return KeyValue::make($setting->key)
                    ->label($setting->label);

            case 'number':
                return TextInput::make($setting->key)
                    ->label($setting->label)
                    ->numeric();

            case 'tags':
                return TagsInput::make($setting->key)
                    ->label($setting->label)
                    ->default([])
                    ->formatStateUsing(fn ($state) => is_array($state) ? $state : []);

            case 'model':
                return $this->createModelField($setting);

            default:
                return TextInput::make($setting->key)
                    ->label($setting->label);
        }
    }

    protected function parseOptions($options)
    {
        if (is_array($options)) {
                if (array_is_list($options)) {
                    $options = array_combine($options, $options);
            }
            return $options;
        }
        if (!$options) {
            return [];
        }
        if (is_string($options)) {
            $decoded = json_decode($options, true);
            if (is_array($decoded)) {
                return $decoded;
            }
            return collect(explode(',', $options))
                ->mapWithKeys(fn ($item) => [trim($item) => trim($item)])
                ->toArray();
        }
        return [];
    }


    public function save(): void
    {
        $data = $this->form->getState();

        foreach ($data as $key => $value) {
            FilamentSetting::where('key', $key)->update(['value' => $value]);
        }

        Notification::make()
            ->success()
            ->title('Settings saved successfully')
            ->send();
    }

    protected function createModelField(FilamentSetting $setting)
    {
        $config = is_array($setting->options) ? $setting->options : [];

        $modelClass = $config['model'] ?? null;
        $labelField = $config['label_field'] ?? 'name';
        $valueField = $config['value_field'] ?? 'id';
        $where = $config['where'] ?? [];
        $scope = $config['scope'] ?? null;

        return Select::make($setting->key)
            ->label($setting->label)
            ->searchable()
            ->preload()
            ->options(function () use ($modelClass, $labelField, $valueField, $where, $scope) {

                if (! $modelClass || ! class_exists($modelClass) || ! is_subclass_of($modelClass, \Illuminate\Database\Eloquent\Model::class)) {
                    return [];
                }

                $query = $modelClass::query();

                foreach ((array) $where as $column => $value) {
                    $query->where($column, $value);
                }

                if ($scope && method_exists($modelClass, 'scope' . ucfirst($scope))) {
                    $query->{$scope}();
                }

                return $query->pluck($labelField, $valueField)->toArray();
            });
    }

}