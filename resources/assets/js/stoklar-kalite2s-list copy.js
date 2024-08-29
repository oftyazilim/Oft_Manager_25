/**
 * Page User List
 */

'use strict';
//import { error } from 'jquery';
// import { size } from 'lodash';
import Swal from 'sweetalert2';

// // Datatable (jquery)
$(function () {
  document.getElementById('baslik').innerHTML = 'Şekerpınar 2. Kalite Stok Listesi';

  var dt_table = $('.datatables-kalite2'),
    offCanvasForm = $('#offcanvasAddRecord');

  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });

  //   // Users datatable
  if (dt_table.length) {
    var dt_record = dt_table.DataTable({
      processing: true,
      serverSide: true,
      ajax: {
        url: baseUrl + 'stok-lists'
      },
      columns: [
        // columns according to JSON
        { data: 'fake_id' },
        { data: 'mamul' },
        { data: 'boy' },
        { data: 'adet2' },
        { data: 'kantarkg' },
        { data: 'adet' },
        { data: 'kg' },
        { data: 'nevi' },
        { data: 'pkno' },
        { data: 'hat' },
        { data: 'tarih' },
        { data: 'saat' },
        { data: 'operator' },
        { data: 'mamulkodu' },
        { data: 'basildi' },
        { data: 'id' },
        { data: 'eylem' },
      ],
      buttons: [
        {
          extend: 'collection',
          className: 'btn btn-label-primary dropdown-toggle mx-4 waves-effect waves-light',
          text: '<i class="ti ti-upload me-2 ti-xs"></i>Dışa Aktar',
          buttons: [
            {
              extend: 'print',
              title: '2. Kalite Stoklar (Şekerpınar)',
              text: '<i class="ti ti-printer me-2" ></i>Print',
              className: 'dropdown-item',
              exportOptions: {
                columns: [1, 2, 3, 4, 6, 7],
                // prevent avatar to be print
                format: {
                  body: function (inner, coldex, rowdex) {
                    if (inner.length <= 0) return inner;
                    var el = $.parseHTML(inner);
                    var result = '';
                    $.each(el, function (index, item) {
                      if (item.classList !== undefined && item.classList.contains('mamul')) {
                        result = result + item.lastChild.firstChild.textContent;
                      } else if (item.innerText === undefined) {
                        result = result + item.textContent;
                      } else result = result + item.innerText;
                    });
                    return result;
                  }
                }
              },
              customize: function (win) {
                //customize print view for dark
                $(win.document.body)
                  .css('color', config.colors.headingColor)
                  .css('border-color', config.colors.borderColor)
                  .css('background-color', config.colors.body);
                $(win.document.body)
                  .find('table')
                  .addClass('compact')
                  .css('color', 'inherit')
                  .css('border-color', 'inherit')
                  .css('background-color', 'inherit');
              },
              // Dışa aktarma sırasında veriyi tekrar çekme işlemi
              action: function (e, dt, node, config) {
                // dt.ajax.reload(function (json) {
                  $ajax: {
                    url: baseUrl + 'stok-lists\listele'

                  // Veriyi yeniden çektikten sonra print işlemini başlat
                  $.fn.dataTable.ext.buttons.print.action.call(this, e, dt, node, config);
                };
              }
            },
            {
              extend: 'csv',
              title: '2. Kalite Stoklar (Şekerpınar)',
              text: '<i class="ti ti-file-text me-2" ></i>Csv',
              className: 'dropdown-item',
              exportOptions: {
                columns: [1, 2, 3, 4, 6, 7],
                // prevent avatar to be display
                format: {
                  body: function (inner, coldex, rowdex) {
                    if (inner.length <= 0) return inner;
                    var el = $.parseHTML(inner);
                    var result = '';
                    $.each(el, function (index, item) {
                      if (item.classList !== undefined && item.classList.contains('mamul')) {
                        result = result + item.lastChild.firstChild.textContent;
                      } else if (item.innerText === undefined) {
                        result = result + item.textContent;
                      } else result = result + item.innerText;
                    });
                    return result;
                  }
                }
              }
            },
            {
              extend: 'excel',
              title: '2. Kalite Stoklar (Şekerpınar)',
              text: '<i class="ti ti-file-spreadsheet me-2"></i>Excel',
              className: 'dropdown-item',
              exportOptions: {
                columns: [1, 2, 3, 4, 6, 7],
                // prevent avatar to be display
                format: {
                  body: function (inner, coldex, rowdex) {
                    if (inner.length <= 0) return inner;
                    var el = $.parseHTML(inner);
                    var result = '';
                    $.each(el, function (index, item) {
                      if (item.classList !== undefined && item.classList.contains('mamul')) {
                        result = result + item.lastChild.firstChild.textContent;
                      } else if (item.innerText === undefined) {
                        result = result + item.textContent;
                      } else result = result + item.innerText;
                    });
                    return result;
                  }
                }
              }
            },
            {
              extend: 'pdf',
              title: '2. Kalite Stoklar (Şekerpınar)',
              text: '<i class="ti ti-file-code-2 me-2"></i>Pdf',
              className: 'dropdown-item',
              exportOptions: {
                columns: [1, 2, 3, 4, 6, 7],
                // prevent avatar to be display
                format: {
                  body: function (inner, coldex, rowdex) {
                    if (inner.length <= 0) return inner;
                    var el = $.parseHTML(inner);
                    var result = '';
                    $.each(el, function (index, item) {
                      if (item.classList !== undefined && item.classList.contains('mamul')) {
                        result = result + item.lastChild.firstChild.textContent;
                      } else if (item.innerText === undefined) {
                        result = result + item.textContent;
                      } else result = result + item.innerText;
                    });
                    return result;
                  }
                }
              }
            },
            {
              extend: 'copy',
              title: '2. Kalite Stoklar (Şekerpınar)',
              text: '<i class="ti ti-copy me-2" ></i>Copy',
              className: 'dropdown-item',
              exportOptions: {
                columns: [1, 2, 3, 4, 6, 7],
                // prevent avatar to be display
                format: {
                  body: function (inner, coldex, rowdex) {
                    if (inner.length <= 0) return inner;
                    var el = $.parseHTML(inner);
                    var result = '';
                    $.each(el, function (index, item) {
                      if (item.classList !== undefined && item.classList.contains('mamul')) {
                        result = result + item.lastChild.firstChild.textContent;
                      } else if (item.innerText === undefined) {
                        result = result + item.textContent;
                      } else result = result + item.innerText;
                    });
                    return result;
                  }
                }
              }
            }
          ]
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
      order: [[7, 'desc']],
      dom:
        '<"row"' +
        '<"col-md-2"<"ms-n2"l>>' +
        '<"col-md-10"<"dt-action-buttons text-xl-end text-lg-start text-md-end text-start d-flex align-items-center justify-content-end flex-md-row flex-column mb-6 mb-md-0 mt-n6 mt-md-0"fB>>' +
        '>t' +
        '<"row"' +
        '<"col-sm-12 col-md-6"i>' +
        '<"col-sm-12 col-md-6"p>' +
        '>',
      lengthMenu: [10, 15, 20, 50, 70, 100], //for length of menu
      language: {
        sLengthMenu: '_MENU_',
        search: '',
        searchPlaceholder: 'Ara',
        info: 'Kayıt: _END_ / _TOTAL_ ',
        paginate: {
          next: '<i class="ti ti-chevron-right ti-sm"></i>',
          previous: '<i class="ti ti-chevron-left ti-sm"></i>'
        }
      },
      // columnDefs: [
      //   {
      //     //For Responsive
      //     className: 'control',
      //     searchable: false,
      //     orderable: false,
      //     responsivePriority: 1,
      //     targets: 0,
      //     render: function (data, type, row, meta) {
      //       return ``;
      //     }
      //   },
      //   {
      //     //targets: 0,
      //     orderable: false,
      //     responsivePriority: 3,
      //     searchable: false,
      //     className: 'dt-body-center',
      //     render: function (data, type, row, meta) {
      //       return `<span style="font-weight: bold; color: limegreen;">${full.fake_id}</span>`;
      //     }
      //   },
      //   {
      //     targets: 1, //mamul
      //     responsivePriority: 1,
      //     className: 'dt-body-left'
      //   },
      //   {
      //     targets: 2, //boy
      //     responsivePriority: 3,
      //     className: 'text-end'
      //   },
      //   {
      //     targets: 3, //adet
      //     responsivePriority: 2,
      //     className: 'dt-body-right',
      //     render: function (data, type, row, meta) {
      //       return data;
      //     }
      //   },
      //   {
      //     targets: 4, //kantarkg
      //     responsivePriority: 1,
      //     className: 'dt-body-right',
      //     render: function (data, type, full, meta) {
      //       return `<span style="font-weight: bold; color: limegreen;">${full.kantarkg}</span>`;
      //     }
      //   },
      //   {
      //     targets: 5, //adet
      //     responsivePriority: 2,
      //     className: 'dt-body-right',
      //     render: function (data, type, row, meta) {
      //       return data;
      //     }
      //   },
      //   {
      //     targets: 6, //kg
      //     responsivePriority: 4,
      //     className: 'dt-body-right',
      //     render: function (data, type, row, meta) {
      //       return data;
      //     }
      //   },
      //   {
      //     targets: 7, //nevi
      //     responsivePriority: 5,
      //     className: 'dt-body-center',
      //     render: function (data, type, full, meta) {
      //       var $nevi = full['nevi'];
      //       return `${
      //         $nevi == 'BYL'
      //           ? `<span style="color: brown; font-weight: bold;">${full.nevi}</span>`
      //           : `<span >${full.nevi}</span>`
      //       }`;
      //     }
      //   },
      //   {
      //     targets: 8, //pkno
      //     responsivePriority: 4,
      //     className: 'dt-body-center',
      //     width: '100%',
      //     render: function (data, type, full, meta) {
      //       return `<span style="white-space: nowrap">${full.pkno}</span>`;
      //     }
      //   },
      //   {
      //     targets: 9, //hat
      //     responsivePriority: 5,
      //     className: 'dt-body-center',
      //     render: function (data, type, full, meta) {
      //       return `<span style="white-space: nowrap">${full.hat}</span>`;
      //     }
      //   },
      //   {
      //     targets: 10, //tarih
      //     responsivePriority: 4,
      //     className: 'dt-body-center',
      //     render: function (data, type, full, meta) {
      //       return `<span style="white-space: nowrap">${full.tarih}</span>`;
      //     }
      //   },
      //   {
      //     targets: 11, //saat
      //     responsivePriority: 5,
      //     className: 'dt-body-center',
      //     render: function (data, type, row, meta) {
      //       return data;
      //     }
      //   },
      //   {
      //     targets: 12, //operator
      //     responsivePriority: 5,
      //     className: 'dt-body-center',
      //     render: function (data, type, row, meta) {
      //       return data;
      //     }
      //   },
      //   {
      //     targets: 13, //mamulkodu
      //     responsivePriority: 5,
      //     className: 'dt-body-center',
      //     render: function (data, type, full, meta) {
      //       return `<span style="white-space: nowrap">${full.mamulkodu}</span>`;
      //     }
      //   },
      //   {
      //     targets: 14, //basildi
      //     className: 'dt-body-center',
      //     responsivePriority: 3,
      //     render: function (data, type, full, meta) {
      //       var $verified = full['basildi'];
      //       return `${
      //         $verified == 1
      //           ? '<i class="ti fs-4 ti-shield-check text-success"></i>'
      //           : '<i class="ti fs-4 ti-shield-x text-danger" ></i>'
      //       }`;
      //     }
      //   },
      //   {
      //     targets: 15, //id
      //     className: 'dt-body-center',
      //     responsivePriority: 5
      //   },
      //   {
      //     // Actions
      //     targets: -1,
      //     title: 'Eylemler',
      //     searchable: false,
      //     responsivePriority: 4,
      //     orderable: false,
      //     // responsivePriority: 3,
      //     render: function (data, type, full, meta) {
      //       return (
      //         '<div class="d-flex align-items-center gap-50">' +
      //         `<button class="btn btn-sm btn-icon edit-record btn-text-secondary rounded-pill waves-effect" data-id="${full['id']}" data-bs-toggle="offcanvas" data-bs-target="#offcanvasAddRecord"><i class="ti ti-edit"></i></button>` +
      //         `<button class="btn btn-sm btn-icon delete-record btn-text-secondary rounded-pill waves-effect" data-id="${full['id']}"><i class="ti ti-trash"></i></button>` +
      //         '<button class="btn btn-sm btn-icon btn-text-secondary rounded-pill waves-effect dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="ti ti-dots-vertical"></i></button>' +
      //         '<div class="dropdown-menu dropdown-menu-end m-0">' +
      //         '<a href="' +
      //         //userView +
      //         '" class="dropdown-item">View</a>' +
      //         '<a href="javascript:;" class="dropdown-item">Suspend</a>' +
      //         '</div>' +
      //         '</div>'
      //       );
      //     }
      //   }
      // ],
      // For responsive popup
      // responsive: {
      //   details: {
      //     display: $.fn.dataTable.Responsive.display.modal({
      //       header: function (row) {
      //         var data = row.data();
      //         return data['musteri'];
      //       }
      //     }),
      //     type: 'column',
      //     renderer: function (api, rowIdx, columns) {
      //       var data = $.map(columns, function (col, i) {
      //         return col.title !== '' // ? Do not show row in modal popup if title is blank (for check box)
      //           ? '<tr data-dt-row="' +
      //               col.rowIndex +
      //               '" data-dt-column="' +
      //               col.columnIndex +
      //               '">' +
      //               '<td>' +
      //               col.title +
      //               ':' +
      //               '</td> ' +
      //               '<td>' +
      //               col.data +
      //               '</td>' +
      //               '</tr>'
      //           : '';
      //       }).join('');

      //       return data ? $('<table class="table"/><tbody />').append(data) : false;
      //     }
      //   }
      // },
      footerCallback: function (row, data, start, end, display) {
        let api = this.api();

        // Remove the formatting to get integer data for summation
        let intVal = function (i) {
          return typeof i === 'string' ? i.replace(/[\$,]/g, '') * 1 : typeof i === 'number' ? i : 0;
        };

        // Total over all pages
        let toplam = $('.datatables-kalite2').DataTable().ajax.json().toplamKg;

        // Update footer
        api.column(3).footer().innerHTML = toplam;
      }
    });
  }

  veriAl();

  function veriAl() {
    $.ajax({
      type: 'GET',
      url: '/stok/verials',
      success: function (response) {
        let data = response;
        document.getElementById('toplamPaket').innerHTML = data[0] + '<span style="font-size: 14px;"> Adet</span>';
        document.getElementById('toplamGenel').innerHTML = data[1] + '<span style="font-size: 14px;"> Kg</span>';
        document.getElementById('toplamHr').innerHTML =
          Math.round(data[2]) + '<span style="font-size: 14px;"> Kg</span>';
        document.getElementById('toplamDiger').innerHTML =
          Math.round(data[3]) + '<span style="font-size: 14px;"> Kg</span>';
      },
      error: function (error) {
        console.log(error);
      }
    });
  }

  // Delete Record
  $(document).on('click', '.delete-record', function () {
    var temp_id = $(this).data('id'),
      dtrModal = $('.dtr-bs-modal.show');

    // hide responsive modal in small screen
    if (dtrModal.length) {
      dtrModal.modal('hide');
    }

    // sweetalert for confirmation of delete
    Swal.fire({
      title: 'Emin misiniz?',
      text: 'Bu işlemi geri alamayacaksınız!',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Evet, Silebilirsin!',
      cancelButtonText: 'Vazgeç',
      customClass: {
        confirmButton: 'btn btn-primary me-3',
        cancelButton: 'btn btn-label-secondary'
      },
      buttonsStyling: false
    }).then(function (result) {
      if (result.value) {
        // delete the data
        $.ajax({
          type: 'DELETE',
          url: `${baseUrl}stok-lists/${temp_id}`,
          success: function () {
            dt_record.draw();
            veriAl();
          },
          error: function (error) {
            console.log(error);
          }
        });

        //success sweetalert
        // Swal.fire({
        //   icon: 'success',
        //   title: 'Silindi!',
        //   text: 'Kayıt silindi',
        //   confirmButtonText: 'Kapat',
        //   customClass: {
        //     confirmButton: 'btn btn-success'
        //   }
        // });
      } else if (result.dismiss === Swal.DismissReason.cancel) {
        Swal.fire({
          title: 'Vazgeçildi',
          text: 'Kayıt silinmedi!',
          icon: 'error',
          confirmButtonText: 'Kapat',
          customClass: {
            confirmButton: 'btn btn-success'
          }
        });
      }
    });
  });

  // edit record
  $(document).on('click', '.edit-record', function () {
    var kayit_id = $(this).data('id'),
      dtrModal = $('.dtr-bs-modal.show');

    // hide responsive modal in small screen
    if (dtrModal.length) {
      dtrModal.modal('hide');
    }

    // changing the title of offcanvas
    $('#offcanvasAddLabel').html('Kayıt Düzeltme');

    // get data
    $.get(`${baseUrl}stok-lists\/${kayit_id}\/edit`, function (data) {
      console.log(data[0].mamul);
      $('#record_id').val(data[0].id);
      $('#mamul').val(data[0].mamul);
      $('#boy').val(data[0].boy);
      $('#kantarkg').val(data[0].kantarkg);
      $('#adet2').val(data[0].adet2);
      $('#hat').val(data[0].hat);
      $('#basildi').val(data[0].basildi);
      $('#nevi').val(data[0].nevi);
    });
  });

  // changing the title
  $('.add-new').on('click', function () {
    // $('#record_id').val(''); //reseting input field
    // $('#offcanvasAddLabel').html('Kayıt Ekle');
  });

  // Filter form control to default size
  // ? setTimeout used for multilingual table initialization
  setTimeout(() => {
    $('.dataTables_filter .form-control').removeClass('form-control-sm');
    $('.dataTables_length .form-select').removeClass('form-select-sm');
  }, 300);

  // validating form and updating user's data
  const addNewRecordForm = document.getElementById('addNewRecordForm');
  // user form validation
  const fv = FormValidation.formValidation(addNewRecordForm, {
    fields: {
      mamul: {
        validators: {
          notEmpty: {
            message: 'Lütfen Mamül Giriniz'
          }
        }
      },
      boy: {
        validators: {
          notEmpty: {
            message: 'Lütfen Boy Giriniz'
          }
        }
      },
      adet: {
        validators: {
          notEmpty: {
            message: 'Lütfen Adet Giriniz'
          }
        }
      },
      kantarkg: {
        validators: {
          notEmpty: {
            message: 'Lütfen Tartım Kilosunu Giriniz'
          }
        }
      },
      nevi: {
        validators: {
          notEmpty: {
            message: 'Lütfen Nevi Giriniz'
          }
        }
      },
      hat: {
        validators: {
          notEmpty: {
            message: 'Lütfen Nevi Giriniz'
          }
        }
      }
    },
    plugins: {
      trigger: new FormValidation.plugins.Trigger(),
      bootstrap5: new FormValidation.plugins.Bootstrap5({
        // Use this for enabling/changing valid/invalid class
        eleValidClass: '',
        rowSelector: function (field, ele) {
          // field is the field name & ele is the field element
          return '.mb-6';
        }
      }),
      submitButton: new FormValidation.plugins.SubmitButton(),
      // Submit the form when all fields are valid
      // defaultSubmit: new FormValidation.plugins.DefaultSubmit(),
      autoFocus: new FormValidation.plugins.AutoFocus()
    }
  }).on('core.form.valid', function () {
    $.ajax({
      data: $('#addNewRecordForm').serialize(),
      url: `${baseUrl}stok-lists`,
      type: 'POST',
      success: function (status) {
        dt_record.draw();
        offCanvasForm.offcanvas('hide');
        Swal.fire({
          icon: 'success',
          title: `Successfully ${status}!`,
          text: `User ${status} Successfully.`,
          customClass: {
            confirmButton: 'btn btn-success'
          }
        });
        veriAl();
      },
      error: function (err) {
        offCanvasForm.offcanvas('hide');
        Swal.fire({
          title: 'Hata var!',
          text: 'Kaydedilmedi!',
          icon: 'error',
          customClass: {
            confirmButton: 'btn btn-success'
          }
        });
      }
    });
  });

  // clearing form data when offcanvas hidden
  offCanvasForm.on('hidden.bs.offcanvas', function () {
    fv.resetForm(true);
  });

  $('#exportExcelButton1').click(function() {
    var searchValue = dt_record.search();
    alert(searchValue);
    var url = '/export/excel?search=' + encodeURIComponent(searchValue);
    // Excel'i dışa aktaran rotaya AJAX isteği gönderiyoruz
    $.ajax({
        url: url, // Laravel rota URL'si
        type: 'GET',
        xhrFields: {
            responseType: 'blob' // Blob olarak cevabı almak için
        },
        success: function(response) {
            var blob = new Blob([response], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });
            var link = document.createElement('a');
            link.href = window.URL.createObjectURL(blob);
            link.download = 'kalite2.xlsx'; // İndirme dosyasının adı
            link.click();
        },
        error: function(xhr, status, error) {
            console.error('Excel export failed:', error);
        }
    });
});
});
