@extends('blogr::layouts.blog')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <h1 class="text-4xl sm:text-5xl font-bold mb-12 text-center">
        {{ __('Portfolio') }}
    </h1>

    @php
        $imageHeight = config('blogr-artist.portfolio.image_height', 400);
        $lightboxNav = config('blogr-artist.portfolio.lightbox_navigation', true);
    @endphp

    <div
        x-data="{
            open: false,
            current: 0,
            images: {{ json_encode($artworks->map(fn($a) => ($t = $a->getDefaultTranslation()) && $t->image ? \Storage::url($t->image) : null)->values()) }},
            crops: {{ json_encode($artworks->map(fn($a) => ['x' => $a->crop_x, 'y' => $a->crop_y])->values()) }},

            openLightbox(index) {
                this.current = index;
                this.open = true;
                document.body.style.overflow = 'hidden';
            },
            closeLightbox() {
                this.open = false;
                document.body.style.overflow = 'auto';
            },
            next() {
                this.current = (this.current + 1) % this.images.length;
            },
            prev() {
                this.current = (this.current - 1 + this.images.length) % this.images.length;
            }
        }"
        @keydown.escape.window="closeLightbox()"
        @keydown.arrow-right.window="open && next()"
        @keydown.arrow-left.window="open && prev()"
        class="space-y-8"
    >
        @foreach($artworks as $index => $artwork)
            @php $t = $artwork->getDefaultTranslation(); @endphp
            @if($t?->image)
            <div
                @click="openLightbox({{ $index }})"
                class="group relative overflow-hidden rounded-2xl cursor-pointer"
            >
                <img
                    src="{{ \Storage::url($t->image) }}"
                    alt="{{ $t->title ?? '' }}"
                    class="w-full h-[{{ $imageHeight }}px] object-cover transition-all duration-500 group-hover:grayscale"
                    style="object-position: {{ $artwork->crop_x }}% {{ $artwork->crop_y }}%"
                    loading="{{ $index < 3 ? 'eager' : 'lazy' }}"
                >
                <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition-all duration-300 rounded-2xl"></div>
            </div>
            @endif
        @endforeach

        {{-- Lightbox --}}
        <template x-teleport="body">
            <div
                x-show="open"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 z-50 flex items-center justify-center bg-black/90"
                style="display: none;"
                @click="closeLightbox()"
            >
                <button
                    @click.stop="closeLightbox()"
                    class="absolute top-4 right-4 text-white hover:text-gray-300 transition-colors z-10"
                    aria-label="Close"
                >
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>

                @if($lightboxNav && $artworks->count() > 1)
                <button
                    @click.stop="prev()"
                    class="absolute left-4 text-white hover:text-gray-300 transition-colors z-10"
                    aria-label="Previous"
                >
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </button>
                <button
                    @click.stop="next()"
                    class="absolute right-4 text-white hover:text-gray-300 transition-colors z-10"
                    aria-label="Next"
                >
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </button>
                @endif

                <div class="max-w-[90vw] max-h-[90vh]" @click.stop>
                    <img
                        :src="images[current]"
                        :alt="'Portfolio image ' + (current + 1)"
                        class="max-w-full max-h-[90vh] object-contain"
                    >
                </div>

                <div class="absolute bottom-4 left-1/2 -translate-x-1/2 text-white text-sm">
                    <span x-text="current + 1"></span> / <span x-text="images.length"></span>
                </div>
            </div>
        </template>
    </div>
</div>
@endsection
