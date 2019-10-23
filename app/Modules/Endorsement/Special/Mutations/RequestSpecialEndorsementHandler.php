<?php

namespace App\Modules\Endorsement\Special\Mutations;

use App\Modules\Endorsement\Special\Exceptions\EndorsementAlreadyGrantedException;
use App\Modules\Endorsement\Special\Exceptions\EndorsementRequestAlreadyExistsException;
use App\Modules\Endorsement\Special\Service\SpecialEndorsementGrant;
use App\Modules\Endorsement\Special\SpecialEndorsement;
use App\User;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class RequestSpecialEndorsementHandler
{
    private $userInstance;

    public function __construct(User $user)
    {
        $this->userInstance = $user;
    }

    /**
     * Return a value for the field.
     *
     * @param  null  $rootValue  Usually contains the result returned from the parent field. In this case, it is always `null`.
     * @param  mixed[]  $args  The arguments that were passed into the field.
     * @param  \Nuwave\Lighthouse\Support\Contracts\GraphQLContext  $context  Arbitrary data that is shared between all fields of a single query.
     * @param  \GraphQL\Type\Definition\ResolveInfo  $resolveInfo  Information about the query itself, such as the execution state, the field name, path to the field from the root, and more.
     * @return mixed
     * @throws EndorsementRequestAlreadyExistsException
     * @throws EndorsementAlreadyGrantedException
     */
    public function __invoke($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        // check if the endorsement has already been granted
        /** @var SpecialEndorsement $endorsement */
        $endorsement = SpecialEndorsement::findOrFail($args['endorsement_id']);

        $user = User::find($args['user_id']);

        $requester = User::find($args['requested_by']);

        return (new SpecialEndorsementGrant($endorsement, $user, $requester))->handle();
    }
}