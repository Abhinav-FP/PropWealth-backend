<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Suburb;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\SuburbsImport;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SuburbController extends Controller
{

    private const GOOGLE_CLOUD_API_URL = 'https://storage.googleapis.com/suburbtrends-map-dev/api/infrastructure';
    private const CACHE_KEY = 'google_cloud_infrastructure_data';
    private const CACHE_DURATION = 3600; // 1 hour in seconds

    /**
     * Fetch and organize data from Google Cloud API only
     */
    public function index(): JsonResponse
    {
        try {
            // Fetch data from Google Cloud API
            $googleCloudData = $this->fetchGoogleCloudData();

            if (empty($googleCloudData)) {
                return response()->json([
                    'type' => 'FeatureCollection',
                    'features' => [],
                    'metadata' => [
                        'total_suburbs' => 0,
                        'total_projects' => 0,
                        'message' => 'No data available from Google Cloud API',
                        'data_source' => 'google_cloud_api',
                        'last_updated' => now()->toISOString()
                    ]
                ]);
            }

            // Organize the data
            $features = $this->organizeGoogleCloudData($googleCloudData);

            // Group by suburb to count unique suburbs
            $uniqueSuburbs = collect($googleCloudData)->groupBy(function ($project) {
                return strtolower($project['Suburb'] ?? 'unknown') . '_' . strtoupper($project['State'] ?? 'unknown');
            })->count();

            return response()->json([
                'type' => 'FeatureCollection',
                'features' => $features,
                'metadata' => [
                    'total_suburbs' => $uniqueSuburbs,
                    'total_projects' => count($googleCloudData),
                    'suburbs_with_projects' => count($features),
                    'data_source' => 'google_cloud_api',
                    'last_updated' => now()->toISOString()
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching or organizing data: ' . $e->getMessage());

            return response()->json([
                'error' => 'Failed to fetch data',
                'message' => 'Unable to retrieve infrastructure data at this time'
            ], 500);
        }
    }

    /**
     * Fetch data from Google Cloud API with caching
     */
    private function fetchGoogleCloudData(): array
    {
        // Try to get cached data first
        $cachedData = Cache::get(self::CACHE_KEY);
        if ($cachedData !== null) {
            return $cachedData;
        }

        try {
            $response = Http::timeout(30)
                ->retry(3, 1000) // Retry 3 times with 1 second delay
                ->get(self::GOOGLE_CLOUD_API_URL);

            if (!$response->successful()) {
                throw new \Exception('Google Cloud API returned status: ' . $response->status());
            }

            $data = $response->json();

            // Validate that we received valid data
            if (!is_array($data)) {
                throw new \Exception('Invalid data format received from Google Cloud API');
            }

            // Cache the data
            Cache::put(self::CACHE_KEY, $data, self::CACHE_DURATION);

            return $data;
        } catch (\Exception $e) {
            Log::error('Failed to fetch Google Cloud data: ' . $e->getMessage());

            // Return empty array if API fails
            return [];
        }
    }

    /**
     * Organize Google Cloud data into GeoJSON features grouped by suburb
     */
    private function organizeGoogleCloudData(array $googleCloudData): array
    {
        // Group projects by suburb/state combination
        $suburbGroups = collect($googleCloudData)->groupBy(function ($project) {
            return strtolower($project['Suburb'] ?? 'unknown') . '_' . strtoupper($project['State'] ?? 'unknown');
        });

        // Transform each suburb group into a feature
        return $suburbGroups->map(function ($projects, $suburbKey) {
            $firstProject = $projects->first();

            // Calculate bbox from all projects in this suburb
            $bbox = $this->calculateBboxFromCoordinates($projects->toArray());

            // Transform all projects for this suburb
            $transformedProjects = $projects->map(function ($project) {
                return [
                    'project_name' => $project['Project Name'] ?? 'Unknown Project',
                    'website' => $project['Website'] ?? null,
                    'suburb' => $project['Suburb'] ?? null,
                    'state' => $project['State'] ?? null,
                    'postcode' => $project['Postcode'] ?? null,
                    'ssc_code' => $project['Code (SSC)'] ?? null,
                    'coordinates' => [
                        'lat' => $project['Lat'] ?? null,
                        'lng' => $project['Long'] ?? null
                    ],
                    'source' => 'google_cloud_api'
                ];
            })->toArray();

            return [
                'type' => 'Feature',
                'id' => $firstProject['Code (SSC)'] ?? $suburbKey,
                'properties' => [
                    'name' => $firstProject['Suburb'] ?? 'Unknown',
                    'state' => $firstProject['State'] ?? 'Unknown',
                    'projects' => $transformedProjects,
                    'project_count' => count($transformedProjects),
                    'total_infrastructure_value' => $this->calculateTotalValue($transformedProjects)
                ],
                'bbox' => $bbox
            ];
        })->values()->toArray();
    }

    /**
     * Calculate total estimated value of projects (if available in future data)
     */
    private function calculateTotalValue(array $projects): ?float
    {
        $total = 0;
        $hasValues = false;

        foreach ($projects as $project) {
            if (isset($project['estimated_value']) && is_numeric($project['estimated_value'])) {
                $total += (float)$project['estimated_value'];
                $hasValues = true;
            }
        }

        return $hasValues ? $total : null;
    }

    /**
     * Calculate bbox from coordinates array with specific dimensions
     */
    private function calculateBboxFromCoordinates(array $projects): ?array
    {
        $validCoordinates = [];

        foreach ($projects as $project) {
            $lat = $project['Lat'] ?? null;
            $lng = $project['Long'] ?? null;

            if (is_numeric($lat) && is_numeric($lng)) {
                $validCoordinates[] = [
                    'lat' => (float)$lat,
                    'lng' => (float)$lng
                ];
            }
        }

        if (empty($validCoordinates)) {
            return null;
        }

        // Calculate center point
        if (count($validCoordinates) === 1) {
            // Use the single coordinate as center
            $centerLat = $validCoordinates[0]['lat'];
            $centerLng = $validCoordinates[0]['lng'];
        } else {
            // Calculate center from all coordinates
            $lats = array_column($validCoordinates, 'lat');
            $lngs = array_column($validCoordinates, 'lng');

            $centerLat = array_sum($lats) / count($lats);
            $centerLng = array_sum($lngs) / count($lngs);
        }

        // Create 3km × 3km bbox around center point
        return $this->createBboxFromCenter($centerLat, $centerLng, 3.0, 3.0);
    }

    /**
     * Create bbox of specific dimensions around a center point
     */
    private function createBboxFromCenter(float $centerLat, float $centerLng, float $widthKm, float $heightKm): array
    {
        // Convert kilometers to degrees
        // 1 degree latitude ≈ 111 km
        // 1 degree longitude varies by latitude: ≈ 111 * cos(latitude) km

        $latDegreeDistance = 111.0; // km per degree latitude
        $lngDegreeDistance = 111.0 * cos(deg2rad($centerLat)); // km per degree longitude at this latitude

        // Calculate half-extents in degrees
        $halfWidthDeg = ($widthKm / 2) / $lngDegreeDistance;
        $halfHeightDeg = ($heightKm / 2) / $latDegreeDistance;

        // Create bbox: [minLng, minLat, maxLng, maxLat]
        return [
            $centerLng - $halfWidthDeg, // minLng (west)
            $centerLat - $halfHeightDeg, // minLat (south)
            $centerLng + $halfWidthDeg,  // maxLng (east)  
            $centerLat + $halfHeightDeg  // maxLat (north)
        ];
    }

    /**
     * Create bbox with custom dimensions (utility method)
     */
    public function createCustomBbox(float $lat, float $lng, float $widthKm = 5.0, float $heightKm = 3.0): array
    {
        return $this->createBboxFromCenter($lat, $lng, $widthKm, $heightKm);
    }

    /**
     * Get projects by state (additional endpoint)
     */
    public function getProjectsByState(string $state): JsonResponse
    {
        try {
            $googleCloudData = $this->fetchGoogleCloudData();

            $stateProjects = array_filter($googleCloudData, function ($project) use ($state) {
                return isset($project['State']) &&
                    strtoupper($project['State']) === strtoupper($state);
            });

            return response()->json([
                'state' => strtoupper($state),
                'projects' => array_values($stateProjects),
                'total_projects' => count($stateProjects)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to fetch projects by state',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Search projects by suburb name
     */
    public function searchProjects(Request $request): JsonResponse
    {
        $searchTerm = $request->get('suburb', '');

        if (empty($searchTerm)) {
            return response()->json([
                'error' => 'Search term is required',
                'message' => 'Please provide a suburb name to search'
            ], 400);
        }

        try {
            $googleCloudData = $this->fetchGoogleCloudData();

            $matchedProjects = array_filter($googleCloudData, function ($project) use ($searchTerm) {
                return isset($project['Suburb']) &&
                    stripos($project['Suburb'], $searchTerm) !== false;
            });

            return response()->json([
                'search_term' => $searchTerm,
                'projects' => array_values($matchedProjects),
                'total_matches' => count($matchedProjects)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to search projects',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Clear the Google Cloud API cache
     */
    public function clearCache(): JsonResponse
    {
        Cache::forget(self::CACHE_KEY);

        return response()->json([
            'message' => 'Cache cleared successfully'
        ]);
    }

    /**
     * Get only Google Cloud infrastructure data (for testing)
     */
    public function getInfrastructureData(): JsonResponse
    {
        try {
            $data = $this->fetchGoogleCloudData();

            return response()->json([
                'data' => $data,
                'count' => count($data),
                'cached' => Cache::has(self::CACHE_KEY)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to fetch infrastructure data',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // ========== ADMIN METHODS (Keep if you still need them for other purposes) ==========

    public function adminIndex()
    {
        $suburbs = Suburb::paginate(20);
        return view('backend.suburbs.index', compact('suburbs'));
    }

    public function showUploadForm()
    {
        return view('backend.suburbs.upload');
    }

    public function import(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|mimes:xlsx,xls,csv|max:2048',
        ]);

        try {
            Excel::import(new SuburbsImport, $request->file('excel_file'));
            return redirect()->back()->with('success', 'Suburb data imported successfully!');
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            $errors = [];
            foreach ($failures as $failure) {
                $errors[] = 'Row ' . $failure->row() . ': ' . implode(', ', $failure->errors());
            }
            return redirect()->back()->withErrors($errors)->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred during import: ' . $e->getMessage());
        }
    }

    public function destroyAll()
    {
        try {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            Suburb::truncate();
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            return redirect()->back()->with('success', 'All suburb data deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while deleting suburb data: ' . $e->getMessage());
        }
    }

    public function create()
    {
        return view('backend.suburbs.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'suburb_id' => 'required|numeric|unique:suburbs,suburb_id',
            'name' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'min_lng' => 'required|numeric',
            'min_lat' => 'required|numeric',
            'max_lng' => 'required|numeric',
            'max_lat' => 'required|numeric',
        ]);

        try {
            $bboxArray = [
                (float)$request->input('min_lng'),
                (float)$request->input('min_lat'),
                (float)$request->input('max_lng'),
                (float)$request->input('max_lat'),
            ];

            Suburb::create([
                'suburb_id' => $request->input('suburb_id'),
                'name' => $request->input('name'),
                'state' => $request->input('state'),
                'bbox' => json_encode($bboxArray),
            ]);

            return redirect()->route('suburbs.adminIndex')->with('success', 'Suburb added successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while adding the suburb: ' . $e->getMessage())->withInput();
        }
    }

    public function edit($id)
    {
        $suburb = Suburb::findOrFail($id);
        $suburb->bbox = json_decode($suburb->bbox, true);
        return view('backend.suburbs.edit', compact('suburb'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'min_lng' => 'required|numeric',
            'min_lat' => 'required|numeric',
            'max_lng' => 'required|numeric',
            'max_lat' => 'required|numeric',
        ]);

        $suburb = Suburb::findOrFail($id);

        try {
            $bboxArray = [
                (float)$request->input('min_lng'),
                (float)$request->input('min_lat'),
                (float)$request->input('max_lng'),
                (float)$request->input('max_lat'),
            ];

            $suburb->update([
                'name' => $request->input('name'),
                'state' => $request->input('state'),
                'bbox' => json_encode($bboxArray),
            ]);

            return redirect()->route('suburbs.adminIndex')->with('success', 'Suburb updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while updating the suburb: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy(Suburb $suburb)
    {
        try {
            $suburb->delete();
            return redirect()->back()->with('success', 'Suburb deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while deleting the suburb: ' . $e->getMessage());
        }
    }
}