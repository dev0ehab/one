<div class="position-relative" style="overflow: auto">

    {{-- table loader --}}
    <div class="table_loader">
        {{ __('admin.loading') }}
    </div>
    {{-- table loader --}}

    {{-- table content --}}
    <table class="table " id="tab">
        <thead>
            <tr>
                <th>
                    <label class="container-checkbox">
                        <input type="checkbox" value="value1" name="name1" id="checkedAll">
                        <span class="checkmark"></span>
                    </label>
                </th>
                <th>{{ __('admin.image') }}</th>
                <th>{{ __('admin.name') }}</th>
                <th>{{ __('admin.email') }}</th>
                <th>{{ __('admin.phone') }}</th>
                <th>{{ __('admin.status') }}</th>
                <th>{{ __('admin.control') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($admins as $admin)
                <tr class="delete_row">
                    <td class="text-center">
                        @if ($admin->id != 1 && auth()->id() != $admin->id)
                            <label class="container-checkbox">
                                <input type="checkbox" class="checkSingle" id="{{ $admin->id }}">
                                <span class="checkmark"></span>
                            </label>
                        @else
                            *
                        @endif
                    </td>

                    <td>
                        <img src="{{ asset($admin->image) }}" width="50px" height="50px" alt="image">
                    </td>
                    <td>{{ $admin->name }}</td>
                    <td>{{ $admin->email }}</td>
                    <td>{{ $admin->phone }}</td>
                    <td>
                        @if ($admin->id != 1)
                            @if ($admin->is_blocked)
                                <span class="btn btn-sm round btn-outline-danger">
                                    {{ __('admin.prohibited') }} <i class="la la-close font-medium-2"></i>
                                </span>
                                <span class="btn btn-sm round btn-outline-success block_user"
                                    data-id="{{ $admin->id }}">{{ __('admin.unblock') }}</span>
                            @else
                                <span class="btn btn-sm round btn-outline-success">
                                    {{ __('admin.unspoken') }} <i class="la la-check font-medium-2"></i>
                                </span>
                                <span class="btn btn-sm round btn-outline-danger block_user"
                                    data-id="{{ $admin->id }}">{{ __('admin.block') }}</span>
                            @endif
                        @else
                            --
                        @endif
                    </td>
                    <td class="product-action">
                        <span class="text-primary">
                            <a href="{{ route('admin.admins.show', ['id' => $admin->id]) }}">
                                <i class="feather icon-eye"></i>
                            </a>
                        </span>
                        @if ($admin->id != 1 && auth()->id() != $admin->id)
                        <span class="action-edit text-primary">
                            <a href="{{ route('admin.admins.edit', ['id' => $admin->id]) }}">
                                <i class="feather icon-edit"></i>
                            </a>
                        </span>
                            <span class="delete-row text-danger" data-url="{{ url('admin/admins/' . $admin->id) }}">
                                <i class="feather icon-trash"></i>
                            </span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{-- table content --}}
    {{-- no data found div --}}
    @if ($admins->count() == 0)
        <div class="d-flex flex-column w-100 align-center mt-4">
            <img src="{{ asset('/storage/images/no_data.png') }}" width="200px" style="" alt="">
            <span class="mt-2"
                style="font-family: cairo ;margin-right: 35px">{{ __('admin.there_are_no_matches_matching') }}</span>
        </div>
    @endif
    {{-- no data found div --}}

</div>
{{-- pagination links div --}}
@if ($admins->count() > 0 && $admins instanceof \Illuminate\Pagination\AbstractPaginator)
    <div class="d-flex justify-content-center mt-3">
        {{ $admins->links() }}
    </div>
@endif
{{-- pagination links div --}}