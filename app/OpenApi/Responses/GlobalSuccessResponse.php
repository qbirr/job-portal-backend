<?php

namespace App\OpenApi\Responses;

use GoldSpecDigital\ObjectOrientedOAS\Objects\MediaType;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Response;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;
use Vyuldashev\LaravelOpenApi\Factories\ResponseFactory;

class GlobalSuccessResponse extends ResponseFactory {
    public function build(): Response {
        $response = Schema::object('ok');
        return Response::ok()->content(MediaType::create('xxx'))->description('Successful response');
    }
}
