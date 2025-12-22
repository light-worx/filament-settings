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
            ->pluck('value', 'key')
            ->toArray();

        $this->form->fill($formData);
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('manage')
                ->label('Add new setting')
                ->icon('heroicon-o-plus')
                ->url('/admin/filament-settings'),
            Action::make('save')
                ->label('Save')
                ->icon('heroicon-o-check')
                ->action('save')
        ];
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

}