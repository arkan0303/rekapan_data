const buttonTambah = document.getElementById('tambah_produk');
const productTable = document.getElementById('product_table');
const productSelect = document.getElementById('product_select');
const formPenjualan = document.getElementById('form_penjualan');
const buttonSubmit = formPenjualan.querySelector("button[type='submit']");
const buttonReset = formPenjualan.querySelector("button[type='reset']");

const INITIAL_EMPTY_DATA_TEXT = 'Tidak ada data.';
const DEFAULT_CELL_CLASS_NAME = 'text-center align-middle';
const TOTAL_COLUMNS = 5;

formPenjualan.onsubmit = (e) => {
    e.preventDefault();
};

buttonTambah.addEventListener('click', insertProductTable);
formPenjualan.addEventListener('reset', resetAll);
buttonSubmit.addEventListener('click', submitPenjualan);

function submitPenjualan() {
    const namaPembeli = formPenjualan.querySelector(
        "input[name='nama_pembeli']"
    ).value;
    const tanggalOrder = formPenjualan.querySelector(
        "input[name='tanggal_order']"
    ).value;
    let totalHarga = document.getElementById('total_harga_produk').textContent;
    totalHarga = totalHarga.replace(/[^\d]/g, '');
}

function resetAll() {
    productTable.querySelector('tbody').innerHTML = '';
    recreateInitialRow();
}

function insertProductTable() {
    const selectedOpt = productSelect.options[productSelect.selectedIndex];
    const tbody = productTable.querySelector('tbody');

    if (tbody.rows[0].cells[0].textContent === INITIAL_EMPTY_DATA_TEXT) {
        tbody.innerHTML = '';
    }

    const existingRows = tbody.rows;
    let existingRow = null;

    for (let i = 0; i < existingRows.length; i++) {
        const row = existingRows[i];
        const productId = row.querySelector("input[name='id_produk']");
        if (productId.value === selectedOpt.value) {
            existingRow = row;
            break;
        }
    }

    if (existingRow) {
        const qtyCell = existingRow.querySelector("input[name='qty_produk']");
        const hargaCell = existingRow.querySelector(
            "td[data-price-column='true']"
        );

        const currentQty = parseInt(qtyCell.value);
        const harga = parseInt(hargaCell.getAttribute('data-harga'));
        const qty = currentQty + 1;
        qtyCell.value = qty;
        updateTotalHarga();
        return;
    }

    const newRow = productTable.tBodies[0].insertRow();
    const newCells = [];

    for (let i = 0; i < productTable.rows[0].cells.length; i++) {
        const newCell = newRow.insertCell();
        newCells.push(newCell);
    }

    const idx = tbody.rows.length;

    newCells[0].className = DEFAULT_CELL_CLASS_NAME;
    newCells[0].textContent = idx;

    newCells[1].className = DEFAULT_CELL_CLASS_NAME;
    newCells[1].appendChild(
        createInput('text', 'nama_produk', idx, selectedOpt.text, true)
    );
    newCells[1].appendChild(
        createInput('hidden', 'id_produk', idx, selectedOpt.value, true)
    );

    const harga = selectedOpt.getAttribute('data-harga');
    const ppn = harga * 0.11; // harga * 11%
    const hargaPpn = parseInt(harga) + parseInt(ppn);

    newCells[2].className = DEFAULT_CELL_CLASS_NAME;
    newCells[2].setAttribute('data-harga', hargaPpn);
    newCells[2].setAttribute('data-price-column', true);
    newCells[2].appendChild(
        createInput(
            'text',
            'harga',
            idx,
            hargaPpn.toLocaleString('id-ID', {
                style: 'currency',
                currency: 'IDR',
            }),
            true
        )
    );

    newCells[3].className = DEFAULT_CELL_CLASS_NAME;
    newCells[3].setAttribute('data-qty', true);
    newCells[3].appendChild(
        createInput('number', 'qty_produk', idx, 1, false, updateTotalHarga)
    );

    updateTotalHarga();

    newCells[4].className = DEFAULT_CELL_CLASS_NAME;
    const deleteBtn = document.createElement('button');
    deleteBtn.addEventListener('click', deleteRow);
    deleteBtn.textContent = 'Delete';
    deleteBtn.className = 'btn btn-outline-danger btn-sm';
    newCells[4].appendChild(deleteBtn);
}

function createInput(type, name, idx, value, readonly, onchange) {
    const input = document.createElement('input');
    input.readOnly = readonly;
    input.name = name;
    input.id = name + idx;
    input.value = value;
    input.className = 'form-control';
    input.type = type;
    if (onchange) {
        input.addEventListener('change', onchange);
    }
    return input;
}

function deleteRow() {
    const tbody = productTable.querySelector('tbody');
    const row = this.closest('tr');
    row.remove();

    updateTotalHarga();
    updateRowNumbers();

    if (tbody.rows.length == 0) {
        recreateInitialRow();
    }
}

function updateTotalHarga() {
    const tbody = productTable.querySelector('tbody');
    const totalHargaTag = document.getElementById('total_harga_produk');

    let prices = [];

    tbody.querySelectorAll('tr').forEach((tr) => {
        let harga = 0;
        let qty = 0;
        tr.querySelectorAll('td').forEach((td) => {
            if (td.hasAttribute('data-harga')) {
                harga = td.getAttribute('data-harga');
            }

            if (td.hasAttribute('data-qty')) {
                qty = td.querySelector("input[name='qty_produk']").value;
            }
        });
        let price = parseInt(harga) * parseInt(qty);
        prices.push(price);
    });

    console.log(prices);

    const totalHarga = prices.reduce((acc, curr) => acc + curr, 0);
    totalHargaTag.textContent = totalHarga.toLocaleString('id-ID', {
        style: 'currency',
        currency: 'IDR',
    });
}

function updateRowNumbers() {
    const tbody = productTable.querySelector('tbody');
    const rows = tbody.querySelectorAll('tr');

    rows.forEach((row, index) => {
        const cellNo = row.cells[0];
        cellNo.textContent = index + 1;
    });
}

function recreateInitialRow() {
    const tbody = productTable.querySelector('tbody');
    const newRow = tbody.insertRow();
    const cell = newRow.insertCell();
    cell.colSpan = TOTAL_COLUMNS;
    cell.className = DEFAULT_CELL_CLASS_NAME + ' fw-bold';
    cell.textContent = INITIAL_EMPTY_DATA_TEXT;
}
