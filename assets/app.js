import 'bootstrap';
import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap-icons/font/bootstrap-icons.css';
import './styles/css/app.css';

import DataTable from 'datatables.net-dt';
import 'datatables.net-dt/css/dataTables.dataTables.css';

document.addEventListener('DOMContentLoaded', function () {
    const tables = document.querySelectorAll('table.datatable');

    tables.forEach(table => {
        if (!table._datatable) {
            new DataTable(table, {
                language: {
                    emptyTable: "No hay datos disponibles en la tabla",
                    decimal: ",",
                    thousands: ".",
                    lengthMenu: "Mostrar _MENU_ registros por página",
                    zeroRecords: "No se encontraron resultados",
                    info: "Mostrando _START_ a _END_ de _TOTAL_ registros",
                    infoEmpty: "Mostrando 0 a 0 de 0 registros",
                    infoFiltered: "(filtrado de _MAX_ registros totales)",
                    search: "Buscar:",
                    loadingRecords: "Cargando...",
                    processing: "Procesando...",
                    paginate: {
                        first: "Primero",
                        last: "Último",
                        next: "Siguiente",
                        previous: "Anterior"
                    }
                },
                responsive: true,
                pageLength: 10
            });
            table._datatable = true;
        }
    });
});

import {Application} from "@hotwired/stimulus"
import {definitionsFromContext} from "@hotwired/stimulus-webpack-helpers"

if (!window.Stimulus) {
    window.Stimulus = Application.start();
    const context = require.context("./controllers", true, /\.js$/);
    Stimulus.load(definitionsFromContext(context));
}
