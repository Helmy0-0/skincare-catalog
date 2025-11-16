@extends('customer.layouts.app')

@section('title', 'About MirGlow')

@section('content')
<div class="container mx-auto py-10 px-6">
    <h1 class="text-4xl font-bold mb-6 text-blue-700">Tentang MirGlow</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">

        {{-- Gambar Brand --}}
        <div>
            <img src="https://img.ws.mms.shopee.co.id/id-11134216-7r98p-ll6hy4pqp4ffc7"
                 alt="MirGlow Skincare"
                 class="rounded-lg shadow-lg w-full object-cover">
        </div>

        {{-- Sejarah Perusahaan --}}
        <div>
            <h2 class="text-2xl font-semibold mb-4 text-blue-600">Sejarah MirGlow</h2>
            <p class="leading-relaxed text-gray-700 mb-4">
                MirGlow adalah brand skincare yang berdiri pada tahun <b>2024</b> dengan visi menyediakan
                produk perawatan kulit yang aman, terjangkau, dan cocok untuk kebutuhan kulit masyarakat
                Indonesia. Berawal dari riset sederhana mengenai bahan aktif seperti
                <i>Niacinamide, Hyaluronic Acid, Salicylic Acid</i>, hingga ekstrak alami,
                MirGlow kini berkembang menjadi salah satu katalog skincare terpercaya.
            </p>

            <p class="leading-relaxed text-gray-700 mb-4">
                Kami percaya bahwa setiap orang berhak memiliki kulit sehat dan bercahaya.
                Oleh karena itu, MirGlow berkomitmen menghadirkan produk dengan formulasi dermatologically tested,
                tanpa kandungan berbahaya, serta mengedepankan transparansi dalam setiap bahan yang digunakan.
            </p>

            <p class="leading-relaxed text-gray-700">
                Hingga hari ini, MirGlow terus berinovasi menghadirkan rangkaian produk berkualitas
                seperti <b>Brightening Series</b>, <b>Hydration Series</b>, hingga <b>Acne Care Series</b>.
                Kami berharap dapat menjadi bagian dari perjalanan skincare kamu setiap hari.
            </p>
        </div>
    </div>

    <div class="mt-10 text-center">
        <a href="/customer/home"
           class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg shadow">
            Kembali ke Beranda
        </a>
    </div>
</div>
@endsection
