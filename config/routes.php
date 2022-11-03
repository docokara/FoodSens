<?php

use App\Controller\HomeController;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

return function (RoutingConfigurator $routes) {
    $routes->add('findRecipe', '/findRecipe')
        // the controller value has the format [controller_class, method_name]
        ->controller([HomeController::class, 'findRecipeByName'])

        // if the action is implemented as the __invoke() method of the
        // controller class, you can skip the 'method_name' part:
        // ->controller(BlogController::class)
    ;
};