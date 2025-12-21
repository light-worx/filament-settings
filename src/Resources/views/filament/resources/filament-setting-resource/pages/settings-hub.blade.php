<div class="p-6">
    <h1 class="text-2xl font-bold mb-6">Settings Hub</h1>

    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
        @foreach ($this->getSettingsResources() as $resource)
            <a href="{{ $resource['url'] }}" class="block p-4 border rounded-lg shadow hover:shadow-md transition">
                <div class="flex items-center gap-3">
                    @if ($resource['icon'])
                        <x-filament::icon :icon="$resource['icon']" class="h-6 w-6 text-gray-500" />
                    @endif
                    <span class="text-base font-medium">
                        {{ $resource['label'] }}
                    </span>
                </div>
            </a>
        @endforeach
    </div>
</div>
