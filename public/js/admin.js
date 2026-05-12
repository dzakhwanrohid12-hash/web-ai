function fetchRealTimeData(page = 1) {
    let searchQuery = document.getElementById("search").value;
    let kendaraanFilter = document.getElementById("filter_kendaraan").value;
    let hariFilter = document.getElementById("filter_hari").value;

    // window.adminDashboardUrl dikirim dari Blade
    let url = new URL(window.adminDashboardUrl);
    url.searchParams.append("search", searchQuery);
    url.searchParams.append("kendaraan", kendaraanFilter);
    url.searchParams.append("hari", hariFilter);
    url.searchParams.append("page", page);

    fetch(url, {
        method: "GET",
        headers: { "X-Requested-With": "XMLHttpRequest", Accept: "text/html" },
    })
        .then((response) => response.text())
        .then((html) => {
            document.getElementById("table-data").innerHTML = html;
        })
        .catch((error) => console.error("Gagal memuat data:", error));
}

// Event Listeners jika elemen form ada
const searchInput = document.getElementById("search");
if (searchInput) {
    searchInput.addEventListener("input", () => fetchRealTimeData(1));
    document
        .getElementById("filter_kendaraan")
        .addEventListener("change", () => fetchRealTimeData(1));
    document
        .getElementById("filter_hari")
        .addEventListener("change", () => fetchRealTimeData(1));

    document.addEventListener("click", function (event) {
        let paginationLink = event.target.closest(".pagination a");
        if (paginationLink) {
            event.preventDefault();
            let url = new URL(paginationLink.href);
            fetchRealTimeData(url.searchParams.get("page"));
        }
    });

    setInterval(function () {
        if (document.activeElement !== searchInput) {
            let activePage =
                document.querySelector(".pagination .active span") ||
                document.querySelector(".pagination .active");
            fetchRealTimeData(activePage ? activePage.innerText.trim() : 1);
        }
    }, 10000);
}
function exportPDF() {
    let searchQuery = document.getElementById("search").value;
    let kendaraanFilter = document.getElementById("filter_kendaraan").value;
    let hariFilter = document.getElementById("filter_hari").value;

    // Buka tab baru menuju route PDF sambil membawa parameter filter saat ini
    let url = `/admin/history/export-pdf?search=${searchQuery}&kendaraan=${kendaraanFilter}&hari=${hariFilter}`;
    window.open(url, "_blank");
}

// Modal Admin
function showDetail(buttonElement) {
    let kondisi = buttonElement.getAttribute("data-kondisi");
    let rawListData = buttonElement.getAttribute("data-list");
    let listData =
        rawListData && rawListData !== "null" ? JSON.parse(rawListData) : {};

    // 1. Tampilkan dan Warnai Kondisi
    let kondisiEl = document.getElementById("modalKondisi");
    kondisiEl.innerText = kondisi;
    if (kondisi.toLowerCase() === "sepi") {
        kondisiEl.style.color = "#10B981";
    } else if (kondisi.toLowerCase() === "sedang") {
        kondisiEl.style.color = "#F59E0B";
    } else {
        kondisiEl.style.color = "#EF4444";
    }

    // 2. Render List Rekomendasi
    let htmlRekomendasi =
        listData.Rekomendasi && listData.Rekomendasi.length > 0
            ? listData.Rekomendasi.map(
                  (loc) => `<li style="margin-bottom:5px;">${loc}</li>`,
              ).join("")
            : '<li style="color: #9CA3AF; list-style:none; margin-left:-18px;">Tidak ada area sepi</li>';
    document.getElementById("modalRekomendasi").innerHTML = htmlRekomendasi;

    // 3. Render List Alternatif
    let htmlAlternatif =
        listData.Alternatif && listData.Alternatif.length > 0
            ? listData.Alternatif.map(
                  (loc) => `<li style="margin-bottom:5px;">${loc}</li>`,
              ).join("")
            : '<li style="color: #9CA3AF; list-style:none; margin-left:-18px;">Tidak ada area sedang</li>';
    document.getElementById("modalAlternatif").innerHTML = htmlAlternatif;

    // 4. Render List Hindari (Baru)
    let htmlHindari =
        listData.Hindari && listData.Hindari.length > 0
            ? listData.Hindari.map(
                  (loc) => `<li style="margin-bottom:5px;">${loc}</li>`,
              ).join("")
            : '<li style="color: #9CA3AF; list-style:none; margin-left:-18px;">Tidak ada area padat</li>';
    let modalHindariEl = document.getElementById("modalHindari");
    if (modalHindariEl) modalHindariEl.innerHTML = htmlHindari;

    // 5. Tampilkan Modal
    document.getElementById("detailModal").style.display = "block";
}

function closeModal() {
    document.getElementById("detailModal").style.display = "none";
}
window.onclick = function (event) {
    let modal = document.getElementById("detailModal");
    if (event.target == modal) {
        modal.style.display = "none";
    }
};

function confirmDelete(userId, userName) {
    // Pesan konfirmasi diperjelas
    if (
        confirm(
            `Apakah Anda yakin ingin menghapus akun "${userName}" ?\n\nCatatan: Riwayat pencarian pengguna ini tidak akan hilang, melainkan diubah menjadi status 'Guest' demi menjaga keutuhan data AI.`,
        )
    ) {
        document.getElementById(`delete-form-${userId}`).submit();
    }
}
