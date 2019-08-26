<?php

use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel;
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use App\Exception\ValidateException;

error_reporting( E_ALL );
ini_set( 'display_errors', 1 );

require_once dirname( __DIR__ ) . '/vendor/autoload.php';
$dbParams = require_once dirname( __DIR__ ) . '/config/db.php';

$routes = require_once dirname( __DIR__ ) . '/config/routes.php';

$request = Request::createFromGlobals();

$context = new RequestContext();
$context->fromRequest( $request );

try {
    $config        = Setup::createAnnotationMetadataConfiguration( [ __DIR__ . '/src/Entity' ], TRUE, NULL, NULL, FALSE );
    $entityManager = EntityManager::create( $dbParams, $config );

    $matcher    = new UrlMatcher( $routes, $context );
    $parameters = $matcher->match( $context->getPathInfo() );

    $controllerResolver = new HttpKernel\Controller\ControllerResolver();
    $argumentResolver   = new HttpKernel\Controller\ArgumentResolver();
    $request->attributes->add( $matcher->match( $request->getPathInfo() ) );

    $controller = $controllerResolver->getController( $request );
    $arguments  = $argumentResolver->getArguments( $request, $controller );

    if ( $controller[0] instanceof \App\Controller\AbstractController ) {
        $controller[0]->setEntityManager( $entityManager )->setRequest( $request );
    }

    $response = call_user_func_array( $controller, $arguments );
} catch ( MethodNotAllowedException $e ) {
    $response = App\Http\ApiResponse::error( "Method not allowed", 405 );
} catch ( ResourceNotFoundException $e ) {
    $response = App\Http\ApiResponse::error( $e->getMessage(), 404 );
} catch ( ValidateException $e ) {
    $response = App\Http\ApiResponse::error( $e->getMessage(), $e->getCode() ?: 400 );
} catch ( Exception $e ) {
    $response = App\Http\ApiResponse::error( "An error occurred:\n" . $e->getMessage() . "\nTrace:\n" . $e->getTraceAsString(), 500 );
}

$response->send();