@php
    $configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Dokümanlar')

<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.10.377/pdf.min.js"></script>
{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.10.377/pdf.worker.min.js"></script> --}}
<script>
  // Worker dosyasının yolunu doğru bir şekilde tanımlayın
  pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.10.377/pdf.worker.min.js';
</script>

@section('vendor-style')
    @vite(['resources/assets/vendor/libs/apex-charts/apex-charts.scss', 'resources/assets/vendor/libs/swiper/swiper.scss', 'resources/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.scss', 'resources/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.scss', 'resources/assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.scss'])
@endsection

@section('page-style')
    <!-- Page -->
    @vite(['resources/assets/vendor/scss/pages/cards-advance.scss'])
@endsection

@section('vendor-script')
    @vite(['resources/assets/vendor/libs/apex-charts/apexcharts.js', 'resources/assets/vendor/libs/swiper/swiper.js', 'resources/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js'])
@endsection

@section('page-script')
@section('content')

    <div class="row g-6">


        <table>
          <tr>
              <th>Title</th>
              <th>Actions</th>
          </tr>
          @foreach($documents as $document)
          <tr>
              <td>{{ $document->title }}</td>
              <td>
                  <a href="{{ route('documents.show', $document->id) }}">View</a>
              </td>
          </tr>
          @endforeach
      </table>


    </div>



      <script src="//mozilla.github.io/pdf.js/build/pdf.mjs" type="module"></script>

      <script type="module">
        // If absolute URL from the remote server is provided, configure the CORS
        // header on that server.
        var url = "{{ route('documents.show', $document->id) }}";  // PDF dosyasının URL'si
        // var url = 'https://raw.githubusercontent.com/mozilla/pdf.js/ba2edeae/web/compressed.tracemonkey-pldi-09.pdf';

        // Loaded via <script> tag, create shortcut to access PDF.js exports.
          var { pdfjsLib } = globalThis;

          // The workerSrc property shall be specified.
          //  pdfjsLib.GlobalWorkerOptions.workerSrc = '//mozilla.github.io/pdf.js/build/pdf.worker.mjs';

          var pdfDoc = null,
          pageNum = 1,
          pageRendering = false,
          pageNumPending = null,
          scale = 0.8,
          canvas = document.getElementById('the-canvas'),
          ctx = canvas.getContext('2d');

          /**
           * Get page info from document, resize canvas accordingly, and render page.
           * @param num Page number.
           */
          function renderPage(num) {
            pageRendering = true;
            // Using promise to fetch the page
            pdfDoc.getPage(num).then(function(page) {
              var viewport = page.getViewport({scale: scale});
              canvas.height = viewport.height;
              canvas.width = viewport.width;

              // Render PDF page into canvas context
              var renderContext = {
                canvasContext: ctx,
                viewport: viewport
              };
              var renderTask = page.render(renderContext);

              // Wait for rendering to finish
              renderTask.promise.then(function() {
                pageRendering = false;
                if (pageNumPending !== null) {
                  // New page rendering is pending
                  renderPage(pageNumPending);
                  pageNumPending = null;
                }
              });
            });

            // Update page counters
            document.getElementById('page_num').textContent = num;
          }

          /**
           * If another page rendering in progress, waits until the rendering is
           * finised. Otherwise, executes rendering immediately.
           */
          function queueRenderPage(num) {
            if (pageRendering) {
              pageNumPending = num;
            } else {
              renderPage(num);
            }
          }

          /**
           * Displays previous page.
           */
          function onPrevPage() {
            if (pageNum <= 1) {
              return;
            }
            pageNum--;
            queueRenderPage(pageNum);
          }
          document.getElementById('prev').addEventListener('click', onPrevPage);

          /**
           * Displays next page.
           */
          function onNextPage() {
            if (pageNum >= pdfDoc.numPages) {
              return;
            }
            pageNum++;
            queueRenderPage(pageNum);
          }
          document.getElementById('next').addEventListener('click', onNextPage);

          /**
           * Asynchronously downloads PDF.
           */
          pdfjsLib.getDocument(url).promise.then(function(pdfDoc_) {
            pdfDoc = pdfDoc_;
            document.getElementById('page_count').textContent = pdfDoc.numPages;

            // Initial/first page rendering
            renderPage(pageNum);
          });
          </script>


<div>
  <button id="prev">Previous</button>
  <button id="next">Next</button>
  &nbsp; &nbsp;
  <span>Page: <span id="page_num"></span> / <span id="page_count"></span></span>
</div>

<canvas id="the-canvas"></canvas>







<style>
  #the-canvas {
    border: 1px solid black;
    direction: ltr;
  }
  </style>


{{-- <div id="pdf-viewer"></div>

<script>
  var url = "{{ route('documents.show', $document->id) }}";  // PDF dosyasının URL'si

  // PDF.js kütüphanesini başlat
  var loadingTask = pdfjsLib.getDocument(url);
  loadingTask.promise.then(function(pdf) {
    console.log('PDF yüklendi');

    // İlk sayfayı getir ve göster
    pdf.getPage(1).then(function(page) {
      console.log('Sayfa getirildi');

      var scale = 1.5;
      var viewport = page.getViewport({ scale: scale });

           // Canvas elementini oluştur
           var canvas = document.createElement('canvas');
           var context = canvas.getContext('2d');
           canvas.height = viewport.height;
           canvas.width = viewport.width;

           document.getElementById('pdf-viewer').appendChild(canvas);

           // Sayfayı canvas üzerinde çiz
           var renderContext = {
               canvasContext: context,
               viewport: viewport
           };
           page.render(renderContext);
       });
   }, function (reason) {
       // PDF yüklenmezse hata
       console.error(reason);
   });
</script> --}}

@endsection
