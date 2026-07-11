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
                <th>{{__('admin.*')}}</th>
                {{--  <th>{{__('admin.link')}}</th>  --}}
                <th>{{__('admin.ip')}}</th>
                <th>{{__('admin.control')}}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reports as $report)
                <tr class="delete_report">
                    <td class="text-center">
                        <label class="container-checkbox">
                            <input type="checkbox" class="checkSingle" id="{{$report->id}}">
                            <span class="checkmark"></span>
                        </label>
                    </td>
                    <td>{{$report->subject}}</td>
                    {{-- <td><a href="{{$report->url}}"> {{__('admin.press_here')}} </a></td> --}}
                    <td>{{$report->ip}}</td>
                    <td class="product-action">
                        <span class="action-edit text-primary"><a href="{{route('admin.reports.show' , ['id' => $report->id])}}"><i class="feather icon-eye"></i></a></span>
                        <span class="delete-row text-danger" data-url="{{url('admin/reports/'.$report->id)}}"><i class="feather icon-trash"></i></span>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{-- table content --}}
    {{-- no data found div --}}
    @if ($reports->count() == 0)
    <div class="d-flex flex-column w-100 align-center mt-4">
        <img src="{{asset('/storage/images/no_data.png')}}" width="200px" style="" alt="">
        <span class="mt-2" style="font-family: cairo ;margin-right: 35px">{{__('admin.there_are_no_matches_matching')}}</span>
    </div>
    @endif
    {{-- no data found div --}}

</div>
{{-- pagination  links div --}}
@if ($reports->count() > 0 && $reports instanceof \Illuminate\Pagination\AbstractPaginator )
    <div class="d-flex justify-content-center mt-3">
        {{$reports->links()}}
    </div>
@endif
{{-- pagination  links div --}}