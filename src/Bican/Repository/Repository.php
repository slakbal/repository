<?php namespace Bican\Repository;

use Bican\Repository\Exceptions\BadMethodCallException;
use Bican\Repository\Exceptions\ModelNotFoundException;

abstract class Repository implements RepositoryInterface {

    /**
     * @var Model
     */
    protected $model;

    /**
     * @var string
     */
    protected $modelName = '';

    /**
     * @var string
     */
    protected $modelFolder = '';

    /**
     * @return mixed
     */
    public function __construct()
    {
        if($this->modelName === '') $this->setModelName();

        $this->setModel($this->modelName);
    }

    /**
     * Set the model name.
     *
     * @return void
     */
    protected function setModelName()
    {
        $repositoryName = explode('\\', get_called_class());

        $modelFolder = ($this->modelFolder !== '') ? '\\' . $this->modelFolder . '\\' : '\\';

        $this->modelName = $repositoryName[0] . $modelFolder . str_replace('Repository', '', end($repositoryName));
    }

    /**
     * Set the model.
     *
     * @param string $name
     * @throws ModelNotFoundException
     */
    protected function setModel($name)
    {
        if ( ! class_exists($name))
        {
            throw new ModelNotFoundException('Model [' . $name . '] does not exist.');
        }

        $this->model = new $name();
    }

    /**
     * Find an entity by id.
     *
     * @param int $id
     * @return mixed
     */
    public function find($id)
    {
        return $this->model->find($id);
    }

    /**
     * Find an entity by id or fail.
     *
     * @param int $id
     * @return mixed
     */
    public function findOrFail($id)
    {
        return $this->model->findOrFail($id);
    }

    /**
     * Find an entity by specific column name.
     *
     * @param string $columnName
     * @param string $value
     * @param array $attributes
     * @return mixed
     */
    public function findBy($columnName, $value, $attributes = ['*'])
    {
        return $this->model->where($columnName, $value)->first($attributes);
    }

    /**
     * Find an entity by specific column name or fail.
     *
     * @param string $columnName
     * @param string $value
     * @param array $attributes
     * @return mixed
     */
    public function findOrFailBy($columnName, $value, $attributes = ['*'])
    {
        return $this->model->where($columnName, $value)->firstOrFail($attributes);
    }

    /**
     * Find all entities.
     *
     * @param array $orderBy
     * @param array $attributes
     * @return mixed
     */
    public function findAll(array $orderBy = ['id', 'asc'], $attributes = ['*'])
    {
        return $this->model->orderBy($orderBy[0], $orderBy[1])->get($attributes);
    }

    /**
     * Find all entities by specific column name.
     *
     * @param string $columnName
     * @param string $value
     * @param array $orderBy
     * @param array $attributes
     * @return mixed
     */
    public function findAllBy($columnName, $value, array $orderBy = ['id', 'asc'], $attributes = ['*'])
    {
        return $this->model->where($columnName, $value)->orderBy($orderBy[0], $orderBy[1])->get($attributes);
    }

    /**
     * Find all entities paginated.
     *
     * @param int $perPage
     * @param array $orderBy
     * @param array $attributes
     * @return mixed
     */
    public function findAllPaginated($perPage = 20, array $orderBy = ['id', 'asc'], $attributes = ['*'])
    {
        return $this->model->orderBy($orderBy[0], $orderBy[1])->paginated($perPage)->get($attributes);
    }

    /**
     * Create a new entity.
     *
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        return $this->model->create($data);
    }

    /**
     * Update an entity.
     *
     * @param integer $id
     * @param array $data
     * @return mixed
     */
    public function update($id, array $data)
    {
        return $this->findOrFail($id)->update($data);
    }

    /**
     * Delete an entity.
     *
     * @param int $id
     * @return mixed
     */
    public function delete($id)
    {
        return $this->findOrFail($id)->delete();
    }

    /**
     * @param string $method
     * @param array $parameters
     * @return mixed
     * @throws BadMethodCallException
     */
    public function __call($method, $parameters)
    {
        if (starts_with($method, 'findBy')) return $this->findBy(snake_case(substr($method, 6)), $parameters[0], isset($parameters[1]) ? $parameters[1] : null);
        if (starts_with($method, 'findOrFailBy')) return $this->findOrFailBy(snake_case(substr($method, 12)), isset($parameters[1]) ? $parameters[1] : null);

        throw new BadMethodCallException('Method [' . $method . '] does not exist.');
    }

}