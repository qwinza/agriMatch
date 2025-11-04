<button id="pay-button" class="btn btn-success w-100">Bayar Sekarang</button>

<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ $clientKey }}"></script>
<script>
    document.getElementById('pay-button').addEventListener('click', function () {
        snap.pay('{{ $snapToken }}', {
            onSuccess: function (result) { window.location.href = "{{ route('transactions.finish') }}?status=success"; },
            onPending: function (result) { window.location.href = "{{ route('transactions.finish') }}?status=pending"; },
            onError: function (result) { window.location.href = "{{ route('transactions.finish') }}?status=failed"; },
            onClose: function () { alert('Transaksi dibatalkan.'); }
        });
    });
</script>