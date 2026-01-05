<x-front-layout>
  @php
    $bg = '#13392f';
    $accent = '#E7B14B';
    $finishUrl = route('sedekah.finish', ['order_id' => $trx->order_id]);
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

      {{-- alert kecil kalau token kosong --}}
      @if(empty($trx->snap_token))
        <div class="mt-5 rounded-2xl border border-red-500/30 bg-red-500/10 p-4 text-left">
          <div class="text-sm font-semibold text-red-200">Snap token belum tersedia</div>
          <div class="mt-1 text-xs text-red-200/80">
            Coba ulangi dari halaman Sedekah Masjid atau refresh halaman ini.
          </div>
        </div>
      @endif

      <button id="payBtn"
        class="mt-6 inline-flex w-full items-center justify-center rounded-2xl px-5 py-3 text-sm font-semibold text-[#13392f] hover:brightness-110 disabled:opacity-50 disabled:cursor-not-allowed"
        style="background: {{ $accent }};"
        @disabled(empty($trx->snap_token))
      >
        Bayar Sekarang
      </button>

      <p class="mt-3 text-xs text-white/65">
        Setelah pembayaran selesai, status akan diperbarui otomatis melalui notifikasi Midtrans.
      </p>
    </div>
  </div>

  {{-- Midtrans Snap (SANDBOX) --}}
  <script
    src="https://app.sandbox.midtrans.com/snap/snap.js"
    data-client-key="{{ config('midtrans.client_key') }}">
  </script>

  <script>
    (function () {
      const token = @json($trx->snap_token);
      const finishUrl = @json($finishUrl);
      const btn = document.getElementById('payBtn');

      function notify(msg) {
        // simpel: pakai alert dulu (kalau mau aku bikin toast Tailwind juga bisa)
        alert(msg);
      }

      function openSnap() {
        if (!token) {
          notify('Snap token belum tersedia. Silakan refresh atau ulangi transaksi.');
          return;
        }
        if (!window.snap || typeof window.snap.pay !== 'function') {
          notify('Snap Midtrans belum siap. Coba beberapa detik lagi atau refresh halaman.');
          return;
        }

        window.snap.pay(token, {
          onSuccess: function () {
            window.location.href = finishUrl;
          },
          onPending: function () {
            window.location.href = finishUrl;
          },
          onError: function () {
            // jangan langsung lempar ke finish tanpa info
            notify('Pembayaran gagal / dibatalkan. Silakan coba lagi.');
            // optional: tetap ke finish biar user lihat status pending/failed
            window.location.href = finishUrl;
          },
          onClose: function () {
            // user menutup popup: biarkan di halaman ini
          }
        });
      }

      if (btn) btn.addEventListener('click', openSnap);

      // auto open tapi aman: tunggu snap.js ready (polling max 3 detik)
      let tries = 0;
      const iv = setInterval(() => {
        tries++;
        if (window.snap && typeof window.snap.pay === 'function') {
          clearInterval(iv);
          setTimeout(() => openSnap(), 300);
        }
        if (tries >= 30) clearInterval(iv); // 30 * 100ms = 3s
      }, 100);
    })();
  </script>
</x-front-layout>
