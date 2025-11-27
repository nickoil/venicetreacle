@props(['align' => 'right', 'width' => '48', 'contentClasses' => 'py-1 bg-white', 'menuName'])

@php
switch ($align) {
    case 'left':
        $alignmentClasses = 'ltr:origin-top-left rtl:origin-top-right start-0';
        break;
    case 'top':
        $alignmentClasses = 'origin-top';
        break;
    case 'right':
    default:
        $alignmentClasses = 'ltr:origin-top-right rtl:origin-top-left end-0';
        break;
}

// Nick pointless when you can just specify the width in the component
switch ($width) {
    case '48':
        $width = 'w-48';
        break;
}
@endphp



<div class="relative" x-data="{ {{ $menuName }}: false, logMenu:false }" >
    <div @click="{{ $menuName }} = ! {{ $menuName }}">
        {{ $trigger }}
    </div>
    <div x-show="{{ $menuName }}"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-75"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="absolute z-50 mt-2 {{ $width }} rounded-md shadow-lg {{ $alignmentClasses }}"
            style="display: none;"
            @click.away="{{ $menuName }} = false">
        <div class="relative rounded-md ring-1 ring-black ring-opacity-5 {{ $contentClasses }}">
            {{ $content }}
        </div>
    </div>
</div>


