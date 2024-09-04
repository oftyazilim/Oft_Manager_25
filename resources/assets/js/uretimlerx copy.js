'use strict';
import Swal from 'sweetalert2';
import ExcelJS from 'exceljs';
import DataTable from 'datatables.net-bs5';

// // Datatable (jquery)
$(function () {
  document.getElementById('baslik').innerHTML = 'Üretim Girişleri';

  var dt_table = $('.datatables-uretimler');

  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });

  if (dt_table.length) {
    var dt_record = dt_table.DataTable({
      processing: true,
      serverSide: true,
      paging: false,
      statesave: true,
      info: true,
      scrollCollapse: false,
      scrollY: '65vh',
      infoCallback: function (settings, start, end, max, total, pre) {
        return ' Listelenen kayıt sayısı:   ' + end;
      },
      ajax: {
        url: baseUrl + 'uretim-list',
      },
      columns: [
        { data: 'fake_id' },
        { data: 'ISTASYONID' },
        { data: 'ISEMRIID' },
        { data: 'STOKID' },
        { data: 'KOD' },
        { data: 'TANIM' },
        { data: 'MMLGRPKOD' },
        { data: 'MIKTAR' },
        { data: 'KAYITTARIH' },
        { data: 'NOTLAR' },
        { data: 'ID' },
        { data: 'EYLEM' }
      ],
      dom:
        '<"row"' +
        '<"col-12 col-md-6 d-flex align-items-center justify-content-center justify-content-md-start gap-2"l<"dt-action-buttons text-xl-end text-lg-start text-md-end text-start"B>>' +
        '>t' +
        '<"row"' +
        '<"col-sm-12 col-md-6"i>' +
        '<"col-sm-12 col-md-6"p>' +
        '>',
      buttons: [
        {
          text: '<i class="ti ti-arrow-right me-0 me-sm-1 "></i><span class="d-none d-sm-inline-block">Excel</span>',
          className: 'export btn btn-succes waves-effect waves-light ms-2 me-2',
          attr: {
            'data-bs-target': '#exportExcelButton'
          }
        }
      ],
      language: {
        search: '',
        searchPlaceholder: 'Ara'
      },
      order: [[3, 'asc']],
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
          targets: 1, //ISTASYONID
          responsivePriority: 5,
          visible: false,
          className: 'dt-body-left'
        },
        {
          targets: 2, //ISEMRIID
          responsivePriority: 5,
          visible: false
        },
        {
          targets: 3, //STOKID
          responsivePriority: 5,
          visible: false
        },
        {
          targets: 4, //KOD
          responsivePriority: 5
        },
        {
          targets: 5, //TANIM
          responsivePriority: 1
        },
        {
          targets: 6, //MMLGRPKOD
          responsivePriority: 3
        },
        {
          targets: 7, //MIKTAR
          responsivePriority: 3
        },
        {
          targets: 8, //KAYITTARIH
          className: 'dt-body-center',
          render: DataTable.render.date(),
          responsivePriority: 1
        },
        {
          targets: 9, //NOTLAR
          className: 'dt-body-right',
          responsivePriority: 1
        },
        {
          targets: 10, //ID
          className: 'dt-body-right',
          responsivePriority: 5,
          orderable: true,
          visible: true
        },
        {
          // Actions
          targets: -1,
          searchable: false,
          responsivePriority: 2,
          orderable: false,
          render: function (data, type, full, meta) {
            return (
              '<div class="d-flex align-items-center">' +
              '<div class="d-flex align-items-center">' +
              `<i class="ti ti-edit edit-record" data-id="${full['ID']}" style="font-size: 20px; line-height:0.7; vertical-align: middle;"></i>` +
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
    // var temp_id = $(this).data('id'),
    //   dtrModal = $('.dtr-bs-modal.show');

    // // hide responsive modal in small screen
    // if (dtrModal.length) {
    //   dtrModal.modal('hide');
    // }

    // // sweetalert for confirmation of delete
    // Swal.fire({
    //   title: 'Emin misiniz?',
    //   text: 'Bu işlemi geri alamayacaksınız!',
    //   icon: 'warning',
    //   showCancelButton: true,
    //   confirmButtonText: 'Evet, Silebilirsin!',
    //   cancelButtonText: 'Vazgeç',
    //   customClass: {
    //     confirmButton: 'btn btn-primary me-3',
    //     cancelButton: 'btn btn-label-secondary'
    //   },
    //   buttonsStyling: false
    // }).then(function (result) {
    //   if (result.value) {
    //     $.ajax({
    //       type: 'DELETE',
    //       url: `${baseUrl}emir-list/${temp_id}`,
    //       success: function () {
    //         dt_record.draw();
    //       },
    //       error: function (error) {
    //         console.log(error);
    //       }
    //     });
    //   } else if (result.dismiss === Swal.DismissReason.cancel) {
    //     Swal.fire({
    //       title: 'Vazgeçildi',
    //       text: 'Kayıt silinmedi!',
    //       icon: 'error',
    //       confirmButtonText: 'Kapat',
    //       customClass: {
    //         confirmButton: 'btn btn-success'
    //       }
    //     });
    //   }
    // });
  });

  // edit record
  $(document).on('click', '.edit-record', function () {
    // var kayit_id = $(this).data('id'),
    //   dtrModal = $('.dtr-bs-modal.show');

    // // hide responsive modal in small screen
    // if (dtrModal.length) {
    //   dtrModal.modal('hide');
    // }
    // // changing the title of offcanvas
    // $('#offcanvasAddLabel').html('Kayıt Düzeltme');

    // fetchDataAndPopulateForm(kayit_id);

    // if (kayit_id) {
    //   document.getElementById('TANIM').disabled = false;
    //   document.getElementById('PLANLANANMIKTAR').disabled = false;
    //   document.getElementById('DURUM').disabled = false;
    //   document.getElementById('AKTIF').disabled = false;
    // }
  });

  async function fetchDataAndPopulateForm(kayit_id) {
    try {
      const response = await fetch(`${baseUrl}emir-list/${kayit_id}/edit`);
      const veri = await response.json();

      fetch(`/emir-list/mamulal/${veri[0].ISTTANIM}`)
        .then(response => response.json())
        .then(data => {
          const select2 = document.getElementById('TANIM');
          select2.innerHTML = '<option value="">Seçiniz</option>';
          data.forEach(option => {
            if (option.KOD == veri[0].KOD) {
              select2.innerHTML += `<option value="${option.KOD}" data-info="${option.MMLGRPKOD}" selected>${option.KOD} - ${option.TANIM}</option>`;
            } else
              select2.innerHTML += `<option value="${option.KOD}" data-info="${option.MMLGRPKOD}">${option.KOD} - ${option.TANIM}</option>`;
          });
        });

      $('#record_id').val(veri[0].ID);

      $('#ISTKOD').val(veri[0].ISTASYONID).trigger('change');
      $('#TANIM').text(veri[0].TANIM).trigger('change');
      $('#KOD').val(veri[0].KOD);
      $('#MMLGRPKOD').val(veri[0].MMLGRPKOD).trigger('change');
      $('#PLANLANANMIKTAR').val(veri[0].PLANLANANMIKTAR);
      $('#DURUM').val(veri[0].DURUM).trigger('change');
      $('#NOTLAR').val(veri[0].NOTLAR);
      $('#URETIMSIRA').val(veri[0].URETIMSIRA);
      $('#AKTIF').prop('checked', veri[0].AKTIF == '1' ? true : false);
    } catch (error) {
      console.error('Veri çekme hatası:', error);
    }
  }

  // Filter form control to default size
  // ? setTimeout used for multilingual table initialization
  setTimeout(() => {
    $('.dataTables_filter .form-control').removeClass('form-control-sm');
    $('.dataTables_length .form-select').removeClass('form-select-sm');
  }, 300);

  // clearing form data when offcanvas hidden
  // offCanvasForm.on('hidden.bs.offcanvas', function () {
  //   fv.resetForm(true);
  // });

  $('.export').on('click', function () {
    var searchValue = dt_record.search();
    $.ajax({
      url: '/exportemir/excel?search=' + encodeURIComponent(searchValue),
      type: 'GET',
      dataType: 'json',
      success: function (response) {
        var workbook = new ExcelJS.Workbook();
        var worksheet = workbook.addWorksheet('IsEmirleri');

        // Başlık satırını ekleyin
        worksheet.columns = [
          { header: 'KOD', key: 'KOD', width: 20 },
          { header: 'TANIM', key: 'TANIM', width: 55 },
          { header: 'STOK GRUP KODU', key: 'ISTKOD', width: 18 },
          { header: 'ANA MAMUL TANIMI', key: 'MMLGRPKOD', width: 30 },
          { header: 'PLANLANAN MİKTAR', key: 'PLANLANANMIKTAR', width: 15 },
          { header: 'ÜRETİM MİKTARI', key: 'URETIMMIKTAR', width: 15 },
          { header: 'DURUM', key: 'DURUM', width: 10 },
          { header: 'NOTLAR', key: 'DURUM', width: 10 },
          { header: 'URETIMSIRA', key: 'DURUM', width: 10 },
          { header: 'AKTİF', key: 'AKTIF', width: 10 }
        ];
        worksheet.getColumn('G').alignment = { horizontal: 'center' };

        // Excel dosyasına verileri ekleyin
        response.forEach(function (veri) {
          worksheet.addRow({
            KOD: veri.KOD,
            TANIM: veri.TANIM,
            ISTKOD: veri.ISTKOD,
            MMLGRPKOD: veri.MMLGRPKOD,
            PLANLANANMIKTAR: veri.PLANLANANMIKTAR,
            URETIMMIKTAR: veri.URETIMMIKTAR,
            DURUM: veri.DURUM,
            NOTLAR: veri.NOTLAR,
            URETIMSIRA: veri.URETIMSIRA,
            AKTIF: veri.AKTIF
          });
        });

        worksheet.autoFilter = {
          from: 'A1',
          to: 'H1'
        };

        // Excel dosyasını Blob olarak yazın
        workbook.xlsx
          .writeBuffer()
          .then(function (data) {
            var blob = new Blob([data], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });
            var link = document.createElement('a');
            link.href = URL.createObjectURL(blob);
            link.download = 'IsEmirleri.xlsx';
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

  $(document).on('click', '.uretim-gir', function () {
    var kayit_id = $(this).data('id');

    $('#modalCenterTitle').html('Üretim Girişi');

    $.get(`${baseUrl}emir-list\/${kayit_id}\/edit`, function (data) {
      $('#rec_id').val(kayit_id);
      $('#urun_id').val(data[0].ID);
      $('#mamul').val(data[0].TANIM);
      $('#URETIMMIKTAR').val('');
      $('#NOTLAR1').val('');
      var today = new Date().toISOString().split('T')[0];
      $('#TARIH').val(today);
      document.getElementById('URETIMMIKTAR').focus();
    });

    myModal.show();

    $(document).on('shown.bs.modal', '#modalCenter', function () {
      document.getElementById('URETIMMIKTAR').focus();
    });
  });

  $(document).on('click', '#btnKaydet', function () {
    var id = $('#rec_id').val();
    var mamul = $('#mamul').val();
    var tarih = $('#TARIH').val();
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
      mamul: mamul,
      tarih: tarih,
      notlar: notlar,
      uretim_miktar: uretimMiktar
    };

    // AJAX request to save the data
    $.ajax({
      url: `${baseUrl}emir/uretimkaydet`, // Your Laravel route to handle saving
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
