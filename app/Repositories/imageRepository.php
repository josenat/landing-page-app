<?php

namespace App\Repositories;

use App\Models\Image;
use App\Repositories\BaseRepository;

/**
 * Class ImageRepository
 * @package App\Repositories
 * @version July 8, 2019, 4:59 pm UTC
*/

class ImageRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'path'
    ];

    /**
     * Return searchable fields
     *
     * @return array
     */
    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Image::class;
    }
}
