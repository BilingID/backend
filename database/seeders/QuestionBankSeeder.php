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
            [
                "pertanyaan" => "Apa yang lebih penting bagi Anda?",
                "pilihan" => ["Kreativitas", "Keteraturan"],
            ],
            [
                "pertanyaan" => "Saat menghadapi masalah, Anda cenderung...",
                "pilihan" => [
                    "Mencari solusi secara spontan",
                    "Mengatur langkah-langkah dengan hati-hati",
                ],
            ],
            [
                "pertanyaan" => "Ketika bekerja dalam tim, Anda lebih suka...",
                "pilihan" => [
                    "Menghasilkan ide-ide baru",
                    "Memastikan setiap detail terorganisir dengan baik",
                ],
            ],
            [
                "pertanyaan" => "Pilih hal yang lebih menggambarkan diri Anda:",
                "pilihan" => [
                    "Fleksibel dan mudah beradaptasi",
                    "Teratur dan terstruktur",
                ],
            ],
            [
                "pertanyaan" => "Apa yang Anda prioritaskan dalam pekerjaan?",
                "pilihan" => [
                    "Menemukan cara baru untuk menyelesaikan tugas",
                    "Mengikuti prosedur yang telah ada",
                ],
            ],
            [
                "pertanyaan" =>
                    "Dalam situasi sulit, keputusan Anda lebih dipengaruhi oleh...",
                "pilihan" => ["Intuisi", "Data dan fakta yang ada"],
            ],
            [
                "pertanyaan" => "Bagaimana cara Anda menyikapi perubahan?",
                "pilihan" => [
                    "Terbuka dan siap beradaptasi",
                    "Lebih suka kestabilan dan konsistensi",
                ],
            ],
            [
                "pertanyaan" =>
                    "Ketika bekerja dengan orang lain, Anda cenderung menjadi...",
                "pilihan" => ["Pemikir kreatif", "Pelaksana yang terorganisir"],
            ],
            [
                "pertanyaan" =>
                    "Apa yang lebih Anda tekankan saat menyelesaikan tugas?",
                "pilihan" => [
                    "Menghasilkan solusi inovatif",
                    "Mematuhi aturan yang ada",
                ],
            ],
            [
                "pertanyaan" => "Bagaimana Anda menghadapi tekanan?",
                "pilihan" => [
                    "Dengan mencari cara baru untuk menyelesaikan masalah",
                    "Dengan mengikuti langkah-langkah yang telah terbukti berhasil",
                ],
            ],
            [
                "pertanyaan" =>
                    "Ketika dihadapkan pada tugas yang rumit, Anda lebih suka...",
                "pilihan" => [
                    "Memecahnya menjadi bagian-bagian kecil",
                    "Mencoba mencari solusi secara keseluruhan",
                ],
            ],
            [
                "pertanyaan" =>
                    "Apa yang lebih Anda utamakan dalam membuat keputusan?",
                "pilihan" => [
                    "Nilai-nilai dan prinsip pribadi",
                    "Bukti-bukti dan data yang ada",
                ],
            ],
            [
                "pertanyaan" =>
                    "Dalam berkomunikasi dengan orang lain, Anda cenderung menjadi...",
                "pilihan" => [
                    "Orang yang suka berimajinasi",
                    "Orang yang konkret dan jelas",
                ],
            ],
            [
                "pertanyaan" =>
                    "Ketika mengerjakan proyek, Anda lebih fokus pada...",
                "pilihan" => [
                    "Menciptakan hal baru",
                    "Menyelesaikan proyek sesuai dengan rencana yang ada",
                ],
            ],
            [
                "pertanyaan" =>
                    "Pilih pendekatan yang lebih menggambarkan cara Anda bekerja:",
                "pilihan" => [
                    "Fleksibel dan terbuka terhadap perubahan",
                    "Terstruktur dan memiliki rencana yang jelas",
                ],
            ],
            [
                "pertanyaan" =>
                    "Apa yang lebih Anda cermati saat menyelesaikan tugas?",
                "pilihan" => [
                    "Kemungkinan-kemungkinan baru",
                    "Prosedur yang sudah teruji",
                ],
            ],
            [
                "pertanyaan" => "Dalam memecahkan masalah, Anda lebih suka...",
                "pilihan" => [
                    "Mengandalkan intuisi dan insting",
                    "Menggunakan logika dan pemikiran rasional",
                ],
            ],
            [
                "pertanyaan" =>
                    "Ketika diberi tugas, Anda cenderung lebih fokus pada...",
                "pilihan" => [
                    "Tujuan akhir yang ingin dicapai",
                    "Langkah-langkah yang harus diikuti",
                ],
            ],
            [
                "pertanyaan" =>
                    "Saat bekerja dalam tim, Anda lebih suka berperan sebagai...",
                "pilihan" => [
                    "Inovator yang mencetuskan ide-ide baru",
                    "Penyusun yang mengatur jalannya proyek",
                ],
            ],
            [
                "pertanyaan" => "Apa yang lebih menggambarkan gaya kerja Anda?",
                "pilihan" => ["Kreatif dan fleksibel", "Teratur dan terstruktur"],
            ],
            [
                "pertanyaan" =>
                    "Ketika dihadapkan pada situasi yang ambigu, Anda cenderung...",
                "pilihan" => [
                    "Mengikuti naluri dan perasaan",
                    "Menganalisis dengan cermat sebelum bertindak",
                ],
            ],
            [
                "pertanyaan" => "Bagaimana cara Anda memecahkan konflik?",
                "pilihan" => [
                    "Dengan mencari solusi yang kreatif",
                    "Dengan menemukan jalan tengah yang bisa diterima semua pihak",
                ],
            ],
            [
                "pertanyaan" => "Dalam bekerja, Anda lebih suka...",
                "pilihan" => [
                    "Mengubah metode kerja jika dibutuhkan",
                    "Tetap mempertahankan cara kerja yang telah terbukti berhasil",
                ],
            ],
            [
                "pertanyaan" =>
                    "Ketika dihadapkan pada situasi baru, Anda lebih condong pada...",
                "pilihan" => [
                    "Menyambut tantangan dengan antusiasme",
                    "Berhati-hati dan ingin mengetahui lebih banyak sebelum bertindak",
                ],
            ],
            [
                "pertanyaan" =>
                    "Saat bekerja dalam tim, peran yang lebih Anda sukai adalah sebagai...",
                "pilihan" => [
                    "Penggerak yang memberikan ide segar",
                    "Pemastikan agar setiap langkah terencana dengan baik",
                ],
            ],
            [
                "pertanyaan" =>
                    "Apa yang lebih Anda tekankan saat berhadapan dengan masalah?",
                "pilihan" => [
                    "Inovasi dan solusi baru",
                    "Mencari cara yang terbukti berhasil sebelumnya",
                ],
            ],
            [
                "pertanyaan" =>
                    "Dalam menghadapi proyek besar, prioritas Anda adalah pada...",
                "pilihan" => [
                    "Kreativitas dan cara baru untuk menyelesaikan",
                    "Mengikuti rencana yang telah disusun dengan baik",
                ],
            ],
            [
                "pertanyaan" =>
                    "Bagaimana Anda bereaksi terhadap perubahan yang mendadak?",
                "pilihan" => [
                    "Dengan mencoba beradaptasi secepat mungkin",
                    "Dengan mengambil waktu untuk mengevaluasi konsekuensinya terlebih dahulu",
                ],
            ],
            [
                "pertanyaan" =>
                    "Dalam situasi stres, Anda cenderung lebih fokus pada...",
                "pilihan" => [
                    "Menemukan solusi baru",
                    "Mengendalikan situasi agar tetap teratur",
                ],
            ],
            [
                "pertanyaan" =>
                    "Apa yang lebih Anda tekankan saat melakukan tugas?",
                "pilihan" => [
                    "Kreativitas dalam menjalankan tugas",
                    "Keteraturan dan kepatuhan pada prosedur yang ada",
                ],
            ],
        ];

        foreach ($questions as $question) {
            $questionBank = QuestionBank::create([
                'question_text' => $question['pertanyaan'],
            ]);
            
            
            foreach ($question['pilihan'] as $choice) {
                $questionBank->choices()->create([
                    'choice_text' => $choice,
                ]);
            }
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
