<div class="card store">
    <div class="card-body">
        <div class="position-relative">
            {{-- table content --}}
            <table class="table " id="tab">
                <thead>
                    <tr>
                        <th>{{__('admin.date')}}</th>
                        <th>{{__('admin.name_to_contact')}}</th>
                        <th>{{__('admin.phone_to_contact')}}</th>
                        <th>{{__('admin.email_to_contact')}}</th>
                        <th>{{__('admin.control')}}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($contacts as $contact)
                    <tr class="delete_contact">
                        <td>{{ $contact->created_at->format('d/m/Y') }}</td>
                        <td>{{$contact->name}}</td>
                        <td>{{$contact->phone}}</td>
                        <td>{{$contact->email}}</td>
                        <td class="product-action">
                            <span class="action-edit text-primary"><a
                                    href="{{route('admin.contacts.show' , ['id' => $contact->id])}}"><i
                                        class="feather icon-eye"></i></a></span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            {{-- table content --}}
            {{-- no data found div --}}
            @if ($contacts->count() == 0)
            <div class="d-flex flex-column w-100 align-center mt-4">
                <img src="{{asset('admin/app-assets/images/pages/404.png')}}" alt="">
                <span class="mt-2" style="font-family: cairo">{{__('admin.there_are_no_matches_matching')}}</span>
            </div>
            @endif
            {{-- no data found div --}}

        </div>
        {{-- pagination links div --}}
        @if ($contacts->count() > 0 && $contacts instanceof \Illuminate\Pagination\AbstractPaginator )
        <div class="d-flex justify-content-center mt-3">
            {{$contacts->links()}}
        </div>
        @endif
        {{-- pagination links div --}}
    </div>
</div>
