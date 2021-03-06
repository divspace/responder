<?php namespace Divspace\Responder;

use Illuminate\Pagination\Paginator;

interface Responder {

    /**
     * @param $includes
     * @internal param $connection
     *
     * @return mixed
     */
    public function parseIncludes($includes);

    /**
     * @param mixed $data
     * @param \League\Fractal\TransformerAbstract|callable $transformer
     * @param string $resourceKey
     *
     * @return array
     */
    public function item($data, $transformer = null, $resourceKey = null);

    /**
     * @param $data
     * @param \League\Fractal\TransformerAbstract|callable $transformer
     * @param string $resourceKey
     *
     * @return array
     */
    public function collection($data, $transformer = null, $resourceKey = null);

    /**
     * @param Paginator $paginator
     * @param \League\Fractal\TransformerAbstract|callable $transformer
     * @param string $resourceKey
     *
     * @return mixed
     */
    public function paginatedCollection(Paginator $paginator, $transformer = null, $resourceKey = null);

}