<?php

namespace Hnk\HnkFrameworkBundle\File\Upload;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploadManager {

    /**
     * Array with file types as keys and absolute paths as values
     *
     * [
     *      "image": '%kernel.project_dir%/var/images'
     * ]
     *
     * @var array
     */
    private $fileDirectories;

    /**
     * Array with entity class names as keys and subdirectory names as values
     * Subdirectory will be parsed by `parseNewFileSubdirectoryPath` method
     *
     * [
     *      App\Entity\User: "user"
     *      App\Entity\Gallery: "gallery/{idUser}"
     * ]
     *
     * @var array
     */
    private $entityFileSubdirectories;

    public function __construct(
        array $fileDirectories,
        array $entityFileSubdirectories
    ) {
        $this->fileDirectories = $fileDirectories;
        $this->entityFileSubdirectories = $entityFileSubdirectories;
    }

    /**
     * Uploads $file if exists
     *
     * @param UploadAwareEntityInterface $entity
     * @param UploadedFile|null $file
     * @param bool $removePreviousFile
     * @throws \Exception
     */
    public function uploadFileForEntityIfNeeded(UploadAwareEntityInterface $entity, ?UploadedFile $file, bool $removePreviousFile = true) {
        if (!$file) {
            return;
        }

        $this->uploadFileForEntity($entity, $file, $removePreviousFile);
    }

    /**
     * Uploads file based on entity configuration, deletes previous file if requested and sets new file path in entity
     *
     * @param UploadAwareEntityInterface $entity
     * @param UploadedFile $file
     * @param bool $removePreviousFile
     * @throws \Exception
     */
    public function uploadFileForEntity(UploadAwareEntityInterface $entity, UploadedFile $file, bool $removePreviousFile = true) {
        $baseTypeDir = $this->getBaseTypeDir($entity);
        $entitySubDirectory = $this->getEntitySubdirectory($entity);

        $fileName = $this->createSafeFileName($file);
        $uploadPath = $this->createPath($baseTypeDir, $entitySubDirectory);

        $file->move($uploadPath, $fileName);

        if ($entity->getCurrentFilePath() && $removePreviousFile) {
            $this->deleteFileFromEntity($entity);
        }

        $newPath = $this->createPath($entitySubDirectory, $fileName);

        $entity->setFilePath($newPath);
    }

    /**
     * Deletes file from the local storage and sets file path to null
     *
     * @param UploadAwareEntityInterface $entity
     * @throws \Exception
     */
    public function deleteFileFromEntity(UploadAwareEntityInterface $entity) {
        $baseTypeDir = $this->getBaseTypeDir($entity);
        $currentFile = $entity->getCurrentFilePath();
        $currentFilePath = $this->createPath($baseTypeDir, $currentFile);

        unlink($currentFilePath);

        $entity->setFilePath(null);
    }

    /**
     * Returns full path to the file
     *
     * @param UploadAwareEntityInterface $entity
     * @return string
     * @throws \Exception
     */
    public function getStoragePath(UploadAwareEntityInterface $entity): string {
        $baseTypeDir = $this->getBaseTypeDir($entity);

        return $this->createPath($baseTypeDir, $entity->getCurrentFilePath());
    }

    public function getBaseTypeDir(UploadAwareEntityInterface $entity): string {
        $fileType = $entity->getFileType();

        if (!array_key_exists($fileType, $this->fileDirectories)) {
            throw new \Exception(sprintf("Unrecognized file type: %s", $fileType));
        }

        return $this->fileDirectories[$fileType];
    }

    public function getEntitySubdirectory(UploadAwareEntityInterface $entity): string {
        $entityClass = get_class($entity);

        if (!array_key_exists($entityClass, $this->entityFileSubdirectories)) {
            throw new \Exception(sprintf("No subdirectory configuration for entity: %s, create it in entity_file_subdirectories parameter", $entityClass));
        }

        $subDirectoryPattern = $this->entityFileSubdirectories[$entityClass];

        return $entity->parseNewFileSubdirectoryPath($subDirectoryPattern);
    }

    public function createPath(...$segments) {
        return join("/", $segments);
    }

    protected function createSafeFileName(UploadedFile $file): string {
        $originalFileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFileName = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFileName);

        return sprintf("%s-%s.%s", $safeFileName, uniqid(), $file->guessClientExtension());
    }
}