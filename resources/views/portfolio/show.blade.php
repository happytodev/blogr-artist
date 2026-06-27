@php
    $title = $translation->title ?? __('Artwork');
@endphp

@extends('blogr::layouts.blog')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <a href="{{ route('artist.portfolio.index') }}" class="inline-flex items-center text-sm text-gray-600 dark:text-gray-400 hover:text-[var(--color-primary)] mb-8 transition-colors">
        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
        </svg>
        {{ __('Back to portfolio') }}
    </a>

    <div class="space-y-8">
        <div class="rounded-2xl overflow-hidden shadow-xl">
            @if($translation->image)
            <img
                src="{{ \Storage::url($translation->image) }}"
                alt="{{ $translation->title }}"
                class="w-full h-auto object-contain bg-gray-100 dark:bg-gray-800"
            >
            @endif
        </div>

        @if($translation->gallery && count($translation->gallery) > 0)
        <div class="overflow-x-auto pb-2">
            <div class="flex gap-4">
                @foreach($translation->gallery as $img)
                <div class="flex-shrink-0 w-48 h-32 rounded-xl overflow-hidden">
                    <img
                        src="{{ \Storage::url($img) }}"
                        alt=""
                        class="w-full h-full object-cover"
                        loading="lazy"
                    >
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <div class="space-y-4">
            <h1 class="text-3xl sm:text-4xl font-bold">{{ $translation->title }}</h1>

            <div class="flex flex-wrap gap-3 text-sm">
                @if($translation->category_name)
                <span class="px-3 py-1 rounded-full bg-[var(--color-primary)]/10 text-[var(--color-primary)] text-sm font-medium">
                    {{ $translation->category_name }}
                </span>
                @endif

                @if($translation->price)
                <span class="px-3 py-1 rounded-full bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 text-sm font-medium">
                    {{ $translation->price }}
                </span>
                @endif

                @if(!$translation->is_available)
                <span class="px-3 py-1 rounded-full bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 text-sm font-medium">
                    {{ __('Sold') }}
                </span>
                @endif
            </div>

            @if($translation->description)
            <div class="prose prose-lg dark:prose-invert max-w-none">
                {{ $translation->description }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
