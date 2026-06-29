@extends('blogr::layouts.blog')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <h1 class="text-4xl sm:text-5xl font-bold mb-12 text-center">
        {{ __('Commissions') }}
    </h1>

    @php
        $autoplaySpeed = config('blogr-artist.commissions.autoplay_speed', 4000);

        $badgeClasses = [
            'open' => 'bg-green-500 text-white',
            'closed' => 'bg-gray-500 text-white',
            'on_request' => 'bg-blue-500 text-white',
            'auction' => 'bg-amber-500 text-white',
            'sold' => 'bg-red-500 text-white',
        ];

        $badgeLabels = [
            'open' => __('Open'),
            'closed' => __('Closed'),
            'on_request' => __('On Request'),
            'auction' => __('Auction'),
            'sold' => __('Sold'),
        ];
    @endphp

    @if(count($commissions) > 0)
    <div
        x-data="{
            currentSlide: 0,
            totalSlides: {{ count($commissions) }},
            autoplayInterval: null,

            init() {
                if (this.totalSlides > 1) this.startAutoplay();
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
                this.currentSlide = (this.currentSlide + 1) % this.totalSlides;
            },

            prev() {
                this.currentSlide = (this.currentSlide - 1 + this.totalSlides) % this.totalSlides;
            },

            goTo(index) {
                this.currentSlide = index;
                if (this.totalSlides > 1) this.startAutoplay();
            }
        }"
        x-on:mouseenter="stopAutoplay()"
        x-on:mouseleave="startAutoplay()"
        class="max-w-5xl mx-auto"
    >
        @foreach($commissions as $index => $artwork)
            @php $t = $artwork->getDefaultTranslation(); @endphp
            <div x-show="currentSlide === {{ $index }}" style="display: none;">
                <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-lg overflow-hidden">
                    @if($t?->image)
                    <div class="relative">
                        <img
                            src="{{ \Storage::url($t->image) }}"
                            alt="{{ $t->title ?? '' }}"
                            class="w-full h-[300px] sm:h-[500px] lg:h-[600px] object-cover"
                        >

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
                        @endif
                    </div>
                    @endif

                    <div class="p-6">
                        <div class="flex items-center justify-between mb-2">
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100">
                                {{ $t->title ?? '' }}
                            </h3>

                            @php $status = $t->status ?? 'open'; @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $badgeClasses[$status] ?? 'bg-blue-500 text-white' }}">
                                {{ $badgeLabels[$status] ?? __('Open') }}
                            </span>
                        </div>

                        @if($t->price)
                        <p class="text-lg font-bold text-[var(--color-primary)] dark:text-[var(--color-primary-dark)]">
                            {{ $t->price }}
                        </p>
                        @endif

                        @if($t->description)
                        <div class="mt-4 prose prose-sm dark:prose-invert max-w-none text-gray-600 dark:text-gray-400">
                            {!! Str::markdown($t->description) !!}
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach

        @if(count($commissions) > 1)
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
