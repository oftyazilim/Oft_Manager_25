/**
 * Page User List
 */

'use strict';
//import { error } from 'jquery';
// import { size } from 'lodash';
import Swal from 'sweetalert2';
import ExcelJS from 'exceljs';

// // Datatable (jquery)
$(function () {
  // var script = document.createElement('script');
  // script.src = 'https://cdnjs.cloudflare.com/ajax/libs/exceljs/4.3.0/exceljs.min.js';
  // script.src = "https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"
  // document.head.appendChild(script);

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
      paging: false,
      scrollCollapse: true,
      scrollY: '50vh',
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
      order: [[15, 'desc']],
      dom:
        '<"row"' +
        '<"col-md-2"<"ms-n2"l>>' +
        '<"col-md-10"<"dt-action-buttons text-xl-end text-lg-start text-md-end text-start d-flex align-items-center justify-content-end flex-md-row flex-column mb-6 mb-md-0 mt-n6 mt-md-0"fB>>' +
        '>t' +
        '<"row"' +
        '<"col-sm-12 col-md-6"i>' +
        '<"col-sm-12 col-md-6"p>' +
        '>',
      // lengthMenu: [10, 15, 20, 50, 70, 100], //for length of menu
      // language: {
      //   sLengthMenu: '_MENU_',
      //   search: '',
      //   searchPlaceholder: 'Ara',
      //   info: 'Kayıt: _END_ / _TOTAL_ ',
      //   paginate: {
      //     next: '<i class="ti ti-chevron-right ti-sm"></i>',
      //     previous: '<i class="ti ti-chevron-left ti-sm"></i>'
      //   }
      // },
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
          targets: 1, //mamul
          responsivePriority: 1,
          className: 'dt-body-left'
        },
        {
          targets: 2, //boy
          responsivePriority: 3,
          className: 'text-end'
        },
        {
          targets: 3, //adet2
          className: 'dt-body-center',
          responsivePriority: 2
        },
        {
          targets: 4, //kantarkg
          responsivePriority: 1,
          className: 'dt-body-right',
          render: function (data, type, full, meta) {
            return `<span style="font-weight: bold; color: limegreen;">${full.kantarkg}</span>`;
          }
        },
        {
          targets: 5, //adet
          responsivePriority: 2,
          className: 'dt-body-right',
          render: function (data, type, row, meta) {
            return data;
          }
        },
        {
          targets: 6, //kg
          responsivePriority: 4,
          className: 'dt-body-right',
          render: function (data, type, row, meta) {
            return data;
          }
        },
        {
          targets: 7, //nevi
          responsivePriority: 5,
          className: 'dt-body-center',
          render: function (data, type, full, meta) {
            var $nevi = full['nevi'];
            return `${
              $nevi == 'BYL'
                ? `<span style="color: brown; font-weight: bold;">${full.nevi}</span>`
                : `<span >${full.nevi}</span>`
            }`;
          }
        },
        {
          targets: 8, //pkno
          responsivePriority: 4,
          className: 'dt-body-center',
          width: '100%',
          render: function (data, type, full, meta) {
            return `<span style="white-space: nowrap">${full.pkno}</span>`;
          }
        },
        {
          targets: 9, //hat
          responsivePriority: 5,
          className: 'dt-body-center',
          render: function (data, type, full, meta) {
            return `<span style="white-space: nowrap">${full.hat}</span>`;
          }
        },
        {
          targets: 10, //tarih
          responsivePriority: 4,
          className: 'dt-body-center',
          render: function (data, type, full, meta) {
            return `<span style="white-space: nowrap">${full.tarih}</span>`;
          }
        },
        {
          targets: 11, //saat
          responsivePriority: 5,
          className: 'dt-body-center',
          render: function (data, type, row, meta) {
            return data;
          }
        },
        {
          targets: 12, //operator
          responsivePriority: 5,
          className: 'dt-body-center',
          render: function (data, type, row) {
            return '<span style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">' + data + '</span>';
        }
        },
        {
          targets: 13, //mamulkodu
          responsivePriority: 5,
          className: 'dt-body-center',
          render: function (data, type, full, meta) {
            return `<span style="white-space: nowrap">${full.mamulkodu}</span>`;
          }
        },
        {
          targets: 14, //basildi
          className: 'dt-body-center',
          responsivePriority: 3,
          render: function (data, type, full, meta) {
            var $verified = full['basildi'];
            return `${
              $verified == 1
                ? '<i class="ti fs-4 ti-shield-check text-success" style="font-size: 13px; line-height: 0.7; vertical-align: middle;"></i>'
                : '<i class="ti fs-4 ti-shield-x text-danger" style="font-size: 13px; line-height: 0.7; vertical-align: middle;"></i>'
            }`;
          }
        },
        {
          targets: 15, //id
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
              `<i class="ti ti-edit edit-record" data-id="${full['id']}" data-bs-toggle="offcanvas" data-bs-target="#offcanvasAddRecord" style="font-size: 20px; line-height:0.7; vertical-align: middle;"></i>` +
              `<i class="ti ti-trash delete-record" data-id="${full['id']}" style="font-size: 20px; line-height: 0.7; vertical-align: middle;"></i>` +
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
              return data['mamul'];
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
        document.getElementById('toplamHr').innerHTML = data[2] + '<span style="font-size: 14px;"> Kg</span>';
        document.getElementById('toplamDiger').innerHTML = data[3] + '<span style="font-size: 14px;"> Kg</span>';
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
      // console.log(data[0]);
      $('#record_id').val(data[0].id);
      $('#mamul').val(data[0].mamul);
      $('#boy').val(data[0].boy);
      $('#kantarkg').val(data[0].kantarkg);
      $('#adet2').val(data[0].adet2);
      $('#hat').val(data[0].hat);
      $('#basildi').prop('checked', data[0].basildi=='1' ? true : false);
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

  $('.export').on('click', function () {

    var searchValue = dt_record.search(); // DataTable'dan arama değerini al
    $.ajax({
      url: '/export/excels?search=' + encodeURIComponent(searchValue),
      type: 'GET',
      dataType: 'json', // Cevabın JSON formatında olduğunu belirtir
      success: function (response) {
        // var Excel = require('exceljs');
        var workbook = new ExcelJS.Workbook();
        var worksheet = workbook.addWorksheet('Kalite2');

        // Başlık satırını ekleyin
        worksheet.columns = [
          { header: 'MAMUL', key: 'mamul', width: 15 },
          { header: 'BOY', key: 'boy', width: 10 },
          { header: 'NEVİ', key: 'nevi', width: 10 },
          { header: 'GRÇ. AD', key: 'adet2', width: 10 },
          { header: 'GRÇ. KG', key: 'kantarkg', width: 10 },
          { header: 'SYS. AD', key: 'adet', width: 10 },
          { header: 'SYS. KG', key: 'kg', width: 10 },
          { header: 'PAKET NO', key: 'pkno', width: 20 },
          { header: 'TARİH', key: 'tarih', width: 15 },
          { header: 'SAAT', key: 'saat', width: 10 },
          { header: 'OPERATÖR', key: 'operator', width: 15 },
          { header: 'MAMUL KODU', key: 'mamulkodu', width: 15 },
          { header: 'BASILDI', key: 'basildi', width: 10 }
        ];
        worksheet.getColumn('C').alignment = {horizontal: 'center'} ;
        worksheet.getColumn('H').alignment = {horizontal: 'center'} ;
        worksheet.getColumn('I').alignment = {horizontal: 'center'} ;
        worksheet.getColumn('L').alignment = {horizontal: 'center'} ;
        worksheet.getColumn('M').alignment = {horizontal: 'center'} ;
        worksheet.getColumn('K').alignment = {horizontal: 'center'} ;

        // Excel dosyasına verileri ekleyin
        response.forEach(function (veri) {
          // var tarih = Math.floor(new Date(veri.tarih).getTime() / 1000);
          // Diyelim ki tarih zaman damgasını hesapladık:
          var unixTimestamp = Math.floor(new Date(veri.tarih).getTime() / 1000);

          // Excel tarih formatına dönüştürme
          var excelDate = 25569 + ((unixTimestamp + 10800) / 86400);

          // Geri dönüşüm işlemi - Unix zaman damgasına çevirme
          var convertedUnixTimestamp = (excelDate - 25569) * 86400 - 10800;

          // Unix zaman damgasını Date objesine çevirme
          var tarih = new Date(convertedUnixTimestamp * 1000);

          // Tarihi YYYY-MM-DD formatında gösterme
          // var tarih = tarihObjesi.toISOString().split('T')[0];
          worksheet.addRow({
            mamul: veri.mamul,
            boy: veri.boy,
            nevi: veri.nevi,
            adet2: parseInt(veri.adet2),
            kantarkg: parseFloat(veri.kantarkg),
            adet: parseInt(veri.adet),
            kg: parseFloat(veri.kg),
            pkno: veri.pkno,
            tarih: tarih,
            saat: veri.saat,
            operator: veri.operator,
            mamulkodu: veri.mamulkodu,
            basildi: veri.basildi
          });
        });

        worksheet.autoFilter = {
          from: 'A1',
          to: 'N1',
         }

        // Excel dosyasını Blob olarak yazın
        workbook.xlsx
        .writeBuffer()
        .then(function (data) {
          var blob = new Blob([data], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });
            var link = document.createElement('a');
            link.href = URL.createObjectURL(blob);
            link.download = 'kalite2.xlsx';
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







    // var searchValue = dt_record.search();
    // // alert(encodeURIComponent(searchValue));
    // var url = '/export/excel?search=' + searchValue;
    // // Excel'i dışa aktaran rotaya AJAX isteği gönderiyoruz
    // $.ajax({
    //   url: url, // Laravel rota URL'si
    //   type: 'GET',
    //   xhrFields: {
    //     responseType: 'blob' // Blob olarak cevabı almak için
    //   },
    //   success: function (response) {
    //     var blob = new Blob([response], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });
    //     var link = document.createElement('a');
    //     link.href = window.URL.createObjectURL(blob);
    //     link.download = 'kalite2.xlsx'; // İndirme dosyasının adı
    //     link.click();
    //   },
    //   error: function (xhr, status, error) {
    //     console.error('Excel export failed:', error);
    //   }
    // });
  });

  // document.getElementById('exportExcelButton1').addEventListener('click', function () {
  //   var searchValue = dt_record.search(); // DataTable'dan arama değerini al
  //   $.ajax({
  //     url: '/export/excel1?search=' + encodeURIComponent(searchValue),
  //     type: 'GET',
  //     dataType: 'json', // Cevabın JSON formatında olduğunu belirtir
  //     success: function (response) {
  //       var workbook = new ExcelJS.Workbook();
  //       var worksheet = workbook.addWorksheet('Kalite2');

  //       // Başlık satırını ekleyin
  //       worksheet.columns = [
  //         { header: 'MAMÜL', key: 'mamul', width: 10 },
  //         { header: 'BOY', key: 'boy', width: 10 },
  //         { header: 'NEVİ', key: 'nevi', width: 10 },
  //         { header: 'GRÇ. AD', key: 'adet2', width: 10 },
  //         { header: 'GRÇ. KG', key: 'kantarkg', width: 15 },
  //         { header: 'SYS. AD', key: 'adet', width: 10 },
  //         { header: 'SYS. KG', key: 'kg', width: 10 },
  //         { header: 'PAKET NO', key: 'pkno', width: 10 },
  //         { header: 'TARİH', key: 'tarih', width: 15 },
  //         { header: 'SAAT', key: 'saat', width: 10 },
  //         { header: 'OPERATÖR', key: 'operator', width: 15 },
  //         { header: 'MAMÜL KODU', key: 'mamulkodu', width: 15 },
  //         { header: 'BASILDI', key: 'basildi', width: 10 },
  //         { header: 'ID', key: 'id', width: 10 }
  //       ];

  //       // Excel dosyasına verileri ekleyin
  //       response.forEach(function (veri) {
  //         worksheet.addRow({
  //           mamul: veri.mamul,
  //           boy: veri.boy,
  //           nevi: veri.nevi,
  //           adet2: parseInt(veri.adet2),
  //           kantarkg: parseFloat(veri.kantarkg),
  //           adet: parseInt(veri.adet),
  //           kg: parseFloat(veri.kg),
  //           pkno: veri.pkno,
  //           tarih: veri.tarih, // Bu alanı tarih olarak formatlamak gerekebilir
  //           saat: veri.saat,
  //           operator: veri.operator,
  //           mamulkodu: veri.mamulkodu,
  //           basildi: veri.basildi,
  //           id: veri.id
  //         });
  //       });

  //       // Excel dosyasını Blob olarak yazın
  //       workbook.xlsx
  //         .writeBuffer()
  //         .then(function (data) {
  //           var blob = new Blob([data], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });
  //           var link = document.createElement('a');
  //           link.href = URL.createObjectURL(blob);
  //           link.download = 'kalite2.xlsx';
  //           link.click();
  //         })
  //         .catch(function (error) {
  //           console.error('Error writing Excel file:', error);
  //         });
  //     },
  //     error: function (xhr, status, error) {
  //       console.error('Excel export failed:', error);
  //     }
  //   });
  // });
});
