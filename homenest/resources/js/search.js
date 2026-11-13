const province = document.getElementById("province_id");
const district = document.getElementById("district_id");

async function loadDistricts(provinceId, selectedId = null) {
    district.innerHTML = '<option value="">Quận / Huyện</option>';

    if (!provinceId) return;

    const res = await fetch(`${province.dataset.url}/${provinceId}/districts`);

    if (!res.ok) {
        console.error("Không tải được quận/huyện:", res.status);
        return;
    }

    const districts = await res.json();

    districts.forEach((d) => {
        district.add(
            new Option(
                d.name,
                d.district_id,
                false,
                String(d.district_id) === String(selectedId),
            ),
        );
    });
}

if (province && district) {
    province.addEventListener("change", () => {
        loadDistricts(province.value);
    });

    if (province.value) {
        loadDistricts(province.value, district.dataset.selected);
    }
}
