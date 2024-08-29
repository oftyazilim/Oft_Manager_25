'use strict';
import Swal from 'sweetalert2';
import axios from 'axios';


  var emirler = json($emirler);
  console.log(emirler);

  $('#datatables-emirler').DataTable({
    processing: true,
    serverSide: true,
    ajax: ('/api/emirler').then(function (response) {
                data = response.data;
                console.log(data[0].ID);
              }),
    columns: [
        { data: 'ID' },
    ]

  });

