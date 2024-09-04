'use strict';
import Swal from 'sweetalert2';
import ExcelJS from 'exceljs';

// // Datatable (jquery)
$(function () {
  document.getElementById('baslik').innerHTML = 'Mamul Kartları';

  var dt_table = $('.datatables-mamuller'),
    select1 = $('#STGRPKOD'),
    select2 = $('#MMLGRPKOD'),
    select3 = $('#SINIF'),
    offCanvasForm = $('#offcanvasAddRecord');

  if (select1.length) {
    var $this = select1;
    $this.wrap('<div class="position-relative"></div>').select2({
      tags: true,
      placeholder: 'Seçin',
      allowClear: true,
      dropdownParent: $this.parent()
    });
  }
  if (select2.length) {
    var $this = select2;
    $this.wrap('<div class="position-relative"></div>').select2({
      tags: true,
      placeholder: 'Seçin',
      allowClear: true,
      dropdownParent: $this.parent()
    });
  }
  if (select3.length) {
    var $this = select3;
    $this.wrap('<div class="position-relative"></div>').select2({
      tags: true,
      placeholder: 'Seçin',
      allowClear: true,
      dropdownParent: $this.parent()
    });
  }

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
      paging: false,
      info: true,
      scrollCollapse: false,
      scrollY: '65vh',
      infoCallback: function(settings, start, end, max, total, pre) {
        return ' Listelenen kayıt sayısı:   ' + end;
    },
      ajax: {
        url: baseUrl + 'mamul-list'
      },
      columns: [
        // columns according to JSON
        { data: 'fake_id' },
        { data: 'KOD' },
        { data: 'TANIM' },
        { data: 'STGRPKOD' },
        { data: 'MMLGRPKOD' },
        { data: 'SINIF' },
        { data: 'AKTIF' },
        { data: 'ID' },
        { data: 'eylem' }
      ],
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
      order: [[7, 'desc']],
      language: {
        search: '',
        searchPlaceholder: 'Ara',
      },
      dom:
        '<"row"' +
        '<"col-md-2"<"ms-n2"l>>' +
        '<"col-md-10"<"dt-action-buttons text-xl-end text-lg-start text-md-end text-start d-flex align-items-center justify-content-end flex-md-row flex-column mb-6 mb-md-0 mt-n6 mt-md-0"fB>>' +
        '>t' +
        '<"row"' +
        '<"col-sm-12 col-md-6"i>' +
        '<"col-sm-12 col-md-6"p>' +
        '>',
      columnDefs: [
        {
          //For Responsive
          className: 'control',
          searchable: false,
          orderable: false,
          responsivePriority: 1,
          targets: 0,
          render: function (data, type, row, meta) {
            return ``;
          }
        },
        {
          //targets: 0,
          orderable: false,
          responsivePriority: 3,
          searchable: false,
          className: 'dt-body-center',
          render: function (data, type, row, meta) {
            return `<span style="font-weight: bold; color: limegreen;">${full.fake_id}</span>`;
          }
        },
        {
          targets: 1, //kod
          responsivePriority: 1,
          className: 'dt-body-left'
        },
        {
          targets: 2, //mamul
          responsivePriority: 3
        },
        {
          targets: 3, //grup
          responsivePriority: 2
        },
        {
          targets: 4, //anamamul
          responsivePriority: 1
        },
        {
          targets: 5, //sinif
          responsivePriority: 1
        },
        {
          targets: 6, //aktif
          className: 'dt-body-center',
          responsivePriority: 3,
          render: function (data, type, full, meta) {
            var $verified = full['AKTIF'];
            return `${
              $verified == 1
                ? '<i class="ti fs-4 ti-shield-check text-success" style="font-size: 13px; line-height: 0.7; vertical-align: middle;"></i>'
                : '<i class="ti fs-4 ti-shield-x text-danger" style="font-size: 13px; line-height: 0.7; vertical-align: middle;"></i>'
            }`;
          }
        },
        {
          targets: 7, //id
          className: 'dt-body-center',
          responsivePriority: 5,
          visible: false
        },
        {
          // Actions
          targets: -1,
          searchable: false,
          responsivePriority: 4,
          orderable: false,
          render: function (data, type, full, meta) {
            return (
              '<div class="d-flex align-items-center">' +
              `<i class="ti ti-edit edit-record" data-id="${full['ID']}" data-bs-toggle="offcanvas" data-bs-target="#offcanvasAddRecord" style="font-size: 20px; line-height:0.7; vertical-align: middle;"></i>` +
              `<i class="ti ti-trash delete-record" data-id="${full['ID']}" style="font-size: 20px; line-height: 0.7; vertical-align: middle;"></i>` +
              '</div>'
            );
          }
        }
      ],
      // For responsive popup
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
        $.ajax({
          type: 'DELETE',
          url: `${baseUrl}mamul-list/${temp_id}`,
          success: function () {
            dt_record.draw();
          },
          error: function (error) {
            console.log(error);
          }
        });
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

    fetchDataAndPopulateForm(kayit_id);
  });

  async function fetchDataAndPopulateForm(kayit_id) {
    try {
      const response = await fetch(`${baseUrl}mamul-list/${kayit_id}/edit`);
      const data = await response.json();

      // console.log(data[0].MMLGRPKOD);
      // console.log($('#MMLGRPKOD').find('option').map(function() { return this.value; }).get());

      $('#record_id').val(data[0].ID);
      $('#TANIM').val(data[0].TANIM);
      $('#KOD').val(data[0].KOD);
      $('#STGRPKOD').val(data[0].STGRPKOD).trigger('change');
      $('#MMLGRPKOD').val(data[0].MMLGRPKOD).trigger('change');
      $('#SINIF').val(data[0].SINIF).trigger('change');
      $('#AKTIF').prop('checked', data[0].AKTIF == '1' ? true : false);
    } catch (error) {
      console.error('Veri çekme hatası:', error);
    }
  }

  // changing the title
  $('.add-new').on('click', function () {
    $('#record_id').val(''); //reseting input field
    $('#TANIM').val('');
    $('#KOD').val('');
    $('#STGRPKOD').val('').trigger('change');
    $('#MMLGRPKOD').val('').trigger('change');
    $('#SINIF').val('').trigger('change');
    $('#AKTIF').prop('checked', false);
    $('#offcanvasAddLabel').html('Kayıt Ekle');
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
      TANIM: {
        validators: {
          notEmpty: {
            message: 'Lütfen Tanım Giriniz'
          }
        }
      },
      KOD: {
        validators: {
          notEmpty: {
            message: 'Lütfen Kod Giriniz'
          }
        }
      },
      STGRPKOD: {
        validators: {
          notEmpty: {
            message: 'Lütfen Grup Kodunu Giriniz'
          }
        }
      },
      MMLGRPKOD: {
        validators: {
          notEmpty: {
            message: 'Lütfen Ana Mamulü Giriniz'
          }
        }
      },
      SINIF: {
        validators: {
          notEmpty: {
            message: 'Lütfen Sınıfını Giriniz'
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
      url: `${baseUrl}mamul-list`,
      type: 'POST',
      success: function (status) {
        dt_record.draw();
        offCanvasForm.offcanvas('hide');
        Swal.fire({
          icon: 'success',
          title: `Kayıt Başarılı!`,
          text: `Mamul başarıyla kaydedildi!`,
          customClass: {
            confirmButton: 'btn btn-success'
          }
        });
      },
      error: function (err) {
        offCanvasForm.offcanvas('hide');
        Swal.fire({
          title: `Hatalı ${err}!`,
          text: 'Kaydedilmedi! ',
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

  $('.export').on('click', function () {
    var searchValue = dt_record.search(); // DataTable'dan arama değerini al
    $.ajax({
      url: '/exportmamul/excel?search=' + encodeURIComponent(searchValue),
      type: 'GET',
      dataType: 'json', // Cevabın JSON formatında olduğunu belirtir
      success: function (response) {
        var workbook = new ExcelJS.Workbook();
        var worksheet = workbook.addWorksheet('MamulKartlari');

        // Başlık satırını ekleyin
        worksheet.columns = [
          { header: 'KOD', key: 'KOD', width: 25 },
          { header: 'TANIM', key: 'TANIM', width: 55 },
          { header: 'STOK GRUP KODU', key: 'STGRPKOD', width: 30 },
          { header: 'ANA MAMUL TANIMI', key: 'MMLGRPKOD', width: 30 },
          { header: 'SINIFI', key: 'SINIF', width: 15 },
          { header: 'AKTİF', key: 'AKTIF', width: 10 }
        ];
        worksheet.getColumn('F').alignment = { horizontal: 'center' };

        // Excel dosyasına verileri ekleyin
        response.forEach(function (veri) {
          worksheet.addRow({
            KOD: veri.KOD,
            TANIM: veri.TANIM,
            STGRPKOD: veri.STGRPKOD,
            MMLGRPKOD: veri.MMLGRPKOD,
            SINIF: veri.SINIF,
            AKTIF: veri.AKTIF,
          });
        });

        worksheet.autoFilter = {
          from: 'A1',
          to: 'F1'
        };

        // Excel dosyasını Blob olarak yazın
        workbook.xlsx
          .writeBuffer()
          .then(function (data) {
            var blob = new Blob([data], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });
            var link = document.createElement('a');
            link.href = URL.createObjectURL(blob);
            link.download = 'MamulKartlari.xlsx';
            link.click();
          })
          .catch(function (error) {
            console.error('Error writing Excel file:', error);
          });
      },
      error: function (xhr, status, error) {
        console.error('Excel export failed:', error);
      }
    });
  });
});
