<?php

namespace App\OpenApi\RequestBodies;

use App\OpenApi\Schemas\UserSchema;
use GoldSpecDigital\ObjectOrientedOAS\Objects\MediaType;
use GoldSpecDigital\ObjectOrientedOAS\Objects\RequestBody;
use Vyuldashev\LaravelOpenApi\Factories\RequestBodyFactory;

class UserCreateRequestBody extends RequestBodyFactory {
    public function build(): RequestBody {
        return RequestBody::create('UserCreate')
            ->content(
                MediaType::json()->schema(UserSchema::ref())
            );
    }
}
