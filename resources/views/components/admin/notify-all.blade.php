<div class="modal fade text-left" id="notify" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <h5 class="modal-title" id="myModalLabel160">{{__('admin.send_notification')}}</h5>
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{$route}}" method="POST" enctype="multipart/form-data" class="notify-form parsley">
                    @csrf
                    <input type="hidden" name="id" class="notify_id">
                    <input type="hidden" name="type" class="notify" value="notifications">
                    <div class="row">

                        <div class="col-md-12 col-12">
                            <div class="form-group">
                                <label for="first-name-column">{{__('admin.the_message_in_arabic')}}</label>
                                <div class="controls">
                                    <textarea name="body_modal_ar" required minlength="3" maxlength="255" class="form-control" cols="30"
                                        rows="10"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 col-12">
                            <div class="form-group">
                                <label for="first-name-column">{{__('admin.the_message_in_english')}}</label>
                                <div class="controls">
                                    <textarea name="body_modal_en" required minlength="3" maxlength="255" class="form-control" cols="30"
                                        rows="10"></textarea>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary send-notify-button">{{__('admin.send')}}</button>
                        <button type="button" class="btn btn-primary"
                            data-dismiss="modal">{{__('admin.cancel')}}</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

{{-- <div class="modal fade text-left" id="mail" tabindex="-1" role="dialog" aria-labelledby="myModalLabel160"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <h5 class="modal-title" id="myModalLabel160">{{__('admin.send_email')}}</h5>
            <div class="modal-header bg-primary white">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{$route}}" method="POST" enctype="multipart/form-data" class="notify-form parsley">
                    @csrf
                    <input type="hidden" name="id" class="notify_id">
                    <input type="hidden" name="type" class="email" value="modal_email">
                    <div class="col-md-12 col-12">
                        <div class="form-group">
                            <label for="first-name-column">{{__('admin.the_written_text_of_the_email')}}</label>
                            <div class="controls">
                                <textarea name="message" class="form-control" minlength="3" maxlength="255" cols="30" rows="10"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary send-notify-button">{{__('admin.send')}}</button>
                        <button type="button" class="btn btn-primary"
                            data-dismiss="modal">{{__('admin.cancel')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div> --}}