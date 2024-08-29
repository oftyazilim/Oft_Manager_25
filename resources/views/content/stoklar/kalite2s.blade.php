@php
    $configData = Helper::appClasses();
@endphp



<meta name="csrf-token" content="{{ csrf_token() }}">
<!-- SweetAlert2 CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

<!-- SweetAlert2 JS -->

@extends('layouts/layoutMaster')

@section('title', '2. Kaliteler (Şekerpınar)')

{{-- Page Scripts --}}
@section('page-script')

    <script src="https://cdnjs.cloudflare.com/ajax/libs/babel-polyfill/7.12.1/polyfill.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/exceljs/4.4.0/exceljs.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection


@section('content')


    {{--
    <div class="row g-6 mb-6">
        <div class="col-sm-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div class="content-left">
                            <span class="text-heading">Toplam Paket Adedi</span>
                            <h4 class="mb-0 me-2">0 </h4>
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
                            <h4 class="mb-0 me-2">0 Kg</h4>
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
                            <h4 class="mb-0 me-2">0 Kg</h4>
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
                            <h4 class="mb-0 me-2">0 Kg</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}



    <div class="card mt-0">
        <div class="card p-2">
            <div id="gridContainer"></div>
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
            document.getElementById('baslik').innerHTML = 'Şekerpınar 2. Kalite Stok Listesi';


            var veri = @json($kalite2);
            var mamuller = @json($mamullers);
            var hatlar = @json($hatlar);
            var nevi = @json($nevi);
            let bkg = 120;

            const dataGrid = $('#gridContainer').dxDataGrid({
                dataSource: veri,
                keyExpr: 'id',
                sorting: {
                    mode: 'single',
                },
                export: {
                    enabled: true,
                },
                scrolling: {
                  mode: 'virtual',
                },
                allowColumnReordering: true,
                allowColumnResizing: true,
                rowAlternationEnabled: true,
                hoverStateEnabled: true,
                showColumnLines: false,
                stateStoring: {
                    enabled: true,
                    type: 'localStorage',
                    storageKey: 'storage',
                },
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
                columnChooser: {
                    enabled: true,
                },
                headerFilter: {
                    visible: true,
                },
                // groupPanel: {
                //     visible: true,
                //     emptyPanelText: 'Gruplanacak sütunlar buraya...',
                // },
                // grouping: {
                //     autoExpandAll: true,
                // },
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
                onExporting(e) {
                    const workbook = new ExcelJS.Workbook();
                    const worksheet = workbook.addWorksheet('2.Kaliteler-Sekerpinar');

                    DevExpress.excelExporter.exportDataGrid({
                        component: e.component,
                        worksheet,
                        autoFilterEnabled: true,
                    }).then(() => {
                        workbook.xlsx.writeBuffer().then((buffer) => {
                            saveAs(new Blob([buffer], {
                                type: 'application/octet-stream'
                            }), 'Kalite2Sekerpinar.xlsx');
                        });
                    });
                },
                onRowInserted: function(e) {
                    let now = new Date();
                    e.data.tarih = now.toISOString().split('T')[0];
                    e.data.saat = now.toTimeString().split(' ')[0];

                    axios.get('/paket-no-als/' + e.data.hat)
                        .then(response => {
                            var pkno = response.data;

                            return axios.get('/mamulkodus', {
                                params: {
                                    m: e.data.mamul,
                                    n: e.data.nevi
                                }
                            }).then(mamulkoduResponse => {
                                const mamulkodu = mamulkoduResponse.data.mamulkodu;
                                const kalinlik = mamulkoduResponse.data.kalinlik;
                                return axios.get('/user').then(userResponse => {
                                    const name = userResponse.data.name;

                                    return axios.post('/stoklar/kalite2s-ekle', {
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

                            return axios.get('/stoklar/kalite2s-listele');
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
                            axios.get('/stoklar/kalite2s-sil/' + e.data.id)
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
                                    DevExpress.ui.notify("Error deleting record", "error",
                                        2000);
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

                    axios.put(`/stoklar/kalite2s-guncelle/${e.oldData.id}`, changes)
                        .then(response => {
                            DevExpress.ui.notify("Data updated successfully", "success", 2000);
                            axios.get('/stoklar/kalite2s-listele')
                                .then(response => {
                                    const veri = response.data;
                                    $('#gridContainer').dxDataGrid({
                                        dataSource: veri,
                                    });
                                })
                                .catch(error => {
                                    console.error('Hata:', error);
                                    DevExpress.ui.notify("Error processing request", "error",
                                        2000);
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
                            var topkg = e.component.cellValue(e.row.rowIndex, "adet") * bkg * (e
                                .component.cellValue(e.row.rowIndex, "boy") / 1000);
                            e.component.cellValue(e.row.rowIndex, "kantarkg", topkg);
                            e.component.cellValue(e.row.rowIndex, "kg", topkg);
                            e.component.cellValue(e.row.rowIndex, "mamul", selectedProductId);
                        };
                    }

                    if (e.dataField === "adet" && e.parentType === "dataRow") {
                        e.editorOptions.onValueChanged = function(args) {
                            var topkg = args.value * bkg * (e.component.cellValue(e.row.rowIndex,
                                "boy") / 1000);
                            e.component.cellValue(e.row.rowIndex, "adet", args.value);
                            e.component.cellValue(e.row.rowIndex, "kg", topkg);
                            e.component.cellValue(e.row.rowIndex, "kantarkg", topkg);
                        }
                    }

                    if (e.dataField === "boy" && e.parentType === "dataRow") {
                        e.editorOptions.onValueChanged = function(args) {
                            var topkg = e.component.cellValue(e.row.rowIndex, "adet") * bkg * (args
                                .value / 1000);
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
                    texts: {
                        // Save, Cancel gibi genel butonlar için
                        saveRowChanges: "Kaydet",
                        cancelRowChanges: "İptal"
                    },
                    mode: 'form',
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
                            items: ['mamul', 'boy', 'nevi', 'hat', 'adet2', {
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
                        allowHeaderFiltering: false,
                    },
                    {
                        dataField: 'mamul',
                        width: '120px',
                        dataType: 'string',
                        allowSorting: false,
                        allowHeaderFiltering: true,
                        // groupIndex: 0,
                        lookup: {
                            dataSource: mamuller,
                            displayExpr: 'mamul',
                            valueExpr: 'mamul',
                        },
                    },
                    {
                        dataField: 'boy',
                        dataType: 'number',
                        width: '70px',
                        allowHeaderFiltering: false,
                        visible: true,
                        editorOptions: {
                            min: 4500,
                            max: 13000
                        },
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
                        dataField: 'adet2',
                        caption: 'Grç. Ad',
                        dataType: 'number',
                        allowHeaderFiltering: false,
                        validationRules: [{
                            type: 'required',
                            message: 'Adet bilgisi gereklidir'
                        }, {
                            type: "range",
                            min: 1,
                            max: 500,
                            message: "Boy 1 ile 500 arası olmalıdır"
                        }],
                        format: '##0',
                        alignment: 'right',
                        width: '70px',

                    },
                    {
                        dataField: 'kantarkg',
                        caption: 'Grç. Kg',
                        dataType: 'number',
                        allowHeaderFiltering: false,
                        format: {
                            type: "fixedPoint",
                            precision: 0,
                            thousandsSeparator: ","
                        },
                        alignment: 'right',
                        width: '80px',
                    },
                    {
                        dataField: 'adet',
                        caption: 'Teo. Ad',
                        dataType: 'number',
                        allowHeaderFiltering: false,
                        validationRules: [{
                            type: 'required',
                            message: 'Adet bilgisi gereklidir'
                        }, {
                            type: "range",
                            min: 1,
                            max: 500,
                            message: "Boy 1 ile 500 arası olmalıdır"
                        }],
                        format: '##0',
                        alignment: 'right',
                        width: '70px',

                    },
                    {
                        dataField: 'kg',
                        caption: 'Teo.Kg',
                        dataType: 'number',
                        allowHeaderFiltering: false,
                        format: {
                            type: "fixedPoint",
                            precision: 0,
                            thousandsSeparator: ","
                        },
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
                        width: '70px',
                        alignment: 'center',
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
                        allowHeaderFiltering: false,
                        dataType: 'boolean',
                        calculateCellValue(rowData) {
                            return rowData.basildi === '1';
                        },
                        width: '70px',
                        cellTemplate: function(container, options) {
                            let iconClass = "";
                            if (options.value == 1) {
                                iconClass = "dx-icon-check";
                            } else if (options.value == 0) {
                                iconClass = "dx-icon-close";
                            }
                            container.addClass("icon-cell");
                            $("<div>")
                                .addClass(iconClass)
                                .addClass("dx-icon")
                                .appendTo(container);
                            // cellTemplate(container, cellInfo) {
                            //   // container.addClass('chart-cell');
                            //   $('<div />').dxSwitch({
                            //     value: Boolean(Number(cellInfo.data.basildi)),
                            //     readOnly: true,
                            //   }).appendTo(container);
                        },
                    },
                    {
                        dataField: 'pkno',
                        alignment: 'center',
                        allowHeaderFiltering: false,
                        allowEditing: false,
                        width: '140px',
                    },
                    {
                        dataField: 'hat',
                        allowHeaderFiltering: false,
                        visible: false,
                        alignment: 'center',
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
                        alignment: 'center',
                        // groupIndex: 0,
                        dataType: 'date',
                        format: "dd.MM.yyyy",
                        width: '100px',
                        sortOrder: 'desc',
                    },
                    {
                        allowHeaderFiltering: false,
                        dataField: 'saat',
                        alignment: 'center',
                        allowEditing: false,
                        width: '85px',
                        sortOrder: 'desc',
                    },
                    {
                        caption: 'Operatör',
                        alignment: 'center',
                        dataField: 'operator',
                        width: '60px',
                    },
                    {
                        caption: 'Mamül Kodu',
                        visible: false,
                        alignment: 'center',
                        dataField: 'mamulkodu',
                        width: '100px',
                    },
                    {
                        caption: 'Kalınlık',
                        visible: false,
                        alignment: 'center',
                        dataField: 'kalinlik',
                        width: '70px',
                    },
                    {
                        caption: 'Vardiya',
                        visible: false,
                        alignment: 'center',
                        dataField: 'vardiya',
                    },
                    {
                        type: "buttons",
                        buttons: [{
                                name: "edit",
                                icon: "edit",
                                onClick: function(e) {
                                    // Custom implementation goes here
                                    e.component.editRow(e.row.rowIndex);
                                    e.event.preventDefault();
                                }
                            }, {
                                name: "delete",
                                icon: "trash"
                            }, {
                                name: "save",
                                text: "Kaydet", // Save butonunu Türkçeleştir
                                icon: "save"
                            },
                            {
                                name: "cancel",
                                text: "İptal", // Cancel butonunu Türkçeleştir
                                icon: "revert"
                            }
                        ],
                    },

                ],
                // toolbar: {
                //         items: [{
                //                 location: 'before',
                //                 template() {
                //                     return $('<div>')
                //                         .addClass('informer')
                //                         .append(
                //                             $('<div>')
                //                             .addClass('count')
                //                             .text(parseFloat(bkg / 1000).toFixed(2) + ' Ton'),
                //                             $('<span>')
                //                             .text('Toplam Gerçek'),
                //                         );
                //                 },
                //             },
                //             {
                //                 location: 'before',
                //                 widget: 'dxSelectBox',
                //                 options: {
                //                     width: 180,
                //                     items: [{
                //                         value: 'tarih',
                //                         text: 'Mamüle Göre Grupla',
                //                       }, {
                //                         value: 'mamul',
                //                         text: 'Tarihe Göre Grupla',
                //                     }],
                //                     displayExpr: 'text',
                //                     valueExpr: 'value',
                //                     value: 'mamul',
                //                     onValueChanged(e) {
                //                         dataGrid.clearGrouping();
                //                         dataGrid.columnOption(e.value, 'groupIndex', 0);
                //                         $('.informer .count').text(getGroupCount(e.value));
                //                     },
                //                 },
                //             }, {
                //                 location: 'before',
                //                 widget: 'dxButton',
                //                 options: {
                //                     text: 'Listeyi Kapat',
                //                     width: 136,
                //                     onClick(e) {
                //                         const expanding = e.component.option('text') ===
                //                             'Listeyi Aç';
                //                         dataGrid.option('grouping.autoExpandAll', expanding);
                //                         e.component.option('text', expanding ? 'Listeyi Kapat' :
                //                             'Listeyi Aç');
                //                     },
                //                 },
                //             }, {
                //                 location: 'after',
                //                 widget: 'dxButton',
                //                 options: {
                //                     icon: 'refresh',
                //                     onClick() {
                //                         dataGrid.refresh();
                //                     },
                //                 },
                //             },
                //             'columnChooserButton',
                //         ]
                //     },

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
                    // totalItems: [{
                    //     column: 'mamul',
                    //     summaryType: 'count',
                    //     alignByColumn: false,
                    //     displayFormat: "Tpl Pk: {0}"
                    //   },
                    //   {
                    //     column: "kantarkg",
                    //     summaryType: "sum",
                    //     valueFormat: 'decimal',
                    //     displayFormat: "{0}"
                    //   },
                    //   {
                    //     column: "adet",
                    //     summaryType: "sum",
                    //     displayFormat: "{0}"

                    //   }
                    // ]
                }
            }).dxDataGrid('instance');

            function getGroupCount(groupField) {
                return DevExpress.data.query(veri)
                    .groupBy(groupField)
                    .toArray().sum;
            }


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
                allowFiltering: true,
                showBorders: true,
                showColumnGrandTotals: true,
                showRowGrandTotals: true,
                showRowTotals: false,
                showColumnTotals: true,
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
                        }, {
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
                        }
                    ],
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
            max-height: 700px;
        }

        #pivotgrid {
            margin-top: 20px;
        }

        .currency {
            text-align: center;
        }

        #descContainer a {
            color: #f05b41;
            text-decoration: underline;
            cursor: pointer;
        }

        #descContainer a:hover {
            text-decoration: none;
        }


#gridContainer .count {
  font-size: 18px;
  font-weight: 500;
}

#gridContainer .dx-toolbar-items-container {
  min-height: 44px;
}
    </style>



@endsection
