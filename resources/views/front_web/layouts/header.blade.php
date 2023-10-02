<!-- start header section -->
<header class="bg-light">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-1 col-4">
                <a href="{{url('/')}}" class="header-logo">
                    <img src="{{asset($settings['logo'])}}" alt="Jobs" class="img-fluid"/>
                </a>
            </div>
            <div class="col-lg-11 col-8">
                <nav class="navbar navbar-expand-lg navbar-light justify-content-end py-0">
                    <button class="navbar-toggler border-0 p-0" type="button" data-bs-toggle="collapse"
                            data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false"
                            aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                        <ul class="navbar-nav align-items-center py-2 py-lg-0">
                            <li class="nav-item">
                                <a class="nav-link {{ Request::is('/') ? 'active' : '' }}" aria-current="page"
                                   href="{{ route('front.home') }}">{{ __('web.home') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ Request::is('search-jobs') || Request::is('job-details*') ? 'active' : '' }}"
                                   href="{{ route('front.search.jobs') }}">{{ __('web.jobs') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ Request::is('company-lists') || Request::is('company-details*') ? 'active' : '' }}"
                                   href="{{ route('front.company.lists') }}">{{ __('web.companies') }}</a>
                            </li>
                            @auth
                                @role('Employer|Admin')
                                <li class="nav-item">
                                    <a class="nav-link {{ Request::is('candidate-lists') || Request::is('candidate-details*') ? 'active' : '' }}"
                                       href="{{ route('front.candidate.lists') }}">{{ __('web.job_seekers') }}</a>
                                </li>
                                @endrole
                            @endauth
                            <li class="nav-item">
                                <a class="nav-link {{ Request::is('about-us') ? 'active' : '' }}"
                                   href="{{ route('front.about.us') }}">{{ __('web.about_us') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ Request::is('contact-us') ? 'active' : '' }}"
                                   href="{{ route('front.contact') }}">{{ __('web.contact_us') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ Request::is('posts*') ? 'active' : '' }}"
                                   href="{{ route('front.post.lists') }}">{{ __('messages.post.blog') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="javascript:void(0)">
                                    {{ getCurrentLanguageName() }}</a>
                                <ul class="nav submenu language-menu">
                                    @foreach(getUserLanguages() as $key => $value)
                                        <li class="languageSelection {{ (checkLanguageSession() == $key) ? 'active' : '' }}"
                                            data-prefix-value="{{ $key }}" style="max-height: 40px">
                                            <a class="dropdown-item {{ (checkLanguageSession() == $key) ? 'active' : '' }}"
                                               href="javascript:void(0)">
                                                @if(array_key_exists($key,\App\Models\User::LANGUAGES_IMAGE))
                                                    @foreach(\App\Models\User::LANGUAGES_IMAGE as $imageKey=> $imageValue)
                                                        @if($imageKey == $key)
                                                            <img class="me-2 country-flag" style="width: 20px;"
                                                                 src="{{asset($imageValue)}}"/>
                                                        @endif
                                                    @endforeach
                                                @else
                                                    <i class="fa fa-flag me-2 fs-7 text-danger" aria-hidden="true"
                                                       style="width: 20px;"></i>
                                                @endif
                                                {{ $value }}</a>
                                        </li>
                                    @endforeach
                                </ul>
                            </li>
                            @if(!Auth::check())
                                <div class="text-lg-end header-btn-grp ms-xxl-5 ms-lg-3">
                                    <ul class="navbar-nav align-items-center py-2 py-lg-0">
                                        <li class="nav-item">
                                            <a href="{{route('front.candidate.login')}}"
                                               class="nav-link btn btn-secondary me-xxl-3 me-2 mb-3 mb-lg-0 nav-link">{{ __('web.login') }}</a>
                                            <ul class="nav submenu">
                                                <li class="nav-item">
                                                    <a href="{{ route('front.candidate.login') }}"
                                                       class="nav-link d-flex align-items-center">
                                                        {{ __('messages.notification_settings.candidate') }}
                                                    </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a href="{{ route('front.employee.login') }}"
                                                       class="nav-link d-flex align-items-center">
                                                        {{ __('messages.company.employer') }}
                                                    </a>
                                                </li>
                                            </ul>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{route('candidate.register')}}"
                                               class="btn btn-primary mb-3 mb-lg-0">{{ __('web.register') }}</a>
                                            <ul class="nav submenu">
                                                <li class="nav-item">
                                                    <a href="{{ route('candidate.register') }}"
                                                       class="nav-link d-flex align-items-center">
                                                        {{ __('messages.notification_settings.candidate') }}
                                                    </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a href="{{ route('employer.register') }}"
                                                       class="nav-link d-flex align-items-center">
                                                        {{ __('messages.company.employer') }}
                                                    </a>
                                                </li>
                                            </ul>
                                        </li>
                                    </ul>
                                </div>
                            @else
                                <div class="text-lg-end header-btn-grp ms-xxl-5 ms-lg-3">
                                    <ul class="navbar-nav align-items-center py-2 py-lg-0">
                                        <li class="nav-item">
                                            <a href="javascript:void(0)"
                                               class="mb-3 mb-lg-0 user-logo d-flex align-items-center">
                                                <img src="{{ getLoggedInUser()->avatar }}" width="50"
                                                     class="rounded object-cover"/>&nbsp;&nbsp;
                                                <span
                                                    class="text-truncate"> {{ __('messages.common.hi') }}, {{getLoggedInUser()->full_name}}</span>
                                            </a>
                                            <ul class="nav submenu">
                                                <li class="nav-item">
                                                    <a href="{{ dashboardURL() }}" data-turbo="false"
                                                       class="nav-link d-flex align-items-center">
                                                        {{ __('web.go_to_dashboard') }}
                                                    </a>
                                                </li>
                                                @role('Candidate')
                                                <li class="nav-item">
                                                    <a href="{{ route('candidate.profile') }}" data-turbo="false"
                                                       class="nav-link d-flex align-items-center">
                                                        {{ __('web.my_profile') }}
                                                    </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a href="{{ route('favourite.jobs') }}" data-turbo="false"
                                                       class="nav-link d-flex align-items-center">
                                                        {{ __('messages.favourite_jobs') }}
                                                    </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a href="{{ route('favourite.companies') }}" data-turbo="false"
                                                       class="nav-link d-flex align-items-center">
                                                        {{ __('messages.candidate_dashboard.followings') }}
                                                    </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a href="{{ route('candidate.applied.job') }}" data-turbo="false"
                                                       class="nav-link d-flex align-items-center">
                                                        {{ __('messages.applied_job.applied_jobs') }}
                                                    </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a href="{{ route('candidate.job.alert') }}" data-turbo="false"
                                                       class="nav-link d-flex align-items-center">
                                                        {{ __('messages.job.job_alert') }}
                                                    </a>
                                                </li>
                                                @endrole
                                                @role('Employer')
                                                <li class="nav-item">
                                                    <a href="{{ route('company.edit.form', \Illuminate\Support\Facades\Auth::user()->owner_id) }}"
                                                       data-turbo="false"
                                                       class="nav-link d-flex align-items-center">
                                                        {{ __('web.my_profile') }}
                                                    </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a href="{{ route('job.index') }}" data-turbo="false"
                                                       class="nav-link d-flex align-items-center">
                                                        {{ __('messages.employer_menu.jobs') }}
                                                    </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a href="{{ route('followers.index') }}" data-turbo="false"
                                                       class="nav-link d-flex align-items-center">
                                                        {{ __('messages.employer_menu.followers') }}
                                                    </a>
                                                </li>
                                                @if(getSettingValue('enable_subscription_plan'))
                                                    <li class="nav-item">
                                                        <a href="{{ route('manage-subscription.index') }}"
                                                           data-turbo="false"
                                                           class="nav-link d-flex align-items-center">
                                                            {{ __('messages.employer_menu.manage_subscriptions') }}
                                                        </a>
                                                    </li>
                                                @endif
                                                <li class="nav-item">
                                                    <a href="{{ route('transactions.index') }}" data-turbo="false"
                                                       class="nav-link d-flex align-items-center">
                                                        {{ __('messages.employer_menu.transactions') }}
                                                    </a>
                                                </li>
                                                @endrole
                                                {{--<li class="nav-item">
                                                    <div class="nav-link d-flex align-items-center pb-0">
                                                        Select Theme
                                                    </div>
                                                    <div class="nav-link d-flex align-items-center pt-0">
                                                        <div class="theme-selector" onclick="selectTheme()"></div>
                                                        <div class="theme-selector" onclick="selectTheme('cerulean')"></div>
                                                        <div class="theme-selector" onclick="selectTheme('vapor')"></div>
                                                        <div class="theme-selector" onclick="selectTheme('sketchy')"></div>
                                                    </div>
                                                </li>--}}
                                                <li class="nav-item">
                                                    <a href="{{ url('logout') }}" data-turbo="false"
                                                       class="nav-link d-flex align-items-center"
                                                       onclick="event.preventDefault(); localStorage.clear();  document.getElementById('logout-form').submit();">
                                                        {{ __('web.logout') }}
                                                    </a>
                                                    <form id="logout-form" action="{{ url('/logout') }}" method="POST"
                                                          class="d-none">
                                                        {{ csrf_field() }}
                                                    </form>
                                                </li>
                                            </ul>
                                        </li>
                                    </ul>
                                </div>
                        @endif
                    </div>
                </nav>
            </div>
        </div>
    </div>
</header>
<!-- end header section -->
