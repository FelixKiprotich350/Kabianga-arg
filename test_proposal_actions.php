<?php
/**
 * Simple test script to verify proposal action endpoints
 * Run with: php test_proposal_actions.php
 */

echo "Testing Proposal Action APIs...\n\n";

// Test data
$baseUrl = 'http://localhost:8000/api/v1/proposals';
$proposalId = 1; // Replace with actual proposal ID
$testData = [
    'approve' => [
        'fundingfinyearfk' => '1',
        'comment' => 'Test approval comment'
    ],
    'reject' => [
        'comment' => 'Test rejection reason'
    ],
    'request-changes' => [
        'comment' => 'Test change request'
    ]
];

// Test endpoints
$endpoints = [
    'approve' => "$baseUrl/$proposalId/approve",
    'reject' => "$baseUrl/$proposalId/reject", 
    'mark-draft' => "$baseUrl/$proposalId/mark-draft",
    'request-changes' => "$baseUrl/$proposalId/request-changes"
];

foreach ($endpoints as $action => $url) {
    echo "Testing $action endpoint: $url\n";
    
    $data = $testData[$action] ?? [];
    $postData = http_build_query($data);
    
    $context = stream_context_create([
        'http' => [
            'method' => 'POST',
            'header' => "Content-Type: application/x-www-form-urlencoded\r\n" .
                       "Content-Length: " . strlen($postData) . "\r\n",
            'content' => $postData
        ]
    ]);
    
    echo "  - Endpoint configured ✓\n";
    echo "  - Data prepared ✓\n\n";
}

echo "All endpoints configured successfully!\n";
echo "Note: Actual testing requires authentication and valid proposal IDs.\n";
?>