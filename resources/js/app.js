import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

const posApp = document.querySelector('[data-pos-app]');

if (posApp) {
    /* ============================================
       SAMPLE PRODUCT DATA
    ============================================ */
    const products = (window.POS_PRODUCTS ?? []).map((product) => ({
        ...product,
        code: `BRG${String(product.id).padStart(3, '0')}`,
        barcode: `899000000${product.id}`,
    }));

    /* ============================================
       FORMAT RUPIAH
    ============================================ */
    function formatRupiah(value) {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            maximumFractionDigits: 0,
        }).format(value);
    }

    /* ============================================
       SEARCH PRODUCT
    ============================================ */
    const searchInput = document.getElementById('searchProduct');
    const productResults = document.getElementById('productResults');

    function searchProduct() {
        const keyword = searchInput.value.toLowerCase().trim();

        if (!keyword) {
            productResults.style.display = 'none';
            return;
        }

        const results = products.filter((product) => (
            product.name.toLowerCase().includes(keyword)
            || product.category?.toLowerCase().includes(keyword)
            || product.code.toLowerCase().includes(keyword)
            || product.barcode.includes(keyword)
        )).slice(0, 6);

        productResults.innerHTML = results.length
            ? results.map((product) => `
                <div class="product-item" data-product-id="${product.id}">
                    <div>
                        <div class="product-name">${product.name}</div>
                        <div class="product-code">${product.code} · ${product.category || 'Produk'} · Stok ${product.stock}</div>
                    </div>
                    <div class="product-price">${formatRupiah(product.price)}</div>
                </div>
            `).join('')
            : '<div class="product-item">Barang tidak ditemukan</div>';

        productResults.style.display = 'block';
    }

    /* ============================================
       CART
    ============================================ */
    let cart = [];

    function addToCart(productId) {
        const product = products.find((item) => item.id === Number(productId));
        if (!product) return;

        const existing = cart.find((item) => item.id === product.id);
        if (existing) {
            existing.qty = Math.min(existing.qty + 1, product.stock);
        } else {
            cart.push({ ...product, qty: 1 });
        }

        searchInput.value = '';
        productResults.style.display = 'none';
        renderCart();
    }

    function renderCart() {
        const cartBody = document.getElementById('cartBody');

        if (cart.length === 0) {
            cartBody.innerHTML = `
                <tr>
                    <td colspan="6" class="empty-cart">
                        <strong>Keranjang masih kosong</strong>
                        Silakan cari atau scan barang.
                    </td>
                </tr>
            `;
        } else {
            cartBody.innerHTML = cart.map((item, index) => `
                <tr>
                    <td>${index + 1}</td>
                    <td>
                        <strong>${item.name}</strong>
                        <div class="product-code">${item.code} · Stok ${item.stock}</div>
                    </td>
                    <td>${formatRupiah(item.price)}</td>
                    <td>
                        <div class="qty-control">
                            <button type="button" data-action="decrease" data-id="${item.id}">−</button>
                            <input type="number" value="${item.qty}" min="1" max="${item.stock}" data-action="quantity" data-id="${item.id}">
                            <button type="button" data-action="increase" data-id="${item.id}">+</button>
                        </div>
                    </td>
                    <td class="align-right"><strong>${formatRupiah(item.price * item.qty)}</strong></td>
                    <td><button class="remove-button" type="button" data-action="remove" data-id="${item.id}">×</button></td>
                </tr>
            `).join('');
        }

        calculateTotal();
    }

    function changeQty(id, amount) {
        const item = cart.find((cartItem) => cartItem.id === Number(id));
        if (!item) return;

        item.qty += amount;
        if (item.qty <= 0) {
            removeItem(id);
            return;
        }

        item.qty = Math.min(item.qty, item.stock);
        renderCart();
    }

    function updateQty(id, qty) {
        const item = cart.find((cartItem) => cartItem.id === Number(id));
        if (!item) return;

        item.qty = Math.min(item.stock, Math.max(1, parseInt(qty, 10) || 1));
        renderCart();
    }

    function removeItem(id) {
        cart = cart.filter((item) => item.id !== Number(id));
        renderCart();
    }

    /* ============================================
       CALCULATE TOTAL
    ============================================ */
    function calculateTotal() {
        let totalQty = 0;
        let subtotal = 0;

        cart.forEach((item) => {
            totalQty += item.qty;
            subtotal += item.price * item.qty;
        });

        const discountPercent = parseFloat(document.getElementById('discountPercent').value) || 0;
        const discountAmount = parseFloat(document.getElementById('discountAmount').value) || 0;
        const tax = parseFloat(document.getElementById('tax').value) || 0;
        const otherFee = parseFloat(document.getElementById('otherFee').value) || 0;
        const percentDiscount = subtotal * (discountPercent / 100);
        const grandTotal = Math.max(0, subtotal - percentDiscount - discountAmount + tax + otherFee);

        document.getElementById('totalQty').textContent = totalQty;
        document.getElementById('itemCount').textContent = `${totalQty} item`;
        document.getElementById('subtotal').textContent = formatRupiah(subtotal);
        document.getElementById('grandTotal').textContent = formatRupiah(grandTotal);
        calculateChange();
    }

    /* ============================================
       CALCULATE CHANGE
    ============================================ */
    function calculateChange() {
        const grandTotal = getGrandTotal();
        const payment = parseFloat(document.getElementById('payment').value) || 0;
        const change = payment - grandTotal;
        const changeBox = document.getElementById('changeBox');

        document.getElementById('change').textContent = formatRupiah(Math.abs(change));
        document.getElementById('changeLabel').textContent = change >= 0 ? 'Kembalian' : 'Uang kurang';
        changeBox.classList.toggle('short-payment', change < 0);
    }

    /* ============================================
       GET GRAND TOTAL
    ============================================ */
    function getGrandTotal() {
        let subtotal = 0;
        cart.forEach((item) => { subtotal += item.price * item.qty; });

        const discountPercent = parseFloat(document.getElementById('discountPercent').value) || 0;
        const discountAmount = parseFloat(document.getElementById('discountAmount').value) || 0;
        const tax = parseFloat(document.getElementById('tax').value) || 0;
        const otherFee = parseFloat(document.getElementById('otherFee').value) || 0;
        const discount = (subtotal * discountPercent) / 100 + discountAmount;

        return Math.max(0, subtotal - discount + tax + otherFee);
    }

    /* ============================================
       PAYMENT METHOD
    ============================================ */
    function selectPayment(button) {
        document.querySelectorAll('.payment-method button').forEach((item) => item.classList.remove('active'));
        button.classList.add('active');
    }

    /* ============================================
       RECEIPT
    ============================================ */
    function openReceipt(payment) {
        const subtotal = cart.reduce((sum, item) => sum + item.price * item.qty, 0);
        const discount = subtotal * (parseFloat(document.getElementById('discountPercent').value) || 0) / 100
            + (parseFloat(document.getElementById('discountAmount').value) || 0);
        const fees = (parseFloat(document.getElementById('tax').value) || 0)
            + (parseFloat(document.getElementById('otherFee').value) || 0);
        const total = getGrandTotal();
        const method = document.querySelector('.payment-method button.active')?.textContent || 'Tunai';

        document.getElementById('receiptNumber').textContent = document.getElementById('transactionNumber').textContent;
        document.getElementById('receiptDate').textContent = new Date().toLocaleString('id-ID', { dateStyle: 'medium', timeStyle: 'short' });
        document.getElementById('receiptItems').innerHTML = cart.map((item) => `
            <div class="receipt-item">
                <div><strong>${item.name}</strong><span>${item.qty} x ${formatRupiah(item.price)}</span></div>
                <strong>${formatRupiah(item.price * item.qty)}</strong>
            </div>
        `).join('');
        document.getElementById('receiptSubtotal').textContent = formatRupiah(subtotal);
        document.getElementById('receiptDiscount').textContent = discount ? `- ${formatRupiah(discount)}` : formatRupiah(0);
        document.getElementById('receiptFees').textContent = formatRupiah(fees);
        document.getElementById('receiptTotal').textContent = formatRupiah(total);
        document.getElementById('receiptMethod').textContent = method;
        document.getElementById('receiptPaid').textContent = formatRupiah(payment);
        document.getElementById('receiptChange').textContent = formatRupiah(payment - total);
        document.getElementById('receiptModal').classList.add('is-open');
        document.getElementById('receiptModal').setAttribute('aria-hidden', 'false');
    }

    /* ============================================
       HOLD TRANSACTION
    ============================================ */
    function holdTransaction() {
        alert(cart.length ? 'Transaksi berhasil ditahan.' : 'Tidak ada transaksi untuk ditahan.');
    }

    /* ============================================
       PAYMENT
    ============================================ */
    function processPayment() {
        if (cart.length === 0) {
            alert('Keranjang masih kosong.');
            return;
        }

        const total = getGrandTotal();
        const payment = parseFloat(document.getElementById('payment').value) || 0;
        if (payment < total) {
            alert('Uang pembayaran masih kurang.');
            return;
        }

        openReceipt(payment);
    }

    /* ============================================
       CANCEL TRANSACTION
    ============================================ */
    function cancelTransaction() {
        if (cart.length && !confirm('Batalkan transaksi ini?')) return;

        cart = [];
        document.getElementById('payment').value = '';
        ['discountPercent', 'discountAmount', 'tax', 'otherFee'].forEach((id) => {
            document.getElementById(id).value = 0;
        });
        renderCart();
    }

    function closeReceipt() {
        document.getElementById('receiptModal').classList.remove('is-open');
        document.getElementById('receiptModal').setAttribute('aria-hidden', 'true');
    }

    function newTransaction() {
        cancelTransaction();
        closeReceipt();
        searchInput.focus();
    }

    /* ============================================
       EVENTS AND INITIAL
    ============================================ */
    searchInput.addEventListener('input', searchProduct);
    document.getElementById('searchButton').addEventListener('click', searchProduct);
    document.getElementById('payment').addEventListener('input', calculateChange);
    ['discountPercent', 'discountAmount', 'tax', 'otherFee'].forEach((id) => {
        document.getElementById(id).addEventListener('input', calculateTotal);
    });
    productResults.addEventListener('click', (event) => {
        const product = event.target.closest('[data-product-id]');
        if (product) addToCart(product.dataset.productId);
    });
    document.getElementById('cartBody').addEventListener('click', (event) => {
        const button = event.target.closest('[data-action]');
        if (!button) return;
        if (button.dataset.action === 'increase') changeQty(button.dataset.id, 1);
        if (button.dataset.action === 'decrease') changeQty(button.dataset.id, -1);
        if (button.dataset.action === 'remove') removeItem(button.dataset.id);
    });
    document.getElementById('cartBody').addEventListener('change', (event) => {
        if (event.target.dataset.action === 'quantity') updateQty(event.target.dataset.id, event.target.value);
    });
    document.querySelectorAll('.payment-method button').forEach((button) => button.addEventListener('click', () => selectPayment(button)));
    document.querySelectorAll('[data-quick-pay]').forEach((button) => button.addEventListener('click', () => {
        document.getElementById('payment').value = button.dataset.quickPay === 'exact' ? getGrandTotal() : button.dataset.quickPay;
        calculateChange();
    }));
    document.getElementById('holdButton').addEventListener('click', holdTransaction);
    document.getElementById('cancelButton').addEventListener('click', cancelTransaction);
    document.getElementById('payButton').addEventListener('click', processPayment);
    document.getElementById('closeReceipt').addEventListener('click', closeReceipt);
    document.getElementById('closeReceiptAction').addEventListener('click', closeReceipt);
    document.getElementById('newTransaction').addEventListener('click', newTransaction);
    document.getElementById('printReceipt').addEventListener('click', () => window.print());
    document.addEventListener('keydown', (event) => {
        if (event.key === 'F2') { event.preventDefault(); searchInput.focus(); }
        if (event.key === 'F4') { event.preventDefault(); document.getElementById('payment').focus(); }
        if (event.key === 'Escape') closeReceipt();
    });
    document.getElementById('currentDate').textContent = new Date().toLocaleString('id-ID', { dateStyle: 'medium', timeStyle: 'short' });
    renderCart();
}
