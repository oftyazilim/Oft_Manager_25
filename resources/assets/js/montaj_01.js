import axios from 'axios';


// Gösterilecek yazılar
const texts = ['0', '...'];
const textsBaslik = ['(Haftalık)', '(Günlük)'];
const textsMiktar1 = ['0', '0'];
const textsMiktar2 = ['0', '0'];
const textsMiktar3 = ['0', '0'];
const textsMiktar4 = ['0', '0'];
const textsMiktar5 = ['0', '0'];
const textsMiktar6 = ['0', '0'];

const miktarTexts = {
  'BG-1': textsMiktar1,
  'PG-1': textsMiktar2,
  'ŞG-1': textsMiktar3,
  'SG-1': textsMiktar4,
  'VG-1': textsMiktar5,
  'GG-1': textsMiktar6
};

let currentTextIndex = 0;

const textContainer = document.getElementById('text-container'); // Tekil öğe
const textContainersBaslik = document.querySelectorAll('.baslikUretim'); // Çoklu öğeler
const textContainersMiktar1 = document.querySelectorAll('.miktarUretim1');
const textContainersMiktar2 = document.querySelectorAll('.miktarUretim2');
const textContainersMiktar3 = document.querySelectorAll('.miktarUretim3');
const textContainersMiktar4 = document.querySelectorAll('.miktarUretim4');
const textContainersMiktar5 = document.querySelectorAll('.miktarUretim5');
const textContainersMiktar6 = document.querySelectorAll('.miktarUretim6');

// Yazıları öğelere yerleştiren fonksiyon
function updateTextContent(container, text) {
  container.textContent = text;
  container.classList.add('visible');
}

// Yazıların kaybolmasını sağlayan fonksiyon
function hideTextContent(container) {
  container.classList.remove('visible');
}

// Herhangi bir öğe listesi için yazıları güncelleyip gösteren fonksiyon
function updateMultipleTextContents(containers, textsArray) {
  containers.forEach((container, index) => {
    updateTextContent(container, textsArray[currentTextIndex]);
  });
}

// Herhangi bir öğe listesi için yazıları gizleyen fonksiyon
function hideMultipleTextContents(containers) {
  containers.forEach(hideTextContent);
}

// Miktar güncelleme işlemini yapan fonksiyon
function updateMiktar() {
  genelPlan = 0;
  genelUretim = 0;

  const grupKodlari = ['BG-1', 'PG-1', 'ŞG-1', 'SG-1', 'VG-1', 'GG-1'];
  grupKodlari.forEach(kod => miktarAl(kod));
}

// Yazıları sırayla gösteren fonksiyon
function showNextText() {
  // Ana öğedeki metni güncelle
  updateTextContent(textContainer, texts[currentTextIndex]);

  // Diğer öğelerdeki metinleri güncelle
  updateMultipleTextContents(textContainersBaslik, textsBaslik);
  updateMultipleTextContents(textContainersMiktar1, textsMiktar1);
  updateMultipleTextContents(textContainersMiktar2, textsMiktar2);
  updateMultipleTextContents(textContainersMiktar3, textsMiktar3);
  updateMultipleTextContents(textContainersMiktar4, textsMiktar4);
  updateMultipleTextContents(textContainersMiktar5, textsMiktar5);
  updateMultipleTextContents(textContainersMiktar6, textsMiktar6);

  // 3 saniye sonra metinleri gizle
  setTimeout(() => {
    hideTextContent(textContainer);
    hideMultipleTextContents(textContainersBaslik);
    hideMultipleTextContents(textContainersMiktar1);
    hideMultipleTextContents(textContainersMiktar2);
    hideMultipleTextContents(textContainersMiktar3);
    hideMultipleTextContents(textContainersMiktar4);
    hideMultipleTextContents(textContainersMiktar5);
    hideMultipleTextContents(textContainersMiktar6);

    // 1 saniye sonra yeni metni göster
    setTimeout(() => {
      currentTextIndex = (currentTextIndex + 1) % texts.length;
      updateMiktar(); // Miktarları güncelle
      showNextText(); // Tekrar sıradaki yazıyı göster
    }, 1000); // Fade-out süresi
  }, 3000); // Görünür olma süresi
}

updateMiktar();
// İlk metni göster
showNextText();

var genelPlan = 0;
var genelUretim = 0;

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
      $('#guncelleme').html('Son Veri Güncelleme: ' + formatliTarihSaat);
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
      var plnHafta =plan.planHafta.toplam_planlanan;
      var urtGun = plan.urtGun.toplam_uretim;
      var urtHafta = plan.urtHafta.toplam_uretim;

      if (plnHafta == null) plnHafta = 0;
      if (urtHafta == null) urtHafta = 0;
      if (urtGun == null) urtGun = 0;

      if (plan && plnHafta !== null) {
        genelPlan += plnHafta;
        genelUretim += urtHafta;
        var yuzde = Math.round((genelUretim / genelPlan) * 100);
        var yorum = '';

        if (yuzde <= 30) yorum = 'berbat';
        else if (yuzde > 30 && yuzde <= 40) yorum = 'kötü';
        else if (yuzde > 40 && yuzde <= 50) yorum = 'sıkıntılı';
        else if (yuzde > 50 && yuzde <= 65) yorum = 'eh işte';
        else if (yuzde > 65 && yuzde <= 80) yorum = 'iyi';
        else if (yuzde > 80 && yuzde <= 90) yorum = 'çok iyi';
        else if (yuzde > 90 && yuzde <= 99) yorum = 'harika';
        else if (yuzde >= 99) yorum = 'süper';

        texts[0] = '%' + yuzde;
        texts[1] = yorum;

        if (urtHafta == null) urtHafta = 0;
        if (urtGun == null) urtGun=0;
        
        updateMiktar1(ist, urtHafta, urtGun);

        $('#' + ist + 'Plan').html(plnHafta);
        var kalan = plnHafta - urtHafta;
        $('#' + ist + 'Kalan').html(kalan);
        var yuzde = Math.round((urtHafta / plnHafta) * 100);
        // console.log(yuzde);
        $('#' + ist + 'Progress').css('width', yuzde + '%');
        $('#' + ist + 'Progress').attr('aria-valuenow', yuzde);

    $('#' + ist + 'AnlikYuzde').html(yuzde);

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


// Miktarları güncelleme fonksiyonu
function updateMiktar1(ist, hafta, gun) {
  if (miktarTexts[ist]) {
    miktarTexts[ist][0] = hafta.toFixed(0);
    miktarTexts[ist][1] = gun.toFixed(0);
  }
}

// 3 saniyede bir fetchData, x ve y fonksiyonlarını çalıştır
setInterval(() => {
  zamanAl();
  mesajAl();
  console.log(1);
}, 3000);



    // İşe başlama saati (08:00)
    const startHour = 8;

    // Mevcut zaman bilgisi
    const now = new Date();
    const currentHour = now.getHours();
    const currentMinute = now.getMinutes();
const totalDays = 6;  // Haftada 6 gün çalışma
const totalHoursPerDay = 8; // Günde 8 saat
const currentDayOfWeek = new Date().getDay();  // Haftanın günü (Pazartesi: 1)

// Toplam haftalık çalışma saatini hesapla
const totalWorkHoursInWeek = totalDays * totalHoursPerDay;

// Haftanın mevcut zamanına kadar olması gereken iş emri sayısını hesapla
const elapsedDays = currentDayOfWeek - 1;  // Pazartesi 0 olsun
const elapsedHoursToday = currentHour; // 5. saat
const elapsedWorkHours = (elapsedDays * totalHoursPerDay) + elapsedHoursToday;

// Performans verilerini almak için AJAX kullan
async function getMachinePerformance() {
    const response = await fetch('/performance'); // Laravel'den veri çek
    const machines = await response.json();

    machines.forEach(machine => {
        const expectedOrders = (elapsedWorkHours / totalWorkHoursInWeek) * machine.weeklyTarget; // Haftalık hedefe göre
        const performance = (machine.completedOrders / expectedOrders) * 100;  // Gerçekleşene göre yüzde hesaplama

        console.log(`Makine: ${machine.name}`);
        console.log(`Planlanan iş emri: ${machine.weeklyTarget.toFixed(2)}`);
        console.log(`Beklenen iş emri: ${expectedOrders.toFixed(2)}`);
        console.log(`Gerçekleşen iş emri: ${machine.completedOrders}`);
        console.log(`Performans: ${performance.toFixed(2)}%`);
    });







// İşe başlanılan saatten şu ana kadar geçen zamanı hesapla
let hoursWorked = currentHour - startHour;
let minutesWorked = currentMinute;

// Eğer saat 08:00'dan önce ise 0 çalışılan saat göster
if (hoursWorked < 0) {
    hoursWorked = 0;
    minutesWorked = 0;
}

console.log(`Çalışılan süre: ${hoursWorked} saat ve ${minutesWorked} dakika`);

}

getMachinePerformance();
