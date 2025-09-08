new DataTable("#tabelBarang", {
    responsive: true,
    columnControl: [["search"]],
    layout: {
        top1: [
            {
                buttons: ["excel", "pdf"],
            },
            {
                div: {
                    className: "layout-full",
                    text: "",
                },
            },
            {
                div: {
                    className: "layout-full",
                    text: "",
                },
            },
        ],
    },
});
new DataTable("#tabelPeminjaman", {
    responsive: true,
    order: [[2, "desc"]],
});

new DataTable("#tabelKategori", {
    responsive: true,
});
