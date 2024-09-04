
'use strict';
import Swal from 'sweetalert2';
import ExcelJS from 'exceljs';
import DataTable from 'datatables.net-bs5';

// Datatable (jquery)
$(function () {
  document.getElementById('baslik').innerHTML = 'Üretim Girişleri';

  var dt_table = $('.datatables-uretimler')
  var myModalElement = document.getElementById('modalCenter');
  var myModal = new bootstrap.Modal(myModalElement, {
    backdrop: true,
    keyboard: true
  });
  var grupSecimi = '';

  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });

  // Users datatable
  if (dt_table.length) {
    var dt_record = dt_table.DataTable({
      processing: true,
      serverSide: true,
      paging: false,
      info: true,
      scrollCollapse: false,
      scrollY: '65vh',
      infoCallback: function(settings, start, end, max, total, pre) {
        return ' Listelenen kayıt sayısı:   ' + end;
    },
      ajax: {
        url: baseUrl + 'uretim-list',
        data: function (d) {
          d.grupSecimi = grupSecimi; // Seçilen filtre değerini AJAX isteğine ekliyoruz
          // Diğer gerekli parametreler de burada eklenebilir
        }
      },
      columns: [
        { data: 'fake_id' },
        { data: 'ISTKOD' },
        { data: 'ISEMRIID' },
        { data: 'STOKID' },
        { data: 'KOD' },
        { data: 'TANIM' },
        { data: 'MMLGRPKOD' },
        { data: 'MIKTAR' },
        { data: 'URETIMTARIH' },
        { data: 'NOTLAR' },
        { data: 'ID' },
        { data: 'EYLEM' }
      ],
      columnDefs: [
        {
          // For Responsive
          className: 'control',
          searchable: false,
          orderable: false,
          responsivePriority: 2,
          targets: 0,
          render: function (data, type, full, meta) {
            return '';
          }
        },
        {
          searchable: false,
          orderable: false,
          targets: 0,
          visible:false,
          render: function (data, type, full, meta) {
            return `<span>${full.fake_id}</span>`;
          }
        },
        {
          // ISTKOD
          targets: 1,
          responsivePriority: 4,
        },
        {
          // ISEMRIID
          targets: 2,
          responsivePriority: 4,
        },
        {
          // STOKID
          targets: 3,
          responsivePriority: 4,
          className: 'dt-body-right',
          visible: false,
        },
        {
          // KOD
          targets: 4,
          responsivePriority: 4
        },
        {
          // TANIM
          targets: 5,
          responsivePriority: 4
        },
        {
          // MMLGRPKOD
          targets: 6,
          responsivePriority: 4,

        },
        {
          // MIKTAR
          targets: 7,
          responsivePriority: 4,
          className: 'dt-body-right'
        },
        {
          // URETIMTARIH
          title: 'TARİH',
          targets: 8,
          responsivePriority: 4,
          className: 'dt-body-center',
          render: DataTable.render.date()
        },
        {
          // NOTLAR
          targets: 9,
          responsivePriority: 4
        },
        {
          // ID
          targets: 10,
          responsivePriority: 4,
          className: 'dt-body-right',
          visible: true,
        },
        {
          // Actions
          targets: -1,
          title: 'Eylemler',
          searchable: false,
          orderable: false,
          render: function (data, type, full, meta) {
            return (
              '<div class="d-flex align-items-center gap-50">' +
              `<button class="btn btn-sm btn-icon edit-record btn-text-secondary rounded-pill waves-effect" data-id="${full['id']}" data-bs-toggle="offcanvas" data-bs-target="#offcanvasAddUser"><i class="ti ti-edit"></i></button>` +
              `<button class="btn btn-sm btn-icon delete-record btn-text-secondary rounded-pill waves-effect" data-id="${full['id']}"><i class="ti ti-trash"></i></button>` +
              '</div>'
            );
          }
        }
      ],
      order: [[10, 'desc']],
       dom:
       '<"row"' +
       '<"col-12 col-md-6 d-flex align-items-center justify-content-center justify-content-md-start gap-2"l<"dt-action-buttons text-xl-end text-lg-start text-md-end text-start"B>>' +
       '<"col-12 col-md-6 d-flex align-items-center justify-content-end flex-column flex-md-row pe-5 gap-md-4 mt-n5 mt-md-0"f<"istasyon mb-6 mb-md-0">>' +
       '>t' +
       '<"row"' +
       '<"col-sm-12 col-md-6"i>' +
       '<"col-sm-12 col-md-6"p>' +
       '>',
      language: {
        search: '',
        searchPlaceholder: 'Ara'
      },
      // Buttons with Dropdown
      buttons: [
        {
          text: '<i class="ti ti-arrow-right me-0 me-sm-1 "></i><span class="d-none d-sm-inline-block">Excel</span>',
          className: 'export btn btn-succes waves-effect waves-light ms-2 me-2',
          attr: {
            'data-bs-target': '#exportExcelButton'
          }
        },
        {
          text: '<i class="ti ti-plus me-0 me-sm-1 ti-xs"></i><span class="d-none d-sm-inline-block">Kayıt Ekle</span>',
          className: 'add-new btn btn-primary waves-effect waves-light',
          attr: {
            'data-bs-toggle': 'offcanvas',
            'data-bs-target': '#offcanvasAddRecord'
          }
        }
      ],
      responsive: {
        details: {
          display: $.fn.dataTable.Responsive.display.modal({
            header: function (row) {
              var data = row.data();
              return data['TANIM'];
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
      },
      initComplete: function () {
        // Adding role filter once table initialized
        this.api()
          .columns(1)
          .every(function () {
            var column = this;
            var select = $('<select id="sss" class="form-select"><option value="">Tüm İstasyonlar</option></select>')
              .appendTo('.istasyon')
              .on('change', function () {
                grupSecimi = $(this).val(); // Seçilen değeri değişkene atıyoruz
                dt_record.draw(); // Tabloyu tekrar yüklüyoruz (AJAX isteği tetiklenir)
              });

            column
              .data()
              .unique()
              .sort()
              .each(function (d, j) {
                select.append('<option value="' + d + '" class="text-capitalize">' + d + '</option>');
              });
          });
      }
    });
  }

  // // Delete Record
  // $(document).on('click', '.delete-record', function () {
  //   alert($(this).data('id'));
  //   var user_id = $(this).data('id'),
  //     dtrModal = $('.dtr-bs-modal.show');

  //   // hide responsive modal in small screen
  //   if (dtrModal.length) {
  //     dtrModal.modal('hide');
  //   }

  //   // sweetalert for confirmation of delete
  //   Swal.fire({
  //     title: 'Emin misiniz?',
  //     text: 'Bu işlemi geri alamayacaksınız!',
  //     icon: 'warning',
  //     showCancelButton: true,
  //     confirmButtonText: 'Evet, Silebilirsin!',
  //     cancelButtonText: 'Vazgeç',
  //     customClass: {
  //       confirmButton: 'btn btn-primary me-3',
  //       cancelButton: 'btn btn-label-secondary'
  //     },
  //     buttonsStyling: false
  //   }).then(function (result) {
  //     if (result.value) {
  //       // delete the data
  //       $.ajax({
  //         type: 'DELETE',
  //         url: `${baseUrl}user-list/${user_id}`,
  //         success: function () {
  //           dt_user.draw();
  //         },
  //         error: function (error) {
  //           console.log(error);
  //         }
  //       });

  //       // success sweetalert
  //       Swal.fire({
  //         icon: 'success',
  //         title: 'Silindi!',
  //         text: 'Kullanıcı silindi',
  //         confirmButtonText: 'Kapat',
  //         customClass: {
  //           confirmButton: 'btn btn-success'
  //         }
  //       });
  //     } else if (result.dismiss === Swal.DismissReason.cancel) {
  //       Swal.fire({
  //         title: 'Vazgeçildi',
  //         text: 'Kullanıcı silinmedi!',
  //         icon: 'error',
  //         confirmButtonText: 'Kapat',
  //         customClass: {
  //           confirmButton: 'btn btn-success'
  //         }
  //       });
  //     }
  //   });
  // });

  // // edit record
  // $(document).on('click', '.edit-record', function () {
  //   var user_id = $(this).data('id'),
  //     dtrModal = $('.dtr-bs-modal.show');

  //   // hide responsive modal in small screen
  //   if (dtrModal.length) {
  //     dtrModal.modal('hide');
  //   }

  //   // changing the title of offcanvas
  //   $('#offcanvasAddUserLabel').html('Edit User');

  //   // get data
  //   $.get(`${baseUrl}user-list\/${user_id}\/edit`, function (data) {
  //     $('#user_id').val(data.id);
  //     $('#add-user-fullname').val(data.name);
  //     $('#add-user-email').val(data.email);
  //   });
  // });


  // setTimeout(() => {
  //   $('.dataTables_filter .form-control').removeClass('form-control-sm');
  //   $('.dataTables_length .form-select').removeClass('form-select-sm');
  // }, 300);

  // const addNewUserForm = document.getElementById('addNewUserForm');

  // const fv = FormValidation.formValidation(addNewUserForm, {
  //   fields: {
  //     name: {
  //       validators: {
  //         notEmpty: {
  //           message: 'Lütfen Tam İsminizi Giriniz'
  //         }
  //       }
  //     },
  //     email: {
  //       validators: {
  //         notEmpty: {
  //           message: 'Please enter your email'
  //         },
  //         emailAddress: {
  //           message: 'The value is not a valid email address'
  //         }
  //       }
  //     },
  //     userContact: {
  //       validators: {
  //         notEmpty: {
  //           message: 'Please enter your contact'
  //         }
  //       }
  //     },
  //     company: {
  //       validators: {
  //         notEmpty: {
  //           message: 'Please enter your company'
  //         }
  //       }
  //     }
  //   },
  //   plugins: {
  //     trigger: new FormValidation.plugins.Trigger(),
  //     bootstrap5: new FormValidation.plugins.Bootstrap5({
  //       // Use this for enabling/changing valid/invalid class
  //       eleValidClass: '',
  //       rowSelector: function (field, ele) {
  //         // field is the field name & ele is the field element
  //         return '.mb-6';
  //       }
  //     }),
  //     submitButton: new FormValidation.plugins.SubmitButton(),
  //     // Submit the form when all fields are valid
  //     // defaultSubmit: new FormValidation.plugins.DefaultSubmit(),
  //     autoFocus: new FormValidation.plugins.AutoFocus()
  //   }
  // }).on('core.form.valid', function () {
  //   $.ajax({
  //     data: $('#addNewUserForm').serialize(),
  //     url: `${baseUrl}user-list`,
  //     type: 'POST',
  //     success: function (status) {
  //       dt_user.draw();
  //       offCanvasForm.offcanvas('hide');
  //       Swal.fire({
  //         icon: 'success',
  //         title: `Successfully ${status}!`,
  //         text: `User ${status} Successfully.`,
  //         customClass: {
  //           confirmButton: 'btn btn-success'
  //         }
  //       });
  //     },
  //     error: function (err) {
  //       offCanvasForm.offcanvas('hide');
  //       Swal.fire({
  //         title: 'Duplicate Entry!',
  //         text: 'Your email should be unique.',
  //         icon: 'error',
  //         customClass: {
  //           confirmButton: 'btn btn-success'
  //         }
  //       });
  //     }
  //   });
  // });

  // offCanvasForm.on('hidden.bs.offcanvas', function () {
  //   fv.resetForm(true);
  // });

});
