<!-- start footer section -->
<footer class="footer bg-light">
    <div class="container">
        <div class="row justify-content-between">
            <div class="col-xxl-3 col-xl-4 col-lg-5 mb-xl-0 mb-5">
                <div class="footer-logo">
                    <a href="{{ route('front.home') }}">
                        <img src="{{ asset($settings['footer_logo']) }}" alt="jobs-landing" class="img-fluid"/>
                    </a>
                </div>
                <p class="d-block text-gray my-4">
                    {{ __('web.footer.newsletter_text') }}
                </p>
                <form id="newsLetterForm">
                    <div class="email d-flex">
                        <div class="response"></div>
                        <input type="email" id="mc-email" name="email" placeholder="{{__('web.enter_your_mail')}}" class="text-gray"
                               autocomplete="off" required>
                        <button
                            class="icon d-flex justify-content-center align-items-center bg-primary border-0 btnLetterSave"
                            title="Subscribe">
                            <i class="fa-solid fa-paper-plane text-white"></i>
                        </button>
                    </div>
                </form>
                <div class="social-icon d-flex mt-4">
                    @if(!empty($settings['facebook_url']))
                        <a href="{{ $settings['facebook_url'] }}" target="_blank" class="social-icon-box d-flex align-items-center justify-content-center me-3">
                            <i class="fa-brands fa-facebook-f text-gray"></i>
                        </a>
                    @endif
                    @if(!empty($settings['twitter_url']))
                        <a href="{{ $settings['twitter_url'] }}" target="_blank" class="social-icon-box d-flex align-items-center justify-content-center me-3">
                            <i class="fa-brands fa-twitter text-gray"></i>
                        </a>
                    @endif
                    @if(!empty($settings['google_plus_url']))
                        <a href="{{ $settings['google_plus_url'] }}" target="_blank" class="social-icon-box d-flex align-items-center justify-content-center me-3">
                            <i class="fa-brands fa-google text-gray"></i>
                        </a>
                    @endif
                    @if(!empty($settings['linkedIn_url']))
                        <a href="{{ $settings['linkedIn_url'] }}" target="_blank" class="social-icon-box d-flex align-items-center justify-content-center me-3">
                            <i class="fa-brands fa-linkedin-in text-gray"></i>
                        </a>
                    @endif
                </div>
            </div>
            <div class="col-xl-2 col-lg-5 col-md-6 mb-3 ps-xl-5">
                <h3 class="mb-3 text-secondary fs-18">{{ __('web.footer.useful_links') }}</h3>
                <ul class="ps-0">
                    <li><a href="{{ url('/') }}"
                           class="text-decoration-none mb-3 d-block {{ Request::is('/') ? 'text-dark' : 'text-gray' }} fs-14">{{ __('web.home') }}</a>
                    </li>
                    <li>
                        <a href="{{ route('front.search.jobs') }}"
                           class="text-decoration-none mb-3 d-block {{ Request::is('search-jobs') || Request::is('job-details*') ? 'text-dark' : 'text-gray' }} fs-14">{{ __('web.jobs') }}</a>
                    </li>
                    <li>
                        <a href="{{ route('front.company.lists') }}"
                           class="text-decoration-none mb-3 d-block {{ Request::is('company-lists') || Request::is('company-details*') ? 'text-dark' : 'text-gray' }} fs-14">{{ __('web.companies') }}</a>
                    </li>
                </ul>
            </div>
            <div class="col-xl-2 col-lg-5 col-md-6 mb-3">
                <h3 class="mb-3 text-secondary fs-18">{{__('web.web_home.helpful_resources')}}</h3>
                <ul class="ps-0">
                    <li>
                        <a href="{{ route('front.about.us') }}"
                           class="text-decoration-none mb-3 d-block {{ Request::is('about-us') ? 'text-dark' : 'text-gray' }} fs-14">
                            {{ __('web.about_us') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('front.contact') }}"
                           class="text-decoration-none mb-3 d-block {{ Request::is('contact-us') ? 'text-dark' : 'text-gray' }} fs-14">
                            {{ __('web.contact_us') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('front.post.lists') }}"
                           class="text-decoration-none mb-3 d-block {{ Request::is('posts') ? 'text-dark' : 'text-gray' }} fs-14">
                            {{ __('messages.post.blog') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('privacy.policy.list') }}"
                           class="text-decoration-none mb-3 d-block {{ Request::is('privacy-policy-list') ? 'text-dark' : 'text-gray' }} fs-14">
                            {{ __('messages.setting.privacy_policy') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('terms.conditions.list') }}"
                           class="text-decoration-none mb-3 d-block {{ Request::is('terms-conditions-list') ? 'text-dark' : 'text-gray' }} fs-14">
                            {{ __('messages.setting.terms_conditions') }}
                        </a>
                    </li>
                </ul>
            </div>
            <div class="col-xl-3 col-lg-5">
                <h3 class="mb-3 text-secondary fs-18">{{__('web.contact_us')}}</h3>
                <div class="footer-info">
                    <div class="d-flex footer-info__block mb-3">
                        <span class="f-icon-box"><i class="fa-solid fa-phone text-gray me-3 fs-14"></i></span>
                        <a href="tel:{{ '+'.$settings['region_code'].' '.$settings['phone'] }}"
                           class="text-decoration-none text-gray fs-14">
                            {{ $settings['region_code'].' '.$settings['phone'] }}
                        </a>
                    </div>
                    <div class="d-flex footer-info__block mb-3">
                        <span class="f-icon-box"><i class="fa-solid fa-location-dot me-3 text-gray fs-14"></i></span>
                        <p class="text-gray mb-0 fs-14">
                            {{$settings['address'] }}
                        </p>
                    </div>
                    <div class="d-flex footer-info__block mb-3">
                        <span class="f-icon-box"><i class="fa-solid fa-at me-3 text-gray mt-2"></i></span>
                        <a href="mailto:{{$settings['email']}}" class="text-decoration-none text-gray">
                            {{ $settings['email'] }}
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-12 text-center mt-lg-5 mt-4
                        copy-right">
                <p class="pt-4 pb-4 text-gray fs-14">
                    &copy;{{ date('Y') }}
                    <a href="{{ getSettingValue('company_url') }}">
                        {{ html_entity_decode($settings['application_name']) }}</a>.
                    {{ __('web.footer.all_rights_reserved') }}.
                </p>
            </div>
        </div>
    </div>
</footer>
<!-- end footer section -->
