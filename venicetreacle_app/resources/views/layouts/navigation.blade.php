<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo :size="'w-12 h-12'" class="block h-9 w-auto fill-current text-gray-800" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                </div>

                                @if(auth()->user()->role->title === 'Administrator')

                <!-- Navigation Links -->
                <div class="hidden sm:flex sm:items-center sm:ms-6">
                        <x-dropdown align="left" width="w-32" menuName="adminMenu"  >
                            <x-slot name="trigger">
                                <x-nav-link :active="request()->routeIs('users.index')" >
                                    <div >{{__('Admin')}}</div>
                                    <x-nav-down-arrow />
                                </x-nav-link>

                            </x-slot>

                            <x-slot name="content">
                                <x-dropdown-link :href="route('users.index')" :active="request()->routeIs('users.index')" @click="adminMenu = ! adminMenu">
                                    {{ __('Users') }}
                                </x-dropdown-link>

                                <x-dropdown-link :href="route('user-logs.index')"  :active="request()->routeIs('user-logs.index')" @click="adminMenu = ! adminMenu">
                                    {{ __('User Log') }}
                                </x-dropdown-link>

                                <x-dropdown-link :href="route('emails.index')" :active="request()->routeIs('emails.index')" @click="adminMenu = ! adminMenu">
                                        {{ __('Email Log') }}
                                </x-dropdown-link>

                                <x-dropdown-link :href="route('blacklist.index')" :active="request()->routeIs('blacklist.index')" @click="adminMenu = ! adminMenu">
                                    {{ __('Email Blacklist') }}
                                </x-dropdown-link>

                                {{-- This is an example of a second tier menu, useful for the future 
                                <x-dropdown-link x-on:click="logMenu = ! logMenu" @click.away="logMenu = false" class="inline-flex items-center justify-between">
                                    {{ __('Logs') }}
                                    <div class="ms-1">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                </x-dropdown-link>

                                <div class="rounded-md ring-1 ring-black ring-opacity-5 py-1 bg-white w-40 absolute left-32 top-[4.5rem] "
                                    x-show="logMenu" x-transition:enter="transition ease-out duration-100"
                                    x-transition:enter-start="transform opacity-0 scale-95">
                                    <x-dropdown-link :href="route('emails.index')" @click="adminMenu = ! adminMenu">
                                        {{ __('Email Log') }}
                                    </x-dropdown-link>
                                    <x-dropdown-link :href="route('blacklist.index')" @click="adminMenu = ! adminMenu">
                                        {{ __('Email Blacklist') }}
                                    </x-dropdown-link>
                                    <x-dropdown-link :href="route('user-logs.index')" @click="adminMenu = ! adminMenu">
                                        {{ __('User Log') }}
                                    </x-dropdown-link>
                                    <x-dropdown-link>
                                        {{ __('App Log') }}
                                    </x-dropdown-link>
                                </div>
                                --}}

                            </x-slot>
                        </x-dropdown>
                    </div>
                    @endif
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>

@if(auth()->user()->role->title === 'Administrator')
            <x-responsive-nav-link :href="route('users.index')" :active="request()->routeIs('users.index')">
                {{ __('Users') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('user-logs.index')" :active="request()->routeIs('user-logs.index')">
                {{ __('User Log') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('emails.index')" :active="request()->routeIs('emails.index')">
                {{ __('Email Log') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('blacklist.index')" :active="request()->routeIs('blacklist.index')">
                {{ __('Email Blacklist') }}
            </x-responsive-nav-link>
@endif            
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
