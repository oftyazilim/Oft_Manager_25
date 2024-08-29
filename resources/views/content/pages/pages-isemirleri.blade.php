@php
    $configData = Helper::appClasses();
@endphp
<script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

@extends('layouts/layoutMaster')

@section('title', 'İş Emirleri')

<!-- Vendor Styles -->
@section('vendor-style')
    @vite(['resources/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.scss', 'resources/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.scss', 'resources/assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.scss', 'resources/assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.scss', 'resources/assets/vendor/libs/flatpickr/flatpickr.scss', 'resources/assets/vendor/libs/datatables-rowgroup-bs5/rowgroup.bootstrap5.scss', 'resources/assets/vendor/libs/@form-validation/form-validation.scss'])
@endsection

<!-- Vendor Scripts -->
@section('vendor-script')
    @vite(['resources/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js', 'resources/assets/vendor/libs/moment/moment.js', 'resources/assets/vendor/libs/flatpickr/flatpickr.js', 'resources/assets/vendor/libs/@form-validation/popular.js', 'resources/assets/vendor/libs/@form-validation/bootstrap5.js', 'resources/assets/vendor/libs/@form-validation/auto-focus.js'])
@endsection

<!-- Page Scripts -->
@section('page-script')
    {{-- @vite(['resources/assets/js/planlama-emirler.js']) --}}

    <script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

      <link rel="stylesheet" href="https://cdn3.devexpress.com/jslib/24.1.3/css/dx.carmine.compact.css">
      <script type="text/javascript" src="https://cdn3.devexpress.com/jslib/23.2.4/js/dx.all.js"></script>

@endsection


@section('content')


    <div class="card mt-2">
        <div class="card p-2">
            {{-- <div class="card-header border-bottom">
                <h5 class="card-title mb-0">Search Filter</h5>
            </div>
            <div class="card-datatable table-responsive">
                <table class="datatables-emirler table">
                    <thead class="border-top">
                        <tr>
                            <th>ID</th>
                            <th>LOGICALREF</th>
                            <th>FICHENO</th>
                            <th>LINENO_</th>
                            <th>ITEMCODEM</th>
                            <th>ITEMCODED</th>
                            <th>CODE</th>
                            <th>NAME</th>
                            <th>PLNAMOUNT</th>
                            <th>ACTAMOUNT</th>
                            <th>BEGDATE</th>
                            <th>PLNDURATON</th>
                            <th>ACDURATION</th>
                            <th>STATUS</th>
                            <th>LINESTATUS</th>
                            <th>BOMMASTERREF</th>
                        </tr>
                    </thead>
                </table>
              </div> --}}
            <div class="demo-container">
                <div id="gridContainer"></div>
            </div>
        </div>


        <script>
            $(() => {
                var emirler = json($emirler);
                console.log(emirler[0].ID);

                // $('#gridContainer').dxDataGrid({
                //     height: 440,
                //     dataSource: emirler,
                //     keyExpr: 'ID',
                //     scrolling: {
                //         mode: 'virtual',
                //     },
                //     sorting: {
                //         mode: 'none',
                //     },
                //     rowDragging: {
                //         allowReordering: true,
                //         onReorder(e) {
                //             const visibleRows = e.component.getVisibleRows();
                //             const toIndex = emirler.findIndex((item) => item.ID === visibleRows[e.toIndex].data
                //                 .ID);
                //             const fromIndex = emirler.findIndex((item) => item.ID === e.itemData.ID);

                //             emirler.splice(fromIndex, 1);
                //             emirler.splice(toIndex, 0, e.itemData);

                //             e.component.refresh();
                //         },
                //     },
                //     showBorders: true,
                //     columns: [{
                //         dataField: 'ID',
                //         width: 55,
                //     }, {
                //         dataField: 'CODE',
                //         lookup: {
                //             dataSource: emirler,
                //             valueExpr: 'ID',
                //             displayExpr: 'NAME',
                //         },
                //         width: 150,
                //     }, {
                //         dataField: 'NAME',
                //         caption: 'ÜRÜN ADI',
                //         lookup: {
                //             dataSource: emirler,
                //             valueExpr: 'ID',
                //             displayExpr: 'NAME',
                //         },
                //         width: 150,
                //     }, 'STATUS'],
                // }).dxDataGrid('instance');









                $('#gridContainer').dxDataGrid({
                    dataSource: emirler,
                    keyExpr: 'ID',
                    sorting: {
                        mode: 'none'
                    },
                    scrolling: {
                        mode: 'virtual',
                    },
                    // rowAlternationEnabled: true,
                    // hoverStateEnabled: true,
                    //columnAutoWidth: true,
                    showBorders: true,
                    //width: '100%',
                    //repaintChangesOnly: true,
                    // onCellPrepared: function(e) {
                    //     if (e.rowType === "data" && e.column.dataField === "STATUS") {
                    //         console.log(e.value);
                    //         e.cellElement.css("color", e.value >= 2 ? "green" : "white");
                    //         e.value = 10;

                    //         //   // Tracks the `Amount` data field
                    //         //   e.watch(function() {
                    //         //       return e.data.Amount;
                    //         //   }, function() {
                    //         //       e.cellElement.css("color", e.data.Amount >= 1 ? "green" :
                    //         //       "red");
                    //         //   })
                    //     }
                    // },
                    rowDragging: {
                        allowReordering: true,
                        onReorder(e) {
                            const visibleRows = e.component.getVisibleRows();
                            const toIndex = emirler.findIndex((item) => item.ID === visibleRows[e.toIndex].data
                                .ID);
                            const fromIndex = emirler.findIndex((item) => item.ID === e.itemData.ID);

                            emirler.splice(fromIndex, 1);
                            emirler.splice(toIndex, 0, e.itemData);

                            e.component.refresh();
                        },
                    },
                    // onContextMenuPreparing: function (e) {
                    //     if (e.row.rowType === "data") {
                    //         e.items = [{
                    //             text: "edit",
                    //             onItemClick: function () {
                    //                 e.component.editRow(e.row.rowIndex);
                    //             }
                    //         },
                    //         {
                    //             text: "insert",
                    //             onItemClick: function () {
                    //                 e.component.addRow();
                    //             }
                    //         },
                    //         {
                    //             text: "delete",
                    //             onItemClick: function () {
                    //                 e.component.deleteRow(e.row.rowIndex);
                    //             }
                    //         }];
                    //     }
                    // },
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
                        highlightCaseSensitive: true,
                    },
                    groupPanel: {
                        visible: true
                    },
                    grouping: {
                        autoExpandAll: false,
                    },
                    columnHidingEnabled: true,
                    // dataRowTemplate(container, item) {
                    //     const {
                    //         data
                    //     } = item;
                    //     const markup = '<tr class=\'main-row\' role=\'row\'>' +
                    //         `<td rowspan='2' role="gridcell"><img src='${data.Picture}' alt='Picture of ${data.FirstName} ${data.LastName}' tabindex='0'/></td>` +
                    //         `<td role='gridcell'>${data.Prefix}</td>` +
                    //         `<td role='gridcell'>${data.FirstName}</td>` +
                    //         `<td role='gridcell'>${data.LastName}</td>` +
                    //         `<td role='gridcell'>${data.Position}</td>` +
                    //         `<td role='gridcell'>${formatDate(new Date(data.BirthDate))}</td>` +
                    //         `<td role='gridcell'>${formatDate(new Date(data.HireDate))}</td>` +
                    //         '</tr>' +
                    //         '<tr class=\'notes-row\' role=\'row\'>' +
                    //         `<td colspan='6' role='gridcell'><div>${data.Notes}</div></td>` +
                    //         '</tr>';

                    //     container.append(markup);
                    // },
                    // dataRowTemplate(container, item) {
                    //     const {
                    //         data
                    //     } = item;
                    //     const markup = '<tr class=\'main-row\' role=\'row\'>' +
                    //         `<td role='gridcell'>${data.ID}</td>` +
                    //         `<td role='gridcell'>${data.LOGICALREF}</td>` +
                    //         `<td role='gridcell'>${data.FICHENO}</td>` +
                    //         `<td role='gridcell'>${data.LINENO_}</td>` +
                    //         `<td role='gridcell'>${data.STATUS} < 1 ? 'Reşit Değil' : (${data.STATUS} <= 3 ? 'Çalışabilir' : 'Emekli')</td>` +
                    //         '</tr>' +
                    //         '<tr class=\'notes-row\' role=\'row\'>' +
                    //         `<td colspan='6' role='gridcell'><div>${data.NAME}</div></td>` +
                    //         '</tr>';

                    //     container.append(markup);
                    // },
                    columns: [{
                            dataField: 'ID',
                            width: '40px',
                        },
                        {
                            dataField: 'LOGICALREF',
                            caption: 'Sale Amount',
                            dataType: 'number',
                            format: 'currency',
                            fontSize: '24px',
                            alignment: 'right',
                            width: '60px',
                        },
                        {
                            dataField: 'FICHENO',

                        },
                        {
                            dataField: 'LINENO_',
                            width: '160px',
                        },
                        {
                            dataField: 'ITEMCODED',
                        },
                        {
                            dataField: 'CODE',
                        },
                        {
                            dataField: 'NAME',
                        },
                        {
                            dataField: 'STATUS',
                            // cellTemplate(container, options) {
                            //     container.addClass('chart-cell');
                            //     $('<div />').dxTextBox({
                            //         value: dataSource.STATUS,

                            //         tooltip: {
                            //             enabled: true,
                            //         },
                            //     }).appendTo(container);
                            //},
                        },
                    ]
                })
            });
        </script>


        <style>
            #gridContainer {
                height: 850px;
            }

            .dx-row img {
                height: 100px;
            }

            #gridContainer tr.main-row td:not(:first-child) {
                height: 21px;
            }

            #gridContainer tr.notes-row {
                white-space: normal;
                vertical-align: top;
            }

            #gridContainer tr.notes-row td {
                height: 40px;
                color: #999;
            }

            .dark #gridContainer tr.notes-row td {
                color: #777;
            }

            #gridContainer tbody.dx-state-hover {
                background-color: #ebebeb;
            }

            .dark #gridContainer tbody.dx-state-hover {
                background-color: #484848;
            }

            #gridContainer tbody.dx-state-hover tr.main-row td {
                color: #000;
            }

            .dark #gridContainer tbody.dx-state-hover tr.main-row td {
                color: #ccc;
            }

            #gridContainer tbody.dx-state-hover tr.notes-row td {
                color: #888;
            }
        </style>

        {{--
        <div class="modal" tabindex="-1" id="offcanvasAddUser" aria-labelledby="offcanvasAddUserLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-simple">
                <div class="modal-content">
                    <div class="modal-header border-bottom">
                        <h5 id="offcanvasAddUserLabel" class="modal-title">Add User</h5>
                        <button type="button" class="btn-close text-reset" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body mx-0 flex-grow-0 p-2 h-100">
                        <form class="add-new-user pt-0" id="addNewUserForm">
                            <input type="hidden" name="id" id="user_id">
                            <div class="mb-6">
                                <label class="form-label" for="add-user-fullname">Full Name</label>
                                <input type="text" class="form-control" id="add-user-fullname" placeholder="John Doe"
                                    name="name" aria-label="John Doe" />
                            </div>
                            <div class="mb-6">
                                <label class="form-label" for="add-user-email">Email</label>
                                <input type="text" id="add-user-email" class="form-control"
                                    placeholder="john.doe@example.com" aria-label="john.doe@example.com" name="email" />
                            </div>
                            <div class="mb-6">
                                <label class="form-label" for="add-user-contact">Contact</label>
                                <input type="text" id="add-user-contact" class="form-control phone-mask"
                                    placeholder="+1 (609) 988-44-11" aria-label="john.doe@example.com" name="userContact" />
                            </div>
                            <div class="mb-6">
                                <label class="form-label" for="add-user-created">Oluşturma Tarihi</label>
                                <input type="text" id="add-user-created" class="form-control" placeholder="Web Developer"
                                    aria-label="jdoe1" name="created_at" />
                            </div>
                            <div class="mb-6">
                                <label class="form-label" for="country">Country</label>
                                <select id="country" class="select2 form-select">
                                    <option value="">Select</option>
                                    <option value="Australia">Australia</option>
                                    <option value="Bangladesh">Bangladesh</option>
                                    <option value="Belarus">Belarus</option>
                                    <option value="Brazil">Brazil</option>
                                    <option value="Canada">Canada</option>
                                    <option value="China">China</option>
                                    <option value="France">France</option>
                                    <option value="Germany">Germany</option>
                                    <option value="India">India</option>
                                    <option value="Indonesia">Indonesia</option>
                                    <option value="Israel">Israel</option>
                                    <option value="Italy">Italy</option>
                                    <option value="Japan">Japan</option>
                                    <option value="Korea">Korea, Republic of</option>
                                    <option value="Mexico">Mexico</option>
                                    <option value="Philippines">Philippines</option>
                                    <option value="Russia">Russian Federation</option>
                                    <option value="South Africa">South Africa</option>
                                    <option value="Thailand">Thailand</option>
                                    <option value="Turkey">Turkey</option>
                                    <option value="Ukraine">Ukraine</option>
                                    <option value="United Arab Emirates">United Arab Emirates</option>
                                    <option value="United Kingdom">United Kingdom</option>
                                    <option value="United States">United States</option>
                                </select>
                            </div>
                            <div class="mb-6">
                                <label class="form-label" for="user-role">User Role</label>
                                <select id="user-role" class="form-select">
                                    <option value="subscriber">Subscriber</option>
                                    <option value="editor">Editor</option>
                                    <option value="maintainer">Maintainer</option>
                                    <option value="author">Author</option>
                                    <option value="admin">Admin</option>
                                </select>
                            </div>
                            <div class="mb-6">
                                <label class="form-label" for="user-plan">Select Plan</label>
                                <select id="user-plan" class="form-select">
                                    <option value="basic">Basic</option>
                                    <option value="enterprise">Enterprise</option>
                                    <option value="company">Company</option>
                                    <option value="team">Team</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary me-3 data-submit">Submit</button>
                            <button type="reset" class="btn btn-label-danger" data-bs-dismiss="modal">Cancel</button>
                        </form>
                    </div>
                </div>
            </div>
        </div> --}}

    </div>

@endsection
