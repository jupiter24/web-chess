<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Entities\User;
use App\Services\ResourceUrl;

/**
 * Class UserTransformer.
 */
class UserTransformer extends TransformerAbstract
{
    /**
     * List of resources possible to include.
     *
     * @var array
     */
    protected $availableIncludes = [
        'tags',
        'games',
    ];

    public function __construct(ResourceUrl $urlGen)
    {
        $this->urlGen = $urlGen;
    }

    /**
     * Transform the User entity.
     *
     * @param \User $model
     *
     * @return array
     */
    public function transform(User $model)
    {
        return array_filter([
            'url'        => $this->urlGen->generate($model),
            'name'       => isset($model->name)       ? $model->name                          : null,
            'email'      => isset($model->email)      ? $model->email                         : null,
            'created_at' => isset($model->created_at) ? $model->created_at->toIso8601String() : null,
            'updated_at' => isset($model->updated_at) ? $model->updated_at->toIso8601String() : null,
        ]);
    }

    /**
     * Include Tags.
     *
     * @param User $user
     *
     * @return League/Fractal/CollectionResource
     */
    public function includeTags(User $user)
    {
        return $this->collection($user->tags, new TagSummaryTransformer());
    }

    /**
     * Include Games.
     *
     * @param User $user
     *
     * @return League/Fractal/CollectionResource
     */
    public function includeGames(User $user)
    {
        return $this->collection($user->games, new GameSummaryTransformer());
    }
}
