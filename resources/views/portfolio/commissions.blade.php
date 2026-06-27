@extends('blogr::layouts.blog')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <h1 class="text-4xl sm:text-5xl font-bold mb-12 text-center">
        {{ __('Commissions') }}
    </h1>

    @php
        $autoplaySpeed = config('blogr-artist.commissions.autoplay_speed', 4000);
        $imageHeight = config('blogr-artist.commissions.image_height', 500);
    @endphp

    @if(count($commissions) > 0)
    <div
        x-data="{
            currentSlide: 0,
            slides: {{ json_encode($commissions->map(fn($a) => ($t = $a->getDefaultTranslation()) ? [
                'image' => $t->image ? \Storage::url($t->image) : null,
                'title' => $t->title ?? '',
                'price' => $t->price ?? '',
                'status' => $t->is_available ? 'Open' : 'Sold',
                'description' => $t->description ?? '',
            ] : null)->values()) }},
            autoplayInterval: null,

            init() {
                if (this.slides.length > 1) {
                    this.startAutoplay();
                }
            },

            startAutoplay() {
                this.stopAutoplay();
                this.autoplayInterval = setInterval(() => this.next(), {{ $autoplaySpeed }});
            },

            stopAutoplay() {
                if (this.autoplayInterval) {
                    clearInterval(this.autoplayInterval);
                    this.autoplayInterval = null;
                }
            },

            next() {
                this.currentSlide = (this.currentSlide + 1) % this.slides.length;
            },

            prev() {
                this.currentSlide = (this.currentSlide - 1 + this.slides.length) % this.slides.length;
            },

            goTo(index) {
                this.currentSlide = index;
                if (this.slides.length > 1) this.startAutoplay();
            }
        }"
        x-on:mouseenter="stopAutoplay()"
        x-on:mouseleave="startAutoplay()"
        class="relative max-w-3xl mx-auto"
    >
        @foreach($commissions as $index => $artwork)
            @php $t = $artwork->getDefaultTranslation(); @endphp
            <div
                x-show="currentSlide === {{ $index }}"
                x-transition:enter="transition ease-out duration-500"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-300"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                class="relative overflow-hidden rounded-2xl"
                style="display: none;"
            >
                @if($t?->image)
                <img
                    src="{{ \Storage::url($t->image) }}"
                    alt="{{ $t->title ?? '' }}"
                    class="w-full h-[{{ $imageHeight }}px] object-cover transition-all duration-500 group-hover:grayscale"
                >
                <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>

                <div class="absolute bottom-0 left-0 right-0 p-6 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                    <h3 class="text-white text-xl font-bold">{{ $t->title }}</h3>
                    @if($t->price)
                    <p class="text-white/90 text-lg mt-1">{{ $t->price }}</p>
                    @endif
                    @if($t->description)
                    <p class="text-white/70 text-sm mt-2 max-w-lg">{{ \Illuminate\Support\Str::limit($t->description, 120) }}</p>
                    @endif
                    <span class="inline-block mt-2 px-3 py-1 rounded-full text-xs font-medium text-white
                        {{ $t->is_available ? 'bg-green-500' : 'bg-red-500' }}">
                        {{ $t->is_available ? __('Open') : __('Sold') }}
                    </span>
                </div>
                @endif
            </div>
        @endforeach

        @if(count($commissions) > 1)
        <button
            @click="prev()"
            class="absolute left-4 top-1/2 -translate-y-1/2 z-10 text-white hover:text-gray-300 transition-colors bg-black/20 hover:bg-black/40 rounded-full p-2"
            aria-label="Previous"
        >
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
        </button>
        <button
            @click="next()"
            class="absolute right-4 top-1/2 -translate-y-1/2 z-10 text-white hover:text-gray-300 transition-colors bg-black/20 hover:bg-black/40 rounded-full p-2"
            aria-label="Next"
        >
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
        </button>

        <div class="flex justify-center mt-6 space-x-3">
            @foreach($commissions as $index => $artwork)
            <button
                @click="goTo({{ $index }})"
                class="w-3 h-3 rounded-full transition-all duration-300"
                :class="currentSlide === {{ $index }} ? 'bg-[var(--color-primary)] scale-125' : 'bg-gray-400 hover:bg-gray-500'"
                aria-label="Go to slide {{ $index + 1 }}"
            ></button>
            @endforeach
        </div>
        @endif
    </div>
    @else
    <p class="text-center text-gray-500 dark:text-gray-400 text-lg">
        {{ __('No commissions available at the moment.') }}
    </p>
    @endif
</div>
@endsection
