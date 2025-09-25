<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>{{ config('app.name', 'Laravel') }}</title>

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.bunny.net">
  <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />



  <!-- Scripts -->
  @vite([
  'resources/css/app.css',
  'resources/js/app.js'
  ])
  <link href="https://cdn.datatables.net/v/dt/jq-3.7.0/jszip-3.10.1/dt-2.3.3/b-3.2.4/b-colvis-3.2.4/b-html5-3.2.4/b-print-3.2.4/cr-2.1.1/cc-1.0.7/date-1.5.6/fc-5.0.4/fh-4.0.3/kt-2.12.1/r-3.0.6/rg-1.5.2/rr-1.5.0/sc-2.4.3/sb-1.8.3/sp-2.3.5/sl-3.1.0/datatables.min.css" rel="stylesheet" integrity="sha384-oeXCSIAxz6d8DmNeNcgpyWtlX9T0AgmIY8GJYCDbndbpOjRueK5s/E9ZRKbUkwY9" crossorigin="anonymous">

  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js" integrity="sha384-VFQrHzqBh5qiJIU0uGU5CIW3+OWpdGGJM9LBnGbuIH2mkICcFZ7lPd/AAtI7SNf7" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js" integrity="sha384-/RlQG9uf0M2vcTw3CX7fbqgbj/h8wKxw7C3zu9/GxcBPRKOEcESxaxufwRXqzq6n" crossorigin="anonymous"></script>
  <script src="https://cdn.datatables.net/v/dt/jq-3.7.0/jszip-3.10.1/dt-2.3.3/b-3.2.4/b-colvis-3.2.4/b-html5-3.2.4/b-print-3.2.4/cr-2.1.1/cc-1.0.7/date-1.5.6/fc-5.0.4/fh-4.0.3/kt-2.12.1/r-3.0.6/rg-1.5.2/rr-1.5.0/sc-2.4.3/sb-1.8.3/sp-2.3.5/sl-3.1.0/datatables.min.js" integrity="sha384-I5Yk3WapAverYvLPpr3zdvtneVgglE6H5Tnj55Np8ppRFkRe9982KsDjN+9wfEkC" crossorigin="anonymous"></script>

  <!-- Scripts -->
  @vite([

  ])

  {{-- @vite([
  'resources/css/app.css',
  'resources/css/dataTables.tailwindcss.css',
  'resources/js/app.js',
  'resources/js/dataTables.js',
  'resources/js/dataTables.tailwindcss.js'
  ]) --}}
</head>

<body class="font-sans antialiased">
  <div class="min-h-screen bg-gray-100">
    @include('layouts.navigation')

    <!-- Page Heading -->
    @isset($header)
    <header class="bg-white shadow">
      <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        {{ $header }}
        @isset($breadcrumb)
        <div class="mt-2">
          {{ $breadcrumb }}
        </div>
        @endisset
      </div>
    </header>
    @endisset

    @if (session('ok'))
    <x-alert type="success" :message="session('ok')" />
    @elseif (session('error'))
    <x-alert type="error" :message="session('error')" />
    @endif

    @if ($errors->any())
    <x-alert type="error" :message="$errors->first()" />
    @endif

    <!-- Page Content -->
    <!-- Page Content -->
    <main>
      {{ $slot }}
    </main>
    @stack('modals')
  </div>
  @vite([
  'resources/js/Tables.js',
  'resources/css/app.css',
  'resources/js/app.js'
  ])
  <script>
    if (!window.tableSorterV2) {
  window.tableSorterV2 = function () {
    return {
      table: null, sortKey: null, sortDir: 'asc',
      init(el) {
        // Allow being called by Alpine's automatic init() (no args)
        // or manually with an element. Fallback to this.$el when available.
        el = el || (this && this.$el) || null;
        if (!el) return;
        if (el.__tableSorterInitialized) return;
        el.__tableSorterInitialized = true;
        this.table = el;
        const head = el.tHead && el.tHead.rows[0];
        if (!head) return;
        Array.from(head.cells).forEach((th, idx) => {
          if (th.hasAttribute('data-nosort')) return;
          th.classList.add('select-none','border-r','border-slate-200','px-4','py-3');
          th.style.cursor = 'pointer';
          const label = th.textContent.trim();
          th.textContent = '';
          const wrap = document.createElement('div');
          wrap.className = 'flex items-center gap-1.5 text-slate-600';
          const lbl = document.createElement('span');
          lbl.className = 'uppercase text-[11px] tracking-[0.08em] font-semibold text-slate-600 leading-none';
          lbl.textContent = label;
          const icon = document.createElement('span');
          icon.className = 'inline-flex items-center justify-center text-sky-500';
          icon.setAttribute('data-sort-icon', '');
          icon.innerHTML = '<svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">\n  <g data-icon-down class="opacity-35">\n    <path d="M6 8h7" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>\n    <path d="M6 12h7" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>\n    <path d="M6 16h7" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>\n    <path d="M16.5 10l2.5 2.5L21.5 10" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>\n    <path d="M19 12.5v3.5" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>\n  </g>\n  <g data-icon-up class="opacity-35">\n    <path d="M6 8h7" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>\n    <path d="M6 12h7" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>\n    <path d="M6 16h7" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>\n    <path d="M19 10v3.5" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>\n    <path d="M16.5 13.5L19 11l2.5 2.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>\n  </g>\n</svg>'
          wrap.appendChild(lbl); wrap.appendChild(icon); th.appendChild(wrap);
          th.addEventListener('click', () => this.sortBy(idx));
        });
        this.updateIcons();
      },
      getRows() {
        const tb = this.table.tBodies[0];
        return Array.from(tb.querySelectorAll('tr')).filter(r => !r.hasAttribute('data-empty-row'));
      },
      cellText(row, idx) {
        const cell = row.cells[idx];
        return cell ? cell.textContent.trim() : '';
      },
      asNumber(s) {
        const t = (s || '').replace(/\s+/g,'').replace(',', '.');
        return /^-?\d+(?:\.\d+)?$/.test(t) ? parseFloat(t) : null;
      },
      asDate(s) {
        const t = Date.parse(s);
        return Number.isNaN(t) ? null : t;
      },
      compare(a,b) {
        const na=this.asNumber(a), nb=this.asNumber(b);
        if (na!==null && nb!==null) return na-nb;
        const da=this.asDate(a), db=this.asDate(b);
        if (da!==null && db!==null) return da-db;
        return a.localeCompare(b, undefined, {numeric:true, sensitivity:'base'});
      },
        updateIcons() {
          const head = this.table.tHead && this.table.tHead.rows[0];
          if (!head) return;
          const baseOpacity = 0.35;
          const inactiveOpacity = 0.12;
          Array.from(head.cells).forEach((th, idx) => {
            const iconWrap = th.querySelector('[data-sort-icon]');
            if (!iconWrap) return;
            const up = iconWrap.querySelector('[data-icon-up]');
            const dn = iconWrap.querySelector('[data-icon-down]');
            if (up) up.style.opacity = baseOpacity;
            if (dn) dn.style.opacity = baseOpacity;
            if (idx === this.sortKey) {
              if (this.sortDir === 'asc') {
                if (up) up.style.opacity = 1;
                if (dn) dn.style.opacity = inactiveOpacity;
              } else {
                if (dn) dn.style.opacity = 1;
                if (up) up.style.opacity = inactiveOpacity;
              }
            }
          });
        },
      sortBy(idx) {
        if (this.sortKey === idx) this.sortDir = this.sortDir === 'asc' ? 'desc' : 'asc';
        else { this.sortKey = idx; this.sortDir = 'asc'; }
        const body = this.table.tBodies[0];
        const rows = this.getRows();
        rows.sort((r1,r2) => {
          const a=this.cellText(r1,idx), b=this.cellText(r2,idx);
          const res=this.compare(a,b);
          return this.sortDir==='asc' ? res : -res;
        });
        rows.forEach(r => body.appendChild(r));
        this.updateIcons();
      }
    }
  }
  document.addEventListener('DOMContentLoaded', ()=>{
    document.querySelectorAll('table[data-sortable]').forEach(el=>{
      if (!el.__tableSorterInitialized) {
        window.tableSorterV2().init(el);
      }
    });
  });
}
  </script>
</body>

</html>