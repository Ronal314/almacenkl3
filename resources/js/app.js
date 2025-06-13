import 'bootstrap-icons/font/bootstrap-icons.css';
import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap';
import "./notifications.js";
import "./confirmations.js";
import Swal from 'sweetalert2';
import TomSelect from 'tom-select';
import 'tom-select/dist/css/tom-select.css'; 
import Chart from 'chart.js/auto';
import flatpickr from "flatpickr";
import "flatpickr/dist/flatpickr.min.css";
import { Spanish } from "flatpickr/dist/l10n/es.js"; 
//import { Tooltip } from 'bootstrap';

import DataTable from 'datatables.net-bs5'; // núcleo con estilos Bootstrap 5
import 'datatables.net-bs5/css/dataTables.bootstrap5.min.css';

import 'datatables.net-responsive-bs5'; // complemento responsive
import 'datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css';



window.flatpickr = flatpickr;
flatpickr.localize(Spanish);
window.Chart = Chart;
window.Swal = Swal;
window.TomSelect = TomSelect;

// const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
// tooltipTriggerList.forEach((tooltipTriggerEl) => {
//   new Tooltip(tooltipTriggerEl);
// });

window.dataTable = new DataTable('#dataTable', 
  {
    responsive: true, 
    pagingType: 'simple_numbers',
    scrollCollapse: true, 
    language: {
      // Traducción al español
      "sProcessing":     "Procesando...",
      "sLengthMenu":     "Mostrar _MENU_ registros",
      "sZeroRecords":    "No se encontraron resultados",
      "sEmptyTable":     "Ningún dato disponible en esta tabla",
      "sInfo":           "Mostrando _START_ al _END_ de un total de _TOTAL_ registros",
      "sInfoEmpty":      "Mostrando 0 al 0 de un total de 0 registros",
      "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
      "sSearch":         "Buscar:"
    }
  }
);
