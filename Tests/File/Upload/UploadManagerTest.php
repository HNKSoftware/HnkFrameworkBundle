<?php

namespace Hnk\HnkFrameworkBundle\Tests\File\Upload;

use Hnk\HnkFrameworkBundle\File\Upload\UploadAwareEntityInterface;
use Hnk\HnkFrameworkBundle\File\Upload\UploadManager;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class Foo implements UploadAwareEntityInterface {
    public $options = [];

    public function __construct($options = []) {
        $this->options = array_merge([
            "fileType" => "dummyType",
            "filePath" => "current_file_path.jpg",
        ], $options);
    }

    public function getFileType(): string {
        return $this->options["fileType"];
    }
    public function setFilePath(?string $filePath): UploadAwareEntityInterface {
        $this->options["filePath"] = $filePath;
        return $this;
    }
    public function getCurrentFilePath(): ?string {
        return $this->options["filePath"];
    }
    public function parseNewFileSubdirectoryPath(string $subDirectory): string {
        return $this->options["parsedPath"] ?? $subDirectory;
    }
}

class UploadManagerTest extends TestCase {

    const FOO_SUBDIRECTORY = "foo";
    const FILE_FOR_DELETE_TEST = "foo/fileForDeleteTest.txt";
    const FILE_FOR_UPLOAD_TEST = "foo/fileForUploadTest.txt";
    const TMP_FILE_FOR_UPLOAD_TEST = "foo/tmpFileForUploadTest.jpg";

    protected $rootDir;

    protected function setUp() {
        $this->rootDir = "/tmp/upload_test";

        $this->prepareFilesForTests();
    }

    protected function tearDown(): void {
        $this->removeFilesAfterTests();
    }

    public function test_uploadFileForEntity_shouldRemoveOldEntityFile() {
        $uploadManager = $this->createUploadManager(
            ["dummyType" => $this->rootDir],
            ["Hnk\HnkFrameworkBundle\Tests\File\Upload\Foo" => self::FOO_SUBDIRECTORY]
        );

        $fullCurrentFilePath = $this->rootDir . "/" . self::FILE_FOR_UPLOAD_TEST;
        $fullTmpFilePath = $this->rootDir . "/" . self::TMP_FILE_FOR_UPLOAD_TEST;
        $foo = new Foo(["filePath" => self::FILE_FOR_UPLOAD_TEST]);

        $upload = new UploadedFile(
            $fullTmpFilePath,
            "tmpFileForUploadTest.jpg",
            "image/jpeg",
            null,
            true
        );

        $uploadManager->uploadFileForEntity(
            $foo,
            $upload,
            true
        );

        $this->assertEquals(false, is_file($fullCurrentFilePath), "old file is deleted");
        $this->assertEquals(false, is_file($fullTmpFilePath), "tmp file is deleted");

        $newFilePath = $foo->getCurrentFilePath();
        $this->assertEquals(true, is_file($this->rootDir . "/" . $newFilePath), "new file exists");
        $this->assertEquals(
            1,
            preg_match('/foo\/tmpfileforuploadtest\-[^\.]*\.jpeg/', $newFilePath),
            "file name should be safe and unique"
        );
    }

    public function test_deleteFileFromEntity() {
        $uploadManager = $this->createUploadManager(
            ["dummyType" => $this->rootDir],
            ["Hnk\HnkFrameworkBundle\Tests\File\Upload\Foo" => self::FOO_SUBDIRECTORY]
        );

        $fullFilePath = $this->rootDir . "/" . self::FILE_FOR_DELETE_TEST;
        $foo = new Foo(["filePath" => self::FILE_FOR_DELETE_TEST]);

        $this->assertEquals(true, is_file($fullFilePath));

        $uploadManager->deleteFileFromEntity($foo);

        $this->assertEquals(false, is_file($fullFilePath));
        $this->assertNull($foo->getCurrentFilePath());
    }

    public function test_getStoragePath() {
        $uploadManager = $this->createUploadManager([
            "dummyType" => "base_path",
        ]);

        $foo = new Foo();

        $this->assertEquals(
            "base_path/current_file_path.jpg",
            $uploadManager->getStoragePath($foo)
        );
    }

    public function test_getBaseTypeDir_throwsOnUnknownEntity() {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Unrecognized file type: dummyType");

        $uploadManager = $this->createUploadManager();

        $foo = new Foo();

        $uploadManager->getBaseTypeDir($foo);
    }

    public function skip_test_getBaseTypeDir_returnsPath() {
        $uploadManager = $this->createUploadManager([
            "dummyType" => "base_path",
        ]);

        $foo = new Foo();

        $this->assertEquals(
            "base_path",
            $uploadManager->getBaseTypeDir($foo)
        );
    }

    public function test_getEntitySubdirectory_throwsOnUnknownEntity() {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("No subdirectory configuration for entity: Hnk\HnkFrameworkBundle\Tests\File\Upload\Foo, create it in entity_file_subdirectories parameter");

        $uploadManager = $this->createUploadManager();

        $foo = new Foo();

        $uploadManager->getEntitySubdirectory($foo);
    }

    public function test_getEntitySubdirectory_returnsParsedPath() {
        $uploadManager = $this->createUploadManager([], [
            "Hnk\HnkFrameworkBundle\Tests\File\Upload\Foo" => "dummy_directory_path"
        ]);

        $foo = new Foo(["parsedPath" => "parsed_directory_path"]);

        $this->assertEquals(
            "parsed_directory_path",
            $uploadManager->getEntitySubdirectory($foo)
        );
    }

    public function test_createPath() {
        $uploadManager = $this->createUploadManager();

        $this->assertEquals("", $uploadManager->createPath());
        $this->assertEquals("/tmp", $uploadManager->createPath("/tmp"));
        $this->assertEquals("/tmp/dir", $uploadManager->createPath("/tmp", "dir"));
    }

    /**
     * @param array $fileDirectories
     * @param array $entityFileSubDirectories
     * @return UploadManager
     */
    private function createUploadManager(
        array $fileDirectories = [],
        array $entityFileSubDirectories = []
    ) {
        return new UploadManager($fileDirectories, $entityFileSubDirectories);
    }

    private function prepareFilesForTests() {
        $fs = new Filesystem();

        $fooSubdirectory = $this->rootDir . "/" . self::FOO_SUBDIRECTORY;
        if (!is_dir($fooSubdirectory)) {
            $fs->mkdir($fooSubdirectory);
        }

        $fileForDeleteTest = $this->rootDir . "/" . self::FILE_FOR_DELETE_TEST;
        if (!is_file($fileForDeleteTest)) {
            $fs->touch($fileForDeleteTest);
        }

        $fileForUploadTest = $this->rootDir . "/" . self::FILE_FOR_UPLOAD_TEST;
        if (!is_file($fileForUploadTest)) {
            $fs->touch($fileForUploadTest);
        }

        $tmpFileForUploadTest = $this->rootDir . "/" . self::TMP_FILE_FOR_UPLOAD_TEST;
        if (!is_file($tmpFileForUploadTest)) {
            $fs->touch($tmpFileForUploadTest);
        }
    }

    private function removeFilesAfterTests() {
        $fs = new Filesystem();

        if (is_dir($this->rootDir)) {
            $fs->remove($this->rootDir);
        }

    }

}