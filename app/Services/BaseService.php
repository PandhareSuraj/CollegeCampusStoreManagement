<?php

namespace App\Services;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * BaseService Abstract Class
 * 
 * Provides foundation for all business logic services in the application
 * Enforces consistent patterns for data access, validation, and error handling
 */
abstract class BaseService
{
    /**
     * The repository instance
     */
    protected mixed $repository;
    
    /**
     * Model instance
     */
    protected string $modelClass;
    
    /**
     * Initialize service
     */
    public function __construct()
    {
        $this->initializeRepository();
    }
    
    /**
     * Initialize the repository - should be implemented by child services
     */
    abstract protected function initializeRepository(): void;
    
    /**
     * Get all records with optional pagination
     */
    public function getAll(int $perPage = 15, array $columns = ['*']): mixed
    {
        return $this->repository->paginate($perPage, $columns);
    }
    
    /**
     * Get all records as collection
     */
    public function getAllCollection(array $columns = ['*']): Collection
    {
        return $this->repository->all($columns);
    }
    
    /**
     * Get record by ID
     */
    public function getById(int|string $id, array $columns = ['*']): ?Model
    {
        return $this->repository->find($id, $columns);
    }
    
    /**
     * Create a new record
     * 
     * @throws Exception
     */
    public function create(array $data): Model
    {
        try {
            // Validate data before creating
            $this->validate($data);
            
            return $this->repository->create($data);
        } catch (Exception $e) {
            throw new Exception("Failed to create record: {$e->getMessage()}");
        }
    }
    
    /**
     * Update a record
     * 
     * @throws Exception
     */
    public function update(int|string $id, array $data): ?Model
    {
        try {
            $record = $this->getById($id);
            
            if (!$record) {
                throw new Exception("Record not found with ID: {$id}");
            }
            
            // Validate data before updating
            $this->validate($data);
            
            return $this->repository->update($id, $data);
        } catch (Exception $e) {
            throw new Exception("Failed to update record: {$e->getMessage()}");
        }
    }
    
    /**
     * Delete a record
     * 
     * @throws Exception
     */
    public function delete(int|string $id): bool
    {
        try {
            $record = $this->getById($id);
            
            if (!$record) {
                throw new Exception("Record not found with ID: {$id}");
            }
            
            return $this->repository->delete($id);
        } catch (Exception $e) {
            throw new Exception("Failed to delete record: {$e->getMessage()}");
        }
    }
    
    /**
     * Validate input data - should be overridden by child services
     * 
     * @throws Exception
     */
    protected function validate(array $data): void
    {
        // Override in child classes with specific validation logic
    }
    
    /**
     * Get repository instance
     */
    public function repository(): mixed
    {
        return $this->repository;
    }
}
