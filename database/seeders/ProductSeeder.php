<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $products = [
            [
                'name' => 'Indomie Goreng',
                'stock' => 10,
                'price' => 3100,
                'description' => 'Indomie Goreng adalah mie instan rasa goreng yang terbuat dari bahan-bahan berkualitas. Indomie Goreng memiliki rasa yang khas pedas dan gurih. Indomie Goreng dapat dinikmati oleh semua kalangan usia.',
                'img' => '0lxKQl2mvqEh70XLvgIA.jpg',
            ],
            [
                'name' => 'Indomie Goreng Jumbo',
                'stock' => 15,
                'price' => 4300,
                'description' => 'Indomie Goreng Jumbo adalah varian mie instan yang lebih besar dari ukuran standar, memberikan pengalaman makan yang lebih puas dan kenikmatan yang berlipat ganda. ',
                'img' => 'Iyo3eLTieosZSvRJTGQl.jpg',
            ],
            [
                'name' => 'Indomie Goreng Jumbo Rasa Ayam Panggang',
                'stock' => 5,
                'price' => 4200,
                'description' => 'Dapatkan pengalaman makan yang memuaskan dan puas dengan Indomie Goreng Jumbo Rasa Ayam Panggang, pilihan sempurna untuk Anda yang ingin menikmati hidangan mie instan dengan cita rasa yang lezat dan mengenyangkan.',
                'img' => 'AgjiGLoxvt5GDpk7bh8B.jpg',
            ],
            [
                'name' => 'Indomie Kuah Rasa Kaldu Ayam',
                'stock' => 20,
                'price' => 3000,
                'description' => 'Rasakan kelezatan lezat dalam setiap sendokan dengan Indomie Kuah Rasa Kaldu Ayam, sajian mie instan yang menggugah selera! Dengan kuah kaldu ayam yang kaya dan bumbu rahasia kami, setiap tegukan akan memanjakan lidah Anda dengan cita rasa yang autentik dan menghangatkan hati.',
                'img' => 'HJpiy51TDS6Be8q2v8LE.jpg',
            ],
            [
                'name' => 'Indomie Kuah Rasa Ayam Bawang',
                'stock' => 23,
                'price' => 3000,
                'description' => 'Rasakan harmoni cita rasa ayam yang gurih dan aroma bawang yang menggoda dengan Indomie Kuah Rasa Ayam Bawang, pilihan sempurna untuk memanjakan lidah Anda!',
                'img' => 'zw34JULbUEUB3us1VzcW.jpg',
            ],
            [
                'name' => 'Indomie Goreng Premium Japanese Takoyaki',
                'stock' => 30,
                'price' => 5900,
                'description' => 'Jelajahi kelezatan baru dengan Indomie Goreng Premium Japanese Takoyaki, hidangan mie instan yang menghadirkan sensasi makan yang luar biasa! Rasakan perpaduan sempurna antara mie kuning yang kenyal dan bumbu premium takoyaki yang kaya akan rasa dan aroma.',
                'img' => 'uZqpGDT91YEbzLHnzd7o.jpg',
            ],
            [
                'name' => 'Indomie Goreng Premium Japanese Shoyu',
                'stock' => 40,
                'price' => 5900,
                'description' => 'Rasakan keanggunan cita rasa Jepang dengan Indomie Goreng Premium Japanese Shoyu, hidangan mie instan yang membawa Anda dalam petualangan kuliner yang otentik! Dibuat dengan mie kuning berkualitas tinggi dan bumbu khas shoyu Jepang yang lezat, setiap suapan akan memanjakan lidah Anda dengan keharmonisan rasa gurih dan sedikit manis.',
                'img' => 'GSuo1GLnektTGtpSPmDL.jpg',
            ],
            [
                'name' => 'Indomie Goreng Premium Japanese Miso',
                'stock' => 20,
                'price' => 5900,
                'description' => 'Selamat datang di dunia cita rasa Jepang yang luar biasa dengan Indomie Goreng Premium Japanese Miso, hidangan mie instan yang menghadirkan kelezatan yang autentik dan memikat! Dibuat dengan mie kuning yang kenyal dan bumbu premium miso Jepang yang kaya akan rasa, setiap suapan akan memanjakan lidah Anda dengan kombinasi unik dari gurih, manis, dan sedikit asin.',
                'img' => '4Du4uPsa6q5J8xfpLkna.jpg',
            ]
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}

/*
'img' => '0lxKQl2mvqEh70XLvgIA.jpg',
'img' => 'Iyo3eLTieosZSvRJTGQl.jpg',
'img' => 'AgjiGLoxvt5GDpk7bh8B.jpg',
'img' => 'HJpiy51TDS6Be8q2v8LE.jpg',
'img' => 'zw34JULbUEUB3us1VzcW.jpg',
'img' => 'uZqpGDT91YEbzLHnzd7o.jpg',
'img' => 'GSuo1GLnektTGtpSPmDL.jpg',
'img' => '4Du4uPsa6q5J8xfpLkna.jpg',
 */
