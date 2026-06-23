<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Services\MARCOSService;

class MarcosAlgorithmTest extends TestCase
{
    /**
     * Test Ideal (AI) and Anti-Ideal (AAI) Calculation
     */
    public function test_ideal_and_anti_ideal_calculation(): void
    {
        $service = new MARCOSService();
        
        $L = [
            [5, 8, 4], // Alternatif 1
            [7, 6, 8], // Alternatif 2
            [3, 9, 5]  // Alternatif 3
        ];
        
        $type = ['benefit', 'cost', 'benefit'];
        
        [$AI, $AAI] = $service->idealAntiIdeal($L, $type);
        
        // Benefit (Max for AI, Min for AAI)
        $this->assertEquals(7, $AI[0]); // max(5,7,3)
        $this->assertEquals(3, $AAI[0]); // min(5,7,3)
        
        // Cost (Min for AI, Max for AAI)
        $this->assertEquals(6, $AI[1]); // min(8,6,9)
        $this->assertEquals(9, $AAI[1]); // max(8,6,9)
        
        // Benefit
        $this->assertEquals(8, $AI[2]); // max(4,8,5)
        $this->assertEquals(4, $AAI[2]); // min(4,8,5)
    }

    /**
     * Test Normalization Matrix
     */
    public function test_normalization_calculation(): void
    {
        $service = new MARCOSService();
        
        $L_ext = [
            [3, 9, 4], // AAI
            [5, 8, 4], // Alt 1
            [7, 6, 8], // Alt 2
            [7, 6, 8], // AI
        ];
        
        $AI = [7, 6, 8];
        $type = ['benefit', 'cost', 'benefit'];
        
        $N = $service->normalisasi($L_ext, $AI, $type);
        
        // AAI Row
        $this->assertEquals(3/7, $N[0][0]); // benefit = x / AI
        $this->assertEquals(6/9, $N[0][1]); // cost = AI / x
        $this->assertEquals(4/8, $N[0][2]); // benefit = x / AI
        
        // AI Row
        $this->assertEquals(7/7, $N[3][0]);
        $this->assertEquals(6/6, $N[3][1]);
        $this->assertEquals(8/8, $N[3][2]);
    }
}
