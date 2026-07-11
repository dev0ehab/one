<div class="position-relative">
    {{-- table loader --}}
    <div class="table_loader">
        {{__('admin.loading')}}
    </div>
    {{-- table loader --}}
    {{-- table content --}}
    <table class="table " id="tab">
        <thead>
            <tr>
                <th>#</th>
                <th>{{__('admin.name')}}</th>
                <th>{{__('admin.control')}}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($roles as $role)
                <tr class="delete_role">
                    <td>{{$loop->iteration}}</td>
                    <td>{{$role->name}}</td>
                    <td class="product-action">
                        <span class="action-edit text-primary"><a
                                href="{{route('admin.roles.edit', ['id' => $role->id])}}"><i
                                    class="feather icon-edit"></i></a></span>
                        @if(!in_array($role->id, [1]))
                            <span class="delete-row text-danger" data-url="{{url('admin/roles/' . $role->id)}}"><i
                                    class="feather icon-trash"></i></span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{-- table content --}}
    {{-- no data found div --}}
    @if ($roles->count() == 0)
        <div class="d-flex flex-column w-100 align-center mt-4">
            <img src="{{asset('/storage/images/no_data.png')}}" width="200px" style="" alt="">
            <span class="mt-2"
                style="font-family: cairo ;margin-right: 35px">{{__('admin.there_are_no_matches_matching')}}</span>
        </div>
    @endif
    {{-- no data found div --}}

</div>
{{-- pagination links div --}}
@if ($roles->count() > 0 && $roles instanceof \Illuminate\Pagination\AbstractPaginator)
    <div class="d-flex justify-content-center mt-3">
        {{$roles->links()}}
    </div>
@endif
{{-- pagination links div --}}