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
                <th>
                    <label class="container-checkbox">
                        <input type="checkbox" value="value1" name="name1" id="checkedAll">
                        <span class="checkmark"></span>
                    </label>
                </th>
                <th>{{__('admin.date')}}</th>
                <th>{{__('admin.name_to_contact')}}</th>
                <th>{{__('admin.phone_to_contact')}}</th>
                <th>{{__('admin.email_to_contact')}}</th>
                <th>{{__('admin.type')}}</th>
                <th>{{__('admin.is_replied')}}</th>
                <th>{{__('admin.control')}}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($contacts as $contact)
                <tr class="delete_contact">
                    <td class="text-center">
                        <label class="container-checkbox">
                            <input type="checkbox" class="checkSingle" id="{{$contact->id}}">
                            <span class="checkmark"></span>
                        </label>
                    </td>
                    <td>{{\Carbon\Carbon::parse($contact->created_at)->format('d/m/Y')}}</td>
                    <td>{{$contact->name ?? $contact->contactable?->name ?? '--'}}</td>
                    <td>{{$contact->phone ?? $contact->contactable?->phone ?? '-'}}</td>
                    <td>{{$contact->email ?? '-'}}</td>
                    <td>
                        @if($contact->type == 'contact')
                            <span class="badge badge-primary">{{__('admin.contact')}}</span>
                        @elseif($contact->type == 'complaint')
                            <span class="badge badge-danger">{{__('admin.complaint')}}</span>
                        @elseif($contact->type == 'suggestion')
                            <span class="badge badge-info">{{__('admin.suggestion')}}</span>
                        @endif
                    </td>
                    <td>
                        @if($contact->reply)
                            <span class="badge badge-success">{{__('admin.yes')}}</span>
                        @else
                            <span class="badge badge-warning">{{__('admin.no')}}</span>
                        @endif
                    </td>
                    <td class="product-action">
                        <span class="action-edit text-primary">
                            <a href="{{route('admin.contacts.show', ['id' => $contact->id])}}">
                                <i class="feather icon-eye"></i>
                            </a>
                        </span>
                        <span class="delete-row text-danger" data-url="{{url('admin/contacts/' . $contact->id)}}">
                            <i class="feather icon-trash"></i>
                        </span>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{-- table content --}}
    {{-- no data found div --}}
    @if ($contacts->count() == 0)
        <div class="d-flex flex-column w-100 align-center mt-4">
            <img src="{{asset('/storage/images/no_data.png')}}" width="200px" style="" alt="">
            <span class="mt-2"
                style="font-family: cairo ;margin-right: 35px">{{__('admin.there_are_no_matches_matching')}}</span>
        </div>
    @endif
    {{-- no data found div --}}

</div>
{{-- pagination links div --}}
@if ($contacts->count() > 0 && $contacts instanceof \Illuminate\Pagination\AbstractPaginator)
    <div class="d-flex justify-content-center mt-3">
        {{$contacts->links()}}
    </div>
@endif
