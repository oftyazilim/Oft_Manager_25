'use strict';
import Swal from 'sweetalert2';
import ExcelJS from 'exceljs';
import DataTable from 'datatables.net-bs5';

// Datatable (jquery)
$(function () {
  document.getElementById('baslik').innerHTML = 'Üretim Girişleri';

  var dt_table = $('.datatables-uretimler');
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
      infoCallback: function (settings, start, end, max, total, pre) {
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
          visible: false,
          render: function (data, type, full, meta) {
            return `<span>${full.fake_id}</span>`;
          }
        },
        {
          // ISTKOD
          targets: 1,
          responsivePriority: 4
        },
        {
          // ISEMRIID
          targets: 2,
          responsivePriority: 4
        },
        {
          // STOKID
          targets: 3,
          responsivePriority: 4,
          className: 'dt-body-right',
          visible: false
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
          responsivePriority: 4
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
          visible: true
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
              `<button class="btn btn-sm btn-icon edit-record btn-text-secondary rounded-pill waves-effect" data-id="${full['ID']}" data-bs-target="#modalCenter"><i class="ti ti-edit"></i></button>` +
              `<button class="btn btn-sm btn-icon delete-record btn-text-secondary rounded-pill waves-effect" data-id="${full['ID']}"><i class="ti ti-trash"></i></button>` +
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

  // Delete Record
  $(document).on('click', '.delete-record', function () {
    var temp_id = $(this).data('id');

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
          url: `${baseUrl}uretim-list/${temp_id}`,
          success: function () {
            dt_record.draw();
          },
          error: function (error) {
            console.log(error);
          }
        });

        // success sweetalert
        Swal.fire({
          icon: 'success',
          title: 'Silindi!',
          text: 'Kullanıcı silindi',
          confirmButtonText: 'Kapat',
          customClass: {
            confirmButton: 'btn btn-success'
          }
        });
      } else if (result.dismiss === Swal.DismissReason.cancel) {
        Swal.fire({
          title: 'Vazgeçildi',
          text: 'Kullanıcı silinmedi!',
          icon: 'error',
          confirmButtonText: 'Kapat',
          customClass: {
            confirmButton: 'btn btn-success'
          }
        });
      }
    });
  });

  // // edit record
  $(document).on('click', '.edit-record', function () {
    var kayit_id = $(this).data('id');

    $('#modalCenterTitle').html('Üretim Düzeltme');

    $.get(`${baseUrl}uretim-list\/${kayit_id}\/edit`, function (data) {
      $('#rec_id').val(kayit_id);
      $('#isemriid').val(data[0].ISEMRIID);
      $('#urun_id').val(data[0].STOKID);
      $('#mamul').val(data[0].TANIM);
      $('#URETIMMIKTAR').val(data[0].MIKTAR);
      $('#miktarTemp').val(data[0].MIKTAR);
      $('#NOTLAR1').val(data[0].NOTLAR);
      var dateParts = data[0].URETIMTARIH.substring(0, 10).split('-');
      var dateObject = new Date(Date.UTC(dateParts[0], dateParts[1] - 1, dateParts[2]));
      var dateTemp = dateObject.toISOString().split('T')[0];
      $('#TARIH').val(dateTemp);
      document.getElementById('URETIMMIKTAR').focus();
    });

    myModal.show();

    $(document).on('shown.bs.modal', '#modalCenter', function () {
      document.getElementById('URETIMMIKTAR').focus();
      document.getElementById('URETIMMIKTAR').select();
    });
  });

  setTimeout(() => {
    $('.dataTables_filter .form-control').removeClass('form-control-sm');
    $('.dataTables_length .form-select').removeClass('form-select-sm');
  }, 300);

  $(document).on('click', '#btnKaydet', function () {
    var id = $('#rec_id').val();
    var mamul = $('#mamul').val();
    var isemriid = $('#isemriid').val();
    var tarih = $('#TARIH').val();
    var miktarTemp = $('#miktarTemp').val();
    var uretimMiktar = $('#URETIMMIKTAR').val();
    var notlar = $('#NOTLAR1').val();

    // Check if required fields are filled
    if (tarih === '' || uretimMiktar === '') {
      alert('Lütfen tüm alanları doldurun.');
      return;
    }

    // Construct the data object to send to the server
    var formData = {
      id: id,
      isemriid: isemriid,
      mamul: mamul,
      tarih: tarih,
      notlar: notlar,
      uretim_miktar: uretimMiktar,
      miktarTemp: miktarTemp
    };

    console.log(formData);

    // AJAX request to save the data
    $.ajax({
      url: `${baseUrl}uretim/uretimkaydet`, // Your Laravel route to handle saving
      method: 'POST',
      data: formData,
      success: function (response) {
        if (response.success) {
          // alert('Kayıt başarıyla yapıldı!');
          $('#modalCenter').modal('hide'); // Hide the modal
          dt_record.draw();
        } else {
          alert('Kayıt sırasında bir hata oluştu.');
        }
      },
      error: function () {
        alert('Sunucu hatası. Lütfen daha sonra tekrar deneyin.');
      }
    });
  });
});
