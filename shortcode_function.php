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
        .s-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 12px; }
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

        /* Premium Toolbar Styles */
        .q-toolbar { display: flex; flex-wrap: wrap; gap: 15px; align-items: center; justify-content: center; background: #fff; padding: 15px; border: 1px solid #dcdcde; border-radius: 4px; margin-bottom: 20px; }
        .q-tool-item { display: flex; align-items: center; gap: 8px; font-size: 13px; color: #1d2327; font-weight: 600; }
        .q-tool-item select { padding: 5px 10px; border: 1px solid #8c8f94; border-radius: 4px; background: #fff; font-size: 13px; }
        
        /* Toggle Switch */
        .switch { position: relative; display: inline-block; width: 34px; height: 18px; }
        .switch input { opacity: 0; width: 0; height: 0; }
        .slider { position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #ccc; transition: .4s; border-radius: 18px; }
        .slider:before { position: absolute; content: ""; height: 12px; width: 12px; left: 3px; bottom: 3px; background-color: white; transition: .4s; border-radius: 50%; }
        input:checked + .slider { background-color: var(--p-blue); }
        input:checked + .slider:before { transform: translateX(16px); }

        .btn-audio-full { background: #fff; border: 1px solid var(--p-blue); color: var(--p-blue); padding: 5px 12px; border-radius: 20px; cursor: pointer; display: flex; align-items: center; gap: 5px; font-size: 13px; font-weight: 600; transition: 0.2s; }
        .btn-audio-full:hover { background: var(--p-blue); color: #fff; }
        .btn-audio-full.playing { background: #d63638; border-color: #d63638; color: #fff; }
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
            <div style="display: flex; gap: 10px; margin-bottom: 20px;">
                <input type="text" class="q-search" id="q-find" placeholder="Cari surat..." onkeyup="searching()" style="margin-bottom: 0; flex-grow: 1;">
                <select id="q-qari" style="padding: 10px; border: 1px solid #8c8f94; border-radius: 4px; background: #fff;">
                    <option value="01">Abdullah Al-Juhany</option>
                    <option value="02">Ab. Muhsin Al-Qasim</option>
                    <option value="03">Ab.rahman As-Sudais</option>
                    <option value="04">Ibrahim Al-Dossari</option>
                    <option value="05" selected>Misyari Rasyid Al-Afasy</option>
                    <option value="06">Yasser Al-Dosari</option>
                </select>
            </div>
            <div class="s-grid">
                <?php foreach ($surahs as $s) : ?>
                <div class="s-card" onclick="getSurah(<?php echo $s['nomor']; ?>, this)" data-audio='<?php echo json_encode($s['audioFull']); ?>'>
                    <div class="s-idx"><?php echo $s['nomor']; ?></div>
                    <div class="s-main">
                        <strong><?php echo $s['namaLatin']; ?></strong>
                        <div style="font-size:12px; color:#646970;"><?php echo $s['arti']; ?></div>
                    </div>
                    <div class="s-ar-box">
                        <div class="s-name-ar"><?php echo $s['nama']; ?></div>
                        <div style="display:flex; align-items:center; justify-content:flex-end; gap:5px; margin-top:5px;">
                            <small style="color:#a7aaad"><?php echo $s['jumlahAyat']; ?> ayat</small>
                            <button class="icon-btn" style="color:var(--p-blue);" title="Play Full Surat" onclick="event.stopPropagation(); playSurahAudio(this.closest('.s-card'), this)">
                                <span class="dashicons dashicons-controls-play"></span>
                            </button>
                        </div>
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

        async function getSurah(no, card) {
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
                
                if (!dA.data || !dA.data.ayat) throw new Error("Data surat tidak valid");
                
                const s = dA.data;
                tafsirStore = (dT.data && dT.data.tafsir) ? dT.data.tafsir : [];

                const qSelect = document.getElementById('q-qari');
                const qKey = qSelect ? qSelect.value : '05';
                
                let audioFullUrl = '';
                try {
                    const audioFullData = card ? JSON.parse(card.getAttribute('data-audio')) : s.audioFull;
                    audioFullUrl = (audioFullData && audioFullData[qKey]) ? audioFullData[qKey] : (s.audioFull ? s.audioFull[qKey] : '');
                } catch(e) { console.warn("Audio data retrieval issue", e); }

                document.getElementById('q-head').innerHTML = `
                    <div style="background:#fff; border:1px solid #dcdcde; padding:20px; margin-bottom:15px; border-radius:4px; display:flex; justify-content:space-between; align-items:center;">
                        <div style="text-align:left;">
                            <h2 style="margin:0; color:var(--p-blue);">${s.namaLatin} &bull; <small style="color:#646970">${s.arti}</small></h2>
                            <div style="font-size:13px; color:#646970; margin-top:5px;">${s.tempatTurun} &bull; ${s.jumlahAyat} Ayat</div>
                        </div>
                        <div style="font-size:1.8rem; font-weight:bold; color:var(--p-blue);">${s.nama}</div>
                    </div>
                    
                    <div class="q-toolbar">
                        <div class="q-tool-item">
                            <span>Ayat:</span>
                            <select onchange="scrollToA(this.value)">
                                <option value="0">Semua</option>
                                ${s.ayat.map(a => `<option value="${a.nomorAyat}">${a.nomorAyat}</option>`).join('')}
                            </select>
                        </div>
                        <div class="q-tool-item">
                            <span>Qari:</span>
                            <select class="sel-qari-detail" onchange="updateQariDetail(this.value, ${no})">
                                ${qSelect ? qSelect.innerHTML : `
                                    <option value="01">Abdullah Al-Juhany</option>
                                    <option value="02">Ab. Muhsin Al-Qasim</option>
                                    <option value="03">Ab.rahman As-Sudais</option>
                                    <option value="04">Ibrahim Al-Dossari</option>
                                    <option value="05" selected>Misyari Rasyid Al-Afasy</option>
                                    <option value="06">Yasser Al-Dosari</option>
                                `}
                            </select>
                        </div>
                        <div class="q-tool-item">
                            <span class="dashicons dashicons-translation"></span> <span>Latin</span>
                            <label class="switch">
                                <input type="checkbox" checked onchange="toggleContent('lt-txt', this.checked)">
                                <span class="slider"></span>
                            </label>
                        </div>
                        <div class="q-tool-item">
                            <span class="dashicons dashicons-editor-textcolor"></span> <span>Arti</span>
                            <label class="switch">
                                <input type="checkbox" checked onchange="toggleContent('id-txt', this.checked)">
                                <span class="slider"></span>
                            </label>
                        </div>
                        ${audioFullUrl ? `
                        <button class="btn-audio-full" id="btn-f-${no}" onclick="playFullSurah('${audioFullUrl}', this)">
                            <span class="dashicons dashicons-controls-play"></span> Play Audio Full
                        </button>` : ''}
                    </div>
                `;
                // Set initial qari
                const selDetail = document.querySelector('.sel-qari-detail');
                if(selDetail) selDetail.value = qKey;

                let html = '';
                s.ayat.forEach(a => {
                    const audio = a.audio[qKey];
                    html += `
                        <div class="a-item" id="ayah-${a.nomorAyat}">
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
                window.scrollTo(0, 0);
            } catch (e) { 
                console.error("eQuran Error:", e);
                container.innerHTML = `<div style="text-align:center; padding:40px;">
                    <p style="color:#d63638; font-weight:bold;">Gagal load data cok.</p>
                    <p style="font-size:12px; color:#646970;">Error: ${e.message}</p>
                    <button class="btn-wp" onclick="getSurah(${no})">Coba Lagi</button>
                </div>`; 
            }
        }

        function updateQariDetail(val, no) {
            const qSelect = document.getElementById('q-qari');
            if(qSelect) qSelect.value = val;
            getSurah(no); // Reload with new qari
        }

        function scrollToA(n) {
            const items = document.querySelectorAll('.a-item');
            if (n == 0) {
                items.forEach(el => el.style.display = 'block');
            } else {
                items.forEach(el => {
                    const id = parseInt(el.id.replace('ayah-', ''));
                    el.style.display = id >= n ? 'block' : 'none';
                });
            }
            document.getElementById('q-view').scrollIntoView({ behavior: 'smooth', block: 'start' });
        }

        function toggleContent(cls, show) {
            document.querySelectorAll(`.${cls}`).forEach(el => el.style.display = show ? 'block' : 'none');
        }

        function playFullSurah(url, btn) {
            if (player && player.src === url) {
                player.pause();
                player = null;
                btn.classList.remove('playing');
                btn.querySelector('.dashicons').className = 'dashicons dashicons-controls-play';
                return;
            }
            playQ(url, btn);
            btn.classList.add('playing');
            btn.querySelector('.dashicons').className = 'dashicons dashicons-controls-pause';
            player.onended = () => {
                btn.classList.remove('playing');
                btn.querySelector('.dashicons').className = 'dashicons dashicons-controls-play';
                player = null;
            };
        }

        function playSurahAudio(card, btn) {
            const urls = JSON.parse(card.getAttribute('data-audio'));
            const qKey = document.getElementById('q-qari').value;
            const url = urls[qKey];
            playQ(url, btn);
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

/**
 * Shortcode untuk menampilkan 1 surat penuh
 * [equran_surat nomor="1" color="red"]
 */
function equran_surat_shortcode($atts) {
    $atts = shortcode_atts(array(
        'nomor' => '1',
        'color' => '#0073aa',
        'audio' => '05'
    ), $atts, 'equran_surat');

    $no = esc_attr($atts['nomor']);
    $color = esc_attr($atts['color']);
    $audio_key = esc_attr($atts['audio']);
    
    $url = "https://equran.id/api/v2/surat/{$no}";
    $response = wp_remote_get($url);
    if (is_wp_error($response)) return "Gagal memuat data.";
    $data = json_decode(wp_remote_retrieve_body($response), true);
    
    if (!isset($data['data'])) return "Surat tidak ditemukan.";
    $s = $data['data'];

    ob_start();
    ?>
    <div class="equran-surat-wrap" style="--p-blue: <?php echo $color; ?>; font-family: sans-serif; max-width: 900px; margin: 20px auto; border: 1px solid #dcdcde; padding: 20px; border-radius: 8px; background: #fff;">
        <div style="text-align:center; padding-bottom:20px; border-bottom: 2px solid var(--p-blue); margin-bottom:20px;">
            <h2 style="color:var(--p-blue); margin:0;"><?php echo $s['namaLatin']; ?> (<?php echo $s['nama']; ?>)</h2>
            <p style="margin:5px 0; color: #646970;"><?php echo $s['arti']; ?> &bull; <?php echo $s['jumlahAyat']; ?> Ayat</p>
            <?php if (isset($s['audioFull'][$audio_key])): ?>
                <audio controls style="width: 100%; max-width: 300px; height: 30px; margin-top: 10px;">
                    <source src="<?php echo $s['audioFull'][$audio_key]; ?>" type="audio/mpeg">
                </audio>
            <?php endif; ?>
        </div>
        <?php foreach ($s['ayat'] as $a): ?>
            <div style="border-bottom: 1px solid #eee; padding: 25px 0;">
                <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 15px;">
                    <div style="display:inline-block; width:28px; height:28px; border:1px solid var(--p-blue); color:var(--p-blue); border-radius:50%; text-align:center; line-height:28px; font-size:11px; font-weight:bold;">
                        <?php echo $a['nomorAyat']; ?>
                    </div>
                    <?php if (isset($a['audio'][$audio_key])): ?>
                        <audio controls style="height: 28px; max-width: 200px;">
                            <source src="<?php echo $a['audio'][$audio_key]; ?>" type="audio/mpeg">
                        </audio>
                    <?php endif; ?>
                </div>
                <div style="text-align:right; font-size:2rem; line-height:2.5; margin-bottom:15px; font-family: 'Amiri', serif;"><?php echo $a['teksArab']; ?></div>
                <div style="color:var(--p-blue); font-style:italic; font-size:0.9rem; margin-bottom:5px;"><?php echo $a['teksLatin']; ?></div>
                <div style="line-height:1.6; color:#3c434a;"><?php echo $a['teksIndonesia']; ?></div>
            </div>
        <?php endforeach; ?>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('equran_surat', 'equran_surat_shortcode');

/**
 * Shortcode untuk menampilkan 1 ayat saja
 * [equran_ayat surat="1" ayat="1" color="red"]
 */
function equran_ayat_shortcode($atts) {
    $atts = shortcode_atts(array(
        'surat' => '1',
        'ayat'  => '1',
        'color' => '#0073aa',
        'audio' => '05'
    ), $atts, 'equran_ayat');

    $no_s = esc_attr($atts['surat']);
    $no_a = esc_attr($atts['ayat']);
    $color = esc_attr($atts['color']);
    $audio_key = esc_attr($atts['audio']);
    
    $url = "https://equran.id/api/v2/surat/{$no_s}";
    $response = wp_remote_get($url);
    if (is_wp_error($response)) return "Gagal memuat data.";
    $data = json_decode(wp_remote_retrieve_body($response), true);
    
    if (!isset($data['data'])) return "Surat tidak ditemukan.";
    $s = $data['data'];
    
    $ayat_found = null;
    foreach ($s['ayat'] as $a) {
        if ($a['nomorAyat'] == $no_a) {
            $ayat_found = $a;
            break;
        }
    }
    
    if (!$ayat_found) return "Ayat tidak ditemukan.";

    ob_start();
    ?>
    <div class="equran-ayat-single" style="--p-blue: <?php echo $color; ?>; font-family: sans-serif; border: 1px solid #dcdcde; border-left: 5px solid var(--p-blue); padding: 25px; border-radius: 4px; max-width: 900px; margin: 20px auto; background: #fff; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
            <div style="font-size: 13px; color: var(--p-blue); font-weight: bold; text-transform: uppercase; letter-spacing: 1px;">
                <?php echo $s['namaLatin']; ?> : Ayat <?php echo $no_a; ?>
            </div>
            <?php if (isset($ayat_found['audio'][$audio_key])): ?>
                <audio controls style="height: 30px; max-width: 250px;">
                    <source src="<?php echo $ayat_found['audio'][$audio_key]; ?>" type="audio/mpeg">
                </audio>
            <?php endif; ?>
        </div>
        <div style="text-align:right; font-size:2.2rem; line-height:2.6; margin-bottom:20px; font-family: 'Amiri', serif;"><?php echo $ayat_found['teksArab']; ?></div>
        <div style="color:var(--p-blue); font-style:italic; font-size:0.95rem; margin-bottom:10px;"><?php echo $ayat_found['teksLatin']; ?></div>
        <div style="line-height:1.6; color:#3c434a; font-size:1.1rem;"><?php echo $ayat_found['teksIndonesia']; ?></div>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('equran_ayat', 'equran_ayat_shortcode');


