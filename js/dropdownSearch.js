document
    .getElementById('customSearchInput')
    .addEventListener('input', function () {
        var inputValue = this.value.trim();
        if (inputValue) {
            searchDatabase(inputValue);
        } else {
            clearDropdown();
        }
    });

document
    .getElementById('customSearchBtn')
    .addEventListener('click', function () {
        var inputValue = document
            .getElementById('customSearchInput')
            .value.trim();
        searchDatabase(inputValue);
    });

document
    .getElementById('customClearBtn')
    .addEventListener('click', function () {
        document.getElementById('customSearchInput').value = '';
        clearDropdown();
    });

document.addEventListener('click', function (event) {
    if (!event.target.closest('.custom-dropdown-search')) {
        document.getElementById('customDropdownMenu').style.display = 'none';
    }
});

function searchDatabase(query) {
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'php/searchCustomer.php', true); // Point to your PHP script
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function () {
        if (xhr.status === 200) {
            var response = JSON.parse(xhr.responseText);
            if (response.status === 'no_data') {
                showNoDataFound(response.query);
            } else {
                populateDropdown(response);
            }
        }
    };
    xhr.send('query=' + encodeURIComponent(query));
}

function populateDropdown(items) {
    var dropdown = document.getElementById('customDropdownMenu');
    dropdown.innerHTML = '';
    dropdown.style.display = 'block';

    items.forEach(function (item) {
        var a = document.createElement('a');
        a.href = '#';
        a.className = 'custom-dropdown-item';
        a.textContent = item.nama; // Assuming the database returns an object with a 'name' property
        a.addEventListener('click', function () {
            addInputValue(item.nama, item.id);
            dropdown.style.display = 'none';
        });
        dropdown.appendChild(a);
    });
}

function addInputValue(nama, id) {
    var input = document.getElementById('customSearchInput');
    input.value = nama;
    input.setAttribute('readonly', true);
    document.getElementById('customer_id').value = id;
    document.getElementById('customClearBtn').style.display = 'block';
}

function showNoDataFound(query) {
    var dropdown = document.getElementById('customDropdownMenu');
    dropdown.innerHTML = ''; // Clear previous content
    dropdown.style.display = 'block';

    var noDataText = document.createElement('div');
    noDataText.className = 'no-data-found';
    noDataText.textContent = 'Data tidak ditemukan';

    var addButton = document.createElement('button');
    addButton.className = 'btn btn-primary btm-sm d-block mx-auto mb-3';
    addButton.textContent = `Tambah "${query}"`;
    addButton.addEventListener('click', function () {
        addData(query);
    });

    dropdown.appendChild(noDataText);
    dropdown.appendChild(addButton);
}

function addData(query) {
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'php/addCustomer.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function () {
        if (xhr.status === 200) {
            var response = JSON.parse(xhr.responseText);
            addInputValue(response.nama, response.id);
            document.getElementById('customDropdownMenu').style.display =
                'none';
        }
    };
    xhr.send('nama=' + encodeURIComponent(query));
}

function clearDropdown() {
    document.getElementById('customSearchInput').value = '';
    document.getElementById('customSearchInput').removeAttribute('readonly');
    document.getElementById('customDropdownMenu').innerHTML = '';
    document.getElementById('customDropdownMenu').style.display = 'none';
    document.getElementById('customClearBtn').style.display = 'none';
}
