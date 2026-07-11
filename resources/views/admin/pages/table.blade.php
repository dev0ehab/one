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
                <th>{{__('admin.image')}}</th>
                <th>{{__('admin.title')}}</th>
                <th>{{__('admin.content')}}</th>
                <th>{{__('admin.control')}}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($pages as $page)
            <tr class="delete_row">
                <td><img width="50" height="50" src="{{ $page->image }}" alt=""></td>
                <td>{{ $page->title }}</td>
                <td>{!! Str::limit($page->content, 100) !!}</td>

                <td class="product-action">
                    <span class="text-primary"><a href="{{ route('admin.pages.show', ['id' => $page->id]) }}"><i
                                class="feather icon-eye"></i></a></span>
                    <span class="action-edit text-primary"><a
                            href="{{ route('admin.pages.edit', ['id' => $page->id]) }}"><i
                                class="feather icon-edit"></i></a></span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    {{-- table content --}}
    {{-- no data found div --}}
    @if ($pages->count() == 0)
    <div class="d-flex flex-column w-100 align-center mt-4">
        <img src="{{asset('admin/app-assets/images/pages/404.png')}}" alt="">
        <span class="mt-2" style="font-family: cairo">{{__('admin.there_are_no_matches_matching')}}</span>
    </div>
    @endif
    {{-- no data found div --}}

</div>
{{-- pagination links div --}}
@if ($pages->count() > 0 && $pages instanceof \Illuminate\Pagination\AbstractPaginator )
<div class="d-flex justify-content-center mt-3">
    {{$pages->links()}}
</div>
@endif
{{-- pagination links div --}}
