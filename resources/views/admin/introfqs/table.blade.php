<div class="position-relative">
    {{-- table loader  --}}
    <div class="table_loader" >
        {{__('admin.loading')}}
    </div>
    {{-- table loader  --}}
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
                <th>{{ __('admin.question')}}</th>
                <th>{{__('admin.section')}}</th>
                <th>{{ __('admin.control')}}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($introFqs as $fqs)
                <tr class="delete_fqs">
                    <td class="text-center">
                        <label class="container-checkbox">
                            <input type="checkbox" class="checkSingle" id="{{$fqs->id}}">
                            <span class="checkmark"></span>
                        </label>
                    </td>
                    <td>{{$fqs->title}}</td>
                    <td>{{$fqs->category->title}}</td>
                    <td class="product-action">
                        <span class="text-primary"><a href="{{route('admin.introfqs.show' , ['id' => $fqs->id])}}"><i class="feather icon-eye"></i></a></span>
                        <span class="action-edit text-primary"><a href="{{route('admin.introfqs.edit' , ['id' => $fqs->id])}}"><i class="feather icon-edit"></i></a></span>
                        <span class="delete-row text-danger" data-url="{{url('admin/introfqs/'.$fqs->id)}}"><i class="feather icon-trash"></i></span>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{-- table content --}}
    {{-- no data found div --}}
    @if ($introFqs->count() == 0)
        <div class="d-flex flex-column w-100 align-center mt-4">
            <img src="{{asset('/storage/images/no_data.png')}}" width="200px" style="" alt="">
            <span class="mt-2" style="font-family: cairo ;margin-right: 35px">{{__('admin.there_are_no_matches_matching')}}</span>
        </div>
    @endif
    {{-- no data found div --}}

</div>
{{-- pagination  links div --}}
@if ($introFqs->count() > 0 && $introFqs instanceof \Illuminate\Pagination\AbstractPaginator )
    <div class="d-flex justify-content-center mt-3">
        {{$introFqs->links()}}
    </div>
@endif
{{-- pagination  links div --}}
