<?php

namespace App\Modules\Bookings\Mutations;

use App\Modules\Bookings\Booking;
use App\Modules\Bookings\Services\BookingsService;
use App\Modules\Bookings\Exceptions\OverlappingBookingException;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class UpdateBookingHandler
{
    /**
     * Return a value for the field.
     *
     * @param  null  $rootValue  Usually contains the result returned from the parent field. In this case, it is always `null`.
     * @param  mixed[]  $args  The arguments that were passed into the field.
     * @param  GraphQLContext  $context  Arbitrary data that is shared between all fields of a single query.
     * @param  ResolveInfo  $resolveInfo  Information about the query itself, such as the execution state, the field name, path to the field from the root, and more.
     * @throws OverlappingBookingException
     */
    public function __invoke($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $service = app()->make(BookingsService::class);

        $service->updateExistingBooking($args);

        return Booking::findOrFail($args['id']);
    }
}