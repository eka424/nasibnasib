<x-front-layout>
    @php
        // ====== Data utama dari controller ======
        // $donasis: collection campaign (judul, deskripsi, target_dana, dana_terkumpul, transaksi_donasis_count, tanggal_selesai)
        // $recentDonations: collection transaksi terbaru (user->name, jumlah, created_at, anonymous)
        $bgImage = 'https://images.unsplash.com/photo-1524231757912-21f4fe3a7200?auto=format&fit=crop&w=2200&q=80';
        $posterFallback = 'https://images.unsplash.com/photo-1541417904950-b855846fe074?auto=format&fit=crop&w=1800&q=80';

        $totalRaised = $donasis->sum('dana_terkumpul');
        $totalTarget = $donasis->sum('target_dana');
        $activeCampaigns = $donasis->count();
        $totalDonors = $donasis->sum(fn ($d) => (int) ($d->transaksi_donasis_count ?? 0));

        $overallProgress = $totalTarget > 0 ? min(100, max(0, ($totalRaised / $totalTarget) * 100)) : 0;

        $recentList = collect($recentDonations ?? [])->map(function ($item) {
            return [
                'name' => $item->anonymous ? 'Anonymous' : ($item->user->name ?? 'Anonim'),
                'amount' => (int) ($item->jumlah ?? 0),
                'time' => optional($item->created_at)->diffForHumans() ?? 'Baru saja',
                'anonymous' => (bool) ($item->anonymous ?? false),
            ];
        });

        if ($recentList->isEmpty()) {
            $recentList = collect([
                ['name' => 'Ahmad Rizki', 'amount' => 500000, 'time' => '2 jam lalu', 'anonymous' => false],
                ['name' => 'Anonymous', 'amount' => 750000, 'time' => '4 jam lalu', 'anonymous' => true],
                ['name' => 'Siti Nurhaliza', 'amount' => 250000, 'time' => '6 jam lalu', 'anonymous' => false],
                ['name' => 'Anonymous', 'amount' => 1000000, 'time' => '1 hari lalu', 'anonymous' => true],
            ]);
        }

        $presetAmounts = [100000, 250000, 500000, 1000000, 2500000, 5000000];
        $selectedAmount = max(0, (int) old('jumlah', $presetAmounts[1] ?? 250000));
        $formattedSelectedAmount = number_format($selectedAmount, 0, ',', '.');

        $hasTransaksiRoute = \Illuminate\Support\Facades\Route::has('donasi.transaksi');
        $donasiActionTemplate = $hasTransaksiRoute
            ? route('donasi.transaksi', ['donasi' => '__ID__'])
            : url()->current();
        $defaultDonasiId = optional($donasis->first())->id;

        $newsletterHasRoute = \Illuminate\Support\Facades\Route::has('newsletter.store');
        $newsletterAction = $newsletterHasRoute ? route('newsletter.store') : url()->current();
        $newsletterMethod = $newsletterHasRoute ? 'POST' : 'GET';

        // helper hari tersisa
        $daysLeft = function ($endDate) {
            if (! $endDate) {
                return null;
            }
            $diff = now()->diffInDays(\Carbon\Carbon::parse($endDate)->endOfDay(), false);
            return max(0, $diff);
        };
    @endphp

    <style>
        :root{
          --bg0:#050914;
          --bg1:#070f1f;
          --glass: rgba(255,255,255,.06);
          --glass2: rgba(255,255,255,.05);
          --border: rgba(255,255,255,.12);
          --text: rgba(255,255,255,.92);
          --muted: rgba(255,255,255,.70);
          --muted2: rgba(255,255,255,.56);
          --primary:#10b981;
          --gold:#d4af37;
          --shadow: rgba(16,185,129,.18);
          --shadow2: rgba(0,0,0,.45);
        }
        .page{min-height:100vh;padding-bottom:84px;}
        .container{width:100%;max-width:1100px;margin:0 auto;padding:0 16px;}
        .glass{
          background: var(--glass);
          border: 1px solid var(--border);
          backdrop-filter: blur(18px);
          -webkit-backdrop-filter: blur(18px);
          box-shadow: 0 18px 60px var(--shadow2), 0 10px 30px var(--shadow);
        }
        body{
          background: radial-gradient(1200px 600px at 20% 0%, rgba(16,185,129,.18), transparent 55%),
                      radial-gradient(900px 500px at 85% 15%, rgba(212,175,55,.16), transparent 55%),
                      linear-gradient(135deg, var(--bg0), var(--bg1));
          color: var(--text);
        }

        /* HERO */
        .hero{position:relative;overflow:hidden;padding:46px 0 26px;}
        .heroBg{position:absolute;inset:0;background-size:cover;background-position:center;opacity:.34;transform:scale(1.03);}
        .heroOverlay{
          position:absolute;inset:0;
          background:
            radial-gradient(900px 500px at 30% 10%, rgba(16,185,129,.22), transparent 60%),
            radial-gradient(700px 420px at 80% 30%, rgba(212,175,55,.18), transparent 55%),
            linear-gradient(to bottom, rgba(5,9,20,.82), rgba(5,9,20,.72), rgba(5,9,20,.90));
        }
        .heroInner{position:relative;text-align:center;}
        .heroBadge{
          width:68px;height:68px;border-radius:20px;margin:0 auto;display:flex;align-items:center;justify-content:center;
          font-size:28px;background: linear-gradient(135deg, rgba(16,185,129,.85), rgba(16,185,129,.45));
          border:1px solid rgba(255,255,255,.16);box-shadow:0 18px 40px rgba(16,185,129,.20);
        }
        .heroKicker{margin:16px 0 8px;font-size:12px;letter-spacing:.34em;color:rgba(197,255,232,.78);text-transform:uppercase;}
        .heroTitle{margin:0;font-size:clamp(28px,4vw,48px);letter-spacing:-.02em;line-height:1.12;}
        .heroSubtitle{margin:10px auto 0;max-width:720px;color:rgba(220,255,245,.82);font-size:16px;line-height:1.6;}

        /* Stats */
        .heroStats{margin:18px auto 0;padding:14px;max-width:980px;border-radius:24px;}
        .statRow{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:10px;}
        .stat{padding:10px 12px;border-radius:18px;background:var(--glass2);border:1px solid rgba(255,255,255,.10);text-align:left;}
        .statLabel{margin:0;font-size:12px;color:rgba(255,255,255,.62);}
        .statValue{margin:6px 0 0;font-size:15px;font-weight:800;}

        .progressWrap{margin-top:12px;padding:10px 12px;border-radius:18px;background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.08);}
        .progressTop{display:flex;justify-content:space-between;align-items:center;margin-bottom:8px;}
        .progressText{font-size:12px;color:rgba(255,255,255,.65);}
        .progressTrack{height:10px;border-radius:999px;background:rgba(255,255,255,.10);overflow:hidden;}
        .progressBar{height:100%;border-radius:999px;background:linear-gradient(90deg,var(--gold),var(--primary));}

        .heroActions{margin:14px auto 0;display:flex;gap:10px;justify-content:center;flex-wrap:wrap;}
        .btnPrimary{
          border:0;cursor:pointer;padding:12px 16px;border-radius:14px;font-weight:800;color:#04110c;
          background:linear-gradient(135deg,var(--gold),var(--primary));box-shadow:0 14px 34px rgba(16,185,129,.18);
        }
        .btnGhost{
          cursor:pointer;padding:12px 16px;border-radius:14px;font-weight:800;color:rgba(255,255,255,.88);
          background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.14);
        }

        /* Main grid */
        .mainGrid{display:grid;grid-template-columns:1fr;gap:14px;padding-top:14px;}
        .card{overflow:hidden;border-radius:24px;}
        .cardHead{padding:18px 18px 0;}
        .cardBody{padding:18px;display:flex;flex-direction:column;gap:14px;}
        .cardTitleRow{display:flex;gap:12px;align-items:flex-start;}
        .iconCircle{
          width:44px;height:44px;border-radius:14px;display:flex;align-items:center;justify-content:center;
          background:linear-gradient(135deg, rgba(16,185,129,.22), rgba(212,175,55,.14));
          border:1px solid rgba(255,255,255,.14);
        }
        .cardTitle{margin:0;font-size:18px;letter-spacing:.2px;}
        .cardDesc{margin:6px 0 0;color:var(--muted2);font-size:13px;}

        .field{display:flex;flex-direction:column;gap:8px;}
        .label{font-size:13px;color:rgba(255,255,255,.78);font-weight:700;}
        .input,.select,.textarea{
          width:100%;border-radius:16px;border:1px solid rgba(255,255,255,.12);
          background:rgba(3,8,16,.42);color:rgba(255,255,255,.92);
          padding:12px 14px;outline:none;
        }
        .input::placeholder,.textarea::placeholder{color:rgba(255,255,255,.50);}
        .input:focus,.select:focus,.textarea:focus{border-color:rgba(16,185,129,.55);box-shadow:0 0 0 4px rgba(16,185,129,.18);}
        .textarea{resize:vertical;min-height:90px;}

        .amountGrid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:10px;}
        .amountPill{
          cursor:pointer;padding:12px 12px;border-radius:16px;border:1px solid rgba(255,255,255,.12);
          background:rgba(255,255,255,.06);color:rgba(255,255,255,.88);font-weight:800;
        }
        .amountPill:hover{border-color:rgba(16,185,129,.38);}
        .amountPill.active{border-color:rgba(16,185,129,.60);background:rgba(16,185,129,.14);}

        .hintRow{display:flex;justify-content:space-between;align-items:center;}
        .hint{font-size:12px;color:rgba(255,255,255,.55);}
        .hintStrong{font-size:12px;font-weight:800;color:rgba(255,255,255,.86);}

        .segmented{display:grid;grid-template-columns:1fr;gap:10px;}
        .segBtn{
          cursor:pointer;text-align:left;padding:12px 14px;border-radius:18px;border:1px solid rgba(255,255,255,.12);
          background:rgba(255,255,255,.05);color:rgba(255,255,255,.88);
        }
        .segBtn.active{border-color:rgba(16,185,129,.60);background:rgba(16,185,129,.12);}
        .segTitle{display:block;font-weight:900;}
        .segSub{display:block;margin-top:3px;font-size:12px;color:rgba(255,255,255,.62);}

        .grid2{display:grid;grid-template-columns:1fr;gap:12px;}
        .grid3{display:grid;grid-template-columns:1fr;gap:12px;}

        .submit{
          cursor:pointer;border:0;border-radius:18px;padding:14px 16px;font-weight:900;font-size:16px;color:#04110c;
          background:linear-gradient(135deg,var(--gold),var(--primary));box-shadow:0 16px 36px rgba(16,185,129,.18);
        }
        .finePrint{margin:0;font-size:12px;color:rgba(255,255,255,.55);}
        .errorText{margin:0;color:#fca5a5;font-size:12px;}

        /* Sidebar */
        .sidebar{display:flex;flex-direction:column;gap:12px;}
        .sideCard{padding:16px;border-radius:24px;}
        .sideHead{display:flex;gap:12px;align-items:flex-start;}
        .sideTitle{margin:0;font-size:14px;font-weight:900;}
        .sideDesc{margin:6px 0 0;font-size:12px;color:rgba(255,255,255,.58);}
        .sideBody{margin-top:12px;display:flex;flex-direction:column;gap:10px;}
        .kv{display:flex;justify-content:space-between;align-items:center;font-size:13px;color:rgba(255,255,255,.74);}
        .kv strong{color:rgba(255,255,255,.92);}
        .miniProgress{margin-top:6px;padding:10px 12px;border-radius:18px;background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.08);}
        .miniProgressTop{display:flex;justify-content:space-between;font-size:12px;color:rgba(255,255,255,.62);margin-bottom:8px;}

        .recentRow{display:flex;justify-content:space-between;gap:10px;padding:10px 0;border-bottom:1px solid rgba(255,255,255,.08);}
        .recentRow:last-child{border-bottom:0;padding-bottom:0;}
        .recentLeft{display:flex;gap:10px;align-items:center;}
        .avatar{
          width:38px;height:38px;border-radius:999px;display:flex;align-items:center;justify-content:center;font-weight:900;
          background:rgba(16,185,129,.12);border:1px solid rgba(16,185,129,.30);
        }
        .avatar.anon{background:rgba(212,175,55,.14);border-color:rgba(212,175,55,.32);}
        .recentName{font-size:13px;font-weight:900;}
        .recentTime{font-size:12px;color:rgba(255,255,255,.56);}
        .recentAmount{font-size:13px;font-weight:900;color:rgba(255,255,255,.90);}

        .payGrid{display:grid;gap:10px;}
        .payBox{border-radius:18px;padding:12px;border:1px solid rgba(255,255,255,.10);background:rgba(255,255,255,.05);}
        .payTitle{font-size:12px;font-weight:900;}
        .payValue{margin-top:6px;font-size:12px;color:rgba(255,255,255,.70);}
        .payBox.blue{background:rgba(59,130,246,.12);border-color:rgba(59,130,246,.22);}
        .payBox.green{background:rgba(16,185,129,.12);border-color:rgba(16,185,129,.22);}
        .payBox.purple{background:rgba(168,85,247,.12);border-color:rgba(168,85,247,.22);}

        /* Campaigns */
        .campaigns{padding-top:26px;}
        .sectionHead{text-align:center;margin-bottom:14px;}
        .sectionKicker{margin:0;font-size:12px;letter-spacing:.34em;color:rgba(197,255,232,.78);}
        .sectionTitle{margin:10px 0 0;font-size:clamp(22px,3vw,34px);}
        .sectionSub{margin:10px auto 0;max-width:760px;color:rgba(220,255,245,.75);}

        .campaignGrid{display:grid;grid-template-columns:1fr;gap:12px;margin-top:14px;}
        .campCard{overflow:hidden;border-radius:24px;}
        .campMedia{position:relative;height:160px;background-size:cover;background-position:center;}
        .campOverlay{position:absolute;inset:0;background:linear-gradient(to right, rgba(5,9,20,.76), rgba(16,185,129,.22), rgba(212,175,55,.10));}
        .campBadge{
          position:absolute;top:12px;right:12px;font-size:12px;font-weight:900;color:rgba(255,255,255,.90);
          background:rgba(255,255,255,.10);border:1px solid rgba(255,255,255,.16);padding:8px 10px;border-radius:999px;
        }
        .campIcon{
          position:absolute;left:12px;bottom:12px;width:46px;height:46px;border-radius:16px;display:flex;align-items:center;justify-content:center;
          background:rgba(255,255,255,.08);border:1px solid rgba(255,255,255,.14);font-size:20px;
        }
        .campBody{padding:14px;}
        .campTitle{margin:0;font-size:16px;font-weight:900;}
        .campDesc{
          margin:8px 0 0;color:rgba(255,255,255,.72);font-size:13px;line-height:1.55;
          display:-webkit-box;-webkit-line-clamp:3;-webkit-box-orient:vertical;overflow:hidden;
        }
        .campKv{margin-top:12px;display:flex;flex-direction:column;gap:8px;}
        .kvRow{display:flex;justify-content:space-between;font-size:12px;color:rgba(255,255,255,.66);}
        .kvRow strong{color:rgba(255,255,255,.90);}
        .btnCamp{
          margin-top:12px;width:100%;border:0;cursor:pointer;padding:12px 14px;border-radius:16px;font-weight:900;color:#04110c;
          background:linear-gradient(135deg,var(--gold),var(--primary));
        }

        /* Newsletter */
        .newsletter{padding:26px 0 18px;}
        .newsletterInner{padding:18px;text-align:center;border-radius:24px;}
        .newsletterTitle{margin:0;font-size:clamp(20px,2.6vw,28px);font-weight:900;}
        .newsletterSub{margin:8px auto 0;max-width:720px;color:rgba(220,255,245,.75);}
        .newsletterForm{margin-top:14px;display:flex;gap:10px;flex-direction:column;}
        .newsletterInput{
          flex:1;border-radius:16px;border:1px solid rgba(255,255,255,.14);
          background:rgba(255,255,255,.06);color:rgba(255,255,255,.92);padding:12px 14px;outline:none;
        }
        .newsletterInput::placeholder{color:rgba(255,255,255,.55);}
        .newsletterBtn{cursor:pointer;border:0;border-radius:16px;padding:12px 14px;font-weight:900;color:#04110c;background:rgba(255,255,255,.92);}

        /* Sticky CTA */
        .stickyCta{position:fixed;left:12px;right:12px;bottom:12px;z-index:50;display:flex;gap:10px;}
        .stickyBtn{flex:1;border-radius:16px;padding:12px 14px;font-weight:900;cursor:pointer;}
        .stickyBtn.primary{border:0;color:#04110c;background:linear-gradient(135deg,var(--gold),var(--primary));}
        .stickyBtn.ghost{color:rgba(255,255,255,.90);background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.14);}

        @media (min-width: 640px){
          .statRow{grid-template-columns:repeat(4,minmax(0,1fr));}
          .amountGrid{grid-template-columns:repeat(3,minmax(0,1fr));}
          .segmented{grid-template-columns:1fr 1fr;}
          .grid2{grid-template-columns:1fr 1fr;}
          .grid3{grid-template-columns:repeat(3,minmax(0,1fr));}
          .newsletterForm{flex-direction:row;}
        }
        @media (min-width: 980px){
          .mainGrid{grid-template-columns:1.25fr .75fr;gap:16px;}
          .campaignGrid{grid-template-columns:repeat(3,minmax(0,1fr));}
          .stickyCta{display:none;}
        }
    </style>

    <div class="page">
        {{-- HERO / JUMBOTRON --}}
        <header class="hero">
            <div class="heroBg" style="background-image:url('{{ $bgImage }}')"></div>
            <div class="heroOverlay"></div>
            <div class="container heroInner">
                <div class="heroBadge" aria-hidden="true">❤</div>
                <p class="heroKicker">DONASI MASJID</p>
                <h1 class="heroTitle">Donasi Masjid Agung Al-A'la</h1>
                <p class="heroSubtitle">
                    Bergabung dalam kebaikan dengan mendukung program masjid secara transparan dan berdampak.
                </p>

                <div class="heroStats glass">
                    <div class="statRow">
                        <div class="stat">
                            <p class="statLabel">Total Terkumpul</p>
                            <p class="statValue">Rp {{ number_format($totalRaised, 0, ',', '.') }}</p>
                        </div>
                        <div class="stat">
                            <p class="statLabel">Target Keseluruhan</p>
                            <p class="statValue">Rp {{ number_format($totalTarget, 0, ',', '.') }}</p>
                        </div>
                        <div class="stat">
                            <p class="statLabel">Donatur</p>
                            <p class="statValue">{{ number_format($totalDonors, 0, ',', '.') }}</p>
                        </div>
                        <div class="stat">
                            <p class="statLabel">Campaign Aktif</p>
                            <p class="statValue">{{ $activeCampaigns }}</p>
                        </div>
                    </div>

                    <div class="progressWrap" aria-label="Progress keseluruhan donasi">
                        <div class="progressTop">
                            <span class="progressText">Progress keseluruhan</span>
                            <span class="progressText">{{ number_format($overallProgress, 0) }}%</span>
                        </div>
                        <div class="progressTrack">
                            <div class="progressBar" style="width: {{ $overallProgress }}%"></div>
                        </div>
                    </div>
                </div>

                <div class="heroActions">
                    <button class="btnPrimary" type="button" data-scroll="#donation-form">Donasi Sekarang</button>
                    <button class="btnGhost" type="button" data-scroll="#campaigns">Lihat Campaign</button>
                </div>
            </div>
        </header>

        {{-- MAIN --}}
        <main class="container mainGrid" id="donation-form">
            {{-- FORM --}}
            <section class="glass card">
                <div class="cardHead">
                    <div class="cardTitleRow">
                        <span class="iconCircle" aria-hidden="true">🎁</span>
                        <div>
                            <h2 class="cardTitle">Form Donasi</h2>
                            <p class="cardDesc">Isi data berikut untuk mengirim donasi terbaik Anda</p>
                        </div>
                    </div>
                </div>

                <form class="cardBody"
                      id="donationForm"
                      method="{{ $hasTransaksiRoute ? 'POST' : 'GET' }}"
                      action="{{ $hasTransaksiRoute && $defaultDonasiId ? route('donasi.transaksi', ['donasi' => $defaultDonasiId]) : url()->current() }}"
                      data-action-template="{{ $donasiActionTemplate }}"
                      data-default-donasi="{{ $defaultDonasiId }}"
                      data-initial-amount="{{ $selectedAmount }}">
                    @if ($hasTransaksiRoute)
                        @csrf
                    @endif

                    <div class="field">
                        <label class="label">Pilih Campaign</label>
                        <select class="select" name="campaign_id" id="campaign_id">
                            <option value="">Donasi Umum</option>
                            @foreach ($donasis as $c)
                                <option value="{{ $c->id }}">{{ $c->judul }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="field">
                        <label class="label">Pilih Jumlah Donasi</label>

                        <div class="amountGrid">
                            @foreach ($presetAmounts as $a)
                                <button type="button" class="amountPill js-amount" data-amount="{{ $a }}">
                                    Rp {{ number_format($a, 0, ',', '.') }}
                                </button>
                            @endforeach
                        </div>

                        <input class="input" type="number" min="10000" name="amount_custom" id="amount_custom"
                               placeholder="Jumlah custom (contoh: 125000)"
                               value="{{ $selectedAmount ?: '' }}">

                        <input type="hidden" name="jumlah" id="amount_final" value="{{ $selectedAmount }}">

                        <div class="hintRow">
                            <span class="hint">Nominal dipilih:</span>
                            <span class="hintStrong" id="amount_preview">Rp {{ $formattedSelectedAmount }}</span>
                        </div>
                        @error('jumlah')
                            <p class="errorText">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="field">
                        <label class="label">Jenis Donasi</label>
                        <div class="segmented">
                            <button type="button" class="segBtn active js-frequency" data-value="once">
                                <span class="segTitle">Donasi Sekali</span>
                                <span class="segSub">Satu kali pembayaran</span>
                            </button>
                            <button type="button" class="segBtn js-frequency" data-value="monthly">
                                <span class="segTitle">Donasi Bulanan</span>
                                <span class="segSub">Komitmen rutin tiap bulan</span>
                            </button>
                        </div>
                        <input type="hidden" name="frequency" id="frequency" value="once">
                    </div>

                    <div class="grid3">
                        <div class="field">
                            <label class="label">Nama Lengkap</label>
                            <input class="input" name="name" placeholder="Masukkan nama" value="{{ old('name') }}">
                        </div>
                        <div class="field">
                            <label class="label">Email</label>
                            <input class="input" type="email" name="email" placeholder="email@example.com" value="{{ old('email') }}">
                        </div>
                        <div class="field">
                            <label class="label">No. WhatsApp</label>
                            <input class="input" name="whatsapp" placeholder="08XXXXXXXXXX" value="{{ old('whatsapp') }}">
                        </div>
                    </div>

                    <div class="grid2">
                        <div class="field">
                            <label class="label">Metode Pembayaran</label>
                            <select class="select" name="pay_method">
                                <option value="transfer">Transfer Bank</option>
                                <option value="ewallet">E-Wallet</option>
                                <option value="card">Kartu Kredit/Debit</option>
                            </select>
                        </div>
                        <div class="field">
                            <label class="label">Kategori</label>
                            <select class="select" name="category">
                                <option value="general">Kategori Umum</option>
                                <option value="pembangunan">Pembangunan</option>
                                <option value="pendidikan">Pendidikan</option>
                                <option value="sosial">Sosial</option>
                            </select>
                        </div>
                    </div>

                    <div class="field">
                        <label class="label">Pesan / Doa (opsional)</label>
                        <textarea class="textarea" rows="3" name="message" placeholder="Tuliskan pesan atau doa...">{{ old('message') }}</textarea>
                    </div>

                    <button class="submit" type="submit">Donasi Sekarang</button>
                    <p class="finePrint">
                        Pembayaran diproses via Xendit (sandbox). Anda akan diarahkan ke halaman pembayaran setelah submit.
                    </p>
                </form>
            </section>

            {{-- SIDEBAR --}}
            <aside class="sidebar">
                {{-- Impact --}}
                <section class="glass sideCard">
                    <div class="sideHead">
                        <span class="iconCircle" aria-hidden="true">📈</span>
                        <div>
                            <h3 class="sideTitle">Dampak Kebaikan</h3>
                            <p class="sideDesc">Ringkasan progress donasi</p>
                        </div>
                    </div>

                    <div class="sideBody">
                        <div class="kv"><span>Total Dana Terkumpul</span><strong>Rp {{ number_format($totalRaised, 0, ',', '.') }}</strong></div>
                        <div class="kv"><span>Target Keseluruhan</span><strong>Rp {{ number_format($totalTarget, 0, ',', '.') }}</strong></div>
                        <div class="kv"><span>Donatur Terdata</span><strong>{{ number_format($totalDonors) }} orang</strong></div>
                        <div class="kv"><span>Campaign Aktif</span><strong>{{ $activeCampaigns }}</strong></div>

                        <div class="miniProgress">
                            <div class="miniProgressTop">
                                <span>Progress</span>
                                <span>{{ number_format($overallProgress, 0) }}%</span>
                            </div>
                            <div class="progressTrack">
                                <div class="progressBar" style="width: {{ $overallProgress }}%"></div>
                            </div>
                        </div>
                    </div>
                </section>

                {{-- Recent --}}
                <section class="glass sideCard">
                    <div class="sideHead">
                        <span class="iconCircle" aria-hidden="true">👥</span>
                        <div>
                            <h3 class="sideTitle">Donasi Terbaru</h3>
                            <p class="sideDesc">Update terbaru dari jamaah</p>
                        </div>
                    </div>

                    <div class="sideBody">
                        @foreach ($recentList as $d)
                            <div class="recentRow">
                                <div class="recentLeft">
                                    <div class="avatar {{ $d['anonymous'] ? 'anon' : '' }}">
                                        {{ $d['anonymous'] ? '❤' : strtoupper(substr($d['name'], 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="recentName">{{ $d['name'] }}</div>
                                        <div class="recentTime">{{ $d['time'] }}</div>
                                    </div>
                                </div>
                                <div class="recentAmount">Rp {{ number_format($d['amount'], 0, ',', '.') }}</div>
                            </div>
                        @endforeach
                    </div>
                </section>

                {{-- Payment info --}}
                <section class="glass sideCard">
                    <div class="sideHead">
                        <span class="iconCircle" aria-hidden="true">💳</span>
                        <div>
                            <h3 class="sideTitle">Info Pembayaran</h3>
                            <p class="sideDesc">Contoh kanal pembayaran</p>
                        </div>
                    </div>

                    <div class="sideBody payGrid">
                        <div class="payBox blue">
                            <div class="payTitle">BCA Virtual Account</div>
                            <div class="payValue">1234 5678 9012 3456</div>
                        </div>
                        <div class="payBox green">
                            <div class="payTitle">GoPay / OVO</div>
                            <div class="payValue">0812-3456-7890</div>
                        </div>
                        <div class="payBox purple">
                            <div class="payTitle">QRIS</div>
                            <div class="payValue">Scan di aplikasi favorit</div>
                        </div>
                    </div>
                </section>
            </aside>
        </main>

        {{-- CAMPAIGNS --}}
        <section class="container campaigns" id="campaigns">
            <div class="sectionHead">
                <p class="sectionKicker">CAMPAIGN AKTIF</p>
                <h2 class="sectionTitle">Program Donasi Masjid</h2>
                <p class="sectionSub">Pilih campaign yang ingin Anda dukung. Semua donasi dicatat secara transparan.</p>
            </div>

            <div class="campaignGrid">
                @foreach ($donasis as $c)
                    @php
                        $p = $c->target_dana > 0 ? min(100, max(0, ($c->dana_terkumpul / $c->target_dana) * 100)) : 0;
                        $left = $daysLeft($c->tanggal_selesai);
                        $donors = (int) ($c->transaksi_donasis_count ?? 0);
                    @endphp

                    <article class="glass campCard">
                        <div class="campMedia" style="background-image:url('{{ $c->poster ?? $posterFallback }}')">
                            <div class="campOverlay"></div>
                            <div class="campBadge">{{ is_null($left) ? 'Fleksibel' : 'Target ' . $left . ' hari' }}</div>
                            <div class="campIcon" aria-hidden="true">🎯</div>
                        </div>

                        <div class="campBody">
                            <h3 class="campTitle">{{ $c->judul }}</h3>
                            <p class="campDesc">{{ \Illuminate\Support\Str::limit(strip_tags($c->deskripsi), 160) }}</p>

                            <div class="campKv">
                                <div class="kvRow"><span>Terkumpul</span><strong>Rp {{ number_format($c->dana_terkumpul, 0, ',', '.') }}</strong></div>
                                <div class="progressTrack"><div class="progressBar" style="width: {{ $p }}%"></div></div>
                                <div class="kvRow"><span>Target</span><strong>Rp {{ number_format($c->target_dana, 0, ',', '.') }}</strong></div>
                                <div class="kvRow"><span>Donatur</span><strong>{{ number_format($donors) }} orang</strong></div>
                            </div>

                            <button class="btnCamp" type="button" data-scroll="#donation-form" data-campaign-id="{{ $c->id }}">Donasi Sekarang</button>
                        </div>
                    </article>
                @endforeach
            </div>

            <div style="margin-top:14px;">
                {{ $donasis->links() }}
            </div>
        </section>

        {{-- NEWSLETTER --}}
        <section class="container newsletter">
            <div class="glass newsletterInner">
                <h2 class="newsletterTitle">Saling Menguatkan dalam Kebaikan</h2>
                <p class="newsletterSub">Dapatkan update campaign donasi terbaru langsung ke email Anda.</p>

                <form class="newsletterForm" method="{{ $newsletterMethod }}" action="{{ $newsletterAction }}">
                    @if ($newsletterHasRoute)
                        @csrf
                    @endif
                    <input class="newsletterInput" type="email" name="email" placeholder="Email Anda" required>
                    <button class="newsletterBtn" type="submit">Berlangganan</button>
                </form>
            </div>
        </section>

        {{-- MOBILE STICKY CTA --}}
        <div class="stickyCta">
            <button class="stickyBtn primary" type="button" data-scroll="#donation-form">Donasi</button>
            <button class="stickyBtn ghost" type="button" data-scroll="#campaigns">Campaign</button>
        </div>

        {{-- JS kecil: scroll + preset amount + frequency --}}
        <script>
            (function () {
                const rupiah = (n) => new Intl.NumberFormat("id-ID", { style: "currency", currency: "IDR", maximumFractionDigits: 0 }).format(n);

                // smooth scroll
                document.querySelectorAll("[data-scroll]").forEach((btn) => {
                    btn.addEventListener("click", () => {
                        const target = btn.getAttribute("data-scroll");
                        const el = document.querySelector(target);
                        if (el) el.scrollIntoView({ behavior: "smooth", block: "start" });
                    });
                });

                // update form action when campaign dipilih
                const form = document.getElementById("donationForm");
                const campaignSelect = document.getElementById("campaign_id");
                const actionTemplate = form?.dataset.actionTemplate || "";
                const defaultDonasiId = form?.dataset.defaultDonasi || "";

                if (form && campaignSelect && actionTemplate.includes("__ID__")) {
                    const updateAction = () => {
                        const chosen = campaignSelect.value || defaultDonasiId;
                        if (chosen) {
                            form.action = actionTemplate.replace("__ID__", chosen);
                        }
                    };
                    campaignSelect.addEventListener("change", updateAction);
                    updateAction();
                }

                const campaignButtons = Array.from(document.querySelectorAll("[data-campaign-id]"));
                if (campaignSelect && campaignButtons.length) {
                    campaignButtons.forEach((btn) => {
                        btn.addEventListener("click", () => {
                            const chosen = btn.getAttribute("data-campaign-id") || "";
                            campaignSelect.value = chosen;
                            campaignSelect.dispatchEvent(new Event("change"));
                        });
                    });
                }

                // amount preset
                const amountButtons = Array.from(document.querySelectorAll(".js-amount"));
                const amountCustom = document.getElementById("amount_custom");
                const amountFinal = document.getElementById("amount_final");
                const amountPreview = document.getElementById("amount_preview");
                const initialAmount = parseInt(form?.dataset.initialAmount || "0", 10) || 0;

                function setActiveAmount(btn) {
                    amountButtons.forEach((b) => b.classList.remove("active"));
                    if (btn) btn.classList.add("active");
                }

                function setAmount(n, { fromCustom = false } = {}) {
                    const clean = Math.max(0, parseInt(n || "0", 10));
                    if (amountFinal) amountFinal.value = String(clean);
                    if (amountPreview) amountPreview.textContent = rupiah(clean || 0);
                    if (!fromCustom && amountCustom) {
                        amountCustom.value = clean ? clean : "";
                    }
                }

                // default
                setAmount(initialAmount);
                const defaultBtn = amountButtons.find((b) => b.getAttribute("data-amount") === String(initialAmount));
                setActiveAmount(defaultBtn || null);

                amountButtons.forEach((btn) => {
                    btn.addEventListener("click", () => {
                        const n = parseInt(btn.getAttribute("data-amount") || "0", 10);
                        if (n > 0) {
                            setActiveAmount(btn);
                            setAmount(n);
                        }
                    });
                });

                if (amountCustom) {
                    amountCustom.addEventListener("input", () => {
                        const n = parseInt(amountCustom.value || "0", 10);
                        setActiveAmount(null);
                        setAmount(n, { fromCustom: true });
                    });
                }

                // frequency
                const freqButtons = Array.from(document.querySelectorAll(".js-frequency"));
                const freqInput = document.getElementById("frequency");

                freqButtons.forEach((btn) => {
                    btn.addEventListener("click", () => {
                        freqButtons.forEach((b) => b.classList.remove("active"));
                        btn.classList.add("active");
                        const v = btn.getAttribute("data-value") || "once";
                        freqInput.value = v;
                    });
                });
            })();
        </script>
    </div>
</x-front-layout>
