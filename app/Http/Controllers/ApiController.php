<?php

namespace App\Http\Controllers;

/**
 * @OA\Info(
 *     title="Kabianga ARG Portal API",
 *     version="1.0.0",
 *     description="Comprehensive REST API for managing research grants, proposals, and projects at the University of Kabianga. Built with Laravel and Sanctum for secure token-based authentication.",
 *     @OA\Contact(
 *         email="support@kabianga.ac.ke",
 *         name="API Support"
 *     ),
 *     @OA\License(
 *         name="MIT",
 *         url="https://opensource.org/licenses/MIT"
 *     )
 * )
 * 
 * @OA\Server(
 *     url="http://localhost:8000/api",
 *     description="Local development server"
 * )
 * 
 * @OA\Server(
 *     url="https://api.kabianga.ac.ke/api",
 *     description="Production server"
 * )
 * 
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     description="Enter your bearer token in the format: Bearer {token}"
 * )
 * 
 * @OA\Tag(
 *     name="Authentication",
 *     description="User authentication and token management"
 * )
 * 
 * @OA\Tag(
 *     name="Proposals",
 *     description="Research proposal management"
 * )
 * 
 * @OA\Tag(
 *     name="Projects",
 *     description="Research project tracking and management"
 * )
 * 
 * @OA\Tag(
 *     name="Users",
 *     description="User management and permissions"
 * )
 * 
 * @OA\Tag(
 *     name="Reports",
 *     description="Analytics and reporting"
 * )
 * 
 * @OA\Tag(
 *     name="Dashboard",
 *     description="Dashboard statistics and activity"
 * )
 */
class ApiController extends Controller
{
    //
}