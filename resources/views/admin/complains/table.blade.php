@php
    use Illuminate\Support\Str;
@endphp
<div class="position-relative">
    {{-- table loader --}}
    <div class="table_loader">
        {{ __('admin.loading') }}
    </div>
    {{-- table loader --}}
    {{-- table content --}}
    <table class="table " id="tab">
        <thead>
            <tr>
                <th>#</th>
                <th>{{ __('admin.complain_number') }}</th>
                <th>{{ __('admin.client') }}</th>
                <th>{{ __('admin.title') }}</th>
                <th>{{ __('admin.status') }}</th>
                <th>{{ __('admin.action') }}</th>
                <th>{{ __('admin.created_at') }}</th>
                <th>{{ __('admin.control') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($complains as $complain)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $complain->complain_number }}</td>
                    <td>{{ $complain->complainable->name ?? '-' }}</td>
                    <td>{{ Str::limit($complain->title, 50) }}</td>
                    <td>
                        @if($complain->status == 'new')
                            <span class="badge badge-lg badge-primary" style="font-size: 0.9rem; padding: 0.4rem 0.8rem;">{{ __('admin.new') }}</span>
                        @elseif($complain->status == 'pending')
                            <span class="badge badge-lg badge-warning" style="font-size: 0.9rem; padding: 0.4rem 0.8rem;">{{ __('admin.pending') }}</span>
                        @elseif($complain->status == 'finish')
                            <span class="badge badge-lg badge-success" style="font-size: 0.9rem; padding: 0.4rem 0.8rem;">{{ __('admin.finish') }}</span>
                        @endif
                    </td>
                    <td>
                        @if($complain->status == 'new')
                            <a href="#" class="btn btn-sm btn-warning change_status" 
                               data-url="{{ route('admin.complains.processing', ['id' => $complain->id]) }}"
                               data-status="processing">
                                <i class="feather icon-play"></i> {{ __('admin.complain_status.processing') }}
                            </a>
                        @elseif($complain->status == 'pending')
                            <a href="#" class="btn btn-sm btn-success change_status" 
                               data-url="{{ route('admin.complains.completed', ['id' => $complain->id]) }}"
                               data-status="completed">
                                <i class="feather icon-check"></i> {{ __('admin.complain_status.completed') }}
                            </a>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td>{{ $complain->created_at->format('Y-m-d H:i') }}</td>
                    <td class="product-action">
                        <span class="action-show text-info">
                            <a href="{{ route('admin.complains.show', ['id' => $complain->id]) }}">
                                <i class="feather icon-eye"></i>
                            </a>
                        </span>
                        <span class="delete-row text-danger" data-url="{{ url('admin/complains/' . $complain->id) }}">
                            <i class="feather icon-trash"></i>
                        </span>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{-- table content --}}
    {{-- no data found div --}}
    @if ($complains->count() == 0)
        <div class="d-flex flex-column w-100 align-center mt-4">
            <img src="{{ asset('/storage/images/no_data.png') }}" width="200px" style="" alt="">
            <span class="mt-2"
                style="font-family: cairo ;margin-right: 35px">{{ __('admin.there_are_no_matches_matching') }}</span>
        </div>
    @endif
    {{-- no data found div --}}

</div>
{{-- pagination links div --}}
@if ($complains->count() > 0 && $complains instanceof \Illuminate\Pagination\AbstractPaginator)
    <div class="d-flex justify-content-center mt-3">
        {{ $complains->links() }}
    </div>
@endif
{{-- pagination links div --}}

