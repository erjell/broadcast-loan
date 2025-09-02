new DataTable("#tabelBarang", {
    responsive: true,
    columnControl: [["orderAsc", "orderDesc", "search"]],
    layout: {
        top1: [
            {
                // buttons: ["excel", "pdf"],
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
});
