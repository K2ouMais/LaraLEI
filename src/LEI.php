<?php

namespace K2ouMais\LaraLEI;

use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

class LEI
{
    private $letters;
    private $numbers;
    private $decodes;
    private $initialChecksum = '00';

    /**
     * LEI constructor.
     */
    public function __construct()
    {
        // Fill the arrays with ranges
        $this->letters = range('A', 'Z');
        $this->numbers = range('0', '9');

        // Starting decode value
        $startDecode = 10;

        // Fill decodes array with letter and decode values
        foreach ($this->letters as $letter) {
            $this->decodes[$letter] = $startDecode;
            $startDecode++;
        }
    }

    /**
     * @return mixed
     */
    public function generate()
    {
        // Merge letters and numbers
        $letterNumb = array_merge($this->letters, $this->numbers);

        // Generic LEI
        $genericLEI = config('LaraLEI.lou_ident') . config('LaraLEI.lei_reserved');

        // Add 12 random chars to the generic LEI
        for ($i = 1; $i <= 12; $i++) {
            $genericLEI .= Arr::random($letterNumb);
        }

        // Convert the generic LEI with an initial checksum of 00
        $convertedLEI = $this->convert($genericLEI . $this->initialChecksum);

        // Calculate the final checksum
        $calculatedChecksum = (98 - $convertedLEI->get('checksum'));

        // Convert the new LEI with the final Checksum. (Adding an initial 0 if the checksum is < 10)
        $validation = $this->convert($genericLEI . str_pad($calculatedChecksum, 2, '0'));

        if ($validation->get('checksum') === 1) {

            // If the converted checksum is 1 the LEI is valid and can be returned
            return $validation->get('unconverted');

        } else {

            // If checksum is > 1, generate a new LEI
            return $this->generate();

        }
    }

    /**
     * @param string $lei
     * @return Collection
     */
    private function convert(string $lei): Collection
    {
        return collect([
            // Unconverted LEI is the real LEI. (Example: 529900ZQE7DSZ3467P0084)
            'unconverted' => (string)$lei,

            // Converted is inclusive the letters to numbers conversion. (Example: 52990035261471328353467250084)
            'converted' => (string)$convertedLEI = str_replace(
                array_keys($this->decodes),
                array_values($this->decodes),
                strtoupper($lei)
            ),

            // Checksum is just the converted LEI modulo 97 (Only valid if 1)
            'checksum' => strlen($lei) !== 20 ? 0 : (int)bcmod($convertedLEI, '97'),
        ]);

    }

    /**
     * @param string $lei
     * @return bool
     */
    public function validate(string $lei): bool
    {
        return $this->convert($lei)->get('checksum') === 1;
    }

    public function search(array $leis)
    {
        try {

            $request = Http::get('https://api.gleif.org/api/v1/lei-records?filter[lei]=' . implode(',', $leis));

            return response()->json($request->json(), $request->status());

        } catch (Exception $ex) {

            return response($ex->getMessage(), 500)->header('Content-Type', 'application/json');

        }
    }
}
