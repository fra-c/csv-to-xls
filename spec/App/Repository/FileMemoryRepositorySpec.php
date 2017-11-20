<?php

namespace spec\App\Repository;

use App\Entity\File;
use App\Exception\FileNotFoundException;
use App\Repository\FileMemoryRepository;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Ramsey\Uuid\Uuid;

class FileMemoryRepositorySpec extends ObjectBehavior
{
    function it_saves_a_file(File $file)
    {
        $id = '9dc896cd-65b3-4486-9691-b7749c8dbb1b';
        $file->getId()->willReturn($id);

        $this->save($file);
        $this->findOneById($id)->shouldReturn($file);
    }

    function it_throws_an_exception_when_a_file_does_not_exist()
    {
        $this->shouldThrow(FileNotFoundException::class)->duringFindOneById('non-existent-file');
    }

    function it_lists_all_the_saved_files(File $file1, File $file2)
    {
        $file1->getId()->willReturn(Uuid::uuid4());
        $file2->getId()->willReturn(Uuid::uuid4());

        $this->save($file1);
        $this->save($file2);

        $this->getAll()->shouldReturn([$file1, $file2]);
    }
}
