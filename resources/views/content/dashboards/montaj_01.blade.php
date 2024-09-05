@isset($pageConfigs)
    {!! Helper::updatePageConfig($pageConfigs) !!}
@endisset

@php
    $configData = Helper::appClasses();

    /* Display elements */
    $customizerHidden = $customizerHidden ?? '';

@endphp
@extends('layouts/commonMaster')

@section('layoutContent')
    <style>
        .custom-row {
            display: flex;
            flex-wrap: wrap;
            margin-right: -5px;
            margin-left: 0px;
        }

        .custom-col {
            flex: 0 0 16.2%;
            max-width: 16.2%;
            flex-grow: 1;
        }

        .custom-col-yazi {
            flex: 0 0 82.2%;
            max-width: 82.5%;
            flex-grow: 1;
        }

        .card-header {
            text-align: center;
            padding: 0%;
            margin-top: 10px;
            font-size: 50px;
            font-weight: bold;
            color: #657fc9;
        }

        .baslik {
            text-align: center;
            padding: 0%;
            margin-top: 10px;
            font-size: 30px;
            font-weight: bold;
            color: rgb(154, 166, 148);
        }

        .centered-div {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 18px;
            padding-top: 13px;
        }

        .miktar {
            display: flex;
            font-size: 120px;
            font-weight: bold;
            justify-content: center;
            margin-top: 50px;
            margin-bottom: 30px;
        }

        .miktar-yuzde {
            display: flex;
            font-size: 80px;
            font-weight: bold;
            justify-content: center;
            margin-top: 50px;
            margin-bottom: 30px;
        }







        .marquee {
            width: 100%;
            overflow: hidden;
            white-space: nowrap;
            box-sizing: border-box;
        }

        .marquee-text {
            display: inline-block;
            padding-left: 100%;
            font-size: 70px;
            height: 70px;
            animation: marquee 20s linear infinite;
            margin-top: 30px;
            color: yellowgreen;
        }

        @keyframes marquee {
            0% {
                transform: translateX(10%);
            }

            100% {
                transform: translateX(-100%);
            }
        }

        .fade-text {
            opacity: 0;
            transition: opacity 1s ease-in-out; /* Yumuşak geçişler */
            font-size: 24px;
            text-align: center;
            margin-top: 20px;
        }

        .visible {
            opacity: 1;
        }

    </style>

    <div class="card m-1">
        <div class="border rounded p-2">
            <h6 class="centered-div " style="font-size: 24px; justify-content: right; padding-right: 10px; color:limegreen;">
                Son Güncelleme: 05.09.2024 23:00
            </h6>
        </div>
    </div>

    <div class="custom-row">

        <div class="custom-col card m-1">
            <div class="card-header">BORU</div>
            <div class="card-body  p-2 m-3">
                <div class="border rounded p-2">
                    <h6 class="text-center baslik">Haftalık Plan</h6>
                    <h4 class="miktar" style="color: limegreen">0</h4>
                </div>
                <div class="border rounded p-2 mt-3">
                    <h6 class="text-center baslik">Üretilen</h6>
                    <h4 class="miktar" style="color: rgb(139, 136, 236)">0</h4>
                </div>
                <div class="border rounded p-2 mt-3">
                    <h6 class="text-center baslik">Kalan</h6>
                    <h4 class="miktar" style="color: rgb(210, 138, 138)">0</h4>
                </div>
                <div class="border rounded p-2 mt-3">
                    <h6 class="text-center baslik">Anlık Performans</h6>
                    <h4 class="miktar-yuzde" style="color: rgb(217, 227, 73)">%80</h4>
                </div>
            </div>
        </div>
        <div class="custom-col card m-1">
            <div class="card-header">POMPA</div>
            <div class="card-body  p-2 m-3">
                <div class="border rounded p-2">
                    <h6 class="text-center baslik">Haftalık Plan</h6>
                    <h4 class="miktar" style="color: limegreen">0</h4>
                </div>
                <div class="border rounded p-2 mt-3">
                    <h6 class="text-center baslik">Üretilen</h6>
                    <h4 class="miktar" style="color: rgb(139, 136, 236)">0</h4>
                </div>
                <div class="border rounded p-2 mt-3">
                    <h6 class="text-center baslik">Kalan</h6>
                    <h4 class="miktar" style="color: rgb(210, 138, 138)">0</h4>
                </div>
                <div class="border rounded p-2 mt-3">
                    <h6 class="text-center baslik">Anlık Performans</h6>
                    <h4 class="miktar-yuzde" style="color: rgb(217, 227, 73)">%80</h4>
                </div>
            </div>
        </div>
        <div class="custom-col card m-1">
            <div class="card-header">ŞASE</div>
            <div class="card-body  p-2 m-3">
                <div class="border rounded p-2">
                    <h6 class="text-center baslik">Haftalık Plan</h6>
                    <h4 class="miktar" style="color: limegreen">0</h4>
                </div>
                <div class="border rounded p-2 mt-3">
                    <h6 class="text-center baslik">Üretilen</h6>
                    <h4 class="miktar" style="color: rgb(139, 136, 236)">0</h4>
                </div>
                <div class="border rounded p-2 mt-3">
                    <h6 class="text-center baslik">Kalan</h6>
                    <h4 class="miktar" style="color: rgb(210, 138, 138)">0</h4>
                </div>
                <div class="border rounded p-2 mt-3">
                    <h6 class="text-center baslik">Anlık Performans</h6>
                    <h4 class="miktar-yuzde" style="color: rgb(217, 227, 73)">%80</h4>
                </div>
            </div>

        </div>
        <div class="custom-col card m-1">
            <div class="card-header">SİFON</div>
            <div class="card-body  p-2 m-3">
                <div class="border rounded p-2">
                    <h6 class="text-center baslik">Haftalık Plan</h6>
                    <h4 class="miktar" style="color: limegreen">0</h4>
                </div>
                <div class="border rounded p-2 mt-3">
                    <h6 class="text-center baslik">Üretilen</h6>
                    <h4 class="miktar" style="color: rgb(139, 136, 236)">0</h4>
                </div>
                <div class="border rounded p-2 mt-3">
                    <h6 class="text-center baslik">Kalan</h6>
                    <h4 class="miktar" style="color: rgb(210, 138, 138)">0</h4>
                </div>
                <div class="border rounded p-2 mt-3">
                    <h6 class="text-center baslik">Anlık Performans</h6>
                    <h4 class="miktar-yuzde" style="color: rgb(217, 227, 73)">%80</h4>
                </div>
            </div>

        </div>
        <div class="custom-col card m-1">
            <div class="card-header">VENTURİ</div>
            <div class="card-body  p-2 m-3">
                <div class="border rounded p-2">
                    <h6 class="text-center baslik">Haftalık Plan</h6>
                    <h4 class="miktar" style="color: limegreen">0</h4>
                </div>
                <div class="border rounded p-2 mt-3">
                    <h6 class="text-center baslik">Üretilen</h6>
                    <h4 class="miktar" style="color: rgb(139, 136, 236)">0</h4>
                </div>
                <div class="border rounded p-2 mt-3">
                    <h6 class="text-center baslik">Kalan</h6>
                    <h4 class="miktar" style="color: rgb(210, 138, 138)">0</h4>
                </div>
                <div class="border rounded p-2 mt-3">
                    <h6 class="text-center baslik">Anlık Performans</h6>
                    <h4 class="miktar-yuzde" style="color: rgb(217, 227, 73)">%80</h4>
                </div>
            </div>
        </div>
        <div class="custom-col card m-1">
            <div class="card-header">GALVANİZ</div>
            <div class="card-body  p-2 m-3">
                <div class="border rounded p-2">
                    <h6 class="text-center baslik">Haftalık Plan</h6>
                    <h4 class="miktar" style="color: limegreen">0</h4>
                </div>
                <div class="border rounded p-2 mt-3">
                    <h6 class="text-center baslik">Üretilen</h6>
                    <h4 class="miktar" style="color: rgb(139, 136, 236)">0</h4>
                </div>
                <div class="border rounded p-2 mt-3">
                    <h6 class="text-center baslik">Kalan</h6>
                    <h4 class="miktar" style="color: rgb(210, 138, 138)">0</h4>
                </div>
                <div class="border rounded p-2 mt-3">
                    <h6 class="text-center baslik">Anlık Performans</h6>
                    <h4 class="miktar-yuzde" style="color: rgb(217, 227, 73)">%80</h4>
                </div>
            </div>
        </div>




    </div>

    <div class="custom-row">
        <div class="custom-col card m-1" style="height: 150px; ">
            <div class="card-body p-1 m-1">
                <div class="border rounded p-2">
                    <h6 class="text-center baslik">Genel Performans</h6>
                    <h4 id="text-container" class="fade-text text-center mt-4 pt-3" style="color: limegreen;
                    font-size: 70px; font-weight: bold;">%70
                    </h4>
                </div>
            </div>
        </div>
        <div class="custom-col-yazi card m-1">
            <div class="card-body  p-1 m-1">
                <div class="border rounded p-2">
                    <div class="marquee">
                        <h1 class="marquee-text">Sevgili arkadaşlar verilerimizi zamanında, eksiksiz ve doğru girelim!</h1>
                    </div>
                </div>
            </div>

        </div>
    </div>




    <div id="text-container" class="fade-text">
      <!-- Dinamik olarak yazılar burada değişecek -->
  </div>

  <script>
      // Gösterilecek yazılar
      const texts = [
          "% 70",
          "KÖTÜ"
      ];

      let currentTextIndex = 0;
      const textContainer = document.getElementById('text-container');

      // Yazıları sırayla gösteren fonksiyon
      function showNextText() {
          // Yeni yazıyı koy
          textContainer.textContent = texts[currentTextIndex];

          // Yazıyı görünür hale getir
          textContainer.classList.add('visible');

          // 3 saniye sonra yazıyı kaybet
          setTimeout(() => {
              textContainer.classList.remove('visible');

              // 1 saniye sonra yeni yazıyı getir
              setTimeout(() => {
                  currentTextIndex = (currentTextIndex + 1) % texts.length;
                  showNextText();
              }, 1000); // Fade-out süresi (1 saniye)
          }, 3000); // Görünür olma süresi (3 saniye)
      }

      // İlk yazıyı göster
      showNextText();
  </script>
@endsection
