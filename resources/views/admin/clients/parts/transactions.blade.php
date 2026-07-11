<div class="card">
    <div class="card-header">
        <h4>{{ __('admin.wallet_transactions') }}</h4>
    </div>
    <div class="card-body">
        @if($transactions->count() > 0)
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>{{ __('admin.type') }}</th>
                            <th>{{ __('admin.amount') }}</th>
                            <th>{{ __('admin.date') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transactions as $transaction)
                            <tr>
                                <td>
                                    @if($transaction->operation == 'deposit')
                                        <span class="badge badge-success">{{ __('admin.deposit') }}</span>
                                    @else
                                        <span class="badge badge-danger">{{ __('admin.withdraw') }}</span>
                                    @endif
                                </td>
                                <td>{{ $transaction->amount }} {{ __('site.currency') }}</td>
                                <td>{{ $transaction->created_at->format('Y-m-d H:i') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if($transactions->hasPages())
                <div class="d-flex justify-content-center mt-3">
                    {{ $transactions->links('pagination::bootstrap-4') }}
                </div>
            @endif
        @else
            <div class="text-center">
                <p>{{ __('admin.no_transactions_found') }}</p>
            </div>
        @endif
    </div>
</div>
