'use strict';
import Swal from 'sweetalert2';
import ExcelJS from 'exceljs';
import DataTable from 'datatables.net-bs5';

// // Datatable (jquery)
$(function () {
  document.getElementById('baslik').innerHTML = 'İş Emirleri';

  var dt_table = $('.datatables-emirler'),
    select1 = $('#TANIM'),
    select4 = $('#ISTKOD'),
    offCanvasForm = $('#offcanvasAddRecord');

  var myModalElement = document.getElementById('modalCenter');
  var displayIcons = false;

  var myModal = new bootstrap.Modal(myModalElement, {
    backdrop: true,
    keyboard: true
  });

  if (select1.length) {
    var $this = select1;
    $this.wrap('<div class="position-relative"></div>').select2({
      tags: true,
      placeholder: 'Seçiniz...',
      allowClear: true,
      selectOnClose: false,
      dropdownParent: $this.parent()
    });
  }

  if (select4.length) {
    var $this = select4;
    $this.wrap('<div class="position-relative"></div>').select2({
      tags: false,
      selectOnClose: false,
      placeholder: 'Seçiniz...',
      allowClear: true,
      dropdownParent: $this.parent()
    });
  }

  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });

  var grupSecimi = '';

  //   // Users datatable
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
        url: baseUrl + 'emir-list',
        data: function (d) {
          d.grupSecimi = grupSecimi; // Seçilen filtre değerini AJAX isteğine ekliyoruz
          // Diğer gerekli parametreler de burada eklenebilir
        }
      },
      columns: [
        // columns according to JSON
        { data: 'fake_id' },
        { data: 'URETIMSIRA' },
        { data: 'ID' },
        { data: 'ISTASYONID' },
        { data: 'URUNID' },
        { data: 'KOD' },
        { data: 'TANIM' },
        { data: 'MMLGRPKOD' },
        { data: 'PLANLANANMIKTAR' },
        { data: 'URETIMMIKTAR' },
        { data: 'PROGRESS' },
        { data: 'DURUM' },
        { data: 'NOTLAR' },
        { data: 'PROSESNOT' },
        { data: 'KAYITTARIH' },
        { data: 'ISTKOD' },
        { data: 'ISTTANIM' },
        { data: 'AKTIF' },
        { data: 'EYLEM' }
      ],
      dom:
        '<"row"' +
        '<"col-12 col-md-6 d-flex align-items-center justify-content-center justify-content-md-start gap-2"l<"dt-action-buttons text-xl-end text-lg-start text-md-end text-start"B>>' +
        '<"col-12 col-md-6 d-flex align-items-center justify-content-end flex-column flex-md-row pe-5 gap-md-4 mt-n5 mt-md-0"f<"istasyon mb-6 mb-md-0">>' +
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
      language: {
        search: '',
        searchPlaceholder: 'Ara'
      },
      order: [[8, 'asc']],
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
          targets: 1, //uretimsira
          className: 'dt-body-right',
          responsivePriority: 5,
          orderable: true,
          visible: true
        },
        {
          targets: 2, //id
          responsivePriority: 3,
          visible: true,
          className: 'dt-body-left'
        },
        {
          targets: 3, //istasyonid
          responsivePriority: 5,
          visible: false
        },
        {
          targets: 4, //urunid
          responsivePriority: 5,
          visible: false
        },
        {
          targets: 5, //kod
          responsivePriority: 5
        },
        {
          targets: 6, //tanim
          responsivePriority: 1
        },
        {
          targets: 7, //mmlgrpkod
          responsivePriority: 3
        },
        {
          targets: 8, //planmiktar
          className: 'dt-body-right',
          responsivePriority: 1
        },
        {
          targets: 9, //uretimmiktar
          className: 'dt-body-right',
          responsivePriority: 1
        },
        {
          targets: 10, // İlerleme
          responsivePriority: 3,
          render: function (data, type, full, meta) {
            var $status_number = full['PROGRESS'];
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
          targets: 11, //durum
          responsivePriority: 3,
          className: 'dt-body-center',
          render: function (data, type, full, meta) {
            var $verified = full['DURUM'];
            return `${
              $verified == 'Üretimde'
                ? '<i class="ti fs-4 ti-shield-check text-success" style="font-size: 13px; line-height: 0.7; vertical-align: middle;"></i>'
                : '<i class="ti fs-4 ti-shield-x text-danger" style="font-size: 13px; line-height: 0.7; vertical-align: middle;"></i>'
            }`;
          }
        },
        {
          targets: 12, //notlar
          responsivePriority: 4,
          visible: false
        },
        {
          targets: 13, //prosesnot
          responsivePriority: 4,
          visible: false
        },
        {
          targets: 14, //tarih
          responsivePriority: 1,
          render: DataTable.render.date()
        },
        {
          targets: 15, //istkod
          responsivePriority: 3,
          className: 'dt-body-center'
        },
        {
          targets: 16, //isttanim
          responsivePriority: 3,
          visible: false
        },
        {
          targets: 17, //aktif
          className: 'dt-body-center',
          responsivePriority: 3,
          visible: false
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
              `<i class="ti ti-edit edit-record" data-id="${full['ID']}" data-bs-toggle="offcanvas" data-bs-target="#offcanvasAddRecord" style="font-size: 20px; line-height:0.7; vertical-align: middle;"></i>` +
              `<i class="ti ti-trash delete-record" data-id="${full['ID']}" style="font-size: 20px; line-height: 0.7; vertical-align: middle;"></i>` +
              (displayIcons
                ? `<i class="ti ti-arrow-up kaydir-yukari" data-id="${full['ID']}" style="font-size: 20px; line-height: 0.7; vertical-align: middle;"></i>`
                : '') +
              (displayIcons
                ? `<i class="ti ti-arrow-down kaydir-asagi" data-id="${full['ID']}" style="font-size: 20px; line-height: 0.7; vertical-align: middle;"></i>`
                : '') +
              `<i class="ti ti-settings uretim-gir" data-id="${full['ID']}" style="font-size: 20px; line-height: 0.7; vertical-align: middle;"></i>` +
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
      },
      initComplete: function () {
        // Adding role filter once table initialized
        this.api()
          .columns(16)
          .every(function () {
            var column = this;
            var select = $('<select id="sss" class="form-select"><option value="">Tüm İstasyonlar</option></select>')
              .appendTo('.istasyon')
              .on('change', function () {
                grupSecimi = $(this).val(); // Seçilen değeri değişkene atıyoruz
                if (grupSecimi !== '') {
                  displayIcons = true;
                } else {
                  displayIcons = false;
                }
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

  // changing the title
  $('.add-new').on('click', function () {
    $('#offcanvasAddLabel').html('Kayıt Ekle');
    $('#record_id').val('');
    $('#TANIM').val('').trigger('change');
    $('#ISTKOD').val('').trigger('change');
    $('#DURUM').val('Beklemede');
    $('#KOD').val('');
    $('#PLANLANANMIKTAR').val('');
    $('#MMLGRPKOD').val('');
    $('#NOTLAR').val('');
    $('#URETIMSIRA').val('');
    $('#AKTIF').prop('checked', false);
    document.getElementById('TANIM').disabled = true;

  });

  // Delete Record
  $(document).on('click', '.delete-record', async function () {
    var temp_id = $(this).data('id'),
      dtrModal = $('.dtr-bs-modal.show');

    // Küçük ekranlarda responsive modal gizleme
    if (dtrModal.length) {
      dtrModal.modal('hide');
    }

    try {
      const hareketSonucu = await hareketKontrol(temp_id);  // Asenkron sonucu bekle

      if (hareketSonucu == 1) {
        Swal.fire({
          title: 'Olumsuz!',
          text: 'Üretim yapılmış kartlar silinemez!',
          icon: 'error',
          confirmButtonText: 'Kapat',
          customClass: {
            confirmButton: 'btn btn-success'
          }
        });
      } else {
        // Silme işlemi için onay kutusu
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
              url: `${baseUrl}emir-list/${temp_id}`,
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
      }
    } catch (error) {
      console.error('Hareket kontrol hatası:', error);
    }
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

    if (kayit_id) {
      document.getElementById('TANIM').disabled = false;
      document.getElementById('PLANLANANMIKTAR').disabled = false;
      document.getElementById('DURUM').disabled = false;
      document.getElementById('AKTIF').disabled = false;
    }
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

  async function hareketKontrol(kayit_id) {
    try {
      const response = await fetch(`${baseUrl}emir-list/${kayit_id}/edit`);
      const veri = await response.json();

      if (veri.length > 0 && veri[0].URETIMMIKTAR !== undefined) {
        return veri[0].URETIMMIKTAR > 0 ? 1 : 0;
      } else {
        console.error('Beklenen veri bulunamadı.');
        return 0;
      }
    } catch (error) {
      console.error('Veri çekme hatası:', error);
      return 0;  // Hata durumunda da bir değer döndürmek iyi olur
    }
  }

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
      ISTKOD: {
        validators: {
          notEmpty: {
            message: 'Lütfen Grup Kodunu Giriniz'
          }
        }
      },
      PLANLANANMIKTAR: {
        validators: {
          notEmpty: {
            message: 'Lütfen Miktar Giriniz'
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
          return '.mb-3';
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
      url: `${baseUrl}emir-list`,
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

  // Filter form control to default size
  // ? setTimeout used for multilingual table initialization
  setTimeout(() => {
    $('.dataTables_filter .form-control').removeClass('form-control-sm');
    $('.dataTables_length .form-select').removeClass('form-select-sm');
  }, 300);

  // clearing form data when offcanvas hidden
  offCanvasForm.on('hidden.bs.offcanvas', function () {
    fv.resetForm(true);
  });

  $('#ISTKOD').on('select2:select', function (e) {
    var ISTKOD = $('#ISTKOD').find(':selected').text();
    if (ISTKOD) {
      document.getElementById('TANIM').disabled = false;
      document.getElementById('TANIM').focus();
      fetch(`/emir-list/mamulal/${ISTKOD}`)
        .then(response => response.json())
        .then(data => {
          const select2 = document.getElementById('TANIM');
          select2.innerHTML = '<option value="">Seçiniz</option>';
          data.forEach(option => {
            select2.innerHTML += `<option value="${option.KOD}" data-info="${option.MMLGRPKOD}">${option.KOD} - ${option.TANIM}</option>`;
          });
        });
    } else {
      document.getElementById('TANIM').disabled = true;
      document.getElementById('DURUM').disabled = true;
      document.getElementById('AKTIF').disabled = true;
    }
  });

  $('#TANIM').on('select2:select', function (e) {
    var selectedOption = e.params.data.element;
    var deger = $(selectedOption).val();
    if (deger) {
      document.getElementById('KOD').value = deger;
      document.getElementById('MMLGRPKOD').value = $(selectedOption).data('info');
      document.getElementById('PLANLANANMIKTAR').disabled = false;
      document.getElementById('DURUM').disabled = false;
      document.getElementById('AKTIF').disabled = false;
      document.getElementById('PLANLANANMIKTAR').focus();
    } else {
      document.getElementById('PLANLANANMIKTAR').disabled = true;
      document.getElementById('DURUM').disabled = true;
      document.getElementById('AKTIF').disabled = true;
    }
  });

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

  $(document).on('click', '.kaydir-yukari', function () {
    var temp_id = $(this).data('id');
    $.ajax({
      url: '/emir/yukariat',
      type: 'POST',
      data: { id: temp_id },
      success: function (response) {
        if (response.success) {
          dt_table.DataTable().ajax.reload();
          // alert('Sıralama işlemi başarılı oldu.');
        } else {
          // alert('Sıralama işlemi başarısız oldu.');
        }
      },
      error: function () {
        alert('Bir hata oluştu.');
      }
    });
  });

  $(document).on('click', '.kaydir-asagi', function () {
    var temp_id = $(this).data('id');
    $.ajax({
      url: '/emir/asagiat',
      type: 'POST',
      data: { id: temp_id },
      success: function (response) {
        if (response.success) {
          dt_table.DataTable().ajax.reload();
          // alert('Sıralama işlemi başarılı oldu.');
        } else {
          // alert('Sıralama işlemi başarısız oldu.');
        }
      },
      error: function () {
        alert('Bir hata oluştu.');
      }
    });
  });

  $(document).on('click', '.uretim-gir', function () {
    var kayit_id = $(this).data('id');
console.log(kayit_id);
    $('#modalCenterTitle').html('Üretim Girişi');

    $.get(`${baseUrl}emir-list\/${kayit_id}\/edit`, function (data) {
      $('#rec_id').val(kayit_id);
      $('#urun_id').val(data[0].ID);
      $('#mamul').val(data[0].TANIM);
      // $('#miktarTemp').val(data[0].MIKTAR);
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
