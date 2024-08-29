@php
$configData = Helper::appClasses();
@endphp


<script src="https://cdnjs.cloudflare.com/ajax/libs/babel-polyfill/7.12.1/polyfill.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/exceljs/4.4.0/exceljs.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>


<script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script>
  window.jQuery || document.write(decodeURIComponent('%3Cscript src="js/jquery.min.js"%3E%3C/script%3E'))
</script>
<script type="text/javascript" src="https://cdn3.devexpress.com/jslib/23.2.4/js/dx.all.js"></script>
<link rel="stylesheet" href="https://cdn3.devexpress.com/jslib/23.2.4/css/dx.material.blue.light.compact.css">
<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/themify-icons/1.0.1/css/themify-icons.min.css"> -->
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<meta name="csrf-token" content="{{ csrf_token() }}">
<!-- SweetAlert2 CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@extends('layouts/layoutMaster')

@section('title', '2. Kaliteler')


@section('content')

<div class="row g-6 mb-6">
  <div class="col-sm-6 col-xl-3">
    <div class="card">
      <div class="card-body">
        <div class="d-flex align-items-start justify-content-between">
          <div class="content-left">
            <span class="text-heading">Toplam Paket Adedi</span>
            <h4 class="mb-0 me-2">{{ $toplamPaket }} </h4>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-sm-6 col-xl-3">
    <div class="card">
      <div class="card-body">
        <div class="d-flex align-items-start justify-content-between">
          <div class="content-left">
            <span class="text-heading">Toplam Ağırlık (Genel)</span>
            <h4 class="mb-0 me-2"> {{ $toplamKg }} Kg</h4>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-sm-6 col-xl-3">
    <div class="card">
      <div class="card-body">
        <div class="d-flex align-items-start justify-content-between">
          <div class="content-left">
            <span class="text-heading">Toplam Ağırlık (HR)</span>
            <h4 class="mb-0 me-2"> {{ $toplamKgHr }} Kg</h4>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-sm-6 col-xl-3">
    <div class="card">
      <div class="card-body">
        <div class="d-flex align-items-start justify-content-between">
          <div class="content-left">
            <span class="text-heading">Toplam Ağırlık (Diğer)</span>
            <h4 class="mb-0 me-2"> {{ $toplamKgDiger }} Kg</h4>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>




<h5 style="margin-bottom: -8px; margin-left: 10px;"> Akyazı 2. Kalite Stok Listesi</h5>
<div class="card mt-2">
  <div class="card p-2">
    <div class="demo-container">
      <div id="gridContainer"></div>
    </div>
  </div>
</div>
<div class="card mt-2">
  <div class="card p-2">
    <div id="pivotgrid-demo">
      <div id="pivotgrid-chart"></div>
      <div id="pivotgrid"></div>
    </div>
  </div>
</div>

<script>
  $(() => {


    var veri = @json($kalite2);
    var mamuller = @json($mamuller);
    var hatlar = @json($hatlar);
    var nevi = @json($nevi);
    let bkg = 0;

    $('#gridContainer').dxDataGrid({
      dataSource: veri,
      keyExpr: 'id',
      sorting: {
        mode: 'single',
      },
      export: {
        enabled: true,
      },
      onExporting(e) {
        const workbook = new ExcelJS.Workbook();
        const worksheet = workbook.addWorksheet('2.Kaliteler');

        DevExpress.excelExporter.exportDataGrid({
          component: e.component,
          worksheet,
          autoFilterEnabled: true,
        }).then(() => {
          workbook.xlsx.writeBuffer().then((buffer) => {
            saveAs(new Blob([buffer], {
              type: 'application/octet-stream'
            }), 'Kalite2.xlsx');
          });
        });
      },
      // scrolling: {
      //   mode: 'virtual',
      // },
      rowAlternationEnabled: false,
      hoverStateEnabled: true,
      showBorders: true,
      width: '100%',
      paging: {
        pageSize: 10,
      },
      pager: {
        showPageSizeSelector: true,
        allowedPageSizes: [10, 25, 50, 100],
      },
      remoteOperations: false,
      searchPanel: {
        visible: true,
        placeholder: 'Ara...',
        highlightCaseSensitive: true,
      },
      filterRow: {
        visible: true,
        applyFilter: 'auto',
      },
      // headerFilter: {
      //   visible: true,
      // },
      groupPanel: {
        visible: true,
        emptyPanelText: 'Gruplanacak sütunlar buraya...',
      },
      grouping: {
        autoExpandAll: true,
      },
      onCellPrepared: function(e) {
        if (e.column.dataField == "kantarkg") {
          if (e.value == 0) {
            e.cellElement.css({
              "color": "white",
              "background-color": "#f0afb0"
            });
          }
          if (e.value > 0) {
            e.cellElement.css({
              "font-weight": "bold"
            });
          }
        };
        if (e.column.dataField == "basildi") {
          if (e.value == 0) {
            e.cellElement.css({
              "color": "#f0afb0",
              // "background-color": "#f0afb0"
            });
          }
        }
      },
      onRowInserted: function(e) {
        let now = new Date();
        e.data.tarih = now.toISOString().split('T')[0];
        e.data.saat = now.toTimeString().split(' ')[0];

        axios.get('/paket-no-al/' + e.data.hat)
          .then(response => {
            var pkno = response.data;

            return axios.get('/mamulkodu', {
              params: {
                m: e.data.mamul,
                n: e.data.nevi
              }
            }).then(mamulkoduResponse => {
              const mamulkodu = mamulkoduResponse.data.mamulkodu;
              const kalinlik = mamulkoduResponse.data.kalinlik;
              return axios.get('/user').then(userResponse => {
                const name = userResponse.data.name;

                return axios.post('/stoklar/kalite2-ekle', {
                  mamul: String(e.data.mamul),
                  boy: e.data.boy,
                  pkno: String(pkno),
                  tarih: e.data.tarih,
                  saat: e.data.saat,
                  hat: e.data.hat,
                  operator: String(name),
                  nevi: String(e.data.nevi),
                  mamulkodu: String(mamulkodu),
                  kalinlik: String(kalinlik),
                  adet: e.data.adet,
                  kg: e.data.kg,
                  kantarkg: e.data.kantarkg,
                  basildi: e.data.basildi,
                });
              });
            });
          })
          .then(response => {
            DevExpress.ui.notify("Data inserted successfully", "success", 2000);

            return axios.get('/stoklar/kalite2-listele');
          })
          .then(response => {
            const veri = response.data;
            $('#gridContainer').dxDataGrid({
              dataSource: veri,
            });
          })
          .catch(error => {
            console.error('Hata:', error);
            DevExpress.ui.notify("Error processing request", "error", 2000);
          });
      },
      onRowRemoving: function(e) {
        var deferred = $.Deferred();
        Swal.fire({
          title: 'Emin misiniz?',
          text: "Bu işlemi geri alamayacaksınız!",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonText: 'Evet, Silebilirsin!',
          cancelButtonText: 'Vazgeç',
          customClass: {
            confirmButton: 'btn btn-primary me-3',
            cancelButton: 'btn btn-label-secondary'
          },
          buttonsStyling: false
        }).then(function(result) {
          if (result.dismiss === Swal.DismissReason.cancel) {
            deferred.resolve(true);
            Swal.fire({
              title: 'Vazgeçildi',
              text: 'Kayıt silinmedi!',
              icon: 'error',
              confirmButtonText: 'Kapat',
              customClass: {
                confirmButton: 'btn btn-success'
              }
            });
          } else if (result.value) {
            deferred.resolve(false);
            axios.get('/stoklar/kalite2-sil/' + e.data.id)
              .then(response => {
                Swal.fire({
                  icon: 'success',
                  title: 'Silindi!',
                  text: 'Kayıt silindi',
                  confirmButtonText: 'Kapat',
                  customClass: {
                    confirmButton: 'btn btn-success'
                  }
                });
              })
              .catch(error => {
                console.error(error);
                DevExpress.ui.notify("Error deleting record", "error", 2000);
              });

          }
        });
        e.cancel = deferred.promise();
      },
      onRowUpdating: function(e) {
        // Güncellenen verileri al
        let updatedData = e.newData;

        // Sadece değişen verileri API'ye gönder
        let changes = {};
        for (let key in updatedData) {
          if (updatedData.hasOwnProperty(key)) {
            changes[key] = updatedData[key];
          }
        }

        console.log(changes);
        axios.put(`/stoklar/kalite2-guncelle/${e.oldData.id}`, changes)
          .then(response => {
            DevExpress.ui.notify("Data updated successfully", "success", 2000);
            axios.get('/stoklar/kalite2-listele')
              .then(response => {
                const veri = response.data;
                $('#gridContainer').dxDataGrid({
                  dataSource: veri,
                });
              })
              .catch(error => {
                console.error('Hata:', error);
                DevExpress.ui.notify("Error processing request", "error", 2000);
              });
            e.component.refresh();
          })
          .catch(error => {
            console.error('Error updating data:', error);
            DevExpress.ui.notify("Error updating data", "error", 2000);
          });

      },
      onEditorPreparing: function(e) {

        if (e.parentType === "dataRow" && e.dataField === "mamul") {
          e.editorOptions.onValueChanged = function(args) {
            var selectedProductId = args.value;
            var selectedProduct = mamuller.find(p => p.mamul === selectedProductId);
            console.log(selectedProduct);
            var price = selectedProduct ? selectedProduct.minkg : 0;
            bkg = price;
            var topkg = e.component.cellValue(e.row.rowIndex, "adet") * bkg * (e.component.cellValue(e.row.rowIndex, "boy") / 1000);
            e.component.cellValue(e.row.rowIndex, "kantarkg", topkg);
            e.component.cellValue(e.row.rowIndex, "kg", topkg);
            e.component.cellValue(e.row.rowIndex, "mamul", selectedProductId);
          };
        }

        if (e.dataField === "adet" && e.parentType === "dataRow") {
          e.editorOptions.onValueChanged = function(args) {
            var topkg = args.value * bkg * (e.component.cellValue(e.row.rowIndex, "boy") / 1000);
            e.component.cellValue(e.row.rowIndex, "adet", args.value);
            e.component.cellValue(e.row.rowIndex, "kg", topkg);
            e.component.cellValue(e.row.rowIndex, "kantarkg", topkg);
          }
        }

        if (e.dataField === "boy" && e.parentType === "dataRow") {
          e.editorOptions.onValueChanged = function(args) {
            var topkg = e.component.cellValue(e.row.rowIndex, "adet") * bkg * (args.value / 1000);
            e.component.cellValue(e.row.rowIndex, "boy", args.value);
            e.component.cellValue(e.row.rowIndex, "kg", topkg);
            e.component.cellValue(e.row.rowIndex, "kantarkg", topkg);
          }
        }
      },
      onInitNewRow: function(e) {
        e.data.kg = 0;
        e.data.boy = 6000;
        e.data.adet = 1;
        e.data.kantarkg = 0;
      },
      columnHidingEnabled: true,
      columnAutoWidth: true,
      editing: {
        refreshMode: 'full',
        allowEditing: true,
        allowUpdating: true,
        allowAdding: true,
        allowDeleting: true,
        confirmDelete: false,
        mode: 'popup',
        popup: {
          title: '2. Kalite Girişi',
          showTitle: true,
          width: 700,
          height: 500,
        },
        form: {
          items: [{
            itemType: 'group',
            outerHeight: 60,
            colCount: 2,
            colSpan: 2,
            items: ['mamul', 'boy', 'nevi', 'hat', 'adet', {
              dataField: 'kantarkg',
              dataType: 'number',
              format: '#,##0.0',
            }, {
              dataField: 'basildi',
              editorType: 'dxCheckBox',
            }],
          }],
        },
      },
      columns: [{
          dataField: 'id',
          dataType: 'number',
          width: '40px',
          allowEditing: false,
          visible: false,
        },
        {
          dataField: 'mamul',
          width: '100px',
          dataType: 'string',
          allowSorting: false,
          // groupIndex: 0,
          lookup: {
            dataSource: mamuller,
            displayExpr: 'mamul',
            valueExpr: 'mamul',
          },
          // validationRules: [{
          //   type: 'required',
          //   message: 'Mamül bilgisi gereklidir'
          // }],
          // editorOptions: {
          //   dropDownOptions: { // Açılır liste seçenekleri
          //     showDropDownButton: true // Açılır liste okunu göster
          //   },
          //   acceptCustomValue: false // Kullanıcının kendi değerini girmesini engelle
          // }
        },
        {
          dataField: 'boy',
          dataType: 'number',
          width: '60px',
          visible: true,
          // editorOptions: {
          //       min: 4500,
          //       max: 13000
          //   },
          validationRules: [{
            type: 'required',
            message: 'Hat bilgisi gereklidir'
          }, {
            type: "range",
            min: 4500,
            max: 13000,
            message: "Boy 4500 ile 13000 arası olmalıdır"
          }],
        },
        {
          dataField: 'kantarkg',
          caption: 'Trtm Kg',
          dataType: 'number',
          format: '#,##0.0',
          alignment: 'right',
          width: '80px',
        },
        {
          dataField: 'adet',
          caption: 'Ad',
          dataType: 'number',
          validationRules: [{
            type: 'required',
            message: 'Adet bilgisi gereklidir'
          }, {
            type: "range",
            min: 1,
            max: 5000,
            message: "Boy 1 ile 500 arası olmalıdır"
          }],
          format: '##0',
          alignment: 'right',
          width: '60px',

        },
        {
          dataField: 'kg',
          caption: 'Kg',
          dataType: 'number',
          format: '#,##0',
          alignment: 'right',
          width: '80px',
          allowEditing: false,
          cellTemplate: function(container, cellInfo) {
            const valueDiv = $("<div>").text(cellInfo.value);
            if (cellInfo.data.kg == 0) {
              valueDiv.css({
                "color": "red",
                "font-weight": "bold"
              });
            }
            if (cellInfo.data.kg > 0) {
              valueDiv.css({
                "color": "blue",
                "font-weight": "bold"
              });
            }
            return valueDiv;
          }
        },
        {
          dataField: 'nevi',
          width: '60px',
          lookup: {
            dataSource: nevi,
            displayExpr: 'nevi',
            valueExpr: 'nevi',
          },
          validationRules: [{
            type: 'required',
            message: 'Nevi bilgisi gereklidir'
          }],
        },
        {
          dataField: 'basildi',
          editable: true,
          allowEditing: true,
          dataType: 'boolean',
          calculateCellValue(rowData) {
            return rowData.basildi === '1';
          },
          width: '70px',
          // cellTemplate: function(container, options) {
          //   let iconClass = "";
          //   if (options.value == 1) {
          //     iconClass = "dx-icon-check";
          //   } else if (options.value == 0) {
          //     iconClass = "dx-icon-close";
          //   }
          //   container.addClass("icon-cell");
          //   $("<div>")
          //     .addClass(iconClass)
          //     .addClass("dx-icon")
          //     .appendTo(container);
          //   // cellTemplate(container, cellInfo) {
          //   //   // container.addClass('chart-cell');
          //   //   $('<div />').dxSwitch({
          //   //     value: Boolean(Number(cellInfo.data.basildi)),
          //   //     readOnly: true,
          //   //   }).appendTo(container);
          // },
        },
        {
          dataField: 'pkno',
          allowEditing: false,
          width: '120px',
        },
        {
          dataField: 'hat',
          width: '60px',
          validationRules: [{
            type: 'required',
            message: 'Hat bilgisi gereklidir'
          }],
          lookup: {
            dataSource: hatlar,
            displayExpr: 'hat',
            valueExpr: 'hat',
          },
        },
        {
          dataField: 'tarih',
          allowEditing: false,
          dataType: 'date',
          format: "dd.MM.yyyy",
          width: '100px',
          sortOrder: 'desc',
        },
        {
          dataField: 'saat',
          allowEditing: false,
          width: '80px',
          sortOrder: 'desc',
        },
        {
          caption: 'Operatör',
          dataField: 'operator',
          width: '60px',
        },
        {
          caption: 'Mamül Kodu',
          dataField: 'mamulkodu',
          width: '100px',
        },
        {
          caption: 'Kalınlık',
          dataField: 'kalinlik',
          width: '70px',
        },
        {
          caption: 'Vardiya',
          dataField: 'vardiya',
        },

      ],
      summary: {
        groupItems: [{
            column: 'mamul',
            summaryType: 'count',
            alignByColumn: false,
            showInGroupFooter: false,
            name: "Toplam Pk",
            displayFormat: "Toplam Pk: {0}"
          },
          {
            column: 'kantarkg',
            summaryType: 'sum',
            alignByColumn: false,
            showInGroupFooter: false,
            name: "Toplam Kg",
            displayFormat: "Toplam Ağırlık: {0}"
          },
          {
            column: 'adet',
            summaryType: 'sum',
            alignByColumn: false,
            showInGroupFooter: false,
            name: "Toplam Ad",
            displayFormat: "Toplam Adet: {0}"
          }
        ],
        totalItems: [{
            column: 'mamul',
            summaryType: 'count',
            alignByColumn: false,
            displayFormat: "Tpl Pk: {0}"
          },
          {
            column: "kantarkg",
            summaryType: "sum",
            valueFormat: 'decimal',
            format: '#,##0.0',
            displayFormat: "{0}"
          },
          {
            column: "adet",
            summaryType: "sum",
            displayFormat: "{0}"

          }
        ]
      },
    });






    const pivotGridChart = $('#pivotgrid-chart').dxChart({
      commonSeriesSettings: {
        type: 'bar',
      },
      tooltip: {
        enabled: true,
        format: '#.0',
        customizeTooltip(args) {
          return {
            html: `${args.seriesName} | Toplam <div class='number'>${args.valueText}</div>`,
          };
        },
      },
      size: {
        height: 200,
      },
      adaptiveLayout: {
        width: 450,
      },
    }).dxChart('instance');


    const pivotGrid = $('#pivotgrid').dxPivotGrid({
      allowSortingBySummary: true,
      allowSorting: true,
      allowFiltering: true,
      fieldChooser: {
        enabled: true,
        height: 400,
      },
      dataSource: {
        fields: [{
          caption: 'Kalınlık',
          width: 120,
          dataField: 'kalinlik',
          area: 'row',
          sortBySummaryField: 'kalinlik',
        }, {
          caption: 'Mamul',
          dataField: 'mamul',
          width: 150,
          area: 'row',
        },
        //  {
        //   dataField: 'tarih',
        //   dataType: 'date',
        //   area: 'column',
        // },
        // {
        //   groupName: 'date',
        //   groupInterval: 'year',
        //   expanded: true,
        // },
          {
          caption: 'Paket Ad',
          dataField: 'pkno',
          dataType: 'number',
          summaryType: 'count',
          format: {
            type: "fixedPoint",
            precision: 0, // Ondalık basamağı sıfır yapmak için
            thousandsSeparator: ","
          },
          area: 'data',
        },{
          caption: 'Kg',
          dataField: 'kantarkg',
          dataType: 'number',
          summaryType: 'sum',
          format: {
            type: "fixedPoint",
            precision: 0, // Ondalık basamağı sıfır yapmak için
            thousandsSeparator: ","
          },
          area: 'data',
        }],
        store: veri,
      },
    }).dxPivotGrid('instance');

    pivotGrid.bindChart(pivotGridChart, {
      dataFieldsDisplayMode: 'splitPanes',
      alternateDataFields: false,
    });

    function expand() {
      const dataSource = pivotGrid.getDataSource();
      dataSource.expandHeaderItem('row', ['kantarkg']);
      dataSource.expandHeaderItem('column', [2013]);
    }

    setTimeout(expand, 0);


  });
</script>

<style>
  #gridContainer {
    max-height: auto;
  }
</style>



@endsection
