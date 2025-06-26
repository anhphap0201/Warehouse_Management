@props(['mobileCards' => null, 'headers' => null])

{{-- Responsive table with mobile card layout option --}}
<div class="table-responsive table-card-container shadow-md sm:rounded-lg">
    {{-- Desktop Table --}}
    <table class="table-desktop w-full text-sm text-left text-gray-700">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
            <tr>
                @if($headers)
                    @foreach($headers as $header)
                        <th scope="col" class="px-3 py-3 sm:px-4 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b border-black">
                            {{ $header }}
                        </th>
                    @endforeach
                @else
                    @isset($header)
                        {{ $header }}
                    @endisset
                @endif
            </tr>
        </thead>
        <tbody>
            {{ $slot }}
        </tbody>
    </table>
    
    {{-- Mobile Cards --}}
    @if($mobileCards)
        <div class="table-mobile">
            {{ $mobileCards }}
        </div>
    @endif
    
    @isset($footer)
    <div class="bg-white dark:bg-slate-800 px-3 py-2 sm:px-4 sm:py-3 border-t border-gray-200 dark:border-slate-700">
        {{ $footer }}
    </div>
    @endisset
</div>
