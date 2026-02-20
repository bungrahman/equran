<?php
/**
 * Plugin Name: eQuran Shortcode
 * Description: Menampilkan Al-Quran lengkap dengan audio dan tafsir melalui shortcode [tampilkan_quran].
 * Author: Bung Rahman
 */

function equran_wordpress_style($atts) {
    // Atribut shortcode
    $atts = shortcode_atts(
        array(
            'color' => '#0073aa', // Default WordPress Blue
        ),
        $atts,
        'tampilkan_quran'
    );

    $primary_color = esc_attr($atts['color']);

    // Ambil daftar surat
    $url = "https://equran.id/api/v2/surat";
    $response = wp_remote_get($url);
    if (is_wp_error($response)) return "Gagal memuat data.";
    $data = json_decode(wp_remote_retrieve_body($response), true);
    $surahs = $data['data'];

    // Load Dashicons
    wp_enqueue_style('dashicons');

    ob_start();
    ?>
    <style>
        :root { --p-blue: <?php echo $primary_color; ?>; --bg-light: #f6f7f7; }
        .q-app { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif; max-width: 900px; margin: auto; color: #1d2327; }
        
        /* Search Box */
        .q-search { width: 100%; padding: 12px; border: 1px solid #8c8f94; border-radius: 4px; margin-bottom: 20px; box-shadow: inset 0 1px 2px rgba(0,0,0,.07); }
        .q-search:focus { border-color: var(--p-blue); box-shadow: 0 0 0 1px var(--p-blue); outline: none; }

        /* List View */
        .s-grid { display: grid; gap: 12px; }
        .s-card { 
            display: flex; align-items: center; padding: 15px; background: #fff; 
            border: 1px solid #dcdcde; border-radius: 4px; cursor: pointer; transition: 0.1s;
        }
        .s-card:hover { border-color: var(--p-blue); background: #f0f6fb; }
        .s-idx { 
            width: 40px; height: 40px; background: #f0f6fb; color: var(--p-blue); 
            border-radius: 50%; display: flex; align-items: center; justify-content: center; 
            font-weight: 600; margin-right: 15px; border: 1px solid #d2e3ef;
        }
        .s-main { flex-grow: 1; }
        .s-ar-box { text-align: right; }
        .s-name-ar { font-size: 1.4rem; font-weight: bold; color: var(--p-blue); }

        /* Detail View */
        #q-view { display: none; }
        .btn-wp { background: var(--p-blue); color: #fff; border: 1px solid #006799; padding: 8px 16px; border-radius: 3px; cursor: pointer; margin-bottom: 20px; display: inline-flex; align-items: center; gap: 5px; }
        .btn-wp:hover { background: #006799; }
        
        /* Toolbar Ayat */
        .a-toolbar { display: flex; align-items: center; gap: 12px; margin-bottom: 15px; padding: 8px; background: #f0f6fb; border-radius: 4px; border-left: 4px solid var(--p-blue); }
        .a-badge { width: 26px; height: 26px; border: 2px solid #00a0d2; color: #00a0d2; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 11px; font-weight: bold; }
        
        /* Icon Button Dashicons */
        .icon-btn { background: none; border: none; cursor: pointer; color: #50575e; padding: 4px; display: flex; align-items: center; transition: 0.2s; }
        .icon-btn:hover { color: var(--p-blue); }
        .icon-btn.active { color: #d63638; } /* Warna merah pas stop */
        .dashicons { font-size: 20px; width: 20px; height: 20px; }

        .ar-txt { text-align: right; font-size: 2.3rem; line-height: 2.8; margin-bottom: 15px; font-family: "Amiri", serif; color: #2c3338; }
        .lt-txt { color: var(--p-blue); font-style: italic; margin-bottom: 8px; font-size: 0.95rem; }
        .id-txt { line-height: 1.6; color: #3c434a; }
        .a-item { border-bottom: 1px solid #dcdcde; padding: 30px 0; }

        /* Modal Tafsir */
        .wp-modal { display: none; position: fixed; z-index: 99999; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); }
        .wp-modal-content { background: #fff; margin: 5% auto; padding: 25px; width: 90%; max-width: 650px; border-radius: 4px; position: relative; box-shadow: 0 3px 6px rgba(0,0,0,0.3); }
    </style>

    <div class="q-app">
        <div id="m-tafsir" class="wp-modal">
            <div class="wp-modal-content">
                <span class="dashicons dashicons-no-alt" style="position:absolute; right:15px; top:15px; cursor:pointer;" onclick="closeM()"></span>
                <h3 id="m-title" style="color:var(--p-blue); margin-top:0;">Tafsir Ayat</h3>
                <hr>
                <div id="m-body" style="margin-top:15px; max-height:400px; overflow-y:auto; white-space:pre-line;"></div>
            </div>
        </div>

        <div id="q-view">
            <button class="btn-wp" onclick="backTo()">
                <span class="dashicons dashicons-arrow-left-alt"></span> Kembali
            </button>
            <div id="q-head" style="text-align:center; padding:20px; background:#fff; border:1px solid #dcdcde; margin-bottom:20px;"></div>
            <div id="a-list"></div>
        </div>

        <div id="q-list">
            <input type="text" class="q-search" id="q-find" placeholder="Cari surat..." onkeyup="searching()">
            <div class="s-grid">
                <?php foreach ($surahs as $s) : ?>
                <div class="s-card" onclick="getSurah(<?php echo $s['nomor']; ?>)">
                    <div class="s-idx"><?php echo $s['nomor']; ?></div>
                    <div class="s-main">
                        <strong><?php echo $s['namaLatin']; ?></strong>
                        <div style="font-size:12px; color:#646970;"><?php echo $s['arti']; ?></div>
                    </div>
                    <div class="s-ar-box">
                        <div class="s-name-ar"><?php echo $s['nama']; ?></div>
                        <small style="color:#a7aaad"><?php echo $s['jumlahAyat']; ?> ayat</small>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <script>
        let tafsirStore = [];
        let player = null;
        let activeBtn = null;

        async function getSurah(no) {
            document.getElementById('q-list').style.display = 'none';
            document.getElementById('q-view').style.display = 'block';
            const container = document.getElementById('a-list');
            container.innerHTML = '<p style="text-align:center; padding:40px;">Memuat data dari API...</p>';

            try {
                const [rA, rT] = await Promise.all([
                    fetch(`https://equran.id/api/v2/surat/${no}`),
                    fetch(`https://equran.id/api/v2/tafsir/${no}`)
                ]);

                const dA = await rA.json();
                const dT = await rT.json();
                const s = dA.data;
                tafsirStore = dT.data.tafsir;

                document.getElementById('q-head').innerHTML = `<h2 style="margin:0; color:var(--p-blue);">${s.namaLatin}</h2><div>${s.arti} &bull; ${s.jumlahAyat} Ayat</div>`;

                let html = '';
                s.ayat.forEach(a => {
                    const audio = a.audio['05'];
                    html += `
                        <div class="a-item">
                            <div class="a-toolbar">
                                <div class="a-badge">${a.nomorAyat}</div>
                                <button class="icon-btn" title="Mainkan Audio" onclick="playQ('${audio}', this)">
                                    <span class="dashicons dashicons-controls-play"></span>
                                </button>
                                <button class="icon-btn" title="Baca Tafsir" onclick="openT(${a.nomorAyat})">
                                    <span class="dashicons dashicons-book-alt"></span>
                                </button>
                                <button class="icon-btn" title="Salin Ayat" onclick="navigator.clipboard.writeText('${a.teksArab}')">
                                    <span class="dashicons dashicons-admin-page"></span>
                                </button>
                            </div>
                            <div class="ar-txt">${a.teksArab}</div>
                            <div class="lt-txt">${a.teksLatin}</div>
                            <div class="id-txt">${a.teksIndonesia}</div>
                        </div>
                    `;
                });
                container.innerHTML = html;
                window.scrollTo(0,0);
            } catch (e) { container.innerHTML = 'Gagal load data cok.'; }
        }

        function playQ(url, btn) {
            if (player) {
                player.pause();
                activeBtn.querySelector('.dashicons').className = 'dashicons dashicons-controls-play';
                activeBtn.classList.remove('active');
            }

            if (!player || player.src !== url) {
                player = new Audio(url);
                player.play();
                activeBtn = btn;
                btn.querySelector('.dashicons').className = 'dashicons dashicons-controls-pause';
                btn.classList.add('active');
                player.onended = () => {
                    btn.querySelector('.dashicons').className = 'dashicons dashicons-controls-play';
                    btn.classList.remove('active');
                    player = null;
                };
            } else {
                player = null;
            }
        }

        function openT(n) {
            const t = tafsirStore.find(x => x.ayat === n);
            document.getElementById('m-title').innerText = `Tafsir Ayat ${n}`;
            document.getElementById('m-body').innerText = t ? t.teks : 'Tafsir kosong.';
            document.getElementById('m-tafsir').style.display = 'block';
        }

        function closeM() { document.getElementById('m-tafsir').style.display = 'none'; }
        function backTo() { 
            if(player) player.pause();
            document.getElementById('q-list').style.display = 'block';
            document.getElementById('q-view').style.display = 'none';
        }
        function searching() {
            let v = document.getElementById('q-find').value.toUpperCase();
            document.querySelectorAll('.s-card').forEach(c => {
                c.style.display = c.innerText.toUpperCase().includes(v) ? "" : "none";
            });
        }
        window.onclick = function(e) { if(e.target.className === 'wp-modal') closeM(); }
    </script>
    <?php
    return ob_get_clean();
}
add_shortcode('tampilkan_quran', 'equran_wordpress_style');

