/**
 * Page User List
 */

'use strict';
import { size } from 'lodash';
import Swal from 'sweetalert2';

// // Datatable (jquery)
$(function () {
  // Variable declaration for table
  var dt_user_table = $('.datatables-satis');
  document.getElementById('baslik').innerHTML = 'Satış Siparişleri';

  // ajax setup
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });

  //   // Users datatable
  if (dt_user_table.length) {
    var dt_user = dt_user_table.DataTable({
      processing: true,
      serverSide: true,
      ajax: {
        url: baseUrl + 'satis-list'
      },
      columns: [
        // columns according to JSON
        { data: 'fisno' },
        { data: 'fake_id' },
        { data: 'musteri' },
        { data: 'aciklama' },
        { data: 'sprkg' },
        { data: 'klnkg' },
        { data: 'durum' },
        { data: 'odemeplankodu' },
        { data: 'tarih' },
        { data: 'birim' }
      ],
      columnDefs: [
        {
          // For Responsive
          className: 'control',
          searchable: false,
          orderable: false,
          responsivePriority: 1,
          targets: 0,
          render: function (data, type, full, meta) {
            return '';
          }
        },
        {
          searchable: false,
          orderable: false,
          responsivePriority: 3,
          targets: 1,
          render: function (data, type, full, meta) {
            return `<span>${full.fisno}</span>`;
          }
        },
        {
          searchable: false,
          orderable: false,
          targets: 0,
          render: function (data, type, full, meta) {
            return `<span>${full.fake_id}</span>`;
          }
        },
        {
          targets: 2,
          responsivePriority: 2,
          render: function (data, type, full, meta) {
            var $name = full['musteri'];
            var $row_output =
              '<a href="' +
              '" class="text-heading text-truncate"><span style="white-space: pre;" class="fw-medium">' +
              $name +
              '</span></a>' +
              '</div>' +
              '</div>';
            return $row_output;
          }
        },
        {
          searchable: false,
          orderable: false,
          responsivePriority: 4,
          targets: 3,
          render: function (data, type, full, meta) {
            return `<span style="white-space: pre">${full.aciklama}</span>`;
          }
        },
        {
          targets: 4,
          responsivePriority: 3,
          className: 'text-end'
        },
        {
          targets: 5,
          responsivePriority: 3,
          className: 'text-end',
          render: function (data, type, full, meta) {
            return `<span style="font-weight: bold;">${full.klnkg}</span>`;
          }
        },
        {
          targets: 7,
          data: 'odemeplankodu',
          responsivePriority: 3,
          className: 'text-end'
        },

        {
          targets: 8,
          responsivePriority: 3,
          render: function (data, type, full, meta) {
            return `<span style="white-space: nowrap">${full.tarih}</span>`;
          }
        },
        {
          // Label
          targets: 6,
          responsivePriority: 3,
          render: function (data, type, full, meta) {
            var $status_number = full['durum'];
            return (
              '<div class="d-flex align-items-center">' +
              '<div class="progress w-100 me-3" style="height: 6px;">' +
              '<div class="progress-bar" style="width: ' +
              $status_number +
              '" aria-valuenow="' +
              $status_number +
              '" aria-valuemin="0" aria-valuemax="100"></div>' +
              '</div>' +
              '<span class="text-heading">%' +
              $status_number +
              '</span></div>'
            );
          }
        },
        {
          targets: 9,
          responsivePriority: 5,
          className: 'text-center'
        }
      ],
      order: [[7, 'desc']],

      lengthMenu: [10, 15, 20, 50, 70, 100], //for length of menu
      language: {
        sLengthMenu: '_MENU_',
        search: '',
        searchPlaceholder: 'Kullanıcı Ara',
        info: 'Kayıt: _END_ / _TOTAL_ ',
        paginate: {
          next: '<i class="ti ti-chevron-right ti-sm"></i>',
          previous: '<i class="ti ti-chevron-left ti-sm"></i>'
        }
      },
      // For responsive popup
      responsive: {
        details: {
          display: $.fn.dataTable.Responsive.display.modal({
            header: function (row) {
              var data = row.data();
              return data['musteri'];
            }
          }),
          type: 'column',
          renderer: function (api, rowIdx, columns) {
            var data = $.map(columns, function (col, i) {
              return col.title !== '' // ? Do not show row in modal popup if title is blank (for check box)
                ? '<tr data-dt-row="' +
                    col.rowIndex +
                    '" data-dt-column="' +
                    col.columnIndex +
                    '">' +
                    '<td>' +
                    col.title +
                    ':' +
                    '</td> ' +
                    '<td>' +
                    col.data +
                    '</td>' +
                    '</tr>'
                : '';
            }).join('');

            return data ? $('<table class="table"/><tbody />').append(data) : false;
          }
        }
      }
    });
  }
});
