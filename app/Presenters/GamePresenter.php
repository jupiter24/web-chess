<?php

namespace App\Presenters;

use App\Transformers\GameTransformer;
use App\Presenters\ExtendedFractalPresenter;

/**
 * Class GamePresenter
 *
 * @package namespace App\Presenters;
 */
class GamePresenter extends ExtendedFractalPresenter
{
    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new GameTransformer();
    }
}
