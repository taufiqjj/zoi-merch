<?php
include 'koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $name = htmlspecialchars($_POST['name']);
  $rate = (int) $_POST['rate'];
  $comment = htmlspecialchars($_POST['comment']);

  if (!empty($name) && $rate > 0 && $rate <= 5 && !empty($comment)) {
    $stmt = $koneksi->prepare("INSERT INTO testimoni (name, rate, comment) VALUES (?, ?, ?)");
    $stmt->bind_param("sis", $name, $rate, $comment);
    $stmt->execute();
    header("Location: index.php");
    exit;
  }
}

$sql = "SELECT * FROM testimoni ORDER BY id DESC";
$result = $koneksi->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Landing Page</title>
  <link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.css" />
  <script
    src="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.js"
    defer></script>
  <!-- <script src="https://unpkg.com/@tailwindcss/browser@4"></script> -->
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-[#bgbgbg]">
  <header class="bg-[#bgbgbg] text-white py-3">
    <div
      class="container mx-auto flex justify-between items-center px-14 py-0">
      <img src="icon/zoi.svg" alt="Logo" style="width: 180px" />
      <nav>
        <ul class="flex space-x-4 text-black items-center gap-1">
          <li><a href="#beranda" class="hover:underline">Beranda</a></li>
          <li><a href="#tentang-kami" class="hover:underline">Tentang</a></li>
          <li>
            <a href="#keunggulan" class="hover:underline">Keunggulan</a>
          </li>
          <li><a href="#produk" class="hover:underline">Produk</a></li>
          <li><a href="#ulasan" class="hover:underline">Ulasan</a></li>
          <li class="bg-black px-4 py-2 rounded-lg">
            <a href="#cta" class="hover:underline text-white">Kontak Kami</a>
          </li>
        </ul>
      </nav>
    </div>
  </header>

  <main>
    <section
      id="beranda"
      class="text-white py-52 bg-cover bg-center h-full"
      style="background-image: url('image/Hero.jpg')">
      <div class="container mx-auto text-left px-4 pl-24">
        <p class="">Apapun Kebutuhan Anda,</p>
        <h2 class="text-4xl font-bold mb-12">Konsultasikan dengan ZOI!</h2>
        <a
          href="https://wa.me/6285156739512"
          class="bg-white/60 px-6 py-2 rounded font-bold hover:bg-gray-200 text-white">Hubungi Kami Sekarang!</a>
      </div>
    </section>

    <!-- Features Section -->
    <section id="beranda" class="flex justify-center -mt-12">
      <div
        class="grid grid-cols-3 divide-x-2 divide-dashed divide-[#454545] text-center rounded-2xl shadow-xl bg-white">
        <div class="p-6 px-24">
          <h4 class="text-xl font-bold mb-2">10.000+</h4>
          <p>Pesanan Selesai</p>
        </div>
        <div class="p-6">
          <h4 class="text-xl font-bold mb-2">1.000+</h4>
          <p>Kepuasan Pelanggan</p>
        </div>
        <div class="p-6">
          <h4 class="text-xl font-bold mb-2">11+</h4>
          <p>Tahun Produksi</p>
        </div>
      </div>
    </section>

    <!-- About Section -->
    <section id="beranda" class="mx-20">
      <div class="flex justify-between items-end">
        <h4 class="text-2xl font-bold">Partner Kami<br />Kekuatan Kami</h4>
        <p class="text-sm font-light">
          Setiap langkah kami didukung oleh partner yang terpercaya.
          <br />Kolaborasi ini membawa nilai nyata untuk semua pihak
        </p>
      </div>
      <div class="mt-2">
        <div class="swiper mySwiper">
          <div class="swiper-wrapper">
            <div class="swiper-slide">
              <img src="image/Bumn.png" class="w-full" alt="Bumn" />
            </div>
            <div class="swiper-slide">
              <img src="image/Pln.png" class="w-full" alt="Pln" />
            </div>
            <div class="swiper-slide">
              <img src="image/UMP.png" class="w-full" alt="UMP" />
            </div>
            <div class="swiper-slide">
              <img src="image/Unwiku.png" class="w-full" alt="Unwiku" />
            </div>
            <div class="swiper-slide">
              <img src="image/Bumn.png" class="w-full" alt="Bumn" />
            </div>
            <div class="swiper-slide">
              <img src="image/Mayora.png" class="w-full" alt="Mayora" />
            </div>
            <div class="swiper-slide">
              <img src="image/PDKB.png" class="w-full" alt="PDKB" />
            </div>
            <div class="swiper-slide">
              <img src="image/Unsoed.png" class="w-full" alt="Unsoed" />
            </div>
          </div>
        </div>
      </div>
    </section>

    <section id="tentang-kami" class="mx-20 mt-8 flex gap-20">
      <div class="w-50%">
        <h4 class="text-2xl font-bold">Tentang Kami</h4>
        <p class="text-xl font-light mt-4">
          Kami adalah vendor konveksi yang berdedikasi untuk menyediakan
          produk berkualitas tinggi dengan sentuhan keahlian terbaik.
          Didirikan dengan semangat inovasi dan dedikasi, perjalanan kami
          dimulai pada tahun 2014 bersama mitra-mitra penjahit berbakat yang
          memiliki pengalaman luas di bidangnya.
          <br />
          <br />Selama lebih dari satu dekade, kami telah berhasil
          menyelesaikan puluhan ribu pcs berbagai jenis pakaian, mulai dari
          kaos, PDH (Pakaian Dinas Harian), hingga blazer. Keberhasilan ini
          tidak lepas dari kerja sama erat dengan mitra-mitra penjahit kami
          yang memastikan setiap jahitan memiliki kualitas dan presisi tinggi.
        </p>
      </div>
      <div class="w-full bg-white rounded-2xl shadow-xl">
        <img src="image/Jahit.png" class="p-8" />
      </div>
    </section>

    <section
      id="keunggulan"
      class="px-20 py-8 bg-[#373737] flex gap-20 items-center mt-8">
      <div class="flex bg-white/10% rounded-2xl shadow-xl">
        <img src="image/baju.png" class="p-8 w-[900px]" />
      </div>
      <div class="w-full text-white">
        <h4 class="text-2xl font-bold">Kenapa Harus Kami?</h4>
        <li class="mt-4">
          <b>Kualitas Unggul</b>: Menggunakan bahan terbaik dengan kontrol
          produksi ketat untuk hasil maksimal.
        </li>
        <li class="mt-4">
          <b>Harga Bersaing</b>: Menawarkan solusi konveksi berkualitas dengan
          harga yang terjangkau.
        </li>
        <li class="mt-4">
          <b>Pelayanan Cepat</b>: Pesanan selesai tepat waktu tanpa mengurangi
          mutu.
        </li>
        <li class="mt-4">
          <b>Custom Design</b>: Fleksibilitas untuk desain sesuai kebutuhan
          dan preferensi pelanggan.
        </li>
        <li class="mt-4">
          <b>Berpengalaman dan Terpercaya</b>: Didukung oleh tim profesional
          dengan portofolio klien yang luas.
        </li>
      </div>
    </section>

    <section id="produk" class="px-20 mt-8">
      <h4 class="text-center text-2xl font-bold pb-4">Produk Kami</h4>
      <div class="flex flex-wrap gap-3 justify-center">
        <div class="bg-white shadow-xl p-3 rounded-xl">
          <img
            src="image/product/PDH.png"
            alt="pdh"
            class="shadow-inner p-2 shadow-[#373737]/50" />
          <h4 class="text-lg font-bold text-center py-3">PDH</h4>
        </div>
        <div class="bg-white shadow-xl p-3 rounded-xl">
          <img
            src="image/product/Lanyard.png"
            alt="pdh"
            class="shadow-inner p-2 shadow-[#373737]/50" />
          <h4 class="text-lg font-bold text-center py-3">Lanyard</h4>
        </div>
        <div class="bg-white shadow-xl p-3 rounded-xl">
          <img
            src="image/product/Id Card.png"
            alt="pdh"
            class="shadow-inner p-2 shadow-[#373737]/50" />
          <h4 class="text-lg font-bold text-center py-3">Id Card</h4>
        </div>
        <div class="bg-white shadow-xl p-3 rounded-xl">
          <img
            src="image/product/Kaos.png"
            alt="pdh"
            class="shadow-inner p-2 shadow-[#373737]/50" />
          <h4 class="text-lg font-bold text-center py-3">Kaos</h4>
        </div>
        <div class="bg-white shadow-xl p-3 rounded-xl">
          <img
            src="image/product/Rompi.png"
            alt="pdh"
            class="shadow-inner p-2 shadow-[#373737]/50" />
          <h4 class="text-lg font-bold text-center py-3">Rompi</h4>
        </div>
        <div class="bg-white shadow-xl p-3 rounded-xl">
          <img
            src="image/product/Blazzer.png"
            alt="pdh"
            class="shadow-inner p-2 shadow-[#373737]/50" />
          <h4 class="text-lg font-bold text-center py-3">Blazzer</h4>
        </div>
        <div class="bg-white shadow-xl p-3 rounded-xl">
          <img
            src="image/product/Hoodie.png"
            alt="pdh"
            class="shadow-inner p-2 shadow-[#373737]/50" />
          <h4 class="text-lg font-bold text-center py-3">Hoodie</h4>
        </div>
        <div class="bg-white shadow-xl p-3 rounded-xl">
          <img
            src="image/product/Polo.png"
            alt="pdh"
            class="shadow-inner p-2 shadow-[#373737]/50" />
          <h4 class="text-lg font-bold text-center py-3">Polo</h4>
        </div>
      </div>
    </section>

    <section id="ulasan" class="mt-8 mx-20">
      <div class="flex justify-between items-end">
        <h4 class="text-2xl font-bold">Ulasan Pelanggan</h4>
      </div>
      <div class="mt-2">
        <div class="swiper ulasanSwiper">
          <div class="swiper-wrapper pb-4">
            <?php if ($result->num_rows > 0): ?>
              <?php while ($row = $result->fetch_assoc()): ?>
                <div class="swiper-slide w-[341px] h-[152px] bg-white p-2 rounded-lg shadow-lg">
                  <div class="flex items-center gap-4">
                    <img src="image/Profile.png" alt="" />
                    <div class="">
                      <h5 class="text-xl font-bold text-black">
                        <?= htmlspecialchars($row['name']) ?>
                      </h5>

                    </div>
                    <div class="flex ml-auto">
                      <?php for ($i = 0; $i < 5; $i++): ?>
                        <img src="image/<?= $i < $row['rate'] ? 'Star.png' : 'Star-empty.png' ?>" alt="Star" class="w-5 h-5" />
                      <?php endfor; ?>
                    </div>
                  </div>
                  <div class="mt-3">
                    <p class="text-sm font-normal">
                      <?= htmlspecialchars($row['comment']) ?>
                    </p>
                  </div>
                </div>
              <?php endwhile; ?>
            <?php else: ?>
          </div>
          <p class="text-center text-gray-600">Belum ada testimoni.</p>
        <?php endif; ?>
        </div>
      </div>
    </section>

    <section class="mt-8 mx-20 bg-white p-6 rounded-lg shadow">
      <h2 class="text-2xl font-bold mb-4">Tambah Testimoni</h2>
      <form action="index.php" method="POST" class="space-y-4">
        <div>
          <label class="block text-sm font-medium">Nama</label>
          <input type="text" name="name" required class="w-full border rounded p-2" />
        </div>
        <!-- Rating Bintang -->
        <div>
          <label class="block text-sm font-medium mb-1">Rating</label>
          <div id="star-rating" class="flex space-x-1 cursor-pointer">
            <?php for ($i = 1; $i <= 5; $i++): ?>
              <svg xmlns="http://www.w3.org/2000/svg"
                data-value="<?= $i ?>"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="1.5"
                class="w-7 h-7 text-yellow-500 transition-colors">
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  d="M11.48 3.499a.562.562 0 011.04 0l2.067 4.193a.563.563 0 00.424.307
                  l4.624.672a.562.562 0 01.312.957l-3.346 3.263a.562.562 0 00-.162.498
                  l.79 4.604a.563.563 0 01-.817.593l-4.133-2.174a.563.563 0 00-.523 0
                  l-4.133 2.174a.563.563 0 01-.817-.593l.79-4.604a.562.562 0 00-.162-.498
                  L2.478 9.628a.562.562 0 01.312-.957l4.624-.672a.562.562 0 00.424-.307
                  l2.067-4.193z" />
              </svg>
            <?php endfor; ?>
          </div>
          <input type="hidden" name="rate" id="rate" required>
        </div>

        <div>
          <label class="block text-sm font-medium">Komentar</label>
          <textarea name="comment" required class="w-full border rounded p-2" rows="3"></textarea>
        </div>
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
          Kirim Testimoni
        </button>
      </form>
    </section>

    <section
      id="cta"
      class="mt-8 flex justify-around items-end"
      style="background-image: url('image/CTA.png')">
      <div class="py-28 text-white">
        <h4 class="text-2xl font-extrabold">
          Percayakan Kebutuhan Anda pada Kami!
        </h4>
        <p>Hubungi Kami untuk Penawaran Spesial</p>
        <button
          class="mt-8 py-2 px-4 rounded-md bg-white text-black flex gap-2 font-bold">
          <a href="https://wa.me/6285156739512" class="flex">
            <img src="icon/wa.png" />
            <p>Klik Disini!</p>
          </a>
        </button>
      </div>
      <img src="image/Vector.png" class="w-[322px] h-[322px]" alt="" />
    </section>
  </main>

  <footer class="py-4 flex border-t-solid">
    <div class="flex align mx-auto text-center">
      <img
        src="icon/zoi.svg"
        alt=""
        class="align-center items-center text-center" />
    </div>
  </footer>
  <script>
    const stars = document.querySelectorAll("#star-rating svg");
    const rateInput = document.getElementById("rate");

    stars.forEach((star, idx) => {
      star.addEventListener("click", () => {
        const rating = parseInt(star.dataset.value);
        rateInput.value = rating;

        stars.forEach((s, i) => {
          if (i < rating) {
            s.setAttribute("fill", "gold");
          } else {
            s.setAttribute("fill", "none");
          }
        });
      });
    });


    document.addEventListener("DOMContentLoaded", function() {
      new Swiper(".mySwiper", {
        slidesPerView: 5,
        spaceBetween: 8,
        loop: true,
        autoplay: {
          delay: 2000,
          disableOnInteraction: true,
        },
      });
    });

    document.addEventListener("DOMContentLoaded", function() {
      new Swiper(".ulasanSwiper", {
        slidesPerView: 4,
        spaceBetween: 8,
        loop: true,
        autoplay: {
          delay: 2000,
          disableOnInteraction: true,
        },
      });
    });
  </script>
</body>

</html>