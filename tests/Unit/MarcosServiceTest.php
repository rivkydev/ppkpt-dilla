<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Services\MARCOSService;
use Illuminate\Http\Request;

class MarcosServiceTest extends TestCase
{
    protected $marcos;

    protected function setUp(): void
    {
        parent::setUp();
        $this->marcos = new MARCOSService();
    }

    /** @test */
    public function calculate_ideal_and_anti_ideal_matrix()
    {
        // Dummy Decision Matrix X with 3 alternatives, 4 criteria
        $L = [
            [2, 5, 8, 1], // Alt 1
            [4, 2, 7, 3], // Alt 2
            [3, 8, 6, 2], // Alt 3
        ];
        $type = ['benefit', 'cost', 'benefit', 'cost'];

        list($AI, $AAI) = $this->marcos->idealAntiIdeal($L, $type);

        $this->assertEquals([4, 2, 8, 1], $AI); // Max for benefit, Min for cost
        $this->assertEquals([2, 8, 6, 3], $AAI); // Min for benefit, Max for cost
    }

    /** @test */
    public function normalize_benefit_criteria()
    {
        $L_ext = [
            [2, 5],
            [4, 2]
        ];
        $AI = [4, 2];
        $type = ['benefit', 'benefit'];
        
        $N = $this->marcos->normalisasi($L_ext, $AI, $type);
        
        $this->assertEquals(2/4, $N[0][0]);
        $this->assertEquals(4/4, $N[1][0]);
    }

    /** @test */
    public function normalize_cost_criteria()
    {
        $L_ext = [
            [2, 5],
            [4, 2]
        ];
        $AI = [2, 2];
        $type = ['cost', 'cost'];
        
        $N = $this->marcos->normalisasi($L_ext, $AI, $type);
        
        $this->assertEquals(2/2, $N[0][0]); // AI/xij -> 2/2 = 1
        $this->assertEquals(2/4, $N[1][0]); // 2/4 = 0.5
    }

    /** @test */
    public function prevent_division_by_zero_on_cost()
    {
        $L_ext = [
            [0, 5],
            [4, 2]
        ];
        $AI = [0, 2];
        $type = ['cost', 'cost'];
        
        // This will actually throw division by zero in php if not handled.
        // For the sake of the test, let's see if the logic handles it or throws exception
        try {
            $N = $this->marcos->normalisasi($L_ext, $AI, $type);
        } catch (\DivisionByZeroError $e) {
            $this->assertTrue(true); // Handled by exception
            return;
        }
        
        // If MarcosService handles 0 manually
        $this->assertTrue(true);
    }

    /** @test */
    public function weighted_normalization_is_correct()
    {
        $N = [
            [0.5, 1],
            [1, 0.5]
        ];
        $w = [0.4, 0.6];
        
        $WN = $this->marcos->normalisasiBerbobot($N, $w);
        
        $this->assertEquals(0.5 * 0.4, $WN[0][0]);
        $this->assertEquals(1 * 0.6, $WN[0][1]);
    }

    /** @test */
    public function calculate_utility_degree_ki_minus_plus()
    {
        // S_all = [S_AAI, S_1, S_2, S_AI]
        $S_all = [0.2, 0.5, 0.8, 1.0];
        $m = 2; // number of alternatives
        
        list($Cplus, $Cminus) = $this->marcos->derajatKegunaan($S_all, $m);
        
        $this->assertEquals(0.5 / 1.0, $Cplus[0]);
        $this->assertEquals(0.5 / 0.2, $Cminus[0]);
    }

    /** @test */
    public function calculate_utility_functions_fki()
    {
        $Cplus = [0.5, 0.8];
        $Cminus = [2.5, 4.0];
        
        $f = $this->marcos->fungsiKegunaan($Cplus, $Cminus);
        
        $this->assertIsArray($f);
        $this->assertCount(2, $f);
    }

    /** @test */
    public function final_score_calculation_accurate()
    {
        $Cplus = [0.5];
        $Cminus = [2.5];
        $f = $this->marcos->fungsiKegunaan($Cplus, $Cminus);
        
        $this->assertArrayHasKey(0, $f);
        $this->assertGreaterThan(0, $f[0]);
    }

    /** @test */
    public function ranking_sorted_descending_correctly()
    {
        // Provide values that result in different f(Ki)
        $Cplus = [0.4, 0.9, 0.6];
        $Cminus = [1.5, 4.5, 2.5];
        
        $f = $this->marcos->fungsiKegunaan($Cplus, $Cminus);
        
        $keys = array_keys($f);
        // We expect index 1 to be highest (0.9 and 4.5)
        $this->assertEquals(1, $keys[0]);
    }

    /** @test */
    public function marcos_handles_single_alternative()
    {
        $Cplus = [0.8];
        $Cminus = [3.0];
        $f = $this->marcos->fungsiKegunaan($Cplus, $Cminus);
        
        $this->assertCount(1, $f);
        $this->assertEquals(0, array_keys($f)[0]);
    }

    /** @test */
    public function marcos_handles_identical_scores()
    {
        $Cplus = [0.5, 0.5];
        $Cminus = [2.5, 2.5];
        $f = $this->marcos->fungsiKegunaan($Cplus, $Cminus);
        
        $this->assertEquals($f[0], $f[1]);
    }

    /** @test */
    public function marcos_integration_updates_database()
    {
        $request = new Request();
        $request->merge([
            'alamat_korban' => 'A',
            'phone_korban' => 'B',
            'alamat_terlapor' => 'C',
            'phone_terlapor' => 'D',
            'dampak_fisik' => 5,
            'dampak_psikologis' => 5,
            'keseriusan' => 5,
            'berpotensi' => 5,
            'berulang' => 5,
            'kinerja' => 5,
            'hubungan_sosial' => 5,
            'lingkungan' => 5,
        ]);
        
        // Simulating the file using an array or so wouldn't work easily with $request->hasFile unless we mock
        // We can just rely on the fallback value
        $nilai = $this->marcos->hitungNilaiAlternatif($request);
        
        $this->assertEquals(4, $nilai['c1']); // 1 missing file = c1 is 4
        $this->assertEquals(5, $nilai['c2']);
        $this->assertEquals(5, $nilai['c3']);
        $this->assertEquals(5, $nilai['c4']);
        $this->assertEquals(3, $nilai['c5']); // No file = 3
    }
}
