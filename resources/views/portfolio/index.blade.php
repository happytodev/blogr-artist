@php
    $title = __('Portfolio');
@endphp

@extends('blogr::layouts.blog')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <h1 class="text-4xl sm:text-5xl font-bold mb-8 text-center">
        {{ $title }}
    </h1>

    <div
        x-data="{
            hoveredId: null,
            hoveredArtwork: null,
            artworks: {{ json_encode($artworks->map(fn($a) => [
                'id' => $a->id,
                'image' => ($t = $a->getDefaultTranslation()) ? ($t->image ? \Storage::url($t->image) : null) : null,
                'title' => ($t = $a->getDefaultTranslation()) ? $t->title : '',
                'price' => ($t = $a->getDefaultTranslation()) ? $t->price : '',
                'category' => ($t = $a->getDefaultTranslation()) ? $t->category_name : '',
                'slug' => ($t = $a->getDefaultTranslation()) ? $t->slug : '',
                'description' => ($t = $a->getDefaultTranslation()) ? \Illuminate\Support\Str::limit($t->description, 100) : '',
            ], $artworks)) }}
        }"
        class="space-y-8"
    >
        <div class="overflow-x-auto pb-4 -mx-4 px-4">
            <div class="flex gap-6">
                @foreach($artworks as $artwork)
                    @php $t = $artwork->getDefaultTranslation(); @endphp
                    <div
                        @mouseenter="hoveredId = {{ $artwork->id }}; hoveredArtwork = artworks.find(a => a.id === {{ $artwork->id }})"
                        @mouseleave="hoveredId = null; hoveredArtwork = null"
                        @click="window.location.href = '{{ route('artist.portfolio.show', $t?->slug ?? 'untitled') }}'"
                        class="flex-shrink-0 w-[300px] group relative overflow-hidden rounded-2xl cursor-pointer transition-all duration-300 hover:shadow-xl"
                    >
                        @if($t?->image)
                        <img
                            src="{{ \Storage::url($t->image) }}"
                            alt="{{ $t->title }}"
                            class="w-full h-[250px] object-cover rounded-2xl transition-all duration-500 group-hover:grayscale"
                            loading="lazy"
                        >
                        @endif

                        <div class="absolute inset-0 bg-black/0 group-hover:bg-black/30 transition-all duration-300 rounded-2xl"></div>

                        <div class="absolute bottom-0 left-0 right-0 p-4 bg-gradient-to-t from-black/70 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 rounded-b-2xl">
                            @if($t?->title)
                            <h3 class="text-white font-semibold text-lg">{{ $t->title }}</h3>
                            @endif
                            @if($t?->price)
                            <p class="text-white/90 text-sm">{{ $t->price }}</p>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{ $artworks->links() }}
    </div>
</div>
@endsection
