<script src="https://app.sandbox.midtrans.com/snap/snap.js"
    data-client-key="{{ config('midtrans.clientKey') }}"></script>
<button id="pay-button">Bayar Sekarang</button>
<script>
    const payButton = document.getElementById('pay-button');
    payButton.addEventListener('click', function () {
        snap.pay('{{ $snapToken }}', {
            onSuccess: function (result) { window.location.href = "{{ route('transactions.finish', ['status' => 'success']) }}"; },
            onPending: function (result) { window.location.href = "{{ route('transactions.finish', ['status' => 'pending']) }}"; },
            onError: function (result) { window.location.href = "{{ route('transactions.finish', ['status' => 'error']) }}"; }
        });
    });
</script>