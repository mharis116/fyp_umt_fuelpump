<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Repositories\HierarchyRepository;

class HierarchyImport implements ToCollection
{
    protected $repo;
    protected $headers = [];

    public function __construct()
    {
        $this->repo = new HierarchyRepository();
    }

    /**
     * Handle Excel import
     */
    public function collection(Collection $rows)
    {
        if ($rows->count() < 2) {
            return;
        }

        // Remove first row (level numbers)
        // $rows->shift();

        // Second row contains headers (e.g. Country, Region, City, Branch, Code, Address)
        $this->headers = array_map(fn($h) => strtolower(trim($h)), $rows->shift()->toArray());

        // Identify last two columns (Code & Address)
        $codeIndex = $this->findColumnIndex($this->headers, ['code']);
        $addressIndex = $this->findColumnIndex($this->headers, ['address']);

        $codeIndex ??= count($this->headers) - 2;
        $addressIndex ??= count($this->headers) - 1;

        // Everything before code & address are hierarchy levels
        $hierarchyHeaders = array_slice($this->headers, 0, $codeIndex);



        /** Validation */
        $this->validateRows($rows, $hierarchyHeaders, $codeIndex, $addressIndex);

        // Process each data row
        foreach ($rows as $row) {
            $row = array_map(fn($v) => trim((string) $v), $row->toArray());

            $levels = [];
            foreach ($hierarchyHeaders as $index => $headerName) {
                $value = $row[$index] ?? '';
                if ($value !== '') {
                    $levels[$headerName] = $value; // <— Keep header name => value mapping
                }
            }

            $code = $row[$codeIndex] ?? '';
            $address = $row[$addressIndex] ?? '';

            if (!empty($levels)) {
                $this->repo->createDynamicHierarchyTree($levels, $code, $address);
            }
        }
    }

    /**
     * Find a column index by possible header names
     */
    protected function findColumnIndex(array $headers, array $possibleNames): ?int
    {
        foreach ($headers as $i => $header) {
            if (in_array(strtolower(trim($header)), $possibleNames)) {
                return $i;
            }
        }
        return null;
    }

    protected function validateRows(Collection $rows, array $hierarchyHeaders, int $codeIndex, int $addressIndex)
    {
        $codes = [];
        $addresses = [];
        $branchNames = [];
        $levelValues = []; // track all names used in each level

        foreach ($rows as $i => $row) {
            $row = array_map(fn($v) => trim((string)$v), $row->toArray());
            $rowNum = $i + 2; // offset for header rows

            // --- Unique Code ---
            $code = $row[$codeIndex] ?? '';
            if ($code !== '') {
                if (in_array($code, $codes)) {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        'code' => "Duplicate code '{$code}' found in row {$rowNum}.",
                    ]);
                }
                $codes[] = $code;
            }

            // --- Unique Address ---
            $address = $row[$addressIndex] ?? '';
            if ($address !== '') {
                if (in_array($address, $addresses)) {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        'address' => "Duplicate address '{$address}' found in row {$rowNum}.",
                    ]);
                }
                $addresses[] = $address;
            }

            // --- Unique Branch Name (last hierarchy level before code) ---
            $branchName = $row[$codeIndex - 1] ?? '';
            if ($branchName !== '') {
                if (in_array(strtolower($branchName), array_map('strtolower', $branchNames))) {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        'branch' => "Duplicate branch name '{$branchName}' found in row {$rowNum}.",
                    ]);
                }
                $branchNames[] = $branchName;
            }

            // --- Cross-level name reuse check ---
            foreach ($hierarchyHeaders as $index => $header) {
                $levelName = $row[$index] ?? '';
                if ($levelName === '') continue;

                // Record names per column level
                $levelValues[$header][] = strtolower($levelName);

                // Ensure this name isn't used in another hierarchy column
                foreach ($levelValues as $lvlHeader => $values) {
                    if ($lvlHeader !== $header && in_array(strtolower($levelName), $values)) {
                        throw \Illuminate\Validation\ValidationException::withMessages([
                            'hierarchy' => "Value '{$levelName}' appears in both '{$lvlHeader}' and '{$header}' columns (row {$rowNum}).",
                        ]);
                    }
                }
            }
        }
    }
}
