<div x-data="{
    get cx() { return $wire.get('data.crop_x') ?? 50 },
    get cy() { return $wire.get('data.crop_y') ?? 50 },
    get imageUrl() { return $wire.get('data.translations.0.image') ? '/storage/' + $wire.get('data.translations.0.image') : null },
}">
    <div class="mt-2">
        <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Crop preview</p>

        <div class="relative overflow-hidden rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-100 dark:bg-gray-800"
             style="height: 300px; max-width: 600px;">
            <template x-if="imageUrl">
                <img
                    :src="imageUrl"
                    :style="'object-position: ' + cx + '% ' + cy + '%'"
                    class="w-full h-full object-cover"
                    alt="Crop preview"
                >
            </template>
            <template x-if="!imageUrl">
                <div class="flex items-center justify-center h-full text-sm text-gray-400">
                    <span>Upload an image first to see crop preview</span>
                </div>
            </template>
        </div>

        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
            Position: <span x-text="cx"></span>% / <span x-text="cy"></span>%
            &mdash; <span class="italic">The preview updates live as you move the sliders</span>
        </p>
    </div>
</div>
