<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Psychotest;
use App\Models\Invoice;
use App\Models\Result;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\QuestionBank;

class PsychotestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $psychotests = DB::table('psychotests')
                        ->join('invoices', 'psychotests.invoice_id', '=', 'invoices.id')
                        ->join('results', 'psychotests.result_id', '=', 'results.id')
                        ->where('psychotests.user_id', Auth::user()->id)
                        ->where('invoices.status', 'paid')
                        ->select('psychotests.code', 'psychotests.attempt_date', 'invoices.status',  'invoices.qr_code', 'results.status', 'results.attachment_path', 'results.updated_at')
                        ->get();

        return $this->success($psychotests, 'Psychotests retrieved successfully');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function getResult(string $code)
    {
        $mbtiResultDescription = [
            "ISTJ" => "Orang dengan kepribadian ISTJ biasanya cenderung pendiam dan serius, namun sangat gigih, bertanggung jawab, dan dapat diandalkan. Orang dengan kepribadian ISTJ umumnya juga selalu menginginkan ketertiban dan keteraturan dalam setiap aspek hidupnya. Oleh sebab itu, ia dijuluki sebagai 'Si Perencana yang Terorganisir'.",
            "ISTP" => "Kepribadian ISTP umumnya adalah orang sangat realistis, logis, spontan, dan berfokus pada masa kini. Orang dengan kepribadian ISTP juga memiliki kemampuan memecahkan masalah dan menghadapi krisis yang baik. Tak heran, pribadi ISTP kerap dijuluki sebagai 'Si Mekanik' atau 'Si Pengrajin'.",
            "ISFJ" => "Kepribadian dengan tipe ISFJ adalah salah satu tipe kepribadian yang paling umum. Orang dengan kepribadian ISFJ biasanya dikenal sebagai pribadi yang penuh perhatian, kehangatan, dan aura positifnya yang bisa membawa ketenangan pada orang-orang di sekitarnya. Ini sebabnya, pribadi ISFJ dijuluki sebagai 'Si Pelindung'.",        
            "ISFP" => "Kepribadian ISFP biasanya adalah pribadi yang bisa membuat orang lain nyaman, memiliki keprihatinan yang tinggi terhadap orang lain, penuh semangat, dan kreatif. Individu ISFP umumnya juga sangat berbakat dalam dunia seni. Oleh sebab itu, ia dijuluki sebagai 'Sang Seniman' di antara kepribadian lainnya.",
            "INFJ" => "Kepribadian INFJ atau yang kerap dijuluki sebagai 'Sang Penasihat' adalah tipe kepribadian yang paling langka. Pribadi INFJ biasanya sangat suportif, peka terhadap perasaan orang lain, dan suka menolong. Tidak hanya itu, ia juga terkenal dengan idealismenya untuk mengubah dunia menjadi tempat yang lebih baik bagi semua orang.",
            "INFP" => "Orang dengan tipe kepribadian INFP terlihat idealis, perfeksionis, dan memiliki jiwa kemanusiaan yang tinggi. Ketika terdapat konflik, pribadi INFP biasanya sangat pandai menjadi mediator untuk menengahi konflik tersebut. Inilah mengapa pribadi INFP dijuluki sebagai 'Si Mediator'.",
            "INTJ" => "Kepribadian INTJ adalah orang yang kreatif dan analitis. Oleh sebab itu, ia sangat pandai membuat strategi dan perencanaan. Selain itu, individu INTJ biasanya juga memiliki kemampuan untuk menciptakan berbagai solusi inovatif bagi setiap permasalahan. Maka dari itu, pribadi INTJ mendapat julukan 'Si Ahli Strategi'.",
            "INTP" => "Individu dengan kepribadian ini, INTP mendapat julukan 'Si Logis' atau 'Sang Pemikir' tentu karena ia adalah seorang pemikir yang logis, analitis, dan berwawasan luas. Namun, pribadi INTP biasanya tidak menyukai aturan dan perencanaan. Sebaliknya, ia lebih suka memiliki banyak pilihan terhadap suatu hal.",
            "ESTP" => "Orang dengan kepribadian ESTP adalah orang yang sangat ramah, antusias, dan pandai berteman. Ia biasanya juga sangat pandai memengaruhi orang lain, serta memiliki kemampuan untuk berpikir dan bertindak cepat dalam situasi yang darurat. Oleh sebab itu, pribadi ESTP kerap dijuluk sebagai 'Sang Pembujuk'.",
            "ESTJ"=> "Individu dengan kepribadian ESTJ dijuluki sebagai 'Si Pengarah yang Tegas' karena ia paling terkenal dengan kemampuannya dalam berorganisasi dan memimpin. Kemampuan mengarahkannya ini didapatkan dari karakteristiknya yang tegas, teliti, disiplin, taat aturan, dan bertanggung jawab.",
            "ESFP"=> "Individu dengan kepribadian ESFP adalah kepribadian yang disebut sebagai kepribadian yang paling ekstrovert. Pasalnya, ia sangat senang menghabiskan waktunya dengan orang lain dan suka menjadi pusat perhatian. Tak heran, pribadi ESFP dijuluki sebagai 'Sang Penghibur'.",
            "ESFJ" => "Orang dengan kepribadian ESFJ biasanya cenderung berhati lembut, setia, ramah, dan terorganisir. Ia sangat suka membantu orang lain, terutama orang-orang di sekitarnya. Nah, inilah alasan mengapa pribadi ESFJ disebut sebagai 'Sang Pengasuh'.",
            "ENFP" => "Pada umumnya orang dengan kepribadian ENFP dijuluki sebagai 'Sang Motivator' di antara tipe kepribadian lainnya. Ini karena orang dengan tipe kepribadian ENFP sangat senang menumbuhkan berbagai ide positif untuk membantu orang lain dan mampu mengalirkan energi positif tersebut pada orang-orang di sekitarnya.",
            "ENFJ" => "Kepribadian ENFJ terkenal akan kemampuannya untuk menjalin persahabatan dengan hampir setiap kepribadian lainnya, bahkan dengan pribadi yang sangat tertutup. Biasanya, individu ENFJ juga memiliki empati yang tinggi, sehingga ia sangat senang membantu orang lain untuk mencapai tujuan mereka. Berkat karakteristiknya ini, pribadi ENFJ dijuluki sebagai 'Sang Protagonis'.",
            "ENTP" => "Individu dengan kepribadian ENTP biasa dikenal sebagai pribadi yang logis, cerdas, kreatif, dan paling suka berargumen. Berkat sifat-sifatnya tersebut, pribadi ENTP mendapat julukan 'Sang Pendebat'.",
            "ENTJ" => "Orang dengan kepribadian ENTJ cenderung berani, memiliki pengetahuan yang luas dan cerdas. Mereka tipe yang logis dan selalu objektif. Dalam bekerja, mereka lebih fokus dan mampu memikirkan ide-ide besar dan cenderung tidak menyukai kegiatan yang monoton. Mereka lebih menyukai pekerjaan yang konseptual dan memecahkan masalah. Kepribadian ENTJ kerap dijuluki sebagai 'Sang Komandan'.",
        ];

        $result = Psychotest::with('result')->where('code', $code)->first()->result;

        if (!$result)
            return $this->error([], 'Psychotest not found', 404);

        if ($result->status == 'wait')
            return $this->error([], 'Psychotest result not ready', 400);
        
        return $this->success([
            'personality' => $result->personality,
            'description' => $mbtiResultDescription[$result->personality],
        ], 'Psychotest result retrieved successfully');
    }


    public function updateResult(Request $request, string $code)
    {
        $request->validate([
            'personality' => 'nullable|string',
            'attachment' => 'nullable|file|mimes:pdf|max:2048',
        ]);

        $result = Psychotest::with('result')->where('code', $code)->first()->result;
        if (!$result)
            return $this->error([], 'Psychotest not found', 404);
        
        if ($request->personality)
            $result->personality = $request->personality;
    
        if ($request->attachment !== null) {
            $result->attachment_path = '/storage/' . $request->attachment->store('attachments', 'public');
        }

        $result->status = 'ready';
        $result->save();

        return $this->success([], 'Psychotest result updated successfully');
    }

    public function getQuestions(string $code) {
        $psychotest = Psychotest::with('result')->where('code', $code)->first();

        if (!$psychotest)
            return $this->error([], 'Psychotest not found', 404);

        if ($psychotest->result->status !== 'wait')
            return $this->error([], 'Psychotest is finished', 400);

        $questions = QuestionBank::inRandomOrder()->limit(50)->get([
            'question_text',
            'choice_a',
            'choice_b',
            'type_a',
            'type_b',
        ]);

        return $this->success($questions, 'Psychotest questions retrieved successfully');
    }

    public function storeAnswer(Request $request, string $code)
    {
        $request->validate([
            'P' => 'required|integer',
            'I' => 'required|integer',
            'J' => 'required|integer',
            'T' => 'required|integer',
            'E' => 'required|integer',
            'N' => 'required|integer',
            'S' => 'required|integer',
            'F' => 'required|integer',
        ]);

        // $psychotest = Psychotest::where('code', $code)->first();
        $psychotest = Psychotest::with('result')->where('code', $code)->first();

        if (!$psychotest)
            return $this->error([], 'Psychotest not found', 404);

        if ($psychotest->answer_id !== null)
            return $this->error([], 'Psychotest answers already stored', 400);

        $P = $request->P / 15 * 100;
        $I = $request->I / 15 * 100;
        $J = $request->J / 15 * 100;
        $T = $request->T / 15 * 100;
        $E = $request->E / 15 * 100;
        $N = $request->N / 15 * 100;
        $S = $request->S / 15 * 100;
        $F = $request->F / 15 * 100;
        
        $a = $I > $E ? "I" : "E";
        $b = $S > $N ? "S" : "N";
        $c = $T > $F ? "T" : "F";
        $d = $J > $P ? "J" : "P";
        
        $personality = $a . $b . $c . $d;

        $psychotest->result->personality = $personality;
        $psychotest->result->status = 'finish';
        $psychotest->attempt_date = now('Asia/Jakarta');

        $psychotest->result->save();
        $psychotest->save();
        
        return $this->success([], 'Psychotest answers stored successfully');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // TODO: 
        $psychotestAmount = 50000;
        
        $invoice = Invoice::create([
            'amount' => $psychotestAmount,
            'qr_code' => Str::random(32),
        ]);
        $result = Result::create();
        
        $psychotest = Psychotest::create([
            'user_id' => Auth::user()->id,
            'invoice_id' => $invoice->id,
            'result_id' => $result->id,
        ]);
        
        if (!$psychotest)
            return $this->error([], 'Failed to create psychotest', 500);

        return $this->success([
            'code' => $psychotest->code,
        ], 'Psychotest created successfully');
    }

    public function processPayment(string $code)
    {
        $psychotest = Psychotest::with('invoice')->with('result')->where('code', $code)->first();
        
        $invoice = $psychotest->invoice;
        $result = $psychotest->result;

        if (!$invoice)
            return $this->error([], 'Psychotest not found', 404);

        if ($invoice->status === 'expired') 
            return $this->error([], 'Psychotest payment expired', 400);

        if ($invoice->status === 'paid')
            return $this->error([], 'Psychotest already processed', 400);
        
        date_default_timezone_set("Asia/Jakarta");

        $currentDatetime = date('Y-m-d H:i:s');
        $paymentDatetime = date('Y-m-d H:i:s', strtotime($invoice->created_at . ' + 15 minutes'));

        if ($currentDatetime > $paymentDatetime) {
            $invoice->status = 'expired';
            $invoice->save();
            
            return $this->error([], 'Psychotest payment expired', 400);
        }

        $invoice->status = 'paid';
        $invoice->save();

        $result->status = 'wait';
        $result->save();

        // event(new PaymentTest($code));

        return $this->success([], 'Psychotest payment successfully processed');
    }

    public function getPayment(string $code)
    {
        $session = Auth::user();
        $invoice = Psychotest::with('invoice')
                        ->where('code', $code)
                        ->where('user_id', $session->id)
                        ->first()
                        ->invoice;

        if (!$invoice)
            return $this->error([], 'Psychotest not found', 404);

        date_default_timezone_set("Asia/Jakarta");

        $now = date('Y-m-d H:i:s');
        $countdown = strtotime($now) - strtotime($invoice->created_at);
        
        return $this->success([
            'status' => $invoice->status,
            'amount' => $invoice->amount,
            'payment_code' => $invoice->qr_code,
            'countdown' => $countdown,
        ], 'Psychotest status retrieved successfully');
    }
 
}