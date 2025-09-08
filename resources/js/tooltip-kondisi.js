// Tooltip enhancer for "Kondisi" cells using the latest "Catatan" text.
// Pure CSS tooltip via data attribute; CSS injected once by JS.

(function(){
  function injectTooltipCssOnce() {
    if (document.getElementById('tooltip-css-injected')) return;
    const style = document.createElement('style');
    style.id = 'tooltip-css-injected';
    style.type = 'text/css';
    style.appendChild(document.createTextNode([
      '[data-tooltip]{ position: relative; cursor: help; }',
      '[data-tooltip]::after{',
      '  content: attr(data-tooltip);',
      '  position: absolute;',
      '  left: 50%; transform: translateX(-50%);',
      '  bottom: calc(100% + 8px);',
      '  max-width: 320px;',
      '  background: rgba(17,24,39,0.95);',
      '  color: #fff;',
      '  padding: 8px 10px;',
      '  border-radius: 6px;',
      '  box-shadow: 0 6px 20px rgba(0,0,0,0.25);',
      '  white-space: pre-wrap;',
      '  line-height: 1.25;',
      '  font-size: 12px;',
      '  opacity: 0;',
      '  pointer-events: none;',
      '  transition: opacity .12s ease, transform .12s ease;',
      '  z-index: 60;',
      '}',
      '[data-tooltip]::before{',
      '  content: "";',
      '  position: absolute;',
      '  left: 50%; transform: translateX(-50%);',
      '  bottom: calc(100% + 4px);',
      '  border: 6px solid transparent;',
      '  border-top-color: rgba(17,24,39,0.95);',
      '  opacity: 0;',
      '  transition: opacity .12s ease;',
      '  z-index: 59;',
      '}',
      '[data-tooltip]:hover::after,[data-tooltip]:focus::after{ opacity: 1; transform: translateX(-50%) translateY(-2px); }',
      '[data-tooltip]:hover::before,[data-tooltip]:focus::before{ opacity: 1; }'
    ].join('\n')));
    document.head.appendChild(style);
  }

  function enhanceKondisiTooltips(root) {
    try {
      injectTooltipCssOnce();
      const tables = (root || document).querySelectorAll('table');
      tables.forEach(table => {
        const headerCells = table.tHead ? Array.from(table.tHead.querySelectorAll('th')) : [];
        if (!headerCells.length) return;

        const idxByName = {};
        headerCells.forEach((th, i) => {
          const name = (th.innerText || th.textContent || '').trim().toLowerCase();
          if (name) idxByName[name] = i;
        });

        const kondisiIdx = idxByName['kondisi'] ?? idxByName['kondisi barang'] ?? idxByName['kondisi barang/asset'];
        const catatanIdx = idxByName['catatan'] ?? idxByName['catatan terbaru'] ?? idxByName['catatan terakhir'] ?? idxByName['catatan paling terbaru'];
        if (kondisiIdx == null || catatanIdx == null) return;

        const rows = table.tBodies ? Array.from(table.tBodies).flatMap(tb => Array.from(tb.rows)) : [];
        rows.forEach(tr => {
          const cells = Array.from(tr.cells);
          const kondisiCell = cells[kondisiIdx];
          const catatanCell = cells[catatanIdx];
          if (!kondisiCell || !catatanCell) return;

          const note = (catatanCell.innerText || catatanCell.textContent || '').trim();
          if (!note) return;

          if (!kondisiCell.hasAttribute('data-tooltip')) {
            kondisiCell.setAttribute('data-tooltip', note);
          }
        });
      });
    } catch (e) {
      if (typeof console !== 'undefined' && console.debug) console.debug('Tooltip enhancement skipped:', e);
    }
  }

  function hookDataTables() {
    if (!(window.jQuery && jQuery.fn && jQuery.fn.dataTable)) return;
    jQuery('table').each(function() {
      const $table = jQuery(this);
      $table.on('draw.dt', function() { enhanceKondisiTooltips($table[0]); });
    });
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', function() {
      enhanceKondisiTooltips(document);
      hookDataTables();
    });
  } else {
    enhanceKondisiTooltips(document);
    hookDataTables();
  }
})();

