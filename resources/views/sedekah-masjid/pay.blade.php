<x-front-layout>
  @php
    $bg = '#13392f';
    $accent = '#E7B14B';
  @endphp

  <div class="min-h-[70vh] text-white flex items-center justify-center px-4" style="background: {{ $bg }};">
    <div class="w-full max-w-lg rounded-[28px] border border-white/14 bg-white/10 p-6 text-center backdrop-blur">
      <h1 class="text-xl font-extrabold">Lanjut Pembayaran</h1>
      <p class="mt-2 text-sm text-white/75">
        Mohon tunggu, pembayaran akan dibuka. Jika tidak muncul, klik tombol di bawah.
      </p>

      <div class="mt-5 rounded-2xl border border-white/10 bg-white/10 p-4 text-left">
        <div class="flex justify-between text-sm text-white/80">
          <span>Order ID</span><span class="font-bold text-white">{{ $trx->order_id }}</span>
        </div>
        <div class="mt-2 flex justify-between text-sm text-white/80">
          <span>Nominal</span><span class="font-extrabold text-white">Rp {{ number_format($trx->jumlah,0,',','.') }}</span>
        </div>
        <div class="mt-2 flex justify-between text-sm text-white/80">
          <span>Status</span><span class="font-bold text-white">{{ strtoupper($trx->status) }}</span>
        </div>
      </div>

      <button id="payBtn"
        class="mt-6 inline-flex w-full items-center justify-center rounded-2xl px-5 py-3 text-sm font-semibold text-[#13392f] hover:brightness-110"
        style="background: {{ $accent }};">
        Bayar Sekarang
      </button>

      <p class="mt-3 text-xs text-white/65">
        Setelah pembayaran selesai, status akan diperbarui otomatis melalui notifikasi Midtrans.
      </p>
    </div>
  </div>

  {{-- Midtrans Snap --}}
  <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
  <script>
    (function(){
      const token = @json($trx->snap_token);
      const btn = document.getElementById('payBtn');

      function openSnap(){
        window.snap.pay(token, {
          onSuccess: function(){ window.location.href = @json(route('sedekah.finish', ['order_id' => $trx->order_id])); },
          onPending: function(){ window.location.href = @json(route('sedekah.finish', ['order_id' => $trx->order_id])); },
          onError:   function(){ window.location.href = @json(route('sedekah.finish', ['order_id' => $trx->order_id])); },
          onClose:   function(){ /* user closed */ }
        });
      }

      btn.addEventListener('click', openSnap);
      setTimeout(openSnap, 700);
    })();
  </script>
</x-front-layout>
