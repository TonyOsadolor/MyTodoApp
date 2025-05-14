<div>
    @if(count($subscriptions) > 0)
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        S/N
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Plan Name
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Code
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Amount
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Plan
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Status
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Date Added
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Action
                    </th>
                </tr>
            </thead>
            <?php $sn = 1; ?>
            @foreach($subscriptions as $subscription)
            <tbody>
                <tr class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700 border-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600">
                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        {{ $sn }}
                    </th>
                    <td class="px-6 py-4">
                        {{ $subscription->plan->name }}
                    </td>
                    <td class="px-6 py-4">
                        {{ $subscription->plan->code }}
                    </td>
                    <td class="px-6 py-4">
                        {{ number_format($subscription->plan->amount, 2) }}
                    </td>
                    <td class="px-6 py-4">
                        {{ $price = $subscription->plan->is_free ? 'Yes' : 'No';  }}
                    </td>
                    <td class="px-6 py-4">
                        {{ $active = $subscription->plan->is_active ? 'Yes' : 'No'}}
                    </td>
                    <td class="px-6 py-4">
                        {{ $subscription->updated_at }}
                    </td>
                    <td class="px-6 py-4">
                        <a {{-- href="/subscriptions/{{$subscription->id}}" --}} class="font-medium text-blue-600 dark:text-blue-500 hover:underline">View</a>
                    </td>
                </tr>
            </tbody>
            <?php $sn++; ?>
            @endforeach
        </table>
    </div>
    @else
    <div id="toast-success" class="flex items-center w-full p-4 mb-4 text-gray-500 bg-white rounded-lg shadow-sm dark:text-gray-400 dark:bg-gray-800" role="alert">
        <div class="inline-flex items-center justify-center shrink-0 w-8 h-8 text-orange-500 bg-orange-100 rounded-lg dark:bg-orange-700 dark:text-orange-200">
            <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM10 15a1 1 0 1 1 0-2 1 1 0 0 1 0 2Zm1-4a1 1 0 0 1-2 0V6a1 1 0 0 1 2 0v5Z"/>
            </svg>
            <span class="sr-only">Warning icon</span>
        </div>
        <div class="ms-3 text-sm font-normal">No Subscriptions Yet</div>
    </div>
    @endif
</div>
