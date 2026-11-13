document.addEventListener("click", async (e) => {
    const btn = e.target.closest(".js-fav");
    if (!btn) return;
    btn.disabled = true;
    try {
        const res = await fetch(btn.dataset.url, {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": document.querySelector(
                    'meta[name="csrf-token"]',
                ).content,
                Accept: "application/json",
            },
        });
        if (!res.ok) return;
        const { saved } = await res.json();
        btn.textContent = saved ? "Đã lưu" : "Lưu";
        btn.classList.toggle("btn-danger", saved);
        btn.classList.toggle("btn-outline-danger", !saved);
    } finally {
        btn.disabled = false;
    }
});
