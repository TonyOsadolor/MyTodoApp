<div>

    <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700">

        <span>Title:</span>
        <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">{{ $data['title'] }}</h5>
        <hr>
        <br>

        <span>Body:</span>
        <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">
            {{ $data['body'] }}
        </p>
        <hr>
        <br>
        @isset($data['has_button'])
            <flux:button icon="globe-alt" variant="primary" href="{{$data['url'] ?? '/'}}">Goto Page</flux:button>
            <hr style="margin: 8px auto!important; border: 0px transparent;">
        @endisset

        <a href="/notifications" class="inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white bg-yellow-700 rounded-lg hover:bg-yellow-800 focus:ring-4 focus:outline-none focus:ring-yellow-300 dark:bg-yellow-600 dark:hover:bg-yellow-700 dark:focus:ring-yellow-800">
            Go Back
            <svg class="rtl:rotate-180 w-3.5 h-3.5 ms-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9"/>
            </svg>
        </a>
    </div>

</div>
