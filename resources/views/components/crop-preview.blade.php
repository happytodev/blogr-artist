@php
    $imageUrl = null;
    if ($firstTranslation = $data['translations'][array_key_first($data['translations'] ?? [])] ?? null) {
        $img = $firstTranslation['image'] ?? null;
        if (is_array($img)) {
            $img = $img[0] ?? null;
        }
        if ($img) {
            if (!str_starts_with($img, '/storage/') && !str_starts_with($img, 'storage/')) {
                $img = '/storage/' . $img;
            }
            $imageUrl = $img;
        }
    }
@endphp

<div
    x-data="{
        cx: {{ ($data['crop_x'] ?? 50) }},
        cy: {{ ($data['crop_y'] ?? 50) }},
    }"
    x-init="
        $watch('$wire.data.crop_x', val => cx = val ?? 50);
        $watch('$wire.data.crop_y', val => cy = val ?? 50);
    "
>
    <div class="mt-2">
        <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Crop preview</p>

        <div class="relative overflow-hidden rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-100 dark:bg-gray-800"
             style="height: 300px; max-width: 600px;">
            @if($imageUrl)
            <img
                src="{{ $imageUrl }}"
                x-bind:style="'object-position: ' + cx + '% ' + cy + '%'"
                class="w-full h-full object-cover"
                alt="Crop preview"
            >
            @else
            <div class="flex items-center justify-center h-full text-sm text-gray-400">
                <span>Upload an image in the translation first to see crop preview</span>
            </div>
            @endif
        </div>

        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
            Position: <span x-text="cx"></span>% / <span x-text="cy"></span>%
            &mdash; <span class="italic">Updates live as you move the sliders</span>
        </p>
    </div>
</div>
