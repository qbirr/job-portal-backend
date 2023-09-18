@extends('front_web.layouts.app')
@section('title')
    {{ __('messages.post.post_details') }}
@endsection
{{--@section('page_css')--}}
{{--    <link href="{{asset('front_web/scss/blog-details.css')}}" rel="stylesheet" type="text/css">--}}
{{--@endsection--}}
@section('content')
    <div class="Blog Detail-page">
        <!-- start hero section -->
        <section class="hero-section position-relative bg-light py-40">
            <div class="container">
                <div class="row align-items-center justify-content-center">
                    <div class="col-lg-6  text-center mb-lg-0 mb-md-5 mb-sm-4 ">
                        <div class="hero-content">
                            <h1 class=" text-secondary mb-3">
                                @lang('web.blog_detail')
                            </h1>
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb justify-content-center mb-0">
                                    <li class="breadcrumb-item">
                                        <a href="{{route('front.home')}}" class="fs-18 text-gray">
                                            @lang('web.home')</a>
                                    </li>
                                    <li class="breadcrumb-item text-primary fs-18" aria-current="page">@lang('web.blog_detail')</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- end hero section -->

        <!-- start-blog-details-section -->
        <section class="blog-detail-section ">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <div class="blog-detail py-60">
                            <h5 class="fs-4 mb-3 text-secondary">
                                {{ html_entity_decode($blog->title) }}</h5>
                            <div class="designer-details d-flex flex-wrap">
                                <div class="me-4">
                                    <img src="{{ isset($blog->user->avatar) ? $blog->user->avatar : asset('front_web/images/job-categories.png')}}"
                                         class="img object-fit-cover rounded">
                                </div>  
                                <p class="fs-16 text-gray me-3">
                                    {{ $blog->user->full_name }}</p>
                                <span class="text-primary me-3"> | </span>
                                <p class="fs-16 text-gray me-3">
                                    {{ \Carbon\Carbon::parse($blog->created_at)->translatedFormat('M jS Y')}}</p>
                                <span class="text-primary me-3"> | </span>
                                <p class="fs-16 text-gray me-3">
                                    {{ isset($comments) ? count($comments) : 0 }} Comment</p>
                            </div>
                            @role('Candidate')
                            <div class="designer-details d-flex flex-wrap">
                                <a href="{{ $url['facebook'] }}" title="@lang('web.web_jobs.facebook')" target="_blank" class="d-flex me-2">
                                    <div class="badge bg-primary py-1 px-2">
                                        <i class="fa-brands fa-facebook fs-18"></i>
                                    </div>
                                </a>
                                <a href="{{ $url['twitter'] }}" title="@lang('web.web_jobs.twitter')" target="_blank" class="d-flex me-2">
                                    <div class="badge bg-primary py-1 px-2">
                                        <i class="fa-brands fa-twitter fs-18"></i>
                                    </div>
                                </a>
                                <a href="{{ $url['gmail'] }}" title="@lang('web.web_jobs.google')" target="_blank" class="d-flex me-2">
                                    <div class="badge bg-primary py-1 px-2">
                                        <i class="fa-brands fa-google fs-18"></i>
                                    </div>
                                </a>
                                <a href="{{ $url['pinterest'] }}" title="@lang('web.web_jobs.pinterest')" target="_blank" class="d-flex me-2">
                                    <div class="badge bg-primary py-1 px-2">
                                        <i class="fa-brands fa-pinterest fs-18"></i>
                                    </div>
                                </a>
                                <a href="{{ $url['linkedin'] }}" title="@lang('web.web_jobs.linkedin')" target="_blank" class="d-flex">
                                    <div class="badge bg-primary py-1 px-2">
                                        <i class="fa-brands fa-linkedin fs-18"></i>
                                    </div>
                                </a>
                            </div>
                            @endrole
                            <div class="blog-img mt-40 mb-40">
                                <img src="{{ !empty($blog->blog_image_url)?$blog->blog_image_url:asset('web/img/blog_default_image.jpg') }}">
                            </div>
                            @php
                                $assignCategories = $blog->postAssignCategories->pluck('name')->toArray();
                            @endphp
                            @if(count($assignCategories) > 0)
                                <div class="designer-details d-flex mb-3">
                                    @forelse($assignCategories as $categoryBadges)
                                        <span class="p-2 me-2 badge bg-{{ getJobOtherColor($loop->index) }}">{{$categoryBadges}}</span>
                                    @empty
                                        <span> {{ __('messages.employer_menu.no_data_available') }} </span>
                                    @endforelse
                                </div>
                            @endif
                            <div class="mb-40 blog-description">
                                {!! !empty($blog->description)? nl2br(($blog->description)):__('messages.common.n/a') !!}
                            </div>
                            <div class="designer-details d-flex justify-content-between">
                                <div class="prev-post">
                                    @if(count($prevPost) >0 )
                                        <div class="card p-3">
                                            @foreach($prevPost as $post)
                                                <a href="{{ route('front.posts.details',$post->id) }}"
                                                   class="text-gray primary-link-hover">
                                                    <h5>
                                                        <small><i class="fa fa-angle-left"></i></small> Previous Post
                                                    </h5>
                                                    <h6 class="mb-0">{{$post->title}}</h6>
                                                </a>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                                <div class="next-post">
                                    @if(count($nextPost) >0 )
                                        <div class="card p-3">
                                            @foreach($nextPost as $post)
                                                <a href="{{ route('front.posts.details',$post->id) }}"
                                                   class="text-gray primary-link-hover">
                                                    <h5>
                                                        Next Post <small><i class="fa fa-angle-right"></i></small>
                                                    </h5>
                                                    <h6 class="mb-0">{{$post->title}}</h6>
                                                </a>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="comments py-60">
                            <h5 class="fs-4 mb-3 text-secondary">
                                @lang('web.web_blog.comments') <span class="comment-count" id="post-comment">({{count($comments)}})</span>
                            </h5>
                            <div class="row comment-box">
                                @foreach($comments as $commentRecord)
                                    <div class="comment-card card py-20 {{$loop->last?'':'mb-40'}}">
                                        <div class="row justify-content-between">
                                            <div class="col-xl-1 col-sm-2 col-3">
                                                <div class="">
                                                    @if(isset($commentRecord->user_id))
                                                        <img class="card-img"
                                                             src="{{$commentRecord->user->avatar }}"
                                                             alt="user-image">
                                                    @else
                                                        <img class="card-img"
                                                             src="{{ asset('front_web/images/job-categories.png')}}"
                                                             alt="user-image">
                                                    @endif
                                            </div>
                                        </div>
                                        <div class="col-xl-6 col-sm-5 col-9 ps-xl-4 ">
                                            <div class="card-body ps-0">
                                                <h5 class="card-title fs-16 text-secondary">
                                                    {{$commentRecord->name}}
                                                    @if($commentRecord->user_id == getLoggedInUserId() && getLoggedInUser())
                                                        <div class="d-inline-flex ms-2">
                                                            <a href="javascript:void(0)" title="{{ __('messages.common.edit') }}"
                                                                   class="edit-comment-btn action-btn" data-id="{{$commentRecord->id}}">
                                                                <div class="badge bg-primary py-2 ms-1" data-text="Edit Comment">
                                                                    <span class="fa fa-pencil"></span>
                                                                </div>
                                                            </a>
                                                           <a href="javascript:void(0)" title="{{ __('messages.common.delete') }}"
                                                                   class="action-btn delete-comment-btn float-right"
                                                                   data-id="{{$commentRecord->id}}">
                                                                <div class="badge bg-primary py-2 ms-1" data-text="Delete Comment">
                                                                    <span class="fa fa-trash"></span>
                                                                </div>
                                                            </a>
                                                        </div>
                                                    @endif
                                                </h5>
                                                <p class="fs-16 text-gray" id="comment-{{$commentRecord->id}}">
                                                    {{ $commentRecord->comment }}</p>
                                            </div>
                                        </div>
                                        <div class="col-sm-5 text-end">
                                            <span class="fs-14 text-gray">
                                                 {{ \Carbon\Carbon::parse($commentRecord->created_at)->translatedFormat('d, M Y g:i a') }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            </div>
                        </div>
                        <div class="leave-comment py-60">
                            <h5 class="fs-4 mb-3 text-secondary mb-4">@lang('messages.post.post_a_comments')</h5>
                            {{ Form::open(['id' => 'commentForm']) }}
                            {{ Form::token() }}
                            {{ Form::hidden('comment-id', null, ['class' => 'comment-id','value' => '']) }}
                                <div class="row clearfix mb-40">
                                    @if(!Auth::check())
                                        <div class="col-md-6 mb-4">
                                            <div class="form-group">
                                                <label for="" class="fs-16 text-secondary mb-2">{{__('web.your_name')}}</label>
                                                <input type="text" name="name" class="form-control fs-14 text-gray br-10 comment-name"
                                                       placeholder="{{__('web.web_blog.your_name')}}">
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-4">
                                            <div class="form-group">
                                                <label for="" class="fs-16 text-secondary mb-2">{{__('web.your_email')}}</label>
                                                <input type="email" name="email" class="form-control fs-14 text-gray br-10 comment-email"
                                                       placeholder="{{__('web.web_blog.your_email')}}">
                                            </div>
                                        </div>
                                    @endif
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="" class="fs-16 text-secondary mb-2">{{__('web.your_comment')}}</label>
                                            <textarea id="comment-field" class="form-control fs-14 text-gray br-10 comment"
                                                      placeholder="{{__('web.web_blog.add_your_comment')}}" rows="3" name="comment"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="row justify-content-center">
                                    <div class="col-sm-6 mb-40 text-center">
                                        <button type="submit" id="submitBtn" class="btn btn-primary"
                                                data-loading-text="<span class='spinner-border spinner-border-sm'></span> {{__('messages.common.process')}}">
                                            @lang('messages.post_comment.post_comment')</button>
                                    </div>
                                </div>
                            {{ Form::close() }}
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- end-blog-details-section -->
        @include('front_web.blogs.templates.templates')
        {{Form::hidden('blogComment',route('blog.create.comment', $blog->id),['id'=>'blogComment'])}}
        {{Form::hidden('defaultBlogImage',asset('front_web/images/job-categories.png'),['id'=>'defaultBlogImage'])}}
    </div>
@endsection
{{--@section('page_scripts')--}}
{{--    <script>--}}
{{--        let blogComment = "{{ route('blog.create.comment', $blog->id) }}";--}}
{{--        let commentUrl = "{{ url('post-comments') }}";--}}
{{--        let editCommentUrl = "{{ '/edit' }}";--}}
{{--        let defaultImage = "{{ asset('front_web/images/job-categories.png') }}";--}}
{{--    </script>--}}
{{--@endsection--}}
