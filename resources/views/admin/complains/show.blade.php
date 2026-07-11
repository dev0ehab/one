@extends('admin.layout.master')

@section('css')
@endsection

@section('content')
    <section id="multiple-column-form">
        <div class="row match-height">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">{{__('admin.show')}}</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label>{{__('admin.complain_number')}}</label>
                                        <div class="controls">
                                            <p class="form-control-static">{{ $complain->complain_number }}</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label>{{__('admin.client')}}</label>
                                        <div class="controls">
                                            <p class="form-control-static">{{ $complain->complainable->name ?? '-' }}</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label>{{__('admin.status')}}</label>
                                        <div class="controls">
                                            <select class="form-control status-select" data-id="{{ $complain->id }}" data-current-status="{{ $complain->status }}" style="min-width: 120px;">
                                                @if($complain->status == 'new')
                                                    <option value="new" selected>{{ __('admin.new') }}</option>
                                                    <option value="pending">{{ __('admin.pending') }}</option>
                                                @elseif($complain->status == 'pending')
                                                    <option value="pending" selected>{{ __('admin.pending') }}</option>
                                                    <option value="finish">{{ __('admin.finish') }}</option>
                                                @elseif($complain->status == 'finish')
                                                    <option value="finish" selected>{{ __('admin.finish') }}</option>
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label>{{__('admin.created_at')}}</label>
                                        <div class="controls">
                                            <p class="form-control-static">{{ $complain->created_at->format('Y-m-d H:i') }}</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-group">
                                        <label>{{__('admin.title')}}</label>
                                        <div class="controls">
                                            <p class="form-control-static">{{ $complain->title }}</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-group">
                                        <label>{{__('admin.description')}}</label>
                                        <div class="controls">
                                            <p class="form-control-static">{{ $complain->description ?? '-' }}</p>
                                        </div>
                                    </div>
                                </div>

                                @if($complain->images->count() > 0)
                                <div class="col-12">
                                    <div class="form-group">
                                        <label>{{__('admin.images')}}</label>
                                        <div class="controls">
                                            <div class="row">
                                                @foreach($complain->images as $image)
                                                    <div class="col-md-3 mb-2">
                                                        <img src="{{ $image->image }}" alt="" class="img-fluid" style="max-height: 200px;">
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif

                                @if($complain->reply)
                                <div class="col-12">
                                    <div class="form-group">
                                        <label>{{__('admin.reply')}}</label>
                                        <div class="controls">
                                            <p class="form-control-static">{{ $complain->reply }}</p>
                                        </div>
                                    </div>
                                </div>
                                @endif

                                <div class="col-12 d-flex justify-content-center mt-3">
                                    <span class="delete-row btn btn-danger mr-1 mb-1" data-url="{{ url('admin/complains/' . $complain->id) }}" style="cursor: pointer;">
                                        {{__('admin.delete')}}
                                    </span>
                                    <a href="{{ url()->previous() }}" class="btn btn-outline-warning mr-1 mb-1">{{__('admin.back')}}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('js')
    <script src="{{asset('admin/app-assets/vendors/js/extensions/sweetalert2.all.min.js')}}"></script>
    <script src="{{asset('admin/app-assets/js/scripts/extensions/sweet-alerts.js')}}"></script>

    <script>
        // Make all inputs and textareas readonly, but allow status select
        $(document).ready(function() {
            $('input').not('.status-select').attr('readonly', true).attr('disabled', true);
            $('textarea').attr('readonly', true).attr('disabled', true);
            // Don't disable status select - it should be enabled for status changes
        });

        // Handle status change
        $(document).on('change', '.status-select', function() {
            var select = $(this);
            var complainId = select.data('id');
            var newStatus = select.val();
            
            // Get the previously selected option (before change)
            var oldStatus = select.data('current-status') || select.data('old-status') || select.find('option[selected]').val();
            
            // Store current value as old for next change
            select.data('old-status', newStatus);
            
            // If status is finish, ask for reply
            if (newStatus === 'finish') {
                Swal.fire({
                    title: '{{ __('admin.reply') }}',
                    input: 'textarea',
                    inputLabel: '{{ __('admin.reply_is_required_when_status_is_finish') }} ({{ __('admin.min') }}: 2, {{ __('admin.max') }}: 500)',
                    inputPlaceholder: '{{ str_replace(':input', __('admin.reply'), __('admin.please_enter')) }}',
                    inputAttributes: {
                        'aria-label': '{{ __('admin.reply') }}',
                        'maxlength': '500'
                    },
                    showCancelButton: true,
                    confirmButtonText: '{{ __('admin.confirm') }}',
                    cancelButtonText: '{{ __('admin.cancel') }}',
                    inputValidator: (value) => {
                        if (!value || value.trim() === '') {
                            return '{{ __('admin.reply_is_required_when_status_is_finish') }}';
                        }
                        if (value.trim().length < 2) {
                            return '{{ __('admin.reply_min_2_characters') }}';
                        }
                        if (value.length > 500) {
                            return '{{ __('admin.reply_max_500_characters') }}';
                        }
                    },
                    preConfirm: (reply) => {
                        return new Promise((resolve, reject) => {
                            if (!reply || reply.trim() === '') {
                                Swal.showValidationMessage('{{ __('admin.reply_is_required_when_status_is_finish') }}');
                                reject(new Error('{{ __('admin.reply_is_required_when_status_is_finish') }}'));
                                return;
                            }
                            
                            if (reply.trim().length < 2) {
                                Swal.showValidationMessage('{{ __('admin.reply_min_2_characters') }}');
                                reject(new Error('{{ __('admin.reply_min_2_characters') }}'));
                                return;
                            }
                            
                            if (reply.length > 500) {
                                Swal.showValidationMessage('{{ __('admin.reply_max_500_characters') }}');
                                reject(new Error('{{ __('admin.reply_max_500_characters') }}'));
                                return;
                            }
                            
                            var data = {
                                status: newStatus,
                                reply: reply,
                                _token: $('meta[name="csrf-token"]').attr('content')
                            };

                            $.ajax({
                                url: '{{ url("admin/complains") }}/' + complainId + '/update-status',
                                type: 'POST',
                                data: data,
                                dataType: 'json',
                                success: function(response) {
                                    if (response.status === 'success') {
                                        resolve(reply);
                                    } else {
                                        var errorMessage = response.message || '{{ __('admin.error') }}';
                                        Swal.showValidationMessage(errorMessage);
                                        reject(new Error(errorMessage));
                                    }
                                },
                                error: function(xhr) {
                                    var errorMessage = '{{ __('admin.error') }}';
                                    if (xhr.responseJSON) {
                                        if (xhr.responseJSON.message) {
                                            errorMessage = xhr.responseJSON.message;
                                        } else if (xhr.responseJSON.errors && xhr.responseJSON.errors.reply) {
                                            errorMessage = xhr.responseJSON.errors.reply[0];
                                        } else if (xhr.responseJSON.errors && xhr.responseJSON.errors.status) {
                                            errorMessage = xhr.responseJSON.errors.status[0];
                                        }
                                    }
                                    
                                    Swal.showValidationMessage(errorMessage);
                                    reject(new Error(errorMessage));
                                }
                            });
                        });
                    }
                }).then((result) => {
                    if (result.isConfirmed && result.value) {
                        Swal.fire({
                            position: 'top-start',
                            type: 'success',
                            title: '{{ __('admin.update_successfully') }}',
                            showConfirmButton: false,
                            timer: 1500,
                            confirmButtonClass: 'btn btn-primary',
                            buttonsStyling: false,
                        });
                        
                        // Update the select options based on new status
                        updateStatusSelectOptions(select, newStatus);
                        
                        // Reload page to show updated data
                        setTimeout(function() {
                            location.reload();
                        }, 1500);
                    } else {
                        // Revert to old status
                        select.val(oldStatus);
                        select.data('old-status', oldStatus);
                    }
                });
            } else {
                // Update status directly
                updateStatus(complainId, newStatus, null, select, oldStatus);
            }
        });

        // Update select options based on current status
        function updateStatusSelectOptions(selectElement, currentStatus) {
            selectElement.empty();
            
            var pendingLabel = '{{ __('admin.pending') }}';
            var finishLabel = '{{ __('admin.finish') }}';
            
            if (currentStatus === 'pending') {
                selectElement.append($('<option>', { value: 'pending', text: pendingLabel, selected: true }));
                selectElement.append($('<option>', { value: 'finish', text: finishLabel }));
            } else if (currentStatus === 'finish') {
                selectElement.append($('<option>', { value: 'finish', text: finishLabel, selected: true }));
            } else {
                // If status is 'new', show new (selected) and pending option
                selectElement.append($('<option>', { value: 'new', text: '{{ __('admin.new') }}', selected: true }));
                selectElement.append($('<option>', { value: 'pending', text: pendingLabel }));
            }
            
            selectElement.data('current-status', currentStatus);
            selectElement.data('old-status', currentStatus);
        }

        function updateStatus(complainId, status, reply, selectElement, oldStatus) {
            return new Promise((resolve, reject) => {
                var data = {
                    status: status,
                    _token: $('meta[name="csrf-token"]').attr('content')
                };
                
                if (reply) {
                    data.reply = reply;
                }

                $.ajax({
                    url: '{{ url("admin/complains") }}/' + complainId + '/update-status',
                    type: 'POST',
                    data: data,
                    dataType: 'json',
                    success: function(response) {
                        Swal.fire({
                            position: 'top-start',
                            type: 'success',
                            title: response.message || '{{ __('admin.update_successfully') }}',
                            showConfirmButton: false,
                            timer: 1500,
                            confirmButtonClass: 'btn btn-primary',
                            buttonsStyling: false,
                        });
                        
                        // Update the select options based on new status
                        updateStatusSelectOptions(selectElement, status);
                        
                        // Reload page to show updated data
                        setTimeout(function() {
                            location.reload();
                        }, 1500);
                        
                        resolve(response);
                    },
                    error: function(xhr) {
                        var errorMessage = xhr.responseJSON?.message || (xhr.responseJSON?.errors?.reply ? xhr.responseJSON.errors.reply[0] : '') || '{{ __('admin.error') }}';
                        
                        Swal.fire({
                            icon: 'error',
                            title: '{{ __('admin.error') }}',
                            text: errorMessage,
                            confirmButtonClass: 'btn btn-danger',
                        });
                        
                        // Revert to old status
                        if (oldStatus) {
                            selectElement.val(oldStatus);
                            selectElement.data('old-status', oldStatus);
                        }
                        
                        reject(xhr);
                    }
                });
            });
        }

        $(document).on('click', '.delete-row', function (e) {
            e.preventDefault();
            var deleteUrl = $(this).data('url');
            
            Swal.fire({
                title: "{{__('admin.are_you_want_to_continue')}}",
                text: "{{__('admin.are_you_want_to_complete_the_delete_process')}}",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: '{{__('admin.confirm')}}',
                confirmButtonClass: 'btn btn-primary',
                cancelButtonText: '{{__('admin.cancel')}}',
                cancelButtonClass: 'btn btn-danger ml-1',
                buttonsStyling: false,
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        type: "DELETE",
                        url: deleteUrl,
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content')
                        },
                        dataType: "json",
                        success: function (response) {
                            Swal.fire({
                                position: 'top-start',
                                type: 'success',
                                title: '{{__('admin.the_selected_has_been_successfully_deleted')}}',
                                showConfirmButton: false,
                                timer: 1500,
                                confirmButtonClass: 'btn btn-primary',
                                buttonsStyling: false,
                            }).then(() => {
                                window.location.href = '{{ route('admin.complains.index') }}';
                            });
                        },
                        error: function (xhr) {
                            Swal.fire({
                                position: 'top-start',
                                type: 'error',
                                title: xhr.responseJSON?.message || '{{__('admin.error')}}',
                                showConfirmButton: false,
                                timer: 1500,
                                confirmButtonClass: 'btn btn-primary',
                                buttonsStyling: false,
                            });
                        }
                    });
                }
            });
        });
    </script>
@endsection

