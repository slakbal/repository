<?php namespace Bican\Repository;

use Bican\Repository\Exceptions\BadMethodCallException;
use Bican\Repository\Exceptions\EntityNotFoundException;
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
     * @param array $columns
     * @return mixed
     */
    public function find($id, array $columns = ['*'])
    {
        return $this->model->select($columns)->find($id);
    }

    /**
     * Find an entity by id or fail.
     *
     * @param int $id
     * @param array $columns
     * @return mixed
     * @throws EntityNotFoundException
     */
    public function findOrFail($id, array $columns = ['*'])
    {
        if( ! $entity = $this->find($id, $columns))
        {
            throw new EntityNotFoundException('Entity not found.');
        }

        return $entity;
    }

    /**
     * Find an entity by specific column name.
     *
     * @param string $columnName
     * @param string $value
     * @param array $columns
     * @return mixed
     */
    public function findBy($columnName, $value, array $columns = ['*'])
    {
        return $this->model->where($columnName, $value)->first($columns);
    }

    /**
     * Find an entity by specific column name or fail.
     *
     * @param string $columnName
     * @param string $value
     * @param array $columns
     * @return mixed
     * @throws EntityNotFoundException
     */
    public function findOrFailBy($columnName, $value, array $columns = ['*'])
    {
        if( ! $entity = $this->findBy($columnName, $value, $columns))
        {
            throw new EntityNotFoundException('Entity not found.');
        }

        return $entity;
    }

    /**
     * Find all entities.
     *
     * @param array $orderBy
     * @param array $columns
     * @return mixed
     */
    public function findAll(array $orderBy = ['id', 'asc'], array $columns = ['*'])
    {
        return $this->model->orderBy($orderBy[0], $orderBy[1])->get($columns);
    }

    /**
     * Find all entities by specific column name.
     *
     * @param string $columnName
     * @param string $value
     * @param array $orderBy
     * @param array $columns
     * @return mixed
     */
    public function findAllBy($columnName, $value, array $orderBy = ['id', 'asc'], array $columns = ['*'])
    {
        return $this->model->where($columnName, $value)->orderBy($orderBy[0], $orderBy[1])->get($columns);
    }

    /**
     * Find all entities paginated.
     *
     * @param int $perPage
     * @param array $orderBy
     * @param array $columns
     * @return mixed
     */
    public function findAllPaginated($perPage = 20, array $orderBy = ['id', 'asc'], array $columns = ['*'])
    {
        return $this->model->select($columns)->orderBy($orderBy[0], $orderBy[1])->paginate($perPage);
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
     * @return boolean
     */
    public function update($id, array $data)
    {
        return $this->findOrFail($id)->update($data);
    }

    /**
     * Delete an entity.
     *
     * @param int $id
     * @return boolean
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
        if (starts_with($method, 'findBy')) return $this->findBy(snake_case(substr($method, 6)), $parameters[0], isset($parameters[1]) ? $parameters[1] : ['*']);
        if (starts_with($method, 'findOrFailBy')) return $this->findOrFailBy(snake_case(substr($method, 12)), $parameters[0], isset($parameters[1]) ? $parameters[1] : ['*']);
        if (starts_with($method, 'findAllBy')) return $this->findAllBy(snake_case(substr($method, 9)), $parameters[0], isset($parameters[1]) ? $parameters[1] : ['id', 'asc'], isset($parameters[2]) ? $parameters[2] : ['*']);

        throw new BadMethodCallException('Method [' . $method . '] does not exist.');
    }

}