<script id="blogTemplate" type="text/x-jsrender">
                
                <div class="comment-card card py-20 mb-40">
                                        <div class="row justify-content-between">
                                            <div class="col-xl-1 col-sm-2 col-3">
                                                <div class="">
                                                        <img class="card-img" src="{{:image}}" alt="user-image">
                                                </div>
                                            </div>
                                            <div class="col-xl-6 col-sm-5 col-9 ps-xl-4 ">
                                                <div class="card-body ps-0">
                                                    <h5 class="card-title fs-16 text-secondary">
                                                       {{:commentName}}
                                                            <div class="d-inline-flex ms-2">
                                                                <a href="javascript:void(0)" title="{{ __('messages.common.edit') }}"
                                                                       class="edit-comment-btn action-btn" data-id="{{:id}}">
                                                                    <div class="badge bg-primary py-2 ms-1" data-text="Edit Comment">
                                                                        <span class="fa fa-pencil"></span>
                                                                    </div>
                                                                </a>
                                                               <a href="javascript:void(0)" title="{{ __('messages.common.delete') }}"
                                                                       class="action-btn delete-comment-btn float-right"
                                                                       data-id="{{:id}}">
                                                                    <div class="badge bg-primary py-2 ms-1" data-text="Delete Comment">
                                                                        <span class="fa fa-trash"></span>
                                                                    </div>
                                                                </a>
                                                            </div>
                                                    </h5>
                                                    <p class="fs-16 text-gray" id="comment-{{:id}}">
                                                        {{:comment}}</p>
                                                </div>
                                            </div>
                                            <div class="col-sm-5 text-end">
                                                <span class="fs-14 text-gray">{{:commentCreated}}</span>
                                            </div>
                                        </div>
                                    </div>         


</script>
