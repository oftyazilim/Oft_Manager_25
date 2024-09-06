import axios from 'axios';

// Gösterilecek yazılar
const texts = ['0', '...'];
const textsBaslik = ['(Haftalık)', '(Günlük)'];
const textsMiktar = ['0', '0'];

let currentTextIndex = 0;
const textContainer = document.getElementById('text-container'); // ID ile tek bir öğeyi seçer
const textContainersBaslik = document.querySelectorAll('.baslikUretim'); // Class ile tüm ilgili öğeleri seçer
const textContainersMiktar = document.querySelectorAll('.miktarUretim'); // Class ile tüm ilgili öğeleri seçer

// Yazıları sırayla gösteren fonksiyon
function showNextText() {
  // Yeni yazıyı ID ile alınan öğeye koy
  textContainer.textContent = texts[currentTextIndex];

  // Tüm baslikUretim class'lı öğelere sırayla yazıyı koy
  textContainersBaslik.forEach((textContainerBaslik) => {
    textContainerBaslik.textContent = textsBaslik[currentTextIndex];

    // Görünür hale getir
    textContainer.classList.add('visible');
    textContainerBaslik.classList.add('visible');
  });

  textContainersMiktar.forEach((textContainerMiktar) => {
    textContainerMiktar.textContent = textsMiktar[currentTextIndex];

    // Görünür hale getir
    textContainer.classList.add('visible');
    textContainerMiktar.classList.add('visible');
  });

  // 3 saniye sonra yazıyı kaybet
  setTimeout(() => {
    textContainer.classList.remove('visible');
    textContainersBaslik.forEach((textContainerBaslik) => {
      textContainerBaslik.classList.remove('visible');
    });

    textContainersMiktar.forEach((textContainerMiktar) => {
      textContainerMiktar.classList.remove('visible');
    });

    // 1 saniye sonra yeni yazıyı getir
    setTimeout(() => {
      currentTextIndex = (currentTextIndex + 1) % texts.length;

      genelPlan = 0;
      genelUretim = 0;

      miktarAl('BG-1');
      miktarAl('PG-1');
      miktarAl('ŞG-1');
      miktarAl('SG-1');
      miktarAl('VG-1');
      miktarAl('GG-1');

      showNextText();

    }, 1000); // Fade-out süresi (1 saniye)
  }, 3000); // Görünür olma süresi (3 saniye)
}


miktarAl('BG-1');
miktarAl('PG-1');
miktarAl('ŞG-1');
miktarAl('SG-1');
miktarAl('VG-1');
miktarAl('GG-1');
// İlk çağrı
showNextText();








// axios.defaults.withCredentials = true;
// axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

var genelPlan = 0;
var genelUretim = 0;

// Axios'u kullanarak veri çeken fonksiyon
function zamanAl() {
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
}

function mesajAl() {
var mesaj = 'Verilerimizi zamanında, eksiksiz ve doğru girelim!';
  axios
    .get('/dashboards/mesajal')
    .then(function (response) {
      mesaj = response.data.DEGER;
      if (mesaj == '') mesaj = 'Arkadaşlar verilerimizi zamanında, eksiksiz ve doğru girelim!';
      $('#altMesaj').html(mesaj);
    })
    .catch(function (error) {
      console.error('Mesaj çekme hatası:', error);
      $('#altMesaj').html(mesaj);
    });
}

function miktarAl(ist) {
  axios
    .get('/dashboards/miktaral', {
      params: {
        param1: ist
      }
    })
    .then(function (response) {
      var plan = response.data;
      if (plan && plan.toplam_planlanan !== null && plan.toplam_uretim !== null) {
        genelPlan += plan.toplam_planlanan;
        genelUretim += plan.toplam_uretim;
        var yuzde = Math.round((genelUretim / genelPlan) * 100);
        var yorum = '';

        if (yuzde <= 20) yorum = 'berbat';
        else if (yuzde > 20 && yuzde <= 40) yorum = 'çok kötü';
        else if (yuzde > 20 && yuzde <= 40) yorum = 'kötü';
        else if (yuzde > 40 && yuzde <= 50) yorum = 'sıkıntılı';
        else if (yuzde > 50 && yuzde <= 60) yorum = 'eh işte';
        else if (yuzde > 60 && yuzde <= 70) yorum = 'fena değil';
        else if (yuzde > 70 && yuzde <= 80) yorum = 'iyi';
        else if (yuzde > 80 && yuzde <= 90) yorum = 'çok iyi';
        else if (yuzde > 90 && yuzde <= 99) yorum = 'harika';
        else if (yuzde >= 99) yorum = 'süper';

        texts[0] = '%' + yuzde;
        texts[1] = yorum;
        textsMiktar[0] = plan.toplam_planlanan;

        textsMiktar[1] = plan.toplam_uretim;

        $('#' + ist + 'Plan').html(plan.toplam_planlanan);
        //$('#' + ist + 'Uretilen').html(textsMiktar[1]);
        $('#' + ist + 'Kalan').html(plan.toplam_planlanan - plan.toplam_uretim);
        var yuzde = Math.round((plan.toplam_uretim / plan.toplam_planlanan) * 100);
        $('#' + ist + 'Progress').css('width', yuzde + '%');
        $('#' + ist + 'Progress').attr('aria-valuenow', yuzde); // Accessibility için
        // $('#' + ist + 'Progress').text(yuzde + '%');  // Progress bar içindeki yüzde metni
        // $('#' + ist + 'AnlikYuzde').html('<span class="yuzdeIsareti">%</span>' + yuzde);
      } else {
        $('#' + ist + 'Plan').html('0');
        $('#' + ist + 'Uretilen').html('0');
        $('#' + ist + 'Kalan').html('0');
        $('#' + ist + 'AnlikYuzde').html('<span class="yuzdeIsareti">%</span>0');
      }
    })
    .catch(function (error) {
      console.error('Plan çekme hatası:', error);
    });
}

// 3 saniyede bir fetchData, x ve y fonksiyonlarını çalıştır
setInterval(() => {
  zamanAl();
  mesajAl();
console.log(1);
}, 3000);
