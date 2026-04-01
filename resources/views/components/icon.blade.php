@props([
    'name' => 'star',
    'size' => 'md',
    'class' => '',
    'outline' => false,
])

@php
    $sizeMap = [
        'xs' => 'w-4 h-4',
        'sm' => 'w-5 h-5',
        'md' => 'w-6 h-6',
        'lg' => 'w-8 h-8',
        'xl' => 'w-10 h-10',
        '2xl' => 'w-12 h-12',
    ];
    
    $sizeClass = $sizeMap[$size] ?? $sizeMap['md'];
    $basePath = $outline ? 'heroicons/outline' : 'heroicons/solid';
@endphp

@svg("heroicons/$basePath/$name", ["class" => "$sizeClass $class"])
