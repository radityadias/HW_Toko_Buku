@props(['books', 'categories', 'transaction'])


<div class="relative overflow-x-auto sm:rounded-lg">
    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="p-4">
                    <div class="flex items-center">
                        <input id="checkbox-all-search" type="checkbox"
                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label for="checkbox-all-search" class="sr-only">checkbox</label>
                    </div>
                </th>
                <th scope="col" class="px-6 py-4">
                    <div class="flex items-center">
                        <span class="mr-2">ID</span>
                    </div>
                </th>
                <th scope="col" class="px-6 py-4">
                    <div class="flex items-center">
                        <span class="mr-2">Name</span>
                    </div>
                </th>
                <th scope="col" class="px-6 py-4">
                    <div class="flex items-center">
                        <span class="mr-2">Books</span>
                    </div>
                </th>
                <th scope="col" class="px-6 py-4">
                    <div class="flex items-center">
                        <span class="mr-2">Quantity</span>
                    </div>
                </th>
                <th scope="col" class="px-6 py-4">
                    <div class="flex items-center">
                        <span class="mr-2">Price</span>
                    </div>
                </th>


                <th scope="col" class="px-6 py-3">
                    Action
                </th>
            </tr>
        </thead>
        <tbody>
            @foreach ($transaction as $item)
                <tr
                    class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                    <td class="w-4 p-4">
                        <div class="flex items-center">
                            <input id="checkbox-table-search-1" type="checkbox"
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                            <label for="checkbox-table-search-1" class="sr-only">checkbox</label>
                        </div>
                    </td>
                    <th scope="row" class=" px-3 py-4 text-gray-900 whitespace-nowrap dark:text-white">
                        <div class="ps-3">
                            <div class="text-base font-semibold">{{ $item->sale_id }}</div>
                        </div>
                    </th>
                    <th scope="row" class=" px-3 py-4 text-gray-900 whitespace-nowrap dark:text-white">
                        <div class="ps-3">
                            <div class="text-base font-semibold">{{ $item->customer->name }}</div>
                        </div>
                    </th>
                    <th scope="row" class=" px-3 py-4 text-gray-900 whitespace-nowrap dark:text-white">
                        <div class="ps-3">
                            @foreach ($item->books as $book )
                                <div class="text-base font-semibold">{{ $book->title }},</div>
                            @endforeach
                        </div>
                    </th>
                    <th scope="row" class=" px-3 py-4 text-gray-900 whitespace-nowrap dark:text-white">
                        <div class="ps-3">
                            <div class="text-base font-semibold">null</div>
                        </div>
                    </th>
                    <th scope="row" class=" px-3 py-4 text-gray-900 whitespace-nowrap dark:text-white">
                        <div class="ps-3">
                            <div class="text-base font-semibold">{{ $item->total_price }}</div>
                        </div>
                    </th>
                    
                    <td class="px-6 py-4">

                        {{-- Delete Categories --}}

                        {{-- Button Delete Categories --}}
                        <button type="button"
                            class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-3 py-1.5 me-2  dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900"
                            data-modal-target="saleDelete{{ $item->sale_id }}"
                            data-modal-toggle="saleDelete{{ $item->sale_id }}">

                            <svg class="w-4 h-4 text-white dark:text-white" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M5 7h14m-9 3v8m4-8v8M10 3h4a1 1 0 0 1 1 1v3H9V4a1 1 0 0 1 1-1ZM6 7h12v13a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V7Z" />
                            </svg>
                        </button>

                        {{-- Modal Delete Books --}}
                        <div id="saleDelete{{ $item->sale_id }}" tabindex="-1" aria-hidden="true"
                            class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                            <div class="relative p-4 w-full max-w-2xl max-h-full">
                                <!-- Modal content -->
                                <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                                    <!-- Modal header -->
                                    <div
                                        class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                                            Delete Book Data
                                        </h3>
                                        <button type="button"
                                            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                                            data-modal-hide="saleDelete{{ $item->sale_id }}">
                                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                                fill="none" viewBox="0 0 14 14">
                                                <path stroke="currentColor" stroke-linecap="round"
                                                    stroke-linejoin="round" stroke-width="2"
                                                    d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                            </svg>
                                            <span class="sr-only">Close modal</span>
                                        </button>
                                    </div>
                                    <!-- Modal body -->
                                    <div class="p-4 md:p-5 space-y-4">
                                        <p class="text-base leading-relaxed text-gray-500 dark:text-gray-400">
                                            Are you sure want to delete the data?
                                        </p>

                                    </div>
                                    <!-- Modal footer -->
                                    <div
                                        class="flex items-center p-4 md:p-5 border-t border-gray-200 rounded-b dark:border-gray-600">
                                        <form action="{{ route('transaction.delete', $item->sale_id) }}"
                                            method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button data-modal-hide="saleDelete{{ $item->sale_id }}"
                                                type="submit"
                                                class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Delete</button>
                                        </form>
                                        <button data-modal-hide="saleDelete{{ $item->sale_id }}"
                                            type="button"
                                            class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">Cancel</button>
                                    </div>
                                </div>
                            </div>
                        </div>


                    </td>

                </tr>
            @endforeach


        </tbody>
    </table>
</div>
