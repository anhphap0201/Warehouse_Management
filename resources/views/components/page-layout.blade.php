@props([
    'title' => '',
    'subtitle' => '',
    'icon' => '',
    'iconColor' => 'blue-600',
    'gradient' => false,
    'actionText' => '',
    'actionRoute' => '',
    'actionIcon' => 'fas fa-plus'
])

<x-app-layout>
    <x-slot name="header">
        @if($gradient)
            <div class="page-header-gradient">
                <div class="page-header-content">
                    <div class="page-title-section">
                        <h1 class="page-title-main text-white">
                            <div class="page-title-icon gradient-bg">
                                <i class="{{ $icon }} text-white text-xl"></i>
                            </div>
                            {{ $title }}
                        </h1>
                        @if($subtitle)
                            <p class="page-subtitle gradient-text">{{ $subtitle }}</p>
                        @endif
                    </div>
                    @if($actionText && $actionRoute)
                        <div class="page-actions">
                            <a href="{{ $actionRoute }}" class="btn-primary-gradient">
                                <i class="{{ $actionIcon }} mr-2"></i>
                                {{ $actionText }}
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        @else
            <div class="page-header-standard">
                <div class="page-header-content">
                    <div class="page-title-section">
                        <h1 class="page-title-main text-gray-900">
                            <div class="page-title-icon simple-bg">
                                <i class="{{ $icon }} text-{{ $iconColor }} text-lg"></i>
                            </div>
                            {{ $title }}
                        </h1>
                        @if($subtitle)
                            <p class="page-subtitle">{{ $subtitle }}</p>
                        @endif
                    </div>
                    @if($actionText && $actionRoute)
                        <div class="page-actions">
                            <a href="{{ $actionRoute }}" class="btn-primary-standard">
                                <i class="{{ $actionIcon }} mr-2"></i>
                                {{ $actionText }}
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        @endif
    </x-slot>

    <div class="page-container">
        <div class="page-content">
            {{ $slot }}
        </div>
    </div>
</x-app-layout>
