<?php


namespace Hnk\HnkFrameworkBundle\File\Upload;

/**
 * Interface UploadAwareEntityInterface
 * @package Hnk\HnkFrameworkBundle\File\Upload
 *
 * Entity should implement this interface to be ready for UploadManager
 */
interface UploadAwareEntityInterface
{
    /**
     * Returns file type
     *
     * @return string
     */
    public function getFileType(): string;

    /**
     * Sets new file path after upload
     *
     * @param string $filePath
     * @return $this
     */
    public function setFilePath(?string $filePath): self;

    /**
     * Returns current file path
     *
     * @return string
     */
    public function getCurrentFilePath(): ?string;

    /**
     * Parse sub directory path, replace parameters
     *
     * @param string $subDirectory
     * @return string
     */
    public function parseNewFileSubdirectoryPath(string $subDirectory): string;
}