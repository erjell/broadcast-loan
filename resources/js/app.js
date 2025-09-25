import "./bootstrap";

import Alpine from "alpinejs";
import JsBarcode from "jsbarcode";
import jszip from "jszip";
import pdfmake from "pdfmake";

window.Alpine = Alpine;
Alpine.start();

window.JsBarcode = JsBarcode;

// Simple global function to toggle all checkboxes by class
window.toggleAllCheckboxes = function (source) {
    const checkboxes = document.querySelectorAll(".itemCheckbox");
    checkboxes.forEach((checkbox) => {
        checkbox.checked = source.checked;
    });
};
