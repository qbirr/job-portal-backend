<div class="col-lg-4 col-md-6 px-xl-3 mb-40">
    <div class="card py-30">
        @if($company->activeFeatured)
            <span><i class="fas fa-bookmark bookmark-class"></i></span>
        @endif
        <div class="row align-items-center">
            <div class="col-2">
                <img src="{{ $company->company_url }}" class="card-img" alt="">
            </div>
            <div class="col-10 px-3">
                <div class="card-body p-0">
                    <a href="{{ route('front.company.details', $company->unique_id) }}"
                       class="text-secondary primary-link-hover">
                        <h5 class="card-title   fs-20 mb-0">
                            {!! $company->user->first_name !!}</h5>
                    </a>
                    <div class="d-flex">
                        {{--                    @if(!empty($company->industry->name))--}}
                        {{--                        <div class="desc d-flex mb-2">--}}
                        {{--                            <i class="fa-solid fa-briefcase text-gray me-3 fs-18"></i>--}}
                        {{--                            <p class="fs-14 text-gray mb-0">{{$company->industry->name}}</p>--}}
                        {{--                        </div>--}}
                        {{--                    @endif--}}
                        @if(!empty($company->location) || !empty($company->location2))
                            <div class="desc location-text d-flex">
                                <i class="fa-solid fa-location-dot  me-1 mt-1 fs-18"></i>
                                <span class="">
                                    {{ (isset($company->location)) ? html_entity_decode(Str::limit($company->location,10,'...')) : __('messages.common.n/a') }}{{ (isset($company->location2)) ? ','.html_entity_decode(Str::limit($company->location2,10,'...')) : '' }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @php
            $open_jobs = $company->jobs->where('status', App\Models\Job::STATUS_OPEN)->count()
        @endphp
            @if($open_jobs <= 0)
                <div class="card-desc mt-3">
                    <div class="desc d-flex mt-2">
                        <p class="jobs-position bg-gray fs-14 mb-0 me-3 text-secondary">
                            {{ __('web.no_positions') }} ->
                        </p>
                    </div>
                </div>
            @else
                <div class="card-desc mt-3">
                    <div class="desc  d-flex mt-2">
                        <a href="{{ route('front.company.details', $company->unique_id) }}"
                           class="jobs-position  fs-14 mb-0 me-3">
                            {{__('web.open_positions')  }} ->
                        </a>
                    </div>
                </div>
            @endif
           
    </div>
</div>


