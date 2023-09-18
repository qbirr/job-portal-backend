<div class="row justify-content-between">
    @forelse($jobs as $job)
        <div class="col-12 px-xl-3 mb-20 ">
            <div class="card py-30  border-left-color">
                <div class="row position-relative">
                    <div class="col-xl-1 col-md-2 col-3 mb-md-0 mb-3">
                        <img src="{{$job->company->company_url}}" class="card-img" alt="">
                    </div>
                    <div class="col-xl-10 col-md-9 col-sm-8 col-12">
                        <div class="card-body p-0 ps-xl-3">
                            <a href="{{route('front.job.details',$job['job_id']) }}"
                               class="text-secondary primary-link-hover">
                                <h5 class="card-title fs-18 mb-0 d-inline-block">
                                    {{ html_entity_decode(Str::limit($job['job_title'], 50)) }}

                                </h5>
                            </a>
                            @if(isset($job->jobShift->shift))
                                <span class="text text-primary fs-12 mb-0 me-3">
                                {{$job->jobShift->shift}}
                                </span>
                            @endif

                            <div class="col-xl-12">
                                <div class="card-desc d-flex flex-wrap mt-2 ">

                                    <div class="desc d-flex me-4">
                                        <i class="fa-solid fa-location-dot text-gray me-3 fs-18"></i>
                                        <p class="fs-14 text-gray mb-2">
                                            {{ (!empty($job->full_location)) ? $job->full_location : 'Location Info. not available.'}}</p>
                                    </div>
                                    <div class="desc d-flex">
                                        <span class="text-gray">
                                            {{$job->currency->currency_icon}}&nbsp</span>
                                        <p class="fs-14 text-gray mb-2">
                                            {{ $job->salary_from}} - {{$job->salary_to}}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @if($job->activeFeatured)
                        <div class="bookmark text-end position-absolute">
                            <i class="text-primary fa-solid fa-bookmark"></i>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @empty
        <div class="col-md-12 text-center text-gray">
            @lang('web.job_menu.no_results_found')
        </div>
    @endforelse
    @if($jobs->count() > 0)
        {{$jobs->links() }}
    @endif
</div>
