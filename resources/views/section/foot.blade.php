   <!-- Common JS -->
   <script src="{{ asset('assets/plugins/common/common.min.js') }}"></script>
   <!-- Custom script -->
   <script src="{{ asset('assets/js/custom.min.js') }}"></script>
   <!-- Chartjs chart -->
   <script src="{{ asset('assets/plugins/chartjs/Chart.bundle.js') }}"></script>
   <!-- Custom dashboard script -->
   <script src="{{ asset('assets/js/dashboard-1.js') }}"></script>

<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.4.1/js/responsive.bootstrap4.min.js"></script>

<script>
$(document).ready(function () {

    // ── Configuration commune ────────────────────────────────────
    var dtConfig = {
        responsive: true,
        language: { url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/fr-FR.json' },
        pageLength: 10,
        dom: 'lfrtip',
        order: [],
    };

    // ── Tableaux simples (pas d'onglets) ─────────────────────────
    // IDs : table-candidats, table-demandes, table-coachs, table-admin-candidats
    [
        'table-candidats',
        'table-demandes',
        'table-coachs',
        'table-demandes-index',
        'table-admin-candidats',
    ].forEach(function (id) {
        var $t = $('#' + id);
        if ($t.length && $t.find('tbody tr.data-row').length > 0) {
            $t.DataTable($.extend(true, {}, dtConfig));
        }
    });

    // ── Tableaux onglets utilisateurs (coach / candidats) ────────
    // IDs : table-users-coachs, table-users-candidats
    [
        'table-users-coachs',
        'table-users-candidats',
    ].forEach(function (id) {
        var $t = $('#' + id);
        if ($t.length && $t.find('tbody tr.data-row').length > 0) {
            $t.DataTable($.extend(true, {}, dtConfig));
        }
    });

    // ── Tableaux onglets entretiens coach (jour/semaine/mois) ────
    // IDs : table-jour, table-semaine, table-mois
    ['jour', 'semaine', 'mois'].forEach(function (key) {
        var $t = $('#table-' + key);
        if ($t.length && $t.find('tbody tr.data-row').length > 0) {
            $t.DataTable($.extend(true, {}, dtConfig, { order: [[4, 'desc']] }));
        }
    });

    // ── Tableaux onglets entretiens admin (jour/semaine/mois) ────
    // IDs : admin-entretiens-jour, admin-entretiens-semaine, admin-entretiens-mois
    ['jour', 'semaine', 'mois'].forEach(function (key) {
        var $t = $('#admin-entretiens-' + key);
        if ($t.length && $t.find('tbody tr.data-row').length > 0) {
            $t.DataTable($.extend(true, {}, dtConfig, { order: [[4, 'desc']] }));
        }
    });

    // ── Recalcul au changement d'onglet (tous les onglets) ───────
    $('a[data-toggle="tab"]').on('shown.bs.tab', function () {
        $.fn.dataTable.tables({ visible: true, api: true }).columns.adjust().responsive.recalc();
    });

});
</script>

