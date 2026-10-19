
import Alpine from 'alpinejs';
import DataTable from 'datatables.net-dt';
import 'datatables.net-dt/css';

window.Alpine = Alpine;

Alpine.start();

document.querySelectorAll('[data-datatable]').forEach((table) => new DataTable(table));
