@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'border-gray-300 focus:border-gray-500 ring-0 ring-offset-0 ring-transparent ring-offset-current rounded-md shadow-sm']) !!}>
