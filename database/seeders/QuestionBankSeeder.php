<?php

namespace Database\Seeders;

use App\Models\Choice;
use App\Models\QuestionBank;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class QuestionBankSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $questions = [
            ['Kamu adalah tipikal orang yang?', 'Spontan, Fleksibel, tidak diikat waktu', 'Terencana dan memiliki deadline jelas', 'P', 'J'],
            ['Kamu adalah tipikal orang yang?', 'Lebih memilih berkomunikasi dengan menulis', 'Lebih memilih berkomunikasi dengan bicara', 'I', 'E'],
            ['Kamu adalah tipikal orang yang?', 'Tidak menyukai hal-hal yang bersifat mendadak dan di luar perencanaan', 'Perubahan mendadak tidak jadi masalah', 'J', 'P'],
            ['Kamu adalah tipikal orang yang?', 'Obyektif', 'Subyektif', 'T', 'F'],
            ['Kamu adalah tipikal orang yang?', 'Menemukan dan mengembangkan ide dengan mendiskusikannya', 'Menemukan dan mengembangkan ide dengan merenungkan', 'E', 'I'],
            ['Kamu adalah tipikal orang yang?', 'Bergerak dari gambaran umum baru ke detail', 'Bergerak dari detail ke gambaran umum sebagai kesimpulan akhir', 'N', 'S'],
            ['Kamu adalah tipikal orang yang?', 'Berorientasi pada dunia eksternal (kegiatan, orang)', 'Berorientasi pada dunia internal (memori, pemikiran, ide)', 'E', 'I'],
            ['Kamu adalah tipikal orang yang?', 'Berbicara mengenai masalah yang dihadapi hari ini dan langkah-langkah praktis mengatasinya', 'Berbicara mengenai visi masa depan dan konsep-konsep mengenai visi tersebut', 'S', 'N'],
            ['Kamu adalah tipikal orang yang?', 'Diyakinkan dengan penjelasan yang menyentuh perasaan', 'Diyakinkan dengan penjelasan yang masuk akal', 'F', 'T'],
            ['Kamu adalah tipikal orang yang?', 'Fokus pada sedikit hobi namun mendalam', 'Fokus pada banyak hobi secara luas dan umum', 'I', 'E'],
            ['Kamu adalah tipikal orang yang?', 'Tertutup dan mandiri', 'Sosial dan ekspresif', 'I', 'E'],
            ['Kamu adalah tipikal orang yang?', 'Aturan, jadwal dan target sangat mengikat dan membebani', 'Aturan, jadwal dan target akan sangat membantu dan memperjelas tindakan', 'P', 'J'],
            ['Kamu adalah tipikal orang yang?', 'Menggunakan pengalaman sebagai pedoman', 'Menggunakan imajinasi dan perenungan sebagai pedoman', 'S', 'N'],
            ['Kamu adalah tipikal orang yang?', 'Berorientasi tugas dan job description', 'Berorientasi pada manusia dan hubungan', 'T', 'F'],
            ['Kamu adalah tipikal orang yang?', 'Pertemuan dengan orang lain dan aktivitas sosial melelahkan', 'Bertemu orang dan aktivitas sosial membuat bersemangat', 'I', 'E'],
            ['Kamu adalah tipikal orang yang?', 'SOP sangat membantu', 'SOP sangat membosankan', 'S', 'N'],
            ['Kamu adalah tipikal orang yang?', 'Mengambil keputusan berdasar logika dan aturan main', 'Mengambil keputusan berdasar perasaan pribadi dan kondisi orang lain', 'T', 'F'],
            ['Kamu adalah tipikal orang yang?', 'Bebas dan dinamis', 'Prosedural dan tradisional', 'N', 'S'],
            ['Kamu adalah tipikal orang yang?', 'Berorientasi pada hasil', 'Berorientasi pada proses', 'J', 'P'],
            ['Kamu adalah tipikal orang yang?', 'Beraktifitas sendirian di rumah menyenangkan', 'Beraktifitas sendirian di rumah membosankan', 'I', 'E'],
            ['Kamu adalah tipikal orang yang?', 'Membiarkan orang lain bertindak bebas asalkan tujuan tercapai', 'Mengatur orang lain dengan tata tertib agar tujuan tercapai', 'P', 'J'],
            ['Kamu adalah tipikal orang yang?', 'Memilih ide inspiratif lebih penting daripada fakta', 'Memilih fakta lebih penting daripada ide inspiratif', 'N', 'S'],
            ['Kamu adalah tipikal orang yang?', 'Mengemukakan tujuan dan sasaran lebih dahulu', 'Mengemukakan kesepakatan terlebih dahulu', 'T', 'F'],
            ['Kamu adalah tipikal orang yang?', 'Fokus pada target dan mengabaikan hal-hal baru', 'Memperhatikan hal-hal baru dan siap menyesuaikan diri serta mengubah target', 'J', 'P'],
            ['Kamu adalah tipikal orang yang?', 'Kontinuitas dan stabilitas lebih diutamakan', 'Perubahan dan variasi lebih diutamakan', 'S', 'N'],
            ['Kamu adalah tipikal orang yang?', 'Pendirian masih bisa berubah tergantung situasi nantinya', 'Berpegang teguh pada pendirian', 'P', 'J'],
            ['Kamu adalah tipikal orang yang?', 'Bertindak step by step dengan timeframe yang jelas', 'Bertindak dengan semangat tanpa menggunakan timeframe', 'S', 'N'],
            ['Kamu adalah tipikal orang yang?', 'Berinisiatif tinggi hampir dalam berbagai hal meskipun tidak berhubungan dengan dirinya', 'Berinisiatif bila situasi memaksa atau berhubungan dengan kepentingan sendiri', 'E', 'I'],
            ['Kamu adalah tipikal orang yang?', 'Lebih memilih tempat yang tenang dan pribadi untuk berkonsentrasi', 'Lebih memilih tempat yang ramai dan banyak interaksi / aktifitas', 'I', 'E'],
            ['Kamu adalah tipikal orang yang?', 'Menganalisa', 'Berempati', 'T', 'F'],
            ['Kamu adalah tipikal orang yang?', 'Berpikir secara matang sebelum bertindak', 'Berani bertindak tanpa terlalu lama berfikir', 'I', 'E'],
            ['Kamu adalah tipikal orang yang?', 'Menghargai seseorang karena sifat dan perilakunya', 'Menghargai seseorang karena skill dan faktor teknis', 'F', 'T'],
            ['Kamu adalah tipikal orang yang?', 'Merasa nyaman bila situasi tetap terbuka terhadap pilihan-pilihan lain', 'Merasa tenang bila semua sudah diputuskan', 'P', 'J'],
            ['Kamu adalah tipikal orang yang?', 'Menarik kesimpulan dengan lama dan hati-hati', 'menarik kesimpulan dengan cepat sesuai naluri', 'S', 'N'],
            ['Kamu adalah tipikal orang yang?', 'Mengekspresikan semangat', 'Menyimpan semangat dalam hati', 'E', 'I'],
            ['Kamu adalah tipikal orang yang?', 'Mengklarifikasi ide dan teori sebelum dipraktekkan', 'Memahami ide dan teori saat mempraktekkannya langsung', 'S', 'N'],
            ['Kamu adalah tipikal orang yang?', 'Melibatkan perasaan itu tidak profesional', 'Terlalu kaku pada peraturan dan pekerjaan itu kejam', 'T', 'F'],
            ['Kamu adalah tipikal orang yang?', 'Mencari kesempatan untuk berkomunikasi secara perorangan', 'Memilih berkomunikasi pada sekelompok orang', 'I', 'E'],
            ['Kamu adalah tipikal orang yang?', 'Yang penting situasi harmonis terjaga', 'Yang penting tujuan tercapai', 'F', 'T'],
            ['Kamu adalah tipikal orang yang?', 'Ketidakpastian itu seru, menegangkan dan membuat hati lebih senang', 'Ketidakpastian membuat bingung dan meresahkan', 'P', 'J'],
            ['Kamu adalah tipikal orang yang?', 'Berfokus pada masa kini (apa yang bisa diperbaiki sekarang)', 'Berfokus pada masa depan (apa yang mungkin dicapai di masa depan)', 'S', 'N'],
            ['Kamu adalah tipikal orang yang?', 'Mempertanyakan', 'Mengakomodasi', 'T', 'F'],
            ['Kamu adalah tipikal orang yang?', 'Secara konsisten mengamati dan mengingat detail', 'Mengamati dan mengingat detail hanya bila berhubungan dengan pola', 'S', 'N'],
            ['Kamu adalah tipikal orang yang?', 'Situasi last minute membuat bersemangat dan memunculkan potensi', 'Situasi last minute sangat menyiksa, membuat stress dan merupakan kesalahan', 'P', 'J'],
            ['Kamu adalah tipikal orang yang?', 'Lebih suka komunikasi tidak langsung (telp, surat, e-mail)', 'Lebih suka komunikasi langsung (tatap muka)', 'I', 'E'],
            ['Kamu adalah tipikal orang yang?', 'Praktis', 'Konseptual', 'S', 'N'],
            ['Kamu adalah tipikal orang yang?', 'Perubahan adalah musuh', 'Perubahan adalah semangat hidup', 'J', 'P'],
            ['Kamu adalah tipikal orang yang?', 'Sering dianggap keras kepala', 'Sering dianggap terlalu memihak', 'T', 'F'],
            ['Kamu adalah tipikal orang yang?', 'Bersemangat saat menolong orang keluar dari kesalahan dan meluruskan', 'Bersemangat saat mengkritik dan menemukan kesalahan', 'T', 'F'],
            ['Kamu adalah tipikal orang yang?', 'Bertindak sesuai situasi dan kondisi yang terjadi saat itu', 'Bertindak sesuai apa yang sudah direncanakan', 'P', 'J'],
            ['Kamu adalah tipikal orang yang?', 'Menggunakan keterampilan yang sudah dikuasai', 'Menyukai tantangan untuk menguasai keterampilan baru', 'S', 'N'],
            ['Kamu adalah tipikal orang yang?', 'Membangun ide pada saat berbicara', 'Membangun ide dengan matang baru membicarakannya', 'E', 'I'],
            ['Kamu adalah tipikal orang yang?', 'Memilih cara yang sudah ada dan sudah terbukti', 'Memilih cara yang unik dan belum dipraktekkan orang lain', 'S', 'N'],
            ['Kamu adalah tipikal orang yang?', 'Hidup harus sudah diatur dari awal', 'Hidup seharusnya mengalir sesuai kondisi', 'J', 'P'],
            ['Kamu adalah tipikal orang yang?', 'Standar harus ditegakkan di atas segalanya (itu menunjukkan kehormatan dan harga diri)', 'Perasaan manusia lebih penting dari sekadar standar (yang adalah benda mati)', 'T', 'F'],
            ['Kamu adalah tipikal orang yang?', 'Daftar dan checklist adalah panduan penting', 'Daftar dan checklist adalah tugas dan beban', 'J', 'P'],
            ['Kamu adalah tipikal orang yang?', 'Menuntut perlakuan yang adil dan sama pada semua orang', 'Menuntut perlakuan khusus sesuai karakteristik masing-masing orang', 'T', 'F'],
            ['Kamu adalah tipikal orang yang?', 'Mementingkan sebab-akibat', 'Mementingkan nilai-nilai personal', 'T', 'F'],
            ['Kamu adalah tipikal orang yang?', 'Puas ketika mampu beradaptasi dengan momentum yang terjadi', 'Puas ketika mampu menjalankan semuanya sesuai rencana', 'P', 'J'],
            ['Kamu adalah tipikal orang yang?', 'Spontan, Easy Going, fleksibel', 'Berhati-hati, penuh pertimbangan, kaku', 'E', 'I'],
        ];

        foreach ($questions as $question) {
            QuestionBank::create([
                'question_text' => $question[0],
                'choice_a' => $question[1],
                'choice_b' => $question[2],
                'type_a' => $question[3],
                'type_b' => $question[4],
            ]);
            
        }

        // create 50 questions
        // for ($i = 0; $i < 100; $i++) {
        //     $question = QuestionBank::create([
        //         'question_text' => 'Question ' . ($i + 1)
        //     ]);

        //     // create 2 choices for each question
        //     $question->choices()->createMany([
        //         [
        //             'choice_text' => 'Choice 1 - ' . ($i + 1),
        //         ],
        //         [
        //             'choice_text' => 'Choice 2 - ' . ($i + 1),
        //         ],
        //     ]);        
        // }
    }
}
