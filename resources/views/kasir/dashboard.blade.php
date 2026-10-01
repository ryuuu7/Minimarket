<x-app-layout>

    <div class="pos-page" data-pos-app>
        <header class="pos-header">
            <div><p class="eyebrow">Point of Sale <span class="live-dot"></span> Kasir aktif</p><h1>Toko Retail Makmur</h1><p class="store-meta">Jl. Contoh No. 123, Jember <span>•</span> Telp. 0812-xxxx-xxxx</p></div>
            <div class="transaction-meta"><span>No. transaksi</span><strong id="transactionNumber">TRX-{{ now()->format('Ymd') }}-001</strong><time id="currentDate"></time></div>
        </header>
        <main class="pos-grid">
            <section class="workspace-column">
                <article class="pos-card add-product-card"><div class="card-heading"><div><span class="section-kicker">01 / Produk</span><h2>Tambah barang</h2></div><kbd>F2</kbd></div><div class="search-row"><div class="search-field"><span aria-hidden="true">⌕</span><input id="searchProduct" type="search" placeholder="Cari nama, kategori, atau kode barang..." autocomplete="off"><div class="product-results" id="productResults"></div></div><button class="primary-button" type="button" id="searchButton">Cari</button></div><div class="customer-row"><label>Pelanggan<select><option>Umum</option><option>Pelanggan Member</option><option>Member VIP</option></select></label><label>No. member / HP<input type="text" placeholder="Opsional"></label></div></article>
                <article class="pos-card cart-card"><div class="card-heading"><div><span class="section-kicker">02 / Belanja</span><h2>Keranjang belanja</h2></div><span class="item-count" id="itemCount">0 item</span></div><div class="cart-table-wrap"><table class="cart-table"><thead><tr><th>#</th><th>Barang</th><th>Harga</th><th>Qty</th><th class="align-right">Subtotal</th><th></th></tr></thead><tbody id="cartBody"></tbody></table></div></article>
                <div class="operator-note"><span class="status-pill">● Siap melayani</span><span>Kasir: {{ auth()->user()->name }}</span><span class="shortcut-note"><kbd>F4</kbd> Bayar <kbd>ESC</kbd> Batal</span></div>
            </section>
            <aside class="payment-column">
                <article class="pos-card summary-card"><div class="card-heading"><div><span class="section-kicker">03 / Ringkasan</span><h2>Ringkasan pembayaran</h2></div><span>▤</span></div><div class="summary-list"><div><span>Total item</span><strong id="totalQty">0</strong></div><div><span>Subtotal</span><strong id="subtotal">Rp 0</strong></div><label><span>Diskon (%)</span><input id="discountPercent" type="number" min="0" max="100" value="0"></label><label><span>Diskon (Rp)</span><input id="discountAmount" type="number" min="0" value="0"></label><label><span>Pajak / PPN</span><input id="tax" type="number" min="0" value="0"></label><label><span>Biaya lain</span><input id="otherFee" type="number" min="0" value="0"></label></div><div class="grand-total"><span>Total akhir</span><strong id="grandTotal">Rp 0</strong></div></article>
                <article class="pos-card payment-card"><div class="payment-label"><div><span class="section-kicker">04 / Pembayaran</span><h2>Uang dibayar</h2></div><kbd>F4</kbd></div><input id="payment" class="payment-input" type="number" min="0" placeholder="0"><div class="quick-payments"><button type="button" data-quick-pay="exact">Uang pas</button><button type="button" data-quick-pay="40000">Rp 40.000</button><button type="button" data-quick-pay="50000">Rp 50.000</button><button type="button" data-quick-pay="100000">Rp 100.000</button></div><div class="payment-label method-label"><span>Metode pembayaran</span></div><div class="payment-method" id="paymentMethod"><button type="button" class="active">Tunai</button><button type="button">QRIS</button><button type="button">Debit</button><button type="button">Kredit</button><button type="button">E-Wallet</button><button type="button">Transfer</button></div><div class="change-box" id="changeBox"><span id="changeLabel">Kembalian</span><strong id="change">Rp 0</strong></div><div class="action-area"><button class="hold-button" type="button" id="holdButton">Tahan</button><button class="cancel-button" type="button" id="cancelButton">Batal</button><button class="pay-button" type="button" id="payButton">Bayar &amp; cetak <span>→</span></button></div></article>
            </aside>
        </main>
    </div>
    <div class="receipt-modal" id="receiptModal" aria-hidden="true">
        <div class="receipt-dialog" role="dialog" aria-modal="true" aria-labelledby="receiptTitle">
            <div class="receipt-toolbar"><span>Transaksi selesai</span><button type="button" id="closeReceipt" aria-label="Tutup struk">×</button></div>
            <div class="receipt-paper" id="receiptPaper">
                <div class="receipt-brand"><span class="receipt-mark">RM</span><h2 id="receiptTitle">Toko Retail Makmur</h2><p>Jl. Contoh No. 123, Jember</p><p>Telp. 0812-xxxx-xxxx</p></div>
                <div class="receipt-meta"><span id="receiptNumber"></span><span id="receiptDate"></span><span>Kasir: {{ auth()->user()->name }}</span></div>
                <div class="receipt-items" id="receiptItems"></div>
                <div class="receipt-totals"><div><span>Subtotal</span><strong id="receiptSubtotal"></strong></div><div><span>Diskon</span><strong id="receiptDiscount"></strong></div><div><span>Pajak / biaya</span><strong id="receiptFees"></strong></div><div class="receipt-grand"><span>Total</span><strong id="receiptTotal"></strong></div></div>
                <div class="receipt-payment"><div><span id="receiptMethod">Tunai</span><strong id="receiptPaid"></strong></div><div><span>Kembalian</span><strong id="receiptChange"></strong></div></div>
                <p class="receipt-thanks">Terima kasih sudah berbelanja</p>
            </div>
            <div class="receipt-actions"><button type="button" class="secondary-button" id="closeReceiptAction">Tutup</button><button type="button" class="pay-button" id="printReceipt">Cetak struk <span>↗</span></button><button type="button" class="new-transaction-button" id="newTransaction">Transaksi baru</button></div>
        </div>
    </div>
    <script>window.POS_PRODUCTS = @json($products);</script>
</x-app-layout>
