<?php

namespace App\OpenApi\Schemas;

use GoldSpecDigital\ObjectOrientedOAS\Contracts\SchemaContract;
use GoldSpecDigital\ObjectOrientedOAS\Objects\AllOf;
use GoldSpecDigital\ObjectOrientedOAS\Objects\AnyOf;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Not;
use GoldSpecDigital\ObjectOrientedOAS\Objects\OneOf;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;
use Vyuldashev\LaravelOpenApi\Contracts\Reusable;
use Vyuldashev\LaravelOpenApi\Factories\SchemaFactory;

class UserSchema extends SchemaFactory implements Reusable
{
    /**
     * @return AllOf|OneOf|AnyOf|Not|Schema
     */
    public function build(): SchemaContract
    {
        return Schema::object('User')
            ->properties(
                Schema::string('id')->default(null),
                Schema::string('first_name')->default(null),
                Schema::string('last_name')->default(null),
                Schema::string('email')->default(null),
                Schema::string('phone')->default(null),
                Schema::string('email_verified_at')->format(Schema::FORMAT_DATE_TIME)->default(null),
                Schema::string('password')->default(null),
                Schema::string('dob')->format(Schema::FORMAT_DATE)->default(null),
                Schema::integer('gender')->default(null),
                Schema::string('country_id')->default(null),
                Schema::string('state_id')->default(null),
                Schema::string('city_id')->default(null),
                Schema::boolean('is_active')->default(1),
                Schema::boolean('is_verified')->default(1),
                Schema::integer('owner_id')->default(null),
                Schema::string('owner_type')->default(null),
                Schema::string('language')->default('en'),
                Schema::string('profile_views')->default(0),
                Schema::string('remember_token')->default(null),
                Schema::string('theme_mode')->default(0),
                Schema::string('created_at')->format(Schema::FORMAT_DATE_TIME)->default(null),
                Schema::string('updated_at')->format(Schema::FORMAT_DATE_TIME)->default(null),
                Schema::string('facebook_url')->default(null),
                Schema::string('twitter_url')->default(null),
                Schema::string('linkedin_url')->default(null),
                Schema::string('google_plus_url')->default(null),
                Schema::string('pinterest_url')->default(null),
                Schema::boolean('is_default')->default(0),
                Schema::string('stripe_id')->default(null),
                Schema::string('region_code')->default(null)
            );
    }
}
