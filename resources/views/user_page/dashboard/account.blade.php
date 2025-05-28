@extends('user_page.app')

@section('content')
    <style>
        #sidebar-overlay {
            -webkit-backdrop-filter: blur(4px); /* For Safari */
            backdrop-filter: blur(4px);
        }
    </style>
    <!-- Navbar -->
    <header class="antialiased">
        <nav class="bg-white border-gray-200 px-4 py-2.5 dark:bg-gray-800">
            <div class="flex justify-between items-center">
                <div class="flex items-center">
                    <button id="hamburger" type="button" class="inline-flex items-center p-2 text-sm text-gray-500 rounded-lg hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700">
                        <i class="fas fa-bars text-xl"></i> 
                    </button>

                    <a href="{{ route('account') }}" class="flex mr-4">
                        <img src="https://flowbite.s3.amazonaws.com/logo.svg" class="mr-3 h-8" alt="FlowBite Logo" />
                        <span class="self-center text-2xl font-semibold whitespace-nowrap dark:text-white">Koperasi</span>
                    </a>
                </div>
                
                <!-- Preview Profile -->
                <div class="flex items-center">
                    <button type="button" class="flex mx-3 text-sm bg-gray-800 rounded-full focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-600" id="user-menu-button" aria-expanded="false" data-dropdown-toggle="dropdown">
                        <span class="sr-only">Open user menu</span>
                        <img class="w-8 h-8 rounded-full" src="" id="profile" alt="user photo">
                    </button>
                    <div class="hidden z-50 my-4 w-56 text-base list-none bg-white rounded divide-y divide-gray-100 shadow dark:bg-gray-700 dark:divide-gray-600" id="dropdown">
                        <div class="py-3 px-4">
                            <span id="email" class="block text-sm text-gray-500 truncate dark:text-gray-400">loading...</span>
                        </div>
                        <div class="py-3 px-4">
                            <span id="name" class="block text-sm text-gray-500 truncate dark:text-gray-400">loading...</span>
                        </div>
                        <div class="py-3 px-4">
                            <span id="phone" class="block text-sm text-gray-500 truncate dark:text-gray-400">loading...</span>
                        </div>
                        <ul class="py-1 text-gray-500 dark:text-gray-400" aria-labelledby="dropdown">
                            <li>
                                <a href="#" id="sign-out" class="block py-2 px-4 text-sm hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Sign out</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>
    </header>

    <!-- Sidebar Overlay -->
    <div id="sidebar-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden"></div>

    <!-- Sidebar -->
    <aside id="sidebar" class="fixed top-0 left-0 z-50 w-64 h-full bg-white border-r border-gray-200 transform -translate-x-full transition-transform dark:bg-gray-800 dark:border-gray-700">
        <div class="p-4 space-y-2">
            <a href="{{ route('account') }}" data-table="simpan" class="block px-4 py-2 rounded text-white hover:bg-gray-100 dark:hover:bg-gray-700">Simpan</a>
            <a href="{{ route('member-profile') }}" data-table="profile" class="block px-4 py-2 rounded  text-white hover:bg-gray-100 dark:hover:bg-gray-700">Profile</a>
        </div>
    </aside>

    <!-- Dynamic Content Container -->
    <div id="table-container" class="p-3 sm:p-5 antialiased">
        <!-- Table -->
        <section class="bg-gray-50 dark:bg-gray-900 p-3 sm:p-5 antialiased">
            <div class="mx-auto max-w-screen-2xl px-4 lg:px-12">
                <div class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-3 md:space-y-0 md:space-x-4 p-4">
                        <div class="flex-1 flex items-center space-x-2">
                            <h5>
                                <span class="text-gray-500 dark:text-white">Account Information</span>
                            </h5>
                        </div>
                    </div>
                    <div class="flex flex-col md:flex-row items-stretch md:items-center md:space-x-3 space-y-3 md:space-y-0 justify-between mx-4 py-4 border-t dark:border-gray-700">
                        <div class="w-full md:w-1/2">
                            <form class="flex items-center">
                                <label for="simple-search" class="sr-only">Search</label>
                                <div class="relative w-full">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                        <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" />
                                        </svg>
                                    </div>
                                    <input type="text" id="simple-search" placeholder="Search for member" required="" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full pl-10 p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                </div>
                            </form>
                        </div>
                        <div class="w-full md:w-auto flex flex-col md:flex-row space-y-2 md:space-y-0 items-stretch md:items-center justify-end md:space-x-3 flex-shrink-0">
                            <button data-drawer-target="main-saving" data-drawer-show="main-saving" aria-controls="main-saving" class="w-full justify-center sm:w-auto text-gray-500 inline-flex items-center bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-primary-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">
                                Deposit Main Saving
                            </button>
                        </div>
                        <div class="w-full md:w-auto flex flex-col md:flex-row space-y-2 md:space-y-0 items-stretch md:items-center justify-end md:space-x-3 flex-shrink-0">
                            <button data-drawer-target="sibuhar" data-drawer-show="sibuhar" aria-controls="sibuhar" aria-controls="sibuhar" class="w-full justify-center sm:w-auto text-gray-500 inline-flex items-center bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-primary-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">
                                Deposit Sibuhar
                            </button>
                        </div>
                        <div class="w-full md:w-auto flex flex-col md:flex-row space-y-2 md:space-y-0 items-stretch md:items-center justify-end md:space-x-3 flex-shrink-0">
                            <button data-drawer-target="loan" data-drawer-show="loan" aria-controls="loan" class="w-full justify-center sm:w-auto text-gray-500 inline-flex items-center bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-primary-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">
                                Take Loan
                            </button>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="p-4">
                                        <div class="flex items-center">
                                            <input id="checkbox-all" type="checkbox" class="w-4 h-4 text-primary-600 bg-gray-100 rounded border-gray-300 focus:ring-primary-500 dark:focus:ring-primary-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                            <label for="checkbox-all" class="sr-only">checkbox</label>
                                        </div>
                                    </th>
                                    <th scope="col" class="p-4">Name</th>
                                    <th scope="col" class="p-4">Simpanan Pokok</th>
                                    <th scope="col" class="p-4">Simpanan Wajib</th>
                                    <th scope="col" class="p-4">Simpanan Sukarela</th>
                                    <th scope="col" class="p-4">Sibuhar</th>
                                    <th scope="col" class="p-4">Debt</th>
                                    <th scope="col" class="p-4">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="border-b dark:border-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700">
                                    <td class="p-4 w-4">
                                        <div class="flex items-center">
                                            <input id="checkbox-table-search-1" type="checkbox" onclick="event.stopPropagation()" class="w-4 h-4 text-primary-600 bg-gray-100 rounded border-gray-300 focus:ring-primary-500 dark:focus:ring-primary-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                            <label for="checkbox-table-search-1" class="sr-only">checkbox</label>
                                        </div>
                                    </td>
                                    <th scope="row" class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        <div class="flex items-center mr-3">
                                            <img src="https://flowbite.s3.amazonaws.com/blocks/application-ui/products/imac-front-image.png" alt="iMac Front Image" class="h-8 w-auto mr-3">
                                            Apple iMac 27&#34;
                                        </div>
                                    </th>
                                    <td class="px-4 py-3">
                                        <span class="bg-primary-100 text-primary-800 text-xs font-medium px-2 py-0.5 rounded dark:bg-primary-900 dark:text-primary-300">Desktop PC</span>
                                    </td>
                                    <td class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        <div class="flex items-center">
                                            <div class="h-4 w-4 rounded-full inline-block mr-2 bg-red-700"></div>
                                            95
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">1.47</td>
                                    <td class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        <div class="flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewbox="0 0 24 24" fill="currentColor" class="w-5 h-5 text-gray-400 mr-2" aria-hidden="true">
                                                <path d="M2.25 2.25a.75.75 0 000 1.5h1.386c.17 0 .318.114.362.278l2.558 9.592a3.752 3.752 0 00-2.806 3.63c0 .414.336.75.75.75h15.75a.75.75 0 000-1.5H5.378A2.25 2.25 0 017.5 15h11.218a.75.75 0 00.674-.421 60.358 60.358 0 002.96-7.228.75.75 0 00-.525-.965A60.864 60.864 0 005.68 4.509l-.232-.867A1.875 1.875 0 003.636 2.25H2.25zM3.75 20.25a1.5 1.5 0 113 0 1.5 1.5 0 01-3 0zM16.5 20.25a1.5 1.5 0 113 0 1.5 1.5 0 01-3 0z" />
                                            </svg>
                                            1.6M
                                        </div>
                                    </td>
                                    <td class="px-4 py-3">$3.2M</td>
                                    <td class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        <div class="flex items-center space-x-4">
                                            <button type="button" data-drawer-target="read-member" data-drawer-show="read-member" aria-controls="read-member" class="py-2 px-3 flex items-center text-sm font-medium text-center text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-primary-700 focus:z-10 focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewbox="0 0 24 24" fill="currentColor" class="w-4 h-4 mr-2 -ml-0.5">
                                                    <path d="M12 15a3 3 0 100-6 3 3 0 000 6z" />
                                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M1.323 11.447C2.811 6.976 7.028 3.75 12.001 3.75c4.97 0 9.185 3.223 10.675 7.69.12.362.12.752 0 1.113-1.487 4.471-5.705 7.697-10.677 7.697-4.97 0-9.186-3.223-10.675-7.69a1.762 1.762 0 010-1.113zM17.25 12a5.25 5.25 0 11-10.5 0 5.25 5.25 0 0110.5 0z" />
                                                </svg>
                                                Preview
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <nav class="flex flex-col md:flex-row justify-between items-start md:items-center space-y-3 md:space-y-0 p-4" aria-label="Table navigation">
                        <span class="text-sm font-normal text-gray-500 dark:text-gray-400">
                            Showing
                            <span class="font-semibold text-gray-900 dark:text-white">1-10</span>
                            of
                            <span class="font-semibold text-gray-900 dark:text-white">1000</span>
                        </span>
                        <ul class="inline-flex items-stretch -space-x-px">
                            <li>
                                <a href="#" class="flex items-center justify-center h-full py-1.5 px-3 ml-0 text-gray-500 bg-white rounded-l-lg border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
                                    <span class="sr-only">Previous</span>
                                    <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    </svg>
                                </a>
                            </li>
                            <li>
                                <a href="#" class="flex items-center justify-center text-sm py-2 px-3 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">1</a>
                            </li>
                            <li>
                                <a href="#" class="flex items-center justify-center text-sm py-2 px-3 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">2</a>
                            </li>
                            <li>
                                <a href="#" aria-current="page" class="flex items-center justify-center text-sm z-10 py-2 px-3 leading-tight text-primary-600 bg-primary-50 border border-primary-300 hover:bg-primary-100 hover:text-primary-700 dark:border-gray-700 dark:bg-gray-700 dark:text-white">3</a>
                            </li>
                            <li>
                                <a href="#" class="flex items-center justify-center text-sm py-2 px-3 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">...</a>
                            </li>
                            <li>
                                <a href="#" class="flex items-center justify-center text-sm py-2 px-3 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">100</a>
                            </li>
                            <li>
                                <a href="#" class="flex items-center justify-center h-full py-1.5 px-3 leading-tight text-gray-500 bg-white rounded-r-lg border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
                                    <span class="sr-only">Next</span>
                                    <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                    </svg>
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </section>

        <!-- Main Saving -->
        <form action="#" id="main-saving" class="fixed top-0 left-0 z-40 w-full h-screen max-w-md p-4 overflow-y-auto transition-transform -translate-x-full bg-white dark:bg-gray-800" tabindex="-1" aria-labelledby="" aria-hidden="true">
            <h5 id="" class="inline-flex items-center mb-4 text-xs font-semibold text-gray-500 uppercase dark:text-gray-400">Deposit Main Saving</h5>
            <button type="button" data-drawer-dismiss="main-saving" aria-controls="main-saving" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 absolute top-2.5 right-2.5 dark:hover:bg-gray-600 dark:hover:text-white">
                <svg aria-hidden="true" class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                <span class="sr-only">Close menu</span>
            </button>
            <div class="grid gap-4"> <!-- Slightly increased gap for cleaner spacing -->
                <div>
                  <label for="amount" class="block mb-2 text-sm font-semibold text-gray-800 dark:text-gray-200">Enter Amount</label>
                  <input
                    type="number"
                    name="amount"
                    id="amount"
                    class="bg-white border border-gray-300 text-gray-900 text-sm rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-600 focus:border-primary-600 block w-full p-3 dark:bg-gray-800 dark:border-gray-700 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                    placeholder="Rp. 100.000"
                    required
                  >
                </div>
            </div>              
            <div class="grid grid-cols-2 gap-3 mt-4">
                <button type="submit" class="w-full justify-center sm:w-auto text-gray-500 inline-flex items-center bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-primary-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">Deposit</button>
                <button type="button" data-drawer-dismiss="main-saving" aria-controls="main-saving" class="w-full justify-center sm:w-auto text-gray-500 inline-flex items-center bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-primary-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">
                    Cancel
                </button>
            </div>
        </form>

        <!-- Sibuhar -->
        <form action="#" id="sibuhar" class="fixed top-0 left-0 z-40 w-full h-screen max-w-md p-4 overflow-y-auto transition-transform -translate-x-full bg-white dark:bg-gray-800" tabindex="-1" aria-labelledby="createProductModal-label" aria-hidden="true">
            <h5 id="" class="inline-flex items-center mb-4 text-xs font-semibold text-gray-500 uppercase dark:text-gray-400">Deposit Sibuhar</h5>
            <button type="button" data-drawer-dismiss="sibuhar" aria-controls="sibuhar" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 absolute top-2.5 right-2.5 dark:hover:bg-gray-600 dark:hover:text-white">
                <svg aria-hidden="true" class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                <span class="sr-only">Close menu</span>
            </button>
            <div class="grid gap-4"> <!-- Slightly increased gap for cleaner spacing -->
                <div>
                  <label for="amount" class="block mb-2 text-sm font-semibold text-gray-800 dark:text-gray-200">Enter Amount</label>
                  <input
                    type="number"
                    name="amount"
                    id="amount"
                    class="bg-white border border-gray-300 text-gray-900 text-sm rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-600 focus:border-primary-600 block w-full p-3 dark:bg-gray-800 dark:border-gray-700 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                    placeholder="Rp. 100.000"
                    required
                  >
                </div>
            </div>              
            <div class="grid grid-cols-2 gap-3 mt-4">
                <button type="submit" class="w-full justify-center sm:w-auto text-gray-500 inline-flex items-center bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-primary-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">Deposit</button>
                <button type="button" data-drawer-dismiss="sibuhar" aria-controls="sibuhar" class="w-full justify-center sm:w-auto text-gray-500 inline-flex items-center bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-primary-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">
                    Cancel
                </button>
            </div>
        </form>

        <!-- Loan -->
        <form action="#" id="loan" class="fixed top-0 left-0 z-40 w-full h-screen max-w-md p-4 overflow-y-auto transition-transform -translate-x-full bg-white dark:bg-gray-800" tabindex="-1" aria-labelledby="createProductModal-label" aria-hidden="true">
            <h5 id="" class="inline-flex items-center mb-4 text-xs font-semibold text-gray-500 uppercase dark:text-gray-400">Take Loan</h5>
            <button type="button" data-drawer-dismiss="loan" aria-controls="loan" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 absolute top-2.5 right-2.5 dark:hover:bg-gray-600 dark:hover:text-white">
                <svg aria-hidden="true" class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                <span class="sr-only">Close menu</span>
            </button>
            <div class="grid gap-4"> <!-- Slightly increased gap for cleaner spacing -->
                <div>
                  <label for="amount" class="block mb-2 text-sm font-semibold text-gray-800 dark:text-gray-200">Enter Amount</label>
                  <input
                    type="number"
                    name="amount"
                    id="amount"
                    class="bg-white border border-gray-300 text-gray-900 text-sm rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-600 focus:border-primary-600 block w-full p-3 dark:bg-gray-800 dark:border-gray-700 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                    placeholder="Rp. 100.000"
                    required
                  >
                </div>
            </div>              
            <div class="grid grid-cols-2 gap-3 mt-4">
                <button type="submit" class="w-full justify-center sm:w-auto text-gray-500 inline-flex items-center bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-primary-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">Take Loan</button>
                <button type="button" data-drawer-dismiss="loan" aria-controls="loan" class="w-full justify-center sm:w-auto text-gray-500 inline-flex items-center bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-primary-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">
                    Cancel
                </button>
            </div>
        </form>
        
        <!-- Preview Member Modal -->
        <div id="read-member" class="overflow-y-auto fixed top-0 left-0 z-40 p-4 w-full max-w-lg h-screen bg-white transition-transform -translate-x-full dark:bg-gray-800" tabindex="-1" aria-labelledby="drawer-label" aria-hidden="true">
            <div>
                <h4 id="read-drawer-label" class="mb-5 leading-none text-xl font-semibold text-gray-900 dark:text-white">Account Information</h4>
            </div>
            <button type="button" data-drawer-dismiss="read-member" aria-controls="read-member" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 absolute top-2.5 right-2.5 inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white">
                <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
                <span class="sr-only">Close menu</span>
            </button>
            <div class="grid grid-cols-3 gap-4 mb-4 sm:mb-5">
                <div class="p-2 w-auto bg-gray-100 rounded-lg dark:bg-gray-700">
                    <img src="https://flowbite.s3.amazonaws.com/blocks/application-ui/products/imac-side-image.png" alt="Member profile picture">
                </div>
            </div> 
            <dl class="grid grid-cols-2 gap-4 mb-4">
                <div class="col-span-2 p-3 bg-gray-100 rounded-lg border border-gray-200 dark:bg-gray-700 sm:col-span-1 dark:border-gray-600">
                    <dt class="mb-2 font-semibold leading-none text-gray-900 dark:text-white">Name</dt>
                    <dd class="flex items-center text-gray-500 dark:text-gray-400">
                        12345678
                    </dd>
                </div>
                <div class="p-3 bg-gray-100 rounded-lg border border-gray-200 dark:bg-gray-700 dark:border-gray-600">
                    <dt class="mb-2 font-semibold leading-none text-gray-900 dark:text-white">Simpanan Pokok</dt>
                    <dd class="text-gray-500 dark:text-gray-400">
                        <dd class="flex items-center text-gray-500 dark:text-gray-400">
                            12345678
                        </dd>
                    </dd>
                </div>
                <div class="p-3 bg-gray-100 rounded-lg border border-gray-200 dark:bg-gray-700 dark:border-gray-600"><dt class="mb-2 font-semibold leading-none text-gray-900 dark:text-white">Simpanan Wajib</dt><dd class="text-gray-500 dark:text-gray-400">Flowbite</dd></div>
                <div class="p-3 bg-gray-100 rounded-lg border border-gray-200 dark:bg-gray-700 dark:border-gray-600"><dt class="mb-2 font-semibold leading-none text-gray-900 dark:text-white">Simpanan Sukarela</dt><dd class="text-gray-500 dark:text-gray-400">Flowbite</dd></div>
                <div class="p-3 bg-gray-100 rounded-lg border border-gray-200 dark:bg-gray-700 dark:border-gray-600"><dt class="mb-2 font-semibold leading-none text-gray-900 dark:text-white">Sibuhar</dt><dd class="text-gray-500 dark:text-gray-400">Apple</dd></div>
                <div class="p-3 bg-gray-100 rounded-lg border border-gray-200 dark:bg-gray-700 dark:border-gray-600"><dt class="mb-2 font-semibold leading-none text-gray-900 dark:text-white">Debt</dt><dd class="text-gray-500 dark:text-gray-400">105 x 15 x 23</dd></div>
            </dl>
            <div class="flex bottom-0 left-0 justify-center pb-4 space-x-4 w-full">
                <button type="button" data-drawer-dismiss="read-member" aria-controls="read-member" class="w-full justify-center sm:w-auto text-gray-500 inline-flex items-center bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-primary-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">
                    Back
                </button>
            </div>
        </div>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/datepicker.min.js"></script>
    </div>

    @push('scripts')
        <!-- Auth Script -->
        {{-- <script>
            document.addEventListener('DOMContentLoaded', async () => {
                const { data: { user }, error } = await window.supabase.auth.getUser();
        
                if (!user || error) {
                    // Not authenticated — redirect to welcome
                    window.location.href = '/member';
                    return;
                }
        
                // Save auth session state
                sessionStorage.setItem('loggedIn', 'true');
        
                // Prevent back button
                history.pushState(null, null, window.location.href);
                window.addEventListener('popstate', function (event) {
                    if (sessionStorage.getItem('loggedIn') === 'true') {
                        history.pushState(null, null, window.location.href);
                    }
                });

                const response = await fetch('/get-member', {
                    method : 'POST',
                    headers : {
                        'Content-Type' : 'application/json',
                        'X-CSRF-TOKEN' : document.querySelector('meta[name="csrf-token"]').content
                    },
                    body : JSON.stringify({email : user.email})
                });

                const userData = await response.json();

                document.getElementById('email').textContent = userData.email;
                document.getElementById('profile').src = userData.profile || 'https://flowbite.com/docs/images/people/profile-picture-5.jpg';
                document.getElementById('name').textContent = userData.username;
                document.getElementById('phone').textContent = userData.phone;
        
                // Sign-out handling
                const signOut = document.getElementById('sign-out');
                if (signOut) {
                    signOut.addEventListener('click', async (event) => {
                        event.preventDefault();
                        await window.supabase.auth.signOut();
                        sessionStorage.removeItem('loggedIn'); // Clear session flag
                        window.location.href = '/member';
                    });
                }
            });
        </script> --}}

        <!-- Sidebar Script -->
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const links = document.querySelectorAll('[data-table]');
                const container = document.getElementById('table-container');
                const sidebar = document.getElementById('sidebar');
                const overlay = document.getElementById('sidebar-overlay');
                const toggleButton = document.getElementById('hamburger');

                // Toggle sidebar
                toggleButton.addEventListener('click', function () {
                    sidebar.classList.toggle('-translate-x-full');
                    overlay.classList.toggle('hidden');
                });

                // Close sidebar on overlay click
                overlay.addEventListener('click', function () {
                    sidebar.classList.add('-translate-x-full');
                    overlay.classList.add('hidden');
                });

                // Close sidebar on Escape key press
                document.addEventListener('keydown', function (event) {
                    if (event.key === 'Escape') {
                        sidebar.classList.add('-translate-x-full');
                        overlay.classList.add('hidden');
                    }
                });
            });
        </script>

        <!-- Toogle Password Script -->
        <script>
            const togglePassword = document.getElementById('toggle-password');
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eye-icon');

            togglePassword.addEventListener('click', () => {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                eyeIcon.classList.toggle('text-blue-600');
            });
        </script>
    @endpush
@endsection