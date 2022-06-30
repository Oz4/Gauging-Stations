let table = $('#IGS-table').DataTable({
  // "processing": true,
  "serverSide": true,
  "ajax": "./php/getData.php",
  columnDefs: [{
    defaultContent: "",
    targets: [0,1,2,3,4,5,6],
    },
    {
      targets: ['nosort'],
      orderable: false,
      defaultContent: "<i onclick='editButton(this)' class='fas fa-pen fa-sm edit-button'></i> <i onclick='deleteButton(this)' class='fas fa-trash fa-sm delete-button'></i> "

    }
  ],
  responsive: true,
  pageLength: 15,
  lengthMenu: [
    [5, 10, 15, 20, 50, -1],
    [5, 10, 15, 20, 50, 'All'],
  ],
  dom: '<"top"Blf>rt<"bottom"ip>',
  buttons: [
    {
      extend: 'excel',
      exportOptions: { columns: [0, 1, 2, 3, 4, 5, 6] },
      title: 'IRAQ GAUGING STATIONS',
    },
    {
      extend: 'pdf',
      exportOptions: { columns: [0, 1, 2, 3, 4, 5, 6] },
      title: 'IRAQ GAUGING STATIONS',
      customize: function (doc) {
        doc.content[1].table.widths = Array(
          doc.content[1].table.body[0].length + 1,
        ).join('*').split('')
      },
    },
    {
      extend: 'print',
      exportOptions: { columns: [0, 1, 2, 3, 4, 5, 6] },
      title: '',
    },
  ],
});

let rowId;
let editButton = (e) => {


  let index = e.closest('tr').rowIndex - 1;

  rowId = table.data()[index]['8'];
  let no = table.data()[index]['0'];
  let tds = table.data()[index]['1'];
  let tur = table.data()[index]['2'];
  let ph = table.data()[index]['3'];
  let level = table.data()[index]['4'];
  let date = table.data()[index]['5'];
  let time = table.data()[index]['6'];

  let modal = document.getElementById('modal-edit-id');
  modal.style.display = 'block';

  document.getElementById('station-input').value = no;
  document.getElementById('tds-input').value = tds;
  document.getElementById('tur-input').value = tur;
  document.getElementById('ph-input').value = ph;
  document.getElementById('level-input').value = level;
  document.getElementById('date-input').value = date;
  document.getElementById('time-input').value = time;

}


let confirmationEditButton = () => {
  $.ajax({
    url: './php/update.php',
    type: 'post',
    data: { 
      id: rowId,
      no: document.getElementById('station-input').value,
      tds: document.getElementById('tds-input').value,
      tur: document.getElementById('tur-input').value,
      ph: document.getElementById('ph-input').value,
      level: document.getElementById('level-input').value,
      date: document.getElementById('date-input').value,
      time: document.getElementById('time-input').value,
    },
    success: (results) => {
      // console.log(results)
      let modal = document.getElementById('modal-edit-id');
      modal.style.display = 'none';
      document.getElementById('edit-log').style.display = 'block';
      table.ajax.reload();

    },
    error: (err) => {
      console.log('Error: edit failed',err);
      document.getElementById('fail-log').style.display = 'block';
    }
  })
}


deleteButton = (e) => {
  let index = e.closest('tr').rowIndex - 1;//get row index to fetch data from table like id
  rowId = table.data()[index]['8'];// get Id to send to php
  let modal = document.getElementById('modal-delete-id');
  modal.style.display = 'block';


}

let confirmationDeleteButton = () => {
  $.ajax({
    url: './php/delete.php',
    type: 'post',
    data: { id: rowId },
    success: (results) => {
      // console.log(results);
      let modal = document.getElementById('modal-delete-id');
      modal.style.display = 'none';
      document.getElementById('delete-log').style.display = 'block';
      table.ajax.reload();
    },
    error: (err) => {
      console.log('Error: delete failed',err);
      document.getElementById('fail-log').style.display = 'block';
    }
  })
}

(function () {
  'use strict'

  // Fetch all the forms we want to apply custom Bootstrap validation styles to
  let forms = document.querySelectorAll('.needs-validation')

  // Loop over them and prevent submission
  Array.prototype.slice.call(forms)
    .forEach(function (form) {
      form.addEventListener('submit', function (event) {
        if (!form.checkValidity()) {
          event.preventDefault()
          event.stopPropagation()
        }

        form.classList.add('was-validated')
      }, false)
    })
})()
