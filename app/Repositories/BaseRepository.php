<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;

/**
 * BaseRepository Abstract Class
 * 
 * Provides CRUD operations and query building for all repositories
 * Implements Repository Pattern for consistent data access layer
 */
abstract class BaseRepository
{
    /**
     * The model instance
     */
    protected Model $model;
    
    /**
     * Initialize repository with model
     */
    abstract public function model(): Model;
    
    /**
     * Begin a new query on the model
     */
    public function query(): Builder
    {
        return $this->model->query();
    }
    
    /**
     * Get all records
     */
    public function all(array $columns = ['*']): Collection
    {
        return $this->query()->get($columns);
    }
    
    /**
     * Paginate records
     */
    public function paginate(int $perPage = 15, array $columns = ['*']): Paginator
    {
        return $this->query()->paginate($perPage, $columns);
    }
    
    /**
     * Find a record by ID
     */
    public function find(int|string $id, array $columns = ['*']): ?Model
    {
        return $this->query()->find($id, $columns);
    }
    
    /**
     * Find record by any attribute
     */
    public function findBy(string $attribute, mixed $value, array $columns = ['*']): ?Model
    {
        return $this->query()->where($attribute, $value)->first($columns);
    }
    
    /**
     * Find all records by attribute
     */
    public function findAllBy(string $attribute, mixed $value, array $columns = ['*']): Collection
    {
        return $this->query()->where($attribute, $value)->get($columns);
    }
    
    /**
     * Create a new record
     */
    public function create(array $data): Model
    {
        return $this->model->create($data);
    }
    
    /**
     * Update a record
     */
    public function update(int|string $id, array $data): ?Model
    {
        $record = $this->find($id);
        
        if ($record) {
            $record->update($data);
        }
        
        return $record;
    }
    
    /**
     * Delete a record
     */
    public function delete(int|string $id): bool
    {
        $record = $this->find($id);
        
        if ($record) {
            return $record->delete();
        }
        
        return false;
    }
    
    /**
     * Check if record exists
     */
    public function exists(int|string $id): bool
    {
        return $this->find($id) !== null;
    }
    
    /**
     * Count total records
     */
    public function count(): int
    {
        return $this->query()->count();
    }
    
    /**
     * Get the model instance
     */
    public function getModel(): Model
    {
        return $this->model;
    }
}
