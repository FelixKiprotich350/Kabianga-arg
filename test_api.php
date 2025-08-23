<?php
// Simple test script to check API endpoints
require_once 'vendor/autoload.php';

use Illuminate\Http\Request;

// Test the collaborators and publications endpoints
echo "Testing API endpoints...\n";

// You can run this script to test the endpoints manually
// php test_api.php

echo "API test script created. Run 'php artisan serve' and test the endpoints manually.\n";
echo "Test URLs:\n";
echo "- GET /api/v1/collaborators?proposalid=1\n";
echo "- POST /api/v1/collaborators\n";
echo "- GET /api/v1/publications?proposalid=1\n";
echo "- POST /api/v1/publications\n";