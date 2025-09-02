import "./bootstrap";

import Alpine from "alpinejs";

import jszip from "jszip";
import pdfmake from "pdfmake";

// // Vanilla core
// import DataTable from "datatables.net";

// // Ekstensi (tanpa -dt)
// import "datatables.net-buttons";
// import "datatables.net-buttons/js/buttons.colVis.mjs";
// import "datatables.net-buttons/js/buttons.html5.mjs";
// import "datatables.net-buttons/js/buttons.print.mjs";
// import "datatables.net-colreorder";
// import "datatables.net-columncontrol";
// import "datatables.net-datetime"; // DateTime
// import "datatables.net-fixedcolumns";
// import "datatables.net-fixedheader";
// import "datatables.net-keytable";
// import "datatables.net-responsive";
// import "datatables.net-rowgroup";
// import "datatables.net-rowreorder";
// import "datatables.net-scroller";
// import "datatables.net-searchbuilder";
// import "datatables.net-searchpanes";
// import "datatables.net-select";

// // Adapter Tailwind (harus setelah core/extensi)
// import "datatables-tailwind-adapter";

// // (opsional) dependensi Buttons export
// import "jszip"; // untuk Excel
// import "pdfmake/build/pdfmake";
// import "pdfmake/build/vfs_fonts";

// DataTable.Buttons.jszip(jszip);
// DataTable.Buttons.pdfMake(pdfmake);

// // Expose for other entry files (e.g., Tables.js)
// if (typeof window !== "undefined") {
//     window.DataTable = DataTable;
// }

window.Alpine = Alpine;
Alpine.start();

// import * as bootstrap from "bootstrap";
// import jQuery from "jquery";
// import jszip from "jszip";
// import pdfmake from "pdfmake";
// import DataTable from "datatables.net-bs5";
// import "datatables.net-buttons-bs5";
// import "datatables.net-buttons/js/buttons.colVis.mjs";
// import "datatables.net-buttons/js/buttons.html5.mjs";
// import "datatables.net-buttons/js/buttons.print.mjs";
// import "datatables.net-colreorder-bs5";
// import "datatables.net-columncontrol-bs5";
// import DateTime from "datatables.net-datetime";
// import "datatables.net-fixedcolumns-bs5";
// import "datatables.net-fixedheader-bs5";
// import "datatables.net-keytable-bs5";
// import "datatables.net-responsive-bs5";
// import "datatables.net-rowgroup-bs5";
// import "datatables.net-rowreorder-bs5";
// import "datatables.net-scroller-bs5";
// import "datatables.net-searchbuilder-bs5";
// import "datatables.net-searchpanes-bs5";
// import "datatables.net-select-bs5";

// DataTable.use(bootstrap);
// DataTable.Buttons.jszip(jszip);
// DataTable.Buttons.pdfMake(pdfmake);

// let table = new DataTable("#tabelBarang");
// $("#tabelBarang").DataTable();
