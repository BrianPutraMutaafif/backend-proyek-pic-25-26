<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Penjual;
use App\Models\User;

class PenjualSeeder extends Seeder
{
    public function run(): void
    {
        $penjuals = [
            ['Kios Bu Tini', 'https://randomuser.me/api/portraits/women/12.jpg', 'Sayur mayur fresh, langsung dari kebon tetangga, bonus senyuman manis.', 'https://picsum.photos/800/600?1', 'Sayur, buah, lombok rawit', 'L001', 'Sebelah gerobak bakso', '0811-1111'],
            ['Warung Bejo', 'https://randomuser.me/api/portraits/men/13.jpg', 'Jual ayam potong, kalau lari dikejar Bejo langsung potong. Halal pokoknya!', 'https://picsum.photos/800/600?2', 'Ayam potong, telur, bumbu dapur', 'L002', 'Depan WC umum', '0811-2222'],
            ['Kios Mbake', 'https://randomuser.me/api/portraits/women/14.jpg', 'Jual jajanan jadul, yang bikin nostalgia masa SD dulu.', 'https://picsum.photos/800/600?3', 'Permen, ciki, es lilin', 'L003', 'Samping lapak kaset bajakan', '0811-3333'],
            ['Kios e Kang Udin', 'https://randomuser.me/api/portraits/men/15.jpg', 'Gas LPG isi penuh, beratnya sampe bikin kang Udin encok.', 'https://picsum.photos/800/600?4', 'Gas LPG, minyak tanah', 'L004', 'Belakang parkiran motor', '0811-4444'],
            ['Kios Siji', 'https://randomuser.me/api/portraits/women/16.jpg', 'Menjual bumbu dapur, resep rahasia masakan emak-emak.', 'https://picsum.photos/800/600?5', 'Bawang, cabai, rempah', 'L005', 'Dekat sumur umum', '0811-5555'],
            ['Kios Mas Darto', 'https://randomuser.me/api/portraits/men/17.jpg', 'Elektronik murah, asal bisa nyala pokoknya jadi.', 'https://picsum.photos/800/600?6', 'Radio, charger, lampu neon', 'L006', 'Depan pangkalan ojek', '0811-6666'],
            ['Kios Gelap', 'https://randomuser.me/api/portraits/dark/18.jpg', 'Re-sell darkweb', 'https://picsum.photos/800/600?7', 'Senjata api, boum, ...', 'L007', 'Samping tukang kunci', '0811-7777'],
            ['Kios Pak Karyo', 'https://randomuser.me/api/portraits/men/19.jpg', 'Baju serba murah, motif bunga tetep laku.', 'https://picsum.photos/800/600?8', 'Kaos, daster, celana kolor', 'L008', 'Belakang musholla', '0811-8888'],
            ['Kios Mas Gatot', 'https://randomuser.me/api/portraits/women/20.jpg', 'Kosmetik KW super, bikin glowing kayak artis sinetron.', 'https://picsum.photos/800/600?9', 'Bedak, lipstik, parfum refill', 'L009', 'Depan warung kopi', '0811-9999'],
            ['Kios Bang Joni', 'https://randomuser.me/api/portraits/men/21.jpg', 'Sembako lengkap, kalau ngutang catet di buku tiga rangkap.', 'https://picsum.photos/800/600?10', 'Beras, minyak goreng, gula', 'L010', 'Dekat pintu selatan', '0812-1010'],
            ['Kios Bu Ida', 'https://randomuser.me/api/portraits/women/22.jpg', 'Kue basah legit, bikin tetangga iri sama wanginya.', 'https://picsum.photos/800/600?11', 'Kue lapis, lemper, klepon', 'L011', 'Samping los ikan', '0812-1111'],
            ['Kios Mas Amba', 'https://randomuser.me/api/portraits/men/1.jpg', 'Menjual berbagai macam kebutuhan sehari-hari dengan harga terjangkau', 'https://picsum.photos/800/600?12', 'Sembako, makanan ringan, minuman, kebutuhan rumah tangga', 'L012', 'Dekat pintu masuk utara', '911'],
            ['Barber Mas Andre', 'https://randomuser.me/api/portraits/men/23.jpg', 'Sepatu ori KW, langkah pede harga merakyat.', 'https://picsum.photos/800/600?13', 'Sepatu, sandal, kaos kaki', 'L013', 'Depan toko emas', '0812-1313'],
            ['Kios Bu Lastri', 'https://randomuser.me/api/portraits/women/24.jpg', 'Tanaman hias, kata Bu Lastri bisa bikin rejeki ngalir.', 'https://picsum.photos/800/600?14', 'Monstera, aglonema, kaktus', 'L014', 'Belakang los daging', '0812-1414'],
            ['Kios Pak Slamet', 'https://randomuser.me/api/portraits/men/25.jpg', 'Obat kuat herbal, katanya bikin joss gandos.', 'https://picsum.photos/800/600?15', 'Jamu, minyak urut, herbal', 'L015', 'Dekat musholla', '0812-1515'],
            ['Kios Plastik', 'https://randomuser.me/api/portraits/women/26.jpg', 'Aksesoris unyu-unyu, biar anak gaul tetep kece.', 'https://picsum.photos/800/600?16', 'Cincin plastik, jepit rambut, gelang karet', 'L016', 'Depan pos ronda', '0812-1616'],
        ];

        foreach ($penjuals as $i => $data) {
            $user = User::where('username', "Seller".($i+1))->first();

            Penjual::create([
                'user_id' => $user->id,
                'nama' => $data[0],
                'foto_profil' => $data[1],
                'deskripsi' => $data[2],
                'foto_kios' => $data[3],
                'produk' => $data[4],
                'lokasi' => $data[5],
                'patokan' => $data[6],
                'kontak' => $data[7],
            ]);
        }
    }
}
