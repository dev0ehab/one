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
            @foreach($appIntros as $appIntro)
                <tr class="delete_intro">
                    <td class="text-center">
                        <label class="container-checkbox">
                            <input type="checkbox" class="checkSingle" id="{{$appIntro->id}}">
                            <span class="checkmark"></span>
                        </label>
                    </td>
                    <td><img src="{{$appIntro->image}}" width="50px" height="50px" alt=""></td>
                    <td>{{$appIntro->title}}</td>
                    <td class="product-action">
                        <span class="text-primary"><a href="{{route('admin.app_intros.show' , ['id' => $appIntro->id])}}"><i class="feather icon-eye"></i></a></span>
                        <span class="action-edit text-primary"><a href="{{route('admin.app_intros.edit' , ['id' => $appIntro->id])}}"><i class="feather icon-edit"></i></a></span>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{-- table content --}}
    {{-- no data found div --}}
    @if ($appIntros->count() == 0)
    <div class="d-flex flex-column w-100 align-center mt-4">
        <img src="{{asset('/storage/images/no_data.png')}}" width="200px" style="" alt="">
        <span class="mt-2" style="font-family: cairo ;margin-right: 35px">{{__('admin.there_are_no_matches_matching')}}</span>
    </div>
    @endif
    {{-- no data found div --}}

</div>
{{-- pagination  links div --}}
@if ($appIntros->count() > 0 && $appIntros instanceof \Illuminate\Pagination\AbstractPaginator )
    <div class="d-flex justify-content-center mt-3">
        {{$appIntros->links()}}
    </div>
@endif
{{-- pagination  links div --}}