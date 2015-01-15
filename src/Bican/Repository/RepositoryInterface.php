<?php namespace Bican\Repository;

interface RepositoryInterface {

    /**
     * Find an entity by id.
     *
     * @param int $id
     * @param array $columns
     * @return mixed
     */
    public function find($id, array $columns = ['*']);

    /**
     * Find an entity by id or fail.
     *
     * @param int $id
     * @param array $columns
     * @return mixed
     * @throws EntityNotFoundException
     */
    public function findOrFail($id, array $columns = ['*']);

    /**
     * Find an entity by specific column name.
     *
     * @param string $columnName
     * @param string $value
     * @param array $columns
     * @return mixed
     */
    public function findBy($columnName, $value, array $columns = ['*']);

    /**
     * Find an entity by specific column name or fail.
     *
     * @param string $columnName
     * @param string $value
     * @param array $columns
     * @return mixed
     * @throws EntityNotFoundException
     */
    public function findOrFailBy($columnName, $value, array $columns = ['*']);

    /**
     * Find all entities.
     *
     * @param array $orderBy
     * @param array $columns
     * @return mixed
     */
    public function findAll(array $orderBy = ['id', 'asc'], array $columns = ['*']);

    /**
     * Find all entities by specific column name.
     *
     * @param string $columnName
     * @param string $value
     * @param array $orderBy
     * @param array $columns
     * @return mixed
     */
    public function findAllBy($columnName, $value, array $orderBy = ['id', 'asc'], array $columns = ['*']);

    /**
     * Find all entities paginated.
     *
     * @param int $perPage
     * @param array $orderBy
     * @param array $columns
     * @return mixed
     */
    public function findAllPaginated($perPage = 20, array $orderBy = ['id', 'asc'], array $columns = ['*']);

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
     * @throws EntityNotFoundException|UnableToUpdateException
     */
    public function update($id, array $data);

    /**
     * Delete an entity.
     *
     * @param int $id
     * @return boolean
     * @throws EntityNotFoundException
     */
    public function delete($id);

}