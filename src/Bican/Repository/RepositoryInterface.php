<?php namespace Bican\Repository;

interface RepositoryInterface {

    /**
     * Find an entity by id.
     *
     * @param int $id
     * @return mixed
     */
    public function find($id);

    /**
     * Find an entity by id or fail.
     *
     * @param int $id
     * @return mixed
     */
    public function findOrFail($id);

    /**
     * Find an entity by specific column name.
     *
     * @param string $columnName
     * @param string $value
     * @param array $attributes
     * @return mixed
     */
    public function findBy($columnName, $value, $attributes = ['*']);

    /**
     * Find an entity by specific column name or fail.
     *
     * @param string $columnName
     * @param string $value
     * @param array $attributes
     * @return mixed
     */
    public function findOrFailBy($columnName, $value, $attributes = ['*']);

    /**
     * Find all entities.
     *
     * @param array $orderBy
     * @param array $attributes
     * @return mixed
     */
    public function findAll(array $orderBy = ['id', 'asc'], $attributes = ['*']);

    /**
     * Find all entities by specific column name.
     *
     * @param string $columnName
     * @param string $value
     * @param array $orderBy
     * @param array $attributes
     * @return mixed
     */
    public function findAllBy($columnName, $value, array $orderBy = ['id', 'asc'], $attributes = ['*']);

    /**
     * Find all entities paginated.
     *
     * @param int $perPage
     * @param array $orderBy
     * @param array $attributes
     * @return mixed
     */
    public function findAllPaginated($perPage = 20, array $orderBy = ['id', 'asc'], $attributes = ['*']);

    /**
     * Create a new entity.
     *
     * @param array $data
     * @return mixed
     */
    public function create(array $data);

    /**
     * Update an entity.
     *
     * @param integer $id
     * @param array $data
     * @return mixed
     */
    public function update($id, array $data);

    /**
     * Delete an entity.
     *
     * @param int $id
     * @return mixed
     */
    public function delete($id);

}