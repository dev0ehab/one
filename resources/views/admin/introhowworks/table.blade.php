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
                <th>{{__('admin.image')}}</th>
                <th>{{__('admin.address')}}</th>
                <th>{{__('admin.control')}}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($introhowworks as $howWork)
                <tr class="delete_howWork">
                    <td class="text-center">
                        <label class="container-checkbox">
                            <input type="checkbox" class="checkSingle" id="{{$howWork->id}}">
                            <span class="checkmark"></span>
                        </label>
                    </td>
                    <td><img src="{{$howWork->image}}" width="50px" height="50px" alt=""></td>
                    <td>{{$howWork->title}}</td>
                    <td class="product-action">
                        <span class="text-primary"><a href="{{route('admin.introhowworks.show' , ['id' => $howWork->id])}}"><i class="feather icon-eye"></i></a></span>
                        <span class="action-edit text-primary"><a href="{{route('admin.introhowworks.edit' , ['id' => $howWork->id])}}"><i class="feather icon-edit"></i></a></span>
                        <span class="delete-row text-danger" data-url="{{url('admin/introhowworks/'.$howWork->id)}}"><i class="feather icon-trash"></i></span>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{-- table content --}}
    {{-- no data found div --}}
    @if ($introhowworks->count() == 0)
        <div class="d-flex flex-column w-100 align-center mt-4">
            <img src="{{asset('/storage/images/no_data.png')}}" width="200px" style="" alt="">
            <span class="mt-2" style="font-family: cairo ;margin-right: 35px">{{__('admin.there_are_no_matches_matching')}}</span>
        </div>
    @endif
    {{-- no data found div --}}

</div>
{{-- pagination  links div --}}
@if ($introhowworks->count() > 0 && $introhowworks instanceof \Illuminate\Pagination\AbstractPaginator )
    <div class="d-flex justify-content-center mt-3">
        {{$introhowworks->links()}}
    </div>
@endif
{{-- pagination  links div --}}