<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../models/Post.php';

// Enable CORS
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-API-Key, X-Api-Key');

// API Key configuration
$validApiKey = 'hoangminhdeptraicodegioihehe'; // API key mới

// Function to validate API key
function validateApiKey() {
    global $validApiKey;
    $headers = getallheaders();
    
    // Check for both X-API-Key and X-Api-Key
    $apiKey = $headers['X-API-Key'] ?? $headers['X-Api-Key'] ?? null;
    
    if (!$apiKey) {
        throw new Exception('API key header not found. Please use X-API-Key or X-Api-Key header.');
    }
    
    if ($apiKey !== $validApiKey) {
        throw new Exception('Invalid API key');
    }
    
    return true;
}

// Handle GET request for API documentation
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    echo json_encode([
        'status' => 'API is running',
        'endpoint' => '/api/posts.php',
        'method' => 'POST',
        'headers' => [
            'Content-Type: application/json',
            'X-API-Key: your-api-key'
        ],
        'body' => [
            'title' => 'Required: Post title',
            'content' => 'Required: Post content',
            'status' => 'Optional: published or draft (default: draft)',
            'image' => 'Optional: Base64 encoded image or image URL'
        ],
        'example' => [
            'title' => 'My Post Title',
            'content' => 'My post content',
            'status' => 'published',
            'image' => 'data:image/jpeg;base64,/9j/4AAQSkZJRg...' // Base64 image data
        ]
    ]);
    exit;
}

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Validate API key
        validateApiKey();

        // Get JSON data from request body
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception('Invalid JSON data');
        }

        // Validate required fields
        if (empty($data['title']) || empty($data['content'])) {
            throw new Exception('Title and content are required');
        }

        // Handle image if provided
        $imagePath = null;
        if (!empty($data['image'])) {
            // Check if it's a base64 image
            if (strpos($data['image'], 'data:image') === 0) {
                $uploadDir = __DIR__ . '/../uploads/posts/';
                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }

                // Get image data
                $imageData = explode(',', $data['image'])[1];
                $imageData = base64_decode($imageData);
                
                // Generate unique filename
                $fileName = uniqid() . '_' . time() . '.jpg';
                $targetPath = $uploadDir . $fileName;
                
                // Save image
                if (file_put_contents($targetPath, $imageData)) {
                    $imagePath = 'uploads/posts/' . $fileName;
                }
            } 
            // Check if it's a URL
            else if (filter_var($data['image'], FILTER_VALIDATE_URL)) {
                $uploadDir = __DIR__ . '/../uploads/posts/';
                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }

                // Get image from URL
                $imageContent = file_get_contents($data['image']);
                if ($imageContent !== false) {
                    $fileName = uniqid() . '_' . time() . '.jpg';
                    $targetPath = $uploadDir . $fileName;
                    
                    // Save image
                    if (file_put_contents($targetPath, $imageContent)) {
                        $imagePath = 'uploads/posts/' . $fileName;
                    }
                }
            }
        }

        // Create post instance
        $post = new Post();

        // Prepare data for storing
        $postData = [
            'title' => $data['title'],
            'content' => $data['content'],
            'status' => $data['status'] ?? 'draft',
            'image' => $imagePath
        ];

        // Store the post
        $result = $post->store($postData);

        if (!$result) {
            throw new Exception('Failed to create post');
        }

        // Return success response
        echo json_encode([
            'success' => true,
            'message' => 'Post created successfully',
            'data' => [
                'title' => $data['title'],
                'content' => $data['content'],
                'status' => $data['status'] ?? 'draft',
                'image' => $imagePath
            ]
        ]);

    } catch (Exception $e) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'error' => $e->getMessage()
        ]);
    }
    exit;
}

// If method is not GET or POST
http_response_code(405);
echo json_encode([
    'success' => false,
    'error' => 'Method not allowed'
]); 