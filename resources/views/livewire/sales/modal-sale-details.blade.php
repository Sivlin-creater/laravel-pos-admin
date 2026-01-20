<div class="space-y-2">
    <div><strong>Customer:</strong> {{ $modalSale?->customer?->name ?? 'N/A' }}</div>
    <div><strong>Payment Method:</strong> {{ $modalSale?->paymentmethod?->name ?? 'N/A' }}</div>
    <div><strong>Items:</strong> {{ $modalSale->salesItems->map(fn($item) => $item->item?->name ?? 'N/A')->join(', ') }}</div>
    <div><strong>Total:</strong> ${{ number_format($modalSale->total ?? 0, 2) }}</div>
    <div><strong>Paid Amount:</strong> ${{ number_format($modalSale->paid_amount ?? 0, 2) }}</div>
    <div><strong>Remaining:</strong> ${{ number_format(($modalSale->total ?? 0) - ($modalSale->paid_amount ?? 0), 2) }}</div>
    <div><strong>Discount:</strong> ${{ number_format($modalSale->discount ?? 0, 2) }}</div>
    <div><strong>Status:</strong> {{ ($modalSale->total ?? 0) <= ($modalSale->paid_amount ?? 0) ? 'Paid' : (($modalSale->paid_amount ?? 0) > 0 ? 'Partial' : 'Pending') }}</div>
    <div><strong>Date:</strong> {{ optional($modalSale->created_at)?->format('d M Y H:i') ?? 'N/A' }}</div>
</div>
