@extends('user.layouts.app')

@section('title', 'Ofisly')

@section('content')
<main class="main">

    <!-- Hero Section -->
    <section id="hero" class="hero section">

        <div class="container">
            <div class="row gy-4">
                <div class="col-lg-6 order-2 order-lg-1 d-flex flex-column justify-content-center"
                    data-aos="fade-up">
                    <h1>Making Work Easier</h1>
                    <p>Kami menyediakan solusi pintar untuk mempermudah pekerjaan Anda</p>
                    <div class="d-flex">
                        <a href="#about" class="btn-get-started">Get Started</a>
                    </div>
                </div>
                <div class="col-lg-6 order-1 order-lg-2 hero-img" data-aos="zoom-out" data-aos-delay="100">
                    <img src="assets/img/hero-img.png" class="img-fluid animated" alt="">
                </div>
            </div>
        </div>

    </section><!-- /Hero Section -->

    <!-- About Section -->
    <section id="about" class="about section">

        <!-- Section Title -->
        <div class="container section-title" data-aos="fade-up">
            <span>About Us<br></span>
            <h2>About</h2>
            <p>Ofisly adalah web tools pintar yang membantu Anda bekerja lebih cepat, efisien, dan tanpa ribet.
                Didesain dengan antarmuka yang sederhana namun kaya fitur, Ofisly memudahkan Anda mengatur tugas,
                berkolaborasi dengan tim, dan mengotomatisasi pekerjaan sehingga waktu dan energi Anda bisa
                digunakan
                untuk hal yang lebih penting.</p>
        </div><!-- End Section Title -->

        <div class="container">

            <div class="row gy-4">
                <div class="col-lg-6 position-relative align-self-start" data-aos="fade-up" data-aos-delay="100">
                    <img src="assets/img/about.png" class="img-fluid" alt="">
                </div>
                <div class="col-lg-6 content" data-aos="fade-up" data-aos-delay="200">
                    <h3>Mengapa Memilih Ofisly?</h3>
                    <p class="fst-italic">
                        Berikut keunggulan web tools Ofisly dibanding yang lain.
                    </p>
                    <ul>
                        <li><i class="bi bi-check2-all"></i> <span>Mempercepat proses kerja dengan fitur yang
                                mudah digunakan.</span></li>
                        <li><i class="bi bi-check2-all"></i> <span>Mengurangi pekerjaan manual melalui
                                otomatisasi proses.</span></li>
                        <li><i class="bi bi-check2-all"></i> <span>Memberikan tampilan yang sederhana namun
                                fungsional untuk semua
                                kebutuhan kerja Anda.</span></li>
                    </ul>
                    <p>
                        Dengan Ofisly, pekerjaan harian menjadi lebih ringan, tim lebih terhubung, dan tujuan Anda
                        tercapai lebih cepat.
                    </p>
                </div>
            </div>

        </div>

    </section><!-- /About Section -->

    <!-- Stats Section -->
    <section id="stats" class="stats section">

        <div class="container" data-aos="fade-up" data-aos-delay="100">

            <div class="row gy-4">

                <div class="col-lg-3 col-md-6">
                    <div class="stats-item text-center w-100 h-100">
                        <span data-purecounter-start="0" data-purecounter-end="3000" data-purecounter-duration="1"
                            class="purecounter"></span>
                        <p>Clients</p>
                    </div>
                </div><!-- End Stats Item -->

                <div class="col-lg-3 col-md-6">
                    <div class="stats-item text-center w-100 h-100">
                        <span data-purecounter-start="0" data-purecounter-end="210" data-purecounter-duration="1"
                            class="purecounter"></span>
                        <p>Projects</p>
                    </div>
                </div><!-- End Stats Item -->

                <div class="col-lg-3 col-md-6">
                    <div class="stats-item text-center w-100 h-100">
                        <span data-purecounter-start="0" data-purecounter-end="12" data-purecounter-duration="1"
                            class="purecounter"></span>
                        <p>Hours Of Support</p>
                    </div>
                </div><!-- End Stats Item -->

                <div class="col-lg-3 col-md-6">
                    <div class="stats-item text-center w-100 h-100">
                        <span data-purecounter-start="0" data-purecounter-end="11" data-purecounter-duration="1"
                            class="purecounter"></span>
                        <p>Workers</p>
                    </div>
                </div><!-- End Stats Item -->

            </div>

        </div>

    </section><!-- /Stats Section -->

    <!-- Services Section -->
    <section id="services" class="services section light-background">

        <!-- Section Title -->
        <div class="container section-title" data-aos="fade-up">
            <span>Services</span>
            <h2>Services</h2>
            <p>Layanan yang Membuat Kerja Lebih Mudah</p>
        </div><!-- End Section Title -->

        <div class="container">
            <div class="row gy-4">
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="service-item position-relative">
                        <div class="icon">
                            <i class="bi bi-bar-chart-line"></i>
                        </div>
                        <a href="" class="">
                            <h3>Dashboard Interaktif</h3>
                        </a>
                        <p>Pantau data dan aktivitas secara real-time dengan
                            tampilan yang informatif dan mudah dipahami.</p>
                    </div>
                </div><!-- End Service Item -->

                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
                    <div class="service-item position-relative">
                        <div class="icon">
                            <i class="bi bi-envelope-paper"></i>
                        </div>
                        <a href="" class="">
                            <h3>Surat Tugas</h3>
                        </a>
                        <p>Buat, kelola, dan arsipkan surat tugas dengan cepat dan terstruktur,
                            tanpa proses manual yang rumit.</p>
                    </div>
                </div><!-- End Service Item -->

                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
                    <div class="service-item position-relative">
                        <div class="icon">
                            <i class="bi bi-calendar2-week"></i>
                        </div>
                        <a href="" class="">
                            <h3>Cuti Karyawan</h3>
                        </a>
                        <p>Ajukan dan kelola cuti karyawan secara online, lengkap dengan
                            status persetujuan yang transparan.</p>
                    </div>
                </div><!-- End Service Item -->
            </div>
        </div>
    </section><!-- /Services Section -->

    <!-- Testimonials Section -->
    <section id="testimonials" class="testimonials section light-background">

        <!-- Section Title -->
        <div class="container section-title" data-aos="fade-up">
            <span>Testimonials</span>
            <h2>Testimonials</h2>
            <p>Pendapat atau pengalaman pengguna yang sudah mencoba produk layanan</p>
        </div><!-- End Section Title -->

        <div class="container" data-aos="fade-up" data-aos-delay="100">

            <div class="swiper init-swiper" data-speed="600" data-delay="5000"
                data-breakpoints="{ &quot;320&quot;: { &quot;slidesPerView&quot;: 1, &quot;spaceBetween&quot;: 40 }, &quot;1200&quot;: { &quot;slidesPerView&quot;: 3, &quot;spaceBetween&quot;: 40 } }">
                <script type="application/json" class="swiper-config">
        {
            "loop": true,
            "speed": 600,
            "autoplay": {
            "delay": 5000
            },
            "slidesPerView": "auto",
            "pagination": {
            "el": ".swiper-pagination",
            "type": "bullets",
            "clickable": true
            },
            "breakpoints": {
            "320": {
                "slidesPerView": 1,
                "spaceBetween": 40
            },
            "1200": {
                "slidesPerView": 3,
                "spaceBetween": 20
            }
            }
        }
        </script>
                <div class="swiper-wrapper">

                    <div class="swiper-slide">
                        <div class="testimonial-item">
                            <p>
                                <i class="bi bi-quote quote-icon-left"></i>
                                <span style="display:block; text-align:justify;">
                                    Sejak menggunakan Ofisly, pekerjaan administrasi di tim kami menjadi jauh lebih
                                    efisien.
                                    Pembuatan surat tugas dan pengaturan cuti karyawan bisa selesai hanya dalam
                                    beberapa klik,
                                    sehingga kami tidak perlu lagi repot dengan proses manual yang memakan waktu.
                                </span>
                                <i class="bi bi-quote quote-icon-right"></i>
                            </p>
                            <img src="assets/img/testimonials/testimonials-1.jpg" class="testimonial-img"
                                alt="">
                            <h3>Saul Goodman</h3>
                            <h4>Ceo &amp; Founder</h4>
                        </div>
                    </div><!-- End testimonial item -->

                    <div class="swiper-slide">
                        <div class="testimonial-item">
                            <p>
                                <i class="bi bi-quote quote-icon-left"></i>
                                <span style="display:block; text-align:justify;">
                                    Fitur dashboard interaktifnya sangat membantu saya memantau progres proyek.
                                    Semua data tersaji dengan jelas dan rapi, sehingga saya dapat mengambil
                                    keputusan
                                    penting secara cepat dan tepat untuk kemajuan tim.
                                </span>
                                <i class="bi bi-quote quote-icon-right"></i>
                            </p>
                            <img src="assets/img/testimonials/testimonials-2.jpg" class="testimonial-img"
                                alt="">
                            <h3>Sara Wilsson</h3>
                            <h4>Designer</h4>
                        </div>
                    </div><!-- End testimonial item -->

                    <div class="swiper-slide">
                        <div class="testimonial-item">
                            <p>
                                <i class="bi bi-quote quote-icon-left"></i>
                                <span style="display:block; text-align:justify;">
                                    Saya menyukai kesederhanaan tampilan Ofisly, namun di balik itu fiturnya sangat
                                    lengkap.
                                    Dari otomatisasi proses kerja hingga pengelolaan tugas harian, semuanya membuat
                                    saya
                                    bisa fokus pada hal-hal penting yang membawa hasil nyata.
                                </span>
                                <i class="bi bi-quote quote-icon-right"></i>
                            </p>
                            <img src="assets/img/testimonials/testimonials-3.jpg" class="testimonial-img"
                                alt="">
                            <h3>Jena Karlis</h3>
                            <h4>Store Owner</h4>
                        </div>
                    </div><!-- End testimonial item -->

                    <div class="swiper-slide">
                        <div class="testimonial-item">
                            <p>
                                <i class="bi bi-quote quote-icon-left"></i>
                                <span style="display:block; text-align:justify;">
                                    Kolaborasi tim menjadi jauh lebih terstruktur sejak memakai Ofisly.
                                    Kami dapat membagi tugas, memantau perkembangan, dan berkomunikasi
                                    dalam satu platform yang rapi serta mudah diakses kapan saja.
                                </span>
                                <i class="bi bi-quote quote-icon-right"></i>
                            </p>
                            <img src="assets/img/testimonials/testimonials-4.jpg" class="testimonial-img"
                                alt="">
                            <h3>Matt Brandon</h3>
                            <h4>Freelancer</h4>
                        </div>
                    </div><!-- End testimonial item -->

                    <div class="swiper-slide">
                        <div class="testimonial-item">
                            <p>
                                <i class="bi bi-quote quote-icon-left"></i>
                                <span style="display:block; text-align:justify;">
                                    Ofisly membantu kami menghemat banyak waktu dalam pengelolaan pekerjaan.
                                    Proses pengajuan dan persetujuan cuti menjadi transparan,
                                    dan semua dokumen penting bisa diakses kapan saja tanpa hambatan.
                                </span>
                                <i class="bi bi-quote quote-icon-right"></i>
                            </p>
                            <img src="assets/img/testimonials/testimonials-5.jpg" class="testimonial-img"
                                alt="">
                            <h3>John Larson</h3>
                            <h4>Entrepreneur</h4>
                        </div>
                    </div><!-- End testimonial item -->

                </div>
                <div class="swiper-pagination"></div>
            </div>

        </div>

    </section><!-- /Testimonials Section -->

    <!-- Call To Action Section -->
    <section id="call-to-action" class="call-to-action section accent-background">

        <div class="container">
            <div class="row justify-content-center" data-aos="zoom-in" data-aos-delay="100">
                <div class="col-xl-10">
                    <div class="text-center">
                        <h3>Siap bekerja lebih cepat?</h3>
                        <p>Coba Ofisly sekarang dan rasakan kemudahan mengatur pekerjaan Anda dalam satu platform.
                        </p>
                        <a class="cta-btn" href="#">Call To Action</a>
                    </div>
                </div>
            </div>
        </div>

    </section><!-- /Call To Action Section -->

        <!-- Lowongan Section -->
    <section id="lowongan" class="lowongan section">
        <div class="container section-title" data-aos="fade-up">
            <span>Lowongan Pekerjaan<br></span>
            <h2>Info Lowongan</h2>
            <p>
                Temukan berbagai lowongan pekerjaan yang tersedia untuk mendukung karir Anda.
                Dapatkan kesempatan terbaik sesuai minat dan kemampuan Anda bersama kami.
            </p>
        </div>

        <div class="container">
            <div class="row gy-4">
                <div class="col-lg-6 position-relative align-self-start" data-aos="fade-up" data-aos-delay="100">
                    <img src="{{ asset('assets/img/loker.jpg') }}" class="img-fluid" alt="">
                </div>
                <div class="col-lg-6 content" data-aos="fade-up" data-aos-delay="200">
                    <h3>Kesempatan Berkarir</h3>
                    <p class="fst-italic">
                        Kami membuka peluang kerja untuk Anda yang ingin berkembang bersama kami.
                    </p>
                    <ul>
                        <li><i class="bi bi-check2-all"></i> <span>Berbagai posisi sesuai bidang keahlian.</span>
                        </li>
                        <li><i class="bi bi-check2-all"></i> <span>Lingkungan kerja profesional dan
                                inovatif.</span></li>
                        <li><i class="bi bi-check2-all"></i> <span>Kesempatan untuk mengembangkan skill dan
                                pengalaman.</span></li>
                    </ul>
                    <p>
                        Jangan lewatkan kesempatan ini, daftar sekarang untuk melihat lowongan pekerjaan yang
                        tersedia.
                    </p>
                    <a href="{{ route('daftar-lowongan.index') }}" class="btn btn-primary mt-3">
                        Selengkapnya
                    </a>
                </div>
            </div>
        </div>
    </section>
    <!-- Lowongan Section -->

    <!-- Contact Section -->
    <section id="contact" class="contact section">

        <!-- Section Title -->
        <div class="container section-title" data-aos="fade-up">
            <span>Contact</span>
            <h2>Contact</h2>
            <p>Hubungi kami jika ada pertanyaan lebih lanjut</p>
        </div><!-- End Section Title -->

        <div class="container" data-aos="fade-up" data-aos-delay="100">

            <div class="row gy-4">

                <div class="col-lg-5">

                    <div class="info-wrap">
                        <div class="info-item d-flex" data-aos="fade-up" data-aos-delay="200">
                            <i class="bi bi-geo-alt flex-shrink-0"></i>
                            <div>
                                <h3>Address</h3>
                                <p>Gedung Permata Indonesia, Jl. Kayoon No.26F,
                                    Embong Kaliasin, Kec. Genteng, Surabaya, Jawa Timur 60271</p>
                            </div>
                        </div><!-- End Info Item -->

                        <div class="info-item d-flex" data-aos="fade-up" data-aos-delay="300">
                            <i class="bi bi-telephone flex-shrink-0"></i>
                            <div>
                                <h3>Call Us</h3>
                                <p> 031 5467541</p>
                            </div>
                        </div><!-- End Info Item -->

                        <div class="info-item d-flex" data-aos="fade-up" data-aos-delay="400">
                            <i class="bi bi-envelope flex-shrink-0"></i>
                            <div>
                                <h3>Email Us</h3>
                                <p> info@permataindonesia.com</p>
                            </div>
                        </div><!-- End Info Item -->

                        <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3957.886075908615!2d112.7483663759458!3d-7.253634992753386!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dd7f95859187a7d%3A0xe2122b0f45209703!2sPermata%20Indonesia!5e0!3m2!1sen!2sid!4v1723469145929!5m2!1sen!2sid" 
                        width="100%" height="270" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                    </div>
                </div>

                <div class="col-lg-7">
                    <form action="forms/contact.php" method="post" class="php-email-form" data-aos="fade-up"
                        data-aos-delay="200">
                        <div class="row gy-4">

                            <div class="col-md-6">
                                <label for="name-field" class="pb-2">Your Name</label>
                                <input type="text" name="name" id="name-field" class="form-control"
                                    required="">
                            </div>

                            <div class="col-md-6">
                                <label for="email-field" class="pb-2">Your Email</label>
                                <input type="email" class="form-control" name="email" id="email-field"
                                    required="">
                            </div>

                            <div class="col-md-12">
                                <label for="subject-field" class="pb-2">Subject</label>
                                <input type="text" class="form-control" name="subject" id="subject-field"
                                    required="">
                            </div>

                            <div class="col-md-12">
                                <label for="message-field" class="pb-2">Message</label>
                                <textarea class="form-control" name="message" rows="10" id="message-field" required=""></textarea>
                            </div>

                            <div class="col-md-12 text-center">
                                <div class="loading">Loading</div>
                                <div class="error-message"></div>
                                <div class="sent-message">Your message has been sent. Thank you!</div>

                                <button type="submit">Send Message</button>
                            </div>

                        </div>
                    </form>
                </div><!-- End Contact Form -->

            </div>

        </div>

    </section><!-- /Contact Section -->

</main>
@endsection