<!DOCTYPE html>
<html>
<head>
    <title>Form Pemesanan</title>
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="ISI_DENGAN_CLIENT_KEY_ANDA"></script>
    <style>
        body { font-family: sans-serif; }
        .container { max-width: 500px; margin: auto; padding: 20px; border: 1px solid #ccc; border-radius: 5px; }
        input, select, button { width: 100%; padding: 8px; margin-bottom: 10px; box-sizing: border-box; }
        #rincian_biaya { border: 1px dashed #aaa; padding: 10px; margin-top: 15px; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Form Pemesanan Barang</h2>
        
        <form id="order-form">
            Nama Pelanggan: <input type="text" id="nama_pelanggan" value="Budi" required><br>
            Email: <input type="email" id="email_pelanggan" value="budi@example.com" required><br>
            No. HP: <input type="text" id="telepon_pelanggan" value="08123456789" required><br>
            <hr>
            Nama Barang: <input type="text" id="nama_barang" value="Buku Pemrograman" required><br>
            Harga Barang (Rp): <input type="number" id="harga_barang" value="150000" required><br>
            Berat (gram): <input type="number" id="berat" value="500" required><br>
            Provinsi Tujuan: <select id="provinsi" required></select><br>
            Kota Tujuan: <select id="kota" required></select><br>
            
            <button type="button" id="cek-ongkir-button">Cek Ongkos Kirim</button>
        </form>

        <div id="rincian_biaya" style="display:none;">
            <h3>Rincian Biaya</h3>
            <p>Harga Barang: <span id="detail-harga"></span></p>
            <p>Ongkos Kirim (JNE REG): <span id="detail-ongkir"></span></p>
            <hr>
            <p><strong>Total Bayar: <span id="detail-total"></span></strong></p>
            <button id="pay-button" style="display:none;">Lanjut ke Pembayaran</button>
        </div>
    </div>

    <script type="text/javascript">
        // --- LOGIKA RAJAONGKIR ---
        document.addEventListener("DOMContentLoaded", () => {
            const provinsiSelect = document.getElementById("provinsi");
            const kotaSelect = document.getElementById("kota");

            // Ambil data provinsi
            fetch("../api/get_provinces.php")
                .then(res => res.json())
                .then(data => {
                    data.rajaongkir.results.forEach(prov => {
                        provinsiSelect.innerHTML += `<option value="${prov.province_id}">${prov.province}</option>`;
                    });
                });

            // Ambil data kota saat provinsi dipilih
            provinsiSelect.addEventListener("change", () => {
                const province_id = provinsiSelect.value;
                kotaSelect.innerHTML = '<option>Memuat...</option>';
                fetch(`../api/get_cities.php?province_id=${province_id}`)
                    .then(res => res.json())
                    .then(data => {
                        kotaSelect.innerHTML = '';
                        data.rajaongkir.results.forEach(city => {
                            kotaSelect.innerHTML += `<option value="${city.city_id}">${city.city_name}</option>`;
                        });
                    });
            });
        });

        // --- LOGIKA CEK ONGKIR ---
        document.getElementById('cek-ongkir-button').onclick = function(e) {
            e.preventDefault();
            const destination = document.getElementById('kota').value;
            const weight = document.getElementById('berat').value;

            if (!destination || !weight) {
                alert('Pilih tujuan dan isi berat terlebih dahulu.');
                return;
            }

            let formData = new FormData();
            formData.append('destination', destination);
            formData.append('weight', weight);
            
            fetch('../api/get_cost.php', { method: 'POST', body: formData })
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        alert(data.error);
                        return;
                    }
                    
                    const ongkirData = data.rajaongkir.results[0].costs.find(c => c.service === 'REG'); // Ambil service REG
                    if (!ongkirData) {
                        alert('Layanan REG tidak tersedia untuk tujuan ini.');
                        return;
                    }
                    
                    const hargaBarang = parseInt(document.getElementById('harga_barang').value);
                    const ongkir = ongkirData.cost[0].value;
                    const totalBiaya = hargaBarang + ongkir;

                    document.getElementById('detail-harga').innerText = `Rp ${hargaBarang.toLocaleString()}`;
                    document.getElementById('detail-ongkir').innerText = `Rp ${ongkir.toLocaleString()}`;
                    document.getElementById('detail-total').innerText = `Rp ${totalBiaya.toLocaleString()}`;
                    
                    document.getElementById('rincian_biaya').style.display = 'block';
                    document.getElementById('pay-button').style.display = 'block';
                });
        };
        
        // --- LOGIKA MIDTRANS ---
        document.getElementById('pay-button').onclick = function(e) {
            e.preventDefault();
            let data = {
                nama_pelanggan: document.getElementById('nama_pelanggan').value,
                email_pelanggan: document.getElementById('email_pelanggan').value,
                telepon_pelanggan: document.getElementById('telepon_pelanggan').value,
                nama_barang: document.getElementById('nama_barang').value,
                harga_barang: document.getElementById('harga_barang').value,
                berat: document.getElementById('berat').value,
                destination: document.getElementById('kota').value
            };
            
            fetch('../charge.php', { 
                method: 'POST', 
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data) 
            })
            .then(response => response.json())
            .then(data => {
                if (data.token) {
                    window.snap.pay(data.token);
                } else {
                    alert(data.error || 'Gagal mendapatkan token pembayaran.');
                }
            });
        };
    </script>
</body>
</html>