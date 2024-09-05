import axios from 'axios';

// Gösterilecek yazılar
const texts = ['% 70', 'Kötü', '%80', 'İyi'];

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

// axios.defaults.withCredentials = true;
// axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

// Axios'u kullanarak veri çeken fonksiyon
function fetchData() {
  axios
    .get('/dashboards/zamanal')
    .then(function (response) {
      var tarih = response.data;
      if (!tarih == '') {
        var unixTimestamp = Math.floor(new Date(tarih.KAYITTARIH).getTime() / 1000);
        var excelDate = 25569 + (unixTimestamp + 10800) / 86400;
        var convertedUnixTimestamp = (excelDate - 25569) * 86400 - 10800;
        var tarih = new Date(convertedUnixTimestamp * 1000);
        var gun = String(tarih.getDate()).padStart(2, '0'); // Günü 2 basamaklı hale getir
        var ay = String(tarih.getMonth() + 1).padStart(2, '0'); // Aylar 0'dan başlıyor, bu yüzden +1 ekliyoruz
        var yil = tarih.getFullYear(); // Yılı alıyoruz
        var saat = String(tarih.getHours()).padStart(2, '0'); // Saat 2 basamaklı hale getir
        var dakika = String(tarih.getMinutes()).padStart(2, '0'); // Dakika 2 basamaklı hale getir

        var formatliTarihSaat = `${gun}.${ay}.${yil} - ${saat}:${dakika}`;
      } else {
        var formatliTarihSaat = '...';
      }
      $('#guncelleme').html('Son Güncelleme: ' + formatliTarihSaat);
    })
    .catch(function (error) {
      console.error('Zaman çekme hatası:', error);
    });

  var ist = 'BG-1';

  axios
    .get('/dashboards/miktaral', {
      params: {
        param1: 'BG-1'
      }
    })
    .then(function (response) {
      var plan = response.data;

      // Eğer 'plan' verisi varsa ve doğru formatta ise
      if (plan && plan.toplam_planlanan !== undefined && plan.toplam_uretim !== undefined) {
        $('#boruPlan').html(plan.toplam_planlanan);
        $('#boruUretilen').html(plan.toplam_uretim);
        $('#boruKalan').html(plan.toplam_planlanan - plan.toplam_uretim);
        var yuzde = Math.round((plan.toplam_uretim / plan.toplam_planlanan) * 100);
        $('#boruAnlikYuzde').html('<span class="yuzdeIsareti">%</span>'+yuzde  );
      } else {
        // Veriyi kontrol etmeme veya varsayılan bir değer gösterme
        $('#boruPlan').html('Veri bulunamadı');
        $('#boruUretilen').html('Veri bulunamadı');
        $('#boruKalan').html('Veri bulunamadı');
        $('#boruAnlikYuzde').html('<span class="yuzdeIsareti">%</span>0');
      }
    })
    .catch(function (error) {
      console.error('Plan çekme hatası:', error);
    });
}

// Her 5 saniyede bir veri çek
setInterval(fetchData, 3000); // 5000 milisaniye = 5 saniye
