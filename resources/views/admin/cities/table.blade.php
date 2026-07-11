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
                <th>{{__('admin.image')}}</th>
                <th>{{__('admin.city_name')}} - {{__('site.name_en')}}</th>
                <th>{{__('admin.city_name')}} - {{__('site.name_ar')}}</th>
                <th>{{__('admin.country')}}</th>
                <th>{{__('admin.related_clients')}}</th>
                <th>{{__('admin.control')}}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($cities as $city)
                <tr class="delete_city">
                    <td>{{$loop->iteration}}</td>
                    <td><img width="50" height="50" src="{{ $city->image }}" alt="{{ $city->getTranslations('name')['en'] ?? $city->name }}"></td>
                    <td>{{ $city->getTranslations('name')['en'] ?? '-' }}</td>
                    <td>{{ $city->getTranslations('name')['ar'] ?? '-' }}</td>
                    <td>
                        <span class="badge badge-primary">{{ $city->country->getTranslations('name')[app()->getLocale()] ?? $city->country->name }}</span>
                    </td>
                    <td>
                        <span class="badge badge-info">{{ $city->clients_count ?? 0 }} {{__('admin.clients')}}</span>
                    </td>
                    <td class="product-action">
                        <span class="action-show text-info">
                            <a href="{{route('admin.cities.show', ['id' => $city->id])}}">
                                <i class="feather icon-eye"></i>
                            </a>
                        </span>
                        <span class="action-edit text-primary">
                            <a href="{{route('admin.cities.edit', ['id' => $city->id])}}">
                                <i class="feather icon-edit"></i>
                            </a>
                        </span>
                        @if($city->clients_count == 0)
                            <span class="delete-row text-danger" data-url="{{url('admin/cities/' . $city->id)}}">
                                <i class="feather icon-trash"></i>
                            </span>
                        @else
                            <span class="text-muted" title="{{__('admin.cannot_delete_city_in_use')}}">
                                <i class="feather icon-lock"></i>
                            </span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{-- table content --}}
    {{-- no data found div --}}
    @if ($cities->count() == 0)
        <div class="d-flex flex-column w-100 align-center mt-4">
            <img src="{{asset('/storage/images/no_data.png')}}" width="200px" style="" alt="">
            <span class="mt-2" style="font-family: cairo ;margin-right: 35px">{{__('admin.there_are_no_matches_matching')}}</span>
        </div>
    @endif
    {{-- no data found div --}}

</div>
{{-- pagination links div --}}
@if ($cities->count() > 0 && $cities instanceof \Illuminate\Pagination\AbstractPaginator)
    <div class="d-flex justify-content-center mt-3">
        {{$cities->links()}}
    </div>
@endif
{{-- pagination links div --}}
